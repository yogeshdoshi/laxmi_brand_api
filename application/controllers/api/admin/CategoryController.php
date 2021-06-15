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



	public function insert_category()
    {
		
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
		if(isset($jsonArray['category_name']) && !empty($jsonArray['category_name'])){
			$category_name= trim($jsonArray['category_name']);
		}
		else{
		
			$resp["message"] = "Category name is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if(isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])){
			$is_active=  trim($jsonArray['is_active']);
		}
		else{
			$is_active=  0;
		}
		
		$array =array(
			'category_name' => $category_name,
			'is_active' => $is_active,
			'created_date' => CURRENT_DATETIME
		);

		$resp=$this->CategoryModel->save_category($array);
		
		if($resp>0)
		{
			$result["message"]="successfully inserted data";
			$this->responsedata(200, 'success', $result);
		}
		else
		{
			$result["message"] = "Something went wrong";
			$this->responsedata(400, 'failed', $result);
		}

}

public function update_category()
{
	
	$jsonArray = json_decode(file_get_contents('php://input'),true); 
	if(isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])){
		$category_id= trim($jsonArray['category_id']);
	}
	else{
	
		$resp["message"] = "Category id is required";
		return $this->responsedata(400, 'failed', $resp);
	}
	if(isset($jsonArray['category_name']) && !empty($jsonArray['category_name'])){
		$category_name= trim($jsonArray['category_name']);
	}
	else{
	
		$resp["message"] = "Category name is required";
		return $this->responsedata(400, 'failed', $resp);
	}

	if(isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])){
		$is_active=  trim($jsonArray['is_active']);
	}
	else{
		$is_active=  0;
	}
	
	$array =array(
		'category_name' => $category_name,
		'is_active' => $is_active,
		'created_date' => CURRENT_DATETIME
	);

	$resp=$this->CategoryModel->update_category($category_id,$array);
	
	if($resp>0)
	{
		$result["message"]="successfully updated data";
		$this->responsedata(200, 'success', $result);
	}
	else
	{
		$result["message"] = "Something went wrong";
		$this->responsedata(400, 'failed', $result);
	}

}

public function delete_category()
{
	$jsonArray = json_decode(file_get_contents('php://input'),true); 
	if ($jsonArray['id']) 
	{
		$resp=$this->CategoryModel->delete_category($jsonArray['id']);
		if($resp>0)
		{
			$resp["message"] = "Successfully deleted data";
			$this->responsedata(200, 'success', $resp);
		}
		else
		{
			$resp["message"] = "No record found";
			$this->responsedata(400, 'failed', $resp);
			
		}
	}
	else{
		$resp["message"] = "Invalid Id";
		$this->responsedata(400, 'failed', $resp);
	}
}
	public function responsedata($status_code, $status, $data)
	{
		json_output($status_code, array('status' => $status, 'data' => $data));
	}
}
