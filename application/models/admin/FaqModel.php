<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class FaqModel extends CI_Model {

    public function alldata($page_no,$where,$order_by)
    {
        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'admin/faq/list',
                'uri_segment' => 4
            ),
            'data' => array(
                'getType'   => 'result',
                'tableName' => 'faq_master',
                'select'    => '*',
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        $data['result']  = $resp['result'];
      
        return $data;
        
    }

    function fetch_single_faq($id)
	{
        $data= array(
            'getType'   => 'rowArray',
            'tableName' => 'faq_master',
            'select'    => '*',
            'where'	 	=> array('is_deleted' => NULL, 'id' => $id)
        );
		$result = $this->MY_Model->getData($data);
		return $result;
	}

    function delete_faq($id)
    {
        $array = array(
			'tableName' => 'faq_master',
			'update'    =>  array(
				'is_deleted' => 1,
				'deleted_at' => CURRENT_DATETIME
			),
			'where'    => array(
				'id' => $id
			)
		);
		return $this->MY_Model->updateData($array);
    }

    function save_faq($data)
    {
        $array = array(
            'tableName' => 'faq_master',
            'insert'    =>  $data
        );

        return $this->MY_Model->insertData($array);
    }


    
  
    function update_faq($id,$array)
    {
        $array = array(
            'tableName' => 'faq_master',
            'update'    =>  $array,
            'where'    => array(
                'id' => $id
            )
        );
        return $this->MY_Model->updateData($array);
    }

}
