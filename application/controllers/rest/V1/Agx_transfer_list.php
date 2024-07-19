<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agx_transfer_list extends PS_Controller
{
	public $menu_code = 'AGXTRLT';
	public $menu_group_code = 'WMS';
  public $menu_sub_group_code = 'TAGXTR';
	public $title = 'AGX TR-Request';
  public $filter;
		public $uname = "api@agx";
	public $agx_api = FALSE;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'rest/V1/agx_transfer_list';
		$this->agx_api = is_true(getConfig('AGX_API'));
  }

  public function index()
  {
		$list = array();
		$path = $this->config->item('upload_path')."agx/TR/Request/";
		$file_path = $this->config->item('upload_file_path')."agx/TR/Request/";

		if($handle = opendir($path))
		{
			while(FALSE !== ($entry = readdir($handle)))
			{
				if($entry !== '.' && $entry !== '..')
				{
					$file = array(
						'name' => $entry,
						'size' => ceil((filesize($file_path.$entry)/1024))." KB",
						'date_modify' => date('Y-m-d H:i:s', filemtime($file_path.$entry))
					);

					$list[] = $file;
				}
			}

			closedir($handle);
		}

    $this->load->view('rest/V1/agx/tr/request_list', ['list' => $list]);
  }


	 public function get_detail()
	 {
		 $sc = TRUE;
		 $ds = array();

		 $fileName = $this->input->post('fileName');
 		 $file_path = $this->config->item('upload_file_path')."agx/TR/Request/".$fileName;
		 $code = "TR / Request / ".$fileName;

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
			 'data' => $sc === TRUE ? $ds : $this->error,
			 'code' => $code
		 );

		 echo json_encode($arr);
	 }


	 private function export_transfer($code)
 	{
 		$sc = TRUE;

 		$this->load->library('export');

 		if(! $this->export->export_transfer($code))
 		{
 			$sc = FALSE;
 			$this->error = trim($this->export->error);
 		}
 		else
 		{
 			$this->transfer_model->set_export($code, 1);
 		}

 		return $sc;
 	}


	public function delete()
	{
		$sc = TRUE;
		$fileName = $this->input->post('fileName');
		$file_path = $this->config->item('upload_file_path')."agx/TR/Request/".$fileName;

		if(file_exists($file_path))
		{
			if( ! unlink($file_path))
			{
				$sc = FALSE;
				$this->error = "Failed to delete file";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "File not found !";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}
} //--- end classs
?>
