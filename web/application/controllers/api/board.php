<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Board extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('my/s_config');
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
	}
	
	/**
	 * レビュー一覧を取得
	 */
	public function lists()
	{
		$view = array();

		$this->load->model('Board_Model', 'Board', TRUE);
		
		$view = $this->Board->lists(BOARD_LIMIT, 0);
		$view['status'] = STATUS_OK;
		
		$view['next'] = 0;

		if($view['count'] > BOARD_LIMIT)
		{
			$view['next'] = 2;
		}
		
		$this->load->view('api/board/lists', $view);
	}
}
