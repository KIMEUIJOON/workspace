<?= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ?>
<map>
<status><?= $status ?></status>
<total><?= (int)$total ?></total>
<lat><?= $lat ?></lat>
<lng><?= $lng ?></lng>
<items>
<?php foreach($this->shops as $v): ?>
<item>
<code><?= $v['id'] ?></code>
<name><?= h($v['name']) ?></name>
<carrier><?= $v['carrier'] ?></carrier>
<address><?= h($v['address']) ?></address>
<campaign><?= ($v['op1'] && $v['campaign_date'] > time()) ? h($v['campaign']) : '' ?></campaign>
<lat><?= $v['lat'] ?></lat>
<lng><?= $v['lng'] ?></lng>
</item>
<?php endforeach; ?>
</items>
</map>