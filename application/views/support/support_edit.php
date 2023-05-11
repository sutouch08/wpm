<?php $this->load->view('include/header'); ?>
<?php $isAdmin = (get_cookie('id_profile') == -987654321 ? TRUE : FALSE); ?>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?> </h3>
	</div>
  <div class="col-sm-9 col-xs-12 padding-5">
  	<p class="pull-right" style="margin-bottom:1px;">
			<?php if(empty($approve_view)) : ?>
			<button type="button" class="btn btn-xs btn-warning top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
			<button type="button" class="btn btn-xs btn-default top-btn" onclick="printOrderSheet()"><i class="fa fa-print"></i> พิมพ์</button>
			<?php endif; ?>

		<?php if(empty($approve_view)) : ?>
			<?php if($order->state < 4 && $isAdmin && $order->never_expire == 0) : ?>
			<button type="button" class="btn btn-xs btn-primary top-btn" onclick="setNotExpire(1)">ยกเว้นการหมดอายุ</button>
			<?php endif; ?>
			<?php if($order->state < 4 && $isAdmin && $order->never_expire == 1) : ?>
				<button type="button" class="btn btn-xs btn-info top-btn" onclick="setNotExpire(0)">ไม่ยกเว้นการหมดอายุ</button>
			<?php endif; ?>
			<?php if($isAdmin && $order->is_expired == 1) : ?>
				<button type="button" class="btn btn-xs btn-warning top-btn" onclick="unExpired()">ทำให้ไม่หมดอายุ</button>
			<?php endif; ?>

			<?php if(($order->is_wms == 0 && $order->state < 4) OR ($order->is_wms == 1 && $order->state < 3)) : ?>
				<?php if($order->is_expired == 0 && ($this->pm->can_add OR $this->pm->can_edit)) : ?>
					<button type="button" class="btn btn-xs btn-yellow top-btn" onclick="editDetail()"><i class="fa fa-pencil"></i> แก้ไขรายการ</button>
				<?php endif; ?>
			<?php endif; ?>

			<?php if($order->status == 0) : ?>
				<button type="button" class="btn btn-xs btn-success top-btn" onclick="saveOrder()"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
		<?php endif; ?>

			<?php if($order->state == 1 && $order->is_approved == 0 && $order->status == 1 && $order->is_expired == 0 && $this->pm->can_approve) : ?>
					<button type="button" class="btn btn-xs btn-success top-btn" onclick="approve()"><i class="fa fa-check"></i> อนุมัติ</button>
			<?php endif; ?>
			<?php if($order->state == 1 && $order->is_approved == 1 && $order->status == 1 && $order->is_expired == 0 && $this->pm->can_approve) : ?>
					<button type="button" class="btn btn-xs btn-danger top-btn" onclick="unapprove()"><i class="fa fa-refresh"></i> ไม่อนุมัติ</button>
			<?php endif; ?>
			<?php if($this->isAPI && $order->is_wms && $order->status == 1 && $order->is_expired == 0 && $order->state == 3) : ?>
				<button type="button" class="btn btn-xs btn-success top-btn" onclick="sendToWMS()">Send to WMS</button>
			<?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
<?php $this->load->view('support/support_edit_header'); ?>
<?php if(empty($approve_view)) : ?>
<?php $this->load->view('orders/order_panel'); ?>
<?php $this->load->view('support/support_discount_bar'); ?>
<?php $this->load->view('orders/order_online_modal'); ?>
<?php else : ?>
	<input type="hidden" id="id_sender" value="<?php echo $order->id_sender; ?>"/>
	<input type="hidden" id="id_address" value="<?php echo $order->id_address; ?>"/>
<?php endif; ?>
<?php $this->load->view('support/support_detail'); ?>


<?php if(!empty($approve_logs)) : ?>
	<div class="row">
		<?php foreach($approve_logs as $logs) : ?>
		<div class="col-sm-12 text-right padding-5 first last">
			<?php if($logs->approve == 1) : ?>
			  <span class="green">
					อนุมัติโดย :
					<?php echo $logs->approver; ?> @ <?php echo thai_date($logs->date_upd, TRUE); ?>
				</span>
			<?php else : ?>
				<span class="red">
				ยกเลิกโดย :
				<?php echo $logs->approver; ?> @ <?php echo thai_date($logs->date_upd, TRUE); ?>
			  </span>
			<?php endif; ?>

		</div>
	<?php endforeach; ?>
	</div>
<?php endif; ?>



<script src="<?php echo base_url(); ?>scripts/support/support.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/support/support_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/print/print_order.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/print/print_address.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/orders/order_online.js?v=<?php echo date('Ymd'); ?>"></script>
<?php if($order->is_wms && $order->status == 1 && $order->is_expired == 0 && $order->state == 3) : ?>
	<script src="<?php echo base_url(); ?>scripts/wms/wms_order.js?v=<?php echo date('Ymd'); ?>"></script>
<?php endif; ?>

<?php $this->load->view('include/footer'); ?>
