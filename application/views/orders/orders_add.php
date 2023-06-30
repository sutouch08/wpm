<?php $this->load->view('include/header'); ?>
<?php $manual_code = getConfig('MANUAL_DOC_CODE');  ?>
<?php if($manual_code == 1) :?>
	<input type="hidden" id="manualCode" value="<?php echo $manual_code; ?>">
	<input type="hidden" id="prefix" value="<?php echo getConfig('PREFIX_ORDER'); ?>">
	<input type="hidden" id="runNo" value="<?php echo getConfig('RUN_DIGIT_ORDER'); ?>">
<?php endif; ?>

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h4 class="title"><?php echo $this->title; ?></h4>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
  	<p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-warning btn-top" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    </p>
  </div>
</div><!-- End Row -->
<hr class=""/>
<form id="addForm" method="post" action="<?php echo $this->home; ?>/add">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Document No</label>
	<?php if($manual_code == 1) : ?>
    <input type="text" class="form-control input-sm" name="code" id="code" value="" />
	<?php else : ?>
		<input type="text" class="form-control input-sm" value="" disabled />
	<?php endif; ?>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center" name="date" id="date" value="<?php echo date('d-m-Y'); ?>" required readonly />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Customer</label>
		<input type="text" class="form-control input-sm text-center" id="customer_code" name="customer_code" value=""/>
	</div>

	<div class="col-lg-4 col-md-6-harf col-sm-6 col-xs-8 padding-5">
		<label class="not-show">cust</label>
		<input type="text" class="form-control input-sm" name="customer" id="customer" value="" required />
	</div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
		<label>Customer ref.</label>
		<input type="text" class="form-control input-sm" name="cust_ref" value="" />
	</div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label>Currency</label>
		<select class="form-control input-sm" name="doc_currency" id="doc_currency" onchange="updateDocRate()">
			<?php echo select_currency(); ?>
		</select>
  </div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label>Rate</label>
		<input type="number" class="form-control input-sm text-center" name="doc_rate" id="doc_rate" value="1.00" />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Channels</label>
		<select class="form-control input-sm" name="channels" required>
			<option value="">Please select</option>
			<?php echo select_channels(); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Payments</label>
		<select class="form-control input-sm" name="payment" id="payment" required>
			<option value="">Please select</option>
			<?php echo select_payment_method(); ?>
		</select>
  </div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5 hide">
    <label>แปรสภาพ</label>
		<select class="form-control input-sm" name="transformed">
			<option value="0">No</option>
			<option value="1">Yes</option>
		</select>
  </div>

	<div class="col-lg-2-harf col-md-2 col-sm-3 col-xs-6 padding-5">
		<label>Warehouse</label>
    <select class="form-control input-sm" name="warehouse" id="warehouse" required>
			<option value="">Please select</option>
			<?php echo select_sell_warehouse(); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Order ref.</label>
		<input type="text" class="form-control input-sm" name="reference" value="" />
  </div>

  <div class="col-lg-4 col-md-10-harf col-sm-8-harf col-xs-8 padding-5">
    <label>Remark</label>
    <input type="text" class="form-control input-sm" name="remark" id="remark" value="">
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label class="display-block not-show">Submit</label>
	<?php if($manual_code == 1) : ?>
    <button type="Button" class="btn btn-xs btn-success btn-block" onclick="validateOrder()"><i class="fa fa-plus"></i> Add</button>
		<button type="submit" class="btn btn-xs btn-success btn-block hide" id="btn-submit">Add</button>
	<?php else : ?>
		<button type="button" class="btn btn-xs btn-success btn-block" onclick="add()"><i class="fa fa-plus"></i> Add</button>
		<button type="submit" class="btn btn-xs btn-success btn-block hide" id="btn-submit">Add</button>
	<?php endif; ?>
  </div>
</div>
<hr class="margin-top-15">
<input type="hidden" name="customerCode" id="customerCode" value="" />
</form>

<script src="<?php echo base_url(); ?>scripts/orders/orders.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/orders/order_add.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
