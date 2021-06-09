<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends CI_Model {

   
    public function alldata($page_no,$where,$order_by)
    {

        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'admin/category',
                'uri_segment' => 3
            ),
            'data' => array(
                'getType'   => 'result',
                'tableName' => 'category_master',
                'select'    => 'category_id,category_name,created_date,is_active',
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        $data['result']  = $resp['result'];
      
        return $data;
        
    }
    public function alldata2()
    {
        return $this->db->select('category_id,category_name,created_date,is_active')->from('category_master')->order_by('category_id','desc')->get()->result();
    }

   
}
