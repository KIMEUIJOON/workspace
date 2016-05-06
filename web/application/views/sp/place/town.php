<?= $this->load->view('sp/common/header', array('title' => $this->area['name'])) ?>
<body>
<div data-role="page" id="p-town">
<div data-theme="f" data-role="header">
<a href="<?= $this->area['prev_url'] ?>" data-icon="arrow-l" data-transition="slide" data-direction="reverse">戻る</a>
<h3><?= $this->area['name'] ?></h3>
<a href="<?= base_path('sp/map') ?>?mode=area&code=<?= $this->area['id'] ?>" data-role="button" rel="external">MAP</a>
</div>

<div data-role="content">
<ul data-role="listview">
<li><a href="<?= base_path('sp/lists/area') ?>?code=<?= $this->area['id'] ?>" data-transition="slide"><?= $this->area['name'] ?></a></li>
<?php foreach($this->towns as $v): ?>
<?php if(empty($v['name'])) continue; ?>
<li><a href="<?= base_path('sp/lists/town') ?>?code=<?= $v['id'] ?>" data-transition="slide"><?= $v['name'] ?> <span class="ui-li-count"><?= (int)$v['cnt'] ?></span></a></li>
<?php endforeach; ?>
</ul><!--/&listview-->

</div><!--/&content-->

</div><!--/&page-->

</body>
</html>
