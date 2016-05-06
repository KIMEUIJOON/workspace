<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	event_image_upload();
	
	$('#tt').tabs(
	{
		fit:true,
		border:false,
		plain:true,
		tools:'#tab-tools',
	    onSelect:function(title)
	    {
	    	if(title == '写真・画像')
	    	{
	        	$('#tab-tools span.l-btn-text').text('写真・画像 更新　');
	        	$('#update_btn').attr("onclick","update_image(); return false;");
	        }
	        else if(title == '動画 (YouTube)')
	        {
	        	$('#tab-tools span.l-btn-text').text('動画 (YouTube) 更新　');
	        	$('#update_btn').attr("onclick","update_movie(); return false;");
	        }
	    }
	});
	
	attentions('写真・動画', <?= $op8 ?>);
});

// 更新
function update_image()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/photos/update_image',
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
				$.messager.show({title:'写真・動画更新完了',msg:'<p>写真・動画を更新しました</p>',timeout:3000,showType:'slide'});
				
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

function update_movie()
{
	loading_start();
	$('#update_btn').linkbutton('disable');
	
	$.ajax(
	{
		type:"post",
		url: BASE_URL + 'member/photos/update_movie',
		data: $("form.movie_form").serialize(),
		dataType: 'json',
		cache: false,
		success: function(data, textStatus)
		{
			if (check_status_member(data.status) == false) return false;
			
			loading_end();
			$('#update_btn').linkbutton('enable');
			
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'動画 (YouTube)更新完了',msg:'<p>動画 (YouTube)を更新しました</p>',timeout:3000,showType:'slide'});
				
				$("#youtube_area").html($("#youtube").val());
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
				url: BASE_URL + 'member/photos/del_photo',
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

</script>
</head>

<body class="easyui-layout">

<?= $this->load->view('member/common/north'); ?>

<?= $this->load->view('member/common/west'); ?>

<div region="center" border="true" title="オプション８ (写真・動画)" style="padding-top: 5px;">




<div id="tt">

<div title="写真・画像" style="padding:10px;">

<form class="image_form" method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<?php for($i = 1; $i <= SHOP_IMAGE_LIMIT; $i++): ?>

<tr>
<th>画像<?= $i ?></th>
<td>

<input type="file" size="40" name="img<?= $i ?>" data-number='<?= $i ?>' />
<input type="hidden" class="img_temp" name="img_temp_src<?= $i ?>" value="" />
<div style="position:relative;" id="image<?= $i ?>">
<?php if (isset($photos[$i])): ?>
<img src="<?= get_image_url($_SESSION['member']['shop_id'], $i, $photos[$i]['ext'], IMAGE_NAME_PHOTO, IMAGE_MIDDLE_NAME, $photos[$i]['time']) ?>" width="200" />
<a href="javascript:void(0);" onclick="del_photo(<?= $i ?>); return false;" style="position:absolute; top:20px; left:220px" class="easyui-linkbutton" iconCls="icon-no">画像削除</a>
<?php endif; ?>
</div>

</td>
</tr>

<?php endfor; ?>

</table>
</form>
</div><!--@TAB1-->
	
<div title="動画 (YouTube)" style="padding:10px;">

<form class="movie_form" method="post" style="padding:10px 10px 20px 15px">

<label>YouTube 動画 埋め込みコード</label>
<input type="text" style="width:97%;" id="youtube" name="youtube" value="<?= h($youtube) ?>" />

<div id="youtube_area" style="margin: 10px 0 10px 0"><?= $youtube ?></div>
<div style="margin-bottom:10px"><span class="help-block" style="color:red">※正しい埋め込みコードを登録するとこちらに動画が表示されます</span></div>

<div class="alert alert-success">
<strong>YouTube埋め込みコードサンプル</strong>
<p>
<?= h('<iframe width="560" height="315" src="http://www.youtube.com/embed/DMMLJ7rnrQg?rel=0" frameborder="0" allowfullscreen></iframe>') ?>
</p>
	
<p><a href="http://support.google.com/youtube/bin/answer.py?hl=ja&answer=171780" target="_blank" style="color:blue">YouTube 動画 埋め込みコードとは？</a></p>
</div>

</form>

</div><!--@TAB2-->
	
</div><!--.easyui-tabs-->

<div id="tab-tools">
<a href="javascript:void(0);" id="update_btn" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="update_image(); return false;">更新</a>
</div>

</div><!--/center-->

<?= $this->load->view('member/common/footer'); ?>
