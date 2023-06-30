<?php
function paymentLabel($order_code, $isExists, $isPaid)
{
	$sc = "";
	if( $isExists === TRUE )
	{
    if( $isPaid == 1 )
		{
			$sc .= '<button type="button" class="btn btn-sm btn-success" onClick="viewPaymentDetail()">';
			$sc .= 'Paid | View detail';
			$sc .= '</button>';
		}
		else
		{
			$sc .= '<button type="button" class="btn btn-sm btn-primary" onClick="viewPaymentDetail()">';
			$sc .= 'Payment uploaded | View detail';
			$sc .= '</button>';
		}
	}

	return $sc;
}



function paymentExists($order_code)
{
  $CI =& get_instance();
  $CI->load->model('orders/order_payment_model');
  return $CI->order_payment_model->is_exists($order_code);
}


function payment_image_url($order_code)
{
  $CI =& get_instance();
	$link	= base_url().'images/payments/'.$order_code.'.jpg';
  $file = $CI->config->item('image_file_path').'payments/'.$order_code.'.jpg';
	if( ! file_exists($file) )
	{
		$link = FALSE;
	}

	return $link;
}


function getSpace($amount, $length)
{
	$sc = '';
	$i	= strlen($amount);
	$m	= $length - $i;
	while($m > 0 )
	{
		$sc .= '&nbsp;';
		$m--;
	}
	return $sc.$amount;
}




function get_summary($order, $details, $banks)
{
	$payAmount = 0;
	$orderAmount = 0;
	$discount = 0;
	$totalAmount = 0;

	$orderTxt = '<div>Order Summary</div>';
	$orderTxt .= '<div>Order No : '.$order->code.'</div>';
	$orderTxt .= '<div style="width:100%; border-bottom:solid 1px #CCC;">&nbsp;</div>';

	foreach($details as $rs)
	{
		$orderTxt .= '<div class="width-100">';
		$orderTxt .=   $rs->product_code.' <span class="pull-right">('.number($rs->qty).') x '.number($rs->price, 2);
		$orderTxt .= '</div>';
		$orderAmount += $rs->qty * $rs->price;
		$discount += $rs->discount_amount;
		$totalAmount += $rs->total_amount;
	}

	$orderTxt .= "<br/>";
	$orderTxt .= 'Total Amount'.getSpace(number( $orderAmount, 2), 24).'<br/><br/>';

	if( ($discount + $order->bDiscAmount) > 0 )
	{
		$orderTxt .= 'Total Discount'.getSpace('- '.number( ($discount + $order->bDiscAmount), 2), 27).'<br/>';
		$orderTxt .= '<br/>';
	}



	$payAmount = $orderAmount - ($discount + $order->bDiscAmount);
	$orderTxt .= 'Net Amount' . getSpace(number( $payAmount, 2), 29).'<br/>';


	$orderTxt .= '====================<br/><br/>';

	if(!empty($banks))
	{
		$orderTxt .= 'Payment can be made at <br/>';
		$orderTxt .= '<br/>';
		foreach($banks as $rs)
		{
			$orderTxt .= '- '.$rs->bank_name.'<br/>';
			$orderTxt .= '&nbsp;&nbsp;&nbsp;&nbsp;Branch '.$rs->branch.'<br/>';
			$orderTxt .= '&nbsp;&nbsp;&nbsp;&nbsp;Account Name '.$rs->acc_name.'<br/>';
			$orderTxt .= '&nbsp;&nbsp;&nbsp;&nbsp;Account Number '.$rs->acc_no.'<br/>';
			$orderTxt .= '--------------------<br/>';
		}
	}

	$orderTxt .= "<br/>";
	$orderTxt .= '** If you want a tax invoice Please request a tax invoice within 5 working days.';

	return $orderTxt;
}


// function select_order_role($role = '')
// {
// 	$sc = '';
// 	$CI =& get_instance();
// 	$rs = $CI->db->query("SELECT * FROM order_role");
// 	if($rs->num_rows() > 0)
// 	{
// 		foreach($rs->result() as $rd)
// 		{
// 			$sc .= '<option value="'.$rd->code.'" '.is_selected($role, $rd->code).'>'.$rd->name.'</option>';
// 		}
// 	}
//
// 	return $sc;
// }

function select_order_role($role = '')
{
	$sc = '';

	$ds = array(
		'C' => 'Consignment (IV)',
		'N' => 'Consignment (TR)',
		'L'	=> 'Lend',
		'P'	=> 'Sponsor',
		'S'	=> 'Sale Order',
		'T'	=> 'Transform',
		'U'	=> 'Support'
	);

	foreach($ds as $key => $value)
	{
		$sc .= '<option value="'.$key.'" '.is_selected($role, $key).'>'.$value.'</option>';
	}

	return $sc;
}



function role_name($role)
{
	$ds = array(
		'C' => 'Consignment (IV)',
		'N' => 'Consignment (TR)',
		'L'	=> 'Lend',
		'M'	=> 'Consignment Sold (TR)',
		'D' => 'Consignment Sold (IV)',
		'P'	=> 'Sponsor',
		'S'	=> 'Sale Order',
		'T'	=> 'Transform',
		'U'	=> 'Support',
	);

	return isset($ds[$role]) ? $ds[$role] : NULL;
}


 ?>
