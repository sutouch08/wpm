// JavaScript Document
function viewValidDetail(id_order)
{
	load_in();
	$.ajax({
		url:"controller/paymentController.php?getValidPaymentDetail",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal('Error', 'Not found', 'error');
			}else{
				var source 	= $("#detailTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#detailBody");
				render(source, data, output);
				$("#confirmModal").modal('show');
			}
		}
	});
}





function removeValidPayment(id_order, name)
{
	swal({
		title: 'Are you sure ?',
		text: 'Do you want to delete '+ name + ' ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url: "controller/paymentController.php?removeValidPayment",
				type:"POST", cache:"false", data:{ "id_order" : id_order, "reference" : name },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title : "Success",
							timer: 1000,
							type: "success"
						});
						$("#"+id_order).remove();
					}else{
						swal("Error!!", "Failed to delete", "error");
					}
				}
			});
		});
}



$("#fromDate").datepicker({
	dateFormat:'dd-mm-yy',
	onClose: function(sd){
		$("#toDate").datepicker("option", "minDate", sd);
	}
});



$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(sd){
		$("#fromDate").datepicker("option", "maxDate", sd);
	}
});


$("#sAcc").autocomplete({
	source:"controller/paymentController.php?searchBankAccount",
	autoFocus:true,
	close: function(){
		var rs = $(this).val();
		//----		bank_name | acc_no | branch | acc_name;
		var arr = rs.split(' | ');
		if( arr.length > 1 ){
			$(this).val(arr[1]);
		}
	}
});
