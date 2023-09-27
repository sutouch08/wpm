var HOME = BASE_URL + '/inventory/temp_receive_po/';

function goBack(){
  window.location.href = HOME;
}



function getSearch(){
  $("#searchForm").submit();
}



function clearFilter(){
  $.get(HOME + 'clear_filter', function(){ goBack(); });
}


$(".search").keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$("#fromDate").datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $("#toDate").datepicker("option", "minDate", sd);
  }
});


$("#toDate").datepicker({
  dateFormat: 'dd-mm-yy',
  onClose:function(sd){
    $("#fromDate").datepicker("option", "maxDate", sd);
  }
});


function get_detail(id)
{
  //--- properties for print
  var prop 			= "width=1100, height=900. left="+center+", scrollbars=yes";
  var center 	= ($(document).width() - 1100)/2;
	var target 	= HOME + 'get_detail/'+id+'?nomenu';
	window.open(target, "_blank", prop );
}



function removeTemp(docEntry, code) {
	swal({
		title: 'Are you sure ?',
		text: 'Do you want to delete '+code+' ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'Cancel',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:HOME + 'remove_temp/'+docEntry,
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

					$('#row-'+docEntry).remove();
					reIndex();
				}else{
					swal("Error", rs, "error");
				}
			}
		});
	});
}
