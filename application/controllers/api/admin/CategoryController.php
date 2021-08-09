<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');

class CategoryController extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('pagination');
        $this->load->model('admin/CategoryModel');
    }

    public function get_category() {

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

            $where = "is_deleted IS NULL and deleted_at IS NULL and is_active=1";
            // $sort=$this->input->post('sort');
            // $popularity=$this->input->post('popularity');
            $order_by = "";

            $resp = $this->CategoryModel->alldata($page_no, $where, $order_by);

            $this->responsedata(200, 'success', $resp);
        }
    }

    public function insert_category() {
        $jsonArray = $_POST; //json_decode(file_get_contents('php://input'),true); 

        if (isset($jsonArray['category_name']) && !empty($jsonArray['category_name'])) {
            $category_name = trim($jsonArray['category_name']);
        } else {
            $resp["message"] = "Category name is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])) {
            $is_active = trim($jsonArray['is_active']);
        } else {
            $is_active = 0;
        }


        // check same category available
        $check = $this->CategoryModel->fetch_single_category_single_category($category_name);
        if ($check > 0) {
            $resp["message"] = "Category already exist!";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (!file_exists(ASSETS_PATH . 'category/' . $category_name)) {
            mkdir(ASSETS_PATH . 'category/' . $category_name, 0777, true);
        }
        $imgpath = "category/" . $category_name;
        // $imgname = "PR_" . $uniqueId;
        $config['upload_path'] = ASSETS_PATH . $imgpath;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = '5000'; // max_size in kb that means 5mb
        // $config['file_name'] = $imgname;

        $this->load->library('upload', $config);

        $images = array();

        $files = $_FILES['image'];

        foreach ($files['name'] as $key => $image) {

            print_r($_FILES['image']);
            echo 'this is key'.$key;
            echo 'this is image'.$image;
            $_FILES['images[]']['name'] = $files['name'][$key];
            $_FILES['images[]']['type'] = $files['type'][$key];
            $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images[]']['error'] = $files['error'][$key];
            $_FILES['images[]']['size'] = $files['size'][$key];

            $ext = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
            $uniqueId = time() . '-' . mt_rand();
            $fileName = "PR_" . str_replace(' ', '_', $category_name) . "_" . time() . '.' . $ext;

            $images[] = $fileName;
            $config['file_name'] = $fileName;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('images[]')) {
                $this->upload->data();
            } else {
                $result["message"] = $files['error'][$key];
                $this->responsedata(400, 'failed', $result);
            }
        }
        $img_path = array();
        foreach ($images as $k => $im) {
            $img[] = base_url() . ASSETS_PATH . 'category/' . $category_name . "/" . $im;
            $img_path = $img;
        }

        $categoryimages = implode(', ', $img_path);

        $array = array(
            'category_name' => $category_name,
            'is_active' => $is_active,
            'image' => $categoryimages,
            'created_date' => CURRENT_DATETIME
        );

        $resp = $this->CategoryModel->save_category($array);

        if ($resp > 0) {
            $result["message"] = "successfully inserted data";
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function update_category() {
        $jsonArray = $_POST; //json_decode(file_get_contents('php://input'),true); 

        if (isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])) {
            $category_id = trim($jsonArray['category_id']);
        } else {
            $resp["message"] = "Category ID is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['category_name']) && !empty($jsonArray['category_name'])) {
            $category_name = trim($jsonArray['category_name']);
        } else {
            $resp["message"] = "Category name is required";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])) {
            $is_active = trim($jsonArray['is_active']);
        } else {
            $is_active = 0;
        }


        // check same category available
        $check = $this->CategoryModel->fetch_single_category($category_name, $category_id);
        if ($check > 0) {
            $resp["message"] = "Category already exist!";
            return $this->responsedata(400, 'failed', $resp);
        }

        if (!file_exists(ASSETS_PATH . 'category/' . $category_name)) {
            mkdir(ASSETS_PATH . 'category/' . $category_name, 0777, true);
        }
        $imgpath = "category/" . $category_name;
        // $imgname = "PR_" . $uniqueId;
        $config['upload_path'] = ASSETS_PATH . $imgpath;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = '5000'; // max_size in kb that means 5mb
        // $config['file_name'] = $imgname;

        $this->load->library('upload', $config);

        $images = array();

        $files = $_FILES['image'];

        foreach ($files['name'] as $key => $image) {
            $_FILES['images[]']['name'] = $files['name'][$key];
            $_FILES['images[]']['type'] = $files['type'][$key];
            $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images[]']['error'] = $files['error'][$key];
            $_FILES['images[]']['size'] = $files['size'][$key];

            $ext = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
            $uniqueId = time() . '-' . mt_rand();
            $fileName = "PR_" . str_replace(' ', '_', $category_name) . "_" . time() . '.' . $ext;

            $images[] = $fileName;
            $config['file_name'] = $fileName;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('images[]')) {
                $this->upload->data();
            } else {
                $result["message"] = $files['error'][$key];
                $this->responsedata(400, 'failed', $result);
            }
        }
        $img_path = array();
        foreach ($images as $k => $im) {
            $img[] = base_url() . ASSETS_PATH . 'category/' . $category_name . "/" . $im;
            $img_path = $img;
        }

        $categoryimages = implode(', ', $img_path);

        $array = array(
            'category_name' => $category_name,
            'is_active' => $is_active,
            'image' => $categoryimages,
            'updated_date' => CURRENT_DATETIME
        );

        /* $jsonArray = json_decode(file_get_contents('php://input'), true);
          if (isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])) {
          $category_id = trim($jsonArray['category_id']);
          } else {

          $resp["message"] = "Category id is required";
          return $this->responsedata(400, 'failed', $resp);
          }
          if (isset($jsonArray['category_name']) && !empty($jsonArray['category_name'])) {
          $category_name = trim($jsonArray['category_name']);
          } else {

          $resp["message"] = "Category name is required";
          return $this->responsedata(400, 'failed', $resp);
          }

          if (isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])) {
          $is_active = trim($jsonArray['is_active']);
          } else {
          $is_active = 0;
          }

          $array = array(
          'category_name' => $category_name,
          'is_active' => $is_active,
          'created_date' => CURRENT_DATETIME
          ); */

        $resp = $this->CategoryModel->update_category($category_id, $array);

        if ($resp > 0) {
            $result["message"] = "successfully updated data";
            $this->responsedata(200, 'success', $result);
        } else {
            $result["message"] = "Something went wrong";
            $this->responsedata(400, 'failed', $result);
        }
    }

    public function delete_category() {
        $response = array();
        $jsonArray = json_decode(file_get_contents('php://input'), true);

        if (isset($jsonArray['id']) && $jsonArray['id'] != '') {
            $resp = $this->CategoryModel->delete_category($jsonArray['id']);
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

    public function responsedata($status_code, $status, $data) {
        json_output($status_code, array('status' => $status, 'data' => $data));
    }

}
