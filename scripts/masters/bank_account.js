var HOME = BASE_URL + 'masters/bank/';

function addNew(){
  window.location.href = HOME + 'add_new';
}



function goBack(){
  window.location.href = HOME;
}


function getEdit(code){
  window.location.href = HOME + 'edit/'+code;
}


function clearFilter(){
  var url = HOME + 'clear_filter';
  $.get(url, function(){
      goBack();
  });

}


$('.search').keyup(function(e){
	if(e.keyCode == 13){
		getSearch();
	}
});


function getSearch()
{
	$('#searchForm').submit();
}


function saveAdd(){
	var bank_code = $('#bank-code').val();
	var account_name = $('#acc-name').val();
	var account_no = $('#acc-no').val();
	var branch = $('#branch').val();

	if(bank_code === ""){
		set_error($('#bank-code'), $('#bank-code-error'), 'Please select bank');
		return false;
	}
	else
	{
		clear_error($('#bank-code'), $('#bank-code-error'));
	}


	if(account_name === ""){
		set_error($('#acc-name'), $('#acc-name-error'), 'Please specify account name');
		return false;
	}
	else
	{
		clear_error($('#acc-name'), $('#acc-name-error'));
	}


	if(account_no === ""){
		set_error($('#acc-no'), $('#acc-no-error'), 'Please specify account number');
		return false;
	}
	else
	{
		clear_error($('#acc-no'), $('#acc-no-error'));
	}


	if(branch === ""){
		set_error($('#branch'), $('#branch-error'), 'Please specify branch');
		return false;
	}
	else
	{
		clear_error($('#branch'), $('#branch-error'));
	}


	load_in();
	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'bank_code' : bank_code,
			'account_name' : account_name,
			'account_no' : account_no,
			'branch' : branch
		},
		success:function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs === 'success'){
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					addNew();
				}, 1200);
			}else{
				swal({
					title:'Error!!',
					text:rs,
					type:'error'
				});
			}
		}
	})
}



function update(){
	var id = $('#id').val();
	var bank_code = $('#bank-code').val();
	var account_name = $('#acc-name').val();
	var account_no = $('#acc-no').val();
	var branch = $('#branch').val();

	if(id === ""){
		swal({
			title:"Error!!",
			text:"Account Id not found",
			type:'error'
		});

		return false;
	}

  if(bank_code === ""){
		set_error($('#bank-code'), $('#bank-code-error'), 'Please select bank');
		return false;
	}
	else
	{
		clear_error($('#bank-code'), $('#bank-code-error'));
	}


	if(account_name === ""){
		set_error($('#acc-name'), $('#acc-name-error'), 'Please specify account name');
		return false;
	}
	else
	{
		clear_error($('#acc-name'), $('#acc-name-error'));
	}


	if(account_no === ""){
		set_error($('#acc-no'), $('#acc-no-error'), 'Please specify account number');
		return false;
	}
	else
	{
		clear_error($('#acc-no'), $('#acc-no-error'));
	}


	if(branch === ""){
		set_error($('#branch'), $('#branch-error'), 'Please specify branch');
		return false;
	}
	else
	{
		clear_error($('#branch'), $('#branch-error'));
	}
	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'bank_code' : bank_code,
			'account_name' : account_name,
			'account_no' : account_no,
			'branch' : branch
		},
		success:function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs === 'success'){
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					addNew();
				}, 1200);
			}else{
				swal({
					title:'Error!!',
					text:rs,
					type:'error'
				});
			}
		}
	})
}


function delete_bank(id){
	$.ajax({
		url:HOME + 'delete/'+id,
		type:'POST',
		cache:false,
		success:function(rs){
			var rs = $.trim(rs);
			if(rs === 'success'){
				swal({
					title:'Deleted',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					goBack();
				}, 1300);
			}
			else
			{
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				})
			}
		}
	})
}




function getDelete(id, name){
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
    delete_bank(id);
  })
}
