<?php 
  /********************************************************************************************************************
  ** Description: Controller for SUPERVISION Candidate DASHBOARD
  ** Created BY: Priyanka Dhikale 22-may-24
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard_candidate extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('supervision_model');
      $this->load->helper('supervision_helper'); 
      
      $this->login_candidate_or_centre_id = $this->session->userdata('SUPERVISION_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('SUPERVISION_USER_TYPE');
      
      if($this->login_user_type != 'candidate') { $this->login_user_type = 'invalid'; }
			$this->supervision_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
    }
        
    public function index()
		{   
			$data['act_id'] = "Dashboard";
			$data['sub_act_id'] = "";

      if($this->login_user_type == "candidate") 
      { 
        $data['page_title'] = 'IIBF - Supervision Candidate Dashboard'; 
        
        $this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
        
        $this->db->where('am.id',$this->login_candidate_or_centre_id);
        $data['form_data'] = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS CandidateStatus, sm.pdc_zone_name");
   
       redirect(site_url('supervision/candidate/dashboard_candidate/session_forms'));
      }
      

      $this->load->view('supervision/candidate/dashboard_candidate', $data);
    }

    function session_forms() {
      $data = array();
      $data['act_id'] = "Forms";
      $this->load->view('supervision/candidate/session_forms_candidate', $data);
    }
    function get_session_form_data_ajax() {
      $table = 'supervision_session_forms am';
 
      
      $column_order = array('am.id',  'am.exam_code', 'am.venue_name',  'am.exam_date', 'am.no_of_session', 'am.center_name', 'am.total_amount','am.created_on', 'am.pay_status', 'cm.candidate_name', 'em.exam_name', 'pay_status', 'am.is_active', 'am.downloaded_file','am.uploaded_file','am.exam_period','am.candidate_id', 'am.venue_code'); //SET COLUMNS FOR SORT

      $column_search = array( 'am.exam_code', 'am.venue_code', 'am.venue_name',  'am.exam_date',  'am.center_name','am.total_amount', 'am.pay_status', 'cm.candidate_name', 'em.exam_name','pay_status',  'cm.candidate_code'); //SET COLUMN FOR SEARCH
      $order = array('am.id' => 'DESC'); // DEFAULT ORDER

      $order = array('am.id' => 'DESC'); // DEFAULT ORDER
      
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

      $paid_status = trim($this->security->xss_clean($this->input->post('paid_status')));
      if($paid_status != "") { $Where .= " AND am.pay_status = '".$paid_status."'"; }

      $Where .= " AND am.candidate_id = '".$this->login_candidate_or_centre_id."'";
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN supervision_candidates cm ON cm.id=am.id  LEFT JOIN supervision_exam_activation em ON em.exam_code=am.exam_code";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where group by am.id $Order $Limit "; //ACTUAL QUERY
      
   
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();

    
      
      $TotalResult = $this->supervision_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->supervision_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $PaidStatus='Rejected';
        if($Res['pay_status']==0) $PaidStatus='Under Process';
        if($Res['pay_status']==1) $PaidStatus='Processed';
        $row[] = $no;
        $row[] = $Res['exam_name'];
        $row[] = $Res['venue_name'];
        $row[] = $Res['exam_date'];
        $row[] = $Res['no_of_session'];
        $row[] = $Res['center_name'];
        $row[] = 'Rs. '.$Res['total_amount'];
        $row[] = date('Y-M-d',strtotime($Res['created_on']));
        
        $row[] = '<span class="badge '.show_faculty_status($Res['pay_status']).'" style="min-width:90px;">'.$PaidStatus.'</span>';
        
        $claim_id = 0;
        $claim_data = $form_data = $this->master_model->getRecords('supervision_claims am', array('am.session_form_id' => $Res['id'], 'am.is_deleted' => '0'), "am.id");        
        if(count($claim_data)>0) {
          $claim_id = url_encode($claim_data[0]['id']);
        }
        $btn_str = ' <div class="text-center_name no_wrap"> ';
        
        
        $btn_str .= '<a href="'.site_url('supervision/candidate/dashboard_candidate/save_session_form/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';

        $btn_str .= '<a href="'.site_url('supervision/candidate/dashboard_candidate/download_form_pdf/?pdf_file='.($Res['downloaded_file'])).'" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a> ';
        
        if($Res['pay_status']==0) {

          $btn_str .= '<a href="'.site_url('supervision/candidate/dashboard_candidate/save_claim_form/='.url_encode($Res['id'])).'/'.($claim_id).'" class="btn btn-success btn-xs" title="Claim"><i  aria-hidden="true" class="fa fa-inr"></i></a> ';

          if(count($claim_data)<=0) 
          {
            $btn_str .= '<a onclick="return confirm(\'Are you sure you want delete session form?\');" href="'.site_url('supervision/candidate/dashboard_candidate/delete_session_form/='.url_encode($Res['id'])).'/'.($claim_id).'" class="btn btn-danger btn-xs" title="Delete"><i  aria-hidden="true" class="fa fa-trash"></i></a> ';

          }
          
        }
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
    

    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->supervision_model->fun_restrict_input($str, $type); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_restrict_input', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

    function claims() {
      $data = array();
      $data['act_id'] = "Claims";
      $this->load->view('supervision/candidate/claims_candidate', $data);
    }
    function get_claim_data_ajax() {
      $table = 'supervision_claims cm';
      
    
      $column_order = array('am.id',  'em.exam_name', 'am.venue_name',  'am.exam_date', 'am.no_of_session', 'am.total_amount', 'am.pay_status', 'am.is_active', 'cm.uploaded_file','cm.id as claim_id,cm.is_paid', 'am.id as session_form_id', 'am.venue_code',  'am.exam_code', 'cm.downloaded_file'); //SET COLUMNS FOR SORT

      $column_search = array( 'am.exam_code', 'am.venue_code', 'am.venue_name',  'am.exam_date', 'am.total_amount', 'em.exam_name'); //SET COLUMN FOR SEARCH
      $order = array('am.id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE am.is_deleted = 0 and cm.is_deleted=0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE am.is_deleted = 0  and cm.is_deleted=0	";
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
        $Where .= " AND (DATE(cm.created_on) >= '".$s_from_date."' AND DATE(cm.created_on) <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (DATE(cm.created_on) >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (DATE(cm.created_on) <= '".$s_to_date."')"; } 

      $paid_status = trim($this->security->xss_clean($this->input->post('paid_status')));
      if($paid_status != "") { $Where .= " AND cm.is_paid = '".$paid_status."'"; }

      $Where .= " AND am.candidate_id = '".$this->login_candidate_or_centre_id."'";
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = "LEFT JOIN supervision_session_forms am ON am.id=cm.session_form_id  LEFT JOIN supervision_exam_activation em ON em.exam_code=am.exam_code ";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where group by am.id $Order $Limit "; //ACTUAL QUERY
      
   
     //  echo $print_query;exit;
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();

      //echo $this->db->last_query();exit;
      
      $TotalResult = $this->supervision_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->supervision_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $PaidStatus='Rejected';
        if($Res['is_paid']==0) $PaidStatus='Under Process';
        if($Res['is_paid']==1) $PaidStatus='Processed';
        $row[] = $no;
        $row[] = $Res['exam_name'];
        $row[] = $Res['venue_name'];
        $row[] = $Res['exam_date'];
        $row[] = $Res['no_of_session'];
        $row[] = 'Rs. '.$Res['total_amount'];
        
        
        $row[] = '<span class="badge '.show_faculty_status($Res['is_paid']).'" style="min-width:90px;">'.$PaidStatus.'</span>';
        
       
        $btn_str = ' <div class="text-center_name no_wrap"> ';
        
        
        $btn_str .= '<a href="'.site_url('supervision/candidate/dashboard_candidate/save_claim_form/'.url_encode($Res['session_form_id']).'/'.url_encode($Res['claim_id'])).'" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';

        $btn_str .= '<a href="'.site_url('supervision/candidate/dashboard_candidate/download_form_pdf/?pdf_file='.($Res['downloaded_file'])).'" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a> ';

        if($Res['pay_status']==0) {
          $btn_str .= '<a onclick="return confirm(\'Are you sure you want delete claim request?\');" href="'.site_url('supervision/candidate/dashboard_candidate/delete_claim_form/='.url_encode($Res['id'])).'/'.url_encode($Res['claim_id']).'" class="btn btn-danger btn-xs" title="Delete"><i  aria-hidden="true" class="fa fa-trash"></i></a> ';

        }
      
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
    
    function delete_claim_form($enc_form_id=0,$enc_claim_id=0) {
      $data = array();
      $form_id = url_decode($enc_form_id);
      $claim_id = url_decode($enc_claim_id);
      $this->db->where('is_paid=',1);
      $data['claim_data'] = $claim_data = $this->master_model->getRecords('supervision_claims am', array( 'am.id' => $claim_id,'am.session_form_id' => $form_id,'am.is_deleted' => '0'), "am.*");        

      if(count($claim_data)>0 )  {
        $this->session->set_flashdata('error','PDC already reviewed this claim request');
        redirect(site_url('/supervision/candidate/dashboard_candidate/claims')); 
      }
     
      $add_data['is_deleted'] = 1;
            
      $this->master_model->updateRecord('supervision_claims', $add_data, array('id'=>$claim_id));
                    
      $this->supervision_model->insert_common_log('candidate : claim form Deleted', 'supervision_claims', $this->db->last_query(), $claim_id,'delete_session_form','The claim form has successfully deleted by the candidate '.$this->login_candidate_or_centre_id, $posted_arr);

      $this->session->set_flashdata('success','claim form details are deleted successfully');
      redirect(site_url('/supervision/candidate/dashboard_candidate/claims')); 
            
    }

    function save_claim_form($enc_form_id=0,$enc_claim_id=0) {
      $data = array();
      $data['act_id'] = "Claims";
      $data['enc_form_id'] = $enc_form_id;
      $data['enc_claim_id'] = $enc_claim_id;
       //START : FIND OUT THE CURRENT MODE AND GET THE claim DATA
       if($enc_claim_id == '0') 
       { 
         $data['mode'] = $mode = "Add"; $form_id = url_decode($enc_form_id);; $claim_id = $enc_claim_id;

         $data['claim_data'] = $claim_data = $this->master_model->getRecords('supervision_claims am', array('am.session_form_id' => $form_id, 'am.is_deleted' => '0'), "am.*");        
         if(count($claim_data) >0) { 
         
          redirect(site_url('/supervision/candidate/dashboard_candidate/claims')); 
        } 
       }
       else
       {
        $claim_id = url_decode($enc_claim_id);
         $form_id = url_decode($enc_form_id);

         
        $data['claim_data'] = $claim_data = $this->master_model->getRecords('supervision_claims am', array('am.id' => $claim_id, 'am.session_form_id' => $form_id, 'am.is_deleted' => '0'), "am.*");        
         if(count($claim_data) == 0) { 
         
          redirect(site_url('/supervision/candidate/dashboard_candidate/claims')); 
        } 
        else if($claim_data[0]['is_deleted']==1) 
          redirect(site_url('/supervision/candidate/dashboard_candidate/claims')); 
         
         $data['mode'] = $mode = "Update";
       }//END 

       $this->db->join('supervision_exam_activation sm', 'sm.exam_code = am.exam_code', 'LEFT');
         $data['form_data'] = $form_data = $this->master_model->getRecords('supervision_session_forms am', array('am.id' => $form_id, 'am.is_deleted' => '0'), "am.*,sm.exam_name");        
         if(count($form_data) == 0) { 
          $this->session->set_flashdata('error','Supervision Report is not exist');
          redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
        } 
        else if($form_data[0]['is_deleted']==1)  {
          $this->session->set_flashdata('error','Supervision Report is not exist');
          redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
        }
          
        
          else if($form_data[0]['uploaded_file']=='' || $form_data[0]['downloaded_file']=='')  {
            $this->session->set_flashdata('error','Please upload signed PDF for session form');
            redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
          }
          else if($form_data[0]['is_active']!=1)  {
            $this->session->set_flashdata('error','Supervision Report is under review');
            redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
          }
           
         

          
        $data['sessions'] = explode(',',$form_data[0]['exam_time']);
        $data['session_wise_amount'] = explode(',',$form_data[0]['session_wise_amount']);
        
       $data['form_id'] =$form_id;
       $data['claim_id'] =$claim_id;
       $data['page_title'] = 'IIBF - Supervision Claim';

       $this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
        
        $this->db->where('am.id',$this->login_candidate_or_centre_id);
        $data['candidate_data'] = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS CandidateStatus, sm.pdc_zone_name");

        $this->db->where('exam_code',$data['candidate_data'][0]['exam_code']);
        $data['exams'] = $this->master_model->getRecords('supervision_exam_activation', array('is_deleted' => '0'));


       if(isset($_POST) && count($_POST) > 0)
      {
          if(count($form_data)>0 &&  $form_data[0]['pay_status']==1)  {
            $this->session->set_flashdata('error','PDC already reviewed Supervision Report');
            redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
          }
          
           $this->form_validation->set_rules('beneficiary_name', 'Beneficiary name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[254]|xss_clean', array('required'=>"Please enter the %s"));     
          $this->form_validation->set_rules('account_no', 'Account no', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|max_length[20]|xss_clean', array('required'=>"Please select the %s"));     
          $this->form_validation->set_rules('bank_branch_name', 'Bank & branch name', 'trim|required|max_length[254]|xss_clean', array('required'=>"Please select the %s"));  
          $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required|max_length[20]|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|xss_clean', array('required'=>"Please select the %s"));  
          $this->form_validation->set_rules('email', 'email id', 'trim|required|max_length[160]|valid_email|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('mobile', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('pan_card', 'Pan card', 'trim|required|max_length[20]|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|xss_clean', array('required'=>"Please select the %s"));  
          if($mode=='Add')
          $this->form_validation->set_rules('pan_card_doc', 'Pan card', 'file_required|required|file_allowed_type[jpeg,jpg,pdf,png]|file_size_max[300]');

          if(isset($_POST['uploaded_file']) && $_POST['uploaded_file']!='') {
            $this->form_validation->set_rules('uploaded_file', 'Signed PDF', 'file_required|file_allowed_type[pdf]|file_size_max[300]');
          }
          if(isset($_POST['canceled_cheque']) && $_POST['canceled_cheque']!='') {
            $this->form_validation->set_rules('canceled_cheque', 'Cancel Cheque', 'file_required|file_allowed_type[jpeg,jpg,pdf,png]|file_size_max[300]');
          }

          if($this->form_validation->run())
          {     
            $posted_arr = json_encode($_POST);

            $outputfile = $pdf_file=$pan_card_doc=$canceled_cheque='';
            $date = date('ymdhis');
            if (isset($_FILES['uploaded_file']['name']) && ($_FILES['uploaded_file']['name'] != '')) {
                $img              = "uploaded_file";
                $tmp_inputidproof = strtotime($date) ;
                $new_filename     = 'up_supervision_claimrequest_'.$this->login_candidate_or_centre_id.$date;
                $config           = array('upload_path' => './uploads/supervision',
                    'allowed_types'                         => 'pdf',
                    'file_name'                             => $new_filename);

                $this->upload->initialize($config);
                
                    if ($this->upload->do_upload($img)) {
                        $dt             = $this->upload->data();
                        $pdf_file   = $dt['file_name'];
                        $outputfile = base_url() . "uploads/supervision/" . $pdf_file;
                    } else {
                        $var_errors .= $this->upload->display_errors();
                        
                        $this->session->set_flashdata('error','Signed document not getting uploaded');
                      redirect(site_url('supervision/candidate/dashboard_candidate/save_claim_form/'.$enc_form_id.'/'.$enc_claim_id));  
                    }
                

            }

            if (isset($_FILES['pan_card_doc']['name']) && ($_FILES['pan_card_doc']['name'] != '')) {
         
              $img              = "pan_card_doc";
              $tmp_inputidproof = strtotime($date) ;
              $new_filename     = 'pan_card_'.$this->login_candidate_or_centre_id.$date;
              $config           = array('upload_path' => './uploads/supervision',
                  'allowed_types'                         => 'jpg|jpeg|png|pdf',
                  'file_name'                             => $new_filename);

              $this->upload->initialize($config);
              
                  if ($this->upload->do_upload($img)) {
                      $dt             = $this->upload->data();
                      $pan_card_doc   = $dt['file_name'];
                      $outputfile = base_url() . "uploads/supervision/" . $pan_card_doc;
                     
                  } else {
                      $var_errors = $this->upload->display_errors();
                      
                      $this->session->set_flashdata('error','Pan card document not getting uploaded');
                      redirect(site_url('supervision/candidate/dashboard_candidate/save_claim_form/'.$enc_form_id.'/'.$enc_claim_id));   
                  }
              

          }
          if (isset($_FILES['canceled_cheque']['name']) && ($_FILES['canceled_cheque']['name'] != '')) {
            
                 $img              = "canceled_cheque";
                 $tmp_inputidproof = strtotime($date) ;
                 $new_filename     = 'cheque_'.$this->login_candidate_or_centre_id.$date;
                 $config           = array('upload_path' => './uploads/supervision',
                     'allowed_types'                         => 'jpg|jpeg|png|pdf',
                     'file_name'                             => $new_filename);
   
                 $this->upload->initialize($config);
                 
                     if ($this->upload->do_upload($img)) {
                         $dt             = $this->upload->data();
                         $canceled_cheque   = $dt['file_name'];
                         $outputfile = base_url() . "uploads/supervision/" . $canceled_cheque;
                        
                     } else {
                         $var_errors = $this->upload->display_errors();
                         
                         $this->session->set_flashdata('error','Cancel cheque not getting uploaded');
                         redirect(site_url('supervision/candidate/dashboard_candidate/save_claim_form/'.$enc_form_id.'/'.$enc_claim_id));   
                     }
                 
   
            }


            if($canceled_cheque!='') {
              $add_data['canceled_cheque'] = $canceled_cheque;
            }
            if($pdf_file!='') {
              $add_data['uploaded_file'] = $pdf_file;
            }
            if($pan_card_doc!='') {
              $add_data['pan_card_doc'] = $pan_card_doc;
            }
            $add_data['candidate_id'] = $this->login_candidate_or_centre_id;
            $add_data['session_form_id'] = $form_id;
            $add_data['total_amount'] = $form_data[0]['total_amount'];
            $add_data['beneficiary_name'] = $this->input->post('beneficiary_name');
            $add_data['account_no'] = $this->input->post('account_no');
            $add_data['bank_branch_name'] = $this->input->post('bank_branch_name');
            $add_data['ifsc_code'] = $this->input->post('ifsc_code');
            $add_data['email'] = $this->input->post('email');
            $add_data['mobile'] = $this->input->post('mobile');
            $add_data['pan_card'] =strtoupper($this->input->post('pan_card'));
            $add_data['is_paid'] = 0;
            $add_data['ip_address'] = get_ip_address(); //general_helper.php   


            $pdfFilePath = "dwn_supervision_claimrequest_".$this->login_candidate_or_centre_id.$form_id.date('ymd',strtotime($this->input->post('exam_date'))).".pdf";

            $add_data['downloaded_file'] = $pdfFilePath;
            
            //
            if($mode == "Add") 
            {
              
              $add_data['created_by'] = $this->login_candidate_or_centre_id;     
              $this->master_model->insertRecord('supervision_claims',$add_data);
              
              $claim_id = $this->db->insert_id();
              $this->supervision_model->insert_common_log('Candidate : claim form added', 'supervision_claims', $this->db->last_query(), 0,'save_claim_form','The claim form has successfully added by the candidate '.$this->login_candidate_or_centre_id, $posted_arr);

              $add_data1['claim_id'] = 'CLM-'.$form_id.'-'.$claim_id;            
              $this->master_model->updateRecord('supervision_claims', $add_data1, array('id'=>$claim_id));
            }
            else if($mode == "Update")
            {
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_candidate_or_centre_id;            
              $this->master_model->updateRecord('supervision_claims', $add_data, array('id'=>$claim_id));
              
              $this->supervision_model->insert_common_log('Candidate : claim form Updated', 'save_claim_form', $this->db->last_query(), $claim_id,'save_claim_form','The claim form has successfully updated by the candidate '.$this->login_candidate_or_centre_id, $posted_arr);
            }

            $sadd_data['pay_status'] = 0;            
            $this->master_model->updateRecord('supervision_session_forms', $sadd_data, array('id'=>$form_id));


           
            //generate PDF
            $this->db->where('am.id',$claim_id);
            $data['claim_details']= $this->master_model->getRecords('supervision_claims am', array('am.is_deleted' => '0'), "am.*");

            $html=$this->load->view('supervision/candidate/claim_form_download', $data, true);
            $this->load->library('m_pdf');
            $pdf =$this->m_pdf->load();
            
            $pdf->WriteHTML($html);
            $path = $pdf->Output('uploads/supervision/'.$pdfFilePath, "F"); 
          
            $success_message = 'Claim form details saved successfully';

            if($data['claim_details'][0]['uploaded_file']=='') {
              $success_message ="<b>Please download the form by clicking on the download button ( <b style='color:#1c84c6;'>Blue</b> icon) and re-upload the signed copy of the honorarium claim form (PDF) by clicking on the edit button( <b style='color:#f8ac59;'>Yellow</b> icon) for final submission.</b>";
            }
            else if($data['claim_details'][0]['uploaded_file']!='' && $data['claim_details'][0]['is_paid']==0) {
              $success_message = 'Claim form details saved successfully';
            }
            $this->session->set_flashdata('success',$success_message);
            redirect(site_url('supervision/candidate/dashboard_candidate/claims'));   
           

          }
        }

       
      $this->load->view('supervision/candidate/save_claim_form_candidate', $data);
    }

    function unique_session_form() {

      //asked by client -8.	Restrict the observer to submit only 1 supervision report for each exam date.
      $this->db->where('id!=', $_POST['form_id']);
      
      $form_data = $this->master_model->getRecords('supervision_session_forms am', array('am.exam_date' => $_POST['exam_date'], 'am.candidate_id' => $this->login_candidate_or_centre_id, 'am.is_deleted' => '0'), "am.*");   

      if(count($form_data)>0) {

        $this->form_validation->set_message('unique_session_form', 'Supervision Report already exist for Date');
          return false;
      }
      //asked by client -8.	Restrict the observer to submit only 1 supervision report for each exam date.

      $this->db->where('id!=', $_POST['form_id']);
      $form_data = $this->master_model->getRecords('supervision_session_forms am', array('am.exam_date' => $_POST['exam_date'], 'am.venue_code' => $_POST['venue_code'], 'am.candidate_id' => $this->login_candidate_or_centre_id, 'am.is_deleted' => '0'), "am.*");   

      if(count($form_data)>0) {

        $this->form_validation->set_message('unique_session_form', 'Supervision Report already exist for Date & Venue');
          return false;
      }
      if(!empty($_POST['exam_time'])) {
        foreach($_POST['exam_time'] as $exam_time) {

          $this->db->where('id!=', $_POST['form_id']);
          $this->db->where('venue_code!=', $_POST['venue_code']);
          $wherecondition= "(exam_time LIKE '%$exam_time%')";
				  $this->db->where($wherecondition);
          $form_data = $this->master_model->getRecords('supervision_session_forms am', array('am.exam_date' => $_POST['exam_date'], 'am.candidate_id' => $this->login_candidate_or_centre_id, 'am.is_deleted' => '0'), "am.*");   
    
          //echo $this->db->last_query();exit;
          if(count($form_data)>0) {
    
            $this->form_validation->set_message('unique_session_form', 'Supervision Report already exist for Date & Time for Diff Venue');
              return false;
          }
        }
      }
      return true;
    }
    
    function delete_session_form($enc_form_id=0) {
      $data = array();
      $form_id = url_decode($enc_form_id);
      $data['form_data'] = $form_data = $this->master_model->getRecords('supervision_session_forms am', array('am.id' => $form_id, 'am.is_deleted' => '0'), "am.*"); 

      if(count($form_data)>0 &&  $form_data[0]['pay_status']==1)  {
        $this->session->set_flashdata('error','PDC already reviewed Supervision Report');
        redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
      }
      $data['claim_data'] = $claim_data = $this->master_model->getRecords('supervision_claims am', array( 'am.session_form_id' => $form_id, 'am.is_deleted' => '0'), "am.*");        

      if(count($claim_data)>0 )  {
        $this->session->set_flashdata('error','Claim details are added for this Supervision Report');
        redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
      }
      $add_data['deleted_on'] = date("Y-m-d H:i:s");
      $add_data['is_deleted'] = 1;
      $add_data['deleted_by'] = $this->login_candidate_or_centre_id;            
      $this->master_model->updateRecord('supervision_session_forms', $add_data, array('id'=>$form_id));
                    
      $this->supervision_model->insert_common_log('candidate : Supervision Report Deleted', 'supervision_session_forms', $this->db->last_query(), $form_id,'delete_session_form','The session form has successfully deleted by the candidate '.$this->login_candidate_or_centre_id, $posted_arr);

      $this->session->set_flashdata('success','Supervision Report details are deleted successfully');
      redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
            
    }
    function save_session_form($enc_form_id=0) {
      $data = array();
      $data['act_id'] = "Forms";
      $data['enc_form_id'] = $enc_form_id;
       //START : FIND OUT THE CURRENT MODE AND GET THE session form DATA
       if($enc_form_id == '0') 
       { 
         $data['mode'] = $mode = "Add"; $form_id = $enc_form_id;
       }
       else
       {
         $form_id = url_decode($enc_form_id);
         
         $data['form_data'] = $form_data = $this->master_model->getRecords('supervision_session_forms am', array('am.id' => $form_id, 'am.is_deleted' => '0'), "am.*");        
         if(count($form_data) == 0) { 
          
          redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
        } 
        else if($form_data[0]['is_deleted']==1) 
          redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
         
        if(count($form_data)>0) {
          $candidate_appeared = explode(',',$form_data[0]['candidate_appeared']);
          if(isset($candidate_appeared[0]))
            $data['form_data'][0]['candidate_appeared1']=$candidate_appeared[0];
          if(isset($candidate_appeared[1]))
            $data['form_data'][0]['candidate_appeared2']=$candidate_appeared[1];
          if(isset($candidate_appeared[2]))
            $data['form_data'][0]['candidate_appeared3']=$candidate_appeared[2];
        }
         $data['mode'] = $mode = "Update";
       }//END : 
       $data['form_id'] =$form_id;
       $data['page_title'] = 'IIBF - Supervision Supervision Report';

       $this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
        
        $this->db->where('am.id',$this->login_candidate_or_centre_id);
        $data['candidate_data'] = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS CandidateStatus, sm.pdc_zone_name");

        //$exam_code_period = explode('-',$data['candidate_data'][0]['exam_code']);
        $this->db->where('exam_code',$data['candidate_data'][0]['exam_code']);
        $this->db->where('exam_period',$data['candidate_data'][0]['exam_period']);
        $data['exams'] = $this->master_model->getRecords('supervision_exam_activation', array('is_deleted' => '0'));


       if(isset($_POST) && count($_POST) > 0)
      {
       
        if($form_id !=0 && count($form_data)>0 &&  $form_data[0]['pay_status']==1) { 
          $this->session->set_flashdata('error','PDC already reviewed this Supervision Report');
          redirect(site_url('/supervision/candidate/dashboard_candidate/session_forms')); 
        }
           

          $this->form_validation->set_rules('exam_code_period', 'Exam', 'trim|required|max_length[10]|xss_clean', array('required'=>"Please select the %s"));     
          $this->form_validation->set_rules('venue_code', 'Venue', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  
          $this->form_validation->set_rules('exam_date', 'Exam date', 'trim|required|callback_unique_session_form|max_length[20]|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('exam_time[]', 'Exam time', 'trim|required|max_length[100]|xss_clean', array('required'=>"Please select the %s"));
          $this->form_validation->set_rules('no_of_pc', 'No. of PC', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|max_length[4]|xss_clean');

          $this->form_validation->set_rules('suitable_venue_loc', 'Location of venue whether suitable and convenient', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['suitable_venue_loc']=='No') {
            $this->form_validation->set_rules('suitable_venue_loc_reason', 'Location of venue whether suitable and convenient - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('venue_open_bef_exam', 'Whether venue was opened before the examination time ', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['venue_open_bef_exam']=='No') {
            $this->form_validation->set_rules('venue_open_bef_exam_reason', 'Whether venue was opened before the examination time  - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('venue_reserved', 'Whether the venue was exclusively reserved for IIBF ', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['venue_reserved']=='No') {
            $this->form_validation->set_rules('venue_reserved_reason', 'Whether the venue was exclusively reserved for IIBF   - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('venue_power_problem', 'Was there a power problem in venue', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['venue_power_problem']=='Yes') {
            $this->form_validation->set_rules('venue_power_problem_sol', 'Was there a power problem in venue  - Solution', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('no_of_supervisors', 'Number of test supervisors in the venue', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('registration_process', 'Whether registration process was completed before the examination time', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['registration_process']=='No') {
            $this->form_validation->set_rules('registration_process_reason', 'Whether registration process was completed before the examination time - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('frisking', ' Whether frisking was done before the candidate were allowed to enter in computer lab', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['frisking']=='No') {
            $this->form_validation->set_rules('frisking_reason', ' Whether frisking was done before the candidate were allowed to enter in computer lab - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('frisking_lady', 'Whether lady frisking staff was available for frisking the lady candidates', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['frisking_lady']=='No') {
            $this->form_validation->set_rules('frisking_lady_reason', 'Whether lady frisking staff was available for frisking the lady candidates - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          

          $this->form_validation->set_rules('mobile_allowed', 'Whether mobile phone,text materials etc. were allowed in venue', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['mobile_allowed']=='Yes') {
            $this->form_validation->set_rules('mobile_allowed_reason', 'Whether mobile phone,text materials etc. were allowed in venue - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('admit_letter_checked', 'Whether candidate admit letter was checked and verified before permitting to sit for examination be the supervisors', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['admit_letter_checked']=='No') {
            $this->form_validation->set_rules('admit_letter_checked_reason', 'Whether candidate admit letter was checked and verified before permitting to sit for examination be the supervisors - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('exam_without_admit_letter', 'Whether any candidate were permitted to appear for the examination without proper admit letter and ID card', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['exam_without_admit_letter']=='Yes') {
            $this->form_validation->set_rules('exam_without_admit_letter_detils', 'Whether any candidate were permitted to appear for the examination without proper admit letter and ID card - Details', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('seat_no_written', 'Whether seat numbers were written againts each PC', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['seat_no_written']=='No') {
            $this->form_validation->set_rules('seat_no_written_reason', 'Whether seat numbers were written againts each PC - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('candidate_seated', 'Whether candidates were seated in the seat number mentioned in the admit letter', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['candidate_seated']=='No') {
            $this->form_validation->set_rules('candidate_seated_reason', 'Whether candidates were seated in the seat number mentioned in the admit letter - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('scribe_arrange', 'Whether separate arrangments was made available for PWD(Person with Disabilities) candidates using scribe', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['scribe_arrange']=='No') {
            $this->form_validation->set_rules('scribe_arrange_reason', 'Whether separate arrangments was made available for PWD(Person with Disabilities) candidates using scribe - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('announcement', 'Whether rules of examination are announced to the candidates by the Invigilators', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          $this->form_validation->set_rules('announcement_gap', 'Whether rules of examination are announced to the candidates by the Invigilators - Gap', 'trim|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          
          
          $this->form_validation->set_rules('exam_started', 'Whether examination started as scheduled', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

           $this->form_validation->set_rules('exam_started_reason', 'Whether examination started as scheduled - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          

          if(isset($_POST['exam_time']) && !empty($_POST['exam_time'])) {
            foreach($_POST['exam_time'] as $k=>$ex_time) {

            }
          }
          

          $this->form_validation->set_rules('started_late', 'Whether any candidate were allowed to start the examination after 15 minutes of scheduled examination', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['started_late']=='Yes') {
            $this->form_validation->set_rules('started_late_reason', 'Whether any candidate were allowed to start the examination after 15 minutes of scheduled examination - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('unfair_candidates', 'Was any unfair means was adopted by the candidates during the examination', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['unfair_candidates']=='Yes') {
            $this->form_validation->set_rules('unfair_candidates_reason', 'Was any unfair means was adopted by the candidates during the examination - Details', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('rough_sheet', 'Rough sheet given to candidates were collected back and destroyed.', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['rough_sheet']=='Yes') {
            $this->form_validation->set_rules('rough_sheet_reason', 'Rough sheet given to candidates were collected back and destroyed. - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('action_for_unfair', 'What is the action taken for unfair means adopted by the candidates', 'trim|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('name_mob_exam_contro', 'Name & Mobile No. of Examination Controller - Sify/NSEIT', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('issue_reported', 'Any issue reported/faced by candidates', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('observation', 'Any other observation /Suggestion if any', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('filled_date', 'Date', 'trim|required|max_length[25]|xss_clean', array('required'=>"Please enter the %s"));

          if(isset($_POST['uploaded_file']) && $_POST['uploaded_file']!='') {
            $this->form_validation->set_rules('uploaded_file', 'Signed PDF', 'file_required|file_allowed_type[pdf]|file_size_max[300]');
          }

          if($this->form_validation->run())
          {     
            $posted_arr = json_encode($_POST);

            $exam_code_period = explode('-',$this->input->post('exam_code_period'));
            $outputfile = $pdf_file='';
            $date = date('ymdhis');
            if (isset($_FILES['uploaded_file']['name']) && ($_FILES['uploaded_file']['name'] != '')) {
                $img              = "uploaded_file";
                $tmp_inputidproof = strtotime($date) ;
                $new_filename     = 'up_supervision_'.$this->login_candidate_or_centre_id.date('ymd',strtotime($this->input->post('exam_date'))).$this->input->post('venue_code');
                $config           = array('upload_path' => './uploads/supervision',
                    'allowed_types'                         => 'pdf',
                    'file_name'                             => $new_filename);

                $this->upload->initialize($config);
                
                    if ($this->upload->do_upload($img)) {
                        $dt             = $this->upload->data();
                        $pdf_file   = $dt['file_name'];
                        $outputfile = base_url() . "uploads/supervision/" . $pdf_file;
                    } else {
                        $var_errors .= $this->upload->display_errors();                        
                    }               

            }

            if($data['candidate_data'][0]['role_id']!='' && $data['candidate_data'][0]['role_id']!=0) {
              $role_fee = $this->master_model->getRecords('supervision_role_fee am', array('am.is_deleted' => '0','am.role_id' => $data['candidate_data'][0]['role_id']));
            }
            $this->db->join('supervision_center_master cm', 'cm.center_code  = am.center_code ', 'LEFT');
            $venue_details = $this->master_model->getRecords('supervision_venue_master am', array('am.venue_delete' => '0','am.exam_code' => $exam_code_period[0],'am.exam_period' => $exam_code_period[1],'am.venue_code' => $this->input->post('venue_code'),'am.exam_date' => $this->input->post('exam_date')), "am.*,cm.center_name");

            $total_amount=0;
            $no_of_session=0;$exam_time_val=$session_wise_amount=$candidate_appeared_to_show='';
            foreach($_POST['exam_time'] as $k=>$exam_time) {
              $exam_time_val.= $exam_time.',';
              $no_of_session++;
              if($k==0) {
                $add_amount = $role_fee[0]['B1_fee'];
                $session_wise_amount .= $role_fee[0]['B1_fee'].',';
              }
                
              else {
                $add_amount = $role_fee[0]['s1_fee'];
                $session_wise_amount .= $role_fee[0]['s1_fee'].',';
              }
                
              
              $total_amount += $add_amount;
              
            }
            if($pdf_file!='') {
              $add_data['uploaded_file'] = $pdf_file;
            }
            $candidate_appeared = '';
           
            foreach($_POST['candidate_appeared'] as $cc=>$c) {
              $candidate_appeared .=  $c.',';
            }
            $candidate_appeared_to_show_arr = explode(',',$candidate_appeared);
            $candidate_appeared_to_show_arr = array_values(array_filter($candidate_appeared_to_show_arr));
            foreach($candidate_appeared_to_show_arr as $kk=>$candidate_appeared_to_showw) {
              $candidate_appeared_to_show .=$_POST['exam_time'][$kk].' : '.$candidate_appeared_to_showw.',';
            }
            
            $this->db->where('exam_code',$exam_code_period[0]);
            $selectedExam = $this->master_model->getRecords('supervision_exam_activation', array('is_deleted' => '0'));
            
            $add_data['candidate_id'] = $this->login_candidate_or_centre_id;
            $add_data['exam_code'] = $exam_code_period[0];
            $add_data['exam_period'] = $exam_code_period[1];
            $add_data['venue_code'] = $this->input->post('venue_code');
            $add_data['venue_name'] = $venue_details[0]['venue_name'];
            $add_data['venueadd1'] = $venue_details[0]['venue_addr1'];
            $add_data['venueadd2'] = $venue_details[0]['venue_addr2'];
            $add_data['venueadd3'] = $venue_details[0]['venue_addr3'];
            $add_data['venueadd4'] = $venue_details[0]['venue_addr4'];
            $add_data['venueadd5'] = $venue_details[0]['venue_addr5'];
            $add_data['center_name'] = $venue_details[0]['center_name'];
            $add_data['venpin'] = $venue_details[0]['venue_pincode'];
            $add_data['exam_date'] = date('Y-m-d',strtotime($this->input->post('exam_date')));
            $add_data['session_wise_amount'] = rtrim($session_wise_amount, ',');
            $add_data['exam_time'] = rtrim($exam_time_val, ',');
            $add_data['no_of_session'] = $no_of_session;
            $add_data['candidate_appeared_to_show'] = rtrim($candidate_appeared_to_show, ',');
            $add_data['no_of_pc'] = trim($this->input->post('no_of_pc'));
            $add_data['suitable_venue_loc'] = $this->input->post('suitable_venue_loc');
            $add_data['suitable_venue_loc_reason'] = ucfirst(trim($this->input->post('suitable_venue_loc_reason')));
            $add_data['venue_open_bef_exam'] = $this->input->post('venue_open_bef_exam');
            $add_data['venue_open_bef_exam_reason'] = ucfirst(trim($this->input->post('venue_open_bef_exam_reason')));
            $add_data['venue_reserved'] = $this->input->post('venue_reserved');
            $add_data['venue_reserved_reason'] = ucfirst(trim($this->input->post('venue_reserved_reason')));
            $add_data['venue_power_problem'] = $this->input->post('venue_power_problem');
            $add_data['venue_power_problem_sol'] = ucfirst(trim($this->input->post('venue_power_problem_sol')));
            $add_data['no_of_supervisors'] = $this->input->post('no_of_supervisors');
            $add_data['registration_process'] = (trim($this->input->post('registration_process')));
            $add_data['registration_process_reason'] = ucfirst(trim($this->input->post('registration_process_reason')));
            $add_data['frisking'] = $this->input->post('frisking');
            $add_data['frisking_reason'] = ucfirst(trim($this->input->post('frisking_reason')));
            $add_data['frisking_lady'] = $this->input->post('frisking_lady');
            $add_data['frisking_lady_reason'] = ucfirst(trim($this->input->post('frisking_lady_reason')));
            $add_data['mobile_allowed'] = $this->input->post('mobile_allowed');
            $add_data['mobile_allowed_reason'] = ucfirst(trim($this->input->post('mobile_allowed_reason')));
            $add_data['admit_letter_checked'] = $this->input->post('admit_letter_checked');
            $add_data['admit_letter_checked_reason'] = ucfirst(trim($this->input->post('admit_letter_checked_reason')));
            $add_data['exam_without_admit_letter'] = $this->input->post('exam_without_admit_letter');
            $add_data['exam_without_admit_letter_detils'] = ucfirst(trim($this->input->post('exam_without_admit_letter_detils')));
            $add_data['seat_no_written'] = $this->input->post('seat_no_written');
            $add_data['seat_no_written_reason'] = ucfirst(trim($this->input->post('seat_no_written_reason')));
            $add_data['candidate_seated'] = $this->input->post('candidate_seated');
            $add_data['candidate_seated_reason'] = ucfirst(trim($this->input->post('candidate_seated_reason')));
            $add_data['scribe_arrange'] = $this->input->post('scribe_arrange');
            $add_data['scribe_arrange_reason'] = ucfirst(trim($this->input->post('scribe_arrange_reason')));
            $add_data['announcement'] = $this->input->post('announcement');
            $add_data['announcement_gap'] = ucfirst(trim($this->input->post('announcement_gap')));
            $add_data['exam_started'] = $this->input->post('exam_started');
            $add_data['exam_started_reason'] = ucfirst(trim($this->input->post('exam_started_reason')));
            $add_data['candidate_appeared'] = rtrim($candidate_appeared);
            $add_data['started_late'] = $this->input->post('started_late');
            $add_data['started_late_reason'] = ucfirst(trim($this->input->post('started_late_reason')));
            $add_data['unfair_candidates'] = $this->input->post('unfair_candidates');
            $add_data['unfair_candidates_reason'] = ucfirst(trim($this->input->post('unfair_candidates_reason')));
            $add_data['rough_sheet'] = $this->input->post('rough_sheet');
            $add_data['rough_sheet_reason'] = ucfirst(trim($this->input->post('rough_sheet_reason')));
            $add_data['action_for_unfair'] = ucfirst(trim($this->input->post('action_for_unfair')));
            $add_data['name_mob_exam_contro'] = ucfirst(trim($this->input->post('name_mob_exam_contro')));
            $add_data['issue_reported'] = ucfirst(trim($this->input->post('issue_reported')));
            $add_data['observation'] = ucfirst(trim($this->input->post('observation')));
            $add_data['filled_date'] = date('Y-m-d',strtotime($this->input->post('filled_date')));           
            $add_data['total_amount'] = $total_amount;
            $add_data['ip_address'] = get_ip_address(); //general_helper.php   
            if($this->input->post('immediate_action') && $this->input->post('immediate_action')==1) {
              $add_data['immediate_action'] = 1;
            }

            $pdfFilePath = "dwn_supervision_".$this->login_candidate_or_centre_id.$form_id.date('ymd',strtotime($this->input->post('exam_date'))).".pdf";

            $add_data['downloaded_file'] = $pdfFilePath;
            $add_data['is_active'] = '1'; 
            $add_data['pay_status'] = '0'; 
            if($mode == "Add") 
            {
                
              $add_data['created_by'] = $this->login_candidate_or_centre_id;     
              $this->master_model->insertRecord('supervision_session_forms  ',$add_data);
              $form_id = $this->db->insert_id();
              $this->supervision_model->insert_common_log('candidate : Supervision Report added', 'supervision_session_forms', $this->db->last_query(), 0,'save_session_form','The session form has successfully added by the candidate '.$this->login_candidate_or_centre_id, $posted_arr);

            }
            else if($mode == "Update")
            {
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_candidate_or_centre_id;            
              $this->master_model->updateRecord('supervision_session_forms', $add_data, array('id'=>$form_id));
                 
              $this->supervision_model->insert_common_log('candidate : Supervision Report Updated', 'supervision_session_forms', $this->db->last_query(), $form_id,'save_session_form','The session form has successfully updated by the candidate '.$this->login_candidate_or_centre_id, $posted_arr);
            }

            if($this->input->post('immediate_action') && $this->input->post('immediate_action')==1) {
                
              $emailerstr = $this->master_model->getRecords('emailer', array(
                'emailer_name' => 'supervision_immediate_action',
              ));
                if (count($emailerstr) > 0) {

                  $this->db->where('am.id',$this->login_candidate_or_centre_id);
                  $candidate_data = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0'), "am.*");
                  
                  $venue = $venue_details[0]['venue_name'].' '.$venue_details[0]['venue_addr1'].' '.$venue_details[0]['venue_addr2'].' '.$venue_details[0]['venue_addr3'].' '.$venue_details[0]['venue_addr4'].' '.$venue_details[0]['venue_addr5'].' '.$venue_details[0]['venue_pincode'];
                  $final_str = str_replace("#observation#", "" .$add_data['observation']."", $emailerstr[0]['emailer_text']);
                  $final_str = str_replace("#exam#", "" .$selectedExam[0]['exam_name']. "", $final_str);
                  $final_str = str_replace("#exam_time#", "" .rtrim($exam_time_val, ','). "", $final_str);
                  $final_str = str_replace("#venue#", "" .$venue. "", $final_str);
                  $final_str = str_replace("#center#", "" .$venue_details[0]['center_name']. "", $final_str);
                  $final_str = str_replace("#exam_date#", "" .date('Y-m-d',strtotime($this->input->post('exam_date'))). "", $final_str);
                  $final_str = str_replace("#candidate#", "" .$candidate_data[0]['candidate_name']. "", $final_str);
                  
                  $info_arr  = array(
                      'to'      => array('je.exm4@iibf.org.in','ad.exm2@iibf.org.in'),
                     
                      'from'    => $emailerstr[0]['from'],
                      'subject' => $emailerstr[0]['subject'] ,
                      'message' => $final_str,
                  );
                  if ($this->Emailsending->mailsend($info_arr)) {
                            
                    $this->supervision_model->insert_common_log('Supervision Report immediate_action:  mail sent to admin', 'supervision_claims', $final_str, $id,'save_session_form','', json_encode($info_arr));
                    
                  
                  }
              }
            }
            //generate PDF            

            $this->db->join('supervision_candidates sm', 'sm.id = am.candidate_id', 'LEFT');
            $this->db->join('supervision_exam_activation se', 'se.exam_code = am.exam_code', 'LEFT');
            $this->db->where('sm.id',$this->login_candidate_or_centre_id);
            $this->db->where('am.id',$form_id);
            
            $data['form_details']= $this->master_model->getRecords('supervision_session_forms am', array('am.is_deleted' => '0'), "am.*, sm.candidate_name, sm.email, sm.mobile, sm.bank, sm.branch, sm.designation, sm.pdc_zone, sm.center_name,se.exam_name");
           
            if(count($data['form_details'])>0) {
              $exam_time = explode(',',$data['form_details'][0]['exam_time']);
              $candidate_appeared = explode(',',$data['form_details'][0]['candidate_appeared']);
            
              $data['form_details'][0]['candidate_appeared'] = $data['form_details'][0]['candidate_appeared_to_show'];
            }
           
            $html=$this->load->view('supervision/candidate/session_form_download', $data, true);
            $this->load->library('m_pdf');
            $pdf =$this->m_pdf->load();
            
            $pdf->WriteHTML($html);
            $path = $pdf->Output('uploads/supervision/'.$pdfFilePath, "F"); 
           
            if($data['form_details'][0]['uploaded_file']=='') {
             
              $success_message = "<b>Please download the form by clicking on the download button ( <b style='color:#1c84c6;'>Blue</b> icon) and re-upload the signed copy of the Supervision Report (PDF) by clicking on the edit button( <b style='color:#ffc107;'>Yellow</b> icon) for final submission. </b>";
            }
            else if($data['form_details'][0]['uploaded_file']!='' && $data['form_details'][0]['pay_status']==0){
              
              $success_message ="<b>Supervision Report details saved successfully. Please click on Claim button (<b style='color:#28a745;'>Green</b> icon) to proceed with the claim form.</b>";
            }
           $this->session->set_flashdata('success',$success_message);
            redirect(site_url('supervision/candidate/dashboard_candidate/session_forms'));   
          
          }
        }

       
      $this->load->view('supervision/candidate/save_session_form_candidate', $data);
    }
    function download_form_pdf($pdf_file='') {
      if(isset($_GET['session_flash']) && $_GET['session_flash']==1)
      $this->session->set_flashdata('success','Supervision Report details successfully');
      

      download_form_pdf_func($_REQUEST['pdf_file']);
    }

    function pdf_test(){
          $this->db->join('supervision_candidates sm', 'sm.id = am.id', 'LEFT');
          $this->db->join('supervision_exam_activation se', 'se.exam_code = am.exam_code', 'LEFT');
            $this->db->where('sm.id',$this->login_candidate_or_centre_id);
            $this->db->where('am.id',8);
            $data['form_details']= $this->master_model->getRecords('supervision_session_forms am', array('am.is_deleted' => '0'), "am.*, sm.candidate_name,, sm.email,, sm.mobile, sm.bank, sm.branch, sm.designation, sm.pdc_zone, sm.center_name,se.exam_name");

            $html=$this->load->view('supervision/session_form_download', $data, true);
            $this->load->library('m_pdf');
            $pdf =$this->m_pdf->load();
            $pdfFilePath = $this->login_candidate_or_centre_id."_".$form_id.".pdf";
            $pdf->WriteHTML($html);
            $path = $pdf->Output('uploads/supervision/'.$pdfFilePath, "F"); 
            echo $html;exit;
    }
    function get_exam_venues_ajax()
    {
			if(isset($_POST) && count($_POST) > 0 && $this->input->post('exam_code')!='')
			{
        $result['flag'] = "success";

        $candidate_data = $this->master_model->getRecords('supervision_candidates', array('id' => $this->login_candidate_or_centre_id), 'id, center_code');

        //to show venues
        $onchange_fun = "get_exam_date_ajax(this.value);validate_file('venue_code')";
				$html = '	<select class="form-control chosen-select ignore_required venue_code" name="venue_code" id="venue_code" required onchange="'.$onchange_fun.'">';
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $exam_period = $this->security->xss_clean($this->input->post('exam_period'));
        $this->db->group_by('venue_code');
        $venue_data = $this->master_model->getRecords('supervision_venue_master', array('center_code' => $candidate_data[0]['center_code'],'exam_code' => trim($exam_code),'exam_period' => trim($exam_period), 'venue_delete' => '0'), 'id, venue_code,venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5', array('venue_name'=>'ASC'));
       // echo $this->db->last_query();exit;
				if(count($venue_data) > 0)
				{
					$html .= '	<option value="">Select </option>';
					foreach($venue_data as $venue)
					{
						$html .= '	<option value="'.$venue['venue_code'].'">'.$venue['venue_name'].' '.$venue['venue_addr1'].' '.$venue['venue_addr2'].' '.$venue['venue_addr3'].' '.$venue['venue_addr4'].' '.$venue['venue_addr5'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select</option>';
        }
				$html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }

    function get_exam_dates_ajax()
    {
			if(isset($_POST) && count($_POST) > 0 && $this->input->post('venue_code')!='' && $this->input->post('exam_code')!='') 
			{
        $result['flag'] = "success";
        //to show cities
        $onchange_fun = "get_exam_time_ajax(this.value);validate_file('exam_date')";
				$html = '	<select class="form-control chosen-select ignore_required exam_date" name="exam_date" id="exam_date" required onchange="'.$onchange_fun.'">';
				$venue_code = $this->security->xss_clean($this->input->post('venue_code'));
        $exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $exam_period = $this->security->xss_clean($this->input->post('exam_period'));
        $this->db->group_by('exam_date');
        $exam_dates_data = $this->master_model->getRecords('supervision_venue_master', array('exam_code' => trim($exam_code),'exam_period' => trim($exam_period),'venue_code' => $venue_code, 'venue_delete' => '0'), 'id, exam_date', array('exam_date'=>'ASC'));

				if(count($exam_dates_data) > 0)
				{
					$html .= '	<option value="">Select </option>';
					foreach($exam_dates_data as $exam_date)
					{
						$html .= '	<option value="'.$exam_date['exam_date'].'">'.$exam_date['exam_date'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select</option>';
        }
				$html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }

    function get_exam_times_ajax()
    {
			if(isset($_POST) && count($_POST) > 0 && $this->input->post('exam_date')!='') 
			{
        $result['flag'] = "success";
        //to show cities
        $onchange_fun = "validate_file('exam_time')";
				$html = '	<select multiple class="exam_time form-control chosen-select ignore_required" name="exam_time[]" id="exam_time" required onchange="'.$onchange_fun.'">';
				$venue_code = $this->security->xss_clean($this->input->post('venue_code'));
        $exam_date = $this->security->xss_clean($this->input->post('exam_date'));
        $exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $exam_period = $this->security->xss_clean($this->input->post('exam_period'));
        $this->db->group_by('session_time');
        $exam_times_data = $this->master_model->getRecords('supervision_venue_master', array('exam_code' => trim($exam_code),'exam_period' => trim($exam_period),'venue_code' => $venue_code,'exam_date' => $exam_date, 'venue_delete' => '0'), 'id, session_time', array('id'=>'ASC'));

       $i=1;
				if(count($exam_times_data) > 0)
				{
					$html .= '	<option value="">Select </option>';
					foreach($exam_times_data as $exam_time)
					{
						$html .= '	<option class="'.$i++.'" id="'.$exam_time['id'].'" value="'.$exam_time['session_time'].'">'.$exam_time['session_time'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select</option>';
        }
				$html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }
    /******** START : CHANGE Candidate PASSWORD ********/
    function change_password()
		{   
      $data['act_id'] = "Change Password";
			
      $log_slug = '';

      if($this->login_user_type == "candidate") 
      { 
        $data['page_title'] = 'IIBF - Supervision Candidate Change Password'; 

        $data['form_data'] = $form_data = $this->master_model->getRecords('supervision_candidates', array('id' => $this->login_candidate_or_centre_id), 'id, is_active, is_deleted');

        $log_slug = 'password_action';
      }
           
      			
			if(isset($_POST) && count($_POST) > 0)
			{ 
				$this->form_validation->set_rules('current_pass_candidate', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password',array('required' => 'Please enter %s'));
				$this->form_validation->set_rules('new_pass_candidate', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				$this->form_validation->set_rules('confirm_pass_candidate', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				
				if($this->form_validation->run())		
				{
          $posted_arr = json_encode($_POST);
          $candidate_or_centre_name = $this->supervision_model->getLoggedInUserDetails($this->login_candidate_or_centre_id, $this->login_user_type);

          if($this->login_user_type == "candidate")
          {
            $up_data['password'] = $this->supervision_model->password_encryption($this->input->post('new_pass_candidate'));
            $up_data['updated_on'] = date("Y-m-d H:i:s");
            $up_data['updated_by'] = $this->login_candidate_or_centre_id;
            $this->master_model->updateRecord('supervision_candidates', $up_data, array('id' => $this->login_candidate_or_centre_id));
            
            $this->supervision_model->insert_common_log('Candidate : Profile password updated', 'supervision_candidates', $this->db->last_query(), $this->login_candidate_or_centre_id, $log_slug, 'The candidate '.$candidate_or_centre_name['disp_name'].' has successfully updated the password', $posted_arr); 
          }
          

					$this->session->set_flashdata('success','Password successfully updated');
					
          redirect(site_url('supervision/candidate/dashboard_candidate/change_password'));
				}
			}
      
      $data['log_slug'] = $log_slug;
      $this->load->view('supervision/candidate/change_password_candidate', $data);
		}/******** END : CHANGE CANDIDATE PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		function validation_check_old_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['current_pass_candidate'] != "")
			{
        if($type == '1') { $current_pass_candidate = $this->security->xss_clean($this->input->post('current_pass_candidate')); }
        else if($type == '0') { $current_pass_candidate = $str; }        
								
				$enc_password = $this->supervision_model->password_encryption($current_pass_candidate);

        if($this->login_user_type == "candidate") 
        { 
          if(count($this->master_model->getRecords('supervision_candidates', array('password' => $enc_password, 'id' => $this->login_candidate_or_centre_id, 'is_active' => '1', 'is_deleted' => '0'), 'id, is_active, is_deleted')) > 0)
          {
            $return_val_ajax = 'true';
          }
        }
        else if($this->login_user_type == "centre") 
        { 
          if(count($this->master_model->getRecords('supervision_centre_master', array('centre_password' => $enc_password, 'centre_id' => $this->login_candidate_or_centre_id, 'status' => '1', 'is_deleted' => '0'), 'centre_id, status, is_deleted')) > 0)
          {
            $return_val_ajax = 'true';
          }
        }        
			}

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['current_pass_candidate'] != "")
        {
          $this->form_validation->set_message('validation_check_old_password','Please enter correct old password');
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
		function validation_check_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'Please enter same password and confirm password';
			if(isset($_POST) && $_POST['confirm_pass_candidate'] != "")
			{
        $new_pass_candidate = $this->security->xss_clean($this->input->post('new_pass_candidate'));
        if($type == '1') { $confirm_pass_candidate = $this->security->xss_clean($this->input->post('confirm_pass_candidate')); }
        else if($type == '0') { $confirm_pass_candidate = $str; }   
        
        if($new_pass_candidate == $confirm_pass_candidate)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_pass_candidate'] != "")
        {
          $this->form_validation->set_message('validation_check_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/

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
          $result = $this->supervision_model->fun_validate_password($str); 
          if($result['flag'] == 'success') { return true; }
          else
          {
            $this->form_validation->set_message('fun_validate_password', $result['response']);
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/
		function validation_check_new_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'New password must be different from Current password';
			
      if(isset($_POST) && $_POST['new_pass_candidate'] != "")
			{
        $current_pass_candidate = $this->security->xss_clean($this->input->post('current_pass_candidate'));
        if($type == '1') { $new_pass_candidate = $this->security->xss_clean($this->input->post('new_pass_candidate')); }
        else if($type == '0') { $new_pass_candidate = $str; } 
        
        if (preg_match('/[A-Z]/', $new_pass_candidate) && preg_match('/[a-z]/', $new_pass_candidate) && preg_match('/[0-9]/', $new_pass_candidate))
        {
          if($current_pass_candidate != $new_pass_candidate)
          {
            $return_val_ajax = 'true';
          }
        }
        else
        {
          $msg = 'Password must contain at least one upper-case character, one lower-case character, one digit and one special character';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['new_pass_candidate'] != "")
        {
          $this->form_validation->set_message('validation_check_new_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/

    /******** START : VIEW PROFILE ********/
    function view_profile()
		{   
      $data['act_id'] = "Profile Settings";
			$data['sub_act_id'] = "View Profile";
      $log_slug = '';

      if($this->login_user_type == "candidate") 
      { 
        $data['page_title'] = 'IIBF - Supervision Candidate View Profile'; 

        $this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
        $this->db->where('am.id',$this->login_candidate_or_centre_id);
        $data['form_data'] = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS CandidateStatus, sm.pdc_zone_name, cm.city_name");
        
        $log_slug = 'candidate_action';
      }
      
      
      $data['log_slug'] = $log_slug;
      $this->load->view('supervision/candidate/view_profile_candidate', $data);
		}/******** END : VIEW PROFILE ********/
  }