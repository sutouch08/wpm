<?php
function get_state_name($state)
{
  $name = array(
    '1' => 'รอดำเนินการ',
    '2' => 'รอชำระเงิน',
    '3' => 'รอจัดสินค้า',
    '4' => 'กำลังจัดสินค้า',
    '5' => 'รอตรวจสินค้า',
    '6' => 'กำลังตรวจสินค้า',
    '7' => 'รอการจัดส่ง',
    '8' => 'จัดส่งแล้ว',
    '9' => 'ยกเลิก',
		'10' => 'ส่ง RC แล้ว',
		'19' => 'In Progress',
		'20' => 'Packing',
		'21' => 'Packed',
		'22' => 'Shipped',
		'23' => 'Canceled'
  );

  return $name[$state];
}


function state_color($state, $is_saved = 1, $is_expired = 0)
{
  if($is_saved == 0 && $is_expired == 0)
  {
    return '';
  }
  else if($is_expired == 1)
  {
    return 'color:#CCC; background-color:#000;';
  }
  else
  {
    $color = array(
      '1' => 'color:#333; background-color:#66CEF5;',
      '2' => 'color:#333; background-color:#FFFF99;',
      '3' => 'color:#000; background-color:#ADA9D4;',
      '4' => 'color:#000; background-color:#FBB57F;',
      '5' => 'color:#FFF; background-color:#DA81F5;',
      '6' => 'color:#FFF; background-color:#088A68;',
      '7' => 'color:#000; background-color:#E7A9CD;',
      '8' => 'color:#333; background-color:#92CD88;',
      '9' => 'color:#000; background-color:#AAB2BD;',
			'10' => 'color:#000; background-color:#66CDAA;;',
			'19' => 'color:#000; background-color:#FBB57F;',
			'20' => 'color:#000; background-color:#088A68;',
			'21' => 'color:#000; background-color:#E7A9CD;',
			'22' => 'color:#333; background-color:#92CD88;',
			'23' => 'color:#000; background-color:#AAB2BD;'
    );

    return $color[$state];
  }
}

?>
