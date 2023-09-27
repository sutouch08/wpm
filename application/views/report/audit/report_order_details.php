<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-8 col-md-8 col-sm-8 padding-5 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h4 class="title-xs"><i class="fa fa-bar-chart"></i> <?php echo $this->title; ?></h4>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> Report</button>
			<button type="button" class="btn btn-sm btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Export</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5 hidden-print"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
<div class="row">
	<div class="col-lg-2-harf col-md-3 col-sm-3-harf col-xs-6 padding-5">
    <label>Date</label>
    <div class="input-daterange input-group width-100">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="fromDate" id="fromDate" placeholder="From" required />
      <input type="text" class="form-control input-sm width-50 text-center" name="toDate" id="toDate" placeholder="To" required/>
    </div>
  </div>

	<div class="col-lg-2 col-md-3 col-sm-2-harf col-xs-6 padding-5">
		<label class="display-block">Expiration</label>
		<select class="form-control input-sm" name="is_expired" id="is_expired">
			<option value="all">All</option>
			<option value="1">Expried Only</option>
			<option value="0">Not Expired Only</option>
		</select>
	</div>

	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label class="display-block">Document Type</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-role-all" onclick="toggleAllRole(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-role-range" onclick="toggleAllRole(0)">Select</button>
    </div>
  </div>


	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label class="display-block">Doc. Status</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-st-all" onclick="toggleAllState(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-st-range" onclick="toggleAllState(0)">Select</button>
    </div>
  </div>

  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label class="display-block">Channels</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-channels-all" onclick="toggleAllChannels(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-channels-range" onclick="toggleAllChannels(0)">Select</button>
    </div>
  </div>

  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label class="display-block">Payments</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pay-all" onclick="toggleAllPayment(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-pay-range" onclick="toggleAllPayment(0)">Select</button>
    </div>
  </div>

  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label class="display-block">Warehouse</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-wh-all" onclick="toggleAllWarehouse(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-wh-range" onclick="toggleAllWarehouse(0)">Select</button>
    </div>
  </div>
</div>

	<input type="hidden" id="allRole" name="allRole" value="1" />
  <input type="hidden" id="allChannels" name="allChannels" value="1" />
  <input type="hidden" id="allPayments" name="allPayments" value="1" />
  <input type="hidden" id="allWarehouse" name="allWarehouse" value="1" />
	<input type="hidden" id="allState" name="allState" value="1" />
	<input type="hidden" id="token" name="token"  value="<?php echo uniqid(); ?>">


<div class="modal fade" id="channels-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:300px; max-width:95%; margin-left:auto; margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="title">Sales Channels</h4>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
        <?php if(!empty($channels_list)) : ?>
          <?php foreach($channels_list as $rs) : ?>
              <label class="display-block">
                <input type="checkbox" class="ace ch-chk" name="channels[]" value="<?php echo $rs->code; ?>"/>
                <span class="lbl">&nbsp; <?php echo $rs->name; ?></span>
              </label>
          <?php endforeach; ?>
        <?php endif;?>
						</div>
        		<div class="divider" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="payment-modal" aria-hidden="true">
	<div class="modal-dialog" style="width:300px; max-width:95%; margin-left:auto; margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="title">Payment Channels</h4>
            </div>
            <div class="modal-body" style="padding:0px;">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
        <?php if(!empty($payment_list)) : ?>
          <?php foreach($payment_list as $rs) : ?>
              <label class="display-block">
                <input type="checkbox" class="ace pay-chk" name="payments[]" value="<?php echo $rs->code; ?>" />
                <span class="lbl">&nbsp; <?php echo $rs->name; ?></span>
              </label>
          <?php endforeach; ?>
        <?php endif;?>
						</div>
        		<div class="divider" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="warehouse-modal" aria-hidden="true">
	<div class="modal-dialog" style="max-width:95%; margin-left:auto; margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="title">List of Warehouse</h4>
            </div>
            <div class="modal-body" style="padding:0px;">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
        <?php if(!empty($warehouse_list)) : ?>
          <?php foreach($warehouse_list as $rs) : ?>
              <label class="display-block">
                <input type="checkbox" class="ace wh-chk" name="warehouse[]" value="<?php echo $rs->code; ?>" />
								<span class="lbl">&nbsp; <?php echo $rs->name; ?></span>
              </label>
          <?php endforeach; ?>
        <?php endif;?>
							</div>

        		<div class="divider" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="state-modal" tabindex="-1" role="dialog" aria-labelledby="warehouse-modal" aria-hidden="true">
	<div class="modal-dialog" style="width:250px; max-width:95%; margin-left:auto; margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="title">List of Status</h4>
            </div>
            <div class="modal-body" style="padding:0px;">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="1" style="margin-right:10px;" /><span class="lbl">  Pending</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="2" style="margin-right:10px;" /><span class="lbl">  Waiting for payment</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="3" style="margin-right:10px;" /><span class="lbl">  Waiting to pick</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="4" style="margin-right:10px;" /><span class="lbl">  Picking</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="5" style="margin-right:10px;" /><span class="lbl">  Waiting to pack</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="6" style="margin-right:10px;" /><span class="lbl">  Packing</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="7" style="margin-right:10px;" /><span class="lbl">  Ready to ship</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="8" style="margin-right:10px;" /><span class="lbl">  Shipped</span></label>
								<label class="display-block"><input type="checkbox" class="ace st-chk" name="state[]" value="9" style="margin-right:10px;" /><span class="lbl">  Cancelled</span></label>
	            </div>
        		<div class="divider" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-labelledby="role-modal" aria-hidden="true">
	<div class="modal-dialog" style="width:250px; max-width:95%; margin-left:auto; margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="title">List of Document Type</h4>
            </div>
            <div class="modal-body" style="padding:0px;">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="S" /><span class="lbl">  WO - Sales Order</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="C" /><span class="lbl">  WC - Consignment(IV)</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="N" /><span class="lbl">  WT - Consignment(TR)</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="P" /><span class="lbl">  WS - Sponsor Order</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="U" /><span class="lbl">  WU - Complementary</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="T" /><span class="lbl">  WQ - Transform Order</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="Q" /><span class="lbl">  WV - Transform Order</span></label>
								<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="L" /><span class="lbl">  WL - Lend Order</span></label>
	            </div>
        		<div class="divider" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<hr>
</form>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center" id="result" style="min-height: 300px; max-height: 600px; overflow:auto;">

	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 margin-top-10">
		<p id="row-result"></p>
	</div>
</div>

<script id="template" type="text/x-handlebars-template">
{{#if details}}
	<table class="table table-striped border-1" style="min-width:1740px;">
		<thead>
			<tr>
				<th class="fix-width-60 text-center">#</th>
				<th class="fix-width-100 text-center">Date</th>
				<th class="fix-width-120">Doc No.</th>
				<th class="fix-width-100 text-right">Amount</th>
				<th class="fix-width-120">Customer Code</th>
				<th class="fix-width-350">Customer Name</th>
				<th class="fix-width-100 text-center">Status</th>
				<th class="fix-width-100 text-center">Expired</th>
				<th class="fix-width-120">Sale Channels</th>
				<th class="fix-width-120">Payment Channels</th>
				<th class="fix-width-200">Warehouse</th>
				<th class="fix-width-100">User</th>
				<th class="fix-width-150">Employee</th>
			</tr>
		</thead>
		<tbody>
			{{#each details}}
				<tr>
					<td class="middle text-center">{{no}}</td>
					<td class="middle text-center">{{date_add}}</td>
					<td class="middle">{{code}}</td>
					<td class="middle text-right">{{total_amount}}</td>
					<td class="middle">{{customer_code}}</td>
					<td class="middle">{{customer_name}}</td>
					<td class="middle text-center">{{state_name}}</td>
					<td class="middle text-center">{{expired}}</td>
					<td class="middle">{{channels_name}}</td>
					<td class="middle">{{payment_name}}</td>
					<td class="middle">{{warehouse_name}}</td>
					<td class="middle">{{uname}}</td>
					<td class="middle">{{emp_name}}</td>
				</tr>
			{{/each}}
		</tbody>
	</table>
	{{else}}
		<div class="alert alert-info margin-top-30">--- Not found ---</div>
	{{/if}}
</script>


<script src="<?php echo base_url(); ?>scripts/report/audit/order_details.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
