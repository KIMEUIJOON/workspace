var MyInfoWidth = 200;
var MyInfoHeight = 88;

var map,mapDiv,imgObj;
var gps_lat, gps_lng;
var marker_list;
var marker_obj = {};
var map_center;

var myInfoWnd2;

// マップの中心をセット
function setMapCenter()
{
	map_center = map.getCenter();
}

// GPS検索による初期化
function initializeGps()
{
	max_height();
	
	marker_list = new google.maps.MVCArray();
	
	createMap(35.682329,139.766129);
	loadMarkerIconImage();
	moveToGpsLocation(true);
	//getMapDataLoad();
}

// 経度緯度指定による初期化
function initializeLatLng(mlat, mlng)
{
	max_height();
	
	marker_list = new google.maps.MVCArray();
	
	loadMarkerIconImage();
	getMapDataLoad(makeUrlParameter({mode:'location', lat:mlat, lng:mlng}), true);
}

// 地域検索による初期化
function initializeArea(md, cd)
{
	max_height();
	
	marker_list = new google.maps.MVCArray();
	
	//createMap(35.682329,139.766129);
	loadMarkerIconImage();
	//moveToGpsLocation(true);
	getMapDataLoad(makeUrlParameter({mode:md, code:cd}), true);
}

// 現在地でショップ情報リロード
function reloadShopThisPlace()
{
	// マーカー全削除
	removeMarkers();
	
	marker_obj = {};
	
	var latlng = map.getCenter();
	
	getMapDataLoad(makeUrlParameter({mode:'location', lat:latlng.lat(), lng:latlng.lng()}));
}

function createMap(lat, lng)
{
	//地図を表示
	var latlng = new google.maps.LatLng(lat, lng);
	var mapOpts = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false
	};
	
	mapDiv = document.getElementById("map_canvas");
	map = new google.maps.Map(mapDiv, mapOpts);
}

function createMarker(latlng, code, title, body, campaign, carrier)
{
	//マーカーを作成
	var marker = new google.maps.Marker({
		position : latlng,
		map : map,
		icon:imgObj[carrier],
		animation: google.maps.Animation.DROP,
		title : title
	});

	var content = body;

	if(campaign)
	{
		content = campaign;
		
		// キャンペーン値がある場合は、キャンペーンを表示
		var myInfoWnd = new MyInfoWnd();
			myInfoWnd.init();
			myInfoWnd.setContent(content);
			myInfoWnd.setTitle(title);
			myInfoWnd.setLink(BASE_URL + 'sp/detail/' + code);
			myInfoWnd.open(marker);
			myInfoWnd.code = code;
			
			marker_list.push(myInfoWnd);
		
		marker_obj[code] = code;
	}

		google.maps.event.addListener(marker, "click", function()
		{
			
			if(campaign && marker_obj[code] == undefined)
			{
				var myInfoWnd = new MyInfoWnd();
				myInfoWnd.init();
				myInfoWnd.setContent(content);
				
				if( ! campaign)
				{
					myInfoWnd.setBoxClassName("arrow_box2");
				}
				
				myInfoWnd.setTitle(title);
				myInfoWnd.setLink(BASE_URL + 'sp/detail/' + code);
				myInfoWnd.open(marker);
				myInfoWnd.code = code;
				
				marker_list.push(myInfoWnd);
				
				
			}
			
			marker_obj[code] = code;
			
			if(!campaign)
			{
				if(!myInfoWnd2)
				{
					myInfoWnd2 = new MyInfoWnd();
					myInfoWnd2.init();
				}
				
				myInfoWnd2.setContent(content);
				myInfoWnd2.setBoxClassName("arrow_box2");
				myInfoWnd2.setTitle(title);
				myInfoWnd2.setLink(BASE_URL + 'sp/detail/' + code);
				myInfoWnd2.open(marker);
				myInfoWnd2.code = code;
			}
		});
	
	return marker;
}

