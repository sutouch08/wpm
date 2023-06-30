<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1 qc-table" style="min-width:900px;">
      <thead>
        <tr class="font-size-12">
          <th class="fix-width-150 text-center">Barcode</th>
          <th class="min-width-200">Item Code</th>
          <th class="fix-width-100 text-center">Order Qty</th>
          <th class="fix-width-100 text-center">Picked Qty</th>
          <th class="fix-width-100 text-center">Packed Qty</th>
          <th class="fix-width-100 text-right">Location</th>
        </tr>
      </thead>
      <tbody id="incomplete-table">
<?php   $show_close = !empty($uncomplete_details) ? 'hide' : ''; ?>
<?php   $show_force = !empty($uncomplete_details) ? '' : 'hide'; ?>
<?php  if(!empty($uncomplete_details)) : ?>
<?php   foreach($uncomplete_details as $rs) : ?>
      <tr class="font-size-12 incomplete" id="row-<?php echo $rs->id; ?>">
        <td class="middle text-center td bc"><?php echo $rs->barcode; ?></td>
        <td class="middle td">
          <?php echo $rs->product_code; ?> :
          <?php if(empty($rs->old_code) OR $rs->old_code == $rs->product_code) : ?>
          <?php     echo $rs->product_name; ?>
          <?php else : ?>
          <?php     echo $rs->old_code; ?>
          <?php endif; ?>
        </td>
        <td class="middle text-center td"><?php echo number($rs->order_qty); ?></td>
        <td class="middle text-center td" id="prepared-<?php echo $rs->id; ?>"> <?php echo number($rs->prepared); ?></td>
        <td class="middle text-center td" id="qc-<?php echo $rs->id; ?>"><?php echo number($rs->qc); ?></td>
        <td class="middle text-right td">
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
          <input type="hidden" class="hidden-qc" id="<?php echo $rs->id; ?>" value="0"/>
          <input type="hidden" id="id-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" />
        </td>
      </tr>

<?php   endforeach; ?>

<?php else : ?>
      <tr><td colspan="6" class="text-center"><h4>--- No Data ---</td></tr>
<?php endif; ?>
        <tr>
          <td colspan="6" class="text-center">
            <div id="force-bar" class="<?php echo $show_force; ?>">
              <button type="button" class="btn btn-sm btn-danger not-show close-order" id="btn-force-close" onclick="forceClose()">Force Close</button>
              <label style="margin-left:25px;">
                <input type="checkbox" class="close-order ace" style="margin-right:10px;" id="chk-force-close"  />
                <span class="lbl">  Not Complete</span>
              </label>
            </div>
            <div class="<?php echo $show_close; ?>" id="close-bar">
              <button type="button" class="btn btn-sm btn-success close-order" id="btn-close" onclick="closeOrder()" <?php echo $disActive; ?>>
                Finish
              </button>
            </div>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>
