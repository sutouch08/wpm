<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h4 class="title"><?php echo $this->title; ?></h4>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <p class="pull-right top-p">
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> New</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Doc No.</label>
      <input type="text" class="form-control input-sm text-center search" name="code" value="<?php echo $code; ?>" />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Invlice No.</label>
      <input type="text" class="form-control input-sm text-center search" name="invoice" value="<?php echo $invoice; ?>" />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Customer</label>
      <input type="text" class="form-control input-sm text-center search" name="customer_code" value="<?php echo $customer_code; ?>" />
    </div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Status</label>
      <select class="form-control input-sm" name="status" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected('0', $status); ?>>Draft</option>
  			<option value="1" <?php echo is_selected('1', $status); ?>>Saved</option>
  			<option value="2" <?php echo is_selected('2', $status); ?>>Cancelled</option>
  		</select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Approval</label>
      <select class="form-control input-sm" name="approve" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected($approve, '0'); ?>>Pending</option>
  			<option value="1" <?php echo is_selected($approve, '1'); ?>>Approved</option>
  		</select>
    </div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>WMS</label>
			<select class="form-control input-sm" name="api" onchange="getSearch()">
				<option value="all">All</option>
				<option value="0" <?php echo is_selected("0", $api); ?>>Not Interface</option>
				<option value="1" <?php echo is_selected("1", $api); ?>>Interface</option>
			</select>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-2-harf col-xs-6 padding-5">
			<label>Consignment Whs</label>
			<select class="form-control input-sm" name="from_warehouse" onchange="getSearch()">
				<option value="all">All</option>
				<?php echo select_consignment_warehouse($from_warehouse); ?>
			</select>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-2-harf col-xs-6 padding-5">
			<label>Receive Whs</label>
			<select class="form-control input-sm" name="to_warehouse" onchange="getSearch()">
				<option value="all">All</option>
				<?php echo select_common_warehouse($to_warehouse); ?>
			</select>
		</div>

    <div class="col-lg-2-harf col-md-2-harf col-sm-3 col-xs-6 padding-5">
      <label>Date</label>
      <div class="input-daterange input-group">
        <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
        <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
      </div>
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
      <label>SAP</label>
  		<select name="sap" class="form-control input-sm" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected('0', $sap); ?>>No</option>
  			<option value="1" <?php echo is_selected('1', $sap); ?>>Yes</option>
  		</select>
    </div>

		<div class="divider-hidden visible-xs"></div>
    <div class="col-md-1 col-sm-1 col-xs-6 padding-5">
      <label class="display-block not-show hidden-xs">btn</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-6 padding-5">
      <label class="display-block not-show hidden-xs">btn</label>
      <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Clear</button>
    </div>
  </div>
</form>
<hr class="margin-top-15 padding-5"/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<p class="pull-right top-p padding-5">Status : Empty = normal,&nbsp;  <span class="blue">NC</span> = Draft,&nbsp;  <span class="purple">OP</span> = WMS process,&nbsp;  <span class="red">CN</span> = Cancelled</p>
  <div class="col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:1100px;">
      <thead>
        <tr>
          <th class="fix-width-60 text-center">#</th>
          <th class="fix-width-80 text-center">Date</th>
          <th class="fix-width-100">Document No</th>
          <th class="fix-width-80">Invlice No</th>
          <th class="fix-width-300">Customer</th>
					<th class="fix-width-120">Location (Receive)</th>
          <th class="fix-width-80 text-right">Qty</th>
          <th class="fix-width-100 text-right">Amount</th>
          <th class="fix-width-60 text-center">Status</th>
          <th class="fix-width-60 text-center">Approval</th>
					<th class="fix-width-60 text-center">WMS</th>
          <th class="min-width-100"></th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($docs)) : ?>
<?php   $no = $this->uri->segment(4) + 1; ?>
<?php   foreach($docs as $rs) : ?>
          <tr class="font-size-12" id="row-<?php $rs->code; ?>">
            <td class="middle hide-text text-center no"><?php echo $no; ?></td>
            <td class="middle hide-text text-center"><?php echo thai_date($rs->date_add, FALSE); ?></td>
            <td class="middle hide-text"><?php echo $rs->code; ?></td>
            <td class="middle"><?php echo $rs->invoice; ?></td>
            <td class="middle hide-text" style="max-width:400px;"><?php echo $rs->customer_name; ?></td>
						<td class="middle hide-text"><?php echo $rs->zone_code; ?></td>
            <td class="middle text-right"><?php echo number($rs->qty); ?></td>
            <td class="middle text-right"><?php echo number($rs->amount, 2); ?></td>
            <td class="middle text-center">
							<?php if($rs->status == 3) : ?>
								<span class="purple">OP</span>
							<?php endif; ?>
              <?php if($rs->status == 2) : ?>
                <span class="red">CN</span>
              <?php endif;?>
              <?php if($rs->status == 0) : ?>
                <span class="blue">NC</span>
              <?php endif; ?>
            </td>
            <td class="middle text-center">
              <?php echo is_active($rs->is_approve); ?>
            </td>
						<td class="middle text-center">
              <?php echo ($rs->is_wms && $rs->is_api ? 'Y' : 'N'); ?>
            </td>
            <td class="middle text-right">
              <button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
          <?php if($this->pm->can_edit && $rs->status == 0) : ?>
              <button type="button" class="btn btn-minier btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
          <?php endif; ?>
          <?php if($this->pm->can_delete && $rs->status != 2) : ?>
              <button type="button" class="btn btn-minier btn-danger" onclick="goDelete('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
          <?php endif; ?>
            </td>
          </tr>
<?php     $no++; ?>
<?php   endforeach; ?>
<?php else : ?>
        <tr>
          <td colspan="10" class="text-center">
            --- Not found ---
          </td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/return_consignment/return_consignment.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
