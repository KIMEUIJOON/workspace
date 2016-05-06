<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['_auth_version'] = '1.0';

// ログイン失敗メッセージ
define('LOGIN_MISS_MSG',	'IDまたはパスワードが違います');

// ID正規表現
define('ID_LENGTH_MIN',	6);
define('ID_LENGTH_MAX',	32);
define('ID_PATTERN',	'/\A[a-z0-9_]{'. ID_LENGTH_MIN .','. ID_LENGTH_MAX .'}\z/ui');
define('ID_ERROR_MSG',	ID_LENGTH_MIN . '～' . ID_LENGTH_MAX . '文字の半角英数字で入力してください。');

// PASS正規表現
define('PASS_LENGTH_MIN',	6);
define('PASS_LENGTH_MAX',	32);
define('PASS_PATTERN',		'/\A[\x20-\x7E]{'. PASS_LENGTH_MIN .','. PASS_LENGTH_MAX .'}\z/u');
define('PASS_ERROR_MSG',	PASS_LENGTH_MIN . '～' . PASS_LENGTH_MAX . '文字の半角英数字、記号で入力してください。');

// Cookieが使用できるかチェック
define('CHECK_COOKIE_NAME',	'CC');
define('CHECK_COOKIE_ERROR_MSG', 'ブラウザの cookie がオフになっています。<br />cookie をオンにしてください。');
