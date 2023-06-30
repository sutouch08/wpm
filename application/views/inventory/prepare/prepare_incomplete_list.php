<?php $showZone = get_cookie('showZone') ? '' : 'hide'; ?>
<?php $showBtn  = get_cookie('showZone') ? 'hide' : '';  ?>
<?php $checked  = get_cookie('showZone') ? 'checked' : ''; ?>


<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:900px;">
      <thead>
        <tr>
          <th class="fix-width-150 middle">Barcode</th>
          <th class="min-width-200 middle">Item Code</th>
          <th class="fix-width-100 middle text-center">Order Qty</th>
          <th class="fix-width-100 middle text-center">Picked Qty</th>
          <th class="fix-width-100 middle text-center">Balance Qty</th>
          <th class="fix-width-150 text-right">
            <label><input type="checkbox" id="showZone" style="margin-right:10px;" <?php echo $checked; ?> />Expand</label>
          </th>
        </tr>
      </thead>
      <tbody id="incomplete-table">

<?php  if(!empty($uncomplete_details)) : ?>
<?php   foreach($uncomplete_details as $rs) : ?>
    <tr class="font-size-12 incomplete" id="incomplete-<?php echo $rs->id; ?>">
      <td class="middle text-center b-click">
        <?php echo (empty($rs->barcode) ? $rs->product_code : $rs->barcode); ?>
      </td>
      <td class="middle">
        <b class="blue">
        <?php echo (empty($rs->old_code) ? $rs->product_code : $rs->old_code); ?>
        </b>  |
        <?php if($rs->old_code == $rs->product_code) : ?>
        <?php     echo $rs->product_name; ?>
        <?php else : ?>
        <?php     echo $rs->product_code; ?>
        <?php endif; ?>
      </td>
      <td class="middle text-center" id="order-qty-<?php echo $rs->id; ?>"><?php echo number($rs->qty); ?></td>
      <td class="middle text-center" id="prepared-qty-<?php echo $rs->id; ?>"><?php echo number($rs->prepared); ?></td>
      <td class="middle text-center" id="balance-qty-<?php echo $rs->id; ?>"><?php echo number($rs->qty - $rs->prepared); ?></td>
      <td class="middle text-right">
        <button
          type="button"
          class="btn btn-default btn-xs btn-pop <?php echo $showBtn; ?>"
          data-container="body"
          data-toggle="popover"
          data-placement="left"
          data-trigger="focus"
          data-content="<?php echo $rs->stock_in_zone; ?>"
          data-original-title=""
          title="">
          Location
        </button>
        <span class="zoneLabel <?php echo $showZone; ?>">
            <?php echo $rs->stock_in_zone; ?>
        </span>
      </td>
    </tr>
<?php endforeach; ?>
<?php
      $force = (!empty($uncomplete_details) ? '' : 'hide');
      $close = (!empty($uncomplete_details) ? 'hide' : '');
?>

    <tr>
      <td colspan="6" class="text-center">
        <div id="force-bar" class="">
          <button type="button" class="btn btn-sm btn-danger not-show" id="btn-force-close" onclick="forceClose()">
            <i class="fa fa-exclamation-triangle"></i>
            &nbsp; Force Close
          </button>
          <label style="margin-left:15px;">
            <input type="checkbox" id="force-close" class="ace" style="margin-right:5px;" onchange="toggleForceClose()" />
            <span class="lbl">  Not complete</span>
          </label>

        </div>


        <div id="close-bar" class="<?php echo $close; ?>">
          <button type="button" class="btn btn-sm btn-success" onclick="finishPrepare()">Finish</button>
        </div>

      </td>
    </tr>

<?php else : ?>

  <tr>
    <td colspan="6" class="text-center">
      <div id="close-bar">
        <button type="button" class="btn btn-sm btn-success" onclick="finishPrepare()">Finish</button>
      </div>
    </td>
  </tr>

<?php endif; ?>
      </tbody>
    </table>
  </div><!--/ col -->
</div><!--/ row-->
