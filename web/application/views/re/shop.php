	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	?>
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
				<div class="centered">
					<div class="row">
						<!--side_navi start-->
						<?= $this->load->view('re/common/side_navi') ?>
						<!--side_navi end-->
						<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 left">
							<!--top_navi start-->
							<div class="container hidden-mobile">
								<ul class="list-inline">
									<li><a href="#">検索TOP</a></li>
									<li><img src="http:\\localhost\src\re\img\shop\allow.png"></li>
									<li><a href="#">
										<?php
										switch ($shopInfo['carrier']) {
										case 'd':
												echo "docomo";
												break;
										case "a":
												echo "au";
												break;
										case "s":
												echo "softbank";
												break;
										case "y":
												echo "ymobile";
												break;
										}
										 ?>
									</a></li>
									<li><img src="http:\\localhost\src\re\img\shop\allow.png"></li>
									<li><a href="#">○○市</a></li>
									<li><img src="http:\\localhost\src\re\img\shop\allow.png"></li>
									<li><a href="#">○○区</a></li>
									<li><img src="http:\\localhost\src\re\img\shop\allow.png"></li>
									<li><a href="#">○○店</a></li>
								</ul>
							</div>
							<!--top_navi end-->
							<!--carrier start-->
							<div class="container">
								<div class="row">
									<a href="#">
										<?php
										switch ($shopInfo['carrier']) {
										case 'd':
												echo "<img class='img-reponsive col-lg-3 col-md-3 col-sm-4 col-xs-5' src='http://localhost/src/re/img/shop/docomo.png'></a>";
												break;
										case "a":
												echo "<img class='img-reponsive col-lg-3 col-md-3 col-sm-4 col-xs-5' src='http://localhost/src/re/img/shop/au.png'></a>";
												break;
										case "s":
												echo "<img class='img-reponsive col-lg-3 col-md-3 col-sm-4 col-xs-5' src='http://localhost/src/re/img/shop/softbank.png'></a>";
												break;
										case "y":
												echo "<img class='img-reponsive col-lg-3 col-md-3 col-sm-4 col-xs-5' src='http://localhost/src/re/img/shop/ymobile.png'></a>";
												break;
										}
										 ?>

									<a href="#">
									<h2><?=$shopInfo['name']?></h2>
									</a>
								</div>
							</div>
							<br>
							<!--carrier end-->
							<!--photo_shop start-->
							<img src="http:\\localhost\src\re\img\shop\shop.png" class="img-rounded img-responsive col-lg-12 col-md-12 col-sm-7 col-xs-12 padding">
							<!--photo_shop end-->
							<!--shopInfo start-->
							<div class="container col-lg-12 col-md-12 col-sm-5 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-heading" style="background-color:#c1021b;">
										<img src="http:\\localhost\src\re\img\shop\title_shopInfo.png">
									</div>
									<div class="panel-body back_gray">

											<a href=""><img class="img-responsive img_right" src="http:\\localhost\src\re\img\shop\button_moveMap.png"></a>
										<table>
											<tr>
												<td>
													<img class="img-responsive" src="http:\\localhost\src\re\img\shop\icon_address.png">
												</td>
												<td>
													<b>&nbsp;住所.アクセス</b>
												</td>
											</tr>
										</table>
										<img class="img-responsive" src="http:\\localhost\src\re\img\shop\divLine.png">
										<?=$shopInfo['address']?>
										<br>
										<br>
										<div>
											<table>
												<tr>
													<td>
														<img class="img-responsive" src="http:\\localhost\src\re\img\shop\icon_hours.png">
													</td>
													<td>
														<b>&nbsp;営業時間.定休日</b>
													</td>
												</tr>
											</table>
										<img class="img-responsive" src="http:\\localhost\src\re\img\shop\divLine.png">

										<p>
											<?=$shopInfo['hours']?>
											<?=$shopInfo['building']?>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											定休日：<?=$shopInfo['holiday']?>
										</p>
									</div>
									</div>
								</div>
							</div>
							<!--shopInfo end-->
							<!--hitCoupon start-->
							<div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-heading" style="background-color:#c1021b;">
										<img src="http:\\localhost\src\re\img\shop\title_coupon.png">
									</div>
									<div class="panel-body back_gray">
										<a href=""><img class="img-responsive" src="http:\\localhost\src\re\img\shop\box_coupon.png"></a>
									</div>
								</div>
							</div>
							<!--hitCoupon end-->
							<!--shopDetail start-->
							<div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-heading" style="background-color:#c1021b;">
										<img src="http:\\localhost\src\re\img\shop\title_shopInfo.png">
									</div>
									<div class="panel-body back_gray">
										<div>
											<table>
												<tr>
													<td>
															<img class="img-responsive" src="http:\\localhost\src\re\img\shop\icon_service.png">
													</td>
													<td>
														<b>&nbsp;サービス一覧</b>
													</td>
												</tr>
											</table>
											<img class="img-responsive" src="http:\\localhost\src\re\img\shop\divLine.png">
											<div class="row">
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<?php
														if ($shopInfo['parking_flag'] == 1) {
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/parking.png'>";
														}else{
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/parking_.png'>";
														}
													?>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<?php
														if ($shopInfo['barrier_free_flag'] == 1) {
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/barrier_free.png'>";
														}else{
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/barrier_free_.png'>";
														}
													?>

												</div>
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<?php
														if ($shopInfo['kids_flag'] == 1) {
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/kids.png'>";
														}else{
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/kids_.png'>";
														}
													?>

												</div>
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<?php
														if ($shopInfo['classes_flag'] == 1) {
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/classes.png'>";
														}else{
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/classes_.png'>";
														}
													?>

												</div>
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<?php
														if ($shopInfo['payment_flag'] == 1) {
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/payment.png'>";
														}else{
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/payment_.png'>";
														}
													?>

												</div>
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<?php
														if ($shopInfo['repair_flag'] == 1) {
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/repair.png'>";
														}else{
															echo "<img class='img-responsive' src='http://localhost/src/re/img/shop/flag/repair_.png'>";
														}
													?>

												</div>
												<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 fontSize">
													<img class="img-responsive" src="http:\\localhost\src\re\img\shop\flag\21hour_.png">
												</div>
											</div>
											<table>
												<tr>
													<td>
															<img class="img-responsive" src="http:\\localhost\src\re\img\shop\icon_presentLocation.png">
													</td>
													<td>
														<b>&nbsp;ショップから周辺マップ</b>
													</td>
												</tr>
											</table>
											<img class="img-responsive" src="http:\\localhost\src\re\img\shop\divLine.png">
											<img class="img-responsive" src="http:\\localhost\src\re\img\shop\mapArea.png">
											<br>
											<div class="shopDetailTable">
												<table>
												<tr>
													<td id="gray">
														店舗名
													</td>
													<td id="white">
														<?=$shopInfo['name']?>
													</td>
												</tr>
												<tr>
													<td id="gray">
														取扱キャリア
													</td>
													<td id="white">
															<?php
															switch ($shopInfo['carrier']) {
															case 'd':
															    echo "docomo";
															    break;
															case "a":
															    echo "au";
															    break;
															case "s":
															    echo "softbank";
															    break;
															case "y":
															    echo "ymobile";
															    break;
															}
															 ?>
													</td>
												</tr>
												<tr>
													<td id="gray">
														所在地
													</td>
													<td id="white">
														<?=$shopInfo['address']?>
													</td>
												</tr>
												<tr>
													<td id="gray">
														アクセス
													</td>
													<td id="white">
														コラムがありません
													</td>
												</tr>
												<tr>
													<td id="gray">
														営業時間.定休日
													</td>
													<td id="white">
														営業時間：<?=$shopInfo['hours']?>
													</td>
												</tr>
												<tr>
													<td id="gray">
														ショップURL
													</td>
													<td id="white">
														<a href="<?=$shopInfo['check_url']?>" style="font-size:9px;">
														<?=$shopInfo['check_url']?>
														</a>
													</td>
												</tr>
												<tr>
													<td id="gray">
														その他サービスなど
													</td>
													<td id="white">
														コラムがありません
													</td>
												</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--shopDetail end-->
							<!--AD_section start-->
							<div class="padding">
								<img class="img-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 padding" src="http:\\localhost\src\re\img\shop\banner_coupon.png">
								<a href="">
								<img class="img-responsive col-lg-6 col-md-6 col-sm-6 col-xs-12 block padding" src="http:\\localhost\src\re\img\shop\banner_classes.png"></a>
								<a href=""><img class="img-responsive col-lg-6 col-md-6 col-sm-6 col-xs-12 block padding" src="http:\\localhost\src\re\img\shop\banner_21hours.png"></a>
							</div>
							<!--AD_section end-->
							<span class="dumy">
							hr </span>
							<!--最新レビュー start-->
							<?= $this->load->view('re/common/review') ?>
							<!--最新レビュー end-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--area_body end-->
	<!--area_footer start-->
			<?= $this->load->view('re/common/area_bottom') ?>
	</body>
	</html>
