<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_Model extends CI_Model {

	/* this function is built for getting data in first row, last row or result */
	public function getData($array) {	

		/*set group concat limit default 1024 characters*/
		$this->db->simple_query('SET SESSION group_concat_max_len=150000000000000000');
		/*set group concat limit*/
		
		/* select */
		if(!empty($array['select'])) {
			$this->db->select($array['select']);
		}
		
		/* where */
		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}
		/* like */
		if(!empty($array['like'])) {
			$this->db->like($array['like']);
		}

		/*Multiple like*/
		if(!empty($array['or_like'])) {
			$this->db->or_like($array['or_like']);
		}

		//$this->db->group_by('user_id'); 
		/* group by */
		if(!empty($array['groupby'])) {
			$this->db->group_by($array['groupby']); 
		}

		/* where in field and where in value */
		if(!empty($array['whereInField']) && !empty($array['whereInVal'])) {
			$this->db->where_in($array['whereInField'],$array['whereInVal']);
		}

		/* where in field and where in value */
		// if(!empty($array['notIn'])) {
			
		// 	$this->db->where_not_in('crm.user_id', $ignore);
		// }

		/* order by field and order by value */
		if(!empty($array['orderBy'])) {
			foreach ($array['orderBy'] as $key => $value) {
				$this->db->order_by($key,$value);
			}
			//$this->db->order_by($array['orderByField'],$array['orderByVal']);
		}

		$this->db->from($array['tableName']);
		if (!empty($array['join'])) {
			foreach ($array['join'] as $key => $value) {
				$this->db->join($key,$value,'Left');
			}
		}

		if (!empty($array['innerjoin'])) {
			foreach ($array['innerjoin'] as $key => $value) {
				$this->db->join($key,$value);
			}
		}

		if(!empty($array['limit']) && (!empty($array['start']|| $array['start']==0))) {
			$this->db->limit($array['limit'], $array['start']);
		}
		
		$query = $this->db->get();
		if(!empty($array['exit'])){
			echo $this->db->last_query();exit;
		}

		if(!empty($array['exit2'])){
			return $this->db->last_query();
		}

		if($array['getType'] == 'first_column') {
			$row = $query->row_array();
			return $row['count'];			
		} else if($array['getType'] == 'first_row') {
			return $query->first_row();
		} else if($array['getType'] == 'last_row') {
			return $query->last_row();					
		} else if($array['getType'] == 'row_array') {
			return $query->row_array();					
		} else if($array['getType'] == 'row') {
			return $query->row();					
		} else if($array['getType'] == 'result') {
			return $query->result();					
		} else if($array['getType'] == 'result_array') {
			return $query->result_array();					
		}
	}

	/*get count of the query data*/
	public function getCountData($array){

		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}
		
		/* select */
		if(!empty($array['select'])) {
			$this->db->select($array['select']);
		}
		$this->db->from($array['tableName']);
		$query = $this->db->get()->row()->noofrows;
		return $query;
	}

	/* this function is use for insert single as well as multiple data */
	public function insertData($array) {	

		if(!empty($array['type']) && isset($array['type']) && $array['type'] == 'batch') {
			$this->db->insert_batch($array['tableName'],$array['insert']);
			return 1;
		} else if(!empty($array['type']) && isset($array['type']) && $array['type'] == 'ignore') {
			$insert_query = $this->db->insert_string($array['tableName'],$array['insert']);
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
			$this->db->query($insert_query);
			return $this->db->insert_id();
		} else {

			$this->db->insert($array['tableName'],$array['insert']);
			return $this->db->insert_id();
		}
	}
	/* this function is use for insert single as well as multiple data */
	public function insertDefaultData($array) {	
			foreach ($array as $key => $value) {
						$layout= array(
							'layout_name' => $value['layout_name'],
							'default_background_color' => $value['default_background_color'],
							'created_at' => CURRENT_DATETIME
							);

					$this->db->insert('layout_master',$layout);
					$layout_id=$this->db->insert_id();
				$i=1;
				foreach ($value['section'] as $key => $value1) {
						
						$section= array(
								'layout_id'=>$layout_id,
								'width' => $value1['width'],
								'height' => $value1['height'],
								'order' => $i,
								'created_at' => CURRENT_DATETIME
							);
						$this->db->insert('section',$section);
						$i++;
							
				}
			}
			return true;
	}
	/* this function is use for update single as well as multiple data */
	public function updateData($array) {	

		if(!empty($array['type']) && isset($array['type']) && $array['type'] == 'batch') {

			$this->db->update_batch($array['tableName'],$array['update'],$array['idtoupdate']); 
		}
		else{

			/* where */
			if(!empty($array['where'])) {
				$this->db->where($array['where']);
			}

			/* where in field and where in value */
			if(!empty($array['whereInField']) && !empty($array['whereInVal'])) {
				$this->db->where_in($array['whereInField'],$array['whereInVal']);
			}

			$this->db->update($array['tableName'], $array['update']);
			if(!empty($array['exit'])){
				echo $this->db->last_query();exit;
			}
			// print_r($this->db->last_query());exit;
			return $this->db->affected_rows();
		}
	}

	public function deleteData($array) {	

		/* where */
		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}

		/* where in field and where in value */
		if(!empty($array['whereInField']) && !empty($array['whereInVal'])) {
			$this->db->where_in($array['whereInField'],$array['whereInVal']);
		}

		$this->db->update($array['tableName'], $array['update']);
		return $this->db->affected_rows();
	}
	
	/*remove data from table*/
	public function deleteActualData($array) {	

		/* where */
		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}

		// /* where in field and where in value */
		if(!empty($array['whereInField']) && !empty($array['whereInVal'])) {
			$this->db->where_in($array['whereInField'],$array['whereInVal']);
		}

		$this->db->delete($array['tableName']);
		return $this->db->affected_rows();
	}

	/*soft delete */
	public function deleteAllData($tableName,$ids){
		$this->db->where_in('id',$ids);
		$this->db->update($tableName, array('is_deleted'=>1));
		return 1;
	}

	//FUNCTION FOR CHECK ADMIN LOGIN
	public function checkLogin($array) {	

		/* select */
		if(!empty($array['select'])) {
			$this->db->select($array['select']);
		}

		/* where */
		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}
		$this->db->from($array['tableName']);
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		$num = $query->num_rows();
		return $num;
	}
	
	//FUNCTION FOR CHECK user LOGIN
	public function userLogin($array) {	

		/* select */
		if(!empty($array['select'])) {
			$this->db->select($array['select']);
		}

		/* where */
		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}
		$this->db->from($array['tableName']);
		$query = $this->db->get();
		$num = $query->num_rows();
		if($num > 0){
			$result=$query->row_array();
			$username=$result['username'];
			$user_id=$result['id'];
			$this->session->set_userdata('sess_user_username',$username);
			$this->session->set_userdata('sess_user_id',$user_id);
		}
		return $num;
	}
	
	//GET ONLY TOTAL NUMBERS OF ROWS OR ONLY SUM OR GRANDTOTAL
	public function getTotalData($array){

		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}

		/* where in field and where in value */
		if(!empty($array['whereInField']) && !empty($array['whereInVal'])) {
			$this->db->where_in($array['whereInField'],$array['whereInVal']);
		}

		/* select */
		if(!empty($array['select'])) {
			$this->db->select($array['select']);
		}


		/* like */
		if(!empty($array['like'])) {
			$this->db->like($array['like']);
		}

		/*Multiple like*/
		if(!empty($array['or_like'])) {
			$this->db->or_like($array['or_like']);
		}

		
		$this->db->from($array['tableName']);
		if (!empty($array['join'])) {
			foreach ($array['join'] as $key => $value) {
				$this->db->join($key,$value,'Left');
			}
		}

		if(!empty($array['groupby'])) {
			$this->db->group_by($array['groupby']); 
		}

		/* order by field and order by value */
		if(!empty($array['orderBy'])) {
			foreach ($array['orderBy'] as $key => $value) {
				$this->db->order_by($key,$value);
			}
			//$this->db->order_by($array['orderByField'],$array['orderByVal']);
		}
		
		$query = $this->db->get()->row()->noofrows;
		if(!empty($array['exit'])){
			echo $this->db->last_query();exit;
		}

		return $query;
	}

	/*check already exist*/
	public function checkAlreadyExist($array){

		if(!empty($array['where'])) {
			$this->db->where($array['where']);
		}
		$this->db->from($array['tableName']);
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		$num = $query->num_rows();
		return $num;
	}

	//COMMON PAGINATION
	//DATA TO BE PASSED FROM CONTROLLER
	//$start=0;
	//$limit=5;
	//$link='register/listdata/no';
	public function pagination($total,$limit,$link){

		$data['page_no'] 			= 0;
		$config['total_rows']   	= $total;				
		$config['per_page'] 		= $limit;
		$config['base_url'] 		= site_url($link);
		//config for bootstrap pagination class integration
		$config['full_tag_open'] 	= '<ul class="pagination">';
		$config['full_tag_close'] 	= '</ul>';
		$config['first_tag_open'] 	= '<li class="paginate_button page-item previous">';
		//$config['first_link'] 		= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
		
		// $config['first_link'] 		= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
		// $config['last_link'] 		= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
		$config['first_tag_close'] 	= '</li>';
		// 
		$config['prev_tag_open'] 	= '<li class="paginate_button page-item previous">';
		$config['prev_link'] 		= '<span class="page-link">Previous</span>';
		$config['prev_tag_close'] 	= '</li>';	

		
		$config['next_tag_open'] 	= '<li class="paginate_button page-item next">';
		$config['next_link'] 		= '<span class="page-link">Next</span>';
		// $config['next_link'] 		= '<li class="page-link"><a class="paginate_button page-item next" id="sampleTable_next">Next</a></li>';
		$config['next_tag_close'] 	= '</li>';
		$config['last_link'] 		= 'Last &raquo;';
		$config['last_tag_open'] 	= '<li  class="paginate_button page-item next" id="sampleTable_next">';
		$config['last_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['num_tag_open'] 	= '<li class="paginate_button page-item"><a class="page-link"';
		$config['num_tag_close'] 	= '</a></li>';
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}	

	public function pagination1($total,$limit,$link){

		$data['page_no'] 			= 0;
		$config['total_rows']   	= $total;				
		$config['per_page'] 		= $limit;
		$config['base_url'] 		= site_url($link);
		//config for bootstrap pagination class integration
		$config['full_tag_open'] 	= '<ul class="pagination">';
		$config['full_tag_close'] 	= '</ul>';
		$config['first_link'] 		= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
		$config['last_link'] 		= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
		$config['first_tag_open'] 	= '<li class="paginate_button page-item previous">';
		$config['first_tag_close'] 	= '</li>';
		// 
		$config['prev_tag_open'] 	= '<li class="paginate_button page-item previous">';
		$config['prev_link'] 		= '<span class="page-link">Previous</span>';
		$config['prev_tag_close'] 	= '</li>';	

		
		$config['next_tag_open'] 	= '<li class="paginate_button page-item next">';
		$config['next_link'] 		= '<span class="page-link">Next</span>';
		$config['next_tag_close'] 	= '</li>';
		// $config['next_link'] 		= '<li class="page-link"><a class="paginate_button page-item next" id="sampleTable_next">Next</a></li>';
		// $config['last_link'] 		= 'Last &raquo;';
		// $config['last_tag_open'] 	= '<li  class="paginate_button page-item next" id="sampleTable_next">';
		// $config['last_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['num_tag_open'] 	= '<li class="paginate_button page-item"><a class="page-link"';
		$config['num_tag_close'] 	= '</a></li>';
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}


	//UPLOAD FILES
	//DATA TO BE PASSED FROM CONTROLLER
	//do_upload('userfile','uploads/','gif|jpg|png');
	//WHERE userfile is name file control eg : <input type="file" name="product_image"> product_image is passed
	//uploads is folder name where you want to move
	//last parameter is of restricted file format
	//dynamic name : encrypted file name generator
	public function do_upload($filename,$path,$format,$dynamic_name = '') {
		if($dynamic_name != ''){
			$config['upload_path']   = $path; 
			$config['allowed_types'] = $format; 
			$config['encrypt_name']  = TRUE;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload($filename)) {
				$error = array('error' => $this->upload->display_errors()); 
				//return $error;
				return false;
			}

			else { 
				$imgName = $this->upload->data();
				return $imgName['file_name'];
			} 
		} else {
			$config['upload_path']   = $path; 
			$config['allowed_types'] = $format; 
			$this->upload->initialize($config);
			if(!$this->upload->do_upload($filename)) {
				$error = array('error' => $this->upload->display_errors()); 
				return $error;
			}
			else { 
				return TRUE; 
			} 
		}
	}
	
	/* get image upload and get all details */
	public function uploadImage($filename,$path,$format,$max_size,$dynamic_name = '') {
		if($dynamic_name != ''){
			$config['upload_path']   = $path; 
			$config['allowed_types'] = $format; 
			$config['encrypt_name']  = TRUE;
			$config['max_size'] = $max_size;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload($filename)) {
				$error = array('error' => $this->upload->display_errors()); 
				// return $error;
				return false;
			}

			else { 
				$imgName = $this->upload->data();
				return $imgName;
			} 
		} else {
			$config['upload_path']   = $path; 
			$config['allowed_types'] = $format; 
			$config['max_size'] = $max_size;
			$this->upload->initialize($config);
			if(!$this->upload->do_upload($filename)) {
				$error = array('error' => $this->upload->display_errors()); 
				return $error;
			}
			else { 
				return TRUE; 
			} 
		}
	}
	

function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}
}