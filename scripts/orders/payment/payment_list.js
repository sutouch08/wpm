// JavaScript Document
setInterval(function(){ getSearch(); }, 1000*60);


function confirmPayment(id)
{
	$("#confirmModal").modal('hide');
	load_in();
	$.ajax({
		url:BASE_URL + 'orders/order_payment/confirm_payment',
		type:"POST",
    cache:"false",
    data:{
      "id" : id
    },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
          title : 'Success',
          text: 'Paid',
          timer: 1000,
          type: 'success'
        });
				$("#row-"+id).remove();
			}else{
				swal("Error", rs, "error");
			}
		}
	});
}



function unConfirmPayment(id)
{
	$("#confirmModal").modal('hide');
	load_in();
	$.ajax({
		url:BASE_URL + 'orders/order_payment/un_confirm_payment',
		type:"POST",
    cache:"false",
    data:{
      "id" : id
    },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
          title : 'Success',
          timer: 1000,
          type: 'success'
        });
				$("#row-"+id).remove();
			}else{
				swal("Error", rs, "error");
			}
		}
	});
}



function viewDetail(id)
{
	load_in();
	$.ajax({
		url:BASE_URL + 'orders/order_payment/get_payment_detail',
		type:"POST",
    cache:"false",
    data:{
      "id" : id
    },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal('Error', 'No information found or payment has already been confirmed.', 'error');
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




function removePayment(id, name)
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
				url: BASE_URL + 'orders/order_payment/remove_payment',
				type:"POST",
        cache:"false",
        data:{
          "id" : id
        },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
              title : "Success",
              timer: 1000,
              type: "success"
            });

						$("#row-"+id).remove();
					}else{
						swal("Error!!", "Unable to delete item or payment confirmed", "error");
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
