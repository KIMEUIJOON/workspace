<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	load();
});

function load()
{
	$('#tt').datagrid(
	{
		url: BASE_URL + 'member/option/load',
		//toolbar: '#toolbar',
		//title: 'オプション設定',
		fit:true,
		iconCls:'icon-save',
		nowrap: false,
		fitColumns: true,
		autoRowHeight:false,
		//idField: 'id',
		singleSelect: true,
		striped: true,
		columns:[[
			{field:'value',title:'',width:50, align:'center',
				formatter: function(value,row,index){
					if(value == 1)
					{
						return '<input type="checkbox" onclick="update(\''+row.id+'\', \''+row.name+'\', this)" name="'+row.id+'" value="1" checked="checked" />';
					}
					else if(value == 0)
					{
						return '<input type="checkbox" onclick="update(\''+row.id+'\', \''+row.name+'\', this)" name="'+row.id+'" value="1" />';
					}
					else
					{
						return '<a href="javascript:void(0);" id="btn" class="easyui-linkbutton" onclick="update_all(); return false;">登録</a>';
					}
				}},
			{field:'name',title:'オプション名',width:90},
			{field:'body',title:'オプション説明',width:220},
			{field:'price',title:'価格',width:50, align:'center'},
			{field:'date_time',title:'登録日',width:60, align:'center'},
		]],
		onLoadSuccess:function(data)
		{
			if (check_status(data.status) == false) return false;
			
			if (data.status != STATUS_OK)
			{
				$.messager.alert('データ取得 ERROR !!',　'<p class="messagerAlert">データが取得できませんでした</p>','error');
			}
			
			$('#btn').linkbutton();
		},
		onLoadError:function(test)
		{
			$.messager.alert('データ通信 ERROR !!',　'<p class="messagerAlert">データ読込に失敗しました</p>','error');
		}
	});
}


function update(id, name, my)
{
	var msg;
	var flag;
	
	if($(my).attr('checked') == 'checked')
	{
		msg = '<p>' + name + 'オプションに登録してもよろしいですか？</p><p style="color:red">来月より課金されます。</strong></p>';
		flag = 1;
	}
	else
	{
		msg = '<p>' + name + 'オプションを解除してもよろしいですか？</p>';
		flag = 0;
	}
	
	var back = false;

	$.messager.confirm('確認',msg,function(r)
	{
		if (r)
		{
			$.ajax(
			{
				type:"post",
				url: BASE_URL + 'member/option/update',
				data: {option: id, status : flag},
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status_member(data.status) == false) return false;
					
					if (data.status == STATUS_OK)
					{
						$.messager.show({title:'オプション更新完了',msg:'<p>オプション設定を更新しました</p>',timeout:3000,showType:'slide'});
					}
					else
					{
						$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
					}
					
					$('#tt').datagrid('reload');
				},
				error: function()
				{
					$('#tt').datagrid('reload');
					$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
				}
			});
		}
		else
		{
			$('#tt').datagrid('reload');
		}
	});
}

// フルオプションパック
function update_all()
{
	$.messager.confirm('確認','<p>すべてのオプションに登録してもよろしいですか？</p>',function(r)
	{
		if (r)
		{
			$.ajax(
			{
				type:"post",
				url: BASE_URL + 'member/option/update_all',
				data: $(':checkbox').serialize(),
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status_member(data.status) == false) return false;
					
					if (data.status == STATUS_OK)
					{
						$.messager.show({title:'オプション更新完了',msg:'<p>オプション設定を更新しました</p>',timeout:3000,showType:'slide'});
					}
					else
					{
						$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
					}
					
					$('#tt').datagrid('reload');
				},
				error: function()
				{
					$('#tt').datagrid('reload');
					$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
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


<div region="center" border="true" title="オプション設定">

<table id="tt">
</table>

</div><!--/center-->


<?= $this->load->view('member/common/footer'); ?>
