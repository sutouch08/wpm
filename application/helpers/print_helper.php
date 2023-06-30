<?php
function doc_type($role)
{
	switch($role)
	{
		case 'S' :
			$content	= "order";
			$title 		= "Packing List";
		break;

		case 'C' :
			$content = "consign";
			$title = "Delivery slip / Invoice (Consignment)";
		break;

		case 'N' :
			$content = "consign";
			$title = "Delivery slip / Invoice (Consignment)";
		break;

		case 'U' :
			$content = "support";
			$title = "Delivery slip / Complimentary slip";
		break;

		case 'P' :
			$content = "sponsor";
			$title = "Delivery slip / Sponsored slip";
		break;

		case 'T' :
			$content = "transform";
			$title = "Delivery slip / Products transform slip";
		break;

		case 'L' :
			$content = "lend";
			$title = "Delivery slip / Lending slip";
		break;

		case 'R' :
			$content 	= "requisition";
			$title 		= "Delivery slip / Requisition slip";
		break;

		default :
			$content = "order";
			$title = "Delivery slip / Invoice";
		break;
	}

	return array("content"=>$content, "title"=>$title);
}






function get_header($order)
{
	$CI =& get_instance();

	//---	เบิกสปอนเซอร์
	if( $order->role == 'P')
	{
		$header	= array(
			"Receiver" => $order->customer_name,
			"Date" => thai_date($order->date_add, FALSE, '/'),
			"Requester" => $CI->user_model->get_name($order->user),
			"Document No" => $order->code,
			"Maker" =>  $CI->user_model->get_name($order->user)
		);
	}



	//---	ยิมสินค้า
	else if($order->role == 'L' )
	{
		$header		= array(
			"Document No"	=> $order->code,
			"Date"	=> thai_date($order->date_add, FALSE, '/'),
			"Lender"	=> $order->customer_name,
			"Maker" => $CI->user_model->get_name($order->user)
		);
	}


	//---	เบิก หรือ เบิกแปรสภาพ
	else if( $order->role == 'R' || $order->role == 'T' )
	{
		$header		= array(
			"Customer"	=> $order->customer_name,
			"Date"	=> thai_date($order->date_add, FALSE, '/'),
			"Requester"	=> $CI->user_model->get_name($order->user),
			"Document No"	=> $order->code
		);
	}

	//---	เบิกอภินันท์
	else if( $order->role == 'U')
	{
		$header	= array(
			"Requester"	=> $order->customer_name,
			"Date"	=> thai_date($order->date_add, FALSE, '/'),
			"Maker"	=> $CI->user_model->get_name($order->user),
			"Document No"	=> $order->code
		);
	}
	else if( $order->role == 'C' OR $order->role == 'N')
	{
		$header	= array(
			"Customer"	 => $order->customer_name,
			"Date"		=> thai_date($order->date_add, FALSE, '/'),
			"Maker" => $CI->user_model->get_name($order->user),
			"Document No" => $order->code
		);
	}
	else
	{
		$ref = !empty($order->reference) ? '['.$order->reference.']' : '';
		$header	= array(
			"Customer"	=> $order->customer_name,
			"Date"		=> thai_date($order->date_add, FALSE, '/'),
			"Maker" => $CI->user_model->get_name($order->user),
			"Document No" => $order->code.$ref
		);
	}

	return $header;
}



function barcodeImage($barcode)
{
	return '<img src="'.base_url().'assets/barcode/barcode.php?text='.$barcode.'" style="height:8mm;" />';
}


function inputRow($text, $style='')
{
  return '<input type="text" class="print-row" value="'.$text.'" style="'.$style.'" />';
}


 ?>
