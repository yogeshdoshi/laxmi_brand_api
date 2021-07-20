<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class OrderModel extends CI_Model {

    public function alldata($page_no,$where,$order_by)
    {
        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'admin/order/list',
                'uri_segment' => 4
            ),
            'data' => array(
                'getType'   => 'result',
                'tableName' => 'order_master as s',
                'select'    => 's.*,c.*',
                'joinType' => "Left",
				'join' => array('user as c' => 's.user_id = c.user_id'),
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        
        return $resp;
        
    }


}