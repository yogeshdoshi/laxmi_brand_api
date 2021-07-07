<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class FaqController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('admin/FaqModel');
    }

    public function get_faq()
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

            $where = "is_deleted IS NULL and deleted_at IS NULL and is_active=1";
           
            $order_by = "";

            $order_by = " id DESC";
            $resp = $this->FaqModel->alldata($page_no, $where, $order_by);


            $this->responsedata(200, 'success', $resp);
        }
    }

    function faq_detail()
    {
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
        if ($jsonArray['id']) {
            $resp = $this->FaqModel->fetch_single_faq($jsonArray['id']);
            if (isset($resp) && $resp!="") {
                $this->responsedata(200, 'success', $resp);
            } else {
                $resp["message"] = "No record found";
                $this->responsedata(400, 'failed', $resp);
            }
        }
		else{
			$resp["message"] = "Id is required";
			$this->responsedata(400, 'failed', $resp);
		}
    }

    function delete_faq()
	{
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
        if ($jsonArray['id']) 
		{
            $resp=$this->FaqModel->delete_faq($jsonArray['id']);
			if($resp>0)
			{
				$result["message"] = "Successfully deleted data";
                $this->responsedata(200, 'success', $result);
			}
			else
			{
                $result["message"] = "No record found";
                $this->responsedata(400, 'failed', $result);
			}
		}
        else{
			$result["message"] = "Invalid Id";
            $this->responsedata(400, 'failed', $result);
        }
	}
	
    public function insert_faq()
    {
		
		$jsonArray = json_decode(file_get_contents('php://input'),true);
      
		if(isset($jsonArray['faq_title']) && !empty($jsonArray['faq_title'])){
			$faq_title= trim($jsonArray['faq_title']);
		}
		else{
		
			$resp["message"] = "Faq title is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['faq_answer']) && !empty($jsonArray['faq_answer'])){
			$faq_answer=  trim($jsonArray['faq_answer']);
		}
		else{
			$resp["message"] = "Faq answer id is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])){
			$is_active=  trim($jsonArray['is_active']);
		}
		else{
			$is_active=  0;
		}
		
      
		$array =array(
			'faq_title' => $faq_title,
			'faq_answer' => $faq_answer,
			'created_at' => CURRENT_DATETIME,
			'updated_at' => NULL,
			'deleted_at' => NULL,
			'is_deleted' =>  NULL
		);

		$resp=$this->FaqModel->save_faq($array);

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

public function update_faq()
{
	
    $jsonArray = json_decode(file_get_contents('php://input'),true); 
    if(isset($jsonArray['id']) && !empty($jsonArray['id'])){
        $faq_id= trim($jsonArray['id']);
    }
    else{
    
        $resp["message"] = "Faq id is required";
        return $this->responsedata(400, 'failed', $resp);
    }
    if(isset($jsonArray['faq_title']) && !empty($jsonArray['faq_title'])){
        $faq_title= trim($jsonArray['faq_title']);
    }
    else{
    
        $resp["message"] = "Faq title is required";
        return $this->responsedata(400, 'failed', $resp);
    }
    
    if(isset($jsonArray['faq_answer']) && !empty($jsonArray['faq_answer'])){
        $faq_answer=  trim($jsonArray['faq_answer']);
    }
    else{
        $resp["message"] = "Faq answer id is required";
        return $this->responsedata(400, 'failed', $resp);
    }
    
    if(isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])){
        $is_active=  trim($jsonArray['is_active']);
    }
    else{
        $is_active=  0;
    }

	$array =array(
		'faq_title' => $faq_title,
			'faq_answer' => $faq_answer,
			'updated_at' => CURRENT_DATETIME
	);

	$resp=$this->FaqModel->update_faq($faq_id,$array);

	if($resp)
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
