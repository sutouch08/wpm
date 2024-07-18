<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Doc No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-sm-6 col-xs-6 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($doc->date_add); ?>" readonly required disabled />
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>From Whs</label>
		<input type="text" class="form-control input-sm text-center edit" id="from_warehouse_code" value="<?php echo $doc->from_warehouse; ?>" disabled/>
	</div>
  <div class="col-lg-3-harf col-md-3 col-sm-3 col-xs-8 padding-5">
    <label class="not-show">&nbsp;</label>
      <input type="text" class="form-control input-sm edit" name="from_warehouse" id="from_warehouse" value="<?php echo $doc->from_warehouse_name; ?>" required disabled/>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>To Whs</label>
		<input type="text" class="form-control input-sm text-center edit" id="to_warehouse_code" value="<?php echo $doc->to_warehouse; ?>" disabled/>
	</div>
  <div class="col-lg-3-harf col-md-3 col-sm-3 col-xs-8 padding-5">
    <label class="not-show">&nbsp;</label>
		<input type="text" class="form-control input-sm edit" name="to_warehouse" id="to_warehouse" value="<?php echo $doc->to_warehouse_name; ?>" required disabled/>
  </div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>AGX</label>
    <select class="form-control input-sm edit" name="is_wms" id="is_wms" disabled>
			<option value="0" <?php echo is_selected('0', $doc->is_wms); ?>>No</option>
			<option value="1" <?php echo is_selected('1', $doc->is_wms); ?>>Yes</option>
		</select>
	</div>

	<div class="col-lg-10 col-md-9-harf col-sm-9 col-xs-8 padding-5">
    <label>Remark</label>
    <input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $doc->remark; ?>" disabled>
  </div>

  <?php if(($doc->status == -1 OR $doc->status == 0)) : ?>
  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-4 padding-5">
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> Edit</button>
		<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="update()"><i class="fa fa-save"></i> Update</button>
  </div>
  <?php else : ?>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-4 padding-5">
      <label>SAP</label>
      <input type="text" class="form-control input-sm" value="<?php echo $doc->inv_code; ?>" disabled>
    </div>
  <?php endif; ?>
</div>
<input type="hidden" id="transfer_code" value="<?php echo $doc->code; ?>" />
<input type="hidden" id="from_warehouse_code" value="<?php echo $doc->from_warehouse; ?>" />
<input type="hidden" id="to_warehouse_code" value="<?php echo $doc->to_warehouse; ?>" />
<input type="hidden" id="old_from_warehouse_code" value="<?php echo $doc->from_warehouse; ?>" />
<input type="hidden" id="old_to_warehouse_code" value="<?php echo $doc->to_warehouse; ?>" />
<input type="hidden" id="is_wms" value="<?php echo $doc->is_wms; ?>" />
<hr class="margin-top-15 margin-bottom-15"/>
