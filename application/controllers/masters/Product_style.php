<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_style extends PS_Controller
{
  public $menu_code = 'DBPDST';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Model';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_style';
    $this->load->model('masters/product_style_model');
  }


  public function index()
  {
    $filter = array(
      'code'      => get_filter('code', 'code', ''),
      'name'      => get_filter('name', 'name', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->product_style_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$style    = $this->product_style_model->get_data($filter, $perpage, $this->uri->segment($segment));

    $data = array();

    if(!empty($style))
    {
      foreach($style as $rs)
      {
        $arr = new stdClass();
        $arr->code = $rs->code;
        $arr->name = $rs->name;
        $arr->menber = $this->product_style_model->count_members($rs->code);

        $data[] = $arr;
      }
    }


    $filter['data'] = $data;

		$this->pagination->initialize($init);
    $this->load->view('masters/product_style/product_style_view', $filter);
  }


  public function add_new()
  {
    $data['code'] = $this->session->flashdata('code');
    $data['name'] = $this->session->flashdata('name');
    $this->load->view('masters/product_style/product_style_add_view', $data);
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

      if($this->product_style_model->is_exists($code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' already exists");
      }



      if($sc === TRUE)
      {
        if($this->product_style_model->add($ds) === TRUE)
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
    $rs = $this->product_style_model->get($code);
    $data = array(
      'code' => $rs->code,
      'name' => $rs->name
    );

    $this->load->view('masters/product_style/product_style_edit_view', $data);
  }



  public function update()
  {
    $sc = TRUE;

    if($this->input->post('code'))
    {
      $old_code = $this->input->post('product_style_code');
      $old_name = $this->input->post('product_style_name');
      $code = $this->input->post('code');
      $name = $this->input->post('name');

      $ds = array(
        'code' => $code,
        'name' => $name
      );

      if($this->product_style_model->is_exists($code, $old_code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' already exists");
      }

      if($sc === TRUE)
      {
        if($this->product_style_model->update($old_code, $ds) === TRUE)
        {
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
      $code = $this->input->post('product_style_code');
    }

    redirect($this->home.'/edit/'.$code);
  }



  public function delete($code)
  {
    if($code != '')
    {
      if($this->product_style_model->delete($code))
      {
        set_message('Failed to delete data');
      }
      else
      {
        set_error('Data has been deleted.');
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


  public function export_to_sap($code)
  {
    $rs = $this->product_style_model->get($code);
    if(!empty($rs))
    {
      $ext = $this->product_style_model->is_sap_exists($code);

      $arr = array(
        'Code' => $rs->code,
        'Name' => $rs->name,
        'Flag' => $ext === TRUE ? 'U' : 'A',
        'UpdateDate' => sap_date(now(), TRUE)
      );

      if($ext)
      {
        return $this->product_style_model->update_sap_model($rs->code, $arr);
      }
      else
      {
        return $this->product_style_model->add_sap_model($arr);
      }
    }

    return FALSE;
  }


  public function style_init()
  {
    $this->load->view('export_products_attribute_view');
  }

}//--- end class
 ?>
