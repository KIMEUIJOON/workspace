<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width" content="user-scalable=no">
<base href="<?= CONF_BASE_URL ?>" />
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&v=3"></script>
<script type="text/javascript" src="src/cm/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="src/ad/js/common.js"></script>
<script type="text/javascript">

var map,mapDiv,imgObj;

var MyInfoWidth = 200;
var MyInfoHeight = 50;

		function initialize() {
		
			createMap(26.321094, 127.817934);
			loadMarkerIconImage();
							
			getMapDataLoad();
		}

function createMap(lat, lng)
{
	//地図を表示
	var latlng = new google.maps.LatLng(lat, lng);
	var mapOpts = {
		zoom: 16,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	mapDiv = document.getElementById("map_canvas");
	map = new google.maps.Map(mapDiv, mapOpts);
}

function createMarker(latlng, title, carrier)
{
	//マーカーを作成
	var marker = new google.maps.Marker({
		position : latlng,
		map : map,
		icon:imgObj[carrier],
		title : title
	});

	//情報ウィンドウを作成
	/*
	var infoWnd = new google.maps.InfoWindow({
		content : "<strong>" +  + "</strong>"
	});

	infoWnd.open(map, marker);
	*/
	
	var myInfoWnd = new MyInfoWnd();
	myInfoWnd.setContent(title);
	myInfoWnd.open(marker);
	
	return marker;
}

function loadMarkerIconImage()
{
	imgObj = new Object();
		
	//imgObj['d'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(22,22), new google.maps.Point(44, 0), null , new google.maps.Size(110,22));
	imgObj['d'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(44,44), new google.maps.Point(0, 0));
	imgObj['a'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(44,44), new google.maps.Point(44, 0));
	imgObj['s'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(44,44), new google.maps.Point(88, 0));
	imgObj['w'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(44,44), new google.maps.Point(132, 0));
	imgObj['e'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(44,44), new google.maps.Point(176, 0));
	imgObj['h'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(44,44), new google.maps.Point(176, 0));
}

function createMarker2(map, latlng, title, img)
{
	//マーカーを作成
	var marker = new google.maps.Marker({
		position : latlng,
		map : map,
		icon:img,
		title : title
	});

	//情報ウィンドウを作成
	var infoWnd = new google.maps.InfoWindow({
		content : "<strong>" + title + "</strong>"
	});

	infoWnd.open(map, marker);
	
	return marker;
}


// マップデータを取得
function getMapDataLoad()
{
	$.ajax(
	{
		type:"get",
		url: 'api/map/control' + location.search,
		dataType: 'xml',
		cache: false,
		success: function(data, textStatus)
		{
			if ($("status",data).text() == STATUS_OK)
			{
				//createMap($("map > lat",data).text(), $("map > lng",data).text());
				
				$("item",data).each(function () {
					createMarker(new google.maps.LatLng($("lat",this).text(), $("lng",this).text()), $("name",this).text() + $("address",this).text(), $("carrier",this).text());
				});
			}
			else
			{
				alert($("message",data).text());
			}
		},
		error: function()
		{
			alert("通信エラー");
		}
	});
}

		function MyInfoWnd(){
			/*
			 * (1)DIVを作る
			 */
			//全体のDivを入れるコンテナ
			this.container_ = document.createElement("div");
			this.container_.className = "arrow_box";
			//this.container_.style.backgroundImage = "url('fukidashi.png')";
			//this.container_.style.backgroundRepeat = "no-repeat";
			//this.container_.style.border = "solid 1px black"; 
			this.container_.style.width = MyInfoWidth + "px";
			this.container_.style.height = MyInfoHeight + "px";
			this.container_.style.position = "absolute";
			
			//クローズボタン
			this.closeBtn_ = document.createElement("div");
			this.closeBtn_.style.width = "17px";
			this.closeBtn_.style.height = "17px";
			this.closeBtn_.style.position = "absolute";
			this.closeBtn_.style.left = "245px";
			this.closeBtn_.style.top = "65px";
			this.closeBtn_.style.cursor = "pointer";
			this.container_.appendChild(this.closeBtn_);
			
			//内容
			this.contents_ = document.createElement("div");
			//this.contents_.style.position = "absolute";
			//this.contents_.style.width = "217px";
			//this.contents_.style.height = "46px";
			//this.contents_.style.position = "absolute";
			//this.contents_.style.left = "21px";
			//this.contents_.style.top = "71px";
			this.container_.appendChild(this.contents_);
			
			//クローズボタンがクリックされたら、地図から削除
			var that = this;
			google.maps.event.addDomListener(this.closeBtn_, "click", function(){
				that.setMap(null);
			});
		}
		MyInfoWnd.prototype = new google.maps.OverlayView();
		MyInfoWnd.prototype.onAdd = function() {
			/*
			 * (2)floatPaneに追加
			 */
			this.layer_ = (this.getPanes()).floatPane;
			this.layer_.appendChild(this.container_);
		};
		MyInfoWnd.prototype.draw = function() {
			/*
			 * (3)地図に描画
			 */
			var pixel = this.getProjection().fromLatLngToDivPixel(this.position);
			
			this.container_.style.left = pixel.x - Math.floor(MyInfoWidth / 2) - 3 + "px";
			this.container_.style.top = pixel.y - MyInfoHeight - 44 - 20 + "px";
		};
		MyInfoWnd.prototype.onRemove = function() {
			/*
			 * (4)floatPaneからdivを削除
			 */
			if (this.layer_ != null) {
				this.layer_.removeChild(this.container_);
				this.layer_ = null;
			}
		};
		MyInfoWnd.prototype.setContent = function(html) {
			this.contents_.innerHTML = html;
		};
		MyInfoWnd.prototype.setPosition = function(position) {
			this.set("position", position);
		};
		MyInfoWnd.prototype.open = function(map_or_marker) {
			if ("position" in map_or_marker) {
				this.setPosition(map_or_marker.getPosition());
				this.setMap(map_or_marker.getMap());
			} else {
				this.setMap(map_or_marker.getMap());
			}
		};
		MyInfoWnd.prototype.close = function() {
			this.setMap(null);
		};

		window.onload = initialize;
		
	</script>
<style type="text/css">
html { height: 100% }
body { height: 100%; margin: 0px; padding: 0px }
#map_canvas { height: 100% }

.arrow_box {
	position: relative;
	background: #b2d524;
	border: 4px solid #f5ad1d;
}
.arrow_box:after, .arrow_box:before {
	top: 100%;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
}

.arrow_box:after {
	border-color: rgba(178, 213, 36, 0);
	border-top-color: #b2d524;
	border-width: 7px;
	left: 50%;
	margin-left: -7px;
}
.arrow_box:before {
	border-color: rgba(245, 173, 29, 0);
	border-top-color: #f5ad1d;
	border-width: 13px;
	left: 50%;
	margin-left: -13px;
}


</style>
</head>

<body>
<div id="map_canvas"></div>
</body>
</html>