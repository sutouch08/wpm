<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Document No.</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>Date</label>
		<input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled readonly />
	</div>
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Customer</label>
		<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
	</div>
	<div class="col-lg-5-harf col-md-6-harf col-sm-6-harf col-xs-8 padding-5">
		<label class="not-show">Customer</label>
		<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
	</div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Customer ref</label>
		<input type="text" class="form-control input-sm edit" id="customer_ref" name="customer_ref" value="<?php echo str_replace('"', '&quot;',$order->customer_ref); ?>" disabled />
	</div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>Currency</label>
		<select class="form-control input-sm edit" name="doc_currency" id="doc_currency" onchange="updateDocRate()" disabled>
			<?php echo select_currency($order->DocCur); ?>
		</select>
		<input type="hidden" id="current-currency" value="<?php echo $order->DocCur; ?>">
		<input type="hidden" id="current-rate" value="<?php echo $order->DocRate; ?>">
	</div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>Rate</label>
		<input type="number" class="form-control input-sm text-center edit" name="doc_rate" id="doc_rate" value="<?php echo $order->DocRate; ?>" disabled/>
	</div>


	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 padding-5">
		<label>Channels</label>
		<select class="form-control input-sm edit" name="channels" id="channels" required disabled>
			<option value="">Please select</option>
			<?php echo select_channels($order->channels_code); ?>
		</select>
	</div>

	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 padding-5">
		<label>Payments</label>
		<select class="form-control input-sm edit" name="payment" id="payment" required disabled>
			<option value="">Please select</option>
			<?php echo select_payment_method($order->payment_code); ?>
		</select>
	</div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Order ref.</label>
		<input type="text" class="form-control input-sm text-center edit" name="reference" id="reference" value="<?php echo $order->reference; ?>" disabled />
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 padding-5">
		<label>Warehouse</label>
		<select class="form-control input-sm edit" name="warehouse" id="warehouse" disabled>
			<option value="">Please select</option>
			<?php echo select_warehouse($order->warehouse_code); ?>
		</select>
	</div>

	<div class="col-lg-11 col-md-7-harf col-sm-7-harf col-xs-9 padding-5">
		<label>Remark</label>
		<input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $order->remark; ?>" disabled />
	</div>

	<?php if($order->state < 4 && $order->is_expired == 0 && ($this->pm->can_add OR $this->pm->can_edit)): ?>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label class="display-block not-show">Edit</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> Edit</i></button>
			<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="validUpdate()"><i class="fa fa-save"></i> Save</i></button>
		</div>
	<?php else : ?>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label>SAP No.</label>
			<input type="text" class="form-control input-sm edit" value="<?php echo $order->inv_code; ?>" disabled />
		</div>
	<?php endif; ?>
    <input type="hidden" name="customerCode" id="customerCode" value="<?php echo $order->customer_code; ?>" />
		<input type="hidden" name="order_code" id="order_code" value="<?php echo $order->code; ?>" />
		<input type="hidden" name="is_wms" id="is_wms" value="<?php echo $order->is_wms; ?>" />
		<input type="hidden" name="address_id" id="address_id" value="<?php echo $order->id_address; //--- id_address ใช้แล้วใน online modal?>" />
</div>
<hr class="margin-bottom-15 padding-5"/>
