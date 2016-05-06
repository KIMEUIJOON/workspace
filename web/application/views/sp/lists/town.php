<?= $this->load->view('sp/common/header', array('title' => $this->area['name'] . $this->town['name'])) ?>
<body>
<div data-role="page" data-add-back-btn="true">
<div data-theme="f" data-role="header">
<a href="#" data-icon="arrow-l" data-role="button" data-rel="back">戻る</a>
<h3><?= $this->area['name'] ?></h3>
<a href="<?= base_path('sp/map') ?>?mode=town&code=<?= $this->town['id'] ?>" data-role="button" rel="external">MAP</a>
</div>
<div data-role="content">
<ul data-role="listview" data-divider-theme="a">
<li data-role="list-divider" role="heading"><?= $this->town['name'] ?> <?= $total ?>件</li>
<?php foreach($this->shops as $v): ?>
<?php if($v['op1'] && $v['campaign']): ?>
<li data-icon="campaign"><a href="<?= base_path('sp/detail/' . $v['id']) ?>" data-transition="slide">
<h3 style="font-size:13px;margin-bottom:10px"><?= h($v['name']) ?></h3>
<p style="color:red"><?= h($v['campaign']) ?></p>
<?php else: ?>
<li><a href="<?= base_path('sp/detail/' . $v['id']) ?>" data-transition="slide">
<h3 style="font-size:13px;margin-bottom:10px"><?= h($v['name']) ?></h3>
<p><?= $v['address'] ?></p>
<?php endif; ?>
</a></li>
<?php endforeach; ?>
</ul><!--/&listview-->

</div><!--/&content-->

</div><!--/&page-->

</body>
</html>
