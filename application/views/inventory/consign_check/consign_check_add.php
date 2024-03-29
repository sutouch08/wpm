<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6 padding-5">
    	<h3 class="title" >
        <?php echo $this->title; ?>
      </h3>
	</div>
    <div class="col-sm-6 padding-5">
      <p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
      </p>
    </div>
</div>
<hr />

<form id="addForm" action="<?php echo $this->home.'/add'; ?>" method="post">
<div class="row">
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>Doc No</label>
        <input type="text" class="form-control input-sm text-center" value="" disabled />
    </div>
		<div class="col-sm-1 col-1-harf padding-5">
    	<label>Date</label>
      <input type="text" class="form-control input-sm text-center" name="date_add" id="dateAdd" value="<?php echo date('d-m-Y'); ?>" readonly />
    </div>
		<div class="col-sm-4 padding-5">
			<label>Customer</label>
			<input type="text" class="form-control input-sm edit" name="customer" id="customer" value=""  required/>
		</div>
		<div class="col-sm-5 padding-5">
			<label>Location[consignment]</label>
			<input type="text" class="form-control input-sm edit" name="zone" id="zone" value="" required />
		</div>
		<div class="col-sm-1 col-1-harf padding-5 hide">
			<label>ช่องทางการรับ</label>
			<select class="form-control input-sm" name="is_wms" id="is_wms">
				<option value="0">Warrix</option>
				<!--<option value="1">WMS</option>-->
			</select>
		</div>
		<div class="col-sm-10 col-10-harf padding-5">
    	<label>Remark</label>
        <input type="text" class="form-control input-sm" name="remark" id="remark" />
    </div>
		<div class="col-sm-1 col-1-harf padding-5">
			<label class="display-block not-show">add</label>
			<button type="button" class="btn btn-xs btn-success btn-block" onclick="add()"><i class="fa fa-plus"></i> Add</button>
		</div>
</div>
<input type="hidden" name="zone_code" id="zone_code">
<input type="hidden" name="customer_code" id="customer_code">
</form>

<script src="<?php echo base_url(); ?>scripts/inventory/consign_check/consign_check.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/consign_check/consign_check_add.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
