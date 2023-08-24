<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive" id="move-table">
  	<table class="table table-striped border-1">
    	<thead>
      	<tr>
        	<th colspan="6" class="text-center">Moved Items</th>
        </tr>

      	<tr>
        	<th class="width-5 text-center">#</th>
          <th class="width-15">Barcode</th>
          <th class="width-20">Items</th>
          <th class="width-25">From Location</th>
          <th class="width-25">To Location</th>
          <th class="width-15 text-center">Qty</th>
        </tr>
      </thead>

      <tbody id="move-list">
<?php if(!empty($details)) : ?>
<?php		$no = 1;						?>
<?php   $total_qty = 0; ?>
<?php		foreach($details as $rs) : 	?>
				<tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
	      	<td class="middle text-center">
						<?php echo $no; ?>
					</td>
					<!--- บาร์โค้ดสินค้า --->
	        <td class="middle">
						<?php echo $rs->barcode; ?>
					</td>
					<!--- รหัสสินค้า -->
	        <td class="middle">
						<?php echo $rs->product_code; ?>
					</td>
					<!--- โซนต้นทาง --->
	        <td class="middle">
	      		<input type="hidden" class="row-zone-from" id="row-from-<?php echo $rs->id; ?>" value="<?php echo $rs->from_zone; ?>" />
						<?php echo $rs->from_zone; ?>
	        </td>
	        <td class="middle" id="row-label-<?php echo $rs->id; ?>">
						<?php 	echo $rs->to_zone; 	?>
	        </td>

					<td class="middle text-center" >
						<?php echo number($rs->qty); ?>
					</td>
	      </tr>
<?php			$no++;			?>
<?php     $total_qty += $rs->qty; ?>
<?php		endforeach;			?>
				<tr>
					<td colspan="5" class="middle text-right"><strong>Total</strong></td>
					<td class="middle text-center"><strong><?php echo number($total_qty); ?></strong></td>
				</tr>
<?php	else : ?>
 				<tr>
        	<td colspan="6" class="text-center"><h4>No items found</h4></td>
        </tr>
<?php	endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if($doc->must_accept == 1 && ! empty($accept_list)) : ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
	<?php if($doc->is_accept == 1 && $doc->accept_by != NULL) : ?>
		<p class="green">Accepted by : <?php echo $doc->display_name; ?> @ <?php echo thai_date($doc->accept_on, TRUE); ?><br/>
			Note : <?php echo $doc->accept_remark; ?></p>
	<?php else : ?>
		<?php foreach($accept_list as $ac) : ?>
			<?php if($ac->is_accept == 1) : ?>
				<p class="green">Accepted by : <?php echo $ac->display_name; ?> @ <?php echo thai_date($ac->accept_on, TRUE); ?></p>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
