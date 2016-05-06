<?= $this->load->view('sp/common/header') ?>
<body>

<div data-role="page" id="review">

<!-- header -->
<div data-role="header" data-theme="f">
<h1>レビューする</h1>
</div>

<!-- content -->
<div data-role="content" data-theme="d">

<h1 class="title"><?= h($name) ?></h1>
<form>
<input type="hidden" name="id" value="<?= $id ?>">
	
<fieldset data-role="fieldcontain">
<label for="name">ハンドルネーム: </label>
<input type="text" name="name" value="" data-mini="true" placeholder="表示されるお名前" id="name">
</fieldset>

<fieldset data-role="fieldcontain">
<label for="score">評価: </label>
<?= form_dropdown('score', $this->config->item('score'), 3, 'data-native-menu="false" data-mini="true" id="score"') ?>
</fieldset>

<fieldset data-role="fieldcontain">
<label for="body">コメント: </label>
<textarea data-mini="true" name="body" maxlength="<?= REVIEW_MAXLENGTH ?>" placeholder="ショップに対するコメントを書いてください" id="body"></textarea>
</fieldset>

<div class="ui-grid-a form-btns">
<div class="ui-block-a"><a data-theme="e" href="javascript:void(0);" onclick="review(); return false;" data-role="button" data-mini="true">送信</a></div>
<div class="ui-block-b"><a href="#" data-rel="back" data-role="button" data-mini="true">キャンセル</a></div>
</div>

</form>
</div>

<script>
	
$(function()
{
	//if($("#review #name").val() == '' && localStorage.getItem(STORAGE_KEY.REVIEW_NAME) != null)
	//{
	//	$("#review #name").val(localStorage.getItem(STORAGE_KEY.REVIEW_NAME));
	//}
});

function review()
{
	$.mobile.showPageLoadingMsg();
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'api/review/add',
		data: $("#review form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			$.mobile.hidePageLoadingMsg();
			
			if (data.status == STATUS_OK)
			{
				// ハンドルネームを保存
				//localStorage.setItem(STORAGE_KEY.REVIEW_NAME, $("#review #name").val());
				
				$("#review #body").val("");
				alert('レビュー送信完了しました');
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
			alert("通信エラーが発生しレビューできませんでした");
		}
	});
}
</script>
</div><!--/&page-->

</body>
</html>
