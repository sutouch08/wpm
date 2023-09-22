<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-sm-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-sm-6 padding-5">
    <p class="pull-right top-p">
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> Add New</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
  <div class="row">
    <div class="col-sm-2 padding-5">
      <label>Doc No.</label>
      <input type="text" class="form-control input-sm text-center search" name="code" value="<?php echo $code; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
      <label>Customer</label>
      <input type="text" class="form-control input-sm text-center search" name="customer" value="<?php echo $customer; ?>" />
    </div>
		<div class="col-sm-2 padding-5">
      <label>Location</label>
      <input type="text" class="form-control input-sm text-center search" name="zone" value="<?php echo $zone; ?>" />
    </div>
    <div class="col-sm-2 padding-5 hide">
    	<label>ช่องทางการรับ</label>
			<select class="form-control input-sm" name="is_wms" onchange="getSearch()">
				<option value="all">All</option>
				<option value="1" <?php echo is_selected('1', $is_wms); ?>>WMS</option>
				<option value="0" <?php echo is_selected('0', $is_wms); ?>>Warrix</option>
			</select>
    </div>
    <div class="col-sm-2 padding-5">
      <label>Status</label>
      <select class="form-control input-sm" name="status" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php echo is_selected('0', $status); ?>>Draft</option>
  			<option value="1" <?php echo is_selected('1', $status); ?>>Saved</option>
  			<option value="2" <?php echo is_selected('2', $status); ?>>Cancelled</option>
        <!--<option value="3" <?php echo is_selected('3', $status); ?>>WMS Process</option> -->
  		</select>
    </div>

    <div class="col-sm-2 padding-5">
      <label>Linked</label>
      <select class="form-control input-sm" name="valid" onchange="getSearch()">
  			<option value="all">All</option>
  			<option value="0" <?php if($valid == '0'){ echo 'selected'; } ?>>No</option>
  			<option value="1" <?php echo is_selected(1, $valid); ?>>Yes</option>
  		</select>
    </div>

    <div class="col-sm-2 padding-5">
      <label>Reference</label>
      <input type="text" class="form-control input-sm text-center search" name="consign_code" value="<?php echo $consign_code; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
      <label>Date</label>
      <div class="input-daterange input-group">
        <input type="text" class="form-control input-sm width-50 from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
        <input type="text" class="form-control input-sm width-50" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
      </div>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">btn</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">btn</label>
      <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
  </div>
</form>
<hr class="margin-top-15"/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
  <div class="col-sm-12 padding-5">
    <p class="pull-right top-p">
      Status : <span class="green">OK</span> = Closed,&nbsp;
      <span class="blue">NC</span> = Open,&nbsp;
      <span class="red">NS</span> = Draft,&nbsp;
			<span class="purple hide">OP</span> = รอรับที่ WMS,&nbsp;
      <span class="red">CN</span> = Cancelled
    </p>
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">#</th>
          <th class="width-10 text-center">Date</th>
          <th class="width-10">Document No</th>
          <th class="width-25">Customer</th>
          <th class="width-25">Location</th>
          <th class="width-10">Reference</th>
          <th class="width-5 text-center">Status</th>
          <th class=""></th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($docs)) : ?>
<?php   $no = $this->uri->segment(4) + 1; ?>
<?php   foreach($docs as $rs) : ?>
          <tr class="font-size-12" id="row-<?php $rs->code; ?>">
            <td class="middle text-center no"><?php echo $no; ?></td>
            <td class="middle text-center"><?php echo thai_date($rs->date_add, FALSE); ?></td>
            <td class="middle"><?php echo $rs->code; ?></td>
            <td class="middle"><?php echo $rs->customer_name; ?></td>
            <td class="middle"><?php echo $rs->zone_name; ?></td>
            <td class="middle"><?php echo $rs->consign_code; ?></td>
            <td class="middle text-center">
              <?php if($rs->status == 2) : ?>
                <span class="red">CN</span>
							<?php elseif($rs->status == 3) : ?>
                <span class="purple">OP</span>
              <?php elseif($rs->status == 0) : ?>
                <span class="red">NS</span>
              <?php elseif($rs->valid == 0) : ?>
                <span class="blue">NC</span>
              <?php elseif($rs->valid == 1) : ?>
                <span class="green">OK</span>
              <?php endif; ?>
            </td>
            <td class="middle text-right">
              <button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
          <?php if($this->pm->can_edit && $rs->status == 0 && $rs->valid == 0) : ?>
              <button type="button" class="btn btn-minier btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
          <?php endif; ?>
          <?php if($this->pm->can_delete && $rs->status != 2 && $rs->valid == 0) : ?>
              <button type="button" class="btn btn-minier btn-danger" onclick="goDelete('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
          <?php endif; ?>
            </td>
          </tr>
<?php     $no++; ?>
<?php   endforeach; ?>
<?php else : ?>
        <tr>
          <td colspan="8" class="text-center">
            --- Not found ---
          </td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/consign_check/consign_check.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
