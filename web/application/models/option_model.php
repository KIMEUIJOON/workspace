<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Option_Model extends MY_Model
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
	public function read($id, $field = '*', $return = FALSE)
	{
		$this->db->select($field);
		$this->db->from('option');
		$this->db->where('shop_id', (int)$id);

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
	 * shop_idよりオプション情報を取得
	 */
	public function get_shop_option($shop_id)
	{
		$this->db->select('*');
		$this->db->from('shop');
		$this->db->join('option', 'shop.id = option.shop_id', 'inner');
		$this->db->where('id', $shop_id);
		
    	$query = $this->db->get();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		$data = $query->row_array();

		return $data;
	}
}