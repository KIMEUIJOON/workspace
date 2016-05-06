// ステータスをチェック
function check_status(status)
{
	if(status == STATUS_EXPIRE)
	{
		location.href = BASE_URL + 'admin/login?ser=1';
		return false;
	}
	
	return true;
}

// メンバーステータスをチェック
function check_status_member(status)
{
	if(status == STATUS_EXPIRE)
	{
		location.href = BASE_URL + 'member/login?ser=1';
		return false;
	}
	
	return true;
}

// alertウィンドウのwidthを変更
function set_messager_alert_width(width)
{
	$(".messager-window").css("width", width);
	$(".messager-body").css("width", 'auto');
	$(".messager-window .panel-header").css("width", 'auto');
	$(".messager-window").next(".window-shadow").css("width", width+12);
}

function loading_start()
{
   	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
				
	$('<div id="loading">').css({
					"position" : "absolute",
					"left" : windowWidth / 2 - 100 / 2,
					"top" : windowHeight /2 - 100 / 2
				}).appendTo("body");
					
	//$('body').css({opacity:0.2});
};

function loading_end()
{
   	$("#loading").remove();
};

// 郵便番号より住所を取得
function zip_code_address()
{
	if($("#zip_code").val().match(/^\d{3}(\s*|-)\d{4}$/))
	{
		$.ajax(
		{
			type:"post",
			url: BASE_URL + 'api/zip_code/address',
			data: {code:$("#zip_code").val()},
			dataType: 'json',
			cache: false,
			success: function(data, textStatus)
			{
				if (data.status == STATUS_OK)
				{
					$('#prefecture').val(data.prefecture);
					$('#address').val(data.address);
				}
				else
				{
					$.messager.alert('ERROR エラー !!', '<p>' + data.message + '</p>','error');
				}
			},
			error: function()
			{
				$.messager.alert('ERROR エラー !!', '<p>通信エラーが発生し住所取得できませんでした</p>','error');
			}
		});
	}
	else
	{
		$.messager.alert('ERROR エラー !!', '<p>郵便番号の形式が正しくありません</p>','error');
	}
}

// 画像非同期アップロードイベント
function event_image_upload()
{
	$('.image_form input[type=file]').change(function()
	{
		$(this).upload(BASE_URL + 'common/upload/image', {'key': $(this).attr("name")}, function(res, tes)
		{
			if (res.status == STATUS_OK)
			{
				$("#image" + $(this).data('number')).empty();
				$('<img />').attr('src', res.image_url).attr('width', 200).appendTo("#image" + $(this).data('number'));
				$("input[name=img_temp_src" + $(this).data('number')+"]").val(res.file_name);
			}
			else
			{
				$.messager.alert('ERROR エラー !!', '<p class="error">' + res.message + '</p>','error');
						
				// alertウィンドウのwidthを変更
				//set_messager_alert_width(450);
			}

		}, 'json');
	});
}

function map_confirm()
{
	var lat = $("#plat").val();
	var lng = $("#plng").val();
	var address = $("#address").val();
	var url = BASE_URL + 'src/html/map.html';
	
	var w_size=640;
	var h_size=530;
	var l_position=Number((window.screen.width-w_size)/2);
	//var t_position=Number((window.screen.height-h_size)/2);
	var t_position=Number((window.screen.height-h_size)/3);

	if (lat != '' & lng != '')
	{
		url = url + '?lat=' + lat + '&lng=' + lng;
	}
	else if(address != '')
	{
		url = url + '?address=' + encodeURIComponent(address);
	}
	
	window.open(url, 'gmap', 'width='+w_size+', height='+h_size+', left='+l_position+', top='+t_position+', menubar=no, toolbar=no, scrollbars=no');
}

function getQueryParams()
{
	var qs=location.search;
	
	if (qs)
	{
		var qsa=qs.substring(1).split('&');
		var params={};
		for(var i=0; i<qsa.length; i++)
		{
			var pair=qsa[i].split('=');
			if (pair[0])
			{
				params[pair[0]]=decodeURIComponent(pair[1]);
			}
		}
		return params;
	}
	
	return null;
}

function escapeHTML(str)
{
	str = str.replace(/&/g,"&amp;");
	str = str.replace(/"/g,"&quot;");
	str = str.replace(/'/g,"&#39;");
	str = str.replace(/</g,"&lt;");
	str = str.replace(/>/g,"&gt;");

	return str;
}

// 文字数カウント
function strLength(strSrc)
{
	len = 0;
	strSrc = escape(strSrc);
	for(i = 0; i < strSrc.length; i++, len++){
		if(strSrc.charAt(i) == "%"){
			if(strSrc.charAt(++i) == "u"){
				i += 3;
				len++;
			}
			i++;
		}
	}
	
	return Math.ceil(len / 2);
}

(function(jQuery)
{
	jQuery.fn.fmreset = function()
	{
		return $(this).find(':input').each(function()
		{
			switch (this.type)
			{
				case 'password':
				case 'select-multiple':
				case 'select-one':
				case 'text':
				case 'textarea':
					$(this).val('');
					break;
				case 'checkbox':
				case 'radio':
					this.checked = false;
					break;
				case 'hidden':
					break;
			}
		});
	};
})(jQuery);

function showObject(elm,type)
{
	var str = '「' + typeof elm + "」の中身";
	var cnt = 0;
	for(i in elm)
	{
		if(type == 'html')
		{
			str += '<br />\n' + "[" + cnt + "] " + i.bold() + ' = ' + elm[i];
		}
		else 
		{
			str += '\n' + "[" + cnt + "] " + i + ' = ' + elm[i];
		}
		cnt++;
		status = cnt;
	}
	
	return str;
}


function nl2br(str, is_xhtml)
{
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}