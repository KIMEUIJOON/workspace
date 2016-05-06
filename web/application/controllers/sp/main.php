<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Main extends User_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Board_Model', 'Board', sTRUE);
		$this->config->load('my/s_config');
	}


	/**
	 * スマフォ indexページ
	 */
	public function index()
	{
		$view = array();

		// 掲示板情報を取得
		$view = $this->Board->lists(BOARD_LIMIT, 0);

		$view['next'] = false;

		if($view['count'] > BOARD_LIMIT)
		{
			$view['next'] = 2;
		}

		$this->load->view('sp/index', $view);
	}
}
