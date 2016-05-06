<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
// 独自移植ライブラリ用のパスの読み込み
$hook['pre_system'] = array(
	'class' => 'MyClasses',
	'function' => 'set_path',
	'filename' => 'MyClasses.php',
	'filepath' => 'hooks'
);

//開発時に自動的にプロファイラを有効にする
$hook['post_controller_constructor'] = array(
	'class' => 'MyClasses',
	'function' => 'enable_profiler',
	'filename' => 'MyClasses.php',
	'filepath' => 'hooks'
);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */