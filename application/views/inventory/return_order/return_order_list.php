<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title title-xs"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
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
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
      <label>Doc No.</label>
      <input type="text" class="form-control input-sm text-center search" name="code" value="<?php echo $code; ?>" />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
      <label>Invoice</label>
      <input type="text" class="form-control input-sm text-center search" name="invoice" value="<?php echo $invoice; ?>" />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
      <label>Customer</label>
      <input type="text" class="form-control input-sm text-center search" name="customer_code" value="<?php echo $customer_code; ?>" />
    </div>
		<div class="col-lg-3 col-md-4-harf col-sm-4-harf col-xs-8 padding-5">
			<label>Location</label>
			<input type="text" class="form-control input-sm padding-5" name="zone" value="<?php echo $zone; ?>" />
		</div>

    <div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
      <label>Acception</label>
  		<select name="must_accept" class="form-control input-sm" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected('0', $must_accept); ?>>No need to accept</option>
  			<option value="1" <?php echo is_selected('1', $must_accept); ?>>Need accept</option>
  		</select>
    </div>

    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
      <label>Status</label>
      <select class="form-control input-sm" name="status" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected('0', $status); ?>>Draft</option>
  			<option value="1" <?php echo is_selected('1', $status); ?>>Success</option>
  			<option value="2" <?php echo is_selected('2', $status); ?>>Cancelled</option>
				<option value="3" <?php echo is_selected('3', $status); ?>>WMS Process</option>
        <option value="4" <?php echo is_selected('4', $status); ?>>Waiting for acceptance</option>
        <option value="5" <?php echo is_selected('5', $status); ?>>Expired</option>
  		</select>
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
      <label>Approval</label>
      <select class="form-control input-sm" name="approve" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected($approve, '0'); ?>>Pending</option>
  			<option value="1" <?php echo is_selected($approve, '1'); ?>>Approved</option>
  		</select>
    </div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5 hide">
      <label>WMS</label>
      <select class="form-control input-sm" name="api" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected($api, '0'); ?>>ไม่ส่ง</option>
  			<option value="1" <?php echo is_selected($api, '1'); ?>>ปกติ</option>
  		</select>
    </div>
    <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-6 padding-5">
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
  			<option value="0" <?php echo is_selected('0', $sap); ?>>Pending</option>
  			<option value="1" <?php echo is_selected('1', $sap); ?>>Success</option>
  		</select>
    </div>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">btn</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
    </div>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">btn</label>
      <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Clear</button>
    </div>
  </div>
</form>
<hr class="margin-top-15 padding-5"/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <p class="pull-right top-p">
      Status : <span class="green bold">SC</span> = Success,&nbsp;
      <span class="grey blod">DF</span> = Draft,&nbsp;
      <span class="blue blod">AP</span> = Waiting for approval,&nbsp;
      <span class="red blod">CN</span> = Cancelled, &nbsp;
      <span class="orange blod">WC</span> = Waiting for acceptance &nbsp;
      <span class="dark blod">EXP</span> = Expired
    </p>
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:1300px;">
      <thead>
        <tr>
          <th class="fix-width-100"></th>
          <th class="fix-width-40 text-center">#</th>
          <th class="fix-width-100 text-center">Date</th>
          <th class="fix-width-120">Document No</th>
          <th class="fix-width-100">Invoice No</th>
          <th class="min-width-200">Customer</th>
					<th class="fix-width-150">Location</th>
          <th class="fix-width-80 text-right">Qty</th>
          <th class="fix-width-100 text-right">Amount</th>
          <th class="fix-width-60 text-center">Status</th>
          <th class="fix-width-60 text-center">Approval</th>
          <th class="fix-width-150">User</th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($docs)) : ?>
<?php   $no = $this->uri->segment($this->segment) + 1; ?>
<?php   foreach($docs as $rs) : ?>
          <tr class="font-size-12" id="row-<?php $rs->code; ?>" style="<?php echo statusBackgroundColor($rs->is_expire, $rs->status, $rs->is_approve); ?>">
            <td class="middle">
              <button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
          <?php if($this->pm->can_edit && $rs->status == 0 && $rs->is_expire == 0) : ?>
              <button type="button" class="btn btn-minier btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
          <?php endif; ?>
          <?php if($this->pm->can_delete && $rs->status != 2) : ?>
              <button type="button" class="btn btn-minier btn-danger" onclick="goDelete('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
          <?php endif; ?>
            </td>
            <td class="middle text-center no"><?php echo $no; ?></td>
            <td class="middle text-center"><?php echo thai_date($rs->date_add, FALSE); ?></td>
            <td class="middle"><?php echo $rs->code; ?></td>
            <td class="middle"><?php echo $rs->invoice; ?></td>
            <td class="middle"><?php echo $rs->customer_name; ?></td>
						<td class="middle"><?php echo $rs->zone_code; ?></td>
            <td class="middle text-right"><?php echo number($rs->qty); ?></td>
            <td class="middle text-right"><?php echo number($rs->amount, 2); ?></td>
            <td class="middle text-center">
              <?php if($rs->is_expire == 1) : ?>
                <span class="dark">EXP</span>
              <?php else : ?>
                <?php if($rs->status == 2) : ?>
                  <span class="red">CN</span>
                <?php endif;?>
                <?php if($rs->status == 0) : ?>
                  <span class="blue">DF</span>
                <?php endif; ?>
                <?php if($rs->status == 1) : ?>
                  <span class="blue">AP</span>
                <?php endif; ?>
                <?php if($rs->status == 4) : ?>
                  <span class="orange">WC</span>
                <?php endif; ?>
              <?php endif; ?>
            </td>
            <td class="middle text-center">
              <?php echo is_active($rs->is_approve); ?>
            </td>
            <td class="middle"><?php echo $rs->display_name; ?></td>
          </tr>
<?php     $no++; ?>
<?php   endforeach; ?>
<?php else : ?>
        <tr>
          <td colspan="11" class="text-center">--- No item found ---</td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->load->view('cancle_modal'); ?>

<script src="<?php echo base_url(); ?>scripts/inventory/return_order/return_order.js?v=<?php echo date('Ymd');?>"></script>
<?php $this->load->view('include/footer'); ?>
