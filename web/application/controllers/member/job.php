<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// 求人情報
class Job extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
	}


	/**
	 * フォーム
	 */
	public function index()
	{
		$view = array();
		
		// 求人情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'job, op6', TRUE);
		
		$this->load->view('member/job', $shop);
	}

	/*
	 * 更新
	 */
	public function update()
	{
		$json = array();

		$json['status'] = STATUS_NG;
		$errors = array();
		
		if(mb_strlen($this->input->post('job'), 'UTF-8') > JOB_LENGTH + 30) // 30はjqueryのlenghtと許容誤差
		{
			$errors[] = '求人情報は' . JOB_LENGTH . '文字以内で入力してください';
		}
		
		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$result = $this->db->update('shop', array('job' => (string)$this->input->post('job')), array('id' => (int)$_SESSION['member']['shop_id']));

		if ($result !== TRUE)
		{
			$json['message'] = 'データベースエラーが発生し更新できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}
}