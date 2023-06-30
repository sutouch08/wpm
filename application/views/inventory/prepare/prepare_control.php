

<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 padding-5">
    <label>Bin Location</label>
    <input type="text" class="form-control input-sm" id="barcode-zone" autofocus />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Qty</label>
    <input type="number" class="form-control input-sm text-center" id="qty" value="1" disabled/>
  </div>
  <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-8 padding-5">
    <label>Barcode item</label>
    <input type="text" class="form-control input-sm" id="barcode-item" disabled/>
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-xs btn-default btn-block" id="btn-submit" onclick="doPrepare()" disabled>Submit</button>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label class="display-block not-show">changeZone</label>
    <button type="button" class="btn btn-xs btn-info btn-block" id="btn-change-zone" onclick="changeZone()">Change Location</button>
  </div>

  <input type="hidden" name="zone_code" id="zone_code" />

</div>
