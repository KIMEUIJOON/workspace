<?= $this->load->view('admin/common/head'); ?>

<script type="text/javascript">

$(function()
{
	event_image_upload();
	
	$('#tt').tabs(
	{
		fit:true,
		border:false,
		plain:true,
		tools:'#tab-tools'
	});
});

// 更新
function update_image()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'admin/banner/update_image',
		data: $("form.image_form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status(data.status) == false) return false;
			
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'バナー広告更新完了',msg:'<p>バナー広告を更新しました</p>',timeout:3000,showType:'slide'});
				
				// アップロードが完了したら img_temp_srcの値を空にする
				$('.img_temp').val('');
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

// 画像削除
function del_photo(num)
{
	$.messager.defaults={ok:"Ok",cancel:"Cancel"};
	
	$.messager.confirm('確認','<p>画像を削除しますか?</p>',function(r)
	{
		if (r)
		{
			loading_start();
			
			$.ajax(
			{
				type:"post",
				url: BASE_URL + 'admin/banner/del_photo',
				data: {number:num},
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status(data.status) == false) return false;
					
					loading_end();
					
					if (data.status == STATUS_OK)
					{
						$.messager.show({title:'削除完了',msg:'<p>画像を削除しました</p>',timeout:3000,showType:'slide'});
						$("#image" + num).empty();
					}
					else
					{
						$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
					}
				},
				error: function()
				{
					loading_end();
					$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し削除できませんでした</p>','error');
				}
			});
		}
	});
}

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('admin/common/north'); ?>

<?= $this->load->view('admin/common/west'); ?>

<div region="center" border="true" title="バナー広告登録" style="padding-top: 5px;">




<div id="tt">

<div title="バナー(468×60)" style="padding:10px;">

<form class="image_form" method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<?php for($i = 1; $i <= SHOP_IMAGE_LIMIT; $i++): ?>

<tr>
<th>画像<?= $i ?></th>
<td>

<input type="file" size="40" name="img<?= $i ?>" data-number='<?= $i ?>' />
<input type="hidden" class="img_temp" name="img_temp_src<?= $i ?>" value="" />
<div style="position:relative;" id="image<?= $i ?>">
<?php if (isset($photos[$i]['ext'])): ?>
<img src="<?= get_banner_url($i, $photos[$i]['ext'], $photos[$i]['time']) ?>" height="60" />
<a href="javascript:void(0);" onclick="del_photo(<?= $i ?>); return false;" style="position:absolute; top:20px; left:490px" class="easyui-linkbutton" iconCls="icon-no">画像削除</a>
<?php endif; ?>
</div>

</td>
</tr>

<tr>
<th>リンクURL<?= $i ?></th>
<td><input type="text" style="width:97%;" name="link_url<?= $i ?>" value="<?= (isset($photos[$i]['url'])) ? h($photos[$i]['url']) : ''?>" /></td>
</tr>

<?php endfor; ?>

</table>
</form>
</div><!--@TAB1-->
	
</div><!--.easyui-tabs-->

<div id="tab-tools">
<a href="javascript:void(0);" id="update_btn" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="update_image(); return false;">バナー広告(468×60) 更新</a>
</div>

</div><!--/center-->

<?= $this->load->view('admin/common/footer'); ?>
