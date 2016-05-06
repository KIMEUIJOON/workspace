<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	strLengthCheck(<?= JOB_LENGTH ?>, '#job');
	
	attentions('求人情報', <?= $op6 ?>);
});

// 求人情報更新
function update()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/job/update',
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
				$.messager.show({title:'求人更新完了',msg:'<p>求人情報を更新しました</p>',timeout:3000,showType:'slide'});
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

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('member/common/north'); ?>

<?= $this->load->view('member/common/west'); ?>

<div region="center" border="true" title="オプション６ (求人情報)">

<div style="padding:8px 10px 0 0;text-align:right">
<a href="javascript:void(0);" id="update_btn" class="easyui-linkbutton" iconCls="icon-save" onclick="update(); return false;">求人情報を更新</a>
</div>

<form method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<tr>
<th style="width: 120px">求人情報<p>※<?= JOB_LENGTH ?>文字以内</p></th>
<td><textarea style="width:97%;line-height: 140%;max-width:600px" id="job" data-check="" wrap="soft" name="job" rows="15"><?= h($job) ?></textarea> <p></p></td>
</tr>

</table>
</form>

</div><!--/center-->

<?= $this->load->view('member/common/footer'); ?>
