// JavaScript Document
var HOME = BASE_URL + 'inventory/return_lend/';

function goDelete(code) {
	swal({
		title: "Are you sure ?",
		text: "Do you want to cancel '"+code+"' ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: true
		}, function(){
			$('#cancle-code').val(code);
			$('#cancle-reason').val('');
			cancle_return(code);
	});
}


function cancle_return(code)
{
	var reason = $.trim($('#cancle-reason').val());

	if(reason == "")
	{
		$('#cancle-modal').modal('show');
		return false;
	}

	load_in();

	$.ajax({
		url: HOME + 'cancle_return',
		type:"POST",
		cache:"false",
		data: {
			"return_code" : code,
			"reason" : reason
		},
		success: function(rs) {
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ) {
				setTimeout(function() {
					swal({
						title: 'Cancled',
						type: 'success',
						timer: 1000
					});

					setTimeout(function(){
						window.location.reload();
					}, 1200);
				}, 200);
			}
			else {
				setTimeout(function() {
					swal("Error !", rs, "error");
				}, 200);
			}
		}
	});
}


function doCancle() {
	let code = $('#cancle-code').val();
	let reason = $.trim($('#cancle-reason').val());

	if( reason.length == 0 || code.length == 0) {
		return false;
	}

	$('#cancle-modal').modal('hide');

	return cancle_return(code);
}



$('#cancle-modal').on('shown.bs.modal', function() {
	$('#cancle-reason').focus();
});


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


function leave(){
	swal({
		title: 'Do you want to leave ?',
		text:'Unsave data will be lost',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		goBack();
	});

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
function printReturn(){
	var code = $("#code").val();
	var center = ($(document).width() - 800) /2;
  var target = HOME + 'print_return/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}


function printWmsReturn(){
	var code = $("#code").val();
	var center = ($(document).width() - 800) /2;
  var target = HOME + 'print_wms_return/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}



function clearFilter(){
  var url = HOME + 'clear_filter';
  $.get(url, function(rs){
    goBack();
  });
}
