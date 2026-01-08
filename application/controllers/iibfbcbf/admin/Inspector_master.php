<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Admin Inspector Master
  ** Created BY: Sagar Matale On 06-01-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Inspector_master extends CI_Controller 
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
    }
    
    public function index()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Inspector Master";
      $data['page_title'] = 'IIBF - BCBF Admin Inspector Master';
      $this->load->view('iibfbcbf/admin/inspector_master_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE INSPECTOR DATA ********/
    public function get_inspector_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_inspector_master im';
      
      $column_order = array('im.inspector_id', 'im.inspector_name', 'im.inspector_mobile', 'im.inspector_email', 'im.inspector_designation', 'im.inspector_username', 'im.inspector_password', 'IF(im.batch_online_offline_flag="2", "Online", (SELECT GROUP_CONCAT(cm.city_name SEPARATOR ", ") FROM iibfbcbf_inspector_centres imc INNER JOIN city_master cm ON cm.id = imc.city WHERE imc.inspector_id = im.inspector_id)) AS AssignedCities', 'IF(im.is_active=0, "Inactive", "Active") AS DispStatus', 'im.is_active', 'im.is_deleted'); //SET COLUMNS FOR SORT
      
      $column_search = array('im.inspector_name', 'im.inspector_mobile', 'im.inspector_email', 'im.inspector_designation', 'im.inspector_username', 'IF(im.batch_online_offline_flag="2", "Online", (SELECT GROUP_CONCAT(cm.city_name SEPARATOR ", ") FROM iibfbcbf_inspector_centres imc INNER JOIN city_master cm ON cm.id = imc.city WHERE imc.inspector_id = im.inspector_id))', 'IF(im.is_active=0, "Inactive", "Active")'); //SET COLUMN FOR SEARCH
      $order = array('im.inspector_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE im.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE im.is_deleted = 0 	";
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      } 
      
      if ($form_action == 'export')
      {
        if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace( $Where, "", -3 );
          $Where .= ')';
        }
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT
      
      $join_qry = " ";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_inspector_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Inspector Name', 'Mobile Number', 'Email Id', 'Inspector Designation', 'Username', 'Password', 'Assigned Centres(City)', 'Status'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['inspector_name'];
        $row[] = $Res['inspector_mobile'];
        $row[] = $Res['inspector_email'];
        $row[] = $Res['inspector_designation'];
        $row[] = $Res['inspector_username'];
        $row[] = $this->Iibf_bcbf_model->password_decryption($Res['inspector_password']);
        $row[] = $Res['AssignedCities'];

        if ($form_action != 'export')
        {
          $row[] = '<span class="badge '.show_faculty_status($Res['is_active']).'" style="min-width:70px;">'.$Res['DispStatus'].'</span>';
          
          $btn_str = ' <div class="text-center no_wrap"> ';

          $function_change_pass = "get_modal_change_password_data('".url_encode($Res['inspector_id'])."')";
          $btn_str .= '<a href="javascript:void(0)" onclick="'.$function_change_pass.'" class="btn btn-warning btn-xs" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></a> ';

          $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/inspector_master/inspector_details/'.url_encode($Res['inspector_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
          
          $btn_str .= '<a href="'.site_url('iibfbcbf/admin/inspector_master/add_inspector/'.url_encode($Res['inspector_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
          
          $btn_str .= ' </div>';
          $row[] = $btn_str;
        }
        else
        {
          $row[] = $Res['DispStatus'];
        }
        
        if ($form_action == 'export')
        {
          array_walk($row, 'filterData');
          $excelData .= implode("\t", array_values($row)) . "\n";
        }

        $data[] = $row; 
      }
      
      if ($form_action == 'export')
      {
        if (count($Rows) == '0')
        {
          $excelData .= 'No records found...' . "\n";
        }

        // Headers for download 
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        // Render excel data 
        echo $excelData;
        exit;
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE INSPECTOR DATA ********/

    /******** START : ADD / UPDATE INSPECTOR DATA ********/
    public function add_inspector($enc_inspector_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Inspector Master";
      
      $data['enc_inspector_id'] = $enc_inspector_id;
            
      if($enc_inspector_id == '0') { $data['mode'] = $mode = "Add"; $inspector_id = $enc_inspector_id; }
      else
      {
        $inspector_id = url_decode($enc_inspector_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_id' => $inspector_id, 'is_deleted' => '0'), "*");
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/inspector_master/add_inspector')); }
        
        $data['mode'] = $mode = "Update";  
        
        $city_id_arr = array();
        $field_arr = $this->master_model->getRecords('iibfbcbf_inspector_centres', array('inspector_id' => $inspector_id));
        if(count($field_arr) > 0)
        {
          foreach($field_arr as $res)
          {            
            $city_id_arr[] = $res['city'];
          }
        }    
        $data['form_city_id_arr'] = $form_city_id_arr = array_values($city_id_arr);        
      }
      
      if(isset($_POST) && count($_POST) > 0)
      { 
        $this->form_validation->set_rules('inspector_name', 'inspector name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('inspector_mobile', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist['.$enc_inspector_id.']|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('inspector_email', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist['.$enc_inspector_id.']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('inspector_designation', 'inspector designation', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('inspector_username', 'inspector username', 'trim|required|min_length[3]|max_length[30]|callback_fun_restrict_input[ValidUsername]|callback_validation_check_username_exist['.$enc_inspector_id.']|xss_clean', array('required'=>"Please enter the %s"));

        if($mode == 'Add') {
          $this->form_validation->set_rules('inspector_password', 'inspector password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				  $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));        
        }

        $this->form_validation->set_rules('batch_online_offline_flag', 'type', 'trim|required|xss_clean', array('required'=>"Please select the %s"));

        if($this->input->post('batch_online_offline_flag') == '1')//offline
        {
          $this->form_validation->set_rules('state[]', 'state', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
          $this->form_validation->set_rules('city[]', 'city', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        }
        $this->form_validation->set_rules('status', 'status', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        //$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        if($this->form_validation->run())
        { 
          //_pa($_POST,1);
          $posted_arr = json_encode($_POST);
          $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');
          
          //echo 'IN';exit;
          $add_data['inspector_name'] = $this->input->post('inspector_name');
          $add_data['inspector_mobile'] = $this->input->post('inspector_mobile');
          $add_data['inspector_email'] = $this->input->post('inspector_email');
          $add_data['inspector_designation'] = $this->input->post('inspector_designation');
          $add_data['inspector_username'] = $this->input->post('inspector_username');
          $add_data['batch_online_offline_flag'] = $batch_online_offline_flag = $this->input->post('batch_online_offline_flag');
          
          if($batch_online_offline_flag == '1')//offline
          {
            $add_data['state_codes'] = implode(",",$this->input->post('state'));
          }
          else { $add_data['state_codes'] = ''; }

          $add_data['is_active'] = $this->input->post('status');
          $add_data['ip_address'] = get_ip_address(); //general_helper.php   
          
          if($mode == "Add") 
          {
            $add_data['inspector_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('inspector_password'));
            $add_data['created_on'] = date("Y-m-d H:i:s");
            $add_data['created_by'] = $this->login_admin_id;              
              
            $this->master_model->insertRecord('iibfbcbf_inspector_master',$add_data);
            $inspector_id = $this->db->insert_id();

            $this->Iibf_bcbf_model->insert_common_log('Admin : Inspector Added', 'iibfbcbf_inspector_master', $this->db->last_query(), $inspector_id,'inspector_action','The inspector has successfully added by the admin '.$dispName['disp_name'].'.', $posted_arr);
            $this->session->set_flashdata('success','Inspector record added successfully');              
          }
          else if($mode == "Update")
          {
            $add_data['updated_on'] = date("Y-m-d H:i:s");
            $add_data['updated_by'] = $this->login_admin_id;            
            $this->master_model->updateRecord('iibfbcbf_inspector_master', $add_data, array('inspector_id'=>$inspector_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Admin : Inspector Updated', 'iibfbcbf_inspector_master', $this->db->last_query(), $inspector_id,'inspector_action','The inspector has successfully updated by the admin '.$dispName['disp_name'].'.', $posted_arr);
							
            $this->session->set_flashdata('success','Inspector record updated successfully');              
          }

          if($inspector_id > 0)
          {
            if($mode == 'Add') { $old_arr = array(); }
            else if($mode == 'Update') { $old_arr = $city_id_arr; }
            $current_arr = $this->input->post('city');

            if(count($current_arr) == 0)
            {
              $this->master_model->deleteRecord('iibfbcbf_inspector_centres','inspector_id', $inspector_id);
            }
            else
            {
              $insert_arr = $delete_arr = array();
              if(count($old_arr) == 0) { $insert_arr = $current_arr; }
              else
              {
                $delete_arr = array_diff($old_arr, $current_arr);
                $insert_arr = array_diff($current_arr,$old_arr);
              }

              foreach($insert_arr as $ress1)
              {
                $add_field = array();
                $add_field['inspector_id'] = $inspector_id;
                $add_field['city'] = $ress1;
                $add_field['created_on'] = date("Y-m-d H:i:s");
                $this->master_model->insertRecord('iibfbcbf_inspector_centres',$add_field);
              }

              foreach($delete_arr as $ress2)
              {
                $this->db->where('inspector_id', $inspector_id);
                $this->db->where('city', $ress2);
                $this->db->delete('iibfbcbf_inspector_centres');
              }
            }
          }

          redirect(site_url('iibfbcbf/admin/inspector_master'));
        }
      }	
      
      $data['page_title'] = 'IIBF - BCBF Admin '.$mode.' Inspector';
      
      $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));
      $this->load->view('iibfbcbf/admin/add_inspector_admin', $data);
    }/******** END : ADD / UPDATE INSPECTOR DATA ********/

    function get_modal_change_password_data()
		{
			$data['enc_inspector_id'] = $enc_inspector_id = $this->input->post('enc_inspector_id');

      if($enc_inspector_id == "0")
			{
				echo "error";
			}
			else
			{
				$inspector_id = url_decode($enc_inspector_id);

        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.inspector_id'=>$inspector_id, 'im.is_deleted'=>'0'));
        
        if(count($inspector_data) == 0)
				{
					echo "error";
				}
				else
				{
					$data['form_data'] = $inspector_data;
          $this->load->view('iibfbcbf/admin/modal_change_password_inspector', $data);
				}
			}
		}

    /******** START : CHANGE INSPECTOR PASSWORD ********/
    public function change_password($enc_inspector_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Inspector Master";
      
      $data['enc_inspector_id'] = $enc_inspector_id;
            
      if($enc_inspector_id == '0') 
      { 
        $this->session->set_flashdata('error','Error occurred. Try again later.');
        redirect(site_url('iibfbcbf/admin/inspector_master')); 
      }
      else
      {
        $inspector_id = url_decode($enc_inspector_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_id' => $inspector_id, 'is_deleted' => '0'), "*");
        if(count($form_data) == 0) 
        { 
          $this->session->set_flashdata('error','Error occurred. Try again later.');
          redirect(site_url('iibfbcbf/admin/inspector_master'));
        }
        else
        {
          if(isset($_POST) && count($_POST) > 0)
          { 
            $this->form_validation->set_rules('inspector_password', 'inspector password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));             
           
            if($this->form_validation->run())
            { 
              //_pa($_POST,1);
              $posted_arr = json_encode($_POST);
              $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');
              
              //echo 'IN';exit;
              $add_data['inspector_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('inspector_password'));
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_admin_id;            
              $this->master_model->updateRecord('iibfbcbf_inspector_master', $add_data, array('inspector_id'=>$inspector_id));
                
              $this->Iibf_bcbf_model->insert_common_log('Admin : Inspector Updated', 'iibfbcbf_inspector_master', $this->db->last_query(), $inspector_id,'inspector_password_action','The inspector password has successfully updated by the admin '.$dispName['disp_name'].'.', $posted_arr);
                  
              $this->session->set_flashdata('success','Inspector password updated successfully');              
              redirect(site_url('iibfbcbf/admin/inspector_master'));
            }
            else
            {
              $this->session->set_flashdata('error','Validation error occurred. Please try again.');
              redirect(site_url('iibfbcbf/admin/inspector_master'));
            }
          }	
          else
          {
            $this->session->set_flashdata('error','Invalid request.');
            redirect(site_url('iibfbcbf/admin/inspector_master'));
          }
        }      
      }
    }/******** END : CHANGE INSPECTOR PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/
    public function check_old_password()
    { 
			if(isset($_POST) && $_POST['inspector_password'] != "")
			{
        $inspector_password = $this->security->xss_clean($this->input->post('inspector_password')); 
        $enc_inspector_id = $this->security->xss_clean($this->input->post('enc_inspector_id')); 
        
        if($enc_inspector_id != "" && $enc_inspector_id != '0') { $inspector_id = url_decode($enc_inspector_id); }
        else { $inspector_id = $enc_inspector_id; }

        //check if inspector mobile exist or not
        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.is_deleted' => '0', 'im.inspector_password' => $this->Iibf_bcbf_model->password_encryption($inspector_password), 'im.inspector_id' => $inspector_id), 'im.inspector_id');
        
        if(count($inspector_data) > 0) { echo 'false'; }
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
        $state_code_arr = $selected_cities_arr = array("0");
				$state_id = $this->security->xss_clean($this->input->post('state_id'));
				$selected_cities = $this->security->xss_clean($this->input->post('selected_cities'));

        if($state_id != "") { $state_code_arr = explode(",", $state_id); }
        if($selected_cities != "") { $selected_cities_arr = explode(",", $selected_cities); }

        $this->db->where_in('state_code', $state_code_arr);
        $city_data = $this->master_model->getRecords('city_master', array('city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
        
        $onchange_fun = "validate_file('city')";
        $data_placeholder = "No City Available";
        if(count($city_data) > 0) { $data_placeholder = "Select City *"; }

				$html = '	<select class="form-control chosen-select" name="city[]" id="city" required onchange="'.$onchange_fun.'" multiple data-placeholder="'.$data_placeholder.'">';
        
				if(count($city_data) > 0)
				{
          foreach($city_data as $city)
					{
            $selected_flag = '';
            if(in_array($city['id'], $selected_cities_arr)) { $selected_flag = 'selected'; }
						$html .= '	<option value="'.$city['id'].'" '.$selected_flag.'>'.$city['city_name'].'</option>';
          }
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

    /******** START : VALIDATION FUNCTION TO CHECK INSPECTOR MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['inspector_mobile'] != "")
			{
        if($type == '1') 
        { 
          $inspector_mobile = $this->security->xss_clean($this->input->post('inspector_mobile')); 
          $enc_inspector_id = $this->security->xss_clean($this->input->post('enc_inspector_id')); 
          
          if($enc_inspector_id != "" && $enc_inspector_id != '0') { $inspector_id = url_decode($enc_inspector_id); }
          else { $inspector_id = $enc_inspector_id; }
        }
        else 
        { 
          $inspector_mobile = $str; 
          $enc_inspector_id = $type;
          $inspector_id = url_decode($enc_inspector_id);
        }

        //check if inspector mobile exist or not
        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.is_deleted' => '0', 'im.inspector_mobile' => $inspector_mobile, 'im.inspector_id !=' => $inspector_id), 'im.inspector_id, im.inspector_mobile, im.inspector_email, im.inspector_username');
              
        if(count($inspector_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['inspector_mobile'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist','The mobile number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK INSPECTOR MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK INSPECTOR EMAIL EXIST OR NOT ********/
    public function validation_check_email_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['inspector_email'] != "")
			{
        if($type == '1') 
        { 
          $inspector_email = $this->security->xss_clean($this->input->post('inspector_email')); 
          $enc_inspector_id = $this->security->xss_clean($this->input->post('enc_inspector_id')); 
          
          if($enc_inspector_id != "" && $enc_inspector_id != '0') { $inspector_id = url_decode($enc_inspector_id); }
          else { $inspector_id = $enc_inspector_id; }
        }
        else 
        { 
          $inspector_email = $str; 
          $enc_inspector_id = $type;
          $inspector_id = url_decode($enc_inspector_id);
        }

        //check if inspector email exist or not
        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.is_deleted' => '0', 'im.inspector_email' => $inspector_email, 'im.inspector_id !=' => $inspector_id), 'im.inspector_id, im.inspector_mobile, im.inspector_email, im.inspector_username');
              
        if(count($inspector_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['inspector_email'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist','The email is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK INSPECTOR EMAIL EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK INSPECTOR USERNAME EXIST OR NOT ********/
    public function validation_check_username_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['inspector_username'] != "")
			{
        if($type == '1') 
        { 
          $inspector_username = $this->security->xss_clean($this->input->post('inspector_username')); 
          $enc_inspector_id = $this->security->xss_clean($this->input->post('enc_inspector_id')); 
          
          if($enc_inspector_id != "" && $enc_inspector_id != '0') { $inspector_id = url_decode($enc_inspector_id); }
          else { $inspector_id = $enc_inspector_id; }
        }
        else 
        { 
          $inspector_username = $str; 
          $enc_inspector_id = $type;
          $inspector_id = url_decode($enc_inspector_id);
        }

        //check if inspector username exist or not
        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.is_deleted' => '0', 'im.inspector_username' => $inspector_username, 'im.inspector_id !=' => $inspector_id), 'im.inspector_id, im.inspector_mobile, im.inspector_email, im.inspector_username');//check in inspector table
              
        if(count($inspector_data) == 0)
        {
          $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_username' => $inspector_username, 'cm.status' => '1', 'cm.is_deleted' => '0'), 'cm.centre_id');//check in centre table

          if(count($centre_data) == 0)
          {
            $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_code' => $inspector_username, 'is_deleted' => '0'), 'agency_id');//check in agency table

            if(count($agency_data) == 0)
            {
              $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_username' => $inspector_username, 'is_deleted' => '0'), 'admin_id');//check in admin table
              
              if(count($admin_data) == 0)
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
        else if($_POST['inspector_username'] != "")
        {
          $this->form_validation->set_message('validation_check_username_exist','The username is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK INSPECTOR USERNAME EXIST OR NOT ********/

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
        $inspector_password = $this->security->xss_clean($this->input->post('inspector_password'));
        if($type == '1') { $confirm_password = $this->security->xss_clean($this->input->post('confirm_password')); }
        else if($type == '0') { $confirm_password = $str; }   
        
        if($inspector_password == $confirm_password)
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
    
    /******** START : INSPECTOR DETAILS PAGE ********/
    public function inspector_details($enc_inspector_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Inspector Master";    
      $data['page_title'] = 'IIBF - BCBF Admin Inspector Details';

      $data['enc_inspector_id'] = $enc_inspector_id;
      $inspector_id = url_decode($enc_inspector_id);
      
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.inspector_id'=>$inspector_id, 'im.is_deleted' => '0'), "*, IF(im.batch_online_offline_flag=1, 'Offline', 'Online') AS DispType, IF(im.is_active=0, 'Inactive', 'Active') AS DispStatus, (SELECT GROUP_CONCAT(sm.state_name SEPARATOR ', ') FROM state_master sm WHERE FIND_IN_SET(sm.state_code,im.state_codes)) AS AssignedStates, (SELECT GROUP_CONCAT(cm.city_name SEPARATOR ', ') FROM iibfbcbf_inspector_centres imc INNER JOIN city_master cm ON cm.id = imc.city WHERE imc.inspector_id = im.inspector_id) AS AssignedCities");
            
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/inspector_master')); }

      $this->load->view('iibfbcbf/admin/inspector_details_admin', $data);
    }/******** END : INSPECTOR DETAILS PAGE ********/

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
 } ?>  