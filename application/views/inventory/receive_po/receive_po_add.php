<?php $this->load->view('include/header'); ?>
<?php $manual_code = getConfig('MANUAL_DOC_CODE');  ?>
<?php if($manual_code == 1) :?>
	<input type="hidden" id="manualCode" value="<?php echo $manual_code; ?>">
	<input type="hidden" id="prefix" value="<?php echo getConfig('PREFIX_RECEIVE_PO'); ?>">
	<input type="hidden" id="runNo" value="<?php echo getConfig('RUN_DIGIT_RECEIVE_PO'); ?>">
<?php endif; ?>
<input type="hidden" id="required_remark" value="<?php echo $this->required_remark; ?>" />
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 padding-5">
    <h3 class="title" >
      <?php echo $this->title; ?>
    </h3>
	</div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 padding-5">
    <p class="pull-right top-p">
			<button type="button" class="btn btn-xs btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    </p>
  </div>
</div>
<hr />

<form id="addForm" action="<?php echo $this->home.'/add'; ?>" method="post">
<div class="row">
    <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    	<label>Document No.</label>
			<?php if($manual_code == 1) : ?>
				<input type="text" class="form-control input-sm" name="code" id="code" value="" required />
			<?php else : ?>
				<input type="text" class="form-control input-sm" value="" disabled />
			<?php endif; ?>
    </div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    	<label>Date</label>
      <input type="text" class="form-control input-sm text-center" name="date_add" id="dateAdd" value="<?php echo date('d-m-Y'); ?>" readonly/>
    </div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5 hide">
			<label>WMS</label>
			<select class="form-control input-sm" name="is_wms" id="is_wms" disabled>
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
		</div>
    <div class="col-lg-6-harf col-md-6 col-sm-5 col-xs-8 padding-5">
    	<label>Remark</label>
        <input type="text" class="form-control input-sm" name="remark" id="remark" placeholder="" />
    </div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label class="display-block not-show">save</label>
			<?php if($this->pm->can_add) : ?>
				<?php if($manual_code == 1) : ?>
							<button type="button" class="btn btn-xs btn-success btn-block" onclick="validateOrder()"><i class="fa fa-plus"></i> Add</button>
				<?php else : ?>
							<button type="button" class="btn btn-xs btn-success btn-block" onclick="addNew()"><i class="fa fa-plus"></i> Add</button>
				<?php endif; ?>
			<?php	endif; ?>
		</div>
</div>
</form>
<hr class="margin-top-15"/>

<script src="<?php echo base_url(); ?>scripts/inventory/receive_po/receive_po.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/receive_po/receive_po_add.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
