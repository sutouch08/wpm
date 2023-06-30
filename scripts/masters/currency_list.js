var HOME = BASE_URL + 'masters/currency/';

function syncData() {
  load_in();

  $.ajax({
    url:HOME + 'sync_data',
    type:'GET',
    case:false,
    success:function(rs) {
      load_out();
      if(rs == 'success') {
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTitle(() => {
          window.location.reload();
        }, 1200);
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  });
}

function toggleActive(code) {
  let active = $('#'+code).is(':checked') ? 1 : 0;

  $.ajax({
    url:HOME + 'set_active',
    type:'POST',
    cache:false,
    data:{
      'code' : code,
      'active' : active
    },
    success:function(rs) {
      console.log(rs);
    }
  });
}


function clearFilter() {
  $.ajax({
    url:HOME + 'clear_filter',
    type:'POST',
    cache:false,
    success:function() {
      window.location.href = HOME;
    }
  })
}
