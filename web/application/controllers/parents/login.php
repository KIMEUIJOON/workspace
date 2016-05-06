<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Parents_Model', 'Parents', TRUE);
		$this->load->model('Shop_model', 'Shop');
		$this->load->model('Relation_Model', 'Relation');
		
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
		$this->load->view('parents/login');
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
		$parent = $this->Parents->login($this->input->post('username'),$this->input->post('passwd'));

		if ($parent == FALSE)
		{
			$json['message'] = LOGIN_MISS_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// 関連ショップ情報取得
		$shop = $this->Relation->get_child($parent['id']);
		
		if (count($shop) == 0 || !isset($shop[0]['id']))
		{
			$json['message'] = '入力したIDで管理するショップが登録されていません';
			$this->output->set_output(json_encode($json));
			return;
		}

		session_regenerate_id(true);

		$_SESSION = array();
		
		$_SESSION['member']['id'] = $parent['id']; // メンバーIDに親IDを設定 ['member']['id']は使用していない
		$_SESSION['member']['shop_id'] = $shop[0]['id'];
		$_SESSION['member']['shop_name'] = $shop[0]['name'];
		$_SESSION['parents']['id'] = $parent['id'];

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
		redirect('member/login', 'location');
	}
}
