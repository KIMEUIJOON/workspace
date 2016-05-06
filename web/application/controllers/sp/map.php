<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Map extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('my/s_config');
		$this->load->helper(array('form'));
	}
	
	
	/**
	 * マップ表示
	 */
	public function index()
	{
		if($this->input->get('type') == 'iphone')
		{
			$this->load->view('sp/map/map-iphone');
		}
		else
		{
			$this->load->view('sp/map/map');
		}
	}
}
