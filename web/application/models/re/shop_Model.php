<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class shop_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	public function read($id)
	{
		$this->db->select('*');
		$this->db->from('shop');
		$this->db->where('id', (int)$id);
  	$result = $this->db->get();
		$row = $result->row_array();
		$return_val = $row;
		return $return_val;
	}
}
