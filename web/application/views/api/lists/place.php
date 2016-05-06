<?= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ?>
<lists>
<status><?= $status ?></status>
<total><?= (int)$total ?></total>
<items>
<?php foreach($this->shops as $v): ?>
<item>
<code><?= $v['id'] ?></code>
<name><?= h($v['name']) ?></name>
<carrier><?= $v['carrier'] ?></carrier>
<address><?= h($v['address']) ?></address>
<campaign><?= h($v['campaign']) ?></campaign>
<lat><?= $v['lat'] ?></lat>
<lng><?= $v['lng'] ?></lng>
</item>
<?php endforeach; ?>
</items>
</lists>