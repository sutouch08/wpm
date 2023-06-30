<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?></h3>
	</div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Pick List</button>
      <button type="button" class="btn btn-sm btn-yellow" onclick="goProcess()"><i class="fa fa-arrow-left"></i> Picking List</button>
    </p>
  </div>
</div>

<hr class="padding-5" />
<?php if($order->state != 4) : ?>
<?php   $this->load->view('inventory/prepare/invalid_state'); ?>
<?php else : ?>

  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Document No</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled>
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
      <label>Doc Date</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo thai_date($order->date_add); ?>" disabled>
    </div>
    <div class="col-lg-6-harf col-md-6-harf col-sm-6-harf col-xs-6 padding-5">
      <label>Cust./Emp.</label>
      <input type="text" class="form-control input-sm" value="<?php echo ($order->customer_ref == '' ? $order->customer_name : $order->customer_ref);  ?>" disabled>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Channles</label>
      <input type="text" class="form-control input-sm" value="<?php echo $order->channels_name; ?>" disabled>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 margin-top-10">
      <label>Remark</label>
      <input type="text" class="form-control input-sm" value="<?php echo $order->remark; ?>" disabled>
    </div>

    <input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
  </div>


  <hr class="margin-top-10 margin-bottom-10"/>

  <?php $this->load->view('inventory/prepare/prepare_control'); ?>

  <hr class="margin-top-10 margin-bottom-10"/>

  <?php $this->load->view('inventory/prepare/prepare_incomplete_list');  ?>

  <?php $this->load->view('inventory/prepare/prepare_completed_list'); ?>

<?php endif; //--- endif order->state ?>

<script src="<?php echo base_url(); ?>scripts/inventory/prepare/prepare.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/prepare/prepare_process.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/beep.js"></script>

<?php $this->load->view('include/footer'); ?>
