<?= $this->load->view('sp/common/header') ?>
<body>
<div data-role="page" id="p-pref">
<div data-theme="f" data-role="header">
<a href="<?= CONF_BASE_URL_SP ?>" data-icon="arrow-l" data-role="button" data-transition="slide" data-direction="reverse">戻る</a>
<h3>都道府県を選択</h3>
</div>

<div data-role="content">
<ul data-role="listview">
<?php foreach($this->config->item('prefecture') as $key => $data): ?>
<li><a href="<?= base_path('sp/place/area') ?>?pf=<?= $key ?>" data-transition="slide"><?= $data ?></a></li>
<?php endforeach; ?>
</ul><!--/&listview-->

</div><!--/&content-->

</div><!--/&page-->

</body>
</html>
