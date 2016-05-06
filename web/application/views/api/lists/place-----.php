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
<address><?= $prefecture[$v['prefecture']] .' > '. $v['area_name'] ?><?= ($v['town_name']) ? ' > ' . $v['town_name'] : '' ?></address>
<campaign><?= h($v['campaign']) ?></campaign>
<lat><?= $v['lat'] ?></lat>
<lng><?= $v['lng'] ?></lng>
</item>
<?php endforeach; ?>
</items>
</lists>