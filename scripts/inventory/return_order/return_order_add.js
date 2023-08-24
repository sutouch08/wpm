function toggleCheckAll(el) {
	if (el.is(":checked")) {
		$('.chk').prop("checked", true);
	} else {
		$('.chk').prop("checked", false);
	}
}


function deleteChecked(){
	load_in();

	setTimeout(function(){
		$('.chk:checked').each(function(){
			var id = $(this).data('id');
			var no = $(this).val();
			removeRow(no, id);
		})

		reIndex();
		recalTotal();
		load_out();
	}, 500)

}



function unsave(){
	var code = $('#return_code').val();

	swal({
		title: "Are you sure ?",
		text: "Do you want to unsave '"+code+"' ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: true
		}, function() {
			load_in();

			$.ajax({
				url:HOME + 'unsave/'+code,
				type:'POST',
				cache:false,
				success:function(rs) {
					load_out();
					if(rs === 'success') {
						setTimeout(function() {
							swal({
								title:'Success',
								type:'success',
								time:1000
							});

							setTimeout(function(){
								goEdit(code);
							}, 1500);
						}, 200);
					}
					else {
						setTimeout(function() {
							swal({
								title:'Error!',
								text:rs,
								type:'error'
							})
						}, 200);
					}
				}
			});
	});
}


function save()
{
	var code = $('#code').val();
	var error = 0;
	var ds = [];

	$('.input-qty').each(function() {
		let qty = parseDefault(parseFloat($(this).val()), 0);

		if( qty > 0) {
			let no = $(this).data('no');
			let arr = {
				'product_code' : $('#item_'+no).val(),
				'product_name' : $('#itemName_'+no).val(),
				'inv_qty' : $('#inv_qty_'+no).val(),
				'qty' : qty,
				'price' : $('#price_'+no).val(),
				'discount' : $('#discount_'+no).val(),
				'order_code' : $('#order_'+no).val(),
				'currency' : $('#currency_'+no).val(),
				'rate' : $('#rate_'+no).val()
			};

			ds.push(arr);
		}
		else {
			error++;
			swal('Please enter the qty completely.');
			$(this).addClass('has-error');
			return false;
		}
	});

	if(error > 0 ) {
		return false;
	}

	if(ds.length == 0) {
		return false;
	}

	load_in();

	$.ajax({
		url:HOME + 'add_details/'+code,
		type:'POST',
		contentType:"application/json; charset=utf-8",
		dataType:"json",
		data: JSON.stringify(ds),
		success:function(rs) {
			load_out();

			if(rs.status === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(() => {
					viewDetail(code);
				}, 1200);
			}
			else {
				swal({
					title:'Error!',
					type:'error',
					text:re.message
				});
			}
		},
		error:function(e) {
			load_out();
			setTimeout(() => {
				swal({
					title:'Error',
					text:e,
					type:'error'
				})
			}, 200);
		}
	})
}



function approve(){
	var code = $('#return_code').val();

	swal({
		title:'Approval',
		text:'Do you want to approve '+code+' ?',
		showCancelButton:true,
		confirmButtonColor:'#8bc34a',
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		closeOnConfirm:true
	}, () => {
		load_in();

		$.ajax({
			url:HOME + 'approve/'+code,
			type:'GET',
			cache:false,
			success:function(rs) {
				load_out();

				if(rs === 'success') {
					setTimeout(() => {
						swal({
							title:'Success',
							type:'success',
							timer:1000
						});

						setTimeout(() => {
							window.location.reload();
						}, 1200);
					}, 200);
				}
				else {
					setTimeout(() => {
						swal({
							title:'Error!',
							text:rs,
							type:'errr'
						}, () => {
							window.location.reload();
						});
					}, 200);
				}
			}
		});
	});
}



function unapprove() {
	var code = $('#return_code').val();
	swal({
		title:'Warning',
		text:'Do you want to unapprove ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonColor:'#DD6B55',
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		closeOnConfirm:true
	}, () => {
		load_in();

		$.ajax({
			url: HOME + 'unapprove/'+code,
			type:'GET',
			cache:false,
			success : function(rs) {
				load_out();
				if(rs === 'success') {
					setTimeout(() => {
						swal({
							title:'Success',
							type:'success',
							timer:1000
						});

						setTimeout(() => {
							window.location.reload();
						}, 1200);
					}, 200);
				}
				else {
					setTimeout(() => {
						swal({
							title:'Error',
							text:rs,
							type:'error'
						}, () => {
							window.location.reload();
						});
					}, 200);
				}
			}
		});
	});
}



function doExport(){
	var code = $('#return_code').val();
	$.get(HOME + 'export_return/'+code, function(rs){
		if(rs === 'success'){
			swal({
				title:'Success',
				type:'success',
				timer:1000
			});
			setTimeout(function(){
				viewDetail(code);
			}, 1500);
		}else{
			swal({
				title:'Error!',
				text:rs,
				type:'error'
			});
		}
	});
}


