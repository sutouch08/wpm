<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/xlsx.full.min.js"></script>
<?php if($document->status == 0) : ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 hidden-xs padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?> </h3>
	</div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="pull-right top-p text-right">
			<button type="button" class="btn btn-xs btn-warning top-btn" onclick="leave()"><i class="fa fa-arrow-left"></i> Back</button>
			<?php if($this->pm->can_edit && $document->status == 0) : ?>
        <button type="button" class="btn btn-xs btn-danger top-btn" onclick="goDelete('<?php echo $document->code; ?>')"><i class="fa fa-exclamation-triangle"></i> Cancel</button>
      <?php endif; ?>
			<button type="button" class="btn btn-xs btn-purple top-btn" onclick="getSample()"><i class="fa fa-download"></i> Example file</button>
    <?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-xs btn-primary top-btn" onclick="getUploadFile()"><i class="fa fa-upload"></i> Import</button>
			<button type="button" class="btn btn-xs btn-success top-btn" onclick="checkLimit()"><i class="fa fa-save"></i> Save</button>
    <?php	endif; ?>

    </p>
  </div>
</div>
<hr />

<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
  	<label>Doc. No.</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $document->code; ?>" disabled />
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center header-box" name="date_add" id="dateAdd" value="<?php echo thai_date($document->date_add); ?>" disabled />
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5 hide">
		<label>ช่องทางการรับ</label>
		<select class="form-control input-sm header-box" name="is_wms" id="is_wms" disabled>
			<option value="1" <?php echo is_selected('1', $document->is_wms); ?>>WMS</option>
			<option value="0" <?php echo is_selected('0', $document->is_wms); ?>>Warrix</option>
		</select>
	</div>
	<div class="col-lg-7-harf col-md-7-harf col-sm-7-harf col-xs-9 padding-5">
		<label>Remark</label>
		<input type="text" class="form-control input-sm header-box" name="remark" id="remark" value="<?php echo $document->remark; ?>" disabled />
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
<?php if($this->pm->can_edit && $document->status == 0) : ?>
		<label class="display-block not-show">edit</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="editHeader()">Edit</button>
		<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="updateHeader()">Update</button>
<?php endif; ?>
	</div>

</div>
<hr class="margin-top-10 margin-bottom-10"/>
<form id="receiveForm" method="post" action="<?php echo $this->home; ?>/save">
<div class="row">
	<div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-4 padding-5">
		<label>Vendor</label>
		<input type="text" class="form-control input-sm" name="vendor_code" id="vendor_code" placeholder="Vendor code" />
	</div>
	<div class="col-lg-7 col-md-6 col-sm-5-harf col-xs-8 padding-5">
	 	<label class="not-show">vendor</label>
	  <input type="text" class="form-control input-sm" name="vendorName" id="vendorName" placeholder="Vendor name" />
	</div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 padding-5 hide">
	 	<label>Request No.</label>
	  <input type="text" class="form-control input-sm text-center" name="requestCode" id="requestCode" placeholder="" />
	</div>
	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5 hide">
		<label class="display-block not-show">clear</label>
		<button type="button" class="btn btn-xs btn-info btn-block hide" id="btn-change-request" onclick="changeRequestPo()" >Change</button>
		<button type="button" class="btn btn-xs btn-primary btn-block" id="btn-get-request" onclick="getRequestData()" >Confirm</button>
	</div>

	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 padding-5">
    	<label>Po No.</label>
        <input type="text" class="form-control input-sm text-center"
				name="poCode" id="poCode" <?php echo ($is_strict) ? 'disabled' : ''; ?>
				placeholder="PO Number" />
    </div>

		<?php if(! $is_strict) : ?>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
			<label class="display-block not-show">clear</label>
			<button type="button" class="btn btn-xs btn-info btn-block hide" id="btn-change-po" onclick="changePo()">Change</button>
			<button type="button" class="btn btn-xs btn-primary btn-block" id="btn-get-po" onclick="getData()">Confirm</button>
		</div>
		<?php else : ?>
			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-3 padding-5">
				<label class="display-block not-show">clear</label>
				<button type="button" class="btn btn-xs btn-primary btn-block" disabled>Confirm</button>
			</div>
		<?php endif; ?>

    <div class="col-lg-2-harf col-md-2-harf col-sm-3 col-xs-5 padding-5">
    	<label>Invoice No.</label>
        <input type="text" class="form-control input-sm text-center" name="invoice" id="invoice" placeholder="Vendor invoice" />
    </div>
		<div class="col-lg-2-harf col-md-2-harf col-sm-3 col-xs-6 padding-5">
			<label>Bin location</label>
      <input type="text" class="form-control input-sm" name="zone_code" id="zone_code" placeholder="Bin location code" value="<?php echo $zone_code; ?>" />
		</div>
    <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 padding-5">
    	<label class="not-show">zone</label>
      <input type="text" class="form-control input-sm zone" name="zoneName" id="zoneName" placeholder="Bin location description" value="<?php echo $zone_name; ?>" />
    </div>

