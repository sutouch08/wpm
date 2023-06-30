<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transform_stock extends PS_Controller
{
  public $menu_code = 'ICTRFS';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = 'REQUEST';
	public $title = 'เบิกแปรสภาพ(สต็อก)';
  public $filter;
  public $role = 'Q';
  public $isClosed = FALSE;
	public $isAPI;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/transform_stock';
    $this->load->model('inventory/transform_model');
    $this->load->model('orders/orders_model');
    $this->load->model('masters/customers_model');
    $this->load->model('orders/order_state_model');
    $this->load->model('masters/product_tab_model');
    $this->load->model('stock/stock_model');
    $this->load->model('masters/product_style_model');
    $this->load->model('masters/products_model');

    $this->load->helper('order');
    $this->load->helper('customer');
    $this->load->helper('users');
    $this->load->helper('state');
    $this->load->helper('product_images');
    $this->load->helper('transform');
    $this->load->helper('warehouse');

		$this->isAPI = is_true(getConfig('WMS_API'));
  }


  public function index()
  {

		$filter = array(
      'code'      => get_filter('code', 'transform_code', ''),
      'customer'  => get_filter('customer', 'transform_customer', ''),
      'user'      => get_filter('user', 'transform_user', ''),
      'user_ref'  => get_filter('user_ref', 'transform_user_ref', ''),
			'notSave' => get_filter('notSave', 'transform_notSave', NULL),
      'onlyMe' => get_filter('onlyMe', 'transform_onlyMe', NULL),
      'isExpire' => get_filter('isExpire', 'transform_isExpire', NULL),
      'from_date' => get_filter('fromDate', 'transform_fromDate', ''),
      'to_date'   => get_filter('toDate', 'transform_toDate', ''),
      'isApprove' => get_filter('isApprove', 'transform_isApprove', 'all'),
			'warehouse' => get_filter('warehouse', 'transform_warehouse', ''),
			'wms_export' => get_filter('wms_export', 'transform_wms_export', 'all'),
      'sap_status' => get_filter('sap_status', 'transform_sap_status', 'all')
    );

		$state = array(
      '1' => get_filter('state_1', 'transform_state_1', 'N'),
      '2' => get_filter('state_2', 'transform_state_2', 'N'),
      '3' => get_filter('state_3', 'transform_state_3', 'N'),
      '4' => get_filter('state_4', 'transform_state_4', 'N'),
      '5' => get_filter('state_5', 'transform_state_5', 'N'),
      '6' => get_filter('state_6', 'transform_state_6', 'N'),
      '7' => get_filter('state_7', 'transform_state_7', 'N'),
      '8' => get_filter('state_8', 'transform_state_8', 'N'),
      '9' => get_filter('state_9', 'transform_state_9', 'N')
    );

    $state_list = array();

    $button = array();

    for($i =1; $i <= 9; $i++)
    {
    	if($state[$i] === 'Y')
    	{
    		$state_list[] = $i;
    	}

      $btn = 'state_'.$i;
      $button[$btn] = $state[$i] === 'Y' ? 'btn-info' : '';
    }

    $button['not_save'] = empty($filter['notSave']) ? '' : 'btn-info';
    $button['only_me'] = empty($filter['onlyMe']) ? '' : 'btn-info';
    $button['is_expire'] = empty($filter['isExpire']) ? '' : 'btn-info';


    $filter['state_list'] = empty($state_list) ? NULL : $state_list;

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

    $role     = 'Q'; //--- Q = แปรสภาพเพื่อสต็อก
		$segment  = 4; //-- url segment
		$rows     = $this->orders_model->count_rows($filter, $role);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$orders   = $this->orders_model->get_data($filter, $perpage, $this->uri->segment($segment), $role);
    $ds       = array();
    if(!empty($orders))
    {
      foreach($orders as $rs)
      {
        $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
        $rs->total_amount  = $this->orders_model->get_order_total_amount($rs->code);
        $rs->state_name    = get_state_name($rs->state);
        $ds[] = $rs;
      }
    }

    $filter['orders'] = $ds;
		$filter['state'] = $state;
    $filter['btn'] = $button;

		$this->pagination->initialize($init);
    $this->load->view('transform/transform_list', $filter);
  }



  public function add_new()
  {
    $this->load->view('transform/transform_add');
  }



  public function add()
  {
    if($this->input->post('customerCode'))
    {
      $this->load->model('masters/warehouse_model');

      $book_code = getConfig('BOOK_CODE_TRANSFORM_STOCK');
      $date_add = db_date($this->input->post('date'));
      if($this->input->post('code'))
      {
        $code = $this->input->post('code');
      }
      else
      {
        $code = $this->get_new_code($date_add);
      }
      $role = 'Q'; //--- T = เบิกแปรสภาพ
      $zone_code = $this->input->post('zoneCode');
      $wh = $this->warehouse_model->get($this->input->post('warehouse'));

      $ds = array(
        'code' => $code,
        'role' => $role,
        'bookcode' => $book_code,
        'customer_code' => $this->input->post('customerCode'),
        'user' => $this->_user->uname,
        'remark' => $this->input->post('remark'),
        'user_ref' => $this->input->post('empName'),
        'zone_code' => $zone_code,
        'warehouse_code' => $wh->code,
				'is_wms' => $wh->is_wms
      );

      if($this->orders_model->add($ds) === TRUE)
      {
        $this->transform_model->add($code);
        $arr = array(
          'order_code' => $code,
          'state' => 1,
          'update_user' => $this->_user->uname
        );

        $this->order_state_model->add_state($arr);

        redirect($this->home.'/edit_detail/'.$code);
      }
      else
      {
        set_error('เพิ่มเอกสารไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        redirect($this->home.'/add_new');
      }
    }
    else
    {
      set_error('ไม่พบข้อมูลลูกค้า กรุณาตรวจสอบ');
      redirect($this->home.'/add_new');
    }
  }



  public function edit_order($code, $approve_view = NULL)
  {
    $this->load->helper('print');
    $this->load->helper('transform');
    $this->load->model('masters/zone_model');
    $this->load->model('approve_logs_model');
		$this->load->model('address/address_model');
		$this->load->helper('sender');

    $ds = array();
    $rs = $this->orders_model->get($code);
    if(!empty($rs))
    {
      $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
      $rs->total_amount  = $this->orders_model->get_order_total_amount($rs->code);
      $rs->user          = $this->user_model->get_name($rs->user);
      $rs->state_name    = get_state_name($rs->state);
      $rs->zone_name     = $this->zone_model->get_name($rs->zone_code);
    }

    $state = $this->order_state_model->get_order_state($code);
    $ost = array();
    if(!empty($state))
    {
      foreach($state as $st)
      {
        $ost[] = $st;
      }
    }

    $details = $this->orders_model->get_order_details($code);
    if(!empty($details))
    {
      foreach($details as $rd)
      {
        $rd->hasTransformProduct = $this->transform_model->hasTransformProduct($rd->id);
        $rd->transform_product = $this->transform_model->get_transform_product($rd->id);
        $rd->sum_transform_product_qty = $this->transform_model->get_sum_transform_product_qty($rd->id);
      }
    }

    $ds['state'] = $ost;
    $ds['order'] = $rs;
    $ds['details'] = $details;
		$ds['addr'] = $this->address_model->get_ship_to_address($rs->customer_code);
		$ds['cancle_reason'] = ($rs->state == 9 ? $this->orders_model->get_cancle_reason($code) : NULL);
    $ds['approve_logs'] = $this->approve_logs_model->get($code);
    $ds['approve_view'] = $approve_view;
    $this->isClosed = $this->transform_model->is_closed($code);
    $this->load->view('transform/transform_edit', $ds);
  }



  public function update_order()
  {
    $sc = TRUE;

    if($this->input->post('order_code'))
    {
			$this->load->model('masters/warehouse_model');

      $code = $this->input->post('order_code');
      $customer_code = $this->input->post('customer_code');
      $zone_code = $this->input->post('zone_code');
      $wh = $this->warehouse_model->get($this->input->post('warehouse'));
      $user_ref = $this->input->post('user_ref');
      $remark = get_null($this->input->post('remark'));

      $ds = array(
        'customer_code' => $this->input->post('customer_code'),
        'date_add' => db_date($this->input->post('date_add')),
        'user_ref' => $this->input->post('user_ref'),
        'remark' => $this->input->post('remark'),
        'status' => 0,
        'zone_code' => $zone_code,
        'warehouse_code' => $wh->code,
        'remark' => $remark,
				'is_wms' => $wh->is_wms,
				'id_address' => NULL,
				'id_sender' => NULL
      );

      $rs = $this->orders_model->update($code, $ds);

      if($rs === FALSE)
      {
        $sc = FALSE;
        $message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
      }
    }
    else
    {
      $sc = FALSE;
      $message = 'ไม่พบเลขที่เอกสาร';
    }

    echo $sc === TRUE ? 'success' : $message;
  }



  public function edit_detail($code)
  {
    $this->load->helper('print');
    $this->load->helper('transform');
    $this->load->helper('product_tab');
    $this->load->model('masters/zone_model');

    $ds = array();
    $rs = $this->orders_model->get($code);
    if(!empty($rs))
    {
      $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
      $rs->total_amount  = $this->orders_model->get_order_total_amount($rs->code);
      $rs->user          = $this->user_model->get_name($rs->user);
      $rs->state_name    = get_state_name($rs->state);
      $rs->zone_name    = $this->zone_model->get_name($rs->zone_code);
    }

    $state = $this->order_state_model->get_order_state($code);
    $details = $this->orders_model->get_order_details($code);
    if(!empty($details))
    {
      foreach($details as $rd)
      {
        $rd->hasTransformProduct = $this->transform_model->hasTransformProduct($rd->id);
        $rd->transform_product = $this->transform_model->get_transform_product($rd->id);
        $rd->sum_transform_product_qty = $this->transform_model->get_sum_transform_product_qty($rd->id);
      }
    }

    $ds['order'] = $rs;
    $ds['details'] = $details;
    $this->isClosed = $this->transform_model->is_closed($code);
    $this->load->view('transform/transform_edit_detail', $ds);

  }



  public function save($code)
  {
    $sc = TRUE;
    $order = $this->orders_model->get($code);

		if(empty($order->id_address))
		{
			$this->load->model('address/address_model');
			$id_address = NULL;

			if(!empty($order->customer_ref))
			{
				$id_address = $this->address_model->get_shipping_address_id_by_code($order->customer_ref);
			}
			else
			{
				$id_address = $this->address_model->get_default_ship_to_address_id($order->customer_code);
			}

			if(!empty($id_address))
			{
				$arr = array(
					'id_address' => $id_address
				);

				$this->orders_model->update($order->code, $arr);
			}
		}


		if(empty($order->id_sender))
		{
			$this->load->model('masters/sender_model');
			$id_sender = NULL;

			$sender = $this->sender_model->get_customer_sender_list($order->customer_code);

			if(!empty($sender))
			{
				if(!empty($sender->main_sender))
				{
					$id_sender = $sender->main_sender;
				}
			}

			if(!empty($id_sender))
			{
				$arr = array(
					'id_sender' => $id_sender
				);

				$this->orders_model->update($order->code, $arr);
			}
		}

    if($sc === TRUE)
    {
      $rs = $this->orders_model->set_status($code, 1);
      if($rs === FALSE)
      {
        $sc = FALSE;
        $message = 'บันทึกออเดอร์ไม่สำเร็จ';
      }
    }

    echo $sc === TRUE ? 'success' : $message;
  }


  public function add_transform_product()
  {
    if($this->input->post('id_order_detail'))
    {
      $this->load->model('inventory/invoice_model');

      $order_code = $this->input->post('order_code');
      $id_order_detail  = $this->input->post('id_order_detail');
      $original_product = trim($this->input->post('original_product'));
      $transform_product = trim($this->input->post('transform_product'));
      $qty = intval($this->input->post('qty'));

      $order = $this->orders_model->get($order_code);
      $sold = $this->invoice_model->get_item_sold_qty($order_code, $original_product);
      $sold_qty = $sold > $qty ? $qty : $sold;
      $valid = $order->state == 8 ? 1 : 0;

      $arr = array(
        'order_code' => $order_code,
        'id_order_detail' => $id_order_detail,
        'original_code' => trim($original_product),
        'product_code' => trim($transform_product),
        'order_qty' => $qty,
        'sold_qty' => $sold_qty,
        'valid' => $valid
        );

      $rs = $this->transform_model->update($arr);

      if( $rs === TRUE)
      {
        $rd = $this->transform_model->get_transform_product($id_order_detail);
        $ra  = getTransformProducts($rd);
        $ra .= '<input type="hidden" id="transform-qty-'.$id_order_detail.'" value="'.$this->transform_model->get_sum_transform_product_qty($id_order_detail).'" />';

        $sc = json_encode(array('data' => $ra));
      }
      else
      {
        $sc = 'ทำรายการไม่สำเร็จ';
      }
    }
    else
    {
      $sc = 'ไม่พบข้อมูลสินค้า';
    }
    echo $sc;
  }




  public function remove_transform_product()
  {
    if($this->input->post('id_order_detail'))
    {
      $id_order_detail = $this->input->post('id_order_detail');
      $product_code = $this->input->post('product_code');

      $rs = $this->transform_model->remove_transform_product($id_order_detail, $product_code);
      if($rs === TRUE)
      {
        $rd = $this->transform_model->get_transform_product($id_order_detail);
        $ra  = getTransformProducts($rd);
        $ra .= '<input type="hidden" id="transform-qty-'.$id_order_detail.'" value="'.$this->transform_model->get_sum_transform_product_qty($id_order_detail).'" />';

        $sc = json_encode(array('data' => $ra));
      }
      else
      {
        $sc = 'ทำรายการไม่สำเร็จ';
      }
    }
    else
    {
      $sc = 'ไม่พบข้อมูลสินค้า';
    }

    echo $sc;
  }



  public function remove_transform_detail()
  {
    $id_order_detail = $this->input->post('id_order_detail');
    if($this->transform_model->remove_transform_detail($id_order_detail) === TRUE)
    {
      $this->db->set('not_return', 1)->where('id', $id_order_detail)->update('order_details');
      echo 'success';
    }
    else
    {
      echo 'ลบการเชื่อมโยงสินค้าไม่สำเร็จ';
    }
  }


  public function set_not_return($id_order_detail, $val)
  {
    $sc = TRUE;

    if( ! $this->db->set('not_return', $val)->where('id', $id_order_detail)->update('order_details'))
    {
      $sc = FALSE;
      $this->error = "Update failed";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function is_exists_connected()
  {
    $id_order_detail = $this->input->get('id_order_detail');
    if($this->transform_model->hasTransformProduct($id_order_detail) === TRUE)
    {
      echo 'exists';
    }
    else
    {
      echo 'not_exists';
    }
  }





  public function get_detail_table($code)
  {
    $this->load->helper('print');
    $this->load->helper('transform');
    $this->load->helper('product_tab');

    $sc = "no data found";
  	$order = $this->orders_model->get($code);

    $details = $this->orders_model->get_order_details($code);
    if(!empty($details))
    {
      $no = 1;
      $total_qty = 0;
      $ds = array();

      foreach($details as $rs)
      {
        $hasTransformProduct = $this->transform_model->hasTransformProduct($rs->id);
        $transform_product = $this->transform_model->get_transform_product($rs->id);
        //$checked = $hasTransformProduct === FALSE ? 'checked' : '';
        $arr = array(
          'id' => $rs->id,
          'no' => $no,
          'imageLink' => get_product_image($rs->product_code, 'mini'),
          'productCode' => $rs->product_code,
          'productName' => $rs->product_name,
          'qty' => number($rs->qty),
          'transProduct' => $transform_product === FALSE ? '' : getTransformProducts($transform_product, $order->state, $order->is_expired),
          'trans_qty' => $this->transform_model->get_sum_transform_product_qty($rs->id),
          'checkbox' => "", //$checked,
          'button' => 'show' //$hasTransformProduct === FALSE ? '' : 'show'
        );

        array_push($ds, $arr);
        $total_qty += $rs->qty;
        $no++;
      }

      $arr = array('total_qty' => number($total_qty));
      array_push($ds, $arr);

      $sc = json_encode($ds);
    }

    echo $sc;
  }

  public function get_new_code($date)
  {
    $date = $date == '' ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_TRANSFORM_STOCK');
    $run_digit = getConfig('RUN_DIGIT_TRANSFORM_STOCK');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->orders_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }



  public function set_never_expire()
  {
    $code = $this->input->post('order_code');
    $option = $this->input->post('option');
    $rs = $this->orders_model->set_never_expire($code, $option);
    echo $rs === TRUE ? 'success' : 'ทำรายการไม่สำเร็จ';
  }


  public function un_expired()
  {
    $code = $this->input->post('order_code');
    $rs = $this->orders_model->un_expired($code);
    echo $rs === TRUE ? 'success' : 'ทำรายการไม่สำเร็จ';
  }

  public function clear_filter()
  {
		$filter = array(
      'transform_code',
      'transform_customer',
      'transform_user',
      'transform_user_ref',
      'transform_fromDate',
      'transform_toDate',
      'transform_isApprove',
			'transform_warehouse',
			'transform_wms_export',
      'transform_sap_status',
      'transform_notSave',
      'transform_onlyMe',
      'transform_isExpire',
      'transform_state_1',
      'transform_state_2',
      'transform_state_3',
      'transform_state_4',
      'transform_state_5',
      'transform_state_6',
      'transform_state_7',
      'transform_state_8',
      'transform_state_9'
    );


    clear_filter($filter);
  }
}
?>
