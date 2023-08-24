<?php $this->load->view('include/header'); ?>
<style>
	.b0p3 {
		border:0px !important;
		padding:3px !important;
	}
</style>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 padding-5 hidden-xs">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?></h3>
	</div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>Doc No.</label>
    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>Customer</label>
    <input type="text" class="form-control input-sm search" name="customer" value="<?php echo $customer; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>User</label>
    <input type="text" class="form-control input-sm search" name="user" value="<?php echo $user; ?>" />
  </div>

	<div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-6 padding-5">
    <label>Channels</label>
    <select class="form-control input-sm" name="channels" onchange="getSearch()">
			<option value="all">All</option>
			<?php echo select_channels($channels); ?>
		</select>
  </div>

	<div class="col-lg-3 col-md-3-harf col-sm-4 col-xs-6 padding-5">
    <label>Account No</label>
		<select class="form-control input-sm" name="account" onchange="getSearch()">
      <option value="">All</option>
      <?php echo select_bank_account($account); ?>
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
    <label>Status</label>
		<select class="form-control input-sm" name="valid" onchange="getSearch()">
      <option value="0" <?php echo is_selected($valid, '0'); ?>>Pending</option>
      <option value="1" <?php echo is_selected($valid, '1'); ?>>Confirmed</option>
    </select>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover border-1" style="min-width:1050px;">
			<thead>
				<tr>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-120 middle">Document No</th>
          <th class="fix-width-100 middle">Channels</th>
					<th class="fix-width-200 middle">Customer</th>
          <th class="fix-width-120 middle hidden-md">User</th>
					<th class="fix-width-100 middle text-center">Date</th>
					<th class="fix-width-100 middle text-center">Time</th>
					<th class="fix-width-100 middle text-right">Amount</th>
					<th class="fix-width-120 middle text-center">Account No</th>
					<th class="fix-width-80"></th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($orders)) : ?>
          <?php $no = $this->uri->segment(4) + 1; ?>
          <?php foreach($orders as $rs) : ?>
            <?php $customer_name = (!empty($rs->customer_ref)) ? $rs->customer_ref : $rs->customer_name; ?>
            <tr id="row-<?php echo $rs->id; ?>" sytle="font-size:12px;">
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle" style="font-size:12px;"><?php echo $rs->order_code; ?></td>
              <td class="middle" style="font-size:12px;"><?php echo $rs->channels; ?></td>
              <td class="middle" style="font-size:12px;"><?php echo $customer_name; ?></td>
              <td class="middle hidden-md" style="font-size:12px;"><?php echo $rs->user; ?></td>
							<td class="middle text-center" style="font-size:12px;"><?php echo date('d-m-Y', strtotime($rs->pay_date)); ?></td>
							<td class="middle text-center" style="font-size:12px;"><?php echo date('H:i:s', strtotime($rs->pay_date)); ?></td>
              <td class="middle text-right" style="font-size:12px;"><?php echo number($rs->pay_amount,2); ?></td>
              <td class="middle text-center" style="font-size:12px;"><?php echo $rs->acc_no; ?></td>
              <td class="middle text-right">
                <button type="button" class="btn btn-minier btn-info" onClick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
          <?php if($this->pm->can_delete) : ?>
                <button type="button" class="btn btn-minier btn-danger" onClick="removePayment(<?php echo $rs->id; ?>, '<?php echo $rs->order_code; ?>')"><i class="fa fa-trash"></i></button>
          <?php endif; ?>
              </td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<div class='modal fade' id='confirmModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width:350px; max-width:95%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class="fa fa-times"></i></button>
            </div>
            <div class='modal-body' id="detailBody">

            </div>
            <div class='modal-footer'>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='imageModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width:500px; max-width:95%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class="fa fa-times"></i></button>
            </div>
            <div class='modal-body' id="imageBody">

            </div>
            <div class='modal-footer'>
            </div>
        </div>
    </div>
</div>

<script id="detailTemplate" type="text/x-handlebars-template">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center"><h4 class="title-xs" style="margin-bottom:0px;">ข้อมูลการชำระเงิน</h4></div>
</div>
<hr/>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<table class="table" style="margin-bottom:0px;">
			<tr><td class="width-35 text-right b0p3">ยอดที่ต้องชำระ :</td><td class="b0p3">{{orderAmount}}</td></tr>
			<tr><td class="text-right b0p3">ยอดโอนชำระ :</td><td class="b0p3"><span style="font-weight:bold; color:#E9573F;">฿ {{ payAmount }}</span></td></tr>
			<tr><td class="text-right b0p3">วันที่โอน :</td><td class="b0p3">{{ payDate }}</td></tr>
			<tr><td class="text-right b0p3">ธนาคาร :</td><td class="b0p3">{{ bankName }}</td></tr>
			<tr><td class="text-right b0p3">สาขา :</td><td class="b0p3">{{ branch }}</td></tr>
			<tr><td class="text-right b0p3">เลขที่บัญชี :</td><td class="b0p3"><span style="font-weight:bold; color:#E9573F;">{{ accNo }}</td></tr>
			<tr><td class="text-right b0p3">ชื่อบัญชี :</td><td class="b0p3">{{ accName }}</td></tr>
			<tr>
				<td colspan="2" class="text-center b0p3">่
					{{#if imageUrl}}
						<a href="javascript:void(0)" onClick="viewImage('{{ imageUrl }}')">
							รูปสลิปแนบ	<i class="fa fa-paperclip fa-rotate-90"></i>
						</a>
					{{else}}
						---  ไม่พบไฟล์แนบ  ---
				{{/if}}
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-center b0p3">่
					{{#if valid}}
				  <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
						<button type="button" class="btn btn-sm btn-warning btn-block" onClick="confirmPayment({{ id }})">
							<i class="fa fa-check-circle"></i> ยืนยันการชำระเงิน
						</button>
				  <?php endif; ?>
				  {{else}}
				  <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
						<button type="button" class="btn btn-sm btn-danger btn-block" onClick="unConfirmPayment({{ id }})">
							<i class="fa fa-check-circle"></i> ยกเลิกการยืนยัน
						</button>
				  <?php endif; ?>
					{{/if}}
				</td>
			</tr>
		</table>
	</div>
</div>
</script>

<script id="orderTableTemplate" type="text/x-handlebars-template">
{{#each this}}
<tr id="{{ id }}" class="font-size-12">
<td class="text-center">{{ no }}</td>
<td> {{ reference }}</td>
<td align="center"> {{ channels }}</td>
<td>{{ customer }}</td>
<td>{{ employee }}</td>
<td align="center">{{ payDate }}</td>
<td align="center">{{ payTime }}</td>
<td align="center">{{ orderAmount }}</td>
<td align="center">{{ payAmount }}</td>
<td align="center">{{ accNo }}</td>
<td align="right">
	<button type="button" class="btn btn-xs btn-warning" onClick="viewDetail({{ id }})"><i class="fa fa-eye"></i></button>
	<button type="button" class="btn btn-xs btn-danger" onClick="removePayment({{ id }}, '{{ reference }}')"><i class="fa fa-trash"></i></button>
 </td>
</tr>
{{/each}}
</script>

<script src="<?php echo base_url(); ?>scripts/orders/payment/payment.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/orders/payment/payment_list.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
