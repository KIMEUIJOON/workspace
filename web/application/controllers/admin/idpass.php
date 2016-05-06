<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Admin_Controller.php' );

class Idpass extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administrator_Model', 'Administrator', TRUE);
		
		$this->config->load('my/auth');
		$this->load->helper(array('url'));
	}


	/*
	 * IDパスワード変更フォーム
	 */
	public function index()
	{
		$this->load->view('admin/idpass');
	}

	/*
	 * パスワードを更新
	 */
	public function passwd()
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		if ( ! preg_match(PASS_PATTERN, $this->input->post('new_passwd')))
		{
			$json['message'] = '新パスワードは、' . PASS_ERROR_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ( ! preg_match(PASS_PATTERN, $this->input->post('old_passwd')))
		{
			$json['message'] = '旧パスワードは、' . PASS_ERROR_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// 現在のパスワードを取得
		$admin = $this->Administrator->read($_SESSION['admin']['id']);
		
		if ((string)$admin['passwd'] === (string)$this->input->post('new_passwd'))
		{
			$json['message'] = '現在のパスワードと新パスワードが同じです';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ((string)$admin['passwd'] !== (string)$this->input->post('old_passwd'))
		{
			$json['message'] = '現在のパスワードが間違っています';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ((string)$admin['username'] === (string)$this->input->post('new_passwd'))
		{
			$json['message'] = 'ログインIDと新パスワードが同じです';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// アップデート
		$this->db->where('id', (int)$_SESSION['admin']['id']);
		$this->db->update('administrator', array('passwd' => (string)$this->input->post('new_passwd')));
		
		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;
		}
		else
		{
			$json['message'] = 'データベースエラーが発生しパスワード更新できませんでした';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * ログインIDを更新
	 */
	public function username()
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		if ( ! preg_match(ID_PATTERN, $this->input->post('username')))
		{
			$json['message'] = 'ログインIDは、' . ID_ERROR_MSG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// 現在のログインIDを取得
		$admin = $this->Administrator->read($_SESSION['admin']['id']);
		
		if ((string)$admin['username'] === (string)$this->input->post('username'))
		{
			$json['message'] = 'ログインIDが変更されていません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ($this->Administrator->is_unique($this->input->post('username'), 'username') !== TRUE)
		{
			$json['message'] = '入力したログインIDは既に使用されています。';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// アップデート
		$this->db->where('id', (int)$_SESSION['admin']['id']);
		$this->db->update('administrator', array('username' => (string)$this->input->post('username')));
		
		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;
			$_SESSION['admin']['username'] = (string)$this->input->post('username');
		}
		else
		{
			$json['message'] = 'データベースエラーが発生しログインIDを更新できませんでした';
		}
		
		$this->output->set_output(json_encode($json));
	}
}