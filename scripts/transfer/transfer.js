var HOME = BASE_URL + 'inventory/transfer/';

function goBack(){
  window.location.href = HOME;
}



function addNew(){
  window.location.href = HOME + 'add_new';
}



function goEdit(code) {
  let uuid = get_uuid();
  $.ajax({
    url:HOME + 'is_document_avalible',
    type:'GET',
    cache:false,
    data:{
      'code' : code,
      'uuid' : uuid
    },
    success:function(rs) {
      if(rs === 'available') {
        window.location.href = HOME + 'edit/'+code+'/'+uuid;
      }
      else {
        swal({
          title:'Oops!',
          text:'The document is being opened/edited. by another machine cannot be edited at this time.',
          type:'warning'
        });
      }
    }
  });
}



function goDetail(code){
  window.location.href = HOME + 'view_detail/'+code;
}




//--- สลับมาใช้บาร์โค้ดในการคีย์สินค้า
function goUseBarcode(){
  let code = $('#transfer_code').val();
  let uuid = get_uuid();
  window.location.href = HOME + 'edit/'+code+'/'+uuid+'/barcode';
}




//--- สลับมาใช้การคื่ย์มือในการย้ายสินค้า
function goUseKeyboard(){
  let code = $('#transfer_code').val();
  let uuid = get_uuid();
  window.location.href = HOME + 'edit/'+code+'/'+uuid;
}




function doApprove() {
  let code = $('#transfer_code').val();

  swal({
    title:'Approval',
    text:'Do you want to approve '+code+' ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#91b784',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  },
  function() {
    load_in();

    $.ajax({
      url:HOME + 'do_approve',
      type:'POST',
      cache:false,
      data:{
        'code' : code
      },
      success:function(rs) {
        load_out();
        if(isJson(rs)) {
          let ds = JSON.parse(rs);

          if(ds.status == 'success') {
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
          else if(ds.status == 'warning') {
            setTimeout(() => {
              swal({
                title:'Warning',
                text:ds.message,
                type:'warning'
              }, () => {
                window.location.reload();
              });
            }, 200);
          }
          else {
            setTimeout(() => {
              swal({
                title:'Error!',
                text:ds.message,
                type:'error'
              });
            }, 200);
          }
        }
        else {
          setTimeout(() => {
            swal({
              title:'Error!',
              text:rs,
              type:'error'
            });
          }, 200);
        }
      }
    });
  });
}


function doReject() {
  let code = $('#transfer_code').val();

  swal({
    title:'Rejection',
    text:'Do you want to reject '+code+' ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#DD6855',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  },
  function() {
    load_in();

    $.ajax({
      url:HOME + 'do_reject',
      type:'POST',
      cache:false,
      data:{
        'code' : code
      },
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
              type:'error'
            });
          }, 200);
        }
      }
    });
  });
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
	}, function(){
    load_in();
		$.ajax({
			url:HOME + 'delete_transfer/'+code,
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



function printTransfer(){
	var center = ($(document).width() - 800) /2;
  var code = $('#transfer_code').val();
  var target = HOME + 'print_transfer/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}


function printWmsTransfer(){
	var center = ($(document).width() - 800) /2;
  var code = $('#transfer_code').val();
  var target = HOME + 'print_wms_transfer/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}



function send_to_wms(code) {
	load_in();
	$.ajax({
		url:HOME + 'send_to_wms/'+code,
		type:'POST',
		cache:false,
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
