<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {

    public function alldata($page_no,$where,$order_by)
    {

       

        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'admin/product',
                'uri_segment' => 3
            ),
            'data' => array(
                'getType'   => 'result',
                'tableName' => 'product_master',
                'select'    => 'pdt_id,pdt_name,category_id,is_active,pdt_about,created_date',
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        $data['result']  = $resp['result'];
      
        return $data;
        	/* custom pagination function */
		
        // return $resp;
        // return $this->db->select('pdt_id,pdt_name,category_id,is_active,pdt_about,created_date')->from('product_master')->order_by('pdt_id','desc')->get()->result();
    }

   
    function getRows($params = array()){ 

        $this->table = 'product_master'; 
        $this->db->select('*'); 
        $this->db->from($this->table); 
         
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
            if(array_key_exists("pdt_id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')){ 
                if(!empty($params['pdt_id'])){ 
                    $this->db->where('pdt_id', $params['pdt_id']); 
                } 
                $query = $this->db->get(); 
                $result = $query->row_array(); 
            }else{ 
                $this->db->order_by('pdt_id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
                 
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
         
        // Return fetched data 
        return $result; 
    } 

}
