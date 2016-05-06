<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Board_Model extends MY_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/*
	 * 追加
	 */
	public function add($name, $body)
	{
		$data = array(
						'name' 			=> (string)$name,
						'body' 			=> (string)$body,
						'entry_time'	=> (int)time(),
						'ipaddress'		=> (string)$this->input->ip_address());
		
		$flag = $this->db->insert('board', $data);
		
		if($flag === FALSE)
		{
			$this->error('掲示板追加エラー ' . $this->db->_error_message());
			return FALSE;
		}
		
		return TRUE;
	}
	

	/**
	 * 一覧を取得
	 */
	public function lists($limit = 50, $offset = 0)
	{
		$this->db->select('id, name, body, entry_time');
		
		// データの総数取得のため再利用
		$this->db->start_cache();
		
		$this->db->from('board');
		$this->db->where('status', (int)1);
		
		$this->db->stop_cache();
		
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();

		$return['data'] = array();
		$return['count'] = $this->db->count_all_results();

		$this->db->flush_cache(); // キャッシュクリア
		
		if ($query->num_rows() > 0)
		{
			$return['data'] =  $query->result_array();
		}
		
		return $return;
	}
}