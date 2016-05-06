<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Newsletter extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Newsletter_Model', 'Newsletter', TRUE);
	}
	
	/**
	 * メールマガジン登録
	 */
	public function add()
	{
		$this->load->library('form_validation');

		$json = array();
		$json['status'] = STATUS_NG;
		
		$errors = array();
		
		if(!$this->input->post('name') || !trim($this->input->post('name')))
		{
			$errors[] = 'お名前を入力してください';
		}
		if($this->form_validation->valid_email($this->input->post('email')) === FALSE)
		{
			$errors[] = 'メールアドレスが正しくありません';
		}

		if($errors)
		{
			$json['message'] = implode("\n", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$this->load->model('Shop_Model', 'Shop');

		if($this->Shop->read($this->input->post('id'), 'id', TRUE) ===  FALSE)
		{
			$json['message'] = 'ショップ情報が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}

		if($this->Newsletter->is_registry($this->input->post('id'), $this->input->post('email')) ===  TRUE)
		{
			$json['message'] = '入力したアドレスで既に登録済みです';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// トークン生成
		$token = get_token(TOKEN_LENGTH);
		
		if(strlen($token) < TOKEN_LENGTH)
		{
			$this->Newsletter->error('Newsletter token生成エラー token:' . $token);
			$json['message'] = 'データベースエラーが発生し登録に失敗しました';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		if($this->Newsletter->add($this->input->post('id'), $this->input->post('name'), $this->input->post('email'), $token) === FALSE)
		{
			$json['message'] = 'データベースエラーが発生し登録に失敗しました';
			$this->output->set_output(json_encode($json));
			return;
		}

		$json['status'] = STATUS_OK;
		$this->output->set_output(json_encode($json));
		return;
	}
}
