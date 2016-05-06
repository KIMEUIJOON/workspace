<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bookmark_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/*
	 * ブックマーク済みかチェック
	 */
	public function registry($token, $shop_id)
	{
		$this->db->select('id');
		$this->db->from('bookmark');
		$this->db->where('token', (string)$token);
		$this->db->where('shop_id', (int)$shop_id);
		
    	$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}

		return FALSE;
	}
	
	
	/*
	 * ブックマーク追加
	 */
	public function add($token, $shop_id)
	{
		if($this->registry($token, $shop_id) === TRUE)
		{
			return TRUE;
		}

		$flag = $this->db->insert('bookmark', array('token' => (string)$token, 'shop_id' => (int)$shop_id, 'entry_time' => (int)time()));
		
		if($flag)
		{
			return TRUE;
		}
		else
		{
			$this->error('Bookmark追加エラー ' . $this->db->_error_message());
			return FALSE;
		}
	}
	
	
	/**
	 * ブックマーク一覧を取得
	 */
	public function lists($token)
	{
		$this->db->select('shop.*');
		$this->db->from('bookmark');
		$this->db->join('shop', 'bookmark.shop_id = shop.id', 'inner');
		$this->db->where('token', (string)$token);
		$this->db->order_by('entry_time', 'DESC');
		
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
}