<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_pwd extends PS_Controller
{
  public $title = 'Change password';
	public $menu_code = 'change password';
	public $menu_group_code = 'SC';
	public $pm;
  public $error;

	public function __construct()
	{
		parent::__construct();
		_check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;
    $this->load->model('users/user_model');
    $this->home = base_url().'user_pwd';

	}


	public function index()
	{
    $code = $this->_user->uname;
    if(!empty($code))
    {
      $user = $this->user_model->get($code);
      if(!empty($user))
      {
        $ds['data'] = $user;
        $this->load->view('users/change_pwd', $ds);
      }
      else
      {
        //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
        redirect(base_url().'users/authentication');
      }
    }
    else
    {
      //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
  		redirect(base_url().'users/authentication');
    }

	}


  public function change($code)
	{
    if(!empty($code))
    {
      $user = $this->user_model->get($code);
      if(!empty($user))
      {
        $ds['data'] = $user;
        $this->load->view('users/change_pwd', $ds);
      }
      else
      {
        //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
        redirect(base_url().'users/authentication');
      }
    }
    else
    {
      //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
  		redirect(base_url().'users/authentication');
    }
	}


	public function check_current_password()
	{
		$uname = $this->input->post('uname');
		$pwd = $this->input->post('pwd');

		$user = $this->user_model->get_user_credentials($uname);

		if(!empty($user))
		{
			if(password_verify($pwd, $user->pwd))
			{
				echo "valid";
			}
			else
			{
				echo "invalid";
			}
		}
		else
		{
			echo "Invalid user name : {$uname}";
		}

	}



  public function change_password()
	{
		$sc = TRUE;
		$uname = $this->input->post('uname');
		$pwd = $this->input->post('pwd');
		$new_pwd = $this->input->post('new_pwd');

		$user = $this->user_model->get_user_credentials($uname);

		if(!empty($user))
		{
			if(password_verify($pwd, $user->pwd))
			{
				//--- change password
				$password = password_hash($new_pwd, PASSWORD_DEFAULT);

				if(!$this->user_model->change_password($user->id, $password))
				{
					$sc = FALSE;
					$this->error = "Failed to change password";
				}
				else
				{
					$arr = array(
						'last_pass_change' => date('Y-m-d')
					);
					//--- update last pass change
					$this->user_model->update_user($user->id, $arr);
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "password is incorrect";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid Username : {$uname}";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}




  public function change_skey()
  {
    $sc = TRUE;
    $uid = trim($this->input->post('uid'));
    $user = $this->user_model->get_user_by_uid($uid);
    if(!empty($user))
    {
      $skey = trim($this->input->post('skey'));
      $skey = md5($skey);
      $is_exists = $this->user_model->is_skey_exists($skey, $uid);
      if($is_exists)
      {
        $sc = FALSE;
        $this->error = "This password cannot be used, please set another code.";
      }
      else
      {
        $arr = array('skey' => $skey);
        if(! $this->user_model->update_user($user->id, $arr))
        {
          $sc = FALSE;
          $this->error = "Failed to change password";
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "The user was not found or the user is invalid.";
    }

    echo $sc === TRUE ? 'success' : $this->error;

  }

}
 ?>
