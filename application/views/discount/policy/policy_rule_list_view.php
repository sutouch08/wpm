
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr style="background-image:none;">
          <th rowspan="2" class="width-5 middle text-center">#</th>
          <th rowspan="2" class="width-10 middle text-center">Code</th>
          <th rowspan="2" class="width-22 middle text-center">Description</th>
          <th rowspan="2" class="width-10 middle text-center">Discount</th>
          <th colspan="6" class="middle text-center">Conditions</th>
        </tr>
        <tr class="font-size-10" style="background-image:none;">
          <th class="width-8 text-center">Customer</th>
          <th class="width-8 text-center">Product</th>
          <th class="width-8 text-center">Channels</th>
          <th class="width-8 text-center">Payments</th>
          <th class="width-8 text-center">Minimum</th>
          <th class="width-10 text-center">action</th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($rules)) : ?>
  <?php $no = 1; ?>
  <?php foreach ($rules as $rs) : ?>
        <tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $rs->code; ?></td>
          <td class="middle"><?php echo $rs->name; ?></td>
          <td class="middle text-center"><?php echo showItemDiscountLabel($rs->item_price, $rs->item_disc, $rs->item_disc_unit); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_customer == 1 ? 'All' : 'condition'); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_product == 1 ? 'All' : 'condition'); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_channels == 1 ? 'All' : 'condition'); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_payment == 1 ? 'All' : 'condition'); ?></td>
          <td class="middle text-center"><?php echo ($rs->qty > 0 ? $rs->qty.' pcs' : ($rs->amount > 0 ? $rs->amount.' '.getConfig('CURRENTCY') : 'No')); ?></td>
          <td class="middle text-right">
            <?php if(empty($view_detail)) : ?>
            <button type="button" class="btn btn-xs btn-info" onclick="viewRuleDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
            <?php if($this->pm->can_edit) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="unlinkRule(<?php echo $rs->id; ?>, '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
          <?php endif; ?>
          </td>
        </tr>
  <?php   $no++; ?>
  <?php endforeach; ?>

<?php else : ?>
      <tr>
        <td colspan="10" class="text-center">
          <h4>Not found</h4>
        </td>
      </tr>

<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
