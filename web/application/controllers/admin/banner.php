<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Admin_Controller.php' );

// バナー
class Banner extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->config->load('my/image');
		$this->load->library('image_lib');
		
		$this->load->model('Banner_model', 'Banner');
	}


	/**
	 * フォーム
	 */
	public function index()
	{
		$view = array();
		$view['photos'] = array();
		
		// ショップ情報取得
		$shop = $this->Banner->read(1);
		
		if($shop['type1'])
		{
			$view['photos'] = unserialize($shop['type1']);
		}

		$this->load->view('admin/banner', $view);
	}

	/*
	 * 更新
	 */
	public function update_image()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		// 画像登録処理
		$images = $this->save_images(SHOP_IMAGE_LIMIT);
		
		if ($images === FALSE)
		{
			$json['message'] = '画像の登録に失敗しました';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$new_images = array();
		

			// 編集前情報を取得
			$shop = $this->Banner->read(1);
			
			if($shop['type1'])
			{
				$photos = unserialize($shop['type1']);
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
				
				$new_images[$i]['url'] = $this->input->post('link_url' . $i);
			}
		
		if($new_images)
		{
			$result = $this->db->update('banner', array('type1' => (string)serialize($new_images)), array('id' => (int)1));

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

		$shop = $this->Banner->read(1);
		$photos = array();
		
		if($shop['type1'])
		{
			$photos = unserialize($shop['type1']);
		}
		
		if(!isset($photos[$num]))
		{
			$json['message'] = '指定された画像はありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$del_photo = $photos[$num];
		unset($photos[$num]['ext']);
		
		$this->db->update('banner', array('type1' => (string)serialize($photos)), array('id' => (int)1));
		
		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;

			@unlink(get_banner_path($num, $del_photo['ext']));
		}
		else
		{
			$json['message'] = '画像情報を削除できませんでした';
		}

		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * 画像登録
	 */
	public function save_images($limit, $image_type = '468_60')
	{
		$this->config->load('my/upload');
		
		$images = array();
				
		// 画像イメージタイプ
		$img_type = $this->config->item('IMG_TYPE');

		for($i = 1; $i <= $limit; $i++)
		{
			$src = $this->input->post("img_temp_src{$i}");

			if ($src)
			{
				$path = get_assets_temp_path($src);

				if (file_exists($path) !== TRUE) continue;

				// 画像の大きさを取得
				$imagesize = getimagesize($path);
				$width 		= $imagesize[0];
				$height		= $imagesize[1];
				$type		= $imagesize[2];
				
				// 拡張子を取得
				if (isset($img_type[$type]))
				{
					$extension = $img_type[$type];
				}
				else
				{
					// 念のための処理
					$extension = get_extension($path);
				}
				
				// 画像パスを取得
				$banner_path = get_banner_path($i, $extension, $image_type);
				
				// サイズを取得
				$size = explode("_", $image_type);
				
				$config = array();
				$config['image_library'] 	= IMAGE_LIBRARY;
				$config['source_image']		= $path;
				$config['new_image']		= $banner_path;
						//$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] 	= TRUE;
				$config['width']			= $size[0];
				$config['height']			= $size[1];

				$this->image_lib->initialize($config);
				$res = $this->image_lib->resize();
				$this->image_lib->clear();

					
				if (file_exists($banner_path) !== TRUE)
				{
					return FALSE;
				}
				
				$images[$i]['ext'] = $extension;
				$images[$i]['time'] = time();
			}
		}

		return $images;
	}
}