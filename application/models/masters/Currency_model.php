<?php
class Currency_model extends CI_Model
{
  public $tb = "currency";

  public function __construct()
  {
    parent::__construct();
  }



  public function get($code)
  {
    $rs = $this->db->where('CurrCode', $code)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_all_active()
  {
    $rs = $this->db->where('active', 1)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert($this->tb, $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('CurrCode', $code)->update($this->tb, $ds);
    }

    return FALSE;
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete($this->tb);
  }


  public function count_rows(array $ds = array())
  {
    if( ! empty($ds['code']))
    {
      $this->db->like('CurrCode', $ds['code']);
    }

    if( ! empty($ds['name']))
    {
      $this->db->like('CurrName', $ds['name']);
    }

    return $this->db->count_all_results($this->tb);
  }


  public function get_list(array $ds = array(), $limit = 20, $offset = 0)
  {
    if( ! empty($ds['code']))
    {
      $this->db->like('CurrCode', $ds['code']);
    }

    if( ! empty($ds['name']))
    {
      $this->db->like('CurrName', $ds['name']);
    }

    $rs = $this->db->limit($limit, $offset)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function getSapCurrency()
  {
    $rs = $this->ms->select('CurrCode, CurrName, DocCurrCod')->get('OCRN');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

}
?>
