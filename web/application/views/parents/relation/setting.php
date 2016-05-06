<?= $this->load->view('member/common/head'); ?>

<script type="text/javascript">

$(function()
{
	strLengthCheck(<?= CAMPAIGN_LENGTH ?>, '#campaign', '#campaign_btn');
	strLengthCheck(<?= CAMPAIGN_BODY_LENGTH ?>, '#campaign_body', '#campaign_body_btn');
	strLengthCheck(<?= COUPON_LENGTH ?>, '#coupon_body', '#coupon_body_btn');
	
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

	// タブ生成
	$('#tt').tabs(
	{
		fit:true,
		border:false,
		plain:true
	});
});

// キャンペーン概要
function campaign_update()
{
	var msg = '<p class="error">すべてのショップにキャンペーン概要を一括設定してもよろしいですか？</p>';
	
	$.messager.confirm('確認',msg,function(r)
	{
		if (r)
		{

		loading_start();
		$('#campaign_btn').linkbutton('disable');
		
		$.ajax(
		{
			type:"post",
			url: BASE_URL + 'parents/relation/campaign_update',
			data: $("form#form_campaign").serialize(),
			dataType: 'json',
			cache: false,
			success: function(data, textStatus)
			{
				if (check_status_member(data.status) == false) return false;
				
				loading_end();
				$('#campaign_btn').linkbutton('enable');
				
			if (data.status == STATUS_OK)
			{
				$.messager.show({title:'キャンペーン更新完了',msg:'<p>キャンペーン概要を一括設定しました</p>',timeout:2000,showType:'slide'});
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
			}
			},
			error: function()
			{
				loading_end();
				$('#campaign_btn').linkbutton('enable');
				$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
			}
		});

		}
	});
}

// キャンペーン内容
function campaign_body_update()
{
	var msg = '<p class="error">すべてのショップにキャンペーン内容を一括設定してもよろしいですか？</p>';
	
	$.messager.confirm('確認',msg,function(r)
	{
		if (r)
		{

		loading_start();
		$('#campaign_body_btn').linkbutton('disable');
		
		$.ajax(
		{
			type:"post",
			url: BASE_URL + 'parents/relation/campaign_body_update',
			data: $("form#form_campaign_body").serialize(),
			dataType: 'json',
			cache: false,
			success: function(data, textStatus)
			{
				if (check_status_member(data.status) == false) return false;
				
				loading_end();
				$('#campaign_body_btn').linkbutton('enable');
				
				if (data.status == STATUS_OK)
				{
					$.messager.show({title:'キャンペーン更新完了',msg:'<p>キャンペーン内容を一括設定しました</p>',timeout:2000,showType:'slide'});
				}
				else
				{
					$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
				}
			},
			error: function()
			{
				loading_end();
				$('#campaign_body_btn').linkbutton('enable');
				$.messager.alert('ERROR エラー !!', '<p class="error">通信エラーが発生し更新できませんでした</p>','error');
			}
		});

		}
	});
}

// クーポン
function coupon_body_update()
{
	var msg = '<p class="error">すべてのショップにクーポン情報を一括設定してもよろしいですか？</p>';
	
	$.messager.confirm('確認',msg,function(r)
	{
		if (r)
		{

		loading_start();
		$('#coupon_body_btn').linkbutton('disable');
		
		$.ajax(
		{
			type:"post",
			url: BASE_URL + 'parents/relation/coupon_body_update',
			data: $("form#form_coupon_body").serialize(),
			dataType: 'json',
			cache: false,
			success: function(data, textStatus)
			{
				if (check_status_member(data.status) == false) return false;
				
				loading_end();
				$('#coupon_body_btn').linkbutton('enable');
				
				if (data.status == STATUS_OK)
				{
					$.messager.show({title:'クーポン更新完了',msg:'<p>クーポン情報を一括設定しました</p>',timeout:2000,showType:'slide'});
				}
				else
				{
					$.messager.alert('ERROR エラー !!', '<p class="error">' + data.message + '</p>','error');
				}
			},
			error: function()
			{
				loading_end();
				$('#coupon_body_btn').linkbutton('enable');
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

<div region="center" border="true" title="全ショップ内容一括設定" style="padding-top: 5px;">

<div id="tt">

<div title="キャンペーン概要" style="padding:10px;">

<div style="padding:8px 10px 0 0;text-align:right">
<a href="javascript:void(0);" id="campaign_btn" class="easyui-linkbutton" iconCls="icon-save" onclick="campaign_update(); return false;">キャンペーン概要を一括更新</a>
</div>

<form id="form_campaign" method="post" style="padding:10px 10px 20px 15px">
<table class="form" style="width:100%">

<tr>
<th style="width:120px">公開終了日時</th>
<td><input type="text" class="dd" name="campaign_date" value="" /></td>
</tr>

<tr>
<th>キャンペーン概要<p>※<?= CAMPAIGN_LENGTH ?>文字以内</p></th>
<td><input type="text" style="width:370px" name="campaign" id="campaign" data-check="" value="" /> <p></p></td>
</tr>

</table>
</form>

</div><!--@TAB1-->

<div title="キャンペーン内容" style="padding:10px;">
	
<div style="padding:8px 10px 0 0;text-align:right">
<a href="javascript:void(0);" id="campaign_body_btn" class="easyui-linkbutton" iconCls="icon-save" onclick="campaign_body_update(); return false;">キャンペーン内容を一括更新</a>
</div>

<form id="form_campaign_body" method="post" style="padding:10px 10px 20px 15px">
<table class="form" style="width:100%">

<tr>
<th style="width:120px">公開終了日時</th>
<td><input type="text" class="dd" name="campaign_date" value="" /></td>
</tr>

<tr>
<th>キャンペーン内容<p>※<?= CAMPAIGN_BODY_LENGTH ?>文字以内</p></th>
<td><textarea style="width:97%;line-height: 140%;" name="campaign_body" data-check="" id="campaign_body" rows="8"></textarea> <p></p></td>
</tr>

</table>
</form>
	
</div><!--@TAB2-->

<div title="クーポン" style="padding:10px;">

<div style="padding:8px 10px 0 0;text-align:right">
<a href="javascript:void(0);" id="coupon_body_btn" class="easyui-linkbutton" iconCls="icon-save" onclick="coupon_body_update(); return false;">クーポン情報を一括更新</a>
</div>

<form id="form_coupon_body" method="post" style="padding:10px 10px 20px 15px">
<table class="form" style="width:100%">

<tr>
<th style="width:120px">公開終了日時</th>
<td><input type="text" class="dd" name="coupon_date" value="" /></td>
</tr>

<tr>
<th>クーポン内容<p>※<?= COUPON_LENGTH ?>文字以内</p></th>
<td><textarea style="width:97%;line-height: 140%;" name="coupon_body" data-check="" id="coupon_body" rows="12"></textarea> <p></p></td>
</tr>

</table>
</form>
</div><!--@TAB3-->

</div><!--/center-->

<?= $this->load->view('member/common/footer'); ?>
