<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    	<h3 class="title" ><?php echo $this->title; ?></h3>
	</div>
    <div class="col-sm-6">
    <p class="pull-right top-p">
			<?php if(empty($approve_view)) : ?>
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
      <button type="button" class="btn btn-sm btn-info" onclick="printReceived()"><i class="fa fa-print"></i> Print</button>
			<?php endif; ?>

			<?php if($doc->valid == 0 && $doc->status == 1 && ! $doc->is_approve && $this->pm->can_approve) : ?>
			<button type="button" class="btn btn-sm btn-primary" onclick="approve()"><i class="fa fa-check"></i> Approve</button>
		<?php elseif($doc->valid == 0 && $doc->status == 1 && $doc->is_approve && $this->pm->can_approve) : ?>
			<button type="button" class="btn btn-sm btn-danger" onclick="unapprove()"><i class="fa fa-times"></i> Cancel Approval</button>
		<?php endif; ?>
    </p>
  </div>
</div>
<hr />

<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
  	<label>Document No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
  </div>
	<div class="col-sm-1 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center" name="date_add" id="dateAdd" value="<?php echo thai_date($doc->date_add); ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>Vendor</label>
    <input type="text" class="form-control input-sm" value="<?php echo $doc->vendor_code; ?>" disabled />
  </div>
  <div class="col-sm-5 padding-5">
  	<label class="not-show">ผู้จำหน่าย</label>
    <input type="text" class="form-control input-sm" value="<?php echo $doc->vendor_name; ?>" disabled />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>PO No.</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->po_code; ?>" disabled />
  </div>

  <div class="col-sm-1 col-1-harf padding-5 last">
  	<label>Invoice No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->invoice_code; ?>" disabled/>
  </div>

  <div class="col-sm-10 padding-5 first">
		<label>Remark</label>
		<input type="text" class="form-control input-sm" value="<?php echo $doc->remark; ?>" disabled />
	</div>
	<div class="col-sm-2 padding-5 last">
		<label>Receipt No.</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $doc->receive_code; ?>" disabled />
	</div>
  <input type="hidden" name="receive_code" id="receive_code" value="<?php echo $doc->code; ?>" />
</div>

<?php
if($doc->status == 2)
{
  $this->load->view('cancle_watermark');
}
?>
<hr class="margin-top-15 margin-bottom-15"/>
<div class="row">
	<div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
      	<tr class="font-size-12">
        	<th class="width-5 text-center">#</th>
          <th class="width-20 text-center">Item code</th>
          <th class="">Description</th>
					<th class="width-10 text-right">Outstanding</th>
          <th class="width-10 text-right">Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($details)) : ?>
          <?php $no =  1; ?>
          <?php $total_qty = 0; ?>
					<?php $total_backlogs = 0; ?>
          <?php foreach($details as $rs) : ?>
            <tr class="font-size-12">
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle"><?php echo $rs->product_code; ?></td>
              <td class="middle"><?php echo $rs->product_name; ?></td>
							<td class="middle text-right"><?php echo number($rs->backlogs); ?></td>
              <td class="middle text-right"><?php echo number($rs->qty); ?></td>
            </tr>
            <?php $no++; ?>
            <?php $total_qty += $rs->qty; ?>
						<?php $total_backlogs += $rs->backlogs; ?>
          <?php endforeach; ?>
          <tr>
            <td colspan="3" class="text-right"><strong>Total</strong></td>
						<td class="text-right"><strong><?php echo number($total_backlogs); ?></strong></td>
            <td class="text-right"><strong><?php echo number($total_qty); ?></strong></td>
          </tr>
        <?php endif; ?>
			  </tbody>
      </table>
    </div>

					<?php if(!empty($approve_list)) :?>
						<?php foreach($approve_list as $appr) : ?>
							<div class="col-sm-12 text-right">
								<?php if($appr->approve == 1) : ?>
									<span class="green">
										Approved by : <?php echo $appr->approver; ?> @ <?php echo thai_date($appr->date_upd, TRUE); ?>
									</span>
								<?php endif; ?>
								<?php if($appr->approve == 0) : ?>
									<span class="red">
										Cancel approval by : <?php echo $appr->approver; ?> @ <?php echo thai_date($appr->date_upd, TRUE); ?>
									</span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/receive_po_request/receive_po_request.js"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/receive_po_request/receive_po_request_add.js"></script>

<?php $this->load->view('include/footer'); ?>
