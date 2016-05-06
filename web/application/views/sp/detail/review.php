<?= $this->load->view('sp/common/header', array('title' => h($name) . ' レビュー')) ?>
<body>
<div data-role="page" id="dreview">

<?php if( ! $this->appli): ?>
<div data-theme="f" data-role="header">
<a href="<?= base_path('sp/detail/' . $id) ?>" data-icon="arrow-l" data-role="button" data-rel="back">戻る</a>
<h3><?= SITE_NAME ?></h3>
<a href="<?= base_path('sp/setup/review/' . $id) ?>" data-rel="dialog" data-role="button" data-icon="info">投稿</a>
</div>
<?php endif; ?>

<div data-role="content">

<ul data-role="listview" data-divider-theme="a" class="allview board">
<li data-role="list-divider" role="heading">
総合評価: <?php if($cnt): ?><span><?= set_starimg($review_score) ?></span> 全<?= $cnt ?>件<?php else: ?>評価なし<?php endif; ?>
</li>
<li style="padding: 0.3em 15px;"><h3><?= h($name) ?></h3></li>
<?php foreach($reviews as $review): ?>
<li data-theme="d"><h3 style="font-size:12px"><?= h($review['name']) ?></h3><p><?= nl2br(h($review['body'])) ?></p><p class="datetime review-level level-<?= $review['score'] ?>">(投稿日:<?= date("Y/m/d", $review['entry_time']) ?>)</p></li>
<?php endforeach; ?>
</ul>

<?php if($next): ?><a data-theme="e" data-icon="forward" href="<?= base_path('sp/detail/review/' . $id . '/' .$next) ?><?php ($this->appli == true) and print '?type=app'; ?>" data-transition="slide" data-role="button" style="margin-top:35px">もっと見る</a><?php endif; ?>

</div><!--/&content-->

<script>
function dialog_review()
{
	if($('.ui-dialog').size() == 0)
	{
		$("<a href='<?= base_path('sp/setup/review/' . $id) ?>' data-rel='dialog'></a>").appendTo("#dreview").click().remove();
	}
}
</script>

</div><!--/&page-->

</body>
</html>
