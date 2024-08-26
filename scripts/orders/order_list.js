$(document).ready(function() {
	//---	reload ทุก 5 นาที
	setTimeout(function(){ goBack(); }, 300000);
});


function sendToWms(code) {
	load_in();
	$.ajax({
		url:BASE_URL + 'orders/orders/send_to_wms',
		type:'POST',
		cache:false,
		data:{
			'code' : code
		},
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
					title:"Error",
					text:rs,
					type:"error",
					html:true
				});
			}
		},
		error:function(xhr, status, error) {
			load_out();
			swal({
				title:'Error!!',
				type:'error',
				text:xhr.responseText,
				html:true
			})
		}
	})
}


function checkAll(el) {
	if(el.is(':checked')) {
		$('.int-chk').prop('checked', true);
	}
	else {
		$('.int-chk').prop('checked', false);
	}
}

var click = 0;

function processToDelivery() {
	if(click > 0) {
		return false;
	}
	else {
		click = 1;
	}

	if($('.int-chk:checked').length > 0) {
		let list = [];

		$('.int-chk:checked').each(function() {
			list.push($(this).val());
		});

		if(list.length > 0) {
			load_in();

			$.ajax({
				url:BASE_URL + 'inventory/delivery_order/process_delivery',
				type:'POST',
				cache:false,
				data:{
					'data' : JSON.stringify(list)
				},
				success:function(rs) {
					load_out();

					if(isJson(rs)) {
						let ds = JSON.parse(rs);

						if(ds.status == 'success') {
							swal({
								title:'Success',
								type:'success',
								timer:1000
							});

							setTimeout(() => {
								window.location.reload();
							}, 1200);
						}
						else {
							swal({
								title:'Some order goes wrong',
								text:ds.message,
								type:'warning',
								html:true
							}, function() {
								window.location.reload()
							});
						}
					}
					else {
						swal({
							title:'Error!',
							text:rs,
							type:'error',
							html:true
						})
					}
				},
				error:function(rs) {
					load_out();

					swal({
						title:'Error!',
						text:rs,
						type:'error',
						html:true
					})
				}
			})
		}
		else {
			click = 0;
		}
	}
	else {
		click = 0;
		swal("กรุณาเลือกรายการ");
		return false;
	}

}
