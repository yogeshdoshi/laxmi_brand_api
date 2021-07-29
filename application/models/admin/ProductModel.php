<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {

    public function alldata($page_no, $where, $order_by) {
        $array = array(
            'pagination' => array(
                'page_no' => $page_no,
                'per_page' => PER_PAGE,
                'link' => 'admin/product/list',
                'uri_segment' => 4
            ),
            'data' => array(
                'getType' => 'resultArray',
                'tableName' => 'product_master as s',
                'select' => 's.*',
                'where' => $where,
                'orderBy' => $order_by,
                'groupBy' => 's.pdt_id'
            )
        );
        $resp = $this->make_pagination->paginate($array);

        foreach ($resp['result'] as $k => $val) {

            $data = array(
                'getType' => 'result',
                'tableName' => 'product_variants as s',
                'select' => '*',
                'where' => array('pdt_id' => $val['pdt_id'])
            );
            $result = $this->MY_Model->getData($data);
            $resp['result'][$k] = array(
                'product' => $val,
                'varient' => $result
            );
        }
        return $resp;
    }

    function fetch_single_product($id) {
        $data = array(
            'getType' => 'resultArray',
            'tableName' => 'product_master as s',
            'select' => 's.*',
            'groupBy' => 's.pdt_id',
            'where' => array('s.is_deleted' => NULL, 's.pdt_id' => $id)
        );
        $result = $this->MY_Model->getData($data);
        foreach ($result as $k => $val) {

            $data = array(
                'getType' => 'result',
                'tableName' => 'product_variants as s',
                'select' => '*',
                'where' => array('pdt_id' => $val['pdt_id'])
            );
            $result2 = $this->MY_Model->getData($data);
            $result[$k] = array(
                'product' => $val,
                'varient' => $result2
            );
        }
        return $result;
    }

    function delete_product($id) {
        $array = array(
            'tableName' => 'product_master',
            'update' => array(
                'is_deleted' => 1,
                'deleted_at' => CURRENT_DATETIME
            ),
            'where' => array(
                'is_deleted' => NULL,
                'pdt_id' => $id
            )
        );
        return $this->MY_Model->updateData($array);
    }

    function delete_variant($id) {
        $array = array(
            'tableName' => 'product_variants',
            'where' => array(
                'pdt_id' => $id
            )
        );
        return $this->MY_Model->deleteData($array);
    }

    function save_product($data) {
        $array = array(
            'tableName' => 'product_master',
            'insert' => $data
        );

        return $this->MY_Model->insertData($array);
    }

    function save_varient($data) {
        $array = array(
            'type' => 'batch',
            'tableName' => 'product_variants',
            'insert' => $data
        );
        return $this->MY_Model->insertData($array);
    }

    function update_product($id, $array) {
        $array = array(
            'tableName' => 'product_master',
            'update' => $array,
            'where' => array(
                'pdt_id' => $id
            )
        );
        $this->MY_Model->updateData($array);

        $data = array(
            'getType' => 'rowArray',
            'tableName' => 'product_master as s',
            'select' => 'c.rowid',
            'joinType' => "Left",
            'join' => array('product_variants as c' => 's.pdt_id = c.pdt_id'),
            'where' => array('s.is_deleted' => NULL, 's.pdt_id' => $id)
        );
        $result = $this->MY_Model->getData($data);
        return $result;
    }

    function update_varient($id, $array) {
        $array = array(
            'tableName' => 'product_variants',
            'update' => $array,
            'where' => array(
                'rowid' => $id
            )
        );
        $this->MY_Model->updateData($array);
    }

}
