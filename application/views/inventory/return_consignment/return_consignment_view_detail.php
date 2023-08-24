<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    	<h3 class="title" >
        <?php echo $this->title; ?>
      </h3>
	</div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
  <?php if($doc->is_approve == 1) : ?>
		<?php if($doc->status == 1) : ?>
			<button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-send"></i> Sent to SAP</button>
		<?php endif; ?>
		<?php if($doc->status == 3) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="sendToWms()"><i class="fa fa-send"></i> Send to WMS</button>
		<?php endif; ?>
	<?php endif; ?>

	<?php if($doc->status == 1 && $doc->is_approve == 0 && $this->pm->can_edit) : ?>
				<button type="button" class="btn btn-sm btn-danger" onclick="unsave()">Unsave</button>
	<?php endif; ?>
	<?php if($doc->status == 1 && $doc->is_approve == 0 && $this->pm->can_approve) : ?>
				<button type="button" class="btn btn-sm btn-primary" onclick="approve()"><i class="fa fa-check"></i> Approve</button>
	<?php endif; ?>
	<?php if($this->pm->can_delete && $doc->status != 2) : ?>
				<button type="button" class="btn btn-sm btn-danger" onclick="goDelete('<?php echo $doc->code; ?>')"><i class="fa fa-times"></i> Cancel</button>
	<?php endif; ?>

	<?php if($doc->status == 2 && $this->_SuperAdmin) : ?>
			<button type="button" class="btn btn-sm btn-primary" onclick="pullBack('<?php echo $doc->code; ?>')">ดึงสถานะกลับมาแก้ไข</button>
	<?php endif; ?>

	<?php if($doc->status != 0) : ?>
				<button type="button" class="btn btn-sm btn-info" onclick="printReturn()"><i class="fa fa-print"></i> Print</button>
				<?php if($doc->status != 2) : ?>
				<button type="button" class="btn btn-sm btn-info" onclick="printWmsReturn()"><i class="fa fa-print"></i> Print</button>
			<?php endif; ?>
	<?php endif; ?>
      </p>
    </div>
</div>
<hr />


<div class="row">
    <div class="col-sm-1 col-1-harf col-xs-6 padding-5">
    	<label>Doc No.</label>
        <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
    </div>
		<div class="col-sm-1 col-1-harf col-xs-6 padding-5">
    	<label>Date</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo thai_date($doc->date_add, FALSE); ?>" disabled/>
    </div>
		<div class="col-sm-1 col-1-harf col-xs-6 padding-5">
			<label>Customer</label>
			<input type="text" class="form-control input-sm text-center" value="<?php echo $doc->customer_code; ?>" disabled />
		</div>
		<div class="col-sm-5 col-xs-12 padding-5">
			<label class="not-show">ลูกค้า</label>
			<input type="text" class="form-control input-sm" value="<?php echo $doc->customer_name; ?>" disabled/>
		</div>
		<div class="col-sm-1 col-1-harf col-xs-6 padding-5">
			<label>Invoice[SAP]</label>
			<input type="text" class="form-control input-sm text-center" value="<?php echo $doc->invoice; ?>" disabled />
		</div>
		<div class="col-sm-1 col-xs-6 padding-5">
			<label>GP(%)</label>
			<input type="number" class="form-control input-sm text-center" value="<?php echo $doc->gp; ?>" disabled />
		</div>

		<div class="col-sm-6 col-xs-12 padding-5">
			<label>From location(consignment)</label>
			<input type="text" class="form-control input-sm" value="<?php echo $doc->from_zone_name; ?>" disabled />
		</div>

		<div class="col-sm-6 col-xs-12 padding-5">
			<label>To location(receive)</label>
			<input type="text" class="form-control input-sm" value="<?php echo $doc->zone_name; ?>" disabled />
		</div>
		<div class="col-sm-1 col-1-harf col-xs-6 padding-5">
			<label>WMS</label>
			<select class="form-control input-sm" disabled>
				<option value="0" <?php echo is_selected("0", $doc->is_wms); ?>>Warrix</option>
				<!--<option value="1" <?php echo is_selected("1", $doc->is_wms); ?>>Pioneer</option>-->
			</select>
		</div>
		<div class="col-sm-1 col-1-harf col-xs-6 padding-5">
			<label>Interface</label>
			<select class="form-control input-sm" disabled>
				<option value="1" <?php echo is_selected("1", $doc->is_api); ?>>ส่ง</option>
				<option value="0" <?php echo is_selected("0", $doc->is_api); ?>>ไม่ส่ง</option>
			</select>
		</div>
    <div class="col-sm-7 col-7-harf col-xs-12 padding-5">
    	<label>Remark</label>
        <input type="text" class="form-control input-sm" value="<?php echo $doc->remark; ?>" disabled />
    </div>
		<div class="col-sm-1 col-1-harf col-xs-6 padding-5">
			<label>SAP NO.</label>
			<input type="text" class="form-control input-sm text-center" value="<?php echo $doc->inv_code; ?>" disabled>
		</div>
