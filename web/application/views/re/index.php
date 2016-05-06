<?= $this->load->view('re/common/header') ?>
<body>
<!-- start-->
<!-- end-->
<!--area_top start-->
<?= $this->load->view('re/common/area_top') ?>
<!--area_top end-->
<!--area_body start-->
<div class="area_body">
	<div class="outer">
		<div class="inner">
			<!--AD_coupon start-->
				<?= $this->load->view('re/banner/main') ?>
			<!--AD_coupon end-->
			<div class="centered">
				<div class="row">
					<!--side_navi start-->
					<?= $this->load->view('re/common/side_navi') ?>
					<!--side_navi end-->
					<!--main_body start -->
					<?= $this->load->view('re/main/main')?>
					<!--main_body end -->
				</div>
			</div>
		</div>
	</div>
</div>
		<!--area_body end-->
		<!--area_footer start-->
		<?= $this->load->view('re/common/area_bottom') ?>
		<!--area_footer end-->
		</body>
		</html>
