<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('my/upload');
	}

	public function image()
	{
		$view = array();
		$config = array();

		$this->config->load('my/image');

		$config['upload_path']		= ASSETS_TEMP_PATH;
		$config['allowed_types']	= 'gif|jpg|png|jpeg';
		$config['max_size']			= '5120'; // 5M
		$config['max_width']		= '0';
		$config['max_height']		= '0';
		$config['max_filename']		= '0';
		$config['encrypt_name']		= TRUE;

		$this->load->library('upload', $config);

		$rtn = $this->upload->do_upload($this->input->post('key'));

		if ($rtn === FALSE)
		{
			$view['message'] = $this->upload->display_errors('', '');
			$view['status'] = STATUS_NG;
		}
		else
		{
			$view = $this->upload->data();
			$view['status'] = STATUS_OK;
			$view['image_url'] = get_assets_temp_url($view['file_name']);
		}

		// 出力
        $this->output->set_header("Content-type: text/html; charset=UTF-8");
        $this->output->set_output(json_encode($view));
	}
	
	
	public function pdf()
	{
		$view = array();
		$config = array();

		$this->config->load('my/image');

		$config['upload_path']		= ASSETS_TEMP_PATH;
		$config['allowed_types']	= 'pdf';
		$config['max_size']			= '5120'; // 5M
		$config['max_filename']		= '0';
		$config['encrypt_name']		= TRUE;

		$this->load->library('upload', $config);

		$rtn = $this->upload->do_upload($this->input->post('key'));

		if ($rtn === FALSE)
		{
			$view['message'] = $this->upload->display_errors('', '');
			$view['status'] = STATUS_NG;
		}
		else
		{
			$view = $this->upload->data();
			$view['status'] = STATUS_OK;
			$view['pdf_url'] = get_assets_temp_url($view['file_name']);
			$view['pdf_name'] = $view['orig_name'];
		}

		// 出力
        $this->output->set_header("Content-type: text/html; charset=UTF-8");
        $this->output->set_output(json_encode($view));
	}
	
	
	public function csv()
	{
		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$view = array();
		$config = array();

		$view['status'] = STATUS_NG;

		// 出力
        $this->output->set_header("Content-type: text/html; charset=UTF-8");
        
        $this->config->load('my/shop');
		$this->config->load('my/import');
		$this->load->helper(array('url', 'form'));
		
		$import_key = time();

		$config['upload_path']		= IMPORT_PATH;
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
		

		
		$inserts = array();
		
			while (($data = fgetcsv_reg($fp)) !== FALSE)
			{
				mb_convert_variables("UTF-8", "SJIS-win", $data);
				
				if(count($data) != 17)
				{
					if(count($data) == 1 && $data[0] == '')
					{
						$num++;
						continue;
					}
					
					$view['message'] = "データが17列ありません。{$num}行目";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}
				
				if ($data[0] == '' OR $data[1] == '' OR $data[3] == '' OR $data[5] == '' OR $data[7] == '' OR $data[8] == '')
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
					$view['message'] = "都道府県が正しくありません {$num}行目 {$data[9]}";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}


				$val = array();
				$val['name'] 			= (string)$data[0];
				$val['post_code']		= (string)$data[1];
				$val['prefecture']		= (string)$prefecture_key;
				$val['address'] 		= (string)$data[3];
				$val['building'] 		= (string)$data[4];
				$val['tel1'] 			= (string)$data[5];
				$val['tel2']			= (string)$data[6];
				$val['hours'] 			= (string)$data[7];
				$val['holiday'] 		= (string)$data[8];
				
				$this->onoff_flag = true;

				$val['parking_flag']		= $this->checkOnOff($data[9]);
				$val['barrier_free_flag']	= $this->checkOnOff($data[10]);
				$val['kids_flag']		= $this->checkOnOff($data[11]);
				$val['classes_flag']	= $this->checkOnOff($data[12]);
				$val['payment_flag']	= $this->checkOnOff($data[13]);
				$val['repair_flag']		= $this->checkOnOff($data[14]);
				
				if($this->onoff_flag !== TRUE)
				{
					$view['message'] = "○以外な文字がふくまれています {$num}行目";
					$this->load->view('admin/import', $view);
					fclose($fp);
					return;
				}
				
				//$val['holiday'] 		= (string)$data[16];
				
				//$val['update_time']		= (int)$import_key;
				
				$inserts[] = $val;
				
				$num++;
			}
			
		fclose($fp);

		// トランザクション開始
		$this->db->trans_start();
			
			$num = 1;
			
			foreach($inserts as $value)
			{
				$flag = $this->db->insert('shop', $value);
			}

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->Coupon->error('インポートトランザクション中にエラーが発生しました ' . $this->db->_error_message());
		    $view['message'] = "トランザクションエラーが発生しインポートできませんでした";
		}
		else
		{
			$view['status'] = STATUS_OK;
			$view['message'] = 'インポート正常に完了しました';
		}
		
        $this->load->view('admin/import', $view);
	}
	
	
	private function checkOnOff($str)
	{
		if($str == '○')
		{
			return (int)1;
		}
		else if($str == '')
		{
			return (int)0;
		}
		else
		{
			$this->onoff_flag = false;
			return FALSE;
		}
	}
}
