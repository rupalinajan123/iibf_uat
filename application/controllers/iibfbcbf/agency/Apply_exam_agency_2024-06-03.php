<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Apply exam functionality
  ** Created BY: Sagar Matale On 02-12-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Apply_exam_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'centre')
      {
        $this->session->set_flashdata('error','You do not have permission to access Apply Exam module');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }

      $this->login_agency_id = $this->login_centre_id = '';
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
        if($agency_data[0]['allow_exam_types'] == 'CSC')
        {
          $this->session->set_flashdata('error','You do not have permission to access Apply Exam module');
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }

      $this->buffer_days_after_training_end_date = '0';
      $this->buffer_days_after_candidate_add_date = '270';
      $this->utr_slip_path = 'uploads/iibfbcbf/utr_slip';

      $this->venue_master_eligible_exam_codes_arr = array(1041,1042);//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
		}

    public function index() { redirect(site_url('iibfbcbf/agency/dashboard_agency')); }
    
    public function candidate_listing($enc_exam_code=0)
    {      
      $data['enc_exam_code'] = $enc_exam_code;
      
      $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $this->login_agency_id, 'bulk');
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','This exam is not active');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
      
      
      $data['act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['sub_act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' : Apply for '.display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */
      $this->load->view('iibfbcbf/agency/apply_exam_candidate_list_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM APPLICATION CANDIDATES DATA ********/
    public function get_exam_candidates_agency_data_ajax()
    {
      $table = 'iibfbcbf_agency_centre_batch btch';
      
      $column_order = array('""', 'btch.batch_id', 'btch.batch_code', 'cand.regnumber', 'CONCAT(cand.salutation," ",cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), "")) AS DispCandidateName', 'cand.training_id', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'mem_ex.exam_fee', 'mem_ex.payment_mode', 'IF(mem_ex.pay_status=0, "Fail", IF(mem_ex.pay_status=1, "Success", IF(mem_ex.pay_status=2, "Pending", IF(mem_ex.pay_status=4, "Cancelled", IF(mem_ex.pay_status=3, IF(mem_ex.ref_utr_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), ""))))) AS DispPayStatus', 'mem_ex.ref_utr_no', 'ex_cm.centre_name', 'ex_mm.medium_description', 'cand.candidate_id', 'mem_ex.member_exam_id', 'mem_ex.pay_status', 'mem_ex.exam_centre_code', 'mem_ex.exam_medium', 'mem_ex.exam_code', 'mem_ex.exam_period'); //SET COLUMNS FOR SORT
      
      $column_search = array('btch.batch_code', 'cand.regnumber', 'CONCAT(cand.salutation," ",cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))', 'cand.training_id', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'mem_ex.exam_fee', 'mem_ex.payment_mode', 'IF(mem_ex.pay_status=0, "Fail", IF(mem_ex.pay_status=1, "Success", IF(mem_ex.pay_status=2, "Pending", IF(mem_ex.pay_status=4, "Cancelled", IF(mem_ex.pay_status=3, IF(mem_ex.ref_utr_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), "")))))', 'mem_ex.ref_utr_no', 'ex_cm.centre_name', 'ex_mm.medium_description'); //SET COLUMN FOR SEARCH

      $order = array('mem_ex.pay_status IS NULL'=>'', 'mem_ex.pay_status'=>'ASC', 'mem_ex.member_exam_id'=>'DESC', 'btch.batch_id'=>'DESC', 'cand.candidate_id'=>'DESC'); // DEFAULT ORDER      

      $enc_exam_code = trim($this->security->xss_clean($this->input->post('enc_exam_code')));
      $exam_code = $active_exam_period = 0;
      if($enc_exam_code != '0') 
      { 
        $exam_code = url_decode($enc_exam_code); 
        //$chk_batch_type = $this->Iibf_bcbf_model->get_batch_type($exam_code);
        $chk_batch_type = 0;

        $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $this->login_agency_id, 'bulk');
        if(count($active_exam_data) > 0)
        {
          $active_exam_period = $active_exam_data[0]['exam_period'];
          $chk_batch_type = $active_exam_data[0]['exam_type'];
        }
      }
      
      $todays_date = date("Y-m-d"); //'2024-01-25';//
      
      $WhereForTotal = $Where = "WHERE 
      btch.centre_id = '".$this->login_agency_or_centre_id."' AND  
      btch.is_deleted = '0' AND 
      btch.batch_status='3' AND 
      btch.batch_type = '".$chk_batch_type."' AND 
      DATE_ADD(btch.batch_end_date, INTERVAL ".$this->buffer_days_after_training_end_date." DAY) < '".$todays_date."' AND      
      DATE_ADD(btch.batch_end_date, INTERVAL ".$this->buffer_days_after_candidate_add_date." DAY) >= '".$todays_date."' AND      
      cand.is_deleted = '0' AND
      cand.re_attempt < '3' AND
      cand.centre_id = '".$this->login_agency_or_centre_id."' AND 
      cand.hold_release_status = '3' AND 
      DATE_ADD(DATE(cand.created_on), INTERVAL ".$this->buffer_days_after_training_end_date." DAY) < '".$todays_date."' AND 
      DATE_ADD(DATE(cand.created_on), INTERVAL ".$this->buffer_days_after_candidate_add_date." DAY) >= '".$todays_date."' AND 
      cand.id_proof_file != '' AND 
      cand.qualification_certificate_file != '' AND 
      cand.candidate_photo != '' AND 
      cand.candidate_sign != '' AND
      
      ((mem_ex.exam_period = '".$active_exam_period."' AND (mem_ex.pay_status != '1' OR mem_ex.pay_status IS NULL)) OR ((mem_ex.exam_period != '".$active_exam_period."' OR mem_ex.exam_period IS NULL) )) AND
      
      cand.candidate_id NOT IN (SELECT candidate_id FROM iibfbcbf_member_exam WHERE exam_date >= '".date("Y-m-d")."' AND exam_period IN (999999999, ".$active_exam_period.") AND CASE WHEN exam_code = '".$exam_code."' THEN pay_status IN (1) WHEN exam_code > 0 AND exam_code != '".$exam_code."' THEN pay_status IN (1,2) END)"; //DEFAULT WHERE CONDITION FOR ALL RECORDS

      /* cand.candidate_id NOT IN (SELECT candidate_id FROM iibfbcbf_member_exam WHERE exam_period = '".$active_exam_period."' AND ((exam_code = '".$exam_code."' AND pay_status = '1') OR (exam_code != '".$exam_code."' AND pay_status IN (1,3))))" */

      //AND (eligib.exam_status IN ('F','V') OR eligib.exam_status IS NULL)
      
      /* (
        (
          mem_ex.exam_period = '".$active_exam_period."' AND (mem_ex.pay_status != '1' OR mem_ex.pay_status IS NULL)
        ) 
        OR 
        (
          (mem_ex.exam_period != '".$active_exam_period."' AND eligib.exam_status IN ('F','V')) OR 
          (mem_ex.exam_period IS NULL)
        )
      ) */ 
      
      //$Where = "WHERE btch.centre_id = '".$this->login_agency_or_centre_id."' AND btch.is_deleted = '0' AND btch.batch_status='3' AND btch.batch_type = '".$chk_batch_type."' AND DATE_ADD(batch_end_date, INTERVAL ".$this->buffer_days_after_training_end_date." DAY) <= '".$todays_date."' AND cand.hold_release_status = '3'  ";
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
      if($s_batch_code != "") 
      { 
        $explode_arr = explode(",",$s_batch_code);

        $Where .= " AND ("; 
        $i=1;
        foreach($explode_arr as $res)
        {
          $Where .= " btch.batch_code LIKE '%".trim($res)."%'";
          if($i < count($explode_arr)) { $Where .= " OR "; }
          $i++;
        }
        $Where .= ") "; 
      }

      $s_regnumber = trim($this->security->xss_clean($this->input->post('s_regnumber')));
      if($s_regnumber != "") 
      { 
        $explode_arr = explode(",",$s_regnumber);

        $Where .= " AND ("; 
        $i=1;
        foreach($explode_arr as $res)
        {
          $Where .= " cand.regnumber LIKE '%".trim($res)."%'";
          if($i < count($explode_arr)) { $Where .= " OR "; }
          $i++;
        }
        $Where .= ") "; 
      }

      $s_name = trim($this->security->xss_clean($this->input->post('s_name')));
      if($s_name != "") { $Where .= " AND CONCAT(cand.salutation,' ', cand.first_name, IF(cand.middle_name != '', CONCAT(' ', cand.middle_name), ''), IF(cand.last_name != '', CONCAT(' ', cand.last_name), '')) LIKE '%".$s_name."%'"; } 
      
      $s_status = trim($this->security->xss_clean($this->input->post('s_status')));
      if($s_status != "") 
      { 
        if($s_status == '3') 
        { 
          $Where .= " AND mem_ex.ref_utr_no = 'IIBFBCBF-TEMP-UTR-NO'"; 
        }
        else if($s_status == '4') 
        { 
          $s_status = '3'; 
          $Where .= " AND mem_ex.ref_utr_no != 'IIBFBCBF-TEMP-UTR-NO'";
        }

        if($s_status != 'NULL') { $Where .= " AND mem_ex.pay_status = '".$s_status."'";  }
        else { $Where .= " AND mem_ex.pay_status IS NULL";  }
      }

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
            
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	      
           
      $join_qry = " INNER JOIN iibfbcbf_batch_candidates cand ON cand.batch_id = btch.batch_id AND cand.agency_id = btch.agency_id AND cand.centre_id = btch.centre_id";      
      $join_qry .= " LEFT JOIN iibfbcbf_member_exam mem_ex ON mem_ex.candidate_id = cand.candidate_id AND mem_ex.exam_code = '".$exam_code."' AND mem_ex.exam_period = '".$active_exam_period."' AND mem_ex.pay_status != '0'";
      $join_qry .= " LEFT JOIN iibfbcbf_exam_centre_master ex_cm ON ex_cm.centre_code = mem_ex.exam_centre_code AND ex_cm.exam_name = '".$exam_code."'";    
      $join_qry .= " LEFT JOIN iibfbcbf_exam_medium_master ex_mm ON ex_mm.medium_code = mem_ex.exam_medium AND ex_mm.exam_code = '".$exam_code."'";    
      ///$join_qry .= " LEFT JOIN iibfbcbf_eligible_master eligib ON eligib.member_no != '' AND eligib.member_no = cand.regnumber AND eligib.exam_code = '".$exam_code."'";    
      
      $Where .= ' GROUP BY cand.candidate_id';
      $WhereForTotal .= ' GROUP BY cand.candidate_id';
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      $selected_ids_str = $_POST['selected_ids_str'];
      if($selected_ids_str != "") { $selected_ids_str_arr = explode(",",$selected_ids_str); } else { $selected_ids_str_arr = array(); }
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $checkbox_str = '';
        if(($Res['pay_status'] == '0' || $Res['pay_status'] == '2' || $Res['pay_status'] == '4') && $Res['exam_centre_code'] != "" && $Res['exam_medium'] != '')
        {
          if($Res['payment_mode'] == "Individual" && $Res['pay_status'] == '2')
          {
            //do not show the checkbox
          }
          else
          {
            if(in_array($Res['member_exam_id'], $selected_ids_str_arr)) { $check_val = "checked"; } else { $check_val = ""; }
            
            $checkbox_str = '<label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$Res['member_exam_id'].'" id="checkboxlist_new_'.$Res['member_exam_id'].'" onclick="update_delete_str('.$Res['member_exam_id'].')" '.$check_val.'><span class="checkmark"></span></label>';
          }
        }
        $row[] = $checkbox_str;

        $row[] = $no;
        $row[] = $Res['batch_code'];
        $row[] = $Res['regnumber'];
        $row[] = $Res['DispCandidateName'];
        $row[] = $Res['training_id'];
        $row[] = $Res['dob'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id'];
        $row[] = $Res['exam_fee'];
        $row[] = $Res['payment_mode'];
        $row[] = '<span class="badge '.show_payment_status($Res['pay_status']).'" style="width: 120px;white-space: normal;line-height: 15px; word-break: break-word;">'.$Res['DispPayStatus'].'</span>'; //iibf_bcbf_helper.php
        $row[] = $Res['ref_utr_no'];
        $row[] = $Res['centre_name'];
        $row[] = $Res['medium_description'];               
        
        $btn_str = ' <div class="text-center no_wrap"> ';
        
        if($Res['pay_status'] == "" || $Res['pay_status'] == '0' || $Res['pay_status'] == '2' || $Res['pay_status'] == '4')
        {
          if($Res['payment_mode'] == "Individual" && $Res['pay_status'] == '2')
          {
            //do not show the edit & clear button
          }
          else
          {
            $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/apply_exam_agency/apply_exam_candidate/'.$enc_exam_code.'/'.url_encode($Res['candidate_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';

            if($Res['exam_centre_code'] != "" || $Res['exam_medium'] != '')
            {
              $onclick_clear = "sweet_alert_clear('".site_url('iibfbcbf/agency/apply_exam_agency/clear_exam_application/'.url_encode($Res['candidate_id']).'/'.url_encode($exam_code))."')";

              $btn_str .= '<a href="javascript:void(0)" class="btn btn-danger btn-xs" title="Clear Exam Application" onclick="'.$onclick_clear.'"><i class="fa fa-eraser" aria-hidden="true"></i></a> ';
            }
            else { $btn_str .= '<a class="btn btn_hide_visibility"></a>';/* THIS IS USED TO KEEP THE BUTTON ALIGNMENT PROPER */ }
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM APPLICATION CANDIDATES DATA ********/ 	 
    
    public function apply_exam_candidate($enc_exam_code='0', $enc_candidate_id='0')
    {      
      $data['enc_exam_code'] = $enc_exam_code;      
      $data['enc_candidate_id'] = $enc_candidate_id;      
      $data['venue_master_eligible_exam_codes_arr'] = $this->venue_master_eligible_exam_codes_arr;      

      //START : GET ACTIVE EXAM DETAILS
      $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $this->login_agency_id, 'bulk'); 
      
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','This exam is not active');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }//END : GET ACTIVE EXAM DETAILS

      $data['act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['sub_act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' : Apply for '.$active_exam_data[0]['description']; 
      
      //START : GET CANDIDATE DETAILS
      $enc_exam_period = url_encode($active_exam_data[0]['exam_period']);
      $resData = $this->Iibf_bcbf_model->get_exam_candidate_details($enc_exam_code, $enc_exam_period, $enc_candidate_id, $this->login_agency_or_centre_id, 'bulk');
      if($resData['flag'] == 'error')
      {
        $this->session->set_flashdata('error',$resData['response_msg']);
        redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
      }//END : GET CANDIDATE DETAILS

      $data['candidate_data'] = $candidate_data = $resData['result_data'];
      $data['id_proof_file_path'] = 'uploads/iibfbcbf/id_proof';
      $data['qualification_certificate_file_path'] = 'uploads/iibfbcbf/qualification_certificate';
      $data['candidate_photo_path'] = 'uploads/iibfbcbf/photo';
      $data['candidate_sign_path'] = 'uploads/iibfbcbf/sign';
      
      $this->db->group_by('exam_code');
      $data['subject_master'] = $this->master_model->getRecords('iibfbcbf_exam_subject_master',array('exam_code'=>$active_exam_data[0]['exam_code'],'subject_delete'=>'0','group_code'=>'C'),'',array('subject_code'=>'ASC'));

      $this->db->join('iibfbcbf_exam_activation_master eam','eam.exam_code = ecm.exam_name AND eam.exam_period = ecm.exam_period');
      $data['centre_master'] = $this->master_model->getRecords('iibfbcbf_exam_centre_master ecm',array('ecm.exam_name'=>$active_exam_data[0]['exam_code']),'',array('ecm.centre_name'=>'ASC'));
            
      $this->db->join('iibfbcbf_exam_activation_master eam','eam.exam_code = emm.exam_code AND eam.exam_period = emm.exam_period');
      $data['medium_master'] = $this->master_model->getRecords('iibfbcbf_exam_medium_master emm',array('emm.exam_code'=>$active_exam_data[0]['exam_code']));

      $data['applied_exam_data'] = $applied_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('candidate_id'=>$candidate_data[0]['candidate_id'], 'exam_code'=>$active_exam_data[0]['exam_code'], 'exam_period'=>$active_exam_data[0]['exam_period'], 'pay_status !='=>'0'),'',array('member_exam_id'=>'DESC'),'',1);
     
      $data['exam_code'] = $exam_code = url_decode($enc_exam_code);
      $data['exam_period'] = $exam_period = $active_exam_data[0]['exam_period'];


      //START : CALCULATE GROUP CODE
      $group_code = $this->Iibf_bcbf_model->get_group_code($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_data[0]['regnumber']);
      $free_paid_flag = 'P';
      $exam_fees = '0.00';
      //END : CALCULATE GROUP CODE

      $this->db->join('iibfbcbf_exam_fee_master fm','fm.exam_code = em.exam_code', 'INNER');
      $this->db->join('iibfbcbf_exam_misc_master mm','mm.exam_code = em.exam_code', 'INNER');
      $this->db->join('iibfbcbf_exam_subject_master sm','sm.exam_code = em.exam_code', 'INNER');
      $get_exam_fee_data = $this->master_model->getRecords('iibfbcbf_exam_master em',array('em.exam_code'=>$exam_code, 'em.exam_delete'=>'0', 'fm.fee_delete'=>'0', 'fm.member_category'=>$candidate_data[0]['registration_type'], 'fm.group_code'=>$group_code, 'fm.exempt'=>'NE', 'fm.exam_code'=>$exam_code, 'fm.exam_period'=>$exam_period, 'mm.misc_delete'=>'0', 'sm.subject_delete'=>'0'), 'fm.cs_tot, fm.igst_tot, sm.exam_date, sm.exam_time');

      if($candidate_data[0]['LoggedInCentreState'] == 'MAH')
      {
        $exam_fees = $get_exam_fee_data[0]['cs_tot'];
      }
      else
      {
        $exam_fees = $get_exam_fee_data[0]['igst_tot'];
      }
      $data['exam_fees'] = $exam_fees;
      
      if(isset($_POST) && count($_POST) > 0)
			{	
        $this->form_validation->set_rules('exam_centre', 'Exam centre', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('exam_medium', 'Exam medium', 'trim|required|xss_clean', array('required'=>"Please select the %s"));

        if(in_array($exam_code, $this->venue_master_eligible_exam_codes_arr)) //VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
        {
          $this->form_validation->set_rules('venue_name', 'venue', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
          $this->form_validation->set_rules('exam_date', 'exam date', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
          $this->form_validation->set_rules('exam_time', 'exam time', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
          //$this->form_validation->set_rules('xxx', 'exam time', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        }
        
        if($this->form_validation->run() == TRUE)  
				{
          $exam_centre_code = trim($this->security->xss_clean($this->input->post('exam_centre')));
          if(in_array($exam_code, $this->venue_master_eligible_exam_codes_arr)) //VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
          {
            $exam_venue_code = trim($this->security->xss_clean($this->input->post('venue_name')));
            $chk_member_exam_id = ''; if(count($applied_exam_data) > 0) { $chk_member_exam_id = $applied_exam_data[0]['member_exam_id']; } 
            $chk_capacity = $this->Iibf_bcbf_model->get_capacity_bulk($exam_code, $exam_period, $exam_centre_code, $exam_venue_code, trim($this->security->xss_clean($this->input->post('exam_date'))), trim($this->security->xss_clean($this->input->post('exam_time'))), $chk_member_exam_id);

            if($chk_capacity <= 0)
            {
              $this->session->set_flashdata('error','The capacity is full');
              redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
            }
          }
          //_pa($_POST);
          
          if(count($get_exam_fee_data) > 0)
          {
            //WE ARE NOT GETTING fee_paid_flag IN ELIGIBLE API RESPONSE, SO WE COMMENTED THIS CODE
            /* $this->db->order_by("id","DESC"); 
            $eligible_data = $this->master_model->getRecords('iibfbcbf_eligible_master',array('member_no'=>$candidate_data[0]['regnumber'],'exam_code'=>$exam_code, 'eligible_period'=>$active_exam_data[0]['exam_period']));
            if(count($eligible_data) > 0)
            {
              if($eligible_data[0]['fee_paid_flag'] == 'F')
              {
                $exam_fees = '0.00';
                $free_paid_flag = 'F';
              }
            } *///END : CALCULATE EXAM FEES
            
            //START : UPDATE CANDIDATE DATA
            $up_candidiate = array();
            $up_candidiate['exam_code'] = $exam_code;
            $up_candidiate['ip_address'] = get_ip_address(); //general_helper.php 
            $up_candidiate['updated_on'] = date('Y-m-d H:i:s');
            $up_candidiate['updated_by'] = $this->login_agency_or_centre_id;
            $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_candidiate,  array('candidate_id'=>$candidate_data[0]['candidate_id']));
            //END : UPDATE CANDIDATE DATA
            
            //START : INSERT / UPDATE MEMBER EXAM DATA
            $add_exam_data = array();            
            
            if(in_array($exam_code, $this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
            {
              $add_exam_data['exam_date'] = trim($this->security->xss_clean($this->input->post('exam_date')));
              $add_exam_data['exam_venue_code'] = $exam_venue_code;
              $add_exam_data['exam_time'] = trim($this->security->xss_clean($this->input->post('exam_time')));
            }
            else
            {
              $add_exam_data['exam_date'] = $get_exam_fee_data[0]['exam_date']; 
              $add_exam_data['exam_time'] = $get_exam_fee_data[0]['exam_time'];
            }

            $add_exam_data['exam_centre_code'] = $exam_centre_code;
            $add_exam_data['exam_medium'] = trim($this->security->xss_clean($this->input->post('exam_medium')));
            $add_exam_data['exam_code'] = $exam_code;
            $add_exam_data['exam_fee'] = $exam_fees;
            $add_exam_data['batch_id'] = $candidate_data[0]['batch_id'];
            $add_exam_data['exam_period'] = $active_exam_data[0]['exam_period'];
            $add_exam_data['pay_status'] = 2;
            $add_exam_data['payment_mode'] = 'Bulk';
            $add_exam_data['candidate_id'] = $candidate_data[0]['candidate_id'];
            $add_exam_data['batch_start_date'] = $candidate_data[0]['batch_start_date'];
            $add_exam_data['batch_end_date']	= $candidate_data[0]['batch_end_date'];
            $add_exam_data['fee_paid_flag'] = $free_paid_flag;            
            $add_exam_data['ip_address'] = get_ip_address(); //general_helper.php 
           /*  _pa($_POST);
            _pa($add_exam_data,1); */

            $posted_arr = json_encode($_POST);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');

            if(count($applied_exam_data) == 0) //FOR ADD MODE
            {
              $add_exam_data['created_on'] = date('Y-m-d H:i:s');
              $add_exam_data['created_by'] = $this->login_agency_or_centre_id;
              
              $this->master_model->insertRecord('iibfbcbf_member_exam',$add_exam_data,true);
              
              $this->Iibf_bcbf_model->insert_common_log('Centre : Candidate Applied for exam', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$exam_code.' & '.$active_exam_data[0]['exam_period'].') by centre '.$centreName['disp_name'], $posted_arr);
               
              $this->session->set_flashdata('success','Exam Details has been updated successfully');
              redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
            }
            else //FOR UPDATE MODE
            {
              $this->master_model->updateRecord('iibfbcbf_member_exam', $add_exam_data,  array('member_exam_id'=>$applied_exam_data[0]['member_exam_id']));

              $this->Iibf_bcbf_model->insert_common_log('Centre : Candidate Applied for exam', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$exam_code.' & '.$active_exam_data[0]['exam_period'].') by centre '.$centreName['disp_name'], $posted_arr);
                            
              $this->session->set_flashdata('success','Exam Details has been updated successfully');
              redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
            }
            //END : INSERT / UPDATE MEMBER EXAM DATA
          }
          else
          {
            $this->session->set_flashdata('error','Your information is incorrect. Please contact to iibf admin.');
            redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
          }
				}
			}
      
      $this->load->view('iibfbcbf/agency/apply_exam_candidate_agency', $data);
    }

    public function clear_exam_application($enc_candidate_id='0',$enc_exam_code='0')
		{       
      $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $this->login_agency_id, 'bulk');  
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','This exam is not active');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
      
      $enc_exam_period = url_encode($active_exam_data[0]['exam_period']);
      $resData = $this->Iibf_bcbf_model->get_exam_candidate_details($enc_exam_code, $enc_exam_period, $enc_candidate_id, $this->login_agency_or_centre_id, 'bulk');
      if($resData['flag'] == 'error')
      {
        $this->session->set_flashdata('error',$resData['response_msg']);
        redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
      }
      $candidate_data = $resData['result_data'];

      $applied_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('candidate_id'=>$candidate_data[0]['candidate_id'], 'exam_code'=>$active_exam_data[0]['exam_code'], 'exam_period'=>$active_exam_data[0]['exam_period']),'',array('member_exam_id'=>'DESC'),'',1);

      if(count($applied_exam_data) > 0)
      {
        //START : CLEAR EXAM CODE IN CANDIDATE TABLE
        $up_candidiate = array();
        $up_candidiate['exam_code'] = '';
        $up_candidiate['ip_address'] = get_ip_address(); //general_helper.php 
        $up_candidiate['updated_on'] = date('Y-m-d H:i:s');
        $up_candidiate['updated_by'] = $this->login_agency_or_centre_id;
        $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_candidiate,  array('candidate_id'=>$candidate_data[0]['candidate_id']));
        $qry1 = $this->db->last_query();
        //END : CLEAR EXAM CODE IN CANDIDATE TABLE
      
        //START : CLEAR EXAM DETAIL IN MEMBER EXAM TABLE
        $clear_exam = array();
        $clear_exam['exam_centre_code'] = '';
        $clear_exam['exam_medium'] = '';
        $clear_exam['exam_code'] = '';
        $clear_exam['exam_fee'] = '';
        $clear_exam['pay_status'] = '2';
        $this->master_model->updateRecord('iibfbcbf_member_exam', $clear_exam,  array('member_exam_id'=>$applied_exam_data[0]['member_exam_id']));
        $qry2 = $this->db->last_query();
        //END : CLEAR EXAM DETAIL IN MEMBER EXAM TABLE

        $posted_arr = json_encode(array());
        $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');

        $this->Iibf_bcbf_model->insert_common_log('Centre : Candidate exam application cleared', 'iibfbcbf_batch_candidates', $qry1.'<br>'.$qry2, $candidate_data[0]['candidate_id'],'candidate_action','The centre '.$centreName['disp_name'].' has successfully cleared the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].') details of the candidate', $posted_arr);

        $this->session->set_flashdata('success','Exam details of the selected application is cleared.');
        redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
      }
      else
      {
        $this->session->set_flashdata('error','Your information is incorrect. Please try again.');
        redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
      }
		}

    //START : THIS FUNCTION IS USED TO MAKE THE SELECTED CANDIDATES PAYMENT
    function make_payment($enc_exam_code='0')
    {
      $data['enc_exam_code'] = $enc_exam_code;
      
      //START : GET ACTIVE EXAM DETAILS
      $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $this->login_agency_id, 'bulk'); 
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','This exam is not active');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }//END : GET ACTIVE EXAM DETAILS

      $data['act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['sub_act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' : Make Payment '.$active_exam_data[0]['description'];

      $data['form_data'] = array();      

      if(isset($_POST) && count($_POST) > 0)
			{	  
        $selcted_member_exam_ids_str = '0';
        if(isset($_POST['selcted_member_exam_ids_str'])) { $selcted_member_exam_ids_str = trim($this->input->post('selcted_member_exam_ids_str'));  }
        $data['selcted_member_exam_ids_str'] = $selcted_member_exam_ids_str;
        

        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $this->db->where_in('me.pay_status', '0,2,4', FALSE);
        $this->db->where_in('me.member_exam_id', $selcted_member_exam_ids_str, FALSE);
        $data['form_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted'=>'0', 'cand.is_deleted'=>'0'), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_centre_code, me.exam_venue_code, me.exam_date, me.exam_time, me.exam_fee, me.pay_status, me.fee_paid_flag, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.registration_type');
        
        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['agency_centre_data'] = $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, am.agency_id, cm.centre_name,  cm.gst_no, cm.centre_state, sm.state_no, sm.state_name, sm.exempt, cm.centre_city, city.city_name, am.agency_name, am.agency_code');

        //START : CALCULATE FRESH CANDIDATE COUNT & REPEATER CANDIDATE COUNT
        $fresh_cnt = $repeater_cnt = 0;
        $capacity_chk_arr = array();
        foreach($candidate_data as $candidate_res)
        {
          if(in_array($active_exam_data[0]['exam_code'], $this->venue_master_eligible_exam_codes_arr)) //VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
          {
            $array_key = $candidate_res['exam_centre_code'].'_'.$candidate_res['exam_venue_code'].'_'.$candidate_res['exam_date'].'_'.$candidate_res['exam_time'];
            $array_key = str_replace(array("-"," "), "_",$array_key);
            if(!array_key_exists($array_key, $capacity_chk_arr))
            {
              $capacity_chk_arr[$array_key]['current_selected_rec'] = 1;   
              $capacity_chk_arr[$array_key]['available_capacity'] = $this->Iibf_bcbf_model->get_capacity_bulk($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_res['exam_centre_code'], $candidate_res['exam_venue_code'], $candidate_res['exam_date'], $candidate_res['exam_time']);     
              
              $capacity_chk_arr[$array_key]['capacity_error_message'] = 'centre code '.$candidate_res['exam_centre_code'].', venue code '.$candidate_res['exam_venue_code'].', date '.$candidate_res['exam_date'].' & time '.$candidate_res['exam_time'];
            }
            else
            {
              $capacity_chk_arr[$array_key]['current_selected_rec'] = $capacity_chk_arr[$array_key]['current_selected_rec'] + 1;
            }
          }

          $applied_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam mem_ex', array('mem_ex.candidate_id' => $candidate_res['candidate_id'], 'mem_ex.exam_period' => $active_exam_data[0]['exam_period']), "mem_ex.pay_status, mem_ex.exam_code, mem_ex.exam_period, mem_ex.exam_date, mem_ex.payment_mode", array('mem_ex.member_exam_id'=>"DESC"));
          if(count($applied_exam_data) > 0)
          {
            $error_applied_exam_flag = 0;
            foreach($applied_exam_data as $exam_data_res)
            {
              if($exam_data_res['payment_mode'] == 'Bulk')
              {
                if(($exam_data_res['pay_status'] == '1' || $exam_data_res['pay_status'] == '3') && $exam_data_res['exam_date'] == $active_exam_data[0]['exam_date'])
                {
                  $error_applied_exam_flag = 1;
                }
              }
              else if($exam_data_res['payment_mode'] == 'Individual')
              {
                if(($exam_data_res['pay_status'] == '1' || $exam_data_res['pay_status'] == '2') && $exam_data_res['exam_date'] == $active_exam_data[0]['exam_date'])
                {
                  $error_applied_exam_flag = 1;
                }
              }
            }

            if($error_applied_exam_flag == '1')
            {
              $this->session->set_flashdata('error','There is some error in posted data');
              redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
            }
          }          

          $member_no = $candidate_res['regnumber'];            
          if($member_no != 0 && $member_no != "")
          {
            /* $eligible_master_data = $this->master_model->getRecords('iibfbcbf_eligible_master',array('member_no'=>$member_no,'exam_code'=>$active_exam_data[0]['exam_code'], 'eligible_period'=>$active_exam_data[0]['exam_period']),'app_category');
            
            if(count($eligible_master_data)>0)
            {
              if($eligible_master_data[0]['app_category'] == 'R' || $eligible_master_data[0]['app_category'] == 'B1')
              {
                $fresh_cnt = $fresh_cnt+1;                      
              }
              else if($eligible_master_data[0]['app_category'] == 'S1') { $repeater_cnt = $repeater_cnt+1; }
              else { $fresh_cnt = $fresh_cnt+1; }
            }
            else
            {
              $fresh_cnt = $fresh_cnt+1;
            } */

            //ELIGIBLE MASTER API CODE GOES HERE
            $eligible_api_res = $this->Iibf_bcbf_model->iibf_bcbf_eligible_master_api($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $member_no);                                          
            if($eligible_api_res['api_res_flag'] == 'success')
            {
              if(isset($eligible_api_res['api_res_response'][0]) && count($eligible_api_res['api_res_response'][0]) > 0)
              {
                $eligible_data = $eligible_api_res['api_res_response'][0];
                if($eligible_data['app_cat'] == 'B1_1')
                {
                  $fresh_cnt = $fresh_cnt+1;                      
                }
                else if($eligible_data['app_cat'] == 'B1_2') { $repeater_cnt = $repeater_cnt+1; }
                else { $fresh_cnt = $fresh_cnt+1; }
              }
              else { $fresh_cnt = $fresh_cnt+1; }
            }
            else
            {
              $fresh_cnt = $fresh_cnt+1;
            }
          }
          else { $fresh_cnt = $fresh_cnt+1; }
        }//END : CALCULATE FRESH CANDIDATE COUNT & REPEATER CANDIDATE COUNT

        if(count($capacity_chk_arr) > 0)
        {
          foreach($capacity_chk_arr as $capacity_chk_res)
          {
            if($capacity_chk_res['available_capacity'] < $capacity_chk_res['current_selected_rec'])
            {
              $this->session->set_flashdata('error','Total available capacity is <b>'.$capacity_chk_res['available_capacity'].'</b> and you have seleceted the <b>'.$capacity_chk_res['current_selected_rec'].'</b> candidates against the '.$capacity_chk_res['capacity_error_message']);
              redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
            }
          }
        }

        $data['fresh_cnt'] = $fresh_cnt;
        $data['repeater_cnt'] = $repeater_cnt;
        //exit;

        //START : SERVER SIDE VALIDATION
        $this->form_validation->set_rules('selcted_member_exam_ids_str', '', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        $chk_form_type = $this->security->xss_clean($this->input->post('chk_form_type'));  
        if($chk_form_type == 'make_payment')
        {
          $this->form_validation->set_rules('utr_no', 'NEFT / RTGS (UTR) Number', 'trim|required|max_length[30]|xss_clean', array('required'=>"Please enter the %s"));               
        }//END : SERVER SIDE VALIDATION 

        $add_payment_data = array();
        if($this->form_validation->run() == TRUE)
        {
          if($chk_form_type == 'candidate_selection') { }
          else if($chk_form_type == 'make_payment')
          {
            $form_selcted_member_exam_ids_str = $this->security->xss_clean($this->input->post('selcted_member_exam_ids_str')); 
            $form_total_fees = $this->security->xss_clean($this->input->post('form_total_fees')); 
            $form_exam_code = $this->security->xss_clean($this->input->post('form_exam_code')); 
            $form_exam_period = $this->security->xss_clean($this->input->post('form_exam_period')); 
            $form_exam_from_date = $this->security->xss_clean($this->input->post('form_exam_from_date')); 
            $form_exam_to_date = $this->security->xss_clean($this->input->post('form_exam_to_date')); 
            $utr_no = $this->security->xss_clean($this->input->post('utr_no'));
            $selcted_member_exam_ids_arr = explode(",",$form_selcted_member_exam_ids_str); 

            $state_code = $agency_centre_data[0]['centre_state']; //logged in centre state code
                                    
            $regnumber_arr = $candidate_id_arr = array();
            $candidate_id_str = '';
            $total_candidate_cnt = count($selcted_member_exam_ids_arr);
            $free_candidate_cnt = $calculated_total_fee = 0;
            foreach($candidate_data as $candidate_res)
            {
              $calculated_total_fee = $calculated_total_fee + $candidate_res['exam_fee']; 
              $candidate_id_arr[] = $candidate_res['candidate_id'];

              if($candidate_res['fee_paid_flag'] != 'F') 
              { 
                $regnumber_arr[] = $candidate_res['regnumber'];
              }
              else { $free_candidate_cnt++; }
            }
            $candidate_id_str = implode(",",$candidate_id_arr);
            
            if($form_selcted_member_exam_ids_str == $selcted_member_exam_ids_str && $form_total_fees == $calculated_total_fee && $form_exam_code == $active_exam_data[0]['exam_code'] && $form_exam_period == $active_exam_data[0]['exam_period'] && $form_exam_from_date == $active_exam_data[0]['exam_from_date'] && $form_exam_to_date == $active_exam_data[0]['exam_to_date'])
            {
              //START : INSERT PAYMENT TABLE ENTRY
              $pg_flag = 'BC';
              $add_payment_data['agency_id'] = $agency_centre_data[0]['agency_id'];
              $add_payment_data['centre_id'] = $this->login_agency_or_centre_id;                
              $add_payment_data['exam_ids'] = $selcted_member_exam_ids_str;
              $add_payment_data['amount'] = $calculated_total_fee;
              $add_payment_data['gateway'] = '1';  // 1= NEFT / RTGS
              $add_payment_data['UTR_no'] = $utr_no;
              $add_payment_data['agency_code'] = $agency_centre_data[0]['agency_code'];              
              $add_payment_data['pay_count'] =  $total_candidate_cnt;
              $add_payment_data['exam_code'] =  $active_exam_data[0]['exam_code'];
              $add_payment_data['exam_period'] =  $active_exam_data[0]['exam_period'];
              $add_payment_data['payment_mode'] =  'Bulk';
              $add_payment_data['pg_flag'] = $pg_flag;
              $add_payment_data['status'] =  '3'; //applied for approval by admin
              $add_payment_data['payment_done_by_agency_id'] =  $this->login_agency_id;
              $add_payment_data['payment_done_by_centre_id'] =  $this->login_centre_id;
              $add_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
              $add_payment_data['created_on'] = date('Y-m-d H:i:s');
              $add_payment_data['created_by'] = $this->login_agency_or_centre_id;
              $pt_id = $this->master_model->insertRecord('iibfbcbf_payment_transaction', $add_payment_data, true);
              //echo $this->db->last_query(); exit;

              if($pt_id > 0)
              {
                $posted_arr = json_encode($_POST);
                $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');
                
                $this->Iibf_bcbf_model->insert_common_log('Centre : Generate Proforma Invoice', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'make_payment','The '.$centreName['disp_name'].' successfully generated the proforma invoice of '.$total_candidate_cnt.' candidates('.$candidate_id_str.') for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', $posted_arr);
                
                $this->master_model->updateRecord('iibfbcbf_payment_transaction', array('receipt_no'=>$pt_id), array('id'=>$pt_id));
                //END : INSERT PAYMENT TABLE ENTRY
                
                $totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = $cess = $unit_R = $unit_S1 = 0;                   
                foreach ($candidate_data as $candidate_res)
                {
                  //START : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
                  $up_exam_arr = array();
                  $up_exam_arr['pay_status'] = '3';
                  $up_exam_arr['payment_mode'] = 'Bulk';
                  $up_exam_arr['exam_period'] = $active_exam_data[0]['exam_period'];
                  $up_exam_arr['exam_date'] = $active_exam_data[0]['exam_date'];
                  $up_exam_arr['ref_utr_no'] = $utr_no;
                  $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_arr,array('member_exam_id'=>$candidate_res['member_exam_id']));
                  //END : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
                  
                  $this->Iibf_bcbf_model->insert_common_log('Centre : Generate Proforma Invoice', 'iibfbcbf_member_exam', $this->db->last_query(), $candidate_res['member_exam_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated the payment status and exam period of the candidate', $posted_arr);
                  
                  $fee_paid_flag = $candidate_res['fee_paid_flag'];
                  $member_no = $candidate_res['regnumber']; 
                  $registration_type = $candidate_res['registration_type']; // NM,O
                    
                  /* START : GET GROUP CODE */
                  $group_code = $this->Iibf_bcbf_model->get_group_code($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'],$member_no);
                  
                  if($member_no != 0 && $member_no != "")
                  {                    
                    /* $eligible_master_data = $this->master_model->getRecords('iibfbcbf_eligible_master',array('member_no'=>$member_no,'exam_code'=>$active_exam_data[0]['exam_code'], 'eligible_period'=>$active_exam_data[0]['exam_period']),'app_category');
                    
                    if(count($eligible_master_data)>0)
                    {
                      if($eligible_master_data[0]['app_category'] == 'R' || $eligible_master_data[0]['app_category'] == 'B1')
                      {
                        $unit_R = $unit_R+1;                      
                      }
                      elseif($eligible_master_data[0]['app_category'] == 'S1') { $unit_S1 = $unit_S1+1; }
                      else { $unit_R = $unit_R+1; }
                    }
                    else
                    {
                      $unit_R = $unit_R+1;
                    } */

                    //ELIGIBLE MASTER API CODE GOES HERE
                    $eligible_api_res = $this->Iibf_bcbf_model->iibf_bcbf_eligible_master_api($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $member_no);                                          
                    if($eligible_api_res['api_res_flag'] == 'success')
                    {
                      if(isset($eligible_api_res['api_res_response'][0]) && count($eligible_api_res['api_res_response'][0]) > 0)
                      {
                        $eligible_data = $eligible_api_res['api_res_response'][0];
                        if($eligible_data['app_cat'] == 'B1_1')
                        {
                          $unit_R = $unit_R+1;                      
                        }
                        elseif($eligible_data['app_cat'] == 'B1_2') { $unit_S1 = $unit_S1+1; }
                        else { $unit_R = $unit_R+1; }
                      }
                      else { $unit_R = $unit_R+1; }
                    }
                    else
                    {
                      $unit_R = $unit_R+1;
                    }
                  }
                  else { $unit_R = $unit_R+1; }
                  /* END : GET GROUP CODE */
                    
                  if($fee_paid_flag != 'F')
                  {
                    $fee_master = $this->master_model->getRecords('iibfbcbf_exam_fee_master',array('fee_delete'=>'0', 'member_category'=>$registration_type, 'group_code'=>$group_code, 'exempt'=>'NE', 'exam_code'=>$active_exam_data[0]['exam_code'], 'exam_period'=>$active_exam_data[0]['exam_period']));
                    
                    if(count($fee_master) > 0)
                    {                  
                      $totol_amt = $totol_amt + $fee_master[0]['fee_amount'];
                      
                      if($state_code == 'MAH')
                      {
                        $total_cgst_amt = $total_cgst_amt + $fee_master[0]['cgst_amt'];
                        $total_sgst_amt = $total_sgst_amt + $fee_master[0]['sgst_amt'];
                      }
                      else
                      {
                        $total_igst_amt = $total_igst_amt + $fee_master[0]['igst_amt'];
                      }
                    }
                  }						
                }
                
                $fee_amt = $totol_amt; // Total amount without any GST                
                $tax_type = '';
                if($state_code == 'MAH')
                {
                  //set a rate (e.g 9%,9% or 18%)
                  $cgst_rate = $this->config->item('cgst_rate');
                  $sgst_rate = $this->config->item('sgst_rate');

                  //set an amount as per rate
                  $cgst_amt = $total_cgst_amt;
                  $sgst_amt = $total_sgst_amt;
                  $cs_total = $calculated_total_fee;
                  $tax_type = 'Intra';
                }
                else
                {
                  //set a rate (e.g 9%,9% or 18%)
                  $igst_rate = $this->config->item('igst_rate');
                  $igst_amt = $total_igst_amt;
                  $igst_total = $calculated_total_fee;
                  $tax_type = 'Inter';
                }	
                
                //START : INSERT EXAM INVOICE TABLE ENTRY
                $iibfbcbf_fees_data = $this->Iibf_bcbf_model->iibfbcbf_get_fees($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period']);
                if($calculated_total_fee > 0) 
                {
                  $add_invoice = array();
                  $add_invoice['pay_txn_id'] = $pt_id;
                  $add_invoice['receipt_no'] = $pt_id;
                  $add_invoice['exam_code'] = $active_exam_data[0]['exam_code'];
                  $add_invoice['exam_period'] = $active_exam_data[0]['exam_period'];

                  //FOR INDIVIDUAL & CSC : center_code, center_name, state_of_center : THIS VALUE SHOULD BE EXAM CENTER CODE, EXAM CENTER NAME & EXAM CENTER STATE
                  //FOR BULK : center_code, center_name, state_of_center : THIS VALUE SHOULD BE LOGGED IN CENTER CODE, CENTER NAME & CENTER STATE
                  $add_invoice['center_code'] = $agency_centre_data[0]['centre_id'];
                  $add_invoice['center_name'] = $agency_centre_data[0]['centre_name'];
                  $add_invoice['state_of_center'] = $state_code;
                                   
                  $add_invoice['institute_code'] = $agency_centre_data[0]['agency_code'];
                  $add_invoice['institute_name'] = $agency_centre_data[0]['agency_name'];
                  $add_invoice['app_type'] = $pg_flag;//xxx need to check this
                  $add_invoice['tax_type'] = $tax_type;
                  $add_invoice['service_code'] = $this->config->item('exam_service_code');
                  $add_invoice['gstin_no'] = '';
                  $add_invoice['qty'] = count($candidate_id_arr) - $free_candidate_cnt;
                  $add_invoice['state_code'] = $agency_centre_data[0]['state_no'];
                  $add_invoice['state_name'] = $agency_centre_data[0]['state_name'];
                  $add_invoice['fresh_fee'] = $iibfbcbf_fees_data['fresh_fee'];
                  $add_invoice['rep_fee'] = $iibfbcbf_fees_data['rep_fee'];
                  $add_invoice['fresh_count'] = $unit_R;
                  $add_invoice['rep_count'] = $unit_S1;
                  $add_invoice['fee_amt'] = $fee_amt;
                  $add_invoice['cgst_rate'] = $cgst_rate;
                  $add_invoice['cgst_amt'] = $cgst_amt;
                  $add_invoice['sgst_rate'] = $sgst_rate;
                  $add_invoice['sgst_amt'] = $sgst_amt;
                  $add_invoice['igst_rate'] = $igst_rate;
                  $add_invoice['igst_amt'] = $igst_amt;
                  $add_invoice['cs_total'] = $cs_total;
                  $add_invoice['igst_total'] = $igst_total;
                  $add_invoice['cess'] = $cess;
                  $add_invoice['exempt'] = $agency_centre_data[0]['exempt'];
                  $add_invoice['transaction_no'] = $utr_no;
                  $add_invoice['created_on'] = date('Y-m-d H:i:s');
                  $invoice_id = $this->master_model->insertRecord('exam_invoice',$add_invoice,true);

                  //echo _pq();
                  //echo "<br>invoice_id==".$invoice_id."<br>";
                  //print_r($add_invoice);
                  $this->Iibf_bcbf_model->insert_common_log('Centre : Generate Proforma Invoice', 'exam_invoice', $this->db->last_query(), $invoice_id,'make_payment','The '.$centreName['disp_name'].' successfully inserted record in exam invoice table', $posted_arr);
                  //die;
                }//END : INSERT EXAM INVOICE TABLE ENTRY

                $this->session->set_flashdata('success','Proforma Invoice Generated');
                redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
              }
              else
              {
                $this->session->set_flashdata('error','Error occurred while generating Proforma Invoice.');
                redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
              }
            }
            else
            {
              $this->session->set_flashdata('error','There is some error in posted data');
              redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
            }            
          } 
          else
          {
            $this->session->set_flashdata('error','Invalid form submission detected.');
            redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
          }         
        }     
      }
      else
      {
        $this->session->set_flashdata('error','URL is edited or page was refreshed. Please try again');
        redirect(site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code));
      }

      $this->load->view('iibfbcbf/agency/make_payment_agency', $data);
    }//END : THIS FUNCTION IS USED TO MAKE THE SELECTED CANDIDATES PAYMENT

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
    
    /******** START : TO GET THE VENUE DETAILS FOR SELECTED CENTRE CODE AND VALID FOR EXAM CODE 1041,1042 ********/
    function get_venue_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $centre_code = $this->security->xss_clean($this->input->post('centre_code'));
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
				$exam_period = $this->security->xss_clean($this->input->post('exam_period'));
				$selected_venue_name = $this->security->xss_clean($this->input->post('selected_venue_name'));

        $exam_date_subject_master_arr = array();
        $subject_master_data = $this->master_model->getRecords('iibfbcbf_exam_subject_master',array('exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'C'),'exam_date',array('subject_code'=>'ASC'));
        if(count($subject_master_data) > 0)
        {
          foreach($subject_master_data as $subject_res)
          {
            $exam_date_subject_master_arr[] = $subject_res['exam_date'];
          }
        }
                
        $this->db->group_by('venue_code');
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $this->db->where_in('exam_date', $exam_date_subject_master_arr);
        $venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('centre_code' => $centre_code, 'exam_period' => $exam_period, 'exam_date >'=>date("Y-m-d")), 'venue_master_id, exam_date, centre_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_pincode, centre_code, exam_period, exam_codes', array('venue_addr1'=>'ASC'));
                
        $html = '	<select required class="form-control" name="venue_name" id="venue_name" onchange="get_venue_date_details(this.value)">';
        if(count($venue_data) > 0 && in_array($exam_code, $this->venue_master_eligible_exam_codes_arr)) //VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
				{
					$html .= '	<option value="">Select Venue Name</option>';
					foreach($venue_data as $venue_res)
					{
            $selected = '';
            if($selected_venue_name == $venue_res['venue_code']) { $selected = 'selected'; }

            $disp_name = $venue_res['venue_name'];
            if($venue_res['venue_addr1'] != "") { $disp_name .= ' ('.$venue_res['venue_addr1'].')'; }
						$html .= '	<option value="'.$venue_res['venue_code'].'" '.$selected.'>'.$disp_name.'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select Venue Name</option>';
        }
				$html .= '</select>';
				
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE DETAILS FOR SELECTED CENTRE CODE AND VALID FOR EXAM CODE 1041,1042 ********/

    /******** START : TO GET THE VENUE DATE DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1041,1042 ********/
    function get_venue_date_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $centre_code = $this->security->xss_clean($this->input->post('centre_code'));
        $venue_code = $this->security->xss_clean($this->input->post('venue_code'));
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
				$exam_period = $this->security->xss_clean($this->input->post('exam_period'));
				$selected_venue_date = $this->security->xss_clean($this->input->post('selected_venue_date'));
        
        $this->db->group_by('exam_date');
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('centre_code' => $centre_code, 'venue_code' => $venue_code, 'exam_period' => $exam_period, 'exam_date >'=>date("Y-m-d")), 'venue_master_id, exam_date, centre_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_pincode, centre_code, exam_period, exam_codes', array('venue_name'=>'ASC'));
        
        $html = '	<select required class="form-control" name="exam_date" id="exam_date" onchange="get_venue_time_details(this.value)">';
        if(count($venue_data) > 0 && in_array($exam_code, $this->venue_master_eligible_exam_codes_arr)) //VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
				{
					$html .= '	<option value="">Select Exam Date</option>';
					foreach($venue_data as $venue_res)
					{
            $selected = '';
            if($selected_venue_date == $venue_res['exam_date']) { $selected = 'selected'; }
						$html .= '	<option value="'.$venue_res['exam_date'].'" '.$selected.'>'.$venue_res['exam_date'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select Exam Date</option>';
        }
				$html .= '</select>';
				
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE DATE DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1041,1042 ********/

    /******** START : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1041,1042 ********/
    function get_venue_time_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $centre_code = $this->security->xss_clean($this->input->post('centre_code'));
        $venue_code = $this->security->xss_clean($this->input->post('venue_code'));
        $exam_date = $this->security->xss_clean($this->input->post('exam_date'));
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
				$exam_period = $this->security->xss_clean($this->input->post('exam_period'));
				$selected_venue_time = $this->security->xss_clean($this->input->post('selected_venue_time'));
        
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('centre_code' => $centre_code, 'venue_code' => $venue_code, 'exam_date' => $exam_date, 'exam_period' => $exam_period, 'exam_date >'=>date("Y-m-d")), 'venue_master_id, exam_date, centre_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_pincode, centre_code, exam_period, exam_codes', array('venue_name'=>'ASC'));
               
        $html = '	<select required class="form-control" name="exam_time" id="exam_time" onchange="get_capacity_details(this.value)">';
        if(count($venue_data) > 0 && in_array($exam_code, $this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
				{
					$html .= '	<option value="">Select Exam Time</option>';
					foreach($venue_data as $venue_res)
					{
            $selected = '';
            if($selected_venue_time == $venue_res['session_time']) { $selected = 'selected'; }
						$html .= '	<option value="'.$venue_res['session_time'].'" '.$selected.'>'.$venue_res['session_time'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select Exam Time</option>';
        }
				$html .= '</select>';
				
        $result['flag'] = "success";
        $result['response'] = $html;        
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1041,1042 ********/

    /******** START : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1041,1042 ********/
    function get_capacity_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $centre_code = $this->security->xss_clean($this->input->post('centre_code'));
        $venue_code = $this->security->xss_clean($this->input->post('venue_code'));
        $exam_date = $this->security->xss_clean($this->input->post('exam_date'));
        $exam_time = $this->security->xss_clean($this->input->post('exam_time'));
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
				$exam_period = $this->security->xss_clean($this->input->post('exam_period'));
				$chk_member_exam_id = $this->security->xss_clean($this->input->post('chk_member_exam_id'));
        
        if($centre_code != '' && $venue_code != '' && $exam_date != '' && $exam_time != '' && $exam_code != '' && $exam_period != '')
        {
          $result['flag'] = "success";
          $result['response'] = $this->Iibf_bcbf_model->get_capacity_bulk($exam_code, $exam_period, $centre_code, $venue_code, $exam_date, $exam_time, $chk_member_exam_id);
        }
        else
        {
          $result['flag'] = "success";
          $result['response'] = '-';
        }
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1041,1042 ********/
  } ?>  