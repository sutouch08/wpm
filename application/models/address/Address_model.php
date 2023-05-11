<?php
class Address_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_shipping_detail($id)
  {
    $rs = $this->db->where('id', $id)->get('address_ship_to');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function get_shipping_address_by_code($code)
  {
    $rs = $this->db->where('code', $code)->order_by('is_default', 'DESC')->limit(1)->get('address_ship_to');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


	public function get_shipping_address_id_by_code($code)
  {
    $rs = $this->db
		->select('id')
		->where('code', $code)
		->order_by('is_default', 'DESC')
		->limit(1)
		->get('address_ship_to');

    if($rs->num_rows() === 1)
    {
      return $rs->row()->id;
    }

    return NULL;
  }



  public function get_default_address($code)
  {
    $rs = $this->db
    ->where('is_default', 1)
    ->where('code', $code)
    ->order_by('is_default', 'DESC')
    ->limit(1)
    ->get('address_ship_to');

    if($rs->num_rows() == 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_shipping_address($code)
  {
    $rs = $this->db->where('code', $code)->get('address_ship_to');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }



	public function get_ship_to_address($code)
  {
    $rs = $this->db->where('customer_code', $code)->get('address_ship_to');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

	public function get_default_ship_to_address_id($code)
	{
		$rs = $this->db
		->select('id')
		->where('customer_code', $code)
		->order_by('is_default', 'DESC')
		->limit(1)
		->get('address_ship_to');

		if($rs->num_rows() === 1)
		{
			return $rs->row()->id;
		}

		return NULL;
	}


  public function add_shipping_address(array $ds = array())
  {
    if(!empty($ds))
    {
      if($this->db->insert('address_ship_to', $ds))
      {
        return $this->db->insert_id();
      }
    }

    return FALSE;
  }



  public function update_shipping_address($id, array $ds = array())
  {
    return $this->db->where('id', $id)->update('address_ship_to', $ds);
  }



  public function delete_shipping_address($id)
  {
    return $this->db->where('id', $id)->delete('address_ship_to');
  }



  public function set_default_shipping_address($id)
  {
    return $this->db->set('is_default', 1)->where('id', $id)->update('address_ship_to');
  }


  public function unset_default_shipping_address($code)
  {
    $this->db->set('is_default', 0)
    ->where('code', $code)
    ->where('is_default', 1);

    return $this->db->update('address_ship_to');
  }



  public function get_id($code, $address = NULL)
  {
    $this->db->select('id')->where('code', $code);
    if($address != NULL)
    {
      $this->db->where('address', $address);
    }
    $this->db->order_by('is_default', 'DESC')->limit(1);
    $rs = $this->db->get('address_ship_to');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->id;
    }

    return FALSE;
  }


  public function count_address($code)
  {
    return $this->db->where('customer_code', $code)->count_all_results('address_ship_to');
  }


  public function get_new_code($code)
  {
    $rs = $this->db->select_max('address_code')->where('customer_code', $code)->order_by('address_code', 'DESC')->get('address_ship_to');
    return $rs->row()->address_code + 1;
  }

} //--- end class

 ?>
