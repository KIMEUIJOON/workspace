<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relation_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/*
	 * プライマリキーのデータ取得
	 */
	public function read($parent_id, $child_id, $return = FALSE)
	{
		$this->db->select('*');
		$this->db->from('relation');
		$this->db->where('parents_id', (string)$parent_id);
		$this->db->where('child_id', (string)$child_id);
		
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
	
	
	/*
	 * 子ショップを取得
	 */
	public function get_child($parent_id)
	{
		$this->db->select('shop.name, shop.id, shop.status_flag, carrier, post_code, prefecture, tel1, address');
		$this->db->from('relation,shop');
		$this->db->where('parents_id', (int)$parent_id);
		$this->db->where('relation.child_id', 'shop.id', false);
		
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