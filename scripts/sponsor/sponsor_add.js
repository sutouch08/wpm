$('#date').datepicker({
  dateFormat:'dd-mm-yy'
});




//---- เปลี่ยนสถานะออเดอร์  เป็นบันทึกแล้ว
function saveOrder(){
  var order_code = $('#order_code').val();
	$.ajax({
		url: BASE_URL + 'orders/sponsor/save/'+ order_code,
		type:"POST",
    cache:false,
		success:function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
          title: 'Saved',
          type: 'success',
          timer: 1000
        });

				setTimeout(function(){
          editOrder(order_code)
        }, 1200);

			}else{
				swal("Error ! ", rs , "error");
			}
		}
	});
}


$("#customerCode").autocomplete({
	source: BASE_URL + 'auto_complete/get_sponsor',
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var code = arr[0];
			var name = arr[1];
			$("#customerCode").val(code);
			$("#customer").val(name);
      getBudget(code);
		}else{
			$("#customerCode").val('');
			$('#customer').val('');
      $('#budgetLabel').val('');
      $('#budgetAmount').val(0);
		}
	}
});

$("#customer").autocomplete({
	source: BASE_URL + 'auto_complete/get_sponsor',
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var code = arr[0];
			var name = arr[1];
			$("#customerCode").val(code);
			$("#customer").val(name);
      getBudget(code);
		}else{
			$("#customerCode").val('');
			$("#customer").val('');
      $('#budgetLabel').val('');
      $('#budgetAmount').val(0);
		}
	}
});


function getBudget(code){
  $.ajax({
    url:BASE_URL + 'orders/sponsor/get_sponsor_budget/'+code,
    type:'GET',
    cache:false,
    success:function(rs) {
      $('#budgetAmount').val(rs);
      $('#budgetLabel').val(addCommas(rs));
    }
  });
}



$('#customer').focusout(function(){
  var code = $(this).val();
  if(code.length == 0)
  {
    $('#customerCode').val('');
    $('#budgetLabel').val('');
    $('#budgetAmount').val(0);
  }
});

$('#customerCode').focusout(function(){
  var code = $(this).val();
  if(code.length == 0)
  {
    $('#customer').val('');
    $('#budgetLabel').val('');
    $('#budgetAmount').val(0);
  }
});


async function updateDocRate() {
  let date = $('#date').val();
  let currency = $('#doc_currency').val();
  let rate = await getCurrencyRate(currency, date);
  $('#doc_rate').val(rate);
}

$('#date').change(function() {
  updateDocRate();
})

function add(){
  var customer_code = $('#customerCode').val();
  var customer_name = $('#customer').val();
  var date_add = $('#date').val();
  var empName = $('#empName').val();
  var warehouse = $('#warehouse').val();
  var manualCode = $('#manualCode').val();

  if(customer_code.length == 0 || customer_name.length == 0){
    swal('Invalid Customer');
    return false;
  }

  if(!isDate(date_add))
  {
    swal('Invalid date');
    return false;
  }

  if(empName.length == 0)
  {
    swal('Invalid customer');
    return false;
  }

  if(warehouse == ""){
    swal('Please select warehouse');
    return false;
  }


  if(manualCode == 1){
    validateOrder();
  }
  else
  {
    $('#addForm').submit();
  }

}


function validateOrder(){
  var prefix = $('#prefix').val();
  var runNo = parseInt($('#runNo').val());
  let code = $('#code').val();

  if(code.length == 0){
    $('#addForm').submit();
    return false;
  }

  let arr = code.split('-');

  if(arr.length == 2){
    if(arr[0] !== prefix){
      swal('Prefix must be '+prefix);
      return false;
    }else if(arr[1].length != (4 + runNo)){
      swal('Run Number is not valid');
      return false;
    }else{
      $.ajax({
        url: BASE_URL + 'orders/orders/is_exists_order/'+code,
        type:'GET',
        cache:false,
        success:function(rs){
          if(rs == 'not_exists'){
            $('#addForm').submit();
          }else{
            swal({
              title:'Error!!',
              text: rs,
              type: 'error'
            });
          }
        }
      })
    }

  }else{
    swal('Invalid document no');
    return false;
  }
}


var customer;
var channels;
var payment;
var date;


function getEdit(){
  let approved = $('#is_approved').val();
  if(approved == 1){
    $('#remark').removeAttr('disabled');
  } else {
    $('.edit').removeAttr('disabled');
  }

  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
  customer = $("#customerCode").val();
	channels = $("#channels").val();
	payment  = $("#payment").val();
	date = $("#date").val();
}


