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
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Pack List</button>
      <button type="button" class="btn btn-sm btn-yellow" onclick="viewProcess()"><i class="fa fa-arrow-left"></i> Packing List</button>
    </p>
  </div>
</div>

<hr class="margin-bottom-10" />
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
      <label>Document No</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
    </div>
    <div class="col-lg-6-harf col-md-6-harf col-sm-6-harf col-xs-8 padding-5">
      <label>Cust./Emp.</label>
      <input type="text" class="form-control input-sm" value="<?php echo ($order->customer_ref == '' ? $order->customer_name : $order->customer_ref);  ?>" disabled />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Channels</label>
      <input type="text" class="form-control input-sm" value="<?php echo $order->channels_name; ?>" disabled />
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
      <label>Date</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo thai_date($order->date_add); ?>" disabled />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
      <label>Remark</label>
      <input type="text" class="form-control input-sm" value="<?php echo $order->remark; ?>" disabled/>
    </div>
    <input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
    <input type="hidden" id="id_box" value="" />
  </div>
  <hr />
<?php
    $this->load->view('inventory/qc/qc_box');
    $this->load->view('inventory/qc/qc_control');
    $this->load->view('inventory/qc/qc_incomplete_list');
    $this->load->view('inventory/qc/qc_complete_list');
?>


  <!--************** Address Form Modal ************-->
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true">
    <div class="modal-dialog" style="width:500px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="colse" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" id="info_body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-primary" onclick="printSelectAddress()"><i class="fa fa-print"></i> พิมพ์</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="optionModal" aria-hidden="true">
    <div class="modal-dialog" style="width:500px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="edit-title"></h4>
        </div>
        <div class="modal-body" id="edit-body">

        </div>
      </div>

    </div>
  </div>

<script id="edit-template" type="text/x-handlebarsTemplate">
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="width-20">รหัส</th>
            <th class="width-40">กล่อง</th>
            <th class="width-15 text-center">ในกล่อง</th>
            <th class="width-15 text-center">เอาออก</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
      {{#each this}}
        <tr>
          <td>{{barcode}}</td>
          <td>กล่องที่ {{box_no}}</td>
          <td class="text-center"><span id="label-{{id_qc}}">{{qty}}</span></td>
          <td class="text-center">
            <input type="number" class="form-control input-sm text-center" id="input-{{id_qc}}" />
          </td>
          <td class="text-right">
          <?php if($this->pm->can_delete) : ?>
            <button type="button" class="btn btn-sm btn-danger" onclick="updateQty({{id_qc}})">Update</button>
          <?php endif; ?>
          </td>
        </tr>
      {{/each}}
        </tbody>
      </table>
    </div>
  </div>
  </script>

<?php
if(!empty($barcode_list))
{
  foreach($barcode_list as $id => $barcode)
  {
    echo '<input type="hidden" class="'.$barcode.'" id="'.$id.'" value="1" />';
  }
}
 ?>

<script src="<?php echo base_url(); ?>scripts/inventory/qc/qc.js"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/qc/qc_process.js"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/qc/qc_control.js"></script>
<script src="<?php echo base_url(); ?>scripts/print/print_address.js"></script>
<script src="<?php echo base_url(); ?>scripts/beep.js"></script>
<?php $this->load->view('include/footer'); ?>
