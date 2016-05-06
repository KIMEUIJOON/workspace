<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

class Basic extends Member_Controller
{
	const FORM_RULES_NAME = 'parents_add';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('my/form_validation');
		$this->config->load('my/auth');
		$this->load->library('form_validation');
		
		$this->load->model('Parents_Model', 'Parents');
	}


	/**
	 * 基本情報画面
	 */
	public function index()
	{
		// 親情報取得
		$item = $this->Parents->read($_SESSION['parents']['id'], '*', TRUE);
		
		$this->load->view('parents/basic', $item);
	}

	/*
	 * 基本情報を更新
	 */
	public function update()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$this->form_validation->set_rules($this->config->item(self::FORM_RULES_NAME));

		if ($this->form_validation->run() !== TRUE)
		{
			$json['message'] = validation_errors('<p class="error">', '</p>');
			$this->output->set_output(json_encode($json));
			return;
		}

		$data = $this->form_validation->get_db_data(self::FORM_RULES_NAME);


		$result = $this->db->update('parents', $data, array('id' => (int)$_SESSION['parents']['id']));

		if ($result !== TRUE)
		{
			$json['message'] = 'データベースエラーが発生し更新できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		else
		{
			$json['status'] = STATUS_OK;
		}

		$this->output->set_output(json_encode($json));
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
		$member = $this->Parents->read($_SESSION['parents']['id'], '*', TRUE);
		
		if ((string)$member['passwd'] === (string)$this->input->post('new_passwd'))
		{
			$json['message'] = '現在のパスワードと新パスワードが同じです';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ((string)$member['passwd'] !== (string)$this->input->post('old_passwd'))
		{
			$json['message'] = '現在のパスワードが間違っています';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ((string)$member['username'] === (string)$this->input->post('new_passwd'))
		{
			$json['message'] = 'ログインIDと新パスワードが同じです';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// アップデート
		$this->db->where('id', (int)$_SESSION['parents']['id']);
		$this->db->update('parents', array('passwd' => (string)$this->input->post('new_passwd')));
		
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
		$member = $this->Parents->read($_SESSION['parents']['id'], '*', TRUE);
		
		if ((string)$member['username'] === (string)$this->input->post('username'))
		{
			$json['message'] = 'ログインIDが変更されていません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if ($this->Parents->is_unique($this->input->post('username'), 'username') !== TRUE)
		{
			$json['message'] = '入力したログインIDは既に使用されています。';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// アップデート
		$this->db->where('id', (int)$_SESSION['parents']['id']);
		$this->db->update('parents', array('username' => (string)$this->input->post('username')));
		
		if ($this->db->affected_rows())
		{
			$json['status'] = STATUS_OK;
		}
		else
		{
			$json['message'] = 'データベースエラーが発生しログインIDを更新できませんでした';
		}
		
		$this->output->set_output(json_encode($json));
	}
}