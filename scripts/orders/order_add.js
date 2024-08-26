$('#date').datepicker({
  dateFormat:'dd-mm-yy'
});


$('#qt_no').autocomplete({
	source:BASE_URL + 'auto_complete/get_active_quotation',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function() {
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if(arr.length === 2) {
			$(this).val(arr[0]);
		}
		else {
			$(this).val('');
		}
	}
})


function get_quotation()
{
	var qt_no = $('#qt_no').val();
	var code = $('#order_code').val();

	swal({
		title: "Are you sure ?",
		text: "All old ones will be deleted and reloaded. Confirm the retrieval of the item or not ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Confirm',
		cancelButtonText: 'Cancel',
		closeOnConfirm: false
		}, function(){
			load_in();
			$.ajax({
				url: BASE_URL + 'orders/orders/load_quotation',
				type:"GET",
				cache:"false",
				data:{
					'order_code' : code,
					'qt_no' : qt_no
				},
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title:'Success',
							type:'success',
							timer:1000
						});

						window.location.reload();

					}else{
						swal("Error !", rs , "error");
					}
				}
			});
	});

}




//---- เปลี่ยนสถานะออเดอร์  เป็นบันทึกแล้ว
function saveOrder(){
  var order_code = $('#order_code').val();
	var id_sender = $('#id_sender').val();
	var tracking = $('#tracking').val();
	$.ajax({
		url: BASE_URL + 'orders/orders/save/'+ order_code,
		type:"POST",
    cache:false,
		data:{
			'id_sender' : id_sender,
			'tracking' : tracking
		},
		success:function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
          title: 'Saved',
          type: 'success',
          timer: 1000
        });
				setTimeout(function(){ editOrder(order_code) }, 1200);
			}else{
				swal("Error ! ", rs , "error");
			}
		}
	});
}




$("#customer").autocomplete({
	source: BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var code = arr[0];
			var name = arr[1];
			$("#customerCode").val(code);
			$('#customer_code').val(code);
			$("#customer").val(name);
		}else{
			$("#customerCode").val('');
			$('#customer_code').val('');
			$(this).val('');
		}
	}
});


