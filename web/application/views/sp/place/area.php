<?= $this->load->view('sp/common/header', array('title' => $this->prefecture)) ?>
<body>
<div data-role="page" id="p-area">
<div data-theme="f" data-role="header">
<a href="<?= base_path('sp/place/pref') ?>" data-icon="arrow-l" data-transition="slide" data-direction="reverse">戻る</a>
<h3><?= $this->prefecture ?></h3>
<a href="<?= CONF_BASE_URL_SP ?>" data-role="button" data-icon="home" data-iconpos="notext">Home</a>
</div>

<div data-role="content">
<ul data-role="listview">
<?php foreach($this->areas as $v): ?>
<?php if((int)$v['child']): ?>
<li><a href="<?= base_path('sp/place/town') ?>?area=<?= $v['id'] ?>" data-transition="slide"><?= $v['name'] ?> <span class="ui-li-count"><?= (int)$v['cnt'] ?></span></a></li>
<?php else: ?>
<li><a href="<?= base_path('sp/lists/area') ?>?code=<?= $v['id'] ?>" data-transition="slide"><?= $v['name'] ?> <span class="ui-li-count"><?= (int)$v['cnt'] ?></span></a></li>
<?php endif; ?>
<?php endforeach; ?>
</ul><!--/&listview-->

</div><!--/&content-->

</div><!--/&page-->

</body>
</html>
