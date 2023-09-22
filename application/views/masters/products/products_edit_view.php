<?php $this->load->view('include/header'); ?>
<style>
li {
  list-style-type: none;
}

.active > a , .active > a:hover {
	background: none;
}

.pg-footer .pg-footer-inner .pg-footer-content {
		position: fixed;
		z-index: 100;
		left: 0px;
		right: 0px;
		bottom: 0px;
		padding: 8px;
		padding-bottom: 15px;
		line-height: 20px;
		background-color:lightsalmon;
}

.bp-footer .bp-footer-inner .bp-footer-content {
		position: fixed;
		left: 0px;
		right: 0px;
		bottom: 0px;
		padding: 8px;
		line-height: 50px;
		background-color:white;
		border-top: solid 1px #dddddd;

}
.header-menu {
	text-align: center;
	vertical-align: middle;
	padding: 8px;
	line-height: 20px;
}


.footer-menu {
	float: left;
	text-align: center;
	vertical-align: middle;
	/*padding: 8px;*/
	line-height: 20px;
	/*border-right: solid 1px #dddddd;*/
}

.footer-menu span {
	display:block;
	font-size: 10px;
	color:white;
}

.content {
  border-left:solid 1px #ccc;
  min-height: 600px;
}

@media (max-width:767px) {
  .help-block {
    margin-top: 0px;
    margin-bottom: 0px;
  }

  .tab-content {
    margin-top:-30px;
  }

  .content {
    padding-top:15px;
    border-left:none;
    padding-left:0px;
    padding-right:0px;
  }
}
</style>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr />
<script src="<?php echo base_url(); ?>assets/js/dropzone.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/dropzone.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/colorbox.css" />
<?php
$tab1 = $tab == 'styleTab' ? 'active in' : '';
$tab2 = $tab == 'itemTab' ? 'active in' : '';
$tab3 = $tab == 'imageTab' ? 'active in' : '';
$tab4 = $tab == 'priceTab' ? 'active in' : '';

?>




<div class="row">
<div class="col-lg-1-harf col-md-2 col-sm-2 hidden-xs padding-right-0 padding-top-15">
	<ul id="myTab1" class="setting-tabs width-100" style="margin-left:0px;">
	  <li class="li-block <?php echo $tab1; ?>" onclick="changeURL('<?php echo $style->code; ?>','styleTab')" >
			<a href="#styleTab" data-toggle="tab" id="styleTab-a" style="text-decoration:none;">Info</a>
		</li>
		<li class="li-block <?php echo $tab2; ?>" onclick="changeURL('<?php echo $style->code; ?>','itemTab')" >
			<a href="#itemTab" data-toggle="tab" id="itemTab-a" style="text-decoration:none;">Items</a>
		</li>
		<li class="li-block <?php echo $tab3; ?>" onclick="changeURL('<?php echo $style->code; ?>','imageTab')" >
			<a href="#imageTab" data-toggle="tab" id="imageTab-a" style="text-decoration:none;" >Images</a>
		</li>
		<li class="li-block <?php echo $tab4; ?>" onclick="changeURL('<?php echo $style->code; ?>','priceTab')" >
			<a href="#priceTab" data-toggle="tab" id="priceTab-a" style="text-decoration:none;" >Prices</a>
		</li>
	</ul>
</div>

<div class="col-lg-10-harf col-md-10 col-sm-10 col-xs-12 content" >
<div class="tab-content" style="border:0;">
	<div class="tab-pane fade <?php echo $tab1; ?>" id="styleTab">
		<?php $this->load->view('masters/products/product_edit_info'); ?>
	</div>
	<div class="tab-pane fade <?php echo $tab2; ?>" id="itemTab">
		<?php $this->load->view('masters/products/product_items'); ?>
	</div>
	<div class="tab-pane fade <?php echo $tab3; ?>" id="imageTab">
		<?php $this->load->view('masters/products/product_image'); ?>
	</div>
	<div class="tab-pane fade <?php echo $tab4; ?>" id="priceTab">
		<?php $this->load->view('masters/products/product_edit_price'); ?>
	</div>
</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->

<div class="pg-footer visible-xs">
	<div class="pg-footer-inner">
		<div class="pg-footer-content text-right">
			<div class="footer-menu width-20">
				<li  onclick="changeURL('<?php echo $style->code; ?>','styleTab', 'a')">
					<a href="#" data-toggle="tab" style="text-decoration:none;"><i class="fa fa-info-circle fa-2x white"></i><span>Info</span></a>
				</li>
			</div>
			<div class="footer-menu width-25">
				<li onclick="changeURL('<?php echo $style->code; ?>','itemTab', 'a')">
					<a href="#" data-toggle="tab" style="text-decoration:none;"><i class="fa fa-cubes fa-2x white"></i><span>Items</span></a>
				</li>
			</div>
			<div class="footer-menu width-25">
				<li onclick="changeURL('<?php echo $style->code; ?>','imageTab', 'a')">
					<a href="#" data-toggle="tab" style="text-decoration:none;"><i class="fa fa-file-image-o fa-2x white"></i><span>Images</span></a>
				</li>
			</div>
			<div class="footer-menu width-25">
				<li onclick="changeURL('<?php echo $style->code; ?>','priceTab', 'a')">
					<a href="#" data-toggle="tab" style="text-decoration:none;"><i class="fa fa-tags fa-2x white"></i><span>Prices</span></a>
				</li>
			</div>
		</div>
	</div><!-- footer inner-->
</div><!-- /.footer -->

<script src="<?php echo base_url(); ?>scripts/masters/products.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/product_info.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/product_image.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/product_items.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/product_price.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
