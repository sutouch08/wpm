function addNew(){
  window.location.href = BASE_URL + 'masters/product_style/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/product_style';
}


function getEdit(code){
  window.location.href = BASE_URL + 'masters/product_style/edit/'+code;
}


function clearFilter(){
  var url = BASE_URL + 'masters/product_style/clear_filter';
  var page = BASE_URL + 'masters/product_style';
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
    window.location.href = BASE_URL + 'masters/product_style/delete/' + code;
  })
}



function getSearch(){
  $('#searchForm').submit();
}
