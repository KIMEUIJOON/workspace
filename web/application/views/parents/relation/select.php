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
		url: BASE_URL + 'parents/relation/load',
		toolbar: '#toolbar',
		title: '編集ショップ選択',
		fit:true,
		//iconCls:'icon-save',
		nowrap: false,
		fitColumns: true,
		autoRowHeight:false,
		//idField: 'id',
		singleSelect: true,
		striped: true,
		columns:[[

			{field:'choice',title:'選択',width:30,align:'center',
				formatter: function(value,row,index){
					if(value == 1)
					{
						return '<input type="radio" onchange="shop_select(\''+row.id+'\', \''+row.name+'\', this)" value="'+row.id+'" name="shop" checked="checked" />';
					}
					else
					{
						return '<input type="radio" onchange="shop_select(\''+row.id+'\', \''+row.name+'\', this)" value="'+row.id+'" name="shop" />';
					}
				}
			},
			{field:'carrier',title:'キャリア',width:70, align:'center', sortable: true},
			{field:'name',title:'ショップ名',width:260, formatter: escapeHTML},
			{field:'post_code',title:'郵便番号',width:80, align:'center'},
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


function shop_select(id, name, my)
{
	var msg;
	
	if($(my).attr('checked') == 'checked')
	{
		msg = '<p class="error">' + name + '<br />を選択してもよろしいですか？</p>';
	}
	
	$.messager.confirm('確認',msg,function(r)
	{
		if (r)
		{
			$.ajax(
			{
				type:"post",
				url: BASE_URL + 'parents/relation/choice',
				data: {shop_id: id},
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status_member(data.status) == false) return false;
					
					if (data.status == STATUS_OK)
					{
						$('body').animate({
							'opacity' : 0
						}, 200, function() {
							location.href = BASE_URL + 'member/basic';
						});
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

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('member/common/north'); ?>

<?= $this->load->view('member/common/west'); ?>


<div region="center" border="false">

<table id="tt">
<div id="toolbar" style="padding:4px;height:auto">
<p>選択中ショップ名： <span id="parent_name" style="color:blue;font-weight:bold"><?= (isset($_SESSION['member']['shop_name'])) ? $_SESSION['member']['shop_name'] : '' ?></span></p>
</div>
</table>

</div><!--/center-->


<?= $this->load->view('member/common/footer'); ?>
