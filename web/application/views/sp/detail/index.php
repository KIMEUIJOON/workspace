<?= $this->load->view('sp/common/header', array('title' => h($name))) ?>
<body>
<div data-role="page" id="detail" data-add-back-btn="true">

<?php if( ! $this->appli): ?>
<div data-theme="f" data-role="header">
<h3><?= SITE_NAME ?></h3>
<a href="<?= CONF_BASE_URL_SP ?>" data-role="button" data-icon="home" class="ui-btn-right" data-iconpos="notext">Home</a>
</div>
<?php endif; ?>

<div data-role="content">

<nav class="vlist-nav">
<ul>
<li><h3>ショップ名</h3><p style="font-size:14px"><?= h($name) ?></p></li>
</ul>
</nav>

<?php if($photos_view): ?>
<section id="gallery">
<ol class="jCarousel">
<?php for($i = 1; $i <= SHOP_FLYER_IMAGE_LIMIT; $i++): ?>
<?php if (isset($photos[$i])): ?>
<li><img src="<?= get_image_url($id, $i, $photos[$i]['ext'], IMAGE_NAME_PHOTO, IMAGE_MIDDLE_NAME) ?>" width="300"></li>
<?php endif; ?>
<?php endfor; ?>
</ol>
</section>
<?php endif; ?>

<?php if($campaign_view || $coupon_view): ?>
<div data-role="collapsible-set">

<?php if($campaign_view): ?>
<div data-role="collapsible" data-theme="a" data-content-theme="a">
<h3>キャンペーン<?php if($campaign_summary_view): ?> (<?= h($campaign) ?>)<?php endif; ?></h3>
<p style="color:red">有効期限:<?= date('Y年m月d日', $campaign_date) ?></p>
<?php if($campaign_summary_view): ?><p><?= h($campaign) ?></p><?php endif; ?>
<?php if($campaign_body_view): ?><p><?= nl2br(h($campaign_body)) ?></p><?php endif; ?>
</div>
<?php endif; ?>

<?php if($coupon_view): ?>
<div data-role="collapsible" data-theme="a" data-content-theme="a">
<h3>お得なクーポン情報</h3>
<p style="color:red">有効期限:<?= date('Y年m月d日', $coupon_date) ?></p>
<p><?= nl2br(h($coupon_body)) ?></p>
</div>
<?php endif; ?>

</div><!--/&collapsible-set-->
<?php endif; ?>

<?php if($review_view): ?>
<nav class="vlist-nav2">
<ul data-role="listview" data-inset="true">
<li>
<?php if( ! $this->appli): ?>
<a href="<?= base_path('sp/detail/review/' . $id) ?>" data-transition="slide">
<?php else: ?>
<a href="app-api://review()">
<?php endif; ?>

<h3>レビュー総合 <?= $review_count ?>件</h3>

<?php if($review_count): ?>
<p><?= set_starimg($review_score) ?>　<span style="font-size:14px"><?= $this->config->item(floor($review_score), 'score') ?></span></p></li>
<?php else: ?>
<p>評価無し</p>
<?php endif; ?>
</a>
</li>
</ul>
</nav>
<?php endif; ?>

<div>
<ul class="snsb">
<li><iframe src="//www.facebook.com/plugins/like.php?href=<?= urlencode(site_url('sp/detail/'.$id)) ?>&amp;send=false&amp;layout=button_count&amp;width=110&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowTransparency="true"></iframe></li>
<iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html?url=<?= urlencode(site_url('sp/detail/'.$id)) ?>&text=<?= urlencode($name) ?>&lang=ja" style="width:110px; height:21px;"></iframe>
</ul>
</div>

<nav class="vlist-nav2">
<ul data-role="listview" data-inset="true">
<li>
<?php if($lat && $lng): ?>

<?php if($this->appli): ?>
<a href="app-api://address()">
<?php else: ?>
<a href="<?= base_path('sp/detail/map/' . $id) ?>" target="_blank">
<?php endif; ?>

