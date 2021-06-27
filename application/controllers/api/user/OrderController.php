<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
defined('BASEPATH') or exit('No direct script access allowed');


class OrderController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('pagination');
		$this->load->model('admin/OrderModel');
	}
}