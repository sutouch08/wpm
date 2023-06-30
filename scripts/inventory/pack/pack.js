function goBack(){
  window.location.href = BASE_URL + 'inventory/pack';
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
  $.get(BASE_URL + 'inventory/pack/clear_filter', function(){
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


function deletePack(id, order, product) {
	swal({
		title: "Are you sure ?",
		text: "Are you really want to delete this record ?<br/>This process cannot be undone",
		type: "warning",
    html: true,
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'Delete',
		cancelButtonText: 'Cancel',
		closeOnConfirm: true
		}, function() {

      load_in();

			$.ajax({
				url: BASE_URL + 'inventory/pack/delete_row',
				type:"POST",
        cache:false,
				data:{
					'id' : id
				},
				success: function(rs) {
          load_out();

					var rs = $.trim(rs);
					if( rs == 'success') {
            setTimeout(() => {
              swal({
                title: 'Deleted',
                type: 'success',
                timer: 1000 });

                $('#row-'+id).remove();
                reIndex();
            }, 200);
					}
          else {
						setTimeout(()=> {
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
