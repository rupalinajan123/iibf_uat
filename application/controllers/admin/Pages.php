<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller 

{

	public $UserID;

	public function __construct()

  	{

   		parent::__construct();

		if( $this->session->id == "" ){

			redirect('admin/Login');

		}		

		$this->load->model('UserModel');

		$this->load->model('Master_model');

		$this->UserID = $this->session->id;

		$this->load->helper('master_helper');

		$this->load->helper('upload_helper');

		$this->load->helper('pagination_helper');

		$this->load->library('pagination');

	}

	public function index()

	{

		$this->session->set_userdata('field','');

		$this->session->set_userdata('value','');

		$this->session->set_userdata('per_page','');

		$this->session->set_userdata('sortkey','');

		$this->session->set_userdata('sortval','');

		$this->session->set_userdata('start','');

		

		$page_info = $this->Master_model->getRecords("page_master");

		$data['page_info'] = $page_info;

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>

			<li>Pages</li>

		</ol>';

		$this->load->view('admin/pages/page_list',$data);

	}

	/* Added for validations */

	public function alpha_numeric_underscore($str)

	{

		return ( ! preg_match("/^[A-Za-z0-9_-]+$/", $str)) ? FALSE : TRUE;

	}

	/**

	 * Alpha-numeric special characters

	 *

	 * @access	public

	 * @param	string

	 * @return	bool       

	 */

	public function alpha_numeric_special($str)

	{

		return ( ! preg_match("/^(?:[A-Za-z0-9]+)(?:[A-Za-z0-9 \~\,\!\@\#\$\%\&\*\^\(\)\-\=\|\\\:\;\"\'\.\<\>\\?\/]*)$/", $str)) ? FALSE : TRUE;

	}

	public function add() 

	{	

		$data = array();

		if(isset($_POST['btnSubmit']))

		{

			$this->form_validation->set_rules('title', 'Title', 'required|callback_alpha_numeric_special|min_length[3]');		

			$this->form_validation->set_rules('url_word', 'URL Title', 'required|callback_alpha_numeric_underscore|min_length[2]|is_unique[page_master.url_word]');	

			$this->form_validation->set_rules('description', 'Description', 'trim|required');

			$this->form_validation->set_rules('page_type', 'Page Type', 'required');	

			$this->form_validation->set_rules('status', 'Status', 'required');	

			$this->form_validation->set_message('alpha_numeric_underscore','Only alphanumeric with underscore values allowed');

			$this->form_validation->set_message('alpha_numeric_special','Only alphanumeric with special charecter values are allowed');

			if ($this->form_validation->run() == TRUE ) {

				$title = trim($this->input->post('title'));

				$url_word = trim($this->input->post('url_word'));

				$description = trim($this->input->post('description'));

				$page_type = trim($this->input->post('page_type'));

				$status = trim($this->input->post('status'));

				$last_modified = date('Y-m-d H:i:s');

				$insert_data = array(

					'title' => $title,

					'url_word' => $url_word,

					'description' => $description,

					'page_type' => $page_type,

					'status' => $status,

					'last_modified' => $last_modified,

					'modified_by' => $this->session->id,

					'created_by' => $this->session->id		

				);

				if($this->Master_model->insertRecord("page_master",$insert_data)) {

					$inserted_id = mysql_insert_id();

					$logs_data = array(

						'date' => date('Y-m-d H:i:s'),

						'title' => 'Add Page Successfull',

						'description'=>serialize($insert_data),

						'userid' => $this->UserID,

						'ip' => $this->input->ip_address()

					);

					$this->master_model->insertRecord('adminlogs',$logs_data);

					$this->session->set_flashdata('success_message','Page added successfully, Please add Meta Keywords and Description');

					redirect(base_url().'admin/pages/edit/'.$inserted_id);

				}

				else {

					$logs_data = array(

						'date'=>date('Y-m-d H:i:s'),

						'title'=>'Add Page Unsuccessfull',

						'description'=>serialize($insert_data),

						'userid' => $this->UserID,

						'ip'=>$this->input->ip_address()

					);

					$this->master_model->insertRecord('adminlogs',$logs_data);

					$this->session->set_flashdata('error','Error occured while adding record');

					redirect(base_url().'admin/pages');

				}

			} else {

				$data['validation_errors'] = validation_errors();

			}

		}

		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Pages</a></li>

			<li class="active">Add</li>

		</ol>';

		$this->load->view('admin/pages/add_page',$data);

	}

	public function getList(){

		$data['result'] = array();

		$data['action'] = array();

		$data['links'] = '';

		$data['success'] = '';

		$field = '';

		$value = '';

		$sortkey = '';

		$sortval = '';

		$per_page = '';

		$limit = 10;

		$start = 0;

		$session_arr = check_session();

		if($session_arr)

		{

			$field = $session_arr['field'];

			$value = $session_arr['value'];

			$sortkey = $session_arr['sortkey'];

			$sortval = $session_arr['sortval'];

			$per_page = $session_arr['per_page'];

			$start = $session_arr['start'];

		}

		$this->db->where('page_delete',0);	

		$total_row = $this->UserModel->getRecordCount("page_master",$field,$value);

		$url = base_url()."admin/Pages/getList/";

		$config = pagination_init($url,$total_row, $per_page, 2);

		$this->pagination->initialize($config);

		$this->db->where('page_delete',0);	

		$res = $this->UserModel->getRecords("page_master",'',$field, $value, $sortkey, $sortval, $per_page, $start);

		//$data['query'] = $this->db->last_query();

		$json_res = json_encode($data);

		if($res)

		{

			$result = $res->result_array();

			$data['result'] = $result;

			foreach($result as $row)

			{

				$confirm = "return confirm('Are you sure to delete this record?');";

				$action = '<a href="'.base_url().'admin/Pages/edit/'.$row['pageid'].'">Edit |</a><a href="'.base_url().'admin/Pages/delete/'.$row['pageid'].'" onclick="'.$confirm.'">Delete </a>';

				$data['action'][] = $action;

			}

			

			if(count($result))

				$data['success'] = 'Success';

			else

				$data['success'] = '';

			

			$str_links = $this->pagination->create_links();

			$data["links"] = $str_links;

			if(($start+$per_page)>$total_row)

				$end_of_total = $total_row;

			else

				$end_of_total = $start+$per_page;

			

			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';

			$data['index'] = $start+1;

		}

		

		$json_res = json_encode($data);

		echo $json_res;

	}

	

	public function edit()

	{		

		$data = array();

		$last = $this->uri->total_segments();

		$id = $this->uri->segment($last);$last = $this->uri->total_segments();

		$id = $this->uri->segment($last);

		$page_info = $data['page_info'] = $this->master_model->getRecords("page_master",array("pageid"=>$id));

		$page_info = $page_info[0];

		if(isset($_POST['btnSubmit'])) {

			$this->form_validation->set_rules('title', 'Title', 'required|callback_alpha_numeric_special|min_length[3]');		

			$this->form_validation->set_rules('description', 'Description', 'required|trim');

			if( $this->input->post('url_word') != $page_info['url_word']) {

			   $is_unique =  '|is_unique[page_master.url_word]';

			} else {

			   $is_unique =  '';

			}

			$this->form_validation->set_rules('url_word', 'URL Title', 'required|callback_alpha_numeric_underscore|min_length[2]'.$is_unique);	

			$this->form_validation->set_rules('meta_keyword', 'Meta Keywords', 'required|min_length[3]|callback_alpha_numeric_special');	

			$this->form_validation->set_rules('meta_desc', 'Meta Description', 'required|min_length[3]|callback_alpha_numeric_special');	

			$this->form_validation->set_rules('page_type', 'Page Type', 'required');	

			$this->form_validation->set_rules('status', 'Status', 'required');	

			$this->form_validation->set_message('alpha_numeric_underscore','Only alphanumeric with underscore values allowed');

			$this->form_validation->set_message('alpha_numeric_special','Only alphanumeric with special charecter values are allowed');

			if ($this->form_validation->run() == TRUE ) {

				$title = trim($this->input->post('title'));

				$url_word = trim($this->input->post('url_word'));

				$description = trim($this->input->post('description'));

				$meta_keyword = trim($this->input->post('meta_keyword'));

				$meta_desc = trim($this->input->post('meta_desc'));

				$page_type = trim($this->input->post('page_type'));

				$status = trim($this->input->post('status'));

				$last_modified = date('Y-m-d H:i:s')	;

				$update_data = array(

					'title' => $title,

					'url_word' => $url_word,

					'description' => $description,

					'page_type' => $page_type,

					'status' => $status,

					'meta_keyword'=>$meta_keyword,

					'meta_desc'=>$meta_desc,

					'last_modified' =>$last_modified,

					'modified_by' => $this->session->id					

				);

				if($this->master_model->updateRecord("page_master",$update_data,array('pageid'=>$id)))

				{

					/* Add log of edited record */

					$desc['updated_data'] = $update_data;

					$desc['old_data'] = $page_info;

					$logs_data = array(

						'date'=> date('Y-m-d H:i:s'),

						'title' => 'Edit Page Successfull',

						'description' => serialize($desc),

						'userid' => $this->UserID,

						'ip'=>$this->input->ip_address()

					);

					$this->master_model->insertRecord('adminlogs',$logs_data);

					$this->session->set_flashdata('success_message','Page updated successfully');

					redirect(base_url().'admin/Pages/edit/'.$id);	

				} else {

					$desc['updated_data'] = $update_data;

					$desc['old_data'] = $page_info;

					$logs_data = array(

						'date' => date('Y-m-d H:i:s'),

						'title' => 'Edit Page Unsuccessfull',

						'description' => serialize($desc),

						'userid' => $this->UserID,

						'ip' => $this->input->ip_address()

					);

					$this->master_model->insertRecord('adminlogs',$logs_data);

					$this->session->set_flashdata('error','Error occured while updating record');

					redirect(base_url().'admin/Pages/edit/'.$id);

				}

			} else {

				$data['validation_errors'] = validation_errors(); 

			}

		}

		if(is_numeric($id))

		{

			$page_info = $this->master_model->getRecords('page_master',array('pageid'=>$id));

			if(count($page_info))

			{

				$data['page_info'] = $page_info[0];

			}

		}

		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Pages</a></li>

			<li class="active">Edit</li>

	    </ol>';

		$this->load->view('admin/pages/edit_page',$data);

	}

	public function delete()

	{

		$last = $this->uri->total_segments();

		$id = $this->uri->segment($last);

		if(is_numeric($id))

		{

			$update_data = array('page_delete'=>1);

			if($this->master_model->updateRecord('page_master', $update_data, array('pageid'=>$id)))

			{

				$logs_data = array(

					'date' => date('Y-m-d H:i:s'),

					'title' => 'Delete Page Successfull',

					'description' => serialize(array('id'=>$id)),

					'userid' => $this->UserID,

					'ip' => $this->input->ip_address()

				);

				$this->master_model->insertRecord('adminlogs',$logs_data);

				$this->session->set_flashdata('success_message','Record deleted successfully');

				redirect(base_url().'admin/Pages');

			}

			else

			{

				$logs_data = array(

					'date'=>date('Y-m-d H:i:s'),

					'title'=>'Delete Page Unsuccessfull',

					'description'=>serialize(array('id'=>$id)),

					'userid'=>$this->UserID,

					'ip'=>$this->input->ip_address()

				);

				$this->master_model->insertRecord('adminlogs',$logs_data);

				$this->session->set_flashdata('error','Error occured while deleting record');

				redirect(base_url().'admin/Pages');

			}

		}

	}

}