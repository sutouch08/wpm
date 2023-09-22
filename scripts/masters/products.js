function addNew(){
  window.location.href = BASE_URL + 'masters/products/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/products';
}


function getEdit(code){
  url = BASE_URL + 'masters/products/edit/' + encodeURIComponent(code);
  window.location.href = url;
}


function changeURL(style, tab, a)
{
	var url = BASE_URL + 'masters/products/edit/' + style + '/' + tab;
	var stObj = { stage: 'stage' };
	window.history.pushState(stObj, 'products', url);
  if( a !== undefined )
  {
    $('#'+tab+'-a').click();
  }
}


function toggleTab(tabName) {
  $('.tab-pane').removeClass('in');
  $('.tab-pane').removeClass('active');

  $('#'+tabName).addClass('active');
  $('#'+tabName).addClass('in');
}



function newItems(){
  var style = $('#style').val();
  window.location.href = BASE_URL + 'masters/products/item_gen/' + style;
}




function clearFilter(){
  var url = BASE_URL + 'masters/products/clear_filter';
  var page = BASE_URL + 'masters/products';
  $.get(url, function(rs){
    window.location.href = page;
  });
}

function export_filter(){
  let code = $('#code').val();
  let name = $('#name').val();
  let group = $('#group').val();
  let sub_group = $('#sub_group').val();
  let category = $('#category').val();
  let kind = $('#kind').val();
  let type = $('#type').val();
  let brand = $('#brand').val();
  let year = $('#year').val();
  let token	= new Date().getTime();


  $('#export_code').val(code);
  $('#export_name').val(name);
  $('#export_group').val(group);
  $('#export_sub_group').val(sub_group);
  $('#export_category').val(category);
  $('#export_kind').val(kind);
  $('#export_type').val(type);
  $('#export_brand').val(brand);
  $('#export_year').val(year);
  $('#token').val(token);

  get_download(token);

  $('#export_filter_form').submit();

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
      url: BASE_URL + 'masters/products/delete_style/' + encodeURIComponent(code),
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



function getSearch(){
  $('#searchForm').submit();
}


function doExport(code){
  load_in();
  $.ajax({
    url:BASE_URL + 'masters/products/export_products/'+ encodeURIComponent(code),
    type:'POST',
    cache:false,
    success:function(rs){
      load_out();
      if(rs === 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });
      }else{
        swal({
          title:'Error',
          text:rs,
          type:'error'
        });
      }
    }
  })
}
