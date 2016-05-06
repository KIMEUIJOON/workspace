<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Cron_model', 'Cron', TRUE);
		$this->config->load('my/shop');
		$this->config->load('my/cron');
	}
	
	/*
	public function area_count()
	{
		$this->db->trans_start();
		
			// カウントをすべて0にする
			$this->db->update('area_code', array('cnt' => (int)0));
		
			foreach ($this->Cron->area_count() as $val)
			{
				$this->db->where('id', (string)$val['area_code']);
				$this->db->update('area_code', array('cnt' => (int)$val['area_count']));
			}
		
		$this->db->trans_complete();
	}
	*/
	
	public function area_child()
	{
		$this->db->trans_start();
		
			// カウントをすべて0にする
			$this->db->update('area_code', array('child' => (int)0));
		
			foreach ($this->Cron->area_child() as $val)
			{
				$this->db->where('id', (string)$val['area_code']);
				$this->db->update('area_code', array('child' => (int)1));
			}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->Cron->error('area_child ERROR' . $this->db->_error_message());
		}
	}
	
	
	/**
	 * 住所から経度・緯度を取得
	 */
	public function geocode()
	{
		$this->load->model('Shop_model', 'Shop', TRUE);
		
		$prefecture = $this->config->item('prefecture');
		
		foreach ($this->Shop->geocode(10) as $val)
		{
			sleep(2);
			$updata = array();
			
			$address = $prefecture[$val['prefecture']] . $val['address'];
			$address = urlencode($address);
			$strRes = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
			
			$aryGeo = json_decode( $strRes, TRUE );
			
			if ( ! isset($aryGeo['results'][0]['geometry']['location']['lat']))
			{
				$updata['cron_flag'] = 0;
				$updata['status'] = $aryGeo['status'];
				$this->db->where('id', $val['id']);
				$this->db->update('shop2', $updata);
				continue;
			}

			$strLat = (string)$aryGeo['results'][0]['geometry']['location']['lat'];
			$strLng = (string)$aryGeo['results'][0]['geometry']['location']['lng'];
			
			$updata['cron_flag'] = 0;
			$updata['geocod_status'] = $aryGeo['status'];
			$updata['lat'] = (float)$strLat;
			$updata['lng'] = (float)$strLng;

			$this->db->where('id', $val['id']);
			$this->db->update('shop2', $updata);
		}
	}
	
	
	/**
	 * メールマガジンのキューに追加
	 */
	public function newsletter_queue()
	{
		$this->load->model('Newsletter_Model', 'Newsletter');
		
		foreach ($this->Cron->get_newsletter_add_task(NEWS_ADD_TASK_LIMIT) as $val)
		{
			$queue = array();
			
			// ショップを指定し購読者を取得
			foreach($this->Newsletter->findAll($val['shop_id']) as $user)
			{
				$queue[$user['id']]['subject']	= mb_convert_kana(str_replace(REPLACE_NAME, $user['name'], $val['subject']),"sKV");
				$queue[$user['id']]['body']		= mb_convert_kana(str_replace(REPLACE_NAME, $user['name'], $val['body']),"sKV");
				$queue[$user['id']]['email']	= $user['email'];
				$queue[$user['id']]['newsletter_add_id'] = $val['id'];
			}
			
			// トランザクション
			$this->db->trans_start();
			
				foreach($queue as $key => $data)
				{
					$this->db->insert('newsletter_queue', $data);
				}
				
				// statusを1に変更してキュー追加完了
				$this->db->update('newsletter_add', array('status' => (int)1), array('id' => (int)$val['id']));
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
			    $this->Cron->error('メールマガジンキュー追加 ERROR' . $this->db->_error_message());
			    break;
			}
		}
	}
	
	
	/**
	 * メールマガジンが送信完了かチェックしメールキューの情報を削除
	 */
	public function newsletter_check()
	{
		$this->load->model('Newsletter_Model', 'Newsletter');
		
		foreach ($this->Cron->get_newsletter_check_task(NEWS_CHECK_TASK_LIMIT) as $val)
		{
			if($this->Cron->newsletter_completed_check($val['id']) === FALSE) continue;
			
			// トランザクション
			$this->db->trans_start();
			
				// statusを2に変更してメール送信完了
				$this->db->update('newsletter_add', array('status' => (int)2), array('id' => (int)$val['id']));
				
				$this->db->delete('newsletter_queue', array('newsletter_add_id' => (int)$val['id'])); 
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
			    $this->Cron->error('メールマガジンキュー削除 ERROR' . $this->db->_error_message());
			    break;
			}
		}
	}
	
	
	/**
	 * メールマガジン配信
	 */
	public function newsletter_send()
	{
		// メール送信
		$this->load->library('smail');

		for($i = 0; $i < NEWS_SENDMAIL_LOOP_LIMIT; $i++)
		{
			$tasks = $this->Cron->get_newsletter_send_task(NEWS_SENDMAIL_LIMIT);
			
			if( ! $tasks) break;
			
			// 全てstatusを1にして配信済みにする 確実な重複配信を避けるため
			foreach ($tasks as $val)
			{
				// トランザクション
				$this->db->trans_start();
				
					// statusを1に変更
					$this->db->update('newsletter_queue', array('status' => (int)1), array('id' => (int)$val['id']));
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE)
				{
				    $this->Cron->error('メールマガジン配信 ERROR' . $this->db->_error_message());
				    break;
				}
			}
			
			$this->smail->smtp_keep();
			
			// メール送信
	    	$this->smail->from(MAIL_ADMIN, SITE_NAME);
	    	
			foreach ($tasks as $val)
			{
		    	$this->smail->to($val['email']);
		    	$this->smail->subject($val['subject']);
		    	$this->smail->message($val['body']);
		    	
		    	$flag = $this->smail->send();
		    	
		    	if ($flag !== TRUE)
		    	{
					// メール送信エラーログに記録
	    			$this->Cron->error('メールマガジン送信エラー : ' . $this->smail->get_error_message());
		    	}
		    }
		    
		    $this->smail->smtp_close();
		    
		    sleep(1);
		}
	}
}
