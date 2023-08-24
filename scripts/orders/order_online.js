// JavaScript Document

function viewImage(imageUrl)
{
	var image = '<img src="'+imageUrl+'" width="100%" />';
	$("#imageBody").html(image);
	$("#imageModal").modal('show');
}




function viewPaymentDetail()
{
	var order_code = $('#order_code').val();
	load_in();
	$.ajax({
		url: BASE_URL + 'orders/orders/view_payment_detail',
		type:"POST",
		cache:"false",
		data:{
			"order_code" : order_code
		},
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal('Error!', 'No information found.', 'error');
			}else{
				var source 	= $("#detailTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#detailBody");
				render(source, data, output);
				$("#confirmModal").modal('show');
			}
		}
	});
}






$("#emsNo").keyup(function(e) {
    if( e.keyCode == 13 )
	{
		saveDeliveryNo();
	}
});






function inputDeliveryNo()
{
	$("#deliveryModal").modal('show');
}






function saveDeliveryNo()
{
	var deliveryNo 	= $("#emsNo").val();
	var order_code 	= $("#order_code").val();
	if( deliveryNo != '')
	{
		$("#deliveryModal").modal('hide');
		$.ajax({
			url: BASE_URL + 'orders/orders/update_shipping_code/',
			type:"POST",
			cache:"false",
			data:{
				"shipping_code" : deliveryNo,
				"order_code" : order_code },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success')
				{
					window.location.reload();
				}
			}
		});
	}
}






function submitPayment()
{
	var order_code	= $("#order_code").val();
	var id_account	= $("#id_account").val();
	var acc_no 			= $('#acc_no').val();
	var image				= $("#image")[0].files[0];
	var payAmount		= $("#payAmount").val();
	var orderAmount = $("#orderAmount").val();
	var payDate			= $("#payDate").val();
	var payHour			= $("#payHour").val();
	var payMin			= $("#payMin").val();
	if( order_code == '' ){
		swal('Error !', 'Order ID not found, please log out of this page and log in again.', 'error');
		return false;
	}

	if( id_account == '' ){
		swal('Error !', 'Bank account information not found Please leave this page and try paying again.', 'error');
		return false;
	}

	if(acc_no == ''){
		swal('Error !', 'Account number not found Please leave this page and try again.', 'error');
		return false;
	}

	if( image == '' ){
		swal('Error !', 'Unable to read attached image data. Please attach the file again.', 'error');
		return false;
	}

	if( payAmount == 0 || isNaN( parseFloat(payAmount) ) || parseFloat(payAmount) < parseFloat(orderAmount) ){
		swal("Error", "Payment amount is incorrect", 'error');
		return false;
	}

	if( !isDate(payDate) ){
		swal('Invalid date');
		return false;
	}

	$("#paymentModal").modal('hide');

	var fd = new FormData();
	fd.append('image', $('input[type=file]')[0].files[0]);
	fd.append('order_code', order_code);
	fd.append('id_account', id_account);
	fd.append('acc_no', acc_no);
	fd.append('payAmount', payAmount);
	fd.append('orderAmount', orderAmount);
	fd.append('payDate', payDate);
	fd.append('payHour', payHour);
	fd.append('payMin', payMin);
	load_in();
	$.ajax({
		url: BASE_URL + 'orders/orders/confirm_payment',
		type:"POST",
		cache: "false",
		data: fd,
		processData:false,
		contentType: false,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success')
			{
				swal({
					title : 'Success',
					type: 'success',
					timer: 1000
				});

				clearPaymentForm();
				setTimeout(function(){
					window.location.reload();
				}, 1200);

			}
			else if( rs == 'fail' )
			{
				swal("Error !", "Data cannot be saved. Please try again.", "error");
			}
			else
			{
				swal("Error !", rs, "error");
			}
		}
	});
}




function readURL(input)
{
   if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#previewImg').html('<img id="previewImg" src="'+e.target.result+'" width="200px" alt="picture of your slip" />');
        }
        reader.readAsDataURL(input.files[0]);
    }
}






