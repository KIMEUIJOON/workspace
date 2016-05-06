<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// アップロード関連設定

// 最終ファイル更新日
$config['upload_version'] = '2012/10/23';

// ASSETS一時保管
define('ASSETS_TEMP_FOLDER_NAME', 'temp');
define('ASSETS_TEMP_PATH', CONF_ROOT_PATH . '/assets/' . ASSETS_TEMP_FOLDER_NAME);

/**
* 一時保管ASSETSURL取得
*/
function get_assets_temp_url($file_name = '')
{
	return CONF_BASE_URL . 'assets/' . ASSETS_TEMP_FOLDER_NAME . '/' . $file_name;
}
/**
* 一時保管画像PATH取得
*/
function get_assets_temp_path($file_name = '')
{
	return ASSETS_TEMP_PATH . '/' . $file_name;
}