function sendToWms() {
	var code = $('#return_code').val();

	load_in();
	$.ajax({
		url:HOME + 'send_to_wms',
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



function editHeader(){
	$('.edit').removeAttr('disabled');
	$('#btn-edit').addClass('hide');
	$('#btn-update').removeClass('hide');
}


function updateHeader(){
	var code = $('#return_code').val();
	var date_add = $('#dateAdd').val();
	var c_invoice = $('#invoice_code').val();
	var invoice = $('#invoice').val();
	var customer_code = $('#customer_code').val();
	var warehouse_code = $('#warehouse_code').val();
	var zone_code = $('#zone_code').val();
	var is_wms = $('#is_wms').val();
	var api = $('#api').val();
	var reqRemark = $('#required_remark').val();
  var remark = $.trim($('#remark').val());
	var currency = $('#doc_currency').val();
	var rate = $('#doc_rate').val();

	if(!isDate(date_add)){
    swal('Invalid date');
    return false;
  }

	if(invoice.length == 0){
		swal('Invoice no is required');
		return false;
	}

	if(customer_code.length == 0){
		swal('Customer is required');
		return false;
	}

	if(warehouse_code.length == 0){
		swal('Please select warehouse');
		return false;
	}

	if(zone_code.length == 0){
		swal('Please specify bin location.');
		return false;
	}

	if(reqRemark == 1 && remark.length < 10) {
		swal({
			title:'Required',
			text:'Please put a note (at least 10 characters long)',
			type:'warning'
		});

		return false;
	}

	var data = {
		'return_code' : code,
		'date_add' : date_add,
		'invoice' : invoice,
		'customer_code' : customer_code,
		'warehouse_code' : warehouse_code,
		'zone_code' : zone_code,
		'doc_currency' : currency,
		'doc_rate' : rate,
		'remark' : remark
	};

	if(invoice != c_invoice) {
		swal({
			title:'Warning !',
			text:'The invoice has changed. The current items will be removed and reloaded according to the specified invoice. Do you want to process ?',
			type:'warning',
			showCancelButton:true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			closeOnConfirm: true
		}, function() {
			load_in();

			$.ajax({
				url:HOME + 'update',
				type:'POST',
				cache:false,
				data:data,
				success:function(rs){
					load_out();
					if( isJson(rs)) {
						let ds = JSON.parse(rs);

						if(ds.status == 'success') {
							$('.edit').attr('disabled', 'disabled');
							$('#btn-update').addClass('hide');
							$('#btn-edit').removeClass('hide');

							if(ds.reload == 'Y') {
								window.location.reload();
							}
						}
						else
						{
							swal({
								title:'Error!!',
								text:ds.message,
								type:'error'
							});
						}
					}
					else {
						swal({
							title:'Error!!',
							text:rs,
							type:'error'
						});
					}
				}
			})
		})
	}
	else {
		load_in();

		$.ajax({
			url:HOME + 'update',
			type:'POST',
			cache:false,
			data:data,
			success:function(rs){
				load_out();
				if( isJson(rs)) {
					let ds = JSON.parse(rs);

					if(ds.status == 'success') {
						$('.edit').attr('disabled', 'disabled');
						$('#btn-update').addClass('hide');
						$('#btn-edit').removeClass('hide');

						if(ds.reload == 'Y') {
							window.location.reload();
						}
					}
					else
					{
						swal({
							title:'Error!!',
							text:ds.message,
							type:'error'
						});
					}
				}
				else {
					swal({
						title:'Error!!',
						text:rs,
						type:'error'
					});
				}
			}
		})
	}
}


function remove_details(code) {
	load_in();
	$.ajax({
		url:HOME + 'remove_details/'+code,
		type:'POST',
		cache:false,
		success:function(rs) {
			if(rs == 'success') {
				window.location.reload();
			}
			else {
				load_out();
				setTimeout(() => {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					});
				}, 200);
			}
		}
	})
}



$('#dateAdd').datepicker({
	dateFormat:'dd-mm-yy'
});


async function updateDocRate() {
  let date = $('#dateAdd').val();
  let currency = $('#doc_currency').val();
  let rate = await getCurrencyRate(currency, date);
  $('#doc_rate').val(rate);
}

$('#dateAdd').change(function() {
  updateDocRate();
})


function addNew()
{
  var date_add = $('#dateAdd').val();
	var invoice = $('#invoice').val();
	var customer_code = $('#customer_code').val();
	var zone_code = $('#zone_code').val();
	var is_wms = $('#is_wms').val();
	let remark = $.trim($('#remark').val());
	let reqRemark = $('#required-remark').val();


  if(!isDate(date_add)){
    swal('Invalid date');
    return false;
  }

	if(invoice.length == 0){
		swal('Invoice no is required');
		return false;
	}

	if(customer_code.length == 0){
		swal('Customer is required');
		return false;
	}

	if(zone_code.length == 0){
		swal('Please specify bin location.');
		return false;
	}

	if(reqRemark == 1 && remark.length < 10) {
		swal({
			title:'Required',
			text:'Please put a note (at least 10 characters long)',
			type:'warning'
		});

		return false;
	}

	load_in();

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data: {
			"date_add" : date_add,
			"invoice" : invoice,
			"customer_code" : customer_code,
			"zone_code" : zone_code,
			"remark" : remark
		},
		success:function(rs) {
			if(isJson(rs)) {
				let ds = JSON.parse(rs);

				if(ds.status == 'success') {
					goEdit(ds.code);
				}
				else {
					load_out();

					setTimeout(() => {
						swal({
							title:'Error!',
							text: ds.message,
							type:'error'
						});
					}, 500);
				}
			}
			else {
				load_out();
				setTimeout(() => {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					})
				}, 500);
			}
		}
	});

  //$('#addForm').submit();
}



