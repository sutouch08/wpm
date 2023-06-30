<?php

function select_currency($code = NULL)
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/currency_model');
  $options = $CI->currency_model->get_all_active();

  if( ! empty($options))
  {
    $df = getConfig('CURRENCY');

    foreach($options as $rs)
    {
      if($code === NULL)
      {
        $sc .= '<option value="'.$rs->CurrCode.'" '.is_selected($df, $rs->CurrCode).'>'.$rs->CurrCode.'</option>';
      }
      else
      {
        $sc .= '<option value="'.$rs->CurrCode.'" '.is_selected($code, $rs->CurrCode).'>'.$rs->CurrCode.'</option>';
      }
    }
  }

  return $sc;
}


function convertPrice($price, $rate = 0, $old_rate = 1)
{

  if($price > 0 && $rate > 0)
  {
    $rate = $old_rate > 0 ? $rate / $old_rate : $rate;
    return round($price * $rate, 6);
  }

  return $price;
}


function convertFC($amount, $rate = 0, $old_rate = 1)
{
  if($amount > 0 && $rate > 0)
  {
    $rate = $old_rate > 0 ? $rate / $old_rate : $rate;
    return round($amount/$rate, 6);
  }

  return $amount;
}

?>
