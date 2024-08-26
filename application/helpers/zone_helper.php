<?php
function zone_in($txt)
{
  $sc = array('0');
  $CI =& get_instance();
  $CI->load->model('masters/zone_model');
  $zone = $CI->zone_model->search($txt);
  if(!empty($zone))
  {
    foreach($zone as $rs)
    {
      $sc[] = $rs->code;
    }
  }

  return $sc;
}

function select_warehouse_zone($whsCode = NULL, $zone_code = NULL)
{
  $sc = "";
  $ci =& get_instance();
  $ci->load->model('masters/zone_model');

  if( ! empty($whsCode))
  {
    $list = $ci->zone_model->get_warehouse_zone($whsCode);

    if( ! empty($list))
    {
      foreach($list as $rs)
      {
        $sc .= '<option value="'.$rs->code.'" data-whs="'.$rs->wareouse_code.'" '.is_selected($zone_code, $rs->code).'>'.$rs->code.' : '.$rs->name.'</option>';
      }
    }
  }


  return $sc;
}


 ?>
