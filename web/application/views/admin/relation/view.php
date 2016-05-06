<?= $this->load->view('admin/common/head'); ?>

<script type="text/javascript">
	
$(function()
{
	load();
});

function qq(value,name)
{
	var obj = new Object();
	obj[name] = value;

	$('#tt').datagrid('load',
	{
		search: obj
	});
}

function update_relation(id, name, my)
{
	var parent_name = $('#parents_name').text();
	
	var msg;
	var flag;
	
	if($(my).attr('checked') == 'checked')
	{
		msg = '<p class="error">親: ' + parent_name + '<br />子: '+ name +'<br /><br /><span style="color:blue">関連設定</span>してもよろしいですか？</p>';
		flag = 1;
	}
	else
	{
		msg = '<p class="error">親: ' + parent_name + '<br />子: '+ name +'<br /><br /><span style="color:red">関連解除</span>してもよろしいですか？</p>';
		flag = 0;
	}
	
	if(!parent_name)
	{
		toggleCheckbox(flag, id);
		$.messager.alert('ERROR エラー !!', '<p class="error">親ショップが選択されていません</p>','error');
		return;
	}

	$.messager.confirm('確認',msg,function(r)
	{
		if (r)
		{
			$.ajax(
			{
				type:"post",
				url: BASE_URL + 'admin/relation/update',
				data: {child_id: id, status : flag},
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status_member(data.status) == false) return false;
					
					if (data.status == STATUS_OK)
					{
						$.messager.show({title:'関連設定完了',msg:'<p>ショップ関連設定を更新しました</p>',timeout:1500,showType:'slide'});
					}
					else
					{
						toggleCheckbox(flag, id);
						$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
					}
					
					//$('#tt').datagrid('reload');
				},
				error: function()
				{
					//$('#tt').datagrid('reload');
					toggleCheckbox(flag, id);
					$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
				}
			});
		}
		else
		{
			toggleCheckbox(flag, id);
		}
	});
}

function toggleCheckbox(flag, id)
{
	if (flag == 1)
	{
		$('input[name='+id+']').removeAttr('Checked'); 
	}
	else
	{
		$('input[name='+id+']').attr('Checked','Checked');
	}
}

function load()
{
	$('#tt').datagrid(
	{
		url: BASE_URL + 'admin/relation/load',
		toolbar: '#toolbar',
		title: '関連ショップ設定',
		fit:true,
		//iconCls:'icon-save',
		nowrap: false,
		fitColumns: true,
		pagination: true,
		pageSize: 50,
		pageList: [10,20,30,40,50,100],
		autoRowHeight:false,
		//idField: 'id',
		singleSelect: true,
		striped: true,
		columns:[[
			{field:'parents_id',title:'設定',width:30,align:'center',
				formatter: function(value,row,index){
					if(value > 1)
					{
						return '<input type="checkbox" onclick="update_relation(\''+row.id+'\', \''+row.name+'\', this)" name="'+row.id+'" value="1" checked="checked" />';
					}
					else
					{
						return '<input type="checkbox" onclick="update_relation(\''+row.id+'\', \''+row.name+'\', this)" name="'+row.id+'" value="1" />';
					}
				}
			},
			{field:'id',title:'ID',width:50, align:'center', sortable: true},
			{field:'carrier',title:'キャリア',width:70, align:'center', sortable: true},
			{field:'name',title:'ショップ名',width:260, formatter: escapeHTML},
			{field:'prefecture',title:'都道府県',width:80, align:'center', sortable: true},
			{field:'tel1',title:'電話番号',width:80, align:'center'}
		]],
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

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('admin/common/north'); ?>

<?= $this->load->view('admin/common/west'); ?>


<div region="center" border="false">

<table id="tt">
<div id="toolbar" style="padding:7px;height:auto">
<input id="ss" class="easyui-searchbox" style="width:300px" searcher="qq" prompt="検索キーワードを入力" menu="#mm"></input>
<p style="margin:10px 0 5px 5px">親ショップ名： <span id="parents_name" style="color:blue;font-weight:bold"><?= (isset($_SESSION['admin']['parents_name'])) ? $_SESSION['admin']['parents_name'] : '' ?></span></p>
</div>
</table>
</div><!--/center-->

<div class="none" style="display: none;">
<div id="win"></div>

<div id="mm" style="width:120px">
<?php foreach($this->search_keys as $k => $v): ?>
<div name="<?= $k ?>"><?= $v ?></div>
<?php endforeach; ?>
</div><!--#mm-->

</div><!--.none-->

<?= $this->load->view('admin/common/footer'); ?>