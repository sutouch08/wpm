<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5">
    <h3 class="title title-xs"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
  	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
			<button type="button" class="btn btn-sm btn-default top-btn" onclick="getSample()">
	       <i class="fa fa-download"></i> Sample file
	     </button>
			<?php if(($this->pm->can_add OR $this->pm->can_edit) && $doc->status == 0) : ?>
				<button type="button" class="btn btn-sm btn-primary top-btn" onclick="getUploadFile()">
	        Import from Excel
	      </button>
				<?php if(empty($doc->ref_code)) : ?>
					<button type="button" class="btn btn-sm btn-info top-btn" onclick="getActiveCheckList()">
		        Import from WX
		      </button>
				<?php endif; ?>
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
				<button type="button" class="btn btn-sm btn-success top-btn" onclick="saveConsign()">
	        <i class="fa fa-save"></i> Save
	      </button>
				<?php endif; ?>
			<?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class=""/>
<form id="addForm" method="post" action="<?php echo $this->home; ?>/update">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Documrnt No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
  </div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Date</label>
    <input type="text" class="form-control input-sm text-center edit" name="date_add" id="date" value="<?php echo thai_date($doc->date_add); ?>" readonly disabled />
  </div>
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
		<label>Customer</label>
		<input type="text" class="form-control input-sm text-center edit" name="customerCode" id="customerCode" value="<?php echo $doc->customer_code; ?>" disabled>
	</div>
	<div class="col-lg-6 col-md-6-harf col-sm-6-harf col-xs-12 padding-5">
    <label class="not-show">ลูกค้า</label>
    <input type="text" class="form-control input-sm edit" name="customer" id="customer" value="<?php echo $doc->customer_name; ?>" disabled />
  </div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label>Currency</label>
		<select class="form-control input-sm edit" name="doc_currency" id="doc_currency" onchange="updateDocRate()" disabled>
			<?php echo select_currency($doc->DocCur); ?>
		</select>
  </div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label>Rate</label>
		<input type="number" class="form-control input-sm text-center edit" name="doc_rate" id="doc_rate" value="<?php echo $doc->DocRate; ?>" disabled />
  </div>

	<div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-6 padding-5">
		<label>Bin location</label>
		<input type="text" class="form-control input-sm text-center edit" id="zone_code" name="zone_code" value="<?php echo $doc->zone_code; ?>" disabled />
	</div>

	<div class="col-lg-4 col-md-6-harf col-sm-6 col-xs-12 padding-5">
    <label class="not-show">โซน[ฝากขาย]</label>
		<input type="text" class="form-control input-sm edit" name="zone" id="zone" value="<?php echo $doc->zone_name; ?>" disabled />
  </div>

	<div class="col-lg-5 col-md-10-harf col-sm-10-harf col-xs-9 padding-5">
    <label>Remark</label>
		<input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $doc->remark; ?>" disabled />
	</div>

<?php if($this->pm->can_edit) : ?>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"></i class="fa fa-pencil"></i> Edit</button>
    <button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="update()"><i class="fa fa-save"></i> Update</button>
  </div>
<?php endif; ?>
</div>
<hr class="margin-top-15">
<input type="hidden" name="consign_code" id="consign_code" value="<?php echo $doc->code; ?>">
<input type="hidden" name="auz" id="auz" value="<?php echo $auz; ?>">
</form>

<?php $this->load->view('account/consign_order/consign_order_control'); ?>
<?php $this->load->view('account/consign_order/consign_order_detail'); ?>


<div class="modal fade" id="check-list-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="width:400px;">
	 <div class="modal-content">
			 <div class="modal-header">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body" id="check-list-body">

			 </div>
			<div class="modal-footer">
			 <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			</div>
	 </div>
 </div>
</div>

<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="width:350px;">
	 <div class="modal-content">
			 <div class="modal-header">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			 <h4 class="modal-title">นำเข้าไฟล์ Excel</h4>
			</div>
			<div class="modal-body">
				<form id="upload-form" name="upload-form" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-sm-9 col-xs-9 padding-5">
						<button type="button" class="btn btn-sm btn-primary btn-block" id="show-file-name" onclick="getFile()">กรุณาเลือกไฟล์ Excel</button>
					</div>

					<div class="col-sm-3 col-xs-3 padding-5">
						<button type="button" class="btn btn-sm btn-info btn-block" onclick="uploadfile()"><i class="fa fa-cloud-upload"></i> นำเข้า</button>
					</div>
				</div>
				<input type="file" class="hide" name="uploadFile" id="uploadFile" accept=".xlsx" />
				<input type="hidden" name="555" />
				</form>
			 </div>
			<div class="modal-footer">

			</div>
	 </div>
 </div>
</div>


<script id="check-list-template" type="text/x-handlebarsTemplate">
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="width-30 text-center">วันที่</th>
					<th class="width-40 text-center">เอกสาร</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="check-list-table">
		 {{#each this}}
			 {{#if nodata}}
				 <tr>
					 <td colspan="3" class="text-center"><h4>ไม่พบรายการ</h4></td>
				 </tr>
			 {{else}}
					<tr>
						<td class="middle text-center">{{date_add}}</td>
						<td class="middle text-center">{{code}}</td>
						<td class="middle text-center">
							<button type="button" class="btn btn-xs btn-info btn-block" onclick="loadCheckDiff('{{code}}')">นำเข้ายอดต่าง</button>
						</td>
					</tr>
				{{/if}}
		 {{/each}}
			</tbody>
		</table>
	</div>
</div>
</script>

<script src="<?php echo base_url(); ?>scripts/account/consign_order/consign_order.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/account/consign_order/consign_order_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/account/consign_order/consign_order_control.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
