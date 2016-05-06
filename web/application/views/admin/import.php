<?= $this->load->view('admin/common/head'); ?>

</head>

<body class="easyui-layout">

<?= $this->load->view('admin/common/north'); ?>

<?= $this->load->view('admin/common/west'); ?>

<div region="center" border="true" title="携帯ショップ情報インポート" style="padding:20px">

<?= form_open_multipart('admin/import/csv') ?>
<div style="margin-bottom:15px">
<input type="file" size="40" name="csvfile" />
</div>
<div style="margin-bottom:15px">
<?= form_dropdown('carrier', $this->config->item('carrier'), set_value('carrier')) ?>
</div>
<?php if(isset($status) AND isset($message) AND $status == STATUS_OK): ?>
<p style="color:blue"><?= $message ?></p>
<?php elseif(isset($status) AND isset($message) AND $status == STATUS_NG): ?>
<p style="color:red"><?= $message ?></p>
<?php endif; ?>

<input type="submit" value="インポート開始">
</form>

</div>

<?= $this->load->view('admin/common/footer'); ?>