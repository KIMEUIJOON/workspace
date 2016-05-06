<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// クーポン
class Coupon extends Member_Controller
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
		
		// ショップ情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'coupon_body, coupon_date, op3', TRUE);
		
		if($shop['coupon_date'])
		{
			$shop['coupon_date'] = date('Y-m-d',$shop['coupon_date']);
		}
		else
		{
			$shop['coupon_date'] = '';
		}
		
		$this->load->view('member/coupon', $shop);
	}

	/*
	 * 更新
	 */
	public function update()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$errors = array();
		$coupon_date = 0;
		
		if($this->input->post('coupon_date'))
		{
			$coupon_date = strtotime($this->input->post('coupon_date'));
			
			if($coupon_date === FALSE)
			{
				$errors[] = '公開終了日時が正しくありません';
			}
		}
		
		if(mb_strlen($this->input->post('coupon_body'), 'UTF-8') > COUPON_LENGTH + 20) // 20はjqueryのlenghtと許容誤差
		{
			$errors[] = 'クーポン情報は' . COUPON_LENGTH . '文字以内で入力してください';
		}

		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$data = array();
		$data['coupon_body'] = (string)$this->input->post('coupon_body');
		$data['coupon_date'] = (int)$coupon_date;
		
		$result = $this->db->update('shop', $data, array('id' => (int)$_SESSION['member']['shop_id']));

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