<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class OrderController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('admin/OrderModel');
    }

    public function get_order()
    {
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
        /* pagination attributes */
        $page_no  = 1;
        if (is_numeric($this->uri->segment(4))) {
            $page_no = $this->uri->segment(4);
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $this->responsedata(400, 'failed', 'Bad request!');
        } else {
            $response['status'] = "200";

            $where = "`c.user_status`=1";
          
            $order_by = "  s.user_id DESC";

           
            $resp = $this->OrderModel->alldata($page_no, $where, $order_by);


            $this->responsedata(200, 'success', $resp);
        }
    }

public function update_order()
{
	$jsonArray = json_decode(file_get_contents('php://input'),true); 
	if(isset($jsonArray['order_id']) && !empty($jsonArray['order_id'])){
		$pid= trim($jsonArray['order_id']);
	}
	else{
	
		$resp["message"] = "order id is required";
		return $this->responsedata(400, 'failed', $resp);
	}

	if(isset($jsonArray['order_status']) && !empty($jsonArray['order_status'])){
		$order_status= trim($jsonArray['order_status']);
	}
	else{
	
		$resp["message"] = "order status is required";
		return $this->responsedata(400, 'failed', $resp);
	}
	
	$array =array(
		'order_status' => $order_status,
		'updated_at' => CURRENT_DATETIME
	);

	$resp=$this->OrderModel->update_order($pid,$array);

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

    public function responsedata($status_code, $status, $data)
    {
        json_output($status_code, array('status' => $status, 'data' => $data));
		
    }
}
