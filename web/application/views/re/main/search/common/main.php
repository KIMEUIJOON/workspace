<!--searchPanel start-->
<section class="panel panel-default">
<div class="searchPanel_back">
  <div class="searchPanel">
    <!--searchPanel_キャリアから絞り込む start-->
    <?= $this->load->view('re/main/search/common/carrier')?>
    <!--searchPanel_キャリアから絞り込む end-->
    <br>
    <div class="row">
      <!--searchPanel_地域から絞り込む start-->
      <?= $this->load->view('re/main/search/pctab/area')?>
      <!--searchPanel_地域から絞り込む end-->

      <div class="col-lg-5 col-md-5 col-sm-5">

        <div class="panel panel-default show-mobile">
        </div>
        <!--searchPanel_その他条件を指定 start-->
        <?= $this->load->view('re/main/search/common/other')?>
        <!--searchPanel_その他条件を指定 end-->
        <!--searchPanel_上記の条件で検索 start-->
                <div>
              <a href=""><img class="img-responsive img-rounded img_right" src="http:\\localhost\src\re\img\pc\button_conditionSearch.png"></a>
          </div>
        </div>
        <!--searchPanel_上記の条件で検索 end-->
      </div>
    </div>
    </section>
    <!--searchPanel end-->
