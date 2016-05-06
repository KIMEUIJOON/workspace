<?= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ?>
<lists>
<status><?= $status ?></status>
<next><?= $next ?></next>
<items>
<?php foreach($data as $v): ?>
<item>
<name><?= h($v['name']) ?></name>
<body><?= h($v['body']) ?></body>
<date>(投稿日:<?= date("Y/m/d", $v['entry_time']) ?>)</date>
</item>
<?php endforeach; ?>
</items>
</lists>