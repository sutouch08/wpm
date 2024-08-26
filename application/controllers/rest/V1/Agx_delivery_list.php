<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agx_delivery_list extends PS_Controller
{
	public $menu_code = 'AGXDOLT';
	public $menu_group_code = 'WMS';
  public $menu_sub_group_code = 'TAGXDO';
	public $title = 'AGX DO - List';
  public $filter;
	public $ch = array();
	public $uname = "api@agx";
	public $agx_api = FALSE;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'rest/V1/agx_delivery_list';

		$this->load->model('orders/orders_model');
    $this->load->model('masters/channels_model');
    $this->load->model('masters/payment_methods_model');
    $this->load->model('masters/products_model');
    $this->load->model('masters/customers_model');
    $this->load->model('orders/order_state_model');
		$this->load->model('masters/warehouse_model');
		$this->load->model('masters/sender_model');
		$this->load->model('agx_logs_model');

		$this->agx_api = is_true(getConfig('AGX_API'));
  }

  public function index()
  {
		$list = array();
		$path = $this->config->item('upload_path')."agx/DO/";
		$file_path = $this->config->item('upload_file_path')."agx/DO/";

		if($handle = opendir($path))
		{
			while(FALSE !== ($entry = readdir($handle)))
			{
				if($entry !== '.' && $entry !== '..')
				{
					$f = $file_path.$entry;

					if(is_file($f))
					{
						$file = array(
							'name' => $entry,
							'size' => ceil((filesize($f)/1024))." KB",
							'date_modify' => date('Y-m-d H:i:s', filemtime($f))
						);

						$list[] = $file;
					}
				}
			}

			closedir($handle);
		}

    $this->load->view('rest/V1/agx/do/delivery_list', ['list' => $list]);
  }


	public function upload_file()
	{
		$sc = TRUE;
		$file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
		$path = $this->config->item('upload_path').'agx/DO/';
		$file	= 'uploadFile';

		$config = array(   // initial config for upload class
			"allowed_types" => "csv|xls|xlsx",
			"upload_path" => $path,
			//"file_name"	=> "import_order",
			"max_size" => 5120,
			"overwrite" => TRUE
		);

		$this->load->library("upload", $config);

		if(! $this->upload->do_upload($file))
		{
			$sc = FALSE;
			$this->error = $this->upload->display_errors();
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}


	function auto_process()
	{
		$sc = TRUE;

		$list = array();
    $limit = 10;
		$path = $this->config->item('upload_path')."agx/DO/";
		$file_path = $this->config->item('upload_file_path')."agx/DO/";

		if($handle = opendir($path))
		{
      $i = 1;
			while(FALSE !== ($entry = readdir($handle)) && $i <= $limit)
			{
				if($entry !== '.' && $entry !== '..')
				{
					$f = $file_path.$entry;

					if(is_file($f))
					{
						$file = new stdClass();
            $file->fileName = $entry;

						$list[] = $file;
            $i++;
					}
				}
			}

			closedir($handle);
		}		

    if( ! empty($list))
    {
      foreach($list as $rs)
      {
				$this->do_process($rs->fileName);
      }
    }
	}


	function process()
	{
		$sc = TRUE;

		$list = array();
    $limit = 10;
		$path = $this->config->item('upload_path')."agx/DO/";
		$file_path = $this->config->item('upload_file_path')."agx/DO/";

		if($handle = opendir($path))
		{
      $i = 1;
			while(FALSE !== ($entry = readdir($handle)) && $i <= $limit)
			{
				if($entry !== '.' && $entry !== '..')
				{
					$f = $file_path.$entry;

					if(is_file($f))
					{
						$file = new stdClass();
            $file->fileName = $entry;

						$list[] = $file;
            $i++;
					}
				}
			}

			closedir($handle);
		}

		$c = 0;

    if( ! empty($list))
    {
			$message = "";
			$files = 0;
			$ok = 0;
			$failed = 0;
      foreach($list as $rs)
      {
        if($this->do_process($rs->fileName))
				{
					$ok++;
				}
				else
				{
					$sc = FALSE;
					$message .= "Failed : {$rs->fileName}<br/>";
					$failed++;
				}
				$files++;
      }

			$this->error = "Process : {$files} files<br/>";
			$this->error .= "Success : {$ok} files<br/>";
			$this->error .= "Failed : {$failed} files<br/>";
			$this->error .= "------------------------------<br/>";
			$this->error .= $message;
    }

		echo $sc === TRUE ? 'success' : $this->error;
	}


	public function process_file()
	{
		$sc = TRUE;
		$fileName = empty($fileName) ? $this->input->post('fileName') : $fileName;

		if( ! $this->do_process($fileName))
		{
			$sc = FALSE;
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}

	public function do_process($fileName)
	{
		$sc = TRUE;

		$fileName = $fileName;
		$file_path = $this->config->item('upload_file_path')."agx/DO/".$fileName;
		$completed_path = $this->config->item('upload_file_path')."agx/DO/Completed/".$fileName;
		$error_path = $this->config->item('upload_file_path')."agx/DO/Error/".$fileName;
		$file_size = 0; //-- file size in byte;
		$uname = $this->uname;

		if(file_exists($file_path))
		{
			$file_size = filesize($file_path);
			$this->load->library('excel');
			$excel = PHPExcel_IOFactory::load($file_path);
			$collection = $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			if( ! empty($collection))
			{
				$i = 1;

				$bookcode = getConfig('BOOK_CODE_ORDER');
				$role = 'S';
				$DocCur = getConfig('CURRENCY');
				$DocRate = 1.0;

				$orderCode = NULL;
				$ref_code = NULL;
				$order_code = NULL;
				$date_add = NULL;
				$channels = NULL;
				$payment = NULL;
				$dfCustomer = $this->customers_model->get(getConfig('DEFAULT_CUSTOMER'));
				$dfChannels = getConfig('AGX_CHANNELS');
				$dfPayment = getConfig('AGX_PAYMENT');
				$whs = $this->warehouse_model->get(getConfig('AGX_WAREHOUSE'));
				$zoneCode = getConfig('AGX_ZONE');
				$id_sender = $this->sender_model->get_id('CA001');
				$remark = NULL;
				$is_exists = FALSE;

				$csv = array();

				$this->db->trans_begin();

				foreach($collection as $rs)
				{
					$csv[$i] = $rs;

					if($i == 1)
					{
						$csv[$i]['L'] = "Error";
					}
					else
					{
						$csv[$i]['L'] = "";
					}

					if( ! empty($rs) && $i != 1)
					{
						if( ! empty($rs['D']) && ! empty($rs['E']) && ! empty($rs['G']))
						{
							//--- check ref_code
							if($ref_code != $rs['D'])
							{
								//--- check ref_code exists
	              $date = PHPExcel_Style_NumberFormat::toFormattedString($rs['E'], 'YYYY-MM-DD');
                $date = date('Y-m-d', strtotime($date));
                $Y = date('Y', strtotime($date));
	              $date_add = $Y == 1970 ? now() : db_date($date, TRUE);

	              //---- order code from web site
	              $ref_code = $rs['D'];

	              //--- shipping Number
	              $shipping_code = get_null($rs['K']);

	              //---- กำหนดช่องทางการขายเป็นรหัส
	              $ch = trim($rs['F']);

								$ch = ($ch == 'LAZADA SPORTSAVER' OR $ch == 'LAZADA') ? '002' : (($ch == 'SHOPEE SPORTSAVER' OR $ch == 'SHOPEE') ? '001' : $dfChannels);

								$channels = $this->channels_model->get($ch);

								if(empty($channels))
								{
									$channels = $this->channels_model->get_default();
								}

	              //--- กำหนดช่องทางการชำระเงิน
	              $payment = $this->payment_methods_model->get($dfPayment);

								if(empty($payment))
								{
									$payment = $this->payment_methods_model->get_default();
								}

								$order_code  = $this->orders_model->get_order_code_by_reference($ref_code);

	              $is_exists = empty($order_code) ? FALSE : TRUE;
							}
							else
							{
								$is_exists = TRUE;
							}

							//------ เช็คว่ามีออเดอร์นี้อยู่ในฐานข้อมูลแล้วหรือยัง
              //------ ถ้ามีแล้วจะได้ order_code กลับมา ถ้ายังจะได้ FALSE;

              if(empty($order_code) OR ($order_code != $orderCode))
              {
								if(empty($order_code))
								{
									$order_code = $this->get_new_code($date_add);
								}
              }

							//-- state ของออเดอร์ จะมีการเปลี่ยนแปลงอีกที
              $state = 3;

							//---- ถ้ายังไม่มีออเดอร์ ให้เพิ่มใหม่ หรือ มีออเดอร์แล้ว แต่ต้องการ update
              //---- โดยการใส่ force update มาเป็น 1
              if($is_exists === FALSE)
              {
                //---- รหัสลูกค้าจะมีการเปลี่ยนแปลงตามเงื่อนไขด้านล่างนี้
                $customer_code = $channels->customer_code;
                //---- ตรวจสอบว่าช่องทางขายที่กำหนดมา เป็นเว็บไซต์หรือไม่(เพราะจะมีช่องทางการชำระเงินหลายช่องทาง)

                $customer = $this->customers_model->get($customer_code);

								if(empty($customer))
								{
									$customer_code = $dfCustomer->code;
									$customer = $dfCustomer;
								}

								//---	หากเป็นออนไลน์ ลูกค้าออนไลน์ชื่ออะไร
              	$customer_ref = addslashes(trim($rs['A']));

              	//---	ถ้าเป็นออเดอร์ขาย จะมี id_sale
              	$sale_code = $customer->sale_code;

                //---	ช่องทางการชำระเงิน
                $payment_code = $payment->code;

                //---	ช่องทางการขาย
                $channels_code = $channels->code;

              	// //---	วันที่เอกสาร

                //--- ค่าจัดส่ง
                $shipping_fee = 0.00;

                //--- ค่าบริการอื่นๆ
                $service_fee = 0; //empty($rs['R']) ? 0.00 : $rs['R'];

                //---- กรณียังไม่มีออเดอร์
                if($is_exists === FALSE)
                {
                  //--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
                  $ds = array(
                    'code' => $order_code,
                    'role' => $role,
                    'bookcode' => $bookcode,
                    'DocCur' => $DocCur,
                    'DocRate' => $DocRate,
                    'reference' => $ref_code,
                    'customer_code' => $customer_code,
                    'customer_ref' => $customer_ref,
                    'channels_code' => $channels_code,
                    'payment_code' => $payment_code,
                    'sale_code' => $sale_code,
                    'state' => $state,
                    'is_paid' => 0,
                    'is_term' => $payment->has_term,
                    'shipping_code' => $shipping_code,
                    'shipping_fee' => 0,
                    'status' => 1,
                    'date_add' => $date_add,
                    'warehouse_code' => $whs->code,
                    'user' => $uname,
                    'is_import' => 1,
										'remark' => $remark,
										'is_wms' => 0,
										'id_sender' => get_null($id_sender)
                  );

                  //--- เพิ่มเอกสาร
                  if($this->orders_model->add($ds) === TRUE)
                  {
										$orderCode = $order_code;

                    $arr = array(
                      'order_code' => $order_code,
                      'state' => $state,
                      'update_user' => $uname
                    );

                    //--- add state event
                    $this->order_state_model->add_state($arr);
                  }
                  else
                  {
                    $sc = FALSE;
                    $this->error = $ref_code.': Add order failed.';
										$csv[$i]['L'] = $this->error;
                  }
                }
              } //--- if $exists == FALSE
						} //---- skip no data row

						//---- เตรียมข้อมูลสำหรับเพิมรายละเอียดออเดอร์
						$item = ! empty($rs['G']) ? $this->products_model->get_with_old_code(trim($rs['G'])) : NULL;

						if(empty($item))
						{
							$sc = FALSE;
							$this->error = 'Product code not found : '.$rs['G'];
							$csv[$i]['L'] = $this->error;
						}
						else if($item->active != 1)
						{
							$sc = FALSE;
							$this->error = 'Product Inactive : '.$rs['G'];
							$csv[$i]['L'] = $this->error;
						}

						$qty = empty($rs['H']) ? 1 : str_replace(',', '', $rs['H']);

						//--- ราคา (เอาราคาที่ใส่มา / จำนวน + ส่วนลดต่อชิ้น)
						$price = empty($rs['I']) ? 0.00 : str_replace(",", "", $rs['I']); //--- ราคารวมไม่หักส่วนลด
						$price = $price > 0 ? ($price/$qty) : 0; //--- ราคาต่อชิ้น



						//--- ส่วนลด (รวม)
						$discount_amount = 0.00;

						//--- ส่วนลด (ต่อชิ้น)
						$discount = $discount_amount > 0 ? ($discount_amount / $qty) : 0;



						//--- total_amount
						$total_amount = ($price * $qty) - $discount_amount;

						//---- เช็คข้อมูล ว่ามีรายละเอียดนี้อยู่ในออเดอร์แล้วหรือยัง
						//---- ถ้ามีข้อมูลอยู่แล้ว (TRUE)ให้ข้ามการนำเข้ารายการนี้ไป
						if( ! empty($item) && $this->orders_model->is_exists_detail($order_code, $item->code) === FALSE)
						{
							//--- ถ้ายังไม่มีรายการอยู่ เพิ่มใหม่
							$arr = array(
								"order_code"	=> $order_code,
								"style_code"		=> $item->style_code,
								"product_code"	=> $item->code,
								"product_name"	=> $item->name,
								"currency" => $DocCur,
								"rate" => $DocRate,
								"cost"  => $item->cost,
								"price"	=> $price,
								"qty"		=> $qty,
								"discount1"	=> $discount,
								"discount2" => 0,
								"discount3" => 0,
								"discount_amount" => $discount_amount,
								"total_amount"	=> round($total_amount,2),
								"totalFrgn" => round($total_amount, 2),
								"id_rule"	=> NULL,
								"is_count" => $item->count_stock,
								"is_import" => 1
							);

							if( $this->orders_model->add_detail($arr) === FALSE )
							{
								$sc = FALSE;
								$this->error = 'Add items failed : '.$ref_code;
								$csv[$i]['L'] = $this->error;
							}
						} //--- end if exists detail

						$orderCode = $order_code;

					} //-- if ( $i != 1)

					$i++;
				} //--- end foreach


				if($sc === TRUE)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
				}

				if($sc === TRUE)
				{
					//--- move file to completed
					if(rename($file_path, $completed_path))
					{
						$logs = array(
							'type' => 'DO',
							'code' => $order_code,
							'file_name' => $fileName,
							'file_path' => $completed_path,
							'file_size' => $file_size,
							'user' => $this->uname
						);

						$this->agx_logs_model->add($logs);
					}
				}
				else
				{
					if($this->create_error_file($csv, $error_path))
					{
						unlink($file_path);
					}
				}
			} //-- if ! empty collection
		}
		else
		{
			$sc = FALSE;
			$this->error = "File {$fileName} not found !";
		}

	 return $sc;
	}


	 public function get_detail()
	 {
		 $sc = TRUE;
		 $ds = array();

		 $fileName = $this->input->post('fileName');
 		 $file_path = $this->config->item('upload_file_path')."agx/DO/".$fileName;
		 $code = "DO / ".$fileName;

		 if(file_exists($file_path))
		 {
			 $this->load->library('excel');
			 $excel = PHPExcel_IOFactory::load($file_path);
			 $collection = $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			 if( ! empty($collection))
			 {
				 $i = 0;

				 foreach($collection as $line)
				 {
					 if($i > 0)
					 {
						 $arr = array(
							 'consignee' => $line['A'],
							 'address' => $line['B'],
							 'tel' => $line['C'],
							 'order_number' => $line['D'],
							 'date' => $line['E'],
							 'channels' => $line['F'],
							 'itemId' => $line['G'],
							 'qty' => $line['H'],
							 'price' => $line['I'],
							 'courier' => $line['J'],
							 'shipping_code' => $line['K']
						 );

						 array_push($ds, $arr);
					 }

					 $i++;
				 }
			 }
		 }
		 else
		 {
			 $sc = FALSE;
			 $this->error = "File not found !";
		 }

		 $arr = array(
			 'status' => $sc === TRUE ? 'success' : 'failed',
			 'message' => $sc === TRUE ? 'success' : $this->error,
			 'data' => $sc === TRUE ? $ds : $this->error,
			 'code' => $code
		 );

		 echo json_encode($arr);
	 }


	 public function create_error_file(array $ds = array(), $error_file_path)
	 {
		 if( ! empty($ds))
		 {
			 // Create a file pointer
			 $f = fopen($error_file_path, 'w');

			 if($f !== FALSE)
			 {
				 $delimiter = ",";
				 fputs($f, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

				 foreach($ds as $line)
				 {
					 fputcsv($f, $line, $delimiter);
				 }

				 fclose($f);

				 return TRUE;
			 }
		 }

		 return FALSE;
	 }


	 public function get_new_code($date)
   {
     $date = $date == '' ? date('Y-m-d') : $date;
     $Y = date('y', strtotime($date));
     $M = date('m', strtotime($date));
     $prefix = getConfig('PREFIX_ORDER');
     $run_digit = getConfig('RUN_DIGIT_ORDER');
     $pre = $prefix .'-'.$Y.$M;
     $code = $this->orders_model->get_max_code($pre);

     if(! is_null($code))
     {
       $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
       $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
     }
     else
     {
       $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
     }

     return $new_code;
   }


	public function delete()
	{
		$sc = TRUE;
		$fileName = $this->input->post('fileName');
		$file_path = $this->config->item('upload_file_path')."agx/DO/".$fileName;

		if(file_exists($file_path))
		{
			if( ! unlink($file_path))
			{
				$sc = FALSE;
				$this->error = "Failed to delete file";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "File not found !";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}

} //--- end classs
?>
