
function checkStyle(){
  var el = $('#code');
  var label = $('#code-error');
  var style = el.val();
  var url = BASE_URL + '/masters/products/is_style_exists/' + style;
  if(style.length > 0){
    $.get(url, function(rs){
      if(rs == 'exists'){
        $('#valid').val(''); ///---
        set_error(el, label, "Model code already exists");
      }else{
        clear_error(el, label);
        $('#valid').val(1);
      }
    });
  }
}


$('#code').focusout(function(){
  if($(this).val().length > 0){
    checkStyle();
  }
});
