<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prepare extends PS_Controller
{
  public $menu_code = 'ICODPR';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = 'PICKPACK';
	public $title = 'จัดสินค้า';
  public $filter;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/prepare';
    $this->load->model('inventory/prepare_model');
    $this->load->model('orders/orders_model');
    $this->load->model('orders/order_state_model');
  }


  public function index()
  {
    $this->load->helper('channels');
    $this->load->helper('payment_method');
    $this->load->helper('warehouse');
    $filter = array(
      'code'          => get_filter('code', 'ic_code', ''),
      'customer'      => get_filter('customer', 'ic_customer', ''),
      'user'          => get_filter('user', 'ic_user', ''),
      'channels'      => get_filter('channels', 'ic_channels', ''),
      'is_online'     => get_filter('is_online', 'ic_is_online', '2'),
      'role'          => get_filter('role', 'ic_role', 'all'),
      'from_date'     => get_filter('from_date', 'ic_from_date', ''),
      'to_date'       => get_filter('to_date', 'ic_to_date', ''),
      'order_by'      => get_filter('order_by', 'ic_order_by', ''),
      'sort_by'       => get_filter('sort_by', 'ic_sort_by', ''),
      'stated'        => get_filter('stated', 'ic_stated', ''),
      'startTime'     => get_filter('startTime', 'ic_startTime', ''),
      'endTime'       => get_filter('endTime', 'ic_endTime', ''),
      'item_code'    => get_filter('item_code', 'ic_item_code', ''),
      'payment' => get_filter('payment', 'ic_payment', 'all'),
      'warehouse' => get_filter('warehouse', 'ic_warehouse', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->prepare_model->count_rows($filter, 3);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$orders   = $this->prepare_model->get_data($filter, $perpage, $this->uri->segment($segment), 3);

    $filter['orders'] = $orders;
    
		$this->pagination->initialize($init);
    $this->load->view('inventory/prepare/prepare_list', $filter);
  }





  public function view_process()
  {
    $this->load->helper('channels');
    $this->load->helper('payment_method');
    $this->load->helper('warehouse');
    $filter = array(
      'code'          => get_filter('code', 'ic_code', ''),
      'customer'      => get_filter('customer', 'ic_customer', ''),
      'display_name'  => get_filter('display_name', 'ic_display_name', ''),
      'channels'      => get_filter('channels', 'ic_channels', ''),
      'is_online'     => get_filter('is_online', 'ic_is_online', '2'),
      'role'          => get_filter('role', 'ic_role', 'all'),
      'from_date'     => get_filter('from_date', 'ic_from_date', ''),
      'to_date'       => get_filter('to_date', 'ic_to_date', ''),
      'order_by'      => get_filter('order_by', 'ic_order_by', ''),
      'sort_by'       => get_filter('sort_by', 'ic_sort_by', ''),
      'stated'        => get_filter('stated', 'ic_stated', ''),
      'startTime'     => get_filter('startTime', 'ic_startTime', ''),
      'endTime'       => get_filter('endTime', 'ic_endTime', ''),
      'item_code'    => get_filter('item_code', 'ic_item_code', ''),
      'payment'  => get_filter('payment', 'ic_payment', 'all'),
      'warehouse' => get_filter('warehouse', 'ic_warehouse', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->prepare_model->count_rows($filter, 4);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/view_process/', $rows, $perpage, $segment);
		$orders   = $this->prepare_model->get_data($filter, $perpage, $this->uri->segment($segment), 4);

    $filter['orders'] = $orders;

		$this->pagination->initialize($init);
    $this->load->view('inventory/prepare/prepare_view_process', $filter);
  }



  public function process($code)
  {
    $this->load->model('masters/customers_model');
    $this->load->model('masters/channels_model');
    $state = $this->orders_model->get_state($code);

    if($state == 3)
    {
      $rs = $this->orders_model->change_state($code, 4);
      if($rs)
      {
        $arr = array(
          'order_code' => $code,
          'state' => 4,
          'update_user' => get_cookie('uname')
        );
        $this->order_state_model->add_state($arr);
      }
    }

    $order = $this->orders_model->get($code);
    $order->customer_name = $this->customers_model->get_name($order->customer_code);
    $order->channels_name = $this->channels_model->get_name($order->channels_code);

    $uncomplete = $this->orders_model->get_unvalid_details($code);
    if(!empty($uncomplete))
    {
      foreach($uncomplete as $rs)
      {
        $rs->barcode = $this->get_barcode($rs->product_code);
        $rs->prepared = $this->get_prepared($rs->order_code, $rs->product_code);
        $rs->stock_in_zone = $this->get_stock_in_zone($rs->product_code, get_null($order->warehouse_code));
      }
    }

    $complete = $this->orders_model->get_valid_details($code);
    if(!empty($complete))
    {
      foreach($complete as $rs)
      {
        $rs->barcode = $this->get_barcode($rs->product_code);
        $rs->prepared = $rs->is_count == 1 ? $this->get_prepared($rs->order_code, $rs->product_code) : $rs->qty;
        $rs->from_zone = $this->get_prepared_from_zone($rs->order_code, $rs->product_code, $rs->is_count);
      }
    }

    $ds = array(
      'order' => $order,
      'uncomplete_details' => $uncomplete,
      'complete_details' => $complete
    );

    $this->load->view('inventory/prepare/prepare_process', $ds);
  }




  public function do_prepare()
  {
    $sc = TRUE;
    $valid = 0;
    if($this->input->post('order_code'))
    {
      $this->load->model('masters/products_model');

      $order_code = $this->input->post('order_code');
      $zone_code  = $this->input->post('zone_code');
      $barcode    = $this->input->post('barcode');
      $qty        = $this->input->post('qty');

      $state = $this->orders_model->get_state($order_code);
      //--- ตรวจสอบสถานะออเดอร์ 4 == กำลังจัดสินค้า
      if($state == 4)
      {
        $item = $this->products_model->get_product_by_barcode($barcode);
        if(empty($item))
        {
          $item = $this->products_model->get($barcode);
        }

        //--- ตรวจสอบบาร์โค้ดที่ยิงมา
        if(!empty($item))
        {
          if($item->count_stock == 1)
          {
            $ds = $this->orders_model->get_order_detail($order_code, $item->code);
            if(!empty($ds))
            {
              //--- ดึงยอดที่จัดแล้ว
              $prepared = $this->get_prepared($ds->order_code, $ds->product_code);

              //--- ยอดคงเหลือค้างจัด
              $bQty = $ds->qty - $prepared;

              //---- ตรวจสอบยอดที่ยังไม่ครบว่าจัดเกินหรือเปล่า
              if( $bQty < $qty)
              {
                $sc = FALSE;
                $message = "สินค้าเกิน กรุณาคืนสินค้าแล้วจัดสินค้าใหม่อีกครั้ง";
              }
              else
              {
                $stock = $this->get_stock_zone($zone_code, $ds->product_code); //1000;

                if($stock < $qty)
                {
                  $sc = FALSE;
                  $message = "สินค้าไม่เพียงพอ กรุณากำหนดจำนวนสินค้าใหม่";
                }
                else
                {
                  $this->db->trans_start();
                  $this->prepare_model->update_buffer($ds->order_code, $ds->product_code, $zone_code, $qty);
                  $this->prepare_model->update_prepare($ds->order_code, $ds->product_code, $zone_code, $qty);
                  $this->db->trans_complete();

                  if($this->db->trans_status() === FALSE)
                  {
                    $sc = FALSE;
                    $message = 'ทำรายการไม่สำเร็จ';
                  }

                  if($sc === TRUE)
                  {
                    $preparedQty = $this->get_prepared($ds->order_code, $ds->product_code);
                    if($preparedQty == $ds->qty)
                    {
                      $this->orders_model->valid_detail($ds->id);
                      $valid = 1;
                    }
                  }
                }
              }

            }
            else
            {
              $sc = FALSE;
              $message = 'สินค้าไม่ตรงกับออเดอร์';
            }
          }
          else
          {
            $sc = FALSE;
            $message = 'สินค้าไม่นับสต็อก ไม่จำเป็นต้องจัดสินค้านี้';
          }
        }
        else
        {
          $sc = FALSE;
          $message = 'บาร์โค้ดไม่ถูกต้อง กรุณาตรวจสอบ';
        }
      }
      else
      {
        $sc = FALSE;
        $message = 'สถานะออเดอร์ถูกเปลี่ยน ไม่สามารถจัดสินค้าต่อได้';
      }
    }

    echo $sc === TRUE ? json_encode(array("id" => $ds->id, "qty" => $qty, "valid" => $valid)) : $message;
  }



  public function get_barcode($item_code)
  {
    $this->load->model('masters/products_model');
    return $this->products_model->get_barcode($item_code);
  }


  public function get_prepared($order_code, $item_code)
  {
    return $this->prepare_model->get_prepared($order_code, $item_code);
  }




  public function get_prepared_from_zone($order_code, $item_code, $is_count)
  {
    if($is_count == 1)
    {
      $sc = 'ไม่พบข้อมูล';
      $buffer = $this->prepare_model->get_prepared_from_zone($order_code, $item_code);
      if(!empty($buffer))
      {
        $sc = '';
        foreach($buffer as $rs)
        {
          $sc .= $rs->name.' : '.number($rs->qty).'<br/>';
        }
      }
    }
    else
    {
      $sc = 'ไม่นับสต็อก';
    }

  	return $sc;
  }




  public function get_stock_in_zone($item_code, $warehouse = NULL)
  {
    $sc = "ไม่มีสินค้า";
    $this->load->model('stock/stock_model');
    $stock = $this->stock_model->get_stock_in_zone($item_code, $warehouse);
    if(!empty($stock))
    {
      $sc = "";
      foreach($stock as $rs)
      {
        $prepared = $this->prepare_model->get_buffer_zone($item_code, $rs->code);
        $qty = $rs->qty - $prepared;
        if($qty > 0)
        {
          $sc .= $rs->name.' : '.($rs->qty - $prepared).'<br/>';
        }

      }
    }

    return empty($sc) ? 'ไม่พบสินค้า' : $sc;
  }




  //---- สินค้าคงเหลือในโซน ลบด้วย สินค้าที่จัดไปแล้ว
  public function get_stock_zone($zone_code, $item_code)
  {
    $this->load->model('stock/stock_model');
    $this->load->model('masters/warehouse_model');
    $this->load->model('masters/zone_model');

    $zone = $this->zone_model->get($zone_code);
    $wh = $this->warehouse_model->get($zone->warehouse_code);
    $gb_auz = getConfig('ALLOW_UNDER_ZERO');
    $wh_auz = $wh->auz == 1 ? TRUE : FALSE;
    $auz = $gb_auz == 1 ? TRUE : $wh_auz;

    if($auz === TRUE)
    {
      return 1000000;
    }

    //---- สินค้าคงเหลือในโซน
    $stock = $this->stock_model->get_stock_zone($zone_code, $item_code);

    //--- ยอดจัดสินค้าที่จัดออกจากโซนนี้ไปแล้ว แต่ยังไม่ได้ตัด
    $prepared = $this->prepare_model->get_prepared_zone($zone_code, $item_code);


    return $stock - $prepared;

  }


  public function set_zone_label($value)
  {
    $this->input->set_cookie(array('name' => 'showZone', 'value' => $value, 'expire' => 3600 , 'path' => '/'));
  }





  public function finish_prepare()
  {
    $code = $this->input->post('order_code');
    $sc = TRUE;

    $state = $this->orders_model->get_state($code);

    //---	ถ้าสถานะเป็นกำลังจัด (บางทีอาจมีการเปลี่ยนสถานะตอนเรากำลังจัดสินค้าอยู่)
    if( $state == 4)
    {
      $this->db->trans_start();

      //--- mark all detail as valid
      $this->orders_model->valid_all_details($code);

      //---	เปลียน state ของออเดอร์ เป็น รอแพ็คสินค้า
      $this->orders_model->change_state($code, 5);

      $arr = array(
        'order_code' => $code,
        'state' => 5,
        'update_user' => get_cookie('uname')
      );

      //--- add state event
      $this->order_state_model->add_state($arr);

      $this->db->trans_complete();

      if($this->db->trans_status() === FALSE)
      {
        $sc = FALSE;
        $message = "ปิดออเดอร์ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง";
      }

    }

    echo $sc === TRUE ? 'success' : $message;
  }



  public function check_state()
  {
    $code = $this->input->get('order_code');
    $rs = $this->orders_model->get_state($code);
    echo $rs;
  }


  public function pull_order_back()
  {
    $code = $this->input->post('order_code');
    $state = $this->orders_model->get_state($code);
    if($state == 4)
    {
      $arr = array(
        'order_code' => $code,
        'state' => 3,
        'update_user' => get_cookie('uname')
      );

      $this->orders_model->change_state($code, 3);
      $this->order_state_model->add_state($arr);
    }

    echo 'success';
  }


  function remove_buffer($order_code, $item_code)
  {
    $this->load->model('inventory/buffer_model');
    $rs = $this->buffer_model->remove_buffer($order_code, $item_code);
    if($rs === TRUE)
    {
      $this->orders_model->unvalid_detail($order_code, $item_code);
      echo 'success';
    }
    else
    {
      echo 'delete fail';
    }
  }


  public function clear_filter()
  {
    $filter = array(
      'ic_code',
      'ic_customer',
      'ic_user',
      'ic_channels',
      'ic_is_online',
      'ic_role',
      'ic_from_date',
      'ic_to_date',
      'ic_order_by',
      'ic_sort_by',
      'ic_stated',
      'ic_startTime',
      'ic_endTime',
      'ic_item_code',
      'ic_display_name',
      'ic_payment',
      'ic_warehouse'
    );

    clear_filter($filter);
  }


} //--- end class
?>
