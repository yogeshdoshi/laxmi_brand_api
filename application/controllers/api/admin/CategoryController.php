<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class CategoryController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('pagination');
		$this->load->model('admin/CategoryModel');
	}

	public function get_category()
	{

		/* pagination attributes */
		$page_no  = 1;
		if (is_numeric($this->uri->segment(3))) {
			$page_no = $this->uri->segment(3);
		}

		$method = $_SERVER['REQUEST_METHOD'];
		if ($method != 'POST') {
			$this->responsedata(400, 'failed', 'Bad request!');
		} else {
			$response['status'] = "200";

			$where = "is_deleted IS NULL and deleted_at IS NULL and is_active=1";
			// $sort=$this->input->post('sort');
			// $popularity=$this->input->post('popularity');
			$order_by = "";

			$resp = $this->CategoryModel->alldata($page_no, $where, $order_by);

			$this->responsedata(200, 'success', $resp);
		}
	}

	public function responsedata($status_code, $status, $data)
	{
		json_output($status_code, array('status' => $status, 'data' => $data));
	}
}
