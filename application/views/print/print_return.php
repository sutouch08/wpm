<?php
  $this->load->helper('print');

  $page = '';
  $page .= $this->printer->doc_header();
	$this->printer->add_title("รับคืนสินค้า(ลดหนี้ขาย)");
	$header	= array(
    'เลขที่' => $doc->code,
    'วันที่'  => thai_date($doc->date_add, FALSE, '/'),
    'ลูกค้า' => $doc->customer_code.' : '.$doc->customer_name,
    'เลขที่อ้างอิง' => $doc->invoice,
    'โซน' => $doc->zone_name,
    'คลัง' => $doc->warehouse_name,
    'พนักงาน' => $this->user_model->get_name($doc->user)
	);
  if($doc->remark != '')
  {
    $header['หมายเหตุ'] = $doc->remark;
  }

	$this->printer->add_header($header);

	$total_row 	= empty($details) ? 0 : count($details);
	$config = array(
    'total_row' => $total_row,
    'font_size' => 10,
    'sub_total_row' => 1
  );

	$this->printer->config($config);

	$row 	= $this->printer->row;
	$total_page = $this->printer->total_page;
	$total_qty 	= 0;
	$total_receive = 0;
  	$total_amount = 0;
	//**************  กำหนดหัวตาราง  ******************************//
	$thead	= array(
						array("ลำดับ", "width:10mm; vertical-align:middle; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("รหัส", "width:40mm; vertical-align:middle; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:60mm; vertical-align:middle; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
            array("ราคา", "width:20mm; vertical-align:middle; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวนคืน", "width:20mm; vertical-align:middle; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวนรับ", "width:20mm; vertical-align:middle; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("มูลค่า", "width:20mm; vertical-align:middle; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
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
						array("ผู้รับ", "", "วันที่ ............................."),
						array("ผู้ตรวจสอบ", "","วันที่ ............................."),
						array("ผู้อนุมัติ", "","วันที่ .............................")
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
				      <span style="font-size:150px; border-color:red; border:solid 10px; border-radius:20px; padding:0 20 0 20;">ยกเลิก</span>
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
							inputRow($rs->product_code),
							inputRow($rs->product_name),
              number($rs->price, 2),
							number($rs->qty),
							number($rs->receive_qty),
              number($rs->amount, 2)
						);
            $total_qty += $rs->qty;
						$total_receive += $rs->receive_qty;
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
					$remark = $doc->remark;
				}else{
					$qty = "";
					$remark = "";
				}

				$sub_total = array(
          array(
          "<td style='height:".$this->printer->row_height."mm; border: solid 1px #ccc;
          border-bottom:0px; border-left:0px; text-align:right; width:131mm;'>
          <strong>รวม</strong>
          </td>
          <td style='height:".$this->printer->row_height."mm; border: solid 1px #ccc;
          border-right:0px; border-bottom:0px; width:20mm; text-align:right;'>
          ".number($total_qty)."</td>
					<td style='height:".$this->printer->row_height."mm; border: solid 1px #ccc;
          border-right:0px; border-bottom:0px; width:20mm; text-align:right;'>
          ".number($total_receive)."</td>
          <td style='height:".$this->printer->row_height."mm; border: solid 1px #ccc;
          border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; width:20mm;
          text-align:right;'>".number($total_amount, 2)."</td>")

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
