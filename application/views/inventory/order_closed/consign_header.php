<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled readonly />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Customer</label>
    <input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
  </div>

  <div class="col-lg-6-harf col-md-6 col-sm-6-harf col-xs-12 padding-5">
    <label>Customer Name</label>
    <input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
  </div>

  <div class="col-lg-1 col-md-1 col-sm-2 col-xs-4 padding-5">
    <label>GP</label>
    <input type="text" class="form-control input-sm text-center edit" name="gp" id="gp" value="<?php echo $order->gp; ?>" disabled />
  </div>

  <div class="col-lg-1 col-md-1 col-sm-2 col-xs-4 padding-5">
    <label>Currency</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->DocCur; ?>" disabled/>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-2 col-xs-4 padding-5">
    <label>Rate</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->DocRate; ?>" disabled/>
  </div>

  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 padding-5">
    <label>From Warehouse</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->warehouse_name; ?>" disabled />
  </div>

  <div class="col-lg-2 col-md-2-harf col-sm-3-harf col-xs-6 padding-5">
    <label>To location</label>
    <input type="text" class="form-control input-sm edit" name="zone_code" id="zone_code" value="<?php echo $order->zone_code; ?>" readonly disabled />
  </div>
  <div class="col-lg-5 col-md-4-harf col-sm-8-harf col-xs-12 padding-5">
    <label class="not-show">bin name</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->zone_name; ?>" disabled />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 padding-5">
    <label>User</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->user; ?>" disabled />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 padding-5">
    <label>Update by</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->update_user; ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label class="font-size-2 blod">SAP No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->inv_code; ?>" disabled />
  </div>
</div>
