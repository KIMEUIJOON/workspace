<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	load();
	
	$('#tb').tabs(
	{
		fit:true,
		border:false,
		plain:true,
	});
	
	attentions('レビュー', <?= $op7 ?>);
});

function load()
{
	$('#tt').datagrid(
	{
		url: BASE_URL + 'member/review/load',
		//toolbar: '#toolbar',
		//title: '携帯電話ショップ一覧',
		fit:true,
		iconCls:'icon-save',
		nowrap: true,
		fitColumns: true,
		pagination: true,
		pageSize: 50,
		pageList: [10,20,30,40,50,100],
		autoRowHeight:false,
		//idField: 'id',
		singleSelect: true,
		striped: true,
		columns:[[
			{field:'score',title:'評価',width:60, align:'center', sortable: true},
			{field:'name',title:'お名前',width:90, formatter: escapeHTML},
			{field:'body',title:'レビュー内容',width:280,formatter: escapeHTML},
			{field:'entry_time',title:'投稿日付',width:70, align:'center', sortable: true},
		]],
		onSelect:function(rowIndex,rowData)
		{
			view(rowData);
		},
		onLoadSuccess:function(data)
		{
			if (check_status(data.status) == false) return false;
			
			if (data.status != STATUS_OK)
			{
				$.messager.alert('データ取得 ERROR !!',　'<p class="messagerAlert">データが取得できませんでした</p>','error');
			}
			
			$('.btn').linkbutton();
		},
		onLoadError:function(test)
		{
			$.messager.alert('データ通信 ERROR !!',　'<p class="messagerAlert">データ読込に失敗しました</p>','error');
		}
	});
}

function view(rowData)
{
	$('#review-entry_time').text(rowData.entry_datetime);
	$('#review-name').text(rowData.name);
	$('#review-score').html($('<div id="review-level-button" class="level-'+rowData.score+'">&nbsp;</div>'));
	$('#review-body').html(nl2br(escapeHTML(rowData.body)));
	
	$('#win').window(
	{
		title: 'レビュー内容',
		iconCls : "icon-tip",
		width:600,
		collapsible: false,
		minimizable: false,
		maximizable: false,
		resizable: false,
		height:300,
		modal:true
	});
}

function update()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/review/update',
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
				$.messager.show({title:'レビュー設定更新完了',msg:'<p>レビュー設定を更新しました</p>',timeout:3000,showType:'slide'});
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

<div region="center" border="true" title="オプション７ (レビュー)" style="padding-top: 5px;">

<div id="tb">

<div title="レビュー設定" style="padding:10px;">

<form method="post" style="padding:10px 10px 10px 15px">
<table class="form" width="100%">

<tr>
<th>レビュー</th>
<td><?= form_dropdown('review_flag', $this->config->item('review'), $review_flag) ?>　　<a href="javascript:void(0);" id="update_btn" class="easyui-linkbutton" iconCls="icon-save" onclick="update(); return false;">レビュー設定を更新</a></td>
</tr>

<tr>
<th>総合評価</th>
<?php if($review_count): ?>
<td><?= set_starimg($review_score) ?>　<span style="font-size:14px"><?= $this->config->item(floor($review_score), 'score') ?></span> 　<strong><?= $review_count ?>件</strong></p></td>
<?php else: ?>
<td>評価無し</td>
<?php endif; ?>
</tr>

</table>
</form>
	
<div class="alert alert-success">
<p>レビューを受け付けない場合は、「非表示」に設定してください。</p>
</div>
	
</div><!--@TAB2-->

<div title="レビュー一覧" style="padding:10px;">

<table id="tt">
</table>
</div><!--@TAB1-->


</div><!--.easyui-tabs-->
</div><!--/center-->

<div style="display: none;">
<div id="win" style="padding:20px">
<table class="table table-bordered table-striped">
<tr>
<th>投稿時間</th>
<td id="review-entry_time"></td>
</tr>

<tr>
<th>お名前</th>
<td id="review-name"></td>
</tr>

<tr>
<th>スコア</th>
<td id="review-score"></td>
</tr>

<tr>
<th>内容</th>
<td id="review-body"></td>
</tr>
</table>

<div style="text-align:center"><a onclick="$('#win').window('close'); return false;" href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-no">ウィンドウを閉じる</a></div>

</div>
</div><!-- /display: none; -->

<?= $this->load->view('member/common/footer'); ?>
