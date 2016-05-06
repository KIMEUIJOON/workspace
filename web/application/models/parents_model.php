<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parents_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * ログイン
	 */
	public function login($username, $passwd)
	{
		$this->db->select('*');
		$this->db->from('parents');
		$this->db->where('username', (string)$username);
		$this->db->where('passwd', (string)$passwd);

		$query = $this->db->get();

		if ($query->num_rows() !== 1)
		{
			// connect チェック
			//$this->is_connect();
			return FALSE;
		}

		$user = $query->row_array();

		return $user;
	}
	
	
	/**
	 * 指定したデータがデータベースでuniqueであるか
	 * $idが設定してある場合は、$idは除外
	 */
	public function is_unique($data, $field = 'username', $id = '')
	{
		$this->db->select('id');
		$this->db->from('parents');
		$this->db->where($field, $data);
		
		// $idが指定している場合は、そのIDを除外
		if ($id)
		{
			$this->db->where('id !=', (int)$id);
		}
		
		$query = $this->db->get();
		
		// connect チェック
		//$this->is_connect();
		
		if ($query->num_rows() === 0) return TRUE;

		return FALSE;
	}
	
	/*
	 * プライマリキーのデータ取得
	 */
	public function read($id, $field = '*', $return = FALSE)
	{
		$this->db->select($field);
		$this->db->from('parents');
		$this->db->where('id', (int)$id);

    	$query = $this->db->get();

		if ($query->num_rows() !== 1)
		{
			// connect チェック
			//$this->is_connect();

			if ($return)
			{
				return FALSE;
			}
		}

		$data = $query->row_array();

		return $data;
	}
	
	
	
	/**
	 * 既に親登録しているか確認
	 */
	public function is_registry($shop_id)
	{
		$this->db->select('*');
		$this->db->from('parents');
		$this->db->where('id', (int)$shop_id);
		
		$query = $this->db->get();
		
		if ($query->num_rows() === 0) return FALSE;

		return $query->row_array();
	}
	
	
	/*
	 * 管理 親検索
	 */
	public function search($where = array(), $field = '*', $limit = 20, $offset = 0, $order = array(), $like = array())
	{
		$return = array();

		$this->db->select($field);

		// データの総数取得のため再利用
		$this->db->start_cache();
		$this->db->from('parents');

		// where 設定
		if (count($where))
		{
			$this->db->where($where);
		}

		// like 設定
		if (count($like))
		{
			foreach($like as $word)
			{
				$this->db->like((string)$word[0],(string)$word[1], (string)$word[2]);
			}
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