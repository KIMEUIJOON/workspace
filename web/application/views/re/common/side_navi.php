<div class="col-lg-3 col-md-3 sidenav show-pc show-pc-lg side_navi left">
  <ul class="list-group">
    <!--side_navi_現在地から探す start-->
    <li class="list-group-item center">
    <a href=""><img class="img-responsive img-rounded" src="http:\\localhost\src\re\img\pc\button_presentLocation.png"></a>
    </li>
    <!--side_navi_現在地から探す end-->
    <!--side_navi_キャリアで検索 start-->
    <li class="list-group-item">
    <div>
      <label>
      <b>キャリアで検索</b>
      </label>
      <ul class="list-unstyled center">
        <li>
          <img class="img-responsive" name="carrier" value="side_navi_docomo" check="off"
          src="http:\\localhost\src\re\img\pc\sideLogo_docomo.png" onclick="checkbox_select(this);">
        </li>
        <li>
          <img class="img-responsive" name="carrier" value="side_navi_au" check="off"
          src="http:\\localhost\src\re\img\pc\sideLogo_au.png" onclick="checkbox_select(this);">
        </li>
        <li>
          <img class="img-responsive" name="carrier" value="side_navi_softbank" check="off"
          src="http:\\localhost\src\re\img\pc\sideLogo_softbank.png" onclick="checkbox_select(this);">
        </li>
        <li>
          <img class="img-responsive" name="carrier" value="side_navi_ymobile" check="off"
          src="http:\\localhost\src\re\img\pc\sideLogo_ymobile.png" onclick="checkbox_select(this);">
        </li>
      </ul>
    </div>
    </li>
      <div class="hiddenCheckbox">
      <input type="checkbox" name="carrier" value="docomo" id="side_navi_docomo">
      <input type="checkbox" name="carrier" value="au" id="side_navi_au">
      <input type="checkbox" name="carrier" value="softbank" id="side_navi_softbank">
      <input type="checkbox" name="carrier" value="ymobile" id="side_navi_ymobile">
      </div>
    <!--side_navi_キャリアで検索 end-->
    <!--side_navi_地域で検索 start-->
    <li class="list-group-item">
    <div>
      <label>
      <b>地域で検索</b>
      </label>
      <div class="row fontSize_navi">
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li><a href="">北海道</a></li>
            <li><a href="">東北</a></li>
            <li><a href="">関東</a></li>
            <li><a href="">中部<br>北陸</a></li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li><a href="">近畿</a></li>
            <li><a href="">中国</a></li>
            <li><a href="">四国</a></li>
            <li><a href="">九州<br>沖縄</a></li>
          </ul>
        </div>
      </div>
    </div>
    </li>
    <!--side_navi_地域で検索 end-->
    <!--side_navi_その他の条件で検索 start-->
    <li class="list-group-item">
      <div>
        <label>
        <b>その他条件で検索</b>
        </label><br>
        <div class="row">
          <div class="side_navi_checkbox">
            <div class="col-lg-12 col-md-12 col-sm-12 fontSize_navi_checkbox" style="padding-right:5px;">
              <ul class="list-unstyled">
                <li><input type="checkbox" name="other_choice" value="parking">駐車場あり</li>
                <li><input type="checkbox" name="other_choice" value="kid-room">キッズルーム</li>
                <li><input type="checkbox" name="other_choice" value="holds-rates">料金収納可</li>
                <li><input type="checkbox" name="other_choice" value="after21">21時以降も営業</li>
                <li><input type="checkbox" name="other_choice" value="barrier-free">バリアフリー</li>
                <li><input type="checkbox" name="other_choice" value="study-smartphone">ケータイ教室</li>
                <li><input type="checkbox" name="other_choice" value="breakdown">故障受付可</li>
              </ul>
                <img class="img-responsive img_right img-rounded block" src="http:\\localhost\src\re\img\pc\button_search.png" alt="Responsive image">
            </div>
          </div>
        </div>
      </div>
    </li>
    <!--side_navi_その他の条件で検索 end-->
  </ul>
</div>
