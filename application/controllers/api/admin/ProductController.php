<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');

class ProductController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('admin/ProductModel');
    }

    public function get_product() {
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

            $where = "s.is_deleted IS NULL and s.deleted_at IS NULL and s.is_active=1";
            $sort = $jsonArray['sort'];
            $popularity = $jsonArray['popularity'];
            $order_by = "";

            if (isset($sort) && $sort > 0) {
                if ($sort == 1) {

                    $order_by .= " s.pdt_name ASC,";
                } else if ($sort == 2) {
                    $order_by .= "  s.pdt_name DESC,";
                } else if ($sort == 0) {
                    $order_by .= "  s.pdt_id DESC,";
                }
            }
            if (isset($popularity)) {
                if ($popularity == 1) {

                    $order_by .= " s.populairty DESC";
                }
            }
            $resp = $this->ProductModel->alldata($page_no, $where, $order_by);

            $this->responsedata(200, 'success', $resp);
        }
    }

    function product_detail() {
        $resp = array();
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if ($jsonArray['id']) {
            $resp = $this->ProductModel->fetch_single_product($jsonArray['id']);
            if (count($resp) > 0) {
                $this->responsedata(200, 'success', $resp);
            } else {
                $resp["message"] = "Id is invalid";
                $this->responsedata(400, 'failed', $resp);
            }
        } else {
            $resp["message"] = "Id is required";
            $this->responsedata(400, 'failed', $resp);
        }
    }

    function delete() {
        $response = array();
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if ($jsonArray['id']) {
            $resp = $this->ProductModel->delete_product($jsonArray['id']);
            if ($resp > 0) {
                $response["message"] = "Successfully deleted data";
                $this->responsedata(200, 'success', $response);
            } else {
                $response["message"] = "No record found";
                $this->responsedata(400, 'failed', $response);
            }
        } else {
            $response["message"] = "Invalid Id";
            $this->responsedata(400, 'failed', $response);
        }
    }

    public function insert_product() {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if (isset($jsonArray['pdt_name']) && !empty($jsonArray['pdt_name'])) {
            $pdt_name = trim($jsonArray['pdt_name']);
        } else {
            $resp["message"] = "Product name is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])) {
            $category_id = trim($jsonArray['category_id']);
        } else {
            $resp["message"] = "Category id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pdt_discount_display']) && !empty($jsonArray['pdt_discount_display'])) {
            $pdt_discount_display = trim($jsonArray['pdt_discount_display']);
        } else {
            $resp["message"] = "Product discount is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pdt_about']) && !empty($jsonArray['pdt_about'])) {
            $pdt_about = trim($jsonArray['pdt_about']);
        } else {
            $resp["message"] = "About product is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pdt_storage_uses']) && !empty($jsonArray['pdt_storage_uses'])) {
            $pdt_storage_uses = trim($jsonArray['pdt_storage_uses']);
        } else {
            $resp["message"] = "Product storage usages is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pdt_other_info']) && !empty($jsonArray['pdt_other_info'])) {
            $pdt_other_info = trim($jsonArray['pdt_other_info']);
        } else {
            $resp["message"] = "Product other detail is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])) {
            $is_active = trim($jsonArray['is_active']);
        } else {
            $is_active = 0;
        }
        if (isset($jsonArray['prdt_images']) && !empty($jsonArray['prdt_images'])) {
            $prdt_images = trim($jsonArray['prdt_images']);
        } else {
            $resp["message"] = "Product image is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        $image = $jsonArray['image'];
        $image_name = "";
        if (strlen($image) > 0) {

            $image_name = round(microtime(true) * 1000) . ".jpg";
            $image_upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/laxmibrand/assets/product/image/' . $image_name;
            $flag = file_put_contents($image_upload_dir, base64_decode($image));
        }

        $array = array(
            'pdt_name' => $pdt_name,
            'category_id' => $category_id,
            'pdt_discount_display' => $pdt_discount_display,
            'pdt_about' => $pdt_about,
            'pdt_storage_uses' => $pdt_storage_uses,
            'pdt_other_info' => $pdt_other_info,
            'is_active' => $is_active,
            'prdt_images' => $image_upload_dir,
            'created_date' => CURRENT_DATETIME,
            'updated_at' => NULL,
            'deleted_at' => NULL,
            'is_deleted' => NULL
        );

        $resp = $this->ProductModel->save_product($array);

        $array2 = array(
            "pdt_id" => $resp,
            "var_id" => $jsonArray['var_id'],
            "pdt_price_actual_500gm" => $jsonArray['pdt_price_actual_500gm'],
            "pdt_price_discounted_500gm" => $jsonArray['pdt_price_discounted_500gm'],
            "pdt_price_enable_500gm" => $jsonArray['pdt_price_enable_500gm'],
            "pdt_price_actual_1kg" => $jsonArray['pdt_price_actual_1kg'],
            "pdt_price_discounted_1kg" => $jsonArray['pdt_price_discounted_1kg'],
            "pdt_price_enable_1kg" => $jsonArray['pdt_price_enable_1kg'],
            "pdt_price_actual_2kg" => $jsonArray['pdt_price_actual_2kg'],
            "pdt_price_discounted_2kg" => $jsonArray['pdt_price_discounted_2kg'],
            "pdt_price_enable_2kg" => $jsonArray['pdt_price_enable_2kg'],
            "pdt_price_actual_3kg" => $jsonArray['pdt_price_actual_3kg'],
            "pdt_price_discounted_3kg" => $jsonArray['pdt_price_discounted_3kg'],
            "pdt_price_enable_3kg" => $jsonArray['pdt_price_enable_3kg'],
            "pdt_price_actual_5kg" => $jsonArray['pdt_price_actual_5kg'],
            "pdt_price_discounted_5kg" => $jsonArray['pdt_price_discounted_5kg'],
            "pdt_price_enable_5kg" => $jsonArray['pdt_price_enable_5kg']
        );

        $resp2 = $this->ProductModel->save_varient($array2);

        if ($resp > 0) {
            $result["message"] = "successfully inserted data";
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function update_product() {

        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if (isset($jsonArray['pdt_id']) && !empty($jsonArray['pdt_id'])) {
            $pid = trim($jsonArray['pdt_id']);
        } else {

            $resp["message"] = "Product id is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['pdt_name']) && !empty($jsonArray['pdt_name'])) {
            $pdt_name = trim($jsonArray['pdt_name']);
        } else {

            $resp["message"] = "Product name is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])) {
            $category_id = trim($jsonArray['category_id']);
        } else {
            $resp["message"] = "Category id is required";
            return $this->responsedata(400, 'failed', $resp);
        }


        if (isset($jsonArray['pdt_discount_display']) && !empty($jsonArray['pdt_discount_display'])) {
            $pdt_discount_display = trim($jsonArray['pdt_discount_display']);
        } else {
            $resp["message"] = "Product discount is required";
            return $this->responsedata(400, 'failed', $resp);
        }


        if (isset($jsonArray['pdt_about']) && !empty($jsonArray['pdt_about'])) {
            $pdt_about = trim($jsonArray['pdt_about']);
        } else {
            $resp["message"] = "About product is required";
            return $this->responsedata(400, 'failed', $resp);
        }


        if (isset($jsonArray['pdt_storage_uses']) && !empty($jsonArray['pdt_storage_uses'])) {
            $pdt_storage_uses = trim($jsonArray['pdt_storage_uses']);
        } else {
            $resp["message"] = "Product storage usages is required";
            return $this->responsedata(400, 'failed', $resp);
        }


        if (isset($jsonArray['pdt_other_info']) && !empty($jsonArray['pdt_other_info'])) {
            $pdt_other_info = trim($jsonArray['pdt_other_info']);
        } else {
            $resp["message"] = "Product other detail is required";
            return $this->responsedata(400, 'failed', $resp);
        }


        if (isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])) {
            $is_active = trim($jsonArray['is_active']);
        } else {
            $is_active = 0;
        }
        if (isset($jsonArray['prdt_images']) && !empty($jsonArray['prdt_images'])) {
            $prdt_images = trim($jsonArray['prdt_images']);
        } else {
            $resp["message"] = "Product image is required";
            return $this->responsedata(400, 'failed', $resp);
        }
        $array = array(
            'pdt_name' => $pdt_name,
            'category_id' => $category_id,
            'pdt_discount_display' => $pdt_discount_display,
            'pdt_about' => $pdt_about,
            'pdt_storage_uses' => $pdt_storage_uses,
            'pdt_other_info' => $pdt_other_info,
            'is_active' => $is_active,
            'prdt_images' => $prdt_images,
            'created_date' => CURRENT_DATETIME,
            'updated_at' => NULL,
            'deleted_at' => NULL,
            'is_deleted' => NULL
        );

        $resp = $this->ProductModel->update_product($pid, $array);

        if (isset($resp['rowid']) && !empty($resp['rowid']) && $resp['rowid'] > 0) {
            $ids = $resp['rowid'];

            $array2 = array(
                "pdt_id" => $pid,
                "var_id" => $jsonArray['var_id'],
                "pdt_price_actual_500gm" => $jsonArray['pdt_price_actual_500gm'],
                "pdt_price_discounted_500gm" => $jsonArray['pdt_price_discounted_500gm'],
                "pdt_price_enable_500gm" => $jsonArray['pdt_price_enable_500gm'],
                "pdt_price_actual_1kg" => $jsonArray['pdt_price_actual_1kg'],
                "pdt_price_discounted_1kg" => $jsonArray['pdt_price_discounted_1kg'],
                "pdt_price_enable_1kg" => $jsonArray['pdt_price_enable_1kg'],
                "pdt_price_actual_2kg" => $jsonArray['pdt_price_actual_2kg'],
                "pdt_price_discounted_2kg" => $jsonArray['pdt_price_discounted_2kg'],
                "pdt_price_enable_2kg" => $jsonArray['pdt_price_enable_2kg'],
                "pdt_price_actual_3kg" => $jsonArray['pdt_price_actual_3kg'],
                "pdt_price_discounted_3kg" => $jsonArray['pdt_price_discounted_3kg'],
                "pdt_price_enable_3kg" => $jsonArray['pdt_price_enable_3kg'],
                "pdt_price_actual_5kg" => $jsonArray['pdt_price_actual_5kg'],
                "pdt_price_discounted_5kg" => $jsonArray['pdt_price_discounted_5kg'],
                "pdt_price_enable_5kg" => $jsonArray['pdt_price_enable_5kg']
            );

            $resp2 = $this->ProductModel->update_varient($ids, $array2);
        }

        if ($resp > 0) {
            $result["message"] = "successfully updated data";
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
