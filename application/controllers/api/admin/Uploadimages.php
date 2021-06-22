<?php

// require APPPATH . 'libraries/Rest_Controller.php';

class Uploadimages extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('admin/UploadModel');
	}
	
	public function  index()
	{

		// print_r($_POST);die;

		$image_for=$_POST['image_for'];
		$reference_id=$_POST['reference_id'];


		$this->load->helper('url');

		$data = array();
		
		if ($_FILES != NULL) {
			if (!empty($_FILES['file']['name'])) {
				
				$config['upload_path'] = ASSETS_PATH;
				$config['allowed_types'] = 'jpg|jpeg|png|gif';
				$config['max_size'] = '2000'; // max_size in kb that means 2mb
				$config['file_name'] = $_FILES['file']['name'];

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('file')) {
					$error = array('error' => $this->upload->display_errors());
					$result["message"] = "Something went wrong";
					$this->responsedata(400, 'failed', $result);
		
				} else {
					$data = array('image_metadata' => $this->upload->data());
				
					$datas = array(
						'image_for' => $image_for,
						'reference_id' => $reference_id,
						'image_path' => $data['image_metadata']['file_path'],
						'image_type' => $data['image_metadata']['image_type'],
						'created_at' => CURRENT_DATETIME
					);

					$this->UploadModel->save_upload($datas);

					$result["message"]="successfully uploaded files";
					$this->responsedata(200, 'success', $result);
		
				}
		} else {
			
			$result["message"] = "Something went wrong";
		$this->responsedata(400, 'failed', $result);
		}
	}
	}

	public function responsedata($status_code, $status, $data)
    {
        json_output($status_code, array('status' => $status, 'data' => $data));
		
    }
}
