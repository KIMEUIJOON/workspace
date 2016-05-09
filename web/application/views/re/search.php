<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?= $this->
load->view('re/common/header') ?>
<body>
<!-- start-->
<!-- end-->
<!--area_top start-->
<?= $this->
load->view('re/common/area_top') ?>
<!--area_top end-->
<br>
<!--area_body start-->
<div class="area_body">
	<div class="outer">
		<div class="inner">
			<div class="centered">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<!--top_panel start -->
					<?= $this->
					load->view('re/search/searchPanel') ?>
					<div class="padding">
					  <table style="width:100%">
					  <tr>
					    <td>
					      <b>キャリア(通信業者)</b>
					    </td>
					    <td>
					      <select name="carrier" class="search_text_border">
					        <option value="no" selected="selected">キャリアを選択してください</option>
					        <option value="docomo">docomo</option>
					        <option value="au">au</option>
					        <option value="softbank">softbank</option>
					        <option value="ymobile">ymobile</option>
					        <option value="total">トータルショップ</option>
					      </select>
					    </td>
					    <td>
					    </td>
					  </tr>
					  <tr>
					    <td>
					      <label>フリーワード</label>
					    </td>
					    <td>
					      <input type="text" class="search_text_border">
					    </td>
					    <td>
					      <label>＆その他の検索項目</label>
					    </td>
					  </tr>
					  </table>
					  <br/>
					  <table style="width:100%;">
					  <tr>
					    <td>
					      <input type="checkbox" name="other_choice" value="parking">駐車場あり
					    </td>
					    <td>
					      <input type="checkbox" name="other_choice" value="kid-room">キッズルーム
					    </td>
					    <td>
					      <input type="checkbox" name="other_choice" value="holds-rates">料金収納可
					    </td>
					    <td>
					      <input type="checkbox" name="other_choice" value="after21">21時以降も営業
					    </td>
					    <td>
					      <input type="checkbox" name="other_choice" value="barrier-free">バリアフリー
					    </td>
					    <td>
					      <input type="checkbox" name="other_choice" value="study-smartphone">ケータイ教室
					    </td>
					    <td>
					      <input type="checkbox" name="other_choice" value="breakdown">故障受付可
					    </td>
					    <td>
					      <img src="http:\\localhost\src\re\img\pc\button_search.png">
					    </td>
					  </tr>
					  </table>
					</div>

					<!--top_panel end -->
					<!--area_各店舗 start -->
					<div class="padding">
						<!--各店舗 start -->
						<?php
            $count = 0;
            while($count < 8){
            $this->load->view('re/search/shopDiv'); $count++; } ?>
						<!--各店舗 end -->
					</div>
					<!--area_店舗 end -->
				</div>
			</div>
		</div>
	</div>
</div>
<!--area_body end-->
<!--area_footer start-->
<?= $this->
load->view('re/common/area_bottom') ?>
</body>
</html>
