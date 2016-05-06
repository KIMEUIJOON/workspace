//var BASE_URL = 'http://localhost/iju5/';
var BASE_URL = 'http://'+location.hostname+'/';
var STATUS_OK = 'OK';
var STATUS_NG = 'NG';
var STATUS_EXPIRE = 'expire';

function getUrlVars()
{
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

// 文字数チェック
function strLengthCheck(maxLength, target, btn)
{
	if(typeof btn === 'undefined') btn = '#update_btn';
	
	var cnt = 0;
	var msg;

	cnt = $(target).val().length;

	strLengthView(cnt, maxLength, target, btn);

	$(target).on("keyup change paste", function() {
	    cnt = $(target).val().length;
	    strLengthView(cnt, maxLength, target, btn);
	});
}

// 文字数を表示
function strLengthView(cnt, maxLength, target, btn)
{
	if(typeof btn === 'undefined') btn = '#update_btn';
	
	if(maxLength >= cnt)
	{
		msg = '文字数：' + cnt;
		$(target).next('p').css("color","Black").text(msg);
		$(target).attr('data-check', 'off');
	}
	else
	{
		msg = '文字数：' + cnt + ' ※' + maxLength + '文字以内で入力してください';
		$(target).next('p').css("color","red").text(msg);
		$(target).attr('data-check', 'on');
	}
	
		if($(target).attr('data-check') == "off")
		{
			$(btn).linkbutton('enable');
		}
		else
		{
			$(btn).linkbutton('disable');
		}
}