<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shop_Model extends MY_Model
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
		$this->db->from('shop');
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
	 * 指定したデータがデータベースでuniqueであるか
	 * $idが設定してある場合は、$idは除外
	 */
	public function is_unique($data, $field = 'name', $id = '')
	{
		$this->db->select('id');
		$this->db->from('shop');
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
	
	
	/**
	 * タスクを取得
	 */
	public function geocode($limt)
	{
		$this->db->select('id, prefecture, address');
		$this->db->from('shop');
		$this->db->where('lat IS NULL', null, false);
		$this->db->where('cron_flag', 1);
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
	 * 市区町村のショップを取得
	 */
	public function get_area_list($area_code, $ins = FALSE)
	{
		if (empty($area_code) OR strlen($area_code) != 5)
		{
			return array();
		}
		
		$where_in = $this->create_where_in('carrier', $ins, ' WHERE ');

		$sql = "SELECT shop.*,area_name,town_name FROM (SELECT id,area_name,town_name FROM  `post_code` where area_code = '{$area_code}') as post_code INNER JOIN shop ON shop.post_code = post_code.id{$where_in} ORDER BY op1 DESC, post_code ASC";

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
	 * タウンのショップを取得
	 */
	public function get_town_list($town_code, $ins = FALSE)
	{
		if (empty($town_code) OR strlen($town_code) != 9)
		{
			return array();
		}
		
		$where_in = $this->create_where_in('carrier', $ins, ' WHERE ');

		$sql = "SELECT shop.*,area_name,town_name FROM (SELECT id,area_name,town_name FROM  `post_code` where town_code = '{$town_code}') as post_code INNER JOIN shop ON shop.post_code = post_code.id{$where_in} ORDER BY op1 DESC, post_code ASC";

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
	 * 経度・緯度より周辺のショップを取得
	 */
	public function get_location($lat, $lng, $ins = FALSE, $km = 10, $limit = 100)
	{
		if (!$lat || !$lng) return array();
		
		$lat = (float)$lat;
		$lng = (float)$lng;
		
		$where_in = $this->create_where_in('carrier', $ins, ' WHERE ');
		
		$sql = "SELECT *, ( 6371 * acos( cos( radians('{$lat}') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('{$lng}') ) + sin( radians('{$lat}') ) * sin( radians( lat ) ) ) ) AS distance FROM shop{$where_in} HAVING distance < '{$km}' ORDER BY distance ASC LIMIT 0 , {$limit}";

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
	 * 管理 ショップ検索
	 */
	public function search($where = array(), $field = '*', $limit = 20, $offset = 0, $order = array(), $like = array())
	{
		$return = array();

		$this->db->select($field);

		// データの総数取得のため再利用
		$this->db->start_cache();
		$this->db->from('shop');

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
	
	
	
	/*
	 * 管理 関連ショップ検索
	 */
	public function search_relation($parents_id, $where = array(), $field = '*', $limit = 20, $offset = 0, $order = array(), $like = array())
	{
		$return = array();

		$this->db->select($field);

		$sql = '(SELECT * FROM  `shop` WHERE  `status_flag` =1) as shop LEFT JOIN (SELECT * FROM  `relation` WHERE  `parents_id` = '.(int)$parents_id.') as relation ON shop.id = relation.child_id';

		// データの総数取得のため再利用
		$this->db->start_cache();
		$this->db->from($sql, false);

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
			$this->db->order_by('parents_id', 'DESC');
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