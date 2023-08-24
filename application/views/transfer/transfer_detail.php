<div class="row">
	<div class="col-sm-12 padding-5 hide" id="zone-table">
    <form id="productForm">
    	<table class="table table-striped table-bordered">
      	<thead>
					<tr>
						<th colspan="6" class="text-center">
							<h4 class="title" id="zoneName"></h4>
						</th>
					</tr>
        	<tr>
          	<th colspan="6">
							<div class="col-sm-6">
              	<button type="button" class="btn btn-sm btn-info" onclick="selectAll()">Select All</button>
								<button type="button" class="btn btn-sm btn-warning" onclick="clearAll()">Clear All</button>
              </div>
              <div class="col-sm-6">
                <p class="pull-right top-p">
                  <button type="button" class="btn btn-sm btn-primary" onclick="addToTransfer()">Transfer Selected</button>
                </p>
              </div>
            </th>
          </tr>

          <tr>
          	<th class="width-10 text-center">#</th>
            <th class="width-20 text-center">Barcode</th>
            <th class="width-40 text-center">Item</th>
            <th class="width-10 text-center">In stock</th>
            <th class="width-10 text-center">Qty</th>
          </tr>
          </thead>

          <tbody id="zone-list"> </tbody>

        </table>
      </form>
    </div>



	<div class="col-sm-12 padding-5" id="transfer-table">
  	<table class="table table-striped border-1">
    	<thead>
      	<tr>
        	<th colspan="7" class="text-center">Transfered Items</th>
        </tr>

      	<tr>
        	<th class="width-5 text-center">#</th>
          <th class="width-15">Barcode</th>
          <th class="width-20">Items</th>
          <th class="width-25">From Location</th>
          <th class="width-25">To Location</th>
          <th class="width-10 text-center">Qty</th>
          <th class="width-5"></th>
        </tr>
      </thead>

      <tbody id="transfer-list">
<?php if(!empty($details)) : ?>
<?php		$no = 1;						?>
<?php   $total_qty = 0; ?>
<?php		foreach($details as $rs) : 	?>
				<tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
	      	<td class="middle text-center no">
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
						<?php echo $rs->from_zone_name; ?>
	        </td>
	        <td class="middle" id="row-label-<?php echo $rs->id; ?>">
						<?php 	echo $rs->to_zone_name; 	?>
	        </td>

					<td class="middle text-center qty">
						<?php echo number($rs->qty); ?>
					</td>

	        <td class="middle text-center">
	          <?php if($this->pm->can_edit && $rs->valid == 0) : ?>
	          	<button type="button" class="btn btn-minier btn-danger"
							onclick="deleteMoveItem(<?php echo $rs->id; ?>, '<?php echo $rs->product_code; ?>')">
								<i class="fa fa-trash"></i>
							</button>
	          <?php endif; ?>
	        </td>

	      </tr>
<?php			$no++;			?>
<?php     $total_qty += $rs->qty; ?>
<?php		endforeach;			?>
				<tr>
					<td colspan="5" class="middle text-right"><strong>Total</strong></td>
					<td class="middle text-center" id="total"><?php echo number($total_qty); ?></td>
					<td></td>
				</tr>
<?php	else : ?>
 				<tr>
        	<td colspan="7" class="text-center"><h4>No item found</h4></td>
        </tr>
<?php	endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script id="zoneTemplate" type="text/x-handlebars-template">
{{#each this}}
	{{#if nodata}}
		<tr>
			<td colspan="6" class="text-center">
				<h4>No item found</h4>
			</td>
		</tr>
	{{else}}
		<tr>
			<td align="center">{{ no }}</td>
		  <td align="center">{{ barcode }}</td>
		  <td>{{ products }}</td>
		  <td align="center" class="qty-label">{{ qty }}</td>
		  <td align="center">
		  	<input type="number" class="form-control input-sm text-center input-qty" max="{{qty}}" id="{{products}}" />
		  </td>
		</tr>
	{{/if}}
{{/each}}
</script>



<script id="transferTableTemplate" type="text/x-handlebars-template">
{{#each this}}
	{{#if nodata}}
	<tr>
		<td colspan="7" class="text-center"><h4>No item found</h4></td>
	</tr>
	{{else}}
		{{#if @last}}
			<tr>
				<td colspan="5" class="text-right"><strong>Total</strong></td>
				<td class="middle text-center" id="total">{{ total }}</td>
				<td></td>
			</tr>
		{{else}}
		<tr class="font-size-12" id="row-{{id}}">
			<td class="middle text-center no">{{ no }}</td>
			<td class="middle">{{ barcode }}</td>
			<td class="middle">{{ products }}</td>
			<td class="middle">{{ from_zone }}</td>
			<td class="middle">{{{ to_zone }}}</td>
			<td class="middle text-center qty">{{ qty }}</td>
			<td class="middle text-center">{{{ btn_delete }}}</td>
		</tr>
		{{/if}}
	{{/if}}
{{/each}}
</script>
