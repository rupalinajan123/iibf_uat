<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Checker extends CI_Controller {	
	public $UserID;			
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('creditnote_admin')) {
			redirect('creditnote/admin/Login');
		}else{
			$UserData = $this->session->userdata('creditnote_admin');
			if($UserData['admin_user_type'] == 'Maker' ){
				redirect('creditnote/admin/Login');
			}
		}
		$this->UserData = $this->session->userdata('creditnote_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
		$this->load->helper('dra_agency_center_mail_helper');
		$this->load->library('email');
        $this->load->model('Emailsending');
	}
	
	//view list of requested refund
	public function refundrequest_list()
	{		
		
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
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		//$data = $this->getUserInfo();
		$res_arr = array();
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'creditnote/admin/checker/refundrequest_list">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'creditnote/admin/checker/refundrequest_list">Checker</a></li>
		</ol>';		
		
		
		
		$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
		$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');	
		$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');	
		//$this->db->group_by('m.req_id');
		//$this->db->where('m.pay_status !=','0');	
		$this->db->where('m.req_status !=','10');	
		$this->db->order_by('m.id','DESC');
		$res_arr = $this->master_model->getRecords("maker_checker m");	
		// echo $this->db->last_query();
		
	    //print_r($res_arr); die;			
		$data['reuest_list'] = $res_arr;

		$this->load->view('creditnote/admin/checker/checker_refundreq_list',$data);
	}
	
	//check deatils of request
	public function request_details(){
     
          if($this->uri->segment(5) ) {

		    $req_id = trim($this->uri->segment(5) ); 
			$req_id = base64_decode($req_id);
			$req_id = intval($req_id);
	       
	    $this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_exceptional_case,m.req_status,m.req_created_on,m.req_modified_on,m.image_name1,m.image_name2,m.image_name3,m.image_name4,m.credit_note_image,p.module_name,cl.maker_id,cl.checker_id,cl.description');
		$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');	
		$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');		
		$this->db->where('m.id =',$req_id);	
		$this->db->group_by('m.req_id');
		$this->db->order_by('m.id','DESC');
		$res_arr = $this->master_model->getRecords("maker_checker m");	
		
		// echo $this->db->last_query(); exit;
				//print_r($res_arr); die;
		$data['reuest_list'] = $res_arr;			
		
		// requyest action details.
		$this->db->select('m.id,m.req_id,cl.maker_id,cl.checker_id,cl.description,cl.created_on,a.name as checker_name, b.name as maker_name,cl.action_status');
		$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
		$this->db->join('credit_note_list cl','m.req_id=cl.req_id');	
		$this->db->join('administrators a','cl.checker_id=a.id','LEFT');
		$this->db->join('administrators b','cl.maker_id=b.id','LEFT');	
		$this->db->where('m.id =',$req_id);	
		
		$this->db->order_by('cl.created_on','DESC');
		$reuest_action_list = $this->master_model->getRecords("maker_checker m");	
		$data['reuest_action_list'] = $reuest_action_list;

		$this->load->view('creditnote/admin/checker/checker_refundreq_details',$data);
			
		}

	}

	//action performed on request
	public function action_status(){
     
     $id	= $this->input->post('id');
     $action	= $this->input->post('action');
      if($action == 1){
 		$title = "Request Approved Successfully.";
 		$title1 = "Error occured while approving request.";
      }elseif($action == 2){
      	$title = "Request Rejected Successfully.";
      	$title1 = "Error occured while rejecting request.";
      }elseif($action == 3){
      	$title = "Request Drop Successfully.";
      	$title1 = "Error occured while Drop request.";
      }

		if(isset($_POST['btnSubmit'])){

			$this->form_validation->set_rules('action','Action','trim|required');
			$this->form_validation->set_rules('checker_id','Checker Id','trim|required');
			$this->form_validation->set_rules('maker_id','Maker Id','trim|required');
			$this->form_validation->set_rules('req_id','Request Id','trim|required');

		if($this->form_validation->run()==TRUE){
            
		
			$action	= $this->input->post('action');

			$insert_data = array(	

								'checker_id'		=>$this->input->post('checker_id'),

								'maker_id'			=>strtoupper($this->input->post('maker_id')),
								
								'req_id'			=>strtoupper($this->input->post('req_id')),
								
								'action_status'		=>$action,

								'description'		=>strtoupper($this->input->post('description')),

								'created_on'		=>date('Y-m-d H:i:s'),

							);

				

				if($this->master_model->insertRecord('credit_note_list',$insert_data))

				{  

			    	$update_data = array(

						 'req_status'		=>$action,

						 'req_modified_on'		=>date('Y-m-d H:i:s'),
					)
;
					$this->master_model->updateRecord('maker_checker',$update_data,array('id'=>$id));
					$obj = new OS_BR();
					$browser_details=implode('|',$obj->showInfo('all'));
					$user_agent = $_SERVER['HTTP_USER_AGENT'];
					$logs_data = array(

									'date'=>date('Y-m-d H:i:s'),

									'title'=>$title,

									'description'=>serialize($insert_data),

									'userid'=>$this->UserID,

									'browser'=>$browser_details,

									'user_agent'=>$user_agent,

									'ip'=>$this->input->ip_address()

								);

					$this->master_model->insertRecord('maker_checker_logs',$logs_data);

					$this->session->set_flashdata('success',$title);

					redirect(base_url().'creditnote/admin/Checker/request_details/'.base64_encode($id));

				}

				else

				{
					$obj = new OS_BR();
					$browser_details=implode('|',$obj->showInfo('all'));
					$user_agent = $_SERVER['HTTP_USER_AGENT'];
					$logs_data = array(

									'date'=>date('Y-m-d H:i:s'),

									'title'=>$title1,

									'description'=>serialize($insert_data),

									'userid'=>$this->UserID,

									'browser'=>$browser_details,

									'user_agent'=>$user_agent,

									'ip'=>$this->input->ip_address()

								);

					$this->master_model->insertRecord('maker_checker_logs',$logs_data);

					$this->session->set_flashdata('error',$title1);

					redirect(base_url().'creditnote/admin/Checker/request_details/'.base64_encode($id));

				}

          }

          else

			{
                $this->session->set_flashdata('error',validation_errors());
				//$data['validation_errors'] = validation_errors(); 
				redirect(base_url().'creditnote/admin/Checker/request_details/'.base64_encode($id));

			}


		}

		redirect(base_url().'creditnote/admin/Checker/request_details/'.base64_encode($id));

	}

	//view list of report
	public function report()
	{		
		
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = $from_date = $end_date ='';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		//$data = $this->getUserInfo();
		$res_arr = array();
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'creditnote/admin/checker/report">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'creditnote/admin/checker/report">Checker</a></li>
		</ol>';		
		
		// Export data using filter from and to date
		 if($this->input->post('export'))
		{

        
		$from_date = $this->input->post('from_date');//'2019-09-12';
		$end_date = $this->input->post('to_date');//'2019-09-15';
		
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "Export_Report.csv";

        $query = "
		SELECT `m`.`req_id` as 'Request Id' , `m`.`req_title` as 'Requset Title', `m`.`req_desc` as 'Request Description', `m`.`req_member_no` as 'Member No', `m`.`transaction_no` as 'Transection No', `m`.`req_exceptional_case` as 'Exceptional Case', `m`.`req_reason` as 'Request Reason', `m`.`req_created_on`,m.credit_note_date, `p`.`module_name` as 'Module Name', `cl`.`description` as 'Status Description',ac.action as 'Request status' FROM `maker_checker` `m` LEFT JOIN `pay_type_master` `p` ON `m`.`req_module`=`p`.`pay_type` LEFT JOIN `credit_note_list` `cl` ON `m`.`req_id`=`cl`.`req_id` LEFT JOIN `credit_note_action` `ac` ON `m`.`req_status`=`ac`.`action_id` WHERE (DATE(credit_note_date) BETWEEN '".$from_date."' AND '".$end_date."') GROUP BY `m`.`req_id` ORDER BY `m`.`id` ASC
		";
        
		$result1 = $this->db->query($query);

		
		//echo $this->db->last_query(); die;
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		
		force_download($filename, $data);
	   } 

		
		// search data using filter from and to date

		 if($this->input->post('submit'))
		{

		$from_date = $this->input->post('from_date');//'2019-09-12';
		$end_date = $this->input->post('to_date');//'2019-09-15';
		
		    $this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.credit_note_date,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
			$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
			$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
			$this->db->where('(DATE(credit_note_date) BETWEEN "'.$from_date.'" AND "'.$end_date.'")');
			$this->db->group_by('m.req_id');
			$this->db->order_by('m.id','DESC');
			$res_arr = $this->master_model->getRecords("maker_checker m");	

	   } else{
			$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.req_modified_on,m.credit_note_date,p.module_name,cl.maker_id,cl.checker_id,cl.description');
		  $this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
		  $this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
		  $this->db->group_by('m.req_id');
		  $this->db->order_by('m.id','DESC');
		  $res_arr = $this->master_model->getRecords("maker_checker m");	
		}
		
		
		// echo $this->db->last_query(); exit;
		$data['from_date'] =$from_date;	
		$data['to_date'] =$end_date;		
		$data['reuest_list'] = $res_arr;			
		
		$this->load->view('creditnote/admin/checker/report',$data);
	}

    // get request reoprt from and to date filter
	public function report_by_date()
	{

		
		//echo "swaa"; die;
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
		$value = '2019-09-15~2019-09-17';
		
		if(strpos($value, '~') !== false)
		{
			$new_value = explode('~',$value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			
			if($value1 != "" && $value2 == "")
			{
				$date1 = $value1;
				$date2 = $value1;
			}
			else if($value1 != "" && $value2 != "")
			{
				$date1 = $value1;
				$date2 = $value2;	
			}
			
			

			$this->db->select('m.req_id,m.req_title,m.req_member_no,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id');
			$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
			$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
			$this->db->where('(DATE(credit_note_date) BETWEEN "'.$date1.'" AND "'.$date2.'")');
			$this->db->group_by('m.req_id');
			$this->db->order_by('m.id','DESC');
			$reuest_list = $this->master_model->getRecords("maker_checker m", '', '', '', $sortkey, $sortval, $per_page, $start);	

			$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.credit_note_date,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
			$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
			$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
			$this->db->where('(DATE(credit_note_date) BETWEEN "'.$date1.'" AND "'.$date2.'")');
			$this->db->group_by('m.req_id');
			$this->db->order_by('m.id','DESC');
			$total_row = $this->UserModel->getRecordCount("maker_checker m","","");
		
			
		}
		
		
		//$data['query'] = $this->db->last_query();
		
		if($reuest_list)
		{
			//$result = $result->result_array();
			
			$data['reuest_list'] = $reuest_list;
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."creditnote/admin/checker/report/";
			//$total_row = count($result);
			$config = pagination_init($url,$total_row, $per_page, 2);
			$this->pagination->initialize($config);
			
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
	
} ?>