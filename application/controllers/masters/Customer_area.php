<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_area extends PS_Controller
{
  public $menu_code = 'DBCARE';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'CUSTOMER';
	public $title = 'Sales Region';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/customer_area';
    $this->load->model('masters/customer_area_model');
  }


  public function index()
  {
		$code = get_filter('code', 'code', '');
		$name = get_filter('name', 'name', '');

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 4; //-- url segment
		$rows = $this->customer_area_model->count_rows($code, $name);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$rs = $this->customer_area_model->get_data($code, $name, $perpage, $this->uri->segment($segment));
    $ds = array(
      'code' => $code,
      'name' => $name,
			'data' => $rs
    );

		$this->pagination->initialize($init);
    $this->load->view('masters/customer_area/customer_area_view', $ds);
  }


  public function add_new()
  {
    $data['code'] = $this->session->flashdata('code');
    $data['name'] = $this->session->flashdata('name');
    $this->load->view('masters/customer_area/customer_area_add_view', $data);
  }


  public function add()
  {
    if($this->input->post('code'))
    {
      $sc = TRUE;
      $code = $this->input->post('code');
      $name = $this->input->post('name');
      $ds = array(
        'code' => $code,
        'name' => $name
      );

      if($this->customer_area_model->is_exists($code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' already exists");
      }

      if($this->customer_area_model->is_exists_name($name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' already exists");
      }

      if($sc === TRUE )
      {
        if($this->customer_area_model->add($ds))
        {
          set_message('Add data successfully');
        }
        else
        {
          $sc = FALSE;
          set_error('Failed to add data');
        }

      }


      if($sc === FALSE)
      {
        $this->session->set_flashdata('code', $code);
        $this->session->set_flashdata('name', $name);
      }
    }
    else
    {
      set_error('No data found');
    }

    redirect($this->home.'/add_new');
  }



  public function edit($code)
  {
    $rs = $this->customer_area_model->get($code);
    $data = array(
      'code' => $rs->code,
      'name' => $rs->name
    );

    $this->load->view('masters/customer_area/customer_area_edit_view', $data);
  }



  public function update()
  {
    $sc = TRUE;

    if($this->input->post('code'))
    {
      $old_code = $this->input->post('customer_area_code');
      $old_name = $this->input->post('customer_area_name');
      $code = $this->input->post('code');
      $name = $this->input->post('name');

      $ds = array(
        'code' => $code,
        'name' => $name
      );

      if($sc === TRUE && $this->customer_area_model->is_exists($code, $old_code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' already exists");
      }

      if($sc === TRUE && $this->customer_area_model->is_exists_name($name, $old_name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' already exists");
      }

      if($sc === TRUE)
      {
        if($this->customer_area_model->update($old_code, $ds) === TRUE)
        {
          set_message('Update data successfully');
        }
        else
        {
          $sc = FALSE;
          set_error('Update data failed');
        }
      }

    }
    else
    {
      $sc = FALSE;
      set_error('No data found');
    }

    if($sc === FALSE)
    {
      $code = $this->input->post('customer_area_code');
    }

    redirect($this->home.'/edit/'.$code);
  }



  public function delete($code)
  {
    if($code != '')
    {
      if($this->customer_area_model->delete($code))
      {
        set_message('Data has been deleted.');
      }
      else
      {
        set_error('Failed to delete data');
      }
    }
    else
    {
      set_error('No data found');
    }

    redirect($this->home);
  }



  public function clear_filter()
	{
		$this->session->unset_userdata('code');
    $this->session->unset_userdata('name');
		echo 'done';
	}

}//--- end class
 ?>