// マップデータを取得
function getMapDataLoad(pram, creatMapFlag)
{
	$.ajax(
	{
		type:"get",
		url: BASE_URL + 'api/map/control?' + pram,
		dataType: 'xml',
		cache: false,
		success: function(data, textStatus)
		{
			if ($("status",data).text() == STATUS_OK)
			{
				if(creatMapFlag)
				{
					createMap($("map > lat",data).text(), $("map > lng",data).text());
				}
				
				$("item",data).each(function ()
				{
					var marker = createMarker(new google.maps.LatLng($("lat",this).text(), $("lng",this).text()), $("code",this).text(), $("name",this).text(),  $("address",this).text(), $("campaign",this).text(), $("carrier",this).text());
					marker_list.push(marker);
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

// マップを全画面表示するためにコンテンツ部分を最大の高さに再設定
function max_height(orientation)
{
    var h = $('div[data-role="header"]').outerHeight(true);
    var f = $('div[data-role="footer"] a.ui-btn').outerHeight(true);
    //var f = $('div[data-role="footer"]').outerHeight(true);
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

    $('div[data-role="content"]').height(w - h - f);
    //$('div[data-role="content"]').height(300);
}

// オリジナルマップピン画像を読み込み
function loadMarkerIconImage()
{
	imgObj = new Object();
		
	//imgObj['d'] = new google.maps.MarkerImage("src/map/icon.png", new google.maps.Size(22,22), new google.maps.Point(44, 0), null , new google.maps.Size(110,22));
	imgObj['d'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(0, 0));
	imgObj['a'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(44, 0));
	imgObj['s'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(88, 0));
	imgObj['w'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(132, 0));
	imgObj['e'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(176, 0));
	imgObj['h'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(220, 0));
	imgObj['k'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(264, 0));
	imgObj['t'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(308, 0));
	imgObj['p'] = new google.maps.MarkerImage("../map/icon.png", new google.maps.Size(44,44), new google.maps.Point(352, 0));
}

// 独自のInfoWindowを作成
function MyInfoWnd(){}

MyInfoWnd.prototype = new google.maps.OverlayView();
MyInfoWnd.prototype.onAdd = function() {
	/*
	* (2)floatPaneに追加
	*/
	this.layer_ = (this.getPanes()).floatPane;
	this.layer_.appendChild(this.container_);
};

// 初期化
MyInfoWnd.prototype.init = function() {
	/*
	* (1)DIVを作る
	*/
	//全体のDivを入れるコンテナ
	this.container_ = document.createElement("div");
	this.container_.className = "arrow_box";
				
	//クローズボタン
	this.closeBtn_ = document.createElement("div");
	this.closeBtn_.className = "close";
	this.container_.appendChild(this.closeBtn_);

	//タイトル
	this.title_ = document.createElement("div");
	this.title_.className = "title";
	this.container_.appendChild(this.title_);
	
	//リンク
	this.dlink_ = document.createElement("a");
	this.dlink_.className = "dlink";
	//this.dlink_.setAttribute("target", "_blank");
	this.container_.appendChild(this.dlink_);
	
	//内容
	this.contents_ = document.createElement("p");
	this.contents_.className = "body";
	this.dlink_.appendChild(this.contents_);

	//クローズボタンがクリックされたら、地図から削除
	var that = this;
	google.maps.event.addDomListener(this.closeBtn_, "click", function(){
		that.setMap(null);
		marker_obj[that.code] = undefined;
	});
};

MyInfoWnd.prototype.draw = function() {
	/*
	* (3)地図に描画
	*/
	var pixel = this.getProjection().fromLatLngToDivPixel(this.position);
	this.container_.style.left = pixel.x - Math.floor(MyInfoWidth / 2) + 25 + "px";
	this.container_.style.top = pixel.y - 120 + "px";
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
MyInfoWnd.prototype.setBoxClassName = function(name) {
	this.container_.className = name;
};
MyInfoWnd.prototype.setContent = function(html) {
	this.contents_.innerHTML = html;
};
MyInfoWnd.prototype.setTitle = function(html) {
	this.title_.innerHTML = html;
};
MyInfoWnd.prototype.setLink = function(html) {
	this.dlink_.href = html;
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


// 現在地に移動
function moveToGpsLocation(load)
{
	navigator.geolocation.getCurrentPosition(function(pos)
	{
		var latitude = pos.coords.latitude;
		var longitude = pos.coords.longitude;
		var acc = pos.coords.accuracy;

		map.panTo(new google.maps.LatLng(latitude, longitude));
		
		if(load)
		{
			removeMarkers();
			getMapDataLoad(makeUrlParameter({mode:'location', lat:latitude, lng:longitude}));
		}
		
		//map.setCenter(new google.maps.LatLng(lat, lng));
	},
	function(error)
	{
		if(error.code == 1)
		{
			alert('GPS検索には位置情報利用の許可が必要です');
		}
		else if(error.code == 2)
		{
			alert('位置情報取得できませんでした');
		}
		else if(error.code == 3)
		{
			alert('位置情報取得タイムアウト');
		}
	},
	{enableHighAccuracy:true,timeout:8000,maximumAge:0});
}

// マーカー全削除
function removeMarkers()
{
	marker_list.forEach(function(marker, idx)
	{
		marker.setMap(null);
	});
	
	if(myInfoWnd2)
	{
		myInfoWnd2.close();
	}
}