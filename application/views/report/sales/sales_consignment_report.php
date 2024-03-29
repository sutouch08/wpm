<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title">
      <i class="fa fa-bar-chart"></i>
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
    <h4 class="title-xs">
      <i class="fa fa-bar-chart"></i>
      <?php echo $this->title; ?>
    </h4>
  </div>
	<div class="col-sm-6">
		<p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> Report</button>
			<button type="button" class="btn btn-xs btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Export</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="hidden-print"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
	<div class="row">
	  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5 ">
	    <label class="display-block">Products</label>
	    <div class="btn-group width-100" style="height:30px;">
	      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pd-all" onclick="toggleAllProduct(1)">All</button>
	      <button type="button" class="btn btn-sm width-50" id="btn-pd-range" onclick="toggleAllProduct(0)">Select</button>
	    </div>
	  </div>
	  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 padding-5">
	    <label class="">From</label>
	    <input type="text" class="form-control input-sm text-center" id="pdFrom" name="pdFrom" disabled>
	  </div>
	  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 padding-5">
	    <label class="">To</label>
	    <input type="text" class="form-control input-sm text-center" id="pdTo" name="pdTo" disabled>
	  </div>

    <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5 ">
	    <label class="display-block">Customers</label>
	    <div class="btn-group width-100" style="height:30px;">
	      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-cus-all" onclick="toggleAllCustomer(1)">All</button>
	      <button type="button" class="btn btn-sm width-50" id="btn-cus-range" onclick="toggleAllCustomer(0)">Select</button>
	    </div>
	  </div>
	  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 padding-5">
	    <label class="">From</label>
	    <input type="text" class="form-control input-sm text-center" id="cusFrom" name="cusFrom" disabled>
	  </div>
	  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 padding-5">
	    <label class="">To</label>
	    <input type="text" class="form-control input-sm text-center" id="cusTo" name="cusTo" disabled>
	  </div>

	  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5 ">
	    <label class="display-block">Warehouse</label>
	    <div class="btn-group width-100" style="height:30px;">
	      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-wh-all" onclick="toggleAllWarehouse(1)">All</button>
	      <button type="button" class="btn btn-sm width-50" id="btn-wh-range" onclick="toggleAllWarehouse(0)">Select</button>
	    </div>
	  </div>

	  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5 ">
			<label class="display-block">Location</label>
	    <div class="btn-group width-100" style="height:30px;">
	      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-zone-all" onclick="toggleAllZone(1)">All</button>
	      <button type="button" class="btn btn-sm width-50" id="btn-zone-range" onclick="toggleAllZone(0)">Select</button>
	    </div>
	  </div>

		<div class="col-lg-4 col-md-4 col-sm-7 col-xs-6 padding-5 ">
			<label class="display-block not-show">zone</label>
			<input type="text" class="form-control input-sm" name="zoneName" id="zoneName" disabled>
		</div>

    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
      <label>Date</label>
      <div class="input-daterange input-group">
        <input type="text" class="form-control input-sm width-50 text-center from-date" id="fromDate" name="fromDate" />
        <input type="text" class="form-control input-sm width-50 text-center" id="toDate" name="toDate" />
      </div>
    </div>

  <input type="hidden" id="allProduct" name="allProduct" value="1">
  <input type="hidden" id="allCustomer" name="allCustomer" value="1">
  <input type="hidden" id="allWarehouse" name="allWhouse" value="1">
	<input type="hidden" id="allZone" name="allZone" value="1">
	<input type="hidden" id="zoneCode" name="zoneCode" value="">
	<input type="hidden" id="token" name="token">
</div>


