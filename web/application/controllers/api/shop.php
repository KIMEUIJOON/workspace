<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Shop extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
	}
	
	/**
	 * ショップ詳細
	 */
	public function detail()
	{
		$view = array();
		$view['status'] = STATUS_NG;
		$view['message'] = '';
		
		$this->load->model('Shop_Model', 'Shop', TRUE);
		$this->load->model('Review_Model', 'Review');
		$this->load->model('Bookmark_Model', 'Bookmark');
		
		$shop_id = $this->input->get('code');
		$token = $this->input->get('token');
		
		$shop = $this->Shop->read($shop_id, '*', TRUE);
		$shop['review'] = $this->Review->counting($shop['id']); // レビュー集計
		$shop['bookmark'] = $this->Bookmark->registry($token, $shop['id']); // ブックマーク登録済みか
		
		$view['status'] = STATUS_OK;
		
		$this->load->view('api/shop/detail', array_merge($shop, $view));
	}
}
