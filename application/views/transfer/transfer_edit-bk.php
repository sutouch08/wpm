<?php $this->load->view('include/header'); ?>
<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
<div class="row">
	<div class="col-sm-3 col-xs-12 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-9 col-xs-12 padding-5">
    	<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		    <?php if($doc->status == 1) : ?>
		      <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-send"></i> Send to SAP</button>
					<?php if($this->pm->can_edit && ($doc->is_wms = 0 OR $doc->api = 0)) : ?>
						<button type="button" class="btn btn-sm btn-danger" onclick="unSave()"><i class="fa fa-exclamation-triangle"></i> Unsave</button>
					<?php endif; ?>
		    <?php endif; ?>
		    <?php if(($doc->status == -1 OR $doc->status == 0) && $this->pm->can_add OR $this->pm->can_edit) : ?>

		      <?php if(($doc->status == -1 OR $doc->status == 0) && $barcode === TRUE) : ?>
		        <button type="button" class="btn btn-sm btn-primary" onclick="goUseKeyboard()">Manual key in</button>
		      <?php endif; ?>


		      <?php if(($doc->status == -1 OR $doc->status == 0) && $barcode === FALSE) : ?>
		        <button type="button" class="btn btn-sm btn-primary" onclick="goUseBarcode()">Scan barcode</button>
		      <?php endif; ?>

					<?php if(($doc->status == -1 OR $doc->status == 0) && ($this->pm->can_add OR $this->pm->can_edit)) : ?>
		      <button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> Save</button>
					<?php endif; ?>
		    <?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr/>
<?php
	$this->load->view('transfer/transfer_edit_header');
	if(($doc->status == -1 OR $doc->status == 0))
	{
		$this->load->view('transfer/transfer_control');
	}

	if($barcode === TRUE)
	{
		$this->load->view('transfer/transfer_detail_barcode');
	}
	else
	{
		$this->load->view('transfer/transfer_detail');
	}
?>

<?php else : ?>
<?php $this->load->view('deny_page'); ?>
<?php endif; ?>
<script src="<?php echo base_url(); ?>scripts/transfer/transfer.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/transfer/transfer_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/transfer/transfer_control.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/transfer/transfer_detail.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/transfer/transfer_edit.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/beep.js"></script>

<?php $this->load->view('include/footer'); ?>
