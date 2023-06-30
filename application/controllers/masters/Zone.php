<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zone extends PS_Controller
{
  public $menu_code = 'DBZONE';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'WAREHOUSE';
	public $title = 'Add/Edit Bin location';
  public $error;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/zone';
    $this->load->model('masters/zone_model');
    $this->load->helper('zone');
    $this->load->helper('warehouse');
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'z_code', ''),
      'uname' => get_filter('uname', 'z_uname', ''),
      'warehouse' => get_filter('warehouse', 'z_warehouse', ''),
      'customer' => get_filter('customer', 'z_customer', ''),
      'active' => get_filter('active', 'z_active', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->zone_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$list = $this->zone_model->get_list($filter, $perpage, $this->uri->segment($segment));

    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $rs->customer_count = $this->zone_model->count_customer($rs->code);
      }
    }

    $filter['list'] = $list;

		$this->pagination->initialize($init);
    $this->load->view('masters/zone/zone_list', $filter);
  }



  public function edit($code)
  {
    if($this->pm->can_edit)
    {
      $zone = $this->zone_model->get($code);
      $ds['ds'] = $zone;
      $ds['customers'] = $this->zone_model->get_customers($code);
      $ds['employees'] = NULL;

      if($zone->role == 8)
      {
        $ds['employees'] = $this->zone_model->get_employee($code);
      }

      $this->load->view('masters/zone/zone_edit', $ds);
    }
    else
    {
      set_error("permission");
      redirect($this->home);
    }
  }



  public function update_owner()
  {
    $sc = TRUE;
    if($this->input->post('zone_code'))
    {
      $zone_code = $this->input->post('zone_code');
      $user_id = get_null($this->input->post('user_id'));

      $zone = $this->zone_model->get($zone_code);

      if( ! empty($zone))
      {
        $arr = array('user_id' => $user_id);

        if( ! $this->zone_model->update($zone->id, $arr))
        {
          $sc = FALSE;
          $this->error = "Update data failed";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid Zone Code";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Missing required parameter : zone_code";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function delete($code)
  {
    $sc = TRUE;
    if($this->pm->can_delete)
    {
      if($this->zone_model->count_customer($code) > 0)
      {
        $sc = FALSE;
        $this->error = "Bin Location cannot be deleted because the customer is associated with it.";
      }
      else
      {
        if($this->zone_model->is_sap_exists($code))
        {
          $sc = FALSE;
          $this->error = "You have to delete Bin location in SAP before delete";
        }
      }

      if($sc === TRUE)
      {
        if( ! $this->zone_model->delete($code))
        {
          $sc = FALSE;
          $this->error = "Delete failed";
        }
      }

    }
    else
    {
      $sc = FALSE;
      set_error('permission');
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }




  public function add_customer()
  {
    $sc = TRUE;
    if($this->pm->can_edit)
    {
      if($this->input->post('zone_code') && $this->input->post('customer_code'))
      {
        $this->load->model('masters/customers_model');
        $code = $this->input->post('zone_code');
        $customer_code = $this->input->post('customer_code');
        $customer = $this->customers_model->get($customer_code);
        if(!empty($customer))
        {
          if($this->zone_model->is_exists_customer($code, $customer->code))
          {
            $sc = FALSE;
            $this->error = "The customer is already associated with this location.";
          }
          else
          {
            $arr = array(
              'zone_code' => $code,
              'customer_code' => $customer->code,
              'customer_name' => $customer->name
            );

            if( ! $this->zone_model->add_customer($arr))
            {
              $sc = FALSE;
              $this->error = "Add customer failed";
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invalid customer";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "No data found";
      }

    }
    else
    {
      $sc = FALSE;
      set_error('permission');
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function delete_customer($id)
  {
    $sc = TRUE;

    if($this->pm->can_edit)
    {
      if( ! $this->zone_model->delete_customer($id))
      {
        $sc = FALSE;
        $this->error = "Delete failed";
      }
    }
    else
    {
      $sc = FALSE;
      set_error('permission');
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function add_employee()
  {
    $sc = TRUE;
    if($this->pm->can_edit)
    {
      if($this->input->post('zone_code') && $this->input->post('empID'))
      {
        $this->load->model('masters/employee_model');
        $code = $this->input->post('zone_code');
        $empName = $this->input->post('empName');
        $empID = $this->input->post('empID');
        $emp = $this->employee_model->get($empID);
        $zone = $this->zone_model->get($code);

        if($zone->role != 8)
        {
          $sc = FALSE;
          $this->error = "This bin location is not for lending products";
        }

        if($sc === TRUE)
        {
          if(!empty($emp))
          {
            if($this->zone_model->is_exists_employee($code, $empID))
            {
              $sc = FALSE;
              $this->error = "Employees are already associated with this zone.";
            }
            else
            {
              $arr = array(
                'zone_code' => $code,
                'empID' => $empID,
                'empName' => $empName
              );

              if( ! $this->zone_model->add_employee($arr))
              {
                $sc = FALSE;
                $this->error = "Add Employee failed";
              }
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "Invalid ";
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "ไม่พบข้อมูล";
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไม่มีสิทธิ์ในการเพิ่มข้อมูล";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function delete_employee($id)
  {
    $sc = TRUE;

    if($this->pm->can_edit)
    {
      if( ! $this->zone_model->delete_employee($id))
      {
        $sc = FALSE;
        $this->error = "ลบรายการไม่สำเร็จ";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไม่มีสิทธิ์ลบข้อมูล";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function syncData()
  {
    $last_sync = $this->zone_model->get_last_sync_date();
    $newData = $this->zone_model->get_new_data($last_sync);

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->zone_model->is_exists_id($rs->id))
        {
          $ds = array(
            'code' => $rs->code,
            'name' => is_null($rs->name) ? $rs->code : $rs->name,
						'warehouse_code' => $rs->warehouse_code,
            'old_code' => $rs->old_code,
            'active' => $rs->Disabled == 'N' ? 1 : 0,
            'last_sync' => date('Y-m-d H:i:s')
          );

          $this->zone_model->update($rs->id, $ds);
        }
        else
        {
          $ds = array(
            'id' => $rs->id,
            'code' => $rs->code,
            'name' => is_null($rs->name) ? $rs->code : $rs->name,
            'warehouse_code' => $rs->warehouse_code,
            'active' => $rs->Disabled == 'N' ? 1 : 0,
            'last_sync' => date('Y-m-d H:i:s'),
            'old_code' => $rs->old_code
          );

          $this->zone_model->add($ds);
        }
      }
    }

    echo 'done';
  }



  //--- check zone
  public function get_zone_code()
  {
    $sc = TRUE;
    if($this->input->get('barcode'))
    {
      $barcode = trim($this->input->get('barcode'));
      $code = $this->zone_model->get_zone_code($barcode);

      if($code === FALSE)
      {
        $sc = FALSE;
      }
    }

    echo $sc === TRUE ? $code : 'not_exists';
  }



  public function get_warehouse_zone()
  {
    $sc = TRUE;
    $code = trim($this->input->get('barcode'));
    $warehouse_code = trim($this->input->get('warehouse_code'));
    if(!empty($code) && !empty($warehouse_code))
    {
      $zone = $this->zone_model->get_zone_detail_in_warehouse($code, $warehouse_code);
      if($zone === FALSE)
      {
        $sc = FALSE;
        $this->error = "ไม่พบโซน";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "รหัสโซนหรือรหัสคลังไม่ถูกต้อง : {$code} | {$warehouse_code}";
    }

    echo $sc === TRUE ? json_encode($zone) : 'not_exists';
  }




  public function export_filter()
  {
    $ds = array(
      'code' => $this->input->post('zone_code'),
      'uname' => $this->input->post('zone_uname'),
      'customer' => $this->input->post('zone_customer'),
      'warehouse' => $this->input->post('zone_warehouse')
    );

    $token = $this->input->post('token');

    $list = $this->zone_model->get_list($ds);

    //--- load excel library
    $this->load->library('excel');

    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Zone master data');

    //--- set Table header


    $this->excel->getActiveSheet()->setCellValue('A1', 'ลำดับ');
    $this->excel->getActiveSheet()->setCellValue('B1', 'รหัสโซน');
    $this->excel->getActiveSheet()->setCellValue('C1', 'ชื่อโซน');
    $this->excel->getActiveSheet()->setCellValue('D1', 'รหัสคลัง');
    $this->excel->getActiveSheet()->setCellValue('E1', 'คลังสินค้า');
    $this->excel->getActiveSheet()->setCellValue('F1', 'รหัสเก่า');
    $this->excel->getActiveSheet()->setCellValue('G1', 'เจ้าของโซน');


    //---- กำหนดความกว้างของคอลัมภ์
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);


    $row = 2;


    if(!empty($list))
    {
      $no = 1;

      foreach($list as $rs)
      {
        //--- ลำดับ
        $this->excel->getActiveSheet()->setCellValue('A'.$row, $no);

        //--- zone code
        $this->excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);

        //--- zone name
        $this->excel->getActiveSheet()->setCellValue('C'.$row, $rs->name);

        //--- warehouse code
        $this->excel->getActiveSheet()->setCellValue('D'.$row, $rs->warehouse_code);

        //---- waehouser name
        $this->excel->getActiveSheet()->setCellValue('E'.$row, $rs->warehouse_name);
        //--- old code
        $this->excel->getActiveSheet()->setCellValue('F'.$row, "{$rs->old_code}");

        //--- user name
        $this->excel->getActiveSheet()->setCellValue('G'.$row, $rs->uname);

        $this->excel->getActiveSheet()->setCellValue('H'.$row, $rs->display_name);



        $no++;
        $row++;
      }

      setToken($token);
      $file_name = "Zone Master Data.xlsx";
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
      header('Content-Disposition: attachment;filename="'.$file_name.'"');
      $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
      $writer->save('php://output');
    }
  }



  public function clear_filter()
  {
    $filter = array('z_code', 'z_uname', 'z_customer', 'z_warehouse', 'z_active');
    clear_filter($filter);
  }

} //--- end class

 ?>
