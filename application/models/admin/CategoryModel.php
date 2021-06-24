<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends CI_Model {

   
    public function alldata($page_no,$where,$order_by)
    {

        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'admin/category/list',
                'uri_segment' => 4
            ),
            'data' => array(
                'getType'   => 'result',
                'tableName' => 'category_master',
                'select'    => '*',
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        $data['result']  = $resp['result'];
      
        return $data;
        
    }
   function save_category($data){
    $array = array(
        'tableName' => 'category_master',
        'insert'    =>  $data
    );

    return $this->MY_Model->insertData($array);
   }

   function update_category($id,$array)
   {
       $array = array(
           'tableName' => 'category_master',
           'update'    =>  $array,
           'where'    => array(
               'category_id' => $id
           )
       );
     $this->MY_Model->updateData($array);
     return 1;
    }

    function delete_category($id)
    {
        $array = array(
			'tableName' => 'category_master',
			'update'    =>  array(
				'is_deleted' => 1,
				'deleted_at' => CURRENT_DATETIME
			),
			'where'    => array(
				'is_deleted' => NULL,
				'category_id' => $id
			)
		);
		return $this->MY_Model->updateData($array);
    }

   
}
