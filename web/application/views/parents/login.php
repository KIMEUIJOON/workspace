<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>Parent Control Panel</title>
<base href="<?= CONF_BASE_URL ?>" />
<link rel="stylesheet" type="text/css" href="src/ad/css/normalize.css" />
<link rel="stylesheet" type="text/css" href="src/cm/themes/pepper-grinder/easyui.css" />
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
$(function(){
	$('#win').window({
		width:450,
		height:150,
		title: 'Login',
		minimizable: false,
		maximizable: false,
		closable: false,
		closed: false,
		collapsible: false,
		draggable: false,
		resizable: false
		//modal:true
	});

	login_session_error(<?= (int)$this->input->get(QUERY_KEY_SESSION_ERROR) ?>);
});

function login()
{
	$('#login').linkbutton('disable');
	loading_start();

	$.ajax({
		type:"post",
		url: BASE_URL + "parents/login/auth",
		data: $("#form-login").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			loading_end();
			$('#login').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$('body').animate({
					'opacity' : 0
				}, 200, function() {
					location.href = BASE_URL + 'member/basic';
				});
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">'+data.message+'</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			$('#login').linkbutton('enable');
			
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生しログインできませんでした</p>','error');
		}
	});
}

// セッション切れエラー
function login_session_error(f)
{
	if(f)
	{
		$.messager.alert('認証期限切れ', '<p class="error">再度ログインが必要です。</p>','error');
	}
}

</script>
</head>

<body>

<div id="win" style="padding:10px;">

<!-- Form -->
<form id="form-login">
<table style="width:97%">

<tr>
<td style="width:75px;"><label for="login-user"><strong>Login ID:</strong></label></td>
<td><input type="text" style="width:100%" name="username" id="login-user" /></td>
</tr>

<tr>
<td style="width:75px;"><label for="login-pass"><strong>Password:</strong></label></td>
<td><input type="password" style="width:100%" name="passwd" id="login-pass" /></td>
</tr>

</table>
</form>

<div style="text-align:right;margin:15px 5px 0 0">
<a class="easyui-linkbutton" id="login" iconCls="icon-ok" href="javascript:void(0);" onclick="login(); return false;">LOGIN ログイン</a>
</div>

</div>

</body>
</html>
