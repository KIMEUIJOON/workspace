<?= $this->load->view('sp/common/header') ?>
<body>
	
<div data-role="page" id="dboard">

<?php if( ! $this->appli): ?>
<div data-role="header" data-theme="f" data-position="inline">
<a href="#" data-icon="arrow-l" data-role="button" data-rel="back">戻る</a>
<h1>掲示板</h1>
<a href="<?= base_path('sp/board/form') ?>" data-rel="dialog" data-role="button" data-icon="info">投稿</a>
</div><!--/&header-->
<?php endif; ?>

<div data-role="content">

<ul data-role="listview" data-divider-theme="a" class="allview board">
<li data-role="list-divider" role="heading">
全<?= $count ?>件 <?= $page ?>ページ目
</li>
<?php foreach($data as $board): ?>
<li data-theme="d"><h3 style="font-size:12px"><?= $board['id'] ?><?= h($board['name']) ?></h3><p><?= nl2br(h($board['body'])) ?></p><p class="datetime">(投稿日:<?= date("Y/m/d", $board['entry_time']) ?>)</p></li>
<?php endforeach; ?>
</ul>

<?php if($next): ?><a data-theme="e" data-icon="forward" href="<?= base_path('sp/board/lists') ?>?page=<?= $next ?><?php ($this->appli == true) and print '&type=app'; ?>" data-role="button" style="margin-top:35px">もっと見る</a><?php endif; ?>

</div><!--/&content-->

<script>
function dialog_board()
{
	if($('.ui-dialog').size() == 0)
	{
		$("<a href='<?= base_path('sp/board/form') ?>' data-rel='dialog'></a>").appendTo("#dboard").click().remove();
	}
}
</script>

</div><!--/&page-->

</body>
</html>
