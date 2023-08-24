function doExport()
{
	var code = $('#transfer_code').val();
	load_in();
	$.ajax({
		url:HOME + 'export_transfer/' + code,
		type:'POST',
		cache:false,
		success:function(rs){
			load_out();
			if(rs == 'success'){
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}else{
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}


function sendToWms() {
	var code = $('#transfer_code').val();

	load_in();
	$.ajax({
		url:HOME + 'send_to_wms/'+code,
		type:'POST',
		cache:false,
		success:function(rs) {
			load_out();
			var rs = $.trim(rs);
			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error',
					html:true
				});
			}
		},
		error:function(xhr, status, error) {
			load_out();
			swal({
				title:'Error!',
				text:xhr.responseText,
				type:'error',
				html:true
			})
		}
	})
}

function deleteMoveItem(id, item_code)
{
	var code = $('#transfer_code').val();

  swal({
		title: 'Are you sure ?',
		text: 'Do you want to delete '+ item_code +' ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: true
	}, function() {
		load_in();

		$.ajax({
			url:HOME + 'delete_detail',
			type:"POST",
      cache:"false",
			data:{
				'code' : code,
				'id' : id
			},
			success: function(rs) {
				load_out();
				var rs = $.trim(rs);
				if( rs == 'success' ) {
					setTimeout(() => {
						swal({
							title:'Success',
							type: 'success',
							timer: 1000
						});

						$('#row-'+id).remove();
						reIndex();
						reCal();
					}, 200);

				}
				else {
					setTimeout(() => {
						swal({
							title:'Error!',
							text: rs,
							type:'error'
						});
					}, 200);
				}
			}
		});
	});
}


function reCal(){
	var total = 0;
	$('.qty').each(function(){
		var qty = parseInt(removeCommas($(this).text()));
		if(!isNaN(qty))
		{
			total += qty;
		}
	});

	$('#total').text(addCommas(total));
}


//------------  ตาราง transfer_detail
function getTransferTable(){
	var code	= $("#transfer_code").val();
	$.ajax({
		url: HOME + 'get_transfer_table/'+ code,
		type:"GET",
    cache:"false",
		success: function(rs){
			if( isJson(rs) ){
				var source 	= $("#transferTableTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#transfer-list");
				render(source, data, output);
			}
		}
	});
}




function getTempTable(){
	var code = $("#transfer_code").val();
	$.ajax({
		url: HOME + 'get_temp_table/'+code,
		type:"GET",
    cache:"false",
		success: function(rs){
			if( isJson(rs) ){
				var source 	= $("#tempTableTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#temp-list");
				render(source, data, output);
			}
		}
	});
}




//--- เพิ่มรายการลงใน transfer detail
//---	เพิ่มลงใน transfer_temp
//---	update stock ตามรายการที่ใส่ตัวเลข
function addToTransfer(){
	var code	= $('#transfer_code').val();

	//---	โซนต้นทาง
	var from_zone = $("#from_zone_code").val();

	if(from_zone.length == 0)
	{
		swal('Invalid source loaction');
		return false;
	}

	//--- โซนปลายทาง
	var to_zone = $('#to_zone_code').val();
	if(to_zone.length == 0)
	{
		swal('Invalid destination location');
		return false;
	}

	//---	จำนวนช่องที่มีการป้อนตัวเลขเพื่อย้ายสินค้าออก
	var count  = countInput();
	if(count == 0)
	{
		swal('Error !', 'Please specify the number of items in at least 1 item to be moved.', 'warning');
		return false;
	}

	//---	ตัวแปรสำหรับเก็บ ojbect ข้อมูล
	var ds  = {};
	var items = [];

	ds.transfer_code = code;
	ds.from_zone = from_zone;
	ds.to_zone = to_zone;



	$('.input-qty').each(function(index, element) {
	    let qty = parseDefault(parseInt($(this).val()),0);

			if(qty > 0) {
				let pd_code  = $(this).attr('id')
				items.push({"item_code" : pd_code, "qty" : qty});
			}
    });

	ds.items = items;

	if( count > 0 ) {
		load_in();
		setTimeout(function(){
			$.ajax({
				url: HOME + 'add_to_transfer',
				type:"POST",
				cache:"false",
				data: {
					"data" : JSON.stringify(ds)
				},
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title: 'success',
							type: 'success',
							timer: 1000
						});

						setTimeout( function(){
							showTransferTable();
						}, 1200);

					}else{

						swal("Error", rs, "error");
					}
				}
			});
		}, 500);
	}
	else
	{
		swal('Error !', 'Please specify the number of items in at least 1 item to be moved.', 'warning');
	}
}



function selectAll(){
	$('.input-qty').each(function(index, el){
		var qty = $(this).attr('max');
		$(this).val(qty);
	});
}


function clearAll(){
	$('.input-qty').each(function(index, el){
		$(this).val('');
	});
}




//----- นับจำนวน ช่องที่มีการใส่ตัวเลข
function countInput(){
	var count = 0;
	$(".input-qty").each(function(index, element) {
        count += ($(this).val() == "" ? 0 : 1 );
    });
	return count;
}


function accept() {
	let canAccept = $('#can-accept').val() == 1 ? true : false;
	let code = $('#transfer_code').val();

	if(canAccept) {
		$('#accept-modal').on('shown.bs.modal', () => $('#accept-note').focus());
		$('#accept-modal').modal('show');
	}
	else {

		swal({
			title:'Acception',
			text:'Do you agree to transfer the goods to your location ?',
			type:'info',
			showCancelButton:true,
			confirmButtonColor:'#87B87F',
			confirmButtonText:'Yes',
			cancelButtonText:'No',
			closeOnConfirm:true
		}, function() {
			load_in();

			$.ajax({
				url:HOME + 'accept_zone',
				type:'POST',
				cache:false,
				data: {
					'code' : code
				},
				success:function(rs) {
					load_out();
					if(isJson(rs))
					{
						let ds = JSON.parse(rs);
						if(ds.status === 'success') {
							swal({
								title:'Success',
								type:'success',
								timer:1000
							});

							setTimeout(() => {
								window.location.reload();
							}, 1200);
						}
						else if(ds.status === 'warning') {

							swal({
								title:'Warning',
								text:ds.message,
								type:'warning'
							}, () => {
								setTimeout(() => {
									window.location.reload();
								}, 500);
							});
						}
						else {
							swal({
								title:'Error!',
								text: rs,
								type:'error'
							});
						}
					}
				}
			})
		})
	}
}


function acceptConfirm() {
	let code = $('#transfer_code').val();
	let note = $.trim($('#accept-note').val());

	if(note.length < 10) {
		$('#accept-error').text('Please enter at least 10 characters in this remark.');
		return false;
	}
	else {
		$('#accept-error').text('');
	}

	load_in();

	$.ajax({
		url:HOME + 'accept_confirm',
		type:'POST',
		cache:false,
		data:{
			"code" : code,
			"accept_remark" : note
		},
		success:function(rs) {
			load_out();
			if(isJson(rs))
			{
				let ds = JSON.parse(rs);
				if(ds.status === 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					setTimeout(() => {
						window.location.reload();
					}, 1200);
				}
				else if(ds.status === 'warning') {

					swal({
						title:'Warning',
						text:ds.message,
						type:'warning'
					}, () => {
						setTimeout(() => {
							window.location.reload();
						}, 500);
					});
				}
				else {
					swal({
						title:'Error!',
						text: rs,
						type:'error'
					});
				}
			}
		}
	});
}