$("#image").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;
		if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
		{
			swal("Invalid file format", "Please select only jpg, jpeg, png or gif file extensions.", "error");
			$(this).val('');
			return false;
		}
		if( size > 2000000 )
		{
			swal("File size is too large", "Attachments must be less than 2 MB in size.", "error");
			$(this).val('');
			return false;
		}
		readURL(this);
		$("#btn-select-file").css("display", "none");
		$("#block-image").animate({opacity:1}, 1000);
	}
});





function clearPaymentForm()
{
	$("#id_account").val('');
	$("#payAmount").val('');
	$("#payDate").val('');
	$("#payHour").val('00');
	$("#payMin").val('00');
	removeFile();
}






function removeFile()
{
	$("#previewImg").html('');
	$("#block-image").css("opacity","0");
	$("#btn-select-file").css('display', '');
	$("#image").val('');
}





$("#payAmount").focusout(function(e) {
	if( $(this).val() != '' && isNaN(parseFloat($(this).val())) )
	{
		swal('Please enter the amount in numbers only.');
	}
});





function dateClick()
{
	$("#payDate").focus();
}





$("#payDate").datepicker({ dateFormat: 'dd-mm-yy'});





function selectFile()
{
	$("#image").click();
}





function payOnThis(id, acc_no)
{
	$("#selectBankModal").modal('hide');
	$.ajax({
		url:BASE_URL + 'orders/orders/get_account_detail/'+id,
		type:"POST",
		cache:"false",
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' )
			{
				swal('Error !', "Can't find the information you are looking for, please try again.", 'error');
			}else{
				var ds = rs.split(' | ');
				var logo 	= '<img src="'+ ds[0] +'" width="50px" height="50px" />';
				var acc	= ds[1];
				$("#id_account").val(id);
				$('#acc_no').val(acc_no);
				$("#logo").html(logo)
				$("#detail").html(acc);
				$("#paymentModal").modal('show');
			}
		}
	});
}





function payOrder()
{
	var order_code = $("#order_code").val();

	$.ajax({
		url: BASE_URL + 'orders/orders/get_pay_amount',
		type:"GET",
		cache:"false",
		data: {
			"order_code" : order_code
		},
		success: function(rs){
			var rs = $.trim(rs);
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);

				$("#orderAmount").val(ds.pay_amount);
				$("#payAmountLabel").text("ยอดชำระ "+ addCommas(ds.pay_amount) +" บาท");
				$("#selectBankModal").modal('show');
			}
			else {
				swal({
					title:"Error!",
					text:rs,
					type:'error'
				});
			}
		}
	});
}



function removeAddress(id)
{
	swal({
		title: 'Delete address ?',
		text: 'Do you really want to delete this address ? This action cannot be undone.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:BASE_URL + 'orders/orders/delete_shipping_address',
				type:"POST",
				cache:"false",
				data:{
					"id_address" : id
				},
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title : "Deleted",
							timer: 1000,
							type: "success"
						});
						reloadAddressTable();
					}else{
						swal("Error !!!", "Delete failed. Please try again.", "error");
					}
				}
			});
		});
}





//----------  edit address  -----------//
function editAddress(id)
{
	$.ajax({
		url:BASE_URL + 'orders/orders/get_shipping_address',
		type:"POST",
		cache:"false",
		data:{
			"id_address" : id
		},
		success: function(rs){
			var rs = $.trim(rs);
			if( isJson(rs) ){
				var ds = $.parseJSON(rs);
				$("#id_address").val(ds.id);
				$("#Fname").val(ds.name);
				$("#address1").val(ds.address);
				$("#sub_district").val(ds.sub_district);
				$('#district').val(ds.district);
				$("#province").val(ds.province);
				$("#postcode").val(ds.postcode);
				$('#country').val(ds.country);
				$("#phone").val(ds.phone);
				$("#email").val(ds.email);
				$("#alias").val(ds.alias);
				$("#addressModal").modal('show');
			}else{
				swal("Error !!", "Address not found.", "error");
			}
		}
	});
}


