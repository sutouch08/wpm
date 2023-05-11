<?php $showZone = get_cookie('showZone') ? '' : 'hide'; ?>
<?php $showBtn  = get_cookie('showZone') ? 'hide' : '';  ?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr><td colspan="6" align="center">รายการที่ครบแล้ว</td></tr>
        <tr>
          <th class="width-15 middle text-center">บาร์โค้ด</th>
          <th class="width-50 middle text-center">สินค้า</th>
          <th class="width-5 middle text-center">จำนวน</th>
          <th class="width-5 middle text-center">จัดแล้ว</th>
          <th class="width-5 middle text-center">คงเหลือ</th>
          <th class="text-right">จัดจากโซน</th>
        </tr>
      </thead>
      <tbody id="complete-table">

<?php  if(!empty($complete_details)) : ?>
<?php   foreach($complete_details as $rs) : ?>
    <tr class="font-size-12">
      <td class="middle text-center"><?php echo $rs->barcode; ?></td>
      <td class="middle">
        <b class="blue">
        <?php echo (empty($rs->old_code) ? $rs->product_code : $rs->old_code); ?>
        </b>
          |
        <?php if($rs->old_code == $rs->product_code) : ?>
        <?php     echo $rs->product_name; ?>
        <?php else : ?>
        <?php     echo $rs->product_code; ?>
        <?php endif; ?>
      </td>
      <td class="middle text-center"><?php echo number($rs->qty); ?></td>
      <td class="middle text-center"><?php echo number($rs->prepared); ?></td>
      <td class="middle text-center"><?php echo number($rs->qty - $rs->prepared); ?></td>
      <td class="middle text-right">
        <button
          type="button"
          class="btn btn-default btn-xs btn-pop <?php echo $showBtn; ?>"
          data-container="body"
          data-toggle="popover"
          data-placement="left"
          data-trigger="focus"
          data-content="<?php echo $rs->from_zone; ?>"
          data-original-title=""
          title="">
          จากโซน
        </button>
        <span class="zoneLabel <?php echo $showZone; ?>" style="display:inline-block;">
            <?php echo $rs->from_zone; ?>
        </span>
        <button type="button" class="btn btn-minier btn-danger" onclick="removeBuffer('<?php echo $order->code; ?>', '<?php echo $rs->product_code; ?>')">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>
<?php endforeach; ?>
<?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>
