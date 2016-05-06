<div class="navi" region="west" split="false" title="管理メニュー" style="width:180px;padding:0px;background:#F2F9FD;overflow-x:hidden;">
<ul>
<?php if($_SESSION['parents']['id']): ?>
<li class="head">▼　複数ショップ管理</li>
<li><a href="<?= site_url('parents/basic') ?>">会社情報</a></li>
<li><a href="<?= site_url('parents/relation/select') ?>">編集ショップ選択</a></li>
<li><a href="<?= site_url('parents/relation/setting') ?>">全ショップ内容一括設定</a></li>
<li class="head">▼　各ショップ設定</li>
<?php endif; ?>
<li><a href="<?= site_url('member/basic') ?>">基本ショップ情報</a></li>
<li><a href="<?= site_url('member/option') ?>">有料オプション設定</a></li>
<li><a href="<?= site_url('sp/detail/' . $_SESSION['member']['shop_id']) ?>" target="_blank">Webサイト確認</a></li>
<li class="head">▼　有料オプション</li>
<li class="op"><a href="<?= site_url('member/campaign/op1') ?>">1.　キャンペーン概要</a></li>
<li class="op"><a href="<?= site_url('member/campaign/op2') ?>">2.　キャンペーン内容</a></li>
<li class="op"><a href="<?= site_url('member/coupon') ?>">3.　クーポン機能</a></li>
<li class="op"><a href="<?= site_url('member/newsletter') ?>">4.　メールマガジン</a></li>
<li class="op"><a href="<?= site_url('member/flyer') ?>">5.　チラシ・POP</a></li>
<li class="op"><a href="<?= site_url('member/job') ?>">6.　求人</a></li>
<li class="op"><a href="<?= site_url('member/review') ?>">7.　レビュー</a></li>
<li class="op"><a href="<?= site_url('member/photos') ?>">8.　写真・動画</a></li>
</ul>
</div><!--/west-->
