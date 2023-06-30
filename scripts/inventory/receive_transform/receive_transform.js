// JavaScript Document
var HOME = BASE_URL + 'inventory/receive_transform/';

function doExport(){
	var code = $('#receive_code').val();
	load_in();
	$.ajax({
		url: HOME + 'do_export/'+code,
		type:'POST',
		cache:false,
		success:function(rs){
			load_out();
			if(rs === 'success'){
				swal({
					title:'Success',
					text:'ส่งข้อมูลเรียบร้อยแล้ว',
					type:'success',
					timer:1000
				});
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

function sendToWms() {
	var code = $('#receive_code').val();
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
			});
		}
	});
}


function goDelete(code){
	swal({
		title: "Are you sure ?",
		text: "Do you really want to cancel '"+code+"' ?<br/>This process cannot be undone.",
		type: "warning",
		html:true,
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: true
		}, function(){
			$('#cancle-reason').val('');
			$('#cancle-code').val(code);
			$('#cancle-modal').on('shown.bs.modal', function() {
				$('#cancle-reason').focus();
			});

			setTimeout(function() {
				$('#cancle-modal').modal('show');
			}, 200);
	});
}


function doCancle() {

	let code = $('#cancle-code').val();
	let reason = $('#cancle-reason').val();

	if(reason.length == 0) {
		$('#cancle-reason').addClass('has-error').focus();
		return false;
	}
	else {
		$('#cancle-reason').removeClass('has-error');
	}

	$('#cancle-modal').modal('hide');

	if(code.length == 0) {
		swal({
			title:'Error!',
			text:'Invalid Document Code',
			type:'error'
		});

		return false;
	}

	load_in();

	$.ajax({
		url: HOME + 'cancle_received',
		type:"POST",
		cache:"false",
		data:{
			"receive_code" : code,
			"reason" : reason
		},
		success: function(rs){
			load_out();

			var rs = $.trim(rs);
			if( rs == 'success' ) {
				swal({
					title: 'Cancled',
					type: 'success',
					timer: 1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);

			}
			else {
				swal("Error !", rs, "error");
			}
		},
		error:function(xhr, status, error) {
			load_out();
			swal({
				title:'Error!',
				text:xhr.responseText,
				type:'error',
				html:true
			});
		}
	});
}



function addNew()
{
  let date_add = $('#dateAdd').val();
	let is_wms = $('#is_wms').val();
  let remark = $.trim($('#remark').val());
	let reqRemark = $('#required-remark').val();


  if(!isDate(date_add)){
    swal('Invalid date');
    return false;
  }

	if(reqRemark == 1 && remark.length < 10) {
		swal({
			title:'Required',
			text:'Please put a note (at least 10 characters long)',
			type:'warning'
		});

		return false;
	}

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'date_add' : date_add,
			'is_wms' : is_wms,
			'remark' : remark
		},
		success:function(rs) {
			var rs = $.trim(rs);
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);
				goEdit(ds.code);
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
			swal({
				title:'Error!',
				text:xhr.responseText,
				type:'error',
				html:true
			})
		}
	})

}



function goAdd(){
  window.location.href = HOME + 'add_new';
}


function goEdit(code){
	window.location.href = HOME + 'edit/'+ code; //"index.php?content=receive_product&edit=Y&id_receive_product="+id;
}


function viewDetail(code){
	window.location.href = HOME + 'view_detail/'+ code; //"index.php?content=receive_product&view_detail=Y&id_receive_product="+id;
}


function goBack(){
	window.location.href = HOME;
}

function getSearch(){
	$("#searchForm").submit();
}


$(".search").keyup(function(e){
	if( e.keyCode == 13 ){
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



// JavaScript Document
function printReceived(){
	var code = $("#receive_code").val();
	var center = ($(document).width() - 800) /2;
  var target = HOME + 'print_detail/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}



function clearFilter(){
  var url = HOME + 'clear_filter';
  $.get(url, function(rs){
    goBack();
  });
}
