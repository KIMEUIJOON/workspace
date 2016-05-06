<!--searchPanel_地域から絞り込む start-->
<div class="col-lg-7 col-md-7 col-sm-7">
  <div class="panel panel-default show-mobile">
  </div>
  <label>
  <b>
  <img src="http:\\localhost\src\re\img\pc\mark_location.png">
  地域から絞り込む</b>
  </label>
  <img class="img-responsive img-rounded img_right hidden-mobile"
  src="http:\\localhost\src\re\img\pc\map.png" usemap="#japan_map">
  <!--mobile_地域から絞り込む start -->
  <?= $this->load->view('re/main/search/mobile/area')?>
  <!-- mobile_地域から絞り込む end -->
</div>
<!--searchPanel_地域から絞り込む end-->
