function addNew(){
  window.location.href = BASE_URL + 'masters/channels/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/channels';
}


function getEdit(code){
  window.location.href = BASE_URL + 'masters/channels/edit/'+code;
}


function clearFilter(){
  var url = BASE_URL + 'masters/channels/clear_filter';
  var page = BASE_URL + 'masters/channels';
  $.get(url, function(rs){
    window.location.href = page;
  });
}


function getDelete(code, name){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ ' + name + ' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    window.location.href = BASE_URL + 'masters/channels/delete/' + code;
  })
}


$('#customer_name').autocomplete({
  source:BASE_URL + 'auto_complete/get_customer_code_and_name',
  autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var code = arr[0];
			var name = arr[1];
			$("#customer_code").val(code);
			$("#customer_name").val(name);
		}else{
			$("#customer_code").val('');
			$(this).val('');
		}
	}
})


function toggleOnline(code){
  var option = $('#online-'+code).val();
  $.ajax({
    url:BASE_URL + 'masters/channels/toggle_online',
    type:'POST',
    cache:false,
    data:{
      'code' : code,
      'is_online' : option
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == '1'){
        $('#online-label-'+code).html('<i class="fa fa-check green"></i>');
        $('#online-'+code).val(rs);
      }else if(rs == '0'){
        $('#online-label-'+code).html('<i class="fa fa-times"></i>');
        $('#online-'+code).val(rs);
      }else{
        swal('Error', rs, 'error');
      }
    }
  })
}
