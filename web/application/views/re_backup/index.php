<?= $this->load->view('re/common/header') ?>
<body>
<!-- start-->
<!-- end-->
<!--area_top start-->
<?= $this->load->view('re/common/area_top') ?>
<!--area_top end-->
<br>
<!--area_body start-->
<div class="area_body">
	<div class="outer">
		<div class="inner">
			<!--AD_coupon start-->
			<div class="centered">
				<a href="re/main_C/search/"><img class="img-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12" src="http:\\localhost\src\re\img\pc\banner_coupon.png"></a>
			</div>
			<!--AD_coupon end-->
			<div class="centered">
				<br>
				<div class="row">
					<!--side_navi start-->
					<?= $this->load->view('re/common/side_navi') ?>
					<!--side_navi end-->
					<!--main_body start -->
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 left">
						<!--searchPanel start-->
						<section class="panel panel-default">
						<div class="searchPanel_back">
							<div class="searchPanel">
								<!--searchPanel_キャリアから絞り込む start-->
								<img class="show-mobile img-rounded img-responsive" src="http:\\localhost\src\re\img\pc\button_presentLocation.png">
								<br>
								<div class="panel panel-default show-mobile">
								</div>
								<label>
								<h4><b>
								<img src="http:\\localhost\src\re\img\pc\mark_carrier.png">
								キャリアから絞り込む</b></h4>
								</label><br>
								<div class="text-center">
									<div class="row row_carrierImg">
										<a href="">
										<img class="col-lg-3 col-md-3 col-sm-3 col-xs-3" src="http:\\localhost\src\re\img\pc\logo_docomo.png">
										</a>
										<a href="">
										<img class="col-lg-3 col-md-3 col-sm-3 col-xs-3" src="http:\\localhost\src\re\img\pc\logo_au.png">
										</a>
										<a href="">
										<img class="col-lg-3 col-md-3 col-sm-3 col-xs-3" src="http:\\localhost\src\re\img\pc\logo_softbank.png">
										</a>
										<a href="">
										<img class="col-lg-3 col-md-3 col-sm-3 col-xs-3" src="http:\\localhost\src\re\img\pc\logo_ymobile.png">
										</a>
								</div>
								</div>
								<!--searchPanel_キャリアから絞り込む end-->
								<br>
								<div class="row">
									<!--searchPanel_地域から絞り込む start-->
									<div class="col-lg-7 col-md-7 col-sm-7">
										<div class="panel panel-default show-mobile">
										</div>
										<label>
										<h4><b>
										<img src="http:\\localhost\src\re\img\pc\mark_location.png">
										地域から絞り込む</b></h4>
										</label><br>
										<img class="img-responsive img-rounded img_right hidden-mobile"
										src="http:\\localhost\src\re\img\pc\map.png" usemap="#japan_map">
										<!--


										<map name="japan_map" id="japan_map">
												<area alt="1" title="" href="#" shape="poly" coords="416,107,571,110,571,147,418,148" />
												<area alt="1" title="" href="#" shape="poly" coords="592,85,723,85,724,205,624,209,621,233,556,238,555,181,585,175" />
												<area alt="2" title="" href="#" shape="poly" coords="262,386,413,387,413,422,264,422" />
												<area alt="2" title="" href="#" shape="poly" coords="298,441,418,442,419,587,370,592,365,545,298,538" />
												<area alt="3" title="" href="#" shape="poly" coords="382,290,538,291,538,327,384,325" />
												<area alt="3" title="" href="#" shape="poly" coords="555,255,655,256,656,429,605,429,600,381,555,373" />
												<area alt="4" title="" href="#" shape="poly" coords="90,384,244,384,242,424,91,421" />
												<area alt="4" title="" href="#" shape="poly" coords="160,442,280,443,281,535,158,534" />
												<area alt="5" title="" href="#" shape="poly" coords="667,518,822,519,823,556,670,554" />
												<area alt="5" title="" href="#" shape="poly" coords="603,449,655,450,658,553,551,550,548,503,598,497" />
												<area alt="6" title="" href="#" shape="poly" coords="222,629,375,630,376,665,222,662" />
												<area alt="6" title="" href="#" shape="poly" coords="221,557,348,559,346,604,225,606" />
												<area alt="7" title="" href="#" shape="poly" coords="435,574,592,574,590,611,441,609" />
												<area alt="7" title="" href="#" shape="poly" coords="534,395,584,397,588,478,536,488,528,535,435,535,435,441,526,437" />
												<area alt="8" title="" href="#" shape="poly" coords="100,708,258,710,256,750,104,745" />
												<area alt="8" title="" href="#" shape="poly" coords="104,556,201,559,201,690,90,687,87,713,67,709,69,666,95,663" />
										</map>
											moto 875.831	/2.3
											100% 382,363

											<map name="japan_map" id="japan_map">
												<area alt="1" title="" href="#" shape="poly" coords="181,47,248,48,248,64,182,64" />
												<area alt="1" title="" href="#" shape="poly" coords="257,37,314,37,315,89,271,91,270,101,242,103,241,79,254,76" />
												<area alt="2" title="" href="#" shape="poly" coords="114,168,180,168,180,183,115,183" />
												<area alt="2" title="" href="#" shape="poly" coords="130,192,182,192,182,255,161,257,159,237,130,234" />
												<area alt="3" title="" href="#" shape="poly" coords="166,126,234,127,234,142,167,141" />
												<area alt="3" title="" href="#" shape="poly" coords="241,111,285,111,285,187,263,187,261,166,241,162" />
												<area alt="4" title="" href="#" shape="poly" coords="39,167,106,167,105,184,40,183" />
												<area alt="4" title="" href="#" shape="poly" coords="70,192,122,193,122,233,69,232" />
												<area alt="5" title="" href="#" shape="poly" coords="290,225,357,226,358,242,291,241" />
												<area alt="5" title="" href="#" shape="poly" coords="262,195,285,196,286,240,240,239,238,219,260,216" />
												<area alt="6" title="" href="#" shape="poly" coords="97,237,163,274,163,289,97,288" />
												<area alt="6" title="" href="#" shape="poly" coords="96,242,151,243,150,263,98,263" />
												<area alt="7" title="" href="#" shape="poly" coords="189,250,257,250,257,266,192,265" />
												<area alt="7" title="" href="#" shape="poly" coords="232,172,254,173,256,208,233,212,230,233,189,233,189,192,229,190" />
												<area alt="8" title="" href="#" shape="poly" coords="43,308,112,309,111,326,45,324" />
												<area alt="8" title="" href="#" shape="poly" coords="45,242,87,243,87,300,39,299,38,310,29,308,30,290,41,288" />
											</map>
									-->
									<map name="japan_map" id="japan_map">
										<area alt="1" title="" href="#" shape="poly" coords="181,47,248,48,248,64,182,64" />
										<area alt="1" title="" href="#" shape="poly" coords="257,37,314,37,315,89,271,91,270,101,242,103,241,79,254,76" />
										<area alt="2" title="" href="#" shape="poly" coords="114,168,180,168,180,183,115,183" />
										<area alt="2" title="" href="#" shape="poly" coords="130,192,182,192,182,255,161,257,159,237,130,234" />
										<area alt="3" title="" href="#" shape="poly" coords="166,126,234,127,234,142,167,141" />
										<area alt="3" title="" href="#" shape="poly" coords="241,111,285,111,285,187,263,187,261,166,241,162" />
										<area alt="4" title="" href="#" shape="poly" coords="39,167,106,167,105,184,40,183" />
										<area alt="4" title="" href="#" shape="poly" coords="70,192,122,193,122,233,69,232" />
										<area alt="5" title="" href="#" shape="poly" coords="290,225,357,226,358,242,291,241" />
										<area alt="5" title="" href="#" shape="poly" coords="262,195,285,196,286,240,240,239,238,219,260,216" />
										<area alt="6" title="" href="#" shape="poly" coords="97,237,163,274,163,289,97,288" />
										<area alt="6" title="" href="#" shape="poly" coords="96,242,151,243,150,263,98,263" />
										<area alt="7" title="" href="#" shape="poly" coords="189,250,257,250,257,266,192,265" />
										<area alt="7" title="" href="#" shape="poly" coords="232,172,254,173,256,208,233,212,230,233,189,233,189,192,229,190" />
										<area alt="8" title="" href="#" shape="poly" coords="43,308,112,309,111,326,45,324" />
										<area alt="8" title="" href="#" shape="poly" coords="45,242,87,243,87,300,39,299,38,310,29,308,30,290,41,288" />
									</map>
										<!-- mobile_地域から絞り込む start -->
										<div class="panel panel-default show-mobile">
											<table>
											<tr>
												<td>
													<a href="re/main_C/view/1011"><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_1.png"></a>
												</td>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_2.png"></a>
												</td>
											</tr>
											<tr>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_3.png"></a>
												</td>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_4.png"></a>
												</td>
											</tr>
											<tr>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_5.png"></a>
												</td>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_6.png"></a>
												</td>
											</tr>
											<tr>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_7.png"></a>
												</td>
												<td>
													<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\pc\table_8.png"></a>
												</td>
											</tr>
											</table>
										</div>
										<div class="panel panel-default show-mobile">
										</div>
										<!-- mobile_地域から絞り込む end -->
									</div>
									<!--searchPanel_地域から絞り込む end-->

									<div class="col-lg-5 col-md-5 col-sm-5">

										<div class="show-mobile padding">
											<div class="col-xs-6">
												<b>路線名<br>
												<input type="text" class="text_border"></b>
											</div>
											<div class="col-xs-6 padding">
												<b>駅名<br>
												<input type="text" class="text_border"></b>
											</div>
											<div class="right padding">
												<img src="http:\\localhost\src\re\img\pc\button_search.png">
											</div>
										</div>

										<div class="panel panel-default show-mobile">
										</div>
										<!--searchPanel_その他条件を指定 start-->
										<label>
										<h4><b>
										<img src="http:\\localhost\src\re\img\pc\mark_barrier.png">
										その他条件を指定 </b></h4>
										</label><br>
										<div class="text-inline">
											<div class="row" style="padding-left:10%">
												<div class="checkbox">
													<div class="col-lg-12 col-md-12 col-sm-12col-xs-6 left checkboxPaddin">
														<ul class="list-unstyled fontSize">
															<li><input type="checkbox" name="other_choice" value="parking">駐車場あり</li>
															<li><input type="checkbox" name="other_choice" value="kid-room">キッズルーム</li>
															<li><input type="checkbox" name="other_choice" value="holds-rates">料金収納可</li>
															<li><input type="checkbox" name="other_choice" value="after21">21時以降も営業</li>
														</ul>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-6 left">
														<ul class="list-unstyled fontSize">
															<li><input type="checkbox" name="other_choice" value="barrier-free">バリアフリー</li>
															<li><input type="checkbox" name="other_choice" value="study-smartphone">ケータイ教室</li>
															<li><input type="checkbox" name="other_choice" value="breakdown">故障受付可</li>
														</ul>
													</div>
												</div>
											</div>
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
								<!-- mobile start-->
								<!--side_navi start-->
								<div class="col-xs-12 show-mobile	">
								</div>
								<!-- mobile end -->
								<!--AD_section start-->
								<section class="text-center AD_section">
								<a href="">
								<img class="img-responsive col-lg-6 col-md-6 col-sm-6 col-xs-12 padding" src="http:\\localhost\src\re\img\pc\banner_classes.png"></a>
								<a href=""><img class="img-responsive col-lg-6 col-md-6 col-sm-6 col-xs-12 padding" src="http:\\localhost\src\re\img\pc\banner_21hours.png"></a>
								</section>
								<div class="dumy">
									 dumy
								</div>
								<!--AD_section end-->
								<!--最新レビュー start-->
								<?= $this->load->view('re/common/review') ?>
								<!--最新レビュー end-->
							</div>
						</div>
					</div>
					<!--main_body end -->
				</div>
			</div>
		</div>
		<!--area_body end-->
		<!--area_footer start-->
		<?= $this->load->view('re/common/area_bottom') ?>
		<!--area_footer end-->
		</body>
		</html>
