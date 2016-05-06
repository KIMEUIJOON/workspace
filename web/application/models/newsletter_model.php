<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/*
	 * 追加
	 */
	public function add($shop_id, $name, $email, $token)
	{
		$data = array(	'shop_id' 		=> (int)$shop_id,
						'name' 			=> (string)$name,
						'email' 		=> (string)$email,
						'token'			=> (string)$token,
						'date_time'		=> (int)time(),
						'ipaddress'		=> (string)$this->input->ip_address());
		
		$flag = $this->db->insert('newsletter', $data);
		
		if($flag === FALSE)
		{
			$this->error('Newsletter追加エラー ' . $this->db->_error_message());
			return FALSE;
		}
		
		return TRUE;
	}
	

	/**
	 * 既に登録しているか確認
	 */
	public function is_registry($shop_id, $email)
	{
		$this->db->select('id');
		$this->db->from('newsletter');
		$this->db->where('shop_id', (int)$shop_id);
		$this->db->where('email', (string)$email);
		
		$query = $this->db->get();
		
		if ($query->num_rows() === 0) return FALSE;

		return TRUE;
	}
	
	
	/**
	 * ショップに登録している購読者をすべて取得
	 */
	public function findAll($shop_id, $select = 'id, name, email')
	{
		$this->db->select($select);
		$this->db->from('newsletter');
		$this->db->where('shop_id', (int)$shop_id);

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
	
	
	/**
	 * ショップに登録している購読者数
	 */
	public function findAllCount($shop_id)
	{
		$this->db->from('newsletter');
		$this->db->where('shop_id', (int)$shop_id);
		return $this->db->count_all_results();
	}
	
	/*
	 * 一覧
	 */
	public function search($where = array(), $field = '*', $limit = 20, $offset = 0, $order = array(), $table = 'newsletter')
	{
		$return = array();

		$this->db->select($field);

		// データの総数取得のため再利用
		$this->db->start_cache();
		$this->db->from($table);

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