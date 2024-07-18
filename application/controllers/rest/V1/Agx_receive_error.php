<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agx_receive_error extends PS_Controller
{
	public $menu_code = 'AGXGRER';
	public $menu_group_code = 'WMS';
  public $menu_sub_group_code = 'TAGXGR';
	public $title = 'AGX GR - Error';
  public $filter;
	public $ch = array();
	public $uname = "api@agx";

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'rest/V1/agx_receive_error';

		$this->load->model('inventory/receive_po_model');
		$this->load->model('inventory/movement_model');
    $this->load->model('masters/products_model');
		$this->load->model('masters/warehouse_model');
		$this->load->model('masters/zone_model');
		$this->load->model('agx_logs_model');
  }

  public function index()
  {
		$list = array();
		$path = $this->config->item('upload_path')."agx/GR/Error/";
		$file_path = $this->config->item('upload_file_path')."agx/GR/Error/";

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

    $this->load->view('rest/V1/agx/gr/receive_error_list', ['list' => $list]);
  }


	public function process_file()
	{
		$sc = TRUE;

		$fileName = $this->input->post('fileName');
		$file_path = $this->config->item('upload_file_path')."agx/GR/Error/".$fileName;
		$completed_path = $this->config->item('upload_file_path')."agx/GR/Completed/".$fileName;
		$error_path = $this->config->item('upload_file_path')."agx/GR/Error/".$fileName;
		$file_size = 0; //-- file size in byte;
		$uname = $this->uname;
		$error = array('1' => "Error");

		if(file_exists($file_path))
		{
			$file_size = filesize($file_path);
			$this->load->library('excel');
			$excel = PHPExcel_IOFactory::load($file_path);
			$collection = $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			if( ! empty($collection) && count($collection) > 1)
			{
				$i = 1;

				$row = $collection[2];

				if( ! empty($row))
				{
					$dfZone = getConfig('AGX_ZONE');
					$dfWhs = getConfig('AGX_WAREHOUSE');

					$ref_code = $row['A'];
					$po_code = $row['C'];
					$invoice_code = $row['D'];
					$zone_code = empty($row['E']) ? $dfZone : $row['E'];
					$zone = $this->zone_model->get($zone_code);
					$doc_date = db_date($row['F'], FALSE);
					$date = PHPExcel_Style_NumberFormat::toFormattedString($row['F'], 'YYYY-MM-DD');
					$date = date('Y-m-d', strtotime($date));
					$thisYear = date('Y');
					$Y = date('Y', strtotime($date));

					$date_add = ($Y < ($thisYear - 1) OR $Y > ($thisYear + 1)) ? now() : db_date($date, TRUE);

					if( ! $this->receive_po_model->is_exists_reference($ref_code))
					{
						if(! empty($po_code))
						{
							$po = $this->receive_po_model->get_po($po_code);

							if( ! empty($po))
							{
								if($po->CANCELED == 'N' && $po->DocStatus == 'O')
								{
									$code = $this->get_new_code($date_add);

									$ds = array(
										"code" => $code,
										"bookcode" => getConfig('BOOK_CODE_RECEIVE_PO'),
										"DocCur" => $po->DocCur,
										"DocRate" => $po->DocRate,
										"vendor_code" => $po->CardCode,
										"vendor_name" => $po->CardName,
										"po_code" => $po_code,
										"invoice_code" => $invoice_code,
										"zone_code" => empty($zone) ? $dfZone : $zone->code,
										"warehouse_code" => empty($zone) ? $dfWhs : $zone->warehouse_code,
										"remark" => "PO {$po_code} Ref. {$invoice_code}",
										"date_add" => $date_add,
										"user" => $this->uname,
										"shipped_date" => db_date($date_add, TRUE),
										"status" => 1,
										"reference" => $ref_code,
										"is_api" => 1
									);

									$this->db->trans_begin();

									if( ! $this->receive_po_model->add($ds))
						      {
						        $sc = FALSE;
						        $this->error = "Create Document Failed";
										$error[2] = $this->error;
						      }
									else
									{
										foreach($collection as $rs)
										{
											if($i > 1 && ! empty($rs['G']))
											{
												$pd = $this->products_model->get(trim($rs['G']));

												if( ! empty($pd))
												{
													$detail = $this->receive_po_model->get_po_detail($po_code, $pd->code);

													if( ! empty($detail))
													{
														$qty = $rs['I'];
														$price = $detail->price;
														$amount = $qty * $detail->price;
														$balance = $detail->OpenQty - $qty;

														$arr = array(
										          'receive_code' => $code,
					                    'baseEntry' => $detail->DocEntry,
					                    'baseLine' => $detail->LineNum,
															'style_code' => $pd->style_code,
															'product_code' => $detail->ItemCode,
															'product_name' => $detail->Dscription,
															'price' => $price,
															'qty' => $qty,
															'receive_qty' => $qty,
															'amount' => $amount,
															'totalFrgn' => $amount,
															'before_backlogs' => $detail->OpenQty,
															'after_backlogs' => $balance < 0 ? 0 : $balance,
															'currency' => $detail->Currency,
															'rate' => $detail->Rate,
															'vatGroup' => $detail->VatGroup,
															'vatRate' => $detail->VatPrcnt
										        );

														if( ! $this->receive_po_model->add_detail($arr))
														{
															$sc = FALSE;
															$this->error = "Failed to insert row";
															$error[$i] = $this->error;
														}
														else
														{
															//--- insert Movement in
						                  $arr = array(
						                    'reference' => $code,
						                    'warehouse_code' => empty($zone) ? $dfWhs : $zone->warehouse_code,
						                    'zone_code' => empty($zone) ? $dfZone : $zone->code,
						                    'product_code' => $detail->ItemCode,
						                    'move_in' => $qty,
						                    'move_out' => 0,
						                    'date_add' => $date_add
						                  );

						                  if( ! $this->movement_model->add($arr))
			                        {
			                          $sc = FALSE;
			                          $this->error = "Insert Movement Failed";
																$error[$i] = $this->error;
			                        }
														}
													}
													else
													{
														$sc = FALSE;
														$this->error = "PO item not exists Or already closed.";
														$error[$i] = $this->error;
													}
												}
												else
												{
													$sc = FALSE;
													$this->error = "Product code not found : {$rs['G']}";
													$error[$i] = $this->error;
												}
											}

											$i++;
										} //--- end foreach collection
									} //-- end if receive_po_model->add();

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
										$this->load->library('export');
										$this->export->export_receive($code);
									}
								}
								else
								{
									$sc = FALSE;
									$this->error = "PO already Cancelled or Closed";
									$error[2] = $this->error;
								}
							}
							else
							{
								$sc = FALSE;
								$this->error = "Invalid PO NO.";
								$error[2] = $this->error;
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "Missing PO NO.";
							$error[2] = $this->error;
						}
					} //--- if exists ref_code

				} //-- if ! empty $row (second row)

				if($sc === TRUE)
				{
					//--- move file to completed
					if(rename($file_path, $completed_path))
					{
						$logs = array(
							'type' => 'GR',
							'code' => $code,
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
					$this->create_error_file($collection, $error_path, $error);
				}
			} //-- if ! empty collection
		}
		else
		{
			$sc = FALSE;
			$this->error = "File {$fileName} not found !";
		}

	 echo $sc === TRUE ? 'success' : $this->error;
	}


	public function move_to_pending()
	{
		$sc = TRUE;
		$fileName = $this->input->post('fileName');
		$file_path = $this->config->item('upload_file_path')."agx/GR/Error/".$fileName;
		$target_file = $this->config->item('upload_file_path')."agx/GR/".$fileName;

		if(file_exists($file_path))
		{
			$this->load->library('excel');
			$excel = PHPExcel_IOFactory::load($file_path);
			$collection = $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			if( ! empty($collection))
			{
				// Create a file pointer
				$f = fopen($target_file, 'w');

				if($f !== FALSE)
				{
					$delimiter = ",";

					fputs($f, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

					foreach($collection as $line)
					{
						unset($line['L']);

						fputcsv($f, $line, $delimiter);
					}

					fclose($f);

					unlink($file_path);
				}
				else
				{
					$sc = FALSE;
					$this->error = "Failed to create file : {$fileName}";
				}
			}
			else
			{
				if( ! rename($file_path, $target_file))
				{
					$sc = FALSE;
					$this->error = "Failed to move file";
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "File not found !";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}


	public function get_detail()
	{
		$sc = TRUE;
		$ds = array();

		$fileName = $this->input->post('fileName');
		$file_path = $this->config->item('upload_file_path')."agx/GR/Error/".$fileName;
		$code = "GR / ".$fileName;

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
							'no' => $i,
							'ref_code' => $line['A'],
							'vendor_code' => $line['B'],
							'po_no' => $line['C'],
							'invoice_no' => $line['D'],
							'location' => $line['E'],
							'doc_date' => $line['F'],
							'sku' => $line['G'],
							'price' => $line['H'],
							'qty' => $line['I'],
							'amount' => $line['J'],
							'currency' => $line['K'],
							'error' => empty($line['L']) ? "" : $line['L']
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
			'data' => $sc === TRUE ? $ds : NULL,
			'code' => $code
			);

			echo json_encode($arr);
		}


	 public function get_file()
	 {
		 $fileName = $this->input->post('fileName');
 		 $file_path = $this->config->item('upload_file_path')."agx/GR/Error/".$fileName;
		 $file = $this->config->item('upload_path')."agx/GR/Error/".$fileName;

		 header('Content-Type: application/csv');
		 header("Content-Disposition: attachment; filename={$fileName}");
		 readfile($file);
	 }


	 public function create_error_file(array $ds = array(), $error_file_path, $error)
	 {
		 if( ! empty($ds))
		 {
			 // Create a file pointer
			 $f = fopen($error_file_path, 'w');

			 if($f !== FALSE)
			 {
				 $delimiter = ",";
				 fputs($f, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

				 $i = 1;

				 foreach($ds as $line)
				 {
					 if( ! empty($error[$i]))
					 {
						 $line[] = $error[$i];
					 }

					 fputcsv($f, $line, $delimiter);

					 $i++;
				 }

				 fclose($f);

				 return TRUE;
			 }
		 }

		 return FALSE;
	 }



	public function delete()
	{
		$sc = TRUE;
		$fileName = $this->input->post('fileName');
		$file_path = $this->config->item('upload_file_path')."agx/GR/Error/".$fileName;

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

} //--- end classs
?>
