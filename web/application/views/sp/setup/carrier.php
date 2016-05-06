<?= $this->load->view('sp/common/header') ?>
<body>
	
<div data-role="page" id="p-setup">

<div data-role="header" data-theme="f" data-position="inline">
<h1>絞込み設定</h1>
<a href="#" id="save" data-map="<?= ($this->input->get('map')) ? 'ok':'ng' ?>" data-icon="check">設定</a>
</div><!--/&header-->

<div data-role="content">

<nav class="listview-slider">
<ul>
<?php foreach($this->config->item('carrier') as $k => $name): ?>
<li>
<span><img src="<?= src_path('img/setup/'.$k.'.png') ?>" width="30" /></span><span><?= $name ?></span>
<div class="switch">
<?= form_dropdown($k, $this->config->item('on_off'), '', 'id="'.$k.'" class="carrier" data-role="slider" data-mini="true"') ?>
</div>
</li>
<?php endforeach; ?>
</ul>
</nav><!--/.listview-slider-->

</div><!--/&content-->

</div><!--/&page-->

</body>
</html>