//---- เพิ่มรายการสินค้าเช้าออเดอร์
function addToOrder(){
  var order_code = $('#order_code').val();
	//var count = countInput();
  var data = [];
  $(".order-grid").each(function(index, element){
    if($(this).val() != ''){
      var code = $(this).attr('id');
      var arr = code.split('qty_');
      data.push({'code' : arr[1], 'qty' : $(this).val()});
    }
  });

	if(data.length > 0 ){
		$("#orderGrid").modal('hide');
		$.ajax({
			url: BASE_URL + 'orders/orders/add_detail/'+order_code,
			type:"POST",
      cache:"false",
      data: {
        'data' : data
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
					$("#btn-save-order").removeClass('hide');
					updateDetailTable(); //--- update list of order detail
				}else{
					swal("Error", rs, "error");
				}
			}
		});
	}
}




// JavaScript Document
function updateDetailTable(){
	var order_code = $("#order_code").val();
	$.ajax({
		url: BASE_URL + 'orders/orders/get_detail_table/'+order_code,
		type:"GET",
    cache:"false",
		success: function(rs){
			if( isJson(rs) ){
				var source = $("#detail-table-template").html();
				var data = $.parseJSON(rs);
				var output = $("#detail-table");
				render(source, data, output);
			}
			else
			{
				var source = $("#nodata-template").html();
				var data = [];
				var output = $("#detail-table");
				render(source, data, output);
			}
		}
	});
}



function removeDetail(id, name){
	swal({
		title: "Are you sure ?",
		text: "Do you really want to delete '" + name + "' ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url: BASE_URL + 'orders/orders/remove_detail/'+ id,
				type:"POST",
        cache:"false",
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', type: 'success', timer: 1000 });
						updateDetailTable();
					}else{
						swal("Error !", rs , "error");
					}
				}
			});
  });
}




$("#pd-box").autocomplete({
	source: BASE_URL + 'auto_complete/get_style_code',
	autoFocus: true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    $(this).val(arr[0]);
  }
});




$('#pd-box').keyup(function(event) {
	if(event.keyCode == 13){
		var code = $(this).val();
		if(code.length > 0){
			setTimeout(function(){
				getProductGrid();
			}, 300);

		}
	}
});


$('#item-code').autocomplete({
	source:BASE_URL + 'auto_complete/get_product_code',
	minLength: 4,
	autoFocus:true
});

$('#item-code').keyup(function(e){
	if(e.keyCode == 13){
		var code = $(this).val();
		if(code.length > 4){
			setTimeout(function(){
				getItemGrid();
			}, 200);
		}
	}
});


$('#input-qty').keyup(function(e){
	if(e.keyCode == 13){
		addItemToOrder();
	}
});


//---- เพิ่มรายการสินค้าเช้าออเดอร์
function addItemToOrder(){
	var orderCode = $('#order_code').val();
	var qty = parseDefault(parseInt($('#input-qty').val()), 0);
	var limit = parseDefault(parseInt($('#stock-qty').val()), 0);
	var itemCode = $('#item-code').val();
  var data = [{'code':itemCode, 'qty' : qty}];

	if(qty > 0 && qty <= limit){
		load_in();
		$.ajax({
			url:BASE_URL + 'orders/orders/add_detail/'+orderCode,
			type:"POST",
			cache:"false",
			data:{
				'data' : data
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

					$("#btn-save-order").removeClass('hide');
					updateDetailTable(); //--- update list of order detail

					setTimeout(function(){
						$('#item-code').val('');
						$('#stock-qty').val('');
						$('#input-qty').val('');
						$('#item-code').focus();
					},1200);


				}else{
					swal("Error", rs, "error");
				}
			}
		});
	}
}




//--- ตรวจสอบจำนวนที่คีย์สั่งใน order grid
function countInput(){
	var qty = 0;
	$(".order-grid").each(function(index, element) {
        if( $(this).val() != '' ){
			qty++;
		}
  });
	return qty;
}



function validUpdate(){
	var date_add = $("#date").val();
	var customer_code = $("#customerCode").val();
  var customer_name = $('#customer').val();
	var user_ref = $("#user_ref").val();
  var warehouse = $('#warehouse').val();

	//---- ตรวจสอบวันที่
	if( ! isDate(date_add) ){
		swal("Invalid date");
		return false;
	}

	//--- ตรวจสอบลูกค้า
	if( customer_code.length == 0 || customer_name == "" ){
		swal("Invalid customer");
		return false;
	}

  if(user_ref == ""){
    swal('Please specify the request [Employee name].');
    return false;
  }


  if(warehouse == ""){
    swal("Please select warehouse");
    return false;
  }

  updateOrder();
}





