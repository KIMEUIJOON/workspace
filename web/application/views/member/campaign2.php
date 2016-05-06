<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	strLengthCheck(<?= CAMPAIGN_BODY_LENGTH ?>, '#campaign_body');
	
		// カレンダー設定
		$('.dd').datebox(
		{
			formatter:function(ts)
			{
				var y=ts.getFullYear();
				var m=ts.getMonth()+1;
				var d=ts.getDate();
				return y +"-" + m + "-" + d;
			}
		});
		
		attentions('キャンペーン内容', <?= $op2 ?>);
});

// 情報更新
function update()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/campaign/update2',
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
				$.messager.show({title:'キャンペーン更新完了',msg:'<p>キャンペーン内容を更新しました</p>',timeout:3000,showType:'slide'});
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

<div region="center" border="true" title="オプション２ (キャンペーン内容)">

<div style="padding:8px 10px 0 0;text-align:right">
<a href="javascript:void(0);" id="update_btn" class="easyui-linkbutton" iconCls="icon-save" onclick="update(); return false;">キャンペーン内容を更新</a>
</div>

<form method="post" style="padding:10px 10px 20px 15px">
<table class="form" style="width:100%">

<tr>
<th style="width:120px">公開終了日時</th>
<td><input type="text" class="dd" name="campaign_date" value="<?= h($campaign_date) ?>" /></td>
</tr>

<tr>
<th>キャンペーン内容<p>※<?= CAMPAIGN_BODY_LENGTH ?>文字以内</p></th>
<td><textarea style="width:97%;line-height: 140%;" name="campaign_body" data-check="" id="campaign_body" rows="8"><?= h($campaign_body) ?></textarea> <p></p></td>
</tr>

</table>
</form>

</div><!--/center-->

<?= $this->load->view('member/common/footer'); ?>
