<?php $this->load->view('include/header'); ?>
<?php if($document->status == 0) : ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 hidden-xs padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?> </h3>
	</div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="leave()"><i class="fa fa-arrow-left"></i> Back</button>
    <?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="checkLimit()"><i class="fa fa-save"></i> Save</button>
    <?php	endif; ?>
    </p>
  </div>
</div>
<hr />
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
  	<label>Document No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $document->code; ?>" disabled />
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center header-box" name="date_add" id="dateAdd" value="<?php echo thai_date($document->date_add); ?>" disabled />
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5 hide">
		<label>ช่องทางการรับ</label>
		<select class="form-control input-sm header-box" name="is_wms" id="is_wms" disabled>
			<option value="1" <?php echo is_selected('1', $document->is_wms); ?>>WMS</option>
			<option value="0" <?php echo is_selected('0', $document->is_wms); ?>>Warrix</option>
		</select>
	</div>
	<div class="col-lg-6-harf col-md-6 col-sm-4-harf col-xs-9 padding-5">
		<label>Remark</label>
		<input type="text" class="form-control input-sm header-box" name="remark" id="remark" value="<?php echo $document->remark; ?>" disabled />
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
<?php if($this->pm->can_edit && $document->status == 0) : ?>
		<label class="display-block not-show">edit</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="editHeader()">
			<i class="fa fa-pencil"></i> Edit
		</button>
		<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="updateHeader()">
			<i class="fa fa-save"></i> Update
		</button>
<?php endif; ?>
	</div>

	<input type="hidden" id="required-remark" value="<?php echo $this->required_remark ? 1 : 0; ?>" />

</div>
<hr class="margin-top-10 margin-bottom-10"/>
<form id="receiveForm" method="post" action="<?php echo $this->home; ?>/save">
<div class="row">
	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
  	<label>Transform No</label>
    <input type="text" class="form-control input-sm text-center" name="order_code" id="order_code" placeholder="Transform No" />
  </div>
	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">clear</label>
		<button type="button" class="btn btn-xs btn-info btn-block hide" id="btn-change-po" onclick="changePo()">Change</button>
		<button type="button" class="btn btn-xs btn-primary btn-block" id="btn-get-po" onclick="getData()">Confirm</button>
	</div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-5 padding-5">
  	<label>Invoice No</label>
    <input type="text" class="form-control input-sm text-center" name="invoice" id="invoice" placeholder="Invoice no" />
  </div>
	<div class="col-lg-2 col-md-2-harf col-sm-2 col-xs-6 padding-5">
  	<label>Bin location</label>
    <input type="text" class="form-control input-sm" name="zone_code" id="zone_code" placeholder="Bin code" value="<?php echo $zone_code; ?>"/>
  </div>
  <div class="col-lg-6 col-md-5-harf col-sm-4-harf col-xs-6 padding-5">
  	<label class="not-show">zone</label>
    <input type="text" class="form-control input-sm zone" name="zoneName" id="zoneName" placeholder="Bin location name"  value="<?php echo $zone_name; ?>"/>
  </div>
</div>
<hr class="margin-top-15"/>
<div class="row">
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label>Qty.</label>
    <input type="text" class="form-control input-sm text-center" id="qty" value="1.00" />
  </div>
  <div class="col-lg-3 col-md-2-harf col-sm-3 col-xs-6 padding-5">
  	<label>Barcode</label>
    <input type="text" class="form-control input-sm text-center" id="barcode" placeholder="Scan barcode to receive product" autocomplete="off"  />
  </div>
  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
  	<label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="checkBarcode()"><i class="fa fa-check"></i> ตกลง</button>
  </div>
  <input type="hidden" name="receive_code" id="receive_code" value="<?php echo $document->code; ?>" />
  <input type="hidden" name="approver" id="approver" value="" />
</div>
<hr class="margin-top-15 margin-bottom-15"/>


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
  	<table class="table table-striped table-bordered" style="min-width:1300px;">
    	<thead>
      	<tr class="font-size-12">
        	<th class="fix-width-40 text-center">#</th>
          <th class="fix-width-120 text-center hide">Barcode</th>
          <th class="fix-width-200 text-center">Item code</th>
          <th class="min-width-250" style="max-width:350px;">Description</th>
					<th class="fix-width-100 text-right">Cost(Avg)</th>
          <th class="fix-width-100 text-center">Transform</th>
					<th class="fix-width-100 text-center">Received</th>
					<th class="fix-width-100 text-center">Waiting Acceptance</th>
          <th class="fix-width-100 text-center">Outstanding</th>
          <th class="fix-width-100 text-center">Qty</th>
					<th class="fix-width-100 text-center">Amount</th>
        </tr>
      </thead>
      <tbody id="receiveTable">

      </tbody>
    </table>
  </div>
</div>
</form>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog input-xlarge">
    <div class="modal-content">
      <div class="modal-header">
      	<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
		    <h4 class='modal-title-site text-center' > Authorized person to accept excess product </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
          	<input type="password" class="form-control input-sm text-center" id="sKey" />
            <span class="help-block red text-center" id="approvError">&nbsp;</span>
          </div>
          <div class="col-sm-12">
            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="doApprove()">Approve</button>
          </div>
        </div>
    	 </div>
      </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>scripts/validate_credentials.js"></script>
<script id="template" type="text/x-handlebarsTemplate">
{{#each this}}
	{{#if @last}}
        <tr>
            <td colspan="4" class="middle text-right"><strong>รวม</strong></td>
            <td class="middle text-center">{{qty}}</td>
						<td class="middle text-center">{{received}}</td>
						<td class="middle text-center">{{uncomplete}}</td>
            <td class="middle text-center">{{backlog}}</td>
            <td class="middle text-center"><span id="total-receive">0</span></td>
						<td class="middle text-center"><span id="total-amount">0.00</span></td>
        </tr>
    {{else}}
        <tr class="font-size-12">
            <td class="middle text-center">{{no}}</td>
            <td class="middle">{{pdCode}}</td>
            <td class="middle">{{pdName}}</td>
						<td class="middle text-right">
							<input type="number" class="form-control input-sm text-right input-price" id="price_{{no}}" value="{{price}}" {{disabled}} />
						</td>
            <td class="middle text-center" id="qty_{{no}}">{{qty}}</td>
						<td class="middle text-center">{{received}}</td>
						<td class="middle text-center">{{uncomplete}}</td>
            <td class="middle text-center">{{backlog}}</td>
            <td class="middle text-center">
                <input type="text" class="form-control input-sm text-center receive-box pdCode" name="receive[{{no}}]" id="receive_{{no}}" data-no="{{no}}" />
            </td>
						<td class="middle text-right line-amount" id="line-amount-{{no}}">0.00</td>
						<input type="hidden" id="product_{{no}}" value="{{pdCode}}"/>
						<input type="hidden" id="product_name_{{no}}" value="{{pdName}}" />
						<input type="hidden" id="limit_{{no}}" value="{{limit}}"/>
						{{#if barcode}}
						<input type="hidden" id="{{barcode}}" value="{{no}}" />
						{{/if}}
						<input type="hidden" id="backlog_{{no}}" value="{{backlog}}" />
        </tr>
    {{/if}}
{{/each}}
</script>

<?php else : ?>
  <?php redirect($this->home.'/view_detail/'.$document->code); ?>
<?php endif; ?>
<script src="<?php echo base_url(); ?>scripts/inventory/receive_transform/receive_transform.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/receive_transform/receive_transform_add.js?v=<?php echo date('YmdH'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
