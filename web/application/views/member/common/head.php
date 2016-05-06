<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>Member Control Panel</title>
<base href="<?= CONF_BASE_URL ?>" />
<link rel="stylesheet" type="text/css" href="src/ad/css/normalize.css" />
<link rel="stylesheet" type="text/css" href="src/cm/themes/cupertino/easyui.css" />
<link rel="stylesheet" type="text/css" href="src/cm/themes/icon.css" />
<link rel="stylesheet" type="text/css" href="src/ad/css/custom.css" />
<script type="text/javascript" src="src/ad/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="src/ad/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="src/ad/js/easyui-lang-ja.js"></script>
<script type="text/javascript" src="src/cm/js/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="src/cm/js/config.js"></script>
<script type="text/javascript" src="src/cm/js/custom.js"></script>
<script type="text/javascript" src="src/ad/js/common.js"></script>
<script type="text/javascript" src="src/cm/js/jquery.upload-1.0.2.min.js"></script>

<script type="text/javascript">
$(function()
{
	$.ajax(
	{
		type:"get",
		url: BASE_URL + 'member/banner',
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			//if (check_status_member(data.status) == false) return false;
			
			if(data.src)
			{
				$("#banner").append(data.src);
			}
		},
		error: function()
		{

		}
	});
});
</script>