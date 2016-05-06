<?= $this->load->view('admin/common/head'); ?>

<script type="text/javascript">
$(function(){
	$('#win').window({
		width:450,
		height:150,
		title: 'Admin Login',
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
		url: BASE_URL + "admin/login/auth",
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
					location.href = BASE_URL + 'admin/shop/view';
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
