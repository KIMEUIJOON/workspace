<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// チラシ、POPリンク
class Flyer extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->config->load('my/image');
		$this->config->load('my/pdf');
		$this->load->library('image_lib');
	}


	/**
	 * フォーム
	 */
	public function index()
	{
		$view = array();
		$view['flyer_image'] = array();
		$view['flyer_pdf'] = array();
		
		// ショップ情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'flyer_image, flyer_pdf, op5', TRUE);
		
		if($shop['flyer_image'])
		{
			$view['flyer_image'] = unserialize($shop['flyer_image']);
		}

		if($shop['flyer_pdf'])
		{
			$view['flyer_pdf'] = unserialize($shop['flyer_pdf']);
		}
		
		// オプション値設定
		$view['op5'] = $shop['op5'];
		
		$this->load->view('member/flyer', $view);
	}

	/*
	 * 更新
	 */
	public function update_image()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		// 更新時間
		$this->update_time = (int)time();

		// 画像登録処理
		$images = $this->save_images($_SESSION['member']['shop_id'], IMAGE_NAME_FLYER, SHOP_FLYER_IMAGE_LIMIT);
		
		if ($images === FALSE)
		{
			$json['message'] = '画像の登録に失敗しました';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$new_images = array();
		
			// 編集前情報を取得
			$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'flyer_image, flyer_pdf', TRUE);
			
			if($shop['flyer_image'])
			{
				$flyer_image = unserialize($shop['flyer_image']);
			}
			
			// 画像情報をviewにセット
			for($i = 1; $i <= SHOP_FLYER_IMAGE_LIMIT; $i++)
			{
				if (isset($images[$i]))
				{
					$new_images[$i] = $images[$i];
					$new_images[$i]['txt'] = $this->input->post('link_text' . $i);
				}
				else if(isset($flyer_image[$i]['ext']))
				{
					$new_images[$i] = $flyer_image[$i];
					$new_images[$i]['txt'] = $this->input->post('link_text' . $i);
				}
				else
				{
					$new_images[$i]['txt'] = $this->input->post('link_text' . $i);
				}
			}
		
		if($new_images)
		{
			$result = $this->db->update('shop', array('flyer_image' => (string)serialize($new_images)), array('id' => (int)$_SESSION['member']['shop_id']));

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
	public function update_pdf()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		// PDF登録処理
		$pdfs = $this->save_pdfs($_SESSION['member']['shop_id']);
		
		if ($pdfs === FALSE)
		{
			$json['message'] = 'PDFの登録に失敗しました';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$new_pdfs = array();
		
			// 編集前情報を取得
			$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'flyer_image, flyer_pdf', TRUE);
			
			if($shop['flyer_pdf'])
			{
				$flyer_pdf = unserialize($shop['flyer_pdf']);
			}
			
			for($i = 1; $i <= SHOP_FLYER_PDF_LIMIT; $i++)
			{
				if (isset($pdfs[$i]))
				{
					$new_pdfs[$i] = $pdfs[$i];
					$new_pdfs[$i]['txt'] = $this->input->post('link_text' . $i);
				}
				else if(isset($flyer_pdf[$i]['time']))
				{
					$new_pdfs[$i] = $flyer_pdf[$i];
					$new_pdfs[$i]['txt'] = $this->input->post('link_text' . $i);
				}
				else
				{
					$new_pdfs[$i]['txt'] = $this->input->post('link_text' . $i);
				}
			}
		

			$result = $this->db->update('shop', array('flyer_pdf' => (string)serialize($new_pdfs)), array('id' => (int)$_SESSION['member']['shop_id']));

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
	public function del_flyer_image()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$num = $this->input->post('number');

		if ($num < 1 OR $num > SHOP_FLYER_IMAGE_LIMIT)
		{
			$json['message'] = '画像番号が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}

		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'id, flyer_image');
		$flyer_image = array();
		
		if($shop['flyer_image'])
		{
			$flyer_image = unserialize($shop['flyer_image']);
		}
		
		if(!isset($flyer_image[$num]['ext']))
		{
			$json['message'] = '指定された画像はありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$del_flyer_image = $flyer_image[$num];
		unset($flyer_image[$num]['ext']);
		unset($flyer_image[$num]['time']);
		
		$this->db->where('id', (int)$shop['id']);
		$this->db->update('shop', array('flyer_image' => (string)serialize($flyer_image)));

		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;

			@unlink(get_image_path($shop['id'], $num, $del_flyer_image['ext'], IMAGE_NAME_FLYER, IMAGE_LARGE_NAME));
			@unlink(get_image_path($shop['id'], $num, $del_flyer_image['ext'], IMAGE_NAME_FLYER, IMAGE_MIDDLE_NAME));
			@unlink(get_image_path($shop['id'], $num, $del_flyer_image['ext'], IMAGE_NAME_FLYER, IMAGE_SMALL_NAME));
			@unlink(get_image_path($shop['id'], $num, $del_flyer_image['ext'], IMAGE_NAME_FLYER, IMAGE_ORIGINAL_NAME));
		}
		else
		{
			$json['message'] = '画像情報を削除できませんでした';
		}

		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * PDF削除
	 */
	public function del_flyer_pdf()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$num = $this->input->post('number');

		if ($num < 1 OR $num > SHOP_FLYER_PDF_LIMIT)
		{
			$json['message'] = 'PDF番号が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}

		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'id, flyer_pdf');
		$flyer_pdf = array();
		
		if($shop['flyer_pdf'])
		{
			$flyer_pdf = unserialize($shop['flyer_pdf']);
		}
		
		if(!isset($flyer_pdf[$num]['time']))
		{
			$json['message'] = '指定されたPDFはありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$del_flyer_pdf = $flyer_pdf[$num];
		unset($flyer_pdf[$num]['time']);
		
		$this->db->where('id', (int)$shop['id']);
		$this->db->update('shop', array('flyer_pdf' => (string)serialize($flyer_pdf)));

		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;

			@unlink(get_pdf_path($shop['id'], $num, PDF_NAME_FLYER));
		}
		else
		{
			$json['message'] = 'PDF情報を削除できませんでした';
		}

		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * PDF登録
	 */
	private function save_pdfs($id)
	{
		$this->config->load('my/upload');
		
		$pdfs = array();

		for($i = 1; $i <= SHOP_FLYER_PDF_LIMIT; $i++)
		{
			$src = $this->input->post("pdf_temp_src{$i}");

			if ($src)
			{
				$path = get_assets_temp_path($src);

				if (file_exists($path) !== TRUE) continue;

				$pdf_path = get_pdf_path($id, $i, PDF_NAME_FLYER);

				$res = copy($path, $pdf_path);

				if ($res !== TRUE)
				{
					return FALSE;
				}

				$pdfs[$i]['time'] = time();
			}
		}

		return $pdfs;
	}
}