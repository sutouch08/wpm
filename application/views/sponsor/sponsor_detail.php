<?php
	$add = $this->pm->can_add;
	$edit = $this->pm->can_edit;
	$delete = $this->pm->can_delete;
	?>
<form id="discount-form">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    	<table class="table table-striped border-1" style="min-width:920px;">
        <thead>
        	<tr class="font-size-12">
            	<th class="fix-width-40 text-center">No.</th>
                <th class="fix-width-60 text-center"></th>
                <th class="fix-width-150">รหัสสินค้า</th>
                <th class="fix-width-250">ชื่อสินค้า</th>
                <th class="fix-width-80 text-center">ราคา</th>
                <th class="fix-width-80 text-center">จำนวน</th>
                <th class="fix-width-120 text-center">ส่วนลด</th>
                <th class="fix-width-100 text-right">มูลค่า</th>
                <th class="fix-width-40 text-center"></th>
            </tr>
        </thead>
        <tbody id="detail-table">
          <?php   $no = 1;              ?>
          <?php   $total_qty = 0;       ?>
          <?php   $total_discount = 0;  ?>
          <?php   $total_amount = 0;    ?>
          <?php   $order_amount = 0;    ?>
          <?php if(!empty($details)) : ?>
          <?php   foreach($details as $rs) : ?>
            <tr class="font-size-10" id="row_<?php echo $rs->id; ?>">
            	<td class="middle text-center">
      					<?php echo $no; ?>
      				</td>

      				<td class="middle text-center padding-0">
              	<img src="<?php echo get_product_image($rs->product_code, 'mini'); ?>" width="40px" height="40px"  />
              </td>

      				<td class="middle">
      					<?php echo $rs->product_code; ?>
      				</td>

              <td class="middle">
      					<?php echo $rs->product_name; ?>
      				</td>

              <td class="middle text-center">
							<?php if(empty($approve_view)) : ?>
								<?php if( ($allowEditPrice && $order->state < 4) OR ($rs->is_count == 0 && $order->state < 8)  ) : ?>
				          	<input type="number"
														class="form-control input-sm text-center price-box hide"
														id="price_<?php echo $rs->id; ?>"
														name="price[<?php echo $rs->id; ?>]"
														value="<?php echo round($rs->price, 2); ?>" />
				        <?php endif; ?>
							<?php endif; ?>
                <span class="price-label" id="price-label-<?php echo $rs->id; ?>">	<?php echo number($rs->price, 2); ?></span>
              </td>

              <td class="middle text-center">
      						<?php echo number($rs->qty); ?>
      				</td>

              <td class="middle text-center">
              	-
              </td>

              <td class="middle text-right">
      					<?php echo number($rs->total_amount, 2); ?>
      				</td>

              <td class="middle text-right">
						<?php if(empty($approve_view)) : ?>
      				<?php if( $rs->is_count == 0 && ($edit OR $add) && $order->state < 8 && $edit_order) : ?>
      					<button type="button" class="btn btn-mini btn-warning" id="btn-show-price-<?php echo $rs->id; ?>" onclick="showNonCountPriceBox(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
      					<button type="button" class="btn btn-mini btn-info hide" id="btn-update-price-<?php echo $rs->id; ?>" onclick="updateNonCountPrice(<?php echo $rs->id; ?>)"><i class="fa fa-save"></i></button>
      				<?php endif; ?>
              <?php if( $order->is_approved == 0 && ( $order->is_paid == 0 && $order->state != 2 && $order->is_expired == 0 ) && ($edit OR $add) && $order->state < 4 ) : ?>
              	<button type="button" class="btn btn-mini btn-danger" onclick="removeDetail(<?php echo $rs->id; ?>, '<?php echo $rs->product_code; ?>')"><i class="fa fa-trash"></i></button>
              <?php endif; ?>
						<?php endif; ?>
              </td>

          </tr>

      <?php			$total_qty += $rs->qty;	?>
      <?php 		$order_amount += $rs->qty * $rs->price; ?>
      <?php			$total_amount += $rs->total_amount; ?>
      <?php			$no++; ?>
          <?php   endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="10" class="text-center"><h4>ไม่พบรายการ</h4></td>
            </tr>
          <?php endif; ?>
			<tr class="font-size-12">
            	<td colspan="6" rowspan="4"></td>
                <td style="border-left:solid 1px #CCC;"><b>จำนวนรวม</b></td>
                <td class="text-right"><b><?php echo number($total_qty); ?></b></td>
                <td class="text-center"><b>Pcs.</b></td>
            </tr>
           <tr class="font-size-12">
                <td style="border-left:solid 1px #CCC;"><b>มูลค่ารวม</b></td>
                <td class="text-right" id="total-td" style="font-weight:bold;"><?php echo number($order_amount, 2); ?></td>
                <td class="text-center"><b>THB.</b></td>
            </tr>
            <tr class="font-size-12">
                <td style="border-left:solid 1px #CCC;"><b>ส่วนลดรวม</b></td>
                <td class="text-right" id="discount-td" style="font-weight:bold;"><?php echo number($total_discount, 2); ?></td>
                <td class="text-center"><b>THB.</b></td>
            </tr>
            <tr class="font-size-12">
                <td style="border-left:solid 1px #CCC;"><b>สุทธิ</b></td>
                <td class="text-right" style="font-weight:bold;" id="netAmount-td"><?php echo number( $total_amount, 2); ?></td>
                <td class="text-center"><b>THB.</b></td>
            </tr>

        </tbody>
        </table>
    </div>
