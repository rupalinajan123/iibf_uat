 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Agency extends CI_Controller {	
	public $UserID;			
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/Version_2/admin/Login');
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
		<li><a href="'.base_url().'iibfdra/Version_2/agency/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/agency/">Agency</a></li>
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
		
		$DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
		
		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('agency_center','dra_inst_registration.id=agency_center.agency_id','LEFT');		
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		//$this->db->where('agency_center.center_add_status="F"');
		$this->db->where_in('dra_inst_registration.id',$DRA_Version_2_instId);
		$this->db->where('agency_center.pay_status !=','0');
		$this->db->where('agency_center.institute_code !=""');		
		$this->db->where('dra_accerdited_master.accerdited_delete !=1');
		$this->db->where('dra_accerdited_master.institute_code !=1');	
		$this->db->group_by('agency_center.institute_code'); 
		$this->db->order_by('dra_inst_registration.status','DESC');	
		$this->db->order_by('dra_inst_registration.modified_on','DESC');
		$result = $this->master_model->getRecords("dra_inst_registration");	
		
		//echo $this->db->last_query(); exit;
		
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
		
		$this->load->view('iibfdra/Version_2/admin/agency/agency_list',$data);
	}
	
	public function change_batch_limit()
  {
    // Form validation rules
    $this->form_validation->set_rules('from_date', 'From Date', 'trim|max_length[20]|required|xss_clean');
     $this->form_validation->set_rules('to_date', 'To Date', 'trim|max_length[20]|required|xss_clean|callback_check_date_comparison');
    $this->form_validation->set_rules('batch_creation_limit', 'Batch Creation Limit', 'trim|max_length[3]|required|xss_clean');
    // $this->form_validation->set_rules('batch_type', 'Batch Type', 'required|xss_clean');
    
    // Run validation
    if ($this->form_validation->run() == TRUE) 
    {
        $agency_id = $_POST['agency_id'];
        $arr_updated_data = [];

        $arr_updated_data['from_date']            = $_POST['from_date'];
        $arr_updated_data['to_date']              = $_POST['to_date'];
        $arr_updated_data['batch_creation_limit'] = $_POST['batch_creation_limit']; 
        $arr_updated_data['batch_type']           = $_POST['batch_type'];
       
        $reason = 'Batch creation limit is set to '.$_POST['batch_creation_limit'];

        if ($_POST['batch_type'] != '') {
					$reason .= ' and permitted batch type set to '.$_POST['batch_type'].' hours ';        	
        }
         $reason .= 'for the period '.date('d-M-Y',strtotime($_POST['from_date'])).' to '.date('d-M-Y',strtotime($_POST['to_date']));

        // Update record logic
        if ($this->master_model->updateRecord('dra_inst_registration',$arr_updated_data,array('id'=>$agency_id), true)) 
        {
          $updated_date   = date('Y-m-d H:i:s');
          $drauserdata    = $this->session->userdata('dra_admin');	
          $user_type_flag = $drauserdata['roleid'];

          $this->load->helper('general_agency_helper');	

          $insert_data = array(	
              'agency_id'		=> $agency_id,		
              'reason'		=> $reason,
              'user_id'		=> $drauserdata['id'],	
              'status'		=> 0,
              'modified_on'	=> $updated_date,
              'updated_by'	=> $user_type_flag,
          );

          log_dra_admin($log_title = "DRA Admin set the batch creation limit.", $log_message = serialize($arr_agency));		
          log_dra_agency_action($log_title = "DRA Admin set the batch creation limit.", $agency_id, serialize($insert_data));

          /* Email Sending */
          $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'agency_batch_limit_email'));
                    
          if(count($emailerstr) > 0)
          {  
              /*'to'=>$email,*/
              $final_str = $emailerstr[0]['emailer_text'];
              $emailContent = '';
              
              if ($_POST['batch_creation_limit'] > 0 && $_POST['batch_type'] != '') {  

              	$subject = 'Limitation on Batch Type as well as the Number of Batches Scheduled Concurrently.';

              	$emailContent .= ' With reference to irregularities/non compliances observed in your DRA Training Batch(es), kindly note that you may schedule only '.$_POST['batch_type'].' Hours'."'".' Batch(es) and upto '.$_POST['batch_creation_limit'].' number of batches concurrently for the Period '.date('d-M-Y',strtotime($_POST['from_date'])).' to '. date('d-M-Y',strtotime($_POST['to_date'])).'.';
              } else if ($_POST['batch_type'] != '') {
              	
              	$subject = 'Limitation on Batch Type.';

              	$emailContent .= ' With reference to irregularities/non compliances observed in your DRA Training Batch(es), kindly note that you may schedule only '.$_POST['batch_type'].' Hours'."'".' Batch(es) for the Period '.date('d-M-Y',strtotime($_POST['from_date'])).' to '. date('d-M-Y',strtotime($_POST['to_date'])).'.';
              } else if ($_POST['batch_creation_limit'] > 0) {

              	$subject = 'Limit on the Number of Batches Scheduled Concurrently.';

              	$emailContent .= ' With reference to irregularities/non compliances observed in your DRA Training Batch(es), kindly note that you may schedule only upto '.$_POST['batch_creation_limit'].' number of batches concurrently for the Period '.date('d-M-Y',strtotime($_POST['from_date'])).' to '. date('d-M-Y',strtotime($_POST['to_date'])).'.';
              }

              $final_str = str_replace('#MASSEGE#', $emailContent , $final_str);
              
              $this->db->join('dra_accerdited_master dm','di.id=dm.dra_inst_registration_id');
          		$arr_agency_data = $this->master_model->getRecords('dra_inst_registration di',array('di.id'=>$agency_id));


              $info_arr = array('to'=>$arr_agency_data[0]['email'],
              'from'=>$emailerstr[0]['from'],
              'cc'=>'dracell@iibf.org.in',
              'bcc'=>'iibfteam@esds.co.in',
              'subject'=>$subject,
              'message'=>$final_str);
          }
			          
          $this->Emailsending->mailsend($info_arr);

          $this->session->set_flashdata('success', 'Batch Creation Limit Set Successfully.');	
          redirect(base_url() .'iibfdra/Version_2/Agency');
        }
        else
        {
          $this->session->set_flashdata('error', 'Error occurred when updating the batch creation limit.');
          redirect(base_url() .'iibfdra/Version_2/Agency');
        }
    }	
    else
    {
      $this->session->set_flashdata('error',validation_errors()); 
      redirect(base_url() .'iibfdra/Version_2/Agency');
    } 
	}

