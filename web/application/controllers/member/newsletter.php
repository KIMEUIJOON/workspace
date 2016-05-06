<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// メールマガジン
class Newsletter extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		
		$this->load->model('Newsletter_model', 'Newsletter');
	}


	/**
	 * フォーム
	 */
	public function index()
	{
		$view = array();
		
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'op4, mtemp_subject, mtemp_body', TRUE);
		
		if( ! $shop['mtemp_body'])
		{
			$shop['mtemp_body'] = REPLACE_NAME . ' 様';
		}
		
		$this->load->view('member/newsletter', $shop + $view);
	}

	/**
	 * 情報ロード
	 */
	public function load()
	{
		$json = array();
		
		$page = ((int)$this->input->post('page')) ? (int)$this->input->post('page') : 1;
		$rows = ((int)$this->input->post('rows')) ? (int)$this->input->post('rows') : 20;
		$sort = ((string)$this->input->post('sort')) ? (string)$this->input->post('sort') : 'id';
		$order = ((string)$this->input->post('order')) ? (string)$this->input->post('order') : 'desc';
		
		$orders = array();
		$orders[$sort] = $order;

		$offset = ($page-1)*$rows;
		
		$where['shop_id'] = $_SESSION['member']['shop_id'];
		
		$users = $this->Newsletter->search($where, 'id, name, email, date_time', $rows, $offset, $orders);
		
		foreach($users['data'] as $k => $user)
		{
			$users['data'][$k]['date_time'] = date('Y-m-d', $user['date_time']);
		}
		
		$json['total']	= $users['count_all'];
		$json['rows']	= $users['data'];
		$json['status'] = STATUS_OK;

		$this->output->set_output(json_encode($json));
	}
	
	
	/**
	 * 配信履歴をロード
	 */
	public function load_history()
	{
		$json = array();
		
		$page = ((int)$this->input->post('page')) ? (int)$this->input->post('page') : 1;
		$rows = ((int)$this->input->post('rows')) ? (int)$this->input->post('rows') : 20;
		$sort = ((string)$this->input->post('sort')) ? (string)$this->input->post('sort') : 'id';
		$order = ((string)$this->input->post('order')) ? (string)$this->input->post('order') : 'desc';
		
		$orders = array();
		$orders[$sort] = $order;

		$offset = ($page-1)*$rows;
		
		$where['shop_id'] = $_SESSION['member']['shop_id'];
		
		$newsletters = $this->Newsletter->search($where, 'id, subject, body, date_time, status', $rows, $offset, $orders, 'newsletter_add');
		
		foreach($newsletters['data'] as $k => $newsletter)
		{
			$newsletters['data'][$k]['date_time'] = date('Y-m-d H:i', $newsletter['date_time']);
		}
		
		$json['total']	= $newsletters['count_all'];
		$json['rows']	= $newsletters['data'];
		$json['status'] = STATUS_OK;

		$this->output->set_output(json_encode($json));
	}
	
	/*
	 * テンプレート更新
	 */
	public function update_temp()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		if(mb_strlen($this->input->post('mtemp_subject')) > 35)
		{
			$json['message'] = 'メール件名は35文字以内で入力してください';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$result = $this->db->update('shop', array('mtemp_subject' => (string)$this->input->post('mtemp_subject'), 'mtemp_body' => (string)$this->input->post('mtemp_body')), array('id' => (int)$_SESSION['member']['shop_id']));

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
	 * メール配信予約
	 */
	public function add()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'op4', TRUE);

		if( ! $shop['op4'])
		{
			$json['message'] = 'メールマガジン有料オプションに登録が必要です';
			$this->output->set_output(json_encode($json));
			return;
		}

		// 購読者が一人でもいるかチェック
		if( ! $this->Newsletter->findAllCount($_SESSION['member']['shop_id']))
		{
			$json['message'] = '購読者がいません';
			$this->output->set_output(json_encode($json));
			return;
		}

		$errors = array();
		
		if( ! $this->input->post('subject') || mb_strlen($this->input->post('subject')) > 35)
		{
			$errors[] = 'メール件名は35文字以内で入力してください';
		}
		
		if(mb_strlen($this->input->post('body')) < 100)
		{
			$errors[] = 'メール本文100文字以上で入力してください';
		}
		
		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$result = $this->db->insert('newsletter_add', array(
			'shop_id'	=> (int)$_SESSION['member']['shop_id'],
			'subject'	=> (string)$this->input->post('subject'),
			'body'		=> (string)$this->input->post('body'),
			'date_time'	=> (int)time(),
			'ipaddress'	=> (string)$this->input->ip_address()
		));

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