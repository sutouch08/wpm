function doExport()
{
	var code = $('#move_code').val();
	load_in();
	$.ajax({
		url:HOME + 'export_move/' + code,
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


function deleteMoveItem(id, code)
{
	var code = $('#move_code').val();

  swal({
		title: 'Are you sure ?',
		text: 'Do you want to delete '+ code +' ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'Cancel',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:HOME + 'delete_detail/'+ id,
			type:"POST",
      cache:false,
			data:{
				'code' : code,
				'id' : id
			},
			success: function(rs) {
				var rs = $.trim(rs);
				if( rs == 'success' ) {
					swal({
						title:'Success',
						type: 'success',
						timer: 1000
					});

					$('#row-'+id).remove();
					reIndex();
					reCal();
				}else{
					swal("Error", rs, "error");
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


//------------  ตาราง move_detail
function getMoveTable(){
	var code	= $("#move_code").val();
	$.ajax({
		url: HOME + 'get_move_table/'+ code,
		type:"GET",
    cache:"false",
		success: function(rs){
			if( isJson(rs) ){
				var source 	= $("#moveTableTemplate").html();
				var data		= JSON.parse(rs);
				var output	= $("#move-list");
				render(source, data, output);
			}
		}
	});
}




function getTempTable(){
	var code = $("#move_code").val();
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




//--- เพิ่มรายการลงใน move detail
//---	เพิ่มลงใน move_temp
//---	update stock ตามรายการที่ใส่ตัวเลข
function addToMove(){
	var code	= $('#move_code').val();

	//---	โซนต้นทาง
	var from_zone = $("#from_zone_code").val();

	if(from_zone.length == 0)
	{
		swal('Invalid source location');
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
		swal('Error !', 'Please specify the number of items you want to move, at least 1 item.', 'warning');
		return false;
	}

	//---	ตัวแปรสำหรับเก็บ ojbect ข้อมูล
	var ds  = [];

	ds.push(
		{'name' : 'move_code', 'value' : code},
		{'name' : 'from_zone', 'value' : from_zone},
		{'name' : 'to_zone', 'value' : to_zone}
	);

	no = 0;
	var items = [];
	$('.input-qty').each(function(index, element) {
	    var qty = $(this).val();
			if( qty != '' && qty != 0 ){
				var pd_code  = $(this).data('products')
				item = {"code" : pd_code, "qty" : qty };
				items.push(item);
			}
    });

		ds.push({"name" : "items", "value" : JSON.stringify(items)});
		console.log(ds);
		//return false;

	if( count > 0 ){
		load_in();
		setTimeout(function(){
			$.ajax({
				url: HOME + 'add_to_move',
				type:"POST",
				cache:"false",
				data: ds ,
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
							showMoveTable();
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

		swal('Error !', 'Please specify the number of items you want to move, at least 1 item.', 'warning');

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
	let code = $('#move_code').val();

	if(canAccept) {
		$('#accept-modal').on('shown.bs.modal', () => $('#accept-note').focus());
		$('#accept-modal').modal('show');
	}
	else {

		swal({
			title:'Acception',
			text:'Do you agree to transfer products into your location ?',
			type:'info',
			showCancelButton:true,
			confirmButtonColor:'#87B87F',
			confirmButtonText:'Yes',
			cancelButtonText:'Cancel',
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
	let code = $('#move_code').val();
	let note = $.trim($('#accept-note').val());

	if(note.length < 10) {
		$('#accept-error').text('Please specify at least 10 characters in this note.');
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
