<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjust_consignment extends PS_Controller
{
  public $menu_code = 'ICCMAJ';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = '';
	public $title = 'Consignment stock adjust';
  public $filter;
  public $error;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/adjust_consignment';
    $this->load->model('inventory/adjust_consignment_model');
    $this->load->model('inventory/movement_model');
    $this->load->model('inventory/sap_consignment_stock_model');
    $this->load->model('masters/warehouse_model');
    $this->load->model('masters/zone_model');
    $this->load->model('masters/products_model');
    $this->load->model('inventory/check_stock_diff_model');
  }


  public function index()
  {
    $filter = array(
      'code'      => get_filter('code', 'ac_code', ''),
      'reference'  => get_filter('reference', 'ac_reference', ''),
      'user'      => get_filter('user', 'ac_user', ''),
      'from_date' => get_filter('from_date', 'ac_from_date', ''),
      'to_date'   => get_filter('from_date', 'ac_to_date', ''),
      'remark' => get_filter('remark', 'ac_remark', ''),
      'status' => get_filter('status', 'ac_status', 'all'),
      'isApprove' => get_filter('isApprove', 'ac_isApprove', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->adjust_consignment_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$list   = $this->adjust_consignment_model->get_list($filter, $perpage, $this->uri->segment($segment));

    $filter['list'] = $list;

		$this->pagination->initialize($init);
    $this->load->view('inventory/adjust_consignment/adjust_consignment_list', $filter);
  }


  public function add_new()
  {
    $this->load->view('inventory/adjust_consignment/adjust_consignment_add');
  }


  public function add()
  {
    $sc = TRUE;
    if($this->input->post('date_add'))
    {
      if($this->pm->can_add)
      {
        $date_add = db_date($this->input->post('date_add'), TRUE);
        $code = empty($this->input->post('code')) ? $this->get_new_code($date_add) : $this->input->post('code');

        $ds = array(
          'code' => $code,
          'bookcode' => getConfig('BOOK_CODE_ADJUST_CONSIGNMENT'),
          'reference' => get_null(trim($this->input->post('reference'))),
          'date_add' => $date_add,
          'user' => $this->_user->uname,
          'remark' => get_null(trim($this->input->post('remark')))
        );

        if(! $this->adjust_consignment_model->add($ds))
        {
          $sc = FALSE;
          $this->error = "Failed to add document Please try again.";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "You do not have permission to perform this operation";
      }
    }

    echo $sc === TRUE ? "success|{$code}" : $this->error;
  }


  public function edit($code)
  {
    $doc = $this->adjust_consignment_model->get($code);
    if(!empty($doc))
    {
      $ds = array(
        'doc' => $this->adjust_consignment_model->get($code),
        'details' => $this->adjust_consignment_model->get_details($code)
      );

      $this->load->view('inventory/adjust_consignment/adjust_consignment_edit', $ds);
    }
    else
    {
      $this->load->view('page_error');
    }
  }


  public function add_detail()
  {
    $sc = TRUE;
    if($this->input->post('code'))
    {
      $code = $this->input->post('code');
      $zone_code = $this->input->post('zone_code');
      $product_code = $this->input->post('pd_code');
      $up_qty = $this->input->post('qty_up');
      $down_qty = $this->input->post('qty_down');
      $qty = $up_qty - $down_qty;
      if($qty != 0)
      {
        $doc = $this->adjust_consignment_model->get($code);
        if(! empty($doc) && $doc->status == 0)
        {
          //--- ตรวจสอบรหัสสินค้า
          $item = $this->products_model->get($product_code);
          if(!empty($item))
          {
            //--- ตรวจสอบรหัสโซน
            $zone = $this->zone_model->get($zone_code);
            if(!empty($zone))
            {
              //--- ตรวจสอบว่ามีรายการที่เงื่อนไขเดียวกันแล้วยังไม่ได้บันทึกหรือเปล่า
              //--- ถ้ามีรายการอยู่จะได้ ข้อมูล กลับมา
              $detail = $this->adjust_consignment_model->get_exists_detail($code, $product_code, $zone_code);

              if(!empty($detail))
              {
                if($detail->valid == 0)
                {
                  //---- ถ้ามีรายการอยู่แล้ว ทำการ update
                  $qty = $up_qty - $down_qty;
                  $stock = $this->sap_consignment_stock_model->get_stock_zone($zone_code, $product_code);
                  $new_qty = $stock + ($qty + $detail->qty);
                  if($new_qty < 0)
                  {
                    $sc = FALSE;
                    $this->error = "Insufficient balance. Already in the list : {$detail->qty}";
                  }
                  else
                  {
                    if(! $this->adjust_consignment_model->update_detail_qty($detail->id, $qty))
                    {
                      $sc = FALSE;
                      $this->error = "Failed to update data";
                    }
                  }
                }
                else
                {
                  $sc = FALSE;
                  $this->error = "The item cannot be adjusted because the item has already been reconciled.";
                }

              }
              else
              {
                //---- ถ้ายังไม่มีรายการ เพิ่มใหม่
                $ds = array(
                  'adjust_code' => $code,
                  'warehouse_code' => $zone->warehouse_code,
                  'zone_code' => $zone->code,
                  'product_code' => $item->code,
                  'qty' => $qty
                );

                if(! $this->adjust_consignment_model->add_detail($ds))
                {
                  $sc = FALSE;
                  $this->error = "Failed to add data";
                }
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "Invalid location";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "Invalid item code";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invalid document or invalid document status";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Quantity must be more than 0";
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "No data found";
    }

    if($sc === TRUE)
    {
      $rs = $this->adjust_consignment_model->get_exists_detail($code, $product_code, $zone_code);

      if(!empty($rs))
      {
        $arr = array(
          'id' => $rs->id,
          'pdCode' => $rs->product_code,
          'pdName' => $rs->product_name,
          'zoneCode' => $rs->zone_code,
          'zoneName' => $rs->zone_name,
          'up' => round(($rs->qty > 0 ? $rs->qty : 0)),
          'down' => ($rs->qty < 0 ? ($rs->qty * -1) : 0),
          'valid' => $rs->valid
        );
      }
      else
      {
        $sc = FALSE;
        $this->error = "Failed to add data";
      }
    }

    echo $sc === TRUE ? json_encode($arr) : $this->error;
  }




  //---- update doc header
  public function update()
  {
    $sc = TRUE;
    if($this->input->post('code'))
    {
      $code = $this->input->post('code');
      $date_add = db_date($this->input->post('date_add'), TRUE);
      $reference = get_null($this->input->post('reference'));
      $remark = get_null($this->input->post('remark'));

      $doc = $this->adjust_consignment_model->get($code);
      if(!empty($doc))
      {
        $arr = array(
          'reference' => $reference,
          'remark' => $remark
        );

        //---- ถ้าบันทึกแล้ว จะไม่สามารถเปลี่ยนแปลงวันที่ได้
        //--- เนื่องจากมีการบันทึก movement ไปแล้วตามวันที่เอกสาร
        if($doc->status == 0)
        {
          $arr['date_add'] = $date_add;
        }

        if(! $this->adjust_consignment_model->update($code, $arr))
        {
          $sc = FALSE;
          $this->error = "Failed to update data";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid doucment number";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }




  public function delete_detail()
  {
    $sc = TRUE;
    if($this->pm->can_edit)
    {
      $id = $this->input->post('id');
      if(!empty($id))
      {
        $detail = $this->adjust_consignment_model->get_detail($id);
        if(!empty($detail))
        {
          $doc = $this->adjust_consignment_model->get($detail->adjust_code);
          if(!empty($doc))
          {
            if($doc->status == 0)
            {
              if($detail->valid == 0)
              {
                if( ! $this->adjust_consignment_model->delete_detail($id))
                {
                  $sc = FALSE;
                  $this->error = "Failed to delete data";
                }
                else
                {
                  if($detail->id_diff)
                  {
                    $this->check_stock_diff_model->update($detail->id_diff, array('status' => 0));
                  }
                }
              }
              else
              {
                $sc = FALSE;
                $this->error = "Items have been reconciled and cannot be edited.";
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "The document has been saved. cannot be edited.";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "Document number not found";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "No data found";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Not found ID";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "You do not have permission to perform this operation";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  ///----- Just change status to 0
  public function unsave()
  {
    $sc = TRUE;
    if($this->input->post('code'))
    {
      $code = $this->input->post('code');
      $doc = $this->adjust_consignment_model->get($code);
      if(!empty($doc))
      {
        if($doc->status == 1)
        {
          $details = $this->adjust_consignment_model->get_details($code);
          if(!empty($details))
          {
            $status = 0; //--- 0 = not save, 1 = saved, 2 = cancled
            if( ! $this->adjust_consignment_model->change_status($code, $status))
            {
              $sc = FALSE;
              $this->error = "Failed to change status";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "No items found please check.";
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid doucment number";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }




  //---- Just change status to 1
  public function save()
  {
    $sc = TRUE;
    if($this->input->post('code'))
    {
      $code = $this->input->post('code');
      $doc = $this->adjust_consignment_model->get($code);
      if(!empty($doc))
      {
        if($doc->status == 0)
        {
          $details = $this->adjust_consignment_model->get_details($code);
          if(!empty($details))
          {
            $status = 1; //--- 0 = not save, 1 = saved, 2 = cancled
            if( ! $this->adjust_consignment_model->change_status($code, $status))
            {
              $sc = FALSE;
              $this->error = "Failed to change status";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "No items found please check";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Document has been saved.";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid doucment number";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function do_approve()
  {
    $sc = TRUE;
    if($this->input->post('code'))
    {
      $this->load->model('approve_logs_model');

      $code = $this->input->post('code');
      $doc = $this->adjust_consignment_model->get($code);
      if(!empty($doc))
      {
        if($doc->status == 1)
        {
          $this->db->trans_begin();
          $details = $this->adjust_consignment_model->get_details($code);
          if(!empty($details))
          {
            foreach($details as $rs)
            {
              if($sc === FALSE)
              {
                break;
              }

              if($rs->valid == 0)
              {
                //--- 1. update movement
                $move_in = $rs->qty > 0 ? $rs->qty : 0;
                $move_out = $rs->qty < 0 ? ($rs->qty * -1) : 0;
                $arr = array(
                  'reference' => $rs->adjust_code,
                  'warehouse_code' => $rs->warehouse_code,
                  'zone_code' => $rs->zone_code,
                  'product_code' => $rs->product_code,
                  'move_in' => $move_in,
                  'move_out' => $move_out,
                  'date_add' => $doc->date_add
                );

                if(! $this->movement_model->add($arr))
                {
                  $sc = FALSE;
                  $this->error = 'Failed to save movement';
                  break;
                }


                //--- 2 ปรับรายการเป็น บันทึกรายการแล้ว (valid = 1)
                if(! $this->adjust_consignment_model->valid_detail($rs->id))
                {
                  $sc = FALSE;
                  $this->error = "Failed to change status";
                  break;
                }
                else
                {
                  if(!empty($rs->id_diff))
                  {
                    $this->check_stock_diff_model->update($rs->id_diff, array('status' => 2));
                  }
                }
              }
            } ///--- end foreach
          }

          //--- do approve
          if($sc === TRUE)
          {
            $user = $this->_user->uname;

            if(!$this->adjust_consignment_model->do_approve($code, $user))
            {
              $sc = FALSE;
              $this->error = "Failed to approve";
            }

            //--- write approve logs
            if($sc === TRUE)
            {
              $this->approve_logs_model->add($code, 1, $user);
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
            $rs = $this->do_export($code);
            if($rs === FALSE)
            {
              $sc = FALSE;
              $this->error = "Approve success but failed to send data to SAP";
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "The document has not been saved";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid doucment number";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }




  public function un_approve()
  {
    $sc = TRUE;
    if($this->input->post('code'))
    {
      $code = $this->input->post('code');
      $sc = $this->unapprove($code);
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function unapprove($code)
  {
    $sc = TRUE;

    $this->load->model('approve_logs_model');

    $doc = $this->adjust_consignment_model->get($code);

    if(!empty($doc))
    {
      if($doc->status == 1)
      {
        if(empty($doc->issue_code) && empty($doc->receive_code))
        {
          $issue_code = $this->adjust_consignment_model->get_sap_issue_doc($code); //--- goods issue
          $receive_code = $this->adjust_consignment_model->get_sap_receive_doc($code); //---- goods receive

          if(empty($issue_code) && empty($receive_code))
          {

            if(! $this->drop_middle($code))
            {
              $sc = FALSE;
              $this->error = "Failed to delete temp data";
            }

            $this->db->trans_begin();

            //-- 1. drop movements
            if($sc === TRUE)
            {
              if(! $this->movement_model->drop_movement($code))
              {
                $sc = FALSE;
                $this->error = "Failed to delete movement";
              }
            }


            //--- 2. change details valid to 0
            if($sc === TRUE)
            {
              if(! $this->adjust_consignment_model->unvalid_details($code))
              {
                $sc = FALSE;
                $this->error = "Failed to change item status";
              }
              else
              {
                $details = $this->adjust_consignment_model->get_details($code);
                if(!empty($details))
                {
                  foreach($details as $rs)
                  {
                    if(!empty($rs->id_diff))
                    {
                      $this->check_stock_diff_model->update($rs->id_diff, array('status'=> 1));
                    }
                  }
                }
              }
            }



            //--- 3. un_approve
            if($sc === TRUE)
            {
              if(! $this->adjust_consignment_model->un_approve($code, $this->_user->uname))
              {
                $sc = FALSE;
                $this->error = "Failed to cancel approval";
              }
            }


            //--- 4. write approve logs
            if($sc === TRUE)
            {
              $this->approve_logs_model->add($code, 0, $this->_user->uname);
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
            $sc = FALSE;
            $this->error = "The document already in SAP cannot be edited.";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "The document already in SAP cannot be edited.้";
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "The document has not been saved.";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เลขที่เอกสารไม่ถูกต้อง";
    }

    return $sc;
  }



  public function get_stock_zone()
  {
    $zone_code = $this->input->get('zone_code');
    $product_code = $this->input->get('product_code');
    $stock = $this->sap_consignment_stock_model->get_stock_zone($zone_code, $product_code);
    echo $stock;
  }


  public function drop_middle($code)
  {
    $sc = TRUE;
    $goods_issue = $this->adjust_consignment_model->get_middle_goods_issue($code);
    if(!empty($goods_issue))
    {
      foreach($goods_issue as $rs)
      {
        if($sc === FALSE)
        {
          break;
        }

        if(! $this->adjust_consignment_model->drop_middle_issue_data($rs->DocEntry))
        {
          $sc = FALSE;
        }
      }
    }

    $goods_receive = $this->adjust_consignment_model->get_middle_goods_receive($code);
    if(!empty($goods_receive))
    {
      foreach($goods_receive as $rs)
      {
        if($sc === FALSE)
        {
          break;
        }

        if(! $this->adjust_consignment_model->drop_middle_receive_data($rs->DocEntry))
        {
          $sc = FALSE;
        }
      }
    }

    return $sc;
  }





  public function view_detail($code, $approve_view = NULL)
  {
    $this->load->model('approve_logs_model');

    $doc = $this->adjust_consignment_model->get($code);

    if(!empty($doc))
    {
      $doc->user_name = $this->user_model->get_name($doc->user);
      $ds = array(
        'doc' => $doc,
        'details' => $this->adjust_consignment_model->get_details($code),
        'approve_view' => $approve_view,
        'approve_list' => $this->approve_logs_model->get($code)
      );

      $this->load->view('inventory/adjust_consignment/adjust_consignment_detail', $ds);
    }
    else
    {
      $this->load->view('page_error');
    }

  }



  public function cancle()
  {
    $sc = TRUE;
    $code = $this->input->post('code');
    if(!empty($code))
    {
      $doc = $this->adjust_consignment_model->get($code);
      if(!empty($doc))
      {
        if(empty($doc->issue_code) && empty($doc->receive_code))
        {
          //---- ถ้าอนุมัติแล้วให้ยกเลิกการอนุมัติก่่อน
          //---- หากเอกสารยังไม่เข้า SAP จะลบเอกสารใน Middle Temp ออก
          //---- drop movement
          //---- แล้วย้อนสถานะรายการ
          if($doc->is_approved == 1)
          {
            if(! $this->unapprove($code))
            {
              $sc = FALSE;
            }
          }

          //---- ถ้าสามารถยกเลิกการอนุมัติได้ หรือ หากยังไม่ได้อนุมัติ
          //---- set is_cancle  = 1 in adjust_detail
          //---- change status = 2 in adjust
          if($sc === TRUE)
          {
            $this->db->trans_begin();
            //---- set is_cancle = 1 in adjust_detail
            if(! $this->adjust_consignment_model->cancle_details($code))
            {
              $sc = FALSE;
              $this->error = "Failed to cancel items";
            }

            //--- change doc status to 2 Cancled
            if($sc === TRUE)
            {
              if(! $this->adjust_consignment_model->change_status($code, 2))
              {
                $sc = FALSE;
                $this->error = "Failed to cancel document";
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
        }
        else
        {
          $sc = FALSE;
          $this->error = "The document already in SAP cannot be edited.";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid doucment number";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Invalid doucment number";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function load_check_diff($code)
  {
    $sc = TRUE;
    $list = $this->input->post('diff');
    if(!empty($list))
    {
      $this->db->trans_begin();
      //---- add diff list to adjust
      foreach($list as $id => $val)
      {
        $diff = $this->check_stock_diff_model->get($id);
        if(!empty($diff))
        {
          if($sc === FALSE)
          {
            break;
          }

          if($diff->status == 0)
          {
            $zone = $this->zone_model->get($diff->zone_code);
            if(!empty($zone))
            {
              $arr = array(
                'adjust_code' => $code,
                'warehouse_code' => $zone->warehouse_code,
                'zone_code' => $zone->code,
                'product_code' => $diff->product_code,
                'qty' => $diff->qty,
                'id_diff' => $diff->id
              );

              $adjust_id = $this->adjust_consignment_model->get_not_save_detail($code, $diff->product_code, $diff->zone_code);
              if(!empty($adjust_id))
              {
                if(! $this->adjust_consignment_model->update_detail($adjust_id, $arr))
                {
                  $sc = FALSE;
                  $this->error = "Update Failed : {$diff->product_code} : {$diff->zone_code}";
                }
              }
              else
              {
                if(! $this->adjust_consignment_model->add_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = "Add detail failed : {$diff->product_code} : {$diff->zone_code}";
                }
              }

              if($sc === TRUE)
              {
                $this->check_stock_diff_model->update($diff->id, array('status' => 1));
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "Invalid bin location";
            }
          }
        }

      } //--- endforeach;

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
      $sc = FALSE;
      $this->error = "No diffination found";
    }

    if($sc === TRUE)
    {
      set_message('Loaded');
    }
    else
    {
      set_error($this->error);
    }

    redirect("{$this->home}/edit/{$code}");
  }




  public function do_export($code)
  {
    $sc = TRUE;
    if(!empty($code))
    {
      $this->load->library('export');
      if(! $this->export->export_adjust_consignment_goods_issue($code))
      {
        $sc = FALSE;
        $this->error = trim($this->export->error);
      }

      if($sc === TRUE)
      {
        if(! $this->export->export_adjust_consignment_goods_receive($code))
        {
          $sc = FALSE;
          $this->error = trim($this->export->error);
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    return $sc;
  }



  public function manual_export()
  {
    $sc = TRUE;
    $code = $this->input->post('code');
    if(!empty($code))
    {
      $rs = $this->do_export($code);
      if($rs === FALSE)
      {
        $sc = FALSE;
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }




  public function get_new_code($date = '')
  {
    $date = $date == '' ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_ADJUST_CONSIGNMENT');
    $run_digit = getConfig('RUN_DIGIT_ADJUST_CONSIGNMENT');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->adjust_consignment_model->get_max_code($pre);
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
      'ac_code',
      'ac_reference',
      'ac_user',
      'ac_from_date',
      'ac_to_date',
      'ac_remark',
      'ac_status',
      'ac_isApprove'
    );

    clear_filter($filter);

    echo 'done';
  }

} //---- End class
?>
