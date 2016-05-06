<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class User extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
	}
	
	/**
	 * ユーザ追加 tokenを返す
	 */
	public function add()
	{
		$this->config->load('my/user');
		
		$view = array();
		$view['status'] = STATUS_NG;
		
		// トークンを生成
		$this->token = get_token(USER_TOKEN_LENGTH);
		
		if(!$this->token)
		{
			$this->User->error('ユーザtoken生成エラー token:' . $this->token);
			$this->load->view('api/user/add', $view);
			return;
		}
		
		$this->load->model('User_Model', 'User', TRUE);
		
		// tokenを登録
		$flag = $this->db->insert('user', array('token' => (string)$this->token, 'entry_time' => (string)time())); 
		
		if($flag)
		{
			$view['status'] = STATUS_OK;
		}
		else
		{
			$this->token = '';
			$this->User->error('ユーザ追加エラー token:' . $this->token . ' ' . $this->db->_error_message());
		}

		$this->load->view('api/user/add', $view);
	}
}
