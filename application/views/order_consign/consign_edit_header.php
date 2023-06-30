<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    	<label>Document No</label>
        <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    	<label>Date</label>
			<input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled />
    </div>
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    	<label>Customer</label>
			<input type="text" class="form-control input-sm text-center edit" id="customer-code" value="<?php echo $order->customer_code; ?>" disabled />
    </div>
    <div class="col-lg-6 col-md-5 col-sm-6-harf col-xs-8 padding-5">
    	<label class="not-show">ลูกค้า[ในระบบ]</label>
			<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
    </div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label>GP</label>
			<div class="input-group width-100">
				<input type="number" class="form-control input-sm text-center edit" name="gp" id="gp" value="<?php echo $order->gp; ?>" disabled />
				<span class="input-group-addon">%</span>
			</div>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
	    <label>Currency</label>
			<select class="form-control input-sm edit" name="doc_currency" id="doc_currency" onchange="updateDocRate()" disabled>
				<?php echo select_currency($order->DocCur); ?>
			</select>
	  </div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
	    <label>Rate</label>
			<input type="number" class="form-control input-sm text-center edit" name="doc_rate" id="doc_rate" value="<?php echo $order->DocRate; ?>" disabled/>
	  </div>

		<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 padding-5">
			<label>From Warehouse</label>
			<select class="form-control input-sm edit" name="warehouse" id="warehouse" required disabled>
				<option value="">Please select</option>
				<?php echo select_sell_warehouse($order->warehouse_code); ?>
			</select>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-3-harf col-xs-4 padding-5">
			<label>To location</label>
			<input type="text" class="form-control input-sm edit" name="zone_code" id="zone_code" value="<?php echo $order->zone_code; ?>" readonly disabled />
		</div>
		<div class="col-lg-5 col-md-4 col-sm-4-harf col-xs-8 padding-5">
	    <label class="not-show">โซน[ฝากขาย]</label>
			<input type="text" class="form-control input-sm edit" name="zone" id="zone" value="<?php echo $order->zone_name; ?>" required disabled/>
	  </div>


		<?php if(empty($approve_view) && ($this->pm->can_add OR $this->pm->can_edit)): ?>
		<div class="col-lg-11 col-md-10-harf col-sm-6 col-xs-9 padding-5">
		 	<label>Remark</label>
		  <input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $order->remark; ?>" disabled />
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
			<label class="display-block not-show">Edit</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> Edit</i></button>
			<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="validUpdate()"><i class="fa fa-save"></i> Save</i></button>
		</div>
		<?php else : ?>
			<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 padding-5">
			 	<label>Remark</label>
			  <input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $order->remark; ?>" disabled />
			</div>
		<?php endif; ?>

    <input type="hidden" name="order_code" id="order_code" value="<?php echo $order->code; ?>" />
    <input type="hidden" name="customerCode" id="customerCode" value="<?php echo $order->customer_code; ?>" />
		<input type="hidden" id="is_approved" value="<?php echo $order->is_approved; ?>" />
		<input type="hidden" name="is_wms" id="is_wms" value="<?php echo $order->is_wms; ?>" />
		<input type="hidden" name="address_id" id="address_id" value="<?php echo $order->id_address; //--- id_address ใช้แล้วใน online modal?>" />
</div>
<hr class="margin-bottom-15"/>
