<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label>Status</label>
    <select class="form-control input-sm" name="is_delete" onchange="getSearch()">
      <option value="all">All</option>
      <option value="1" <?php echo is_selected('1', $is_delete); ?>>Deleted</option>
      <option value="0" <?php echo is_selected('0', $is_delete); ?>>OK</option>
    </select>
  </div>

	<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>Completed Date</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="from-date" value="<?php echo $from_date; ?>">
      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="to-date" value="<?php echo $to_date; ?>">
    </div>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>

	<input type="hidden" name="search" value="1" />
</div>
<hr class="margin-top-15 padding-5">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:900px;">
      <thead>
        <tr>
          <th class="fix-width-40 text-center">#</th>
					<th class="fix-width-150">Document No.</th>
					<th class="fix-width-150">Files Name</th>
					<th class="fix-width-50">Size</th>
					<th class="fix-width-100 text-center">Status</th>
					<th class="fix-width-200">Date modified</th>
					<th class="fix-width-150">User</th>
					<th class="min-width-100"></th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($list))  : ?>
<?php $no = $this->uri->segment(5) + 1; ?>
<?php   foreach($list as $rs)  : ?>
				<input type="hidden" id="file-name-<?php echo $no; ?>" value="<?php echo $rs->file_name; ?>" />
        <tr class="font-size-12" id="row-<?php echo $no; ?>">
          <td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->code; ?></td>
          <td class="middle"><?php echo $rs->file_name; ?></td>
					<td class="middle"><?php echo ceil($rs->file_size/1024); ?> KB</td>
					<td class="middle text-center"><?php echo $rs->is_deleted ? '<span class="red">Deleted</span>' : '<span class="green">OK</span>'; ?></td>
					<td class="middle"><?php echo thai_date($rs->date_upd, TRUE); ?></td>
					<td class="middle"><?php echo $rs->user; ?></td>
          <td class="middle">
					<?php if( ! $rs->is_deleted) : ?>
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail(<?php echo $no; ?>)">View Details</button>
						<?php if($this->_SuperAdmin) : ?>
							<button type="button" class="btn btn-mini btn-danger" onclick="getDelete('<?php echo $rs->code; ?>', <?php echo $no; ?>)">Delete</button>
						<?php endif;?>
					<?php endif; ?>
					</td>
        </tr>
<?php  $no++; ?>
<?php endforeach; ?>
<?php else : ?>
      <tr>
        <td colspan="8" class="text-center"><h4>This folder is empty</h4></td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:95vw; max-width:95vw;">
		<div class="modal-content">
			<div class="modal-header" style="border-bottom:solid 1px #e5e5e5;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title-site text-center margin-top-5 margin-bottom-5" id="modal-title">Transfer detail</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" style="max-height:70vh; overflow:auto;">
						<table class="table table-striped table-bordered" style="table-layout: fixed; overflow:hidden; min-width:1320px;">
							<thead>
								<tr>
									<th class="fix-width-150 text-center">consignee</th>
									<th class="fix-width-150 text-center">address</th>
									<th class="fix-width-150 text-center">tel</th>
									<th class="fix-width-150 text-center">orderNumber</th>
									<th class="fix-width-100 text-center">date</th>
									<th class="fix-width-150 text-center">channels</th>
									<th class="fix-width-150 text-center">itemId</th>
									<th class="fix-width-60 text-center">qty</th>
									<th class="fix-width-60 text-center">price</th>
									<th class="fix-width-100 text-center">courier</th>
									<th class="fix-width-100 text-center">Shipping code</th>
								</tr>
							</thead>
							<tbody id="csv-detail">

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-success" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

<script id="detail-template" type="text/x-handlebarsTemplate">
	{{#each this}}
		<tr>
			<td class="" style="white-spce:nowrap; overflow:hidden;">{{consignee}}</td>
			<td class="" style="white-spce:nowrap; overflow:hidden;">{{address}}</td>
			<td class="" style="white-spce:nowrap; overflow:hidden;">{{tel}}</td>
			<td class="">{{order_number}}</td>
			<td class="">{{date}}</td>
			<td class="text-center">{{channels}}</td>
			<td class="">{{itemId}}</td>
			<td class="text-center">{{qty}}</td>
			<td class="text-center">{{price}}</td>
			<td class="">{{courier}}</td>
			<td class="">{{shipping_code}}</td>
		</tr>
	{{/each}}
</script>

<script>
	var HOME = BASE_URL + 'rest/V1/agx_delivery_completed/';

	function goBack() {
		window.location.href = HOME;
	}

	function clearFilter(){
		$.get(HOME + 'clear_filter', function(){
			goBack();
		});
	}




	function getSearch(){
		$('#searchForm').submit();
	}




	$('.search').keyup(function(e){
		if(e.keyCode == 13){
			getSearch();
		}
	});



	$('#from-date').datepicker({
		dateFormat:'dd-mm-yy',
		onClose:function(sd){
			$('#to-date').datepicker('option', 'minDate', sd);
		}
	});



	$('#to-date').datepicker({
		dateFormat:'dd-mm-yy',
		onClose:function(sd){
			$('#to-date').datepicker('option', 'maxDate', sd);
		}
	});

	function viewDetail(no) {
		let fileName = $('#file-name-'+no).val();

		load_in();

		$.ajax({
			url:HOME + "get_detail",
			type:'POST',
			cache:false,
			data:{
				'fileName' : fileName
			},
			success:function(rs) {
				load_out();
				if(isJson(rs)) {
					let ds = JSON.parse(rs);

					if(ds.status == 'success') {
						$('#modal-title').text(ds.code);
						let source = $('#detail-template').html();
						let data = ds.data;
						let output = $('#csv-detail');

						render(source, data, output);

						$('#detailModal').modal('show');
					}
					else {
						swal({
							title:'Error!',
							text: ds.message,
							type:'error'
						});
					}
				}
				else {
					swal({
						title:'Error !',
						text:rs,
						type:'error'
					});
				}
			},
			error:function(rs) {
				load_out();

				swal({
					title:'Error!',
					text:rs.responseText,
					type:'error'
				})
			}
		})
	}


	function getDelete(code, no) {
		swal({
			title:"Are you sure ?",
			text:'ต้องการลบ '+code+' หรือไม่ ?',
			type:'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6855',
			confirmButtonText: 'ใช่ ลบเลย',
			cancelButtonText: 'ยกเลิก',
			closeOnConfirm: false
		}, function() {
			doDelete(code, no);
		});
	}


	function doDelete(code, no){
		let fileName = $('#file-name-'+no).val();

		$.ajax({
			url:HOME + "delete",
			type:'POST',
			cache:false,
			data:{
				'code' : code,
				'fileName' : fileName
			},
			success:function(rs) {
				if(rs == 'success') {
					swal({
						title:'Deleted',
						type:'success',
						timer:1000
					});

					setTimeout(function(){
						$('#row-'+no).remove();
						reIndex();
					}, 1200);
				}
				else {
					swal({
						title:'Error',
						text:rs,
						type:'error'
					})
				}
			}
		})
	}
</script>

<?php $this->load->view('include/footer'); ?>
