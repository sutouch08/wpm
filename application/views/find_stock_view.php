<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title title-xs"><?php echo $this->title; ?></h3>
  </div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>SKU</label>
    <input type="text" class="form-control text-center search" name="code" id="code" value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Description</label>
    <input type="text" class="form-control text-center search" name="name" id="name" value="<?php echo $name; ?>" />
  </div>

	<div class="col-lg-2-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Sell price</label>
		<div class="input-reduction">
		<select class="form-control input-discount" name="operater">
			<option value="equal">Equal</option>
			<option value="less_than" <?php echo is_selected('less_than', $operater); ?>> Less than </option>
			<option value="more_than" <?php echo is_selected('more_than', $operater); ?>> More than </option>
		</select>
		<input type="number" class="form-control input-unit search text-center" name="price" id="price" value="<?php echo $price; ?>" />
	</div>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Size</label>
    <input type="text" class="form-control text-center search" name="size" id="size" value="<?php echo $size; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Color group</label>
    <select class="form-control" name="color_group" id="color_group">
      <option value="">All</option>
      <?php echo select_color_group($color_group); ?>
    </select>
  </div>

  <div class="col-lg-3-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Warehouse</label>
    <select class="form-control" id="warehouse" name="warehouse">
      <option value="">All</option>
      <?php echo select_warehouse($warehouse); ?>
    </select>
  </div>


	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Group</label>
    <select class="form-control" name="group">
			<option value="">All</option>
			<?php echo select_product_group($group); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Category</label>
		<select class="form-control" name="category">
			<option value="">All</option>
			<?php echo select_product_category($category); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Kind</label>
		<select class="form-control" name="kind">
			<option value="">All</option>
			<?php echo select_product_kind($kind); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Type</label>
		<select class="form-control" name="type">
			<option value="">All</option>
			<?php echo select_product_type($type); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Brand</label>
		<select class="form-control" name="brand">
			<option value="">All</option>
			<?php echo select_product_brand($brand); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Year</label>
		<select class="form-control" name="year">
			<option value="">All</option>
			<?php echo select_years($year); ?>
		</select>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Clear</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-bordered table-hover" style="min-width:1000px;">
			<thead>
				<tr>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-60 middle">Image</th>
					<th class="fix-width-200 middle">SKU</th>
					<th class="fix-width-80 middle">Price</th>
					<th class="fix-width-80 middle text-center">In stock</th>
					<th class="fix-width-80 middle text-center">Reserved</th>
					<th class="fix-width-80 middle text-center">Available</th>
					<th class="fix-width-80 middle text-center">Color</th>
					<th class="fix-width-80 middle text-center">Size</th>
          <th class="min-width-250 middle">Description</th>
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment(4) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr id="row-<?php echo $rs->code; ?>" class="font-size-12">
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle text-center"><img src="<?php echo get_product_image($rs->code, 'mini'); ?>" /></td>
						<td class="middle"><?php echo $rs->code; ?></td>
						<td class="middle"><?php echo number($rs->price, 2); ?></td>
						<td class="middle text-center blue"><?php echo number($rs->OnHand); ?></td>
						<td class="middle text-center green"><?php echo number($rs->ordered); ?></td>
						<td class="middle text-center red"><?php echo number($rs->balance); ?></td>
						<td class="middle text-center"><?php echo $rs->color_code; ?></td>
						<td class="middle text-center"><?php echo $rs->size_code; ?></td>
            <td class="middle"><?php echo $rs->name; ?></td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
function clearFilter(){
  $.get(BASE_URL+'find_stock/clear_filter', function(){
    window.location.href = BASE_URL + 'find_stock';
  })
}


function getSearch()
{
	load_in();
	$('#searchForm').submit();
}


$('.search').keyup(function(e){
	if(e.keyCode == 13){
		getSearch();
	}
})
</script>

<?php $this->load->view('include/footer'); ?>
