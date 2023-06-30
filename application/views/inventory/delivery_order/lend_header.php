<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled readonly />
  </div>

  <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Owner</label>
    <input type="text" class="form-control input-sm edit" value="<?php echo $order->empName; ?>" disabled />
  </div>
  <div class="col-lg-2 col-md-3 col-sm-2-harf col-xs-6 padding-5">
    <label>Reference</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->user_ref; ?>" disabled />
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label>Currency</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $order->DocCur; ?>" disabled/>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label>Rate</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->DocRate; ?>" disabled/>
  </div>

  <div class="col-lg-3 col-md-3-harf col-sm-3-harf col-xs-6 padding-5">
    <label>From Warehouse</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->warehouse_name; ?>" disabled />
  </div>

  <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
    <label>To location</label>
    <input type="text" class="form-control input-sm edit" name="zone_code" id="zone_code" value="<?php echo $order->zone_code; ?>" readonly disabled />
  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 padding-5">
    <label class="not-show">bin name</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->zone_name; ?>" disabled />
  </div>


  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>User</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->user; ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Update by</label>
    <input type="text" class="form-control input-sm" value="<?php echo $order->update_user; ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Delivery date</label>
    <input type="text" class="form-control input-sm text-center" id="ship-date" value="<?php echo thai_date($order->shipped_date, FALSE); ?>" disabled />
  </div>
  <?php if($order->state == 7) : ?>
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block not-show">x</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit-ship-date" onclick="activeShipDate()">Change Date</button>
    <button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update-ship-date" onclick="updateShipDate()">Update</button>
  </div>
<?php endif; ?>
</div>
