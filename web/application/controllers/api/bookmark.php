<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Bookmark extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
	}
	
	/**
	 * ブックマーク登録
	 */
	public function add()
	{
		$view = array();
		$view['status'] = STATUS_NG;
		$view['message'] = '';
		
		$this->load->model('User_Model', 'User', TRUE);
		$this->load->model('Shop_Model', 'Shop');
		$this->load->model('Bookmark_Model', 'Bookmark');
		
		$shop_id = $this->input->get('code');
		$token = $this->input->get('token');
		
		// tokenとshop_idが正しいかチェック
		$flag1 = $this->User->read($token, 'token', TRUE);
		$flag2 = $this->Shop->read($shop_id, 'id', TRUE);
		
		if ($flag1 === FALSE OR $flag2 === FALSE)
		{
			$view['message'] = 'ブックマークに失敗しました';
			$this->load->view('api/common', $view);
			$this->User->error('ブックマークERROR! token:' . $token . ' shop_id:' . $shop_id);
			return;
		}
		
		// ブックマーク登録
		$flag = $this->Bookmark->add($token, $shop_id);
			
		if($flag)
		{
			$view['status'] = STATUS_OK;
		}
		else
		{
			$view['message'] = 'ブックマークに失敗しました';
		}
		
		$this->load->view('api/common', $view);
	}
	
	
	/**
	 * ブックマーク削除
	 */
	public function del()
	{
		$view = array();
		$view['status'] = STATUS_NG;
		$view['message'] = '';
		
		$this->load->model('Bookmark_Model', 'Bookmark', TRUE);
		
		$shop_id = $this->input->get('code');
		$token = $this->input->get('token');
		
		// ブックマーク登録されているかチェック
		$flag = $this->Bookmark->registry($token, $shop_id);
			
		if($flag)
		{
			$this->db->where('token', (string)$token);
			$this->db->where('shop_id', (int)$shop_id);
			$result = $this->db->delete('bookmark');
			
			if ($result)
			{
				$view['status'] = STATUS_OK;
			}
			else
			{
				$view['message'] = 'ブックマーク削除に失敗しました';
				$this->Bookmark->error('ブックマーク削除に失敗 token:' . $token . ' shop_id:' . $shop_id);
			}
		}
		else
		{
			$view['status'] = STATUS_OK;
		}
		
		$this->load->view('api/common', $view);
	}
	
	
	/**
	 * ブックマークリストを取得
	 */
	public function lists()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Bookmark_Model', 'Bookmark', TRUE);
		
		$this->shops = $this->Bookmark->lists($this->input->get('token'));

		$this->load->view('api/bookmark/lists', $view);
	}
}
