<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ProductModel extends CI_Model {

    public function alldata($page_no,$where,$order_by)
    {
        $array = array(
            
            'pagination' => array(
                'page_no'     => $page_no,
                'per_page'    => PER_PAGE,
                'link'        =>'user/product/list',
                'uri_segment' => 4
            ),
            'data' => array(
                'getType'   => 'resultArray',
                'tableName' => 'product_master as s',
                'select'    => 's.*',
                'where' => $where,
                'orderBy' => $order_by,
                'groupBy' =>'s.pdt_id'
            )
        );
        $resp = $this->make_pagination->paginate($array);
       
        foreach($resp['result'] as $k=>$val){

            $data= array(
                'getType'   => 'result',
                'tableName' => 'product_variants as s',
                'select'    => '*',
                'where'	 	=> array('pdt_id' => $val['pdt_id'])
            );
            $result = $this->MY_Model->getData($data);
            $resp['result'][$k]=array(
                'product'=>$val,
                'varient'=>$result
            );
        }
        return $resp;
        
    }

    function fetch_single_product($id)
	{
        $data= array(
            'getType'   => 'rowArray',
            'tableName' => 'product_master as s',
            'select'    => 's.*,s.pdt_id as product_id,c.is_active as varient_isactive,c.rowid ,c.var_id ,c.is_active as varient_status ,c.pdt_price_actual_30gm,c.pdt_price_discounted_30gm,c.pdt_price_enable_30gm,c.pdt_price_actual_60gm,c.pdt_price_discounted_60gm,c.pdt_price_enable_60gm,c.pdt_price_actual_100gm,c.pdt_price_discounted_100gm,c.pdt_price_enable_100gm,c.pdt_price_actual_250gm,c.pdt_price_discounted_250gm,c.pdt_price_enable_250gm,c.pdt_price_actual_500gm,c.pdt_price_actual_500gm ,c.pdt_price_discounted_500gm	 ,c.pdt_price_enable_500gm ,c.pdt_price_actual_1kg ,c.pdt_price_discounted_1kg ,c.pdt_price_enable_1kg ,c.pdt_price_actual_2kg ,c.pdt_price_discounted_2kg ,c.pdt_price_enable_2kg ,c.pdt_price_actual_3kg ,c.pdt_price_discounted_3kg ,c.pdt_price_enable_3kg ,c.pdt_price_actual_5kg ,c.pdt_price_discounted_5kg ,c.pdt_price_enable_5kg,cm.category_name',
            'joinType' => "Left",
            'join' => array('product_variants as c' => 's.pdt_id = c.pdt_id','category_master as cm'=>'s.category_id = cm.category_id'),
            'where'=> array('s.is_deleted' => NULL, 's.pdt_id' => $id)
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

    function save_varient($data)
    {
        $array = array(
            'tableName' => 'product_variants',
            'insert'    =>  $data
        );
        return $this->MY_Model->insertData($array);
    }

    function update_product($id,$array)
    {
        $array = array(
            'tableName' => 'product_master',
            'update'    =>  $array,
            'where'    => array(
                'pdt_id' => $id
            )
        );
        $this->MY_Model->updateData($array);

        $data= array(
            'getType'   => 'rowArray',
            'tableName' => 'product_master as s',
            'select'    => 'c.rowid',
            'joinType' => "Left",
            'join' => array('product_variants as c' => 's.pdt_id = c.pdt_id'),
            'where'	 	=> array('s.is_deleted' => NULL, 's.pdt_id' => $id)
        );
		$result = $this->MY_Model->getData($data);
		return $result;
        
    }
  
    function update_varient($id,$array)
    {
        $array = array(
            'tableName' => 'product_variants',
            'update'    =>  $array,
            'where'    => array(
                'rowid' => $id
            )
        );
        $this->MY_Model->updateData($array);
    }

}
