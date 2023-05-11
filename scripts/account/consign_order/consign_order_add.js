var zero_qty = 0;


function saveConsign(){
  var code = $('#consign_code').val();
  var details = $('.rox').length;
  if(details == 0){
    swal("ไม่พบรายการสินค้า");
    return false;
  }

  check_zero();

  if(zero_qty > 0){
    swal("พบรายการที่เป็น 0");
    return false;
  }else{
    $('.qty').css('color', '');
  }

  swal({
		title: "บันทึกขายและตัดสต็อก",
		text: "เมื่อบันทึกแล้วจะไม่สามารถแก้ไขได้ ต้องการบันทึกหรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#8CC152",
		confirmButtonText: 'บันทึก',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
		}, function(){
      load_in();
      $.ajax({
        url: HOME + 'save_consign/'+code,
        type:'POST',
        cache:'false',
        success:function(rs){
          load_out();
          if(rs == 'success'){
            setTimeout(function() {
              swal({
                title:'Saved',
                type:'success',
                timer:1000
              });

              setTimeout(function(){
                viewDetail(code);
              },1500);
            }, 200);

          }
          else {
            setTimeout(function() {
              swal({
                title:'Error!',
                text: rs,
                html:true,
                type:'error'
              });
            }, 200);
          }
        }
      });
	});
}

function check_zero(){
  zero_qty = 0;
  $('.qty').each(function(){
    var qty = parseInt($(this).text())
    if(qty == 0){
      var id = $(this).attr('id');
      var arr = id.split('-');
      if(arr.length == 2){
        id = arr[1];
        $('#row-'+id).css('color','red');
        zero_qty++;
      }
    }
  })
}


