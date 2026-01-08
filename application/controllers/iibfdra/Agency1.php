<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Agency1 extends CI_Controller {	
	public $UserID;			
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
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
	
	
	public function index()
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
		<li><a href="'.base_url().'iibfdra/agency1/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/agency1/">Agency</a></li>
		</ol>';		
		
		//ON (u.account_id = a.account_id OR n.account_id = a.account_id)
		
				
		/*
		OLD CODE
		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('agency_center','dra_inst_registration.id=agency_center.agency_id','LEFT');		
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		$this->db->where('agency_center.center_add_status="F"');
		$this->db->where('agency_center.pay_status !=0');
		$this->db->where('agency_center.institute_code !=""');		
		$this->db->where('dra_accerdited_master.accerdited_delete !=1');
		$this->db->where('dra_accerdited_master.institute_code !=1');	
		$this->db->order_by('dra_inst_registration.status','DESC');	
		$this->db->order_by('dra_inst_registration.modified_on','DESC');	
		$result = $this->master_model->getRecords("dra_inst_registration");	*/
		
		
		
		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('agency_center','dra_inst_registration.id=agency_center.agency_id','LEFT');		
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		//$this->db->where('agency_center.center_add_status="F"');
		$this->db->where('agency_center.pay_status !=','0');
		$this->db->where('agency_center.institute_code !=""');		
		$this->db->where('dra_accerdited_master.accerdited_delete !=1');
		$this->db->where('dra_accerdited_master.institute_code !=1');	
		$this->db->group_by('agency_center.institute_code'); 
		$this->db->order_by('dra_inst_registration.status','DESC');	
		$this->db->order_by('dra_inst_registration.modified_on','DESC');
		$result = $this->master_model->getRecords("dra_inst_registration");	
		
		// echo $this->db->last_query(); exit;
		
		$data['agency_list'] = array();	
		if(count($result))
		{
			//$result = $res->result_array();			
			foreach($result as $row)
			{
				$confirm = "";
				$str_btn = '';
				$row['created_on'] = date_format(date_create($row['created_on']),"d-M-Y");
				$agency_id =  $row['id'];
				
				$this->db->where('agency_id',$agency_id);
				$this->db->where('center_type','R');
				$this->db->where('center_display_status','1'); // added on 18 mar 2019 to hide centers from list		 
				$total_row_regular_center = $this->master_model->getRecordCount("agency_center",$field,$value);				
				$row['regular_center'] = $total_row_regular_center;				
				
				$this->db->where('agency_id',$agency_id);
				$this->db->where('center_type','T');
				$this->db->where('center_display_status','1');	 // added on 18 mar 2019 to hide centers from list				
				$total_row_temp_center = $this->master_model->getRecordCount("agency_center",$field,$value);				
				$row['temp_center'] = $total_row_temp_center;					
			
				$res_arr[] = $row;
			}			
			$data['agency_list'] = $res_arr;			
		}		
		
		$this->load->view('iibfdra/admin/agency1/agency_list',$data);
	}
	
	// function to view All batches receipt -
	public function agency_detail($agency_id)
	{
		$agency_id = $agency_id;
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/agency1/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/agency1/">Agency</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/agency1/agency_detail/'.$agency_id.'">Agency Detail</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/agency1/agency_detail';		
		
		// Accept and Reject Ajency 
		if(isset($_REQUEST['status'])) {
			if($_REQUEST['status'] == 1) {
				$this->agency_deactivate($agency_id);				
			}else{
				$this->agency_activate($agency_id);
			}			
		}
		
		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->where('dra_inst_registration.id',$agency_id);		
		$res_agency = $this->master_model->getRecords("dra_inst_registration");
		
		
		$this->db->select('agency_center.*,state_master.state_name,city_master.city_name');
		$this->db->where('agency_id',$agency_id);
		$this->db->where('agency_center.center_display_status','1');
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->join('state_master','agency_center.state=state_master.state_code','LEFT');
		$this->db->order_by("agency_center.created_on", "DESC");
		$res_arr = $this->master_model->getRecords("agency_center");
		$res_value = array();
		
	
		$res_arr_log = array();
		$this->db->join('dra_admin','dra_agency_action_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_action_adminlogs.agency_id',$agency_id);
		$this->db->order_by('dra_agency_action_adminlogs.date','DESC');		
		$res_arr_log = $this->master_model->getRecords("dra_agency_action_adminlogs");
		
		
		if(count($res_arr))
		{
			//$result = $res_arr->result_array();			
			foreach($res_arr as $row_val)
			{
				
				 if( $row_val['center_type'] == 'R'){
					$row_val['center_type'] = 'Regular';
				  }else{
				 	$row_val['center_type'] = 'Temporary'; 
				 }
			
				 if( $row_val['center_status'] == 'A'){		
				 			 
				 	$row_val['center_status_text'] = 'Approved';
										
				  }if( $row_val['center_status'] == 'IR'){
					  
				 	$row_val['center_status_text'] = 'In Review';
					
				  }if( $row_val['center_status'] == 'R'){					  
				 	$row_val['center_status_text'] = 'Reject'; 					
				 }
				 
				$res_value[] = $row_val;
			}			
		}else{
			redirect(base_url() . 'iibfdra/Agency');
		}
		
		$data['center_result'] = $res_value;
		$data['result'] = $res_agency[0];
		$data['agency_log'] = $res_arr_log;			 
		$this->load->view('iibfdra/admin/agency1/agency_detail',$data);	
		
	}
	
	public function agency_renew($agency_id)
	{
		$agency_id = $agency_id;
		
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/agency1/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/agency1/">Agency</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/agency1/agency_detail/'.$agency_id.'">Agency Renew</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/agency1/agency_renew';		
		
		// Accept and Reject Ajency 
		if(isset($_REQUEST['status'])) {
			if($_REQUEST['status'] == 1){
				$this->agency_deactivate($agency_id);				
			}else{
				$this->agency_activate($agency_id);
			}					
		}
		
		if(isset($_REQUEST['action'])) {
			
			if($_REQUEST['action'] == 'renew_regular'){
				
				$this->renew_accreditation_period($agency_id);	
				$renewal_type = $this->input->post('renewal_type');
				$this->session->set_flashdata('success', 'Agency Regular Center Applied with '.$renewal_type.' Renewal !');
				
				redirect(base_url() . 'iibfdra/Agency');		
			}			
		}
		
		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->where('dra_inst_registration.id',$agency_id);
		$res_agency = $this->master_model->getRecords("dra_inst_registration");		
		
		$this->db->select('agency_center.*,state_master.state_name,city_master.city_name');
		$this->db->where('agency_id',$agency_id);
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->join('state_master','agency_center.state=state_master.state_code','LEFT');
		$this->db->where('center_status','A');
		$this->db->where('center_display_status','1');
		$this->db->where('center_validity_to !=','');
		$this->db->where('center_type','R');
		$res_arr = $this->master_model->getRecords("agency_center");
		$res_value = array();
		
		if(count($res_arr))
		{
			//$result = $res_arr->result_array();			
			foreach($res_arr as $row_val)
			{				
				 $center_id_arr[] = $row_val['center_id'];
				if( $row_val['center_type'] == 'R'){
					$row_val['center_type'] = 'Regular';
				}else{
					$row_val['center_type'] = 'Temporary'; 
				}
				
				if( $row_val['center_status'] == 'A'){
					$row_val['center_status_text'] = 'Approved';									
				}elseif( $row_val['center_status'] == 'IR'){				  
					$row_val['center_status_text'] = 'In Review';				
				}elseif( $row_val['center_status'] == 'R'){					  
					$row_val['center_status_text'] = 'Reject'; 					
				}
				
				$res_value[] = $row_val;
			}			
		}else{
			redirect(base_url() . 'iibfdra/Agency');
		}
		
		$res_center_renew = array();
		$center_id_array = array_values($center_id_arr);
		$center_id_arr_res = implode(',',$center_id_array);
		
		$this->db->where('agency_center_renew.agency_id',$agency_id);
		$this->db->where('agency_center_renew.center_type','R');		
		$where = "FIND_IN_SET(".$center_id_arr_res.",`centers_id`)"; 		
		$this->db->order_by("agency_center_renew.created_on", "DESC");
		$this->db->limit(1);	
		$res_center_renew = $this->master_model->getRecords('agency_center_renew'); 		
		
		$data['res_center_renew'] = $res_center_renew;
		$data['center_result'] = $res_value;
		$data['result'] = $res_agency[0];			 
		$this->load->view('iibfdra/admin/agency1/agency_renew',$data);	
		
	}
	
	// Activate Agency 
	public function agency_activate($agency_id){	
	
		$this->load->helper('general_agency_helper');	
		
		$updated_date = date('Y-m-d H:i:s');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$reason = $_REQUEST['reject_reason'];
		
		$update_data = array(
		'status'		=> 1,									
		'modified_on'	=> $updated_date
		);
		
		$insert_data = array(	
		'agency_id'		=> $agency_id,		
		'reason'		=> $reason,
		'user_id'		=> $drauserdata['id'],	
		'status'		=> 1,
		'modified_on'	=> $updated_date,
		'updated_by'	=> $user_type_flag,
		);
						
		log_dra_admin($log_title = "DRA Admin Activate Agency", $log_message = serialize($update_data));		
		$this->master_model->updateRecord('dra_inst_registration',$update_data,array('id' => $agency_id));
		log_dra_agency_action($log_title = "Agency Activate",$agency_id,serialize($insert_data));	
	}
	
	// Reject / deactivate Agency 
	public function agency_deactivate($agency_id){	
		
		$this->load->helper('general_agency_helper');	
	
		$updated_date = date('Y-m-d H:i:s');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];		
		$reason = $_REQUEST['reject_reason'];
		$update_data = array(
		'status'		=> 0,									
		'modified_on'	=> $updated_date
		);
		
		$insert_data = array(	
		'agency_id'		=> $agency_id,		
		'reason'		=> $reason,
		'user_id'		=> $drauserdata['id'],	
		'status'		=> 0,
		'modified_on'	=> $updated_date,
		'updated_by'	=> $user_type_flag,
		);
						
		log_dra_admin($log_title = "DRA Admin Deactivate Agency", $log_message = serialize($update_data));		
		$this->master_model->updateRecord('dra_inst_registration',$update_data,array('id' => $agency_id));
		log_dra_agency_action($log_title = "Agency Deactivate",$agency_id,serialize($insert_data));	
	}
	

	// function to view All batches receipt -
	public function training_center_detail($center_id)
	{	
		$center_id = $center_id;
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/agency1/agency_detail">
		<i class="fa fa-home"></i> Home</a></li>
			  <li class=""><a href="'.base_url().'iibfdra/agency1/">Agency</a></li>
			  <li class="active">Agency Center Details</li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/agency1/agency_detail';		
		
		// Accept and Reject Center 
		if(isset($_REQUEST['action'])) {
			
			if($_REQUEST['action'] == 'update_status'){
			
				if(isset($_REQUEST['action_status'])) {
					if($_REQUEST['action_status'] != 'A' &&  $_REQUEST['action_status'] != ''){						
						$this->agency_center_reject($center_id);				
					}else if($_REQUEST['action_status'] == 'A'){
						//$this->agency_batch_approve($batch_id);
						$this->agency_center_approve($center_id);
					}
				}			
			
			} else if($_REQUEST['action'] == 'add_date'){				
				$this->add_accreditation_period($center_id);					
			}
		}		
		
		$this->db->select('agency_center.*,dra_inst_registration.inst_name,state_master.state_name,city_master.city_name');
		$this->db->join('dra_inst_registration','dra_inst_registration.id=agency_center.agency_id','LEFT');
		$this->db->join('state_master','agency_center.state=state_master.state_code','LEFT');
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->where('agency_center.center_display_status','1');
		$this->db->where('agency_center.center_id = "'.$center_id.'"');	
		$res_center = $this->master_model->getRecords('agency_center'); 		
		
		if(count($res_center) > 0){
		
		}else{
			redirect(base_url() . 'iibfdra/Agency');
		}
		//payment_transaction city_master
		
		/*
		 Need to check pay type : pay_type : 1,2,3 etc
		SELECT `payment_transaction`.`id`, `exam_invoice`.`invoice_image` FROM `payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id`=`payment_transaction`.`id` WHERE `payment_transaction`.`ref_id` = "7"
		*/
		
		$res_center[0]['invoice_image'] = '';
		$res_center[0]['transaction_id'] = '';
		
		//if($res_center[0]['pay_status'] == 1){
		if($res_center[0]['pay_status'] == 1 || $res_center[0]['pay_status'] == 2  ){
			 
		 	//Need to check pay type : pay_type : 1,2,3 etc 12 for DRA		
			//$select_invoice = 'payment_transaction.id,exam_invoice.invoice_image';	
			$this->db->select('payment_transaction.id,exam_invoice.invoice_image');	
			$this->db->join('exam_invoice','exam_invoice.pay_txn_id=payment_transaction.id','INNER');		
			$this->db->where('payment_transaction.ref_id = "'.$center_id.'"');
			$this->db->where('exam_invoice.invoice_image !=""');
			$this->db->where('exam_invoice.app_type = "H"');
			$this->db->where('payment_transaction.pay_type = 16');	
			$this->db->order_by("exam_invoice.created_on", "DESC");
    		$this->db->limit(1);
			$res_invoice = $this->master_model->getRecords('payment_transaction');
			if(count($res_invoice) > 0 ){
				$res_center[0]['invoice_image'] = $res_invoice[0]['invoice_image'];	
				$res_center[0]['transaction_id'] = $res_invoice[0]['id'];	
			}else{
				$res_center[0]['invoice_image'] = '';	
				$res_center[0]['transaction_id'] = '';	
			}
		
			
		}		
		
		$data['result'] = $res_center[0];
		
		$this->db->join('dra_admin','dra_agency_center_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_center_adminlogs.center_id',$center_id);
		$this->db->order_by('dra_agency_center_adminlogs.date','DESC');		
		$res_logs = $this->master_model->getRecords("dra_agency_center_adminlogs");	
		//$res_center_logs = $res_logs->result_array();		
		$data['agency_center_logs'] = $res_logs;
		
		
		$this->load->view('iibfdra/admin/agency1/agency_center_detail',$data);		
	}

	// Approve Agency Center 
	public function agency_center_approve($center_id){	
	
		$this->load->helper('general_agency_helper');
		  
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$updated_date = date('Y-m-d H:i:s');
		//pay_status 2 : make payment 
		if($user_type_flag == '1')
		{
			$center_status = 'A';
			$pay_status = '2';
			
		}else if($user_type_flag == '2'){
			$center_status = 'AR'; // Approved by recommender
			$pay_status = '2';
		}
		
		$update_data = array(
						'center_status'		=> $center_status,	
						'user_id'			=> $drauserdata['id'],								
						'modified_on'		=> $updated_date,
						'date_of_approved'  => $updated_date,
						'pay_status'		=> '2',	
						'updated_by' 		=> $user_type_flag 
						);
			
		log_dra_agency_admin($log_title = "DRA Admin Approved Agency Center", $log_message = serialize($update_data));
		log_dra_agency_center_detail($log_title = "Center Approved",$center_id,serialize($update_data));				
		log_dra_admin($log_title = "DRA Admin Approved Agency Center", $log_message = serialize($update_data));	

		$this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_id));
		if($user_type_flag == '1')
		{
		agency_center_approve_mail($center_id);
		}

	}
	
	// Reject Agency Center
	public function agency_center_reject($center_id){
		
		$this->load->helper('general_agency_helper');	
	
		$updated_date = date('Y-m-d H:i:s');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$center_add_status = $this->input->post('center_add_status');
		
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}
		
		
		if($center_add_status == 'F'){
			//refund case
			//'pay_status'	=> '4' for refund pending
			$update_data = array(
				'center_status'	=> 'R',	
				'user_id'		=> $drauserdata['id'],										
				'modified_on'	=> $updated_date,
				'pay_status'	=> '4',
				'updated_by' 	=> $user_type_flag 
				);
			
			}else{				
				$update_data = array(
				'center_status'	=> 'R',	
				'user_id'		=> $drauserdata['id'],										
				'modified_on'	=> $updated_date,
				'updated_by' 	=> $user_type_flag 
				);
				
			}
		
		$insert_data = array(	
						'agency_id'		=>$this->input->post('agency_id'),
						'user_type'		=>$user_type,
						'center_id'		=>$center_id,
						'rejection'		=>$this->input->post('rejection'),
						'user_id'		=>$drauserdata['id'],						
						'created_on'	=>date('Y-m-d H:i:s'),
						);
						
		$log_data =	array(	
						'agency_id'		=>$this->input->post('agency_id'),
						'user_type'		=>$user_type,
						'rejection'		=>$this->input->post('rejection'),
						'updated_by' 	=> $user_type_flag ,
						'user_id'		=>$drauserdata['id'],
						'created_on'	=>date('Y-m-d H:i:s'),
						);	
		
		if($this->master_model->insertRecord('agency_center_rejection',$insert_data)){	
			$this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_id));
			agency_center_reject_mail($center_id);
		}
		
		log_dra_agency_admin($log_title = "DRA Admin Reject Agency Center", $log_message = serialize($update_data));
		log_dra_agency_center_detail($log_title = "Center Rejected",$center_id,serialize($log_data));		
		
		log_dra_admin($log_title = "DRA Admin Reject Agency Center", $log_message = serialize($update_data));	
		log_dra_admin($log_title = "DRA Admin Reject Agency Center Add entry in agency_center_rejection table", $log_message = serialize($insert_data));	

		
	}	
	// Approve Agency Center 
	public function add_accreditation_period($center_id){	
		  
		$this->load->helper('general_agency_helper');
		
		$center_add_status 	= $this->input->post('center_add_status');
		$drauserdata 		= $this->session->userdata('dra_admin');	
		$user_type_flag 	= $drauserdata['roleid'];	
		$updated_date 		= date('Y-m-d H:i:s');
		$center_type 		= $this->input->post('center_type');
		$is_renew			= $this->input->post('is_renew');
		
		if($center_type == 'R'){
			//$from_date 	= date('Y-m-d H:i:s');	// flow change as suggested by sonal		
			$from_date 	= date ("Y-m-d", strtotime ($this->input->post('center_validity_from_date')));
			$todate_val = $this->input->post('center_validity_to_date');
			$to_date = date ("Y-m-d", strtotime ($todate_val));
		}else{
			$from_date 	= date ("Y-m-d", strtotime ($this->input->post('center_validity_from_date')));
			$to_date 	= date ("Y-m-d", strtotime ($this->input->post('center_validity_to_date')));
			
			// OLD code to set accreditation period
			//$to_date =  date ("Y-m-d", strtotime ($from_date ."+90 days"));
			//$from_date;	 
			//echo date('Y-m-d', strtotime("+90 days"));
			//$today = "2015-06-15"; // Or can put $today = date ("Y-m-d");
			//$fiveDays = date ("Y-m-d", strtotime ($today ."+5 days"));		
		}
		
		$this->db->where('center_id',$center_id);		
		//$res_data = $this->UserModel->getRecords("agency_center"); //NCC
		$res_data = $this->master_model->getRecords("agency_center");
		//$update_accreditation_reson = $this->input->post('update_accreditation_reson');	
		if(isset($_REQUEST['update_accreditation_reason'])){
			
			$update_accreditation_reason  = $_REQUEST['update_accreditation_reason'];
		}else{
			$update_accreditation_reason = '';	
		}
		//$result_val = $res_data->result_array();		
		$result = $res_data[0]; 		
		$update_data = array();
		if( $result['center_validity_to'] != '' )	{
			if($is_renew == 1){
				$query_val = 'RENEW';
			}else{
				$query_val = 'UPDATE';
			}
			
				//center_validity_to		
				$update_data = array(						
							'user_id'				=> $drauserdata['id'],								
							'modified_on'			=> $updated_date,					
							'center_validity_from'  => $from_date,
							'center_validity_to'  	=> $to_date,
							'is_renew'				=> 0,
							'updated_by' 			=> $user_type_flag 
							);
							
				$update_data_log = array(						
							'user_id'						=> $drauserdata['id'],								
							'modified_on'					=> $updated_date,
							'center_validity_from'  		=> $from_date,
							'is_renew'						=> 0,
							'center_validity_to'  			=> $to_date,
							'update_accreditation_reason'  	=> $update_accreditation_reason,
							'updated_by' 					=> $user_type_flag 
							);	
						
		}else{
			$query_val = 'ADD';	
			// For fresh center 
			if($center_add_status == 'F'){
	//center_validity_to date_of_approved is assign at the time of add accreditation period => sugg by sonal on 29 jan 2019		
				$update_data = array(						
							'user_id'				=> $drauserdata['id'],								
							'modified_on'			=> $updated_date,	
							'date_of_approved'		=> $updated_date,		
							'center_validity_from'  => $from_date,
							'center_validity_to'  	=> $to_date,
							'updated_by' 			=> $user_type_flag 
							);
				
			}else{
				$update_data = array(						
							'user_id'				=> $drauserdata['id'],								
							'modified_on'			=> $updated_date,
							'center_validity_from'  => $from_date,
							'center_validity_to'  	=> $to_date,
							'updated_by' 			=> $user_type_flag 
							);	
			}		
		}	
		
		
		if($query_val == 'ADD')	{
			$update_data_log = $update_data;
		}
		
		
		if($query_val == 'RENEW'){
			
			$update_renewal_data = array(				
								'modified_on'			=> $updated_date,
								'center_validity_from'  => $from_date,
								'center_validity_to'  	=> $to_date,							
								);
			
			//$this->db->where('agency_center_renew.center_id = "'.$center_id.'"');
			$this->db->order_by("agency_center_renew.created_on", "DESC");
			$this->db->limit(1);	
			$this->master_model->updateRecord('agency_center_renew',$update_renewal_data,array('centers_id' => $center_id));	

			
		}
					
		log_dra_admin($log_title = "DRA Admin ".$query_val." Accreditation Period for Agency Center", $log_message = serialize($update_data));		
		log_dra_agency_center_detail($log_title = "Accreditation Period  ".$query_val." ",$center_id,serialize($update_data_log));	
		$this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_id));
		center_accradation_period_mail($center_id);

	}
	
	
	
	// Renew Regular Center by admin 
	public function renew_accreditation_period($agency_id)
	{	
		$this->load->helper('general_agency_helper');		
		//$center_add_status 	= $this->input->post('center_add_status');
		$drauserdata 		= $this->session->userdata('dra_admin');	
		$user_type_flag 	= $drauserdata['roleid'];	
		$updated_date 		= date('Y-m-d H:i:s');
		$center_type 		= 'R';
		$agency_id			= $this->input->post('agency_id');
		$is_renew			= $this->input->post('is_renew');
		//$from_date 	= date('Y-m-d H:i:s');	// flow change as suggested by sonal		
		$from_date 	= date ("Y-m-d", strtotime ($this->input->post('center_validity_from_date')));
		$todate_val = $this->input->post('center_validity_to_date');
		$to_date = date ("Y-m-d", strtotime ($todate_val));
		
		$renewal_type = $this->input->post('renewal_type');
		$center_ids = $this->input->post('center_ids');
		$center_arr = explode(',',$center_ids);
		
		foreach($center_arr as $center_id){
			
			if($renewal_type == 'free' ){
			
				$update_data = array(						
								'user_id'				=> $drauserdata['id'],								
								'modified_on'			=> $updated_date,					
								'center_validity_from'  => $from_date,
								'center_validity_to'  	=> $to_date,
								'is_renew'				=> 0,
								'updated_by' 			=> $user_type_flag 
								);
				
				$log_data =	array(	
							'user_id'				=> $drauserdata['id'],								
							'modified_on'			=> $updated_date,					
							'center_validity_from'  => $from_date,
							'center_validity_to'  	=> $to_date,
							'is_renew'				=> 0,
							'updated_by' 			=> $user_type_flag, 
							'renewal_type'			=> $renewal_type
							);
				
			}elseif($renewal_type == 'pay'){
				
				$update_data = array(						
								'user_id'				=> $drauserdata['id'],								
								'modified_on'			=> $updated_date,					
								'center_validity_from'  => $from_date,
								'center_validity_to'  	=> $to_date,
								'is_renew'				=> 1,
								'pay_status'			=> '2',
								'updated_by' 			=> $user_type_flag 
								);
				
				$log_data =	array(	
								'user_id'				=> $drauserdata['id'],								
								'modified_on'			=> $updated_date,					
								'center_validity_from'  => $from_date,
								'center_validity_to'  	=> $to_date,
								'is_renew'				=> 1,
								'pay_status'			=> '2',
								'updated_by' 			=> $user_type_flag,
								'renewal_type'			=> $renewal_type
								);
			}
					
			$this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_id));	
			log_dra_agency_center_detail($log_title = "Renew Accreditation Period ",$center_id,serialize($log_data));
			
		}
		
		$insert_data = array(
							'agency_id'				=> $agency_id,						
							'centers_id'			=> $center_ids,	
							'center_validity_from'  => $from_date,
							'center_validity_to'  	=> $to_date,
							'renew_type'			=> $renewal_type,
							'pay_status'			=> '2',
							'created_on'			=> $updated_date,	
							'update_by' 			=> $user_type_flag 
							);		
		
		if($this->master_model->insertRecord('agency_center_renew',$insert_data)){	
			log_dra_admin($log_title = "DRA Admin Renew regular Agency", $log_message = serialize($insert_data));	
		}
		
	}
	
	
	// function to view All receipts -
	public function training_center_receipt($center_id)
	{
		$center_id = $center_id;
		$receipt_array = array();
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/agency1/agency_detail">
		<i class="fa fa-home"></i> Home</a></li>
			  <li class=""><a href="'.base_url().'iibfdra/agency1/">Agency</a></li>
			  <li class="active"><a href="'.base_url().'iibfdra/agency1/training_center_detail/'.$center_id.'">Agency Center Details</a></li>
			  <li class="active">Agency Center Receipt Details</li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/agency1/agency_detail';		
		
		// Accept and Reject Center 
		if(isset($_REQUEST['action'])) {
			
			if($_REQUEST['action'] == 'update_status'){
			
				if(isset($_REQUEST['action_status'])) {
					if($_REQUEST['action_status'] != 'A' &&  $_REQUEST['action_status'] != ''){						
						$this->agency_center_reject($center_id);				
					}else if($_REQUEST['action_status'] == 'A'){
						//$this->agency_batch_approve($batch_id);
						$this->agency_center_approve($center_id);
					}
				}			
			
			} else if($_REQUEST['action'] == 'add_date'){				
				$this->add_accreditation_period($center_id);					
			}
		}		
		
		$this->db->select('agency_center.*,dra_inst_registration.inst_name,state_master.state_name,city_master.city_name');
		$this->db->join('dra_inst_registration','dra_inst_registration.id=agency_center.agency_id','LEFT');
		$this->db->join('state_master','agency_center.state=state_master.state_code','LEFT');
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->where('agency_center.center_id = "'.$center_id.'"');	
		$res_center = $this->master_model->getRecords('agency_center'); 	
		echo $this->db->last_query();exit;
		if(count($res_center) > 0){
		
		}else{
			redirect(base_url() . 'iibfdra/Agency');
		}
			
		//payment_transaction city_master		
		/*
		 Need to check pay type : pay_type : 1,2,3 etc
		SELECT `payment_transaction`.`id`, `exam_invoice`.`invoice_image` FROM `payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id`=`payment_transaction`.`id` WHERE `payment_transaction`.`ref_id` = "7"
		*/
		
		$res_center[0]['invoice_image'] = '';
		$res_center[0]['transaction_id'] = '';
		
		//if($res_center[0]['pay_status'] == 1){
		if($res_center[0]['pay_status'] == '1' || $res_center[0]['pay_status'] == '2'){
			 
		 	//Need to check pay type : pay_type : 1,2,3 etc 12 for DRA		
			//$select_invoice = 'payment_transaction.id,exam_invoice.invoice_image';	
			$this->db->select('payment_transaction.id,exam_invoice.invoice_image,exam_invoice.date_of_invoice');	
			$this->db->join('exam_invoice','exam_invoice.pay_txn_id=payment_transaction.id','INNER');		
			$this->db->where('payment_transaction.ref_id = "'.$center_id.'"');
			$this->db->where('exam_invoice.invoice_image !=""');
			$this->db->where('exam_invoice.app_type = "H"');
			$this->db->where('payment_transaction.pay_type = 16');	
			$this->db->order_by("exam_invoice.created_on", "DESC");
    		$this->db->limit(1);
			$res_invoice = $this->master_model->getRecords('payment_transaction');
			if(count($res_invoice) > 0 ){
				$res_center[0]['invoice_image'] = $res_invoice[0]['invoice_image'];	
				$res_center[0]['transaction_id'] = $res_invoice[0]['id'];
				$res_center[0]['date_of_invoice'] = $res_invoice[0]['date_of_invoice'];		
			}else{
				$res_center[0]['invoice_image'] = '';	
				$res_center[0]['transaction_id'] = '';	
			}
		}			
		
		//$center_ids = explode(',',$center_id);
		//$this->db->where_in('agency_center_renew.centers_id ',$center_ids);
		
		$where = "FIND_IN_SET('".$center_id."', centers_id)"; 
		$this->db->where( $where );
		$res_center_renew_arr = $this->master_model->getRecords('agency_center_renew'); 
	
		
		if(count($res_center_renew_arr) > 0){
			foreach($res_center_renew_arr as $res_center_renew){
				
				if($res_center_renew['pay_status'] == 1){			
				 
					$agency_renew_id = $res_center_renew['agency_renew_id'];
					$centers_id = $res_center_renew['centers_id'];							
					
					// pay type 17 for renew
					//'app_type' => 'W', // W for Agency Center Renew
					//$select_invoice = 'payment_transaction.id,exam_invoice.invoice_image';
						
					$this->db->select('payment_transaction.id,exam_invoice.invoice_image,exam_invoice.date_of_invoice');	
					$this->db->join('exam_invoice','exam_invoice.pay_txn_id=payment_transaction.id','INNER');		
					$this->db->where('payment_transaction.ref_id = "'.$agency_renew_id.'"');
					$this->db->where('exam_invoice.invoice_image !=""');
					$this->db->where('exam_invoice.app_type = "W"');
					$this->db->where('payment_transaction.pay_type = 17');	
					//$this->db->order_by("exam_invoice.created_on", "DESC");
					//$this->db->limit(1);
					$res_renew_invoice = $this->master_model->getRecords('payment_transaction');
					if(count($res_renew_invoice) > 0 ){
						$receipt_array[] = array('invoice_image' => $res_renew_invoice[0]['invoice_image'] , 'transaction_id' => $res_renew_invoice[0]['id'],'date_of_invoice' => $res_renew_invoice[0]['date_of_invoice'], 'center_id' => $centers_id , 'agency_renew_id' => $agency_renew_id );
						
					}else{
						//$res_center[0]['invoice_image'] = '';	
						//$res_center[0]['transaction_id'] = '';	
					}
				}
			}
			
		}
		
		$data['result'] = $res_center[0];		
		$this->db->join('dra_admin','dra_agency_center_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_center_adminlogs.center_id',$center_id);
		$this->db->order_by('dra_agency_center_adminlogs.date','DESC');		
		$res_logs = $this->master_model->getRecords("dra_agency_center_adminlogs");			
		$data['agency_center_logs'] = $res_logs;
		$data['renew_receipt'] = $receipt_array;		
		
		$this->load->view('iibfdra/admin/agency1/receipt',$data);	
	}

	
} ?>