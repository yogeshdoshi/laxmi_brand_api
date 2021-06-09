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
                'select'    => 'pdt_id,pdt_name,category_id,is_active,pdt_about,created_date,populairty',
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        $data['result']  = $resp['result'];
      
        return $data;
        
    }

    function fetch_single_product($id)
	{
        $where="pdt_id = ".$id;
        $data= array(
            'getType'   => 'result',
            'tableName' => 'product_master',
            'select'    => 'pdt_id,pdt_name,category_id,is_active,pdt_about,created_date,populairty',
            'where'	 	=> array('is_deleted' => NULL, 'pdt_id' => $id)
        );
		$result = $this->MY_Model->getData($data);
		return $result;
	}

    function delete_product($id)
    {
        $array = array(
			'tableName' => 'product_master',
			'update'    =>  array(
				'is_deleted' => 1,
				'deleted_at' => CURRENT_DATETIME
			),
			'where'    => array(
				'is_deleted' => NULL,
				'pdt_id' => $id
			)
		);
		return $this->MY_Model->updateData($array);
    }

    function save_product($data)
    {
        $array = array(
            'tableName' => 'product_master',
            'insert'    =>  $data
        );

        return $this->MY_Model->insertData($array);
    }
  

}
