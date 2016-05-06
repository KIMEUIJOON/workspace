<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_code_Model extends MY_Model
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
		$this->db->from('post_code');
		$this->db->where('id', (string)$id);

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
	 * 指定したデータがデータベースでuniqueであるか
	 * $idが設定してある場合は、$idは除外
	 */
	public function is_unique($data, $field = 'id')
	{
		$this->db->select('id');
		$this->db->from('post_code');
		$this->db->where($field, $data);
		
		$query = $this->db->get();
		
		// connect チェック
		//$this->is_connect();
		
		if ($query->num_rows() === 0) return TRUE;

		return FALSE;
	}
	
	
	
	/**
	 * タスクを取得
	 */
	public function get_task_geocode_area($limt)
	{
		$this->db->select('id, name, SUBSTRING( id FROM 1 FOR 2 ) AS prefecture', false);
		$this->db->from('area_code');
		$this->db->where('geocod_flag', 0);
		$this->db->where('lat IS NULL', null, false);
		$this->db->order_by('id', 'ASC');
		$this->db->limit($limt);

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
	 * 市区町村を取得
	 */
	 /*
	public function get_area_list($prefecture)
	{
		$this->db->select('id, name, cnt, child');
		$this->db->from('area_code');
		$this->db->like('id', (string)$prefecture, 'after'); 
		//$this->db->where('cnt >', (int)0);
		$this->db->order_by('cnt', 'DESC');
		$this->db->order_by("sort", "asc");

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
	*/
	
	
	/**
	 * 市区町村を取得
	 */
	public function get_area_list($prefecture, $ins = FALSE)
	{
		if (empty($prefecture) OR strlen($prefecture) != 2)
		{
			return array();
		}
		
		$where_in = $this->create_where_in('carrier', $ins, ' AND ');

		$sql = "SELECT * FROM (SELECT id,name,sort,child FROM `area_code` WHERE id like '{$prefecture}%') as area_code LEFT JOIN (SELECT count(area_code) as cnt,area_code FROM (SELECT id,post_code,carrier FROM `shop` WHERE prefecture = '{$prefecture}'{$where_in}) as shop INNER JOIN post_code ON shop.post_code = post_code.id group by area_code) as area_count ON area_code.id = area_count.area_code ORDER BY cnt DESC, sort ASC";

		$query = $this->db->query($sql);

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
	 * タウンを取得
	 */
	 /*
	public function get_town_list($area_code)
	{
		$this->db->select('id, name');
		$this->db->from('town_code');
		$this->db->like('id', (string)$area_code, 'after'); 
		$this->db->where('name !=', (string)'');
		//$this->db->order_by('cnt', 'DESC');
		$this->db->order_by("sort", "asc");

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
	*/
	
	
	/**
	 * タウンを取得
	 */
	public function get_town_list($area_code, $ins = FALSE)
	{
		if (empty($area_code) OR strlen($area_code) != 5)
		{
			return array();
		}
		
		$prefecture = substr($area_code, 0, 2);
		$where_in = $this->create_where_in('carrier', $ins, ' AND ');
		
		$sql = "SELECT * FROM (SELECT id,name,sort FROM `town_code` WHERE id like '{$area_code}%') as town_code LEFT JOIN (SELECT COUNT( town_code ) AS cnt, town_code FROM (SELECT id, town_code FROM `post_code` WHERE area_code = '{$area_code}') AS post_code INNER JOIN (SELECT post_code,carrier FROM  `shop` WHERE prefecture = '{$prefecture}'{$where_in}) as shop ON post_code.id = shop.post_code GROUP BY town_code) as town_count ON town_code.id = town_count.town_code ORDER BY cnt DESC, sort ASC";


		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	/*
	 * areaをキーで取得
	 */
	public function get_area($area_code)
	{
		if (empty($area_code) OR strlen($area_code) != 5)
		{
			return FALSE;
		}

		$this->db->select('*');
		$this->db->from('area_code');
		$this->db->where('id', (string)$area_code);

    	$query = $this->db->get();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		$data = $query->row_array();

		return $data;
	}
	
	
	/*
	 * townをキーで取得
	 */
	public function get_town($town_code)
	{
		if (empty($town_code) OR strlen($town_code) != 9)
		{
			return FALSE;
		}

		$this->db->select('*');
		$this->db->from('town_code');
		$this->db->where('id', (string)$town_code);

    	$query = $this->db->get();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		$data = $query->row_array();

		return $data;
	}
	
	
	/*
	 * 郵便番号より、areaとtownの緯度・経度を取得
	 */
	public function get_area_tonw_location_by_postcode($post_code)
	{
		$sql = "SELECT post_code.id,area_code.lat as area_lat,area_code.lng as area_lng,town_code.lat as town_lat,town_code.lng as town_lng FROM `post_code`,area_code,town_code WHERE post_code.id = '{$post_code}' AND post_code.area_code = area_code.id AND post_code.town_code = town_code.id";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return array();
		}
	}
}