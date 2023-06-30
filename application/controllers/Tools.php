<?php
class Tools extends CI_Controller
{
  public $ms;

  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }

  public function set_rows()
  {
    if($this->input->post('set_rows') && $this->input->post('set_rows') > 0)
    {
      $rows = intval($this->input->post('set_rows'));
      $cookie = array(
        'name' => 'rows',
        'value' => $rows > 300 ? 300 : $rows,
        'expire' => 2592000, //--- 30 days
        'path' => '/'
      );

      $this->input->set_cookie($cookie);
    }

    echo 'done';
  }


  public function getCurrencyRate()
  {
    $rate = 0.00;    
    $date = db_date($this->input->get('date'));

    $cur = $this->input->get('currency');
    $df = getConfig('CURRENCY');

    if( ! empty($cur))
    {
      if($cur == $df)
      {
        $rate = 1.00;
      }
      else
      {
        $rs = $this->ms->select('Rate')->where('RateDate', $date)->where('Currency', $cur)->get('ORTT');

        if($rs->num_rows() == 1)
        {
          $rate = $rs->row()->Rate;
        }
      }
    }

    echo $rate;
  }
}

 ?>
