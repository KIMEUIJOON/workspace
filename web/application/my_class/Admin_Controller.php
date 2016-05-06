<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  管理者共通処理設定
 */
class Admin_Controller extends CI_Controller
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
    	session_start();
		
		$this->config->load('my/shop');
		$this->load->helper(array('form'));
		
		// 認証チェック
		if ( ! isset($_SESSION['admin']['id']) OR $_SESSION['admin']['id'] <= 0)
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
				redirect('admin/login?' . QUERY_KEY_SESSION_ERROR. '=' . 1, 'location');
			}
		}
		
		$this->load->model('Shop_model', 'Shop', TRUE);
	}
}