<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * DB connect チェック
	 */
	public function is_connect($return = FALSE)
	{
		if ( ! $this->db->conn_id)
		{
			if ($return) return FALSE;

			$this->load->helper('url');
			redirect('err/db', 'location');
		}

		if ($return) return TRUE;
	}
	
	
	/**
	 * ユニーク制約などの違反時に指定の値で上書きする
	 */
	public function insert_duplicate($table, $data, $primary)
	{
		$str = $this->db->insert_string($table, $data);
		
		unset($data[$primary]);
		
		$update = '';
		
		foreach ($data as $key => $val)
		{
			if($update) $update .= ',';
			
			$update .= $key . '=' . $this->db->escape($val);
		}
		
		$sql = $str . ' ON DUPLICATE KEY UPDATE ' . $update;
		
		return $this->db->query($sql);
	}


	/**
	 * 配列からwhere in を生成
	 */
	public function create_where_in($name, $ins = FALSE, $pre = '', $next = '')
	{
		$where_in = '';
		
		if($ins && is_array($ins))
		{
			$add = '';
			
			foreach($ins as $v)
			{
				if($add) $add .= ',';
				
				$add .= "'" . $v . "'";
			}
			
			$where_in = $pre . "{$name} in ({$add})" . $next;
		}
		
		return $where_in;
	}
	

	/**
	 * エラーメッセージを保存
	 */
	public function error($msg, $url = FALSE)
	{
		$datas = array(	'message'		=> (string)$msg,
						'error_time'	=> (int)time());
		
		if ($url)
		{
			$datas['url'] = (string)$this->input->server('REQUEST_URI');
		}
		
		$this->db->insert('log_error', $datas);
	}
}