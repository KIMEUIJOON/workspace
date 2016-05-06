<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 郵便番号と店名が同じかチェック
	 */
	public function shop_unique($post_code, $name, $carrier)
	{
		$this->db->select('id');
		$this->db->from(IMPORT_SHOP_TABLE_NAME);
		$this->db->where('post_code', $post_code);
		$this->db->where('carrier', $carrier);
		$this->db->where('name', $name);
		
		$query = $this->db->get();
		
		if ($query->num_rows() === 0) return TRUE;

		return FALSE;
	}
}