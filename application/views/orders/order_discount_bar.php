<?php
if(empty($order->has_payment) && !$order->is_paid && !$order->is_expired && !$order->is_approved) :
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-5 padding-5 margin-bottom-5">
		<?php if( $allowEditDisc && ($order->role == 'S' OR $order->role == 'C') OR $order->role == 'N') : ?>
    	<button type="button" class="btn btn-xs btn-warning" id="btn-edit-discount" onclick="showDiscountBox()">
				<?php if($order->role == 'C' OR $order->role == 'N') : ?>
					Edit GP
				<?php else : ?>
					Edit discount
				<?php endif; ?>
			</button>
      <button type="button" class="btn btn-xs btn-primary hide" id="btn-update-discount" onClick="getApprove('discount')">
				<?php if( $order->role == 'C' OR $order->role == 'N') : ?>
					Save GP
				<?php else : ?>
					Save discount
				<?php endif; ?>
			</button>
		<?php endif; ?>
		<?php if($allowEditPrice) : ?>
      <button type="button" class="btn btn-xs btn-warning" id="btn-edit-price" onClick="showPriceBox()">Edit price</button>
      <button type="button" class="btn btn-xs btn-primary hide" id="btn-update-price" onClick="getApprove('price')">Save price</button>
		<?php endif; ?>
    </div>
</div>
<hr/>
<?php endif; ?>

<?php $this->load->view('validate_credentials'); ?>

<script src="<?php echo base_url(); ?>scripts/orders/order_discount.js"></script>
