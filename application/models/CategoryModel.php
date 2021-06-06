<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends CI_Model {

   
  
    public function alldata()
    {
        return $this->db->select('category_id,category_name,created_date,is_active')->from('category_master')->order_by('category_id','desc')->get()->result();
    }

   
}
