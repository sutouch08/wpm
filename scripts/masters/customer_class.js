function addNew(){
  window.location.href = BASE_URL + 'masters/customer_class/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/customer_class';
}


function getEdit(code){
  window.location.href = BASE_URL + 'masters/customer_class/edit/'+code;
}


function clearFilter(){
  var url = BASE_URL + 'masters/customer_class/clear_filter';
  var page = BASE_URL + 'masters/customer_class';
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
    window.location.href = BASE_URL + 'masters/customer_class/delete/' + code;
  })
}



function getSearch(){
  $('#searchForm').submit();
}
