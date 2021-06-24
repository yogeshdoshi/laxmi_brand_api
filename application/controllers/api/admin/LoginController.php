<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class LoginController extends REST_Controller {

    public function __construct() { 
        parent::__construct();
        
        // Load the LoginModel model
        $this->load->model('admin/LoginModel');
    }
    
    public function login_post() {
        // Get the post data
        $email = $this->post('email');
        $password = $this->post('password');
        
        // Validate the post data
        if(!empty($email) && !empty($password)){
            
            // Check if any LoginModel exists with the given credentials
            $con['returnType'] = 'single';
            $con['conditions'] = array(
                'email' => $email,
                'password' => md5($password),
                'status' => 1
            );
            $LoginModel = $this->LoginModel->getRows($con);
            
            if($LoginModel){
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'LoginModel login successful.',
                    'data' => $LoginModel
                ], REST_Controller::HTTP_OK);
            }else{
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response("Wrong email or password.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            // Set the response and exit
            $this->response("Provide email and password.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}