function unSaveConsign(){
  var code = $('#consign_code').val();
  msg  = '<center><span style="color:red;">ก่อนยืนยันการทำรายการนี้</span></center>';
  msg += '<center><span style="color:red;">คุณต้องแน่ใจว่าได้ลบ เอกสารใน SAP แล้ว</span></center>';
  msg += '<center><span style="color:red;">ต้องการยกเลิกการเปิดบิลหรือไม่</span></center>';

  swal({
    title: "ยกเลิกการเปิดบิล ?",
    text: msg,
    type: "warning",
    html:true,
    showCancelButton: true,
    confirmButtonColor: "#FA5858",
    confirmButtonText: 'ใช่, ฉันต้องการ',
    cancelButtonText: 'ยกเลิก',
    closeOnConfirm: true
    }, function(){
      load_in();
      $.ajax({
        url: HOME + 'unsave_consign/'+code,
        type:'POST',
        cache:'false',
        success:function(rs){
          load_out();
          var rs = $.trim(rs);
          if(rs == 'success'){
            setTimeout(function() {
              swal({
                title:'Success',
                type:'success',
                timer:1000
              });

              setTimeout(function(){
                goEdit(code);
              }, 1500);
            }, 200);

          }else{
            setTimeout(function() {
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


$('#date').datepicker({
  dateFormat:'dd-mm-yy'
});



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


$('#pd-box').autocomplete({
  source: BASE_URL + 'auto_complete/get_style_code',
  autoFocus:true
})


//---	กำหนดให้สามารถค้นหาโซนได้ก่อนจะค้นหาลูกค้า(กรณี edit header)
$(document).ready(function(){
	var customer_code = $('#customerCode').val();
	zoneInit(customer_code, false);
});



function zoneInit(customer_code, edit) {
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
  var customer_code = $('#customerCode').val();
  var customer_name = $('#customer').val();
  var date_add = $('#date').val();
  var zone_code = $('#zone_code').val();
  var zone_name = $('#zone').val();


  if(customer_code.length == 0 || customer_name.length == 0){
    swal('ชื่อลูกค้าไม่ถูกต้อง');
    return false;
  }

  if(!isDate(date_add))
  {
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(zone_code.length == 0 || zone_name.length == 0)
  {
    swal('โซนไม่ถูกต้อง');
    return false;
  }

  $('#addForm').submit();
}


var customer;
var payment;
var date;


function getEdit(){
  $('.edit').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}


function update(){
  let code = $('#consign_code').val();
  let date = $('#date').val();
  let remark = $('#remark').val();
  var customer_code = $('#customerCode').val();
  let customer_name = $('#customer').val();
  let zone_code = $('#zone_code').val();
  let zone_name = $('#zone').val();

  if(!isDate(date)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(customer_code.length == 0 || customer_name.length == 0){
    swal('ชื่อลูกค้าไม่ถูกต้อง');
    return false;
  }

  if(zone_code.length == 0 || zone_name.length == 0)
  {
    swal('โซนไม่ถูกต้อง');
    return false;
  }


  load_in();
  $.ajax({
    url: HOME + 'update',
    type:'POST',
    cache:false,
    data:{
      'code' : code,
      'date_add' : date,
      'customerCode' : customer_code,
      'customer' : customer_name,
      'zone_code' : zone_code,
      'remark' : remark
    },
    success:function(rs){
      load_out();
      if(rs === 'success'){
        swal({
          title:'Updted',
          type:'success',
          timer:1000
        });

        $('.edit').attr('disabled', 'disabled');
        $('#btn-edit').removeClass('hide');
        $('#btn-update').addClass('hide');
      }
    }
  })
}


function deleteRow(id, code){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+code+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
      deleteDetail(id);
	});
}


function deleteDetail(id){
  $.ajax({
    url: HOME + 'delete_detail/'+id,
    type:'POST',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){

        swal({
          title:'Deleted',
          type:'success',
          timer:1000
        });
        $('#row-'+id).remove();
        reIndex();
        updateTotalQty();
        updateTotalAmount();
      }
    }
  });
}



function clearAll(){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบรายการทั้งหมด หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
      deleteAllDetails();
	});
}


function deleteAllDetails(){
  var code = $('#consign_code').val();
  $.ajax({
    url: HOME + 'delete_all_details/'+code,
    type:'POST',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){

        swal({
          title:'Deleted',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1500);
      }
    }
  });
}



function unSave(id){
  msg  = '<center><span style="color:red;">ก่อนยืนยันการทำรายการนี้</span></center>';
  msg += '<center><span style="color:red;">คุณต้องแน่ใจว่าได้ลบ เอกสารใบสั่งซื้อ(SO) ใน Formula แล้ว</span></center>';
  msg += '<center><span style="color:red;">ต้องการยกเลิกการเปิดบิลหรือไม่</span></center>';

  swal({
    title: "ยกเลิกการเปิดบิล ?",
    text: msg,
    type: "warning",
    html:true,
    showCancelButton: true,
    confirmButtonColor: "#FA5858",
    confirmButtonText: 'ใช่, ฉันต้องการลบ',
    cancelButtonText: 'ยกเลิก',
    closeOnConfirm: true
    }, function(){
      load_in();
      $.ajax({
        url:'controller/consignController.php?unSaveConsign',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id
        },
        success:function(rs){
          load_out();
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Success',
              type:'success',
              timer:1000
            });

            setTimeout(function(){
              window.location.reload();
            }, 1500);

          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
  });
}



//--- ลบรายการนำเข้ายอดต่าง
function clearImportDetail(check_code){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบรายการนำเข้าจาก '"+ check_code +"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
    closeOnConfirm:false
		}, function(){
      load_in();
      var code = $('#consign_code').val();

      $.ajax({
        url: HOME + 'remove_import_details/'+code,
        type:'POST',
        cache:'false',
        data:{
          'check_code' : check_code
        },
        success:function(rs){
          load_out();
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Success',
              type:'success',
              timer:1000
            });

            setTimeout(function(){
              window.location.reload();
            }, 1500);

          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
	});
}


function getActiveCheckList(){
  var zone_code = $('#zone_code').val();
  load_in();
  $.ajax({
    url:HOME + 'get_active_check_list/'+zone_code,
    type:'GET',
    cache:'false',
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var source = $('#check-list-template').html();
        var data = $.parseJSON(rs);
        var output = $('#check-list-body');
        render(source, data, output);
        $('#check-list-modal').modal('show');
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}



function loadCheckDiff(check_code){
  $('#check-list-modal').modal('hide');
  swal({
    title: "นำเข้ายอดต่าง",
		text: "ต้องการนำเข้ายอดต่างจากเอกสารกระทบยอด "+check_code+" หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    var code = $('#consign_code').val();
    load_in();
    $.ajax({
      url: HOME + 'load_check_diff/'+code,
      type:'POST',
      cache:'false',
      data:{
        'check_code' : check_code
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title: 'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            window.location.reload();
          },1500);
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });

  });//--- swal
}


function getSample(){
  var token	= new Date().getTime();
	get_download(token);
	window.location.href = HOME + 'get_sample_file/'+token;
}



function getUploadFile(){
  $('#upload-modal').modal('show');
}



function getFile(){
  $('#uploadFile').click();
}


$("#uploadFile").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;

		if( size > 5000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
			$(this).val('');
			return false;
		}
		//readURL(this);
    $('#show-file-name').text(name);
	}
});



function uploadfile(){
  var code = $('#consign_code').val();
  var excel = $('#uploadFile')[0].files[0];

	$("#upload-modal").modal('hide');

	var fd = new FormData();

	fd.append('excel', $('input[type=file]')[0].files[0]);
	load_in();

	$.ajax({
		url:HOME + 'import_excel_file/'+code,
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
          title:'Success',
          type:'success',
          timer: 1000
        });

				setTimeout(function(){
          window.location.reload();
        }, 1200);
			}
			else
			{
        swal({
          title:"Error",
          text: rs,
          html:true,
          type:'error'
        });
			}
		},
		error:function(xhr, status, error){
			load_out();
			var errorMessage = xhr.status + ': '+xhr.statusText;
			swal({
				title:'Error!',
				text:'Error-'+errorMessage,
				type:'error'
			});
		}
	});
}

function validateOrder(){
  var prefix = $('#prefix').val();
  var runNo = parseInt($('#runNo').val());
  let code = $('#code').val();
  if(code.length == 0){
    add();
    return false;
  }

  let arr = code.split('-');

  if(arr.length == 2){
    if(arr[0] !== prefix){
      swal('Prefix ต้องเป็น '+prefix);
      return false;
    }else if(arr[1].length != (4 + runNo)){
      swal('Run Number ไม่ถูกต้อง');
      return false;
    }else{
      $.ajax({
        url: HOME + 'is_exists/'+code,
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
    swal('เลขที่เอกสารไม่ถูกต้อง');
    return false;
  }

}
