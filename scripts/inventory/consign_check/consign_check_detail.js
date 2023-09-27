function showDetail(pdCode){
  let code = $('#check_code').val();
  $.ajax({
    url: HOME + 'get_checked_detail/' + code,
    type:'GET',
    cache:'false',
    data:{
      'product_code' : pdCode
    },
    success:function(rs){
      if(isJson(rs)){
        var source = $('#box-detail-template').html();
        var data   = $.parseJSON(rs);
        var output = $('#modal_body');
        render(source, data, output);
        $('#checked-detail-modal').modal('show');
      }else{
        swal(rs);
      }
    }
  });
}



function removeCheckedItem(id_box, pdCode, qty, box){
  swal({
    title:'Are you sure ?',
    text:'Do you want to delete data in '+ box + ' ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText: 'Yes',
		cancelButtonText: 'Cancel',
		closeOnConfirm: true
  },function(){
    var code = $('#check_code').val();
    $.ajax({
      url: HOME + 'remove_checked_item/' + code,
      type:'POST',
      cache:'false',
      data:{
        'id_box' : id_box,
        'product_code' : pdCode
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          $('#row-'+id_box+'-'+pdCode).remove();
          qty = parseInt(qty);

          //--- update check qty in row
          var c_qty = parseInt($('.checked-' + pdCode).text());
          c_qty = c_qty - qty;
          $('.checked-' + pdCode).text(c_qty);

          //--- update diff qty in row
          var diff  = parseInt($('.diff-' + pdCode).text());
          diff  = diff + qty;
          $('.diff-' + pdCode).text(diff);

          //--- update total
          updateTotalCheckedQty();
          updateTotalDiffQty();

          swal({
            title:'Deleted',
            type:'success',
            timer:1000
          });
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  });
}




function clearDetails(){
  swal({
    title:'Are you sure ?',
    text:'Do you want to delete all data ?',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText: 'Yes',
		cancelButtonText: 'Cancel',
		closeOnConfirm: false
  },function(){
      var code = $('#check_code').val();
      $.ajax({
        url: HOME + 'clear_all_details/' + code,
        type:'POST',
        cache:'false',
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Deleted',
              type:'success',
              timer:1000
            });

            setTimeout(function(){
              window.location.reload();
            }, 1500);

          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
  });
}



function closeCheck(){
  var code = $('#check_code').val();
  var sumChecked = parseInt($('#sumCount').val());
  var sumDiff = parseInt($('#sumDiff').val());

  if(sumDiff <= 0){
    swal('ไม่พบยอดต่าง');
    return false;
  }

  swal({
    title:'Finish Count',
    text:'<center>The amount different from the counting '+ sumDiff +' pieces will be taken to deduct the consignment amount.</center><center>Do you want to continue?</center>',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'Yes',
    cancelButtonText:'Cancel',
    confirmButtonColor:'#F6BB42',
    closeOnConfirm:false
  }, function(){
    confirmCloseConisgn(code, sumChecked);
  });
}


function confirmCloseConisgn(code, sumChecked)
{
  if(sumChecked <= 0){
    swal({
      title:'Total count not found !',
      text:'<center>All remaining inventory in the location will be taken to deduct consignment sales.</center><center>Do you want to continue ?</center>',
      type:'warning',
      html:true,
      showCancelButton:true,
      confirmButtonText:'Yes',
      cancelButtonText:'Cancel',
      confirmButtonColor:'#F6BB42',
      closeOnConfirm:false
    }, function(){
      closeConsignCheck(code);
    });
  }else{
    closeConsignCheck(code);
  }
}



function closeConsignCheck(code){
  load_in();
  $.ajax({
    url:HOME + 'close_consign_check/'+code,
    type:'POST',
    cache:'false',
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          viewDetail(code);
        },1500);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}



function reloadStock(){
  swal({
    title:'Reload Stock ?',
    text:'Reload all remaining inventory in the location ?',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'Yes',
    cancelButtonText:'Cancel',
    confirmButtonColor:'#F6BB42',
    closeOnConfirm:false
  }, function(){
    var code = $('#check_code').val();
    load_in();
    $.ajax({
      url: HOME + 'reload_stock/'+code,
      type:'POST',
      cache:'false',
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            window.location.reload();
          }, 1500);
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  });
}


function openCheck(){
  swal({
    title:'Cancel save state ?',
    text:'<center>Do you want to cancel the recording for further counting?</center><center>Do you want to continue?</center>',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'Yes',
    cancelButtonText:'Cancel',
    confirmButtonColor:'#F6BB42',
    closeOnConfirm:false
  }, function(){
    var code = $('#check_code').val();
    load_in();
    $.ajax({
      url: HOME + 'open_consign_check/' + code,
      type:'POST',
      cache:'false',
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            goEdit(code);
          }, 1500);
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  });
}
