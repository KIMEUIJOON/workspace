<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// キャンペーン
class Campaign extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
	}


	/**
	 * 概要
	 */
	public function op1()
	{
		$view = array();
		
		// ショップ情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'campaign, campaign_date, op1', TRUE);
		
		if($shop['campaign_date'])
		{
			$shop['campaign_date'] = date('Y-m-d',$shop['campaign_date']);
		}
		else
		{
			$shop['campaign_date'] = '';
		}
		
		$this->load->view('member/campaign1', $shop);
	}

	/**
	 * 内容
	 */
	public function op2()
	{
		$view = array();
		
		// ショップ情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'campaign_body, campaign_date, op2', TRUE);
		
		if($shop['campaign_date'])
		{
			$shop['campaign_date'] = date('Y-m-d',$shop['campaign_date']);
		}
		else
		{
			$shop['campaign_date'] = '';
		}
		
		$this->load->view('member/campaign2', $shop);
	}
	
	/*
	 * 更新
	 */
	public function update1()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$errors = array();
		$campaign_date = 0;
		
		if($this->input->post('campaign_date'))
		{
			$campaign_date = strtotime($this->input->post('campaign_date'));
			
			if($campaign_date === FALSE)
			{
				$errors[] = '公開終了日時が正しくありません';
			}
		}

/*
		if((strlen(mb_convert_encoding($this->input->post('campaign'),'SJIS','UTF-8'))) > ((CAMPAIGN_LENGTH + 3) * 2))
		{
			$errors[] = 'キャンペーン概要は' . CAMPAIGN_LENGTH . '文字以内で入力してください';
		}
		if((strlen(mb_convert_encoding($this->input->post('campaign_body'),'SJIS','UTF-8'))) > ((CAMPAIGN_BODY_LENGTH + 18) * 2))
		{
			$errors[] = 'キャンペーン内容は' . CAMPAIGN_BODY_LENGTH . '文字以内で入力してください';
		}
*/
		if(mb_strlen($this->input->post('campaign'), 'UTF-8') > CAMPAIGN_LENGTH + 5) // 5はjqueryのlenghtと許容誤差
		{
			$errors[] = 'キャンペーン概要は' . CAMPAIGN_LENGTH . '文字以内で入力してください';
		}

		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}

		$data = array();
		$data['campaign'] = (string)$this->input->post('campaign');
		$data['campaign_date'] = (int)$campaign_date;
		
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
	
	
	
	/*
	 * 更新
	 */
	public function update2()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$errors = array();
		$campaign_date = 0;
		
		if($this->input->post('campaign_date'))
		{
			$campaign_date = strtotime($this->input->post('campaign_date'));
			
			if($campaign_date === FALSE)
			{
				$errors[] = '公開終了日時が正しくありません';
			}
		}

		if(mb_strlen($this->input->post('campaign_body'), 'UTF-8') > CAMPAIGN_BODY_LENGTH + 20) // 20はjqueryのlenghtと許容誤差
		{
			$errors[] = 'キャンペーン内容は' . CAMPAIGN_BODY_LENGTH . '文字以内で入力してください' . mb_strlen($this->input->post('campaign_body'), 'UTF-8');
		}
		
		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}

		$data = array();
		$data['campaign_body'] = (string)$this->input->post('campaign_body');
		$data['campaign_date'] = (int)$campaign_date;
		
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