function setSender()
{
	var order_code = $('#order_code').val();
	var id_sender = $('#id_sender').val();

	if(id_sender == "" ) {
		swal("Please specify sender");
		return false;
	}

	if($('#sender option:selected').data('gen') == 1) {
		//--- gen tracking no
		//--- get prfix
		let prefix = $('#sender option:selected').data('prefix');
		prefix = prefix + order_code.replace('-', '');
		$('#tracking').val(prefix);
	}
	else {
		$('#tracking').val('');
	}


	$.ajax({
		url:BASE_URL + 'orders/orders/set_sender',
		type:'POST',
		cache:false,
		data:{
			'order_code' : order_code,
			'id_sender' : id_sender
		},
		success:function(rs) {
			if(rs == 'success') {
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
					type:'error'
				})
			}
		}
	})
}


//---------   ------------------//
function setAddress(id)
{
	var order_code = $('#order_code').val();
	$.ajax({
		url:BASE_URL + 'orders/orders/set_address',
		type:"POST",
		cache:"false",
		data:{
			"id_address" : id,
			"order_code" : order_code
		},
		success: function(rs){
			$(".btn-address").removeClass('btn-success');
			$("#btn-"+id).addClass('btn-success');
			$('#address_id').val(id);
		}
	});
}

function update_tracking() {
	var trackingNo = $('#tracking').val();
	var order_code = $('#order_code').val();
	$.ajax({
		url: BASE_URL + 'orders/orders/update_shipping_code/',
		type:"POST",
		cache:"false",
		data:{
			"shipping_code" : trackingNo,
			"order_code" : order_code },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success')
			{
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				$('#trackingNo').val(trackingNo);
			}
			else {
				swal({
					title:'Error!',
					type:'error',
					text:rs
				});
			}
		}
	});
}



function reloadAddressTable()
{
	var customer_code = $("#customerCode").val();
	var customer_ref = $('#customer_ref').val();
	$.ajax({
		url:BASE_URL + 'orders/orders/get_address_table',
		type:"POST",
		cache:"false",
		data:{
			'customer_code' : customer_code,
			'customer_ref' : customer_ref
		},
		success: function(rs){
			var rs = $.trim(rs);
			if(isJson(rs)){
				var source 	= $("#addressTableTemplate").html();
				var data 		= $.parseJSON(rs);
				var output 	= $("#adrs");
				render(source, data, output);
			}else{
				$("#adrs").html('<tr><td colspan="7" align="center">Not found</td></tr>');
			}
		}
	});
}






function saveAddress()
{
	var customer_code = $('#customerCode').val();
	var code 			= $('#customer_ref').val();
	var name			= $("#Fname").val();
	var addr			= $("#address1").val();
	var subdistrict = $('#sub_district').val();
	var district  = $('#district').val();
	var province  = $('#province').val();
	var country = $('#country').val() == "" ? "Thailand" : $('#country').val();
	var email			= $("#email").val();
	var alias 		= $("#alias").val();


	if( name == '' ){
		swal('Please specify consignee');
		return false;
	}

	if( addr.length == 0 ){
		swal('Please specify address');
		return false;
	}

	if(subdistrict.length == 0){
		swal('Please specify subdistrict');
		return false;
	}


	if(district.length == 0){
		swal('Please specify district');
		return false;
	}

	if(province.length == 0){
		swal('Please specify province');
		return false;
	}


	if( alias == '' ){
		swal('Alaias name is required');
		return false;
	}


	var ds = [];

	ds.push( {"name" : "id_address", "value" : $("#id_address").val() } );
	ds.push( {"name" : "customer_code", "value" : $("#customerCode").val() });
	ds.push( {"name" : "customer_ref", "value" : $("#customer_ref").val() } );
	ds.push( {"name" : "name", "value" : $("#Fname").val() } );
	ds.push( {"name" : "address", "value" : $("#address1").val() } );
	ds.push( {"name" : "sub_district", "value" : $("#sub_district").val() } );
	ds.push( {"name" : "district", "value" : $("#district").val() } );
	ds.push( {"name" : "province", "value" : $("#province").val() } );
	ds.push( {"name" : "postcode", "value" : $("#postcode").val() } );
	ds.push( {"name" : "country", "value" : country});
	ds.push( {"name" : "phone", "value" : $("#phone").val() } );
	ds.push( {"name" : "email", "value" : $("#email").val() } );
	ds.push( {"name" : "alias", "value" : $("#alias").val() } );

	$("#addressModal").modal('hide');

	load_in();
	$.ajax({
		url:BASE_URL + 'orders/orders/save_address',
		type:"POST",
		cache:"false",
		data: ds,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs === 'success'){
				reloadAddressTable();
				clearAddressField();
			}else{
				swal({
					title:'Error !',
					text:rs,
					type:'error'
				});
				$("#addressModal").modal('show');
			}
		}
	});
}