</div>
<!--  End Order Detail ----------------->
</form>
<!-- order detail template ------>
<script id="detail-table-template" type="text/x-handlebars-template">
{{#each this}}
	{{#if @last}}
    <tr class="font-size-12">
    	<td colspan="6" rowspan="4"></td>
      <td style="border-left:solid 1px #CCC;"><b>จำนวนรวม</b></td>
      <td class="text-right"><b>{{ total_qty }}</b></td>
      <td class="text-center"><b>Pcs.</b></td>
    </tr>

    <tr class="font-size-12">
      <td style="border-left:solid 1px #CCC;"><b>มูลค่ารวม</b></td>
      <td class="text-right"><b>{{ order_amount }}</b></td>
      <td class="text-center"><b>THB.</b></td>
    </tr>

    <tr class="font-size-12">
      <td style="border-left:solid 1px #CCC;"><b>ส่วนลดรวม</b></td>
      <td class="text-right"><b>{{ total_discount }}</b></td>
      <td class="text-center"><b>THB.</b></td>
    </tr>

    <tr class="font-size-12">
      <td style="border-left:solid 1px #CCC;"><b>สุทธิ</b></td>
      <td class="text-right"><b>{{ net_amount }}</b></td>
      <td class="text-center"><b>THB.</b></td>
    </tr>
	{{else}}
        <tr class="font-size-10" id="row_{{ id }}">
            <td class="middle text-center">{{ no }}</td>
            <td class="middle text-center padding-0">
            	<img src="{{ imageLink }}" width="40px" height="40px"  />
            </td>
            <td class="middle">{{ productCode }}</td>
            <td class="middle">{{ productName }}</td>
						<td class="middle text-center">{{ price }}</td>
            <td class="middle text-center">{{ qty }}</td>
            <td class="middle text-center">{{ discount }}</td>
            <td class="middle text-right">{{ amount }}</td>
            <td class="middle text-right">
            <?php if( ($edit OR $add) && $order->is_approved == 0 ) : ?>
            	<button type="button" class="btn btn-xs btn-danger" onclick="removeDetail({{ id }}, '{{ productCode }}')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
            </td>
        </tr>
	{{/if}}
{{/each}}
</script>

<script id="nodata-template" type="text/x-handlebars-template">
	<tr>
      <td colspan="11" class="text-center"><h4>ไม่พบรายการ</h4></td>
  </tr>
</script>
