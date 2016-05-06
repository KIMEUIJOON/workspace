<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Place extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 地域リストを取得
	 */
	public function area()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->areas = array();
		
		$this->load->model('Post_code_Model', 'Post', TRUE);
		
		$pf = $this->input->get('pf');
		
		$this->areas = $this->Post->get_area_list($pf, $this->get_carrier());
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
		$this->load->view('api/place/area', $view);
	}
	
	/**
	 * タウンリストを取得
	 */
	public function town()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Post_code_Model', 'Post', TRUE);
		
		$area = $this->input->get('area');
		
		$this->towns = $this->Post->get_town_list($area, $this->get_carrier());
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
		$this->load->view('api/place/town', $view);
	}
}
