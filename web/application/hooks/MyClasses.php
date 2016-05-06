<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyClasses
{
	/**
	 * set include_path
	 */
    public function set_path()
    { 
        ini_set( 'include_path', APPPATH . 'my_class/');//余計なインクルードパスを読み込まないようにした
        //ini_set("include_path", realpath(APPPATH . 'my_class/') . PATH_SEPARATOR . ini_get("include_path") );
    }
    
    // 開発時に自動的にプロファイラを有効にする
	public function enable_profiler()
	{
		if (ENABLE_PROFILER)
		{
			$CI = &get_instance();
			$CI->output->enable_profiler(TRUE);
		}
	}

	// HTTPレスポンスヘッダの調整
	public function set_header()
	{
		$CI = &get_instance();
		$CI->output->set_header("Content-Type: text/html; charset=UTF-8");
		$CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$CI->output->set_header("Cache-Control: post-check=0, pre-check=0", FALSE);
		$CI->output->set_header("Pragma: no-cache");
		$CI->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		$CI->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	}
}