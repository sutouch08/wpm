<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends PS_Controller
{
  public $menu_code = 'DBBANK';
	public $menu_group_code = 'DB';
	public $title = 'Bank Account';
	public $error;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/bank';
    $this->load->model('masters/bank_model');
    $this->load->helper('bank');
  }



  public function index()
  {
    $filter = array(
      'account_name' => get_filter('account_name', 'account_name', ''),
      'account_no' => get_filter('account_no', 'account_no', ''),
      'branch' => get_filter('branch', 'branch', ''),
      'bank_code' => get_filter('bank_code', 'bank_code', 'all')
    );



		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->bank_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$banks = $this->bank_model->get_list($filter, $perpage, $this->uri->segment($segment));

    $filter['data'] = $banks;

		$this->pagination->initialize($init);
    $this->load->view('masters/bank/bank_account_list', $filter);
  }




  public function add_new()
  {
    $this->load->view('masters/bank/bank_account_add');
  }



	public function add()
	{
		$sc = TRUE;
		if($this->pm->can_add)
		{
			$bank_code = $this->input->post('bank_code');
			$account_name = $this->input->post('account_name');
			$account_no = $this->input->post('account_no');
			$branch = $this->input->post('branch');

			if(empty($bank_code))
			{
				$sc = FALSE;
				$this->error = "Please select a bank";
			}

			if(empty($account_name))
			{
				$sc = FALSE;
				$this->error = "Please specify account name";
			}


			if(empty($account_no))
			{
				$sc = FALSE;
				$this->error = "Please enter account number";
			}

			if(empty($branch))
			{
				$sc = FALSE;
				$this->error = "Please specify branch";
			}


			if($sc === TRUE)
			{
				//--- check duplicate acc_no
				$exists = $this->bank_model->is_exists($account_no);

				if($exists === FALSE)
				{
					$bank = $this->bank_model->get_bank($bank_code);

					if(! empty($bank))
					{
						$arr = array(
							'bank_code' => $bank_code,
							'bank_name' => $bank->name,
							'branch' => $branch,
							'acc_name' => $account_name,
							'acc_no' => $account_no
						);

						if(! $this->bank_model->add($arr))
						{
							$sc = FALSE;
							$this->error = "Failed to add account number";
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "invalid bank";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "duplicate account number";
				}

			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "You do not have the right to add a bank account.";
		}


		echo $sc === TRUE ? 'success' : $this->error;
	}


	public function edit($id)
	{
		$account = $this->bank_model->get($id);
		$ds = array(
			'data' => $account
		);

		$this->load->view('masters/bank/bank_account_edit', $ds);
	}



	public function update()
	{
		$sc = TRUE;
		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$bank_code = $this->input->post('bank_code');
			$account_name = $this->input->post('account_name');
			$account_no = $this->input->post('account_no');
			$branch = $this->input->post('branch');

			if(empty($bank_code))
			{
				$sc = FALSE;
				$this->error = "Please select a bank";
			}

			if(empty($account_name))
			{
				$sc = FALSE;
				$this->error = "Please specify account name";
			}


			if(empty($account_no))
			{
				$sc = FALSE;
				$this->error = "Please enter account number";
			}

			if(empty($branch))
			{
				$sc = FALSE;
				$this->error = "Please specify branch";
			}

			if(empty($id))
			{
				$sc = FALSE;
				$this->error = "Account Id not found";
			}


			if($sc === TRUE)
			{

				//--- check duplicate acc_no
				$exists = $this->bank_model->is_exists($account_no, $id);

				if($exists === FALSE)
				{
					$bank = $this->bank_model->get_bank($bank_code);

					if(! empty($bank))
					{
						$arr = array(
							'bank_code' => $bank_code,
							'bank_name' => $bank->name,
							'branch' => $branch,
							'acc_name' => $account_name,
							'acc_no' => $account_no
						);

						if(! $this->bank_model->update($id, $arr))
						{
							$sc = FALSE;
							$this->error = "Failed to add account number";
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "invalid bank";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "duplicate account number";
				}

			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "You do not have the right to modify the bank account.";
		}


		echo $sc === TRUE ? 'success' : $this->error;
	}


	public function delete($id)
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			if(!empty($id))
			{
				//--- check transection
				$this->load->model('orders/order_payment_model');

				if($this->order_payment_model->has_account_transection($id))
				{
					$sc = FALSE;
					$this->error = "This account is already in use. deletion is not allowed";
				}
				else
				{
					if(! $this->bank_model->delete($id))
					{
						$sc = FALSE;
						$this->error = "Failed to delete account";
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Account Id not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "You do not have the right to delete a bank account.";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}



  public function clear_filter()
  {
    $filter = array(
      'account_name',
      'account_no',
      'branch',
      'bank_code'
    );

    clear_filter($filter);

    echo "done!";
  }


} //---- end class
?>
