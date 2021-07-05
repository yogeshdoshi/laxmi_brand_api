<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Make_pagination {

    protected $CI;

    /* ===============================================================
	// constructor
	=============================================================== */
    public function __construct() {

        /* assign the codeigniter super-object */
        $this->CI =& get_instance();
        
        /* load pagination library */
        $this->CI->load->library('pagination');
    }

    /* ===============================================================
	// paginate function
	=============================================================== */
    public function paginate($array) {

        $data = array();

        /* total number of rows */
        $total_array = $array['data'];
        $total_array['getType'] = 'firstColumn';
        $total_array['select'] = 'count(*) as count';
        
        $total_records = 0;
        if(isset($array['data']['groupBy']) && $array['data']['groupBy']!='') {

            $total_array['exit'] = 1;
            $sql = $this->CI->MY_Model->getData($total_array);
            if(!empty($sql)) {
                $new_sql = "SELECT COUNT(*) as count FROM ($sql) as temp";
                $query = $this->CI->db->query($new_sql);
                $total_records = $query->first_row()->count;
            }
        } else {
            $total_records = $this->CI->MY_Model->getData($total_array);
        }

        /* if rows is getter then */
        $links = array();
        $result = array();
        $page_no = 0;
        if ($total_records > 0) {

            /* pagination links */
            $config['base_url'] = base_url().$array['pagination']['link'];
            $config['total_rows'] = $total_records;
            $config['per_page'] = $array['pagination']['per_page'];
            $config['uri_segment'] = $array['pagination']['uri_segment'];
             
            $config['num_links'] = 2;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
        
            $config['full_tag_open']     = '<ul class="pagination">';
            $config['full_tag_close']   = '</ul>';
            
            $config['first_tag_open']   = '<li class="paginate_button page-item previous">';
            $config['first_link']       = '<span class="page-link">&laquo;</span>';
            $config['first_tag_close']  = '</li>';
            
            $config['prev_tag_open']    = '<li class="paginate_button page-item previous">';
            $config['prev_link']        = '<span class="page-link">Previous</span>';
            $config['prev_tag_close']   = '</li>';  
            
            $config['next_tag_open']    = '<li class="paginate_button page-item next">';
            $config['next_link']        = '<span class="page-link">Next</span>';
            $config['next_tag_close']   = '</li>';

            $config['last_tag_open']     = '<li class="paginate_button page-item next" id="sampleTable_next">';
            $config['last_link']        = '<span class="page-link">&raquo;</span>';
            $config['last_tag_close']    = '</li>';

            $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close']    = '</a></li>';

            $config['num_tag_open']     = '<li class="paginate_button page-item"><a class="page-link"';
            $config['num_tag_close']    = '</a></li>';
             
            $this->CI->pagination->initialize($config);
                 
            /* build paging links */
            $links = $this->CI->pagination->create_links();

            /* result */
            $start = ($array['pagination']['page_no'] - 1) * $array['pagination']['per_page'];
            $limit = $array['pagination']['per_page'];

            $page_no = $start;

            $result_array = $array['data'];
            $result_array['limit'] = $limit;
            $result_array['start'] = $start;
            $result = $this->CI->MY_Model->getData($result_array);

        }
        $data['total_record'] =$total_records;
        $data['total_pages']=ceil($total_records/$array['pagination']['per_page']);
        $data["result"] = $result;

        return $data;
    }
 

}