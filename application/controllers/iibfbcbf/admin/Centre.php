<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Centre Master
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Centre extends CI_Controller 
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
        $this->session->set_flashdata('error','You do not have permission to access Centre module');
        redirect(site_url('iibfbcbf/admin/dashboard_admin'));
      }
		}
    
    public function index()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Centre Master";
      $data['page_title'] = 'IIBF - BCBF Centre Master';

      $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, am.is_active', array('agency_name'=>'ASC'));

      $this->load->view('iibfbcbf/admin/centre_master_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CENTER DATA ********/
    public function get_centre_data_ajax()
    {
      $table = 'iibfbcbf_centre_master fm';
      
      $column_order = array('fm.centre_id', 'CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")") AS agency_name', 'CONCAT(fm.centre_name, " (", fm.centre_username, ")") AS centre_name', 'DATE(fm.created_on) AS CreatedOn', 'cm.city_name', 'sm.state_name', 'fm.centre_contact_person_name', 'fm.centre_contact_person_mobile', 'fm.centre_username', 'fm.centre_password', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted"))) AS DispStatus', 'fm.status'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")")', 'CONCAT(fm.centre_name, " (", fm.centre_username, ")")', 'DATE(fm.created_on)', 'cm.city_name', 'sm.state_name', 'fm.centre_contact_person_name', 'fm.centre_contact_person_mobile', 'fm.centre_username', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted")))'); //SET COLUMN FOR SEARCH
      $order = array('fm.centre_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.is_deleted = 0   ";
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
      if($s_agency != "") { $Where .= " AND fm.agency_id = '".$s_agency."'"; } 

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
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = " LEFT JOIN state_master sm ON sm.state_code = fm.centre_state";
      $join_qry .= " LEFT JOIN city_master cm ON cm.id = fm.centre_city";
      $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = fm.agency_id";
            
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

        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/centre/centre_details/'.url_encode($Res['centre_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
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

  	/******** START : CENTER DETAILS PAGE ********/
    public function centre_details($enc_centre_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Centre Master";      
      $data['page_title'] = 'IIBF - BCBF Centre Details';

      $data['enc_centre_id'] = $enc_centre_id;
      $centre_id = url_decode($enc_centre_id);

      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master cmm', 'cmm.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am1', 'am1.agency_id = cm.agency_id', 'LEFT');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $centre_id, 'cm.is_deleted' => '0'), "cm.*, IF(cm.centre_type=1, 'Regular', IF(cm.centre_type=2, 'Temporary', '')) AS DispCentreType, IF(cm.status=0, 'Inactive', IF(cm.status=1, 'Active', IF(cm.status=2, 'In Review', 'Re-submitted'))) AS DispStatus, sm.state_name, cmm.city_name, am1.agency_name, am1.agency_code, am1.allow_exam_types");
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/centre')); }  
      
      //$data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('module_slug' => 'centre_action', 'pk_id' => $centre_id), 'log_id, module_slug, description, created_on', array('created_on'=>'ASC'));

      $this->load->view('iibfbcbf/admin/centre_details_admin', $data);
    }/******** END : CENTER DETAILS PAGE ********/

    /******** START : CENTER STATUS CHANGE ********/ 
    public function change_centre_status() 
    {      
      $flag = "error";
      $response = '';
      if(isset($_POST) && $_POST['enc_id'] != "")
      { 
        $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
        $status = $this->security->xss_clean($this->input->post('status')); 
        $status_num_val = $this->security->xss_clean($this->input->post('status_num_val')); 
        $return_status = '';
        $id = url_decode($enc_id);        
        $this->db->where("centre_id",$id);
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0'), 'centre_id,status');
        
        if(count($centre_data) > 0)
        {
          if($centre_data[0]['status'] == $status_num_val)
          {
            $return_status = $centre_data[0]['status'];
            if($status == 'Deactivate')
            { 
              $return_status = 0;
              $update_data["status"] = 0;
              $response = 'The centre has been successfully Deactivated.'; 
              $this->session->set_flashdata('centre_status_success',$response); 
              $this->Iibf_bcbf_model->insert_common_log('Centre : Deactivated', 'iibfbcbf_centre_master', $this->db->last_query(), $id,'centre_action','The centre has been successfully deactivated by the admin', json_encode($update_data));              
            }
            else if($status == 'Activate')
            { 
              $return_status = 1;
              $update_data["status"] = 1;
              $response = 'The centre has been successfully Activated.';
              $this->session->set_flashdata('centre_status_success',$response);
              $this->Iibf_bcbf_model->insert_common_log('Centre : Activated', 'iibfbcbf_centre_master', $this->db->last_query(), $id,'centre_action','The centre has been successfully activated by the admin.', json_encode($update_data));              
            } 
            $this->db->where("centre_id",$id);
            $this->db->update("iibfbcbf_centre_master",$update_data); 
            $flag = "success";
          } 
          else
          {
            $this->session->set_flashdata('centre_status_error',"The centre status was already changed.");
          }
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $response;
      $result['status'] = $return_status;
      echo json_encode($result);  
    } 
    /******** START : CENTER STATUS CHANGE ********/
   
 } 
?>  