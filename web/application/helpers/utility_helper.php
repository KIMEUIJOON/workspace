<?php

/**
* アプリからのアクセスかチェック
*/
function is_app()
{
	$ua=$_SERVER['HTTP_USER_AGENT'];
	if(strpos($ua,'mobnavi')!==false)
	{
		return true;
	}
	
	return false;
}

// --------------------------------------------------------------------

/**
* 評価
*/
function set_starimg($score)
{
	$star_html = '';

	if($score < 0.5)
	{
		return '<img src="'.CONF_BASE_URL.'src/sp/img/star_05.png" width="18">';
	}
	
	$s_int = floor($score);
	
	$s_decimal = $score - $s_int;
	
	for($i = 1; $i <= $s_int; $i++)
	{
		$star_html = $star_html . '<img src="'.CONF_BASE_URL.'src/sp/img/star_10.png" width="18">';
	}
	
	if($s_decimal > 0)
	{
		if($s_decimal >= 0.5)
		{
			$star_html = $star_html . '<img src="'.CONF_BASE_URL.'src/sp/img/star_05.png" width="18">';
		}
	}

	return $star_html;
}
// --------------------------------------------------------------------

/**
* width_heightを置き換え
*/
function replace_width_height($str, $w, $h)
{
	if(!$str) return '';
	
	$str = preg_replace('/width="[0-9]+"/is', 'width="'.$w.'"', $str);
	$str = preg_replace('/height="[0-9]+"/is', 'width="'.$h.'"', $str);

	return $str;
}
// --------------------------------------------------------------------

/**
* ファイル名から拡張子を取得
*/
function get_extension($filename = '')
{
	return strtolower(substr($filename, strrpos($filename, '.') + 1));
}

// --------------------------------------------------------------------

/**
* テスト用の画像を表示
*/
function set_test_image()
{
	$path = CONF_BASE_URL . 'src/images/test.jpg';
	
	$random = mt_rand(0,99);
	
	if ($random % 2)
	{
		return $path;
	}
	
	return '';
}

// --------------------------------------------------------------------
/**
* 整数で0からはじまらない数字チェック
*/
function is_int0($str)
{
	if (preg_match("/\A[1-9]{1}[0-9]*\z/", $str))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// --------------------------------------------------------------------
/**
* Returns HTML escaped variable
*
* @access	public
* @param	mixed
* @return	mixed
*/

function h($var, $charset = 'UTF-8')
{
	if ($var === FALSE) return '';

	if (is_array($var))
	{
		return array_map('h', $var);
	}
	else
	{
		return htmlspecialchars($var, ENT_QUOTES, $charset);
	}
}


// --------------------------------------------------------------------

/**
* HTTP Request
*/
function curlRequest($url, $user_agent)
{
	$result = array();
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);


	$result['body'] = curl_exec($ch);
	$result['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	return $result;
}

// --------------------------------------------------------------------

    /**
     * ファイルポインタから行を取得し、CSVフィールドを処理する
     * @param resource handle
     * @param int length
     * @param string delimiter
     * @param string enclosure
     * @return ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
     */
    function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false;
        while (($eof != true)and(!feof($handle))) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
            $_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
            $_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }


// --------------------------------------------------------------------

/**
* 住所や郵便番号より緯度・経度を取得
*/
function geocode($str)
{
	$r = array();
	$r['status'] = '';
	$r['lat'] = '';
	$r['lng'] = '';

	$address = urlencode($str);
	$strRes = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');

	$aryGeo = json_decode( $strRes, TRUE );

	if( ! isset($aryGeo['status'])) $r;
	
	$r['status'] = $aryGeo['status'];

	if ( ! isset($aryGeo['results'][0]['geometry']['location']['lat']) OR $r['status'] != 'OK')
	{
		$CI =& get_instance();
		$CI->load->model('Post_code_Model', 'Post', TRUE);
		$CI->Post->error('geocode error ! status:' . $r['status'] . '  address:' . $str);
		return $r;
	}
	
	$r['lat'] = (float)$aryGeo['results'][0]['geometry']['location']['lat'];
	$r['lng'] = (float)$aryGeo['results'][0]['geometry']['location']['lng'];
	
	return $r;
}

// --------------------------------------------------------------------
/**
* トークンを生成
*/

if(CREATE_TOKEN == 1)
{
	function get_token($length = 32)
	{
		$s = file_get_contents('/dev/urandom', false, NULL, 0, $length);

		$s = substr(base64_encode($s), 0 , $length);
		return str_replace(array('/', '+'), array('-', '_'), $s);
	}
}
else
{
	function get_token($length = 32)
	{
		$token = preg_split("//u", bin2hex(mhash(MHASH_SHA512, mt_rand())),-1,PREG_SPLIT_NO_EMPTY);
			
		shuffle($token);
			
		$str = '';
		$cnt = 0;
			
		foreach($token as $val)
		{
			++$cnt;
				
			$str = $str . $val;
				
			if($cnt >= $length) break;
		}

		return $str;
	}
}

// --------------------------------------------------------------------
/**
* 検索キーワードを分割しワードを配列で返す
*/
function parse_search_keyword($str)
{
	$str = str_replace('　', ' ', $str);
	return explode(" ", trim(preg_replace("/[\s]+/", " ", $str)));
}

// --------------------------------------------------------------------

/**
* パスワードを生成
*/
function make_passwd($length = 8)
{
	$pool = 'abcdeghkmpqsvwxyz23456789';

	$str = '';
		
	for ($i=0; $i < $length; $i++)
	{
		$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
	}
		
	return $str;
}

// --------------------------------------------------------------------

/**
* ログインIDを生成
*/
function make_username($id)
{
	$pool = 'abcdeghkmpqsvwxyz';

	$str = '';
		
	for ($i=0; $i < 3; $i++)
	{
		$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
	}
	
	return $str . sprintf('%05d', $id);
}