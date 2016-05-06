<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// 動画・写真
class Photos extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->config->load('my/image');
		$this->load->library('image_lib');
	}


	/**
	 * フォーム
	 */
	public function index()
	{
		$view = array();
		$view['photos'] = array();
		
		// ショップ情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'photos, youtube, op8', TRUE);
		
		if($shop['photos'])
		{
			$view['photos'] = unserialize($shop['photos']);
		}

		$view['youtube'] = $shop['youtube'];
		$view['op8'] = $shop['op8'];
		
		$this->load->view('member/photos', $view);
	}

	/*
	 * 更新
	 */
	public function update_image()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		// 画像登録処理
		$images = $this->save_images($_SESSION['member']['shop_id'], IMAGE_NAME_PHOTO, SHOP_IMAGE_LIMIT);
		
		if ($images === FALSE)
		{
			$json['message'] = '画像の登録に失敗しました';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$new_images = array();
		
		if($images)
		{
			// 編集前情報を取得
			$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'photos', TRUE);
			
			if($shop['photos'])
			{
				$photos = unserialize($shop['photos']);
			}
			
			// 画像情報をviewにセット
			for($i = 1; $i <= SHOP_IMAGE_LIMIT; $i++)
			{
				if (isset($images[$i]))
				{
					$new_images[$i] = $images[$i];
				}
				else if(isset($photos[$i]))
				{
					$new_images[$i] = $photos[$i];
				}
			}
		}
		
		if($new_images)
		{
			$result = $this->db->update('shop', array('photos' => (string)serialize($new_images)), array('id' => (int)$_SESSION['member']['shop_id']));

			if ($result !== TRUE)
			{
				$json['message'] = 'データベースエラーが発生し更新できませんでした';
				$this->output->set_output(json_encode($json));
				return;
			}
		}
		
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * 更新
	 */
	public function update_movie()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		// youtube
		$youtube = $this->input->post("youtube");

		// youtube簡易チェック
		if($youtube)
		{
			$check = false;
			
			if(strstr($youtube, 'src="http://www.youtube.com/'))
			{
				$check = true;
			}
			else if(strstr($youtube, 'src="https://www.youtube.com/'))
			{
				$check = true;
			}
			else if(strstr($youtube, 'src="http://www.youtube-nocookie.com'))
			{
				$check = true;
			}
			else if(strstr($youtube, 'src="https://www.youtube-nocookie.com'))
			{
				$check = true;
			}
			
			if($check === false)
			{
				$json['message'] = 'YouTube埋め込みコードが正しくありません';
				$this->output->set_output(json_encode($json));
				return;
			}
		}
		
		$result = $this->db->update('shop', array('youtube' => (string)trim($youtube)), array('id' => (int)$_SESSION['member']['shop_id']));

		if ($result !== TRUE)
		{
			$json['message'] = 'データベースエラーが発生し更新できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * 画像削除
	 */
	public function del_photo()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$num = $this->input->post('number');

		if ($num < 1 OR $num > SHOP_IMAGE_LIMIT)
		{
			$json['message'] = '画像番号が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}

		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'id, photos');
		$photos = array();
		
		if($shop['photos'])
		{
			$photos = unserialize($shop['photos']);
		}
		
		if(!isset($photos[$num]))
		{
			$json['message'] = '指定された画像はありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$del_photo = $photos[$num];
		unset($photos[$num]);
		
		$this->db->where('id', (int)$shop['id']);
		$this->db->update('shop', array('photos' => (string)serialize($photos)));

		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;

			@unlink(get_image_path($shop['id'], $num, $del_photo['ext'], IMAGE_NAME_PHOTO, IMAGE_LARGE_NAME));
			@unlink(get_image_path($shop['id'], $num, $del_photo['ext'], IMAGE_NAME_PHOTO, IMAGE_MIDDLE_NAME));
			@unlink(get_image_path($shop['id'], $num, $del_photo['ext'], IMAGE_NAME_PHOTO, IMAGE_SMALL_NAME));
			@unlink(get_image_path($shop['id'], $num, $del_photo['ext'], IMAGE_NAME_PHOTO, IMAGE_ORIGINAL_NAME));
		}
		else
		{
			$json['message'] = '画像情報を削除できませんでした';
		}

		$this->output->set_output(json_encode($json));
	}
}