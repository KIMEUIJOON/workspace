function escapeHTML(str)
{
	str = str.replace(/&/g,"&amp;");
	str = str.replace(/"/g,"&quot;");
	str = str.replace(/'/g,"&#39;");
	str = str.replace(/</g,"&lt;");
	str = str.replace(/>/g,"&gt;");

	return str;
}

function makeUrlParameter(pram_obj)
{
	var pram = '';
	
	$.each(pram_obj, function(key, value)
	{
		if(pram) pram = pram + '&';
		pram = pram + key + '=' + value;
	});

	return pram + getStorageCarrierParameter();
}

function getStorageCarrierParameter()
{
	var pram = '';
	var object;
	
	var privatemode = false;
	try { localStorage.setItem('check','') }
	catch(e) { privatemode = true }
	
	if (privatemode != true)
	{
		if(localStorage.getItem(STORAGE_KEY.CARRIER) != null)
		{
			object = JSON.parse(localStorage.getItem(STORAGE_KEY.CARRIER));
			
			$.each(object, function(key, value)
			{
				// オフの設定値の場合のみセット
				if(value == 2)
				{
					if(pram) pram = pram + '&';
					pram = pram + key + '=' + value;
				}
			});
			
			if(pram) pram = '&' +pram;
		}
	}
	else
	{
		if($.cookie(STORAGE_KEY.CARRIER) != null)
		{
			object = JSON.parse($.cookie(STORAGE_KEY.CARRIER));
			
			$.each(object, function(key, value)
			{
				// オフの設定値の場合のみセット
				if(value == 2)
				{
					if(pram) pram = pram + '&';
					pram = pram + key + '=' + value;
				}
			});
			
			if(pram) pram = '&' +pram;
		}
	}
	
	return pram;
}

$(document).on("pagebeforecreate", "#p-setup", function (e)
{
	var latlng;
	var ios = false;
	var object;
	
	var agent = navigator.userAgent;
	
	if(agent.search(/iPhone/) != -1 || agent.search(/iPad/) != -1 || agent.search(/iPod/) != -1)
	{
		ios = true;
	}
	
	var privatemode = false;
	try { localStorage.setItem('check','') }
	catch(e) { privatemode = true }
	
	if (privatemode != true)
	{
		if(localStorage.getItem(STORAGE_KEY.CARRIER) != null)
		{
			object = JSON.parse(localStorage.getItem(STORAGE_KEY.CARRIER));
			
			$.each(object, function(key, value)
			{
			   $('select[name="'+key+'"]').val(value);
			});
		}
		else
		{
			// 設定がされていない場合は、すべてオン
			$('select.carrier').val(1);
		}
	}
	else
	{
		if($.cookie(STORAGE_KEY.CARRIER) != null)
		{
			object = JSON.parse($.cookie(STORAGE_KEY.CARRIER));
			
			$.each(object, function(key, value)
			{
			   $('select[name="'+key+'"]').val(value);
			});
		}
		else
		{
			// 設定がされていない場合は、すべてオン
			$('select.carrier').val(1);
		}
	}
	
	$(this).on("vclick", "#save", function(e)
	{
		var json = {};
		
		$('select.carrier').each(function(i)
		{
			json[$(this).attr('name')] = $(this).val();
		});
		
		var stringJSON = JSON.stringify(json);
		
		if (privatemode != true)
		{
			// ローカルストレージに保存
			localStorage.setItem(STORAGE_KEY.CARRIER, stringJSON);
		}
		else
		{
			$.cookie(STORAGE_KEY.CARRIER, stringJSON);
		}
		
		$('.ui-dialog').dialog('close');
		
		if($(this).data('map') == 'ok')
		{
			if(ios)
			{
				location.href = BASE_URL + 'sp/map?lat='+map_center.lat()+'&lng=' + map_center.lng();
			
			}
			else
			{
				reloadShopThisPlace();
			}
		}
	});
	
	if(ios && $("#save").data('map') == 'ok')
	{
		$(this).on("vclick", "a[data-icon='delete']", function(e)
		{
			location.href = BASE_URL + 'sp/map?lat='+map_center.lat()+'&lng=' + map_center.lng();
		});
	}
});

$(document).on("pagebeforecreate", "#p-pref, #p-area, #p-town", function (e)
{
	var pram = getStorageCarrierParameter();

	$('ul>li>a').each(function(i)
	{
		var url = $(this).attr('href') + pram;
		$(this).attr('href', url);
	});
});

// オリジナルのセレクトメニュー
$(document).on('pageinit', '#review', function(){
  var select = $(this).find('#review-level');
  select.on('change', function(){
    $('#review-level-button').removeClass('level-1 level-2 level-3 level-4 level-5').addClass('level-' + select.find('option:selected').val());
  });
});


$(document).on("pageinit", "#detail", function (e)
{
	$(".jCarousel").hide();
	setTimeout(function()
	{
			$(".jCarousel").show();
			jCarousel.ini();
			jCarousel.set();
	}, 500);
});
