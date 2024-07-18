<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
</div><!-- End Row -->
<hr class=""/>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1 dataTable">
      <thead>
        <tr>
          <th class="fix-width-40 text-center">#</th>
					<th class="fix-width-200">Files</th>
          <th class="fix-width-200">Date modified </th>
					<th class="fix-width-100">Size </th>
					<th class="min-width-100"></th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($list))  : ?>
<?php $no = 1; ?>
<?php   foreach($list as $file)  : ?>
        <tr class="font-size-12" id="row-<?php echo $no; ?>">
          <td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo $file['name']; ?></td>
          <td class="middle"><?php echo $file['date_modify']; ?></td>
					<td class="middle"><?php echo $file['size']; ?></td>
          <td class="middle">
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail('<?php echo $file['name']; ?>')">View Details</button>
						<button type="button" class="btn btn-mini btn-primary" onclick="process('<?php echo $file['name']; ?>')">Process</button>
						<button type="button" class="btn btn-mini btn-danger" onclick="getDelete('<?php echo $file['name']; ?>', <?php echo $no; ?>)">Delete</button>
					</td>
        </tr>
<?php  $no++; ?>
<?php endforeach; ?>
<?php else : ?>
      <tr>
        <td colspan="5" class="text-center"><h4>This folder is empty XX</h4></td>
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
	function process(fileName) {
		load_in();
		$.ajax({
			url:BASE_URL + "rest/V1/agx_delivery_list/process_file/",
			type:'POST',
			cache:false,
			data:{
				'fileName' : fileName
			},
			success:function(rs) {
				load_out();
				if(rs == 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					setTimeout(function(){
						window.location.reload();
					}, 1200);
				}
				else {
					swal({
						title:'Error',
						type:'error',
						text:rs,
						html:true
					}, function() {
						window.location.reload();
					});
				}
			},
			error:function(xhr, status, error) {
				load_out();
				swal({
					title:'Error',
					type:'error',
					text:xhr.responseText,
					html:true
				}, function() {
					window.location.reload();
				})
			}
		})
	}


	function viewDetail(fileName) {
		load_in();

		$.ajax({
			url:BASE_URL + "rest/V1/agx_delivery_list/get_detail",
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


	function getDelete(fileName, no) {
		swal({
			title:"Are you sure ?",
			text:'ต้องการลบ '+fileName+' หรือไม่ ?',
			type:'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6855',
			confirmButtonText: 'ใช่ ลบเลย',
			cancelButtonText: 'ยกเลิก',
			closeOnConfirm: false
		}, function() {
			doDelete(fileName, no);
		});
	}


	function doDelete(fileName, no){
		$.ajax({
			url:BASE_URL + "rest/V1/agx_delivery_list/delete",
			type:'POST',
			cache:false,
			data:{
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
