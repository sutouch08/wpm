<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_order extends PS_Controller
{
  public $menu_code = 'ICRTOR';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = 'RETURN';
	public $title = 'Goods return';
  public $filter;
  public $error;
	public $wms;
	public $isAPI;
  public $segment = 4;
  public $required_remark = 0;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/return_order';
    $this->load->model('inventory/return_order_model');
    $this->load->model('masters/warehouse_model');
    $this->load->model('masters/zone_model');
    $this->load->model('masters/customers_model');
    $this->load->model('masters/products_model');

		$this->isAPI = is_true(getConfig('WMS_API'));
  }


  public function index()
  {
		$this->load->helper('warehouse');
    $filter = array(
      'code'    => get_filter('code', 'sm_code', ''),
      'invoice' => get_filter('invoice', 'sm_invoice', ''),
      'customer_code' => get_filter('customer_code', 'sm_customer_code', ''),
      'from_date' => get_filter('from_date', 'sm_from_date', ''),
      'to_date' => get_filter('to_date', 'sm_to_date', ''),
      'status' => get_filter('status', 'sm_status', 'all'),
      'approve' => get_filter('approve', 'sm_approve', 'all'),
			'zone' => get_filter('zone', 'sm_zone', ''),
			'api' => get_filter('api', 'sm_api', 'all'),
      'must_accept' => get_filter('must_accept', 'sm_must_accept', 'all'),
      'sap' => get_filter('sap', 'sm_sap', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows = $this->return_order_model->count_rows($filter);
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$document = $this->return_order_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

    if(!empty($document))
    {
      foreach($document as $rs)
      {
        $rs->qty = $this->return_order_model->get_sum_qty($rs->code);
        $rs->amount = $this->return_order_model->get_sum_amount($rs->code);
      }
    }

    $filter['docs'] = $document;
		$this->pagination->initialize($init);
    $this->load->view('inventory/return_order/return_order_list', $filter);
  }


  public function add_details($code)
  {
    $sc = TRUE;
    $data = json_decode(file_get_contents('php://input'));

    if( ! empty($data))
    {
      //--- start transection
      $this->db->trans_begin();

      $doc = $this->return_order_model->get($code);

      if(!empty($doc))
      {
        $vat_rate = getConfig('SALE_VAT_RATE'); //--- 0.07

        //--- drop old detail
        $this->return_order_model->drop_details($code);

        foreach($data as $rs)
        {
          if($rs->qty > 0)
          {
            $rate = $rs->rate > 0 ? $rs->rate : 1;
            $disc_amount = $rs->discount == 0 ? 0 : $rs->price * ($rs->discount * 0.01);
            $amount = $rs->qty * ($rs->price - $disc_amount);
            $arr = array(
              'return_code' => $code,
              'invoice_code' => $doc->invoice,
              'order_code' => get_null($rs->order_code),
              'product_code' => $rs->product_code,
              'product_name' => $rs->product_name,
              'sold_qty' => $rs->inv_qty,
              'qty' => $rs->qty,
              'currency' => $rs->currency,
              'rate' => $rate,
              'receive_qty' => $rs->qty,
              'price' => $rs->price,
              'discount_percent' => $rs->discount,
              'amount' => $amount,
              'totalFrgn' => convertFC($amount, $rate, 1),
              'vat_amount' => get_vat_amount($amount, $vat_rate)
            );

            if($this->return_order_model->add_detail($arr) === FALSE)
            {
              $sc = FALSE;
              $this->error = 'Add item failed';
              break;
            }
          } //--- end if qty > 0
        } //--- end foreach

        if( $sc === TRUE)
        {
          if( ! $this->return_order_model->set_status($code, 1))
          {
            $sc = FALSE;
            $this->error = "Save document failed";
          }
        }

        if($sc === TRUE)
        {
          $this->db->trans_commit();
        }
        else
        {
          $this->db->trans_rollback();
        }
      }
      else
      {
        //--- empty document
        $sc = FALSE;
        set_error('Document not found.');
      }
    }
    else
    {
      $sc = FALSE;
      set_error('No return items found.');
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error
    );

    echo json_encode($arr);
  }

  
  public function delete_detail($id)
  {
    $rs = $this->return_order_model->delete_detail($id);
    echo $rs === TRUE ? 'success' : 'Delete item failed';
  }


  public function unsave($code)
  {
    $sc = TRUE;

    if($this->pm->can_edit)
    {
      $docNum = $this->return_order_model->get_sap_doc_num($code);
      if(empty($docNum))
      {
        $arr = array(
          'status' => 0,
          'is_approve' => 0,
          'approver' => NULL,
          'inv_code' => NULL
        );

        if( ! $this->return_order_model->update($code, $arr))
        {
          $sc = FALSE;
          $this->error = 'Failed to cancel save.';
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Please cancel the document Debit No. {$docNum} in SAP before canceling the save.";
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = 'You do not have the right to cancel the recording.';
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function approve($code)
  {
    $this->load->model('inventory/movement_model');

		$sc = TRUE;

    if($this->pm->can_approve)
    {
      $this->load->model('approve_logs_model');
			$doc = $this->return_order_model->get($code);
			if(!empty($doc))
			{
				if($doc->status == 1 ) //--- status บันทึกแล้วเท่านั้น
				{
          $this->db->trans_begin();

          if( ! $this->return_order_model->approve($code))
          {
            $sc = FALSE;
            $this->error = "Approve Faiiled";
          }

					if($sc === TRUE)
					{
            $this->approve_logs_model->add($code, 1, $this->_user->uname);

            if($doc->must_accept == 1)
            {
              $this->return_order_model->set_status($code, 4);
            }
            else
            {
              $arr = array('shipped_date' => now());
              $this->return_order_model->update($code, $arr);

              $details = $this->return_order_model->get_details($doc->code);

              if(!empty($details))
              {
                //---- add movement
                if($this->isAPI === FALSE OR $doc->is_wms == 0 OR $doc->api == 0)
                {
                  foreach($details as $rs)
                  {
                    if($sc === FALSE) { break; }

                    $arr = array(
                      'reference' => $doc->code,
                      'warehouse_code' => $doc->warehouse_code,
                      'zone_code' => $doc->zone_code,
                      'product_code' => $rs->product_code,
                      'move_in' => $rs->receive_qty,
                      'date_add' => db_date($doc->date_add, TRUE)
                    );

                    if($this->movement_model->add($arr) === FALSE)
                    {
                      $sc = FALSE;
                      $this->error = 'Failed to save movement';
                    }
                  }

                  if($sc === TRUE)
                  {
                    $this->return_order_model->update($code, array('is_complete' => 1));
                  }
                }
              }
              else
              {
                $sc = FALSE;
                $this->error = "Return item not found";
              }
            }
					}

          if($sc === TRUE)
          {
            $this->db->trans_commit();
          }
          else
          {
            $this->db->trans_rollback();
          }

          if($sc === TRUE)
          {
            if($doc->must_accept == 0)
            {
              if($this->isAPI === TRUE && $doc->is_wms == 1 && $doc->api == 1)
              {
                $this->wms = $this->load->database('wms', TRUE);
                $this->load->library('wms_receive_api');
                $exported = $this->wms_receive_api->export_return_order($doc, $details);  //--- send data to WMS ;

                if($exported)
                {
                  $this->return_order_model->set_status($code, 3); //--- on wms process;
                }
                else
                {
                  $sc = FALSE;
                  $this->error = "approved successfully but failed to send data to WMS, please refresh the screen and press send again";
                }
              }
              else
              {
                $export = $this->do_export($code);

                if(! $export)
                {
                  $sc = FALSE;
                  $this->error = "approved successfully but failed to send data to SAP, please refresh the screen and press submit again.";
                }
              }
            }
          }
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid status";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = 'The document number is invalid.';
			}
    }
    else
    {
			$sc = FALSE;
			$this->error = 'You do not have permission to approve';
    }

		echo $sc === TRUE ? 'success' : $this->error;
  }



  public function accept_confirm()
  {
    $this->load->model('inventory/movement_model');
		$sc = TRUE;

    $code = $this->input->post('code');
    $remark = trim($this->input->post('accept_remark'));

    $doc = $this->return_order_model->get($code);

    if(!empty($doc))
    {
      $date_add = getConfig('ORDER_SOLD_DATE') === 'D' ? $doc->date_add : now();

      if($doc->status == 4 )
      {
        $status = $this->isAPI === TRUE && $doc->is_wms == 1 ? 3 : 1;
        $ship_date = $this->isAPI === TRUE && $doc->is_wms == 1 ? NULL : now();
        $arr = array(
          "status" => $status,
          "shipped_date" => $ship_date,
          "is_accept" => 1,
          "accept_by" => $this->_user->uname,
          "accept_on" => now(),
          "accept_remark" => $remark
        );

        $this->db->trans_begin();

        if( ! $this->return_order_model->update($code, $arr))
        {
          $sc = FALSE;
          $this->error = "Update Acception Failed";
        }

        if($sc === TRUE)
        {
          $details = $this->return_order_model->get_details($doc->code);

          if( ! empty($details))
          {
            if($this->isAPI == FALSE OR $doc->is_wms == 0 OR $doc->api == 0)
            {
              foreach($details as $rs)
              {
                if($sc === FALSE)
                {
                  break;
                }

                $arr = array(
                  'reference' => $doc->code,
                  'warehouse_code' => $doc->warehouse_code,
                  'zone_code' => $doc->zone_code,
                  'product_code' => $rs->product_code,
                  'move_in' => $rs->receive_qty,
                  'date_add' => db_date($doc->date_add, TRUE)
                );

                if($this->movement_model->add($arr) === FALSE)
                {
                  $sc = FALSE;
                  $this->error = 'Failed to save movement';
                }
              }

              if($sc === TRUE)
              {
                $this->return_order_model->update($code, array('is_complete' => 1));
              }
            }

          }
          else
          {
            $sc = FALSE;
            $this->error = "Return item not found";
          }
        }

        if($sc === TRUE)
        {
          $this->db->trans_commit();
        }
        else
        {
          $this->db->trans_rollback();
        }

        if($sc === TRUE)
        {
          if($this->isAPI === TRUE && $doc->is_wms == 1 && $doc->api == 1)
          {
            $this->wms = $this->load->database('wms', TRUE);
            $this->load->library('wms_receive_api');
            $exported = $this->wms_receive_api->export_return_order($doc, $details);  //--- send data to WMS ;

            if($exported)
            {
              $this->return_order_model->set_status($code, 3); //--- on wms process;
            }
            else
            {
              $sc = FALSE;
              $this->error = "Successful confirmation, but failed to send data to WMS, please refresh the screen and press send again.";
            }
          }
          else
          {
            $export = $this->do_export($code);

            if(! $export)
            {
              $sc = FALSE;
              $this->error = "approved successfully but failed to send data to SAP, please refresh the screen and press submit again.";
            }
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = 'Invalid document number';
    }


		echo $sc === TRUE ? 'success' : $this->error;
  }



  public function unapprove($code)
  {
		$sc = TRUE;

    if($this->pm->can_approve)
    {
      //--- check document in SAP
      $sap = $this->return_order_model->get_sap_return_order($code);

      if(empty($sap))
      {
        //-- delete temp data
        $temp = $this->return_order_model->get_middle_return_doc($code);

        if( ! empty($temp))
        {
          foreach($temp as $tmp)
          {
            $this->return_order_model->drop_middle_exits_data($tmp->DocEntry);
          }
        }

        $this->load->model('inventory/movement_model');
        $this->load->model('approve_logs_model');

        $arr = array(
          'status' => 1,
          'is_approve' => 0,
          'is_accept' => NULL,
          'accept_on' => NULL,
          'accept_by' => NULL,
          'accept_remark' => NULL
        );


        if($this->return_order_model->update($code, $arr))
        {
          $this->approve_logs_model->add($code, 0, $this->_user->uname);

          $this->movement_model->drop_movement($code);
        }
        else
        {
					$sc = FALSE;
          $this->error = 'Failed to cancel the approval of the document.';
        }
      }
			else
			{
				$sc = FALSE;
				$this->error = "The document already in SAP. Please cancel the document in SAP before cancel the document.";
			}
    }
    else
    {
			$sc = FALSE;
      $this->error = 'You do not have permission to approve';
    }

		echo $sc === TRUE ? 'success' : $this->error;
  }


  public function add_new()
  {
    $this->load->view('inventory/return_order/return_order_add');
  }


  public function add()
  {
    $sc = TRUE;

    if($this->input->post('date_add'))
    {
      $date_add = db_date($this->input->post('date_add'), TRUE);
      $invoice = trim($this->input->post('invoice'));
      $customer_code = trim($this->input->post('customer_code'));
			$zone_code = trim($this->input->post('zone_code'));
      $remark = trim($this->input->post('remark'));

			$zone = $this->zone_model->get($zone_code);

      $iv = $this->return_order_model->get_invoice($invoice, $customer_code);

      if( ! empty($iv))
      {
        if($iv->CANCELED == 'N')
        {
          $code = $this->get_new_code($date_add);

          $must_accept = empty($zone->user_id) ? 0 : 1;

          $arr = array(
            'code' => $code,
            'bookcode' => getConfig('BOOK_CODE_RETURN_ORDER'),
            'DocCur' => $iv->DocCur,
            'DocRate' => $iv->DocRate,
            'invoice' => $invoice,
            'customer_code' => $customer_code,
            'warehouse_code' => $zone->warehouse_code,
            'zone_code' => $zone->code,
            'user' => $this->_user->uname,
            'date_add' => $date_add,
            'remark' => $remark,
            'is_wms' => 0,
            'api' => 1,
            'must_accept' => $must_accept
          );

          if( ! $this->return_order_model->add($arr))
          {
            $sc = FALSE;
            $this->error = "Failed to add document Please try again.";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invoice already cancelled";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid invoice number OR Invalid customer";
      }

    }
    else
    {
      $sc = FALSE;
      set_error("Document data not found or form is blank, please check.");
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'code' => $sc === TRUE ? $code : NULL
    );

    echo json_encode($arr);
  }


  public function edit($code)
  {
    $doc = $this->return_order_model->get($code);
    $doc->customer_name = $this->customers_model->get_name($doc->customer_code);
    $doc->zone_name = $this->zone_model->get_name($doc->zone_code);
    $doc->warehouse_name = $this->warehouse_model->get_name($doc->warehouse_code);
    $details = $this->return_order_model->get_details($code);
      //--- ถ้าไม่มีรายละเอียดให้ไปดึงจากใบกำกับมา
    if(empty($details))
    {
      $details = $this->return_order_model->get_invoice_details($doc->invoice);

      if(! empty($details))
      {
        foreach($details as $rs)
        {
          if($rs->qty > 0)
          {
            $rs->id = 0;
            $rs->invoice_code = $doc->invoice;
            $rs->barcode = $this->products_model->get_barcode($rs->product_code);
            $rs->sold_qty = round($rs->qty, 2);
            $rs->discount_percent = round($rs->discount, 2);
            $rs->qty = round($rs->qty, 2);
            $rs->price = round(add_vat($rs->price), 2);
            $rs->amount = round((get_price_after_discount($rs->price, $rs->discount_percent) * $rs->qty), 2);
          }
        }
      }
    }
    else
    {
      foreach($details as $rs)
      {
        $returned_qty = $this->return_order_model->get_returned_qty($doc->invoice, $rs->product_code);
        $qty = $rs->sold_qty - ($returned_qty - $rs->qty);

				$rs->id = $rs->id;
				$rs->invoice_code = $doc->invoice;
				$rs->order_code = $rs->order_code;
				$rs->barcode = $this->products_model->get_barcode($rs->product_code);
				$rs->product_code = $rs->product_code;
				$rs->product_name = $rs->product_name;
				$rs->sold_qty = $qty;
				$rs->discount_percent = $rs->discount_percent;
				$rs->qty = $rs->qty;
				$rs->price = round($rs->price,2);
        $rs->currency = $rs->currency;
        $rs->rate = $rs->rate;
				$rs->amount = round($rs->amount,2);
      }
    }


    $ds = array(
      'doc' => $doc,
      'details' => $details
    );

    if($doc->status == 0)
    {
      $this->load->view('inventory/return_order/return_order_edit', $ds);
    }
    else
    {
      $this->load->view('inventory/return_order/return_order_view_detail', $ds);
    }

  }



  public function update()
  {
    $sc = TRUE;
    if($this->input->post('return_code'))
    {
      $code = $this->input->post('return_code');
      $date_add = db_date($this->input->post('date_add'), TRUE);
      $invoice = trim($this->input->post('invoice'));
      $customer_code = $this->input->post('customer_code');
			$zone_code = $this->input->post('zone_code');
			$DocCur = $this->input->post('doc_currency');
      $DocRate = $this->input->post('doc_rate') <= 0 ? 1 : $this->input->post('doc_rate');
      $zone = $this->zone_model->get($zone_code);
      $remark = $this->input->post('remark');
      $must_accept = empty($zone->user_id) ? 0 : 1;

      $doc = $this->return_order_model->get($code);

      $arr = array(
        'date_add' => $date_add,
        'DocCur' => $DocCur,
        'DocRate' => $DocRate,
        'invoice' => $invoice,
        'customer_code' => $customer_code,
        'warehouse_code' => $zone->warehouse_code,
        'zone_code' => $zone->code,
        'remark' => $remark,
        'must_accept' => $must_accept,
        'update_user' => $this->_user->uname
      );

      if($this->return_order_model->update($code, $arr) === FALSE)
      {
        $sc = FALSE;
        $this->error = 'Update failed';
      }

      if($doc->invoice != $invoice)
      {
        $this->return_order_model->remove_details($code);
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = 'Document number not found';
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'reload' => $doc->invoice == $invoice ? 'N' : 'Y'
    );

    echo json_encode($arr);
  }



  public function view_detail($code)
  {
    $this->load->model('approve_logs_model');
    $doc = $this->return_order_model->get($code);
    $details = $this->return_order_model->get_details($code);
    $ds = array(
      'doc' => $doc,
      'details' => $details,
      'approve_list' => $this->approve_logs_model->get($code)
    );

    $this->load->view('inventory/return_order/return_order_view_detail', $ds);
  }


  public function get_invoice($invoice)
  {
    $sc = TRUE;
    $details = $this->return_order_model->get_invoice_details($invoice);
    $ds = array();
    if(empty($details))
    {
      $sc = FALSE;
      $message = 'No data found';
    }

    if(!empty($details))
    {
      foreach($details as $rs)
      {
        $returned_qty = $this->return_order_model->get_returned_qty($invoice, $rs->product_code);
        $qty = $rs->qty - $returned_qty;
        $row = new stdClass();
        if($qty > 0)
        {
          $row->barcode = $this->products_model->get_barcode($rs->product_code);
          $row->invoice = $invoice;
					$row->order_code = $rs->order_code;
          $row->code = $rs->product_code;
          $row->name = $rs->product_name;
          $row->price = round($rs->price, 2);
          $row->discount = round($rs->discount, 2);
          $row->qty = round($qty, 2);
          $row->amount = 0;
          $ds[] = $row;
        }
      }
    }

    echo $sc === TRUE ? json_encode($ds) : $message;
  }



  public function get_open_invoice_list($customer_code)
  {
    $ds = array();
    $txt = trim($_REQUEST['term']);
    $customer_code = $customer_code == "no_customer_selected" ? NULL : urldecode($customer_code);

    $result = $this->return_order_model->get_open_invoice_list($txt, $customer_code);

    if( ! empty($result))
    {
      foreach($result as $rs)
      {
        $arr = array(
          'label' => $rs->DocNum,
          'invoice' => $rs->DocNum,
          'customer_code' => $rs->CardCode,
          'customer_name' => $rs->CardName
        );

        array_push($ds, $arr);
      }
    }

    echo json_encode($ds);

  }



	//--- print received
  public function print_detail($code)
  {
    $this->load->library('printer');
    $doc = $this->return_order_model->get($code);
    $doc->customer_name = $this->customers_model->get_name($doc->customer_code);
    $doc->warehouse_name = $this->warehouse_model->get_name($doc->warehouse_code);
    $doc->zone_name = $this->zone_model->get_name($doc->zone_code);
    $details = $this->return_order_model->get_details($code);

    if(!empty($details))
    {
      foreach($details as $rs)
      {
        $rs->barcode = $this->products_model->get_barcode($rs->product_code);
      }
    }
    $ds = array(
      'doc' => $doc,
      'details' => $details
    );

    $this->load->view('print/print_return', $ds);
  }


  public function print_wms_return($code)
  {
    $this->load->library('xprinter');
    $doc = $this->return_order_model->get($code);
    $doc->customer_name = $this->customers_model->get_name($doc->customer_code);
    $doc->warehouse_name = $this->warehouse_model->get_name($doc->warehouse_code);
    $doc->zone_name = $this->zone_model->get_name($doc->zone_code);
    $details = $this->return_order_model->get_count_item_details($code); //--- get only count item

    $ds = array(
      'order' => $doc,
      'details' => $details
    );

    $this->load->view('print/print_wms_return', $ds);
  }



  public function cancle_return($code)
  {
    $sc = TRUE;

    if($this->pm->can_delete)
    {
			$doc = $this->return_order_model->get($code);

			if(!empty($doc))
			{
				if($doc->status == 1 OR $this->_SuperAdmin)
				{
					//--- check sap
					$sap = $this->return_order_model->get_sap_doc_num($code);

					if(empty($sap))
					{
						//--- cancle middle
						if($sc === TRUE)
						{
							if($this->drop_middle_exits_data($code))
							{
                $arr = array(
                  'status' => 2,
                  'cancle_reason' => trim($this->input->post('reason')),
                  'cancle_user' => $this->_user->uname
                );

								$this->db->trans_start();
                $this->return_order_model->update($code, $arr);
					      $this->return_order_model->set_status($code, 2);
					      $this->db->trans_complete();

					      if($this->db->trans_status() === FALSE)
					      {
					        $sc = FALSE;
					        $this->error = $this->db->error();
					      }
							}
							else
							{
								$sc = FALSE;
								$this->error = "Cannot Delete Middle Temp data";
							}
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "Please cancel the document in SAP before proceeding.";
					}
				}
				else
				{
					$sc = FALSE;

					if($doc->status == 3)
					{
						$this->error = "The document is in the process of being admitted, not allowed to cancel.";
					}

					if($doc->status == 2)
					{
						$this->error = "The document has been cancelled.";
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid Document Number";
			}
    }
    else
    {
      $sc = FALSE;
      $this->error = 'You do not have the right to cancel the document.';
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



	public function drop_middle_exits_data($code)
  {
    $sc = TRUE;
    $middle = $this->return_order_model->get_middle_return_doc($code);

    if(!empty($middle))
    {
      foreach($middle as $rs)
      {
        if( ! $this->return_order_model->drop_middle_exits_data($rs->DocEntry))
				{
					$sc = FALSE;
				}
      }
    }

    return $sc;
  }




  public function get_item()
  {
    if($this->input->post('barcode'))
    {
      $barcode = trim($this->input->post('barcode'));
      $item = $this->products_model->get_product_by_barcode($barcode);
      if(!empty($item))
      {
        echo json_encode($item);
      }
      else
      {
        echo 'not-found';
      }
    }
  }





  public function do_export($code)
  {
    $sc = TRUE;
    $this->load->library('export');
    if(! $this->export->export_return($code))
    {
      $sc = FALSE;
      $this->error = trim($this->export->error);
    }

    return $sc;
  }




  //---- เรียกใช้จากภายนอก
  public function export_return($code)
  {
    if($this->do_export($code))
    {
      echo 'success';
    }
    else
    {
      echo $this->error;
    }
  }


	public function send_to_wms()
	{
		$sc = TRUE;

		if($this->input->post('code'))
		{
			$code = trim($this->input->post('code'));

			$doc = $this->return_order_model->get($code);
			if(!empty($doc))
			{
				if($doc->status != 2 && $doc->status != 0)
				{
					$details = $this->return_order_model->get_details($doc->code);

					if(!empty($details))
					{
						$this->wms = $this->load->database('wms', TRUE);
						$this->load->library('wms_receive_api');
						$rs = $this->wms_receive_api->export_return_order($doc, $details);

						if($rs)
						{
							$this->return_order_model->set_status($doc->code, 3);
						}
						else
						{
							$sc = FALSE;
							$this->error = $this->wms_receive_api->error;
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "ไม่พบรายการคืนสินค้า";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "สถานะเอกสารไม่ถุกต้อง";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "รหัสเอกสารไม่ถูกต้อง";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing required parameter: code";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}



  public function get_new_code($date)
  {
    $date = $date == '' ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_RETURN_ORDER');
    $run_digit = getConfig('RUN_DIGIT_RETURN_ORDER');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->return_order_model->get_max_code($pre);
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


  public function clear_filter()
  {
    $filter = array(
      'sm_code',
      'sm_invoice',
      'sm_customer_code',
      'sm_from_date',
      'sm_to_date',
      'sm_status',
      'sm_approve',
			'sm_warehouse',
      'sm_zone',
      'sm_must_accept',
			'sm_api',
      'sm_sap'
    );
    clear_filter($filter);
  }


} //--- end class
?>