function addNewAddress()
{
	clearAddressField();
	$("#addressModal").modal('show');
}



$('#sub_district').autocomplete({
	source:BASE_URL + 'auto_complete/sub_district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 4){
			$('#sub_district').val(adr[0]);
			$('#district').val(adr[1]);
			$('#province').val(adr[2]);
			$('#postcode').val(adr[3]);
		}
	}
});


$('#district').autocomplete({
	source:BASE_URL + 'auto_complete/district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 3){
			$('#district').val(adr[0]);
			$('#province').val(adr[1]);
			$('#postcode').val(adr[2]);
		}
	}
});


$('#province').autocomplete({
	source:BASE_URL + 'auto_complete/province',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	}
})



$('#postcode').autocomplete({
	source:BASE_URL + 'auto_complete/postcode',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 4){
			$('#sub_district').val(adr[0]);
			$('#district').val(adr[1]);
			$('#province').val(adr[2]);
			$('#postcode').val(adr[3]);
			$('#postcode').focus();
		}
	}
})


function clearAddressField()
{
	$("#id_address").val('');
	$("#Fname").val('');
	$("#address1").val('');
	$('#sub_district').val('');
	$('#district').val('');
	$("#province").val('');
	$("#postcode").val('');
	$("#phone").val('');
	$("#email").val('');
	$("#alias").val('');
}



if($('#btn').length) {
	var clipboard = new Clipboard('#btn');
}




function Summary(){
	var amount 		= parseFloat( removeCommas($("#total-td").text() ) );
	var discount 	= parseFloat( removeCommas( $("#discount-td").text() ) );
	var netAmount = amount - discount;
	$("#netAmount-td").text( addCommas( parseFloat(netAmount).toFixed(2) ) );

}


function print_order(id)
{
	var wid = $(document).width();
	var left = (wid - 900) /2;
	window.open("controller/orderController.php?print_order&order_code="+id, "_blank", "width=900, height=1000, left="+left+", location=no, scrollbars=yes");
}



function getSummary()
{
	var order_code = $("#order_code").val();
	$.ajax({
		url:BASE_URL + 'orders/orders/get_summary',
		type:"POST",
		cache:"false",
		data:{
			"order_code" : order_code
		},
		success: function(rs){
			$("#summaryText").html(rs);
		}
	});

	$("#orderSummaryTab").modal("show");
}



$("#Fname").keyup(function(e){ if( e.keyCode == 13 ){ $("#address1").focus(); 	} });
$("#address1").keyup(function(e){ if( e.keyCode == 13 ){ $("#sub_district").focus(); 	} });
$("#phone").keyup(function(e){ if( e.keyCode == 13 ){ $("#email").focus(); 	} });
$("#email").keyup(function(e){ if( e.keyCode == 13 ){ $("#alias").focus(); 	} });
$("#alias").keyup(function(e){ if( e.keyCode == 13 ){ saveAddress(); } });
