<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ex_Form_validation extends CI_Form_validation
{
	public $CI;

	public function __construct($rules = array())
	{
		parent::__construct($rules);

		$this->CI =& get_instance();

		$this->CI->load->helper('form');

		$this->set_error_delimiters('', '');
	}


	/**
	 * データベースにinsert/updateするデータを取得
	 *
	 * @access	public
	 * @param	string	validation_rules設定ファイルの連想配列名
	 * @return	array
	 */
	public function get_db_data($conf_name)
	{
		$data = array();

		foreach ($this->CI->config->item($conf_name) as $value)
		{
			if (isset($value['type']) !== TRUE)	continue;

			switch ($value['type'])
			{
				case 'string':
					$data[$value['field']] = (string) $this->CI->input->post($value['field']);
					break;

				case 'int':
					$data[$value['field']] = (int) $this->CI->input->post($value['field']);
					break;

				case 'bool':
					$data[$value['field']] = (bool) $this->CI->input->post($value['field']);
					break;

				case 'float':
					$data[$value['field']] = (float) $this->CI->input->post($value['field']);
					break;
					
				default:
					$data[$value['field']] = $this->CI->input->post($value['field']);
					break;
			}
		}

		return $data;
	}


	/**
	 * フォーム初期値データ設定 (編集画面の初期値に使用) 
	 * set_valueで取得できるようにpostdataにセット
	 *
	 * @access  public
	 * @param	array
	 * @return  string
	 */
	public function set_default_postdata($data = array(), $config = array())
	{
		if (count($_POST) !== 0) return;
		
		if (count($config) !== 0)
		{
			foreach ($config as $rules)
			{
				if (isset($data[$rules['field']]))
				{
					$row = $data[$rules['field']];
					
					if (is_array($row))
					{
						foreach ($row as $val)
						{
							$this->_field_data[$rules['field']]['postdata'][] = $val;
						}
					}
					else
					{
						$this->_field_data[$rules['field']]['postdata'] = $row;
					}
				}
			}
		}
		else
		{
			foreach ($data as $field => $row)
			{
				if (is_array($row))
				{
					foreach ($row as $val)
					{
						$this->_field_data[$field]['postdata'][] = $val;
					}
				}
				else
				{
					$this->_field_data[$field]['postdata'] = $row;
				}
			}
		}
	}


	/**
	 * エラーメッセージをJSONでreturn
	 *
	 * @access	public
	 * @param	string	validation_rules設定ファイルの連想配列名
	 * @return	string	JSON
	 */
	public function error_message_json($conf_name)
	{
		$json = array();
		$json['status'] = STATUS_ERROR;
		$json['errors'] = array();
		
		foreach ($this->CI->config->item($conf_name) as $value)
		{
			$msg = form_error($value['field']);

			if ($msg)
			{
				$json['errors'][$value['field']] = $msg;
			}
		}

		$this->CI->output->set_output(json_encode($json));
	}


	/**
	 * エラーがあるか確認
	 */
	public function is_error()
	{
		if (count($this->_error_array))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * Valid Email オーバーライド
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_email($str)
	{
		//return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
		return ( ! preg_match("/^([a-z0-9\+_\-]+)([\.a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}


	/**
	 * 連続空白文字や前後の空白文字を削除
	 *
	 * @access  public
	 * @param	string
	 * @return  string
	 */
	public function space_beautify($str)
	{
		if(!$str) return '';

		$str = str_replace('　', ' ', $str);

		return trim(preg_replace("/[ ]+/", " ", $str));
	}


	/**
	 * 制御文字が含まれているかチェック
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function cntrl($str)
	{
		return (preg_match('/\A[[:^cntrl:]]+\z/u', $str) == 0) ? FALSE : TRUE;
	}


	/**
	 * 改行以外の制御文字が含まれているかチェック
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function cntrl_rn($str)
	{
		return (preg_match('/\A[\r\n[:^cntrl:]]+\z/u', $str) == 0) ? FALSE : TRUE;
	}
	
	
	/**
	 * URLが正しいかチェック
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
    public function valid_url($str)
    {
		$pattern = "/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/";
		
		if (!preg_match($pattern, $str))
		{
			return FALSE;
		}

		return TRUE;
	}
	
	
	/**
	 * 郵便番号をチェック
	 */
	public function zip_code($str)
	{
		$post_flag = FALSE;
		
		if(!$str) return $post_flag;
		
		// 郵便番号チェック
		if (preg_match("/^\d{3}\-\d{4}$/", $str))
		{
		    $post_flag = TRUE;
		}
		else
		{
			if (preg_match("/^\d{7}/", $str))
			{
			    $post_flag = TRUE;
			    $str = substr($str, 0, 3) . '-' . substr($str, 3);
			}
		}
		
		if($post_flag)
		{
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	/**
	 * 電話番号が正しいかチェック
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
    public function valid_tel($tel)
    {
		return preg_match("/^0\d{9,10}$/", str_replace("-", "", $tel)) ? TRUE : FALSE;
	}
}