// ログアウト処理
function logout()
{
	$.messager.defaults={ok:"Ok",cancel:"Cancel"};
	
	$.messager.confirm('確認','<p>ログアウトしてもよろしいですか?</p>',function(r)
	{
		if (r)
		{
			location.href = BASE_URL + 'admin/login/logout';
		}
	});
}