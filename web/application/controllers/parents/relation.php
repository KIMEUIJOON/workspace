<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// 複数管理
class Relation extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		if (!$_SESSION['parents']['id'])
		{
				// ログインページへリダイレクト
				redirect('parents/login?' . QUERY_KEY_SESSION_ERROR. '=' . 1, 'location');
		}
		
		$this->load->model('Relation_Model', 'Relation');
		
		$this->load->library('form_validation');
	}


	/**
	 * 関連ショップ一覧
	 */
	public function select()
	{
		$this->load->view('parents/relation/select');
	}


	/**
	 * 関連ショップ情報ロード
	 */
	public function load()
	{
		$json = array();
		
		// 関連ショップを取得
		$item = $this->_get_relation_shop();
		
		$prefecture = $this->config->item('prefecture');
		$carrier = $this->config->item('carrier');

		// 表示用にマスタや日時を変換
		foreach ($item as $key => $val)
		{
			$item[$key]['carrier'] = $carrier[$val['carrier']];
			$item[$key]['prefecture'] = (isset($prefecture[$val['prefecture']])) ? $prefecture[$val['prefecture']] : '';
			$item[$key]['choice'] = ($_SESSION['member']['shop_id'] == $val['id']) ? 1 : '';
		}

		$json['total']	= count($item);
		$json['rows']	= $item;
		$json['status'] = STATUS_OK;
		
		$this->output->set_output(json_encode($json));
	}


	/*
	 * 編集ショップ選択
	 */
	public function choice()
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		// ショップ情報取得
		$item = $this->Shop->read($this->input->post("shop_id"), '*', TRUE);
		
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

		// 選択したショップが正しい子ショップか確認
		$relation = $this->Relation->read($_SESSION['parents']['id'], $item['id'], TRUE);

		if (!$relation)
		{
			$json['message'] = '選択したショップと正しい関連にありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$_SESSION['member']['shop_id'] = $item['id'];
		$_SESSION['member']['shop_name'] = $item['name'];

		$json['status'] = STATUS_OK;
		$this->output->set_output(json_encode($json));
	}
	
	
	/**
	 * 一括設定
	 */
	public function setting()
	{
		$this->load->view('parents/relation/setting');
	}
	
	
	/*
	 * クーポン一括更新
	 */
	public function coupon_body_update()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$errors = array();
		$coupon_date = 0;
		
		if($this->input->post('coupon_date'))
		{
			$coupon_date = strtotime($this->input->post('coupon_date'));
			
			if($coupon_date === FALSE)
			{
				$errors[] = '公開終了日時が正しくありません';
			}
		}
		
		if(mb_strlen($this->input->post('coupon_body'), 'UTF-8') > COUPON_LENGTH + 20) // 20はjqueryのlenghtと許容誤差
		{
			$errors[] = 'クーポン情報は' . COUPON_LENGTH . '文字以内で入力してください';
		}

		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$data = array();
		$data['coupon_body'] = (string)$this->input->post('coupon_body');
		$data['coupon_date'] = (int)$coupon_date;
		
		
		// 関連ショップを取得
		$shops = $this->_get_relation_shop();
		
		// トランザクション
		$this->db->trans_start();
		
			foreach ($shops as $shop)
			{
				$result = $this->db->update('shop', $data, array('id' => (int)$shop['id']));
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
	 * キャンペーン内容一括更新
	 */
	public function campaign_body_update()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$errors = array();
		$campaign_date = 0;
		
		if($this->input->post('campaign_date'))
		{
			$campaign_date = strtotime($this->input->post('campaign_date'));
			
			if($campaign_date === FALSE)
			{
				$errors[] = '公開終了日時が正しくありません';
			}
		}

		if(mb_strlen($this->input->post('campaign_body'), 'UTF-8') > CAMPAIGN_BODY_LENGTH + 20) // 20はjqueryのlenghtと許容誤差
		{
			$errors[] = 'キャンペーン内容は' . CAMPAIGN_BODY_LENGTH . '文字以内で入力してください' . mb_strlen($this->input->post('campaign_body'), 'UTF-8');
		}
		
		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}

		$data = array();
		$data['campaign_body'] = (string)$this->input->post('campaign_body');
		$data['campaign_date'] = (int)$campaign_date;
		
		// 関連ショップを取得
		$shops = $this->_get_relation_shop();
		
		// トランザクション
		$this->db->trans_start();
		
			foreach ($shops as $shop)
			{
				$result = $this->db->update('shop', $data, array('id' => (int)$shop['id']));
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
	 * キャンペーン概要一括更新
	 */
	public function campaign_update()
	{
		$json = array();

		$json['status'] = STATUS_NG;

		$errors = array();
		$campaign_date = 0;
		
		if($this->input->post('campaign_date'))
		{
			$campaign_date = strtotime($this->input->post('campaign_date'));
			
			if($campaign_date === FALSE)
			{
				$errors[] = '公開終了日時が正しくありません';
			}
		}

		if(mb_strlen($this->input->post('campaign'), 'UTF-8') > CAMPAIGN_LENGTH + 5) // 5はjqueryのlenghtと許容誤差
		{
			$errors[] = 'キャンペーン概要は' . CAMPAIGN_LENGTH . '文字以内で入力してください';
		}

		if($errors)
		{
			$json['message'] = implode("<br />", $errors);
			$this->output->set_output(json_encode($json));
			return;
		}

		$data = array();
		$data['campaign'] = (string)$this->input->post('campaign');
		$data['campaign_date'] = (int)$campaign_date;
		
		// 関連ショップを取得
		$shops = $this->_get_relation_shop();
		
		// トランザクション
		$this->db->trans_start();
		
			foreach ($shops as $shop)
			{
				$result = $this->db->update('shop', $data, array('id' => (int)$shop['id']));
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
	 * 関連ショップ情報取得
	 */
	private function _get_relation_shop()
	{
		// 関連ショップを取得
		$item = $this->Relation->get_child($_SESSION['parents']['id']);
		
		return $item;
	}
}