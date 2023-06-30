<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
</div>
<hr/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Item Code</label>
    <input type="text" class="form-control input-sm search" id="item_code" name="item_code"  value="<?php echo $item_code; ?>" />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Bin Location</label>
    <input type="text" class="form-control input-sm search" id="zone_code" name="zone_code" value="<?php echo $zone_code; ?>" />
  </div>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>System Bin Inc.</label>
    <select class="form-control input-sm" id="show_system" name="show_system">
			<option value="no" <?php echo is_selected('no', $show_system); ?>>No</option>
			<option value="yes" <?php echo is_selected('yes', $show_system); ?>>Yes</option>
		</select>
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block">Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Clear</button>
  </div>
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-purple btn-block" onclick="doExport()">Export Result</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<form id="exportFrom" method="post" action="<?php echo $this->home; ?>/export">
  <input type="hidden" id="item" name="item">
  <input type="hidden" id="zone" name="zone">
	<input type="hidden" id="system" name="system">
  <input type="hidden" id="token" name="token" value="<?php echo uniqid(); ?>">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:800px;">
      <tr>
        <th class="fix-width-40 text-center">#</th>
        <th class="fix-width-300">Items</th>
				<th class="fix-width-100 text-center">Balance</th>
        <th class="fix-width-150">Bin Code</th>
        <th class="min-width-200">Description</th>
      </tr>
      <tbody>
    <?php if( !empty($data)) : ?>
    <?php $no = $this->uri->segment(4) + 1; ?>
    <?php foreach($data as $rs) : ?>
      <tr class="font-size-12">
        <td class="text-center no"><?php echo $no; ?></td>
        <td><?php echo $rs->ItemCode . (empty($rs->U_OLDCODE) ? "" : " | {$rs->U_OLDCODE}"); ?></td>
				<td class="text-center"><?php echo number($rs->OnHandQty); ?></td>
        <td><?php echo $rs->BinCode; ?></td>
        <td><?php echo $rs->Descr; ?></td>
      </tr>
    <?php  $no++; ?>
    <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="5" class="text-center">--- No data ---</td>
      </tr>
    <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>

  function getSearch()
  {
    $('#searchForm').submit();
  }

  function clearFilter()
  {
    $.get(BASE_URL + 'inventory/consign_stock/clear_filter', function(){
      window.location.href = "<?php echo $this->home; ?>";
    });
  }

  $('.search').keyup(function(e){
    if(e.keyCode == 13){
      var item = $('#item_code').val();
      var zone = $('#zone_code').val();
      if(item.length > 0 || zone.length > 0){
        getSearch();
      }
    }
  })


  function doExport(){
    var item = $('#item_code').val();
    var zone = $('#zone_code').val();
		var system = $('#show_system').val();
    var token = $('#token').val();
    if(item.length > 0 || zone.length > 0)
    {
      $('#item').val(item);
      $('#zone').val(zone);
			$('#system').val(system);
      get_download(token);
      $('#exportFrom').submit();
    }
  }
</script>

<?php $this->load->view('include/footer'); ?>
