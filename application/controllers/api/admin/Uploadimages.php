<?php

// require APPPATH . 'libraries/Rest_Controller.php';

class Uploadimages extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('admin/UploadModel');
		$this->load->model('admin/ProductModel');
	}

	public function  index()
	{
		$result = array();
		$res = array();

		$image_for =  isset($_POST['image_for']) ? $_POST['image_for'] : 0;
		$reference_id = isset($_POST['reference_id']) ? $_POST['reference_id'] : 0;
		$uniqueId = time() . '-' . mt_rand();
		$imgname = "";
		$imgpath = "";

		if ($image_for == 1) {
			$imgpath = "product/image";
			$imgname = "PR_" . $reference_id . "_" . $uniqueId;
		} else if ($image_for == 2) {
			$imgpath = "category/image";
			$imgname = "CA_" . $reference_id . "_" . $uniqueId;
		} else if ($image_for == 3) {
			$imgpath = "slider/image";
			$imgname = "AD_"  . $uniqueId;
		} else if ($image_for == 4) {
			$imgpath = "slider/image";
			$imgname = "SL_" . $uniqueId;
		}

		$this->load->helper('url');
		$data = array();

		if ($_FILES != NULL) {
			if (!empty($_FILES['file']['name']) && $_FILES['file']['name'] != "") {

				$config['upload_path'] = ASSETS_PATH . $imgpath;
				$config['allowed_types'] = 'jpg|jpeg|png|gif';
				$config['max_size'] = '2000'; // max_size in kb that means 2mb
				$config['file_name'] = $imgname;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('file')) {
					$error = array('error' => $this->upload->display_errors());
					$result["message"] = $error;
					$this->responsedata(400, 'failed', $result);
				} else {
					$data = array('image_metadata' => $this->upload->data());
					$datas = array(
						'image_for' => $image_for,
						'reference_id' => $reference_id,
						'image_name' => $data['image_metadata']['orig_name'],
						'image_path' => $data['image_metadata']['full_path'],
						'image_type' => $data['image_metadata']['image_type'],
						'created_at' => CURRENT_DATETIME
					);

					$this->UploadModel->save_upload($datas);
					$result["message"] = "successfully uploaded files";
					$this->responsedata(200, 'success', $result);
				}
			} else {
				$result["message"] = "Something went wrong";
				$this->responsedata(400, 'failed', $result);
			}
		}
	}

	public function saveproduct()
	{

		$jsonArray = $_POST; //json_decode(file_get_contents('php://input'),true); 
		if (isset($jsonArray['pdt_name']) && !empty($jsonArray['pdt_name'])) {
			$pdt = trim($jsonArray['pdt_name']);
			$pdt_name = str_replace(' ', '_', $pdt);
			$pname = trim($jsonArray['pdt_name']);
		} else {
			$resp["message"] = "Product name is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		if (!file_exists(ASSETS_PATH . 'product/' . $pdt_name)) {
			mkdir(ASSETS_PATH . 'product/' . $pdt_name, 0777, true);
		}
		$imgpath = "product/" . $pdt_name;
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

			$uniqueId = time() . '-' . mt_rand();
			$fileName = "PR_" . $pdt_name . "_" . time();

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
			$img[] = base_url() . ASSETS_PATH . 'product/' . $pdt_name . "/" . $im . ".jpg";
			$img_path = $img;
		}

		$productimages = implode(', ', $img_path);

		if (isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])) {
			$category_id =  trim($jsonArray['category_id']);
		} else {
			$resp["message"] = "Category id is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if (isset($jsonArray['pdt_discount_display']) && !empty($jsonArray['pdt_discount_display'])) {
			$pdt_discount_display =  trim($jsonArray['pdt_discount_display']);
		} else {
			$resp["message"] = "Product discount is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if (isset($jsonArray['pdt_about']) && !empty($jsonArray['pdt_about'])) {
			$pdt_about =  trim($jsonArray['pdt_about']);
		} else {
			$resp["message"] = "About product is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if (isset($jsonArray['pdt_storage_uses']) && !empty($jsonArray['pdt_storage_uses'])) {
			$pdt_storage_uses =  trim($jsonArray['pdt_storage_uses']);
		} else {
			$resp["message"] = "Product storage usages is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if (isset($jsonArray['pdt_other_info']) && !empty($jsonArray['pdt_other_info'])) {
			$pdt_other_info =  trim($jsonArray['pdt_other_info']);
		} else {
			$resp["message"] = "Product other detail is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if (isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])) {
			$is_active =  trim($jsonArray['is_active']);
		} else {
			$is_active =  0;
		}
		if (isset($productimages) && !empty($productimages)) {
			$prdt_images =  $productimages;
		} else {
			$resp["message"] = "Product image is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		// varient validations
		$var_type_count = 0;
		if (isset($jsonArray['var_type']) && !empty($jsonArray['var_type']) && is_array($jsonArray['var_type'])) {
			$var_type_count =  count($jsonArray['var_type']);
		} else {
			$resp["message"] = "Varient type is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		$var_is_active_count = 0;
		if (isset($jsonArray['var_is_active']) && !empty($jsonArray['var_is_active']) && is_array($jsonArray['var_is_active'])) {
			$var_is_active_count =  count($jsonArray['var_is_active']);
		} else {
			$resp["message"] = "Varient status is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		$var_discount_price_count = 0;
		if (isset($jsonArray['var_discount_price']) && !empty($jsonArray['var_discount_price']) && is_array($jsonArray['var_discount_price'])) {
			$var_discount_price_count =  count($jsonArray['var_discount_price']);
		} else {
			$resp["message"] = "Varient discount price is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		if (($var_discount_price_count == $var_is_active_count) && ($var_is_active_count == $var_type_count)) {



			$this->db->trans_start();

			$array = array(
				'pdt_name' => $pname,
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
				'is_deleted' =>  NULL
			);

			$resp = $this->ProductModel->save_product($array);

			$varient_array = array();

			foreach ($jsonArray['var_type'] as $k => $v) {
				$varient_array[] = array(
					"pdt_id" => $resp,
					"var_id" =>  $k,
					"is_active" =>  isset($jsonArray['var_is_active'][$k]) ? $jsonArray['var_is_active'][$k] : 1,
					"var_type" => $v,
					"var_discount_price" =>  isset($jsonArray['var_discount_price'][$k]) ? $jsonArray['var_discount_price'][$k] : 0,
					'created_at' => CURRENT_DATETIME,
				);
			}

			$resp2 = $this->ProductModel->save_varient($varient_array);
			$this->db->trans_complete();

			if ($resp > 0) {
				$result["message"] = "successfully inserted data";
				$this->responsedata(200, 'success', $result);
			} else {
				$result["message"] = "Something went wrong";
				$this->responsedata(400, 'failed', $result);
			}
		} else {
			$resp["message"] = "varient data is invalid";
			return $this->responsedata(400, 'failed', $resp);
		}
	}

	public function saveslider()
	{

		if (isset($_FILES['image']) && !empty($_FILES['image'])) {
			$file_type = $_FILES['image']['type']; //returns the mimetype

			$allowed = array("image/jpeg", "image/jpg", "image/png", "application/png");
			foreach ($file_type as $fl) {
				if (!in_array($fl, $allowed)) {
					$resp["message"] = "Only jpeg,jpg,png is allowed image type";
					return $this->responsedata(400, 'failed', $resp);
				}
			}
		} else {
			$resp["message"] = "Image is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		$pdt_name = "";
		if (!file_exists(ASSETS_PATH . 'slider')) {
			mkdir(ASSETS_PATH . 'slider', 0777, true);
		}
		$imgpath = "slider";
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

			$uniqueId = time() . '-' . mt_rand();
			$fileName = "SL_" . $uniqueId;

			$images[] = $fileName;

			$config['file_name'] = $fileName;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('images[]')) {
				$up = $this->upload->data();

				if (!$up) {
					$result["message"] = $files['error'];
					$this->responsedata(400, 'failed', $result);
				}
			} else {
				$result["message"] = "something went wrong";
				$this->responsedata(400, 'failed', $result);
			}
		}
		$img_path = array();
		foreach ($images as $k => $im) {
			$img[] = base_url() . ASSETS_PATH . 'slider/' . $im . ".jpg";
			$img_path = $img;
		}

		foreach ($img_path as $ims) {
			$array = array(
				'image_path' => $ims,
				'created_at' => CURRENT_DATETIME
			);

			$resp = $this->UploadModel->save_slider($array);
		}
		if ($resp > 0) {
			$result["message"] = "successfully inserted data";
			$this->responsedata(200, 'success', $result);
		} else {
			$result["message"] = "Something went wrong";
			$this->responsedata(400, 'failed', $result);
		}
	}

	public function saveadvertisement()
	{
		if (isset($_FILES['image']) && !empty($_FILES['image'])) {
			$file_type = $_FILES['image']['type']; //returns the mimetype

			$allowed = array("image/jpeg", "image/jpg", "image/png", "application/png");
			foreach ($file_type as $fl) {
				if (!in_array($fl, $allowed)) {
					$resp["message"] = "Only jpeg,jpg,png is allowed image type";
					return $this->responsedata(400, 'failed', $resp);
				}
			}
		} else {
			$resp["message"] = "Image is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		$pdt_name = "";
		if (!file_exists(ASSETS_PATH . 'advertisement')) {
			mkdir(ASSETS_PATH . 'advertisement', 0777, true);
		}
		$imgpath = "advertisement";
		$config['upload_path'] = ASSETS_PATH . $imgpath;
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '5000'; // max_size in kb that means 5mb

		$this->load->library('upload', $config);

		$images = array();

		$files = $_FILES['image'];

		foreach ($files['name'] as $key => $image) {
			$_FILES['images[]']['name'] = $files['name'][$key];
			$_FILES['images[]']['type'] = $files['type'][$key];
			$_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['images[]']['error'] = $files['error'][$key];
			$_FILES['images[]']['size'] = $files['size'][$key];

			$uniqueId = time() . '-' . mt_rand();
			$fileName = "SL_" . $uniqueId;

			$images[] = $fileName;

			$config['file_name'] = $fileName;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('images[]')) {
				$up = $this->upload->data();

				if (!$up) {
					$result["message"] = $files['error'];
					$this->responsedata(400, 'failed', $result);
				}
			} else {
				$result["message"] = "something went wrong";
				$this->responsedata(400, 'failed', $result);
			}
		}
		$img_path = array();
		foreach ($images as $k => $im) {
			$img[] = base_url() . ASSETS_PATH . 'advertisement/' . $im . ".jpg";
			$img_path = $img;
		}

		foreach ($img_path as $ims) {
			$array = array(
				'image_path' => $ims,
				'created_at' => CURRENT_DATETIME
			);
			$resp = $this->UploadModel->save_advertisement($array);
		}

		if ($resp > 0) {
			$result["message"] = "successfully inserted data";
			$this->responsedata(200, 'success', $result);
		} else {
			$result["message"] = "Something went wrong";
			$this->responsedata(400, 'failed', $result);
		}
	}
	public function responsedata($status_code, $status, $data)
	{
		json_output($status_code, array('status' => $status, 'data' => $data));
	}
}
