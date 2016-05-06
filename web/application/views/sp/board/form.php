<?= $this->load->view('sp/common/header') ?>
<body>
	
<div data-role="page" id="board">

<div data-role="header" data-theme="f" data-position="inline">

<h1>掲示板</h1>

</div><!--/&header-->

<div data-role="content">

<form>

<fieldset data-role="fieldcontain">
<label for="name">ハンドルネーム: </label>
<input type="text" name="name" value="" maxlength="20" data-mini="true" placeholder="表示されるお名前" id="name">
</fieldset>

<fieldset data-role="fieldcontain">
<label for="body">コメント: </label>
<textarea data-mini="true" name="body" maxlength="<?= BOARD_MAXLENGTH ?>" placeholder="<?= BOARD_MAXLENGTH ?>文字以内でコメントを書いてください" id="body"></textarea>
</fieldset>

<div class="ui-grid-a form-btns">
<div class="ui-block-a"><a data-theme="e" href="javascript:void(0);" onclick="board(); return false;" data-role="button" data-theme="b" data-mini="true">送信</a></div>
<div class="ui-block-b"><a href="#" data-rel="back" data-role="button" data-mini="true">キャンセル</a></div>
</div>

</form>

</div><!--/&content-->


<script>

$(function()
{
	//if($("#board #name").val() == '' && localStorage.getItem(STORAGE_KEY.BOARD_NAME) != null)
	//{
	//	$("#board #name").val(localStorage.getItem(STORAGE_KEY.BOARD_NAME));
	//}
});

function board()
{
	$.mobile.showPageLoadingMsg();
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'sp/board/add',
		data: $("#board form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			$.mobile.hidePageLoadingMsg();
			
			if (data.status == STATUS_OK)
			{
				// ハンドルネームを保存
				//localStorage.setItem(STORAGE_KEY.BOARD_NAME, $("#board #name").val());
				alert('送信完了しました');
				$('.ui-dialog').dialog('close');
				
				$("ul.board li:first-child").after('<li data-theme="d"><h3 style="font-size:12px">' +escapeHTML(data.name)+ '</h3><p>' +escapeHTML(data.body)+ '</p><p class="datetime">(投稿日:' +data.entry_time+ ')</p></li>');
				$('ul.board').listview('refresh');
			}
			else
			{
				alert(data.message);
			}
		},
		error: function()
		{
			$.mobile.hidePageLoadingMsg();
			alert("通信エラーが発生し送信できませんでした");
		}
	});
}

</script>

</div><!--/&page-->

</body>
</html>
