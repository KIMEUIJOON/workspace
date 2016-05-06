<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends MY_Model
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
	public function read($token, $field = '*', $return = FALSE)
	{
		$this->db->select($field);
		$this->db->from('user');
		$this->db->where('token', (string)$token);

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
}