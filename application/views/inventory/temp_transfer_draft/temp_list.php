<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-xs-12 visible-xs padding-5">
		<h3 class="title-xs"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-success" onclick="export_diff()">Export Difference</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Customer</label>
    <input type="text" class="form-control input-sm search" name="customer" value="<?php echo $customer; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Status</label>
    <select class="form-control input-sm" name="status" onchange="getSearch()">
      <option value="all">All</option>
      <option value="Y" <?php echo is_selected('Y', $status); ?>>Success</option>
			<option value="D" <?php echo is_selected('D', $status); ?>>Draft</option>
      <option value="N" <?php echo is_selected('N', $status); ?>>Pending</option>
      <option value="E" <?php echo is_selected('E', $status); ?>>Error</option>
    </select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Is Received</label>
    <select class="form-control input-sm" name="is_received" onchange="getSearch()">
      <option value="all">All</option>
      <option value="Y" <?php echo is_selected('Y', $is_received); ?>>Yes</option>
      <option value="N" <?php echo is_selected('N', $is_received); ?>>No</option>
    </select>
  </div>

	<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>Date</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
    </div>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:1360px;">
      <thead>
        <tr>
					<th class="fix-width-80"></th>
          <th class="fix-width-40 text-center">#</th>
          <th class="fix-width-100 text-center">Date</th>
          <th class="fix-width-120">Document No</th>
          <th class="fix-width-120">Customer Code</th>
          <th class="fix-width-250">Customer Name</th>
          <th class="fix-width-150">Temp Date</th>
          <th class="fix-width-150">SAP Date</th>
					<th class="fix-width-60 text-center">Is Received</th>
					<th class="fix-width-150">Received Date</th>
          <th class="fix-width-60 text-center">Status</th>
					<th class="min-width-100">Remark</th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($orders))  : ?>
<?php $no = $this->uri->segment(4) + 1; ?>
<?php   foreach($orders as $rs)  : ?>

        <tr class="font-size-12">
					<td class="">
						<button type="button" class="btn btn-minier btn-info" onclick="get_detail('<?php echo $rs->U_ECOMNO; ?>')">
							<i class="fa fa-eye"></i>
						</button>
					</td>
          <td class="middle text-center"><?php echo $no; ?></td>

          <td class="middle text-center"><?php echo thai_date($rs->DocDate); ?></td>

          <td class="middle"><?php echo $rs->U_ECOMNO; ?></td>

          <td class="middle"><?php echo $rs->CardCode; ?></td>

          <td class="middle"><?php echo $rs->CardName; ?></td>

          <td class="middle" >
						<?php
						if(!empty($rs->F_E_CommerceDate))
						{
							echo thai_date($rs->F_E_CommerceDate, TRUE);
						}
						 ?>
					</td>

          <td class="middle">
						<?php if($rs->F_SapDate !== NULL) : ?>
						<?php echo thai_date($rs->F_SapDate, TRUE); ?>
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<?php if($rs->F_Receipt === 'Y') : ?>
							<span class="green">Yes</span>
						<?php else : ?>
							No
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<?php if($rs->F_ReceiptDate !== NULL) : ?>
							<?php echo thai_date($rs->F_ReceiptDate, TRUE); ?>
						<?php endif; ?>
					</td>
					<td class="text-center">
						<?php if($rs->F_Sap === NULL) : ?>
							<span class="blue">Pending</span>
						<?php elseif($rs->F_Sap === 'N') : ?>
							<span class="red">ERROR</span>
						<?php elseif($rs->F_Sap === 'D') : ?>
							<span class="blue">Draft</span>
						<?php elseif($rs->F_Sap == 'Y') : ?>
							<span class="green">Success</span>
						<?php endif; ?>
					</td>
					<td>
						<?php if( $rs->F_Sap === 'N' && ! empty($rs->Message)) : ?>
							<?php echo $rs->Message; ?>
            <?php endif; ?>
					</td>
        </tr>
<?php  $no++; ?>
<?php endforeach; ?>
<?php else : ?>
      <tr>
        <td colspan="11" class="text-center"><h4>Not found</h4></td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<form id="reportForm" method="post" action="<?php echo $this->home; ?>/export_diff">
	<input type="hidden" id="token" name="token" value="<?php echo uniqid(); ?>">
</form>

<script src="<?php echo base_url(); ?>scripts/inventory/temp/temp_transfer_draft_list.js?v=<?php echo date('YmdH'); ?>"></script>
<script>
$(document).ready(function(){
	$('[data-toggle="popover"]').popover({
		'container' : 'body',
		'template' : '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
	});
})


function export_diff()
{
  var token = $('#token').val();
  get_download(token);
  $('#reportForm').submit();
}

</script>
<?php $this->load->view('include/footer'); ?>
