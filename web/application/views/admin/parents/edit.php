<?= $this->load->view('admin/common/head'); ?>

</head>

<body>

<div id="tb" style="margin-top: 5px;">

<div title="基本情報" style="padding:10px;overflow-x:hidden;">
<form method="post" style="padding:10px 10px 20px 15px">
<?= form_hidden('id', $this->id) ?>

<table class="form" width="100%">

<tr>
<th>会社名</th>
<td><input type="text" style="width:97%;" name="name" value="<?= h($name) ?>" /></td>
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
<th>電話番号</th>
<td><input type="text" size="20" class="span2" name="tel1" value="<?= h($tel1) ?>" /></td>
</tr>

</table>
</form>
</div><!--@TAB1-->


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

</div>

</body>
</html>
