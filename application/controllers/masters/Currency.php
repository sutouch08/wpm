<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Currency extends PS_Controller
{
  public $menu_code = 'DBCURN';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = '';
	public $title = 'Currency List';
  public $error;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/currency';
    $this->load->model('masters/currency_model');
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'code', ''),
      'name' => get_filter('name', 'name', ''),
      'active' => get_filter('active', 'active', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->currency_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$filter['data'] = $this->currency_model->get_list($filter, $perpage, $this->uri->segment($segment));

		$this->pagination->initialize($init);
    $this->load->view('masters/currency/currency_list', $filter);
  }


  public function sync_data()
  {
    $sc = TRUE;

    $data = $this->currency_model->getSapCurrency();

    if( ! empty($data))
    {
      foreach($data as $rs)
      {
        $ds = $this->currency_model->get($rs->CurrCode);

        $arr = array(
          'CurrCode' => $rs->CurrCode,
          'CurrName' => $rs->CurrName,
          'DocCurrCod' => $rs->DocCurrCod
        );

        if( ! empty($ds))
        {
          if( ! $this->currency_model->update($ds->CurrCode, $arr))
          {
            $sc = FALSE;
            set_error('update');
          }
        }
        else
        {
          if( ! $this->currency_model->add($arr))
          {
            $sc = FALSE;
            set_error('insert');
          }
        }
      }
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  function set_active()
  {
    $sc = TRUE;

    $code = $this->input->post('code');
    $active = empty($this->input->post('active')) ? 0 : 1;

    $arr = array(
      'active' => $active
    );

    if( ! $this->currency_model->update($code, $arr))
    {
      $sc = FALSE;
      set_error('update');
    }

    echo $sc === TRUE ? 'success' : $this->error;

  }


  public function clear_filter()
  {
    $filter = array('code', 'name', 'active');
    clear_filter($filter);
  }

} //--- end class

 ?>
