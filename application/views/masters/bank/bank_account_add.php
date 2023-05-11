<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6 col-xs-6 padding-5">
    <h3 class="title">
      <i class="fa fa-credit-card"></i> <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-sm-6 col-xs-6 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>&nbsp; กลับ</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-30 padding-5"/>
<form class="form-horizontal">
	<div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">ธนาคาร</label>
    <div class="col-xs-12 col-sm-3">
        <select class="form-control input-sm" id="bank-code" name="bank_code" required>
					<option value="">เลือกธนาคาร</option>
					<?php echo select_bank(); ?>
  			</select>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="bank-code-error"></div>
  </div>



  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">ชื่อบัญชี</label>
    <div class="col-xs-12 col-sm-3">
			<input type="text" name="acc_name" id="acc-name" class="form-control input-sm" required autofocus />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="acc-name-error"></div>
  </div>

	<div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">เลขที่บัญชี</label>
    <div class="col-xs-12 col-sm-3">
			<input
				type="text"
				name="acc_no"
				id="acc-no"
				placeholder="000-0-00000-0"
				class="form-control input-sm"
				required />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="acc-no-error"></div>
  </div>

	<div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">สาขา</label>
    <div class="col-xs-12 col-sm-3">
			<input type="text" name="branch" id="branch" class="form-control input-sm" required />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="branch-error"></div>
  </div>

<?php if($this->pm->can_add) : ?>
	<div class="divider-hidden"></div>
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right"></label>
    <div class="col-xs-12 col-sm-3">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success" onclick="saveAdd()"><i class="fa fa-save"></i> Save</button>
      </p>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline">
      &nbsp;
    </div>
  </div>
<?php endif; ?>
</form>


<script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.js"></script>
<script src="<?php echo base_url(); ?>scripts/masters/bank_account.js"></script>


<script>
	$('#acc-no').mask('999-9-99999-9');
</script>

<?php $this->load->view('include/footer'); ?>
