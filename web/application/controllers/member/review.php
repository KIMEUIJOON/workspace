<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// レビュー
class Review extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		
		$this->load->model('Review_model', 'Review', TRUE);
	}


	/**
	 * フォーム
	 */
	public function index()
	{
		$view = array();
		
		// レビュー情報取得
		$shop = $this->Shop->read($_SESSION['member']['shop_id'], 'review_flag, op7', TRUE);
		
		$review = $this->Review->counting($_SESSION['member']['shop_id']);

		$shop['review_count'] = $review['cnt'];
				
		if($review['cnt'])
		{
			$shop['review_score'] = round($review['score_sum'] / $review['cnt'], 1);
		}

		$this->load->view('member/review', $shop);
	}


	/**
	 * レビュー情報ロード
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
		
		$reviews = $this->Review->search($where, 'score, name, body, entry_time', $rows, $offset, $orders);
		
		foreach($reviews['data'] as $k => $review)
		{
			$reviews['data'][$k]['entry_time'] = date('Y-m-d', $review['entry_time']);
			$reviews['data'][$k]['entry_datetime'] = date('Y-m-d H:i:s', $review['entry_time']);
		}
		
		$json['total']	= $reviews['count_all'];
		$json['rows']	= $reviews['data'];
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

		if(! $this->config->item($this->input->post('review_flag'), 'review'))
		{
			$json['message'] = 'レビューの設定が不正です';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$result = $this->db->update('shop', array('review_flag' => (int)$this->input->post('review_flag')), array('id' => (int)$_SESSION['member']['shop_id']));

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