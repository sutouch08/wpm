<input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $order->customer_code; ?>" />
<input type="hidden" id="customer_ref" value="<?php echo $order->customer_ref; ?>" />

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

<hr/>

<div class="row">
  <div class="col-sm-12 text-right">
    <button type="button" class="btn btn-sm btn-info top-btn" onclick="printAddress()"><i class="fa fa-print"></i> Delivery Slip</button>
    <button type="button" class="btn btn-sm btn-primary top-btn" onclick="printOrder()"><i class="fa fa-print"></i> Packing List </button>
    <button type="button" class="btn btn-sm btn-success top-btn" onclick="printOrderBarcode()"><i class="fa fa-print"></i> Packing List (barcode)</button>
    <button type="button" class="btn btn-sm btn-warning top-btn" onclick="showBoxList()"><i class="fa fa-print"></i> Packing List (Box)</button>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-bordered" style="min-width:940px;">
      <thead>
        <tr class="font-size-12">
          <th class="fix-width-40 text-center">#</th>
          <th class="fix-width-250 text-center">Items</th>
          <th class="fix-width-150 text-center">Price</th>
          <th class="fix-width-100 text-center">Ordered</th>
          <th class="fix-width-100 text-center">Picked</th>
          <th class="fix-width-100 text-center">Packed</th>
          <th class="fix-width-100 text-center">Discount</th>
          <th class="fix-width-100 text-center">Amount</th>
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
              <?php echo number($rs->price, 2); ?>&nbsp; <?php echo $rs->currency; ?>
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
            Bill discount
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
            Total amount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice, 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>

        <tr>
          <td colspan="3">
            Total discount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalDiscount + $order->bDiscAmount, 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>

        <tr>
          <td colspan="3" class="blod">
            Net amount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice - ($totalDiscount + $order->bDiscAmount), 2); ?>&nbsp; <?php echo $order->DocCur; ?>
          </td>
        </tr>

<?php else : ?>
      <tr><td colspan="8" class="text-center"><h4>No data</h4></td></tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<!--************** Address Form Modal ************-->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true">
  <div class="modal-dialog" style="width:500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" id="info_body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="printSelectAddress()"><i class="fa fa-print"></i> Print</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('inventory/order_closed/box_list');  ?>

<script src="<?php echo base_url(); ?>scripts/print/print_address.js"></script>
<script src="<?php echo base_url(); ?>scripts/print/print_order.js"></script>