</div>

<input type="hidden" id="return_code" value="<?php echo $doc->code; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $doc->customer_code; ?>" />
<input type="hidden" name="warehouse_code" id="warehouse_code" value="<?php echo $doc->warehouse_code; ?>"/>
<input type="hidden" name="zone_code" id="zone_code" value="<?php echo $doc->zone_code; ?>" />

<hr class="margin-top-15 margin-bottom-15"/>
<?php
if($doc->status == 2)
{
  $this->load->view('cancle_watermark');
}

if($doc->status == 3)
{
  $this->load->view('on_process_watermark');
}
?>
<div class="row">
	<div class="col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped border-1" style="min-width:900px;">
			<thead>
				<tr>
					<th class="width-5 text-center">#</th>
					<th class="15">Item code</th>
					<th class="">Description</th>
					<th class="width-10 text-center">Inovice</th>
					<th class="width-10 text-right">Price</th>
					<th class="width-10 text-right">GP</th>
					<th class="width-10 text-right">Returned Qty</th>
					<th class="width-10 text-right">Received Qty</th>
					<th class="width-10 text-right">Amount</th>
				</tr>
			</thead>
			<tbody id="detail-table">
<?php if(!empty($details)) : ?>
<?php  $no = 1; ?>
<?php  $total_qty = 0; ?>
<?php  $total_receive = 0; ?>
<?php  $total_amount = 0; ?>
<?php  foreach($details as $rs) : ?>
	<?php $color = $rs->qty == $rs->receive_qty ? "" : "color:red !important"; ?>
				<tr style="<?php echo $color; ?>">
					<td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->product_code; ?></td>
					<td class="middle"><?php echo $rs->product_name; ?></td>
					<td class="middle text-center"><?php echo $rs->invoice_code; ?></td>
					<td class="middle text-right"><?php echo number($rs->price, 2); ?></td>
					<td class="middle text-right"><?php echo $rs->discount_percent; ?> %</td>
					<td class="middle text-right"><?php echo round($rs->qty,2); ?></td>
					<td class="middle text-right"><?php echo round($rs->receive_qty,2); ?></td>
					<td class="middle text-right"><?php echo number($rs->amount,2); ?></td>
				</tr>
<?php
				$no++;
				$total_qty += $rs->qty;
				$total_receive += $rs->receive_qty;
				$total_amount += $rs->amount;
?>
<?php  endforeach; ?>
				<tr>
					<td colspan="6" class="middle text-right">รวม</td>
					<td class="middle text-right" id="total-qty"><?php echo number($total_qty); ?></td>
					<td class="middle text-right" id="total-qty"><?php echo number($total_receive); ?></td>
					<td class="middle text-right" id="total-amount"><?php echo number($total_amount, 2); ?></td>
				</tr>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>


<script src="<?php echo base_url(); ?>scripts/inventory/return_consignment/return_consignment.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/return_consignment/return_consignment_add.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
