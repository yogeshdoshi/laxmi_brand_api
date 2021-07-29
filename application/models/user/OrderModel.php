<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model {

    public function alldata($page_no, $where, $order_by) {
        $returnArr = array();
        $data = array(
            'getType' => 'resultArray',
            'tableName' => 'order_master',
            'select' => '*',
            'where' => $where,
            'orderBy' => $order_by
        );

        /* total number of rows */
        $total_array = $data;
        $total_array['getType'] = 'firstColumn';
        $total_array['select'] = 'count(*) as count';

        $total_records = 0;
        $total_array['exit'] = 1;

        $sql = $this->MY_Model->getData($total_array);
        if (empty($sql)) {
            $sql = "SELECT COUNT(*) as count FROM ($sql) as temp";
        }
        $query = $this->db->query($sql);
        $total_records = $query->first_row()->count;


        /* get result or array */
        /* result */
        $start = ($page_no - 1) * PER_PAGE;
        $limit = PER_PAGE;

        $result_array = $data;
        $result_array['limit'] = $limit;
        $result_array['start'] = $start;
        $result = $this->MY_Model->getData($result_array);

        if (count($result) > 0) {
            foreach ($result AS $i => $result_item) {
                $where = 'order_id = "' . $result_item['order_id'] . '"';
                $order_design_product_data = array(
                    'getType' => 'resultArray',
                    'tableName' => 'order_design_details',
                    'select' => 'pdt_id',
                    'where' => $where,
                    'orderBy' => ''
                );
                $product_id_list = $this->MY_Model->getData($order_design_product_data);
                if (count($product_id_list) > 0) {
                    $product_id = '';
                    foreach ($product_id_list AS $product_id_item) {
                        if ($product_id != $product_id_item['pdt_id']) {
                            $product_id = $product_id_item['pdt_id'];
                            $whereVariant = 'odd.order_id = "' . $result_item['order_id'] . '" and odd.pdt_id = "' . $product_id . '"';
                            $order_design_variant_data = array(
                                'getType' => 'resultArray',
                                'tableName' => 'order_design_details as odd',
                                'joinType' => 'left',
                                'join' => array(
                                    'product_variants as pv' => 'odd.pdt_id = pv.pdt_id and odd.var_id = pv.var_id',
                                ),
                                'select' => 'pv.rowid, pv.var_id, pv.var_type, pv.var_discount_price, pv.var_actual_price, odd.qty as var_qty',
                                'where' => $whereVariant,
                                'orderBy' => ''
                            );
                            $product_variant_list = $this->MY_Model->getData($order_design_variant_data);
                            $result[$i]['products'][] = array(
                                'pdt_id' => $product_id,
                                'variant' => $product_variant_list
                            );
                        }
                    }
                }
            }
        }

        $returnArr['total_record'] = $total_records;
        $returnArr['total_pages'] = ceil($total_records / PER_PAGE);
        $returnArr["result"] = $result;

        return $returnArr;

        /* $array = array(
          'pagination' => array(
          'page_no' => $page_no,
          'per_page' => PER_PAGE,
          'link' => 'admin/order/list',
          'uri_segment' => 4
          ),
          'data' => array(
          'getType' => 'result',
          'tableName' => 'order_master as s',
          'select' => 's.*,c.*',
          'joinType' => "Left",
          'join' => array('order_design_details as c' => 's.order_id = c.order_id'),
          'where' => $where,
          'orderBy' => $order_by
          )
          );
          $resp = $this->make_pagination->paginate($array);

          return $resp; */
    }

}
