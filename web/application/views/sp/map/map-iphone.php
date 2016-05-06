<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= SITE_NAME ?></title>
<base href="<?= CONF_BASE_URL ?>src/sp/">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="../cm/css/normalize.css">
<link rel="stylesheet" href="css/custom.css">
<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
<script src="../cm/js/config.js"></script>
<style>
html, body {width: 100%; height: 100%;}
</style>
</head>

<body>

<div id="map_canvas" style="width:100%;height:100%;"><div style="position: absolute;left: 5px;bottom:10px;width:100px;border:1px solid #000;z-index:999999">aaa</div></div>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true&v=3"></script>
<script src="js/map-iphone.js"></script>
<script type="text/javascript">

$(function()
{
   	initializeGps();
});

function test(str)
{
	alert(str);
}

</script>

</body>
</html>
