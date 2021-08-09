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
            'tableName' => 'image_promotional_slider_master',
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
    
     function delete_existing_promotional_slider() {
        $array = array(
            'tableName' => 'image_promotional_slider_master',
            'update' => array(
                'is_deleted' => 1,
                'deleted_at' => CURRENT_DATETIME
            )            
        );
        return $this->MY_Model->updateData($array);
    }

    function delete_existing_advertisement_slider_img() {
        $array = array(
            'tableName' => 'image_advertisement_master',
            'update' => array(
                'is_deleted' => 1,
                'deleted_at' => CURRENT_DATETIME
            )
            
        );
        return $this->MY_Model->updateData($array);
    }
     
     

      public function get_existing_active_promotional_sliders($where) {
        $array =  array(
                'getType' => 'result',
                'tableName' => 'image_promotional_slider_master',
                'select' => 'image_path',
                'where' => $where                
            );
        $resp = $this->MY_Model->getData($array);

        return $resp;
    }

public function get_existing_active_marketing_sliders($where) {
        $array =  array(
                'getType' => 'result',
                'tableName' => 'image_advertisement_master',
                'select' => 'image_path',
                'where' => $where                
            );
        $resp = $this->MY_Model->getData($array);

        return $resp;
    }
    
}