</div>
<hr class="margin-top-15 padding-5"/>
<div class="row">
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>Currency</label>
		<select class="form-control input-sm width-100" id="DocCur" onchange="changeRate()" disabled>
			<?php echo select_currency($this->dfCurrency); ?>
		</select>
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label>Rate</label>
		<input type="number" class="form-control input-sm text-center" id="DocRate" value="1.00" disabled/>
	</div>
	<div class="col-lg-3-harf col-md-3-harf col-sm-3-harf col-xs-12 hidden-xs text-cetner">&nbsp;</div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 hidden-xs padding-5">
    	<label>Qty.</label>
        <input type="number" class="form-control input-sm text-center" id="qty" value="1.00" />
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 hidden-xs padding-5">
    	<label>Barcode</label>
      <input type="text" class="form-control input-sm text-center" id="barcode" placeholder="Scan barcode product" autocomplete="off"  />
  </div>
  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3 hidden-xs padding-5">
    	<label class="display-block not-show">ok</label>
        <button type="button" class="btn btn-xs btn-primary btn-block" onclick="checkBarcode()"><i class="fa fa-check"></i> Submit</button>
  </div>
    <input type="hidden" name="receive_code" id="receive_code" value="<?php echo $document->code; ?>" />
    <input type="hidden" name="approver" id="approver" value="" />
		<input type="hidden" id="allow_over_po" value="<?php echo $allow_over_po; ?>">
		<input type="hidden" id="is_strict_request" value="<?php echo $is_strict; ?>" />
		<input type="hidden" id="dfCurrency" value="<?php echo $this->dfCurrency; ?>" />

</div>
<hr class="margin-top-15"/>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-right margin-bottom-5">
		<button type="button" class="btn btn-xs btn-primary" onclick="receiveAll()">Receive all</button>
		<button type="button" class="btn btn-xs btn-warning" onclick="clearAll()">Clear all</button>
	</div>
</div>
<hr class="padding-5"/>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive" id="receiveTable">

  </div>
</div>
</form>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog input-xlarge">
    <div class="modal-content">
      <div class="modal-header">
      	<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
		    <h4 class='modal-title-site text-center' >Authorized person to accept excess product</h4>
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

<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="width:500px;">
	 <div class="modal-content">
			 <div class="modal-header">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			 <h4 class="modal-title">Import File</h4>
			</div>
			<div class="modal-body">
				<form id="upload-form" name="upload-form" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-sm-9">
						<button type="button" class="btn btn-sm btn-primary btn-block" id="show-file-name" onclick="getFile()">Please select a file.</button>
					</div>

					<div class="col-sm-3">
						<button type="button" class="btn btn-sm btn-info" onclick="uploadfile()"><i class="fa fa-cloud-upload"></i> Import</button>
					</div>
				</div>
				<input type="file" class="hide" name="uploadFile" id="uploadFile" accept=".xlsx" />
				</form>
			 </div>
			<div class="modal-footer">

			</div>
	 </div>
 </div>
</div>


<?php $this->load->view('cancle_modal'); ?>

