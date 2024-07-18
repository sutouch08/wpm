<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agx_transfer_completed extends PS_Controller
{
	public $menu_code = 'AGXTRCP';
	public $menu_group_code = 'WMS';
  public $menu_sub_group_code = 'TAGXTR';
	public $title = 'AGX TR-Completed';
  public $filter;
	public $uname = "api@agx";

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'rest/V1/agx_transfer_completed';
		$this->load->model('agx_logs_model');
  }

	public function index()
  {
		$filter = array(
			'type' => 'TR',
      'code' => get_filter('code', 'agx_tr_code', ''),
      'is_delete' => get_filter('is_delete', 'agx_tr_is_delete', 'all'),
			'from_date' => get_filter('from_date', 'agx_from_date', ''),
			'to_date' => get_filter('to_date', 'agx_to_date', '')
    );

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_rows();
			//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
			if($perpage > 300)
			{
				$perpage = 20;
			}

			$segment  = 5; //-- url segment
			$rows     = $this->agx_logs_model->count_rows($filter);
			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);

			$filter['list'] = $this->agx_logs_model->get_list($filter, $perpage, $this->uri->segment($segment));

			$this->pagination->initialize($init);

			$this->load->view('rest/V1/agx/tr/completed_list', $filter);
		}
  }


	 public function get_detail()
	 {
		 $sc = TRUE;
		 $ds = array();

		 $fileName = $this->input->post('fileName');
 		 $file_path = $this->config->item('upload_file_path')."agx/TR/Completed/".$fileName;
		 $code = "TR / Completed / ".$fileName;

		 if(file_exists($file_path))
		{
			$this->load->library('excel');
			$excel = PHPExcel_IOFactory::load($file_path);
			$collection = $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			if( ! empty($collection))
			{
				$i = 0;

				foreach($collection as $line)
				{
					if($i > 0)
					{
						$arr = array(
							'date' => $line['A'],
							'code' => $line['B'],
							'from_location' => $line['C'],
							'to_location' => $line['D'],
							'item_code' => $line['E'],
							'request_qty' => $line['F'],
							'transfer_qty' => empty($line['G']) ? 0 : $line['G']
						);

						array_push($ds, $arr);
					}

					$i++;
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "File not found !";
		}

		 $arr = array(
			 'status' => $sc === TRUE ? 'success' : 'failed',
			 'message' => $sc === TRUE ? 'success' : $this->error,
			 'data' => $sc === TRUE ? $ds : NULL,
			 'code' => $code
		 );

		 echo json_encode($arr);
	 }


	public function delete()
	{
		$sc = TRUE;
		$fileName = $this->input->post('fileName');
		$code = $this->input->post('code');
		$file_path = $this->config->item('upload_file_path')."agx/TR/Completed/".$fileName;
		$file_size = 0; //--- file size in byte;

		$list = $this->agx_logs_model->get_log_id('TR', $code, $fileName);

		if( ! empty($list))
		{
			$ids = [];

			foreach($list as $rs)
			{
				$ids[] = $rs->id;
			}

			if($this->agx_logs_model->set_delete($ids))
			{
				if(file_exists($file_path))
				{
					if(! unlink($file_path))
					{
						$sc = FALSE;
						$this->error = "Failed to delete file";
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Failed to update complete logs";
			}
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}

	public function clear_filter()
	{
		$filter = array(
			'agx_tr_code',
			'agx_tr_is_delete',
			'agx_from_date',
			'agx_to_date'
		);

		return clear_filter($filter);
	}
} //--- end classs
?>
