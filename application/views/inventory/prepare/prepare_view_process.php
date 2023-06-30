<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning btn-100" onclick="goBack()"><i class="fa fa-arrow-left"></i> Pick List</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>Document No.</label>
	    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
	  </div>

	  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>Customer</label>
	    <input type="text" class="form-control input-sm search" name="customer" value="<?php echo $customer; ?>" />
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>User</label>
	    <input type="text" class="form-control input-sm search" name="display_name" value="<?php echo $display_name; ?>" />
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>Channels</label>
			<select class="form-control input-sm" name="channels" onchange="getSearch()">
	      <option value="">All</option>
	      <?php echo select_channels($channels); ?>
	    </select>
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>Online/Offline</label>
			<select class="form-control input-sm" name="is_online" onchange="getSearch()">
	      <option value="2">All</option>
	      <option value="1" <?php echo is_selected($is_online, '1'); ?>>Online</option>
				<option value="0" <?php echo is_selected($is_online, '0'); ?>>Offline</option>
	    </select>
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>Type</label>
			<select class="form-control input-sm" name="role" onchange="getSearch()">
	      <option value="all">All</option>
	      <option value="S" <?php echo is_selected($role, 'S'); ?>>Sales Order</option>
				<option value="C" <?php echo is_selected($role, 'C'); ?>>Consignment(Inv)</option>
				<option value="N" <?php echo is_selected($role, 'N'); ?>>Consignment(TR)</option>
				<option value="P" <?php echo is_selected($role, 'P'); ?>>Sponsor (External)</option>
				<option value="U" <?php echo is_selected($role, 'U'); ?>>Sponsor (Internal)</option>
				<option value="Q" <?php echo is_selected($role, 'Q'); ?>>Transform (Stock)</option>
				<option value="T" <?php echo is_selected($role, 'T'); ?>>Transform (Sell)</option>
				<option value="L" <?php echo is_selected($role, 'L'); ?>>Lend</option>
	    </select>
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
	    <label>Payments</label>
			<select class="form-control input-sm" name="payment" onchange="getSearch()">
				<option value="">All</option>
				<?php echo select_payment_method($payment); ?>
			</select>
	  </div>

		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
	    <label>Doc Date</label>
	    <div class="input-daterange input-group">
	      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
	      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
	    </div>
	  </div>

		<div class="col-lg-3 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
			<label>Warehouse</label>
			<select class="form-control input-sm" name="warehouse" onchange="getSearch()">
				<option value="all">All</option>
				<?php echo select_sell_warehouse($warehouse); ?>
			</select>
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
			<label class="display-block not-show">btn</label>
			<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
			<label class="display-block not-show">btn</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearProcessFilter()">Clear</button>
		</div>
	</div>
<hr class="margin-top-15">
<input type="hidden" name="order_by" id="order_by" value="<?php echo $order_by; ?>">
<input type="hidden" name="sort_by" id="sort_by" value="<?php echo $sort_by; ?>">
</form>
<?php echo $this->pagination->create_links(); ?>
<?php $sort_date = $order_by == '' ? "" : ($order_by === 'date_add' ? ($sort_by === 'DESC' ? 'sorting_desc' : 'sorting_asc') : ''); ?>
<?php $sort_code = $order_by == '' ? '' : ($order_by === 'code' ? ($sort_by === 'DESC' ? 'sorting_desc' : 'sorting_asc') : ''); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover border-1" style="min-width:900px;">
			<thead>
				<tr>
					<th class="fix-width-60 middle"></th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-100 middle text-center sorting <?php echo $sort_date; ?>" id="sort_date_add" onclick="sort('date_add')">Date</th>
					<th class="fix-width-120 middle sorting <?php echo $sort_code; ?>" id="sort_code" onclick="sort('code')">Document No.</th>
					<th class="fix-width-100 middle text-center">Qty.</th>
          <th class="fix-width-150 middle">Channels</th>
					<th class="fix-width-150 middle">User</th>
					<th class="min-width-150 middle">Cust./Emp.</th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($orders)) : ?>
          <?php $no = $this->uri->segment(4) + 1; ?>
          <?php foreach($orders as $rs) : ?>
            <?php $customer_name = (!empty($rs->customer_ref)) ? $rs->customer_ref : $rs->customer_name; ?>
            <tr id="row-<?php echo $rs->code; ?>">
							<td class="middle text-right no-wrap">
								<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
									<button type="button" class="btn btn-xs btn-info" onClick="goPrepare('<?php echo $rs->code; ?>')">Choose</button>
								<?php endif; ?>
								<?php if($this->pm->can_delete) : ?>
									<!--
									<button type="button" class="btn btn-minier btn-warning" onClick="pullBack('<?php echo $rs->code; ?>')">Roll back</button>
								-->
								<?php endif; ?>
							</td>
              <td class="middle text-center no"><?php echo $no; ?></td>
							<td class="middle text-center"><?php echo thai_date($rs->date_add, FALSE,'/'); ?></td>
              <td class="middle">
								<?php echo $rs->code; ?>
								<?php echo (empty($rs->reference) ? "" : "[".$rs->reference."]"); ?>
							</td>
							<td class="middle text-center"><?php echo number($rs->qty); ?></td>
							<td class="middle"><?php echo $rs->channels_name; ?></td>
							<td class="middle"><?php echo $rs->display_name;; ?></td>
              <td class="middle">
								<?php if($rs->role == 'L' OR $rs->role == 'R') : ?>
									<?php echo $rs->empName; ?>
								<?php else : ?>
									<?php echo $customer_name; ?>
								<?php endif; ?>
              </td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="8" class="text-center">--- No Data ---</td>
          </tr>
        <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/prepare/prepare.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/prepare/prepare_list.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
