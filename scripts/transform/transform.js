
var ROLE = $('#role').val();
if(ROLE == 'Q')
{
  var HOME = BASE_URL + 'inventory/transform_stock/';
}
else
{
  var HOME = BASE_URL + 'inventory/transform/';
}



function addNew(){
  window.location.href = HOME + 'add_new';
}



function goBack(){
  window.location.href = HOME;
}



function editDetail(){
  var code = $('#order_code').val();
  window.location.href = HOME + 'edit_detail/'+ code;
}


function editOrder(code){
  window.location.href = HOME + 'edit_order/'+ code;
}



function clearFilter(){
  var url = HOME + 'clear_filter';
  $.get(url, function(rs){ goBack(); });
}



function getSearch(){
  $('#searchForm').submit();
}



$('.search').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#toDate").datepicker("option", "minDate", ds);
	}
});

$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#fromDate").datepicker("option", "maxDate", ds);
	}
});

function approve()
{
  if(validateTransformProducts()) {
    var order_code = $('#order_code').val();

  	var is_wms = $('#is_wms').val();

  	if(is_wms) {
  		var id_address = $('#address_id').val();
  		var id_sender = $('#id_sender').val();

  		if(id_address == "") {
  			swal("Please specify delivery address.");
  			return false;
  		}

  		if(id_sender == "") {
  			swal("Please specify delivery person.");
  			return false;
  		}
  	}

  	load_in();
    $.ajax({
      url:BASE_URL + 'orders/orders/do_approve/'+order_code,
      type:'POST',
      cache:false,
      success:function(rs){
        if(rs === 'success'){
          change_state();
        }else{
  				load_out();
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
    });
  }
  else{
		swal('Error !', 'Found an item that was not properly linked to the product. Please check.', 'error');
	}
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
        swal({
          title:'Success',
          text:'ยกเลิกการอนุมัติแล้ว',
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

function change_state(){
	var order_code = $("#order_code").val();
	var state = 3;
	var id_address = $('#address_id').val();
	var id_sender = $('#id_sender').val();
	var trackingNo = $('#trackingNo').val();
	var tracking = $('#tracking').val();
	var is_wms = $('#is_wms').val();

	if(is_wms) {
		if(state == 3 && id_address == "") {
			swal("กรุณาระบุที่อยู่จัดส่ง");
			return false;
		}

		if(state == 3 && id_sender == "") {
			swal("กรุณาระบุผู้จัดส่ง");
			return false;
		}

		if($('#sender option:selected').data('tracking') == 1) {
			if(trackingNo != tracking) {
				swal("กรุณากดบันทึก Tracking No");
				return false;
			}

			if(trackingNo.length === 0) {
				swal("กรุณาระบุ Tracking No");
				return false;
			}
		}
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
						"tracking" : tracking
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
										title:'Error!',
										text:rs,
										type:'error',
										html:true
									})
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

function toggleState(state){
  var current = $('#state_'+state).val();
  if(current == 'Y'){
    $('#state_'+state).val('N');
    $('#btn-state-'+state).removeClass('btn-info');
  }else{
    $('#state_'+state).val('Y');
    $('#btn-state-'+state).addClass('btn-info');
  }

  getSearch();
}


function toggleNotSave(){
  var current = $('#notSave').val();
  if(current == ''){
    $('#notSave').val(1);
    $('#btn-not-save').addClass('btn-info');
  }else{
    $('#notSave').val('');
    $('#btn-not-save').removeClass('btn-info');
  }

  getSearch();
}


function toggleOnlyMe(){
  var current = $('#onlyMe').val();
  if(current == ''){
    $('#onlyMe').val(1);
    $('#btn-only-me').addClass('btn-info');
  }else{
    $('#onlyMe').val('');
    $('#btn-only-me').removeClass('btn-info');
  }

  getSearch();
}


function toggleIsExpire(){
  var current = $('#isExpire').val();
  if(current == ''){
    $('#isExpire').val(1);
    $('#btn-expire').addClass('btn-info');
  }else{
    $('#isExpire').val('');
    $('#btn-expire').removeClass('btn-info');
  }

  getSearch();
}
