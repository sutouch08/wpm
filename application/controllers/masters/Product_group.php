<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_group extends PS_Controller
{
  public $menu_code = 'DBPDGP';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Group';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_group';
    $this->load->model('masters/product_group_model');
  }


  public function index()
  {
		$code = get_filter('code', 'pd_group_code', '');
		$name = get_filter('name', 'pd_group_name', '');

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 4; //-- url segment
		$rows = $this->product_group_model->count_rows($code, $name);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$group = $this->product_group_model->get_data($code, $name, $perpage, $this->uri->segment($segment));

    $data = array();

    if(!empty($group))
    {
      foreach($group as $rs)
      {
        $arr = new stdClass();
        $arr->code = $rs->code;
        $arr->name = $rs->name;
        $arr->menber = $this->product_group_model->count_members($rs->code);

        $data[] = $arr;
      }
    }


    $ds = array(
      'code' => $code,
      'name' => $name,
			'data' => $data
    );

		$this->pagination->initialize($init);
    $this->load->view('masters/product_group/product_group_view', $ds);
  }


  public function add_new()
  {
    $data['code'] = $this->session->flashdata('code');
    $data['name'] = $this->session->flashdata('name');

    $this->load->view('masters/product_group/product_group_add_view', $data);
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

      if($this->product_group_model->is_exists($code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' already exists");
      }

      if($this->product_group_model->is_exists_name($name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' already exists");
      }

      if($sc === TRUE)
      {
        if($this->product_group_model->add($ds))
        {
          //--- export to sap
          $this->export_sap_product_group($code, $code);

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

    $rs = $this->product_group_model->get($code);
    $data = array(
      'code' => $rs->code,
      'name' => $rs->name
    );

    $this->load->view('masters/product_group/product_group_edit_view', $data);
  }



  public function update()
  {
    $sc = TRUE;

    if($this->input->post('code'))
    {
      $old_code = $this->input->post('product_group_code');
      $old_name = $this->input->post('product_group_name');
      $code = $this->input->post('code');
      $name = $this->input->post('name');

      $ds = array(
        'code' => $code,
        'name' => $name
      );

      if($sc === TRUE && $this->product_group_model->is_exists($code, $old_code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' already exists");
      }

      if($sc === TRUE && $this->product_group_model->is_exists_name($name, $old_name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' already exists");
      }

      if($sc === TRUE)
      {
        if($this->product_group_model->update($old_code, $ds) === TRUE)
        {
          //--- export to sap
          $this->export_sap_product_group($code, $old_code);

          set_message('Update data successfully');
        }
        else
        {
          $sc = FALSE;
          set_error('Failed to update data');
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
      $code = $this->input->post('product_group_code');
    }

    redirect($this->home.'/edit/'.$code);
  }



  public function delete($code)
  {
    if($code != '')
    {
      if($this->product_group_model->delete($code))
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


  public function export_sap_product_group($code, $old_code)
  {
    $rs = $this->product_group_model->get($code);
    if(!empty($rs))
    {
      $ext = $this->product_group_model->is_sap_exists($old_code);

      if($ext)
      {
        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'UpdateDate' => sap_date(now(), TRUE),
          'Flag' => 'U'
        );

        if($code != $old_code)
        {
          $arr['OLDCODE'] = $old_code;
        }

        //return $this->product_group_model->update_sap_product_group($old_code, $arr);
      }
      else
      {
        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'UpdateDate' => sap_date(now(), TRUE),
          'Flag' => 'A',
          'OLDCODE' => NULL
        );

      }

      return $this->product_group_model->add_sap_product_group($arr);
    }

    return FALSE;
  }


  public function clear_filter()
	{
		$filter = array('pd_group_code', 'pd_group_name');
    clear_filter($filter);
		echo 'done';
	}

}//--- end class
 ?>
