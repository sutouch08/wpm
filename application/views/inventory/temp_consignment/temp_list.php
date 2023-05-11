<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
		<div class="col-sm-6">
			<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-success" onclick="export_diff()">Export ยอดต่าง</button>
			</p>
		</div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>ลูกค้า/ผู้เบิก</label>
    <input type="text" class="form-control input-sm search" name="customer" value="<?php echo $customer; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label>สถานะ</label>
    <select class="form-control input-sm" name="status" onchange="getSearch()">
      <option value="all">ทั้งหมด</option>
      <option value="Y" <?php echo is_selected('Y', $status); ?>>เข้าแล้ว</option>
      <option value="N" <?php echo is_selected('N', $status); ?>>ยังไม่เข้า</option>
      <option value="E" <?php echo is_selected('E', $status); ?>>Error</option>
    </select>
  </div>

	<div class="col-sm-2 padding-5">
    <label>วันที่</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
    </div>
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
  <div class="col-sm-12">
    <p class="pull-right">
      สถานะ : ว่างๆ = ปกติ, &nbsp;
      <span class="red">ERROR</span> = เกิดข้อผิดพลาด, &nbsp;
      <span class="blue">NC</span> = ยังไม่เข้า SAP
    </p>
  </div>
  <div class="col-sm-12">
    <table class="table table-striped border-1 dataTable">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-10 text-center">วันที่</th>
          <th class="width-10">เลขที่เอกสาร </th>
          <th class="10">รหัสลูกค้า</th>
          <th class="15">ชื่อลูกค้า</th>
          <th class="width-10">เข้าถังกลาง</th>
          <th class="width-10">เข้า SAP</th>
          <th class="width-5 text-center">สถานะ</th>
					<th class="width-20">หมายเหตุ</th>
					<th class="width-5"></th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($orders))  : ?>
<?php $no = $this->uri->segment(4) + 1; ?>
<?php   foreach($orders as $rs)  : ?>

        <tr class="font-size-12">
          <td class="text-center"><?php echo $no; ?></td>

          <td class="text-center"><?php echo thai_date($rs->DocDate); ?></td>

          <td class="">
						<a href="javascript:void(0)" onclick="getInvoice('<?php echo $rs->U_ECOMNO; ?>')">
						<?php echo $rs->U_ECOMNO; ?>
						</a>
					</td>

          <td class=""><?php echo $rs->CardCode; ?></td>

          <td class="hide-text"><?php echo $rs->CardName; ?></td>

          <td class="" ><?php echo thai_date($rs->F_E_CommerceDate, TRUE); ?></td>

          <td class="">
						<?php
							if(!empty($rs->F_SapDate))
							{
								echo thai_date($rs->F_SapDate, TRUE);
							}
						 ?>
					</td>
					<td class="text-center">
            <?php if($rs->F_Sap === NULL) : ?>
              <span class="blue">NC</span>
            <?php elseif($rs->F_Sap === 'N') : ?>
              <span class="red">ERROR</span>
						<?php elseif($rs->F_Sap == 'Y') : ?>
							<span class="green">สำเร็จ</span>
            <?php endif; ?>
          </td>
          <td class="">
            <?php
            if($rs->F_Sap === 'N')
            {
              echo $rs->Message;
            }
            ?>
          </td>
					<td class="text-right">
						<button type="button" class="btn btn-minier btn-info" onclick="get_detail(<?php echo $rs->DocEntry; ?>)">
							<i class="fa fa-eye"></i>
					</td>
        </tr>
<?php  $no++; ?>
<?php endforeach; ?>
<?php else : ?>
      <tr>
        <td colspan="10" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<form id="reportForm" method="post" action="<?php echo $this->home; ?>/export_diff">
	<input type="hidden" id="token" name="token" value="<?php echo uniqid(); ?>">
</form>
<script src="<?php echo base_url(); ?>scripts/inventory/temp/temp_consignment_list.js?v=<?php echo date('YmdH'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