<div class="modal fade" id="wh-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' id='modal' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='title' id='modal_title'>List of warehouse</h4>
            </div>
            <div class='modal-body' id='modal_body' style="padding:0px;">
        <?php if(!empty($whList)) : ?>
          <?php foreach($whList as $rs) : ?>
            <div class="col-sm-12">
              <label>
                <input type="checkbox" class="chk" id="<?php echo $rs->code; ?>" name="warehouse[<?php echo $rs->code; ?>]" value="<?php echo $rs->code; ?>" style="margin-right:10px;" />
                <?php echo $rs->code; ?> | <?php echo $rs->name; ?>
              </label>
            </div>
          <?php endforeach; ?>
        <?php endif;?>

        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>OK</button>
            </div>
        </div>
    </div>
</div>
<hr>
</form>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive" id="rs" style="max-height:600px; overflow:auto;">

  </div>
</div>

<script id="template" type="text/x-handlebars-template">
  <table class="table table-bordered border-1" style="min-width:2420px;">
    <thead>
      <tr>
        <th class="fix-width-40 text-center">#</th>
        <th class="fix-width-100 text-center">Date</th>
        <th class="fix-width-120 text-center">Document No</th>
        <th class="fix-width-150 text-center">SKU</th>
        <th class="fix-width-250 text-center">Description</th>
        <th class="fix-width-100 text-center">Cost</th>
        <th class="fix-width-100 text-center">Price</th>
        <th class="fix-width-100 text-center">Discount</th>
        <th class="fix-width-100 text-center">Price after disc.</th>
        <th class="fix-width-100 text-center">Qty</th>
        <th class="fix-width-120 text-center">Total Disc.</th>
        <th class="fix-width-120 text-center">Total Amount</th>
        <th class="fix-width-120 text-center">Total Cost</th>
        <th class="fix-width-100 text-center">Customer Code</th>
        <th class="fix-width-200 text-center">Customer Name</th>
        <th class="fix-width-100 text-center">Warehouse Code</th>
        <th class="fix-width-200 text-center">Warehouse Name</th>
        <th class="fix-width-100 text-center">Location Code</th>
        <th class="fix-width-200 text-center">Location Name</th>
      </tr>
    </thead>
{{#each bs}}
  {{#if nodata}}
    <tr>
      <td colspan="15" align="center"><h4>-----  Not found  -----</h4></td>
    </tr>
  {{else}}
    {{#if @last}}
    <tr class="font-size-14">
      <td colspan="9" class="text-right">Total</td>
      <td class="text-right">{{ totalQty }}</td>
      <td class="text-right">{{ totalDiscount }}</td>
      <td class="text-right">{{ totalCost }}</td>
      <td class="text-right">{{ totalAmount }}</td>
      <td colspan="2"></td>
    </tr>
    {{else}}
    <tr class="font-size-12">
      <td class="fix-width-40 text-center">{{no}}</td>
      <td class="fix-width-100 text-center">{{date_add}}</td>
      <td class="fix-width-120 text-center">{{reference}}</td>
      <td class="fix-width-150">{{product_code}}</td>
      <td class="fix-width-250">{{product_name}}</td>
      <td class="fix-width-100 text-right">{{cost}}</td>
      <td class="fix-width-100 text-right">{{price}}</td>
      <td class="fix-width-100 text-right">{{discount_label}}</td>
      <td class="fix-width-100 text-right">{{sell}}</td>
      <td class="fix-width-100 text-right">{{qty}}</td>
      <td class="fix-width-120 text-right">{{total_discount}}</td>
      <td class="fix-width-120 text-right">{{total_amount}}</td>
      <td class="fix-width-120 text-right">{{total_cost}}</td>
      <td class="fix-width-100 text-center">{{customer_code}}</td>
      <td class="fix-width-200">{{customer_name}}</td>
      <td class="fix-width-100 text-center">{{warehouse_code}}</td>
      <td class="fix-width-200">{{warehouse_name}}</td>
      <td class="fix-width-100 text-center">{{zone_code}}</td>
      <td class="fix-width-200">{{zone_name}}</td>
    </tr>
    {{/if}}
  {{/if}}
{{/each}}
  </table>
</script>

<script src="<?php echo base_url(); ?>scripts/report/sales/sales_consignment_report.js?v=<?php date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
