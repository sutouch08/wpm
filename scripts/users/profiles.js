function newProfile(){
  window.location.href = BASE_URL+'users/profiles/add_profile';
}



function goBack(){
  window.location.href = BASE_URL+'users/profiles';
}

function getEdit(id){
  window.location.href = BASE_URL + 'users/profiles/edit_profile/'+id;
}


function clearFilter(){
  var url = BASE_URL+'users/profiles/clear_filter';
  var page = BASE_URL+'users/profiles';
  $.get(url, function(rs){
    window.location.href = page;
  });
}


function getDelete(id, name){
  swal({
    title:'Are sure ?',
    text:'Do you want to delete '+ name +' ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
  },function(){
    window.location.href = BASE_URL + 'users/profiles/delete_profile/'+id;
  })
}
