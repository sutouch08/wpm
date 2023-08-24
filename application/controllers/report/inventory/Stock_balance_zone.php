<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_balance_zone extends PS_Controller
{
  public $menu_code = 'RICSBZ';
	public $menu_group_code = 'RE';
  public $menu_sub_group_code = 'REINVT';
	public $title = 'Inventory report by zone (SAP)';
  public $filter;
  public $error;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'report/inventory/stock_balance_zone';
    $this->load->model('report/inventory/stock_balance_report_model');
    $this->load->model('masters/products_model');
  }

  public function index()
  {
    $this->load->model('masters/warehouse_model');
    $whList = $this->warehouse_model->get_all_warehouse();
    $ds['whList'] = $whList;
    $this->load->view('report/inventory/report_stock_balance_zone', $ds);
  }


  public function get_report()
  {
    ini_set('memory_limit','512M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
    ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
    ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv
    $sc = TRUE;
    $allProduct = $this->input->get('allProduct');
    $pdFrom = $this->input->get('pdFrom');
    $pdTo = $this->input->get('pdTo');

    $allWhouse = $this->input->get('allWhouse');
    $warehouse = $this->input->get('warehouse');

    $allZone = $this->input->get('allZone');
    $zoneCode = $this->input->get('zoneCode');
    $zoneName = $this->input->get('zoneName');

    $wh_list = '';
    if(!empty($warehouse) && empty($zoneCode))
    {
      $i = 1;
      foreach($warehouse as $wh)
      {
        $wh_list .= $i === 1 ? $wh : ', '.$wh;
        $i++;
      }
    }

    //---  Report title
    $ds['reportDate'] = thai_date(date('Y-m-d'),FALSE, '/');
    $ds['whList']   = $allWhouse == 1 ? 'All' : $wh_list;
    $ds['zoneList'] = $allZone == 1 ? 'All' : $zoneCode." - ".$zoneName;
    $ds['productList']   = $allProduct == 1 ? 'All' : '('.$pdFrom.') - ('.$pdTo.')';

    $bs = array();

    $result = $this->stock_balance_report_model->get_stock_balance_zone($allProduct, $pdFrom, $pdTo, $allWhouse, $warehouse, $allZone, $zoneCode);

    if(!empty($result))
    {
      if(count($result) > 2000)
      {
        $sc = FALSE;
        $this->error = "The amount of data is too large to be displayed. Please export data instead of screen display.";
      }
      else
      {
        $no = 1;
        $totalQty = 0;
        $totalAmount = 0;

        foreach($result as $rs)
        {
          $amount = $rs->qty * $rs->price;

          $arr = array(
            'no' => number($no),
            'warehouse' => $rs->warehouse_code,
            'zone' => $rs->zone_name,
            'pdCode' => $rs->product_code,
            'pdName' => $rs->product_name,
            'price' => number($rs->price, 2),
            'qty' => number($rs->qty),
            'amount' => number($amount, 2)
          );

          array_push($bs, $arr);
          $totalQty += $rs->qty;
          $totalAmount += $amount;

          $no++;
        }

        $arr = array( 'totalQty' => number($totalQty), 'totalAmount' => number($totalAmount, 2));
        array_push($bs, $arr);
      }
    }
    else
    {
      $arr = array('nodata' => 'nodata');
      array_push($bs, $arr);
    }

    $ds['bs'] = $bs;

    echo $sc === TRUE ? json_encode($ds) : $this->error;
  }





  public function do_export()
  {
    ini_set('memory_limit','512M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
    ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
    ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv

    $sc = TRUE;
    $allProduct = $this->input->post('allProduct');
    $pdFrom = $this->input->post('pdFrom');
    $pdTo = $this->input->post('pdTo');

    $allWhouse = $this->input->post('allWhouse');
    $warehouse = $this->input->post('warehouse');

    $allZone = $this->input->post('allZone');
    $zoneCode = $this->input->post('zoneCode');
    $zoneName = $this->input->post('zoneName');

    $token = $this->input->post('token');

    $wh_list = '';
    if(!empty($warehouse) && empty($zoneCode))
    {
      $i = 1;
      foreach($warehouse as $wh)
      {
        $wh_list .= $i === 1 ? $wh : ', '.$wh;
        $i++;
      }
    }

    //---  Report title
    $report_title = "Inventory report by zone (SAP) on ".date('d/m/Y');
    $whList = $allWhouse == 1 ? 'All' : $wh_list;
    $zoneList = $allZone == 1 ? 'All' : $zoneCode." - ".$zoneName;
    $productList  = $allProduct == 1 ? 'All' : '('.$pdFrom.') - ('.$pdTo.')';

    $bs = array();

    $result = $this->stock_balance_report_model->get_stock_balance_zone($allProduct, $pdFrom, $pdTo, $allWhouse, $warehouse, $allZone, $zoneCode);

    //--- load excel library
    $this->load->library('excel');

    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Stock Balance Report');

    //--- set report title header
    $this->excel->getActiveSheet()->setCellValue('A1', $report_title);
    $this->excel->getActiveSheet()->mergeCells('A1:G1');
    $this->excel->getActiveSheet()->setCellValue('A2', 'Warehouse');
    $this->excel->getActiveSheet()->setCellValue('B2', $whList);
    $this->excel->getActiveSheet()->mergeCells('B2:G2');
    $this->excel->getActiveSheet()->setCellValue('A3', 'Zone');
    $this->excel->getActiveSheet()->setCellValue('B3', $zoneList);
    $this->excel->getActiveSheet()->mergeCells('B3:G3');
    $this->excel->getActiveSheet()->setCellValue('A4', 'Items');
    $this->excel->getActiveSheet()->setCellValue('B4', $productList);
    $this->excel->getActiveSheet()->mergeCells('B4:G4');

    //--- set Table header
    $this->excel->getActiveSheet()->setCellValue('A5', 'No');
    $this->excel->getActiveSheet()->setCellValue('B5', 'Warehouse');
    $this->excel->getActiveSheet()->setCellValue('C5', 'Zone Code');
    $this->excel->getActiveSheet()->setCellValue('D5', 'Zone name');
    $this->excel->getActiveSheet()->setCellValue('E5', 'Item Code');
    $this->excel->getActiveSheet()->setCellValue('F5', 'Description');
    $this->excel->getActiveSheet()->setCellValue('G5', 'Price');
    $this->excel->getActiveSheet()->setCellValue('H5', 'Qty');
    $this->excel->getActiveSheet()->setCellValue('I5', 'Amount');

    $row = 6;
    if(!empty($result))
    {
      $no = 1;
      $totalQty = 0;
      foreach($result as $rs)
      {
        $this->excel->getActiveSheet()->setCellValue('A'.$row, $no);
        $this->excel->getActiveSheet()->setCellValue('B'.$row, $rs->warehouse_code);
        $this->excel->getActiveSheet()->setCellValue('C'.$row, $rs->zone_code);
        $this->excel->getActiveSheet()->setCellValue('D'.$row, $rs->zone_name);
        $this->excel->getActiveSheet()->setCellValue('E'.$row, $rs->product_code);
        $this->excel->getActiveSheet()->setCellValue('F'.$row, $rs->product_name);
        $this->excel->getActiveSheet()->setCellValue('G'.$row, $rs->price);
        $this->excel->getActiveSheet()->setCellValue('H'.$row, $rs->qty);
        $this->excel->getActiveSheet()->setCellValue('I'.$row, "=G{$row}*H{$row}");
        $no++;
        $row++;
      }

      $ro = $row - 1;
      $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Total');
      $this->excel->getActiveSheet()->mergeCells('A'.$row.':G'.$row);
      $this->excel->getActiveSheet()->setCellValue('H'.$row, "=SUM(H6:H{$ro})");
      $this->excel->getActiveSheet()->setCellValue('I'.$row, "=SUM(I6:I{$ro})");
      $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
    }

    setToken($token);
    $file_name = "Report Stock Zone.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
    header('Content-Disposition: attachment;filename="'.$file_name.'"');
    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    $writer->save('php://output');

  }


  public function export_to_check()
  {
    ini_set('memory_limit','512M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
    ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
    ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv

    $allProduct = 1;
    $pdFrom = NULL;
    $pdTo = NULL;

    $allWhouse = 1;
    $warehouse = NULL;

    $allZone = 0;
    $zoneCode = $this->input->post('zone_code');
    $token = $this->input->post('token');

    $result = $this->stock_balance_report_model->get_stock_balance_zone($allProduct, $pdFrom, $pdTo, $allWhouse, $warehouse, $allZone, $zoneCode);

    //--- load excel library
    $this->load->library('excel');

    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Report Stock Zone');

    //--- set Table header
    $this->excel->getActiveSheet()->setCellValue('A1', 'barcode');
    $this->excel->getActiveSheet()->setCellValue('B1', 'item_code');
    $this->excel->getActiveSheet()->setCellValue('C1', 'qty');

    $row = 2;
    if(!empty($result))
    {
      foreach($result as $rs)
      {
        $this->excel->getActiveSheet()->setCellValue('A'.$row, $this->products_model->get_barcode($rs->product_code));
        $this->excel->getActiveSheet()->setCellValue('B'.$row, $rs->product_code);
        $this->excel->getActiveSheet()->setCellValue('C'.$row, $rs->qty);
        $row++;
      }

      $this->excel->getActiveSheet()->getStyle('A2:A'.($row -1))->getNumberFormat()->setFormatCode('0');
    }

    setToken($token);
    $file_name = "Report Stock Zone.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
    header('Content-Disposition: attachment;filename="'.$file_name.'"');
    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    $writer->save('php://output');
  }


} //--- end class








 ?>
