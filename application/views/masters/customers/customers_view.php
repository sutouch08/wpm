<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
    <h3 class="title-xs"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
  	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-info top-btn" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
			<?php if($this->_SuperAdmin) : ?>
			<button type="button" class="btn btn-sm btn-primary top-btn" onclick="syncAllData()"><i class="fa fa-refresh"></i> Sync All</button>
      <button type="button" class="btn btn-sm btn-success top-btn" onclick="addNew()"><i class="fa fa-plus"></i> Add New</button>
    <?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Customer</label>
    <input type="text" class="form-control input-sm" name="code" id="code" value="<?php echo $code; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Group</label>
    <select class="form-control input-sm filter" name="group" id="customer_group">
			<option value="all">All</option>
			<?php echo select_customer_group($group); ?>
			<option value="NULL" <?php echo is_selected('NULL', $group); ?>>No group</option>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Kind</label>
    <select class="form-control input-sm filter" name="kind" id="customer_kind">
			<option value="all">All</option>
			<?php echo select_customer_kind($kind); ?>
			<option value="NULL" <?php echo is_selected('NULL', $kind); ?>>No Kind</option>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Type</label>
    <select class="form-control input-sm filter" name="type" id="customer_type">
			<option value="all">All</option>
			<?php echo select_customer_type($type); ?>
			<option value="NULL" <?php echo is_selected('NULL', $type); ?>>No Type</option>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>Region</label>
		<select class="form-control input-sm filter" name="area" id="customer_area">
			<option value="all">All</option>
			<?php echo select_customer_area($area); ?>
			<option value="NULL" <?php echo is_selected('NULL', $area); ?>>No Region</option>
		</select>
	</div>

	<div class="col-lg-1 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Grade</label>
    <select class="form-control input-sm filter" name="class" id="customer_class">
			<option value="all">All</option>
			<?php echo select_customer_class($class); ?>
			<option value="NULL" <?php echo is_selected('NULL', $class); ?>>No Grade</option>
		</select>
  </div>


	<div class="col-lg-1 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Status</label>
    <select class="form-control input-sm filter" name="status" id="status">
			<option value="all">All</option>
			<option value="1" <?php echo is_selected('1', $status); ?>>Active</option>
			<option value="0" <?php echo is_selected('0', $status); ?>>Disactive</option>
		</select>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
		<button type="submit" class="btn btn-xs btn-primary btn-block">Search</button>
  </div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Reset</button>
  </div>

</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover border-1" style="min-width:900px;">
			<thead>
				<tr>
					<th class="fix-width-80 text-center"></th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-200 middle">Name</th>
					<th class="fix-width-100 middle">Old Code</th>
					<th class="fix-width-100 middle">Group</th>
					<th class="fix-width-100 middle">Kind</th>
					<th class="fix-width-100 middle">Type</th>
					<th class="fix-width-60 middle text-center">Grade</th>
					<th class="fix-width-60 middle text-center">Status</th>
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment(4) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr style="font-size:11px;">
						<td class="text-right">
							<?php if($this->pm->can_edit) : ?>
								<button type="button" class="btn btn-mini btn-warning" onclick="getEdit('<?php echo $rs->code; ?>')">
									<i class="fa fa-pencil"></i>
								</button>
							<?php endif; ?>
							<?php if($this->pm->can_delete) : ?>
								<button type="button" class="btn btn-mini btn-danger" onclick="getDelete('<?php echo $rs->code; ?>', '<?php echo $rs->name; ?>')">
									<i class="fa fa-trash"></i>
								</button>
							<?php endif; ?>
						</td>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle">
							<?php echo $rs->code; ?>
						</td>
						<td class="middle"><?php echo $rs->name; ?></td>
						<td class="middle"><?php echo $rs->old_code; ?></td>
						<td class="middle"><?php echo $rs->group; ?></td>
						<td class="middle"><?php echo $rs->kind; ?></td>
						<td class="middle"><?php echo $rs->type; ?></td>
						<td class="middle"><?php echo $rs->class; ?></td>
						<td class="middle text-center">
							<?php echo is_active($rs->active); ?>
						</td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/customers.js"></script>

<?php $this->load->view('include/footer'); ?>
