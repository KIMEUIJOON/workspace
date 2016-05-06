<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * エリアコードのカウント数を取得
	 */
	 /*
	public function area_count()
	{
		$this->db->select('area_code, count(area_code) as area_count', false);
		$this->db->from('shop');
		$this->db->join('post_code', 'shop.post_code = post_code.id', 'inner');
		$this->db->group_by("area_code"); 

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
	 * エリアの下にデータがあるか child
	 */
	public function area_child()
	{
		$this->db->select('area_code, count(area_code) as child', false);
		$this->db->from('post_code');
		$this->db->where('town_name != ""', null, false);
		$this->db->group_by("area_code"); 

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
	 * メールマガジンの未キュー追加のtaskを取得
	 */
	public function get_newsletter_add_task($limit)
	{
		$this->db->select('*');
		$this->db->from('newsletter_add');
		$this->db->where('status ', (int)0);
		$this->db->order_by('id', 'ASC');
		$this->db->limit($limit);

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
	 * メールマガジン送信完了チェックtaskを取得
	 */
	public function get_newsletter_check_task($limit)
	{
		$this->db->select('id');
		$this->db->from('newsletter_add');
		$this->db->where('status ', (int)1);
		$this->db->order_by('id', 'ASC');
		$this->db->limit($limit);

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
	 * メールマガジン送信完了 メールキューチェック
	 */
	public function newsletter_completed_check($add_id)
	{
		$this->db->from('newsletter_queue');
		$this->db->where('newsletter_add_id ', (int)$add_id);
		$this->db->where('status ', (int)0);

		if($this->db->count_all_results() > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	/**
	 * メールマガジン配信タスク取得
	 */
	public function get_newsletter_send_task($limit)
	{
		$this->db->select('*');
		$this->db->from('newsletter_queue');
		$this->db->where('status ', (int)0);
		$this->db->order_by('id', 'ASC');
		$this->db->limit($limit);

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