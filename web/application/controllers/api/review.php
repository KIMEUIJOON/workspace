<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Review extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * レビュー追加
	 */
	public function add()
	{
		$this->config->load('my/s_config');
		
		$json = array();
		$json['status'] = STATUS_NG;
		$json['message'] = '';
		
		$this->load->model('Review_Model', 'Review', TRUE);

		$errors = array();
		
		$shop_id	= $this->input->post('id');
		$name		= $this->input->post('name');
		$body		= $this->input->post('body');
		$score		= $this->input->post('score');
		
		$this->load->model('Shop_Model', 'Shop');


		$shop = $this->Shop->read($shop_id, 'id, op7, review_flag', TRUE);
		
		if($shop ===  FALSE)
		{
			$json['message'] = 'ショップ情報が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// レビューが拒否設定されていないかチェック
		if($shop['op7'] && $shop['review_flag'] == 0)
		{
			$json['message'] = '現在レビューを受け付けていません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		
		if(!$name || !trim($name))
		{
			$errors[] = 'ハンドルネームを入力してください';
		}
		
		if(!$body OR mb_strlen($body) < 10)
		{
			$errors[] = 'レビューの内容は10文字以上が必要です。';
		}
		elseif(mb_strlen($body) > REVIEW_MAXLENGTH)
		{
			$errors[] = 'レビューの内容は'.REVIEW_MAXLENGTH.'文字以内で入力してください';
		}
		
		if($score < 1 OR $score > 5)
		{
			$errors[] = '評価が正しく選択されていません';
		}
		
		if($errors)
		{
			$json['message'] = implode("\n", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// レビュー登録
		$flag = $this->Review->add($shop_id, $name, $body, $score);
			
		if($flag)
		{
			$json['status'] = STATUS_OK;
		}
		else
		{
			$json['message'] = 'レビューの投稿に失敗しました';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	
	/**
	 * レビュー一覧を取得
	 */
	public function lists()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Review_Model', 'Review', TRUE);
		
		$this->reviews = $this->Review->lists($this->input->get('code'));

		//$this->load->view('api/review/lists', $view);
	}
}
