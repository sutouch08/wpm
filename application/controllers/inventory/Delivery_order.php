
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_order extends PS_Controller
{
  public $menu_code = 'ICODDO';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = 'PICKPACK';
	public $title = 'Ready To Ship';
  public $filter;
  public $error;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/delivery_order';
    $this->load->model('inventory/delivery_order_model');
    $this->load->model('orders/orders_model');
    $this->load->model('orders/order_state_model');
  }


  public function index()
  {
    $this->load->model('masters/customers_model');
    $this->load->helper('channels');
    $this->load->helper('order');
    $this->load->helper('warehouse');
    $filter = array(
      'code'          => get_filter('code', 'ic_code', ''),
      'customer'      => get_filter('customer', 'ic_customer', ''),
      'user'          => get_filter('user', 'ic_user', ''),
      'role'          => get_filter('role', 'ic_role', ''),
      'channels'      => get_filter('channels', 'ic_channels', ''),
      'from_date'     => get_filter('from_date', 'ic_from_date', ''),
      'to_date'       => get_filter('to_date', 'ic_to_date', ''),
      'sort_by'       => get_filter('sort_by', 'ic_sort_by', ''),
      'order_by'      => get_filter('order_by', 'ic_order_by', ''),
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
		$rows     = $this->delivery_order_model->count_rows($filter, 7);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$orders   = $this->delivery_order_model->get_data($filter, $perpage, $this->uri->segment($segment), 7);

    $filter['orders'] = $orders;

		$this->pagination->initialize($init);
    $this->load->view('inventory/delivery_order/delivery_list', $filter);
  }


  public function confirm_order()
  {
    $sc = TRUE;
    $message = 'Update failed';
    $this->load->model('masters/products_model');
    $this->load->model('inventory/buffer_model');
    $this->load->model('inventory/cancle_model');
    $this->load->model('inventory/movement_model');
    $this->load->helper('discount');
    $code = $this->input->post('order_code');
    if($code)
    {
      $order = $this->orders_model->get($code);

			$date_add = getConfig('ORDER_SOLD_DATE') == 'D' ? $order->date_add : now();

      if($order->role == 'T' OR $order->role == 'Q')
      {
        $this->load->model('inventory/transform_model');
      }

      if($order->role == 'L')
      {
        $this->load->model('inventory/lend_model');
      }

      if($order->state == 7)
      {
        $this->db->trans_start();

        //--- change state
       $this->orders_model->change_state($code, 8);

			 if(empty($order->shipped_date))
			 {
				 $this->orders_model->update($code, array('shipped_date' => now())); //--- update shipped date
			 }


        //--- add state event
        $arr = array(
          'order_code' => $code,
          'state' => 8,
          'update_user' => $this->_user->uname
        );

        $this->order_state_model->add_state($arr);

        //---- รายการทีรอการเปิดบิล
        $bill = $this->delivery_order_model->get_bill_detail($code);

        if(!empty($bill))
        {
          foreach($bill as $rs)
          {
            //--- ถ้ามีรายการที่ไมสำเร็จ ออกจาก loop ทันที
            if($sc === FALSE)
            {
              break;
            }

            //--- ถ้ายอดตรวจ น้อยกว่า หรือ เท่ากับ ยอดสั่ง ใช้ยอดตรวจในการตัด buffer
            //--- ถ้ายอดตวจ มากกว่า ยอดสั่ง ให้ใช้ยอดสั่งในการตัด buffer (บางทีอาจมีการแก้ไขออเดอร์หลังจากมีการตรวจสินค้าแล้ว)
            $sell_qty = ($rs->order_qty >= $rs->qc) ? $rs->qc : $rs->order_qty;

            //--- ดึงข้อมูลสินค้าที่จัดไปแล้วตามสินค้า
            $buffers = $this->buffer_model->get_details($code, $rs->product_code);

            if(!empty($buffers))
            {
              $no = 0;
              foreach($buffers as $rm)
              {
                if($sell_qty > 0)
                {
                //--- ถ้ายอดใน buffer น้อยกว่าหรือเท่ากับยอดสั่งซื้อ (แยกแต่ละโซน น้อยกว่าหรือเท่ากับยอดสั่ง (ซึ่งควรเป็นแบบนี้))
                  $buffer_qty = $rm->qty <= $sell_qty ? $rm->qty : $sell_qty;

                  //--- ทำยอดให้เป็นลบเพื่อตัดยอดออก เพราะใน function  ใช้การบวก
                  $qty = $buffer_qty * (-1);

                  //--- 1. ตัดยอดออกจาก buffer
                  //--- นำจำนวนติดลบบวกกลับเข้าไปใน buffer เพื่อตัดยอดให้น้อยลง

                  if($this->buffer_model->update($rm->order_code, $rm->product_code, $rm->zone_code, $qty) !== TRUE)
                  {
                    $sc = FALSE;
                    $message = 'Update buffer failed';
                    break;
                  }

                  //--- ลดยอด sell qty ลงตามยอด buffer ทีลดลงไป
                  $sell_qty += $qty;

                  //--- 2. update movement
                  $arr = array(
                    'reference' => $order->code,
                    'warehouse_code' => $rm->warehouse_code,
                    'zone_code' => $rm->zone_code,
                    'product_code' => $rm->product_code,
                    'move_in' => 0,
                    'move_out' => $buffer_qty,
                    'date_add' => $date_add
                  );

                  if($this->movement_model->add($arr) === FALSE)
                  {
                    $sc = FALSE;
                    $message = 'Update movement failed';
                    break;
                  }

                  $item = $this->products_model->get($rs->product_code);
                  //--- ข้อมูลสำหรับบันทึกยอดขาย
                  $total_amount = $rs->final_price * $buffer_qty;
                  $totalFrgn = convertFC($total_amount, $rs->rate, 1);

                  $total_cost = $rs->cost * $buffer_qty;

                  $arr = array(
                          'reference' => $order->code,
                          'role'   => $order->role,
                          'payment_code'   => $order->payment_code,
                          'channels_code'  => $order->channels_code,
                          'product_code'  => $rs->product_code,
                          'product_name'  => $item->name,
                          'product_style' => $item->style_code,
                          'cost'  => $rs->cost,
                          'price'  => $rs->price,
                          'sell'  => $rs->final_price,
                          'qty'   => $buffer_qty,
                          'currency' => $rs->currency,
                          'rate' => $rs->rate,
                          'discount_label'  => discountLabel($rs->discount1, $rs->discount2, $rs->discount3),
                          'discount_amount' => ($rs->discount_amount * $buffer_qty),
                          'total_amount'   => $total_amount,
                          'total_cost'   => $total_cost,
                          'totalFrgn' => $totalFrgn,
                          'margin'  =>  $totalFrgn > 0 ? $totalFrgn - $total_cost : $total_amount - $total_cost,
                          'id_policy'   => $rs->id_policy,
                          'id_rule'     => $rs->id_rule,
                          'customer_code' => $order->customer_code,
                          'customer_ref' => $order->customer_ref,
                          'sale_code'   => $order->sale_code,
                          'user' => $order->user,
                          'date_add'  => $date_add, //---- เปลี่ยนไปตาม config ORDER_SOLD_DATE
                          'zone_code' => $rm->zone_code,
                          'warehouse_code'  => $rm->warehouse_code,
                          'update_user' => $this->_user->uname,
                          'budget_code' => $order->budget_code,
                          'empID' => $order->empID,
                          'empName' => $order->empName,
                          'approver' => $order->approver
                  );

                  //--- 3. บันทึกยอดขาย
                  if($this->delivery_order_model->sold($arr) !== TRUE)
                  {
                    $sc = FALSE;
                    $message = 'Insert sale record failed';
                    break;
                  }
                } //--- end if sell_qty > 0
              } //--- end foreach $buffers
            } //--- end if wmpty ($buffers)


            //------ ส่วนนี้สำหรับโอนเข้าคลังระหว่างทำ
            //------ หากเป็นออเดอร์เบิกแปรสภาพ
            if($order->role == 'T' OR $order->role == 'Q')
            {
              //--- ตัวเลขที่มีการเปิดบิล
              $sold_qty = ($rs->order_qty >= $rs->qc) ? $rs->qc : $rs->order_qty;

              //--- ยอดสินค้าที่มีการเชื่อมโยงไว้ในตาราง tbl_order_transform_detail (เอาไว้โอนเข้าคลังระหว่างทำ รอรับเข้า)
              //--- ถ้ามีการเชื่อมโยงไว้ ยอดต้องมากกว่า 0 ถ้ายอดเป็น 0 แสดงว่าไม่ได้เชื่อมโยงไว้
              $trans_list = $this->transform_model->get_transform_product($rs->id);

              if(!empty($trans_list))
              {
                //--- ถ้าไม่มีการเชื่อมโยงไว้
                foreach($trans_list as $ts)
                {
                  //--- ถ้าจำนวนที่เชื่อมโยงไว้ น้อยกว่า หรือ เท่ากับ จำนวนที่ตรวจได้ (ไม่เกินที่สั่งไป)
                  //--- แสดงว่าได้ของครบตามที่ผูกไว้ ให้ใช้ตัวเลขที่ผูกไว้ได้เลย
                  //--- แต่ถ้าได้จำนวนที่ผูกไว้มากกว่าที่ตรวจได้ แสดงว่า ได้สินค้าไม่ครบ ให้ใช้จำนวนที่ตรวจได้แทน
                  $move_qty = $ts->order_qty <= $sold_qty ? $ts->order_qty : $sold_qty;

                  if( $move_qty > 0)
                  {
                    //--- update ยอดเปิดบิลใน tbl_order_transform_detail field sold_qty
                    if($this->transform_model->update_sold_qty($ts->id, $move_qty) === TRUE )
                    {
                      $sold_qty -= $move_qty;
                    }
                    else
                    {
                      $sc = FALSE;
                      $message = 'Failed to update transform item qty';
                    }
                  }
                }
              }
            }


            //--- if lend
            if($order->role == 'L')
            {
              //--- ตัวเลขที่มีการเปิดบิล
              $sold_qty = ($rs->order_qty >= $rs->qc) ? $rs->qc : $rs->order_qty;

              $arr = array(
                'order_code' => $code,
                'product_code' => $rs->product_code,
                'product_name' => $rs->product_name,
                'qty' => $sold_qty,
                'empID' => $order->empID
              );

              if($this->lend_model->add_detail($arr) === FALSE)
              {
                $sc = FALSE;
                $message = 'Failed to update lend item qty';
              }
            }

          } //--- end foreach $bill
        } //--- end if empty($bill)




        //--- เคลียร์ยอดค้างที่จัดเกินมาไปที่ cancle หรือ เคลียร์ยอดที่เป็น 0
        $buffer = $this->buffer_model->get_all_details($code);
        //--- ถ้ายังมีรายการที่ค้างอยู่ใน buffer เคลียร์เข้า cancle
        if(!empty($buffer))
        {
          foreach($buffer as $rs)
          {
            if($rs->qty != 0)
            {
              $arr = array(
                'order_code' => $rs->order_code,
                'product_code' => $rs->product_code,
                'warehouse_code' => $rs->warehouse_code,
                'zone_code' => $rs->zone_code,
                'qty' => $rs->qty,
                'user' => $this->_user->uname
              );

              if($this->cancle_model->add($arr) === FALSE)
              {
                $sc = FALSE;
                $message = 'Insert Cancel item failed';
                break;
              }
            }

            if($this->buffer_model->delete($rs->id) === FALSE)
            {
              $sc = FALSE;
              $message = 'Delete buffer failed';
              break;
            }
          }
        }


        //--- บันทึกขายรายการที่ไม่นับสต็อก
        $bill = $this->delivery_order_model->get_non_count_bill_detail($order->code);

        if(!empty($bill))
        {
          foreach($bill as $rs)
          {
            //--- ข้อมูลสำหรับบันทึกยอดขาย
            $total_amount = $rs->final_price * $rs->qty;
            $totalFrgn = convertFC($total_amount, $rs->rate, 1);

            $arr = array(
                    'reference' => $order->code,
                    'role'   => $order->role,
                    'payment_code'   => $order->payment_code,
                    'channels_code'  => $order->channels_code,
                    'product_code'  => $rs->product_code,
                    'product_name'  => $rs->product_name,
                    'product_style' => $rs->style_code,
                    'cost'  => $rs->cost,
                    'price'  => $rs->price,
                    'sell'  => $rs->final_price,
                    'qty'   => $rs->qty,
                    'currency' => $rs->currency,
                    'rate' => $rs->rate,
                    'discount_label'  => discountLabel($rs->discount1, $rs->discount2, $rs->discount3),
                    'discount_amount' => ($rs->discount_amount * $rs->qty),
                    'total_amount'   => $total_amount,
                    'total_cost'   => $rs->cost * $rs->qty,
                    'totalFrgn' => $totalFrgn,
                    'margin'  => ($rs->final_price * $rs->qty) - ($rs->cost * $rs->qty),
                    'id_policy'   => $rs->id_policy,
                    'id_rule'     => $rs->id_rule,
                    'customer_code' => $order->customer_code,
                    'customer_ref' => $order->customer_ref,
                    'sale_code'   => $order->sale_code,
                    'user' => $order->user,
                    'date_add'  => $date_add, //--- เปลี่ยนตาม Config ORDER_SOLD_DATE
                    'zone_code' => NULL,
                    'warehouse_code'  => NULL,
                    'update_user' => $this->_user->uname,
                    'budget_code' => $order->budget_code,
                    'is_count' => 0,
                    'empID' => $order->empID,
                    'empName' => $order->empName,
                    'approver' => $order->approver
            );

            //--- 3. บันทึกยอดขาย
            if($this->delivery_order_model->sold($arr) !== TRUE)
            {
              $sc = FALSE;
              $message = 'Insert sale record failed';
              break;
            }
          }

        }

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
          $sc = FALSE;
        }
      } //--- end if state == 7
      else
      {
        $sc = FALSE;
      }
    }
    else
    {
      $sc = FALSE;
      $message = 'Order code not found';
    }

    if($sc === TRUE)
    {
      $this->do_export($code);
    }
    else
    {
      //--- ถ้า error
      $this->orders_model->set_exported($code, 3, $message);
    }

    echo $sc === TRUE ? 'success' : $message;
  }


  public function process_delivery()
  {
    $sc = TRUE;
    $error = 0;
    $err_msg = array();

    $list = json_decode($this->input->post('data'));

    $this->load->model('masters/products_model');
    $this->load->model('masters/warehouse_model');
    $this->load->model('masters/zone_model');
    $this->load->model('inventory/prepare_model');
    $this->load->model('inventory/buffer_model');
    $this->load->model('inventory/qc_model');
    $this->load->model('inventory/cancle_model');
    $this->load->model('inventory/movement_model');
    $this->load->helper('discount');

    if( ! empty($list))
    {
      foreach($list as $code)
      {
        $sd = TRUE;

        $order = $this->orders_model->get($code);

        $date_add = getConfig('ORDER_SOLD_DATE') == 'D' ? $order->date_add : now();

        if($order->state == 3)
        {
          $whs = $this->warehouse_model->get($order->warehouse_code);

          if( ! empty($whs->default_zone))
          {
            $zone = $this->zone_model->get($whs->default_zone);

            if( ! empty($zone))
            {
              $this->db->trans_start();

              //--- change state
              $arr = array(
                'state' => 8,
                'shipped_date' => empty($order->shipped_date) ? $date_add : $order->shipped_date
              );

              if( ! $this->orders_model->update($code, $arr))
              {
                $sd = FALSE;
                $error++;
                $err_msg[] = "{$code} : Failed to update order state";
              }

              if($sd === TRUE)
              {
                //--- add state event
                $arr = array(
                  'order_code' => $code,
                  'state' => 8,
                  'update_user' => $this->_user->uname
                );

                if( ! $this->order_state_model->add_state($arr))
                {
                  $sd = FALSE;
                  $error++;
                  $err_msg[] = "{$code} : Failed to create order state logs";
                }
              }

              //---- รายการทีรอการเปิดบิล
              $bill = $this->delivery_order_model->get_order_details($code);


              if( ! empty($bill))
              {
                foreach($bill as $rs)
                {
                  //--- ถ้ามีรายการที่ไมสำเร็จ ออกจาก loop ทันที
                  if($sd === FALSE)
                  {
                    break;
                  }

                  //--- ถ้ายอดตรวจ น้อยกว่า หรือ เท่ากับ ยอดสั่ง ใช้ยอดตรวจในการตัด buffer
                  //--- ถ้ายอดตวจ มากกว่า ยอดสั่ง ให้ใช้ยอดสั่งในการตัด buffer (บางทีอาจมีการแก้ไขออเดอร์หลังจากมีการตรวจสินค้าแล้ว)
                  $sell_qty = $rs->order_qty;

                  if($sell_qty > 0)
                  {
                    //--- add prepare
                    if($sd === TRUE)
                    {
                      if( ! $this->prepare_model->drop_prepare($order->code))
                      {
                        $sd = FALSE;
                        $error++;
                        $err_msg[] = "{$code} : Failed to drop previous picking logs";
                      }
                      else
                      {
                        $prepare = array(
                          'order_code' => $order->code,
                          'product_code' => $rs->product_code,
                          'zone_code' => $zone->code,
                          'qty' => $sell_qty,
                          'user' => $this->_user->uname
                        );

                        if( ! $this->prepare_model->add($prepare))
                        {
                          $sd = FALSE;
                          $error++;
                          $err_msg[] = "{$code} : Failed to create picking logs";
                        }
                      }
                    }

                    //--- add qc
                    if($sd === TRUE)
                    {
                      //--- drop current qc
                  		if( ! $this->qc_model->drop_qc($order->code))
                      {
                        $sd = FALSE;
                        $error++;
                        $err_msg[] = "{$code} : Failed to drop previous packing logs";
                      }
                      else
                      {
                        $qc = array(
                          'order_code' => $order->code,
                          'product_code' => $rs->product_code,
                          'qty' => $sell_qty,
                          'box_id' => NULL,
                          'user' => $this->_user->uname
                        );

                        if( ! $this->qc_model->add($qc))
                        {
                          $sd = FALSE;
                          $error++;
                          $err_msg[] = "{$code} : Failed to create packing logs";
                        }
                      }
                    }

                    //--- add movement
                    if($sd === TRUE)
                    {
                      //--- 2. update movement
                      $arr = array(
                        'reference' => $order->code,
                        'warehouse_code' => $zone->warehouse_code,
                        'zone_code' => $zone->code,
                        'product_code' => $rs->product_code,
                        'move_in' => 0,
                        'move_out' => $sell_qty,
                        'date_add' => $date_add
                      );

                      if( ! $this->movement_model->add($arr))
                      {
                        $sd = FALSE;
                        $error++;
                        $err_msg[] = "{$code} : Failed to create movement logs";
                      }
                    }

                    $item = $this->products_model->get($rs->product_code);
                    //--- ข้อมูลสำหรับบันทึกยอดขาย
                    $total_amount = $rs->final_price * $sell_qty;
                    $totalFrgn = convertFC($total_amount, $rs->rate, 1);
                    $total_cost = $rs->cost * $sell_qty;

                    $arr = array(
                      'reference' => $order->code,
                      'role'   => 'S',
                      'payment_code'   => $order->payment_code,
                      'channels_code'  => $order->channels_code,
                      'product_code'  => $rs->product_code,
                      'product_name'  => $item->name,
                      'product_style' => $item->style_code,
                      'cost'  => $rs->cost,
                      'price'  => $rs->price,
                      'sell'  => $rs->final_price,
                      'qty'   => $sell_qty,
                      'currency' => $rs->currency,
                      'rate' => $rs->rate,
                      'discount_label'  => discountLabel($rs->discount1, $rs->discount2, $rs->discount3),
                      'discount_amount' => ($rs->discount_amount * $sell_qty),
                      'total_amount'   => $total_amount,
                      'total_cost'   => $total_cost,
                      'totalFrgn' => $totalFrgn,
                      'margin'  =>  $totalFrgn > 0 ? $totalFrgn - $total_cost : $total_amount - $total_cost,
                      'id_policy'   => $rs->id_policy,
                      'id_rule'     => $rs->id_rule,
                      'customer_code' => $order->customer_code,
                      'customer_ref' => $order->customer_ref,
                      'sale_code'   => $order->sale_code,
                      'user' => $order->user,
                      'date_add'  => $date_add, //---- เปลี่ยนไปตาม config ORDER_SOLD_DATE
                      'zone_code' => $zone->code,
                      'warehouse_code'  => $zone->warehouse_code,
                      'update_user' => $this->_user->uname,
                      'budget_code' => $order->budget_code,
                      'empID' => $order->empID,
                      'empName' => $order->empName,
                      'approver' => $order->approver
                    );

                    //--- 3. บันทึกยอดขาย
                    if( ! $this->delivery_order_model->sold($arr))
                    {
                      $sd = FALSE;
                      $error++;
                      $err_msg[] = "{$code} : Failed to create sales transection @ {$rs->product_code}";
                    }
                  }
                } //--- end foreach $bill
              } //--- end if empty($bill)

              if($sd === TRUE)
              {
                $this->db->trans_commit();

                if( ! $this->do_export($code))
                {
                  $this->orders_model->set_exported($code, 3, $this->error);
                }
              }
              else
              {
                $this->db->trans_rollback();
              }
            }
            else
            {
              $error++;
              $err_msg[] = "Invalid Bin Code : {$whs->default_zone}";
            }
          }
          else
          {
            $error++;
            $err_msg[] = "{$code} : No Default bin location for warehouse {$order->warehouse_code}";
          }
        }
        else
        {
          $error++;
          $err_msg[] = "{$code} : Invalid order state. Orders state should be 'waiting to pick'";
        }
      } //--- end foreach list
    } //-- end if ! empty list

    $message = "";

    if($error > 0)
    {
      if( ! empty($err_msg))
      {
        foreach($err_msg as $ems)
        {
          $message .= $ems."<br/>";
        }
      }
    }

    $arr = array(
      'status' => $error == 0 ? 'success' : 'warning',
      'message' => $error == 0 ? 'success' : $message
    );

    echo json_encode($arr);
  }


  public function view_detail($code)
  {
    $this->title = "Ready to ship";
    $this->load->model('masters/customers_model');
    $this->load->model('inventory/qc_model');
		$this->load->model('masters/warehouse_model');
		$this->load->model('masters/channels_model');
		$this->load->model('masters/payment_methods_model');
    $this->load->helper('order');
    $this->load->helper('discount');

    $order = $this->orders_model->get($code);
    $order->customer_name = $this->customers_model->get_name($order->customer_code);
		$order->warehouse_name = $this->warehouse_model->get_name($order->warehouse_code);
		$order->channels_name = $this->channels_model->get_name($order->channels_code);
		$order->payment_name = $this->payment_methods_model->get_name($order->payment_code);

    if($order->role == 'C' OR $order->role == 'N' OR $order->role == 'L')
    {
      $this->load->model('masters/zone_model');
      $order->zone_name = $this->zone_model->get_name($order->zone_code);
    }

    $details = $this->delivery_order_model->get_billed_detail($code);
    $box_list = $this->qc_model->get_box_list($code);
    $ds['order'] = $order;
    $ds['details'] = $details;
    $ds['box_list'] = $box_list;
    $this->load->view('inventory/delivery_order/bill_detail', $ds);
  }



	public function update_shipped_date()
	{
		$sc = TRUE;
		$code = $this->input->post('order_code');
		$date = db_date($this->input->post('shipped_date'), FALSE);

		$arr = array(
			'shipped_date' => $date,
			'update_user' => $this->_user->uname
		);

		if( ! $this->orders_model->update($code, $arr))
		{
			$sc = FALSE;
			$this->error = "Update Shipped data failed";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}


  public function get_state()
  {
    $code = $this->input->get('order_code');
    $state = $this->orders_model->get_state($code);
    echo $state;
  }


  private function export_order($code)
  {
    $sc = TRUE;
    $this->load->library('export');
    if(! $this->export->export_order($code))
    {
      $sc = FALSE;
      $this->error = trim($this->export->error);
    }

    return $sc;
  }


  private function export_transfer_order($code)
  {
    $sc = TRUE;
    $this->load->library('export');
    if(! $this->export->export_transfer_order($code))
    {
      $sc = FALSE;
      $this->error = trim($this->export->error);
    }

    return $sc;
  }


  private function export_transfer_draft($code)
  {
    $sc = TRUE;
    $this->load->library('export');
    if(! $this->export->export_transfer_draft($code))
    {
      $sc = FALSE;
      $this->error = trim($this->export->error);
    }

    return $sc;
  }


  private function export_transform($code)
  {
    $sc = TRUE;
    $this->load->library('export');
    if(! $this->export->export_transform($code))
    {
      $sc = FALSE;
      $this->error = trim($this->export->error);
    }

    return $sc;
  }


  //--- manual export by client
  public function do_export($code)
  {
    $order = $this->orders_model->get($code);
    $sc = TRUE;
    if(!empty($order))
    {
      switch($order->role)
      {
        case 'C' : //--- Consign (SO)
          $sc = $this->export_order($code);
          break;

        case 'L' : //--- Lend
          $sc = $this->export_transfer_order($code);
          break;

        case 'N' : //--- Consign (TR)
          $sc = $this->export_transfer_draft($code);
          break;

        case 'P' : //--- Sponsor
          $sc = $this->export_order($code);
          break;

        case 'Q' : //--- Transform for stock
          $sc = $this->export_transform($code);
          break;

        case 'S' : //--- Sale order
          $sc = $this->export_order($code);
          break;

        case 'T' : //--- Transform for sell
          $sc = $this->export_transform($code);
          break;

        case 'U' : //--- Support
          $sc = $this->export_order($code);
          break;

        default : ///--- sale order
          $sc = $this->export_order($code);
          break;
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document No not found : {$code}";
    }

    return $sc;
  }



  public function manual_export($code)
  {
    $rs = $this->do_export($code);
    echo $rs === TRUE ? 'success' : $this->error;
  }



  public function clear_filter()
  {
    $filter = array(
      'ic_code',
      'ic_customer',
      'ic_user',
      'ic_role',
      'ic_channels',
      'ic_from_date',
      'ic_to_date',
      'ic_sort_by',
      'ic_order_by',
      'ic_warehouse'
    );

    clear_filter($filter);
  }

} //--- end class
?>
