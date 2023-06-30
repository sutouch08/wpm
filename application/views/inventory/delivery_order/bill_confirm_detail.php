
<input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
<?php
if($order->role == 'N' OR $order->role == 'C')
{
	$this->load->view('inventory/delivery_order/consign_header');
}
elseif($order->role == 'S')
{
	$this->load->view('inventory/delivery_order/sales_header');
}
elseif($order->role == 'U' OR $order->role == 'P' OR $order->role == 'Q' OR $order->role == 'T')
{
	$this->load->view('inventory/delivery_order/other_header');
}
elseif($order->role == 'L')
{
	$this->load->view('inventory/delivery_order/lend_header');
}
 ?>

<hr class="margin-top-15"/>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-right">
    <?php if( $this->pm->can_edit || $this->pm->can_add ) : ?>
      <button type="button" class="btn btn-sm btn-primary" id="btn-confirm-order" onclick="confirmOrder()">Delivery confirm</button>
    <?php endif; ?>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-bordered" style="min-width:800px;">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">#</th>
          <th class="width-35 text-center">Items</th>
          <th class="width-10 text-center">Price</th>
          <th class="width-10 text-center">Ordered</th>
          <th class="width-10 text-center">Picked</th>
          <th class="width-10 text-center">Packed</th>
          <th class="width-10 text-center">Disc.</th>
          <th class="width-10 text-center">Amount</th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($details)) : ?>
<?php   $no = 1;
        $totalQty = 0;
        $totalPrepared = 0;
        $totalQc = 0;
        $totalAmount = 0;
        $totalDiscount = 0;
        $totalPrice = 0;
?>
<?php   foreach($details as $rs) :  ?>
<?php     $color = ($rs->order_qty == $rs->qc OR $rs->is_count == 0) ? '' : 'red'; ?>
        <tr class="font-size-12 <?php echo $color; ?>">
          <td class="text-center">
            <?php echo $no; ?>
          </td>

          <!--- รายการสินค้า ที่มีการสั่งสินค้า --->
          <td>
            <?php echo limitText($rs->product_code.' : '. $rs->product_name, 100); ?>
          </td>

          <!--- ราคาสินค้า  --->
          <td class="text-center">
            <?php echo number($rs->price, 2); ?>
          </td>

          <!---   จำนวนที่สั่ง  --->
          <td class="text-center">
            <?php echo number($rs->order_qty); ?>
          </td>

          <!--- จำนวนที่จัดได้  --->
          <td class="text-center">
            <?php echo $rs->is_count == 0 ? number($rs->order_qty) : number($rs->prepared); ?>
          </td>

          <!--- จำนวนที่ตรวจได้ --->
          <td class="text-center">
            <?php echo $rs->is_count == 0 ? number($rs->order_qty) : number($rs->qc); ?>
          </td>

          <!--- ส่วนลด  --->
          <td class="text-center">
            <?php echo discountLabel($rs->discount1, $rs->discount2, $rs->discount3); ?>
          </td>

          <td class="text-right">
            <?php echo $rs->is_count == 0 ? number($rs->final_price * $rs->order_qty) : number( $rs->final_price * $rs->qc , 2); ?>
						&nbsp; <?php echo $rs->currency; ?>
          </td>

        </tr>
<?php
      $totalQty += $rs->order_qty;
      $totalPrepared += ($rs->is_count == 0 ? $rs->order_qty : $rs->prepared);
      $totalQc += ($rs->is_count == 0 ? $rs->order_qty : $rs->qc);
      $totalDiscount += ($rs->is_count == 0 ? $rs->discount_amount * $rs->order_qty : $rs->discount_amount * $rs->qc);
      $totalAmount += ($rs->is_count == 0 ? $rs->final_price * $rs->order_qty : $rs->final_price * $rs->qc);
      $totalPrice += ($rs->is_count == 0 ? $rs->price * $rs->order_qty : $rs->price * $rs->qc);
      $no++;
?>
<?php   endforeach; ?>
        <tr class="font-size-12">
          <td colspan="3" class="text-right font-size-14">
            Total
          </td>

          <td class="text-center">
            <?php echo number($totalQty); ?>
          </td>

          <td class="text-center">
            <?php echo number($totalPrepared); ?>
          </td>

          <td class="text-center">
            <?php echo number($totalQc); ?>
          </td>

          <td class="text-center">
            Bill Discount
          </td>

          <td class="text-right">
            <?php echo number($order->bDiscAmount, 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>


        <tr>
          <td colspan="3" rowspan="3">
            Remark : <?php echo $order->remark; ?>
          </td>
          <td colspan="3" class="blod">
            Total Amount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice, 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>

        <tr>
          <td colspan="3" class="blod">
            Total Discount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalDiscount + $order->bDiscAmount, 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>

        <tr>
          <td colspan="3" class="blod">
            Net Amount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice - ($totalDiscount + $order->bDiscAmount), 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>

<?php else : ?>
      <tr><td colspan="8" class="text-center"><h4>No Data</h4></td></tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
	$('#ship-date').datepicker({
		'dateFormat' : 'dd-mm-yy'
	});

	function activeShipDate() {
		$('#ship-date').removeAttr('disabled');
		$('#btn-edit-ship-date').addClass('hide');
		$('#btn-update-ship-date').removeClass('hide');
	}

	function updateShipDate() {
		let shipDate = $('#ship-date').val();
		let order = $('#order_code').val();

		$.ajax({
			url:HOME + 'update_shipped_date',
			type:'POST',
			cache:false,
			data:{
				'order_code' : order,
				'shipped_date' : shipDate
			},
			success:function(rs) {
				rs = $.trim(rs);
				if(rs === 'success') {
					$('#ship-date').attr('disabled', 'disabled');
					$('#btn-update-ship-date').addClass('hide');
					$('#btn-edit-ship-date').removeClass('hide');
				}
				else {
					swal({
						title:'Error!',
						type:'error',
						text:rs
					});
				}
			}
		})
	}
</script>
