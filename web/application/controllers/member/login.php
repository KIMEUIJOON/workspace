<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Member_model', 'Member', TRUE);
		$this->load->model('Shop_model', 'Shop');
		
		session_start();
		
		$this->config->load('my/auth');
	}


	/**
	 * ログインフォーム
	 */
	public function index()
	{
		// cookieが使えるかチェックのためにセット
		setcookie(CHECK_COOKIE_NAME, "1");
		$this->load->view('member/login');
	}
	
	
	/**
	 * 認証
	 */
	public function auth()
	{
		$json = array();
		$json['status'] = STATUS_NG;

		// クッキーが使えるかチェック
		if ( ! isset($_COOKIE[CHECK_COOKIE_NAME]))
		{
			$json['message'] = CHECK_COOKIE_ERROR_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}

		$this->load->library('form_validation');
		
		// ID/PASS形式パターンチェック
		if ( ! preg_match(ID_PATTERN, $this->input->post('username')) OR ! preg_match(PASS_PATTERN, $this->input->post('passwd')))
		{
			$json['message'] = LOGIN_MISS_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}

		// ログイン認証
		$member = $this->Member->login($this->input->post('username'),$this->input->post('passwd'));

		if ($member == FALSE)
		{
			$json['message'] = LOGIN_MISS_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// assetsフォルダが存在するかチェック
		$res = make_assets_path($member['id']);
		
		if($res !== TRUE)
		{
			$this->Member->error($res);
			
			$json['message'] = $res;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		session_regenerate_id(true);

		$_SESSION = array();
		
		$_SESSION['member']['id'] = $member['id'];
		$_SESSION['member']['shop_id'] = $member['id'];
		$_SESSION['parents']['id'] = false;
			
		$json['status'] = STATUS_OK;

		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * ログアウト
	 */
	public function logout()
	{
		$parents = $_SESSION['parents']['id'];
		
		$_SESSION = array();
		session_destroy();
		
		// ログインページへリダイレクト
		if($parents)
		{
			redirect('parents/login', 'location');
		}
		else
		{
			redirect('member/login', 'location');
		}
	}
}
