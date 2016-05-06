<?= $this->load->view('admin/common/head'); ?>

<script type="text/javascript">

// パスワード更新
function update_passwd()
{
	loading_start();

	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'admin/idpass/passwd',
		data: {new_passwd: $('#new_passwd').val(), old_passwd: $('#old_passwd').val()},
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'更新完了',msg:'<p>パスワードを変更しました</p>',timeout:3000,showType:'slide'});
				$('#new_passwd').val('');
				$('#old_passwd').val('');
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
		}
	});
}

// ログインID変更
function update_username()
{
	loading_start();

	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'admin/idpass/username',
		data: {username: $('#username').val()},
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status(data.status) == false) return false;
			
			loading_end();
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'更新完了',msg:'<p>ログインIDを変更しました</p>',timeout:3000,showType:'slide'});
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
		}
	});
}

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('admin/common/north'); ?>

<?= $this->load->view('admin/common/west'); ?>

<div region="center" border="true" title="ログインID・パスワード変更" style="padding:20px">

<table class="form">

<tr>
<th>ログインID</th>
<td><input type="text" style="width:200px" id="username" name="username" value="<?= $_SESSION['admin']['username'] ?>" maxlength="<?= ID_LENGTH_MAX ?>" /> ※<?= ID_LENGTH_MIN ?>～<?= ID_LENGTH_MAX ?>文字の半角英数字</td>
</tr>

<tr>
<th>&nbsp;</th>
<td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="update_username(); return false;">ログインID変更</a></td>
</tr>

</table>

<table class="form">

<tr>
<td colspan="2">※パスワードを変更する場合は、新パスワードと現在のパスワードを入力して「パスワード変更」を押してください。</td>
</tr>

<tr>
<th>新パスワード</th>
<td><input type="password" style="width:200px" id="new_passwd" name="new_passwd" value="" maxlength="<?= PASS_LENGTH_MAX ?>" /> ※<?= PASS_LENGTH_MIN ?>～<?= PASS_LENGTH_MAX ?>文字の半角英数字、記号</td>
</tr>
	
<tr>
<th>旧パスワード</th>
<td><input type="password" style="width:200px" id="old_passwd" name="old_passwd" value="" maxlength="<?= PASS_LENGTH_MAX ?>" /> ※現在のパスワードを入力してください</td>
</tr>

<tr>
<th>&nbsp;</th>
<td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="update_passwd(); return false;">パスワード変更</a></td>
</tr>

</table>
	
</div><!--/center-->

<?= $this->load->view('admin/common/footer'); ?>
