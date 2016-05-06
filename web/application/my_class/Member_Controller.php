<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  メンバー共通処理設定
 */
class Member_Controller extends CI_Controller
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
    	session_start();
		
		$this->config->load('my/shop');
		
		// 認証チェック
		if ( ! isset($_SESSION['member']['id']) || ! isset($_SESSION['member']['shop_id']) || !$_SESSION['member']['id'])
		{
			// Ajaxの場合は
			if ($this->input->is_ajax_request())
			{
				header("HTTP/1.0 200 " . STATUS_NG);
				$json = array('status' => STATUS_EXPIRE, 'rows' => array());
				echo json_encode($json);
				exit;
			}
			else
			{
				// ログインページへリダイレクト
				redirect('member/login?' . QUERY_KEY_SESSION_ERROR. '=' . 1, 'location');
			}
		}
		
		$this->load->model('Shop_model', 'Shop', TRUE);
	}
	
	
	/*
	 * 画像登録
	 */
	public function save_images($id, $image_name, $limit)
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
				
				// オリジナル画像
				$original_path = get_image_path($id, $i, $extension, $image_name, IMAGE_ORIGINAL_NAME);

				$res = copy($path, $original_path);

				if ($res !== TRUE)
				{
					return FALSE;
				}

				// 画像パスを取得
				$large_path = get_image_path($id, $i, $extension, $image_name, IMAGE_LARGE_NAME);
				$middle_path = get_image_path($id, $i, $extension, $image_name, IMAGE_MIDDLE_NAME);
				$small_path = get_image_path($id, $i, $extension, $image_name, IMAGE_SMALL_NAME);
				
				$thumb = array();
				$thumb[] = array('path' => $large_path, 'size' => IMAGE_LARGE_SIZE);
				$thumb[] = array('path' => $middle_path, 'size' => IMAGE_MIDDLE_SIZE);
				$thumb[] = array('path' => $small_path, 'size' => IMAGE_SMALL_SIZE);
				
				foreach ($thumb as $info)
				{
					$flag = TRUE;
					
					if ($info['size'] >= $width)
					{
						// 元画像が小さいのでそのままコピー
						$res = copy($original_path, $info['path']);
					}
					else
					{
						$config = array();
						$config['image_library'] 	= IMAGE_LIBRARY;
						$config['source_image']		= $original_path;
						$config['new_image']		= $info['path'];
						//$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] 	= TRUE;
						$config['width']			= $info['size'];
						$config['height']			= 10000;

						$this->image_lib->initialize($config);
						$res = $this->image_lib->resize();
						$this->image_lib->clear();
					}
					
					if (file_exists($info['path']) !== TRUE)
					{
						return FALSE;
					}
				}
				
				$images[$i]['ext'] = $extension;
				$images[$i]['time'] = time();
			}
		}

		return $images;
	}
}