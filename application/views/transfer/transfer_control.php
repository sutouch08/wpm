<?php if($barcode) : ?>

  <div class="row">
  	<div class="col-sm-2 padding-5">
      	<button type="button" class="btn btn-xs btn-default btn-block" onclick="showTransferTable()">Transfered Items</button>
      </div>
  	<div class="col-sm-2 padding-5 control-btn">
      	<button type="button" class="btn btn-xs btn-danger btn-block" onclick="getMoveOut()">Move Item Out</button>
      </div>
      <div class="col-sm-2 padding-5 control-btn">
      	<button type="button" class="btn btn-xs btn-info btn-block" onclick="getMoveIn()">Move Item In</button>
      </div>
  </div>

  <hr id="barcode-hr" class="margin-top-15 margin-bottom-15 hide" />

  <div class="row">
  	<div class="col-sm-12 padding-5 moveOut-zone hide">
      <div class="row">
        <div class="col-sm-3 padding-5">
          <label>From Location</label>
          <input type="text" class="form-control input-sm" id="fromZone-barcode" placeholder="Scan a barcode to select location" />
        </div>

        <div class="col-sm-2 padding-5">
          <label class="display-block not-show">newZone</label>
          <button type="button" class="btn btn-xs btn-info btn-block" id="btn-new-zone" onclick="newFromZone()" disabled >Change Location</button>
        </div>

        <div class="col-sm-1 padding-5">
          <label>Qty</label>
          <input type="number" class="form-control input-sm text-center" id="qty-from" value="1" disabled />
        </div>

        <div class="col-sm-3 padding-5">
          <label>Barcode Item</label>
          <input type="text" class="form-control input-sm" id="barcode-item-from" placeholder="Scan a barcode to move item out" disabled />
        </div>
      </div>
    </div>

    <div class="col-sm-12 padding-5 moveIn-zone hide">
      <div class="row">
        <div class="col-sm-3 padding-5">
          <label>To Location</label>
          <input type="text" class="form-control input-sm" id="toZone-barcode" placeholder="Scan a barcode to select location" />
        </div>
        <div class="col-sm-4 padding-5">
          <label class="display-block not-show">zoneName</label>
          <input type="text" class="form-control input-sm" id="zoneName-label" disabled />
        </div>
        <div class="col-sm-2 padding-5">
          <label class="display-block not-show">newzone</label>
        	<button type="button" class="btn btn-xs btn-info btn-block" id="btn-new-to-zone" onclick="newToZone()" disabled>Change Location</button>
        </div>
        <div class="col-sm-1 padding-5">
          <label>Qty</label>
          <input type="number" class="form-control input-sm text-center" id="qty-to" value="1" disabled />
        </div>
        <div class="col-sm-3 padding-5">
          <label>Barcode Item</label>
          <input type="text" class="form-control input-sm" id="barcode-item-to" placeholder="Scan a barcode to move item in" disabled />
        </div>
      </div>
    </div>
  </div>

<?php else : ?>

<div class="row">
  <div class="col-sm-4 padding-5">
    <label>From Location</label>
    <input type="text" class="form-control input-sm" id="from-zone" placeholder="Specify location" autofocus />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getProductInZone()">Get Items</button>
  </div>

  <div class="col-sm-4 padding-5">
    <label>To Location</label>
    <input type="text" class="form-control input-sm" id="to-zone" placeholder="Specify location" />
  </div>

  <div class="col-sm-2 col-sm-offset-1">
    <label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-xs btn-default btn-block" onclick="showTransferTable()">Transfered Items</button>
  </div>


</div>
<?php endif; ?>
<input type="hidden" name="from_zone_code" id="from_zone_code" value="" />
<input type="hidden" name="to_zone_code" id="to_zone_code" value="" />
<hr class="margin-top-15 margin-bottom-15" />