<?php endif; ?><h3>所在地</h3><p>〒<?= h($post_code) ?><br><?= $this->config->item($prefecture, 'prefecture') ?><?= h($address) ?> <?= h($building) ?></p><?php if($lat && $lng): ?></a><?php endif; ?></li>
<li>
<?php if($tel1 || $tel2): ?>
<a href="tel:<?= ($tel1) ? $tel1 : $tel2 ?>"><h3>電話番号</h3><p><?= $tel1 ?><?php if($tel1 && $tel2) echo '<br>'; ?><?= $tel2 ?></p></a>
<?php else: ?>
<h3>電話番号</h3><p><?= $tel1 ?><?php if($tel1 && $tel2) echo '<br>'; ?><?= $tel2 ?></p>
<?php endif; ?>
</li>
<li><h3>営業時間</h3><p><?= nl2br(h($hours)) ?></p></li>
<li><h3>定休日</h3><p><?= nl2br(h($holiday)) ?></p></li>
<li><h3>駐車場</h3><p><?= $this->config->item($parking_flag, 'parking') ?></p></li>
<?php if($counter): ?><li><h3>カウンター数</h3><p><?= $counter ?></p></li><?php endif; ?>
<li><h3>設備・サービス</h3>
<table>
<tbody>
<tr><th>バリアフリー</th><td><?= $this->config->item($barrier_free_flag, 'barrier_free') ?></td></tr>
<tr><th>キッズコーナー</th><td><?= $this->config->item($kids_flag, 'yes_no') ?></td></tr>
<tr><th>ケータイ教室</th><td><?= $this->config->item($classes_flag, 'yes_no') ?></td></tr>
<tr><th>料金収納</th><td><?= $this->config->item($payment_flag, 'yes_no') ?></td></tr>
<tr><th>修理受付</th><td><?= $this->config->item($repair_flag, 'yes_no') ?></td></tr>
</tbody>
</table>
</li>
</ul>
</nav>

<?php if($youtube_view): ?>
<div class="video-container">
<?= replace_width_height($youtube, 300, 169) ?>
</div>
<?php endif; ?>

<?php if($job_view): ?>
<div data-role="collapsible" data-theme="a" data-content-theme="a">
<h3>求人情報</h3>
<p><?= nl2br(h($job)) ?></p>
</div>
<?php endif; ?>

<?php if($flyer_view): ?>
<nav class="vlist-nav">
<ul>
<li><h3>チラシ・POP</h3>
<?php for($i = 1; $i <= SHOP_FLYER_IMAGE_LIMIT; $i++): ?>
<?php if (isset($flyer_image[$i]['ext']) && !empty($flyer_image[$i]['txt'])): ?>
<p><a href="<?= get_image_url($id, $i, $flyer_image[$i]['ext'], IMAGE_NAME_FLYER, IMAGE_MIDDLE_NAME, $flyer_image[$i]['time']) ?>" target="_blank" data-ajax="false"><?= h($flyer_image[$i]['txt']) ?></a></p>
<?php endif; ?>
<?php endfor; ?>
<?php for($i = 1; $i <= SHOP_FLYER_PDF_LIMIT; $i++): ?>
<?php if (isset($flyer_pdf[$i]['time']) && !empty($flyer_pdf[$i]['txt'])): ?>
<p><a href="<?= get_pdf_url($id, $i, IMAGE_NAME_FLYER) ?>" target="_blank" data-ajax="false"><?= h($flyer_pdf[$i]['txt']) ?></a></p>
<?php endif; ?>
<?php endfor; ?>
</li>
</ul>
</nav>
<?php endif; ?>

<?php if($lat && $lng): ?>
<?php if($this->appli): ?>
<a href="app-api://route()" data-role="button" data-icon="info" data-theme="b">マップアプリで経路検索</a>
<?php else: ?>
<a href="javascript:void(0);" onclick="routeSearch('<?= $lat ?>', '<?= $lng ?>'); return false;" data-role="button" data-icon="search" data-theme="b">ショップまで経路検索</a>
<?php endif; ?>
<?php endif; ?>

<?php if($review_send): ?>
<a href="<?= base_path('sp/setup/review/' . $id) ?>" data-rel="dialog" data-role="button" data-icon="info" data-theme="e">レビューする</a>
<?php endif; ?>
<?php if($newsletter_view): ?>
<a href="<?= base_path('sp/setup/newsletter/' . $id) ?>" data-rel="dialog" data-role="button" data-icon="check" data-theme="e">メールマガジン登録</a>
<?php endif; ?>

</div><!--/&content-->

<script>
// 経路検索
function routeSearch(lat, lng)
{
	$.mobile.showPageLoadingMsg();
	
	navigator.geolocation.getCurrentPosition(function(pos)
	{
		var latitude = pos.coords.latitude;
		var longitude = pos.coords.longitude;

		var url = 'http://maps.google.com/maps?saddr='+ latitude + ','+longitude+'&daddr='+lat+','+lng+'&dirflg=d';
		location.href = url;
	},
	function(error)
	{
		$.mobile.hidePageLoadingMsg();
		
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
</script>

</div><!--/&page-->
</body>
</html>
