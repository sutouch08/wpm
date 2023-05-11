<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prepare_list_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_list(array $ds = array(), $perpage = '', $offset = '')
  {
    $this->db
    ->select('prepare.*')
    ->select('order_state.name AS state_name')
    ->select('zone.name AS zone_name')
    ->from('prepare')
    ->join('zone', 'prepare.zone_code = zone.code', 'left')
    ->join('orders', 'prepare.order_code = orders.code', 'left')
    ->join('order_state', 'orders.state = order_state.state');

    if(!empty($ds['order_code']))
    {
      $this->db->like('prepare.order_code', $ds['order_code']);
    }

    if(!empty($ds['pd_code']))
    {
      $this->db->like('product_code', $ds['pd_code']);
    }

    if(!empty($ds['zone_code']))
    {
      $this->db->group_start();
      $this->db->like('zone.code', $ds['zone_code']);
      $this->db->or_like('zone.name', $ds['zone_code']);
      $this->db->group_end();
    }

    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->db->where('prepare.date_upd >=', from_date($ds['from_date']));
      $this->db->where('prepare.date_upd <=', to_date($ds['to_date']));
    }

    $this->db->order_by('prepare.date_upd', 'DESC');

    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get();
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function count_rows(array $ds = array())
  {
    $this->db
    ->from('prepare')
    ->join('zone', 'prepare.zone_code = zone.code', 'left');

    if(!empty($ds['order_code']))
    {
      $this->db->like('prepare.order_code', $ds['order_code']);
    }

    if(!empty($ds['pd_code']))
    {
      $this->db->like('product_code', $ds['pd_code']);
    }

    if(!empty($ds['zone_code']))
    {
      $this->db->group_start();
      $this->db->like('zone.code', $ds['zone_code']);
      $this->db->or_like('zone.name', $ds['zone_code']);
      $this->db->group_end();
    }

    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->db->where('prepare.date_upd >=', from_date($ds['from_date']));
      $this->db->where('prepare.date_upd <=', to_date($ds['to_date']));
    }

    return $this->db->count_all_results();
  }


} //-- end class

?>
