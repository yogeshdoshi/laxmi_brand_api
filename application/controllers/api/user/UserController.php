<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');

class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('user/UserModel');
        $this->load->model('user/OrderModel');
    }

    public function save_device() {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        $uniqueUserId=null;
        $contactnumber=null;
        if (isset($jsonArray['uniqueUserId']) && !empty($jsonArray['uniqueUserId'])) {
            $uniqueUserId = trim($jsonArray['uniqueUserId']);
        } else {
            $resp["message"] = "Unique id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['contactnumber']) && !empty($jsonArray['contactnumber'])) {
            $contactnumber = trim($jsonArray['contactnumber']);
        } else {
            $resp["message"] = "Contact number is required";
            return $this->responsedata(400, 'failed', $resp);
        }

          $where = "user_mobile=".$contactnumber." and unique_deviceid='".$uniqueUserId."'";
         $resp1=$this->UserModel->get_single_user($where);
         if(!empty($resp1)){
            $resp["message"] = "User already exists";
            $resp["user_id"] = $resp1[0]->user_id;
            return $this->responsedata(409, 'failed', $resp);
         }

        $array = array(
            "unique_deviceid" => $uniqueUserId,
            "user_mobile" => $contactnumber,
            'created_at' => CURRENT_DATETIME        
        );

        $resp = $this->UserModel->save_deviceid($array);

        if ($resp > 0) {
            $result["message"] = "successfully inserted data";
            $result["user_id"] = $resp;
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function save_order() {
        $jsonArray = json_decode(file_get_contents('php://input'), true);        
        if (isset($jsonArray['user_mobile']) && !empty($jsonArray['user_mobile'])) {
            $user_mobile = trim($jsonArray['user_mobile']);
        } else {
            $resp["message"] = "Unique id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['unique_deviceid']) && !empty($jsonArray['unique_deviceid'])) {
            $unique_deviceid = trim($jsonArray['unique_deviceid']);
        } else {
            $resp["message"] = "Contact number is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['user_id']) && !empty($jsonArray['user_id'])) {
            $user_id = trim($jsonArray['user_id']);
        } else {
            $resp["message"] = "User id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['total_amount']) && !empty($jsonArray['total_amount'])) {
            $amount = trim($jsonArray['total_amount']);
        } else {
            $resp["message"] = "Amount is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['address']) && !empty($jsonArray['address'])) {
            $address = trim($jsonArray['address']);
        } else {
            $resp["message"] = "Address is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pincode']) && !empty($jsonArray['pincode'])) {
            $pincode = trim($jsonArray['pincode']);
        } else {
            $resp["message"] = "Pincode is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['landmark']) && !empty($jsonArray['landmark'])) {
            $landmark = trim($jsonArray['landmark']);
        } else {
            $resp["message"] = "Landmark is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['products']) && count($jsonArray['products']) > 0) {
            foreach ($jsonArray['products'] AS $product_list) {
                if (!array_key_exists('pdt_id', $product_list)) {
                    $resp["message"] = "Product ID is required";
                    return $this->responsedata(400, 'failed', $resp);
                }

                if (isset($product_list['variant']) && count($product_list['variant']) > 0) {
                    foreach ($product_list['variant'] AS $variant_item) {
                        if (!array_key_exists('var_id', $variant_item) && !array_key_exists('qty', $variant_item)) {
                            $resp["message"] = "Var ID & Quantity is required";
                            return $this->responsedata(400, 'failed', $resp);
                        }
                    }
                } else {
                    $resp["message"] = "Product Variant is required";
                    return $this->responsedata(400, 'failed', $resp);
                }
            }
        } else {
            $resp["message"] = "Product is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        /* if (isset($jsonArray['pdt_id']) && !empty($jsonArray['pdt_id'])) {
          $prdid = trim($jsonArray['pdt_id']);
          } else {
          $resp["message"] = "Product ID is required";
          return $this->responsedata(400, 'failed', $resp);
          }

          if (isset($jsonArray['var_id']) && !empty($jsonArray['var_id'])) {
          $var_id = trim($jsonArray['var_id']);
          } else {
          $resp["message"] = "Varient ID is required";
          return $this->responsedata(400, 'failed', $resp);
          }

          if (isset($jsonArray['qty']) && !empty($jsonArray['qty'])) {
          $qty = trim($jsonArray['qty']);
          } else {
          $resp["message"] = "Quantity is required";
          return $this->responsedata(400, 'failed', $resp);
          } */

        $this->db->trans_start();
        $array = array(
            "user_mobile" => $user_mobile,
            "unique_deviceid" => $unique_deviceid,
            "user_id" => $user_id,
            "order_status" => "created",
            "amount" => $amount,
            'discount_amount' => (isset($jsonArray['discount_amount']) && $jsonArray['discount_amount'] != '') ? $jsonArray['discount_amount'] : NULL,
            'address' => $address,
            'city' => (isset($jsonArray['city']) && $jsonArray['city'] != '') ? $jsonArray['city'] : 'surat',
            'pincode' => $pincode,
            'landmark' => $landmark,
            'order_created_date' => CURRENT_DATETIME,
            'updated_at' => NULL
        );
        $resp_id = $this->UserModel->save_order($array);

        $order_design_insert = array();
        foreach ($jsonArray['products'] AS $product_list) {
            $product_id = $product_list['pdt_id'];
            foreach ($product_list['variant'] AS $variant_item) {
                $order_design_insert[] = array(
                    'order_id' => $resp_id,
                    'pdt_id' => $product_id,
                    'var_id' => $variant_item['var_id'],
                    'qty' => $variant_item['qty'],
                    'created_at' => CURRENT_DATETIME
                );
            }
        }
        $resp2 = $this->UserModel->save_order_design($order_design_insert);

        /* $array2 = array(
          "order_id" => $resp_id,
          "pdt_id" => $prdid,
          "var_id" => $var_id,
          "qty" => $qty,
          'created_at' => CURRENT_DATETIME
          );
          $resp2 = $this->UserModel->save_order_design($array2); */
        $this->db->trans_complete();
        if ($resp2 > 0) {
            $result["message"] = "Order placed successfully";
            $result["order_id"] = $resp_id;
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function get_order() {
        /* pagination attributes */
        $page_no = 1;
        if (is_numeric($this->uri->segment(3))) {
            $page_no = $this->uri->segment(3);
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $this->responsedata(400, 'failed', 'Bad request!');
        } else {
            $jsonArray = json_decode(file_get_contents('php://input'), true);

            if (isset($jsonArray['user_mobile']) && !empty($jsonArray['user_mobile'])) {
                $user_mobile = trim($jsonArray['user_mobile']);
            } else {
                $resp["message"] = "Unique id is required";
                return $this->responsedata(400, 'failed', $resp);
            }

            if (isset($jsonArray['unique_deviceid']) && !empty($jsonArray['unique_deviceid'])) {
                $unique_deviceid = trim($jsonArray['unique_deviceid']);
            } else {
                $resp["message"] = "Contact number is required";
                return $this->responsedata(400, 'failed', $resp);
            }

            if (isset($jsonArray['user_id']) && !empty($jsonArray['user_id'])) {
                $user_id = trim($jsonArray['user_id']);
            } else {
                $resp["message"] = "User id is required";
                return $this->responsedata(400, 'failed', $resp);
            }

            $response['status'] = "200";
            $where = "user_mobile = '" . $user_mobile . "' and unique_deviceid = '" . $unique_deviceid . "' and user_id = '" . $user_id . "'";
            $order_by = "";
            $resp = $this->OrderModel->alldata($page_no, $where, $order_by);
            $this->responsedata(200, 'success', $resp);
        }
    }

    public function update_user_order() {
        $result["message"] = "successfully inserted data";
        $this->responsedata(200, 'success', $result);
    }

    public function responsedata($status_code, $status, $data) {
        json_output($status_code, array('status' => $status, 'data' => $data));
    }

}
