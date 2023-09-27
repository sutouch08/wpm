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
    <label>Document Date</label>
    <div class="input-daterange input-group width-100">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="fromDate" id="fromDate" placeholder="From" required />
      <input type="text" class="form-control input-sm width-50 text-center" name="toDate" id="toDate" placeholder="To" required/>
    </div>
  </div>

	<div class="col-lg-2 col-md-3 col-sm-2-harf col-xs-6 padding-5">
		<label class="display-block">Acceptance</label>
		<select class="form-control input-sm" name="is_accept" id="is_accept">
			<option value="0">Pending</option>
			<option value="1">Accepted</option>
			<option value="all">All</option>
		</select>
	</div>

	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label class="display-block">Document Type</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-role-all" onclick="toggleAllRole(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-role-range" onclick="toggleAllRole(0)">Select</button>
    </div>
  </div>

</div>

	<input type="hidden" id="allRole" name="allRole" value="1" />
	<input type="hidden" id="token" name="token"  value="<?php echo uniqid(); ?>">

	<div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-labelledby="role-modal" aria-hidden="true">
		<div class="modal-dialog" style="width:300px; max-width:95%; margin-left:auto; margin-right:auto;">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="title">Select Document Type</h4>
	            </div>
	            <div class="modal-body" style="padding:0px;">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
									<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="WW" /><span class="lbl">  WW - Inventory Transfer</span></label>
									<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="MV" /><span class="lbl">  MV - Location Move</span></label>
									<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="RT" /><span class="lbl">  RT - Goods Receive Transform</span></label>
									<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="RN" /><span class="lbl">  RN - Return Lend</span></label>
									<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="WR" /><span class="lbl">  WR - Goods Receipt Transform</span></label>
									<label class="display-block"><input type="checkbox" class="ace role-chk" name="role[]" value="SM" /><span class="lbl">  SM - Goods Return</span></label>
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
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5" id="result">

	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 margin-top-10">
		<p id="row-result"></p>
	</div>
</div>

<script id="template" type="text/x-handlebars-template">
{{#if data}}
	<table class="table table-striped border-1" style="min-width:950px;">
		<thead>
			<tr>
				<th class="fix-width-60 text-center">#</th>
				<th class="fix-width-100 text-center">Document Date</th>
				<th class="fix-width-120">Document No.</th>
				<th class="fix-width-150">Location Owner</th>
				<th class="fix-width-100 text-center">Acceptance</th>
				<th class="fix-width-150">Accepted By</th>
				<th class="fix-width-150">Accepted Date</th>
				<th class="min-width-100">Note</th>
			</tr>
		</thead>
		<tbody>
			{{#each data}}
				<tr>
					<td class="middle text-center">{{no}}</td>
					<td class="middle text-center">{{date_add}}</td>
					<td class="middle">{{code}}</td>
					<td class="middle">{{owner_name}}</td>
					<td class="middle">{{is_accept}}</td>
					<td class="middle">{{accept_by}}</td>
					<td class="middle">{{accept_on}}</td>
					<td class="middle">{{accept_remark}}</td>
				</tr>
			{{/each}}
		</tbody>
	</table>
	{{else}}
		<div class="alert alert-info margin-top-30 text-center">--- Not found ---</div>
	{{/if}}
</script>


<script src="<?php echo base_url(); ?>scripts/report/audit/transfer_acception.js?v=<?php date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
