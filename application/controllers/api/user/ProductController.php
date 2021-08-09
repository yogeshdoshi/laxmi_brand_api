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
		$this->load->model('user/ProductModel');
	}

	public function get_product()
    {
		$jsonArray = json_decode(file_get_contents('php://input'),true); 
        /* pagination attributes */
        $page_no  = 1;
        if (is_numeric($this->uri->segment(4))) {
            $page_no = $this->uri->segment(4);
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $this->responsedata(400, 'failed', 'Bad request!');
        } else {
            $response['status'] = "200";
			$category_id=isset($jsonArray['category_id'])?$jsonArray['category_id']:0;
            $where = "s.is_deleted IS NULL and s.deleted_at IS NULL and s.is_active=1 and s.category_id=".$category_id;
            $sort = $jsonArray['sort'];
            $popularity = $jsonArray['popularity'];
            $order_by = "";

            if (isset($sort) && $sort > 0) {
                if ($sort == 1) {

                    $order_by .= " s.pdt_name ASC,";
                } else if ($sort == 2) {
                    $order_by .= "  s.pdt_name DESC,";
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

    public function get_whats_app_link(){
        $resp=$this->ProductModel->fetch_whatsapp_grp_link();
    }


	public function responsedata($status_code, $status, $data)
    {
        json_output($status_code, array('status' => $status, 'data' => $data));
    }

}