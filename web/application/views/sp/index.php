<?= $this->load->view('sp/common/header') ?>
<body>
<div data-role="page" data-theme="f">
<div data-theme="f" data-role="header">
<h3><?= SITE_NAME ?></h3>
</div>
<div data-role="content" style="padding-top:5px">

<ul data-role="listview" data-divider-theme="b" data-inset="true" style="margin-bottom:35px">
<li data-theme="c">
<a href="<?= base_path('sp/map') ?>" data-transition="slide" rel="external"><img src="<?= src_url('img/gps.png') ?>" alt="" class="ui-li-icon">GPSで現在地から検索</a>
</li>
<li data-theme="c">
<a href="<?= base_path('sp/place/pref') ?>" data-transition="slide"><img src="<?= src_url('img/area.png') ?>" alt="" class="ui-li-icon">エリアから検索</a>
</li>
<li data-theme="c">
<a href="<?= base_path('sp/setup/carrier') ?>" data-inline="true" data-rel="dialog" data-transition="slideup"><img src="<?= src_url('img/serch.png') ?>" alt="" class="ui-li-icon">キャリア絞込み設定</a>
</li>
<li data-theme="c">
<a href="<?= base_path('sp/board/form') ?>" data-inline="true" data-rel="dialog" data-transition="slideup"><img src="<?= src_url('img/voice.png') ?>" alt="" class="ui-li-icon">掲示板に投稿</a>
</li>
</ul>

<div style="margin: -20px 0 25px 0">
<ul class="snsb">
<li><iframe src="//www.facebook.com/plugins/like.php?href=<?= urlencode(base_url()) ?>&amp;send=false&amp;layout=button_count&amp;width=110&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowTransparency="true"></iframe></li>
<li><iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?url=<?= urlencode(base_url()) ?>&text=<?= urlencode(SITE_NAME) ?>&lang=ja" style="width:110px; height:21px;"></iframe></li>
</ul>
</div>

<ul data-role="listview" data-divider-theme="f" class="allview board">
<li data-role="list-divider" role="heading">つぶやき掲示板</li>
<?php foreach($data as $board): ?>
<li data-theme="d"><h3 style="font-size:12px"><?= h($board['name']) ?></h3><p><?= nl2br(h($board['body'])) ?></p><p class="datetime">(投稿日:<?= date("Y/m/d", $board['entry_time']) ?>)</p></li>
<?php endforeach; ?>
</ul>

<?php if($next): ?><a data-theme="e" href="<?= base_path('sp/board/lists') ?>?page=<?= $next ?>" data-icon="forward" data-role="button" style="margin-top:35px">もっと見る</a><?php endif; ?>

</div><!--/&content-->

</div><!--/&page-->

</body>
</html>
