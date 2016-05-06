<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrator_Model extends MY_Model
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
		$this->db->from('administrator');
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


	/*
	 * プライマリキーのデータ取得
	 */
	public function read($id, $return = FALSE)
	{
		$this->db->select('*');
		$this->db->from('administrator');
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
	 * 全管理者を取得
	 */
	public function get_all()
	{
		$this->db->select('*');
		$this->db->from('administrator');
		$this->db->order_by('id', 'ASC');

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
	 * 指定したデータがデータベースでuniqueであるか
	 * $idが設定してある場合は、$idは除外
	 */
	public function is_unique($data, $field = 'username', $id = '')
	{
		$this->db->select('id');
		$this->db->from('administrator');
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
}
