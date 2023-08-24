<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order_details extends PS_Controller
{
  public $menu_code = 'RAODDT';
	public $menu_group_code = 'RE';
  public $menu_sub_group_code = 'REAUDIT';
	public $title = 'Detailed document status report';
  public $filter;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'report/audit/order_details';
    $this->load->model('report/audit/order_details_model');
    $this->load->model('masters/products_model');
    $this->load->model('orders/orders_model');
    $this->load->model('masters/channels_model');
    $this->load->model('masters/payment_methods_model');
    $this->load->model('masters/warehouse_model');
  }

  public function index()
  {
    $ds = array(
      'channels_list' => $this->channels_model->get_data(),
      'payment_list' => $this->payment_methods_model->get_data(),
      'warehouse_list' => $this->warehouse_model->get_sell_warehouse_list()
    );

    $this->load->view('report/audit/report_order_details', $ds);
  }



  public function get_report()
  {
    $sc = TRUE;

    if($this->input->get("json"))
    {
      $json = json_decode($this->input->get("json"));

      if( ! empty($json))
      {
        $filter = array(
          "from_date" => $json->fromDate,
          "to_date" => $json->toDate,
          "all_role" => $json->allRole,
          "role" => $json->role,
          "is_expired" => $json->isExpired,
          "all_state" => $json->allState,
          "state" => $json->state,
          "all_channels" => $json->allChannels,
          "channels" => $json->channels,
          "all_payment" => $json->allPayments,
          "payment" => $json->payment,
          "all_warehouse" => $json->allWarehouse,
          "warehouse" => $json->warehouse
        );

        $limit = 1000;
        $rows = $this->order_details_model->count_rows($filter);
        $details = $this->order_details_model->get_data($filter, $limit);
        $ds = array();

        if( ! empty($details))
        {
          $no = 1;
          foreach($details as $rs)
          {
            $rs->no = number($no);
            $rs->expired = $rs->is_expired == 1 ? 'Y' : 'N';
            $rs->date_add = thai_date($rs->date_add, FALSE, '/');
            $rs->total_amount = number($this->order_details_model->get_doc_total($rs->code), 2);
            $no++;
          }
        }

        $ds = array(
          "limit" => $limit,
          "rows" => $rows,
          "details" => $details
        );
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid request data format";
      }
    }

    echo $sc === TRUE ? json_encode($ds) : $this->error;
  }



  public function do_export()
  {
    $token = $this->input->post('token');

    //--- load excel library
    $this->load->library('excel');

    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Order details Report');

    if($this->input->post('fromDate'))
    {
      $allRole = $this->input->post('allRole');
      $allWarehouse = $this->input->post('allWarehouse');
      $allChannels = $this->input->post('allChannels');
      $allPayments = $this->input->post('allPayments');
      $allState = $this->input->post('allState');
      $is_expired = $this->input->post('is_expired');
      $fromDate = $this->input->post('fromDate');
      $toDate = $this->input->post('toDate');
      $role = $this->input->post('role');
      $channels = $this->input->post('channels');
      $payments = $this->input->post('payments');
      $warehouse = $this->input->post('warehouse');
      $state = $this->input->post('state');

      $filter = array(
        "from_date" => $fromDate,
        "to_date" => $toDate,
        "all_role" => $allRole,
        "role" => $role,
        "is_expired" => $is_expired,
        "all_state" => $allState,
        "state" => $state,
        "all_channels" => $allChannels,
        "channels" => $channels,
        "all_payment" => $allPayments,
        "payment" => $payments,
        "all_warehouse" => $allWarehouse,
        "warehouse" => $warehouse
      );

      $details = $this->order_details_model->get_data($filter);

      //---  Report title
      $report_title = "Detailed document status report";
      $role_title = "Document : ". ($allRole == 1 ? 'All' : $this->get_role_title($role));
      $expire_title = "Document status : ".($is_expired == 'all' ? 'All' : ($is_expired == 1 ? 'Only expired' : 'Only not expired'));
      $wh_title     = 'Warehouse :  '. ($allWarehouse == 1 ? 'All' : $this->get_title($warehouse));
      $ch_title = "Sales Channels : ". ($allChannels == 1 ? 'All' : $this->get_title($channels));
      $pay_title = "Payments : ". ($allPayments == 1 ? "All" : $this->get_title($payments));
      $state_title = "Status : ".($allState == 1 ? "All" : $this->get_state_title($state));

      //--- set report title header
      $this->excel->getActiveSheet()->setCellValue('A1', $report_title);
      $this->excel->getActiveSheet()->setCellValue('A2', $role_title);
      $this->excel->getActiveSheet()->setCellValue('A3', $expire_title);
      $this->excel->getActiveSheet()->setCellValue('A4', $state_title);
      $this->excel->getActiveSheet()->setCellValue('A5', $ch_title);
      $this->excel->getActiveSheet()->setCellValue('A6', $pay_title);
      $this->excel->getActiveSheet()->setCellValue('A7', $wh_title);
      $this->excel->getActiveSheet()->setCellValue('A8', 'Document Date : ('.thai_date($fromDate,'/') .') - ('.thai_date($toDate,'/').')');

      //--- set Table header
      $row = 9;

      $this->excel->getActiveSheet()->setCellValue("A{$row}", 'No');
      $this->excel->getActiveSheet()->setCellValue("B{$row}", 'Date');
      $this->excel->getActiveSheet()->setCellValue("C{$row}", 'Document No');
      $this->excel->getActiveSheet()->setCellValue("D{$row}", 'Amount');
      $this->excel->getActiveSheet()->setCellValue("E{$row}", 'Customer Code');
      $this->excel->getActiveSheet()->setCellValue("F{$row}", 'Customer Name');
      $this->excel->getActiveSheet()->setCellValue("G{$row}", 'Status');
      $this->excel->getActiveSheet()->setCellValue("H{$row}", 'Expired');
      $this->excel->getActiveSheet()->setCellValue("I{$row}", 'Sales Channels');
      $this->excel->getActiveSheet()->setCellValue("J{$row}", 'Payment Channels');
      $this->excel->getActiveSheet()->setCellValue("K{$row}", 'Warehouse Code');
      $this->excel->getActiveSheet()->setCellValue("L{$row}", 'Warehouse Name');
      $this->excel->getActiveSheet()->setCellValue("M{$row}", 'Username');
      $this->excel->getActiveSheet()->setCellValue("N{$row}", 'Employee');

      $row++;

      //---- กำหนดความกว้างของคอลัมภ์
      $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
      $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
      $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
      $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(90);
      $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
      $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
      $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
      $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
      $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
      $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
      $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);

      if( ! empty($details))
      {
        $no = 1;
        foreach($details as $rs)
        {
          $y		= date('Y', strtotime($rs->date_add));
          $m		= date('m', strtotime($rs->date_add));
          $d		= date('d', strtotime($rs->date_add));
          $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

          $amount = $this->order_details_model->get_doc_total($rs->code);

          $this->excel->getActiveSheet()->setCellValue("A{$row}", $no);
          $this->excel->getActiveSheet()->setCellValue("B{$row}", $date);
          $this->excel->getActiveSheet()->setCellValue("C{$row}", $rs->code);
          $this->excel->getActiveSheet()->setCellValue("D{$row}", $amount);
          $this->excel->getActiveSheet()->setCellValue("E{$row}", $rs->customer_code);
          $this->excel->getActiveSheet()->setCellValue("F{$row}", $rs->customer_name);
          $this->excel->getActiveSheet()->setCellValue("G{$row}", $rs->state_name);
          $this->excel->getActiveSheet()->setCellValue("H{$row}", $rs->is_expired == 1 ? 'Y' : 'N');
          $this->excel->getActiveSheet()->setCellValue("I{$row}", $rs->channels_name);
          $this->excel->getActiveSheet()->setCellValue("J{$row}", $rs->payment_name);
          $this->excel->getActiveSheet()->setCellValue("K{$row}", $rs->warehouse_code);
          $this->excel->getActiveSheet()->setCellValue("L{$row}", $rs->warehouse_name);
          $this->excel->getActiveSheet()->setCellValue("M{$row}", $rs->uname);
          $this->excel->getActiveSheet()->setCellValue("N{$row}", $rs->emp_name);

          $no++;
          $row++;
        }

        $this->excel->getActiveSheet()->getStyle("B10:B{$row}")->getNumberFormat()->setFormatCode('dd/mm/yyyy');
        $this->excel->getActiveSheet()->getStyle("D10:D{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
      }
    }

    setToken($token);
    $file_name = "Report Order Details.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
    header('Content-Disposition: attachment;filename="'.$file_name.'"');
    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    $writer->save('php://output');

  }


  public function get_title($ds = array())
  {
    $list = "";
    if(!empty($ds))
    {
      $i = 1;
      foreach($ds as $rs)
      {
        $list .= $i === 1 ? $rs : ", {$rs}";
        $i++;
      }
    }

    return $list;
  }


  private function get_role_title($ds = array())
  {
    $list = "";

    $roles = array(
      'S' => 'WO',
      'C' => 'WC',
      'N' => 'WT',
      'P' => 'WS',
      'U' => 'WU',
      'T' => 'WQ',
      'Q' => 'WV',
      'L' => 'WL'
    );

    if( ! empty($ds))
    {
      $i = 1;
      foreach($ds as $rs)
      {
        if( ! empty($roles[$rs]))
        {
          $list .= $i=== 1 ? $roles[$rs] : ", {$roles[$rs]}";
          $i++;
        }
      }
    }

    return $list;
  }


  private function get_state_title($ds = array())
  {
    $list = "";
    
    $states = array(
      '1' => 'Pending',
      '2' => 'Waiting for payment',
      '3' => 'Waiting to pick',
      '4' => 'Picking',
      '5' => 'Waiting to pack',
      '6' => 'Packing',
      '7' => 'Ready to ship',
      '8' => 'Shipped',
      '9' => 'Cancelled'
    );

    if( ! empty($ds))
    {
      $i = 1;
      foreach($ds as $rs)
      {
        if( ! empty($states[$rs]))
        {
          $list .= $i === 1 ? $states[$rs] : ", {$states[$rs]}";
          $i++;
        }
      }
    }

    return $list;
  }


} //--- end class








 ?>
