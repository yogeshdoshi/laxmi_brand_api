<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');

class OrderController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('admin/OrderModel');
    }

    public function get_order() {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        /* pagination attributes */
        $page_no = 1;
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

    public function update_order() {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if (isset($jsonArray['order_id']) && !empty($jsonArray['order_id'])) {
            $pid = trim($jsonArray['order_id']);
        } else {
            $resp["message"] = "order id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['order_status']) && !empty($jsonArray['order_status'])) {
            $order_status = trim($jsonArray['order_status']);
        } else {
            $resp["message"] = "order status is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['total_order_amount']) && !empty($jsonArray['total_order_amount'])) {
            $amount = trim($jsonArray['total_order_amount']);
        } else {
            $resp["message"] = "order total amount is required";
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
                        if (!array_key_exists('var_id', $variant_item) && !array_key_exists('var_qty', $variant_item)) {
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

        $array = array(
            'order_status' => $order_status,
            'discount_amount' => (isset($jsonArray['discount_amount']) && $jsonArray['discount_amount'] != '') ? $jsonArray['discount_amount'] : NULL,
            'amount' => $amount,
            'address' => $address,
            'city' => (isset($jsonArray['city']) && $jsonArray['city'] != '') ? $jsonArray['city'] : 'surat',
            'pincode' => $pincode,
            'landmark' => $landmark,
            'updated_at' => CURRENT_DATETIME
        );
        $resp = $this->OrderModel->update_order($pid, $array);

        foreach ($jsonArray['products'] AS $product_list) {
            $product_id = $product_list['pdt_id'];
            foreach ($product_list['variant'] AS $variant_item) {
                // order design details
                $where = array('order_id' => $pid, 'pdt_id' => $product_id, 'var_id' => $variant_item['var_id']);
                $update_arr = array('qty' => $variant_item['var_qty']);
                $this->OrderModel->update_order_design($where, $update_arr);

                // product variants
                $where = array('rowid' => $variant_item['rowid'], 'pdt_id' => $product_id, 'var_id' => $variant_item['var_id']);
                $update_arr = array('var_type' => $variant_item['var_type'], 'var_discount_price' => $variant_item['var_discount_price'], 'var_actual_price' => $variant_item['var_actual_price']);
                $this->OrderModel->update_product_variant($where, $update_arr);
            }
        }

        if ($resp > 0) {
            $result["message"] = "Order details updated successfully";
            $result["order_id"] = $pid;
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function responsedata($status_code, $status, $data) {
        json_output($status_code, array('status' => $status, 'data' => $data));
    }

}
