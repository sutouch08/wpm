var HOME = BASE_URL + 'inventory/move/';

function goBack(){
  window.location.href = HOME;
}



function addNew(){
  window.location.href = HOME + 'add_new';
}



function goEdit(code){
  window.location.href = HOME + 'edit/'+code;
}


function goDetail(code){
  window.location.href = HOME + 'view_detail/'+code;
}




//--- สลับมาใช้บาร์โค้ดในการคีย์สินค้า
function goUseBarcode(){
  var code = $('#move_code').val();
  window.location.href = HOME + 'edit/'+code+'/barcode';
}




//--- สลับมาใช้การคื่ย์มือในการย้ายสินค้า
function goUseKeyboard(){
  var code = $('#move_code').val();
  window.location.href = HOME + 'edit/'+code;
}





function goDelete(code, status){
  var title = 'Do you want to cancel '+ code +' ?';
  if(status == 1){
    title = 'To cancel You must first cancel this document in SAP. Do you want to cancel '+ code +' ?';
  }

	swal({
		title: 'Are you sure ?',
		text: title,
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: true
	}, function() {

    load_in();

		$.ajax({
			url:HOME + 'delete_move/'+code,
			type:"POST",
      cache:"false",
			success: function(rs) {
        load_out();
				var rs = $.trim(rs);
				if( rs == 'success' ) {
          setTimeout(() => {
            swal({
              title:'Success',
              type: 'success',
              timer: 1000
            });

            setTimeout(function(){
              goBack();
            }, 1200);
          }, 200);
				}
        else {
          setTimeout(() => {
            swal("Error!", rs, "error");
          }, 200);
				}
			}
		});
	});
}



function clearFilter(){
  $.get(HOME + 'clear_filter', function(){
		goBack();
	});
}




function getSearch(){
  $('#searchForm').submit();
}




$('.search').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});



$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});



$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


$('#date').datepicker({
  dateFormat:'dd-mm-yy'
});



function printMove(){
	var center = ($(document).width() - 800) /2;
  var code = $('#move_code').val();
  var target = HOME + 'print_move/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}
