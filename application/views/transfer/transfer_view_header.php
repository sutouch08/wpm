<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Doc No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($doc->date_add); ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-3 padding-5">
    <label>From Whs</label>
    <input type="text" class="form-control input-sm edit" name="from_warehouse_code" id="from_warehouse_code" value="<?php echo $doc->from_warehouse; ?>" disabled />
  </div>

  <div class="col-lg-3 col-md-3 col-sm-6-harf col-xs-9 padding-5">
    <label class="not-show">คลังต้นทาง</label>
    <input type="text" class="form-control input-sm edit" name="from_warehouse" id="from_warehouse" value="<?php echo $doc->from_warehouse_name; ?>" disabled/>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-3 padding-5">
    <label>To Whs</label>
    <input type="text" class="form-control input-sm edit" name="to_warehouse_code" id="to_warehouse_code" value="<?php echo $doc->to_warehouse; ?>" disabled />
  </div>

	<div class="col-lg-3 col-md-3 col-sm-6-harf col-xs-9 padding-5">
    <label class="not-show">คลังปลายทาง</label>
		<input type="text" class="form-control input-sm edit" name="to_warehouse" id="to_warehouse" value="<?php echo $doc->to_warehouse_name; ?>" disabled/>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>AGX</label>
    <select class="form-control input-sm edit" name="is_wms" id="is_wms" disabled>
			<option value="0" <?php echo is_selected('0', $doc->is_wms); ?>>No</option>
			<option value="1" <?php echo is_selected('1', $doc->is_wms); ?>>Yes</option>
		</select>
	</div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
		<label>Status</label>
		<select class="form-control input-sm edit" disabled>
			<option>Unknow</option>
      <option <?php echo is_selected('-1', $doc->status); ?>>Draft</option>
      <option <?php echo is_selected('0', $doc->status); ?>>Waiting for approve</option>
      <option <?php echo is_selected('3', $doc->status); ?>>Waiting for AGX</option>
      <option <?php echo is_selected('4', $doc->status); ?>>Waitting for acceptance</option>
      <option <?php echo is_selected('1', $doc->status); ?>>Saved</option>
      <option <?php echo is_selected('2', $doc->status); ?>>Cancelled</option>
		</select>
	</div>

  <div class="col-xs-4 padding-5 visible-xs">
		<label>SAP</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $doc->inv_code; ?>" disabled >
	</div>

  <div class="col-lg-7-harf col-md-7-harf col-sm-10 col-xs-12 padding-5">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $doc->remark; ?>" disabled>
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-2 padding-5 hidden-xs">
		<label>SAP</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $doc->inv_code; ?>" disabled >
	</div>
</div>
<input type="hidden" id="transfer_code" value="<?php echo $doc->code; ?>" />
<hr class="margin-top-15 margin-bottom-15"/>
<?php if($doc->must_accept == 1) : ?>
<div class="row margin-bottom-10">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <span class="">Location owners : </span>
    <?php if( ! empty($accept_list)) : ?>
      <?php foreach($accept_list AS $ac) : ?>
        <?php if($ac->is_accept == 1) : ?>
          <span class="label label-success label-white middle"><i class="fa fa-check-circle"></i> <?php echo $ac->display_name; ?></span>
        <?php else : ?>
          <span class="label label-default label-white middle"><?php echo $ac->display_name; ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php endif; ?>
