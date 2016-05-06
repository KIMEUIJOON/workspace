<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Lists extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * 地域のショップリストを取得
	 */
	public function area()
	{
		$view = array();

		$this->load->model('Shop_Model', 'Shop', TRUE);
		$this->load->model('Post_code_Model', 'Post');
		
		$code = $this->input->get('code');
		
		$this->area = $this->Post->get_area($code);
		
		if(!$this->area) show_404();
		
		$view['prefecture'] = $this->config->item(substr($code, 0, 2), 'prefecture');
		
		$this->shops = $this->Shop->get_area_list($code, $this->get_carrier());
		
		$view['total'] = count($this->shops);

		// imageを設定

		$this->load->view('sp/lists/area', $view);
	}
	
	/**
	 * タウンのショップリストを取得
	 */
	public function town()
	{
		$view = array();

		$this->load->model('Shop_Model', 'Shop', TRUE);
		$this->load->model('Post_code_Model', 'Post');
		
		$view['prefecture'] = $this->config->item('prefecture');
		
		$code = $this->input->get('code');
		
		$this->town = $this->Post->get_town($code);
		
		if(!$this->town) show_404();

		$this->area = $this->Post->get_area(substr($code, 0, 5));
		
		$this->shops = $this->Shop->get_town_list($code, $this->get_carrier());
		
		$view['total'] = count($this->shops);
		
		// imageを設定
		
		$this->load->view('sp/lists/town', $view);
	}
}
