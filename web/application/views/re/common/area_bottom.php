<div class="area_footer hidden-mobile">
	<div class="outer sitemap">
		<div class="inner">
			<div class="centered">
				<div class="row">
						<div class="container">
							<section>
							<div class="col-lg-2 col-md-2 col-sm-2">
								<strong>キャリア別検索</strong>
								<HR width="80%" align="right" style="color:red; background-color:red; height:1px; border:none" />
								<ul class="list-unstyled">
									<li>docomo</li>
									<li>au</li>
									<li>softbank</li>
									<li>y!mobile</li>
								</ul>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<strong>地域別検索<hr /></strong>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<ul class="list-unstyled">
											<li>北海道</li>
											<li>東北</li>
											<li>関東</li>
											<li>中部・北陸</li>
										</ul>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6">
										<ul class="list-unstyled">
											<li>近畿</li>
											<li>中国</li>
											<li>四国</li>
											<li>九州・沖縄</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<strong>特集一覧								<hr /></strong>

								<ul class="list-unstyled">
									<li>スマホ教室の開催中のショップ</li>
									<li>21時以降も営業のお店</li>
								</ul>
							</div>
							<br>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<img src="http:\\localhost\src\re\img\pc\button_facebook.png" class="img-responsive padding">
								<img src="http:\\localhost\src\re\img\pc\tweet.png" class="img-responsive padding">
								<img src="http:\\localhost\src\re\img\pc\button_line.png" class="img-responsive padding">
							</div>
							</section>
						</div>
				</div>
			</div>
			<?= $this->
			load->view('re/common/area_footer') ?>
		</div>
	</div>
</div>


<div class="area_footer show-mobile">
	<div class="outer">
		<div class="inner">
        <section>
				<div class="bottom_mobileSearchTitle">
					 <strong>ショップを探す</strong>
				</div>
				<div class="bottom_mobileSearchSmalltitle">
          <img src="http:\\localhost\src\re\img\pc\mark_carrier.png">
					 キャリアから探す
				</div>
				<div class="bottom_mobileSearchContent">
					<div class="text-center">
				    <div class="row">
				      <img class="img-responsive col-lg-3 col-md-3 col-sm-3 col-xs-3" name="carrier" value="bottom_docomo" check="off"
				      src="http:\\localhost\src\re\img\pc\sideLogo_docomo.png" onclick="checkbox_select(this);">
				      <img class="img-responsive col-lg-3 col-md-3 col-sm-3 col-xs-3" name="carrier" value="bottom_au" check="off"
				      src="http:\\localhost\src\re\img\pc\sideLogo_au.png" onclick="checkbox_select(this);">
				      <img class="img-responsive col-lg-3 col-md-3 col-sm-3 col-xs-3" name="carrier" value="bottom_softbank" check="off"
				      src="http:\\localhost\src\re\img\pc\sideLogo_softbank.png" onclick="checkbox_select(this);">
				      <img class="img-responsive col-lg-3 col-md-3 col-sm-3 col-xs-3" name="carrier" value="bottom_ymobile" check="off"
				      src="http:\\localhost\src\re\img\pc\sideLogo_ymobile.png" onclick="checkbox_select(this);">
				  </div>
				    <div class="hiddenCheckbox">
				    <input type="checkbox" name="carrier" value="docomo" id="bottom_docomo">
				    <input type="checkbox" name="carrier" value="au" id="bottom_au">
				    <input type="checkbox" name="carrier" value="softbank" id="bottom_softbank">
				    <input type="checkbox" name="carrier" value="ymobile" id="bottom_ymobile">
				    </div>
				  </ul>
        </div>
			</div>
				<div class="bottom_mobileSearchSmalltitle">
          <img src="http:\\localhost\src\re\img\pc\mark_location.png">
					 地域から探す
				</div>
				<div class="bottom_mobileSearchContent">

				</div>
				<div class="col-xs-12 bottom_mobileSearchSmalltitle center">
					<div class="col-xs-10">
            <ul id="bottom">
              <li>
                <img src="http:\\localhost\src\re\img\pc\button_facebook.png" class="img-responsive">
              </li>
              <li>
	               <img src="http:\\localhost\src\re\img\pc\button_facebookShare.png" class="img-responsive">
              </li>
              <li>
	               <img src="http:\\localhost\src\re\img\pc\tweet.png" class="img-responsive">
              </li>
              <li>
	               <img src="http:\\localhost\src\re\img\pc\button_line.png" class="img-responsive">
              </li>
            </ul>
						<img src="http:\\localhost\src\re\img\pc\divLine.png" class="img-responsive">
					</div>
					<br/>
					<br/>
					<br/>
					<table style="width:60%">
					<tr>
						 運営会社情報
					</tr>
					<tr>
						 ご利用規約
					</tr>
					<tr>
						 プライバシーポリシー
					</tr>
					</table>
				</div>
          </section>
			<!--mobile end -->
			<?= $this->
			load->view('re/common/area_footer') ?>
		</div>
	</div>
</div>
