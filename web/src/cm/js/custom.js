function attentions(name, option)
{
	if(option != 0) return;

	var msg = '<div id="attention" style="padding:20px"><p><span style="color:red">※こちらの機能は現在使用できません。</span></p><p>「'+name+'」は有料オプションとなります。</p><p>左メニューの「有料オプション設定」をご確認ください。</p></div>';
	
	$('.layout-panel-center').prepend(msg);
	
	$('#attention').window(
	{
		title: name,
		iconCls : "icon-tip",
		width:370,
		collapsible: false,
		minimizable: false,
		maximizable: false,
		draggable:false,
		resizable: false,
		closable:false,
		height:170,
		modal:true,
		inline:true
	});
}

// ログアウト処理
function logout()
{
	$.messager.defaults={ok:"Ok",cancel:"Cancel"};
	
	$.messager.confirm('確認','<p>ログアウトしてもよろしいですか?</p>',function(r)
	{
		if (r)
		{
			location.href = BASE_URL + 'member/login/logout';
		}
	});
}