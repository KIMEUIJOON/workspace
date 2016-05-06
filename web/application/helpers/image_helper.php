<?php

// --------------------------------------------------------------------

/**
* 切り取ってサムネイルを作成する
*/
function crop_thumbnail_image_write($read_path, $width, $height, $write_path)
{
	$r = array();
	$r['status'] = FALSE;
	$flag = FALSE;
	
	try
	{
		$image = new imagick();
		$image->readImage($read_path);
		$image->cropThumbnailImage($width, $height);
		
		$flag = $image->writeImage($write_path);
		$r['file_path']	= $image->getImageFilename();
		$path_parts		= pathinfo($r['file_path']);
		$r['file_name'] = $path_parts['basename'];
		$r['ext'] 		= (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
		$image->destroy();
		
	}
	catch (Exception $e)
	{
	    $r['message'] = $e->getMessage();
	}
	
	if($flag === TRUE && isset($r['message']) === FALSE && !empty($r['ext']) && !empty($r['file_name']))
	{
		$r['status'] = TRUE;
	}
	
	return $r;
}

// --------------------------------------------------------------------

/**
* 切り取ってサムネイルを表示
*/
function crop_thumbnail_image_view($read_path, $width, $height, $mime_type = '')
{
	try
	{
		$image = new imagick();
		$image->readImage($read_path);
		$image->cropThumbnailImage($width, $height);
	}
	catch (Exception $e)
	{
	    //$e->getMessage();
	}

	if(empty($mime_type))
	{
		$mime_type = get_image_mime_type($read_path);
	}
	
	header("Content-type: {$mime_type}");
	echo $image;
	
	$image->destroy();
	exit;
}


// --------------------------------------------------------------------

/**
* サムネイルを作成する
*/
function thumbnail_image_write($read_path, $maxWidth, $maxHeight, $write_path)
{
	$r = array();
	$r['status'] = FALSE;
	$flag = FALSE;
	
	try
	{
		$image = new imagick($read_path);
		$w = $image->getImageWidth();
		$h = $image->getImageHeight();

		if($maxWidth && $maxHeight)
		{
			$fitbyWidth = (($maxWidth/$w)<($maxHeight/$h)) ?true:false;

			if($fitbyWidth)
			{
			    $image->thumbnailImage($maxWidth, 0, false);
			}else{
			    $image->thumbnailImage(0, $maxHeight, false);
			}
		}
		else
		{
			if($maxWidth)
			{
				$image->thumbnailImage($maxWidth, 0, false);
			}
			else
			{
				$image->thumbnailImage(0, $maxHeight, false);
			}
		}
		
		$flag = $image->writeImage($write_path);
		$r['file_path']	= $image->getImageFilename();
		$path_parts		= pathinfo($r['file_path']);
		$r['file_name'] = $path_parts['basename'];
		$r['ext'] 		= (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
		$image->destroy();
	}
	catch (Exception $e)
	{
	    $r['message'] = $e->getMessage();
	}
	
	if($flag === TRUE && isset($r['message']) === FALSE && !empty($r['ext']) && !empty($r['file_name']))
	{
		$r['status'] = TRUE;
	}
	
	return $r;
}

// --------------------------------------------------------------------

/**
* サムネイルを表示
*/
function thumbnail_image_view($read_path, $maxWidth, $maxHeight, $mime_type = '')
{
	try
	{
		$image = new imagick($read_path);
		$w = $image->getImageWidth();
		$h = $image->getImageHeight();

		if($maxWidth && $maxHeight)
		{
			$fitbyWidth = (($maxWidth/$w)<($maxHeight/$h)) ?true:false;

			if($fitbyWidth)
			{
			    $image->thumbnailImage($maxWidth, 0, false);
			}else{
			    $image->thumbnailImage(0, $maxHeight, false);
			}
		}
		else
		{
			if($maxWidth)
			{
				$image->thumbnailImage($maxWidth, 0, false);
			}
			else
			{
				$image->thumbnailImage(0, $maxHeight, false);
			}
		}
	}
	catch (Exception $e)
	{
	    //$e->getMessage();
	}

	if(empty($mime_type))
	{
		$mime_type = get_image_mime_type($read_path);
	}
	
	header("Content-type: {$mime_type}");
	echo $image;
	
	$image->destroy();
	exit;
}

// --------------------------------------------------------------------

/**
* 画像の拡張子よりmime_typeを取得
*/
function get_image_mime_type($image_path, $error = 'image/jpeg')
{
	$size = @getimagesize($image_path);

	if($size === FALSE)
	{
		$mime = $error;
	}
	else
	{
		$mime = $size['mime'];
	}
	
	return $mime;
}