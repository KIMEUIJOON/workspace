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

function load()
{
	$('#tt').datagrid(
	{
		url: BASE_URL + 'admin/shop/load',
		toolbar: '#toolbar',
		title: '携帯電話ショップ一覧',
		fit:true,
		//iconCls:'icon-save',
		nowrap: true,
		fitColumns: true,
		pagination: true,
		pageSize: 50,
		pageList: [10,20,30,40,50,100],
		autoRowHeight:false,
		idField: 'id',
		singleSelect: true,
		striped: true,
		columns:[[
			{field:'opt',title:'編集',width:50,align:'center',
				formatter:function(value,rowData)
				{
					return '<a href="javascript:void(0)" class="btn" plain="true" iconCls="icon-edit" onclick="edit_window('+rowData.id+'); return false;"></a>';
				}
			},
			{field:'id',title:'ID',width:70, align:'center', sortable: true},
			{field:'carrier',title:'キャリア',width:80, align:'center', sortable: true},
			{field:'name',title:'ショップ名',width:280, formatter: escapeHTML},
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

// ショップ新規登録 window
function add_window()
{
	$.ajaxSetup({
	   timeout : 2000,
	   error: function(xhr)
	   {
	   	   $('#win').window('close');
	   	   $.messager.alert('データ通信 ERROR !!',　'<p class="messagerAlert">データ読込に失敗しました<br />' + xhr.statusText + '</p>','error');
	   }
	});
		
	$('#win').window(
	{
		title: '　新規ショップ登録',
		iconCls : "icon-add",
		width:700,
		collapsible: false,
		minimizable: false,
		maximizable: false,
		height:$(window).height() - 30,
		modal:true,
		href: BASE_URL + 'admin/shop/add_form',
		cache: false,
		onLoad:function(data, status, xhr)
		{
			if(xhr.statusText == STATUS_NG)
			{
				var obj = JSON.parse(data);
				if (check_status(obj.status) == false) return false;
				
				$('#win').window('close');
				$.messager.alert('ERROR エラー !!', '<p class="error">' + obj.message + '</p>','error');
			}

			$('#tb').tabs(
			{
				fit:true,
				border:false,
				plain:true,
				tools:[
					{  
        				iconCls:'icon-save',
        				text: '新規ショップ登録',
        				plain: true,
        				handler:function(){add();}
    				},
					{  
        				iconCls:'icon-cancel',
        				text: '閉じる',
        				plain: true,
        				handler:function(){$('#win').window('close');}
    				}
    			]
			});
		}
	});
}


// ショップ編集 window
function edit_window(id)
{
	$.ajaxSetup({
	   timeout : 2000,
	   error: function(xhr)
	   {
	   	   $('#win').window('close');
	   	   $.messager.alert('データ通信 ERROR !!',　'<p class="messagerAlert">データ読込に失敗しました<br />' + xhr.statusText + '</p>','error');
	   }
	});
		
	$('#win').window(
	{
		title: '　ショップ情報更新',
		iconCls : "icon-save",
		width:700,
		collapsible: false,
		minimizable: false,
		maximizable: false,
		height:$(window).height() - 30,
		modal:true,
		href: BASE_URL + 'admin/shop/edit/' + id,
		cache: false,
		onLoad:function(data, status, xhr)
		{
			if(xhr.statusText == STATUS_NG)
			{
				var obj = JSON.parse(data);
				if (check_status(obj.status) == false) return false;
				
				$('#win').window('close');
				$.messager.alert('ERROR エラー !!', '<p class="error">' + obj.message + '</p>','error');
			}
			
			$('#tb').tabs(
			{
				fit:true,
				border:false,
				plain:true,
				tools:[
					{  
        				iconCls:'icon-save',
        				text: 'ショップ情報更新',
        				plain: true,
        				handler:function(){update();}
    				},
					{  
        				iconCls:'icon-cancel',
        				text: '閉じる',
        				plain: true,
        				handler:function(){$('#win').window('close');}
    				}
    			]
			});
		}
	});
}


// 新規登録処理
function add()
{
	loading_start();

	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'admin/shop/add',
		data: $("form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status(data.status) == false) return false;
			
			loading_end();
			
			if (data.status == STATUS_OK)
			{
				$('#win').window('close');
				
				$.messager.show({title:'新規登録完了',msg:'<p>ショップを新規登録しました</p>',timeout:3000,showType:'slide'});
				
				// ショップ一覧情報をリロード
				$('#tt').datagrid('reload');
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し登録できませんでした</p>','error');
		}
	});
}

// 基本情報更新
function update()
{
	loading_start();
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'admin/shop/update',
		data: $("form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'更新完了',msg:'<p>基本情報を更新しました</p>',timeout:3000,showType:'slide'});
				
				// ショップ一覧情報をリロード
				$('#tt').datagrid('reload');
				
				$('#win').window('refresh');
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
		},
		error: function()
		{
			loading_end();
			
			$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
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
<div id="toolbar" style="padding:7px">
<a href="javascript:void(0);" onclick="add_window(); return false;" class="easyui-linkbutton" iconCls="icon-add" plain="true">ショップ新規登録</a>　　
<input id="ss" class="easyui-searchbox" style="width:300px" searcher="qq" prompt="検索キーワードを入力" menu="#mm"></input>
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