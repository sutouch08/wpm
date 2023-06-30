function goBack(){
  window.location.href = BASE_URL + 'inventory/buffer';
}

function getSearch(){
  $('#searchForm').submit();
}


$('.search').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
})


function clearFilter(){
  $.get(BASE_URL + 'inventory/buffer/clear_filter', function(){
    goBack();
  })
}


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


function deleteBuffer(id, order, product) {
	swal({
		title: "Are you sure ?",
		text: "Do you really to delete this record ?<br/>This process cannot be undone",
		type: "warning",
    html:true,
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Delete',
		cancelButtonText: 'Cancel',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url: BASE_URL + 'inventory/buffer/delete_buffer',
				type:"POST",
        cache:"false",
				data:{
					'id' : id
				},
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title: 'Deleted',
							type: 'success',
							timer: 1000 });

						$('#row-'+id).remove();
						reIndex();
					}else{
						swal("Error !", rs , "error");
					}
				}
			});
	});
}
