<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// PDF関連設定

// 最終ファイル更新日
$config['pdf_version'] = '2012/07/08';

define('SHOP_FLYER_PDF_LIMIT', 5);

define('PDF_NAME_FLYER', 'flyer');

/**
* PDF PATH取得
*/
function get_pdf_path($id = '', $num = '', $name)
{
	if (is_int0($id) !== TRUE OR is_int0($num) !== TRUE) return FALSE;

	$path = get_user_assets_path($id);

	return $path . $name . '-' . $num . '.' . 'pdf';
}

/**
* 画像URL取得
*/
function get_pdf_url($id = '', $num = '', $name)
{
	if (is_int0($id) !== TRUE OR is_int0($num) !== TRUE) return FALSE;

	$url = get_user_assets_url($id);

	return $url . $name . '-' . $num . '.' . 'pdf';
}