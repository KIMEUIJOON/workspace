<?= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ?>
<area>
<status><?= $status ?></status>
<items>
<?php foreach($this->areas as $v): ?>
<item>
<code><?= $v['id'] ?></code>
<name><?= $v['name'] ?></name>
<count><?= (int)$v['cnt'] ?></count>
<child><?= $v['child'] ?></child>
</item>
<?php endforeach; ?>
</items>
</area>