<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    <h3 class="title">
      <i class="fa fa-users"></i> <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right">
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add New</button>
      <?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class="title-block"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-sm-2">
    <label>Code</label>
    <input type="text" class="width-100" name="code" id="code" value="<?php echo $code; ?>" />
  </div>

  <div class="col-sm-2">
    <label>Name</label>
    <input type="text" class="width-100" name="name" id="name" value="<?php echo $name; ?>" />
  </div>

  <div class="col-sm-2">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-sm btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-sm-2">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="width-5 middle text-center">#</th>
					<th class="width-10 middle">Code</th>
					<th class="width-15 middle">Name</th>
					<th class="width-30 middle">Default Customer</th>
					<th class="width-15 middle text-center">Type code</th>
					<th class="width-5 middle text-center">Online</th>
					<th class="width-5 middle text-center">Default</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment(4) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->code; ?></td>
						<td class="middle"><?php echo $rs->name; ?></td>
						<td class="middle"><?php echo $rs->customer_name; ?></td>
						<td class="middle text-center"><?php echo $rs->type_code; ?></td>
						<td class="middle text-center">
						<?php if($this->pm->can_edit) : ?>
							<input type="hidden" id="online-<?php echo $rs->code; ?>" value="<?php echo $rs->is_online; ?>" />
								<a href="javascript:void(0)" id="online-label-<?php echo $rs->code; ?>" onclick="toggleOnline('<?php echo $rs->code; ?>')">
									<?php if($rs->is_online) : ?>
									<i class="fa fa-check green"></i>
									<?php else : ?>
									<i class="fa fa-times"></i>
									<?php endif; ?>
								</a>
						<?php else : ?>
							<?php if($rs->is_online) : ?>
							<i class="fa fa-check green"></i>
							<?php else : ?>
							<i class="fa fa-times"></i>
							<?php endif; ?>
						<?php endif; ?>
						</td>
						<td class="middle text-center">
							<?php if($rs->is_default) : ?>
								<i class="fa fa-check green"></i>
							<?php else : ?>
								<i class="fa fa-times"></i>
							<?php endif; ?>
						</td>

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
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/channels.js"></script>

<?php $this->load->view('include/footer'); ?>
