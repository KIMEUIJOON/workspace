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
<li><a href="#p-setup" data-inline="true" data-rel="dialog" data-transition="slideup">設定</a></li>
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


<div data-role="page" id="p-setup">

<div data-role="header" data-theme="f" data-position="inline">

<h1>絞込み設定</h1>
<a href="#" id="save" data-map="ok" data-icon="check">設定</a>
</div><!--/&header-->

<div data-role="content">

<nav class="listview-slider">
<ul>
<?php foreach($this->config->item('carrier') as $k => $name): ?>
<li>
<span><img src="<?= src_url('img/setup/'.$k.'.png') ?>" width="30" /></span><span><?= $name ?></span>
<div class="switch">
<?= form_dropdown($k, $this->config->item('on_off'), '', 'id="'.$k.'" class="carrier" data-role="slider" data-mini="true"') ?>
</div>
</li>
<?php endforeach; ?>
</ul>
</nav><!--/.listview-slider-->

</div><!--/&content-->

</div><!--/&page-->


</body>
</html>