function updateOrder() {
	let order_code = $("#order_code").val();
	let date_add = $("#date").val();
	let customer_code = $("#customerCode").val();
  let customer_name = $("#customer").val();
	let user_ref = $('#user_ref').val();
  let warehouse = $('#warehouse').val();
	let transformed = $('#transformed').val();
  let currency = $('#doc_currency').val();
  let rate = $('#doc_rate').val();
	var remark = $("#remark").val();

	load_in();

	$.ajax({
		url:BASE_URL + 'orders/sponsor/update_order',
		type:"POST",
		cache:"false",
		data:{
      "order_code" : order_code,
  		"date_add"	: date_add,
  		"customer_code" : customer_code,
      "DocCur" : currency,
      "DocRate" : rate,
      "user_ref" : user_ref,
      "warehouse" : warehouse,
			"transformed" : transformed,
  		"remark" : remark,
    },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
          title: 'Done !',
          type: 'success',
          timer: 1000
        });

				setTimeout(function(){
          window.location.reload();
        }, 1200);

			}else{
				swal({
          title: "Error!",
          text: rs,
          type: 'error'
        });
			}
		}
	});
}



function recalDiscount(){
	updateOrder(1);
}



// JavaScript Document
function changeState(){
    var order_code = $("#order_code").val();
    var state = $("#stateList").val();
		var id_address = $('#address_id').val();
		var id_sender = $('#id_sender').val();
		var trackingNo = $('#trackingNo').val();
		var tracking = $('#tracking').val();
		var cancle_reason = $.trim($('#cancle-reason').val());

		var is_wms = $('#is_wms').val();

		// if(is_wms) {
		// 	if(state == 3 && id_address == "") {
		// 		swal("กรุณาระบุที่อยู่จัดส่ง");
		// 		return false;
		// 	}
    //
		// 	if(state == 3 && id_sender == "") {
		// 		swal("กรุณาระบุผู้จัดส่ง");
		// 		return false;
		// 	}
    //
		// 	if($('#sender option:selected').data('tracking') == 1) {
		// 		if(trackingNo != tracking) {
		// 			swal("กรุณากดบันทึก Tracking No");
		// 			return false;
		// 		}
    //
		// 		if(trackingNo.length === 0) {
		// 			swal("กรุณาระบุ Tracking No");
		// 			return false;
		// 		}
		// 	}
		// }

		if(state == 9 && cancle_reason == "") {
			$('#cancle-modal').modal('show');
			return false;
		}


    if( state != 0){
			load_in();
        $.ajax({
            url:BASE_URL + 'orders/orders/order_state_change',
            type:"POST",
            cache:"false",
            data:{
              "order_code" : order_code,
              "state" : state,
							"id_address" : id_address,
							"id_sender" : id_sender,
							"tracking" : tracking,
							"cancle_reason" : cancle_reason
            },
            success:function(rs){
							load_out();
                var rs = $.trim(rs);
                if(rs == 'success'){
                    swal({
                      title:'success',
                      text:'status updated',
                      type:'success',
                      timer: 1000
                    });

                    setTimeout(function(){
                      window.location.reload();
                    }, 1500);

                }else{
                    swal({
											title:"Error !",
											text:rs,
											type: "error",
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
        });
    }
}


function doCancle() {
	$('#cancle-modal').modal('hide');
	if($.trim($('#cancle-reason').val()) == "") {
		return false;
	}

	return changeState();
}



$('#cancle-modal').on('shown.bs.modal', function() {
	$('#cancle-reason').focus();
});


function showReason() {
	$('#cancle-reason-modal').modal('show');
}



function setNotExpire(option){
  var order_code = $('#order_code').val();
  load_in();
  $.ajax({
    url:BASE_URL + 'orders/orders/set_never_expire',
    type:'POST',
    cache:'false',
    data:{
      'order_code' : order_code,
      'option' : option
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          window.location.reload();
        },1500);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}

function unExpired(){
  var order_code = $('#order_code').val();
  load_in();
  $.ajax({
    url:BASE_URL + 'orders/orders/un_expired',
    type:'GET',
    cache:'false',
    data:{
      'order_code' : order_code
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success')
      {
        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          window.location.reload();
        },1500);
      }
      else
      {
        swal('Error', rs, 'error');
      }
    }
  })
}
