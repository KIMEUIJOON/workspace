<?= $this->load->view('sp/common/header') ?>
<body>

<div data-role="page" id="newsletter">

<!-- header -->
<div data-role="header" data-theme="f">
<h1>メールマガジン</h1>
</div>

<!-- content -->
<div data-role="content" data-theme="d">

<h1 class="title"><?= h($name) ?></h1>
<form>
<input type="hidden" name="id" value="<?= $id ?>">

<fieldset data-role="fieldcontain">
<label for="name">お名前: </label>
<input type="text" name="name" value="" id="name">
</fieldset>

<fieldset data-role="fieldcontain">
<label for="email">Email:</label>
<input type="email" name="email" id="email" value="">
</fieldset>

<div class="ui-grid-a form-btns">
<div class="ui-block-a"><a data-theme="e" href="javascript:void(0);" onclick="newsletter(); return false;" data-role="button" data-mini="true">登録</a></div>
<div class="ui-block-b"><a href="#" data-rel="back" data-role="button" data-mini="true">キャンセル</a></div>
</div>
</form>
</div>

<script>
	
$(function()
{
	//if($("#newsletter #name").val() == '' && localStorage.getItem(STORAGE_KEY.NEWS_NAME) != null)
	//{
	//	$("#newsletter #name").val(localStorage.getItem(STORAGE_KEY.NEWS_NAME));
	//}
	
	//if($("#newsletter #email").val() == '' && localStorage.getItem(STORAGE_KEY.NEWS_EMAIL) != null)
	//{
	//	$("#newsletter #email").val(localStorage.getItem(STORAGE_KEY.NEWS_EMAIL));
	//}
});

function newsletter()
{
	$.mobile.showPageLoadingMsg();
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'api/newsletter/add',
		data: $("#newsletter form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			$.mobile.hidePageLoadingMsg();
			
			if (data.status == STATUS_OK)
			{
				// 名前・メールアドレスを保存
				//localStorage.setItem(STORAGE_KEY.NEWS_NAME, $("#newsletter #name").val());
				//localStorage.setItem(STORAGE_KEY.NEWS_EMAIL, $("#newsletter #email").val());
				
				alert('メールマガジン登録完了しました');
				$('.ui-dialog').dialog('close');
			}
			else
			{
				alert(data.message);
			}
		},
		error: function()
		{
			$.mobile.hidePageLoadingMsg();
			alert("通信エラーが発生しメールマガジン登録できませんでした");
		}
	});
}
</script>
</div><!--/&page-->

</body>
</html>
