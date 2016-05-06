<?= $this->load->view('admin/common/head'); ?>

</head>

<body>

<div id="tb" style="margin-top: 5px;">

<div title="基本情報" style="padding:10px;overflow-x:hidden;">
<form method="post" style="padding:10px 10px 20px 15px">
<?= form_hidden('id', $this->id) ?>

<table class="form" width="100%">

<tr>
<th>ステータス</th>
<td><?= form_dropdown('status_flag', $this->config->item('status_flag'), $status_flag) ?></td>
</tr>

<tr>
<th>ショップ名</th>
<td><input type="text" style="width:97%;" name="name" value="<?= h($name) ?>" /></td>
</tr>

<tr>
<th>キャリア</th>
<td><?= form_dropdown('carrier', $this->config->item('carrier'), $carrier) ?></td>
</tr>

<tr>
<th>郵便番号</th>
<td><input id="zip_code" type="text" size="12" class="span2" name="post_code" value="<?= $post_code ?>" /> <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="zip_code_address(); return false;">郵便番号から住所取得</a></td>
</tr>

<tr>
<th>都道府県</th>
<td><?= form_dropdown('prefecture', $this->config->item('prefecture'), $prefecture, 'id="prefecture"') ?></td>
</tr>

<tr>
<th>住所</th>
<td><input type="text" style="width:97%;" id="address" name="address" value="<?= h($address) ?>" /></td>
</tr>

<tr>
<th>建物名等</th>
<td><input type="text" style="width:97%;" name="building" value="<?= h($building) ?>" /></td>
</tr>

<tr>
<th>地図</th>
<td>経度：　<input type="text" size="13" name="lat" class="span2" id="plat" value="<?= ($lat) ? $lat : '' ?>" />　緯度:　<input type="text" size="13" class="span2" name="lng" id="plng" value="<?= ($lng) ? $lng : '' ?>" />　<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="map_confirm(); return false;">地図から確認</a></td>
</tr>

<tr>
<th>電話番号</th>
<td><input type="text" size="20" class="span2" name="tel1" value="<?= h($tel1) ?>" />　<input type="text" size="20" class="span2" name="tel2" value="<?= h($tel2) ?>" /></td>
</tr>

<tr>
<th>営業時間</th>
<td><textarea style="width:97%;line-height: 140%;" name="hours" rows="2"><?= h($hours) ?></textarea></td>
</tr>

<tr>
<th>定休日</th>
<td><textarea style="width:97%;line-height: 140%;" name="holiday" rows="2"><?= h($holiday) ?></textarea></td>
</tr>

<tr>
<th>カウンター数</th>
<td><input type="text" size="10" name="counter" value="<?= h($counter) ?>" class="span1" /> 席</td>
</tr>

</table>

<table class="form" width="100%">

<tr>
<th>駐車場</th>
<td><?= form_dropdown('parking_flag', $this->config->item('parking'), $parking_flag) ?></td>
</tr>

<tr>
<th>バリアフリー</th>
<td><?= form_dropdown('barrier_free_flag', $this->config->item('barrier_free'), $barrier_free_flag) ?></td>
</tr>

<tr>
<th>キッズコーナー</th>
<td><?= form_dropdown('kids_flag', $this->config->item('yes_no'), $kids_flag, 'class="mpg"') ?></td>
</tr>

<tr>
<th>ケータイ教室</th>
<td><?= form_dropdown('classes_flag', $this->config->item('yes_no'), $classes_flag, 'class="mpg"') ?></td>
</tr>
	
<tr>
<th>料金収納</th>
<td><?= form_dropdown('payment_flag', $this->config->item('yes_no'), $payment_flag, 'class="mpg"') ?></td>
</tr>
	
<tr>
<th>修理受付</th>
<td><?= form_dropdown('repair_flag', $this->config->item('yes_no'), $repair_flag, 'class="mpg"') ?></td>
</tr>

</table>
</form>
</div><!--@TAB1-->


<?php if(isset($username)): ?>
<div title="ID・PASS" style="padding:20px 20px 30px 25px">

<table class="form">

<tr>
<th>ログインID</th>
<td><span style="font-size:16px"><?= $username ?></span></td>
</tr>

<tr>
<th>パスワード</th>
<td><span style="font-size:16px"><?= $passwd ?></span></td>
</tr>

</table>

</div><!--@TAB2-->

<div title="有料オプション" style="padding:20px 20px 30px 25px">

<table class="table table-bordered table-striped" style="width:400px">

<?php foreach($this->config->item('option') as $key => $op): ?>
<?php if(!isset($$op['id'])) continue; ?>
<tr>
<th style="width:200px"><?= $op['name'] ?></th>
<td style="width:30px;text-align:center"><span style="font-size:16px"><?= ($$op['id']) ? '○' : '━'; ?></span></td>
<td style="width:150px;text-align:center"><?= ($option[$op['id'] . '_time']) ? date('Y-m-d', $option[$op['id'] . '_time']) : '<span style="font-size:16px">━</span>' ?></td>
</tr>
<?php endforeach; ?>

</table>

</div><!--@TAB3-->
<?php endif; ?>


</div>

</body>
</html>
