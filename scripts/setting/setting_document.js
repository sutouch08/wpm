function checkDocumentSetting(){
  var pre = {};
  var data = {};
  var prefix_error = 0;
  var error_message = 'Duplicated prefix ';
  $('.prefix').each(function(index, el){
    name = $(this).attr('name');
    value = $(this).val();
    //--- ถ้าพบว่ามีรายการใดที่ว่าง
    if($(this).val() == ''){
      $(this).addClass('has-error');
      error_message = 'Please enter the document prefix in all fields.';
      prefix_error++;
    }

    if(value.length != 2){
      $(this).addClass('has-error');
      error_message ='Please specify a 2-character document prefix.';
      prefix_error++;
      return false;
    }

    if(pre[value] !== undefined){
      $(this).addClass('has-error');
      error_message = error_message + pre[value]+', ';
      prefix_error++;
    }else{
      $(this).removeClass('has-error');
      pre[value] = value;
      data[name] = $(this).val();
    }
  });

  if(prefix_error > 0){
    swal('Error!', error_message, 'error');
    return false;
  }


  var min = 3;
  var max = 7;
  var error = 0;
  $('.digit').each(function(index,el){
    name = $(this).attr('name');
    value = $(this).val();

    if(value < min || value > max || value == ''){
      $(this).addClass('has-error');
      error++;
    }else{
      $(this).removeClass('has-error');
    }
  });

  if(error > 0){
    swal('The number of units must be between 3 - 7 digits.');
    return false;
  }


  updateConfig('documentForm');

}



function checkPrefix(){
  var pre = {};
  var data = {};
  $('.prefix').each(function(index, el){
    name = $(this).attr('name');
    value = $(this).val();
    //--- ถ้าพบว่ามีรายการใดที่ว่าง
    if($(this).val() == ''){
      $(this).addClass('has-error');
      swal('Please enter the document prefix in all fields.');
      return false;
    }

    if(pre[value] !== undefined){
      $(this).addClass('has-error');
      swal('Duplicated '+ pre[value]);
      return false;
    }else{
      $(this).removeClass('has-error');
      pre[value] = value;
      data[name] = $(this).val();
    }
  });

  return data;
}



function checkDigit(){
  var min = 3;
  var max = 7;
  var error = 0;
  $('.digit').each(function(index,el){
    name = $(this).attr('name');
    value = $(this).val();

    if(value < min || value > max || value == ''){
      $(this).addClass('has-error');
      error++;
    }else{
      $(this).removeClass('has-error');
    }
  });

  if(error > 0){
    swal('The number of units must be between 3 - 7 digits.');
    return false;
  }

  return true;
}
