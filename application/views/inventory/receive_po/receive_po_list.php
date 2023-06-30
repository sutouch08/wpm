<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
  	</h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 padding-5">
  	<p class="pull-right top-p">
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> Add new</button>
      <?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>PO No.</label>
    <input type="text" class="form-control input-sm search" name="po" value="<?php echo $po; ?>" />
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Invoice No.</label>
    <input type="text" class="form-control input-sm search" name="invoice" value="<?php echo $invoice; ?>" />
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Vendor</label>
		<input type="text" class="form-control input-sm search" name="vendor" value="<?php echo $vendor; ?>" />
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>User</label>
		<input type="text" class="form-control input-sm search" name="user" value="<?php echo $user; ?>" />
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5 hide">
    <label>Receive at</label>
		<select name="is_wms" class="form-control input-sm" onchange="getSearch()">
			<option value="all">All</option>
			<option value="0" <?php echo is_selected('0', $is_wms); ?>>Warrix</option>
			<option value="1" <?php echo is_selected('1', $is_wms); ?>>WMS</option>
		</select>
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Status</label>
		<select name="status" class="form-control input-sm" onchange="getSearch()">
			<option value="all">All</option>
			<option value="0" <?php echo is_selected('0', $status); ?>>Draft</option>
			<option value="1" <?php echo is_selected('1', $status); ?>>Saved</option>
			<option value="2" <?php echo is_selected('2', $status); ?>>Cancelled</option>
			<option value="3" <?php echo is_selected('3', $status); ?>>WMS Process</option>
			<option value="4" <?php echo is_selected('4', $status); ?>>Waiting for acception</option>
			<option value="5" <?php echo is_selected('5', $status); ?>>Expired</option>
		</select>
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Acception</label>
		<select name="must_accept" class="form-control input-sm" onchange="getSearch()">
			<option value="all">All</option>
			<option value="1" <?php echo is_selected('1', $must_accept); ?>>Must accept</option>
			<option value="0" <?php echo is_selected('0', $must_accept); ?>>No need to accept</option>
		</select>
  </div>


	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>SAP</label>
		<select name="sap" class="form-control input-sm" onchange="getSearch()">
			<option value="all">All</option>
			<option value="0" <?php echo is_selected('0', $sap); ?>>Pending</option>
			<option value="1" <?php echo is_selected('1', $sap); ?>>Success</option>
		</select>
  </div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Date</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
    </div>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block">Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Clear</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
  <div class="col-sm-12 padding-5">
    <p class="pull-right">
      Status : Blank = Saved, &nbsp;
      <span class="red">CN</span> = Cancel, &nbsp;
      <span class="blue">DF</span> = Draft, &nbsp;
			<span class="purple">OP</span> = WMS Process, &nbsp;
			<span class="orange">WC</span> = Waiting for acception, &nbsp;
			<span class="dark">EXP</span> = Expired &nbsp;
    </p>
  </div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover border-1" style="min-width:1100px;">
			<thead>
				<tr>
					<th class="fix-width-100"></th>
					<th class="fix-width-40 middle text-center">Status</th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-100 middle text-center">Date</th>
					<th class="fix-width-100 middle">Doc No.</th>
					<th class="fix-width-150 middle">Invoice No.</th>
					<th class="fix-width-100 middle">PO No.</th>
					<th class="min-width-250 middle">Vendor</th>
					<th class="fix-width-100 middle text-center">Qty</th>
					<th class="fix-width-150 middle">User</th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($document)) : ?>
          <?php $no = $this->uri->segment(4) + 1; ?>
          <?php foreach($document as $rs) : ?>
            <tr id="row-<?php echo $rs->code; ?>" style="font-size:12px; <?php echo statusBackgroundColor($rs->is_expire, $rs->status); ?>">
							<td class="middle">
								<button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
								<?php if(($this->pm->can_edit OR $this->pm->can_delete) && $rs->status == 0) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
									<button type="button" class="btn btn-minier btn-danger" onclick="goDelete('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
								<?php endif; ?>
							</td>
							<td class="middle text-center">
								<?php if($rs->is_expire) : ?>
									<span class="dark">EXP</span>
								<?php else : ?>
									<?php if($rs->status == 0 ) : ?>
										<span class="blue"><strong>DF</strong></span>
									<?php endif; ?>
									<?php if($rs->status == 2) : ?>
										<span class="red"><strong>CN</strong></span>
									<?php endif; ?>
									<?php if($rs->status == 3 ) : ?>
										<span class="purple"><strong>OP</strong></span>
									<?php endif; ?>
									<?php if($rs->status == 4) : ?>
										<span class="orange"><strong>WC</strong></span>
									<?php endif; ?>
								<?php endif; ?>
							</td>
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle text-center"><?php echo thai_date($rs->date_add, FALSE, '/'); ?></td>
              <td class="middle"><?php echo $rs->code; ?></td>
              <td class="middle"><?php echo $rs->invoice_code; ?></td>
              <td class="middle"><?php echo $rs->po_code; ?></td>
              <td class="middle"><?php echo $rs->vendor_name; ?></td>
              <td class="middle text-center"><?php echo number($rs->qty); ?></td>
							<td class="middle">
                <?php echo $rs->display_name; ?>
              </td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $this->load->view('cancle_modal'); ?>

<script src="<?php echo base_url(); ?>scripts/inventory/receive_po/receive_po.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