$('#customer_code').autocomplete({
  source: BASE_URL + 'auto_complete/get_customer_code_and_name',
  autoFocus: true,
  close: function() {
    let rs = $.trim($(this).val());
    let arr = rs.split(' | ');
    if(arr.length == 2) {
      let code = arr[0];
      let name = arr[1];
      $('#customerCode').val(code);
      $('#customer_code').val(code);
      $('#customer').val(name);
    }
    else {
      $('#customerCode').val('');
      $('#customer').val('');
      $(this).val('');
    }
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

var customer;
var channels;
var payment;
var date;
var doc_currency;
var doc_rate;


function getEdit(){
  $('.edit').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');

  customer = $("#customerCode").val();
	channels = $("#channels").val();
	payment  = $("#payment").val();
	date = $("#date").val();
  doc_currency = $('#doc_currency').val();
  doc_rate = $('#doc_rate').val();
}


function add() {
  let data_add = $('#date').val();
  let customer_code = $('#customer_code').val();
  let customer = $("#customerCode").val();
	let channels = $("#channels").val();
	let payment  = $("#payment").val();
	let date = $("#date").val();
  let doc_currency = $('#doc_currency').val();
  let doc_rate = $('#doc_rate').val();
  let warehouse = $('#warehouse').val();

  if(customer_code.length == 0 || customer.length == 0 || customer_code != customer) {
    swal("Invalid customer code");
    return false;
  }

  if( ! isDate(data_add)) {
    swal("Invalid date format");
    return false;
  }

  if(doc_currency == "") {
    swal("Please define currency");
    return false;
  }

  if(doc_rate <= 0) {
    swal("Invalid currency exchange rate");
    return false;
  }

  $('#btn-submit').click();
}

//---- เพิ่มรายการสินค้าเช้าออเดอร์
function addToOrder(){
  let order_code = $('#order_code').val();

  let data = [];

  $(".order-grid").each(function(index, element){
    if($(this).val() != '') {
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



//---- เพิ่มรายการสินค้าเช้าออเดอร์
function addItemToOrder(){
	var orderCode = $('#order_code').val();
	var qty = parseDefault(parseInt($('#input-qty').val()), 0);
	var limit = parseDefault(parseInt($('#stock-qty').val()), 0);
	var itemCode = $('#item-code').val();
  var auz = $('#auz').val();
  var data = [{'code':itemCode, 'qty' : qty}];

	if(qty > 0 && (qty <= limit || auz == 1)){
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
		confirmButtonText: 'ํYes',
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
	autoFocus:true,
  close:function() {
    var rs = $(this).val();
    var arr = rs.split(' | ');
    $(this).val(arr[0]);
  }
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
	var channels_code = $("#channels").val();
	var payment_code = $("#payment").val();
  var currency = $('#doc_currency').val();
  var rate = $('#doc_rate').val();
  var recal = 0;


	//---- ตรวจสอบวันที่
	if( ! isDate(date_add) ){
		swal("Invalid date");
		return false;
	}

	//--- ตรวจสอบลูกค้า
	if( customer_code.length == 0 || customer_name == "" ){
		swal("Invalid Customer");
		return false;
	}

  if(channels_code == ""){
    swal('Please select sales channels');
    return false;
  }


  if(payment_code == ""){
    swal('Please select payment method');
    return false;
  }


	//--- ตรวจสอบความเปลี่ยนแปลงที่สำคัญ
	if( (date_add != date) || ( customer_code != customer ) || ( channels_code != channels ) || ( payment_code != payment ) )
  {
		recal = 1; //--- ระบุว่าต้องคำนวณส่วนลดใหม่
	}

  updateOrder(recal);
}





function updateOrder(recal){
	let order_code = $("#order_code").val();
	let date_add = $("#date").val();
	let customer_code = $("#customerCode").val();
  let customer_name = $("#customer").val();
  //let customer_ref = $('#customer_ref').val();
	let channels_code = $("#channels").val();
	let payment_code = $("#payment").val();
	let reference = $('#reference').val();
  let warehouse_code = $('#warehouse').val();
	//let transformed = $('#transformed').val();
	let remark = $("#remark").val();
  let currency = $('#doc_currency').val();
  let rate = $('#doc_rate').val();
  let current_currency = $('#current-currency').val();
  let current_rate = $('#current-rate').val();


	load_in();

	$.ajax({
		url:BASE_URL + 'orders/orders/update_order',
		type:"POST",
		cache:"false",
		data:{
      "order_code" : order_code,
  		"date_add"	: date_add,
  		"customer_code" : customer_code,
      "DocCur" : currency,
      "DocRate" : rate,
      "current_currency" : current_currency,
      "current_rate" : current_rate,
      //"customer_ref" : customer_ref,
  		"channels_code" : channels_code,
  		"payment_code" : payment_code,
  		"reference" : reference,
      "warehouse_code" : warehouse_code,
  		"remark" : remark,
			//"transformed" : transformed,
      "recal" : recal
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
		var is_wms = $('#is_wms').val();
		var trackingNo = $('#trackingNo').val();
		var tracking = $('#tracking').val();
		var id_address = $('#address_id').val();
		var id_sender = $('#id_sender').val();
		var cancle_reason = $.trim($('#cancle-reason').val());
		
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
                    swal("Error !", rs, "error");
                }
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


function validateOrder(){
  var prefix = $('#prefix').val();
  var runNo = parseInt($('#runNo').val());
  let code = $.trim($('#code').val());

  if(code.length == 0){
    $('#btn-submit').click();
    return false;
  }

  let arr = code.split('-');

  if(arr.length == 2){
    if(arr[0] !== prefix){
      swal('Prefix ต้องเป็น '+prefix);
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
            add();
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
