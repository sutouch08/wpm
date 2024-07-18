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
          $request_file = $this->ci->config->item('upload_file_path')."agx/TR/Request/{$doc->code}.csv";
          $confirm_file = $this->ci->config->item('upload_file_path')."agx/TR/Confirm/{$doc->code}.csv";
          $complete_file = $this->ci->config->item('upload_file_path')."agx/TR/Completed/{$doc->code}.csv";

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
              $header = array('date', 'code', 'from_location', 'to_location', 'item_code', 'request_qty', 'transfer_qty');

              // Create a file pointer
              $f = fopen($request_file, 'w');
              $delimiter = ",";
              fputs($f, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
              fputcsv($f, $header, $delimiter);

              if( ! empty($details))
              {
                foreach($details as $rs)
                {
                  $row = array(
                    now(),
                    $rs->transfer_code,
                    $rs->from_zone,
                    $rs->to_zone,
                    $rs->product_code,
                    $rs->qty
                  );

                  fputcsv($f, $row, $delimiter);
                }
              }

              fclose($f);
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
