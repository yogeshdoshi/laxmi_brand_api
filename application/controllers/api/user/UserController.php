<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class UserController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('user/UserModel');
	}

	public function save_device()
    {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if (isset($jsonArray['uniqueUserId']) && !empty($jsonArray['uniqueUserId'])) {
            $uniqueUserId = trim($jsonArray['uniqueUserId']);
        } else {

            $resp["message"] = "Unique id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['contactnumber']) && !empty($jsonArray['contactnumber'])) {
            $contactnumber =  trim($jsonArray['contactnumber']);
        } else {
            $resp["message"] = "Contact number is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        $array = array(
            "uniqueUserId" => $uniqueUserId,
            "contactnumber" => $contactnumber,
            'created_at' => CURRENT_DATETIME,
            'updated_at' => NULL,
            'deleted_at' => NULL
        );

        $resp = $this->UserModel->save_deviceid($array);

        if ($resp > 0) {
            $result["message"] = "successfully inserted data";
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

	public function save_order()
    {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if (isset($jsonArray['user_mobile']) && !empty($jsonArray['user_mobile'])) {
            $user_mobile = trim($jsonArray['user_mobile']);
        } else {
            $resp["message"] = "Unique id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['unique_deviceid']) && !empty($jsonArray['unique_deviceid'])) {
            $unique_deviceid =  trim($jsonArray['unique_deviceid']);
        } else {
            $resp["message"] = "Contact number is required";
            return $this->responsedata(400, 'failed', $resp);
        }

		if (isset($jsonArray['user_id']) && !empty($jsonArray['unique_deviceid'])) {
            $user_id =  trim($jsonArray['user_id']);
        } else {
            $resp["message"] = "User id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

		if (isset($jsonArray['amount']) && !empty($jsonArray['amount'])) {
            $amount =  trim($jsonArray['amount']);
        } else {
            $resp["message"] = "Amount is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pdt_id']) && !empty($jsonArray['pdt_id'])) {
            $prdid =  trim($jsonArray['pdt_id']);
        } else {
            $resp["message"] = "Product ID is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['var_id']) && !empty($jsonArray['var_id'])) {
            $var_id =  trim($jsonArray['var_id']);
        } else {
            $resp["message"] = "Varient ID is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['qty']) && !empty($jsonArray['qty'])) {
            $qty =  trim($jsonArray['qty']);
        } else {
            $resp["message"] = "Quantity is required";
            return $this->responsedata(400, 'failed', $resp);
        }

		$this->db->trans_start();
        $array = array(
            "user_mobile" => $user_mobile,
            "unique_deviceid" => $unique_deviceid,
            "user_id" => $user_id,
            "order_status" =>"created",
            "amount" => $amount,
            'order_created_date' => CURRENT_DATETIME,
            'updated_at' => NULL
        );
        $resp_id = $this->UserModel->save_order($array);

        $array2 = array(
            "order_id" => $resp_id,
            "pdt_id" => $prdid,
            "var_id" => $var_id,
            "qty" => $qty,
            'created_at' => CURRENT_DATETIME
        );
        $resp2 = $this->UserModel->save_order_design($array2);
        $this->db->trans_complete();
        if ($resp2 > 0) {
            $result["message"] = "successfully inserted data";
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function update_user_order(){
        $result["message"] = "successfully inserted data";
        $this->responsedata(200, 'success', $result);
    }
	public function responsedata($status_code, $status, $data)
    {
        json_output($status_code, array('status' => $status, 'data' => $data));
    }
}