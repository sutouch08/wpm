$('#date').datepicker({
  dateFormat:'dd-mm-yy'
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



//---- เปลี่ยนสถานะออเดอร์  เป็นบันทึกแล้ว
function saveOrder(){
  var order_code = $('#order_code').val();
  load_in();

	$.ajax({
		url: BASE_URL + 'orders/orders/save/'+ order_code,
		type:"POST",
    cache:false,
		success:function(rs){
      load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
        setTimeout(function() {
          swal({
            title: 'Saved',
            type: 'success',
            timer: 1000
          });

          setTimeout(function() {
            editOrder(order_code)
          }, 1200);
        }, 200);

			}else{
				swal("Error ! ", rs , "error");
			}
		}
	});
}



$("#customerCode").autocomplete({
	source: BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var code = arr[0];
			var name = arr[1];
			$("#customerCode").val(code);
			$("#customer").val(name);
      zoneInit(code, true);
		}else{
			$("#customerCode").val('');
			$("#customer").val('');
      zoneInit('');
		}
	}
});


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
			$("#customer").val(name);
      zoneInit(code, true);
		}else{
			$("#customerCode").val('');
			$("#customer").val('');
      zoneInit('');
		}
	}
});


//---	กำหนดให้สามารถค้นหาโซนได้ก่อนจะค้นหาลูกค้า(กรณี edit header)
$(document).ready(function(){
	var customer_code = $('#customerCode').val();
	zoneInit(customer_code, false);
});



function zoneInit(customer_code, edit)
{
  if(edit) {
    $('#zone_code').val('');
    $('#zone').val('');
  }

  $('#zone').autocomplete({
    source:BASE_URL + 'auto_complete/get_consign_zone/' + customer_code,
    autoFocus: true,
    close:function(){
      var rs = $.trim($(this).val());
      var arr = rs.split(' | ');
      if(arr.length == 2)
      {
        var code = arr[0];
        var name = arr[1];
        $('#zone_code').val(code);
        $('#zone').val(name);
      }else{
        $('#zone_code').val('');
        $('#zone').val('');
      }
    }
  });

  $('#zone_code').autocomplete({
    source:BASE_URL + 'auto_complete/get_consign_zone/' + customer_code,
    autoFocus: true,
    close:function(){
      var rs = $.trim($(this).val());
      var arr = rs.split(' | ');
      if(arr.length == 2)
      {
        var code = arr[0];
        var name = arr[1];
        $('#zone_code').val(code);
        $('#zone').val(name);
      }else{
        $('#zone_code').val('');
        $('#zone').val('');
      }
    }
  });

}


function add(){
  var manualCode = $('#manualCode').val();
  if(manualCode == 1){
    validateOrder();
  }
  else{
    addOrder();
  }
}


function addOrder(){
  var customer_code = $('#customerCode').val();
  var customer_name = $('#customer').val();
  var date_add = $('#date').val();
  var zone_code = $('#zone_code').val();
  var zone_name = $('#zone').val();
  var warehouse = $('#warehouse').val();
  var gp = $('#gp').val();

  if(customer_code.length == 0 || customer_name.length == 0){
    swal('Invalid customer');
    return false;
  }

  if(!isDate(date_add))
  {
    swal('Invalid date');
    return false;
  }

  if(zone_code.length == 0 || zone_name.length == 0)
  {
    swal('Invalid location');
    return false;
  }

  if(warehouse.length == 0){
    swal('Please select warehouse');
    return false;
  }

  if(gp === "") {
    swal({
      title:'Oops!',
      text:"Please assign GP, if no GP, specify 0.",
      type:'warning'
    });

    return false;
  }

  $('#addForm').submit();
}


var customer;
var channels;
var payment;
var date;


function getEdit(){
  let approved = $('#is_approved').val();
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');

  if(approved == 1){
    $('#remark').removeAttr('disabled');
  } else {
    $('.edit').removeAttr('disabled');
  }

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
		text: "Do you really want to delete '" + name + "'?",
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
	var zone_code = $('#zone_code').val();
  var zone_name = $('#zone').val();
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

  if(zone_code == '' || zone_name.length == 0)
  {
    swal('Invalid location');
    return false;
  }

  if(warehouse.length == 0){
    swal('Please select warehouse');
    return false;
  }

  updateOrder();
}





function updateOrder(){
	var order_code = $("#order_code").val();
	var date_add = $("#date").val();
	var customer_code = $("#customerCode").val();
  var zone_code = $('#zone_code').val();
  var gp = $('#gp').val();
  var warehouse = $('#warehouse').val();
	var remark = $("#remark").val();
  let currency = $('#doc_currency').val();
  let rate = $('#doc_rate').val();

	load_in();

	$.ajax({
		url:HOME + 'update_order',
		type:"POST",
		cache:"false",
		data:{
      "order_code" : order_code,
  		"date_add"	: date_add,
  		"customer_code" : customer_code,
      "gp" : gp,
  		"remark" : remark,
      "zone_code" : zone_code,
      "warehouse" : warehouse,
      "DocCur" : currency,
      "DocRate" : rate
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



// JavaScript Document
function changeState(){
    var order_code = $("#order_code").val();
    var state = $("#stateList").val();
		var id_address = $('#address_id').val();
		var id_sender = $('#id_sender').val();
		var trackingNo = $('#trackingNo').val();
		var tracking = $('#tracking').val();
		var is_wms = $('#is_wms').val();
		var cancle_reason = $.trim($('#cancle-reason').val());

		if(is_wms) {

			if(state == 3 && id_address == "") {
				swal("Please specify delivery address.");
				return false;
			}

			if(state == 3 && id_sender == "") {
				swal("Please specify the shipper.");
				return false;
			}

			if($('#sender option:selected').data('tracking') == 1) {
				if(trackingNo != tracking) {
					swal("Please save Tracking No");
					return false;
				}

				if(trackingNo.length === 0) {
					swal("Please specify Tracking No");
					return false;
				}
			}
		}

		if(state == 9 && cancle_reason == "") {
			$('#cancle-modal').modal('show');
			return false;
		}


		load_in();
    if( state != 0){
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
  let code = $('#code').val();

  if(code.length == 0){
    addOrder();
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
            addOrder();
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
    swal('Invalid document number');
    return false;
  }
}


function update_gp(){
  let gp = $('#gp').val();
  let code = $('#order_code').val();
  load_in();
  $.ajax({
    url:BASE_URL + 'orders/orders/update_gp',
    type:'POST',
    cache:false,
    data:{
      'code' : code,
      'gp' : gp
    },
    success:function(rs){
      load_out();
      rs = $.trim(rs);
      if(rs === 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);

      } else {
        swal(rs);
      }
    }
  })
}

function approve()
{
  var order_code = $('#order_code').val();
	var is_wms = $('#is_wms').val();

	if(is_wms == 1) {
		var id_address = $('#address_id').val();
		var id_sender = $('#id_sender').val();

		if(id_address == "") {
			swal("Please specify the shipping address.");
			return false;
		}

		if(id_sender == "") {
			swal("Please specify the shipper.");
			return false;
		}
	}

  $.ajax({
    url:BASE_URL + 'orders/orders/do_approve/'+order_code,
    type:'POST',
    cache:false,
    success:function(rs){
      if(rs === 'success'){
        //change_state();
        swal({
          title:'Approved',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1500);
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

function unapprove()
{
  var order_code = $('#order_code').val();
  $.ajax({
    url:BASE_URL + 'orders/orders/un_approve/'+order_code,
    type:'POST',
    cache:false,
    success:function(rs){
      if(rs === 'success'){
        //change_state();
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1500);
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
