<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
  	<p class="pull-right top-p">
    <?php if($this->pm->can_add) : ?>
      <button type="button" class="btn btn-sm btn-success top-btn" onclick="addNew()"><i class="fa fa-plus"></i> Add New</button>
			<button type="button" class="btn btn-sm btn-primary top-btn" onclick="getUploadFile()"><i class="fa fa-upload"></i> Import</button>
    <?php endif; ?>
		<button type="button" class="btn btn-sm btn-purple top-btn" onclick="getTemplate()"><i class="fa fa-download"></i> template file</button>
    </p>
  </div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>รหัส</label>
    <input type="text" class="form-control" name="code" id="code" value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>ชื่อ</label>
    <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>บาร์โค้ด</label>
    <input type="text" class="form-control" name="barcode" id="barcode" value="<?php echo $barcode; ?>" />
  </div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6 padding-5">
    <label>สี</label>
    <input type="text" class="form-control" name="color" id="color" value="<?php echo $color; ?>" />
  </div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6 padding-5">
    <label>ไซส์</label>
    <input type="text" class="form-control" name="size" id="size" value="<?php echo $size; ?>" />
  </div>


	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>กลุ่ม</label>
    <select class="form-control" name="group" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_product_group($group); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5 last">
    <label>กลุ่มย่อย</label>
		<select class="form-control" name="sub_group" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_product_sub_group($sub_group); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5 ">
    <label>หมวดหมู่</label>
		<select class="form-control" name="category" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_product_category($category); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>ประเภท</label>
		<select class="form-control" name="kind" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_product_kind($kind); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>ชนิด</label>
		<select class="form-control" name="type" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_product_type($type); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>ยี่ห้อ</label>
		<select class="form-control" name="brand" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_product_brand($brand); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>ปี</label>
		<select class="form-control" name="year" onchange="getSearch()">
			<option value="">ทั้งหมด</option>
			<?php echo select_years($year); ?>
		</select>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-sm btn-primary btn-block"><i class="fa fa-search"></i> ค้นหา</button>
  </div>
	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-bordered table-hover" style="min-width:1150px;">
			<thead>
				<tr>
					<th class="fix-width-50 middle text-center">#</th>
					<th class="fix-width-120 text-center"></th>
					<th class="fix-width-200 middle text-center">SKU</th>
					<th class="fix-width-120 middle text-center">Barcode</th>
					<th class="fix-width-100 middle text-center">Model</th>
					<th class="fix-width-50 middle text-center">Color</th>
					<th class="fix-width-50 middle text-center">Size</th>
					<th class="fix-width-80 middle text-center">Price</th>
					<th class="fix-width-100 middle text-center">Group</th>
					<th class="fix-width-100 middle text-center">Category</th>
					<th class="fix-width-80 middle text-center">Year</th>
					<th class="fix-width-40 middle text-center">Sell</th>
					<th class="fix-width-40 middle text-center">Active</th>
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment(4) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr id="row-<?php echo $rs->code; ?>" class="font-size-12">
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle text-right">
							<button type="button" class="btn btn-minier btn-info" onclick="sendToSap('<?php echo $rs->code; ?>')"><i class="fa fa-send"></i></button>
							<?php if($this->pm->can_add) : ?>
								<button type="button" class="btn btn-minier btn-primary" onclick="duplicate('<?php echo $rs->code; ?>')">
									<i class="fa fa-copy"></i>
								</button>
							<?php endif; ?>
							<?php if($this->pm->can_edit) : ?>
								<button type="button" class="btn btn-minier btn-warning" onclick="getEdit('<?php echo $rs->code; ?>')">
									<i class="fa fa-pencil"></i>
								</button>
							<?php endif; ?>
							<?php if($this->pm->can_delete) : ?>
								<button type="button" class="btn btn-minier btn-danger" onclick="getDelete('<?php echo $rs->code; ?>', '<?php echo $rs->name; ?>')">
									<i class="fa fa-trash"></i>
								</button>
							<?php endif; ?>
						</td>
						<td class="middle"><?php echo $rs->code; ?></td>
						<td class="middle"><?php echo $rs->barcode; ?></td>
						<td class="middle"><?php echo $rs->style_code; ?></td>
						<td class="middle text-center"><?php echo $rs->color_code; ?></td>
						<td class="middle text-center"><?php echo $rs->size_code; ?></td>
						<td class="middle"><?php echo number($rs->price, 2); ?></td>
						<td class="middle"><?php echo $rs->group; ?></td>
						<td class="middle"><?php echo $rs->category; ?></td>
						<td class="middle text-center"><?php echo $rs->year; ?></td>
						<td class="middle text-center"><?php echo is_active($rs->can_sell); ?></td>
						<td class="middle text-center"><?php echo is_active($rs->active); ?></td>

					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<form id="edit-form" method="post" action="<?php echo $this->home; ?>/edit">
	<input type="hidden" id="item-code" name="itemCode" />
</form>
<?php $this->load->view('masters/product_items/import_items'); ?>

<script src="<?php echo base_url(); ?>scripts/masters/items.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
