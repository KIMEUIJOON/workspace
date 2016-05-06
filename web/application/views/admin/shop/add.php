<?= $this->load->view('admin/common/head'); ?>

</head>

<body>

<div id="tb" style="margin-top: 5px;">

<div title="基本情報" style="padding:10px;overflow-x:hidden;">
<form method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<tr>
<th>ステータス</th>
<td><?= form_dropdown('status_flag', $this->config->item('status_flag'), '') ?></td>
</tr>

<tr>
<th>ショップ名</th>
<td><input type="text" style="width:97%;" name="name" value="" /></td>
</tr>

<tr>
<th>キャリア</th>
<td><?= form_dropdown('carrier', $this->config->item('carrier'), '') ?></td>
</tr>

<tr>
<th>郵便番号</th>
<td><input id="zip_code" type="text" size="12" class="span2" name="post_code" value="" /> <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="zip_code_address(); return false;">郵便番号から住所取得</a></td>
</tr>

<tr>
<th>都道府県</th>
<td><?= form_dropdown('prefecture', $this->config->item('prefecture'), '', 'id="prefecture"') ?></td>
</tr>

<tr>
<th>住所</th>
<td><input type="text" style="width:97%;" id="address" name="address" value="" /></td>
</tr>

<tr>
<th>建物名等</th>
<td><input type="text" style="width:97%;" name="building" value="" /></td>
</tr>

<tr>
<th>地図</th>
<td>経度：　<input type="text" size="13" name="lat" class="span2" id="plat" value="" />　緯度:　<input type="text" size="13" class="span2" name="lng" id="plng" value="" />　<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="map_confirm(); return false;">地図から確認</a></td>
</tr>

<tr>
<th>電話番号</th>
<td><input type="text" size="20" class="span2" name="tel1" value="" />　<input type="text" size="20" class="span2" name="tel2" value="" /></td>
</tr>

<tr>
<th>営業時間</th>
<td><textarea style="width:97%;line-height: 140%;" name="hours" rows="2"></textarea></td>
</tr>

<tr>
<th>定休日</th>
<td><textarea style="width:97%;line-height: 140%;" name="holiday" rows="2"></textarea></td>
</tr>

<tr>
<th>カウンター数</th>
<td><input type="text" size="10" name="counter" value="" class="span1" /> 席</td>
</tr>

</table>

<table class="form" width="100%">

<tr>
<th>駐車場</th>
<td><?= form_dropdown('parking_flag', $this->config->item('parking'), '') ?></td>
</tr>

<tr>
<th>バリアフリー</th>
<td><?= form_dropdown('barrier_free_flag', $this->config->item('barrier_free'), '') ?></td>
</tr>

<tr>
<th>キッズコーナー</th>
<td><?= form_dropdown('kids_flag', $this->config->item('yes_no'), '', 'class="mpg"') ?></td>
</tr>

<tr>
<th>ケータイ教室</th>
<td><?= form_dropdown('classes_flag', $this->config->item('yes_no'), '', 'class="mpg"') ?></td>
</tr>
	
<tr>
<th>料金収納</th>
<td><?= form_dropdown('payment_flag', $this->config->item('yes_no'), '', 'class="mpg"') ?></td>
</tr>
	
<tr>
<th>修理受付</th>
<td><?= form_dropdown('repair_flag', $this->config->item('yes_no'), '', 'class="mpg"') ?></td>
</tr>

</table>
</form>
</div><!--@TAB1-->

</div>

</body>
</html>
