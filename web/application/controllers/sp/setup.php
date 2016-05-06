<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Setup extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('my/s_config');
		$this->load->helper(array('form'));
	}
	
	/**
	 * キャリア絞り込み設定
	 */
	public function carrier()
	{
		$this->load->view('sp/setup/carrier');
	}
	
	
	/**
	 * メールマガジン
	 */
	public function newsletter($id = '')
	{
		if(!$id) show_404();

		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$shop = $this->Shop->read($id, 'id, name, op4', TRUE);
		
		if(!$shop || !$shop['op4'])
		{
			show_404();
		}
		
		$this->load->view('sp/setup/newsletter', $shop);
	}
	
	
	/**
	 * レビュー
	 */
	public function review($id = '')
	{
		if(!$id) show_404();

		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$shop = $this->Shop->read($id, 'id, name, op7, review_flag', TRUE);
		
		if(!$shop)
		{
			show_404();
		}
		
		/*
		if($shop['op7'] && $shop['review_flag'] == 0)
		{
			show_404();
		}
		*/
		
		$this->load->view('sp/setup/review', $shop);
	}
}
