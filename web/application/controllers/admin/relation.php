<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Admin_Controller.php' );

class Relation extends Admin_Controller
{
	// 簡易検索ボックスキー
	public $search_keys = array(
		'name'		=> 'ショップ名',
		'id'		=> 'ショップID',
		'tel1'		=> '電話番号'
	);


	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		
		$this->load->model('Relation_Model', 'Relation');
	}


	/*
	 * ショップ一覧表示
	 */
	public function view()
	{
		$this->load->view('admin/relation/view');
	}

	/*
	 * 親ショップ選択
	 */
	public function choice_parent($id = '')
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		// ショップ情報取得
		$item = $this->Shop->read($id, '*', TRUE);
		
		if($item === FALSE)
		{
			$json['message'] = '選択したショップ情報が取得できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// ステータスがメンバーかチェック
		if (($item['status_flag']>0) === false)
		{
			$json['message'] = $item['name'] . ' は未登録です';
			$this->output->set_output(json_encode($json));
			return;
		}

		$_SESSION['admin']['parents_id'] = $item['id'];
		$_SESSION['admin']['parents_name'] = $item['name'];
		
		// ショップ名前をセット
		$json['name'] = $item['name'];
		$json['status'] = STATUS_OK;
		$this->output->set_output(json_encode($json));
	}

	/*
	 * 更新
	 */
	public function update()
	{
		$json = array();

		$json['status'] = STATUS_NG;
		$errors = array();
		
		if ((isset($_SESSION['admin']['parents_id']) && $_SESSION['admin']['parents_id']) !== TRUE)
		{
			$json['message'] = '親会社情報が選択されていません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// ショップ情報取得
		$child = $this->Shop->read($this->input->post('child_id'), '*', TRUE);
		
		if($child === FALSE)
		{
			$json['message'] = '選択したショップ情報が取得できませんでした';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		// ステータスがメンバーかチェック
		if (($child['status_flag']>0) === false)
		{
			$json['message'] = $child['name'] . ' はメンバー未登録です';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$status = $this->input->post('status');
		
		$relation = $this->Relation->read($_SESSION['admin']['parents_id'], $child['id'], TRUE);
		
		if($relation)
		{
			if($status == 1)
			{
				$json['message'] = '既に登録されています';
				$this->output->set_output(json_encode($json));
				return;
			}
			else
			{
				$result = $this->db->delete('relation', array('parents_id' => $_SESSION['admin']['parents_id'], 'child_id' => $child['id'])); 
				
				if ($result !== TRUE OR $this->db->affected_rows() == 0)
				{
					$json['message'] = 'データベースエラーが発生し解除できませんでした';
					$this->output->set_output(json_encode($json));
					return;
				}
			}
		}
		else
		{
			if($status == 1)
			{
				$flag = $this->db->insert('relation', array('parents_id' => $_SESSION['admin']['parents_id'], 'child_id' => $child['id']));
				
				if(!$flag)
				{
					$json['message'] = 'データベースエラーが発生し登録できませんでした';
					$this->output->set_output(json_encode($json));
					return;
				}
			}
		}
		
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}

	/*
	 * ショップメンバー一覧をロード
	 */
	public function load()
	{
		$json = array();
		
		$page = ((int)$this->input->post('page')) ? (int)$this->input->post('page') : 1;
		$rows = ((int)$this->input->post('rows')) ? (int)$this->input->post('rows') : 20;
		$sort = ((string)$this->input->post('sort')) ? (string)$this->input->post('sort') : '';
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
		
		if ($sort)
		{
			$orders[$sort] = $order;
		}
		
		$offset = ($page-1)*$rows;

		if (isset($_SESSION['admin']['parents_id']) && $_SESSION['admin']['parents_id'])
		{
			$item = $this->Shop->search_relation($_SESSION['admin']['parents_id'], $where, '*', $rows, $offset, $orders, $like);
		}
		else
		{
			// 親が設定されていない時
			$where['status_flag'] = (int)1;
			$item = $this->Shop->search($where, '*', $rows, $offset, $orders, $like);
		}
		
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
}