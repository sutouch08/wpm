<?php
class Agx_auto_transfer extends CI_Controller
{
  public $home;
  public $mc;
  public $ms;
	public $user;
  public $limit = 10;


  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE); //--- SAP database
    $this->mc = $this->load->database('mc', TRUE); //--- Temp Database
    $this->home = base_url().'auto/agx_auto_transfer';

		$this->load->model('inventory/transfer_model');
		$this->load->model('inventory/movement_model');
		$this->load->model('masters/products_model');
		$this->load->model('agx_logs_model');

    $this->agx_api = is_true(getConfig('AGX_API'));
  }

  public function index()
  {
    $list = array();
		$limit = 10;
		$path = $this->config->item('upload_path')."agx/TR/Confirm/";
		$file_path = $this->config->item('upload_file_path')."agx/TR/Confirm/";

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


  public function do_process($fileName)
	{
		$sc = TRUE;

		$file_path = $this->config->item('upload_file_path')."agx/TR/Confirm/".$fileName;
		$completed_path = $this->config->item('upload_file_path')."agx/TR/Completed/".$fileName;
		$error_path = $this->config->item('upload_file_path')."agx/TR/Error/".$fileName;
		$file_size = 0; //-- file size in byte;
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
				$code = NULL;
				$doc = NULL;
				$date_add = now();
				$valid = 1;

				$row = $collection[2];

				if( ! empty($row))
				{
					$date = $row['A'];
					$code = $row['B'];
					$doc = $this->transfer_model->get($code);

					if( ! empty($doc))
					{
						$date_add = getConfig('ORDER_SOLD_DATE') == 'D' ? $doc->date_add : (empty($date) ? now() : date('Y-m-d H:i:s', strtotime($date)));
					}


					if( ! empty($doc))
					{
						if($doc->status == 3)
						{
							$this->db->trans_begin();

							foreach($collection as $line)
							{
								if( ! empty($line) && $i > 1)
								{
									$from_zone = $line['C'];
									$to_zone = $line['D'];
									$item_code = $line['E'];
									$request_qty = $line['F'];
									$receive_qty = $line['G'];

									$item = $this->products_model->get_with_old_code($item_code);

									if( ! empty($item))
									{
										$detail = $this->transfer_model->get_detail_by_product_and_zone($doc->code, $item->code, $from_zone, $to_zone);

										if( ! empty($detail))
										{
											$wms_qty = $receive_qty <= $detail->qty ? $receive_qty : $detail->qty;

											$arr = array(
												'wms_qty' => $wms_qty,
												'valid' => $wms_qty == $detail->qty ? 1 : 0
											);

											if($detail->qty != $wms_qty)
											{
												$valid = 0;
											}

											if( ! $this->transfer_model->update_detail($detail->id, $arr))
											{
												$sc = FALSE;
												$this->error = "Failed to update transfer qty at line {$i} : {$item_code}";
												$error[$i] = $this->error;
											}
											else
											{
												//--- add_movement
												//--- 2. update movement
												$move_out = array(
													'reference' => $doc->code,
													'warehouse_code' => $doc->from_warehouse,
													'zone_code' => $detail->from_zone,
													'product_code' => $detail->product_code,
													'move_in' => 0,
													'move_out' => $wms_qty,
													'date_add' => $date_add
												);

												$move_in = array(
													'reference' => $doc->code,
													'warehouse_code' => $doc->to_warehouse,
													'zone_code' => $detail->to_zone,
													'product_code' => $detail->product_code,
													'move_in' => $wms_qty,
													'move_out' => 0,
													'date_add' => $date_add
												);

												//--- move out
												if(! $this->movement_model->add($move_out))
												{
													$sc = FALSE;
													$this->error = "Failed to create outgoing movement";
													$error[$i] = $this->error;
													break;
												}

												//--- move in
												if(! $this->movement_model->add($move_in))
												{
													$sc = FALSE;
													$this->error = "Failed to create incoming movement";
													$error[$i] = $this->error;
													break;
												}
											}
										}
										else
										{
											$sc = FALSE;
											$this->error = "Item not found at line {$i}";
											$error[$i] = $this->error;
										}
									}
									else
									{
										$sc = FALSE;
										$this->error = "Item not found at line {$i} : {$item_code}";
										$error[$i] = $this->error;
									}
								}

								$i++;
							} //--- end foreach

							if($sc === TRUE)
							{
								$arr = array(
									'shipped_date' => $date_add,
									'status' => 1,
									'valid' => $valid
								);

								if( ! $this->transfer_model->update($doc->code, $arr))
								{
									$sc = FALSE;
									$this->error = "Fail to update document status";
								}
							}

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
								$this->export_transfer($doc->code);
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "Invalid document status";
							$error[2] = $this->error;
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "Invalid document number";
						$error[2] = $this->error;
					}
				}
			} //-- if ! empty collection

			if($sc === TRUE)
			{
				//--- move file to completed
				if(rename($file_path, $completed_path))
				{
					$logs = array(
						'type' => 'TR',
						'code' => $doc->code,
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
				if($this->create_error_file($collection, $error_path, $error))
				{
					unlink($file_path);
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "File {$fileName} not found !";
		}

	 return $sc;
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

	 private function detectDelimiter($fh)
	 {
		 $delimiters = ["\t", ";", "|", ","];
		 $data_1 =[]; $data_2 = [];
		 $delimiter = $delimiters[0];

		 foreach($delimiters as $d) {
			 $data_1 = explode($fh, $d);
			 if(sizeof($data_1) > sizeof($data_2)) {
				 $delimiter = $d;
				 $data_2 = $data_1;
			 }
		 }

		 return $delimiter;
	 }


   private function export_transfer($code)
 	{
 		$sc = TRUE;

 		$this->load->library('export');

 		if(! $this->export->export_transfer($code))
 		{
 			$sc = FALSE;
 			$this->error = trim($this->export->error);
 		}
 		else
 		{
 			$this->transfer_model->set_export($code, 1);
 		}

 		return $sc;
 	}

} //-- end class

 ?>
