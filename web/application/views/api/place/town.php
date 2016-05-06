<?= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ?>
<town>
<status><?= $status ?></status>
<items>
<?php foreach($this->towns as $v): ?>
<?php if(empty($v['name'])) continue; ?>
<item>
<code><?= $v['id'] ?></code>
<name><?= $v['name'] ?></name>
<count><?= (int)$v['cnt'] ?></count>
</item>
<?php endforeach; ?>
</items>
</town>