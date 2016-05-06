<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administrator_model', 'Administrator', TRUE);
		
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
		$this->load->view('admin/login');
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
		$admin = $this->Administrator->login($this->input->post('username'),$this->input->post('passwd'));

		if ($admin == FALSE)
		{
			$json['message'] = LOGIN_MISS_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		session_regenerate_id(true);

		$_SESSION['admin']['id'] = $admin['id'];
		$_SESSION['admin']['username'] = $admin['username'];
		
		$json['status'] = STATUS_OK;

		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * ログアウト
	 */
	public function logout()
	{
		$_SESSION = array();
		session_destroy();
		
		// ログインページへリダイレクト
		redirect('admin/login', 'location');
	}
}
