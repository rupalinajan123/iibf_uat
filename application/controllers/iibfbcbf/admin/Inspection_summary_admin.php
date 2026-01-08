<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF ADMIN INSPECTION SUMMAY REPORT
  ** Created BY: Sagar Matale On 09-01-2024
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Inspection_summary_admin extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      
      $this->login_agency_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'admin' && $this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'admin')
      {
        $this->session->set_flashdata('error','You do not have permission to access Faculty module');
        redirect(site_url('iibfbcbf/admin/dashboard_agency'));
      }

      $this->training_schedule_file_path = 'uploads/iibfbcbf/training_schedule';
      $this->inspection_report_by_admin_file_path = 'uploads/iibfbcbf/inspection_report_by_admin';
      $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->inspection_attachment_path = 'uploads/iibfbcbf/inspection_attachment';
    }

    public function index($enc_batch_id=0)
    {
      $data['act_id'] = "Reports";
			$data['sub_act_id'] = "Inspection Summary";
			$data['candidate_photo_path'] = $this->candidate_photo_path;
			$data['inspection_attachment_path'] = $inspection_attachment_path = $this->inspection_attachment_path;
      
      $this->db->group_by('bi.batch_id');
      $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = bi.batch_id', 'INNER');
      $data['batch_dropdown_data'] = $this->master_model->getRecords('iibfbcbf_batch_inspection bi', array('btch.is_deleted'=>'0'), 'btch.batch_id, btch.batch_code, btch.batch_hours, btch.batch_start_date, btch.batch_end_date, btch.inspector_id');
      
      $batch_id = '0';
      if($enc_batch_id != '0')
      {
        $batch_id = url_decode($enc_batch_id);

        $inspection_all_data = $this->Iibf_bcbf_model->get_inspection_data($batch_id);

        $data['batch_data'] = $batch_data = $inspection_all_data['batch_data'];
        $data['user_data'] = $inspection_all_data['user_data'];
        $data['bank_cand_data'] = $inspection_all_data['bank_cand_data'];
        $data['centre_data'] = $inspection_all_data['centre_data'];
        $data['inspection_data'] = $inspection_data = $inspection_all_data['inspection_data'];
        $data['batch_candidate_data'] = $inspection_all_data['batch_candidate_data'];
        $data['candidate_inspection_data_arr'] = $inspection_all_data['candidate_inspection_data_arr'];
        
        if(count($batch_data) == 0 || count($inspection_data) == 0) { redirect(site_url('iibfbcbf/admin/inspection_summary_admin')); }
      }

      $data['batch_id'] = $batch_id;
      $data['enc_batch_id'] = $enc_batch_id;
      $data['page_title'] = 'IIBF - BCBF Inspection Summary Report';
      $this->load->view('iibfbcbf/admin/inspection_summary_report_admin', $data);
    }

    function candidates_inspection_details($enc_batch_id='0', $enc_candidate_id='0')
    {
      $data['act_id'] = "Reports";
			$data['sub_act_id'] = "Inspection Summary";

      $batch_id = $candidate_id = '0';
      if($enc_batch_id != '0')
      {
        $batch_id = url_decode($enc_batch_id);

        $this->db->where_in('acb.batch_status', array(3,4));
        $data['batch_data'] = $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.is_deleted' => '0'), "acb.batch_id, acb.agency_id, acb.centre_id, acb.inspector_id, acb.batch_code");
        
        if(count($batch_data) == 0) { redirect(site_url('iibfbcbf/admin/inspection_summary_admin/index/'.$enc_batch_id)); }
      }
      
      if($enc_candidate_id != '0')
      {
        $candidate_id = url_decode($enc_candidate_id);
        $data['candidate_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.agency_id'=>$batch_data[0]['agency_id'], 'bc.centre_id'=>$batch_data[0]['centre_id'], 'bc.batch_id'=>$batch_id, 'bc.is_deleted'=>'0', 'bc.candidate_id'=>$candidate_id), 'bc.candidate_id, bc.training_id, bc.regnumber, bc.salutation, bc.first_name, bc.middle_name, bc.last_name, bc.dob, bc.mobile_no, bc.email_id, bc.candidate_photo, bc.hold_release_status');
                
        if(count($candidate_data) == 0) { redirect(site_url('iibfbcbf/admin/inspection_summary_admin/index/'.$enc_batch_id)); }
      }
       
      $this->db->join('iibfbcbf_batch_inspection bi', 'bi.inspection_id = ci.batch_inspection_id', 'INNER');
      $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = ci.inspector_id', 'INNER');
      $data['candidate_inspection_data'] = $candidate_inspection_data = $this->master_model->getRecords('iibfbcbf_candidate_inspection ci', array('ci.batch_id'=>$batch_id, 'ci.candidate_id'=>$candidate_id), 'ci.batch_id, ci.batch_inspection_id, ci.candidate_id, ci.inspector_id, ci.attendance, ci.remark, ci.created_on, bi.inspection_no, im.inspector_name', array('ci.id'=>'ASC'));
            
      $data['batch_id'] = $batch_id;
      $data['enc_batch_id'] = $enc_batch_id;
      $data['candidate_id'] = $candidate_id;
      $data['enc_candidate_id'] = $enc_candidate_id;
      $data['page_title'] = 'IIBF - BCBF Candidate Inspection Details';
      $this->load->view('iibfbcbf/admin/candidates_inspection_details_admin', $data);
    }

    function change_hold_release_status()
    {
      if(isset($_POST['cand_id']) && $_POST['cand_id'] != "" && isset($_POST['status']) && $_POST['status'] != "")
			{
        $response = $this->Iibf_bcbf_model->change_candidate_hold_release_status_common($_POST, 'admin');	

        if($response != 'success') { $this->session->set_flashdata('error','Error occurred. Please try again.'); }
        echo $response;
      }
			else
			{
        $this->session->set_flashdata('error','Error occurred. Please try again.');
				echo "error";
      }
    }

    function apply_search()
    { 
      if(isset($_POST) && count($_POST) > 0 && isset($_POST['export_to_pdf']) && $_POST['export_to_pdf'] != "")
      {
        $batch_id = trim($this->security->xss_clean($this->input->post('s_batch_id')));
        $inspection_all_data = $this->Iibf_bcbf_model->get_inspection_data($batch_id);

        $data['batch_data'] = $batch_data = $inspection_all_data['batch_data'];
        $data['user_data'] = $inspection_all_data['user_data'];
        $data['bank_cand_data'] = $inspection_all_data['bank_cand_data'];
        $data['centre_data'] = $inspection_all_data['centre_data'];
        $data['inspection_data'] = $inspection_data = $inspection_all_data['inspection_data'];
        $data['batch_candidate_data'] = $inspection_all_data['batch_candidate_data'];
        $data['candidate_inspection_data_arr'] = $inspection_all_data['candidate_inspection_data_arr'];
        
        $data['candidate_photo_path'] = $this->candidate_photo_path;
			  $data['inspection_attachment_path'] = $inspection_attachment_path = $this->inspection_attachment_path;
        
        $data['inspection_report_pdf_flag'] = '1';
        $data['inspection_report_by_admin_file_path'] = $this->inspection_report_by_admin_file_path;
        $data['inspection_attachment_path'] = $this->inspection_attachment_path;

        if(count($batch_data) == 0 || count($inspection_data) == 0) { redirect(site_url('staging/iibfbcbf/admin/inspection_summary_admin')); }
        
        $html = $this->load->view('iibfbcbf/common/inc_inspection_report_content_pdf', $data, true);
        //echo $html; die;
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdfFilePath = 'batch_inspection_report_pdf_'.$batch_data[0]['batch_code'].".pdf";
        //generate the PDF from the given html
        $pdf->WriteHTML($html);
        //download it.
        $pdf->Output($pdfFilePath, "D");
        exit;
      }

      $enc_batch_id = 0;
      if(isset($_POST) && count($_POST) > 0)
      {
        $enc_batch_id = url_encode(trim($this->security->xss_clean($this->input->post('s_batch_id'))));
      }
      redirect(site_url('iibfbcbf/admin/inspection_summary_admin/index/'.$enc_batch_id));
    }
  }