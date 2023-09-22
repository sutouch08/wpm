<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr style="margin-bottom:0px;"/>
<script src="<?php echo base_url(); ?>assets/js/dropzone.js"></script>
<link rel="stylesheet" href="<?php  echo base_url();?>assets/css/dropzone.css" />
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-30" id="styleTab">
		<?php $this->load->view('masters/products/product_info'); ?>
	</div>
</div><!--/ row  -->

<script src="<?php echo base_url(); ?>scripts/masters/products.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/product_info.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/code_validate.js"></script>
<?php $this->load->view('include/footer'); ?>
