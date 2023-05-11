<?php $this->load->view('include/header'); ?>
<div class="row top-row">
  <div class="col-sm-12 top-col">
    <h4 class="title"><?php echo $code; ?></h4>
  </div>
</div>
<hr/>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">No.</th>
          <th class="width-20">รหัสสินค้า</th>
          <th class="">ชื่อสินค้า</th>
          <th class="width-15">From</th>
          <th class="width-15">To</th>
          <th class="width-10 text-right">Qty</th>
          <th class="width-10 text-right">Bin Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($details)) : ?>
          <?php $no = 1; ?>
          <?php $qty = 0; ?>
          <?php $available = 0; ?>
          <?php foreach($details as $rs) : ?>
            <?php $hilight = ($rs->Quantity > $rs->onhand) ? 'color:red;' : ''; ?>
            <tr style="<?php echo $hilight; ?>">
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle"><?php echo $rs->ItemCode; ?></td>
              <td class="middle"><?php echo $rs->Dscription; ?></td>
              <td class="middle"><?php echo $rs->F_FROM_BIN; ?></td>
              <td class="middle"><?php echo $rs->F_TO_BIN; ?></td>
              <td class="middle text-right"><?php echo intval($rs->Quantity); ?></td>
              <td class="middle text-right"><?php echo $rs->onhand; ?></td>
            </tr>
            <?php $no++; ?>
            <?php $qty += $rs->Quantity; ?>
            <?php $available += $rs->onhand; ?>
          <?php endforeach; ?>
          <tr>
            <td colspan="5" class="text-right">Total</td>
            <td class="text-right"><?php echo number($qty); ?></td>
            <td class="text-right"><?php echo number($available); ?></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $this->load->view('include/footer'); ?>
