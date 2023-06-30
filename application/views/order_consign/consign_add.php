<?php $this->load->view('include/header'); ?>
<?php $manual_code = getConfig('MANUAL_DOC_CODE');  ?>
<?php if($manual_code == 1) :?>
<?php  $prefix = $this->role == 'C' ? getConfig('PREFIX_CONSIGN_SO') : getConfig('PREFIX_CONSIGN_TR'); ?>
<?php  $runNo = $this->role == 'C' ? getConfig('RUN_DIGIT_CONSIGN_SO') : getConfig('RUN_DIGIT_CONSIGN_TR'); ?>
	<input type="hidden" id="manualCode" value="<?php echo $manual_code; ?>">
	<input type="hidden" id="prefix" value="<?php echo $prefix; ?>">
	<input type="hidden" id="runNo" value="<?php echo $runNo; ?>">
<?php endif; ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title title-xs">
			<?php echo $this->title; ?>
		</h3>
	</div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
  	<p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="addForm" method="post" action="<?php echo $this->home; ?>/add">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Document No</label>
		<?php if($manual_code == 1) : ?>
	    <input type="text" class="form-control input-sm" name="code" id="code" value="" />
		<?php else : ?>
			<input type="text" class="form-control input-sm" value="" disabled />
		<?php endif; ?>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center" name="date" id="date" value="<?php echo date('d-m-Y'); ?>" required />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Customer</label>
    <input type="text" class="form-control input-sm text-center" name="customerCode" id="customerCode" value="" required />
  </div>

  <div class="col-lg-6 col-md-5 col-sm-6-harf col-xs-8 padding-5">
    <label class="not-show">customer</label>
    <input type="text" class="form-control input-sm" name="customer" id="customer" value="" required />
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>GP</label>
		<div class="input-group width-100">
			<input type="number" class="form-control input-sm" name="gp" id="gp" value="" />
			<span class="input-group-addon" style="background-color:white;">%</span>
		</div>
		<input type="hidden" name="unit" id="unit" value="%"/>
	</div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label>Currency</label>
		<select class="form-control input-sm" name="doc_currency" id="doc_currency" onchange="updateDocRate()">
			<?php echo select_currency(); ?>
		</select>
  </div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label>Rate</label>
		<input type="number" class="form-control input-sm text-center" name="doc_rate" id="doc_rate" value="1.00" />
  </div>

	<div class="col-lg-3 col-md-3 col-sm-4-harf col-xs-6 padding-5">
		<label>From Warehouse</label>
    <select class="form-control input-sm" name="warehouse" id="warehouse" required>
			<option value="">Please Select</option>
			<?php echo select_sell_warehouse(); ?>
		</select>
  </div>

	<div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-6 padding-5">
    <label>To Location</label>
    <input type="text" class="form-control input-sm text-center" name="zone_code" id="zone_code" value="" required />
  </div>

	<div class="col-lg-5 col-md-4-harf col-sm-4-harf col-xs-6 padding-5">
    <label class="not-show">โซน[ฝากขาย]</label>
		<input type="text" class="form-control input-sm" name="zone" id="zone" value="" />
  </div>


  <div class="col-lg-11 col-md-10-harf col-sm-6 col-xs-9 padding-5">
    <label>Remark</label>
    <input type="text" class="form-control input-sm" name="remark" id="remark" value="">
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-xs btn-success btn-block" onclick="add()"><i class="fa fa-plus"></i> Add</button>
  </div>
</div>
</form>
<hr class="margin-top-15">

<?php if($this->menu_code == 'SOCCSO') : ?>
<script src="<?php echo base_url(); ?>scripts/order_consign/consign_so.js?v=<?php echo date('Ymd'); ?>"></script>
<?php else : ?>
<script src="<?php echo base_url(); ?>scripts/order_consign/consign_tr.js?v=<?php echo date('Ymd'); ?>"></script>
<?php endif; ?>
<script src="<?php echo base_url(); ?>scripts/order_consign/consign.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/order_consign/consign_add.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
