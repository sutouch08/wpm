$('#barcode').keyup(function(e){
  if(e.keyCode == 13){
    var barcode = $.trim($(this).val());

    if(barcode.length > 0){
      doReceive();
    }
  }
});


function lend_code_init()
{
  let empID = $('#empID').val();
  $('#lend_code').autocomplete({
    source:BASE_URL + 'auto_complete/get_valid_lend_code/'+ empID,
    autoFocus:true
  });
}


function qtyInit() {
  $('.qty').keyup(function() {
    let no = $(this).data('no');
    let qty = parseDefault(parseFloat($(this).val()), 0);
    let limit = parseDefault(parseFloat($('#backlogs-'+no).val()), 0);

    if(qty > limit || qty < 0) {
      $(this).addClass('has-error');
    }
    else {
      $(this).removeClass('has-error');
    }

    recalTotal();
  });
}


$(document).ready(function(){
  lend_code_init();
  qtyInit();
});

function load_lend_details(){
  let code = $('#lend_code').val();

  if(code.length > 0)
  {
    load_in();
    $('#btn-set-code').addClass('hide');

    $.ajax({
      url: HOME + 'get_lend_details/'+code,
      type:'GET',
      cache:false,
      success:function(rs){
        load_out();
        if(isJson(rs)){
          let data = JSON.parse(rs);
          $('#empName').val(data.empName);
          $('#empID').val(data.empID);
          let source = $('#template').html();
          let output = $('#result');
          render(source, data, output);
          $('#btn-change-code').removeClass('hide');
          $('#lend_code').attr('disabled', 'disabled');
          $('#lendCode').val(code);
          qtyInit();
          lend_code_init();
        }
        else {
          $('#btn-set-code').removeClass('hide');
          $('#lendCode').val('');
          swal({
            title:'Error!',
            text:rs,
            type:'error'
          });
        }
      }
    })
  }
}



$('#lend_code').keyup(function(e){
  if(e.keyCode === 13){
    load_lend_details();
  }
})


function change_lend_code(){
  swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		$("#result").html('');
		$('#btn-change-code').addClass('hide');
		$('#btn-set-code').removeClass('hide');
		$('#lend_code').val('');
		$('#lend_code').removeAttr('disabled');
		swal({
			title:'Success',
			text:'ยกเลิกข้อมูลเรียบร้อยแล้ว',
			type:'success',
			timer:1000
		});
		setTimeout(function(){
			$('#lend_code').focus();
		}, 1200);
	});
}


function setZone(){
  let zone = $('#zone_code').val();
  let code = $('#lend_code').val();
  if(zone.length == 0){
    swal('โซนไม่ถูกต้อง');
    return false;
  }

  $('#zone').attr('disabled', 'disabled');
  $('#zone_code').attr('disabled', 'disabled');
  $('#btn-set-zone').addClass('hide');
  $('#btn-change-zone').removeClass('hide');

  if(code.length == 0){
    $('#lend_code').focus();
    return;
  }

  $('#barcode').focus();
}


function changeZone(){
  $('#zone_code').val('');
  $('#zone').val('');
  $('#zone').removeAttr('disabled');
  $('#zone_code').removeAttr('disabled');
  $('#btn-change-zone').addClass('hide');
  $('#btn-set-zone').removeClass('hide');
  $('#zone_code').focus();
}



function addToBarcode(barcode){
  $('#barcode').val(barcode);
  $('#barcode').focus();
}



function doReceive() {
  let barcode = $('#barcode').val();
  let qty = parseDefault( parseFloat($('#qty').val()), 1); //--- //--- ถ้า NaN ให้ค่าเป็น 1
  $('#barcode').focusout();

  if( $('.' + barcode).length ) {
    let bc = $('.' + barcode);
    let no = bc.val();
    let cqty = parseDefault(parseFloat($('#receiveQty-'+no).val()), 0); //--- ถ้า NaN ให้ค่าเป็น 0
    let limit = parseDefault(parseFloat($('#backlogs-'+no).val()), 0); //--- ถ้า NaN ให้ค่าเป็น 0
    let sum_qty = cqty + qty;
    if(sum_qty > limit) {
      swal({
        title:'Oops !',
        text:"จำนวนเกินยอดค้างรับ",
        type:'warning'
      });

      beep();
      return false;
    }

    $('#receiveQty-'+no).val(sum_qty);
    $('#barcode').val('');
    $('#qty').val(1);
    $('#barcode').focus();
    recalTotal();
  }
  else {
    swal({
      title:'Oops!',
      text:'ไม่พบสินค้า',
      type:'warning'
    });
  }
}


function receiveAll() {
  $('.qty').each(function() {
    let no = $(this).data('no');
    let qty = parseDefault(parseFloat($('#backlogs-'+no).val()), 0);
    $(this).val(qty);
  });

  recalTotal();
}


function clearAll() {
  $('.qty').each(function() {
    $(this).val('');
  });

  recalTotal();
}
