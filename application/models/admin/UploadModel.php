<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UploadModel extends CI_Model{
 
    function save_upload($data){
        $array = array(
            'tableName' => 'image_master',
            'insert'    =>  $data
        );

        return $this->MY_Model->insertData($array);
    }

    function save_slider($data){
        $array = array(
            'tableName' => 'image_slider_master',
            'insert'    =>  $data
        );

        return $this->MY_Model->insertData($array);
    }
 
    function save_advertisement($data){
        $array = array(
            'tableName' => 'image_advertisement_master',
            'insert'    =>  $data
        );

        return $this->MY_Model->insertData($array);
    }
     
}