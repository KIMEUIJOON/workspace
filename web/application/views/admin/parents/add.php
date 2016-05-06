<?= $this->load->view('admin/common/head'); ?>

</head>

<body>

<div id="tb" style="margin-top: 5px;">

<div title="基本情報" style="padding:10px;overflow-x:hidden;">
<form method="post" style="padding:10px 10px 20px 15px">
<table class="form" width="100%">

<tr>
<th>会社名</th>
<td><input type="text" style="width:97%;" name="name" value="" /></td>
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
<th>電話番号</th>
<td><input type="text" size="20" class="span2" name="tel1" value="" /></td>
</tr>

</table>
</form>
</div><!--@TAB1-->

</div>

</body>
</html>
