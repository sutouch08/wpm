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
			<!--<button type="button" class="btn btn-xs btn-purple" onclick="exportToCheck()"><i class="fa fa-file-excel-o"></i> Export stock template</button>-->
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

		<div class="col-lg-2 col-md-2 col-sm-7 col-xs-6 padding-5 ">
			<label class="display-block not-show">zone</label>
			<input type="text" class="form-control input-sm" name="zoneName" id="zoneName" disabled>
		</div>
  <input type="hidden" id="allProduct" name="allProduct" value="1">
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
	<div class="col-sm-12" id="rs">

    </div>
</div>

<form id="exportForm" method="post" action="<?php echo $this->home; ?>/export_to_check">
	<input type="hidden" name="zone_code" id="export-zone-code" />
	<input type="hidden" id="export-token" name="token">
</form>



<script id="template" type="text/x-handlebars-template">
  <table class="table table-bordered table-striped">
    <tr>
      <th colspan="6" class="text-center">Report on consignment inventories as of {{ reportDate }} {{ reportDate }}</th>
    </tr>
    <tr>
      <th colspan="6" class="text-center"> Warehouse : {{ whList }} </th>
    </tr>
		<tr>
      <th colspan="6" class="text-center"> Location : {{ zoneList }} </th>
    </tr>
    <tr>
      <th colspan="6" class="text-center"> Products : {{ productList }} </th>
    </tr>
    <tr class="font-size-12">
      <th class="width-5 middle text-center">#</th>
			<th class="width-10 middle text-center">Warehouse</th>
      <th class="width-25 middle text-center">Location</th>
      <th class="width-20 middle text-center">SKU</th>
      <th class="width-30 middle text-center">Description</th>
      <th class="width-10 text-right middle">Balance</th>
    </tr>
{{#each bs}}
  {{#if nodata}}
    <tr>
      <td colspan="6" align="center"><h4>-----  Not found  -----</h4></td>
    </tr>
  {{else}}
    {{#if @last}}
    <tr class="font-size-14">
      <td colspan="5" class="text-right">Total</td>
      <td class="text-right">{{ totalQty }}</td>
    </tr>
    {{else}}
    <tr class="font-size-12">
      <td class="middle text-center">{{no}}</td>
      <td class="middle">{{ warehouse }}</td>
			<td class="middle">{{ zone }}</td>
      <td class="middle">{{ pdCode }}</td>
      <td class="middle">{{ pdName }}</td>
      <td class="middle text-right">{{ qty }}</td>
    </tr>
    {{/if}}
  {{/if}}
{{/each}}
  </table>
</script>

<script src="<?php echo base_url(); ?>scripts/report/inventory/consignment_stock_zone.js?v=<?php date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
