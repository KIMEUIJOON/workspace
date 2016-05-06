<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_Model extends MY_Model
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
		$this->db->from('member');
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
		$this->db->from('member');
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
		$this->db->from('member');
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
	 * 既にメンバー登録しているか確認
	 */
	public function is_registry($shop_id)
	{
		$this->db->select('*');
		$this->db->from('member');
		$this->db->where('id', (int)$shop_id);
		
		$query = $this->db->get();
		
		if ($query->num_rows() === 0) return FALSE;

		return $query->row_array();
	}
}