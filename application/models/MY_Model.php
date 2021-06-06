<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{

    /* ===============================================================
    // get data in first row, last row or result, etc
    =============================================================== */
    public function getData($array) {

        /* set group concat limit default 1024 characters */
        if (!empty($array['groupConcatMax']) && $array['groupConcatMax'] == 1) {
            $this->db->simple_query('SET SESSION group_concat_max_len=150000');
        }

        /* select */
        if (!empty($array['select'])) {
            $this->db->select($array['select']);
        }

        /* where */
        if (!empty($array['where'])) {
            $this->db->where($array['where']);
        }

         /* or where */
         if (!empty($array['or_where'])) {
            $this->db->or_where($array['or_where']);
        }

        /* group by */
        if (!empty($array['groupBy'])) {
            $this->db->group_by($array['groupBy']);
        }

        /* having */
        if (!empty($array['having'])) {
            $this->db->having($array['having']);
        }

        /* where in field and where in value */
        if (!empty($array['whereInField']) && !empty($array['whereInVal'])) {
            $this->db->where_in($array['whereInField'], $array['whereInVal']);
        }

        /* order by field and order by value */
        if (!empty($array['orderBy'])) {
            $this->db->order_by($array['orderBy']);
        }

        /* set the table for get data */
        $this->db->from($array['tableName']);

        /* add jointype is a type of joining between two table by default is inner */
        if (!empty($array['join'])) {
            foreach ($array['join'] as $key => $value) {
                if (!empty($array['joinType'])) {
                    $this->db->join($key, $value, $array['joinType']);
                } else {
                    $this->db->join($key, $value);
                }
            }
        }

        /* set limit */
        if (!empty($array['limit']) && (isset($array['start']))) {
            $this->db->limit($array['limit'], $array['start']);
        }

        /* get data */
        $query = $this->db->get();

        /* show the last executed query */
        if (!empty($array['exit']) && $array['exit'] == 1) {
            return $this->db->last_query();
        }

        /* get difference type of result */
        if ($array['getType'] == 'firstColumn') {
            return $query->first_row()->count;
        } else if ($array['getType'] == 'firstRow') {
            return $query->first_row();
        } else if ($array['getType'] == 'rowArray') {
            return $query->row_array();
        } else if ($array['getType'] == 'result') {
            return $query->result();
        } else if ($array['getType'] == 'resultArray') {
            return $query->result_array();
        } else if ($array['getType'] == 'json') {
            return json_encode($query->result_array());
        }
    }

    /* ===============================================================
    // insert data
    =============================================================== */
    public function insertData($array) {

        if (!empty($array['type']) && isset($array['type']) && $array['type'] == 'ignore') {

            $insert_query = $this->db->insert_string($array['tableName'], $array['insert']);
            $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
            $this->db->query($insert_query);
            return $this->db->insert_id();

        } else if (!empty($array['type']) && isset($array['type']) && $array['type'] == 'batch') {

            $this->db->insert_batch($array['tableName'], $array['insert']);
            return 1;

        } else {

            $this->db->insert($array['tableName'], $array['insert']);
            return $this->db->insert_id();
        }
    }

    /* ===============================================================
    // update data
    =============================================================== */
    public function updateData($array) {

        if (!empty($array['type']) && isset($array['type']) && $array['type'] == 'batch') {

            $this->db->update_batch($array['tableName'], $array['update'], $array['id']);

        } else {

            /* where */
            if (!empty($array['where'])) {
                $this->db->where($array['where']);
            }

            /* where in field and where in value */
            if (!empty($array['whereInField']) && !empty($array['whereInVal'])) {
                $this->db->where_in($array['whereInField'], $array['whereInVal']);
            }
            
            if (!empty($array['whereInField']) && !empty($array['whereNOTInVal'])) {
                $this->db->where_not_in($array['whereInField'], $array['whereNOTInVal']);
            }
            

            $this->db->update($array['tableName'], $array['update']);
            return $this->db->affected_rows();
        }
    }

    /* ===============================================================
    // delete data
    =============================================================== */
    public function deleteData($array) {

        /* where */
        if (!empty($array['where'])) {
            $this->db->where($array['where']);
        }

        /* where in field and where in value */
        if (!empty($array['whereInField']) && !empty($array['whereInVal'])) {
            $this->db->where_in($array['whereInField'], $array['whereInVal']);
        }

        $this->db->delete($array['tableName']);
        return $this->db->affected_rows();
    }

}