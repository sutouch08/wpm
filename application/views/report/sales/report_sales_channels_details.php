<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-10 col-md-10 col-sm-10 padding-5 hidden-xs">
    <h3 class="title">
      <i class="fa fa-bar-chart"></i>
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
    <h3 class="title-xs">
      <i class="fa fa-bar-chart"></i>
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Export</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Sales Channels</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-channels-all" onclick="toggleAllChannels(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-channels-range" onclick="toggleAllChannels(0)">Select</button>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
    <label>Products</label>
    <input type="text" class="form-control input-sm text-center" id="pdFrom" name="pdFrom" placeholder="From">
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
    <label class="display-block not-show">End</label>
    <input type="text" class="form-control input-sm text-center" id="pdTo" name="pdTo" placeholder="To">
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
    <label class="display-block">Reference</label>
    <input type="text" class="form-control input-sm text-center" id="refCodeFrom" name="refCodeFrom" placeholder="From">
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
    <label class="display-block not-show">End</label>
    <input type="text" class="form-control input-sm text-center" id="refCodeTo" name="refCodeTo" placeholder="To">
  </div>


  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>Date</label>
    <div class="input-daterange input-group width-100">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="fromDate" id="fromDate" placeholder="From" required />
      <input type="text" class="form-control input-sm width-50 text-center" name="toDate" id="toDate" placeholder="To" required/>
    </div>
  </div>

  <input type="hidden" id="allChannels" name="allChannels" value="1">
	<input type="hidden" id="token" name="token"  value="<?php echo uniqid(); ?>">
</div>


<div class="modal fade" id="channels-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' id='modal' style="width:400px; max-width:95%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='title' id='modal_title'>List of sales channels</h4>
            </div>
            <div class='modal-body' id='modal_body' style="padding:0px;">
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


<script src="<?php echo base_url(); ?>scripts/report/sales/sales_channels_details.js"></script>
<?php $this->load->view('include/footer'); ?>
