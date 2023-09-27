<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-sm-8 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
    </div>
		<div class="col-sm-4 padding-5">
			<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> Report</button>
				<button type="button" class="btn btn-sm btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Export</button>
			</p>
		</div>
</div><!-- End Row -->
<hr class="hidden-print"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
<div class="row">
	<div class="col-sm-1 col-1-harf padding-5">
		<label class="display-block">Owner</label>
		<div class="btn-group width-100">
			<button type="button" class="btn btn-sm btn-primary width-50" id="btn-user-all" onclick="toggleAllUser(1)">All</button>
			<button type="button" class="btn btn-sm width-50" id="btn-user-use" onclick="toggleAllUser(0)">Specify</button>
		</div>
	</div>
	<div class="col-sm-3 padding-5">
		<label class="display-block not-show">&nbsp;</label>
		<input type="text" class="form-control input-sm" name="u_name" id="u_name" placeholder="Specify user name" disabled>
	</div>
	<div class="col-sm-1 col-1-harf padding-5">
		<label>Products</label>
		<div class="btn-group width-100">
			<button type="button" class="btn btn-sm btn-primary width-50" id="btn-pd-all" onclick="toggleAllProduct(1)">All</button>
			<button type="button" class="btn btn-sm width-50" id="btn-pd-range" onclick="toggleAllProduct(0)">Specify</button>
		</div>
	</div>
	<div class="col-sm-2 padding-5">
		<label class="not-show">Start</label>
		<input type="text" class="form-control input-sm" id="pdFrom" name="pdFrom" placeholder="From" disabled>
	</div>
	<div class="col-sm-2 padding-5">
		<label class="not-show">End</label>
		<input type="text" class="form-control input-sm" id="pdTo" name="pdTo" placeholder="To" disabled>
	</div>
	<div class="col-sm-2 padding-5">
		<label>Date</label>
		<div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="fromDate" id="fromDate" readonly placeholder="From">
      <input type="text" class="form-control input-sm width-50 text-center" name="toDate" id="toDate" readonly placeholder="To">
    </div>
	</div>
</div>
<input type="hidden" id="allUser" name="allUser" value="1" />
<input type="hidden" id="dname" name="dname" value="" />
<input type="hidden" id="allProduct" name="allProduct" value="1" />
<input type="hidden" id="token" name="token" value="<?php echo uniqid(); ?>">
<hr>
</form>

<div class="row">
  <div class="col-sm-12 padding-5 table-responsive">
		<table class="table table-bordered" style="min-width:1260px;">
			<thead>
				<tr class="font-size-12">
					<th class="fix-width-40 text-center">#</th>
					<th class="fix-width-100 text-center">Owner(User)</th>
					<th class="fix-width-100 text-center">Maker</th>
					<th class="fix-width-100 text-center">Date</th>
					<th class="fix-width-120 text-center">Document No</th>
					<th class="fix-width-200 text-center">Items(Orignal)</th>
					<th class="fix-width-200 text-center">Items(Transformed)</th>
					<th class="fix-width-80 text-center">Price</th>
					<th class="fix-width-80 text-center">Sent</th>
					<th class="fix-width-80 text-center">Returned</th>
					<th class="fix-width-80 text-center">Outstanding Qty</th>
					<th class="fix-width-80 text-center">Outstanding Amount</th>
				</tr>
			</thead>
			<tbody id="result">

			</tbody>
		</table>
  </div>
</div>

<script id="template" type="text/x-handlebarsTemplate">
	{{#each this}}
		{{#if @last}}
			<tr class="font-size-12">
				<td colspan="8" class="middle text-right">Total</td>
				<td class="middle text-right">{{total_qty}}</td>
				<td class="middle text-right">{{total_turn}}</td>
				<td class="middle text-right">{{total_balance}}</td>
				<td class="middle text-right">{{total_amount}}</td>
			</tr>
		{{else}}
			<tr class="font-size-10">
				<td class="middle text-center">{{no}}</td>
				<td class="middle">{{user_ref}}</td>
				<td class="middle">{{user}}</td>
				<td class="middle">{{date_add}}</td>
				<td class="middle">{{order_code}}</td>
				<td class="middle">{{original_code}}</td>
				<td class="middle">{{product_code}}</td>
				<td class="middle text-right">{{price}}</td>
				<td class="middle text-right">{{qty}}</td>
				<td class="middle text-right">{{return}}</td>
				<td class="middle text-right">{{balance}}</td>
				<td class="middle text-right">{{amount}}</td>
			</tr>
		{{/if}}
	{{/each}}
</script>



<script src="<?php echo base_url(); ?>scripts/report/audit/report_transform_backlogs.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
