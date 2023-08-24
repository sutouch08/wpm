function addNew(){
  window.location.href = BASE_URL + 'orders/sponsor/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'orders/sponsor';
}



function editDetail(){
  var code = $('#order_code').val();
  window.location.href = BASE_URL + 'orders/sponsor/edit_detail/'+ code;
}


function editOrder(code){
  window.location.href = BASE_URL + 'orders/sponsor/edit_order/'+ code;
}



function clearFilter(){
  var url = BASE_URL + 'orders/sponsor/clear_filter';
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
  var order_code = $('#order_code').val();
	var is_wms = $('#is_wms').val();

	// if(is_wms == 1) {
	// 	var id_address = $('#address_id').val();
	// 	var id_sender = $('#id_sender').val();
  //
	// 	if(id_address == "") {
	// 		swal("กรุณาระบุที่อยู่จัดส่ง");
	// 		return false;
	// 	}
  //
	// 	if(id_sender == "") {
	// 		swal("กรุณาระบุผู้จัดส่ง");
	// 		return false;
	// 	}
	// }

	load_in();

  $.ajax({
    url:BASE_URL + 'orders/orders/do_approve/'+order_code,
    type:'POST',
    cache:false,
    success:function(rs){
      if(rs === 'success'){
        change_state();
      }
			else{
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
  var order_code = $('#order_code').val();
	var id_address = $('#address_id').val();
	var id_sender = $('#id_sender').val();
	var trackingNo = $('#trackingNo').val();
	var tracking = $('#tracking').val();
	var is_wms = $('#is_wms').val();

	// if(is_wms == 1) {
  //
	// 	if(id_address == "") {
	// 		swal("กรุณาระบุที่อยู่จัดส่ง");
	// 		return false;
	// 	}
  //
	// 	if(id_sender == "") {
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

  $.ajax({
    url:BASE_URL + 'orders/orders/order_state_change',
    type:'POST',
    cache:false,
    data:{
			"order_code" : order_code,
			"state" : 3,
			"id_address" : id_address,
			"id_sender" : id_sender,
			"tracking" : tracking
    },
    success:function(rs){
			load_out();
      if(rs === 'success'){
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
          title:'Error!!',
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
