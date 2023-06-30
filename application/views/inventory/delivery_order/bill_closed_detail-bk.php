<input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $order->customer_code; ?>" />
<input type="hidden" id="customer_ref" value="<?php echo $order->customer_ref; ?>" />
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <?php if(!empty($order->reference)) : ?>
      <label class="font-size-12 blod">
        <?php echo $order->code.' ['.$order->reference.']';  ?>
      </label>
    <?php else : ?>
    <label class="font-size-14 blod">
      <?php echo $order->code; ?>
    </label>
    <?php endif; ?>
  </div>

  <?php if($order->role == 'C' OR $order->role == 'N') : ?>
  <div class="col-sm-4 padding-5">
    <label class="font-size-12 blod">Customer : <?php echo empty($order->customer_ref) ? $order->customer_name : $order->customer_ref; ?></label>
  </div>
  <?php else : ?>
    <div class="col-sm-6 padding-5">
      <label class="font-size-14 blod">
        <?php if($order->role == 'L' OR $order->role == 'U' OR $order->role == 'R') : ?>
          Employee : <?php echo $order->empName; ?>
          <?php if(!empty($order->user_ref)) : ?>
            &nbsp;&nbsp;[Reference : <?php echo $order->user_ref; ?>]
          <?php endif; ?>
        <?php else: ?>
        Customer : <?php echo empty($order->customer_ref) ? $order->customer_name : $order->customer_ref; ?>
      <?php endif; ?>
      </label>
    </div>
  <?php endif; ?>

  <?php if($order->role == 'C' OR $order->role == 'N') : ?>
    <div class="col-sm-4 padding-5">
      <label class="font-size-2 blod">Bin Location : <?php echo $order->zone_name; ?></label>
    </div>
    <div class="col-sm-2 padding-5 last text-right">
      <label class="font-size-14 blod">User : <?php echo $order->user; ?></label>
    </div>
  <?php else : ?>
  <div class="col-sm-4 padding-5 last text-right">
    <label class="font-size-14 blod">User : <?php echo $order->user; ?></label>
  </div>
  <?php endif; ?>

  <?php if( $order->remark != '') : ?>
    <div class="col-sm-12">
      <label class="font-size-14 blod">Remark :</label>
      <?php echo $order->remark; ?>
    </div>
  <?php endif; ?>
</div>
<hr/>

<div class="row">
  <div class="col-sm-12 text-right">
    <button type="button" class="btn btn-sm btn-info" onclick="printAddress()"><i class="fa fa-print"></i> Delivery Slip</button>
    <button type="button" class="btn btn-sm btn-primary" onclick="printOrder()"><i class="fa fa-print"></i> Packing List </button>
    <button type="button" class="btn btn-sm btn-success" onclick="printOrderBarcode()"><i class="fa fa-print"></i> Packing List (barcode)</button>
    <button type="button" class="btn btn-sm btn-warning" onclick="showBoxList()"><i class="fa fa-print"></i> Packing List (Box)</button>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">#</th>
          <th class="width-35 text-center">Items</th>
          <th class="width-10 text-center">Price</th>
          <th class="width-10 text-center">Ordered</th>
          <th class="width-10 text-center">Picked</th>
          <th class="width-10 text-center">Packed</th>
          <th class="width-10 text-center">Discount</th>
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
            <?php echo number($order->bDiscAmount, 2); ?>
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
            <?php echo number($totalPrice, 2); ?>
          </td>
        </tr>

        <tr>
          <td colspan="3">
            Total discount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalDiscount + $order->bDiscAmount, 2); ?>
          </td>
        </tr>

        <tr>
          <td colspan="3" class="blod">
            Net amount
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice - ($totalDiscount + $order->bDiscAmount), 2); ?>
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
