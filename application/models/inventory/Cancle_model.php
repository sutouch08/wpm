<?php
class Cancle_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get('cancle');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_data(array $ds = array(), $perpage = NULL, $offset = NULL)
  {
    $this->db
    ->select('cancle.*')
    ->select('zone.name AS zone_name')
    ->select('order_state.name AS state_name')
    ->from('cancle')
    ->join('zone', 'cancle.zone_code = zone.code', 'left')
    ->join('orders', 'cancle.order_code = orders.code', 'left')
    ->join('order_state', 'orders.state = order_state.state');

    if(!empty($ds['order_code']))
    {
      $this->db->like('cancle.order_code',$ds['order_code']);
    }

    if(!empty($ds['pd_code']))
    {
      $this->db->like('cancle.product_code', $ds['pd_code']);
    }

    if(!empty($ds['zone_code']))
    {
      $this->db->group_start();
      $this->db->like('cancle.zone_code', $ds['zone_code']);
      $this->db->or_like('zone.name', $ds['zone_code']);
      $this->db->group_end();
    }

    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->db->where('cancle.date_upd >=', from_date($ds['from_date']));
      $this->db->where('cancle.date_upd <=', to_date($ds['to_date']));
    }

    if($perpage > 0)
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function count_rows(array $ds = array(), $perpage = NULL, $offset = NULL)
  {
    $this->db
    ->from('cancle')
    ->join('zone', 'cancle.zone_code = zone.code', 'left')
    ->join('orders', 'cancle.order_code = orders.code', 'left')
    ->join('order_state', 'orders.state = order_state.state');

    if(!empty($ds['order_code']))
    {
      $this->db->like('cancle.order_code',$ds['order_code']);
    }

    if(!empty($ds['pd_code']))
    {
      $this->db->like('cancle.product_code', $ds['pd_code']);
    }

    if(!empty($ds['zone_code']))
    {
      $this->db->group_start();
      $this->db->like('cancle.zone_code', $ds['zone_code']);
      $this->db->or_like('zone.name', $ds['zone_code']);
      $this->db->group_end();
    }

    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->db->where('cancle.date_upd >=', from_date($ds['from_date']));
      $this->db->where('cancle.date_upd <=', to_date($ds['to_date']));
    }

    return $this->db->count_all_results();
  }


  public function get_sum_cancle_product($order_code, $product_code)
  {
    $rs = $this->db->select_sum('qty')
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->get('cancle');

    return intval($rs->row()->qty);
  }


  public function get_details($order_code, $product_code)
  {
    $rs = $this->db
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->get('cancle');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_all_details($order_code)
  {
    $rs = $this->db->where('order_code', $order_code)->get('cancle');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_sum_stock($code)
  {
    $rs = $this->db->select_sum('qty')->where('product_code', $code)->get('cancle');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->qty;
    }

    return 0;
  }



  public function is_exists($order_code, $product_code, $zone_code)
  {
    $rs = $this->db
    ->select('id')
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->where('zone_code', $zone_code)
    ->get('cancle');

    if($rs->num_rows() == 1)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      if($this->is_exists($ds['order_code'], $ds['product_code'], $ds['zone_code']))
      {
        return $this->update($ds['order_code'], $ds['product_code'], $ds['zone_code'], $ds['qty']);
      }
      else
      {
        return $this->db->insert('cancle', $ds);
      }
    }

    return FALSE;
  }



  public function update($order_code, $product_code, $zone_code, $qty)
  {
    return $this->db
    ->set('qty', "qty + {$qty}", FALSE)
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->where('zone_code', $zone_code)
    ->update('cancle');
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete('cancle');
  }


  public function restore_buffer($code)
  {
    $sc = TRUE;

    $rs = $this->db->where('order_code', $code)->get('cancle');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $rd)
      {
        if($sc === FALSE)
        {
          break;
        }

        if($this->is_buffer_exists($rd->order_code, $rd->product_code, $rd->zone_code) === TRUE)
        {
          $qs = $this->db
          ->set("qty", "qty + {$rs->qty}", FALSE)
          ->where('order_code', $rd->order_code)
          ->where('product_code', $rd->product_code)
          ->where('zone-code', $rd->zone_code)
          ->where('user', $rd->user)
          ->update('buffer');

          if(! $qs)
          {
            $sc = FALSE;
          }
        }
        else
        {
          $arr = array(
            'order_code' => $rd->order_code,
            'product_code' => $rd->product_code,
            'warehouse_code' => $rd->warehouse_code,
            'zone_code' => $rd->zone_code,
            'qty' => $rd->qty,
            'user' => $rd->user
          );

          if(! $this->db->insert('buffer', $arr) )
          {
            $sc = FALSE;
          }

          if($sc === TRUE)
          {
            if(! $this->delete($rd->id) )
            {
              $sc = FALSE;
            }
          }
        }
      } //--- end foreach
    }

    return $sc;
  }


  public function is_buffer_exists($code, $pd_code, $zone_code)
  {
    $rs = $this->db->select('id')
    ->where('order_code', $code)
    ->where('product_code', $pd_code)
    ->where('zone_code', $zone_code)
    ->get('buffer');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }

}
 ?>
