<div region="north" border="false" style="height:83px; background: #185A87 url(src/ad/images/repeat_bk.gif) repeat-x left top;border-bottom:3px solid #185A87;overflow-y:hidden;">
<img id="logo" src="src/ad/images/member.gif" /><div id="banner" style="position:absolute;left: 450px;z-index:3;top: 10px; background-color: #ccf"></div>
<?php if($_SESSION['parents']['id']): ?><p style="position:absolute; bottom:35px; right:10px;z-index:10;background-color:#fff;padding:5px;font-weight:bold"><?= $_SESSION['member']['shop_name'] ?></p><?php endif; ?>
<a href="javascript:void(0)" class="easyui-linkbutton" onclick="logout(); return false;" style="position:absolute; bottom:10px; right:10px">ログアウト</a>
</div><!--/north-->
