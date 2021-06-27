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
		$image_for = $_POST['image_for'];
		$reference_id = $_POST['reference_id'];
		$uniqueId = time() . '-' . mt_rand();
		$imgname = "";
		$imgpath = "";

		if ($image_for == 1) {
			$imgpath = "product/image";
			$imgname = "PR_" . $reference_id . "_" . $uniqueId;
		} else if ($image_for == 2) {
			$imgpath = "category/image";
			$imgname = "CA_" . $reference_id . "_" . $uniqueId;
		}

		$this->load->helper('url');
		$data = array();

		if ($_FILES != NULL) {
			if (!empty($_FILES['file']['name'])) {

				$config['upload_path'] = ASSETS_PATH . $imgpath;
				$config['allowed_types'] = 'jpg|jpeg|png|gif';
				$config['max_size'] = '2000'; // max_size in kb that means 2mb
				$config['file_name'] = $imgname;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('file')) {
					$error = array('error' => $this->upload->display_errors());
					$result["message"] = "Something went wrong";
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

	public function saveproduct(){
		$jsonArray = $_POST;//json_decode(file_get_contents('php://input'),true); 
		if(isset($jsonArray['pdt_name']) && !empty($jsonArray['pdt_name'])){
			$pdt_name= trim($jsonArray['pdt_name']);
		}
		else{
			$resp["message"] = "Product name is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		$imgpath = "product/image";
		// $imgname = "PR_" . $uniqueId;
		$config['upload_path'] = ASSETS_PATH . $imgpath;
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '2000'; // max_size in kb that means 2mb
		// $config['file_name'] = $imgname;
		
        $this->load->library('upload', $config);
		
        $images = array();
		
		$files=$_FILES['image'];
        foreach ($files['name'] as $key => $image) {
			$_FILES['images[]']['name']= $files['name'][$key];
            $_FILES['images[]']['type']= $files['type'][$key];
            $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['images[]']['error']= $files['error'][$key];
            $_FILES['images[]']['size']= $files['size'][$key];
			
			$uniqueId = time() . '-' . mt_rand();
            $fileName = "PR_" .$pdt_name."_". time();

            $images[] = $fileName;

            $config['file_name'] = $fileName;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('images[]')) {
                $this->upload->data();
            } else {
                return false;
            }
        }
		$productimages = implode(', ', $images);
      
		if(isset($jsonArray['category_id']) && !empty($jsonArray['category_id'])){
			$category_id=  trim($jsonArray['category_id']);
		}
		else{
			$resp["message"] = "Category id is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['pdt_discount_display']) && !empty($jsonArray['pdt_discount_display'])){
			$pdt_discount_display=  trim($jsonArray['pdt_discount_display']);
		}
		else{
			$resp["message"] = "Product discount is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['pdt_about']) && !empty($jsonArray['pdt_about'])){
			$pdt_about=  trim($jsonArray['pdt_about']);
		}
		else{
			$resp["message"] = "About product is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['pdt_storage_uses']) && !empty($jsonArray['pdt_storage_uses'])){
			$pdt_storage_uses=  trim($jsonArray['pdt_storage_uses']);
		}
		else{
			$resp["message"] = "Product storage usages is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['pdt_other_info']) && !empty($jsonArray['pdt_other_info'])){
			$pdt_other_info=  trim($jsonArray['pdt_other_info']);
		}
		else{
			$resp["message"] = "Product other detail is required";
			return $this->responsedata(400, 'failed', $resp);
		}
		
		if(isset($jsonArray['is_active']) && !empty($jsonArray['is_active'])){
			$is_active=  trim($jsonArray['is_active']);
		}
		else{
			$is_active=  0;
		}
		if(isset($productimages) && !empty($productimages)){
			$prdt_images=  $productimages;
		}
		else{
			$resp["message"] = "Product image is required";
			return $this->responsedata(400, 'failed', $resp);
		}

		
		$array =array(
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
			'is_deleted' =>  NULL
		);

		$resp=$this->ProductModel->save_product($array);
		
		$array2 =array(
			"pdt_id"=> $resp,
			"var_id"=> $jsonArray['var_id'],
			"pdt_price_actual_500gm"=> $jsonArray['pdt_price_actual_500gm'],
			"pdt_price_discounted_500gm"=> $jsonArray['pdt_price_discounted_500gm'],
			"pdt_price_enable_500gm"=> $jsonArray['pdt_price_enable_500gm'],
			"pdt_price_actual_1kg"=> isset($jsonArray['pdt_price_actual_1kg']) ? $jsonArray['pdt_price_actual_1kg']:0,
			"pdt_price_discounted_1kg"=> isset($jsonArray['pdt_price_discounted_1kg']) ? $jsonArray['pdt_price_discounted_1kg']:0,
			"pdt_price_enable_1kg"=> isset($jsonArray['pdt_price_enable_1kg']) ? $jsonArray['pdt_price_enable_1kg']:0,
			"pdt_price_actual_2kg"=> isset($jsonArray['pdt_price_actual_2kg']) ? $jsonArray['pdt_price_actual_2kg']:0,
			"pdt_price_discounted_2kg"=> isset($jsonArray['pdt_price_discounted_2kg']) ? $jsonArray['pdt_price_discounted_2kg']:0,
			"pdt_price_enable_2kg"=> isset($jsonArray['pdt_price_enable_2kg']) ? $jsonArray['pdt_price_enable_2kg']:0,
			"pdt_price_actual_3kg"=> isset($jsonArray['pdt_price_actual_3kg']) ? $jsonArray['pdt_price_actual_3kg']:0,
			"pdt_price_discounted_3kg"=> isset($jsonArray['pdt_price_discounted_3kg']) ? $jsonArray['pdt_price_discounted_3kg']:0,
			"pdt_price_enable_3kg"=> isset($jsonArray['pdt_price_enable_3kg']) ? $jsonArray['pdt_price_enable_3kg']:0,
			"pdt_price_actual_5kg"=> isset($jsonArray['pdt_price_actual_5kg']) ? $jsonArray['pdt_price_actual_5kg']:0,
			"pdt_price_discounted_5kg"=> isset($jsonArray['pdt_price_discounted_5kg']) ? $jsonArray['pdt_price_discounted_5kg']:0,
			"pdt_price_enable_5kg"=> isset($jsonArray['pdt_price_enable_5kg']) ? $jsonArray['pdt_price_enable_5kg']:0
		);

		$resp2=$this->ProductModel->save_varient($array2);
		
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


	public function responsedata($status_code, $status, $data)
	{
		json_output($status_code, array('status' => $status, 'data' => $data));
	}
}
