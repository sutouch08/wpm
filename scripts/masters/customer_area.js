function addNew(){
  window.location.href = BASE_URL + 'masters/customer_area/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/customer_area';
}


function getEdit(code){
  window.location.href = BASE_URL + 'masters/customer_area/edit/'+code;
}


function clearFilter(){
  var url = BASE_URL + 'masters/customer_area/clear_filter';
  var page = BASE_URL + 'masters/customer_area';
  $.get(url, function(rs){
    window.location.href = page;
  });
}


function getDelete(code, name){
  swal({
    title:'Are sure ?',
    text:'Do you want to delete ' + name + ' ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
  },function(){
    window.location.href = BASE_URL + 'masters/customer_area/delete/' + code;
  })
}



function getSearch(){
  $('#searchForm').submit();
}
