<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency/Centre Batch Applicant Checklist
  ** Created BY: Sagar Matale On 18-12-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Batch_applicant_checklist extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      $this->id_proof_file_path = 'uploads/iibfbcbf/id_proof';
      $this->qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
      $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->candidate_sign_path = 'uploads/iibfbcbf/sign';

      $this->agency_id = 0;
      if($this->login_user_type == 'agency') 
      { 
        $this->agency_id = $this->login_agency_or_centre_id; 
      }
      else if($this->login_user_type == 'centre')
      {
        $agency_id_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id' => $this->login_agency_or_centre_id), "agency_id");
        if(count($agency_id_data) > 0)
        {
          $this->agency_id = $agency_id_data[0]['agency_id'];
        }
      }

      $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->agency_id), "agency_id, allow_exam_codes, allow_exam_types");
      
      if(count($agency_data) > 0)
      {
        if($agency_data[0]['allow_exam_types'] == 'CSC')
        {
          //$this->session->set_flashdata('error','You do not have permission to access Batch applicant checklist module');
          //redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
		}
    
    public function index()
    {   
      $data['act_id'] = "Batch Applicant Checklist";
      $data['sub_act_id'] = "Batch Applicant Checklist";
      $data['page_title'] = 'IIBF - BCBF Agency Batch Applicant Checklist';

      if($this->login_user_type == 'centre')
      {
        $this->db->where('btch.centre_id', $this->login_agency_or_centre_id);
      }
      else if($this->login_user_type == 'agency')
      {
        $this->db->where('btch.agency_id', $this->login_agency_or_centre_id);
      }

      $this->db->where_not_in('btch.batch_status',array(0,1,8));
      $data['batch_data'] = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.is_deleted' => '0'), 'btch.batch_id, btch.agency_id, btch.centre_id, btch.batch_code, btch.batch_type, btch.batch_hours, btch.batch_start_date, btch.batch_end_date, btch.batch_status',array('btch.batch_id'=>'DESC')); 
      //echo $this->db->last_query(); exit;
      
      $this->load->view('iibfbcbf/agency/batch_applicant_checklist', $data);
    }

    //START : SERVER SIDE AJAX CALL TO GET CANDIDATE LISTING
    function get_candidate_list()
    {
      $result['flag'] = "error";
      $result['response_data'] = '';
			if(isset($_POST))
			{
        if($_POST['enc_batch_id'] != "")
        {        
          $batch_id = url_decode($this->security->xss_clean($this->input->post('enc_batch_id')));  
          $this->db->where_not_in('btch.batch_status',array(0,1,8));
          $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.batch_id' => $batch_id, 'btch.is_deleted' => '0'), 'batch_id');

          if(count($batch_data) > 0)
          {   
            $result['flag'] = "success";

            $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.batch_id' => $batch_id, 'cand.is_deleted' => '0'), 'cand.candidate_id, cand.regnumber, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, cand.gender, cand.mobile_no, cand.alt_mobile_no, cand.email_id, cand.alt_email_id, cand.qualification, cand.address1, cand.address2, cand.address3, cand.address4, cand.id_proof_type, cand.id_proof_number, cand.id_proof_file, cand.qualification_certificate_file, cand.candidate_photo, cand.candidate_sign');

            $result['candidate_count'] = count($candidate_data);

            $data['candidate_data'] = $candidate_data;
            $result['response_data'] = $this->load->view('iibfbcbf/common/inc_batch_applicant_checklist_candidate_list_common', $data, true);
          }
        }
        else
        {
          $result['flag'] = "success";          
        }
			} 
			
      echo json_encode($result);
    }//END : SERVER SIDE AJAX CALL TO GET CANDIDATE LISTING

    /******** START : SERVER SIDE AJAX CALL TO GET THE CANDIDATES DATA ********/
    public function get_candidate_list_ajax()
    { 
      $batch_id = 0;
      $enc_batch_id = trim($this->security->xss_clean($this->input->post('s_batch')));
      if($enc_batch_id != "") {  $batch_id = url_decode($enc_batch_id);  }

      $table = 'iibfbcbf_batch_candidates cand';      

      //$id_proof_type_arr = array('1'=>'Aadhar Card', '2'=>'Driving Licence', '3'=>'Employee ID', '4'=>'Pan Card', '5'=>'Passport');
      //$qualification_arr = array('1'=>'Under Graduate', '2'=>'Graduate', '3'=>'Post Graduate');
      $column_order = array('cand.candidate_id', 'cand.training_id', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), "")) AS DispName', 'IF(cand.id_proof_type = 1, "Aadhar Card", IF(cand.id_proof_type = 2, "Driving Licence", IF(cand.id_proof_type = 3, "Employee ID", IF(cand.id_proof_type = 4, "Pan Card", IF(cand.id_proof_type = 5, "Passport",""))))) AS DispIdProofType', 'cand.id_proof_number', 'cand.candidate_photo', 'cand.candidate_sign', 'cand.id_proof_file', 'cand.qualification_certificate_file', 'IF(cand.gender = 1, "Male", IF(cand.gender = 2, "Female", "")) AS DispGender', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'CONCAT(cand.address1, IF(cand.address2 != "", CONCAT(" ", cand.address2), ""), IF(cand.address3 != "", CONCAT(" ", cand.address3), ""), IF(cand.address4 != "", CONCAT(" ", cand.address4), "")) AS DispAddress', 'IF(cand.qualification = 1, "Under Graduate", IF(cand.qualification = 2, "Graduate", IF(cand.qualification = 3, "Post Graduate", ""))) AS DispQualification'); //SET COLUMNS FOR SORT
      
      $column_search = array('cand.training_id', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))', 'IF(cand.id_proof_type = 1, "Aadhar Card", IF(cand.id_proof_type = 2, "Driving Licence", IF(cand.id_proof_type = 3, "Employee ID", IF(cand.id_proof_type = 4, "Pan Card", IF(cand.id_proof_type = 5, "Passport","")))))', 'cand.id_proof_number', 'IF(cand.gender = 1, "Male", IF(cand.gender = 2, "Female", ""))', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'CONCAT(cand.address1, IF(cand.address2 != "", CONCAT(" ", cand.address2), ""), IF(cand.address3 != "", CONCAT(" ", cand.address3), ""), IF(cand.address4 != "", CONCAT(" ", cand.address4), ""))', 'IF(cand.qualification = 1, "Under Graduate", IF(cand.qualification = 2, "Graduate", IF(cand.qualification = 3, "Post Graduate", "")))'); //SET COLUMN FOR SEARCH
      $order = array('cand.candidate_id' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE cand.batch_id = '".$batch_id."' AND cand.is_deleted = '0' AND btch.is_deleted = '0' AND btch.batch_status NOT IN (0,1) "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE cand.batch_id = '".$batch_id."' AND cand.is_deleted = '0' AND btch.is_deleted = '0' AND btch.batch_status NOT IN (0,1) ";

      if($this->login_user_type == 'centre')
      {
        $WhereForTotal .= " AND cand.centre_id = '".$this->login_agency_or_centre_id."' ";
        $Where .= " AND cand.centre_id = '".$this->login_agency_or_centre_id."' ";
      }
      else if($this->login_user_type == 'agency')
      {
        $WhereForTotal .= " AND cand.agency_id = '".$this->login_agency_or_centre_id."' ";
        $Where .= " AND cand.agency_id = '".$this->login_agency_or_centre_id."' ";
      }      
      
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
      
      $join_qry = " INNER JOIN iibfbcbf_agency_centre_batch btch ON btch.batch_id = cand.batch_id"; 
            
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
        $row[] = $Res['training_id'];
        $row[] = $Res['DispName'];
        $row[] = $Res['DispIdProofType'];
        $row[] = $Res['id_proof_number'];

        $candidate_photo = '<div id="candidate_photo_preview" class="upload_img_preview" style="margin:0 auto;">';
        if($Res['candidate_photo'] != "")
        {
          $candidate_photo_link = base_url($this->candidate_photo_path.'/'.$Res['candidate_photo'])."?".time();
          
          $candidate_photo .= '<a href="'.$candidate_photo_link.'"  class="example-image-link" data-lightbox="candidate_images" data-title="'.$Res['DispName']." (".$Res['training_id'].")".'">
            <img src="'.$candidate_photo_link.'">
          </a>';
        }
        else
        {
          $candidate_photo .= '<i class="fa fa-picture-o" aria-hidden="true"></i>';
        }
        $candidate_photo .= '</div>';
        $row[] = $candidate_photo;

        $candidate_sign = '<div id="candidate_sign_preview" class="upload_img_preview" style="margin:0 auto;">';
        if($Res['candidate_sign'] != "")
        {
          $candidate_sign_link = base_url($this->candidate_sign_path.'/'.$Res['candidate_sign'])."?".time();
          
          $candidate_sign .= '<a href="'.$candidate_sign_link.'"  class="example-image-link" data-lightbox="candidate_images" data-title="'.$Res['DispName']." (".$Res['training_id'].")".'">
            <img src="'.$candidate_sign_link.'">
          </a>';
        }
        else
        {
          $candidate_sign .= '<i class="fa fa-picture-o" aria-hidden="true"></i>';
        }
        $candidate_sign .= '</div>';
        $row[] = $candidate_sign;

        $id_proof_file = '<div id="id_proof_file_preview" class="upload_img_preview" style="margin:0 auto;">';
        if($Res['id_proof_file'] != "")
        {
          $id_proof_file_link = base_url($this->id_proof_file_path.'/'.$Res['id_proof_file'])."?".time();
          
          $id_proof_file .= '<a href="'.$id_proof_file_link.'"  class="example-image-link" data-lightbox="candidate_images" data-title="'.$Res['DispName']." (".$Res['training_id'].")".'">
            <img src="'.$id_proof_file_link.'">
          </a>';
        }
        else
        {
          $id_proof_file .= '<i class="fa fa-picture-o" aria-hidden="true"></i>';
        }
        $id_proof_file .= '</div>';
        $row[] = $id_proof_file;
        
        $qualification_certificate_file = '<div id="qualification_certificate_file_preview" class="upload_img_preview" style="margin:0 auto;">';
        if($Res['qualification_certificate_file'] != "")
        {
          $qualification_certificate_file_link = base_url($this->qualification_certificate_file_path.'/'.$Res['qualification_certificate_file'])."?".time();
          
          $qualification_certificate_file .= '<a href="'.$qualification_certificate_file_link.'"  class="example-image-link" data-lightbox="candidate_images" data-title="'.$Res['DispName']." (".$Res['training_id'].")".'">
            <img src="'.$qualification_certificate_file_link.'">
          </a>';
        }
        else
        {
          $qualification_certificate_file .= '<i class="fa fa-picture-o" aria-hidden="true"></i>';
        }
        $qualification_certificate_file .= '</div>';
        $row[] = $qualification_certificate_file;

       
        $row[] = $Res['DispGender'];
        $row[] = $Res['dob'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id'];
        $row[] = $Res['DispAddress'];
        $row[] = $Res['DispQualification'];       
        
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
    }/******** END : SERVER SIDE AJAX CALL TO GET THE CANDIDATES DATA ********/

    function export_to_pdf()
    {
      if(isset($_POST) && count($_POST) > 0)
			{     
        $batch_id = url_decode($this->security->xss_clean($this->input->post('s_batch')));  
        
        if($this->login_user_type == 'centre')
        {
          $this->db->where('cand.centre_id',$this->login_agency_or_centre_id);
        }
        else if($this->login_user_type == 'agency')
        {
          $this->db->where('cand.agency_id',$this->login_agency_or_centre_id);
        }

        $this->db->where_not_in('btch.batch_status',array(0,1));
        $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = cand.batch_id', 'INNER');
        $this->db->select('(IF(cand.id_proof_type = 1, "Aadhar Card", IF(cand.id_proof_type = 2, "Driving Licence", IF(cand.id_proof_type = 3, "Employee ID", IF(cand.id_proof_type = 4, "Pan Card", IF(cand.id_proof_type = 5, "Passport","")))))) AS DispIdProofType, (IF(cand.qualification = 1, "Under Graduate", IF(cand.qualification = 2, "Graduate", IF(cand.qualification = 3, "Post Graduate", "")))) AS DispQualification',FALSE);
        $data['candidate_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.batch_id' => $batch_id, 'cand.is_deleted' => '0', 'btch.is_deleted' => '0'), 'cand.candidate_id, cand.training_id, (CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))) AS DispName, cand.id_proof_number, cand.candidate_photo, cand.candidate_sign, cand.id_proof_file, cand.qualification_certificate_file, cand.dob, cand.mobile_no, cand.email_id, (CONCAT(cand.address1, IF(cand.address2 != "", CONCAT(" ", cand.address2), ""), IF(cand.address3 != "", CONCAT(" ", cand.address3), ""), IF(cand.address4 != "", CONCAT(" ", cand.address4), ""))) AS DispAddress, (IF(cand.gender = 1, "Male", IF(cand.gender = 2, "Female", ""))) AS DispGender, btch.batch_code', array('cand.candidate_id' => 'ASC')); 
        
        $data['id_proof_file_path'] = $this->id_proof_file_path;
        $data['qualification_certificate_file_path'] = $this->qualification_certificate_file_path;
        $data['candidate_photo_path'] = $this->candidate_photo_path;
        $data['candidate_sign_path'] = $this->candidate_sign_path;
        $html = $this->load->view('iibfbcbf/agency/inc_batch_applicant_checklist_pdf',$data,true);
        
        $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.batch_id' => $batch_id), 'btch.batch_code');
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdfFilePath = 'batch_applicant_list_pdf_'.$batch_data[0]['batch_code'].'_'.date('YmdHis').'.pdf';
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
			}
      redirect(site_url('iibfbcbf/agency/batch_applicant_checklist'));
    }
 } ?>  