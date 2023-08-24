<?php $this->load->view('include/header'); ?>
<?php $manual_code = getConfig('MANUAL_DOC_CODE');  ?>
<?php if($manual_code == 1) :?>
	<input type="hidden" id="manualCode" value="<?php echo $manual_code; ?>">
	<input type="hidden" id="prefix" value="<?php echo getConfig('PREFIX_RETURN_LEND'); ?>">
	<input type="hidden" id="runNo" value="<?php echo getConfig('RUN_DIGIT_RETURN_LEND'); ?>">
<?php endif; ?>
<input type="hidden" id="required_remark" value="<?php echo $this->required_remark; ?>" />
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 hidden-xs padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 visible-xs padding-5">
    <h3 class="title-xs"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="leave()"><i class="fa fa-arrow-left"></i> Back</button>
			<?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="getValidate()"><i class="fa fa-save"></i> Save</button>
			<?php	endif; ?>
    </p>
  </div>
</div>
<hr />

<form id="addForm">
<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-3 padding-5">
		<label>Doc No.</label>
	<?php if($manual_code == 1) : ?>
		<input type="text" class="form-control input-sm" name="code" id="code" value="" />
	<?php else : ?>
		<input type="text" class="form-control input-sm" id="code" value="" disabled />
	<?php endif; ?>
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
  	<label>Date</label>
    <input type="text" class="form-control input-sm text-center" name="date_add" id="dateAdd" value="<?php echo date('d-m-Y'); ?>" readonly />
  </div>
	<div class="col-lg-3 col-md-3-harf col-sm-3-harf col-xs-6 padding-5">
		<label>Borrower(Emp)</label>
		<input type="text" class="form-control input-sm edit" name="empName" id="empName" value="" placeholder="" required/>
	</div>
	<div class="col-lg-6 col-md-5 col-sm-5 col-xs-12 padding-5">
  	<label>Remark</label>
    <input type="text" class="form-control input-sm" name="remark" id="remark" placeholder="" />
  </div>

	<div class="divider-hidden"></div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Lend code</label>
		<input type="text" class="form-control input-sm text-center" name="lend_code" id="lend_code" value="" placeholder="" required>
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">doc</label>
		<button type="button" class="btn btn-xs btn-success btn-block" id="btn-set-code" onclick="load_lend_details()">Submit</button>
		<button type="button" class="btn btn-xs btn-primary btn-block hide" id="btn-change-code" onclick="change_lend_code()">Change</button>
	</div>
	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-5 padding-5">
		<label>Bin Location</label>
		<input type="text" class="form-control input-sm" name="zone_code" id="zone_code" value="" required />
	</div>
	<div class="col-lg-6-harf col-md-4-harf col-sm-4-harf col-xs-9 padding-5">
		<label class="not-show">โซน[รับคืน]</label>
		<input type="text" class="form-control input-sm edit" name="zone" id="zone" value="" placeholder="กำหนดโซนที่จะรับสินค้าเข้า" required />
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">chang</label>
		<button type="button" class="btn btn-xs btn-primary btn-block hide" id="btn-change-zone" onclick="changeZone()">Change</button>
		<button type="button" class="btn btn-xs btn-success btn-block" id="btn-set-zone" onclick="setZone()">OK</button>
	</div>
</div>
<div class="divider"></div>
<div class="row">
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label>Qty</label>
		<input type="number" class="form-control input-sm text-center" id="qty" value="1">
	</div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>Barcode</label>
		<input type="text" class="form-control input-sm text-center" id="barcode" placeholder="Product barcode">
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">barcode</label>
		<button type="button" class="btn btn-xs btn-success btn-block" onclick="doReceive()">OK</button>
	</div>

	<div class="col-lg-5 col-md-3 col-sm-3 col-xs-4">&nbsp;</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label class="display-block not-show">add</label>
		<button type="button" class="btn btn-xs btn-primary btn-block" onclick="receiveAll()">Return all</button>
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label class="display-block not-show">clear</label>
		<button type="button" class="btn btn-xs btn-danger btn-block" onclick="clearAll()">Clear all</button>
	</div>
</div>

<hr class="margin-top-15"/>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped border-1" style="min-width:1000px;">
			<thead>
				<tr>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-150 middle">Barcode</th>
					<th class="min-width-200 middle">Items</th>
					<th class="fix-width-100 middle text-center">Lended Qty</th>
					<th class="fix-width-100 middle text-center">Returned Qty</th>
					<th class="fix-width-100 middle text-center">Outstanding</th>
					<th class="fix-width-100 middle text-center">Qty</th>
				</tr>
			</thead>
			<tbody id="result">

			</tbody>
		</table>
	</div>
</div>
<input type="hidden" name="empID" id="empID" value="">
</form>

<?php $this->load->view('cancle_modal'); ?>

<script id="template" type="text/x-handlebarsTemplate">
{{#each details}}
	{{#if nodata}}
		<tr>
			<td colspan="7" class="middle text-center">Not found</td>
		</tr>
	{{else}}
		{{#if @last}}
			<tr class="font-size-14">
				<td colspan="3" class="middle text-right">รวม</td>
				<td class="middle text-center">{{totalLend}}</td>
				<td class="middle text-center">{{totalReceived}}</td>
				<td class="middle text-center">{{totalBacklogs}}</td>
				<td class="middle text-center" id="totalQty">0</td>
			</tr>
		{{else}}
			<tr>
				<input type="hidden" class="{{barcode}}" data-no="{{no}}" value="{{no}}">
				<input type="hidden" id="lendQty-{{no}}" value="{{lendQty}}" />
				<input type="hidden" id="receivedQty-{{no}}" value="{{received}}" />
				<input type="hidden" id="backlogs-{{no}}" value="{{backlogs}}" />

				<td class="middle text-center no">{{no}}</td>
				<td class="middle">
					{{#if barcode}}
					<span class="barcode" onClick="addToBarcode('{{barcode}}')">{{barcode}}</span>
					{{/if}}
				</td>
				<td class="middle">{{itemCode}}</td>
				<td class="middle text-center">{{lendQtyLabel}}</td>
				<td class="middle text-center">{{receivedLabel}}</td>
				<td class="middle text-center">{{backlogsLabel}}</td>
				<td class="middle text-center">
				{{#if backlogs}}
					<input type="number"
					class="form-control input-sm text-right qty"
					data-product="{{itemCode}}"
					id="receiveQty-{{no}}"
					data-no="{{no}}"
					value="" />
				{{/if}}
				</td>
			</tr>
		{{/if}}
	{{/if}}
{{/each}}
</script>



<script src="<?php echo base_url(); ?>scripts/inventory/return_lend/return_lend.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/return_lend/return_lend_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/return_lend/return_lend_control.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/beep.js"></script>
<?php $this->load->view('include/footer'); ?>
