
//--- properties for print
var prop 			= "width=800, height=900, left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;

//--- พิมพ์ใบนำส่งสำหรับแปะหน้ากล่อง
function printAddress()
{
	var order_code = $('#order_code').val();
	var customer_code = $('#customer_code').val();
	var customer_ref = $('#customer_ref').val();
	if( customer_ref != '' ){
		getOnlineAddress()
	}else{
		getAddressForm();
	}
}




//--- เอา id address online
function getOnlineAddress()
{
	var code = $("#customer_ref").val();
	var order_code = $("#order_code").val();
	$.ajax({
		url: BASE_URL + 'masters/address/get_online_address/'+ code,
		type:"GET",
		cache: false,
		success: function(id){
			var id = $.trim(id);
			if( id == 'noaddress' || isNaN( parseInt(id) ) ){
				noAddress();
			}else{
				printOnlineAddress(id, order_code);
			}
		}
	});
}




//--- ตรวจสอบว่าลูกค้ามีที่อยู่มากกว่า 1 ที่อยู่หรือไม่
//--- ถ้ามีมากกว่า 1 ที่อยู่ จะให้เลือกก่อนว่าจะให้ส่งที่ไหน ใช้ขนส่งอะไร
function getAddressForm()
{
	var order_code     = $("#order_code").val();
	var customer_code  = $("#customer_code").val();
	$.ajax({
		url: BASE_URL + 'masters/address/get_address_form',
		type:"POST",
    cache: "false",
    data:{
        "order_code" : order_code,
        "customer_code" : customer_code
    },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'no_address' ){
				noAddress();
			}else if( rs == 'no_sender' ){
				noSender();
			}else if( rs == 1 ){
				printPackingSheet();
			}else{
				$("#info_body").html(rs);
				$("#infoModal").modal("show");
			}
		}
	});
}





function printPackingSheet()
{
  var order_code = $("#order_code").val();
	var customer_code = $('#customer_code').val();
	var target = BASE_URL + 'masters/address/print_address_sheet/'+order_code+'/'+customer_code;
	window.open(target, "_blank", prop);
}





function printOnlineAddress(id, code)
{
	var center 	= ($(document).width() - 800)/2;
	var target 	= BASE_URL + 'masters/address/print_online_address/'+id+'/' + code;
	window.open(target, "_blank", prop );
}





function printSelectAddress()
{
	var order_code = $("#order_code").val();
	var customer_code   = $("#customer_code").val();
	var id_ad    = $('input[name=id_address]:radio:checked').val();
	var id_sen	 = $('input[name=id_sender]:radio:checked').val();
  var target   = BASE_URL + 'masters/address/print_address_sheet/'+order_code+'/'+customer_code+'/'+id_ad+'/'+id_sen;

	if( isNaN(parseInt(id_ad)) ){
    swal("Please select shpping address", "", "warning");
    return false;
  }

	if( isNaN(parseInt(id_sen)) ){
    swal("Please select shipper", "", "warning");
    return false;
  }

	$("#infoModal").modal('hide');


	window.open(target, "_blank", prop);
}




function noAddress()
{
	swal("Not found", "Customer address not found Please check if the customer already has an address in the system.", "warning");
}




function noSender()
{
	swal("Not found", "Shipper not found Please check the customer has already assigned the sender's name in the system or not.", "warning");
}
