<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	$('#tt').tabs(
	{
		fit:true,
		border:false,
		plain:true,
		tools:'#tab-tools',
	    onSelect:function(title)
	    {
	    	if(title == '基本情報')
	    	{
	    		$('#tab-tools').empty();

	    		$('#tab-tools').append('<a href="javascript:void(0);" id="update_btn" iconCls="icon-save" plain="true" onclick="update(); return false;">基本情報 更新</a>');
	        	$('#update_btn').linkbutton();
	        	$('#tab-tools').css('border', '1px solid rgb(174, 208, 234)');
	        }
	        else if(title == 'ID・PASS')
	        {
	        	$('#tab-tools').css('border', 'none');
	        	$('#tab-tools').empty();
	        }
	    }
	});
});

// 基本情報更新
function update()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'parents/basic/update',
		data: $("form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'更新完了',msg:'<p>基本情報を更新しました</p>',timeout:3000,showType:'slide'});
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
		}
	});
}

// パスワード更新
function update_passwd()
{
	loading_start();

	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'parents/basic/passwd',
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
		url: BASE_URL + 'parents/basic/username',
		data: {username: $('#username').val()},
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
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

<?= $this->load->view('member/common/north'); ?>

<?= $this->load->view('member/common/west'); ?>

<div region="center" border="true" title="会社情報登録" style="padding-top: 5px;">

<div id="tt">

<div title="基本情報" style="padding:10px;">

<form method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<tr>
<th>会社名</th>
<td><input type="text" style="width:97%;" name="name" value="<?= h($name) ?>" /></td>
</tr>

<tr>
<th>郵便番号</th>
<td><input id="zip_code" type="text" size="12" class="span2" name="post_code" value="<?= $post_code ?>" /> <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="zip_code_address(); return false;">郵便番号から住所取得</a></td>
</tr>

<tr>
<th>都道府県</th>
<td><?= form_dropdown('prefecture', $this->config->item('prefecture'), $prefecture, 'id="prefecture"') ?></td>
</tr>

<tr>
<th>住所</th>
<td><input type="text" style="width:97%;" id="address" name="address" value="<?= h($address) ?>" /></td>
</tr>

<tr>
<th>建物名等</th>
<td><input type="text" style="width:97%;" name="building" value="<?= h($building) ?>" /></td>
</tr>

<tr>
<th>電話番号</th>
<td><input type="text" size="20" class="span2" name="tel1" value="<?= h($tel1) ?>" /></td>
</tr>

</table>
	
</form>
</div><!--@TAB1-->


<div title="ID・PASS" style="padding:20px 20px 30px 25px">

<table class="form">

<tr>
<th>ログインID</th>
<td><input type="text" style="width:200px" id="username" name="username" value="<?= $username ?>" maxlength="<?= ID_LENGTH_MAX ?>" /> ※<?= ID_LENGTH_MIN ?>～<?= ID_LENGTH_MAX ?>文字の半角英数字</td>
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
	
</div><!--@TAB2-->

</div><!--.easyui-tabs-->

<div id="tab-tools" style="border:none"></div>

</div><!--/center-->

<?= $this->load->view('member/common/footer'); ?>
