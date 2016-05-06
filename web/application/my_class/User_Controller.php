<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Common_Controller.php' );

/**
 *  ユーザ共通処理設定
 */
class User_Controller extends Common_Controller
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('my/shop');
	}
	
	
	/**
	 * パラメーターよりキャリア設定を取得
	 */
	public function get_carrier()
	{
		$ins = array();
		$search_flag = FALSE;

		foreach ($this->config->item('carrier') as $key => $v)
		{
			$k = $this->input->get($key);
			
			if($k == 2)
			{
				$search_flag = TRUE;
			}
			else
			{
				$ins[] = (string)$key;
			}
		}
		
		if($search_flag)
		{
			// もし全てがキャリア設定オフだった場合は、ダミーで検索して1件も取得しないようにする
			if(!count($ins)) $ins[] = (string)'xx';
			return $ins;
		}
		
		return FALSE;
	}
}