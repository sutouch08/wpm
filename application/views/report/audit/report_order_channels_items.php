<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-8 col-md-8 col-sm-8 padding-5 hidden-xs">
    <h3 class="title">
      <i class="fa fa-bar-chart"></i>
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h4 class="title-xs"><i class="fa fa-bar-chart"></i> <?php echo $this->title; ?></h4>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Export</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5 hidden-print"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block">Channels</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-channels-all" onclick="toggleAllChannels(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-channels-range" onclick="toggleAllChannels(0)">Select</button>
    </div>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block">Payment</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pay-all" onclick="toggleAllPayment(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-pay-range" onclick="toggleAllPayment(0)">Select</button>
    </div>
  </div>


  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block">Warehouse</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-wh-all" onclick="toggleAllWarehouse(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-wh-range" onclick="toggleAllWarehouse(0)">Select</button>
    </div>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block">Proeucts</label>
    <div class="btn-group width-100" style="height:30px;">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pd-all" onclick="toggleAllProduct(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-pd-range" onclick="toggleAllProduct(0)">Select</button>
    </div>
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block not-show">From</label>
    <input type="text" class="form-control input-sm text-center" id="pdFrom" name="pdFrom" placeholder="From" disabled>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label class="display-block not-show">To</label>
    <input type="text" class="form-control input-sm text-center" id="pdTo" name="pdTo" placeholder="To" disabled>
  </div>

	<div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-6 padding-5">
    <label>Date</label>
    <div class="input-daterange input-group width-100">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="fromDate" id="fromDate" placeholder="From" required />
      <input type="text" class="form-control input-sm width-50 text-center" name="toDate" id="toDate" placeholder="To" required/>
    </div>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-1" class="btn btn-xs btn-block " onclick="toggleState(1)">Pending</button>
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1 col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-2" class="btn btn-xs btn-block" onclick="toggleState(2)">Waiting for payment</button>
	</div>
	<div class="col-lg-1-harf col-md-1 col-sm-1 col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-3" class="btn btn-xs btn-block" onclick="toggleState(3)">Waiting to pick</button>
	</div>
	<div class="col-lg-1-harf col-md-1 col-sm-1 col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-4" class="btn btn-xs btn-block" onclick="toggleState(4)">Picking</button>
	</div>
	<div class="col-lg-1-harf col-md-1 col-sm-1 col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-5" class="btn btn-xs btn-block" onclick="toggleState(5)">Waiting to pack</button>
	</div>
	<div class="col-lg-1-harf col-md-1 col-sm-1 col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-6" class="btn btn-xs btn-block" onclick="toggleState(6)">Packing</button>
	</div>
	<div class="col-lg-1-harf col-md-1 col-sm-1 col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-7" class="btn btn-xs btn-block" onclick="toggleState(7)">Ready to ship</button>
	</div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">x</label>
		<button type="button" id="btn-state-8" class="btn btn-xs btn-block" onclick="toggleState(8)">Shipped</button>
	</div>
</div>



  <input type="hidden" id="allChannels" name="allChannels" value="1">
  <input type="hidden" id="allPayments" name="allPayments" value="1">
  <input type="hidden" id="allProducts" name="allProducts" value="1">
  <input type="hidden" id="allWarehouse" name="allWarehouse" value="1">
  <input type="hidden" id="state-1" name="state[1]" value="0">
  <input type="hidden" id="state-2" name="state[2]" value="0">
  <input type="hidden" id="state-3" name="state[3]" value="0">
  <input type="hidden" id="state-4" name="state[4]" value="0">
  <input type="hidden" id="state-5" name="state[5]" value="0">
  <input type="hidden" id="state-6" name="state[6]" value="0">
  <input type="hidden" id="state-7" name="state[7]" value="0">
  <input type="hidden" id="state-8" name="state[8]" value="0">
	<input type="hidden" id="token" name="token"  value="<?php echo uniqid(); ?>">


<div class="modal fade" id="channels-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='title'>List of sales channels</h4>
            </div>
            <div class='modal-body' style="padding:0px;">
        <?php if(!empty($channels_list)) : ?>
          <?php foreach($channels_list as $rs) : ?>
            <div class="col-sm-12">
              <label>
                <input type="checkbox" class="chk" name="channels[]" value="<?php echo $rs->code; ?>" style="margin-right:10px;" />
                <span class="lbl">
                <?php echo $rs->name; ?>
                </span>
              </label>

            </div>
          <?php endforeach; ?>
        <?php endif;?>

        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payment-modal" tabindex='-1' role='dialog' aria-labelledby='payment-modal' aria-hidden='true'>
	<div class='modal-dialog' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='title'>List of payment channels</h4>
            </div>
            <div class='modal-body' style="padding:0px;">
        <?php if(!empty($payment_list)) : ?>
          <?php foreach($payment_list as $rs) : ?>
            <div class="col-sm-12">
              <label>
                <input type="checkbox" class="pay-chk" name="payments[]" value="<?php echo $rs->code; ?>" style="margin-right:10px;" />
                <span class="lbl">
                <?php echo $rs->name; ?>
                </span>
              </label>
            </div>
          <?php endforeach; ?>
        <?php endif;?>

        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="warehouse-modal" tabindex='-1' role='dialog' aria-labelledby='warehouse-modal' aria-hidden='true'>
	<div class='modal-dialog' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='title'>List of warehouse</h4>
            </div>
            <div class='modal-body' style="padding:0px;">
        <?php if(!empty($warehouse_list)) : ?>
          <?php foreach($warehouse_list as $rs) : ?>
            <div class="col-sm-12">
              <label>
                <input type="checkbox" class="wh-chk" name="warehouse[]" value="<?php echo $rs->code; ?>" style="margin-right:10px;" />
                <span class="lbl">
                <?php echo $rs->name; ?>
                </span>
              </label>
            </div>
          <?php endforeach; ?>
        <?php endif;?>

        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>OK</button>
            </div>
        </div>
    </div>
</div>
<hr>
</form>

<div class="row">
  <div class="col-sm-12" id="result">
    <blockquote>
      <p class="lead" style="color:#CCC;">
        The report will not show shipping information on screen.
				Because the data has many columns that are too long to display on the screen.
				All If you want all data, export the data to an Excel file instead.
      </p>
    </blockquote>
  </div>
</div>


<script src="<?php echo base_url(); ?>scripts/report/audit/order_channels_items.js"></script>
<?php $this->load->view('include/footer'); ?>
