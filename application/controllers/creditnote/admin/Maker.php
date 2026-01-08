<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Maker extends CI_Controller {	
	public $UserID;			
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('creditnote_admin')) {
			redirect('creditnote/admin/Login');
		}else{
			$UserData = $this->session->userdata('creditnote_admin');
			//print_r($UserData); die;
			if($UserData['admin_user_type'] == 'Cheker'){
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
		<li><a href="'.base_url().'creditnote/admin/maker/refundrequest_list">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'creditnote/admin/maker/refundrequest_list">Maker</a></li>
		</ol>';		
		
		
		
		$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
		$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
		$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
		//$this->db->group_by('m.req_id');
		$this->db->order_by('m.id','DESC');
		$res_arr = $this->master_model->getRecords("maker_checker m");	
		
		// echo $this->db->last_query(); exit; 
				
		$data['reuest_list'] = $res_arr;			
		
		$this->load->view('creditnote/admin/maker/maker_refundreq_list',$data);
	}
	
	//check deatils of request
	public function request_details(){
     
          if($this->uri->segment(5) ) {

		    $req_id = trim($this->uri->segment(5) ); 
			$req_id = base64_decode($req_id);
			$req_id = intval($req_id);
	     //request details  
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

		$this->load->view('creditnote/admin/maker/maker_refundreq_details',$data);
			
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

			$this->form_validation->set_rules('checker_id','Checker Id');

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

					redirect(base_url().'creditnote/admin/Maker/request_details/'.base64_encode($id));

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

					redirect(base_url().'creditnote/admin/Maker/request_details/'.base64_encode($id));

				}

          }

          else

			{
                $this->session->set_flashdata('error',validation_errors());
				//$data['validation_errors'] = validation_errors(); 
				redirect(base_url().'creditnote/admin/Maker/request_details/'.base64_encode($id));

			}

		}

		redirect(base_url().'creditnote/admin/Maker/request_details/'.base64_encode($id));

	}

	//view list of requested refund cancelation 
	public function cancellation_list()
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
		<li><a href="'.base_url().'creditnote/admin/maker/cancellation_list">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'creditnote/admin/maker/cancellation_list">Maker</a></li>
		</ol>';		
		
		
		$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_created_on,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
		$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
		$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
		//$this->db->group_by('m.req_id');
		//$this->db->where('m.req_status !=',3);
		$this->db->where('(m.req_status != 3 AND m.req_status != 10)');			
		$this->db->order_by('m.id','DESC');
		$res_arr = $this->master_model->getRecords("maker_checker m");	
		
		// echo $this->db->last_query(); exit;
				
		$data['reuest_list'] = $res_arr;			
		
		$this->load->view('creditnote/admin/maker/cancellation_list',$data);
	}
	
	// Funciton to do cancellation
	public function do_cancellation(){
		$transaction_no = $this->uri->segment(5);
		$error = '';
		$flag_payment = 0;
		$flag_member = 0;
		$flag_admit = 0;
		$flag_invoice = 0;
		$flag_blended = 0;
		$get_transaction_no = $this->master_model->getRecords('maker_checker',array('req_status'=>1),'transaction_no');
		
			// Check on in payment_transaction
			$payment_transaction = $this->master_model->getRecords('payment_transaction',array('transaction_no'=>$transaction_no),'receipt_no,ref_id,status,exam_code,member_regnumber,pay_type');
			if($payment_transaction){ 
				if($payment_transaction[0]['status'] == 1){
					$flag_payment = 1;
					
					$member_register = $this->master_model->getRecords('member_registration',array('regnumber'=>$payment_transaction[0]['member_regnumber']),'excode,exam_period,registrationtype,excode');
					
					if($payment_transaction[0]['pay_type'] == 2){ 
						// Check on member exam
						$member_exam = $this->master_model->getRecords('member_exam',array('id'=>$payment_transaction[0]['ref_id']),'pay_status,exam_period');
						
						if($member_register[0]['registrationtype'] == 'NM'){
							if($member_register[0]['excode'] == $member_exam[0]['exam_code'])
							{
								if($member_register[0]['exam_period'] == $member_exam[0]['exam_period']){
									$update_member = array('isactive'=>'0','isdeleted'=>1);
									$this->master_model->updateRecord('member_registration',$update_member,array('regnumber'=>$payment_transaction[0]['member_regnumber']));
								}
							}
						}
						
						if($member_exam[0]['pay_status'] == 1){ 
							$flag_member = 1;
						}else{
							$error = 'Pay status not update in member_exam table';
						}
					
					}
					
					if($payment_transaction[0]['exam_code'] != 101 && $payment_transaction[0]['exam_code'] != 1010){
						if($payment_transaction[0]['pay_type'] == 2){
							// Check on admit card table
							$admit_card = $this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$payment_transaction[0]['ref_id']),'remark');
							if($admit_card[0]['remark'] == 1){
								$flag_admit = 1;
							}else{
								$error = 'Admit card not genrate';	
							}
						}
					}
					
					
					
					// Check on exam invoice
					$exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$payment_transaction[0]['receipt_no']),'invoice_no,invoice_image');
					if($exam_invoice[0]['invoice_no'] != '' && $exam_invoice[0]['invoice_image'] != ''){
						$flag_invoice = 1;	
					}else{
						$error = 'Invoice not genrate';	
					}
					/*echo 'flag_invoice>>'. $flag_invoice;
					echo '<br/>';
					echo 'flag_admit>>'. $flag_admit;
					echo '<br/>';
					echo 'flag_member>>'. $flag_member;
					echo '<br/>';
					echo 'flag_payment>>'. $flag_payment;
					echo '<br/>';
					exit;*/ 
					
					if($payment_transaction[0]['pay_type'] == 10){
							// check on blended registration table
							$blended_reg = $this->master_model->getRecords('blended_registration' , array('blended_id'=>$payment_transaction[0]['ref_id']),'pay_status');
							if($blended_reg[0]['pay_status'] == 1){
								$flag_blended = 1;
							}else{
								$error = 'Pay Status is already zero';	
							}
						}
						
					// check on Contact Class registration table
					if($payment_transaction[0]['pay_type'] == 11){
						$contact_class_reg = $this->master_model->getRecords('contact_classes_registration' , array('contact_classes_id'=>$payment_transaction[0]['ref_id']),'pay_status');
						if($contact_class_reg[0]['pay_status'] == 1){
							$flag_contact = 1;
						}else{
							$error = 'Pay Status is already zero';	
						}
					}
										
					// check on special elearning member registration table
					if($payment_transaction[0]['pay_type'] == 20){
						$elearning_member_reg = $this->master_model->getRecords('spm_elearning_member_subjects' , array('regid'=>$payment_transaction[0]['ref_id']),'status');
						if($elearning_member_reg[0]['status'] == 1){
							$flag_contact = 1;
						}else{
							$error = 'Pay Status is already zero';	
						}
					}
						
					if($flag_invoice == 1  && $flag_payment == 1){
						
						if($payment_transaction[0]['pay_type'] == 2 || $payment_transaction[0]['pay_type'] == 18){
						// Update member exam table
							$update_member_exam = array('pay_status'=>0);
							$this->master_model->updateRecord('member_exam',$update_member_exam,array('id'=>$payment_transaction[0]['ref_id']));
						}
	
						// Update exam_invoice
						$update_exam_invoice = array('transaction_no' => '');
						$this->master_model->updateRecord('exam_invoice',$update_exam_invoice,array('receipt_no'=>$payment_transaction[0]['receipt_no']));
						
						if($payment_transaction[0]['pay_type'] == 1){ 
							$update_member_reg = array('isactive'=>'0','isdeleted'=>1);
							$this->master_model->updateRecord('member_registration',$update_member_reg,array('regnumber'=>$payment_transaction[0]['member_regnumber'],'regid'=>$payment_transaction[0]['ref_id']));
						}
						
						if($payment_transaction[0]['pay_type'] == 20){ 
							$update_member_reg = array('status'=>'3');
							$this->master_model->updateRecord('spm_elearning_member_subjects',$update_member_reg,array('regnumber'=>$payment_transaction[0]['member_regnumber'],'pt_id'=>$payment_transaction[0]['id']));
							
						}
	
						if($payment_transaction[0]['exam_code'] != 101 || $payment_transaction[0]['exam_code'] != 1010){
							if($payment_transaction[0]['pay_type'] == 2){
							// Update admit card table
								$update_admit = array('remark' => 3); 
								$this->master_model->updateRecord('admit_card_details',$update_admit,array('mem_exam_id'=>$payment_transaction[0]['ref_id']));
							}
						}
						if($payment_transaction[0]['pay_type'] == 10){
							// Update admit card table
								$update_blended = array('pay_status' => 3); 
								$blended_reg_update = $this->master_model->updateRecord('blended_registration',$update_blended,array('blended_id'=>$payment_transaction[0]['ref_id']));
								$blended_reg_getrecord = $this->master_model->getRecords('blended_registration ' , array('blended_id'=>$payment_transaction[0]['ref_id']),'program_code,member_no');
								
								$program_code = $blended_reg_getrecord[0]['program_code'];
								$member_no = $blended_reg_getrecord[0]['member_no'];
								if($program_code!='' && $member_no!=''){
									//$update_blended = array('attempt' => 0);
									$select = 'program_code ,member_number';
									$this->db->where('program_code',$program_code);
									$this->db->where('member_number',$member_no);
									$blended_eligible = $this->master_model->getRecords('blended_eligible_master',array('attempt'=>'1'),$select);
									//echo $this->db->last_query();
									//die;
									//print_r($blended_eligible);
									if(!empty($blended_eligible) && $blended_eligible[0]['attempt']== 1){
										$update_eligible = array('attempt' => 0);
										$this->master_model->updateRecord('blended_eligible_master',$update_eligible,array('member_number'=>$member_no , 'program_code'=>$program_code));
									}
								}
							}
						
						// Update contact class regisytration table	
						if($payment_transaction[0]['pay_type'] == 11){
								$update_contact_class = array('pay_status' => 3); 
								$contact_reg_update = $this->master_model->updateRecord('contact_classes_registration',$update_contact_class,array('contact_classes_id'=>$payment_transaction[0]['ref_id']));
						 }	
						
	
						$update_trans = array('req_status' => 4);
						$this->master_model->updateRecord('maker_checker',$update_trans,array('transaction_no'=>$transaction_no));
						
						$obj = new OS_BR();
						$browser_details=implode('|',$obj->showInfo('all'));
						$user_agent = $_SERVER['HTTP_USER_AGENT'];
						$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Cancellation Request',
									'description'=>' Transaction no :'.$transaction_no,
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address(),
									'browser'=>$browser_details,
									'user_agent'=>$user_agent
								);
						$this->master_model->insertRecord('maker_checker_logs',$logs_data);
						
						$this->session->set_flashdata('success','Successfully done with cancellation');
					}
				}else{
					$error = 'Transaction is not successfull';
				}
			}else{
				$error = 'Invalid transaction number';
			}
			$this->session->set_flashdata('success','Successfully done with cancellation');
			redirect(base_url().'creditnote/admin/Maker/cancellation_list');
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
		<li><a href="'.base_url().'creditnote/admin/maker/report">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'creditnote/admin/maker/report">Maker</a></li>
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
		SELECT `m`.`req_id` as 'Request Id' , `m`.`req_title` as 'Requset Title', `m`.`req_desc` as 'Request Description', `m`.`req_member_no` as 'Member No', `m`.`transaction_no` as 'Transection No', `m`.`req_exceptional_case` as 'Exceptional Case', `m`.`req_reason` as 'Request Reason',m.credit_note_date, `m`.`req_created_on`, `p`.`module_name` as 'Module Name', `cl`.`description` as 'Status Description',ac.action as 'Request status' FROM `maker_checker` `m` LEFT JOIN `pay_type_master` `p` ON `m`.`req_module`=`p`.`pay_type` LEFT JOIN `credit_note_list` `cl` ON `m`.`req_id`=`cl`.`req_id` LEFT JOIN `credit_note_action` `ac` ON `m`.`req_status`=`ac`.`action_id` WHERE (DATE(credit_note_date) BETWEEN '".$from_date."' AND '".$end_date."') GROUP BY `m`.`req_id` ORDER BY `m`.`id` ASC
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
		
		    $this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.req_modified_on,m.credit_note_date,p.module_name,cl.maker_id,cl.checker_id,cl.description');
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
		
		$this->load->view('creditnote/admin/maker/report',$data);
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
			
			

			$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.credit_note_date,m.req_created_on,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
			$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
			$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');
			$this->db->where('(DATE(credit_note_date) BETWEEN "'.$date1.'" AND "'.$date2.'")');
			$this->db->group_by('m.req_id');
			$this->db->order_by('m.id','DESC');
			$reuest_list = $this->master_model->getRecords("maker_checker m", '', '', '', $sortkey, $sortval, $per_page, $start);	

			$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.credit_note_date,m.req_created_on,m.req_modified_on,p.module_name,cl.maker_id,cl.checker_id,cl.description');
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
			
			if(count($reuest_list))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."creditnote/admin/Maker/report/";
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

