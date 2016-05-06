<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['_common_version'] = '2012-08-24';

define('STATUS_OK', 'OK');
define('STATUS_NG', 'NG');
define('STATUS_EXPIRE', 'expire');

define('SITE_NAME', '携帯ショップ徹底ナビ');

// 置き換え名前タグ メールマガジン
define('REPLACE_NAME', '[NAME]');

// トークン長さ
define('TOKEN_LENGTH', 32);

//define('CONF_BASE_URL_SP',	CONF_BASE_URL . 'sp/');
//define('CONF_BASE_URL_SP',	CONF_BASE_PATH . 'sp/'); // URLも絶対パスで指定する
//define('CONF_BASE_PATH_SP',	CONF_BASE_PATH. 'sp/');
define('CONF_BASE_URL_SP',	CONF_BASE_PATH); // URLも絶対パスで指定する
define('CONF_BASE_PATH_SP',	CONF_BASE_PATH);

// セッション切れの時、ログイン画面のエラー判断 クエリ名
define('QUERY_KEY_SESSION_ERROR',	'ser'); // 1の場合はログイン画面にてアラート画面


// SHOPステータス
$config['status_flag'] = array(
	0 => '未登録',
	1 => 'メンバー',
	//8 => '公開停止',
	//9 => 'ログイン停止',
);


// 1フォルダの最大フォルダ数
define('ASSETS_FOLDER_LIMIT', 50);

/**
* ユーザのassets pathを取得
*/
function get_user_assets_path($id = '')
{
	return CONF_ROOT_PATH . 'assets/' . ceil($id / ASSETS_FOLDER_LIMIT) . '/' . $id . '/';
}

/**
* ユーザのassets urlを取得
*/
function get_user_assets_url($id = '')
{
	return CONF_BASE_URL . 'assets/' . ceil($id / ASSETS_FOLDER_LIMIT) . '/' . $id . '/';
}

/**
* ルートからの絶対パスを生成
*/
function base_path($str = '')
{
	return CONF_BASE_PATH . $str;
}

/**
* ユーザのassets pathフォルダが存在するかチェックし無い場合は生成
*/
function make_assets_path($shop_id)
{
	if ( ! preg_match("/\A[1-9]{1}[0-9]*\z/", $shop_id))
	{
		return 'ショップIDが正しくありません';
	}
	
	$path = get_user_assets_path($shop_id);
	
	if(file_exists($path) !== TRUE)
	{
		if ( mkdir( $path, 0777, true ) !== TRUE)
		{
			return 'mkdir ERROR shop_id:' . $shop_id . ' path:'. $path;
		}

		if(chmod($path, 0777) !== TRUE)
		{
			return 'chmod ERROR shop_id:' . $shop_id . ' path:'. $path;
		}

		else if(fileperms($path) != "16895")
		{
			if(chmod($path, 0777) !== TRUE)
			{
				return 'chmod ERROR shop_id:' . $shop_id . ' path:'. $path;
			}
		}
	}
	
	return TRUE;
}
