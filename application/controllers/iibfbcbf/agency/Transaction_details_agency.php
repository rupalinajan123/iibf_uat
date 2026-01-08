<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Transaction Details
  ** Created BY: Sagar Matale On 11-12-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Transaction_details_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('getregnumber_helper');
      $this->load->helper('file');
      $this->load->helper('directory');
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      //genarate_iibf_bcbf_exam_invoice(11);
      $this->utr_slip_path = 'uploads/iibfbcbf/utr_slip';

      
      $this->login_agency_id = $this->login_centre_id = $this->allow_exam_types = '';
      if($this->login_user_type == 'agency')
      {
        $this->login_agency_id = $this->login_agency_or_centre_id;
      }
      else if($this->login_user_type == 'centre')
      {
        $this->login_centre_id = $this->login_agency_or_centre_id;

        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master',array('centre_id'=>$this->login_agency_or_centre_id), 'centre_id, agency_id');
        if(count($centre_data) > 0)
        {
          $this->login_agency_id = $centre_data[0]['agency_id'];
        }
      }

      $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->login_agency_id), "agency_id, allow_exam_codes, allow_exam_types");
      if(count($agency_data) > 0)
      {
        $this->allow_exam_types = $agency_data[0]['allow_exam_types'];
      }

      $this->venue_master_eligible_exam_codes_arr = array(1041,1042,1057);//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041, 1042, 1057. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041, 1042, 1057. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM

      $this->eligible_exam_code_for_admit_card_arr = array(1039,1040,1041,1042,1057); //ADMITCARD IS AVAILABLE ONLY FOR 1039,1040,1041,1042,1057 EXAM CODES. 1039 & 1040 ARE CSC MODE EXAMS. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM
    }
    
    public function index()
    {   
      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Transaction Details';
      $data['allow_exam_types'] = $this->allow_exam_types;

      $data['utr_slip_path'] = $utr_slip_path = $this->utr_slip_path;
      $data['utr_slip_error'] = '';
      $error_flag = 0;

      $data['agency_centre_data'] = array();
      $agency_id = '0';
      if($this->login_user_type == 'agency') 
      { 
        $agency_id = $this->login_agency_or_centre_id; 

        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id'=>$agency_id, 'cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name');
      }
      else if($this->login_user_type == 'centre') 
      { 
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, cm.agency_id, cm.centre_username');
        if(count($centre_data) > 0)
        {
          $agency_id = $centre_data[0]['agency_id'];
        }
      }

      /******** START : CODE TO UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/
      if(isset($_POST) && count($_POST) > 0 && $this->login_user_type == 'centre')
			{
        $enc_payment_id = $this->security->xss_clean($this->input->post('enc_payment_id'));
        $payment_id = url_decode($enc_payment_id);
        
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id' => $payment_id, 'pt.UTR_no' => 'IIBFBCBF-TEMP-UTR-NO', 'status'=>'3'), 'pt.id, pt.exam_ids, pt.amount, pt.exam_code, pt.exam_period', array('pt.id'=>'DESC'));
        if(count($payment_data) == 0)
        {
          $this->session->set_flashdata('error','Invalid request');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
        }
        
        //START : GET ACTIVE EXAM DETAILS
        $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details(url_encode($payment_data[0]['exam_code']), $agency_id,'bulk'); 
        if(count($active_exam_data) == 0)
        {
          $this->session->set_flashdata('error','This exam is not active');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
        }
        else
        {
          if($active_exam_data[0]['exam_period'] != $payment_data[0]['exam_period'])
          {
            $this->session->set_flashdata('error','This exam is not active');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
          }
        }//END : GET ACTIVE EXAM DETAILS
        
        $selcted_member_exam_ids_str = $payment_data[0]['exam_ids'];
        
        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $this->db->where_in('me.pay_status', '3', FALSE);
        $this->db->where_in('me.member_exam_id', $selcted_member_exam_ids_str, FALSE);
        $data['form_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted'=>'0', 'cand.is_deleted'=>'0'), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_centre_code, me.exam_venue_code, me.exam_date, me.exam_time, me.exam_fee, me.pay_status, me.fee_paid_flag, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.registration_type');

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['agency_centre_data'] = $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, am.agency_id, cm.centre_name, cm.gst_no AS CentreGST, cm.centre_state, sm.state_no, sm.state_name, sm.exempt, cm.centre_city, cm.invoice_address, city.city_name, am.agency_name, am.agency_code, am.gst_no AS AgencyGST');
        
        //START : SERVER SIDE VALIDATION
        $this->form_validation->set_rules('utr_no', 'NEFT / RTGS (UTR) Number', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|max_length[30]|callback_validation_check_utr_exist[]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('utr_slip', 'payment (UTR) slip', 'trim|required|callback_fun_validate_file_upload[utr_slip|y|jpg,jpeg,png|100|payment (UTR) slip]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb' 
        /* $this->form_validation->set_rules('gst_centre_id', 'GST No to be displayed on Invoice', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  */        
        //END : SERVER SIDE VALIDATION 

        $up_payment_data = array();
        if($this->form_validation->run() == TRUE)
        { 
          if(in_array($active_exam_data[0]['exam_code'],$this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041, 1042, 1057. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041, 1042, 1057. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM
          {
            foreach ($candidate_data as $candidate_res)
            {
              //echo '<br>'.$active_exam_data[0]['exam_code']." - ".$active_exam_data[0]['exam_period']." - ".$candidate_res['exam_centre_code']." - ".$candidate_res['exam_venue_code']." - ".$candidate_res['exam_date']." - ".$candidate_res['exam_time'];
              $chk_capacity = $this->Iibf_bcbf_model->get_capacity_bulk($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_res['exam_centre_code'], $candidate_res['exam_venue_code'], $candidate_res['exam_date'], $candidate_res['exam_time'], $candidate_res['member_exam_id'], 'make_payment');

              if($chk_capacity <= 0)
              {
                $this->session->set_flashdata('error','The capaciy is full');
                redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
              }
            }
          }          

          if($_FILES['utr_slip']['name'] != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $new_img_name = "utr_slip_".date("YmdHis").'_'.rand(1000,9999);
            $upload_data1 = $this->Iibf_bcbf_model->upload_file("utr_slip", array('png','jpg','jpeg'), $new_img_name, "./".$utr_slip_path, "png|jpeg|jpg",0,'','','','100','1000','500',$new_img_name);
            if($upload_data1['response'] == 'error')
            {
              $data['utr_slip_error'] = $upload_data1['message'];
              $error_flag = 1;
            }
            else if($upload_data1['response'] == 'success')
            {
              $up_payment_data['UTR_slip_file'] = $upload_data1['message'];
            }
          }

          if($error_flag == 1)
          {
            @unlink("./".$utr_slip_path."/".$upload_data1['message']);
          }
          else if($error_flag == 0)
          {            
            $utr_no = $this->security->xss_clean($this->input->post('utr_no')); 
            $payment_date = $this->security->xss_clean($this->input->post('payment_date')); 
            /* $gst_centre_id = $this->security->xss_clean($this->input->post('gst_centre_id')); */
            
            $gstin_no = '-';
            //if($gst_centre_id > 0) { $gstin_no = $agency_centre_data[0]['gst_no']; /* logged in centre GST number */ }
            if($agency_centre_data[0]['invoice_address'] == '1') { $gstin_no = $agency_centre_data[0]['AgencyGST']; }
            else if($agency_centre_data[0]['invoice_address'] == '2') { $gstin_no = $agency_centre_data[0]['CentreGST']; }
            
            //START : UPDATE PAYMENT TABLE ENTRY
            $up_payment_data['UTR_no'] = $utr_no;
            $up_payment_data['date'] = $payment_date;
            $up_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
            $up_payment_data['updated_on'] = date('Y-m-d H:i:s');
            $up_payment_data['updated_by'] = $this->login_agency_or_centre_id;
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $up_payment_data, array('id'=>$payment_id));
            
            $posted_arr = json_encode($_POST);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');
                  
            $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_id,'make_payment','The '.$centreName['disp_name'].' successfully updated the payment details for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', $posted_arr);
            //UPDATE : INSERT PAYMENT TABLE ENTRY
            
            foreach ($candidate_data as $candidate_res)
            {
              //START : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
              $up_exam_arr = array();
              $up_exam_arr['ref_utr_no'] = $utr_no;
              $up_exam_arr['updated_on'] = date('Y-m-d H:i:s');
              $up_exam_arr['updated_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_arr,array('member_exam_id'=>$candidate_res['member_exam_id']));
              //END : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
                    
              $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'iibfbcbf_member_exam', $this->db->last_query(), $candidate_res['member_exam_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated the payment details of the candidate', $posted_arr);

              $this->Iibf_bcbf_model->insert_common_log('Candidate : Update payment details', 'iibfbcbf_batch_candidates', '', $candidate_res['candidate_id'],'candidate_action','The '.$centreName['disp_name'].' successfully updated the payment details for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', '');
            }
              
            //START : UPDATE EXAM INVOICE TABLE ENTRY
            $pg_flag = 'BC';
            $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('pay_txn_id'=>$payment_id, 'receipt_no'=>$payment_id, 'exam_code'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'center_code'=> $agency_centre_data[0]['centre_id'], 'center_name' => $agency_centre_data[0]['centre_name'], 'institute_code' => $agency_centre_data[0]['agency_code'], 'institute_name' => $agency_centre_data[0]['agency_name'], 'app_type'=>$pg_flag), 'invoice_id');            
            if(count($exam_invoice_data) > 0) 
            {
              $up_invoice = array();
              $up_invoice['gstin_no'] = $gstin_no;
              $up_invoice['transaction_no'] = $utr_no;
              $up_invoice['modified_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('exam_invoice',$up_invoice,array('invoice_id'=>$exam_invoice_data[0]['invoice_id']));
              
              $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'exam_invoice', $this->db->last_query(), $exam_invoice_data[0]['invoice_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated record in exam invoice table', $posted_arr);
              //die;
            }//END : UPDATE EXAM INVOICE TABLE ENTRY

            $this->session->set_flashdata('success','NEFT/RTGS Payment details is added and sent for approval');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
          }
        }
      }/******** END : FUNCTION TO UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/

      $this->load->view('iibfbcbf/agency/transaction_details_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_transaction_data_ajax()
    {
      $form_action = '';
      if(isset($_POST['form_action'])) { $form_action = trim($this->security->xss_clean($this->input->post('form_action'))); }

      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")") AS DispCentre', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'pt.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")") AS description', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'pt.exam_code', 'pt.exam_period', '"" AS DispMemberNumbers', '"" AS DispMemberTrainingIds', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=4, "Cancelled", IF(pt.status=3, IF(pt.UTR_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), ""))))) AS DispPayStatus', 'pt.status', 'pt.pg_flag'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")")', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")")', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'pt.exam_code', 'pt.exam_period', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=4, "Cancelled", IF(pt.status=3, IF(pt.UTR_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), "")))))'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC'); // DEFAULT ORDER 

      if($this->allow_exam_types == 'CSC')
      {
        $WhereForTotal = "WHERE pt.payment_done_by_agency_id = '".$this->login_agency_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE pt.payment_done_by_agency_id = '".$this->login_agency_id."' ";

        if($this->login_user_type == 'centre')
        {
          $WhereForTotal .= " AND pt.payment_done_by_centre_id = '".$this->login_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where .= " AND pt.payment_done_by_centre_id = '".$this->login_centre_id."' ";
        }
      }
      else
      {
        if($this->login_user_type == 'centre')
        {
          $WhereForTotal = "WHERE pt.centre_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where = "WHERE pt.centre_id = '".$this->login_agency_or_centre_id."' ";
        }
        else if($this->login_user_type == 'agency')
        {
          $WhereForTotal = "WHERE pt.agency_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where = "WHERE pt.agency_id = '".$this->login_agency_or_centre_id."' ";
        }      
      }
      
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
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND pt.centre_id = '".$s_centre."'"; } 
     
      //$s_member_no = trim($this->security->xss_clean($this->input->post('s_member_no')));
      /* if($s_member_no != "") { $Where .= " AND ((SELECT GROUP_CONCAT(bc.regnumber SEPARATOR ', ') from iibfbcbf_batch_candidates bc WHERE FIND_IN_SET(bc.candidate_id, (SELECT GROUP_CONCAT(me.candidate_id) from iibfbcbf_member_exam me WHERE FIND_IN_SET(me.member_exam_id,pt.exam_ids)))) LIKE '%".$s_member_no."%' )"; }  */
      //if($s_member_no != "") { $Where .= " AND me.regnumber = '".$s_member_no."'"; }
      
      $s_utr_no = trim($this->security->xss_clean($this->input->post('s_utr_no')));
      if($s_utr_no != "") { $Where .= " AND (pt.UTR_no LIKE '%".$s_utr_no."%' OR pt.transaction_no LIKE '%".$s_utr_no."%')"; } 

      $s_receipt_no = trim($this->security->xss_clean($this->input->post('s_receipt_no')));
      if($s_receipt_no != "") { $Where .= " AND pt.receipt_no = '".$s_receipt_no."'"; } 

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
      if($s_payment_status != "") 
      {
        if($s_payment_status == '3') 
        { 
          $Where .= " AND pt.UTR_no = 'IIBFBCBF-TEMP-UTR-NO'"; 
        }
        else if($s_payment_status == '5') 
        { 
          $s_payment_status = '3'; 
          $Where .= " AND pt.UTR_no != 'IIBFBCBF-TEMP-UTR-NO'";
        }

        $Where .= " AND pt.status = '".$s_payment_status."'"; 
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $WhereForTotal .= " GROUP BY pt.id";
      $Where .= " GROUP BY pt.id";
      
      $join_qry = " LEFT JOIN iibfbcbf_exam_master em ON em.exam_code = pt.exam_code"; 
      $join_qry .= " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = pt.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      $join_qry .= " INNER JOIN iibfbcbf_agency_master am ON am.agency_id = pt.agency_id";
      //$join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
      //$join_qry .= " LEFT JOIN iibfbcbf_member_exam me ON me.pt_id = pt.id";     
      //$join_qry .= " LEFT JOIN iibfbcbf_batch_candidates cand ON cand.candidate_id = me.candidate_id"; 
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);

      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Agency_transaction_details_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Centre Name', 'NEFT / RTGS (UTR) or Transaction Number', 'Receipt No.', 'Application', 'Amount', 'No. of Candidates', 'Payment Date', 'Exam Code', 'Exam Period', 'Registration No.', 'Training Ids', 'Payment Mode', 'Status'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }
      
      $data = array();
      $no = $_POST['start'];    
      
      $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
      $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
      $get_active_exam_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0'), "em.exam_code, em.description, em.exam_type, IF(em.exam_type = 1,'Basic', IF(em.exam_type = 2, 'Advanced','')) AS DispExamType, eam.exam_period, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd, eam.exam_from_date, eam.exam_from_time, eam.exam_to_date, eam.exam_to_time");
      $active_exam_code_arr = array();
      if(count($get_active_exam_data) > 0)
      {
        foreach($get_active_exam_data as $get_active_exam_res)
        {
          $active_exam_code_arr[]=$get_active_exam_res['exam_code'];
        }
      }
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
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

        if ($form_action != 'export')
        {
          $row[] = '<span class="badge '.show_payment_status($Res['status']).'" style="width: 120px;white-space: normal;line-height: 15px; word-break: break-word;">'.$Res['DispPayStatus'].'</span>'; //iibf_bcbf_helper.php              
          
          $text_centre_cls = '';
          if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
          {
            $text_centre_cls = 'text-center';
          }
          $btn_str = ' <div class="'.$text_centre_cls.' no_wrap" style="width: 90px; margin: 0 auto;"> ';

          if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC') { }
          else
          {
            $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_agency/'.url_encode($Res['id'])).'" class="btn btn-success btn-xs" title="Payment Receipt"><i class="fa fa-money"></i></a> ';
          }
          
          if($Res['DispTransactionNo'] == "IIBFBCBF-TEMP-UTR-NO" && in_array($Res['exam_code'],$active_exam_code_arr))
          {
            if($this->login_user_type == 'centre')
            {
              $onclick_fun = "update_payment_details_modal('".url_encode($Res['id'])."')";
              $btn_str .= ' <button class="btn btn-warning btn-xs" title="Update Payment Details" onclick="'.$onclick_fun.'"><i class="fa fa-inr"></i></button> ';
            }
            
            $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice/'.url_encode($Res['id'])).'" target="_blank" class="btn btn-info btn-xs" title="Proforma Invoice"><i class="fa fa-list-alt" aria-hidden="true"></i></a> ';
          }        

        $this->db->limit(1);
        $invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.receipt_no' => $Res['receipt_no'], 'ei.exam_code'=>$Res['exam_code'], 'ei.exam_period'=>$Res['exam_period'], 'ei.app_type'=>$Res['pg_flag'], 'ei.invoice_image !='=>''), "invoice_id,gstin_no,invoice_no,created_on");
        
        if(count($invoice_data) > 0 && !empty($invoice_data[0]['invoice_id']) && $invoice_data[0]['invoice_id'] != "")
        {
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/download_file_common/index/'.url_encode($invoice_data[0]['invoice_id']).'/invoice_image').'" class="btn btn-primary btn-xs" title="View Invoice"><i class="fa fa-file-text"></i></a> ';
        }

        if(count($invoice_data) > 0 && !empty($invoice_data[0]['gstin_no']) && $invoice_data[0]['gstin_no'] != "" && $invoice_data[0]['created_on'] >= '2025-06-01 00:01:01')
        {
          $str_invoice_no = str_replace("/","_",$invoice_data[0]['invoice_no']);
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/get_e_invoice/'.url_encode($str_invoice_no)).'" class="btn btn-info btn-xs" title="View E-Invoice"><i class="fa fa-file-text-o"></i></a> ';
        }

          if($Res['status'] == "1" && in_array($Res['exam_code'], $this->eligible_exam_code_for_admit_card_arr))
          {
            if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC') { } 
            else
            {
              $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/admit_cards/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="View Admit Cards"><i class="fa fa-file-text"></i></a> ';
            }
          }
          $btn_str .= ' </div>';
          $row[] = $btn_str; 
        }
        else if ($form_action == 'export')
        {
          $row[] = $Res['DispPayStatus'];
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/  
 
    public function payment_receipt_agency($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Payment Receipt';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
      
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = pt.agency_id', 'INNER');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = pt.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, cm.centre_name, cm.centre_username, cm2.city_name');

      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency')); }

      $this->load->view('iibfbcbf/agency/payment_receipt_agency',$data);
    }

    function payment_receipt_candidate_listing_agency($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Payment Receipt : Candidate List';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
      
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.amount');

      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency')); }

      $this->load->view('iibfbcbf/agency/payment_receipt_candidate_listing_agency',$data);
    }

    function get_payment_receipt_candidate_listing_data_ajax()
    {
      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""), " (", cand.training_id, ")") AS DispCandidateName', 'cand.training_id', 'me.exam_fee', 'me.candidate_id'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""), " (", cand.training_id, ")")', 'cand.training_id', 'me.exam_fee'); //SET COLUMN FOR SEARCH
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
        $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_candidate_agency/'.url_encode($Res['id']).'/'.url_encode($Res['candidate_id'])).'" class="btn btn-success btn-xs" title="Candidate Payment Receipt"><i class="fa fa-money"></i></a> ';
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

    function payment_receipt_candidate_agency($enc_pt_id=0, $enc_candidate_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);
      
      $data['enc_candidate_id'] = $enc_candidate_id;
      $candidate_id = url_decode($enc_candidate_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Candidate Payment Receipt';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }

      $this->db->join('iibfbcbf_member_exam me', 'FIND_IN_SET(me.member_exam_id, pt.exam_ids)', 'INNER');
      $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
      $this->db->join('iibfbcbf_exam_medium_master mm', 'mm.medium_code = me.exam_medium AND mm.exam_code = me.exam_code', 'LEFT');
      $this->db->join('iibfbcbf_exam_centre_master ecm', 'ecm.centre_code = me.exam_centre_code AND ecm.exam_name = me.exam_code', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id, 'cand.candidate_id'=>$candidate_id), 'pt.id, pt.agency_id, pt.centre_id, pt.exam_ids, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, me.member_exam_id, me.candidate_id, me.exam_fee, me.exam_centre_code, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.training_id, cand.email_id, mm.medium_description, ecm.centre_name');
      
      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_candidate_listing_agency/'.$enc_pt_id)); }

      $this->load->view('iibfbcbf/agency/payment_receipt_candidate_agency',$data);
    }

    /******** START : FUNCTION TO GET THE POP UP FOR UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/
    function get_payment_details_modal_ajax()
    {
      $flag = "error";
      $response = "";

			if(isset($_POST) && count($_POST) > 0 && $this->login_user_type == 'centre')
			{
        $enc_payment_id = $this->security->xss_clean($this->input->post('enc_payment_id'));
        $payment_id = url_decode($enc_payment_id);
        
        $data['form_utr_no'] = $this->security->xss_clean($this->input->post('form_utr_no'));
        $data['form_payment_date'] = $this->security->xss_clean($this->input->post('form_payment_date'));
        //$data['form_gst_centre_id'] = $this->security->xss_clean($this->input->post('form_gst_centre_id'));
        $data['utr_slip_error'] = $this->security->xss_clean($this->input->post('utr_slip_error'));
        $data['form_utr_no_error'] = $this->security->xss_clean($this->input->post('form_utr_no_error'));
        $data['form_payment_date_error'] = $this->security->xss_clean($this->input->post('form_payment_date_error'));
        $data['form_utr_slip_error'] = $this->security->xss_clean($this->input->post('form_utr_slip_error'));
        //$data['form_gst_centre_id_error'] = $this->security->xss_clean($this->input->post('form_gst_centre_id_error'));
        
        $data['payment_data'] = $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id' => $payment_id, 'pt.UTR_no' => 'IIBFBCBF-TEMP-UTR-NO'), 'pt.id, pt.exam_ids, pt.amount, pt.exam_code, pt.exam_period', array('pt.id'=>'DESC'));
        if(count($payment_data) > 0)
				{
					$this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
          $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
          $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
          $data['agency_centre_data'] = $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, am.agency_id, cm.centre_name, cm.gst_no AS CentreGST, cm.centre_state, sm.state_no, sm.state_name, sm.exempt, cm.centre_city, cm.invoice_address, city.city_name, am.agency_name, am.agency_code, am.gst_no AS AgencyGST');
          
          $agency_id = '0';
          if(count($agency_centre_data) > 0) { $agency_id = $agency_centre_data[0]['agency_id']; }
          $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details(url_encode($payment_data[0]['exam_code']), $agency_id,'bulk'); 
          
          if(count($active_exam_data) > 0)
          { 
            if($active_exam_data[0]['exam_period'] == $payment_data[0]['exam_period'])
            {
              $flag = "success";
              $response = $this->load->view('iibfbcbf/agency/inc_update_payment_details_modal',$data, true);
            }
          }
        }				
      }      

      $result = array();
      $result['flag'] = $flag;
      $result['response'] = $response;
      echo json_encode($result);
    }/******** END : FUNCTION TO GET THE POP UP FOR UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/

    /******** START : VALIDATION FUNCTION TO CHECK UTR NUMBER EXIST OR NOT ********/
    public function validation_check_utr_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['utr_no'] != "")
			{
        if($type == '1') 
        { 
          $utr_no = $this->security->xss_clean($this->input->post('utr_no'));          
        }
        else 
        { 
          $utr_no = $str;
        }

        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('utr_no' => $utr_no), 'utr_no');
      
        if(count($payment_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['utr_no'] != "")
        {
          $this->form_validation->set_message('validation_check_utr_exist','This UTR No is already present. Please enter unique utr no.');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK UTR NUMBER EXIST OR NOT ********/

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

    /******** START : VALIDATION FUNCTION TO CHECK VALID FILE ********/
    function fun_validate_file_upload($str,$parameter) // Custom callback function for check valid file
    {
      $result = $this->Iibf_bcbf_model->fun_validate_file_upload($parameter); 
      if($result['flag'] == 'success') { return true; }
      else
      {
        $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
        return false;
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID FILE ********/

    /******** START : FUNCTION TO DISPLAY THE Proforma INVOICE ********/
    public function proforma_invoice($enc_pt_id=0)
    {
      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
      if($this->login_user_type == 'agency') { $this->db->where('cm.agency_id', $this->login_agency_or_centre_id); }
      else if($this->login_user_type == 'centre') { $this->db->where('cm.centre_id', $this->login_agency_or_centre_id); }
      $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array(), 'cm.centre_id, cm.agency_id, cm.centre_name, cm.gst_no, cm.centre_state, cm.centre_city, cm.centre_address1, cm.centre_address2, cm.centre_address3, cm.centre_address4, cm.centre_district, sm.state_name AS CentreState, sm.state_no, am.agency_name, am.agency_code, city.city_name');

      $pt_id = url_decode($enc_pt_id);
      $this->db->join('exam_invoice ei', 'ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag', 'INNER');
      if($this->login_user_type == 'agency') { $this->db->where('pt.agency_id', $this->login_agency_or_centre_id); }
      else if($this->login_user_type == 'centre') { $this->db->where('pt.centre_id', $this->login_agency_or_centre_id); }
      $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id'=>$pt_id, 'pt.status'=>'3', 'pt.payment_mode'=>'Bulk'), 'pt.id, pt.exam_ids, pt.exam_code, pt.exam_period, pt.gateway, pt.amount, pt.UTR_no, pt.UTR_slip_file, pt.pay_count, ei.fresh_fee, ei.rep_fee, ei.fresh_count, ei.rep_count, ei.fee_amt, ei.cgst_rate, ei.cgst_amt, ei.sgst_rate, ei.sgst_amt, ei.cs_total, ei.igst_rate, ei.igst_amt, ei.igst_total, ei.transaction_no');
      
      if(count($payment_data) == 0)
      {
        $this->session->set_flashdata('error','Record not found');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
      //$join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
      //_pa($payment_data,1);
      /* echo $pt_id; exit; */
      
      $fresh_cnt = $payment_data[0]['fresh_count'];
      $repeater_cnt = $payment_data[0]['rep_count'];
      $wordamt = convert_amount_into_words(intval($payment_data[0]['amount']));
      $final_total = $payment_data[0]['amount'];	
      $total_fresh_fee = $fresh_cnt * $payment_data[0]['fresh_fee'];
			$total_rep_fee = $repeater_cnt * $payment_data[0]['rep_fee'];			
			
			$igst_total = $sgst_amt = $cs_amnt = '';			
			if($agency_centre_data[0]['centre_state'] == 'MAH')
      {	
				$cs_amnt = $payment_data[0]['cgst_amt'];
				$sgst_amt = $payment_data[0]['sgst_amt'];
      }
      elseif($agency_centre_data[0]['centre_state'] != 'MAH')
      {
        $igst_total = $payment_data[0]['igst_amt'];
      }
			
			$date_of_invoice = date("d-m-Y"); 
			$address = rtrim($agency_centre_data[0]['centre_address1'],",");
      if($agency_centre_data[0]['centre_address2'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_address2']),","); }
      if($agency_centre_data[0]['centre_address3'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_address3']),","); }
      if($agency_centre_data[0]['centre_address4'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_address4']),","); }
      if($agency_centre_data[0]['centre_district'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_district']),","); }
      if($agency_centre_data[0]['city_name'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['city_name']),","); }
			
      $data['amount_in_word'] = $wordamt;
      $data['invoice_no'] = 'TEMP_INVOICE_NO';
      $data['date_of_invoice'] = $date_of_invoice;
      $data['transaction_no'] = $payment_data[0]['transaction_no'];
      $data['recepient_name'] = $agency_centre_data[0]['centre_name']." (".$agency_centre_data[0]['agency_name']." - ".$agency_centre_data[0]['agency_code'].")";
      $data['address'] = $address;
      $data['centre_state'] = $agency_centre_data[0]['CentreState'];
      $data['centre_state_code'] = $agency_centre_data[0]['state_no'];
      $data['centre_gstn'] = $agency_centre_data[0]['gst_no'];
      $data['fresh_fee_amount'] = number_format_upto2($payment_data[0]['fresh_fee']);
      $data['rep_fee_amount'] = number_format_upto2($payment_data[0]['rep_fee']);
      $data['discount_amt'] = '-';
      $data['net_amt'] = '-';
      $data['ste_code'] = $agency_centre_data[0]['centre_state'];
      $data['cgst_rate'] = number_format_upto2($payment_data[0]['cgst_rate']);
      $data['cgst_amt'] = $cs_amnt;
      $data['sgst_rate'] = number_format_upto2($payment_data[0]['sgst_rate']);
      $data['sgst_amt'] = $sgst_amt;
      $data['final_total'] = $final_total;
      $data['igst_total'] = $igst_total;
      $data['invoice_number'] ='TEMP_INVOICE_NO';
      $data['igst_rate'] = number_format_upto2($payment_data[0]['igst_rate']);
      $data['total_fresh_fee'] = $total_fresh_fee;
      $data['total_rep_fee'] = $total_rep_fee;
      $data['fresh_count'] = $fresh_cnt;
      $data['rep_count'] = $repeater_cnt;
      
			$this->load->view('iibfbcbf/agency/proforma_invoice_agency',$data);			
		}/******** END : FUNCTION TO DISPLAY THE Proforma INVOICE ********/

    /******** START : FUNCTION TO DISPLAY THE Candidates Admit Card ********/
    public function admit_cards($enc_pt_id=0)
    { 

      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access Admit Card module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Candidate Admitcard : Candidate Candidate Admitcard List';
      
      $this->load->view('iibfbcbf/agency/admit_cards',$data);     
    }/******** END : FUNCTION TO DISPLAY THE Candidates Admit Card ********/

    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE ADMITCARD DATA ********/
    public function get_admitcard_data_ajax()
    {
      $table = 'iibfbcbf_admit_card_details bc'; 
      $GroupBy = "";

      $pt_id_enc = trim($this->security->xss_clean($this->input->post('pt_id_enc'))); 
      $pt_id = url_decode($pt_id_enc);
       
      $column_order = array('bc.admitcard_id', 'bc.centre_name', 'bc.exm_cd', 'bc.exm_prd', 'bc.mem_mem_no AS no_of_candidates', 'ca.training_id', 'bc.mam_nam_1', 'ca.mobile_no', 'ca.email_id', 'bc.inscd', 'bc.centre_code', 'bc.exam_date'); //SET COLUMNS FOR SORT
      $column_search = array('bc.centre_name', 'bc.exm_cd', 'bc.exm_prd', 'bc.mem_mem_no'); //SET COLUMN FOR SEARCH 
      $order = array('bc.admitcard_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE bc.pt_id = '".$pt_id."'";  //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE bc.pt_id = '".$pt_id."'";   

      /* if($this->login_user_type == 'agency')
      {
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$this->login_agency_or_centre_id), 'agency_code');

        $WhereForTotal .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' "; // AND bc.exm_cd IN('1037','1038') //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' "; //  AND bc.exm_cd IN('1037','1038')
      }
      else if($this->login_user_type == 'centre')
      {
        $center_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id'=>$this->login_agency_or_centre_id), 'centre_username,agency_id');

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$center_data[0]["agency_id"]), 'agency_code');

        $WhereForTotal .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' AND bc.centre_code = '".$center_data[0]["centre_username"]."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' AND bc.centre_code = '".$center_data[0]["centre_username"]."' "; // AND bm.exam_code = '1038'
      } */      
      
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
        $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/download_admitcard_pdf/'.url_encode($Res['admitcard_id'])).'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';

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
      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access Admit Card module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }

      $this->Iibf_bcbf_model->download_admit_card_pdf_single($enc_admitcard_id, 'download');
    }

    /* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */
    function get_directory_list($dir_name)
    {
      return $this->array_sort_ascending(directory_map('./'.$dir_name, 1)); // This is use to get all folders and files from current directory excluding subfolders
    }
    
    /* SORT ARRAY IN ASCENDING ORDER USING VALUES NOT KEY */
    function array_sort_ascending($array)
    {
      if($array != "") { sort($array); /* sort() - sort arrays in ascending order. rsort() - sort arrays in descending order. */ }
      return $array;
    }
    
    /* RECURSIVE FUNCTION TO DELETE ALL SUB FILES AND FOLDER FROM REQUIRED FOLDER */
    function rmdir_recursive($dir) 
    {
      foreach(scandir($dir) as $file) 
      {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) 
        {
          $this->rmdir_recursive("$dir/$file");
        }
        else unlink("$dir/$file");
      }
      rmdir($dir);
    }

    /* Function to download E-invoice Added By ANIL S on 07 July 2025 for IIBF BCBF */
    public function get_e_invoice($enc_inv_no)
    {       
      $this->Iibf_bcbf_model->download_e_invoice($enc_inv_no);       
    }
    /* Function to download E-invoice Added By ANIL S on 07 July 2025 for IIBF BCBF */


    ///////////////// ONLINE PAYMENT IMPLEMENTATION ////////////////

    public function proforma_invoice_list()
    {   
      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Proforma Invoice List";
      $data['page_title'] = 'IIBF - BCBF Agency Proforma Invoice List';
      $data['allow_exam_types'] = $this->allow_exam_types;

      $data['utr_slip_path'] = $utr_slip_path = $this->utr_slip_path;
      $data['utr_slip_error'] = '';
      $error_flag = 0;

      $data['agency_centre_data'] = array();
      $agency_id = '0';
      if($this->login_user_type == 'agency') 
      { 
        $agency_id = $this->login_agency_or_centre_id; 

        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id'=>$agency_id, 'cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name');
      }
      else if($this->login_user_type == 'centre') 
      { 
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, cm.agency_id, cm.centre_username');
        if(count($centre_data) > 0)
        {
          $agency_id = $centre_data[0]['agency_id'];
        }
      }

      /******** START : CODE TO UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/
      if(isset($_POST) && count($_POST) > 0 && $this->login_user_type == 'centre')
      {
        $enc_payment_id = $this->security->xss_clean($this->input->post('enc_payment_id'));
        $payment_id = url_decode($enc_payment_id);
        
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id' => $payment_id, 'pt.UTR_no' => 'IIBFBCBF-TEMP-UTR-NO', 'status'=>'3'), 'pt.id, pt.exam_ids, pt.amount, pt.exam_code, pt.exam_period', array('pt.id'=>'DESC'));
        if(count($payment_data) == 0)
        {
          $this->session->set_flashdata('error','Invalid request');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
        }
        
        //START : GET ACTIVE EXAM DETAILS
        $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details(url_encode($payment_data[0]['exam_code']), $agency_id,'bulk'); 
        if(count($active_exam_data) == 0)
        {
          $this->session->set_flashdata('error','This exam is not active');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
        }
        else
        {
          if($active_exam_data[0]['exam_period'] != $payment_data[0]['exam_period'])
          {
            $this->session->set_flashdata('error','This exam is not active');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
          }
        }//END : GET ACTIVE EXAM DETAILS
        
        $selcted_member_exam_ids_str = $payment_data[0]['exam_ids'];
        
        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $this->db->where_in('me.pay_status', '3', FALSE);
        $this->db->where_in('me.member_exam_id', $selcted_member_exam_ids_str, FALSE);
        $data['form_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted'=>'0', 'cand.is_deleted'=>'0'), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_centre_code, me.exam_venue_code, me.exam_date, me.exam_time, me.exam_fee, me.pay_status, me.fee_paid_flag, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.registration_type');

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['agency_centre_data'] = $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, am.agency_id, cm.centre_name, cm.gst_no AS CentreGST, cm.centre_state, sm.state_no, sm.state_name, sm.exempt, cm.centre_city, cm.invoice_address, city.city_name, am.agency_name, am.agency_code, am.gst_no AS AgencyGST');
        
        //START : SERVER SIDE VALIDATION
        $this->form_validation->set_rules('utr_no', 'NEFT / RTGS (UTR) Number', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|max_length[30]|callback_validation_check_utr_exist[]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('utr_slip', 'payment (UTR) slip', 'trim|required|callback_fun_validate_file_upload[utr_slip|y|jpg,jpeg,png|100|payment (UTR) slip]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb' 
        /* $this->form_validation->set_rules('gst_centre_id', 'GST No to be displayed on Invoice', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  */        
        //END : SERVER SIDE VALIDATION 

        $up_payment_data = array();
        if($this->form_validation->run() == TRUE)
        { 
          if(in_array($active_exam_data[0]['exam_code'],$this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041, 1042, 1057. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041, 1042, 1057. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM
          {
            foreach ($candidate_data as $candidate_res)
            {
              //echo '<br>'.$active_exam_data[0]['exam_code']." - ".$active_exam_data[0]['exam_period']." - ".$candidate_res['exam_centre_code']." - ".$candidate_res['exam_venue_code']." - ".$candidate_res['exam_date']." - ".$candidate_res['exam_time'];
              $chk_capacity = $this->Iibf_bcbf_model->get_capacity_bulk($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_res['exam_centre_code'], $candidate_res['exam_venue_code'], $candidate_res['exam_date'], $candidate_res['exam_time'], $candidate_res['member_exam_id'], 'make_payment');

              if($chk_capacity <= 0)
              {
                $this->session->set_flashdata('error','The capaciy is full');
                redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
              }
            }
          }          

          if($_FILES['utr_slip']['name'] != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $new_img_name = "utr_slip_".date("YmdHis").'_'.rand(1000,9999);
            $upload_data1 = $this->Iibf_bcbf_model->upload_file("utr_slip", array('png','jpg','jpeg'), $new_img_name, "./".$utr_slip_path, "png|jpeg|jpg",0,'','','','100','1000','500',$new_img_name);
            if($upload_data1['response'] == 'error')
            {
              $data['utr_slip_error'] = $upload_data1['message'];
              $error_flag = 1;
            }
            else if($upload_data1['response'] == 'success')
            {
              $up_payment_data['UTR_slip_file'] = $upload_data1['message'];
            }
          }

          if($error_flag == 1)
          {
            @unlink("./".$utr_slip_path."/".$upload_data1['message']);
          }
          else if($error_flag == 0)
          {            
            $utr_no = $this->security->xss_clean($this->input->post('utr_no')); 
            $payment_date = $this->security->xss_clean($this->input->post('payment_date')); 
            /* $gst_centre_id = $this->security->xss_clean($this->input->post('gst_centre_id')); */
            
            $gstin_no = '-';
            //if($gst_centre_id > 0) { $gstin_no = $agency_centre_data[0]['gst_no']; /* logged in centre GST number */ }
            if($agency_centre_data[0]['invoice_address'] == '1') { $gstin_no = $agency_centre_data[0]['AgencyGST']; }
            else if($agency_centre_data[0]['invoice_address'] == '2') { $gstin_no = $agency_centre_data[0]['CentreGST']; }
            
            //START : UPDATE PAYMENT TABLE ENTRY
            $up_payment_data['UTR_no'] = $utr_no;
            $up_payment_data['date'] = $payment_date;
            $up_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
            $up_payment_data['updated_on'] = date('Y-m-d H:i:s');
            $up_payment_data['updated_by'] = $this->login_agency_or_centre_id;
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $up_payment_data, array('id'=>$payment_id));
            
            $posted_arr = json_encode($_POST);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');
                  
            $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_id,'make_payment','The '.$centreName['disp_name'].' successfully updated the payment details for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', $posted_arr);
            //UPDATE : INSERT PAYMENT TABLE ENTRY
            
            foreach ($candidate_data as $candidate_res)
            {
              //START : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
              $up_exam_arr = array();
              $up_exam_arr['ref_utr_no'] = $utr_no;
              $up_exam_arr['updated_on'] = date('Y-m-d H:i:s');
              $up_exam_arr['updated_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_arr,array('member_exam_id'=>$candidate_res['member_exam_id']));
              //END : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
                    
              $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'iibfbcbf_member_exam', $this->db->last_query(), $candidate_res['member_exam_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated the payment details of the candidate', $posted_arr);

              $this->Iibf_bcbf_model->insert_common_log('Candidate : Update payment details', 'iibfbcbf_batch_candidates', '', $candidate_res['candidate_id'],'candidate_action','The '.$centreName['disp_name'].' successfully updated the payment details for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', '');
            }
              
            //START : UPDATE EXAM INVOICE TABLE ENTRY
            $pg_flag = 'BC';
            $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('pay_txn_id'=>$payment_id, 'receipt_no'=>$payment_id, 'exam_code'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'center_code'=> $agency_centre_data[0]['centre_id'], 'center_name' => $agency_centre_data[0]['centre_name'], 'institute_code' => $agency_centre_data[0]['agency_code'], 'institute_name' => $agency_centre_data[0]['agency_name'], 'app_type'=>$pg_flag), 'invoice_id');            
            if(count($exam_invoice_data) > 0) 
            {
              $up_invoice = array();
              $up_invoice['gstin_no'] = $gstin_no;
              $up_invoice['transaction_no'] = $utr_no;
              $up_invoice['modified_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('exam_invoice',$up_invoice,array('invoice_id'=>$exam_invoice_data[0]['invoice_id']));
              
              $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'exam_invoice', $this->db->last_query(), $exam_invoice_data[0]['invoice_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated record in exam invoice table', $posted_arr);
              //die;
            }//END : UPDATE EXAM INVOICE TABLE ENTRY

            $this->session->set_flashdata('success','NEFT/RTGS Payment details is added and sent for approval');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
          }
        }
      }/******** END : FUNCTION TO UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/

      $this->load->view('iibfbcbf/agency/proforma_invoice_list', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_proforma_invoice_transaction_data_ajax()
    {
      $form_action = '';
      if(isset($_POST['form_action'])) { $form_action = trim($this->security->xss_clean($this->input->post('form_action'))); }

      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")") AS DispCentre', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'pt.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")") AS description', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'pt.exam_code', 'pt.exam_period', '"" AS DispMemberNumbers', '"" AS DispMemberTrainingIds', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=4, "Cancelled", IF(pt.status=3, IF(pt.UTR_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), ""))))) AS DispPayStatus', 'pt.status', 'pt.pg_flag'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")")', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")")', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'pt.exam_code', 'pt.exam_period', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=4, "Cancelled", IF(pt.status=3, IF(pt.UTR_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), "")))))'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC'); // DEFAULT ORDER 

      if($this->allow_exam_types == 'CSC')
      {
        $WhereForTotal = "WHERE pt.payment_done_by_agency_id = '".$this->login_agency_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE pt.payment_done_by_agency_id = '".$this->login_agency_id."' ";

        if($this->login_user_type == 'centre')
        {
          $WhereForTotal .= " AND pt.payment_done_by_centre_id = '".$this->login_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where .= " AND pt.payment_done_by_centre_id = '".$this->login_centre_id."' ";
        }
      }
      else
      {
        if($this->login_user_type == 'centre')
        {
          $WhereForTotal = "WHERE pt.centre_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where = "WHERE pt.centre_id = '".$this->login_agency_or_centre_id."' ";
        }
        else if($this->login_user_type == 'agency')
        {
          $WhereForTotal = "WHERE pt.agency_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where = "WHERE pt.agency_id = '".$this->login_agency_or_centre_id."' ";
        }      
      }
      
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
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND pt.centre_id = '".$s_centre."'"; } 
     
      //$s_member_no = trim($this->security->xss_clean($this->input->post('s_member_no')));
      /* if($s_member_no != "") { $Where .= " AND ((SELECT GROUP_CONCAT(bc.regnumber SEPARATOR ', ') from iibfbcbf_batch_candidates bc WHERE FIND_IN_SET(bc.candidate_id, (SELECT GROUP_CONCAT(me.candidate_id) from iibfbcbf_member_exam me WHERE FIND_IN_SET(me.member_exam_id,pt.exam_ids)))) LIKE '%".$s_member_no."%' )"; }  */
      //if($s_member_no != "") { $Where .= " AND me.regnumber = '".$s_member_no."'"; }
      
      $s_utr_no = trim($this->security->xss_clean($this->input->post('s_utr_no')));
      if($s_utr_no != "") { $Where .= " AND (pt.UTR_no LIKE '%".$s_utr_no."%' OR pt.transaction_no LIKE '%".$s_utr_no."%')"; } 

      $s_receipt_no = trim($this->security->xss_clean($this->input->post('s_receipt_no')));
      if($s_receipt_no != "") { $Where .= " AND pt.receipt_no = '".$s_receipt_no."'"; } 

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
      if($s_payment_status != "") 
      {
        if($s_payment_status == '3') 
        { 
          $Where .= " AND pt.UTR_no = 'IIBFBCBF-TEMP-UTR-NO'"; 
        }
        else if($s_payment_status == '5') 
        { 
          $s_payment_status = '3'; 
          $Where .= " AND pt.UTR_no != 'IIBFBCBF-TEMP-UTR-NO'";
        }

        $Where .= " AND pt.status = '".$s_payment_status."'"; 
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $WhereForTotal .= " GROUP BY pt.id";
      $Where .= " GROUP BY pt.id";
      
      $join_qry = " LEFT JOIN iibfbcbf_exam_master em ON em.exam_code = pt.exam_code"; 
      $join_qry .= " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = pt.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      $join_qry .= " INNER JOIN iibfbcbf_agency_master am ON am.agency_id = pt.agency_id";
      //$join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
      //$join_qry .= " LEFT JOIN iibfbcbf_member_exam me ON me.pt_id = pt.id";     
      //$join_qry .= " LEFT JOIN iibfbcbf_batch_candidates cand ON cand.candidate_id = me.candidate_id"; 
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);

      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Agency_transaction_details_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Centre Name', 'NEFT / RTGS (UTR) or Transaction Number', 'Receipt No.', 'Application', 'Amount', 'No. of Candidates', 'Payment Date', 'Exam Code', 'Exam Period', 'Registration No.', 'Training Ids', 'Payment Mode', 'Status'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }
      
      $data = array();
      $no = $_POST['start'];    
      
      $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
      $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
      $get_active_exam_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0'), "em.exam_code, em.description, em.exam_type, IF(em.exam_type = 1,'Basic', IF(em.exam_type = 2, 'Advanced','')) AS DispExamType, eam.exam_period, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd, eam.exam_from_date, eam.exam_from_time, eam.exam_to_date, eam.exam_to_time");
      $active_exam_code_arr = array();
      if(count($get_active_exam_data) > 0)
      {
        foreach($get_active_exam_data as $get_active_exam_res)
        {
          $active_exam_code_arr[]=$get_active_exam_res['exam_code'];
        }
      }
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
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

        if ($form_action != 'export')
        {
          $row[] = '<span class="badge '.show_payment_status($Res['status']).'" style="width: 120px;white-space: normal;line-height: 15px; word-break: break-word;">'.$Res['DispPayStatus'].'</span>'; //iibf_bcbf_helper.php              
          
          $text_centre_cls = '';
          if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
          {
            $text_centre_cls = 'text-center';
          }
          $btn_str = ' <div class="'.$text_centre_cls.' no_wrap" style="width: 150px; margin: 0 auto;"> ';

          
          
          if($Res['DispTransactionNo'] == "IIBFBCBF-TEMP-UTR-NO" && in_array($Res['exam_code'],$active_exam_code_arr))
          {
            if($this->login_user_type == 'centre')
            {
              
              if($Res['status'] != "1")
              { 
                $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/transaction_details_agency/view_online_payment_agency/'.url_encode($Res['id'])).'" target="_blank" class="btn btn-success btn" title="Make Payment">Make Payment</a> <br><br>';
              }              

              $onclick_fun = "update_payment_details_modal('".url_encode($Res['id'])."')";
              $btn_str .= ' <button class="btn btn-warning btn-xs" title="Update Payment Details" onclick="'.$onclick_fun.'"><i class="fa fa-inr"></i></button> '; 
              
            }
            
            $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice/'.url_encode($Res['id'])).'" target="_blank" class="btn btn-info btn-xs" title="Proforma Invoice"><i class="fa fa-list-alt" aria-hidden="true"></i></a> ';
          }     

          if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC') { }
          else
          {
            $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_agency/'.url_encode($Res['id'])).'" class="btn btn-success btn-xs" title="Payment Receipt"><i class="fa fa-money"></i></a> ';
          }   

        $this->db->limit(1);
        $invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.receipt_no' => $Res['receipt_no'], 'ei.exam_code'=>$Res['exam_code'], 'ei.exam_period'=>$Res['exam_period'], 'ei.app_type'=>$Res['pg_flag'], 'ei.invoice_image !='=>''), "invoice_id,gstin_no,invoice_no,created_on");
        
        if(count($invoice_data) > 0 && !empty($invoice_data[0]['invoice_id']) && $invoice_data[0]['invoice_id'] != "")
        {
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/download_file_common/index/'.url_encode($invoice_data[0]['invoice_id']).'/invoice_image').'" class="btn btn-primary btn-xs" title="View Invoice"><i class="fa fa-file-text"></i></a> ';
        }

        if(count($invoice_data) > 0 && !empty($invoice_data[0]['gstin_no']) && $invoice_data[0]['gstin_no'] != "" && $invoice_data[0]['created_on'] >= '2025-06-01 00:01:01')
        {
          $str_invoice_no = str_replace("/","_",$invoice_data[0]['invoice_no']);
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/get_e_invoice/'.url_encode($str_invoice_no)).'" class="btn btn-info btn-xs" title="View E-Invoice"><i class="fa fa-file-text-o"></i></a> ';
        }

          if($Res['status'] == "1" && in_array($Res['exam_code'], $this->eligible_exam_code_for_admit_card_arr))
          {
            if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC') { } 
            else
            {
              $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/admit_cards/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="View Admit Cards"><i class="fa fa-file-text"></i></a> ';
            }
          }
          $btn_str .= ' </div>';
          $row[] = $btn_str; 
        }
        else if ($form_action == 'export')
        {
          $row[] = $Res['DispPayStatus'];
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/  

    /*Function to view payment information for online payment Added By ANIL S on 03 Dec 2025 for IIBF BCBF*/
    public function view_online_payment_agency($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "View Online Payment Information ";
      $data['page_title'] = 'IIBF - BCBF Agency Online Payment Information ';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
       
      $this->db->join('iibfbcbf_exam_master em', 'em.exam_code = pt.exam_code', 'INNER');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = pt.agency_id', 'INNER');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = pt.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, cm.centre_name, cm.centre_username, cm2.city_name, CONCAT(em.description," (", em.exam_code, ")") AS description');

      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency')); }

      $this->load->view('iibfbcbf/agency/view_online_payment_agency',$data);
    }
    /*Function to view payment information for online payment Added By ANIL S on 03 Dec 2025 for IIBF BCBF*/


    /*Function to make online payment for Bulk Added By ANIL S on 04 Dec 2025 for IIBF BCBF*/
    public function make_online_payment_agency($enc_pt_id)
    {
      if($enc_pt_id)
      {
        $pt_id = url_decode($enc_pt_id);
        $this->db->where('status !=',4);
        $paymentTransInfo = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id' => $pt_id));

        if (count($paymentTransInfo) <= 0) {
          $this->session->set_flashdata('error', 'Payment/Proformo details are not found, or a Proforma invoice is invoked.'); 
            redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list'));
        }

        $proforma_no = $paymentTransInfo[0]['proformo_invoice_no'];

        $exam_code = $paymentTransInfo[0]['exam_code'];
        $exam_period = $paymentTransInfo[0]['exam_period'];
        
        $this->db->order_by('exam_period','DESC');
        $active_exam_info =$this->master_model->getRecords('iibfbcbf_exam_activation_master', array(
          'exam_code' => $exam_code,
          'exam_period' => $exam_period,
          'exam_activation_delete' => 0
        ) );

        $active_exam_from_date = isset($active_exam_info[0]['exam_from_date']) ? $active_exam_info[0]['exam_from_date'] : 0;
        $active_exam_to_date   = isset($active_exam_info[0]['exam_to_date']) ? $active_exam_info[0]['exam_to_date'] : 0;    

        $current_date  = date('Y-m-d');
        if ($current_date >= $active_exam_from_date && $current_date <= $active_exam_to_date)
        {
            $this->db->where('proformo_invoice_no',$proforma_no);
            $this->db->where_in('status',[1,6]);
            $successTransInfo = $this->master_model->getRecords('iibfbcbf_payment_transaction');

            if (count($successTransInfo) > 0) {
              $this->session->set_flashdata('error', 'Payment has already been completed/inprocess for this transaction.');
              redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
            }
            if (isset($paymentTransInfo[0]['status']) && ($paymentTransInfo[0]['status'] == 1 || $paymentTransInfo[0]['status'] == 6) ) {
              $this->session->set_flashdata('error', 'Payment has already been completed/inprocess for this transaction.');
              redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list'));
            }
        }
        else
        {
          $this->session->set_flashdata('error', 'Unfortunately, you are not within the exam eligibility period.');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
        }

        $bulk_exam_invoice_data=$this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pt_id,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'app_type'=>'BC')); 

        /*START: To initiate the payment process*/
        if( isset($paymentTransInfo) && count($paymentTransInfo)>0 )
        {
          if( $paymentTransInfo[0]['status'] == 0 || $paymentTransInfo[0]['status'] == 7 )
          {
            $proformo_invoice_no = $paymentTransInfo[0]['proformo_invoice_no'];

            // get the last or previous inprocess transation so we cant process the current transaction
            $InprocessPayment = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('proformo_invoice_no' => $proformo_invoice_no,'status' => 6));

            if (count($InprocessPayment) > 0) 
            {
              $this->session->set_flashdata('error', 'Payment has already been inprocess for this transaction.');
              redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
            }

            // Update status = 7 so that the Make Payment button does not appear
            $update_trans_data = $log_trans_all_data = array('status' => 7);
            $this->master_model->updateRecord('iibfbcbf_payment_transaction',$update_trans_data,array('id'=>$pt_id));

            $iibfbcbf_member_exam = $this->master_model->getRecords('iibfbcbf_member_exam',array('pt_id' => $pt_id)); 

            $arrPaymentTransInfo = array_shift($paymentTransInfo);
            
            $arr_insert_data = $arrPaymentTransInfo;

            unset($arr_insert_data['id']);
            

            $arr_insert_data['created_on']   = date('Y-m-d H:i:s');
            $arr_insert_data['status'] = 6; //status = 6 is Online Bulk Payment Inprocess

            $pt_id = $this->master_model->insertRecord('iibfbcbf_payment_transaction',$arr_insert_data, true);    
 
            $MerchantOrderNo = sbi_exam_order_id($pt_id); //getregnumber_helper.php

            //$ref4 = $exam_code.date('Y').date('m'); 
            //324^BC^IIBF_BULK_BCBF^100034
            $custom_field          = $pt_id."^BC^IIBF_BULK_BCBF^".$MerchantOrderNo;
            $custom_field_billdesk = $pt_id."-BC-IIBF_BULK_BCBF-" . $MerchantOrderNo;

            // update receipt no. in dra payment transaction -
            $update_trans_data = $log_trans_all_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('iibfbcbf_payment_transaction',$update_trans_data,array('id'=>$pt_id));
            
            $newPaymentTransactionEntry = 1; 

            $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Initiated', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'transaction_action','The Online Payment Initiated by the Centre', json_encode($log_trans_all_data)); 

            $arr_update_member_exam = [];
            if($iibfbcbf_member_exam)
            {

              //Update Existing Member Exam Records
              $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Initiated - Before Update New Payment Id in iibfbcbf_member_exam table', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'transaction_action','Before New Payment Id update in iibfbcbf_member_exam table', json_encode($iibfbcbf_member_exam)); 

              foreach ($iibfbcbf_member_exam as $key => $pfMember) 
              { 
                $update_iibfbcbf_member_exam_data['pt_id']       = $pt_id;
                $update_iibfbcbf_member_exam_data['created_on']  = date('Y-m-d H:i:s');  

                $this->master_model->updateRecord('iibfbcbf_member_exam',$update_iibfbcbf_member_exam_data,array('member_exam_id'=>$pfMember['member_exam_id']));
              }

              $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Initiated - Update New Payment Id in iibfbcbf_member_exam table', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'transaction_action','New Payment Id updated in iibfbcbf_member_exam table', json_encode($update_iibfbcbf_member_exam_data));

            } 
            $paymentTransInfo = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id' => $pt_id)); 
          }
        }

        $update_data["status"] = 6; 
        $update_data["updated_on"] = date("Y-m-d H:i:s"); 

        $MerchantOrderNo = sbi_exam_order_id($pt_id); //getregnumber_helper.php 
        //$ref4 = $exam_code.date('Y').date('m'); 
        //324^BC^IIBF_BULK_BCBF^100034
        $custom_field          = $pt_id."^BC^IIBF_BULK_BCBF^".$MerchantOrderNo;
        $custom_field_billdesk = $pt_id."-BC-IIBF_BULK_BCBF-" . $MerchantOrderNo; 

        $update_query = $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('id' => $pt_id));

        $pg_name = 'billdesk';

        $amount           = $paymentTransInfo[0]['amount'];
        if($amount > 0) 
        {
            $inv_id = $bulk_exam_invoice_data[0]['invoice_id'];
          
            if($newPaymentTransactionEntry==1)
            {
              //Existing New Exam Invoice Records
              $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Initiated - Before Insert New Exam Invoice Records in exam_invoice table', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'transaction_action','Before Insert New Exam Invoice Records in exam_invoice table', json_encode($bulk_exam_invoice_data));

              $invoice_insert_array = array_shift($bulk_exam_invoice_data);
              unset($invoice_insert_array['invoice_id']);

              $inv_id = $this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);

              $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Initiated - Insert New Exam Invoice Records in exam_invoice table', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'transaction_action','Insert New Exam Invoice Records in exam_invoice table', json_encode($invoice_insert_array)); 
            }

            $invoice_update_array = array(
            'pay_txn_id' => $pt_id,
            'receipt_no' => $MerchantOrderNo, 
            'created_on' => date('Y-m-d H:i:s')
            );
            $this->master_model->updateRecord('exam_invoice', $invoice_update_array, array(
            'invoice_id' => $inv_id
            ));

            $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Initiated - Update Bulk Exam Invoice Records', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'transaction_action','Update Bulk Exam Invoice Records', json_encode($invoice_update_array));  

            $bulk_exam_invoice_data=$this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pt_id,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'app_type'=>'BC'));

            $inv_id = $bulk_exam_invoice_data[0]['invoice_id'];
        
            if($pg_name == 'billdesk')
            { 
              $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $inv_id, $inv_id, '', 'iibfbcbf/agency/transaction_details_agency/handle_billdesk_response', '', '', '', $custom_field_billdesk);
              
              if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
              {
                $userarr = array('pt_id' => $pt_id, 'receipt_no' => $MerchantOrderNo);
                //$this->session->set_userdata('SESSION_MEMBER_DATA', $userarr); 

                $data['bdorderid']      = $billdesk_res['bdorderid'];
                $data['token']          = $billdesk_res['token'];
                $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                $data['returnUrl']      = $billdesk_res['returnUrl'];
                $data['bulk_payment_flag'] = 'IIBF_BULK_BCBF';////
                // echo "<pre>"; print_r($data); exit;
                $this->load->view('pg_billdesk/pg_billdesk_form', $data);
              }
              else
              {
                if (isset($billdesk_res['status']) && $billdesk_res['status'] == 409) {
                  $this->session->set_flashdata('error', 'Payment has been cancelled.');  
                } else {
                  $this->session->set_flashdata('error', 'Transaction failed...!');
                }
                
                redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
              }
            }
        }

        /*END: To initiate the payment process*/
           

      }        
    }


    public function handle_billdesk_response()
    {
      
      if (isset($_REQUEST['transaction_response']))
      {
        $response_encode        = $_REQUEST['transaction_response'];
        $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
        $responsedata           = $bd_response['payload'];
        $attachpath             = $invoiceNumber = '';
        $MerchantOrderNo        = $responsedata['orderid'];
        $utr_no = $transaction_no   = $responsedata['transactionid'];
        $transaction_error_type = $responsedata['transaction_error_type'];
        $transaction_error_desc = $responsedata['transaction_error_desc'];
        $bankid                 = $responsedata['bankid'];
        $txn_process_type       = $responsedata['txn_process_type'];
        $merchIdVal             = $responsedata['mercid'];
        $Bank_Code              = $responsedata['bankid'];
        $amount                 = $responsedata['amount'];
        $encData                = $_REQUEST['transaction_response'];
        $auth_status            = $responsedata['auth_status'];
        // echo "<pre>"; print_r($responsedata); exit;
        
        $get_user_regnum_info=$this->master_model->getRecords('iibfbcbf_payment_transaction',array('receipt_no'=>$MerchantOrderNo));

        if (count($get_user_regnum_info) > 0 && ($get_user_regnum_info[0]['status'] == 1 && $get_user_regnum_info[0]['callback'] == 'S2S')  )
        {
          $this->session->set_flashdata('success', 'Transaction completed successfully! Thank you for apply the Bulk exam.');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
        } 
        else if (count($get_user_regnum_info) > 0 && ($get_user_regnum_info[0]['status'] == 0 && $get_user_regnum_info[0]['callback'] == 'S2S')  )
        {
          $this->session->set_flashdata('success', 'Transaction details could not be found for the active exam period. Please verify and try again.');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
        }

        $payment_action   = "Pending";
        $flash_massege    = "Transaction details is inprocess. please try again after sometime.";
        
        if ($transaction_error_type != '' && $transaction_error_desc != '') {
          $flash_massege    = $transaction_error_type . " - " . $transaction_error_desc;  
        }

        $flash_status     = "success";
        $paymentStatus    = 6;

        // Get the Transaction response from BillDesk API Using the receipt number 
        $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
        // echo $MerchantOrderNo.'<pre>'; print_r($qry_api_response); exit;
        if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300')
        {
          $payment_action = "Approved";
          $flash_massege  = "Transaction completed successfully! Thank you for apply the Bulk exam.";
          $flash_status   = "success";
          $paymentStatus  = 1;
        } 
        else if ($auth_status == '0399' && $qry_api_response['auth_status'] == '0399') 
        {
          $payment_action   = "Rejected";
          $flash_massege    = $transaction_error_desc;
          $flash_status     = "error";
          $paymentStatus    = 0;
        }
          
        $update_data = array(
          'transaction_no'      => $transaction_no,
          'status'              => $paymentStatus,
          'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
          'auth_code'           => $auth_status,
          'bankcode'            => $bankid,
          //'amount'              => $amount,
          'paymode'             => $txn_process_type,
          'callback'            => 'B2B',
        );
        
        $update_query = $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

        if ($this->db->affected_rows() && $paymentStatus != 6)
        {
          $get_user_regnum_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo));
          if (count($get_user_regnum_info) > 0)
          {
              
          }
          else
          {
            $this->session->set_flashdata('error', 'Transaction details not found.');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list'));
          }
        }
        else
        {
          $this->session->set_flashdata($flash_status, $flash_massege);
          redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list'));
        }

      }
      else
      {
        $this->session->set_flashdata('error', 'Something Went Wrong');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice_list')); 
      }

      $data['enc_pt_id'] = $enc_pt_id;

      $chk_exam_mode = "";
      if($enc_pt_id)
      {
        $id = $pt_id = url_decode($enc_pt_id);
        //$utr_no = $this->input->post('utr_no');
        //$mem_count = $this->input->post('mem_count');
        //$payment_amt = $this->input->post('payment_amt'); 
        $action = "Approved";

        $updte_data = array();
        $updated_date = date('Y-m-d H:i:s');
        
        $admit_mem_exam_ids_arr = array();
        $admit_mem_exam_idlst = '';
        $admit_memexamidlst = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id' => $id),'exam_ids,status,amount,pay_count');

        

        if(isset($admit_memexamidlst) && count($admit_memexamidlst) > 0 && $admit_memexamidlst[0]['status'] == '3')
        { 

          if($admit_memexamidlst){
            $admit_mem_exam_idlst = $admit_memexamidlst[0]['exam_ids'];
            $admit_mem_exam_ids_arr = explode(",",$admit_mem_exam_idlst);
          } 
          
          $payment_amt = $admit_memexamidlst[0]['amount'];
          $mem_count = $admit_memexamidlst[0]['pay_count'];

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
            
            $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Failed', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Online Payment Failed by the Centre', json_encode($updte_data));
            
            $data['success'] = 'error'; 
            $json_res = json_encode($data);
            echo $json_res;
            exit; die();
          }     
          
          $flag = 0;
          $sub_arr = $member_array = array();
          
          if($action == "Approved")
          {
            $status = '1';
            $desc = 'Payment Success - Online Payment by Centre';  
            $data['success'] = 'success';
            //break;
            
            $approve_reject_data['status'] = $status;
            //$approve_reject_data['UTR_no'] = $utr_no;
            $approve_reject_data['pay_count'] = $mem_count;
            $approve_reject_data['amount'] = $payment_amt;
            //$approve_reject_data['date'] = date("Y-m-d H:i:s", strtotime($payment_date));
            $approve_reject_data['description'] = $desc;
            $approve_reject_data['updated_on'] = $updated_date; 
             
            $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Successfully', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Online Payment Successfully by the Centre', json_encode($approve_reject_data));
          }
          else if($action == "Rejected")
          {
            $sub_arr = $member_array;
            $status = '4';
            $desc = 'Payment Failed - by Centre';
            
            $approve_reject_data['status'] = $status;
            $approve_reject_data['description'] = $desc;
            $approve_reject_data['updated_on'] = $updated_date; 
             
            $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Failed', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Online Payment Failed by the Centre', json_encode($approve_reject_data));
            $data['success'] = 'success';
            
          }
          
          // update required table
          //echo $chk_exam_mode." == 'Online' && ".count($sub_arr)." == ".count($member_array)." && ".$flag." == 0 && (".count($member_array)." > 0 || ".$this->input->post('action')." == 'Rejected')) || ".$chk_exam_mode." == 'Offline'";
          if((count($sub_arr) == count($member_array) && $flag == 0 && (count($member_array) > 0 || $action == "Rejected")) || $chk_exam_mode == 'Online')
          {
            if($status == '1')//start : if payment action is approve, then check for capacity 
            {
              if(in_array($exam_mode_qry[0]['exam_code'],$this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041, 1042, 1057. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041, 1042, 1057. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID EXAM
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
                        $this->Iibf_bcbf_model->insert_common_log('Transaction : Registration No. Generated Successfully', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Registration No. generated successfully after Online Payment Success by the Centre', json_encode($log_update_data));
                        
                        
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
                          $this->Iibf_bcbf_model->insert_common_log('Transaction : Member Images Updated Successfully', 'iibfbcbf_batch_candidates', $this->db->last_query(), $memregid,'transaction_action','The Member Images Updated successfully after Online Payment Success by the Centre', json_encode($upd_files));
                          
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
                  $this->Iibf_bcbf_model->insert_common_log('Transaction : Exam Invoice updated after Online Payment Success', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Exam Invoice updated after Online Payment Success by the Centre', json_encode($invoice_update_data));
                  
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
                      $this->Iibf_bcbf_model->insert_common_log('Transaction : Re-attempt successfully after Online Payment Success', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Re-attempt successfully after Online Payment Success by the Centre', json_encode($log_update_datas)); 

                      $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$memexamid),'candidate_id, exam_code, exam_period');
                      $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Online Payment Success by Centre', 'iibfbcbf_batch_candidates', '', $candidate_id,'candidate_action','The transaction is successful by the centre and candidate successfully applied for the exam ('.$member_exam_data[0]['exam_code'].' & '.$member_exam_data[0]['exam_period'].'). ', '');
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
                    $this->Iibf_bcbf_model->insert_common_log('Transaction : Member Exam Payment Transaction Status updated after Online Payment Success', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Member Exam Payment Transaction Status updated after Online Payment Success by the Centre', json_encode($update_member_exam_data));
                    
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
                    $this->Iibf_bcbf_model->insert_common_log('Transaction : Member Exam Payment Transaction Status updated after Online Payment Failed', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Member Exam Payment Transaction Status updated after Online Payment Failed by the Centre', json_encode($update_mem_ex_data));

                    $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$memexamids),'candidate_id, exam_code, exam_period');
                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment Failed by Centre', 'iibfbcbf_batch_candidates', '', $member_exam_data[0]['candidate_id'],'candidate_action','The transaction is failed by the centre and candidate exam application failed for the exam ('.$member_exam_data[0]['exam_code'].' & '.$member_exam_data[0]['exam_period'].'). ', '');
                  }
                }
                
                $data['success'] = 'success';
              }
            }
          }
          else
          {
            //log_dra_admin($log_title = "IIBFBCBF Admin NEFT Approved Failed.", $log_message = serialize($update_data));
            $this->Iibf_bcbf_model->insert_common_log('Transaction : Online Payment Failed', 'iibfbcbf_payment_transaction', $this->db->last_query(), $id,'transaction_action','The Online Payment Failed by the Centre', json_encode($approve_reject_data));
            
            $data['success'] = 'error2';  
          }

        }else{
          $data['success'] = 'invalid_request';  
        }

        $json_res = json_encode($data);
        echo $json_res;
        exit; die();

      }

    }
    /*Function to make online payment for Bulk Added By ANIL S on 04 Dec 2025 for IIBF BCBF*/

 } ?>  