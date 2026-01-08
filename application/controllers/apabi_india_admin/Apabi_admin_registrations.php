<?php 
  /********************************************************************************************************************
  ** Description: Controller for APABI INDIA REGISTRATIONS
  ** Created BY: Sagar Matale On 30-09-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Apabi_admin_registrations extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('Apabi_india_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
      $this->load->helper('file'); 

      $this->login_admin_id = $this->session->userdata('APABI_INDIA_ADMIN_LOGIN_ID');
      $this->Apabi_india_model->check_apabi_session_all_pages(); // If admin session is not started then redirect to logout
		}
    
    public function index()
    {   
      $data['act_id'] = "Registrations";
      $data['sub_act_id'] = "Registrations";
      $data['page_title'] = 'APABI INDIA Admin - Registrations';

      $this->load->view('apabi_india_admin/apabi_registrations_admin', $data);
    }

    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE REGISTRATION DATA ********/
    public function get_registration_data_ajax()
    {
      $table = 'apabi_india_registrations ar';
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));
      
      $column_order = array('ar.apabi_id', 'og.org_name', 'ar.apabi_india_code',  'CONCAT(ar.salutation, " ", ar.name) AS Dispname', 'ar.designation', 'ar.email', 'ar.mobile', 'ar.created_on', 'ar.salutation', 'ar.name'); //SET COLUMNS FOR SORT

      $column_search = array('ar.apabi_id', 'og.org_name', 'ar.apabi_india_code',  'CONCAT(ar.salutation, " ", ar.name)', 'ar.designation', 'ar.email', 'ar.mobile', 'ar.created_on', 'ar.salutation', 'ar.name'); //SET COLUMN FOR SEARCH
      $order = array('ar.apabi_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE ar.is_deleted = '0' AND ar.is_active = '1' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE ar.is_deleted = '0' AND ar.is_active = '1' ";
      if(isset($_POST['search']['value']) && $_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      } 
      
      if ($form_action == 'export')
      {
        ob_start(); // Start output buffering
        if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace($Where, "", -3);
          $Where .= ')';
        }
      }
      
      //CUSTOM SEARCH
      /* $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
      if($s_agency != "") { $Where .= " AND fm.agency_id = '".$s_agency."'"; } 

      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."' AND DATE(fm.created_on) <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (DATE(fm.created_on) <= '".$s_to_date."')"; } 

      $s_centre_status = trim($this->security->xss_clean($this->input->post('s_centre_status')));
      if($s_centre_status != "") { $Where .= " AND fm.status = '".$s_centre_status."'"; } */
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; 
      if (isset($_POST['length']) && $_POST['length'] != '-1' && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = " LEFT JOIN apabi_organization_master og ON og.org_id = ar.org_id";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        $fileName = "apabi_india_registration_data_" . date('Y-m-d') . ".xls";

        $fields = array('Sr. No.', 'Participant ID', 'Name', 'Organization Name', 'Designation', 'Mobile Number', 'Email id', 'Registration Date'); // Column names 
        
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();

      if(isset($_POST['start'])) { $no = $_POST['start']; } else { $no = 0; }
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();        
        $row[] = $no;
        
        if ($form_action == 'export')
        {
          $row[] = $Res['apabi_india_code'];
          $row[] = $Res['Dispname'];
          $row[] = $Res['org_name'];
          $row[] = $Res['designation'];
          $row[] = $Res['mobile'];
          $row[] = $Res['email'];          
          $row[] = $Res['created_on'];

          //array_walk($row, 'filterData');
          $excelData .= implode("\t", array_values($row)) . "\n";
        }
        else
        {
          $row[] = $Res['org_name'];
          $row[] = $Res['apabi_india_code'];
          $row[] = $Res['Dispname'];
          $row[] = $Res['designation'];
          $row[] = $Res['email'];
          $row[] = $Res['mobile'];
          $row[] = date("d/m/Y h:iA", strtotime($Res['created_on']));                      
                  
          $btn_str = ' <div class="text-center no_wrap"> ';

          $btn_str .= ' <a href="'.site_url('apabi_india_admin/apabi_admin_registrations/registrations_details/'.url_encode($Res['apabi_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
          
          $btn_str .= ' </div>';
          $row[] = $btn_str;
        }
        
        $data[] = $row; 
      }     
      
      if ($form_action == 'export')
      {        
        if (count($Rows) == '0')
        {
          //$excelData .= 'No records found...' . "\n";
        }

        // Headers for download 
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        // Render excel data 
        $excelData = html_entity_decode($excelData);
        echo $excelData;

        ob_end_flush(); // Flush the output buffer and send headers
        exit;
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE REGISTRATION DATA ********/

  	/******** START : REGISTRATION DETAILS PAGE ********/
    public function registrations_details($enc_apabi_id=0)
    {   
      $data['act_id'] = "Registrations";
      $data['sub_act_id'] = "Registrations";
      $data['page_title'] = 'APABI Admin - Registration Details';

      $data['enc_apabi_id'] = $enc_apabi_id;
      $apabi_id = url_decode($enc_apabi_id);

      $this->db->join('apabi_organization_master og', 'og.org_id = ar.org_id', 'LEFT');
      
      $data['form_data'] = $form_data = $this->master_model->getRecords('apabi_india_registrations ar', array('ar.apabi_id' => $apabi_id, 'ar.is_deleted' => '0', 'ar.is_active' => '1'), "ar.*, og.org_name");
      if(count($form_data) == 0) { redirect(site_url('apabi_india_admin/apabi_admin_registrations')); }  
      

      $this->load->view('apabi_india_admin/apabi_registrations_details_admin', $data);
    }/******** END : REGISTRATION DETAILS PAGE ********/   
 }