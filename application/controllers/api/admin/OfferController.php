<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class OfferController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('admin/OfferModel');
    }

    public function get_offer()
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

            $where = "is_deleted IS NULL and deleted_at IS NULL";
           
            $order_by = " offerid DESC";
            $resp = $this->OfferModel->alldata($page_no, $where, $order_by);

            $this->responsedata(200, 'success', $resp);
        }
    }

    function offer_detail()
    {
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
        if ($jsonArray['id']) {
            $resp = $this->OfferModel->fetch_single_offer($jsonArray['id']);
            if (count($resp) > 0) {
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

    function delete_offer()
	{
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
        if ($jsonArray['id']) 
		{
            $resp=$this->OfferModel->delete_offer($jsonArray['id']);
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
	
    public function insert_offer()
    {
	
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
		if(isset($jsonArray['offer_description']) && !empty($jsonArray['offer_description'])){
			$offer_description= trim($jsonArray['offer_description']);
		}
		else{
		
			$resp["message"] = "offer description is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['offer_product_id']) && !empty($jsonArray['offer_product_id'])){
			$offer_product_id=  trim($jsonArray['offer_product_id']);
		}
		else{
			$resp["message"] = "offer product id is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
        if(isset($jsonArray['discount_amount']) && !empty($jsonArray['discount_amount'])){
			$discount_amount=  trim($jsonArray['discount_amount']);
		}
		else{
			$resp["message"] = "offer discount is required";
			return $this->responsedata(400, 'failed', $resp);
		}

        if(isset($jsonArray['offer_status']) && !empty($jsonArray['offer_status'])){
			$offer_status=  trim($jsonArray['offer_status']);
		}
		else{
			$resp["message"] = "offer status is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		$array =array(
            "offer_description"=>$offer_description,
            "offer_product_id"=>$offer_product_id,
            "discount_amount"=>$discount_amount,
            "offer_status"=>$offer_status,
			'created_at' => CURRENT_DATETIME,
			'updated_at' => NULL,
			'deleted_at' => NULL,
			'is_deleted' =>  NULL
		);

		$resp=$this->OfferModel->save_offer($array);

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

public function update_offer()
{
	
    $jsonArray = json_decode(file_get_contents('php://input'),true); 
    if(isset($jsonArray['id']) && !empty($jsonArray['id'])){
        $offer_id= trim($jsonArray['id']);
    }
    else{
    
        $resp["message"] = "offer id is required";
        return $this->responsedata(400, 'failed', $resp);
    }
    if(isset($jsonArray['offer_title']) && !empty($jsonArray['offer_title'])){
        $offer_title= trim($jsonArray['offer_title']);
    }
    else{
    
        $resp["message"] = "offer title is required";
        return $this->responsedata(400, 'failed', $resp);
    }
    
    if(isset($jsonArray['offer_answer']) && !empty($jsonArray['offer_answer'])){
        $offer_answer=  trim($jsonArray['offer_answer']);
    }
    else{
        $resp["message"] = "offer answer id is required";
        return $this->responsedata(400, 'failed', $resp);
    }
    
    if(isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])){
        $is_active=  trim($jsonArray['is_active']);
    }
    else{
        $is_active=  0;
    }

	$array =array(
		'offer_title' => $offer_title,
			'offer_answer' => $offer_answer,
			'updated_at' => CURRENT_DATETIME
	);

	$resp=$this->OfferModel->update_offer($offer_id,$array);

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
