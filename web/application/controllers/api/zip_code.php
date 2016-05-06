<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zip_code extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 郵便番号からアドレスを返す
	 */
	public function address()
	{
		$json = array();
		$json['status'] = STATUS_NG;
		
		$post_code = $this->input->post('code');
		$post_flag = FALSE;
		
		// 郵便番号チェック
		if (preg_match("/^\d{3}\-\d{4}$/", $post_code))
		{
		    $post_flag = TRUE;
		}
		else
		{
			if (preg_match("/^\d{7}/", $post_code))
			{
			    $post_flag = TRUE;
			    $post_code = substr($post_code, 0, 3) . '-' . substr($post_code, 3);
			}
		}
		
		if ($post_flag !== TRUE)
		{
			$json['message'] = '郵便番号の形式が正しくありません';
			$this->output->set_output(json_encode($json));
			return;
		}
		
		$this->load->model('Post_code_Model', 'Post', TRUE);
		
		$post = $this->Post->read($post_code, '*', TRUE);
		
		if($post)
		{
			$json['status'] = STATUS_OK;
			$json['prefecture'] = $post['prefecture'];
			$json['address'] = $post['area_name'] . $post['town_name'];
		}
		else
		{
			$json['message'] = '郵便番号が正しくありません';
		}

		$this->output->set_output(json_encode($json));
	}
}
