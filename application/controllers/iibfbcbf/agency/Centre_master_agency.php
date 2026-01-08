<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Centre Master
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Centre_master_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
      
      if($this->login_user_type != 'agency')
      {
        $this->session->set_flashdata('error','You do not have permission to access Centre module');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
		}
    
    public function index($search_centre_status='')
    {   
      $data['act_id'] = "Centre Master";
      $data['sub_act_id'] = "Centre Master";
      $data['page_title'] = 'IIBF - BCBF Agency Centre Master';
      $data['search_centre_status'] = $search_centre_status;

      $this->load->view('iibfbcbf/agency/centre_master_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CENTER DATA ********/
    public function get_centre_data_ajax()
    {
      $table = 'iibfbcbf_centre_master fm';
      
      $column_order = array('fm.centre_id', 'CONCAT(fm.centre_name, " (", fm.centre_username, ")") AS centre_name', 'DATE(fm.created_on) AS CreatedOn', 'cm.city_name', 'sm.state_name', 'fm.centre_contact_person_name', 'fm.centre_contact_person_mobile', 'fm.centre_username', 'fm.centre_password', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted"))) AS DispStatus', 'fm.status'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(fm.centre_name, " (", fm.centre_username, ")")', 'DATE(fm.created_on)', 'cm.city_name', 'sm.state_name', 'fm.centre_contact_person_name', 'fm.centre_contact_person_mobile', 'fm.centre_username', 'fm.centre_password', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted")))'); //SET COLUMN FOR SEARCH
      $order = array('fm.centre_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.agency_id = '".$this->login_agency_id."' AND fm.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.agency_id = '".$this->login_agency_id."' AND fm.is_deleted = 0 	";
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
        $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."' AND DATE(fm.created_on) <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (DATE(fm.created_on) <= '".$s_to_date."')"; } 

      $s_centre_status = trim($this->security->xss_clean($this->input->post('s_centre_status')));
      if($s_centre_status != "") { $Where .= " AND fm.status = '".$s_centre_status."'"; }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN state_master sm ON sm.state_code = fm.centre_state";
      $join_qry .= " LEFT JOIN city_master cm ON cm.id = fm.centre_city";
            
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
        $row[] = $Res['centre_name'];
        $row[] = $Res['CreatedOn'];
        $row[] = $Res['city_name'];
        $row[] = $Res['state_name'];
        $row[] = $Res['centre_contact_person_name'];
        $row[] = $Res['centre_contact_person_mobile'];
        $row[] = $Res['centre_username'];
        $row[] = $this->Iibf_bcbf_model->password_decryption($Res['centre_password']);
                
        $row[] = '<span class="badge '.show_faculty_status($Res['status']).'" style="min-width:90px;">'.$Res['DispStatus'].'</span>';
        
        $btn_str = ' <div class="text-center no_wrap"> ';

        $function_change_pass = "get_modal_change_password_data('".url_encode($Res['centre_id'])."')";
        $btn_str .= '<a href="javascript:void(0)" onclick="'.$function_change_pass.'" class="btn btn-warning btn-xs" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></a> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/centre_master_agency/centre_details_agency/'.url_encode($Res['centre_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        $btn_str .= '<a href="'.site_url('iibfbcbf/agency/centre_master_agency/add_centre_agency/'.url_encode($Res['centre_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        /* if(in_array($Res['centre_id'],$delete_ids_str_arr)) { $check_val = "checked"; } else { $check_val = ""; }
        $row[] = '<label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$Res['centre_id'].'" id="checkboxlist_new_'.$Res['centre_id'].'" onclick="update_delete_str('.$Res['centre_id'].')" '.$check_val.'><span class="checkmark"></span></label>'; */
        
        $data[] = $row; 
      }			
      
      $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $TotalResult, //All result count
      "recordsFiltered" => $FilteredResult, //Disp result count
      /* "Query" => $print_query, */
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE CENTER DATA ********/
  	
    /******** START : ADD / UPDATE CENTER DATA ********/
    public function add_centre_agency($enc_centre_id=0)
    {   
      $data['act_id'] = "Centre Master";
      $data['sub_act_id'] = "Centre Master";      

      $data['enc_centre_id'] = $enc_centre_id;         
      if($enc_centre_id == '0') { $data['mode'] = $mode = "Add"; $centre_id = $enc_centre_id; }
      else
      {
        $centre_id = url_decode($enc_centre_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('agency_id' => $this->login_agency_id, 'centre_id' => $centre_id, 'is_deleted' => '0'), "*, IF(status=0, 'Inactive', IF(status=1, 'Active', IF(status=2, 'In Review', 'Re-submitted'))) AS DispStatus");
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/centre_master_agency/add_centre_agency')); }
        
        $data['mode'] = $mode = "Update";       
      }
      
      //echo $enc_centre_id;
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('centre_name', 'centre name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|callback_validation_check_centre_name_exist['.$enc_centre_id.']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('centre_address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('centre_address2', 'address line-2', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('centre_address3', 'address line-3', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('centre_address4', 'address line-4', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('centre_state', 'state', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('centre_city', 'city', 'trim|required|xss_clean', array('required'=>"Please select the %s"));       
        $this->form_validation->set_rules('centre_district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[30]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('centre_pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode['.$this->input->post('centre_state').']|xss_clean', array('required'=>"Please enter the %s"));        
        $this->form_validation->set_rules('centre_mobile', 'centre contact number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_fun_restrict_input[first_zero_not_allowed]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist['.$enc_centre_id.']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('centre_contact_person_name', 'name of contact person', 'trim|required|max_length[90]|callback_fun_restrict_input[allow_only_alphabets_and_space]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('centre_contact_person_mobile', 'contact person mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_fun_restrict_input[first_zero_not_allowed]|min_length[10]|max_length[10]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('centre_contact_person_email', 'contact person email id', 'trim|required|max_length[80]|valid_email|xss_clean', array('required'=>"Please enter the %s"));
        /* $this->form_validation->set_rules('centre_type', 'centre type', 'trim|required|xss_clean', array('required'=>"Please select the %s")); */
        $this->form_validation->set_rules('gst_no', 'GST no.', 'trim|required|min_length[15]|max_length[15]|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|callback_fun_validate_gst_no|callback_validation_check_gst_no_exist['.$enc_centre_id.']|xss_clean', array('required'=>"Please enter the %s"));
        
        if($mode == 'Add') {
          $this->form_validation->set_rules('centre_password', 'centre password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				  $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));        
        }
        
        $this->form_validation->set_rules('invoice_address', 'address on invoice', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('centre_remarks', 'centre remark', 'trim|max_length[300]|xss_clean');
        $this->form_validation->set_rules('declaration', 'declaration', 'trim|required|xss_clean', array('required'=>"Please confirm the details"));
        
        //$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        if($this->form_validation->run())
        {
          //echo 'in'; 
          //_pa($_POST,1); //iibfbcbf/iibf_bcbf_helper.php
          $posted_arr = json_encode($_POST);
          $agencyName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_id, 'agency');
          
          $add_data['agency_id'] = $this->login_agency_id;
          $add_data['centre_name'] = $this->input->post('centre_name');
          $add_data['centre_address1'] = $this->input->post('centre_address1');
          $add_data['centre_address2'] = $this->input->post('centre_address2');
          $add_data['centre_address3'] = $this->input->post('centre_address3');
          $add_data['centre_address4'] = $this->input->post('centre_address4');
          $add_data['centre_state'] = $this->input->post('centre_state');
          $add_data['centre_city'] = $this->input->post('centre_city');
          $add_data['centre_district'] = $this->input->post('centre_district');
          $add_data['centre_pincode'] = $this->input->post('centre_pincode');
          $add_data['centre_mobile'] = $this->input->post('centre_mobile');
          $add_data['centre_contact_person_name'] = $this->input->post('centre_contact_person_name');
          $add_data['centre_contact_person_mobile'] = $this->input->post('centre_contact_person_mobile');
          $add_data['centre_contact_person_email'] = strtolower($this->input->post('centre_contact_person_email'));
          $add_data['centre_type'] = '1'; //$this->input->post('centre_type');
          $add_data['gst_no'] = $this->input->post('gst_no');
          $add_data['invoice_address'] = $this->input->post('invoice_address');
          $add_data['centre_remarks'] = $this->input->post('centre_remarks');          
          
          if($mode == "Add") 
          {
            $add_data['centre_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('centre_password'));
            $add_data['status'] = '2';
            $add_data['created_on'] = date("Y-m-d H:i:s");
            $add_data['created_by'] = $this->login_agency_id;
            
            $this->master_model->insertRecord('iibfbcbf_centre_master',$add_data);
            $centre_id = $this->db->insert_id();

            $this->Iibf_bcbf_model->insert_common_log('Agency : centre Added', 'iibfbcbf_centre_master', $this->db->last_query(), $centre_id,'centre_action','The centre has successfully added by the agency '.$agencyName['disp_name'], $posted_arr);

            //GENERATE CENTER USER ID AND UPDATE IT IN TABLE. (Agency Code-agency serial number : 1515-001, 1515-002, 1515-003)            
            $up_data = array();
            $up_data['centre_username'] = $this->generate_centre_user_id($centre_id);
            $this->master_model->updateRecord('iibfbcbf_centre_master', $up_data, array('centre_id'=>$centre_id));
            
            $this->session->set_flashdata('success','centre record added successfully');              
          }
          else if($mode == "Update")
          {
            if($form_data[0]['status'] == '1') //IF CENTER STATUS IS ACTIVE AND AGENCY AGAIN UPDATE THE RECORD THEN MAKE CENTER STATUS AS RE-SUBMITTED
            {
              $add_data['status'] = '3';
            }

            $add_data['updated_on'] = date("Y-m-d H:i:s");
            $add_data['updated_by'] = $this->login_agency_id;            
            $this->master_model->updateRecord('iibfbcbf_centre_master', $add_data, array('centre_id'=>$centre_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Agency : centre Updated', 'iibfbcbf_centre_master', $this->db->last_query(), $centre_id,'centre_action','The centre has successfully updated by the agency '.$agencyName['disp_name'], $posted_arr);
            
            $this->session->set_flashdata('success','centre record updated successfully');              
          }

          redirect(site_url('iibfbcbf/agency/centre_master_agency'));
        }
      }	
      
      $data['page_title'] = 'IIBF - BCBF Agency '.$mode.' Centre';

      $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));
      $this->load->view('iibfbcbf/agency/add_centre_agency', $data);
    }/******** END : ADD / UPDATE CENTER DATA ********/

    function get_city_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $onchange_fun = "validate_file('centre_city')";
				$html = '	<select class="form-control chosen-select" name="centre_city" id="centre_city" required onchange="'.$onchange_fun.'">';
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

    /******** START : CENTER DETAILS PAGE ********/
    public function centre_details_agency($enc_centre_id=0)
    {   
      $data['act_id'] = "Centre Master";
      $data['sub_act_id'] = "Centre Master";      
      $data['page_title'] = 'IIBF - BCBF Agency Faculty Details';

      $data['enc_centre_id'] = $enc_centre_id;
      $centre_id = url_decode($enc_centre_id);

      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master cmm', 'cmm.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id' => $this->login_agency_id, 'cm.centre_id' => $centre_id, 'cm.is_deleted' => '0'), "cm.*, IF(cm.centre_type=1, 'Regular', IF(cm.centre_type=2, 'Temporary', '')) AS DispCentreType, IF(cm.status=0, 'Inactive', IF(cm.status=1, 'Active', IF(cm.status=2, 'In Review', 'Re-submitted'))) AS DispStatus, sm.state_name, cmm.city_name, am.agency_name, am.agency_code, am.allow_exam_types");
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/centre_master_agency')); }

      $this->load->view('iibfbcbf/agency/centre_details_agency', $data);
    }/******** END : CENTER DETAILS PAGE ********/

    /******** START : VALIDATION FUNCTION TO CHECK CENTER WITH SAME CITY EXIST OR NOT ********/
    //CHECK IF CENTER WITH SAME CITY IS EXIST OR NOT
    public function validation_check_centre_name_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['centre_name'] != "")
			{
        $centre_city = $this->security->xss_clean($this->input->post('centre_city')); 
        if($type == '1') 
        { 
          $centre_name = $this->security->xss_clean($this->input->post('centre_name')); 
          $enc_centre_id = $this->security->xss_clean($this->input->post('enc_centre_id')); 
          
          if($enc_centre_id != "" && $enc_centre_id != '0') { $centre_id = url_decode($enc_centre_id); }
          else { $centre_id = $enc_centre_id; }
        }
        else 
        { 
          $centre_name = $str;
          $enc_centre_id = $type;
          $centre_id = url_decode($enc_centre_id);
        }

        //check if CENTER WITH SAME CITY exist or not
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0', 'centre_name' => $centre_name, 'centre_city' => $centre_city, 'agency_id' => $this->login_agency_id, 'centre_id !=' => $centre_id), 'centre_id, agency_id, centre_name, status');
      
        if(count($centre_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['centre_name'] != "")
        {
          $this->form_validation->set_message('validation_check_centre_name_exist','The centre with same centre name is already exist in your agency');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK CENTER WITH SAME CITY EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    public function validation_check_valid_pincode($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['centre_pincode'] != "")
			{
        if($type == '1') 
        { 
          $centre_pincode = $this->security->xss_clean($this->input->post('centre_pincode')); 
          $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
        }
        else 
        { 
          $centre_pincode = $str; 
          $selected_state_code = $type;
        }

        $this->db->where(" '".$centre_pincode."' BETWEEN start_pin AND end_pin ");
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
        else if($_POST['centre_pincode'] != "")
        {
          $pin_length = strlen($_POST['centre_pincode']);

          $err_msg = 'Please enter valid pincode as per selected city';
          if($pin_length != 6) { $err_msg = 'Please enter only 6 numbers in pincode'; }

          $this->form_validation->set_message('validation_check_valid_pincode',$err_msg);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK CENTER MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['centre_mobile'] != "")
			{
        if($type == '1') 
        { 
          $centre_mobile = $this->security->xss_clean($this->input->post('centre_mobile')); 
          $enc_centre_id = $this->security->xss_clean($this->input->post('enc_centre_id')); 
          
          if($enc_centre_id != "" && $enc_centre_id != '0') { $centre_id = url_decode($enc_centre_id); }
          else { $centre_id = $enc_centre_id; }
        }
        else 
        { 
          $centre_mobile = $str; 
          $enc_centre_id = $type;
          $centre_id = url_decode($enc_centre_id);
        }

        //check if centre mobile exist or not
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0', 'centre_mobile' => $centre_mobile, 'centre_id !=' => $centre_id), 'centre_id, agency_id, centre_mobile, status');
      
        if(count($centre_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['centre_mobile'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist','The mobile number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK CENTER MOBILE EXIST OR NOT ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK CENTER GST NUMBER EXIST OR NOT ********/
    /****** SAME GST NUMBER IS ALLOWED FOR MULTIPLE CENTRES IN SAME STATE  */
    public function validation_check_gst_no_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['gst_no'] != "")
			{
        $centre_state = $this->security->xss_clean($this->input->post('centre_state')); 

        if($type == '1') 
        { 
          $gst_no = $this->security->xss_clean($this->input->post('gst_no')); 
          $enc_centre_id = $this->security->xss_clean($this->input->post('enc_centre_id')); 
          
          if($enc_centre_id != "" && $enc_centre_id != '0') { $centre_id = url_decode($enc_centre_id); }
          else { $centre_id = $enc_centre_id; }
        }
        else 
        { 
          $gst_no = $str; 
          $enc_centre_id = $type;
          $centre_id = url_decode($enc_centre_id);
        }

        //check if centre mobile exist or not
        if($centre_state != '') { $this->db->where('centre_state !=', $centre_state); } /****** SAME GST NUMBER IS ALLOWED FOR MULTIPLE CENTRES IN SAME STATE  */
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0', 'gst_no' => $gst_no, 'centre_id !=' => $centre_id), 'centre_id, agency_id, gst_no, status');
        
        if(count($centre_data) == 0)
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
    }/******** END : VALIDATION FUNCTION TO CHECK CENTER GST NUMBER EXIST OR NOT ********/

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

    /******** START : VALIDATION FUNCTION TO CHECK CENTER USERNAME EXIST OR NOT ********/
    //first check username is exist in iibfbcbf_centre_master
    // if not then check username is exist in iibfbcbf_agency_master
    // if not then check username is exist in inspector table
    // if not then check username is exist in iibfbcbf_admin
    /* public function validation_check_centre_username_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['centre_username'] != "")
			{
        if($type == '1') 
        { 
          $centre_username = $this->security->xss_clean($this->input->post('centre_username')); 
          $enc_centre_id = $this->security->xss_clean($this->input->post('enc_centre_id')); 
          
          if($enc_centre_id != "" && $enc_centre_id != '0') { $centre_id = url_decode($enc_centre_id); }
          else { $centre_id = $enc_centre_id; }
        }
        else 
        { 
          $centre_username = $str; 
          $enc_centre_id = $type;
          $centre_id = url_decode($enc_centre_id);
        }

        //check if centre username exist or not
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0', 'centre_username' => $centre_username, 'centre_id !=' => $centre_id), 'centre_id, agency_id, centre_username, status');
      
        if(count($centre_data) == 0) //check if username is exist in iibfbcbf_centre_master
        {
          $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('is_deleted' => '0', 'agency_code' => $centre_username), 'agency_id, agency_name, agency_code');

          if(count($agency_data) == 0) //check if username is exist in iibfbcbf_agency_master
          {
            $inspector_data = array(); //inspector query goes here

            if(count($inspector_data) == 0) //check if username is exist in inspector table
            {
              $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('is_deleted' => '0', 'admin_username' => $centre_username), 'admin_id, admin_username');

              if(count($admin_data) == 0) //check if username is exist in iibfbcbf_admin
              {
                $return_val_ajax = 'true';
              }
            }
          }
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['centre_username'] != "")
        {
          $this->form_validation->set_message('validation_check_centre_username_exist','The username is already exist');
          return false;
        }
      }
    } *//******** END : VALIDATION FUNCTION TO CHECK CENTER USERNAME EXIST OR NOT ********/

    /******** START : GENERATE UNIQUE CENTER NAME ********/
    function generate_centre_user_id($centre_id='0')
    {
      $centre_qry = $this->db->query('SELECT centre_id FROM iibfbcbf_centre_master WHERE agency_id = "'.$this->login_agency_id.'" AND centre_id <= "'.$centre_id.'"');
      $centre_count = $centre_qry->num_rows();

      $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->login_agency_id), 'agency_id, agency_code');
      
      $centre_user_id = $agency_data[0]['agency_code'].'-'.sprintf('%03d',$centre_count);
      return $centre_user_id;

      //check if centre username exist or not
      /* $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0', 'centre_username' => $centre_user_id, 'centre_id !=' => $centre_id), 'centre_id, agency_id, centre_username, status');
      
      if(count($centre_data) == 0) //check if username is exist in iibfbcbf_centre_master
      {
        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('is_deleted' => '0', 'inspector_username' => $centre_user_id), 'inspector_id, inspector_username, is_active');

        if(count($inspector_data) == 0) //check if username is exist in iibfbcbf_inspector_master
        {
          $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('is_deleted' => '0', 'admin_username' => $centre_user_id), 'admin_id, admin_username');

          if(count($admin_data) == 0) //check if username is exist in iibfbcbf_admin
          {
            return $centre_user_id;
          }
          else { return $centre_user_id.'_'.date("YmdHis"); }
        }
        else { return $centre_user_id.'_'.date("YmdHis"); }
      }
      else { return $centre_user_id.'_'.date("YmdHis"); } */
    }/******** END : GENERATE UNIQUE CENTER NAME ********/

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
        $new_password = $this->security->xss_clean($this->input->post('centre_password'));
        if($type == '1') { $confirm_password = $this->security->xss_clean($this->input->post('confirm_password')); }
        else if($type == '0') { $confirm_password = $str; }   
        
        if($new_password == $confirm_password)
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

    function get_modal_change_password_data()
		{
			$data['enc_centre_id'] = $enc_centre_id = $this->input->post('enc_centre_id');

      if($enc_centre_id == "0")
			{
				echo "error";
			}
			else
			{
				$centre_id = url_decode($enc_centre_id);

        $result_data = $this->master_model->getRecords('iibfbcbf_centre_master am', array('am.centre_id'=>$centre_id, 'am.is_deleted'=>'0'));
        
        if(count($result_data) == 0)
				{
					echo "error";
				}
				else
				{
					$data['form_data'] = $result_data;
          $this->load->view('iibfbcbf/agency/modal_change_password_centre', $data);
				}
			}
		}

    /******** START : CHANGE CENTER PASSWORD ********/
    public function change_password($enc_centre_id=0)
    {   
      $data['enc_centre_id'] = $enc_centre_id;
            
      if($enc_centre_id == '0') 
      { 
        $this->session->set_flashdata('error','Error occurred. Try again later.');
        redirect(site_url('iibfbcbf/agency/centre_master_agency')); 
      }
      else
      {
        $centre_id = url_decode($enc_centre_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id' => $centre_id, 'is_deleted' => '0'), "*");
        if(count($form_data) == 0) 
        { 
          $this->session->set_flashdata('error','Error occurred. Try again later.');
          redirect(site_url('iibfbcbf/agency/centre_master_agency'));
        }
        else
        {
          if(isset($_POST) && count($_POST) > 0)
          { 
            $this->form_validation->set_rules('centre_password', 'password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));             
           
            if($this->form_validation->run())
            { 
              //_pa($_POST,1);
              $posted_arr = json_encode($_POST);
              $agencyName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_id, 'agency');
              
              //echo 'IN';exit;
              $add_data['centre_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('centre_password'));
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_agency_id;            
              $this->master_model->updateRecord('iibfbcbf_centre_master', $add_data, array('centre_id'=>$centre_id));
                
              $this->Iibf_bcbf_model->insert_common_log('Agency : Centre Password Updated', 'iibfbcbf_centre_master', $this->db->last_query(), $centre_id,'centre_password_action','The centre password has successfully updated by the agency '.$agencyName['disp_name'].'.', $posted_arr);
                  
              $this->session->set_flashdata('success','Centre password updated successfully');              
              redirect(site_url('iibfbcbf/agency/centre_master_agency'));
            }
            else
            {
              $this->session->set_flashdata('error','Validation error occurred. Please try again.');
              redirect(site_url('iibfbcbf/agency/centre_master_agency'));
            }
          }	
          else
          {
            $this->session->set_flashdata('error','Invalid request.');
            redirect(site_url('iibfbcbf/agency/centre_master_agency'));
          }
        }      
      }
    }/******** END : CHANGE AGENCY PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/
    public function check_old_password()
    { 
			if(isset($_POST) && $_POST['centre_password'] != "")
			{
        $centre_password = $this->security->xss_clean($this->input->post('centre_password')); 
        $enc_centre_id = $this->security->xss_clean($this->input->post('enc_centre_id')); 
        
        if($enc_centre_id != "" && $enc_centre_id != '0') { $centre_id = url_decode($enc_centre_id); }
        else { $centre_id = $enc_centre_id; }

        $result_data = $this->master_model->getRecords('iibfbcbf_centre_master am', array('am.is_deleted' => '0', 'am.centre_password' => $this->Iibf_bcbf_model->password_encryption($centre_password), 'am.centre_id' => $centre_id), 'am.centre_id');
        
        if(count($result_data) > 0) { echo 'false'; }
        else { echo 'true'; }
			}
      else
      {
        echo 'true';
      }
    }/******** END : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/
  } ?>  