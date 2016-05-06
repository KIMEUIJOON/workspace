<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 画像関連設定

// 最終ファイル更新日
$config['img_version'] = '2012/07/08';

// 画像ライブラリ　ImageMagick OR GD
define('IMAGE_LIBRARY', 'gd2'); // imagemagick

// 画像拡張子
$config['IMG_TYPE'] = array(1 => 'gif', 2 => 'jpg', 3 => 'png');

// 画像名
define('IMAGE_NAME_PHOTO', 'photo');
define('IMAGE_NAME_FLYER', 'flyer');

// ショップ画像数
define('SHOP_IMAGE_LIMIT', 10);

// チラシ画像数
define('SHOP_FLYER_IMAGE_LIMIT', 5);

// 画像サイズ名
define('IMAGE_LARGE_NAME', 'large');
define('IMAGE_MIDDLE_NAME', 'middle');
define('IMAGE_SMALL_NAME', 'small');
define('IMAGE_ORIGINAL_NAME', 'original');

define('IMAGE_LARGE_SIZE', 1200);
define('IMAGE_MIDDLE_SIZE', 600);
define('IMAGE_SMALL_SIZE', 200);


/**
* 画像PATH取得
*/
function get_image_path($id = '', $num = '', $ext = '', $name, $type)
{
	if (is_int0($id) !== TRUE OR is_int0($num) !== TRUE OR $ext == '') return FALSE;

	$path = get_user_assets_path($id);

	return $path . $name . '-' . $type . '-' . $num . '.' . $ext;
}

/**
* 画像URL取得
*/
function get_image_url($id = '', $num = '', $ext = '', $name, $type, $time = '')
{
	if (is_int0($id) !== TRUE OR is_int0($num) !== TRUE OR $ext == '') return FALSE;

	$url = get_user_assets_url($id);

	if($time)
	{
		return $url . $name . '-' . $type . '-' . $num . '.' . $ext . '?time=' . $time;
	}
	else
	{
		return $url . $name . '-' . $type . '-' . $num . '.' . $ext;
	}
}



/**
* banner画像PATH取得
*/
function get_banner_path($num = '', $ext = '', $type="468_60")
{
	if (is_int0($num) !== TRUE OR $ext == '') return FALSE;

	return CONF_ROOT_PATH . 'assets/banner/' . $type . '_' . $num . '.' . $ext;
}

/**
* banner画像URL取得
*/
function get_banner_url($num = '', $ext = '', $time = '', $type="468_60")
{
	if (is_int0($num) !== TRUE OR $ext == '') return FALSE;

	if($time)
	{
		return CONF_BASE_URL . 'assets/banner/' . $type . '_' . $num . '.' . $ext . '?time=' . $time;
	}
	else
	{
		return CONF_BASE_URL . 'assets/banner/' . $type . '_' . $num . '.' . $ext;
	}
}