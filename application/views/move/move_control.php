<?php if($barcode) : ?>

  <div class="row">
  	<div class="col-sm-2">
      	<button type="button" class="btn btn-xs btn-default btn-block" onclick="showMoveTable()">Show Moved Items</button>
      </div>
  	<div class="col-sm-2 control-btn">
      	<button type="button" class="btn btn-xs btn-danger btn-block" onclick="getMoveOut()">Move Items Out</button>
      </div>
      <div class="col-sm-2 control-btn">
      	<button type="button" class="btn btn-xs btn-info btn-block" onclick="getMoveIn()">Move Items In</button>
      </div>
  </div>

  <hr id="barcode-hr" class="margin-top-15 margin-bottom-15 hide" />

  <div class="row">
  	<div class="col-sm-12 moveOut-zone hide">
      <div class="row">
        <div class="col-sm-3 padding-5 first">
          <label>From location</label>
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
          <label>Barcode item</label>
          <input type="text" class="form-control input-sm" id="barcode-item-from" placeholder="Scan a barcode to move items out." disabled />
        </div>
      </div>
    </div>

    <div class="col-sm-12 moveIn-zone hide">
      <div class="row">
        <div class="col-sm-3 padding-5 first">
          <label>To Location</label>
          <input type="text" class="form-control input-sm" id="toZone-barcode" placeholder="Scan a barcode to select location" />
        </div>
        <div class="col-sm-4 padding-5 hide">
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
        <div class="col-sm-3 padding-5 last">
          <label>Barcode item</label>
          <input type="text" class="form-control input-sm" id="barcode-item-to" placeholder="Scan a barcode to move items in." disabled />
        </div>
      </div>
    </div>
  </div>

<?php else : ?>

<div class="row">
  <div class="col-sm-4 padding-5 first">
    <label>From location</label>
    <input type="text" class="form-control input-sm" id="from-zone" placeholder="" autofocus />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getProductInZone()">Get Items</button>
  </div>
  <div class="col-sm-4 padding-5">
    <label>To location</label>
    <input type="text" class="form-control input-sm" id="to-zone" placeholder="" />
  </div>

  <div class="col-sm-3 text-right">
    <label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-xs btn-default" onclick="showMoveTable()">Show Moved Items</button>
  </div>


</div>
<?php endif; ?>
<input type="hidden" name="from_zone_code" id="from_zone_code" value="" />
<input type="hidden" name="to_zone_code" id="to_zone_code" value="" />
<hr class="margin-top-15 margin-bottom-15" />
