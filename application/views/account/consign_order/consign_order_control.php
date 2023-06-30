<div class="row">  
  <div class="col-lg-2-harf col-md-2 col-sm-4 col-xs-8 padding-5">
    <label>SKU code</label>
    <input type="text" class="form-control input-sm" id="item-code" />
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Price</label>
    <input type="number" class="form-control input-sm text-center" id="txt-price" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Discount</label>
    <input type="text" class="form-control input-sm text-center" id="txt-disc" />
  </div>

  <div class="col-lg-1 col-md-1 col-sm-2 col-xs-4 padding-5">
    <label>In stock</label>
    <label class="form-control input-sm text-center blue" style="margin-bottom:0px;" id="stock-qty">0</label>
  </div>
  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-4 padding-5">
    <label>Qty</label>
    <input type="number" class="form-control input-sm text-center" id="txt-qty" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2-harf col-xs-4 padding-5">
    <label>Amount</label>
    <span class="form-control input-sm text-center" id="txt-amount">0</span>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2 padding-5">
    <label class="display-block not-show">submit</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="addToDetail()">Add</button>
  </div>
  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2 padding-5">
    <label class="display-block not-show">Reset</label>
    <button type="button" class="btn btn-xs btn-default btn-block" onclick="clearFields()">Clear</button>
  </div>
</div>
<hr class="margin-top-15 margin-bottom-15">
<div class="row">
  <!--
  <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-8 padding-5">
    <input type="text" class="form-control input-sm text-center" id="pd-box" placeholder="Model" />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getProductGrid()"><i class="fa fa-tags"></i> Show</button>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">&nbsp;</div>
-->

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <input type="text" class="form-control input-sm text-center" name="ref_code" id="ref_code" value="<?php echo $doc->ref_code; ?>" disabled>
  </div>
  <div class="col-lg-1-harf col-md-2-harf col-sm-2-harf col-xs-4 padding-5">
    <button type="button" class="btn btn-xs btn-danger btn-block" onclick="clearImportDetail('<?php echo $doc->ref_code; ?>')">
      Delete imported
    </button>
  </div>

  <?php if($this->pm->can_add OR $this->pm->can_edit OR $this->pm->can_delete) : ?>
  <div class="col-lg-9 col-md-7-harf col-sm-7-harf col-xs-4 padding-5 text-right">
    <button type="button" class="btn btn-xs btn-danger btn-clock" onclick="clearAll()">Delete all entries</button>
  </div>
  <?php endif; ?>
</div>

<input type="hidden" id="product_code" />
<input type="hidden" id="count_stock" value="1" />
<hr class="margin-top-15 margin-bottom-15" />
<div class="row margin-bottom-5">
  <div class="col-sm-12 col-xs-12 padding-5">
  <?php if(getConfig('ALLOW_EDIT_PRICE')) : ?>
    <button type="button" class="btn btn-xs btn-warning" id="btn-edit-price" onclick="getEditPrice()">Edit price</button>
    <button type="button" class="btn btn-xs btn-primary hide" id="btn-update-price" onclick="updatePrice()">Update price</button>
  <?php endif; ?>
  <?php if(getConfig('ALLOW_EDIT_DISCOUNT')) : ?>
    <button type="button" class="btn btn-xs btn-warning" id="btn-edit-disc" onclick="getEditDiscount()">Edit discount</button>
    <button type="button" class="btn btn-xs btn-primary hide" id="btn-update-disc" onclick="updateDiscount()">Update discount</button>
  <?php endif; ?>
  </div>
</div>

<form id="orderForm">
<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal" style="min-width:300px; max-width:90vw;">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-center" id="modalTitle">title</h4>
        <div class="width-100" style="display:flex; justify-content:center;">
          <div style="width:180px; margin-top:10px;">
            <div class="input-group">
              <span class="input-group-addon">Doscount</span>
              <input type="text" class="form-control input-sm text-center" id="discountLabel" value="0"/>
              <span class="input-group-addon">%</span>
            </div>
          </div>
        </div>
			 </div>
			 <div class="modal-body table-responsive" id="modalBody">

       </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onClick="addToOrder()" >Add To List</button>
			 </div>
		</div>
	</div>
</div>
</form>
