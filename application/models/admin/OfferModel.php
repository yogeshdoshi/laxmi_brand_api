<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class OfferModel extends CI_Model {

    public function alldata($page_no,$where,$order_by)
    {
        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'admin/offer/list',
                'uri_segment' => 4
            ),
            'data' => array(
                'getType'   => 'result',
                'tableName' => 'offer_master',
                'select'    => '*',
                'where' 	=> $where,
                'orderBy' => $order_by
            )
        );
        $resp = $this->make_pagination->paginate($array);
        $data['result']  = $resp['result'];
      
        return $data;
        
    }

    function fetch_single_offer($offerid)
	{
        $data= array(
            'getType'   => 'rowArray',
            'tableName' => 'offer_master',
            'select'    => '*',
            'where'	 	=> array('is_deleted' => NULL, 'offerid' => $offerid)
        );
		$result = $this->MY_Model->getData($data);
		return $result;
	}

    function delete_offer($offerid)
    {
        $array = array(
			'tableName' => 'offer_master',
			'update'    =>  array(
				'is_deleted' => 1,
				'deleted_at' => CURRENT_DATETIME
			),
			'where'    => array(
				'offerid' => $offerid
			)
		);
		return $this->MY_Model->updateData($array);
    }

    function save_offer($data)
    {
        $array = array(
            'tableName' => 'offer_master',
            'insert'    =>  $data
        );

        return $this->MY_Model->insertData($array);
    }


    
  
    function update_offer($offerid,$array)
    {
        $array = array(
            'tableName' => 'offer_master',
            'update'    =>  $array,
            'where'    => array(
                'offerid' => $offerid
            )
        );
        return $this->MY_Model->updateData($array);
    }

}
