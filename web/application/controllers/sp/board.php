<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Board extends User_Controller
{
	public $appli = false;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('my/s_config');
	}
	
	
	/**
	 * フォーム
	 */
	public function form()
	{
		$this->load->helper(array('form'));
		$this->load->view('sp/board/form');
	}
	
	/**
	 * 追加
	 */
	public function add()
	{
		$json = array();
		$json['status'] = STATUS_NG;
		$json['message'] = '';
		
		$this->load->model('Board_Model', 'Board', TRUE);

		$errors = array();
		
		$name		= $this->input->post('name');
		$body		= $this->input->post('body');
		
		if(!$name || !trim($name))
		{
			$errors[] = 'ハンドルネームを入力してください';
		}
		elseif(mb_strlen($name) > 20)
		{
			$errors[] = 'ハンドルネームは20文字以内で入力してください';
		}
		
		if(!$body OR mb_strlen($body) < 10)
		{
			$errors[] = 'コメントは10文字以上が必要です。';
		}
		elseif(mb_strlen($body) > BOARD_MAXLENGTH)
		{
			$errors[] = 'コメントは140文字以内で入力してください';
		}

		if($errors)
		{
			$json['message'] = implode("\n", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$flag = $this->Board->add($name, $body);
			
		if($flag)
		{
			$json['status'] = STATUS_OK;
			$json['name'] = $name;
			$json['body'] = $body;
			$json['entry_time'] = date("Y/m/d");
		}
		else
		{
			$json['message'] = '投稿に失敗しました';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	
	/**
	 * 一覧を取得
	 */
	public function lists()
	{
		// アプリからのアクセスかチェック
		$type = $this->input->get('type');
		
		if($type == 'app')
		{
			$this->appli = true;
		}
		
		$page = $this->input->get('page');
		
		($page == false) and $page = 1;
		if(is_int0($page) === FALSE) show_404();
		
		$view = array();
		
		$offset = ($page - 1) * BOARD_LIMIT;
		
		$this->load->model('Board_Model', 'Board', TRUE);
		
		$view = $this->Board->lists(BOARD_LIMIT, $offset);

		$view['next'] = false;
		$view['page'] = $page;
		
		if($view['count'] > (BOARD_LIMIT + $offset))
		{
			$view['next'] = $page+1;
		}

		$this->load->view('sp/board/lists', $view);
	}
}
