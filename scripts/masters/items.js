var HOME = BASE_URL + 'masters/items/';

var click = 0;

function addNew(){
  window.location.href = HOME + 'add_new';
}



function goBack(){
  window.location.href = HOME;
}


function getEdit(code){
	$('#item-code').val(code);
	$('#edit-form').submit();
}


function update() {

  if(click != 0) {
    return false;
  }

  click = 1;

	let err = 0;

  $('.r').removeClass('has-error');
  $('.e').text('');

  $('.r').each(function() {
    let el = $(this);

    if(el.val() == "") {
      let la = el.attr('id');
      $('#'+la+"-error").text('Required');
      el.addClass('has-error');
      err++;
    }
  });


  let h = {
    'code' : $('#code').val().trim(),
    'old_code' : $('#old-code').val().trim(),
    'name' :$('#name').val().trim(),
    'style' : $('#style').val().trim(),
    'old_style' : $('#old-style').val().trim(),
    'color' : $('#color').val().trim(),
    'size' : $('#size').val().trim(),
    'barcode' : $('#barcode').val().trim(),
    'cost' : parseDefault(parseFloat($('#cost').val()), 0),
    'price' : parseDefault(parseFloat($('#price').val()), 0),
    'unit' : $('#unit').val(),
    'brand' : $('#brand').val(),
    'group' : $('#group').val(),
    'main_group' : $('#mainGroup').val(),
    'sub_group' : $('#subGroup').val(),
    'category' : $('#category').val(),
    'kind' : $('#kind').val(),
    'type' : $('#type').val(),
    'year' : $('#year').val(),
    'count_stock' : $('#count-stock').is(':checked') ? 1 : 0,
    'can_sell' : $('#can-sell').is(':checked') ? 1 : 0,
    'active' : $('#active').is(':checked') ? 1 : 0
  };

  if(h.cost < 0) {
    $('#cost').addClass('has-error');
    $('#cost-error').text('Cost cannot be less than 0');
    err++;
  }

  if(h.price < 0) {
    $('#price').addClass('has-error');
    $('#price-error').text('Price cannot be less than 0');
    err++
  }

  if(err > 0) {
    click = 0;
    return false;
  }

	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			"data" : JSON.stringify(h)
		},
		success:function(rs) {
			load_out();

			if(rs == 'success') {
				swal({
					title:"Success",
					type:'success',
					timer:1000
				});
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}

      click = 0;
		},
		error:function(xhr) {
			load_out();
			swal({
				title:"Error!",
				text:'Error : '+xhr.responseText,
				type:'error',
				html:true
			});

      click = 0;
		}
	})
}

function duplicate(code){
  window.location.href = HOME + 'duplicate/'+code;
}



$('#style').autocomplete({
  source: BASE_URL + 'auto_complete/get_style_code',
  autoFocus:true,
  close:function() {
    let rs = $(this).val();
    let arr = rs.split(' | ');
    if(arr.length == 2) {
      $(this).val(arr[0]);
    }
    else {
      $(this).val('');
    }
  }
});

$('#color').autocomplete({
  source: BASE_URL + 'auto_complete/get_color_code_and_name',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var err = rs.split(' | ');
    if(err.length == 2){
      $(this).val(err[0]);
    }else{
      $(this).val('');
    }
  }
});


$('#size').autocomplete({
  source:BASE_URL + 'auto_complete/get_size_code_and_name',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var err = rs.split(' | ');
    if(err.length == 2){
      $(this).val(err[0]);
    }else{
      $(this).val('');
    }
  }
});


function checkAdd(){
  var code = $('#code').val();

  if(code.length > 0){
    $.ajax({
      url:HOME + 'is_exists_code/'+code,
      type:'GET',
      cache:false,
      success:function(rs){
        if(rs != 'ok'){
          set_error($('#code'), $('#code-error'), rs);
          return false;
        }else{
          clear_error($('#code'), $('#code-error'));
          $('#btn-submit').click();
        }
      }
    })
  }
}



function add() {
  if(click != 0) {
    return false;
  }

  click = 1;
  err = 0;

  $('.r').removeClass('has-error');
  $('.e').text('');

  $('.r').each(function() {
    let el = $(this);

    if(el.val() == "") {
      let la = el.attr('id');
      $('#'+la+"-error").text('Required');
      el.addClass('has-error');
      err++;
    }
  });


  let h = {
    'code' : $.trim($('#code').val()),
    'old_code' : $.trim($('#old-code').val()),
    'name' : $.trim($('#name').val()),
    'style' : $.trim($('#style').val()),
    'old_style' : $.trim($('#old-style').val()),
    'color' : $.trim($('#color').val()),
    'size' : $.trim($('#size').val()),
    'barcode' : $.trim($('#barcode').val()),
    'cost' : parseDefault(parseFloat($('#cost').val()), 0),
    'price' : parseDefault(parseFloat($('#price').val()), 0),
    'unit' : $('#unit').val(),
    'brand' : $('#brand').val(),
    'group' : $('#group').val(),
    'main_group' : $('#mainGroup').val(),
    'sub_group' : $('#subGroup').val(),
    'category' : $('#category').val(),
    'kind' : $('#kind').val(),
    'type' : $('#type').val(),
    'year' : $('#year').val(),
    'count_stock' : $('#count-stock').is(':checked') ? 1 : 0,
    'can_sell' : $('#can-sell').is(':checked') ? 1 : 0,
    'active' : $('#active').is(':checked') ? 1 : 0
  };

  if(h.cost < 0) {
    $('#cost').addClass('has-error');
    $('#cost-error').text('Cost cannot be less than 0');
    err++;
  }

  if(h.price < 0) {
    $('#price').addClass('has-error');
    $('#price-error').text('Price cannot be less than 0');
    err++
  }

  if(err > 0) {
    click = 0;
    return false;
  }

  load_in();

  $.ajax({
    url:HOME + 'add',
    type:'POST',
    cache:false,
    data:{
      'data' : JSON.stringify(h)
    },
    success:function(rs) {
      load_out();

      if(rs == 'success') {
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(() => {
          addNew();
        }, 1200);
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error',
          html:true
        });

        click = 0;
      }
    },
    error:function(rs) {
      load_out();

      swal({
        title:'Error!',
        text:rs.responseText,
        type:'error',
        html:true
      });

      click = 0;
    }
  })
}


function clearFilter(){
  var url = HOME + 'clear_filter';
  var page = BASE_URL + 'masters/products';
  $.get(url, function(){
    goBack();
  });
}


function getDelete(code){
  swal({
    title:'Are sure ?',
    text:'Do you want to delete ' + code + ' ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: false
  },function(){
    $.ajax({
      url: BASE_URL + 'masters/items/delete_item/' + encodeURIComponent(code),
      type:'GET',
      cache:false,
      success:function(rs){
        if(rs === 'success'){
          swal({
            title:'Deleted',
            type:'success',
            timer:1000
          });

          $('#row-'+code).remove();
        }else{
          swal({
            title:'Error!',
            text:rs,
            type:'error'
          });
        }
      }
    })

  })
}

function getTemplate(){
  var token	= new Date().getTime();
	get_download(token);
	window.location.href = BASE_URL + 'masters/items/download_template/'+token;
}

function getSearch(){
  $('#searchForm').submit();
}


function sendToSap(code) {
	load_in();
	$.ajax({
		url:BASE_URL + 'masters/items/send_to_sap',
		type:'POST',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs) {
			load_out();
			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}
			else {
				swal({
					title:'Error',
					text:rs,
					type:'error'
				})
			}
		}
	})
}
