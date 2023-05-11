// JavaScript Document
var HOME = BASE_URL + 'inventory/receive_po_request/';

function goDelete(code){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการยกเลิก '"+code+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ไม่ใช่',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url: HOME + 'cancle_received',
				type:"POST",
				cache:"false",
				data:{
					"receive_code" : code
				},
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title: 'Cancled',
							type: 'success',
							timer: 1000
						});

						setTimeout(function(){
							window.location.reload();
						}, 1200);

					}else{
						swal("Error !", rs, "error");
					}
				}
			});
	});
}



function addNew()
{
  var date_add = $('#dateAdd').val();
  var remark = $('#remark').val();
  if(!isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  $('#addForm').submit();
}



function goAdd(){
  window.location.href = HOME + 'add_new';
}


function goEdit(code){
	window.location.href = HOME + 'edit/'+ code;
}


function viewDetail(code){
	window.location.href = HOME + 'view_detail/'+ code;
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



function doExport(){
	var code = $('#receive_code').val();
	load_in();
	$.ajax({
		url: HOME + 'do_export/'+code,
		type:'POST',
		cache:false,
		success:function(rs){
			load_out();
			if(rs == 'success'){
				swal({
					title:'Success',
					text:'Send data successfully',
					type:'success',
					timer:1000
				});
			}else{
				swal({
					title:'Errow!',
					text: rs,
					type:'error'
				});
			}
		}
	})
}


function clearFilter(){
  var url = HOME + 'clear_filter';
  $.get(url, function(rs){
    goBack();
  });
}

function approve(){
	var code = $('#receive_code').val();
	$.ajax({
		url:HOME + 'do_approve',
		type:'POST',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs === 'success'){
				swal({
					title:'Approved',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);
			}
			else{
				swal("Error!!", rs, 'error');
			}
		}
	})
}


function unapprove(){
	var code = $('#receive_code').val();
	$.ajax({
		url:HOME + 'un_approve',
		type:'POST',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs === 'success'){
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);
			}
			else{
				swal("Error!!", rs, 'error');
			}
		}
	})
}
