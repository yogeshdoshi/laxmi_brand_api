<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryController extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
       
    }

	public function index()
	{
		$page_no=1;
		$array = array(
			'pagination' => array(
				'page_no'     => $page_no,
				'per_page'    => PER_PAGE,
				'link'        => base_url('admin/product'),
				'uri_segment' => 3
			),
			'data' => array(
				'getType'   => 'result',
				'tableName' => 'product_master',
				'select'    => 'pdt_id,pdt_name,category_id,is_active,pdt_about,created_date',
				// 'where' 	=> "$where2"
			)
		);
     
		$resp = $this->my_pagination->paginate($array);
		print_r($resp);die;

        // print_r($this->input->post());die;
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
            $response['status']="200";
            $resp = $this->CategoryModel->alldata();
			json_output(200,array('status' => 200,'data' => $resp));
            // json_output($response['status'],($resp));
		}
	}




}
