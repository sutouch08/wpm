<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12">
    	<h4 class="title"><?php echo $this->title; ?></h4>
	</div>
</div>
<hr style="border-color:#CCC; margin-top: 15px; margin-bottom:0px;" />

<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
  <li class="li-block active"><a href="#general" data-toggle="tab">General</a></li>
	<li class="li-block"><a href="#company" data-toggle="tab">Company</a></li>
	<li class="li-block"><a href="#system" data-toggle="tab">System</a></li>
	<li class="li-block"><a href="#inventory" data-toggle="tab">Inventory</a></li>
  <li class="li-block"><a href="#order" data-toggle="tab">Orders</a></li>
  <li class="li-block"><a href="#document" data-toggle="tab">Documents</a></li>
	<li class="li-block"><a href="#bookcode" data-toggle="tab">Book Code</a></li>
	<li class="li-block"><a href="#SAP" data-toggle="tab">SAP</a></li>
	<li class="li-block"><a href="#AGX" data-toggle="tab">AGX</a></li>
	<!--
	<li class="li-block"><a href="#WMS" data-toggle="tab">WMS</a></li>
	<li class="li-block"><a href="#chatbot" data-toggle="tab">ข้อมูล CHATBOT</a></li>
-->

</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1500px;">
<div class="tab-content" style="border:0px;">
<!---  ตั้งค่าทั่วไป  ----------------------------------------------------->
<?php $this->load->view('setting/setting_general'); ?>

<!---  ตั้งค่าบริษัท  ------------------------------------------------------>
<?php $this->load->view('setting/setting_company'); ?>

<!---  ตั้งค่าระบบ  ----------------------------------------------------->
<?php
		$this->load->view('setting/setting_system');
 ?>

<!---  ตั้งค่าออเดอร์  --------------------------------------------------->
<?php $this->load->view('setting/setting_order'); ?>

<!---  ตั้งค่าเอกสาร  --------------------------------------------------->
<?php $this->load->view('setting/setting_document'); ?>

<?php $this->load->view('setting/setting_bookcode'); ?>

<?php $this->load->view('setting/setting_sap'); ?>

<?php $this->load->view('setting/setting_inventory'); ?>

<?php //$this->load->view('setting/setting_wms'); ?>

<?php //$this->load->view('setting/setting_chatbot'); ?>

<?php $this->load->view('setting/setting_agx'); ?>


</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->


<script src="<?php echo base_url(); ?>scripts/setting/setting.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/setting/setting_document.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
