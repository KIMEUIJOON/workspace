<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/*
	 * レビュー追加
	 */
	public function add($shop_id, $name, $body, $score)
	{
		$data = array(	'shop_id' 		=> (int)$shop_id,
						'score'			=> (float)$score,
						'name' 			=> (string)$name,
						'body' 			=> (string)$body,
						'entry_time'	=> (int)time(),
						'ipaddress'		=> (string)$this->input->ip_address());
		
		$flag = $this->db->insert('review', $data);
		
		if($flag === FALSE)
		{
			$this->error('レビュー追加エラー ' . $this->db->_error_message());
			return FALSE;
		}
		
		return TRUE;
	}
	

	/**
	 * 指定したショップのレビューを集計
	 */
	public function counting($shop_id)
	{
		$this->db->select('count(id) as cnt,sum(score) as score_sum', FALSE);
		$this->db->from('review');
		$this->db->where('shop_id', (int)$shop_id);
		$this->db->where('status', (int)1);
		
		$query = $this->db->get();

		return $query->row_array();
	}
	
	
	/**
	 * レビュー一覧を取得
	 */
	public function lists($shop_id, $limit = 50, $offset = 0)
	{
		$this->db->select('shop_id, score, name, body, entry_time');
		$this->db->from('review');
		$this->db->where('shop_id', (int)$shop_id);
		$this->db->where('status', (int)1);
		$this->db->order_by('entry_time', 'DESC');
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	/*
	 * レビュー情報検索
	 */
	public function search($where = array(), $field = '*', $limit = 20, $offset = 0, $order = array())
	{
		$return = array();

		$this->db->select($field);

		// データの総数取得のため再利用
		$this->db->start_cache();
		$this->db->from('review');

		// where 設定
		if (count($where))
		{
			$this->db->where($where);
		}

		$this->db->stop_cache();

		// order 設定
		if (count($order))
		{
			foreach ($order as $k => $v)
			{
				$this->db->order_by((string)$k, (string)$v);
			}
		}
		else
		{
			$this->db->order_by('id', 'DESC');
		}

		$this->db->limit($limit, $offset);

		$query = $this->db->get();

		$return['data'] = array();
		$return['count_all'] = $this->db->count_all_results();

		$this->db->flush_cache(); // キャッシュクリア

		if ($query->num_rows() > 0)
		{
			$return['data'] = $query->result_array();
			return $return;
		}
		else
		{
			// connect チェック
			//$this->is_connect();
			return $return;
		}
	}
}