$('#warehouse').autocomplete({
	source:BASE_URL + 'auto_complete/get_warehouse_code_and_name',
	autoFocus:true,
	close:function(){
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#warehouse_code').val(arr[0]);
			$('#warehouse').val(arr[1]);
			zoneInit();
		}else{
			$('#warehouse_code').val('');
			$('#warehouse').val('');
		}
	}
});


$('#customer_code').autocomplete({
	source:BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus:true,
	close:function(){
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#customer_code').val(arr[0]);
			$('#customer').val(arr[1]);
		}
		else {
			$('#customer_code').val('');
			$('#customer').val('');
		}

		invoice_init();
	}
});

$('#customer').autocomplete({
	source:BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus:true,
	close:function() {
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#customer_code').val(arr[0]);
			$('#customer').val(arr[1]);
		}
		else {
			$('#customer_code').val('');
			$('#customer').val('');
		}

		invoice_init();
	}
});


function invoice_init() {
	let customer_code = $('#customer_code').val();

	customer_code = customer_code == "" ? "no_customer_selected" : encodeURIComponent(customer_code);

	$('#invoice').autocomplete({
		source:HOME + 'get_open_invoice_list/'+customer_code,
		autoFocus:true,
		select:function(event, ui) {
			console.log(ui);
	    $('#invoice').val(ui.item.invoice);
	    $('#customer_code').val(ui.item.customer_code);
			$('#customer').val(ui.item.customer_name);
			invoice_init();
	  }
	});
}


$('#zone_code').autocomplete({
	source : BASE_URL + 'auto_complete/get_zone_code_and_name',
	autoFocus:true,
	close:function(){
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#zone').val(arr[1]);
			$('#zone_code').val(arr[0]);
		}else{
			$('#zone').val('');
			$('#zone_code').val('');
		}
	}
});


$('#zone').autocomplete({
	source : BASE_URL + 'auto_complete/get_zone_code_and_name',
	autoFocus:true,
	close:function(){
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#zone').val(arr[1]);
			$('#zone_code').val(arr[0]);
		}else{
			$('#zone').val('');
			$('#zone_code').val('');
		}
	}
});


function recalRow(el, no) {
	var price = parseFloat($('#price_' + no).val());
	var qty = parseFloat(el.val());
	var discount = parseFloat($('#discount_' + no).val()) * 0.01;
	price = isNaN(price) ? 0 : price;
	qty = isNaN(qty) ? 0 : qty;
	discount = qty * (price * discount);
	var amount = (qty * price) - discount;
	amount = amount.toFixed(2);
	$('#amount_' + no).text(addCommas(amount));
	recalTotal();
}



function recalTotal(){
	var totalAmount = 0;
	var totalQty = 0;
	$('.amount-label').each(function(){
		let amount = removeCommas($(this).text());
		amount = parseDefault(parseFloat(amount), 0);
		totalAmount += amount;
	});

	$('.input-qty').each(function(){
		let qty = $(this).val();
		qty = parseDefault(parseFloat(qty), 0);
		totalQty += qty;
	});

	totalQty = totalQty.toFixed(2);
	totalAmount = totalAmount.toFixed(2);

	$('#total-qty').text(addCommas(totalQty));
	$('#total-amount').text(addCommas(totalAmount));
}



function removeRow(no, id){
	if(id != '' && id != '0' && id != 0){
		$.ajax({
			url:HOME + 'delete_detail/'+id,
			type:'GET',
			cache:false,
			success:function(rs){
				if(rs == 'success'){
					$('#row_' + no).remove();
					//reIndex();
					//recalTotal();
				}
				else
				{
					swal(rs);
					return false;
				}
			}
		});
	}
	else
	{
		$('#row_'+no).remove();
		// reIndex();
		// recalTotal();
	}
}


function accept() {
	$('#accept-modal').on('shown.bs.modal', () => $('#accept-note').focus());
	$('#accept-modal').modal('show');
}

function acceptConfirm() {
	let code = $('#return_code').val();
	let note = $.trim($('#accept-note').val());

	if(note.length < 10) {
		$('#accept-error').text('Please put a note (at least 10 characters long)');
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

			if(rs === 'success') {
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
					title:'Error!',
					text: rs,
					type:'error'
				});
			}
		}
	});

}


$(document).ready(function(){
	invoice_init();
	load_out();
});
