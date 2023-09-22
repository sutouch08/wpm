<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_tab extends PS_Controller
{
  public $menu_code = 'DBPTAB';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Tab';
  public $error = '';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_tab';
    //--- load model
    $this->load->model('masters/product_tab_model');
    $this->load->helper('product_tab');
  }


  public function index()
  {
    $filter = array(
      'tab_name' => get_filter('tab_name', 'tab_name', ''),
      'parent' => get_filter('parent', 'parent', '')
    );

    //--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->product_tab_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$tabs = $this->product_tab_model->get_list($filter, $perpage, $this->uri->segment($segment));

    if(!empty($tabs))
    {
      foreach($tabs as $rs)
      {
        if($rs->id_parent == 0)
        {
          $rs->parent = "TOP LEVEL";
        }

        $rs->members = $this->product_tab_model->countMember($rs->id);
      }
    }

    $filter['tabs'] = $tabs;
		$this->pagination->initialize($init);
    $this->load->view('masters/product_tab/product_tab_view', $filter);
  }


  public function add_new()
  {
    $this->load->view('masters/product_tab/product_tab_add');
  }


  public function add()
  {
    if($this->input->post('tab_name'))
    {
      $name = trim($this->input->post('tab_name'));
      $parent = $this->input->post('tabs');

      if(! $this->product_tab_model->isExists('name', $name))
      {
        $arr = array(
          'name' => $name,
          'id_parent' => $parent
        );

        if($this->product_tab_model->add($arr))
        {
          set_message("Add data successfully");
        }
        else
        {
          set_error("Failed to add data");
        }
      }
      else
      {
        set_error("already exists");
      }
    }
    else
    {
      set_error("Please specify name");
    }

    redirect($this->home.'/add_new');
  }


  public function edit($id)
  {
    $ds = $this->product_tab_model->get($id);
    $this->load->view('masters/product_tab/product_tab_edit', $ds);
  }


  public function update($id)
  {
    if($this->input->post('tab_name'))
    {
      $name = trim($this->input->post('tab_name'));
      $parent = $this->input->post('tabs');

      if(! $this->product_tab_model->isExists('name', $name, $id))
      {
        $arr = array(
          'name' => $name,
          'id_parent' => $parent
        );

        if(! $this->product_tab_model->update($id, $arr))
        {
          set_error('Failed to update data');
        }
        else
        {
          set_message('Update data successfully');
        }
      }
      else
      {
        set_error('Duplicated');
      }
    }
    else
    {
      set_error('Please specify name');
    }

    redirect($this->home.'/edit/'.$id);
  }


  public function delete($id)
  {
    $sc = TRUE;

    if(! $this->product_tab_model->is_has_child($id))
    {
      if( ! $this->product_tab_model->delete($id))
      {
        $sc = FALSE;
        $this->error = "Failed to delete data";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "This tab cannot be delete becuase child tab exists";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }

  public function clear_filter()
  {
    $filter = array(
      'tab_name',
      'parent'
    );

    return clear_filter($filter);
  }




}//--- end class
?>
