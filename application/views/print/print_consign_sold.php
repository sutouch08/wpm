<?php
  $this->load->helper('print');

  $page = '';
  $page .= $this->printer->doc_header();
  $title = 'Consignment Order';
	$this->printer->add_title($title);
	$header	= array(
    'No.' => $doc->code,
    'Date'  => thai_date($doc->date_add, FALSE, '/'),
    'Customer' => $doc->customer_code.' : '.$doc->customer_name,
    'Reference' => $doc->ref_code,
    'Location' => $doc->zone_name,
    'Warehouse' => $doc->warehouse_name,
    'Maker' => $this->user_model->get_name($doc->user)
	);

	$this->printer->add_header($header);

	$total_row 	= empty($details) ? 0 : count($details);
  $subtotal_row = 4;
	$config = array(
    'total_row' => $total_row,
    'font_size' => 10,
    'sub_total_row' => $subtotal_row,
    'footer' => TRUE
  );

	$this->printer->config($config);

	$row 	= $this->printer->row;
	$total_page = $this->printer->total_page;
	$total_qty 	= 0;
  $total_price = 0;
  $total_discount = 0;
  $total_amount = 0;

	//**************  กำหนดหัวตาราง  ******************************//
	$thead	= array(
						array("#", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("Barcode", "width:15%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("Items", "width:30%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
            array("Price", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
            array("Disc.", "width:15%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("Qty.", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("Amount", "width:15%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);

	$this->printer->add_subheader($thead);

	//***************************** กำหนด css ของ td *****************************//
	$pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
              "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:right; border-left: solid 1px #ccc; border-top:0px;"
							);
	$this->printer->set_pattern($pattern);

	//*******************************  กำหนดช่องเซ็นของ footer *******************************//
	$footer	= array(
						array("Maker", "", "Date ............................."),
						array("Inspector", "","Date ............................."),
						array("Approver", "","Date .............................")
						);

  $this->printer->set_footer($footer);

	$n = 1;
  $index = 0;
	while($total_page > 0 )
	{
		$page .= $this->printer->page_start();
			$page .= $this->printer->top_page();
			$page .= $this->printer->content_start();
				$page .= $this->printer->table_start();
				if($doc->status == 2)
				{
					$page .= '
				  <div style="width:0px; height:0px; position:relative; left:30%; line-height:0px; top:300px;color:red; text-align:center; z-index:100000; opacity:0.1; transform:rotate(-45deg)">
				      <span style="font-size:150px; border-color:red; border:solid 10px; border-radius:20px; padding:0 20 0 20;">Cancel</span>
				  </div>';
				}

				$i = 0;

				while($i < $row)
        {
					$rs = isset($details[$index]) ? $details[$index] : array();
					if(!empty($rs))
          {
            $data = array(
              $n,
							inputRow($rs->barcode),
							inputRow($rs->product_code.' : '.$rs->product_name),
              number($rs->price, 2),
              $rs->discount,
              number($rs->qty),
              number($rs->amount, 2)
						);

            $total_qty += $rs->qty;
            $total_discount += $rs->discount_amount;
            $total_price += $rs->qty * $rs->price;
            $total_amount += $rs->amount;
          }
          else
          {
            $data = array("", "", "", "","", "", "");
          }
					$page .= $this->printer->print_row($data);
					$n++;
          $i++;
          $index++;
				}

				$page .= $this->printer->table_end();

				if($this->printer->current_page == $this->printer->total_page)
				{
					$qty = number($total_qty);
          $amount = number($total_price, 2);
          $discount = number($total_discount, 2);
          $net_amount = number($total_amount, 2);
				}else{
					$qty = "";
          $amount = '';
          $discount = "";
          $net_amount = "";
				}



        //--- จำนวนรวม   ตัว
        $sub_qty  = '<td class="width-60 subtotal-first text-center" style="height:'.$this->printer->row_height.'mm;">';
        $sub_qty .= '</td>';
        $sub_qty .= '<td class="width-25 subtotal">';
        $sub_qty .=  '<strong>Total Qty.</strong>';
        $sub_qty .= '</td>';
        $sub_qty .= '<td class="width-15 subtotal text-right">';
        $sub_qty .=  $qty;
        $sub_qty .= '</td>';

        //--- ราคารวม
        $sub_price  = '<td rowspan="'.($subtotal_row).'" class="subtotal-first font-size-10" style="height:'.$this->printer->row_height.'mm;">';
        $sub_price .=  '<strong>Remark : </strong> '.$doc->remark;
        $sub_price .= '</td>';
        $sub_price .= '<td class="subtotal">';
        $sub_price .=  '<strong>Sub Total</strong>';
        $sub_price .= '</td>';
        $sub_price .= '<td class="subtotal text-right">';
        $sub_price .= $amount;
        $sub_price .= '</td>';

        //--- ส่วนลดรวม
        $sub_disc  = '<td class="subtotal" style="height:'.$this->printer->row_height.'mm;">';
        $sub_disc .=  '<strong>Total Discount</strong>';
        $sub_disc .= '</td>';
        $sub_disc .= '<td class="subtotal text-right">';
        $sub_disc .=  $discount;
        $sub_disc .= '</td>';

        //--- ยอดสุทธิ
        $sub_net  = '<td class="subtotal" style="height:'.$this->printer->row_height.'mm;">';
        $sub_net .=  '<strong>Total Amount</strong>';
        $sub_net .= '</td>';
        $sub_net .= '<td class="subtotal text-right">';
        $sub_net .=  $net_amount;
        $sub_net .= '</td>';

        $sub_total = array(
          array($sub_qty),
          array($sub_price),
          array($sub_disc),
          array($sub_net)
        );


			$page .= $this->printer->print_sub_total($sub_total);
			$page .= $this->printer->content_end();
			$page .= $this->printer->footer;
		  $page .= $this->printer->page_end();
		  $total_page --;
      $this->printer->current_page++;
	}

	$page .= $this->printer->doc_footer();

  echo $page;
?>
