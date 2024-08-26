<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouse extends PS_Controller
{
  public $menu_code = 'DBWRHS';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'WAREHOUSE';
	public $title = 'Warehouse';
  public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/warehouse';
    $this->load->model('masters/warehouse_model');
    $this->load->helper('warehouse');
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'wh_code', ''),
      'name' => get_filter('name', 'wh_name', ''),
      'role' => get_filter('role', 'wh_role', 'all'),
      'is_consignment' => get_filter('is_consignment', 'is_consignment', 'all'),
      'active' => get_filter('active', 'wh_active', 'all'),
      'sell' => get_filter('sell', 'wh_sell', 'all'),
      'prepare' => get_filter('prepare', 'wh_prepare', 'all'),
      'auz' => get_filter('auz', 'wh_auz', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$rows = $this->warehouse_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$list = $this->warehouse_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $rs->zone_count = $this->warehouse_model->count_zone($rs->code);
      }
    }

    $filter['list'] = $list;

		$this->pagination->initialize($init);
    $this->load->view('masters/warehouse/warehouse_list', $filter);
  }



  public function edit($code)
  {
    if($this->pm->can_edit)
    {
      $this->load->helper('zone');
      $ds['ds'] = $this->warehouse_model->get($code);
      $this->load->view('masters/warehouse/warehouse_edit', $ds);
    }
    else
    {
      set_error("You do not have the right to change warehouse data");
      redirect($this->home);
    }
  }



  public function update()
  {
    if($this->pm->can_edit)
    {
      if($this->input->post('code'))
      {
        $code = $this->input->post('code');
        $arr = array(
          'role' => $this->input->post('role'),
          'default_zone' => $this->input->post('default_zone'),
          'sell' => $this->input->post('sell'),
          'prepare' => $this->input->post('prepare'),
          'auz' => $this->input->post('auz'),
          'active' => $this->input->post('active'),
          'is_consignment' => get_null($this->input->post('is_consignment')),
          'update_user' => $this->_user->uname
        );

        if($this->warehouse_model->update($code, $arr))
        {
          set_message("Update Successfull");
          redirect($this->home.'/edit/'.$code);
        }
        else
        {
          set_error("Update Fail");
          redirect($this->home.'/edit/'.$code);
        }
      }
      else
      {
        set_error('No data found');
        redirect($this->home);
      }
    }
    else
    {
      set_error('You do not have the right to change warehouse data');
      redirect($this->home);
    }
  }


  public function delete($code)
  {
    $sc = TRUE;

    if($this->pm->can_delete)
    {
      //---- count member if exists reject action
      if($this->warehouse_model->has_zone($code))
      {
        $sc = FALSE;
        $this->error = 'The warehouse cannot be deleted because there are location in it.';
      }
      //--- check warehouse in SAP if exists reject action
      else if($this->warehouse_model->is_sap_exists($code))
      {
        $sc = FALSE;
        $this->error = 'The warehouse cannot be deleted because warehouse still in SAP';
      }
      else
      {
        if($this->warehouse_model->delete($code) === FALSE)
        {
          $sc = FALSE;
          $this->error = 'Failed to delete data';
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = 'You do not have the right to delete';
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function syncData()
  {
    $last_sync = $this->warehouse_model->get_last_sync_date();
    //$last_sync = date('Y-m-d H:i:s', strtotime('2019-01-01 00:00:00'));
    $newData = $this->warehouse_model->get_new_data($last_sync);

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->warehouse_model->is_exists($rs->code))
        {
          $ds = array(
            'name' => $rs->name,
            'active' => $rs->Inactive == 'Y' ? 0 : 1,
            'last_sync' => date('Y-m-d H:i:s'),
            'update_user' => 'SAP',
            'old_code' => $rs->old_code,
            'limit_amount' => 0 //$rs->limit_amount
          );

          $this->warehouse_model->update($rs->code, $ds);
        }
        else
        {
          $ds = array(
            'code' => $rs->code,
            'name' => $rs->name,
            'active' => $rs->Inactive == 'Y' ? 0 : 1,
            'last_sync' => date('Y-m-d H:i:s'),
            'update_user' => 'SAP',
            'old_code' => $rs->old_code,
            'limit_amount' => 0 //$rs->limit_amount
          );

          $this->warehouse_model->add($ds);
        }
      }
    }

    echo 'done';
  }


  public function syncAllData()
  {
    $last_sync = date('Y-m-d H:i:s', strtotime('2019-01-01 00:00:00'));
    $newData = $this->warehouse_model->get_new_data($last_sync);

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->warehouse_model->is_exists($rs->code))
        {
          $ds = array(
            'name' => $rs->name,
            'active' => $rs->Inactive == 'Y' ? 0 : 1,
            'last_sync' => date('Y-m-d H:i:s'),
            'update_user' => 'SAP',
            'old_code' => $rs->old_code,
            'limit_amount' => 0 //$rs->limit_amount
          );

          $this->warehouse_model->update($rs->code, $ds);
        }
        else
        {
          $ds = array(
            'code' => $rs->code,
            'name' => $rs->name,
            'active' => $rs->Inactive == 'Y' ? 0 : 1,
            'last_sync' => date('Y-m-d H:i:s'),
            'update_user' => 'SAP',
            'old_code' => $rs->old_code,
            'limit_amount' => 0 //$rs->limit_amount
          );

          $this->warehouse_model->add($ds);
        }
      }
    }

    echo 'done';
  }



  public function clear_filter()
  {
    $filter = array('wh_code', 'wh_name', 'wh_role', 'is_consignment', 'wh_active', 'wh_sell', 'wh_prepare', 'wh_auz');
    clear_filter($filter);
  }

} //--- end class

 ?>
