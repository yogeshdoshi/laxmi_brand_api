<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class UserModel extends CI_Model {

    function save_deviceid($data)
    {
        $array = array(
            'tableName' => 'user_device',
            'insert'    =>  $data
        );
        return $this->MY_Model->insertData($array);
    }

    function save_order($data)
    {
        $array = array(
            'tableName' => 'order_master',
            'insert'    =>  $data
        );
        return $this->MY_Model->insertData($array);
    }
    function save_order_design($data)
    {
        $array = array(
            'tableName' => 'order_design_details',
            'insert'    =>  $data
        );
        return $this->MY_Model->insertData($array);
    }
}