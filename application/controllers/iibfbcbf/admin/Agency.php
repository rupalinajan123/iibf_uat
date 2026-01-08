<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Master
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_admin_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'admin')
      {
        $this->session->set_flashdata('error','You do not have permission to access Agency module');
        redirect(site_url('iibfbcbf/admin/dashboard_admin'));
      }
		}
    
    public function index()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Agency Master";
      $data['page_title'] = 'IIBF - BCBF Agency Master';
      $this->load->view('iibfbcbf/admin/agency_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE AGENCY DATA ********/
    public function get_agency_data_ajax()
    {
      $table = 'iibfbcbf_agency_master am';
      
      $column_order = array('am.agency_id', 'CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types IS NULL,"",IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types)),")") AS agency_name', 'am.estb_year', 'am.agency_address1', 'sm.state_name', 'cm.city_name', 'DATE(am.created_on) AS CreatedDate', 'am.contact_person_name', 'am.contact_person_designation', 'am.contact_person_mobile', 'am.contact_person_email', 'am.agency_code', 'am.agency_password', 'IF(am.is_active=1, "Active", IF(am.is_active=0, "Inactive", "In Review")) AS AgencyStatus', 'am.is_active'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types IS NULL,"",IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types)),")")', 'am.estb_year', 'am.agency_address1', 'sm.state_name', 'cm.city_name', 'DATE(am.created_on)', 'am.contact_person_name', 'am.contact_person_designation', 'am.contact_person_mobile', 'am.contact_person_email', 'am.agency_code', 'IF(am.is_active=1, "Active", IF(am.is_active=0, "Inactive", "In Review"))'); //SET COLUMN FOR SEARCH
      $order = array('am.agency_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE am.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE am.is_deleted = 0 	";
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."' AND DATE(am.created_on) <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (DATE(am.created_on) <= '".$s_to_date."')"; } 

      $s_agency_code = trim($this->security->xss_clean($this->input->post('s_agency_code')));
      if($s_agency_code != "") { $Where .= " AND am.agency_code = '".$s_agency_code."'"; }

      $s_agency_type = trim($this->security->xss_clean($this->input->post('s_agency_type')));
      if($s_agency_type != "") { $Where .= " AND am.allow_exam_types = '".$s_agency_type."'"; }

      $s_agency_status = trim($this->security->xss_clean($this->input->post('s_agency_status')));
      if($s_agency_status != "") { $Where .= " AND am.is_active = '".$s_agency_status."'"; }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN state_master sm ON am.agency_state = sm.state_code LEFT JOIN city_master cm ON am.agency_city = cm.id";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['agency_name'];
        $row[] = $Res['estb_year'];
        $row[] = $Res['agency_address1'];
        $row[] = $Res['state_name'];
        $row[] = $Res['city_name'];
        $row[] = $Res['CreatedDate'];
        $row[] = $Res['contact_person_name'];
        $row[] = $Res['contact_person_designation'];
        $row[] = $Res['contact_person_mobile'];
        $row[] = $Res['contact_person_email'];
        $row[] = $Res['agency_code'];
        $row[] = $this->Iibf_bcbf_model->password_decryption($Res['agency_password']);
        
        $row[] = '<span class="badge '.show_faculty_status($Res['is_active']).'" style="min-width:90px;">'.$Res['AgencyStatus'].'</span>';
        
        $btn_str = ' <div class="text-center no_wrap"> ';

        $function_change_pass = "get_modal_change_password_data('".url_encode($Res['agency_id'])."')";
        $btn_str .= '<a href="javascript:void(0)" onclick="'.$function_change_pass.'" class="btn btn-warning btn-xs" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></a> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/agency/agency_details/'.url_encode($Res['agency_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        $btn_str .= '<a href="'.site_url('iibfbcbf/admin/agency/add_agency/'.url_encode($Res['agency_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        $data[] = $row; 
      }			
      
      $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $TotalResult, //All result count
      "recordsFiltered" => $FilteredResult, //Disp result count
      "Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }
    /******** END : SERVER SIDE DATATABLE CALL FOR GET THE AGENCY DATA ********/

    /******** START : ADD / UPDATE AGENCY DATA ********/
    public function add_agency($enc_agency_id=0)
    {
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Agency Master";      
      
      //START : FIND OUT THE CURRENT MODE AND GET THE AGENCY DATA
      if($enc_agency_id == '0') 
      { 
        $data['mode'] = $mode = "Add"; $agency_id = $enc_agency_id;
      }
      else
      {
        $agency_id = url_decode($enc_agency_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.agency_id' => $agency_id, 'am.is_deleted' => '0'), "am.*");        
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/agency/add_agency')); }
        
        $data['mode'] = $mode = "Update";
      }//END : FIND OUT THE CURRENT MODE AND GET THE AGENCY DATA

      $data['page_title'] = 'IIBF - '.$mode.' BCBF Agency';
      $data['enc_agency_id'] = $enc_agency_id;

      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('agency_name', 'agency name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));     
        $this->form_validation->set_rules('estb_year', 'establishment year', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  
        $this->form_validation->set_rules('agency_address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('agency_address2', 'address line-2', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('agency_address3', 'address line-3', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('agency_address4', 'address line-4', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('agency_state', 'state', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('agency_city', 'city', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('agency_district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[30]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('agency_pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode['.$this->input->post('agency_state').']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_name', 'contact person name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_designation', 'contact person designation', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_mobile', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist['.$enc_agency_id.']|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_email', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist['.$enc_agency_id.']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('gst_no', 'GST no.', 'trim|required|min_length[15]|max_length[15]|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|callback_fun_validate_gst_no|callback_validation_check_gst_no_exist['.$enc_agency_id.']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('allow_exam_types', 'agency type', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        if($mode == 'Add') {
          $this->form_validation->set_rules('agency_password', 'password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				  $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));        
        }

        if($mode == 'Update')
        {
          $this->form_validation->set_rules('is_active', 'status', 'trim|required|xss_clean', array('required'=>"Please select the %s")); 
        }

        //$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
          $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');

          $add_data['agency_name'] = $this->input->post('agency_name');
          $add_data['estb_year'] = $this->input->post('estb_year');
          $add_data['agency_address1'] = $this->input->post('agency_address1');
          $add_data['agency_address2'] = $this->input->post('agency_address2');
          $add_data['agency_address3'] = $this->input->post('agency_address3');
          $add_data['agency_address4'] = $this->input->post('agency_address4');
          $add_data['agency_state'] = $this->input->post('agency_state');
          $add_data['agency_city'] = $this->input->post('agency_city');
          $add_data['agency_district'] = $this->input->post('agency_district');
          $add_data['agency_pincode'] = $this->input->post('agency_pincode');
          $add_data['contact_person_name'] = $this->input->post('contact_person_name');
          $add_data['contact_person_designation'] = $this->input->post('contact_person_designation');
          $add_data['contact_person_mobile'] = $this->input->post('contact_person_mobile');
          $add_data['contact_person_email'] = strtolower($this->input->post('contact_person_email'));
          $add_data['gst_no'] = $this->input->post('gst_no');
          $add_data['ip_address'] = get_ip_address(); //general_helper.php   
          
          $add_data['allow_exam_types'] = $allow_exam_types = $this->input->post('allow_exam_types');
          if($allow_exam_types == 'CSC') { $add_data['allow_exam_codes'] = '1039,1040'; }
          else if($allow_exam_types == 'Bulk/Individual') { $add_data['allow_exam_codes'] = '1037,1038'; }
          else { $add_data['allow_exam_codes'] = ''; }

          if($mode == "Add") 
          {
            $add_data['is_active'] = '1';
            $add_data['agency_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('agency_password'));
            $add_data['created_on'] = date("Y-m-d H:i:s");
            $add_data['created_by'] = $this->login_admin_id;
            
            $add_data['allow_exam_codes'] = '1037,1038';
            $this->master_model->insertRecord('iibfbcbf_agency_master  ',$add_data);
            $agency_id = $this->db->insert_id();

            $total_record_qry = $this->db->query('SELECT am.agency_id FROM iibfbcbf_agency_master am WHERE am.agency_id <= "'.$agency_id.'"');
            $get_total_record = $total_record_qry->num_rows();
            
            $up_data['agency_code'] = 1000 + $get_total_record;            
            $this->master_model->updateRecord('iibfbcbf_agency_master', $up_data, array('agency_id'=>$agency_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Admin : Agency Added', 'iibfbcbf_agency_master', $this->db->last_query(), $agency_id,'agency_action','The agency has successfully added by the admin '.$dispName['disp_name'], $posted_arr); 
            
            $this->session->set_flashdata('success','Agency record added successfully');
          }
          else if($mode == "Update")
          {
            $add_data['is_active'] = $this->input->post('is_active');
            $add_data['updated_on'] = date("Y-m-d H:i:s");
            $add_data['updated_by'] = $this->login_admin_id;            
            $this->master_model->updateRecord('iibfbcbf_agency_master', $add_data, array('agency_id'=>$agency_id));
                          
            $this->Iibf_bcbf_model->insert_common_log('Admin : Agency Updated', 'iibfbcbf_agency_master', $this->db->last_query(), $agency_id,'agency_action','The agency has successfully updated by the admin '.$dispName['disp_name'], $posted_arr);
            
            $this->session->set_flashdata('success','Agency record updated successfully');              
          }
          
          redirect(site_url('iibfbcbf/admin/agency'));
        }
      }	
      
      $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));
      $this->load->view('iibfbcbf/admin/add_agency_admin', $data);
    }/******** END : ADD / UPDATE AGENCY DATA ********/ 

    function get_modal_change_password_data()
		{
			$data['enc_agency_id'] = $enc_agency_id = $this->input->post('enc_agency_id');

      if($enc_agency_id == "0")
			{
				echo "error";
			}
			else
			{
				$agency_id = url_decode($enc_agency_id);

        $result_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.agency_id'=>$agency_id, 'am.is_deleted'=>'0'));
        
        if(count($result_data) == 0)
				{
					echo "error";
				}
				else
				{
					$data['form_data'] = $result_data;
          $this->load->view('iibfbcbf/admin/modal_change_password_agency', $data);
				}
			}
		}

    /******** START : CHANGE AGENCY PASSWORD ********/
    public function change_password($enc_agency_id=0)
    {   
      $data['enc_agency_id'] = $enc_agency_id;
            
      if($enc_agency_id == '0') 
      { 
        $this->session->set_flashdata('error','Error occurred. Try again later.');
        redirect(site_url('iibfbcbf/admin/agency')); 
      }
      else
      {
        $agency_id = url_decode($enc_agency_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $agency_id, 'is_deleted' => '0'), "*");
        if(count($form_data) == 0) 
        { 
          $this->session->set_flashdata('error','Error occurred. Try again later.');
          redirect(site_url('iibfbcbf/admin/agency'));
        }
        else
        {
          if(isset($_POST) && count($_POST) > 0)
          { 
            $this->form_validation->set_rules('agency_password', 'password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));             
           
            if($this->form_validation->run())
            { 
              //_pa($_POST,1);
              $posted_arr = json_encode($_POST);
              $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');
              
              //echo 'IN';exit;
              $add_data['agency_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('agency_password'));
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_admin_id;            
              $this->master_model->updateRecord('iibfbcbf_agency_master', $add_data, array('agency_id'=>$agency_id));
                
              $this->Iibf_bcbf_model->insert_common_log('Admin : Agency Password Updated', 'iibfbcbf_agency_master', $this->db->last_query(), $agency_id,'agency_password_action','The agency password has successfully updated by the admin '.$dispName['disp_name'].'.', $posted_arr);
                  
              $this->session->set_flashdata('success','Agency password updated successfully');              
              redirect(site_url('iibfbcbf/admin/agency'));
            }
            else
            {
              $this->session->set_flashdata('error','Validation error occurred. Please try again.');
              redirect(site_url('iibfbcbf/admin/agency'));
            }
          }	
          else
          {
            $this->session->set_flashdata('error','Invalid request.');
            redirect(site_url('iibfbcbf/admin/agency'));
          }
        }      
      }
    }/******** END : CHANGE AGENCY PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/
    public function check_old_password()
    { 
			if(isset($_POST) && $_POST['agency_password'] != "")
			{
        $agency_password = $this->security->xss_clean($this->input->post('agency_password')); 
        $enc_agency_id = $this->security->xss_clean($this->input->post('enc_agency_id')); 
        
        if($enc_agency_id != "" && $enc_agency_id != '0') { $agency_id = url_decode($enc_agency_id); }
        else { $agency_id = $enc_agency_id; }

        $result_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0', 'am.agency_password' => $this->Iibf_bcbf_model->password_encryption($agency_password), 'am.agency_id' => $agency_id), 'am.agency_id');
        
        if(count($result_data) > 0) { echo 'false'; }
        else { echo 'true'; }
			}
      else
      {
        echo 'true';
      }
    }/******** END : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/

    function get_city_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $onchange_fun = "validate_file('agency_city')";
				$html = '	<select class="form-control chosen-select ignore_required" name="agency_city" id="agency_city" required onchange="'.$onchange_fun.'">';
				$state_id = $this->security->xss_clean($this->input->post('state_id'));
        
        $city_data = $this->master_model->getRecords('city_master', array('state_code' => $state_id, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
				if(count($city_data) > 0)
				{
					$html .= '	<option value="">Select City</option>';
					foreach($city_data as $city)
					{
						$html .= '	<option value="'.$city['id'].'">'.$city['city_name'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select City</option>';
        }
				$html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }

    /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    public function validation_check_valid_pincode($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['agency_pincode'] != "")
			{
        if($type == '1') 
        { 
          $agency_pincode = $this->security->xss_clean($this->input->post('agency_pincode')); 
          $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
        }
        else 
        { 
          $agency_pincode = $str; 
          $selected_state_code = $type;
        }

        $this->db->where(" '".$agency_pincode."' BETWEEN start_pin AND end_pin ");
        $result_data = $this->master_model->getRecords('state_master', array('state_code' => $selected_state_code), 'id, state_code, start_pin, end_pin');
              
        if(count($result_data) > 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['agency_pincode'] != "")
        {
          $pin_length = strlen($_POST['agency_pincode']);

          $err_msg = 'Please enter valid pincode as per selected city';
          if($pin_length != 6) { $err_msg = 'Please enter only 6 numbers in pincode'; }

          $this->form_validation->set_message('validation_check_valid_pincode',$err_msg);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['contact_person_mobile'] != "")
			{
        if($type == '1') 
        { 
          $contact_person_mobile = $this->security->xss_clean($this->input->post('contact_person_mobile')); 
          $enc_agency_id = $this->security->xss_clean($this->input->post('enc_agency_id')); 
          
          if($enc_agency_id != "" && $enc_agency_id != '0') { $agency_id = url_decode($enc_agency_id); }
          else { $agency_id = $enc_agency_id; }
        }
        else 
        { 
          $contact_person_mobile = $str; 
          $enc_agency_id = $type;
          $agency_id = url_decode($enc_agency_id);
        }

        //check if agency mobile exist or not
        $result_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0', 'am.contact_person_mobile' => $contact_person_mobile, 'am.agency_id !=' => $agency_id), 'am.agency_id, am.contact_person_mobile, am.contact_person_email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['contact_person_mobile'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist','The mobile number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY EMAIL ID EXIST OR NOT ********/
    public function validation_check_email_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['contact_person_email'] != "")
			{
        if($type == '1') 
        { 
          $contact_person_email = strtolower($this->security->xss_clean($this->input->post('contact_person_email'))); 
          $enc_agency_id = $this->security->xss_clean($this->input->post('enc_agency_id')); 
          
          if($enc_agency_id != "" && $enc_agency_id != '0') { $agency_id = url_decode($enc_agency_id); }
          else { $agency_id = $enc_agency_id; }
        }
        else 
        { 
          $contact_person_email = strtolower($str); 
          $enc_agency_id = $type;
          $agency_id = url_decode($enc_agency_id);
        }

        //check if agency mobile exist or not
        $result_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0', 'am.contact_person_email' => $contact_person_email, 'am.agency_id !=' => $agency_id), 'am.agency_id, am.contact_person_mobile, am.contact_person_email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['contact_person_email'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist','The email id is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->Iibf_bcbf_model->fun_restrict_input($str, $type); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_restrict_input', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
    function fun_validate_password($str) // Custom callback function for check valid PASSWORD
    {
      if($str != '')
      {
        $password_length = strlen($str);
        $err_msg = '';
        if($password_length < 8) { $err_msg = 'Please enter minimum 8 characters in password'; }
        else if($password_length > 20) { $err_msg = 'Please enter maximum 20 characters in password'; }

        if($err_msg != "")
        {
          $this->form_validation->set_message('fun_validate_password', $err_msg);
          return false;
        }
        else
        {
          $result = $this->Iibf_bcbf_model->fun_validate_password($str); 
          if($result['flag'] == 'success') { return true; }
          else
          {
            $this->form_validation->set_message('fun_validate_password', $result['response']);
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
		function validation_check_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'Please enter same password and confirm password';
			if(isset($_POST) && $_POST['confirm_password'] != "")
			{
        $agency_password = $this->security->xss_clean($this->input->post('agency_password'));
        if($type == '1') { $confirm_password = $this->security->xss_clean($this->input->post('confirm_password')); }
        else if($type == '0') { $confirm_password = $str; }   
        
        if($agency_password == $confirm_password)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_password'] != "")
        {
          $this->form_validation->set_message('validation_check_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY GST NUMBER EXIST OR NOT ********/
    public function validation_check_gst_no_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['gst_no'] != "")
			{
        if($type == '1') 
        { 
          $gst_no = $this->security->xss_clean($this->input->post('gst_no')); 
          $enc_agency_id = $this->security->xss_clean($this->input->post('enc_agency_id')); 
          
          if($enc_agency_id != "" && $enc_agency_id != '0') { $agency_id = url_decode($enc_agency_id); }
          else { $centre_id = $enc_agency_id; }
        }
        else 
        { 
          $gst_no = $str; 
          $enc_agency_id = $type;
          $agency_id = url_decode($enc_agency_id);
        }

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('is_deleted' => '0', 'gst_no' => $gst_no, 'agency_id !=' => $agency_id), 'agency_id, gst_no, is_active');
      
        if(count($agency_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['gst_no'] != "")
        {
          $this->form_validation->set_message('validation_check_gst_no_exist','The gst number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY GST NUMBER EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID GST NUMBER ********/
    function fun_validate_gst_no($str) // Custom callback function for check valid GST number
    {
      if($str != '')
      {
        $result = $this->Iibf_bcbf_model->fun_validate_gst_no($str); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_validate_gst_no', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID GST NUMBER ********/

  	/******** START : AGENCY DETAILS PAGE ********/ 
    public function agency_details($enc_agency_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Agency Master";      
      $data['page_title'] = 'IIBF - BCBF Agency Details';

      $data['enc_agency_id'] = $enc_agency_id;
      $agency_id = url_decode($enc_agency_id);
      
      $this->db->join('state_master sm', 'sm.state_code = am.agency_state', 'LEFT');
      $this->db->join('city_master cm', 'cm.id = am.agency_city', 'LEFT');
      $this->db->where('am.agency_id',$agency_id);
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'Active', 'Inactive') AS AgencyStatus, sm.state_name, cm.city_name");

      //print_r($form_data);die;
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/agency')); }  
      
      //$data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('pk_id' => $agency_id, 'module_slug' => 'agency_action'), 'log_id, module_slug, description, created_on', array('created_on'=>'ASC')); 
       
      //echo $this->db->last_query();
      $this->load->view('iibfbcbf/admin/agency_details_admin', $data);
    }
    /******** END : AGENCY DETAILS PAGE ********/

    /******** START : AGENCY STATUS CHANGE ********/ 
    public function change_agency_status() 
    {      
      $flag = "error";
      $response = '';
      if(isset($_POST) && $_POST['enc_id'] != "")
      { 
        $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
        $status_value = $this->security->xss_clean($this->input->post('status_value')); 
        $id = url_decode($enc_id);        
        $this->db->where("agency_id",$id);
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('is_deleted' => '0'), 'agency_id,is_active');
        
        if(count($agency_data) > 0)
        {
          if($agency_data[0]['is_active'] != $status_value)
          {
            $update_data["is_active"] = $status_value;
            if($status_value == '0')
            {
              $response = 'The agency has been successfully Deactivated.';  
              $this->session->set_flashdata('agency_status_success',$response);
              $this->Iibf_bcbf_model->insert_common_log('Agency : Deactivated', 'iibfbcbf_agency_master', $this->db->last_query(), $id,'agency_action','The agency has been successfully deactivated by the admin', json_encode($update_data)); 
            }
            else if($status_value == '1')
            {
              $response = 'The agency has been successfully Activated.';
              $this->session->set_flashdata('agency_status_success',$response);
              $this->Iibf_bcbf_model->insert_common_log('Agency : Activated', 'iibfbcbf_agency_master', $this->db->last_query(), $id,'agency_action','The agency has been successfully activated by the admin.', json_encode($update_data));
            }
            
            $this->db->where("agency_id",$id);
            $this->db->update("iibfbcbf_agency_master",$update_data); 
            $flag = "success";
          } 
          else
          {
            $this->session->set_flashdata('agency_status_error',"The agency status was already changed.");
          }
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $response;
      $result['is_active'] = $status_value;
      echo json_encode($result);  
    } 
    /******** START : AGENCY STATUS CHANGE ********/
   
 } 
?>  