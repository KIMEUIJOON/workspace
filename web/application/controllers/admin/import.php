<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Admin_Controller.php' );

class Import extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Shop_Model', 'Shop', TRUE);
		$this->load->model('Post_code_Model', 'Post');
		$this->load->model('Import_Model', 'Import');
		$this->config->load('my/import');
		$this->load->helper(array('url', 'form'));
		$this->load->library('form_validation');
		// 出力
        $this->output->set_header("Content-type: text/html; charset=UTF-8");
	}


	/*
	 * import フォーム
	 */
	public function index()
	{
		$this->load->view('admin/import');
	}


	public function csv()
	{
		$view = array();
		$config = array();

		$view['status'] = STATUS_NG;
        
        $this->config->load('my/shop');
		$this->config->load('my/import');
		$this->load->helper(array('url', 'form'));
		
		$import_key = time();

		$config['upload_path']		= IMPORT_SHOP_PATH;
		$config['allowed_types']	= 'csv';
		$config['max_size']			= '15360'; // 15M
		//$config['encrypt_name']		= TRUE;
		$config['file_name']		= $import_key . '.csv';

		$this->load->library('upload', $config);

		$rtn = $this->upload->do_upload('csvfile');

		if ($rtn === FALSE)
		{
			$view['message'] = $this->upload->display_errors('', '');
			$this->load->view('admin/import', $view);
			return;
		}
		
		$data = $this->upload->data();

		$fp = fopen($data['full_path'] , "r");
		
		$prefecture = $this->config->item('prefecture');
		
		$num = 1;
		
		if($this->config->item($this->input->post('carrier'), 'carrier') == FALSE)
		{
			$view['message'] = "キャリアが間違っています {$this->input->post('carrier')}";
			$this->load->view('admin/import', $view);
			return;
		}
		
		$carrier = $this->input->post('carrier');
		
		$inserts = array();
		
			while (($data = fgetcsv_reg($fp)) !== FALSE)
			{
				mb_convert_variables("UTF-8", "SJIS-win", $data);
				
				foreach($data as $k => $v)
				{
					$data[$k] = trim($v);
					
					if($k != 16 && $k != 5 && $k != 6 && stristr($v, '?') !== FALSE)
					{
						$view['message'] = "データが?が含まれています {$num}行目 {$v}";
						$this->load->view('admin/import', $view);
						fclose($fp);
						return;
					}
				}
				
				if(count($data) != 17)
				{
					if(count($data) == 1 && $data[0] == '')
					{
						$num++;
						continue;
					}
					
					$ary_count = count($data);
					
					$view['message'] = "データが17列ありません。{$num}行目 {$ary_count}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}
				
				//if ($data[0] == '' OR $data[1] == '' OR $data[3] == '' OR $data[5] == '' OR $data[7] == '' OR $data[8] == '')
				if ($data[0] == '' OR $data[1] == '' OR $data[3] == '')
				{
					$flag = false;
					// 全て空かチェック
					foreach($data as $ch)
					{
						if($ch)
						{
							$flag = true;
						}
					}
					// すべて空だった場合はスルー
					if($flag == false)
					{
						$num++;
						continue;
					}
					
					$view['message'] = "空値があります。{$num}行目";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}
				
				$prefecture_key = array_search($data[2], $prefecture);
				if ($prefecture_key === FALSE)
				{
					$view['message'] = "都道府県が正しくありません {$num}行目 {$data[2]}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}

				$data[1] = str_replace("〒", "", $data[1]);
				if($this->Post->read($data[1], 'id', true) === FALSE)
				{
					$view['message'] = "郵便番号が正しくありません {$num}行目 {$data[1]}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}

				$data[5] = str_replace("?", "", $data[5]);
				$data[6] = str_replace("?", "", $data[6]);
				if(($data[5] AND $this->form_validation->alpha_dash($data[5]) == FALSE) OR ($data[6] AND $this->form_validation->alpha_dash($data[6]) == FALSE))
				{
					$view['message'] = "電話番号が正しくありません {$num}行目 {$data[5]} {$data[6]}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}

				if(($data[5] && stristr($data[5], '-') === FALSE) OR ($data[6] && stristr($data[6], '-') === FALSE))
				{
					$view['message'] = "電話番号に-が入っていません {$num}行目 {$data[5]} {$data[6]}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}
				
				if(strpos($data[3], $data[2]) === 0)
				{
					$view['message'] = "住所に都道府県が入っています {$num}行目 {$data[2]}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}

				$val = array();
				$val['name'] 			= (string)$data[0];
				$val['carrier']			= (string)$carrier;
				$val['post_code']		= (string)$data[1];
				$val['prefecture']		= (string)$prefecture_key;
				$val['address'] 		= (string)$data[3];
				$val['building'] 		= (string)$data[4];
				$val['tel1'] 			= (string)$data[5];
				$val['tel2']			= (string)$data[6];
				$val['hours'] 			= (string)$data[7];
				$val['holiday'] 		= (string)$data[8];
				
				$this->onoff_flag = true;

				$val['parking_flag']		= $this->checkOnOff($data[9], true);
				$val['barrier_free_flag']	= $this->checkOnOff($data[10]);
				$val['kids_flag']		= $this->checkOnOff($data[11]);
				$val['classes_flag']	= $this->checkOnOff($data[12]);
				$val['payment_flag']	= $this->checkOnOff($data[13]);
				$val['repair_flag']		= $this->checkOnOff($data[14]);
				
				if($this->onoff_flag !== TRUE)
				{
					$view['message'] = "○×以外な文字がふくまれています {$num}行目";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}
				
				$val['check_url'] 		= (string)$data[16];

				$inserts[] = $val;
				
				$num++;
			}
			
		fclose($fp);

		// トランザクション開始
		$this->db->trans_begin();
			
			$num = 1;
			
			foreach($inserts as $value)
			{
				if($this->Import->shop_unique($value['post_code'], $value['name'], $value['carrier']) === FALSE)
				{
					$view['message'] = "同じ名前のショップが既に登録されています {$num}行目 {$value['post_code']} {$value['name']}";
					$this->load->view('admin/import', $view);
					$this->db->trans_rollback();
					return;
				}
				
				$flag = $this->db->insert(IMPORT_SHOP_TABLE_NAME, $value);
				
				$num++;
			}

		if ($this->db->trans_status() === FALSE)
		{
			$this->Import->error('インポートトランザクション中にエラーが発生しました ' . $this->db->_error_message());
		    $view['message'] = "トランザクションエラーが発生しインポートできませんでした";
		    $this->db->trans_rollback();
		}
		else
		{
			$view['status'] = STATUS_OK;
			$view['message'] = 'インポート正常に完了しました';
			$this->db->trans_commit();
		}
		
        $this->load->view('admin/import', $view);
	}
	
	
	private function checkOnOff($str, $parking = false)
	{
		if($str == '○')
		{
			return (int)1;
		}
		else if($str == '' || $str == '×' || $str == '無')
		{
			return (int)0;
		}
		else if($parking == true && ($str == '○(有料)' OR $str == '○（有料）'OR $str == '有料'))
		{
			return (int)2;
		}
		else
		{
			$this->onoff_flag = false;
			return FALSE;
		}
	}
}
