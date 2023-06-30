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

  <div class="col-lg-5-harf col-md-6-harf col-sm-6-harf col-xs-12 padding-5">
    <label>Customer Name</label>
    <input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Cust ref</label>
    <input type="text" class="form-control input-sm edit" id="customer_ref" name="customer_ref" value="<?php echo str_replace('"', '&quot;',$order->customer_ref); ?>" disabled />
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label>Currency</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->DocCur; ?>" disabled/>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label>Rate</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->DocRate; ?>" disabled/>
  </div>

  <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
    <label>Channels</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->channels_name; ?>" disabled/>
  </div>
  <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
    <label>Payments</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->payment_name; ?>" disabled />
  </div>
  <div class="col-lg-2 col-md-3 col-sm-2 col-xs-6 padding-5">
    <label>Order ref</label>
    <input type="text" class="form-control input-sm text-center edit" name="reference" id="reference" value="<?php echo $order->reference; ?>" disabled />
  </div>

  <div class="col-lg-4 col-md-5-harf col-sm-5-harf col-xs-6 padding-5">
    <label>Warehouse</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->warehouse_name; ?>" disabled />
  </div>


  <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 padding-5">
    <label>User</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->user; ?>" disabled />
  </div>

  <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 padding-5">
    <label>Update by</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->update_user; ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label class="font-size-2 blod">SAP No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->inv_code; ?>" disabled />
  </div>
</div>
