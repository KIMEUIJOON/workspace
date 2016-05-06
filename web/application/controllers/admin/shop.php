<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Admin_Controller.php' );

class Shop extends Admin_Controller
{
	const FORM_RULES_NAME = 'shop_add';

	// 簡易検索ボックスキー
	public $search_keys = array(
		'name'		=> 'ショップ名',
		'id'		=> 'ショップID',
		'tel1'		=> '電話番号'
	);


	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('my/form_validation');
		$this->load->library('form_validation');
	}


	/*
	 * ショップ一覧表示
	 */
	public function view()
	{
		$this->load->view('admin/shop/view');
	}

	
	/*
	 * ショップ追加登録フォーム
	 */
	public function add_form()
	{
		$this->load->view('admin/shop/add');
	}

	/*
	 * 編集フォーム
	 */
	public function edit($id = '')
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		// ショップ情報取得
		$item = $this->Shop->read($id, '*', TRUE);
		
		if($item === FALSE)
		{
			$this->output->set_header("HTTP/1.0 200 " . STATUS_NG);
			$json['message'] = 'データベースエラーが発生し登録できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}

		// メンバー登録しているかチェック
		$this->load->model('Member_Model', 'Member');
		$member = $this->Member->is_registry($item['id']);
		
		if($member !== FALSE)
		{
			$item['username']	= $member['username'];
			$item['passwd']		= $member['passwd'];
			
			// オプション情報取得
			$this->load->model('Option_Model', 'Option');
			$item['option'] = $this->Option->read($item['id']);
		}
		
		// updateに必要なIDをhiddenにセット
		$this->id = $item['id'];

		$this->load->view('admin/shop/edit', $item);
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

		$data['lat'] = ($this->input->post('lat')) ? (float)$this->input->post('lat') : NULL;
		$data['lng'] = ($this->input->post('lng')) ? (float)$this->input->post('lng') : NULL;
		$data['counter'] = ($this->input->post('counter') != '') ? (int)$this->input->post('counter') : NULL;
		

		// トランザクション
		$this->db->trans_start();
		
			// データベース保存
			$result = $this->db->insert('shop', $data);

			// ステータス値が0以上の場合はメンバー生成
			if($data['status_flag'] > 0)
			{
				$this->create_member($this->db->insert_id());
			}
		
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

		$data['lat'] = ($this->input->post('lat')) ? (float)$this->input->post('lat') : NULL;
		$data['lng'] = ($this->input->post('lng')) ? (float)$this->input->post('lng') : NULL;
		$data['counter'] = ($this->input->post('counter') != '') ? (int)$this->input->post('counter') : NULL;
		
		// トランザクション
		$this->db->trans_start();
		
			$result = $this->db->update('shop', $data, array('id' => (int)$id));

			// ステータス値が0以上の場合はメンバー生成チェック
			if($data['status_flag'] > 0)
			{
				$this->create_member($id);
			}

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

		$item = $this->Shop->search($where, '*', $rows, $offset, $orders, $like);

		$prefecture = $this->config->item('prefecture');
		$carrier = $this->config->item('carrier');

		// 表示用にマスタや日時を変換
		foreach ($item['data'] as $key => $val)
		{
			$item['data'][$key]['carrier'] = $carrier[$val['carrier']];
			$item['data'][$key]['prefecture'] = (isset($prefecture[$val['prefecture']])) ? $prefecture[$val['prefecture']] : '';
		}

		$json['total']	= $item['count_all'];
		$json['rows']	= $item['data'];
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}


	/*
	 * メンバー登録処理
	 */
	private function create_member($shop_id)
	{
		$this->load->model('Member_Model', 'Member');
		
		// 一度既にメンバー登録している可能性もあるためチェック
		$result = $this->Member->is_registry($shop_id);
		
		if ($result !== FALSE)
		{
			// 既に処理済み
			return TRUE;
		}

		// ユーザIDとPASSWORDを生成
		$data = array();
		$data['id']			= $shop_id;
		$data['username']	= make_username($shop_id);
		$data['passwd']		= make_passwd();

		// データベース保存
		$result = $this->db->insert('member', $data);
		$result = $this->db->insert('option', array('shop_id' => $shop_id));
		$result = make_assets_path($shop_id);
		
		return TRUE;
	}


	/*
	 * クーポン詳細情報
	 */
	/*
	public function detail()
	{
		$id = $this->inpu->post('id');

		// IDが正しい整数かチェック
		if ( ! is_int0($id)) show_404();

		$item = $this->Coupon->read($id, '*', TRUE);

		if ($item === FALSE)
		{
			show_404();
			return;
		}

		$json['status'] = STATUS_SUCCESS;

		$json = $json + $item;

		$this->output->set_output(json_encode($json));
	}
	*/

	/*
	 * クーポン削除
	 */
	 /*
	public function del()
	{
		$json = array();
		$json['status'] = STATUS_ERROR;
		
		$result = $this->db->delete('coupon', array('id' => (int)$this->input->post('id'))); 
		
		if ($result !== TRUE OR $this->db->affected_rows() == 0)
		{
			$json['message'] = 'データベースエラーが発生し削除できませんでした';
		}
		else
		{
			$json['status'] = STATUS_SUCCESS;
		}
		
		$this->output->set_output(json_encode($json));
	}
	*/
}