<?php

	/*********  Sender  ***********/
	$sender			= '<div class="col-lg-12" style="font-size:18px; padding-top:15px; padding-bottom:30px;">';
	$sender			.= '<span style="display:block; margin-bottom:10px;">'.$cName.'</span>';
	$sender			.= '<span style="width:70%; display:block;">'.$cAddress.' '.$cPostCode.'</span>';
	$sender			.= '<span style="display:block"> Tel. '.$cPhone.'</span>';
	$sender			.= '</div>';
	/********* / Sender *************/



	/*********** Receiver  **********/
	$receiver		= '<div class="col-lg-12" style="font-size:18px; padding-left: 250px; padding-top:15px; padding-bottom:40px;">';
	$receiver		.= '<span style="display:block; margin-bottom:10px;">'.$ad->name.'</span>';
	$receiver		.= '<span style="display:block;">'.$ad->address.'</span>';
	$receiver		.= '<span style="display:block;"> '.$ad->sub_district.' '.$ad->district.'</span>';
	$receiver		.= '<span style="display:block;"> '.$ad->province.' '.$ad->postcode.'</span>';
	$receiver		.= $ad->phone == '' ? '' : '<span style="display:block;">Tel. '.$ad->phone.'</span>';
	$receiver		.= '</div>';
	/********** / Receiver ***********/

	/********* Transport  ***********/
	$transport = '';
	if( $sd !== FALSE )
	{
		$transport	= '<table style="width:100%; border:0px; margin-left: 30px; position: relative; bottom:1px;">';
		$transport	.= '<tr style="font-18px;"><td>'. $sd->name .'</td></tr>';
		$transport	.= '<tr style="font-18px;"><td>'. $sd->address1 .' '.$sd->address2.'</td></tr>';
		$transport	.= '<tr style="font-18px;"><td>Tel. '. $sd->phone.' Open : '.date('H:i', strtotime($sd->open)).' - '.date('H:i', strtotime($sd->close)).' - ( '.$sd->type.')</td></tr>';
		$transport 	.= '</table>';
	}

	/*********** / transport **********/

	$total_page		= $boxes <= 1 ? 1 : ($boxes+1)/2;
	$Page = '';

	$config = array("row" => 16, "header_row" => 0, "footer_row" => 0, "sub_total_row" => 0);
	$this->printer->config($config);


	$Page .= $this->printer->doc_header();
	$n = 1;
	while($total_page > 0 )
	{
		$Page .= $this->printer->page_start();

		if( $n < ($boxes+1) )
		{
			$Page .= $this->printer->content_start();
			$Page .= '<table style="width:100%; border:0px;"><tr><td style="width:50%;">';
			$Page .= $sender;
			$Page .= '</td><td style=" vertical-align:text-top; text-align:right; font-size:18px; padding-top:25px; padding-right:15px;">'.$reference.' : Box No. '.$n.' / '.$boxes.'</td></tr></table>';
			$Page .= $receiver;
			$Page .= $transport;
			$Page .= $this->printer->content_end();
			$n++;
		}
		if( $n < ($boxes+1) )
		{
			$Page .= $this->printer->content_start();
			$Page .= '<table style="width:100%; border:0px;"><tr><td style="width:50%;">';
			$Page .= $sender;
			$Page .= '</td><td style=" vertical-align:text-top; text-align:right; font-size:18px; padding-top:25px; padding-right:15px;">'.$reference.' : Box No. '.$n.' / '.$boxes.'</td></tr></table>';
			$Page .= $receiver;
			$Page .= $transport;
			$Page .= $this->printer->content_end();
			$n++;
		}
		if( $n > $boxes ){
			if( $n > $boxes && ($n % 2) == 0 )
			{
				$Page .= '
				<style>.table-bordered > tbody > tr > td { border : solid 1px #333 !important;  }</style>
				<table class="table table-bordered" >
					<tr style="font-size:10px">
						<td style="width:8%;">ใบสั่งงาน</td>
						<td style="width:25%;">
              <input type="checkbox" style="margin-left:10px; margin-right:5px;"> รับ
              <input type="checkbox" checked style="margin-left:10px; margin-right:5px;"> ส่ง
            </td>
						<td style="width:27%;">
              วันที่ '.date("d/m/Y").'
              <input type="checkbox" style="margin-left:10px; margin-right:5px;">เช้า
              <input type="checkbox" style="margin-left:10px; margin-right:5px;"> บ่าย
            </td>
						<td style="width:20%;">
              จำนวน '.$boxes.' กล่อง
            </td>
						<td style="width:20%;">
              ออเดอร์ :  '.$reference.'
            </td>
					</tr>
					<tr style="font-size:10px;">
            <td>ขนส่ง</td>
            <td>'.$sd->name.'</td>
            <td colspan="3">'.$sd->address1.' '.$sd->address2.' ('.$sd->phone.')</td>
          </tr>
					<tr style="font-size:10px;">
            <td>ผู้รับ</td>
            <td>'.$ad->name.'</td>
            <td colspan="3">'.$ad->address.' ต. '.$ad->sub_district.' อ. '.$ad->district.' จ. '.$ad->province.' '.$ad->postcode.'</td>
          </tr>
					<tr style="font-size:10px;">
            <td>ผู้ติดต่อ</td>
            <td>'.$ad->name.'</td>
            <td>Tel. '.$ad->phone.'</td>
            <td>ผู้สั่งงาน '.get_cookie('uname').'</td>
            <td>Tel. </td>
          </tr>
				</table>';
			}
			$n++;
		}
		$Page .= $this->printer->page_end();

		$total_page--;
	}
	$Page .= $this->printer->doc_footer();
	echo $Page;
