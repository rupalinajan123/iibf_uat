<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Kyc_log extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('ncvet/Ncvet_model');
      $this->load->model('ncvet/Kyc_model');
      $this->load->helper('ncvet/ncvet_helper'); 

      $this->login_admin_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE');
      $this->Kyc_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
    }
    
    public function index()
		{   
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

			$data['act_id'] = "kyc_log";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'NCVET - KYC '.$dispName['disp_sidebar_name'].' Logs';
      $data['dispName'] = $dispName;
      
      $this->load->view('ncvet/kyc/kyc_logs_common', $data);
    }

    /******** START : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
    function get_kyc_log_data_ajax()
    {
      $table = 'ncvet_kyc_log_data ld';
      
      $column_order = array('ld.log_id', 'ld.regnumber', 'ld.description', 'ld.created_on'); //, 'ld.training_id', 'ld.exam_code' //SET COLUMNS FOR SORT
      
      $column_search = array('ld.regnumber', 'ld.description', 'ld.created_on'); //, 'ld.training_id', 'ld.exam_code' //SET COLUMN FOR SEARCH
      $order = array('ld.log_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE ld.login_type = '".$this->login_user_type."' AND ld.login_id = '".$this->login_admin_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE ld.login_type = '".$this->login_user_type."' AND ld.login_id = '".$this->login_admin_id."' ";      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      //$module_name = 'no_module';
      $module_name = 'ncvet';
      $s_module_type = trim($this->security->xss_clean($this->input->post('s_module_type')));
      if($s_module_type != "") { $module_name = $s_module_type; } 
      $Where .= " AND ld.module_name = '".$module_name."'"; 

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = "";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        /*$row[] = $Res['training_id'];*/
        $row[] = $Res['regnumber'];
        /*$row[] = $Res['exam_code'];*/
        $row[] = $Res['description'];
        $row[] = date("d M Y  h:i A",strtotime($Res['created_on']));
        
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
	
  }	    