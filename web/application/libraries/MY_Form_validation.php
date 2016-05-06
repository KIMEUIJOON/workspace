<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/libraries/EX_Form_validation.php';

class MY_Form_validation extends EX_Form_validation
{
	public function __construct($rules = array())
	{
		parent::__construct($rules);
	}


	/**
	 * ユーザIDチェック
	 */
	function username_check($str, $val)
	{
		if (preg_match(ID_PATTERN, $str))
		{
			if ($val == 'coupon')
			{
				$check = $this->CI->Coupon->is_unique($str, 'username', $this->CI->input->post('id'));
			}
			else if ($val == 'admin')
			{
				$check = $this->CI->Administrator->is_unique($str, 'username', $this->CI->input->post('id'));
			}
			
			if ($check !== TRUE)
			{
				$this->set_message('username_check', 'このログインIDは既に使用されています。');
				return FALSE;
			}
			
			return TRUE;
		}
		else
		{
			$this->set_message('username_check', ID_ERROR_MSG);
			return FALSE;
		} 
	}
	
	/**
	 * パスワード形式チェック
	 */
	function passwd_check($str)
	{
		if (preg_match(PASS_PATTERN, $str))
		{
			if((string)$str === (string)$this->CI->input->post('username'))
			{
				$this->set_message('passwd_check', 'ユーザIDと同じ文字をパスワードに指定することはできません。');
				return FALSE;
			}
			
			return TRUE;
		}
		else
		{
			$this->set_message('passwd_check', PASS_ERROR_MSG);
			return FALSE;
		} 
	}
}