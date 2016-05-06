<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	event_image_upload();
	event_pdf_upload();
	
	$('#tt').tabs(
	{
		fit:true,
		border:false,
		plain:true,
		tools:'#tab-tools',
	    onSelect:function(title)
	    {
	    	if(title == 'チラシ画像')
	    	{
	        	$('#tab-tools span.l-btn-text').text('チラシ画像 更新　');
	        	$('#update_btn').attr("onclick","update_image(); return false;");
	        }
	        else if(title == 'チラシPDF')
	        {
	        	$('#tab-tools span.l-btn-text').text('チラシＰＤＦ 更新　');
	        	$('#update_btn').attr("onclick","update_pdf(); return false;");
	        }
	    }
	});
	
	attentions('チラシ・ＰＯＰ', <?= $op5 ?>);
});

// 画像更新
function update_image()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/flyer/update_image',
		data: $("form.image_form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'チラシ画像更新完了',msg:'<p>チラシ画像を更新しました</p>',timeout:3000,showType:'slide'});
				
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

// ＰＤＦ更新
function update_pdf()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/flyer/update_pdf',
		data: $("form.pdf_form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'チラシＰＤＦ更新完了',msg:'<p>チラシＰＤＦを更新しました</p>',timeout:3000,showType:'slide'});
				
				// アップロードが完了したら pdf_temp_srcの値を空にする
				$('.pdf_temp').val('');
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
function del_flyer_image(num)
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
				url: BASE_URL + 'member/flyer/del_flyer_image',
				data: {number:num},
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status_member(data.status) == false) return false;
					
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

// PDF削除
function del_flyer_pdf(num)
{
	$.messager.defaults={ok:"Ok",cancel:"Cancel"};
	
	$.messager.confirm('確認','<p>ＰＤＦを削除しますか?</p>',function(r)
	{
		if (r)
		{
			loading_start();
			
			$.ajax(
			{
				type:"post",
				url: BASE_URL + 'member/flyer/del_flyer_pdf',
				data: {number:num},
				dataType: 'json',
				cache: false,
				success: function(data, textStatus)
				{
					if (check_status_member(data.status) == false) return false;
					
					loading_end();
					
					if (data.status == STATUS_OK)
					{
						$.messager.show({title:'削除完了',msg:'<p>ＰＤＦを削除しました</p>',timeout:3000,showType:'slide'});
						$("#pdf" + num).empty();
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

// PDF非同期アップロードイベント
function event_pdf_upload()
{
	$('.pdf_form input[type=file]').change(function()
	{
		$(this).upload(BASE_URL + 'common/upload/pdf', {'key': $(this).attr("name")}, function(res, tes)
		{
			if (res.status == STATUS_OK)
			{
				$("#pdf" + $(this).data('number')).empty();
				$('<a target="_blank">' + res.pdf_name + '</a>').attr('href', res.pdf_url).appendTo("#pdf" + $(this).data('number'));
				$("input[name=pdf_temp_src" + $(this).data('number')+"]").val(res.file_name);
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + res.message + '</p>','error');
			}

		}, 'json');
	});
}

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('member/common/north'); ?>

<?= $this->load->view('member/common/west'); ?>

<div region="center" border="true" title="オプション５ (チラシ・ＰＯＰ)" style="padding-top: 5px;">




<div id="tt">

<div title="チラシ画像" style="padding:10px;">

<form class="image_form" method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<?php for($i = 1; $i <= SHOP_FLYER_IMAGE_LIMIT; $i++): ?>

<tr>
<th>チラシ画像<?= $i ?></th>
<td>

<input type="file" size="40" name="img<?= $i ?>" data-number='<?= $i ?>' />
<input type="hidden" class="img_temp" name="img_temp_src<?= $i ?>" value="" />
<div style="position:relative;" id="image<?= $i ?>">
<?php if (isset($flyer_image[$i]['ext']) && $flyer_image[$i]['ext']): ?>
<img src="<?= get_image_url($_SESSION['member']['shop_id'], $i, $flyer_image[$i]['ext'], IMAGE_NAME_FLYER, IMAGE_MIDDLE_NAME, $flyer_image[$i]['time']) ?>" width="200" />
<a href="javascript:void(0);" onclick="del_flyer_image(<?= $i ?>); return false;" style="position:absolute; top:20px; left:220px" class="easyui-linkbutton" iconCls="icon-no">画像削除</a>
<?php endif; ?>
</div>

</td>
</tr>

<tr>
<th>リンクテキスト<?= $i ?></th>
<td><input type="text" style="width:97%;" name="link_text<?= $i ?>" value="<?= (isset($flyer_image[$i]['txt'])) ? h($flyer_image[$i]['txt']) : ''?>" /></td>
</tr>
<?php endfor; ?>

</table>
</form>
</div><!--@TAB1-->
	
<div title="チラシPDF" style="padding:10px;">

<form class="pdf_form" method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<?php for($i = 1; $i <= SHOP_FLYER_PDF_LIMIT; $i++): ?>

<tr>
<th>チラシPDF<?= $i ?></th>
<td>

<input type="file" size="40" name="pdf<?= $i ?>" data-number='<?= $i ?>' />
<input type="hidden" class="pdf_temp" name="pdf_temp_src<?= $i ?>" value="" />
<div style="margin:10px 0 6px 0" id="pdf<?= $i ?>">
<?php if (isset($flyer_pdf[$i]['time'])): ?>
<a target="_blank" href="<?= get_pdf_url($_SESSION['member']['shop_id'], $i, IMAGE_NAME_FLYER) ?>" class="easyui-linkbutton" iconCls="icon-ok">PDFを確認</a>
<a href="javascript:void(0);" onclick="del_flyer_pdf(<?= $i ?>); return false;" class="easyui-linkbutton" iconCls="icon-no">PDF削除</a>
<?php endif; ?>
</div>

</td>
</tr>

<tr>
<th>リンクテキスト<?= $i ?></th>
<td><input type="text" style="width:97%;" name="link_text<?= $i ?>" value="<?= (isset($flyer_pdf[$i]['txt'])) ? h($flyer_pdf[$i]['txt']) : ''?>" /></td>
</tr>
<?php endfor; ?>

</table>
</form>

</div><!--@TAB2-->
	
</div><!--.easyui-tabs-->

<div id="tab-tools">
<a href="javascript:void(0);" id="update_btn" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="update_image(); return false;">チラシ画像 更新　</a>
</div>

</div><!--/center-->

<?= $this->load->view('member/common/footer'); ?>
