<?php

require APPPATH . 'libraries/Rest_Controller.php';

class LoginController extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('admin/UploadModel');
	}
	function index_post()
	{
	}
	function login_post()
	{
		print_r($this->post());
		die;
		// $rawpostdata = file_get_contents("php://input");
		// $post = json_decode($rawpostdata, true);
		// $username = $post['username'];
		// $password = $post['password'];
		if (!isset($_POST['username']) || !isset($_POST['password'])) {
			header("HTTP/1.1 200 OK");
			echo json_encode(array('status' => 'invalid parameters'));
			return;
		}
		$username = $_POST['username'];
		$password = $_POST['password'];
		if (empty($username) || empty($password) || !preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
			header("HTTP/1.1 200 OK");
			echo json_encode(array('status' => 'fail', 'error' => 'invalid input'));
			return;
		}


		$username = addslashes($username);
		$password = addslashes($password);
		$password = md5($password);

		$query = $this->db->query("SELECT id, username, password FROM user WHERE username='{$username}' and password='{$password}' ");
		if ($query->num_rows > 0) {
			$result = $query->result();
			$id = $result[0]->id;
			$username = $result[0]->username;

			$this->session->set_userdata('id', $id);
			$this->session->set_userdata('username', $username);
			$apikey = md5($id . $username . time());
			$this->session->set_userdata('apikey', $apikey);

			$data = array(
				'key' => $apikey,
				'user_id' => $id
			);

			$this->db->insert('keys', $data);

			header("HTTP/1.1 200 OK");
			echo json_encode(array('status' => 'successs', 'apikey' => $apikey));
			// $this->response(array('status' => 'successs'), 200);
		} else {
			header("HTTP/1.1 200 OK");
			echo json_encode(array('status' => 'fail'));
			// $this->response(array('status' => 'fail'), 200);
		}
		// echo 'Total Results: ' . $query->num_rows();
	}


	public function  pallavi_post()
	{
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
					print_r($error);
		
				} else {
					$data = array('image_metadata' => $this->upload->data());
					print_r($data);
		
				}
		} else {
			
			// print_r($data);
		}
	}
	}

	public function  uploadimg_post()
	{
		print_r("1111");die;
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
					print_r($error);
		
				} else {
					$data = array('image_metadata' => $this->upload->data());
					print_r($data);
		
				}
		} else {
			
			// print_r($data);
		}
	}
	}
}
