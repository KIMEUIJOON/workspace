<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// オプション
class Option extends Member_Controller
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
		$this->load->view('member/option');
	}


	/**
	 * オプション情報ロード
	 */
	public function load()
	{
		$this->load->model('Option_model', 'Option');
		
		$json = array();
		
		$shop = $this->Option->get_shop_option($_SESSION['member']['shop_id']);
		
		if($shop === FALSE)
		{
			$this->Option->error('get_shop_option ERROR' . $this->db->_error_message());
			$json['status'] = STATUS_NG;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$option = $this->config->item('option');
		
		$op_all = true;
		
		// オプション値を設定
		foreach($option as $key => $op)
		{
			if($op['id'] != 'op_all')
			{
				$option[$key]['value'] = $shop[$op['id']];
				
				// フルオプションパック確認ため未加入オプションがあった場合はfalse
				if($shop[$op['id']] == 0)
				{
					$op_all = false;
				}
				
				if($shop[$op['id']] == 1 && $shop[$op['id'] . '_time'])
				{
					$option[$key]['date_time'] = date('Y-m-d', $shop[$op['id'] . '_time']);
				}
			}
			else
			{
				$op_all_key = $key;
			}
		}
		
		if($op_all)
		{
			$option[$op_all_key]['date_time'] = '<span style="color:red">登録済</span>';
		}

		$json['rows']	= $option;
		$json['status'] = STATUS_OK;

		$this->output->set_output(json_encode($json));
	}


	/*
	 * 更新
	 */
	public function update()
	{
		$json = array();

		$json['status'] = STATUS_NG;
		$errors = array();
		
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], '*', TRUE);
		
		$option = $this->input->post('option');
		$status = $this->input->post('status');
		
		if( ! isset($shop[$option]) || ($status != 1 && $status != 0))
		{
			$json['message'] = 'オプション値が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// 登録されている情報と変更されているか確認
		if($shop[$option] == $status)
		{
			// 変更されていないのでそのままOKを返す
			$json['status'] = STATUS_OK;
			$this->output->set_output(json_encode($json));
			return;
		}
		
		
		$this->db->trans_start();
		
		$this->db->update('shop', array($option => (int)$status), array('id' => (int)$_SESSION['member']['shop_id']));
		
		// オプションを有効にした場合は、登録時間も保存
		if($status)
		{
			$this->db->update('option', array($option . '_time' => (int)time()), array('shop_id' => (int)$_SESSION['member']['shop_id']));
		}
		
		$this->db->trans_complete();
			
		if ($this->db->trans_status() === FALSE)
		{
			$json['message'] = 'データベースエラーが発生し更新できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}
	
	
	/*
	 * フルオプションパック
	 */
	public function update_all()
	{
		$json = array();

		$json['status'] = STATUS_NG;
		$errors = array();
		
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], '*', TRUE);
		
		$option = $this->config->item('option');
		
		$update = array();
		
		// オプション値を設定
		foreach($option as $key => $op)
		{
			if($op['id'] != 'op_all')
			{
				// オプションに登録されていない場合のみ更新
				if($shop[$op['id']] != 1)
				{
					$update[] = $op['id'];
				}
			}
		}
		
		if( ! $update)
		{
			$json['message'] = '既にフルオプションパックに登録済みです。';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$this->db->trans_start();
		
		foreach($update as $val)
		{
			$this->db->update('shop', array($val => (int)1), array('id' => (int)$_SESSION['member']['shop_id']));
			$this->db->update('option', array($val . '_time' => (int)time()), array('shop_id' => (int)$_SESSION['member']['shop_id']));
		}
		
		$this->db->trans_complete();
			
		if ($this->db->trans_status() === FALSE)
		{
			$json['message'] = 'データベースエラーが発生し更新できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}
}