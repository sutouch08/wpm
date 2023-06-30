<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-8 col-xs-8 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-4 col-xs-4 padding-5">
    <p class="pull-right top-p">
<?php if( $this->pm->can_add ) : ?>
      <button type="button" class="btn btn-xs btn-success btn-100 top-btn" onclick="goAdd()">
        <i class="fa fa-plus"></i>&nbsp; New
      </button>
<?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Document No</label>
    <input type="text" class="form-control input-sm search text-center" name="code" id="code" value="<?php echo $code; ?>" autofocus />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Customer</label>
    <input type="text" class="form-control input-sm search text-center" name="customer" id="customer" value="<?php echo $customer; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Bin location</label>
    <input type="text" class="form-control input-sm search text-center" name="zone" id="zone" value="<?php echo $zone; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Reference</label>
    <input type="text" class="form-control input-sm search text-center" name="ref_code" id="ref_code" value="<?php echo $ref_code; ?>" />
  </div>

  <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
    <label class="display-block">Date</label>
    <div class="input-daterange input-group">
        <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>">
        <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>">
      </div>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Status</label>
    <select class="form-control input-sm" name="status" id="status" onchange="getSearch()">
      <option value="all" <?php echo is_selected($status, 'all'); ?>>All</option>
      <option value="0" <?php echo is_selected($status, '0'); ?>>Draft</option>
      <option value="1" <?php echo is_selected($status, '1'); ?>>Success</option>
      <option value="2" <?php echo is_selected($status, '2'); ?>>Cancelled</option>
    </select>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
    <label class="display-block not-show">search</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
    <label class="display-block not-show">Reset</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Clear</button>
  </div>
</div>
</form>
<hr class="margin-top-15 margin-bottom-15"/>

<?php echo $this->pagination->create_links(); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="pull-right top-p">
      <span> Status : &nbsp; &nbsp;</span>
      <span class="green">SC</span><span class="margin-right-15"> = Success</span>
      <span class="blue">DF</span><span class="margin-right-15"> = Draft</span>
      <span class="red">CN</span><span class=""> = Cancelled</span>
    </p>
  </div>
</div>

<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
     <table class="table table-striped border-1" style="min-width:1200px;">
       <thead>
         <tr class="font-size-12">
           <th class="fix-width-40 text-center">#</th>
           <th class="fix-width-80"></th>
           <th class="fix-width-40 text-center">Status</th>
           <th class="fix-width-100">Date</th>
           <th class="fix-width-120">Document No</th>
           <th class="fix-width-350">Customer</th>
           <th class="fix-width-100 text-right">Amount</th>
           <th class="fix-width-250">Bin Location</th>
           <th class="fix-width-100 text-center">Reference</th>
         </tr>
       </thead>
       <tbody>
<?php if(!empty($docs)) : ?>
<?php   $no = $this->uri->segment(4) + 1; ?>
<?php   foreach($docs as $rs) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->code; ?>">
          <td class="middle text-center no">
            <?php echo $no; ?>
          </td>
          <td class="middle text-right">
            <?php if($rs->status == 1) : ?>
              <button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
            <?php endif; ?>
            <?php if($rs->status == 0 && $this->pm->can_edit) : ?>
              <button type="button" class="btn btn-minier btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
            <?php endif; ?>
            <?php if($rs->status == 0 && $this->pm->can_delete) : ?>
              <button type="button" class="btn btn-minier btn-danger" onclick="getDelete('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
          </td>
          <td class="middle text-center">
            <?php if($rs->status == 2) : ?>
              <span class="red">CN</span>
            <?php endif; ?>
            <?php if($rs->status == 0) : ?>
              <span class="blue">DF</span>
            <?php endif; ?>
            <?php if($rs->status == 1) : ?>
              <span class="green">SC</span>
            <?php endif; ?>
          </td>
          <td class="middle">
            <?php echo thai_date($rs->date_add, FALSE); ?>
          </td>
          <td class="middle">
            <?php echo $rs->code; ?>
          </td>
          <td class="middle">
            <?php echo $rs->customer_name; ?>
          </td>

          <td class="middle text-right">
            <?php echo number($rs->amount, 2); ?>
          </td>

          <td class="middle">
            <?php echo $rs->zone_name; ?>
          </td>
          <td class="middle text-center">
            <?php echo $rs->ref_code; ?>
          </td>

        </tr>
<?php    $no++; ?>
<?php   endforeach; ?>
<?php else : ?>
        <tr>
          <td colspan="9" class="middle text-center">---- No item found ----</td>
        </tr>
<?php endif; ?>
       </tbody>
     </table>
   </div>
 </div>
 <?php $this->load->view('cancle_modal'); ?>
<script src="<?php echo base_url(); ?>scripts/account/consign_order/consign_order.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
