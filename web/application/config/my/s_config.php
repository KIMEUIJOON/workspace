<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['_s_config_version'] = '2012-11-16';

// レビューのリミット
define('REVIEW_LIMIT', 30);
define('REVIEW_MAXLENGTH', 500);

// 掲示板リミット
define('BOARD_LIMIT', 10);
define('BOARD_MAXLENGTH', 140);

// リソースURL取得
function src_url($path = '', $mode = 'sp')
{
	return CONF_BASE_URL . 'src/'.$mode.'/' . $path;
}

function src_path($path = '', $mode = 'sp')
{
	return CONF_BASE_PATH . 'src/'.$mode.'/' . $path;
}