// Custom callback function for date comparison
public function check_date_comparison()
{
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');

    if (strtotime($from_date) > strtotime($to_date)) {
        $this->form_validation->set_message('check_date_comparison', 'The To Date must be greater than the From Date.');
        return FALSE;
    } else {
        return TRUE;
    }
}


	// function to view All batches receipt -
	public function agency_detail($agency_id)
	{
		$agency_id = $agency_id; 
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Version_2/agency/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/Version_2/agency/">Agency</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/agency/agency_detail/'.$agency_id.'">Agency Detail</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/agency/agency_detail';		
		
		$res_accredated_agency = $this->master_model->getRecords("dra_accerdited_master",array('dra_inst_registration_id'=>$agency_id));

		$this->db->join('exam_invoice ei', 'ei.pay_txn_id = pt.id AND ei.institute_code = '.$res_accredated_agency[0]['institute_code']);
    $this->db->order_by("pt.id", "DESC");
    
    $arr_transaction = $this->master_model->getRecords('payment_transaction pt',array('pt.pay_type'=>17),'pt.proformo_invoice_no,pt.status,pt.transaction_no,pt.qty,pt.receipt_no,pt.transaction_details,pt.date,pt.amount,pt.tds_amount,ei.center_name,ei.invoice_no,ei.invoice_image,ei.invoice_id');

    $data['arr_transaction']	= $arr_transaction;

		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->where('dra_inst_registration.id',$agency_id);		
		$res_agency = $this->master_model->getRecords("dra_inst_registration");

		$prev_agency_data = $res_agency[0];

		// Accept and Reject Ajency 
		if(isset($_REQUEST['status'])) {
			if($_REQUEST['status'] == 1) {
				$this->agency_deactivate($agency_id);				
			}else{
				$this->agency_activate($agency_id);
			}			
		}
		
		if (isset($_POST['agency_submit'])) 
    {
    	$this->form_validation->set_rules('inst_name', 'Agency Name', 'trim|max_length[75]|required|xss_clean');
      $this->form_validation->set_rules('estb_year', 'Year Of Establishment', 'trim|max_length[4]|required|xss_clean');
      $this->form_validation->set_rules('inst_stdcode', 'STD Code', 'trim|max_length[6]|required|xss_clean');
      $this->form_validation->set_rules('inst_phone', 'Telephone Number', 'trim|max_length[12]|required|xss_clean');
      $this->form_validation->set_rules('inst_fax_no', 'Fax Number', 'trim|max_length[12]|required|xss_clean');
      $this->form_validation->set_rules('inst_website', 'Agency Website', 'trim|max_length[100]|required|xss_clean');
      $this->form_validation->set_rules('main_office_address', 'Agency Main Office Address', 'trim|max_length[100]|required|xss_clean');

      if (isset($_POST['main_address1']) && $_POST['main_address1'] != '') {
          $this->form_validation->set_rules('main_address1', 'Address 1', 'trim|max_length[100]|required|xss_clean');
      }

      if (isset($_POST['main_address2']) && $_POST['main_address2'] != '') {
          $this->form_validation->set_rules('main_address2', 'Address 2', 'trim|max_length[100]|required|xss_clean');
      }

      if (isset($_POST['main_address3']) && $_POST['main_address3'] != '') {
          $this->form_validation->set_rules('main_address3', 'Address 3', 'trim|max_length[100]|required|xss_clean');
      }

      if (isset($_POST['main_address4']) && $_POST['main_address4'] != '') {
          $this->form_validation->set_rules('main_address4', 'Address 4', 'trim|max_length[100]|required|xss_clean');
      }

      $this->form_validation->set_rules('main_district','Agency Main District','trim|max_length[75]|required|xss_clean');
      $this->form_validation->set_rules('main_city', 'Agency Main City', 'trim|required|xss_clean');
      $this->form_validation->set_rules('state_name', 'Agency Main State', 'trim|required|xss_clean');
      $this->form_validation->set_rules('main_pincode', 'Agency Main Pincode', 'trim|max_length[6]|required|xss_clean');
      $this->form_validation->set_rules('inst_head_name', 'Head Of Agency', 'trim|max_length[75]|required|xss_clean');
      $this->form_validation->set_rules('inst_head_contact_no', 'Director Contact Number', 'trim|max_length[10]|required|xss_clean');
      $this->form_validation->set_rules('inst_head_email', 'Director Email', 'trim|max_length[75]|required|xss_clean');
      $this->form_validation->set_rules('inst_altr_person_name', 'Name of Alternate Contact Person of the agency', 'trim|max_length[75]|required|xss_clean');
      $this->form_validation->set_rules('inst_alter_contact_no', 'Mobile No. of the Alternate Contact Person of the agency', 'trim|max_length[10]|required|xss_clean');
      $this->form_validation->set_rules('inst_altr_email', 'Email ID of the Alternate Contact Person of the agency', 'trim|max_length[75]|required|xss_clean');
    
      if ($this->form_validation->run() == TRUE) 
      {
      	$arr_agency = $_POST;
      	$agency_id  = $arr_agency['id'];

      	if (array_key_exists('state_name', $arr_agency)) {
				    $arr_agency['main_state'] = $arr_agency['state_name'];
				    unset($arr_agency['state_name']);
				}

				// Remove 'agency_submit' key
				if (array_key_exists('agency_submit', $arr_agency)) {
				    unset($arr_agency['agency_submit']);
				}

				// Remove 'agency_submit' key
				if (array_key_exists('reject_reason', $arr_agency)) {
				    unset($arr_agency['reject_reason']);
				}

				// Remove 'agency_submit' key
				if (array_key_exists('id', $arr_agency)) {
				    unset($arr_agency['id']);
				}

				if ($this->master_model->updateRecord('dra_inst_registration',$arr_agency,array('id'=>$agency_id), true)) 
        {

        	$changed_fields = $this->getUpdatedAgencyData($_POST,$prev_agency_data);
          $changed_fields_message = implode(', ', $changed_fields);
          
          if (trim($changed_fields_message) != '') {
            $log_message = 'The following fields were agency updated by the Admin: ' . $changed_fields_message; 
          } else {
             $log_message = 'No changes were made by the admin during the agency update.';
          }

        	$updated_date   = date('Y-m-d H:i:s');
					$drauserdata    = $this->session->userdata('dra_admin');	
					$user_type_flag = $drauserdata['roleid'];

						$this->load->helper('general_agency_helper');	
					
						$insert_data = array(	
						'agency_id'		=> $agency_id,		
						'reason'		  => $log_message,
						'user_id'		  => $drauserdata['id'],	
						'status'		  => 0,
						'modified_on'	=> $updated_date,
						'updated_by'	=> $user_type_flag,
						);
										
						log_dra_admin($log_title = "DRA Admin Updated the Agency Details", $log_message = serialize($arr_agency));		
						log_dra_agency_action($log_title = "Agency Updated",$agency_id,serialize($insert_data));

						$this->session->set_flashdata('success', 'Agency details Updated Successfully..!');	
						redirect(base_url() .'iibfdra/Version_2/Agency/agency_detail/'.$agency_id);
        }
        else
        {
        	$this->session->set_flashdata('error', 'Error occured, When updating the agency details.');
        	redirect(base_url() .'iibfdra/Version_2/Agency/agency_detail/'.$agency_id);
        }      	
      }	
    }	

		$this->db->where('city_master.city_delete','0');
		$this->db->where('city_master.state_code',$res_agency[0]['main_state']);
		$res_city = $this->master_model->getRecords("city_master");

		$this->db->where('state_master.state_delete','0');
		$res_state = $this->master_model->getRecords("state_master");

		
		$this->db->select('agency_center.*,state_master.state_name,city_master.city_name');
		$this->db->where('agency_id',$agency_id);
		$this->db->where('agency_center.center_display_status','1');
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->join('state_master','agency_center.state=state_master.state_code','LEFT');
		$this->db->order_by("agency_center.created_on", "DESC");
		$res_arr = $this->master_model->getRecords("agency_center");
		$res_value = array();
		
	    //  print_r($res_arr); die;
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
			redirect(base_url() . 'iibfdra/Version_2/Agency');
		}
		//print_r($res_value);die;
		$data['center_result'] = $res_value;
		$data['result'] = $res_agency[0];
		$data['res_state']  = $res_state;
		$data['res_city']   = $res_city;
		$data['agency_log'] = $res_arr_log;			 
		$this->load->view('iibfdra/Version_2/admin/agency/agency_detail',$data);	
	}
	
	private function getUpdatedAgencyData($request_data,$original_data)
  {
    $updated_fields = array(); // Array to store only updated fields
    $changed_fields = array();
    // echo "<pre>"; print_r($request_data);
    // echo "<pre>"; print_r($original_data); exit;

    // Only proceed if form is submitted
    if (isset($request_data) && count($request_data) > 0) 
    {
      // Compare each form input with original data, if changed, add to the array
      if ($original_data['inst_name'] != $request_data['inst_name']) {
          $updated_fields['Salutation'] = $request_data['inst_name'];
          $changed_fields[] = 'Salutation';
      }

      if ($original_data['estb_year'] != $request_data['estb_year']) {
          $updated_fields['Year Of Establishment'] = $request_data['estb_year'];
          $changed_fields[] = 'Year Of Establishment';
      }

      if ($original_data['inst_stdcode'] != $request_data['inst_stdcode']) {
          $updated_fields['STD Code'] = $request_data['inst_stdcode'];
          $changed_fields[] = 'STD Code';
      }

      if ($original_data['inst_phone'] != $request_data['inst_phone']) {
          $updated_fields['Telephone Number'] = $request_data['inst_phone'];
          $changed_fields[] = 'Telephone Number';
      }

      if ($original_data['inst_fax_no'] != $request_data['inst_fax_no']) {
          $updated_fields['Fax Number'] = $request_data['inst_fax_no'];
          $changed_fields[] = 'Fax Number';
      }

      if ($original_data['inst_website'] != $request_data['inst_website']) {
          $updated_fields['Agency Website'] = $request_data['inst_website'];
          $changed_fields[] = 'Agency Website';        
      }

      if ($original_data['main_office_address'] != $request_data['main_office_address']) {
          $updated_fields['Agency Main Office Address'] = $request_data['main_office_address'];
          $changed_fields[] = 'Agency Main Office Address';
      }

      if ($original_data['main_address1'] != $request_data['main_address1']) {
          $updated_fields['Agency Main Address 1'] = $request_data['main_address1'];
          $changed_fields[] = 'Agency Main Address 1';
      }

      if ($original_data['main_address2'] != $request_data['main_address2']) {
          $updated_fields['Agency Main Address 2'] = $request_data['main_address2'];
          $changed_fields[] = 'Agency Main Address 2';
      }

      if ($original_data['main_address3'] != $request_data['main_address3']) {
          $updated_fields['Agency Main Address 3'] = $request_data['main_address3'];
          $changed_fields[] = 'Agency Main Address 3';
      }

      if ($original_data['main_address4'] != $request_data['main_address4']) {
          $updated_fields['Agency Main Address 4'] = $request_data['main_address4'];
          $changed_fields[] = 'Agency Main Address 4';
      }

      if ($original_data['main_district'] != $request_data['main_district']) {
          $updated_fields['Agency Main District'] = $request_data['main_district'];
          $changed_fields[] = 'Agency Main District';
      }

      if ($original_data['main_state'] != $request_data['state_name']) {
          $updated_fields['Agency State Name'] = $request_data['state_name'];
          $changed_fields[] = 'Agency State Name';
      }

      if ($original_data['main_city'] != $request_data['main_city']) {
          $updated_fields['Agency Main City'] = $request_data['main_city'];
          $changed_fields[] = 'Agency Main City';
      }

      if (isset($request_data['main_pincode']) && $original_data['main_pincode'] != $request_data['main_pincode']) {
          $updated_fields['Agency Main Pincode'] = $request_data['main_pincode'];
          $changed_fields[] = 'Agency Main Pincode';
      }

      if (isset($request_data['inst_head_name']) && $original_data['inst_head_name'] != $request_data['inst_head_name']) {
          $updated_fields['Name Of Director/ Head Of Agency'] = $request_data['inst_head_name'];
          $changed_fields[] = 'Name Of Director/ Head Of Agency';
      }

      if (isset($request_data['inst_head_contact_no']) && $original_data['inst_head_contact_no'] != $request_data['inst_head_contact_no']) {
          $updated_fields['Director Contact Number'] = $request_data['inst_head_contact_no'];
          $changed_fields[] = 'Director Contact Number';
      }

      if (isset($request_data['inst_head_email']) && $original_data['inst_head_email'] != $request_data['inst_head_email']) {
          $updated_fields['Director Email Id'] = $request_data['inst_head_email'];
          $changed_fields[] = 'Director Email Id';
      }
    }
    return $changed_fields;
  }

	/*GET VALUES OF CITY */
  public function getCity() 
  {
    if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
    {
        $state_code = $this->security->xss_clean($this->input->post('state_code'));
        $result   = $this->master_model->getRecords('city_master', array('state_code' => $state_code,'city_delete' => 0));
        if ($result) 
        {
            echo '<option value=""> Select City </option>';
            foreach ($result AS $data) 
            {
                if ($data) 
                {
                    echo '<option value="' . $data['id'] . '">' . $data['city_name'] . '</option>';
                }
            }
        } 
        else 
        {
            echo '<option value="">City Not Available, Please select other state</option>';
        }
		
	}
  }
	
	public function agency_renew($agency_id)
	{
    $this->load->helper('general_agency_helper');
		$agency_id = $agency_id;
		
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Version_2/agency/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/Version_2/agency/">Agency</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/agency/agency_detail/'.$agency_id.'">Agency Renew</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/agency/agency_renew';		
		
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
				
				redirect(base_url() . 'iibfdra/Version_2/Agency');		
			}			
		}
		
		$this->db->select('dra_inst_registration.*,dra_accerdited_master.institute_code,state_master.state_name,city_master.city_name');
		$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT');	
		$this->db->join('city_master','dra_inst_registration.main_city=city_master.id','LEFT');			
		$this->db->join('dra_accerdited_master','dra_inst_registration.id=dra_accerdited_master.dra_inst_registration_id','INNER');
		$this->db->where('dra_inst_registration.id',$agency_id);
		$res_agency = $this->master_model->getRecords("dra_inst_registration");		
		
    if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_action']) && $_POST['form_action'] == 'update_center_validity_Form')
    {
      $center_ids_str = $this->security->xss_clean($this->input->post('selected_center_ids_hidden'));
      $center_validity_from_date = $this->security->xss_clean($this->input->post('center_validity_from_date'));
      $center_validity_to_date = $this->security->xss_clean($this->input->post('center_validity_to_date'));

      $center_ids_arr = explode(",",$center_ids_str);
      if(count($center_ids_arr) > 0)
      {
        $drauserdata = $this->session->userdata('dra_admin');
        $user_type_flag 	= $drauserdata['roleid'];	
        foreach($center_ids_arr as $center_ids_res) 
        { 
          $up_data = array();
          $up_data['user_id'] = $drauserdata['id'];
          $up_data['modified_on'] = date("Y-m-d H:i:s");
          $up_data['center_validity_from'] = $center_validity_from_date;
          $up_data['center_validity_to'] = $center_validity_to_date;
          $up_data['is_renew'] = '0';
          $up_data['updated_by'] = $user_type_flag;
          $up_data['check_city_state_for_active'] = $res_agency[0]['state_name'];
          $this->master_model->updateRecord('agency_center',$up_data,array('center_id' => $center_ids_res));
          
          $log_data = array();
          $log_data['user_id'] = $drauserdata['id'];
          $log_data['modified_on'] = date("Y-m-d H:i:s");
          $log_data['center_validity_from'] = $center_validity_from_date;
          $log_data['center_validity_to'] = $center_validity_to_date;
          $log_data['is_renew'] = '0';
          $log_data['updated_by'] = $user_type_flag;
          $log_data['renewal_type'] = 'free';
          log_dra_agency_center_detail($log_title = "Center Accreditation Period Updated",$center_ids_res,serialize($log_data));  
          //echo $this->db->last_query();	
        }
        
        $this->session->set_flashdata('success', 'Center validity period updated successfully');
        redirect(site_url('iibfdra/Version_2/agency/agency_renew/'.$agency_id));
      }
      else
      {
        $this->session->set_flashdata('error', 'Error occurred. Please try again.');
        redirect(site_url('iibfdra/Version_2/agency/agency_renew/'.$agency_id));
      }      
    }
		
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
			redirect(base_url() . 'iibfdra/Version_2/Agency');
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
		$this->load->view('iibfdra/Version_2/admin/agency/agency_renew',$data);	
		
	}
	
	// Activate Agency 
	public function agency_activate($agency_id)
	{		
		$this->load->helper('general_agency_helper');	
		
		$updated_date = date('Y-m-d H:i:s');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$reason = $_REQUEST['reason'];
		
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
		$reason = $_REQUEST['reason'];
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
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/agency/agency_detail">
		<i class="fa fa-home"></i> Home</a></li>
			  <li class=""><a href="'.base_url().'iibfdra/Version_2/agency/">Agency</a></li>
			  <li class="active">Agency Center Details</li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/agency/agency_detail';		
		
		// Accept and Reject Center 
		if(isset($_REQUEST['action'])) {
			
			if($_REQUEST['action'] == 'update_status'){
			
				if(isset($_REQUEST['action_status'])) {
					if($_REQUEST['action_status'] != 'A' &&  $_REQUEST['action_status'] != ''){						
						$this->agency_center_reject($center_id);				
					}else if($_REQUEST['action_status'] == 'A'){
						//$this->agency_batch_approve($batch_id);
						$this->agency_center_approve($center_id,$_REQUEST);
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
			redirect(base_url() . 'iibfdra/Version_2/Agency');
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
			$this->db->where('payment_transaction.pay_type = 12');	
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
			// echo "<pre>"; print_r($res_center); exit;
			
		}		
		
		$data['result'] = $res_center[0];
		
		$this->db->join('dra_admin','dra_agency_center_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_center_adminlogs.center_id',$center_id);
		$this->db->order_by('dra_agency_center_adminlogs.date','DESC');		
		$res_logs = $this->master_model->getRecords("dra_agency_center_adminlogs");	
		//$res_center_logs = $res_logs->result_array();		
		$data['agency_center_logs'] = $res_logs;
		
		
		$this->load->view('iibfdra/Version_2/admin/agency/agency_center_detail',$data);		
	}

	// Approve Agency Center 
	public function agency_center_approve($center_id,$request)
	{		
		$this->load->helper('general_agency_helper');
		$payment_required = $request['payment_required'];

		if ($payment_required == 'without_payment') {
			$required_amount  = '';
		} else {
			$required_amount  = $request['required_amount'];
		}

		

		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$updated_date = date('Y-m-d H:i:s');
		//pay_status 2 : make payment 
		if($user_type_flag == '1')
		{
			$center_status = 'A';
			$pay_status = '2';
		} else if($user_type_flag == '2') {
			$center_status = 'AR'; // Approved by recommender
			$pay_status = '2';
		}

		if ($payment_required == 'with_payment') {
			$center_status = 'A';
			$pay_status = '2';
		} else {
			$center_status = 'A';
			$pay_status = '1';
		}

		$update_data = array(
						'center_status'		=> $center_status,	
						'user_id'			=> $drauserdata['id'],								
						'modified_on'		=> $updated_date,
						'date_of_approved'  => $updated_date,
						'pay_status'		=> $pay_status,
						'payment_required'	=> $payment_required,
						'required_amount'	=> $required_amount,
						'updated_by' 		=> $user_type_flag 
						);
			
		log_dra_agency_admin($log_title = "DRA Admin Approved Agency Center", $log_message = serialize($update_data));
		log_dra_agency_center_detail($log_title = "Center Approved",$center_id,serialize($update_data));				
		log_dra_admin($log_title = "DRA Admin Approved Agency Center", $log_message = serialize($update_data));	

		$this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_id));
		//if($user_type_flag == '1')
		//{
		agency_center_approve_mail($center_id,$user_type_flag);
		//}

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
			agency_center_reject_mail($center_id,$user_type_flag);
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
			//$today = "2017-06-17"; // Or can put $today = date ("Y-m-d");
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
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/agency/agency_detail">
		<i class="fa fa-home"></i> Home</a></li>
			  <li class=""><a href="'.base_url().'iibfdra/Version_2/agency/">Agency</a></li>
			  <li class="active"><a href="'.base_url().'iibfdra/Version_2/agency/training_center_detail/'.$center_id.'">Agency Center Details</a></li>
			  <li class="active">Agency Center Receipt Details</li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/agency/agency_detail';		
		
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
		
		if(count($res_center) > 0){
		
		}else{
			redirect(base_url() . 'iibfdra/Version_2/Agency');
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
			$this->db->where(' (payment_transaction.pay_type = 16 OR payment_transaction.pay_type = 12) ');	
			$this->db->order_by("exam_invoice.created_on", "DESC");
    		$this->db->limit(1);
			$res_invoice = $this->master_model->getRecords('payment_transaction');
			
			if(count($res_invoice) > 0 ){
				$res_center[0]['invoice_image']   = $res_invoice[0]['invoice_image'];	
				$res_center[0]['transaction_id']  = $res_invoice[0]['id'];
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
		
		$this->load->view('iibfdra/Version_2/admin/agency/receipt',$data);	
	}

	
} ?>