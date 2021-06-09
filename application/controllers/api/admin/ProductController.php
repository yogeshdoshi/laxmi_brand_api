<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class ProductController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('admin/ProductModel');
    }

    public function get_product()
    {
        /* pagination attributes */
        $page_no  = 1;
        if (is_numeric($this->uri->segment(3))) {
            $page_no = $this->uri->segment(3);
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $this->responsedata(400, 'failed', 'Bad request!');
        } else {
            $response['status'] = "200";

            $where = "is_deleted IS NULL and deleted_at IS NULL and is_active=1";
            $sort = $this->input->post('sort');
            $popularity = $this->input->post('popularity');
            $order_by = "";

            if (isset($sort) && $sort > 0) {
                if ($sort == 1) {

                    $order_by .= " product_master.pdt_name ASC,";
                } else if ($sort == 2) {
                    $order_by .= "  product_master.pdt_name DESC,";
                }
            }
            if (isset($popularity)) {
                if ($popularity == 1) {

                    $order_by .= " product_master.populairty DESC";
                }
            }
            $resp = $this->ProductModel->alldata($page_no, $where, $order_by);


            $this->responsedata(200, 'success', $resp);
        }
    }

    function product_detail()
    {
        if ($this->input->post('id')) {
            $resp = $this->ProductModel->fetch_single_product($this->input->post('id'));

            if (count($resp) > 0) {
                $this->responsedata(200, 'success', $resp);
            } else {
                $resp["message"] = "No record found";
                $this->responsedata(400, 'failed', $resp);
            }
        }
    }

    function delete()
	{
		if($this->input->post('id'))
		{
            $resp=$this->ProductModel->delete_product($this->input->post('id'));
			if($resp>0)
			{
                $this->responsedata(200, 'success', $resp);
			}
			else
			{
                $resp["message"] = "No record found";
                $this->responsedata(400, 'failed', $resp);
			}
		}
        else{
            $resp["message"] = "Invalid Id";
            $this->responsedata(400, 'failed', $resp);
        }
	}

    public function insert_product()
    {
       

		$validation = array(
			
			array(
				'field' => 'pdt_name',
				'label' => 'Product Name',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
			array(
				'field' => 'category_id',
				'label' => 'Category Id',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
            array(
				'field' => 'pdt_discount_display',
				'label' => 'product discount',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
            array(
				'field' => 'pdt_about',
				'label' => 'product about',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
            array(
				'field' => 'pdt_storage_uses',
				'label' => 'product storage uses',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
            array(
				'field' => 'is_active',
				'label' => 'is active',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
            array(
				'field' => 'prdt_images',
				'label' => 'product images',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			),
            array(
				'field' => 'pdt_other_info',
				'label' => 'product other info',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => '%s is required.'
				)
			)
			
		);

		$this->form_validation->set_rules($validation);
       
		if($this->form_validation->run() == FALSE) { 

            $resp["message"] =  validation_errors();
            $this->responsedata(400, 'failed', $resp);
		} else { 
       
			$array =array(
				
					'pdt_name' => trim($this->input->post('pdt_name')),
					'category_id' => trim($this->input->post('category_id')),
					'pdt_discount_display' => trim($this->input->post('pdt_discount_display')),
					'pdt_about' => trim($this->input->post('pdt_about')),
					'pdt_storage_uses' => trim($this->input->post('pdt_storage_uses')),
					'pdt_other_info' => trim($this->input->post('pdt_other_info')),
					'is_active' => trim($this->input->post('is_active')),
					'prdt_images' => trim($this->input->post('prdt_images')),
					'created_date' => CURRENT_DATETIME,
					'updated_at' => NULL,
					'deleted_at' => NULL,
					'is_deleted' =>  NULL
				
				
			);

            $resp=$this->ProductModel->save_product($array);
			if($resp>0)
			{
                $this->responsedata(200, 'success', $resp);
			}
			else
			{
                $resp["message"] = "Something went wrong";
                $this->responsedata(400, 'failed', $resp);
			}

    }
}

    public function responsedata($status_code, $status, $data)
    {
        json_output($status_code, array('status' => $status, 'data' => $data));
    }
}
