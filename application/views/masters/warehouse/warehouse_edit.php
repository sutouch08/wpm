<?php $this->load->view('include/header'); ?>
<?php
	$sell_yes = $ds->sell == 1 ? 'btn-success' : '';
	$sell_no = $ds->sell == 0 ? 'btn-danger' : '';
	$prepare_yes = $ds->prepare == 1 ? 'btn-success' : '';
	$prepare_no = $ds->prepare == 0 ? 'btn-danger' : '';
	$auz_yes = $ds->auz == 1 ? 'btn-success' : '';
	$auz_no = $ds->auz == 0 ? 'btn-danger' : '';
	$btn_active = $ds->active == 1 ? 'btn-success' : 'btn-danger';
	$cm_yes = $ds->is_consignment == 1 ? 'btn-success' : '';
	$cm_no = empty($ds->is_consignment) ? 'btn-danger' : '';
 ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
    <h3 class="title-xs"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
  	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class=""/>
<form class="form-horizontal" id="addForm" method="post" action="<?php echo $this->home."/update"; ?>">

	<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right">Whs Code</label>
    <div class="col-xs-12 col-sm-3">
      <input type="text" class="form-control input-sm" value="<?php echo $ds->code; ?>" disabled />
    </div>
  </div>



  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right">Description</label>
    <div class="col-xs-12 col-sm-3">
			<input type="text" class="form-control input-sm" value="<?php echo $ds->name; ?>" disabled />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
  </div>

	<div class="form-group">
 	 <label class="col-sm-3 control-label no-padding-right">Whs Role</label>
 	 <div class="col-xs-12 col-sm-3">
 		 <select class="form-control input-sm" name="role" required>
 		 	<option value="">Select</option>
			<?php echo select_warehouse_role($ds->role); ?>
 		 </select>
 	 </div>
  </div>

	<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right">Maximum Amount Allowed</label>
    <div class="col-xs-12 col-sm-3">
			<input type="text" class="form-control input-sm" value="<?php echo number($ds->limit_amount, 2); ?>" disabled />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline" >
			Total value (capital) of goods allowed in this warehouse. If you don't want to limit the value, set it to 0.00.
		</div>
  </div>

	<div class="form-group">
 	 <label class="col-sm-3 control-label no-padding-right">Consignment IV</label>
 	 <div class="col-xs-12 col-sm-2">
 		<div class="btn-group width-100">
 			<button type="button" class="btn btn-sm width-50 <?php echo $cm_yes; ?>" id="btn-cm-yes" onclick="toggleConsignment(1)">Yes</button>
			<button type="button" class="btn btn-sm width-50 <?php echo $cm_no; ?>" id="btn-cm-no" onclick="toggleConsignment(0)">No</button>
 		</div>
 	 </div>
  </div>


	<div class="form-group">
 	 <label class="col-sm-3 control-label no-padding-right">Allow to Sell</label>
 	 <div class="col-xs-12 col-sm-2">
 		<div class="btn-group width-100">
 			<button type="button" class="btn btn-sm width-50 <?php echo $sell_yes; ?>" id="btn-sell-yes" onclick="toggleSell(1)">Yes</button>
			<button type="button" class="btn btn-sm width-50 <?php echo $sell_no; ?>" id="btn-sell-no" onclick="toggleSell(0)">No</button>
 		</div>
 	 </div>
  </div>

	<div class="form-group">
 	 <label class="col-sm-3 control-label no-padding-right">Allow to Pick</label>
 	 <div class="col-xs-12 col-sm-2">
 		<div class="btn-group width-100">
 			<button type="button" class="btn btn-sm width-50 <?php echo $prepare_yes; ?>" id="btn-prepare-yes" onclick="togglePrepare(1)">Yes</button>
			<button type="button" class="btn btn-sm width-50 <?php echo $prepare_no; ?>" id="btn-prepare-no" onclick="togglePrepare(0)">No</button>
 		</div>
 	 </div>
  </div>

	<div class="form-group">
 	 <label class="col-sm-3 control-label no-padding-right">Nagative Stock Allowed</label>
 	 <div class="col-xs-12 col-sm-2">
 		<div class="btn-group width-100">
 			<button type="button" class="btn btn-sm width-50 <?php echo $auz_yes; ?>" id="btn-auz-yes" onclick="toggleAuz(1)">Yes</button>
			<button type="button" class="btn btn-sm width-50 <?php echo $auz_no; ?>" id="btn-auz-no" onclick="toggleAuz(0)">No</button>
 		</div>
 	 </div>
  </div>

	<div class="form-group">
 	 <label class="col-sm-3 control-label no-padding-right">Status</label>
 	 <div class="col-xs-12 col-sm-2">
		 <button type="button" class="btn btn-sm <?php echo $btn_active; ?>" style="width:100px;" disabled>
			 <?php echo $ds->active == 1 ? 'Active' : 'Inactive'; ?>
		 </button>
 	 </div>
  </div>



	<div class="divider-hidden">

	</div>
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right"></label>
    <div class="col-xs-12 col-sm-3">
      <p class="pull-right">
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
      </p>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline">
      &nbsp;
    </div>
  </div>
	<input type="hidden" name="code" value="<?php echo $ds->code; ?>">
	<input type="hidden" name="sell" id="sell" value="<?php echo $ds->sell; ?>">
	<input type="hidden" name="prepare" id="prepare" value="<?php echo $ds->prepare; ?>">
	<input type="hidden" name="auz" id="auz" value="<?php echo $ds->auz; ?>">
	<!--
	<input type="hidden" name="active" id="active" value="<?php echo $ds->active; ?>">
-->
	<input type="hidden" name="is_consignment" id="is_consignment" value="<?php echo $ds->is_consignment; ?>">
</form>

<script src="<?php echo base_url(); ?>scripts/masters/warehouse.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
