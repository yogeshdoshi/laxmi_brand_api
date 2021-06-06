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
        $this->perPage = 5; 
    }

    public function get_product()
    {

  	/* pagination attributes */
      $page_no  = 1;
      if(is_numeric($this->uri->segment(3))) {
          $page_no = $this->uri->segment(3);
      }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $this->responsedata(400,'failed', 'Bad request!');
        } else {
            $response['status'] = "200";

            $where="is_deleted IS NULL and deleted_at IS NULL and is_active=1";
            $sort=$this->input->get('sort');
            $order_by="";

            if(isset($sort) and $sort>0)
            {
                if($sort==1)
                {

                    $order_by =" product_master.pdt_name ASC";
                }
                else if($sort==2){
                    $order_by ="  product_master.pdt_name DESC";
                }
            }
            $resp = $this->ProductModel->alldata($page_no,$where,$order_by);
           

         

            $this->responsedata(200,'success', $resp);
        }
    }

    public function responsedata($status_code,$status, $data)
    {
        json_output($status_code,array('status' => $status,'data' => $data));
    }

  
}
