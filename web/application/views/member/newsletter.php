<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	load();
	load_history();
	
	$('#tb').tabs(
	{
		fit:true,
		border:false,
		plain:true,
		tools:'#tab-tools',
	    onSelect:function(title)
	    {
	    	if(title == 'テンプレート')
	    	{
	    		$('#tab-tools').empty();
	    		
	    		$('#tab-tools').append('<a href="javascript:void(0);" id="update_btn" iconCls="icon-save" plain="true" onclick="update_temp(); return false;">テンプレート更新</a>');
	        	$('#update_btn').linkbutton();
	        	$('#tab-tools').css('border', '1px solid rgb(174, 208, 234)');
	        }
	        else if(title == '購読者一覧')
	        {
	        	$('#tab-tools').css('border', 'none');
	        	$('#tab-tools').empty();
	        }
	        else if(title == 'メール作成')
	        {
	        	$('#tab-tools').empty();
	        	
	    		$('#tab-tools').append('<a href="javascript:void(0);" class="mail_add_btn" iconCls="icon-add" plain="true" onclick="mail_add(); return false;">メール配信予約</a>');
	        	$('.mail_add_btn').linkbutton();
	        	$('#tab-tools').css('border', '1px solid rgb(174, 208, 234)');
	        }
	        else if(title == '配信履歴')
	        {
	        	$('#tab-tools').css('border', 'none');
	        	$('#tab-tools').empty();
	        }
	    }
	});
	
	attentions('メールマガジン', <?= $op4 ?>);
});

function load()
{
	$('#tt').datagrid(
	{
		url: BASE_URL + 'member/newsletter/load',
		//toolbar: '#toolbar',
		//title: '携帯電話ショップ一覧',
		fit:true,
		//iconCls:'icon-save',
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
			{field:'name',title:'お名前',width:90, formatter: escapeHTML},
			{field:'email',title:'E-MAIL',width:200},
			{field:'date_time',title:'登録日',width:70, align:'center'},
		]],
		onLoadSuccess:function(data)
		{
			if (check_status(data.status) == false) return false;
			
			if (data.status != STATUS_OK)
			{
				$.messager.alert('データ取得 ERROR !!',　'<p class="messagerAlert">データが取得できませんでした</p>','error');
			}
		},
		onLoadError:function(test)
		{
			$.messager.alert('データ通信 ERROR !!',　'<p class="messagerAlert">データ読込に失敗しました</p>','error');
		}
	});
}

// 配信履歴
function load_history()
{
	$('#tt2').datagrid(
	{
		url: BASE_URL + 'member/newsletter/load_history',
		fit:true,
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
			{field:'subject',title:'メール件名',width:90, formatter: escapeHTML},
			{field:'body',title:'メール内容',width:280,formatter: escapeHTML},
			{field:'date_time',title:'予約日時',width:70, align:'center'},
			{field:'status',title:'配信状況',width:50, align:'center', formatter : 
				function(value,rowData)
				{
					if(value == 0 || value == 1)
					{
						return '準備中';
					}
					else if(value == 2)
					{
						return '完了';
					}
				}
			}
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
		},
		onLoadError:function(test)
		{
			$.messager.alert('データ通信 ERROR !!',　'<p class="messagerAlert">データ読込に失敗しました</p>','error');
		}
	});
}