// check 4 days check for refund request.
public function no_of_days_cron(){
        
		$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_status,m.req_exceptional_case,m.req_created_on,m.req_modified_on,a.name as maker_name');
		$this->db->join('administrators a','a.id=m.req_maker_id','LEFT');
		$this->db->where('m.req_status','0');
		$this->db->where('m.req_created_on <',date('Y-m-d H:i:s'));
		$res_arr = $this->master_model->getRecords("maker_checker m");	
		//print_r($res_arr); die;
		if(count($res_arr) > 0){
			foreach ($res_arr as $arr){
				$date1 = DATE($arr['req_created_on']); 
				$date2 = date('Y-m-d'); 
				  
				  function dateDiff($date1, $date2) 
					{
					  $date1_ts = strtotime($date1);
					  $date2_ts = strtotime($date2);
					  $diff = $date2_ts - $date1_ts;
					  return round($diff / 86400);
					}
					$dateDiff= dateDiff($date1, $date2);

				if($dateDiff >=4){
                     //Mail sending when request initiated

					$this->db->join('exam_invoice','exam_invoice.receipt_no=payment_transaction.receipt_no');
					$this->db->limit('1');
					$transaction_details = $this->master_model->getRecords('payment_transaction', array(
					'payment_transaction.transaction_no' => $arr['transaction_no']  ,
					'status' => '1'
					),'exam_invoice.invoice_no,amount');
                   
					
								 $message = '<html>Dear Checker,<br/><br/>Refund request having following details is initiated by '.$arr['maker_name'].'<br><br>
								     1. Title: '.$arr['req_title'].'<br>
								     2. Transaction No: '.$arr['transaction_no'].'<br>
								     3.	Invoice No: '.$transaction_details[0]['invoice_no'].'<br>
								     4.	Amount: '.$transaction_details[0]['amount'].'<br>
								     5.	Exceptional Case(Yes/No): '.$arr['req_exceptional_case'].'<br><br>
								     Regards,<br>
									 IIBF

								 	</html>';

								// $this->email->send();
                				 $to_mail = 'pawansing.pardeshi@esds.co.in';
                				 $bcc = array('bhushan.amrutkar@esds.co.in');
		   
					            $info_arr = array('to'=>$to_mail,'from'=>'noreply@iibf.org.in','bcc'=>$bcc,'subject'=>'Refund Request '.$arr['req_id'].' Initiated by '.$arr['maker_name'],'message'=>$message);
					            $this->Emailsending->mailsend($info_arr);
                               //end of email sending

				}
				// remove bellow echo when cron is live.
				//echo $arr['req_title'].'<br>'; die;
					
			}
		}
}
	
} ?>