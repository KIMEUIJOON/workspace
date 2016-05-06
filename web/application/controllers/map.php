<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function iphone()
	{
		$this->load->view('map/iphone');
	}
	
	public function android()
	{

	}
}
