<?= $this->load->view('sp/common/header') ?>
<body>

<div data-role="page" id="p-map">
	
<div data-role="header" data-theme="f">
<a href="javascript:void(0)" onclick="moveToGpsLocation(true); return false;">現在地</a>
<h1><?= SITE_NAME ?></h1>
<a href="<?= CONF_BASE_URL_SP ?>" data-role="button" data-icon="home" data-iconpos="notext">Home</a>
</div>

<div data-role="content" style="padding:0;">
<div id="map_canvas" style="width:100%;height:100%;"></div>
</div>

<div data-role="footer">
<div data-role="navbar">
<ul>
<li><a href="javascript:void(0)" onclick="reloadShopThisPlace(); return false;">ここで再検索</a></li>
<li><a href="<?= base_path('sp/place/pref') ?>" rel="external">地域へ移動</a></li>
<li><a href="<?= base_path('sp/setup/carrier') ?>?map=1" data-rel="dialog" onclick="setMapCenter()">設定</a></li>
</ul>
</div><!-- /navbar -->
</div><!-- /footer -->

</div><!-- /page -->

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true&v=3"></script>
<script src="js/map2.js"></script>
<script type="text/javascript">

$(function()
{
	//alert(screen.height + " " + window.outerHeight + " " + window.innerHeight + " " + screen.width + " " + window.outerWidth + " " + window.innerWidth);
	var prams = getUrlVars();
	
	if(prams["mode"] == 'location')
	{
    	initializeGps();
    }
    else if(prams["mode"] == 'area' || prams["mode"] == 'town')
    {
    	initializeArea(prams["mode"], prams["code"]);
    }
    else if(prams["lat"] !== undefined && prams["lng"] !== undefined)
    {
    	initializeLatLng(prams["lat"], prams["lng"]);
    }
    else
    {
    	initializeGps();
    }
    
	$(window).on("orientationchange", function(e){
		window.scrollTo(0,1);
		max_height(e.orientation);
		//setTimeout(scrollTo, 100, 0, 1);
		//alert(screen.height + " " + window.outerHeight + " " + window.innerHeight + " " + screen.width + " " + window.outerWidth + " " + window.innerWidth);
	});
});

</script>

</body>
</html>