<script src="<?php echo base_url(); ?>scripts/validate_credentials.js"></script>
<script id="template" type="text/x-handlebarsTemplate">
<table class="table table-striped table-bordered" style="min-width:1110px;">
	<thead>
		<tr class="font-size-12">
			<th class="fix-width-40 text-center">#</th>
			<th class="fix-width-200">Item code</th>
			<th class="" style="min-width:250px; max-width:350px;">Description</th>
			<th class="fix-width-100 text-center">Price (PO)</th>
			<th class="fix-width-100 text-center">Purchase Qty.</th>
			<th class="fix-width-100 text-center">Outstanding Qty.</th>
			<th class="fix-width-100 text-center" style="width:100px !important;">Qty.</th>
			<th calss="fix-width-120 text-center" style="width:120px !important;">Amount</th>
		</tr>
	</thead>
	<tbody>
	{{#each this}}
	{{#if @last}}
		<tr>
			<td colspan="4" class="middle text-right"><strong>Total</strong></td>
			<td class="middle text-center">{{qty}}</td>
			<td class="middle text-center">{{backlog}}</td>
			<td class="middle text-center"><span id="total-receive"></span></td>
			<td class="middle text-right"><span id="total-amount"></span></td>
		</tr>
	{{else}}
		<tr class="font-size-12">
			<td class="middle text-center no">{{no}}</td>
			<td class="middle">{{pdCode}}</td>
			<td class="middle">{{pdName}}</td>
			<td class="middle text-right">{{price_label}} <span style="font-size:10px;">{{currency}}</span></td>
			<td class="middle text-center" id="qty_{{uid}}">{{qty_label}}</td>
			<td class="middle text-center">{{backlog_label}}</td>
			<td class="middle text-center">
				<input type="number" class="form-control input-sm text-center receive-box pdCode" name="receive[{{uid}}]" id="receive_{{uid}}" data-uid="{{uid}}" value="" />
			</td>
			<td class="middle text-right" id="line_total_{{uid}}">0.00</td>
				<input type="hidden" id="limit_{{uid}}" value="{{limit}}"/>
				<input type="hidden" id="backlog_{{uid}}" value="{{backlog}}" />
				{{#if barcode}}
					<input type="hidden" class="{{barcode}}" id="bc-{{uid}}" data-limit="{{limit}}" value="{{uid}}" />
				{{/if}}
				{{#if isOpen}}
					<input type="hidden" name="items[{{uid}}]" id="item_{{uid}}" value="{{pdCode}}" />
					<input type="hidden" name="prices[{{uid}}]" id="price_{{uid}}" value="{{price}}" />
					<input type="hidden" name="currency[{{uid}}]" id="currency_{{uid}}" value="{{currency}}" />
					<input type="hidden" name="rate[{{uid}}]" id="rate_{{uid}}" value="{{Rate}}" />
					<input type="hidden" name="vatGroup[{{uid}}]" id="vatGroup_{{uid}}" value="{{vatGroup}}">
					<input type="hidden" name="vatRate[{{uid}}]" id="vatRate_{{uid}}" value="{{vatRate}}">
					<input type="hidden" name="docEntry-{{uid}}" id="docEntry_{{uid}}" value="{{docEntry}}" />
					<input type="hidden" name="lineNum-{{uid}}" id="lineNum_{{uid}}" value="{{lineNum}}" />
				{{/if}}
		</tr>
	{{/if}}
	{{/each}}
	</tbody>
</table>
</script>

<script id="request-template" type="text/x-handlebarsTemplate">
<table class="table table-striped table-bordered" style="min-width:1000px;">
	<thead>
		<tr class="font-size-12">
			<th class="fix-width-40 text-center">#</th>
			<th class="fix-width-120 text-center">Barcode</th>
			<th class="fix-width-150">Item code</th>
			<th class="min-width-200">Description</th>
			<th class="fix-width-100 text-center">Purchase Qty.</th>
			<th class="fix-width-100 text-center">Approved Qty.</th>
			<th class="fix-width-100 text-center">Outstanding.</th>
			<th class="fix-width-100 text-center">Qty.</th>
		</tr>
	</thead>
	<tbody id="receiveTable">
	{{#each this}}
	{{#if @last}}
		<tr>
			<td colspan="4" class="middle text-right"><strong>Total</strong></td>
			<td class="middle text-center">{{totalQty}}</td>
			<td class="middle text-center">{{totalRequest}}</td>
			<td class="middle text-center">{{totalBacklog}}</td>
			<td class="middle text-center"><span id="total-receive"></span></td>
		</tr>
	{{else}}
		<tr class="font-size-12">
			<td class="middle text-center no">{{ no }}</td>
			<td class="middle barcode" id="barcode_{{uid}}">{{barcode}}</td>
			<td class="middle">{{pdCode}}</td>
			<td class="middle">{{pdName}}</td>
			<td class="middle text-center" id="qty_{{uid}}">{{qty_label}}</td>
			<td class="middle text-center">{{request_qty_label}}</td>
			<td class="middle text-center">{{backlog_label}}</td>
			<td class="middle text-center">
				<input type="hidden" id="backlog_{{uid}}" value="{{backlog}}" />
				<input type="hidden" id="limit_{{uid}}" value="{{limit}}"/>
				{{#if barcode}}
					<input type="hidden" class="{{barcode}}" id="bc-{{uid}}" data-limit="{{limit}}" value="{{uid}}" />
				{{/if}}

				{{#if isOpen}}
					<input type="number" class="form-control input-sm text-center receive-box pdCode" name="receive[{{uid}}]" id="receive_{{uid}}" data-uid="{{uid}}" value="{{receive_qty}}" />
					<input type="hidden" name="items[{{uid}}]" id="item_{{uid}}" value="{{pdCode}}" />
					<input type="hidden" name="prices[{{uid}}]" id="price_{{uid}}" value="{{price}}" />
					<input type="hidden" name="currency[{{uid}}]" id="currency_{{uid}}" value="{{currency}}" />
					<input type="hidden" name="rate[{{uid}}]" id="rate_{{uid}}" value="{{Rate}}" />
					<input type="hidden" name="vatGroup[{{uid}}]" id="vatGroup_{{uid}}" value="{{vatGroup}}">
					<input type="hidden" name="vatRate[{{uid}}]" id="vatRate_{{uid}}" value="{{vatRate}}">
					<input type="hidden" name="docEntry-{{uid}}" id="docEntry_{{uid}}" value="{{docEntry}}" />
					<input type="hidden" name="lineNum-{{uid}}" id="lineNum_{{uid}}" value="{{lineNum}}" />
				{{/if}}
			</td>
		</tr>
	{{/if}}
	{{/each}}

	</tbody>
</table>
</script>


<?php else : ?>
  <?php redirect($this->home.'/view_detail/'.$document->code); ?>
<?php endif; ?>
<script src="<?php echo base_url(); ?>scripts/inventory/receive_po/receive_po.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/receive_po/receive_po_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/beep.js"></script>

<?php $this->load->view('include/footer'); ?>
