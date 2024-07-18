<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agx
{
  protected $ci;
  public $error;

  public function __construct()
	{
    // Assign the CodeIgniter super-object
    $this->ci =& get_instance();
	}

  public function export_transfer_request($code)
  {
    $sc = TRUE;
    $this->ci->load->model('inventory/transfer_model');

    $doc = $this->ci->transfer_model->get($code);

    if( ! empty($doc))
    {
      if($doc->status == 1 OR $doc->status == 3)
      {
        $details = $this->ci->transfer_model->get_details($code);

        if( ! empty($details))
        {
          $request_file = $this->ci->config->item('upload_file_path')."agx/TR/Request/{$doc->code}.xls";
          $confirm_file = $this->ci->config->item('upload_file_path')."agx/TR/Confirm/{$doc->code}.xls";
          $complete_file = $this->ci->config->item('upload_file_path')."agx/TR/Completed/{$doc->code}.xls";

          if( ! file_exists($complete_file) && ! file_exists($confirm_file))
          {
            if( file_exists($request_file))
            {
              if( ! unlink($request_file))
              {
                $sc = FALSE;
                $this->error = "Cannot delete prevoius file";
              }
            }

            if($sc === TRUE)
            {
              $this->ci->load->library('excel');
              $this->ci->excel->setActiveSheetIndex(0);
              $excel = $this->ci->excel->getActiveSheet();
              $excel->setTitle($doc->code);

              $row = 1;
              $excel->setCellValue("A{$row}", 'date');
              $excel->setCellValue("B{$row}", 'code');
              $excel->setCellValue("C{$row}", 'from_location');
              $excel->setCellValue("D{$row}", 'to_location');
              $excel->setCellValue("E{$row}", 'item_code');
              $excel->setCellValue("F{$row}", 'request_qty');
              $excel->setCellValue("G{$row}", 'transfer_qty');

              $row++;

              if( ! empty($details))
              {
                foreach($details as $rs)
                {
                  $excel->setCellValue("A{$row}", now());
                  $excel->setCellValue("B{$row}", $rs->transfer_code);
                  $excel->setCellValue("C{$row}", $rs->from_zone);
                  $excel->setCellValue("D{$row}", $rs->to_zone);
                  $excel->setCellValue("E{$row}", $rs->product_code);
                  $excel->setCellValue("F{$row}", $rs->qty);
                  $excel->setCellValue("G{$row}", '');

                  $row++;
                }
              }

              $file_name = "{$doc->code}.xls";
              $writer = PHPExcel_IOFactory::createWriter($this->ci->excel, 'Excel5');
              $writer->save($request_file);
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "This document already processed";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "No item found";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid document status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Invalid document number";
    }

    return $sc;
  }

} //--- end class
