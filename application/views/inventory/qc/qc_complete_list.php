<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr><th colspan="6" class="text-center">Completed</th></tr>

        <tr class="font-size-12">
          <th class="fix-width-150 text-center">Barcode</th>
          <th class="min-width-200">Item Code</th>
          <th class="fix-width-100 text-center">Order Qty</th>
          <th class="fix-width-100 text-center">Picked Qty</th>
          <th class="fix-width-100 text-center">Packed Qty</th>
          <th class="fix-width-100 text-right">Location</th>
        </tr>
      </thead>
      <tbody id="complete-table">

<?php  if(!empty($complete_details)) : ?>
<?php   foreach($complete_details as $rs) : ?>
      <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
        <td class="middle text-center"><?php echo $rs->barcode; ?></td>
        <td class="middle">
          <?php echo $rs->product_code; ?> :
          <?php if(empty($rs->old_code) OR $rs->old_code == $rs->product_code) : ?>
          <?php     echo $rs->product_name; ?>
          <?php else : ?>
          <?php     echo $rs->old_code; ?>
          <?php endif; ?>
        </td>
        <td class="middle text-center"><?php echo number($rs->order_qty); ?></td>
        <td class="middle text-center" id="prepared-<?php echo $rs->id; ?>"><?php echo number($rs->prepared); ?></td>
        <td class="middle text-center" id="qc-<?php echo $rs->id; ?>"><?php echo number($rs->qc); ?></td>
        <td class="middle text-right">
          <?php if(($rs->qc > $rs->prepared OR $rs->qc > $rs->order_qty) && $this->pm->can_delete) : ?>
            <button type="button" class="btn btn-xs btn-warning must-edit" onclick="showEditOption('<?php echo $order->code; ?>', '<?php echo $rs->product_code; ?>')">
              <i class="fa fa-pencil"></i> Edit
            </button>
          <?php endif; ?>
          <button
            type="button"
            class="btn btn-default btn-xs btn-pop"
            data-container="body"
            data-toggle="popover"
            data-placement="left"
            data-trigger="focus"
            data-content="<?php echo $rs->from_zone; ?>"
            data-original-title=""
            title="">
            Location
          </button>
          <input type="hidden" id="id-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" />
        </td>
      </tr>

<?php   endforeach; ?>
<?php endif; ?>

      </tbody>
    </table>
  </div>
</div>
