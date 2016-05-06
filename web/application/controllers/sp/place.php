<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Place extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * 都道府県一覧を表示
	 */
	public function pref()
	{
		$this->load->view('sp/place/pref');
	}
	
	/**
	 * 地域リストを取得
	 */
	public function area()
	{
		$this->areas = array();

		$this->load->model('Post_code_Model', 'Post', TRUE);

		$pf = $this->input->get('pf');

		$this->prefecture = $this->config->item($pf, 'prefecture');

		if(!$this->prefecture) show_404();
		
		$this->areas = $this->Post->get_area_list($pf, $this->get_carrier());

		$this->load->view('sp/place/area');
	}
	
	/**
	 * タウンリストを取得
	 */
	public function town()
	{
		$this->towns = array();
		
		$this->load->model('Post_code_Model', 'Post', TRUE);
		
		$code = $this->input->get('area');
		
		$this->area = $this->Post->get_area($code);
		
		if(!$this->area) show_404();
		
		//戻るURLを生成
		$query = $this->input->get();
		unset($query['area']);
		$this->area['prev_url'] = site_url('sp/place/area') . '?' . http_build_query(array('pf' => substr($code, 0, 2)) + $query);

		$this->towns = $this->Post->get_town_list($code, $this->get_carrier());

		$this->load->view('sp/place/town');
	}
}
