<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Admin_Controller.php' );

class Parents extends Admin_Controller
{
	const FORM_RULES_NAME = 'parents_add';

	// 簡易検索ボックスキー
	public $search_keys = array(
		'name'		=> '会社名',
		'id'		=> '会社ID',
		'tel1'		=> '電話番号'
	);


	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('my/form_validation');

		$this->load->library('form_validation');
		
		$this->load->model('Parents_Model', 'Parents');
	}


	/*
	 * 一覧表示
	 */
	public function view()
	{
		$this->load->view('admin/parents/view');
	}

	
	/*
	 * 追加登録フォーム
	 */
	public function add_form()
	{
		$this->load->view('admin/parents/add');
	}

	/*
	 * 編集フォーム
	 */
	public function edit($id = '')
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		// 会社情報取得
		$item = $this->Parents->read($id, '*', TRUE);
		
		if($item === FALSE)
		{
			$this->output->set_header("HTTP/1.0 200 " . STATUS_NG);
			$json['message'] = '会社情報が取得できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// updateに必要なIDをhiddenにセット
		$this->id = $item['id'];

		$this->load->view('admin/parents/edit', $item);
	}
	
	
	/*
	 * ショップ追加登録
	 */
	public function add()
	{
		$json = array();
		$json['status'] = STATUS_NG;

		$this->form_validation->set_rules($this->config->item(self::FORM_RULES_NAME));

		if ($this->form_validation->run() !== TRUE)
		{
			$json['message'] = validation_errors('<p class="error">', '</p>');
			$this->output->set_output(json_encode($json));
			return;
		}

		$data = $this->form_validation->get_db_data(self::FORM_RULES_NAME);
		
		// トランザクション
		$this->db->trans_start();
		
			$result = $this->db->insert('parents', $data);

			$data['username']	= make_username($this->db->insert_id());
			$data['passwd']		= make_passwd();

			$result = $this->db->update('parents', $data, array('id' => (int)$this->db->insert_id()));
			
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$json['message'] = 'データベースエラーが発生し登録できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		else
		{
			$json['status'] = STATUS_OK;
			$json['username'] = $data['username'];
			$json['passwd'] = $data['passwd'];
			$json['name'] = $data['name'];
		}
		
		$this->output->set_output(json_encode($json));
	}


	/*
	 * 更新
	 */
	public function update()
	{
		$json = array();
		$json['status'] = STATUS_NG;

		$this->form_validation->set_rules($this->config->item(self::FORM_RULES_NAME));

		if ($this->form_validation->run() !== TRUE)
		{
			$json['message'] = validation_errors('<p class="error">', '</p>');
			$this->output->set_output(json_encode($json));
			return;
		}

		// ID
		$id = $this->input->post('id');

		$data = $this->form_validation->get_db_data(self::FORM_RULES_NAME);

		// トランザクション
		$this->db->trans_start();
		
			$result = $this->db->update('parents', $data, array('id' => (int)$id));

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$json['message'] = 'データベースエラーが発生し更新できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		else
		{
			$json['status'] = STATUS_OK;
		}

		$this->output->set_output(json_encode($json));
	}

	/*
	 * ショップ一覧をロード
	 */
	public function load()
	{
		$json = array();
		
		$page = ((int)$this->input->post('page')) ? (int)$this->input->post('page') : 1;
		$rows = ((int)$this->input->post('rows')) ? (int)$this->input->post('rows') : 20;
		$sort = ((string)$this->input->post('sort')) ? (string)$this->input->post('sort') : 'id';
		$order = ((string)$this->input->post('order')) ? (string)$this->input->post('order') : 'desc';

		$search = $this->input->post('search');

		$like = array();
		$where = array();
		
		// 簡易キーワード検索 likeセット
		if (is_array($search))
		{
			foreach ($search as $key => $value)
			{
				if ( ! isset($this->search_keys[$key])) continue;
				if (!$value) continue;
					
				if($key == 'id')
				{
					$where['id'] = (int)$value;
					continue;
				}
				
				foreach (parse_search_keyword($value) as $word)
				{
					$like[] = array($key, (string)$word, '');
				}
			}
		}

		$orders = array();
		$orders[$sort] = $order;

		$offset = ($page-1)*$rows;

		$item = $this->Parents->search($where, '*', $rows, $offset, $orders, $like);

		$prefecture = $this->config->item('prefecture');

		// 表示用にマスタや日時を変換
		foreach ($item['data'] as $key => $val)
		{
			$item['data'][$key]['prefecture'] = (isset($prefecture[$val['prefecture']])) ? $prefecture[$val['prefecture']] : '';
		}

		$json['total']	= $item['count_all'];
		$json['rows']	= $item['data'];
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}

	/*
	 * 親ショップ選択
	 */
	public function choice($id = '')
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		// 情報取得
		$item = $this->Parents->read($id, '*', TRUE);
		
		if($item === FALSE)
		{
			$json['message'] = '選択した親会社情報が取得できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}

		$_SESSION['admin']['parents_id'] = $item['id'];
		$_SESSION['admin']['parents_name'] = $item['name'];

		$json['status'] = STATUS_OK;
		$this->output->set_output(json_encode($json));
	}
}