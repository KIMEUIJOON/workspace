<?= $this->load->view('sp/common/header') ?>
<body>

<div data-role="page" id="map">

<div data-theme="f" data-role="header">
<?php if (!is_app()): ?>
<a href="javascript:close_win();" data-icon="delete">地図を閉じる</a>
<?php endif; ?>
<h1>地図</h1>
</div>

<div data-role="content" style="padding:0;">

<div id="mapCanvas" style="width:100%;height:100%;">
</div>

</div><!--/&content-->
</div><!--/&page-->

<script src="http://maps.googleapis.com/maps/api/js?sensor=true&v=3"></script>
<script>

$(function()
{
	max_height();
	latlng = new google.maps.LatLng(<?= $lat ?>, <?= $lng ?>);
	map_view(latlng);
});


function map_view(latlng)
{
	map = new google.maps.Map(
				document.getElementById("mapCanvas"),{
					zoom : 17,
					center : latlng,
					mapTypeControlOptions : {
						style : google.maps.MapTypeControlStyle.DROPDOWN_MENU
					},
					mapTypeId : google.maps.MapTypeId.ROADMAP
				}
	);

	var marker = new google.maps.Marker({
		position: latlng, /* マーカーを立てる場所の緯度・経度 */
		map: map, /*マーカーを配置する地図オブジェクト */
	});

	//情報ウィンドウを作成
	var infoWnd = new google.maps.InfoWindow();
	infoWnd.setContent('<p style="margin-bottom:5px;line-height: 1.5"><?= $name ?></p><p style="font-size:80%;line-height:1.5"><?= $this->config->item($prefecture, 'prefecture'); ?><?= h($address) ?> <?= h($building) ?><?php if($tel1 || $tel2): ?><br>TEL:<?= ($tel1) ? $tel1:$tel2 ?></p><?php endif; ?></p>');
	infoWnd.open(map, marker);

	google.maps.event.addListener(marker, "click", function(){
		infoWnd.open(map, marker);
	});
}

// マップを全画面表示するためにコンテンツ部分を最大の高さに再設定
function max_height(orientation)
{
    var h = $('div[data-role="header"]').outerHeight(true);
    var w;
    
    if (navigator.userAgent.search(/Android/) != -1)
    {
    	var newHeight;
    	
    	if(orientation == undefined)
    	{
    		w = Math.round(window.outerHeight / window.devicePixelRatio);
    	}
    	else
    	{
			if (orientation == 'portrait')
			{
				newHeight = (window.outerHeight > window.outerWidth)? window.outerHeight : window.outerWidth ;
			}
			else if(orientation == 'landscape')
			{
				newHeight = (window.outerHeight > window.outerWidth)? window.outerWidth : window.outerHeight ;
			}
			
			w = Math.round(newHeight / window.devicePixelRatio);
    	}
    }
    else
    {
    	w = window.innerHeight;
    }

    $('div[data-role="content"]').height(w - h);
}

function close_win(){ 
	var nvua = navigator.userAgent; 
		if(nvua.indexOf('MSIE') >= 0){ 
			if(nvua.indexOf('MSIE 5.0') == -1) { 
				top.opener = ''; 
			} 
		} 
		else if(nvua.indexOf('Gecko') >= 0){ 
			top.name = 'CLOSE_WINDOW'; 
			wid = window.open('','CLOSE_WINDOW'); 
		} 
	top.close(); 
}
</script>

</body>
</html>