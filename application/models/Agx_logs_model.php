<?php
class Agx_logs_model extends CI_Model
{
  private $tb = "agx_completed_logs";

  public function __construct()
  {
    parent::__construct();
  }


  public function get_log_id($type, $code, $file_name)
  {
    $rs = $this->db
    ->select('id')
    ->where('type', $type)
    ->where('code', $code)
    ->where('file_name', $file_name)
    ->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function add(array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->insert($this->tb, $ds);
    }

    return TRUE;
  }


  public function set_delete($ids = array())
  {
    if( ! empty($ids))
    {
      return $this->db
      ->set('is_deleted', 1)
      ->where_in('id', $ids)
      ->update($this->tb);
    }

    return FALSE;
  }


  public function count_rows(array $ds = array())
  {
    if( ! empty($ds['type']) && $ds['type'] != 'all')
    {
      $this->db->where('type', $ds['type']);
    }

    if( ! empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    if( isset($ds['is_delete']) && $ds['is_delete'] != 'all')
    {
      $this->db->where('is_deleted', $ds['is_delete']);
    }

    if( ! empty($ds['from_date']))
    {
      $this->db->where('date_upd >=', from_date($ds['from_date']));
    }

    if( ! empty($ds['to_date']))
    {
      $this->db->where('date_upd <=', to_date($ds['to_date']));
    }

    return $this->db->count_all_results($this->tb);
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if( ! empty($ds['type']) && $ds['type'] != 'all')
    {
      $this->db->where('type', $ds['type']);
    }

    if( ! empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    if( isset($ds['is_delete']) && $ds['is_delete'] != 'all')
    {
      $this->db->where('is_deleted', $ds['is_delete']);
    }

    if( ! empty($ds['from_date']))
    {
      $this->db->where('date_upd >=', from_date($ds['from_date']));
    }

    if( ! empty($ds['to_date']))
    {
      $this->db->where('date_upd <=', to_date($ds['to_date']));
    }

    $rs = $this->db->order_by('id', 'DESC')->limit($perpage, $offset)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

} //--- end class

 ?>
