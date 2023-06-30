<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
</div><!-- End Row -->
<hr/>

<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Document No</label>
    <input type="text" class="form-control input-sm search" name="order_code"  value="<?php echo $order_code; ?>" />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Item Code</label>
    <input type="text" class="form-control input-sm search" name="pd_code" value="<?php echo $pd_code; ?>" />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Bin Code</label>
    <input type="text" class="form-control input-sm search" name="zone_code" value="<?php echo $zone_code; ?>" />
  </div>

  <div class="col-lg-2-harf col-md-2-harf col-sm-3 col-xs-6 padding-5">
    <label>Date</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
    </div>

  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block">Search</button>
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Clear</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:1000px;">
      <tr>
				<?php if($this->pm->can_delete) : ?>
					<th class="fix-width-40"></th>
				<?php endif; ?>
        <th class="fix-width-40 text-center">#</th>
        <th class="fix-width-150">Date Time</th>
        <th class="fix-width-150">Document No.</th>
        <th class="fix-width-200">Item Code</th>
        <th class="fix-width-100">Qty.</th>
        <th class="fix-width-100">State</th>
    		<th class="min-width-100">Bin Location</th>
      </tr>
      <tbody>
    <?php if( !empty($data)) : ?>
    <?php $no = $this->uri->segment(4) + 1; ?>
    <?php foreach($data as $rs) : ?>
      <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
				<?php if($this->pm->can_delete) : ?>
					<td class="text-right">
						<button type="button" class="btn btn-minier btn-danger"
						onclick="deleteBuffer(<?php echo $rs->id;?>, '<?php echo $rs->order_code; ?>', '<?php echo $rs->product_code; ?>')">
						<i class="fa fa-trash"></i>
					</button>
				</td>
			<?php endif; ?>
        <td class="text-center no"><?php echo $no; ?></td>
        <td><?php echo thai_date($rs->date_upd, TRUE); ?></td>
        <td><?php echo $rs->order_code; ?></td>
        <td><?php echo $rs->product_code; ?></td>
        <td><?php echo number($rs->qty); ?></td>
    		<td><?php echo $rs->state_name; ?></td>
        <td> <?php echo $rs->zone_name; ?></td>
      </tr>
    <?php  $no++; ?>
    <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="8" class="text-center">--- ไม่พบข้อมูล ---</td>
      </tr>
    <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?php echo base_url(); ?>scripts/inventory/buffer/buffer.js"></script>

<?php $this->load->view('include/footer'); ?>
