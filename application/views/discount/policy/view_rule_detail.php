<?php
echo $this->printer->doc_header();
$currency = getConfig('CURRENTCY');
?>
<?php if(!$id_rule) : ?>
<?php    $sc .= "ERROR"; ?>
<?php else : ?>
<div class="container">
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <tr class="">
        <td class="width-15 middle text-right"><strong>Rule No</strong></td>
        <td class="width-20 middle"><?php echo $rule->code; ?></td>
        <td class="width-15 middle text-right"><strong>Rule Name</strong></td>
        <td class="width-50 middle" ><?php echo $rule->name; ?></td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Policy No</strong></td>
        <td class="middle"><?php echo empty($policy) ? '' : $policy->code; ?></td>
        <td class="middle text-right"><strong>Policy Name</strong></td>
        <td class="middle" ><?php echo empty($policy) ? '' : $policy->name; ?></td>
      </tr>
      <tr class="">
        <td class="middle text-right"><strong>Created at</strong></td>
        <td class="middle"><?php echo thai_date($rule->date_add); ?></td>
        <td class="middle text-right"><strong>User</strong></td>
        <td class="middle" ><?php echo $this->user_model->get_name($rule->user); ?></td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Update at</strong></td>
        <td class="middle"><?php echo thai_date($rule->date_upd); ?></td>
        <td class="middle text-right"><strong>User</strong></td>
        <td class="middle" ><?php echo $this->user_model->get_name($rule->update_user); ?></td>
      </tr>
      <tr class="">
        <td class="middle text-right"><strong>Discount</strong></td>
        <td class="middle">
          <?php echo $rule->item_disc; ?>
          <?php echo ($rule->item_disc_unit == 'amount' ? $currency : '%'); ?>
        </td>
        <td class="middle text-right"><strong>Net Price</strong></td>
        <td class="middle"><?php echo ($rule->item_price > 0 ? number($rule->item_price, 2).' '.$currency : 'No'); ?></td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Min - Qty</strong></td>
        <td class="middle"><?php echo ($rule->qty > 0 ? number($rule->qty) : 'No'); ?></td>
        <td class="middle text-right"><strong>Min - Amount</strong></td>
        <td class="middle"><?php echo ($rule->amount > 0 ? number($rule->amount, 2).' '.$currency : 'No'); ?></td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Can group</strong></td>
        <td class="middle"><?php echo $rule->canGroup == 1 ? 'Yes' : 'No'; ?></td>
      </tr>

      <tr>
        <td colspan="4" class="text-center"><strong>Customer</strong></td>
      </tr>
      <?php if($rule->all_customer == 1) : ?>
      <tr class="">
        <td class="middle text-right"><strong>Customer</strong></td>
        <td colspan="3"><?php echo 'ทั้งหมด'; ?></td>
      </tr>
      <?php endif; ?>
      <!------------ รายชื่อลูกค้าแบบกำหนดรายบุคคล --------->
      <?php if($rule->all_customer == 0) : ?>
      <?php   $qs = $this->discount_rule_model->getCustomerRuleList($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Customer List</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->code.' : '.$rs->name : ', '.$rs->code.' : '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!----------- จบรายชื่อลูกค้า  ------------>
      <!---------- กลุ่มลูกค้า ----------->
      <?php   $qs = $this->discount_rule_model->getCustomerGroupRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Customer List</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>

      <!---------- จบกลุ่มลูกค้า ----------->
      <!---------- ชนิดลูกค้า ----------->
      <?php   $qs = $this->discount_rule_model->getCustomerTypeRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Type</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบชนิดลูกค้า ----------->
      <!---------- ประเภทลูกค้า ----------->
      <?php   $qs = $this->discount_rule_model->getCustomerKindRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Kind</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบประเภทลูกค้า ----------->
      <!---------- เขตลูกค้า ----------->
      <?php   $qs = $this->discount_rule_model->getCustomerAreaRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Region</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบเชตลูกค้า ----------->
      <!---------- เกรดลูกค้า ----------->
      <?php   $qs = $this->discount_rule_model->getCustomerClassRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Grade</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบเกรดลูกค้า ----------->
      <?php endif; ?>
      <tr>
        <td colspan="4" class="text-center"><strong>Products</strong></td>
      </tr>
      <?php if($rule->all_product == 1) : ?>
      <tr class="">
        <td class="middle text-right"><strong>All Products</strong></td>
        <td colspan="3"><?php echo 'Yes'; ?></td>
      </tr>
      <?php endif; ?>

      <!------------ ถ้าไม่ได้เลือกสินค้าทั้งหมด แต่เลือกเป็นรุ่น --------->
      <?php if($rule->all_product == 0) : ?>
      <?php   $qs = $this->discount_rule_model->getProductStyleRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Model</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->code : ', '.$rs->code; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!----------- จบรุ่นสินค้า  ------------>

      <!---------- กลุ่มสินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductGroupRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Group</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบกลุ่มสินค้า ----------->
      <!---------- กลุ่มย่อยสินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductSubGroupRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Sub Group</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบกลุ่มย่อยสินค้า ----------->
      <!---------- ชนิดสินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductTypeRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Type</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบชนิดสินค้า ----------->
      <!---------- ประเภทสินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductKindRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Kind</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบประเภทสินค้า ----------->
      <!---------- หมวดหมู่สินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductCategoryRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Category</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบหมวดหมู่สินค้า ----------->
      <!---------- ยี่ห้อสินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductBrandRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Brand</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบยี่ห้อสินค้า ----------->
      <!---------- ปีสินค้า ----------->
      <?php   $qs = $this->discount_rule_model->getProductYearRule($id_rule); ?>
      <?php   if($qs->num_rows() > 0) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Year</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs->result() as $rs) : ?>
          <?php echo $i == 1 ? $rs->year : ', '.$rs->year; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบปีสินค้า ----------->

    <?php endif; ?>

    <tr>
      <td colspan="4" class="text-center"><strong>Sales channels and payments</strong></td>
    </tr>
    <tr class="">
      <td class="middle text-right"><strong>Sales channels</strong></td>
      <td colspan="3">
        <?php if($rule->all_channels == 1) : ?>
            All
        <?php else : ?>
          <?php $qs = $this->discount_rule_model->getChannelsRule($id_rule); ?>
          <?php if($qs->num_rows() > 0) : ?>
            <?php $i = 1; ?>
            <?php foreach($qs->result() as $rs) : ?>
              <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
            <?php endforeach; ?>
          <?php endif; ?>

        <?php endif; ?>
      </td>
    </tr>
    <tr class="">
      <td class="middle text-right"><strong>Payments</strong></td>
      <td colspan="3">
        <?php if($rule->all_payment == 1) : ?>
            All
        <?php else : ?>
          <?php $qs = $this->discount_rule_model->getPaymentRule($id_rule); ?>
          <?php if($qs->num_rows() > 0) : ?>
            <?php $i = 1; ?>
            <?php foreach($qs->result() as $rs) : ?>
              <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
            <?php endforeach; ?>
          <?php endif; ?>

        <?php endif; ?>
      </td>
    </tr>

    </table>
  </div>
</div>
</div>
<?php endif; ?>
<?php
echo $this->printer->doc_footer();
 ?>
