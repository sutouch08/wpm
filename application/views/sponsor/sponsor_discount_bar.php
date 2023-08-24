
<?php if(!$order->is_expired && !$order->is_approved) : ?>

<div class="row">
	<div class="col-sm-12 col-xs-12 padding-5 margin-top-5 margin-bottom-5">
		<?php if($allowEditPrice) : ?>
      <button type="button" class="btn btn-sm btn-warning" id="btn-edit-price" onClick="showPriceBox()">Edit price</button>
      <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-price" onClick="getApprove('price')">Update Price</button>
		<?php endif; ?>
    </div>
</div>
<hr/>
<?php endif; ?>

<?php $this->load->view('validate_credentials'); ?>

<script src="<?php echo base_url(); ?>scripts/orders/order_discount.js?v=<?php echo date('Ymd'); ?>"></script>