function view(rowData)
{
	$('#news-date_time').text(rowData.date_time);
	$('#news-subject').text(escapeHTML(rowData.subject));
	$('#news-body').html(nl2br(escapeHTML(rowData.body)));
	
	$('#win').window(
	{
		title: 'メール配信内容',
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

function update_temp()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/newsletter/update_temp',
		data: $("#mail_temp form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'テンプレート更新完了',msg:'<p>テンプレートを更新しました</p>',timeout:3000,showType:'slide'});
				
				// テンプレートの内容をメール作成フォームに反映
				$('#subject').val($('#mtemp_subject').val());
				$('#body').val($('#mtemp_body').val());
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

function mail_add()
{

$.messager.confirm('確認','<p>メール配信予約してもよろしいですか？</p>',function(r)
{  
if (r)
{
	loading_start();
	$('.mail_add_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/newsletter/add',
		data: $("#mail_send form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			$('.mail_add_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$('#tt2').datagrid('reload');
				$('#tb').tabs('select', '配信履歴');
				$.messager.show({title:'メール配信予約完了',msg:'<p>メール配信予約しました</p>',timeout:3000,showType:'slide'});
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			$('.mail_add_btn').linkbutton('enable');
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生しメール配信予約できませんでした</p>','error');
		}
	});
}
});
}

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('member/common/north'); ?>

<?= $this->load->view('member/common/west'); ?>

<div region="center" border="true" title="オプション４ (メールマガジン)" style="padding-top: 5px;">

<div id="tb">
	
<div title="メール作成" id="mail_send" style="padding:10px;">

<form method="post" style="padding:10px 10px 10px 15px">

<div class="alert alert-success">
<p>メール配信予約すると順次購読者様へメールが送信されます。</p>
<p>配信システムの混雑状況によっては送信まで時間がかかる場合もあります。</p>
<p style="color:red">※文章中の <?= REPLACE_NAME ?> は購読者の名前に変換されます。</p>
</div>

<table class="form" width="100%">

<tr>
<th>メール件名</th>
<td><input type="text" style="width:400px" name="subject" id="subject" value="<?= h($mtemp_subject) ?>" /></td>
</tr>

<tr>
<th>メール本文</th>
<td><textarea style="width:400px;line-height: 140%;" name="body" id="body" rows="15"><?= h($mtemp_body) ?></textarea><p>※改行はメール文でも反映されます</p></td>
</tr>

<tr>
<th>&nbsp;</th>
<td><a href="javascript:void(0);" class="mail_add_btn" iconCls="icon-add" onclick="mail_add(); return false;">メール配信予約</a></td>
</tr>

</table>
</form>
	
</div><!--@TAB1-->


<div title="配信履歴" style="padding:10px;">

<table id="tt2">
</table>
</div><!--@TAB2-->

<div title="購読者一覧" style="padding:10px;">

<table id="tt">
</table>
</div><!--@TAB3-->

<div title="テンプレート" id="mail_temp" style="padding:10px;">

<form method="post" style="padding:10px 10px 10px 15px">

<div class="alert alert-success">
<p><strong>メールテンプレート設定</strong></p>
<p>下記にメールの件名と本文の雛形を登録できます。定型文章を毎回入力する手間が省けます。</p>
<p>本文には、購読者が連絡できるようにメールアドレスや電話番号等の連絡先を必ず記載してください。</p>
<p style="color:red">※文章中の <?= REPLACE_NAME ?> は購読者の名前に変換されます。</p>
</div>

<table class="form" width="100%">

<tr>
<th>メール件名</th>
<td><input type="text" style="width:400px" name="mtemp_subject" id="mtemp_subject" value="<?= h($mtemp_subject) ?>" /></td>
</tr>

<tr>
<th>メール本文</th>
<td><textarea style="width:400px;line-height: 140%;" name="mtemp_body" id="mtemp_body" rows="15"><?= h($mtemp_body) ?></textarea><p>※改行はメール文でも反映されます</p></td>
</tr>

</table>
</form>
	
</div><!--@TAB4-->
	
</div><!--.easyui-tabs-->
	
<div id="tab-tools" style="border:none"></div>

</div><!--/center-->


<div style="display: none;">
<div id="win" style="padding:20px">
<table class="table table-bordered table-striped">
<tr>
<th>予約日時</th>
<td id="news-date_time"></td>
</tr>

<tr>
<th>メール件名</th>
<td id="news-subject"></td>
</tr>

<tr>
<th>メール本文</th>
<td id="news-body"></td>
</tr>
</table>

<div style="text-align:center"><a onclick="$('#win').window('close'); return false;" href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-no">ウィンドウを閉じる</a></div>

</div>
</div><!-- /display: none; -->


<?= $this->load->view('member/common/footer'); ?>
