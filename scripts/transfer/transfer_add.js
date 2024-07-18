function add() {
  var isManual = $('#manualCode').length;
  if(isManual === 1) {
    getValidate();
  }
  else {
    addTransfer();
  }
}


function getValidate() {
  var prefix = $('#prefix').val();
  var runNo = parseInt($('#runNo').val());
  let code = $('#code').val();
  if(code.length == 0) {
    addTransfer();
    return false;
  }

  let arr = code.split('-');

  if(arr.length == 2){
    if(arr[0] !== prefix){
      swal('Prefix must be '+prefix);
      return false;
    }else if(arr[1].length != (4 + runNo)){
      swal('Run Number is invalid');
      return false;
    }else{
      $.ajax({
        url: BASE_URL + 'inventory/transfer/is_exists/'+code,
        type:'GET',
        cache:false,
        success:function(rs){
          if(rs == 'not_exists'){
            addTransfer();
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

  }
  else {
    swal('Invalid document number');
    return false;
  }
}



//--- เพิ่มเอกสารโอนคลังใหม่
function addTransfer() {

  var code = $('#code').val();

  //--- วันที่เอกสาร
  var date_add = $('#date').val();

  //--- คลังต้นทาง
  var from_warehouse = $('#from_warehouse').val();
  var from_warehouse_code = $('#from_warehouse_code').val();

  //--- คลังปลายทาง
  var to_warehouse = $('#to_warehouse').val();
  var to_warehouse_code = $('#to_warehouse_code').val();
  var is_wms = $('#is_wms').val();

  //--- หมายเหตุ
  var remark = $.trim($('#remark').val());
  var reqRemark = $('#require_remark').val() == 1 ? true : false;

  //--- ตรวจสอบวันที่
  if( ! isDate(date_add))
  {
    swal('Invaid date');
    return false;
  }

  //--- ตรวจสอบคลังต้นทาง
  if(from_warehouse.length == 0 || from_warehouse_code == ''){
    swal('Invalid source warehouse');
    return false;
  }

  //--- ตรวจสอบคลังปลายทาง
  if(to_warehouse_code == '' || to_warehouse.length == 0){
    swal('Invalid destination warehouse');
    return false;
  }

  //--- ตรวจสอบว่าเป็นคนละคลังกันหรือไม่ (ต้องเป็นคนละคลังกัน)
  if( from_warehouse_code == to_warehouse_code) {
    swal('Source and destination warehouse cannot be the same');
    return false;
  }

  if(reqRemark && remark.length < 10) {
    swal({
      title: 'Required',
      text: "Please specify at least 10 characters.",
      type:'warning'
    });

    return false;
  }

  load_in();

  $.ajax({
    url:HOME + 'add',
    type:'POST',
    cache:false,
    data:{
      'code' : code,
      'date' : date_add,
      'from_warehouse_code' : from_warehouse_code,
      'to_warehouse_code' : to_warehouse_code,
      'is_wms' : is_wms,
      'remark' : remark
    },
    success:function(rs) {
      load_out();
      if(isJson(rs)) {
        let ds = JSON.parse(rs);
        if(ds.status == 'success') {
          let uuid = get_uuid();
          window.location.href = HOME + 'edit/'+ds.code+'/'+uuid;
        }
        else {
          swal({
            title:'Error!',
            text:ds.message,
            type:'error'
          });
        }
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  });
}




//--- update เอกสาร
function update(){
  //--- ไอดีเอกสาร สำหรับส่งไปอ้างอิงการแก้ไข
  var code = $('#transfer_code').val();

  //--- คลังต้นทาง
  var from_warehouse = $('#from_warehouse_code').val();
  var old_from_wh = $('#old_from_warehouse_code').val();
  //--- คลังปลายทาง
  var to_warehouse = $('#to_warehouse_code').val();
  var old_to_wh = $('#old_to_warehouse_code').val();
  //--  วันที่เอกสาร
  var date_add = $('#date').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  //--- ตรวจสอบไอดี
  if(code == ''){
    swal('Error !', 'Document number not found', 'error');
    return false;
  }

  //--- ตรวจสอบวันที่
  if( ! isDate(date_add)){
    swal('Invalid date');
    return false;
  }

  //--- ตรวจสอบคลังต้นทาง
  if(from_warehouse == ''){
    swal('Please select warehouse');
    return false;
  }

  //--- ตรวจสอบคลังปลายทาง
  if(to_warehouse == ''){
    swal('Please select warehouse');
    return false;
  }

  //--- ตรวจสอบว่าเป็นคนละคลังกันหรือไม่ (ต้องเป็นคนละคลังกัน)
  if( from_warehouse == to_warehouse){
    swal('Source and destination warehouse cannot be the same');
    return false;
  }


  //--- ตรวจสอบหากมีการเปลี่ยนคลัง ต้องเช็คก่อนว่ามีการทำรายการไปแล้วหรือยัง
  if(from_warehouse != old_from_wh || to_warehouse != old_to_wh)
  {
    $.ajax({
      url:HOME + 'is_exists_detail/'+code,
      type:'POST',
      cache:false,
      success:function(rs)
      {
        if(rs === 'exists')
        {
          swal({
            title:'Warning !',
            text:'The transaction has been made and cannot change the warehouse.',
            type:'warning'
          });

          return false;
        }
        else
        {
          do_update(code, date_add, from_warehouse, to_warehouse, remark);
        }
      }
    })
  }
  else
  {
    do_update(code, date_add, from_warehouse, to_warehouse, remark);
  }
}



function do_update(code, date_add, from_warehouse, to_warehouse, remark)
{
	var is_wms = $('#is_wms').val();

  load_in();
  //--- ถ้าไม่มีอะไรผิดพลาด ส่งข้อมูไป update
  $.ajax({
    url: HOME + 'update/'+code,
    type:'POST',
    cache:'false',
    data:{
      'date_add' : date_add,
      'from_warehouse' : from_warehouse,
      'to_warehouse' : to_warehouse,
      'remark' : remark,
			'is_wms' : is_wms
    },
    success:function(rs){
      load_out();

      var rs = $.trim(rs)
      if( rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);

      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}



//--- แก้ไขหัวเอกสาร
function getEdit(){
  $('.edit').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}



//---  บันทึกเอกสาร
function save(){
  var code = $('#transfer_code').val();

  //--- check temp
  $.ajax({
    url:HOME + 'check_temp_exists/'+code,
    type:'POST',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      //--- ถ้าไม่มียอดค้างใน temp
      if( rs == 'not_exists') {
        //--- ส่งข้อมูลไป formula
        saveTransfer(code);
      }
      else{
        swal({
          title:'ข้อผิดพลาด !',
          text:'Found an item that has not been transferred to the destination, please check.',
          type:'error'
        });
      }
    }
  });
}



function saveTransfer(code)
{
  load_in();

  $.ajax({
    url:HOME + 'save_transfer/'+code,
    type:'POST',
    cache:false,
    success:function(rs) {
      load_out();
      if(isJson(rs)) {
        let ds = JSON.parse(rs);
        if(ds.status == 'success') {
          swal({
            title:'Saved',
            type:'success',
            timer:1000
          });

          setTimeout(function() {
            goDetail(code);
          }, 1200);
        }
        else if(ds.status == 'warning') {
          swal({
            title:'Warning',
            text:ds.message,
            type:'warning'
          }, () => {
            goDetail(code);
          });
        }
        else {
          swal({
            title:'Error!',
            text:ds.message,
            type:'error'
          });
        }
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  });
}



function unSave(){
  var code = $('#transfer_code').val();
  swal({
		title: 'Warning !!',
		text: 'To cancel recording You must cancel this document in SAP first. Do you want to cancel the '+ code +' ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:HOME + 'unsave_transfer/'+ code,
			type:"POST",
      cache:"false",
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title:'Success',
						type: 'success',
						timer: 1000
					});

					setTimeout(function(){
						window.location.reload();
					}, 1200);

				}else{
					swal("Error!", rs, "error");
				}
			}
		});
	});
}



$('#from_warehouse_code').autocomplete({
  source:BASE_URL + 'auto_complete/get_warehouse_code_and_name',
  autoFocus:true,
  close:function() {
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2)
    {
      code = arr[0];
      name = arr[1];
      $('#from_warehouse_code').val(code);
      $('#from_warehouse').val(name);
    }
    else
    {
      $('#from_warehouse_code').val('');
      $('#from_warehouse').val('');
    }
  }
});


$('#to_warehouse_code').autocomplete({
  source:BASE_URL + 'auto_complete/get_warehouse_code_and_name',
  autoFocus:true,
  close:function() {
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2)
    {
      code = arr[0];
      name = arr[1];
      $('#to_warehouse_code').val(code);
      $('#to_warehouse').val(name);
    }
    else
    {
      $('#to_warehouse_code').val('');
      $('#to_warehouse').val('');
    }
  }
});




$('#from_warehouse').autocomplete({
  source:BASE_URL + 'auto_complete/get_warehouse_code_and_name',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2)
    {
      code = arr[0];
      name = arr[1];
      $('#from_warehouse_code').val(code);
      $(this).val(name);
    }
    else
    {
      $('#from_warehouse_code').val('');
      $(this).val('');
    }
  }
});


$('#to_warehouse').autocomplete({
  source:BASE_URL + 'auto_complete/get_warehouse_code_and_name',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2)
    {
      code = arr[0];
      name = arr[1];
      $('#to_warehouse_code').val(code);
      $(this).val(name);
    }
    else
    {
      $('#to_warehouse_code').val('');
      $(this).val('');
    }
  }
});


$('#wx_code').autocomplete({
	source:BASE_URL + 'auto_complete/get_wx_code',
	autoFocus:true,
	close:function() {
		var rs = $(this).val();
		if(rs == 'not found') {
			$(this).val('');
		}
	}
})
