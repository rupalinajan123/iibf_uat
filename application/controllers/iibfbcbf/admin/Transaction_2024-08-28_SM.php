<?php 
  /********************************************************************************************************************
    ** Description: Controller for BCBF Centre Batches Candidates Master
    ** Created BY: Sagar Matale On 27-11-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Transaction extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      $this->load->helper('getregnumber_helper'); 
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      
      if($this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
      
      $this->id_proof_file_path = 'uploads/iibfbcbf/id_proof';
      $this->qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
      $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->candidate_sign_path = 'uploads/iibfbcbf/sign';
      
      $this->utr_slip_path = 'uploads/iibfbcbf/utr_slip';
      
      if($this->login_user_type != 'admin')
      {
        $this->session->set_flashdata('error','You do not have permission to access Batche Candidates module');
        redirect(site_url('iibfbcbf/admin/dashboard_admin'));
      }
      
      $this->batches_id_edit_candidate_arr = array('00'=>'2023-12-01'); //array('batch id'=>'batch add/edit date'); array('1'=>'2023-11-30', '2'=>'2023-12-01');
      
      /* $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('status' => '1'), 'id');
      foreach($payment_data as $res)
      {
        $enc_pt_id = url_encode($res['id']);
        $this->Iibf_bcbf_model->generate_admit_card_common($enc_pt_id);
      } */

      $this->venue_master_eligible_exam_codes_arr = array(1041,1042);//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS

      $this->eligible_exam_code_for_admit_card_arr = array(1039,1040,1041,1042); //ADMITCARD IS AVAILABLE ONLY FOR 1039,1040,1041,1042 EXAM CODES. 1039 & 1040 ARE CSC MODE EXAMS. 1041 & 1042 ARE HYBRID MODE EXAMS
    }
    
    public function index($enc_batch_id=0)
    {      
      $data['enc_batch_id'] = $enc_batch_id; 
      
      $data['agency_data'] = $data['agency_centre_data'] = array();
      $data['act_id'] = "Approve NEFT Transactions";
      $data['sub_act_id'] = "Approve NEFT Transactions";
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Approve NEFT Transactions : List'; 
      
      $this->load->view('iibfbcbf/admin/neft_transactions_list_admin', $data);
    }
    
    public function neft_transactions($enc_id=0)
    {      
      $data['enc_batch_id'] = $enc_id; 
      
      $data['agency_data'] = $data['agency_centre_data'] = array();
      
      $data['agency_centre_data'] = array();
      
      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm1.city_name');
      
      $data['act_id'] = "Approve NEFT Transactions";
      $data['sub_act_id'] = "Approve NEFT Transactions";
      $data['utr_slip_path'] = $this->utr_slip_path;
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Approve NEFT Transactions : List'; 
      
      $this->load->view('iibfbcbf/admin/neft_transactions_list_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_transaction_data_ajax()
    {
      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")") AS DispCentre', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'ei.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")") AS description', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'pt.exam_code', 'pt.exam_period', '"" AS DispMemberNumbers', '"" AS DispMemberTrainingIds', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.status', 'ei.invoice_image','ei.invoice_id', 'me.regnumber', 'cand.training_id'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")")', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")")', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'ei.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")")', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'pt.exam_code', 'pt.exam_period', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", "")))))', 'me.regnumber', 'cand.training_id'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC'); // DEFAULT ORDER
      
      //$WhereForTotal = "WHERE pt.gateway = '1' ";
      $WhereForTotal = "WHERE pt.gateway IN ('1','2','3') AND pt.date != '' AND pt.UTR_no != 'IIBFBCBF-TEMP-UTR-NO' ";
      //$Where = "WHERE pt.gateway = '1'  ";
      $Where = "WHERE pt.gateway IN ('1','2','3') AND pt.date != '' AND pt.UTR_no != 'IIBFBCBF-TEMP-UTR-NO' ";
      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      /*$s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND pt.centre_id = '".$s_centre."'"; } */
      
      $s_member_no = trim($this->security->xss_clean($this->input->post('s_member_no')));
      /* if($s_member_no != "") { $Where .= " AND ((SELECT GROUP_CONCAT(bc.regnumber SEPARATOR ', ') from iibfbcbf_batch_candidates bc WHERE FIND_IN_SET(bc.candidate_id, (SELECT GROUP_CONCAT(me.candidate_id) from iibfbcbf_member_exam me WHERE FIND_IN_SET(me.member_exam_id,pt.exam_ids)))) LIKE '%".$s_member_no."%' )"; } */ 
      if($s_member_no != "") { $Where .= " AND me.regnumber = '".$s_member_no."'"; }      
      
      $s_utr_no = trim($this->security->xss_clean($this->input->post('s_utr_no')));
      if($s_utr_no != "") { $Where .= " AND (pt.UTR_no LIKE '%".$s_utr_no."%' OR pt.transaction_no LIKE '%".$s_utr_no."%' )"; } 

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if($s_exam_code != "") { $Where .= " AND pt.exam_code = '".$s_exam_code."'"; } 

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if($s_exam_period != "") { $Where .= " AND pt.exam_period = '".$s_exam_period."'"; } 
      
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      if($s_from_date != "") { $Where .= " AND DATE(pt.date) >= '".$s_from_date."'"; } 
      
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      if($s_to_date != "") { $Where .= " AND DATE(pt.date) <= '".$s_to_date."'"; } 
      
      $s_payment_mode = trim($this->security->xss_clean($this->input->post('s_payment_mode')));
      if($s_payment_mode != "") { $Where .= " AND pt.payment_mode = '".$s_payment_mode."'"; } 
      
      $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") { $Where .= " AND pt.status = '".$s_payment_status."'"; } 
      
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $WhereForTotal .= " GROUP BY pt.id";
      $Where .= " GROUP BY pt.id";

      $join_qry = " LEFT JOIN iibfbcbf_exam_master em ON em.exam_code = pt.exam_code";       
      $join_qry .= " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = pt.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      $join_qry .= " INNER JOIN iibfbcbf_agency_master am ON am.agency_id = pt.agency_id";
      $join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
      $join_qry .= " LEFT JOIN iibfbcbf_member_exam me ON me.pt_id = pt.id";
      $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates cand ON cand.candidate_id = me.candidate_id";
      
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
        $row[] = $Res['DispCentre'];
        $row[] = $Res['DispTransactionNo'];
        $row[] = $Res['receipt_no'];
        $row[] = $Res['description'];
        $row[] = $Res['amount'];
        $row[] = $Res['pay_count'];
        $row[] = $Res['PaymentDate'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];

        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $cand_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.pt_id' => $Res['id']), 'me.candidate_id, cand.regnumber, cand.training_id');
        $DispMemberNumbers = $DispMemberTrainingIds = '';
        if(count($cand_data) > 0)
        {
          foreach($cand_data as $cand_res)
          {
            if($cand_res['regnumber'] != '') { $DispMemberNumbers .= $cand_res['regnumber'].', '; }
            $DispMemberTrainingIds .= $cand_res['training_id'].', ';
          }
        }

        $row[] = rtrim($DispMemberNumbers,", ");        
        $row[] = rtrim($DispMemberTrainingIds,", ");        
        $row[] = $Res['payment_mode']; 
        $row[] = '<span class="badge '.show_payment_status($Res['status']).'" style="width: 100px;white-space: normal;line-height: 15px; word-break: break-word;">'.$Res['DispPayStatus'].'</span>'; //iibf_bcbf_helper.php
        
        $btn_str = ' <div class="text-right no_wrap"> ';
        
        if($Res['status'] == '3' && $Res['payment_mode'] == 'Bulk') // status = 3 for Applied
        {
          $enc_id = $Res['id']; //url_encode($Res['id']);
          $btn_str .= '<a href="javascript:void(0)" onclick="verifyTransaction('.$enc_id.');" class="btn btn-warning btn-xs" title="Verify Transactions Details"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a>';
        } 
        
        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/transaction/payment_receipt_agency/'.url_encode($Res['id'])).'" class="btn btn-success btn-xs" title="Agency Payment Receipt"><i class="fa fa-money" aria-hidden="true"></i></a>';
        
        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/transaction/transactions_details/'.url_encode($Res['id'])).'" class="btn btn-primary btn-xs" title="View NEFT Transactions Details"><i class="fa fa-eye" aria-hidden="true"></i></a>';
        
        if($Res['invoice_image'] != "")
        {
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/download_file_common/index/'.url_encode($Res['invoice_id']).'/invoice_image').'" class="btn btn-info btn-xs" title="View Invoice"><i class="fa fa-file-text"></i></a> ';
        }
        
        if($Res['status'] == "1" && in_array($Res['exam_code'], $this->eligible_exam_code_for_admit_card_arr))
        {
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/transaction/admit_cards/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="Admit Card"><i class="fa fa-id-card" aria-hidden="true"></i></a> ';
        }
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        /* if(in_array($Res['batch_id'],$delete_ids_str_arr)) { $check_val = "checked"; } else { $check_val = ""; }
        $row[] = '<label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$Res['batch_id'].'" id="checkboxlist_new_'.$Res['batch_id'].'" onclick="update_delete_str('.$Res['batch_id'].')" '.$check_val.'><span class="checkmark"></span></label>'; */
        
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    
    public function transactions_details($enc_pt_id=0)
    {
      $data['act_id'] = "Approve NEFT Transactions";
      $data['sub_act_id'] = "Approve NEFT Transactions Payment";      
      $data['page_title'] = 'IIBF - BCBF Approve NEFT Transactions Payment Details';
      
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);
      
      /*$data['act_id'] = "Transaction Details";
        $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Payment Receipt : Candidate List';*/
      
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.amount');
      
      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/admin/transaction/neft_transactions')); }
      
      //$data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('pk_id' => $transaction_id, 'module_slug' => 'agency_action'), 'log_id, module_slug, description, created_on', array('created_on'=>'ASC')); 
      
      //echo $this->db->last_query();
      $this->load->view('iibfbcbf/admin/transaction_details_admin', $data);
    }
    
    function get_neft_payment_receipt_candidate_listing_data_ajax()
    {
      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), "")) AS DispCandidateName', 'cand.training_id', 'me.exam_fee', 'me.candidate_id'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))', 'cand.training_id', 'me.exam_fee'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC', 'me.candidate_id' => 'DESC'); // DEFAULT ORDER
      
      $enc_pt_id = trim($this->security->xss_clean($this->input->post('enc_pt_id')));
      $pt_id = url_decode($enc_pt_id);
      
      $WhereForTotal = "WHERE pt.id = '".$pt_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE pt.id = '".$pt_id."' ";        
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }    
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = " INNER JOIN iibfbcbf_member_exam me ON FIND_IN_SET(me.member_exam_id, pt.exam_ids)";
      $join_qry .= " INNER JOIN iibfbcbf_batch_candidates cand ON cand.candidate_id = me.candidate_id";
      
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
        $row[] = $Res['DispCandidateName'];
        $row[] = $Res['training_id'];
        $row[] = $Res['exam_fee'];
        
        $btn_str = ' <div class="text-center no_wrap"> ';
        $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/transaction/payment_receipt_candidate_agency/'.url_encode($Res['id']).'/'.url_encode($Res['candidate_id'])).'" class="btn btn-success btn-xs" title="Candidate Payment Receipt"><i class="fa fa-money"></i></a> ';
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
    
    public function payment_receipt_agency($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);
      
      $data['act_id'] = "Approve NEFT Transactions";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Payment Receipt';
      
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = pt.agency_id', 'INNER');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = pt.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, cm.centre_name, cm.centre_username, cm2.city_name');
      
      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/admin/transaction/neft_transactions')); }
      
      $this->load->view('iibfbcbf/admin/payment_receipt_agency_admin',$data);
    }
    
    function payment_receipt_candidate_agency($enc_pt_id=0, $enc_candidate_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);
      
      $data['enc_candidate_id'] = $enc_candidate_id;
      $candidate_id = url_decode($enc_candidate_id);
      
      $data['act_id'] = "Approve NEFT Transactions";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Candidate Payment Receipt';
      
      $this->db->join('iibfbcbf_member_exam me', 'FIND_IN_SET(me.member_exam_id, pt.exam_ids)', 'INNER');
      $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
      $this->db->join('iibfbcbf_exam_medium_master mm', 'mm.medium_code = me.exam_medium AND mm.exam_code = me.exam_code', 'LEFT');
      $this->db->join('iibfbcbf_exam_centre_master ecm', 'ecm.centre_code = me.exam_centre_code AND ecm.exam_name = me.exam_code', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id, 'cand.candidate_id'=>$candidate_id), 'pt.id, pt.agency_id, pt.centre_id, pt.exam_ids, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, me.member_exam_id, me.candidate_id, me.exam_fee, me.exam_centre_code, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.training_id, cand.email_id, mm.medium_description, ecm.centre_name');
      
      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/admin/transaction/transactions_details/'.$enc_pt_id)); }
      
      $this->load->view('iibfbcbf/admin/payment_receipt_candidate_admin',$data);
    }
    
    
    
    // function to get transaction details by id -
    public function getTransactionDetails()
    { 
      $flag = "error"; 
      $result = array();
      if(isset($_POST) && $_POST['enc_id'] != "")
      {  
        $enc_id = $this->security->xss_clean($this->input->post('enc_id'));   
        //$id = url_decode($enc_id);        
        $id = ($enc_id);        
        $this->db->where("pt.id",$id);
        $this->db->join('iibfbcbf_exam_master as em','em.exam_code = pt.exam_code','LEFT');
        $payment_transaction_data = $this->master_model->getRecords('iibfbcbf_payment_transaction as pt', array('pt.gateway' => '1'), 'pt.id, pt.UTR_no AS transaction_no, pt.UTR_slip_file, em.description, pt.pay_count AS member_count, pt.amount, DATE_FORMAT(pt.date,"%Y-%m-%d") As date, pt.exam_period, pt.status'); 
        if(count($payment_transaction_data) > 0)
        {
          $payment_transaction_data[0]['enc_payment_id'] = url_encode($payment_transaction_data[0]['id']);
          $result = $payment_transaction_data[0]; 
          $flag = "success"; 
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $result; 
      echo json_encode($result); 
    }
    
    
    /******** START : APPROVE / REJECT NEFT TRANSACTIONS ********/
    public function approveNeftTransactions()
    {
      $data = array();
      $chk_exam_mode = "";
      if($this->input->post('id') && $this->input->post('action'))
      {
        $id = $this->input->post('id');
        $utr_no = $this->input->post('utr_no');
        $mem_count = $this->input->post('mem_count');
        $payment_amt = $this->input->post('payment_amt');
        $payment_date = $this->input->post('payment_date');
        
        $updte_data = array();
        $updated_date = date('Y-m-d H:i:s');
        
        $admit_mem_exam_ids_arr = array();
        $admit_mem_exam_idlst = '';
        $admit_memexamidlst = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id' => $id),'exam_ids,status');

        if(isset($admit_memexamidlst) && count($admit_memexamidlst) > 0 && $admit_memexamidlst[0]['status'] == '3')
        { 

          if($admit_memexamidlst){
            $admit_mem_exam_idlst = $admit_memexamidlst[0]['exam_ids'];
            $admit_mem_exam_ids_arr = explode(",",$admit_mem_exam_idlst);
          }

          
          
          $this->db->select("pt.id, pt.exam_code, pt.exam_period, ea.exam_mode");
          $this->db->join("iibfbcbf_exam_activation_master ea", "ea.exam_code = pt.exam_code AND ea.exam_period = pt.exam_period");
          $exam_mode_qry = $this->master_model->getRecords('iibfbcbf_payment_transaction pt',array('pt.id'=>$id, 'ea.exam_activation_delete'=>'0'));
          $error_qry = $this->db->last_query();
          //echo $this->db->last_query(); die;
          if(count($exam_mode_qry) > 0)
          {
            $data['chk_exam_mode'] = $chk_exam_mode = $exam_mode_qry[0]['exam_mode']; //Online or Offline
          }
          else
          {
            
            $this->Iibf_bcbf_model->insert_common_log('Transaction : NEFT Approved Failed', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The NEFT Approved Failed by the admin', json_encode($updte_data));
            
            $data['success'] = 'error'; 
            $json_res = json_encode($data);
            echo $json_res;
            exit; die();
          }     
          
          $flag = 0;
          $sub_arr = $member_array = array();
          
          if($this->input->post('action') == "Approved")
          {
            $status = '1';
            $desc = 'Payment Success - Approved by Admin';  
            $data['success'] = 'Success';
            //break;
            
            $approve_reject_data['status'] = $status;
            $approve_reject_data['UTR_no'] = $utr_no;
            $approve_reject_data['pay_count'] = $mem_count;
            $approve_reject_data['amount'] = $payment_amt;
            //$approve_reject_data['date'] = date("Y-m-d H:i:s", strtotime($payment_date));
            $approve_reject_data['description'] = $desc;
            $approve_reject_data['updated_on'] = $updated_date; 
            
            //log_dra_admin($log_title = "IIBFBCBF Admin NEFT Approved Successfully", $log_message = serialize($approve_reject_data));
            $this->Iibf_bcbf_model->insert_common_log('Transaction : NEFT Approved Successfully', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The NEFT Approved Successfully by the admin', json_encode($approve_reject_data));
          }
          else if($this->input->post('action') == "Rejected")
          {
            $sub_arr = $member_array;
            $status = '4';
            $desc = 'Payment Failed - Rejected by Admin';
            
            $approve_reject_data['status'] = $status;
            $approve_reject_data['description'] = $desc;
            $approve_reject_data['updated_on'] = $updated_date; 
            
            //log_dra_admin($log_title = "DRA Admin NEFT Rejected Successfully", $log_message = serialize($approve_reject_data));
            $this->Iibf_bcbf_model->insert_common_log('Transaction : NEFT Rejected Successfully', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The NEFT Rejected Successfully by the admin', json_encode($approve_reject_data));
            $data['success'] = 'success';
            
          }
          
          // update required table
          //echo $chk_exam_mode." == 'Online' && ".count($sub_arr)." == ".count($member_array)." && ".$flag." == 0 && (".count($member_array)." > 0 || ".$this->input->post('action')." == 'Rejected')) || ".$chk_exam_mode." == 'Offline'";
          if((count($sub_arr) == count($member_array) && $flag == 0 && (count($member_array) > 0 || $this->input->post('action') == "Rejected")) || $chk_exam_mode == 'Online')
          {
            if($status == '1')//start : if payment action is approve, then check for capacity 
            {
              if(in_array($exam_mode_qry[0]['exam_code'],$this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
              {
                $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
                $this->db->where_in('me.pay_status', '3', FALSE);
                $this->db->where_in('me.member_exam_id', $admit_mem_exam_idlst, FALSE);
                $candidate_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted'=>'0', 'cand.is_deleted'=>'0'), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_centre_code, me.exam_venue_code, me.exam_date, me.exam_time, me.exam_fee, me.pay_status, me.fee_paid_flag, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.registration_type');
                
                foreach ($candidate_data as $candidate_res)
                {
                  $chk_capacity = $this->Iibf_bcbf_model->get_capacity_bulk($candidate_res['exam_code'], $candidate_res['exam_period'], $candidate_res['exam_centre_code'], $candidate_res['exam_venue_code'], $candidate_res['exam_date'], $candidate_res['exam_time'], '', 'make_payment');/* , $candidate_res['member_exam_id'], 'make_payment' */

                  //echo '<br>'.$candidate_res['member_exam_id'].' >> '.$chk_capacity;
                  if($chk_capacity <= 0)
                  {
                    //$this->session->set_flashdata('error','The capaciy is full');
                    //redirect(site_url('iibfbcbf/admin/transaction/neft_transactions'));

                    $data['success'] = 'capacity_error'; 
                    $json_res = json_encode($data);
                    echo $json_res; exit;
                  }
                } 
              } 
            }//end : if payment action is approve, then check for capacity
            //exit; 

            $approve_reject_data['approve_reject_date'] = date("Y-m-d H:i:s");
            if($this->master_model->updateRecord('iibfbcbf_payment_transaction',$approve_reject_data,  array('id' => $id)))
            {
              //generate registration number for members if admin approves NEFT transaction
              if( $status == '1' ) //WHEN PAYMENT IS APPROVED
              {
                //$memexamidlst = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid' => $id));             
                
                if( count( $admit_mem_exam_ids_arr ) > 0 ) 
                {
                  foreach( $admit_mem_exam_ids_arr as $memexamids ) 
                  {
                    $memexamid = $memexamids; //$memexamids['memexamid'];
                    $memregid = $this->master_model->getValue('iibfbcbf_member_exam',array('member_exam_id' => $memexamid), 'candidate_id');
                    if( $memregid ) 
                    {
                      $regnum = $this->master_model->getValue('iibfbcbf_batch_candidates',array('candidate_id'=>$memregid),'regnumber'); 
                      //echo "<BR>regnum = ".$regnum;
                      if( empty( $regnum ) ) 
                      {
                        //$memregnumber = generate_dra_reg_num();
                        //$memregnumber = generate_nm_reg_num();
                        $memregnumber = generate_NM_memreg($memregid);
                        $update_batch_candidates_data = array(
                        'regnumber'   => $memregnumber
                        );  
                        $this->master_model->updateRecord('iibfbcbf_batch_candidates',$update_batch_candidates_data, array('candidate_id' => $memregid));

                        if(isset($memregnumber) && $memregnumber != '') 
                        { 
                          $update_regnumber_data = array();
                          $update_regnumber_data['regnumber'] = $memregnumber; 
                          $this->master_model->updateRecord('iibfbcbf_member_exam',$update_regnumber_data, array('member_exam_id' => $memexamid));
                        }                  

                        $update_admit_card_data = array(
                        'mem_mem_no'    => $memregnumber
                        );  
                        $this->master_model->updateRecord('iibfbcbf_admit_card_details',$update_admit_card_data, array('mem_exam_id' => $memexamid)); 
                        
                        $log_update_data = array(
                        'regnumber'   => $memregnumber,
                        'candidate_id'     => $memregid,
                        
                        );
                        //log_dra_admin($log_title = "DRA Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_data));
                        $this->Iibf_bcbf_model->insert_common_log('Transaction : Registration No. Generated Successfully', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Registration No. generated successfully after NEFT Approval by the admin', json_encode($log_update_data));
                        
                        
                        //update uploaded file names which will include generated registration number
                        //get cuurent saved file names from DB
                        $currentpics = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id'=>$memregid), 'candidate_photo, candidate_sign, id_proof_file, qualification_certificate_file');                   
                        $current_id_proof_file = $current_qualification_certificate_file = $current_candidate_photo = $current_candidate_sign = '';
                        
                        if( count($currentpics) > 0 )
                        {
                          $current_id_proof_file = $currentpics[0]['id_proof_file'];
                          $current_qualification_certificate_file = $currentpics[0]['qualification_certificate_file'];
                          $current_candidate_photo = $currentpics[0]['candidate_photo'];
                          $current_candidate_sign = $currentpics[0]['candidate_sign'];
                        }
                        
                        $upd_files = array();
                        if( !empty( $current_id_proof_file ) ) 
                        {
                          $new_id_proof_file = 'id_proof_'.$memregnumber.'.'.strtolower(pathinfo($current_id_proof_file, PATHINFO_EXTENSION));
                          
                          $chk_rename_id_proof = $this->Iibf_bcbf_model->check_file_rename($current_id_proof_file, "./".$this->id_proof_file_path."/", $new_id_proof_file);
                          
                          if($chk_rename_id_proof == 'success')
                          { 
                            $upd_files['id_proof_file'] = $new_id_proof_file; 
                          }
                        }
                        
                        if( !empty( $current_qualification_certificate_file ) ) 
                        {
                          $new_qualification_certificate_file = 'quali_cert_'.$memregnumber.'.'.strtolower(pathinfo($current_qualification_certificate_file, PATHINFO_EXTENSION));
                          
                          $chk_rename_quali_cert = $this->Iibf_bcbf_model->check_file_rename($current_qualification_certificate_file, "./".$this->qualification_certificate_file_path."/", $new_qualification_certificate_file);
                          
                          if($chk_rename_quali_cert == 'success')
                          { 
                            $upd_files['qualification_certificate_file'] = $new_qualification_certificate_file; 
                          }
                        }
                        
                        if( !empty( $current_candidate_photo ) ) 
                        {
                          $new_candidate_photo = 'photo_'.$memregnumber.'.'.strtolower(pathinfo($current_candidate_photo, PATHINFO_EXTENSION));
                          
                          $chk_rename_photo = $this->Iibf_bcbf_model->check_file_rename($current_candidate_photo, "./".$this->candidate_photo_path."/", $new_candidate_photo);
                          
                          if($chk_rename_photo == 'success')
                          { 
                            $upd_files['candidate_photo'] = $new_candidate_photo; 
                          }
                        }
                        
                        if( !empty( $current_candidate_sign ) ) 
                        {
                          $new_candidate_sign = 'sign_'.$memregnumber.'.'.strtolower(pathinfo($current_candidate_sign, PATHINFO_EXTENSION));
                          
                          $chk_rename_sign = $this->Iibf_bcbf_model->check_file_rename($current_candidate_sign, "./".$this->candidate_sign_path."/", $new_candidate_sign);
                          
                          if($chk_rename_sign == 'success')
                          { 
                            $upd_files['candidate_sign'] = $new_candidate_sign; 
                          }
                        }
                        
                        if(count($upd_files)>0)
                        {
                          //log_dra_admin($log_title = "DRA Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));
                          $this->Iibf_bcbf_model->insert_common_log('Transaction : Member Images Updated Successfully', 'iibfbcbf_batch_candidates', $this->db->last_query(), $memregid,'transaction_action','The Member Images Updated successfully after NEFT Approval by the admin', json_encode($upd_files));
                          
                          $this->master_model->updateRecord('iibfbcbf_batch_candidates',$upd_files,array('candidate_id'=>$memregid));
                        }
                      }
                    }
                  }
                } 
                
                /******************* code added for GST Changes ***************/ 
                // get invoice
                $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $id, 'exam_code'=>$exam_mode_qry[0]['exam_code'], 'exam_period'=>$exam_mode_qry[0]['exam_period'], 'app_type'=>'BC'),'invoice_id');
                
                if(count($exam_invoice) > 0 && $payment_amt > 0)
                {
                  // generate exam invoice no
                  //$invoice_no = generate_exam_invoice_number($exam_invoice[0]['invoice_id']);
                  
                  $invoice_no = generate_iibfbcbf_exam_invoice_number($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php
                  //$invoice_no = generate_draexam_invoice_number($exam_invoice[0]['invoice_id']);
                  
                  if($invoice_no)
                  {
                    //$invoice_no = $this->config->item('exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
                    
                    $invoice_no = $this->config->item('iibfbcbf_exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
                    
                    //$invoice_no = $this->config->item('draexam_invoice_no_prefix').$invoice_no;
                  }
                  
                  //get payment date from iibfbcbf_payment_transaction
                  $payment_date = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id' => $id),'date,UTR_no');
                  
                  // update invoice details
                  $invoice_update_data = array('invoice_no' => $invoice_no,'transaction_no' => $payment_date[0]['UTR_no'],'date_of_invoice' => $updated_date,'modified_on' => $updated_date);
                  $this->db->where('pay_txn_id',$id);
                  $this->master_model->updateRecord('exam_invoice',$invoice_update_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                  
                  //log_dra_admin($log_title = "DRA Exam Invoice updated after NEFT Approval.", $log_message = serialize($invoice_update_data));
                  $this->Iibf_bcbf_model->insert_common_log('Transaction : Exam Invoice updated after NEFT Approval', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Exam Invoice updated after NEFT Approval by the admin', json_encode($invoice_update_data));
                  
                  // generate invoice image
                  //$invoice_img_path = genarate_draexam_invoice($exam_invoice[0]['invoice_id']);  
                  $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  
                }
                
                /******************* eof code added for GST Changes ***************/
                
                if( count( $admit_mem_exam_ids_arr ) > 0 ) 
                {
                  foreach( $admit_mem_exam_ids_arr as $memexamids ) 
                  {
                    $memexamid = $memexamids; //$memexamids['memexamid'];
                    $candidate_id = $this->master_model->getValue('iibfbcbf_member_exam',array('member_exam_id' => $memexamid), 'candidate_id');
                    if( $candidate_id ) 
                    {
                      //$regnum = $this->master_model->getValue('iibfbcbf_batch_candidates',array('candidate_id'=>$candidate_id),'regnumber'); 
                      $reattempt = $this->master_model->getValue('iibfbcbf_batch_candidates',array('candidate_id'=>$candidate_id),'re_attempt');
                      if($reattempt >= 1)
                      {
                        $re_attempt = $reattempt + 1; 
                        $update_batch_candidates_attempt_data["re_attempt"] = $re_attempt;
                        //$update_batch_candidates_attempt_data["new_reg"] = 0; 
                      }
                      else
                      {
                        $re_attempt = $reattempt + 1; 
                        $update_batch_candidates_attempt_data["re_attempt"] = $re_attempt; 
                      } 
                      $update_batch_candidates_attempt_data['updated_on'] = date('Y-m-d H:i:s');
                      $this->master_model->updateRecord('iibfbcbf_batch_candidates',$update_batch_candidates_attempt_data, array('candidate_id' => $candidate_id));
                      
                      $log_update_datas = array(
                      'candidate_id'     => $candidate_id,
                      're_attempt'      => $re_attempt
                      ); 
                      $this->Iibf_bcbf_model->insert_common_log('Transaction : Re-attempt successfully after NEFT Approval', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Re-attempt successfully after NEFT Approval by the admin', json_encode($log_update_datas)); 

                      $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$memexamid),'candidate_id, exam_code, exam_period');
                      $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success by admin', 'iibfbcbf_batch_candidates', '', $candidate_id,'candidate_action','The transaction is approved by the admin and candidate successfully applied for the exam ('.$member_exam_data[0]['exam_code'].' & '.$member_exam_data[0]['exam_period'].'). ', '');
                    } 
                    
                    //update pay_status flag in iibfbcbf_member_exam table 
                    $update_iibfbcbf_member_exam_data = array();
                    $update_iibfbcbf_member_exam_data['pay_status'] = $status;                                  
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$update_iibfbcbf_member_exam_data, array('member_exam_id' => $memexamid));
                    $update_member_exam_data = array(
                    'pay_status' => $status,
                    'member_exam_id' => $memexamid
                    );
                    //log_dra_admin($log_title = "DRA Member Exam Payment Transaction Status updated after NEFT Approval.", $log_message = serialize($update_member_exam_data));
                    $this->Iibf_bcbf_model->insert_common_log('Transaction : Member Exam Payment Transaction Status updated after NEFT Approval', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Member Exam Payment Transaction Status updated after NEFT Approval by the admin', json_encode($update_member_exam_data));
                    
                    $data['success'] = 'success';
                    
                  } 
                }
              }
              else if($status == '4')
              {
                if( count( $admit_mem_exam_ids_arr ) > 0 ) 
                {
                  foreach( $admit_mem_exam_ids_arr as $memexamids ) 
                  {
                    //update pay_status flag in iibfbcbf_member_exam table 
                    $update_iibfbcbf_mem_ex_data['pay_status'] = $status;
                    $update_iibfbcbf_mem_ex_data['ref_utr_no'] = ''; 
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$update_iibfbcbf_mem_ex_data, array('member_exam_id' => $memexamids));
                    $update_mem_ex_data = array(
                    'pay_status' => $status,
                    'member_exam_id' => $memexamids
                    ); 
                    $this->Iibf_bcbf_model->insert_common_log('Transaction : Member Exam Payment Transaction Status updated after NEFT Rejected', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Member Exam Payment Transaction Status updated after NEFT Rejected by the admin', json_encode($update_mem_ex_data));

                    $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$memexamids),'candidate_id, exam_code, exam_period');
                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment reject by admin', 'iibfbcbf_batch_candidates', '', $member_exam_data[0]['candidate_id'],'candidate_action','The transaction is rejected by the admin and candidate exam application failed for the exam ('.$member_exam_data[0]['exam_code'].' & '.$member_exam_data[0]['exam_period'].'). ', '');
                  }
                }
                
                $data['success'] = 'success';
              }
            }
          }
          else
          {
            //log_dra_admin($log_title = "IIBFBCBF Admin NEFT Approved Failed.", $log_message = serialize($update_data));
            $this->Iibf_bcbf_model->insert_common_log('Transaction : NEFT Approved Failed', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The NEFT Approved Failed by the admin', json_encode($approve_reject_data));
            
            $data['success'] = 'error2';  
          }

        }else{
          $data['success'] = 'invalid_request';  
        }

      }
      
      $json_res = json_encode($data);
      echo $json_res;
    } /******** END : APPROVE / REJECT NEFT TRANSACTIONS ********/   
    
    
    function generate_admit_card($enc_pt_id='')
    {
      if(isset($_POST) && count($_POST) > 0)
      {
        if($enc_pt_id != '') 
        {
          $this->Iibf_bcbf_model->generate_admit_card_common($enc_pt_id);          
        }
      }
    }
    
    /******** START : FUNCTION TO DISPLAY THE Candidates Admit Card ********/
    public function admit_cards($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);  
      
      $data['agency_data'] = $data['agency_centre_data'] = array(); 
      
      $data['act_id'] = "Approve NEFT Transactions";
      $data['sub_act_id'] = "Candidate Admitcard";
      $data['utr_slip_path'] = $this->utr_slip_path;
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Admitcard : Candidate Admitcard : List'; 
      
      $this->load->view('iibfbcbf/admin/admit_cards_admin', $data);
      
    }/******** END : FUNCTION TO DISPLAY THE Candidates Admit Card ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE ADMITCARD DATA ********/
    public function get_admitcard_data_ajax()
    {
      $table = 'iibfbcbf_admit_card_details bc'; 
      $GroupBy = "";
      
      $pt_id_enc = trim($this->security->xss_clean($this->input->post('pt_id_enc'))); 
      
      $column_order = array('bc.admitcard_id', 'bc.centre_name', 'bc.exm_cd', 'bc.exm_prd', 'bc.mem_mem_no AS no_of_candidates', 'ca.training_id', 'bc.mam_nam_1', 'ca.mobile_no', 'ca.email_id', 'bc.inscd', 'bc.centre_code', 'bc.exam_date'); //SET COLUMNS FOR SORT
      $column_search = array('bc.centre_name', 'bc.exm_cd', 'bc.exm_prd', 'bc.mem_mem_no'); //SET COLUMN FOR SEARCH 
      $order = array('bc.admitcard_id' => 'DESC'); // DEFAULT ORDER 
      
      $Where = "";
      $WhereForTotal = "";
      if($pt_id_enc != "") { 
        $pt_id_enc = url_decode($pt_id_enc);  
        $WhereForTotal = "WHERE bc.pt_id = '".$pt_id_enc."' ";
        $Where = "WHERE bc.pt_id = '".$pt_id_enc."' ";
      }   
      
      /*if($this->login_user_type == 'agency')
        {
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$this->login_agency_or_centre_id), 'agency_code');
        
        $WhereForTotal = "WHERE bc.inscd = '".$agency_data[0]["agency_code"]."' "; // AND bc.exm_cd IN('1037','1038') //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.inscd = '".$agency_data[0]["agency_code"]."' "; //  AND bc.exm_cd IN('1037','1038') 
        
        }
        else if($this->login_user_type == 'centre')
        {
        $center_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id'=>$this->login_agency_or_centre_id), 'centre_username,agency_id');
        
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$center_data[0]["agency_id"]), 'agency_code');
        
        $WhereForTotal = "WHERE bc.inscd = '".$agency_data[0]["agency_code"]."' AND bc.centre_code = '".$center_data[0]["centre_username"]."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.inscd = '".$agency_data[0]["agency_code"]."' AND bc.centre_code = '".$center_data[0]["centre_username"]."' "; // AND bm.exam_code = '1038'
      }*/      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }
      
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND bc.centre_code = '".$s_centre."'"; } 
      $s_term = trim($this->security->xss_clean($this->input->post('s_term')));
      if($s_term != "") { $Where .= " AND (bc.centre_name LIKE '%".$s_term."%' OR bc.mem_mem_no LIKE '%".$s_term."%' OR bc.exm_cd LIKE '%".$s_term."%' OR bc.exm_prd LIKE '%".$s_term."%' OR ca.training_id LIKE '%".$s_term."%' OR bc.mam_nam_1 LIKE '%".$s_term."%' OR ca.mobile_no LIKE '%".$s_term."%' OR ca.email_id LIKE '%".$s_term."%' OR bc.inscd LIKE '%".$s_term."%')"; } 
      /* 
        $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") { $Where .= " AND bc.status = '".$s_payment_status."'"; } */
      
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = " LEFT JOIN iibfbcbf_batch_candidates ca ON ca.regnumber = bc.mem_mem_no"; 
      
      
      
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
        
        $btn_str = ' <div class="text-centerx no_wrap" style="width: 60px; margin: 0 auto;"> '; 
        $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/transaction/download_admitcard_pdf/'.url_encode($Res['admitcard_id'])).'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';
        
        $row[] = $Res['centre_name'];
        $row[] = $Res['exm_cd'];
        $row[] = $Res['exm_prd'];
        $row[] = $Res['no_of_candidates'];      
        $row[] = $Res['training_id'];      
        $row[] = $Res['mam_nam_1'];      
        $row[] = $Res['mobile_no'];      
        $row[] = $Res['email_id'];      
        $row[] = $Res['inscd'];      
        
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/  
    
    public function download_admitcard_pdf($enc_admitcard_id = '0')
    {
      $this->Iibf_bcbf_model->download_admit_card_pdf_single($enc_admitcard_id, 'download');
    }

  } ?>    