<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= (isset($title)) ? h($title) . ' - ' . SITE_NAME : SITE_NAME ?></title>
<base href="<?= CONF_BASE_URL ?>src/sp/">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<?php if($this->uri->segment(1) == FALSE): ?>
<meta name="description" content="携帯ショップのキャンペーン情報、クーポン情報、キャッシュバック情報、チラシ情報、求人情報が満載！ドコモ、au、ソフトバンク、ウィルコム、イーモバイル、併売店も検索可能。店舗までの経路検索も出来ます。">
<?php endif; ?>
<link rel="stylesheet" href="../cm/css/normalize.css">
<link rel="stylesheet" href="themes/my-custom-theme.min.css">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile.structure-1.2.0.min.css">
<link rel="stylesheet" href="css/custom-ui.css">
<link rel="stylesheet" href="css/custom.css">
<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
<script src="js/config.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
<script src="../cm/js/config.js"></script>
<script src="js/custom.js"></script>
<script src="js/jCarousel.min.js"></script>
<script src="js/jquery.cookie.js"></script>
</head>
