<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Admitcard Master
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Admitcard extends CI_Controller 
  {
    public function __construct()
    {
      exit;
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
        $this->session->set_flashdata('error','You do not have permission to access Agency module');
        redirect(site_url('iibfbcbf/admin/dashboard_admin'));
      }
		}
    
    public function index()
    {   
      $data['act_id'] = "Download Admitcard";
      $data['sub_act_id'] = "Download Admitcard";
      $data['page_title'] = 'IIBF - BCBF Download Admitcard';

      $data['agency_centre_data'] = array();

      $this->db->join('iibfbcbf_agency_master am', 'am.agency_code = bm.inscd', 'LEFT');
      $this->db->join('iibfbcbf_exam_centre_master cm', 'cm.centre_code = bm.center_code', 'LEFT');
      $this->db->group_by('bm.center_code');
      $this->db->order_by('cm.centre_name'); 
      
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_admit_card_info bm', array(), 'bm.center_code, cm.centre_name');

      $this->load->view('iibfbcbf/admin/admitcard_admin', $data);
    }

    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE ADMITCARD DATA ********/
    public function get_admitcard_data_ajax()
    {
      $table = 'iibfbcbf_admit_card_info bc'; 
      
      $center_code_enc = trim($this->security->xss_clean($this->input->post('center_code_enc')));
      $exam_code_enc = trim($this->security->xss_clean($this->input->post('exam_code_enc')));
      $exam_period_enc = trim($this->security->xss_clean($this->input->post('exam_period_enc')));
      
      if($center_code_enc == "" && $exam_code_enc == "" && $exam_period_enc == ""){ 
        $column_order = array('bc.admitcard_id', 'cm.centre_name', 'bc.exm_cd', 'ea.exam_period', 'count(DISTINCT bc.mem_mem_no) AS no_of_candidates', 'bc.center_code', 'bc.date'); //SET COLUMNS FOR SORT        
        $column_search = array('cm.centre_name', 'bc.exm_cd', 'ea.exam_period'); //, 'count(bc.mem_mem_no)' //SET COLUMN FOR SEARCH
      }else{
        $column_order = array('bc.admitcard_id', 'cm.centre_name', 'bc.exm_cd', 'ea.exam_period', 'bc.mem_mem_no AS no_of_candidates', 'bc.center_code', 'bc.date'); //SET COLUMNS FOR SORT
        $column_search = array('cm.centre_name', 'bc.exm_cd', 'ea.exam_period', 'bc.mem_mem_no'); //SET COLUMN FOR SEARCH
      }
        
      $order = array('bc.admitcard_id' => 'DESC'); // DEFAULT ORDER 
          
      $WhereForTotal = "WHERE 1 "; // AND bc.exm_cd IN('1037','1038') //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE 1 "; //  AND bc.exm_cd IN('1037','1038') 
 
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }

      if($center_code_enc != "") { 
        $center_code_enc = url_decode($center_code_enc);  
        $Where .= " AND bc.center_code = '".$center_code_enc."'"; 
        $WhereForTotal .= " AND bc.center_code = '".$center_code_enc."'"; 
      }
      if($exam_code_enc != "") { 
        $exam_code_enc = url_decode($exam_code_enc);  
        $Where .= " AND bc.exm_cd = '".$exam_code_enc."'"; 
        $WhereForTotal .= " AND bc.exm_cd = '".$exam_code_enc."'"; 
      }
      if($exam_period_enc != "") { 
        $exam_period_enc = url_decode($exam_period_enc);  
        $Where .= " AND ea.exam_period = '".$exam_period_enc."'"; 
        $WhereForTotal .= " AND ea.exam_period = '".$exam_period_enc."'"; 
      } 
 
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND bc.center_code = '".$s_centre."'"; } 
      $s_term = trim($this->security->xss_clean($this->input->post('s_term')));
      if($s_term != "") { $Where .= " AND (cm.centre_name LIKE '%".$s_term."%' OR bc.mem_mem_no LIKE '%".$s_term."%' OR bc.exm_cd LIKE '%".$s_term."%' OR ea.exam_period LIKE '%".$s_term."%')"; } 
      /* 
      $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") { $Where .= " AND bc.status = '".$s_payment_status."'"; } */
      
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = " LEFT JOIN iibfbcbf_exam_activation_master ea ON ea.exam_code = bc.exm_cd"; 
      $join_qry .= " LEFT JOIN iibfbcbf_exam_centre_master cm ON cm.centre_code = bc.center_code"; 

      if($center_code_enc != "" && $exam_code_enc != "" && $exam_period_enc != ""){
        $GroupBy .= ",bc.mem_mem_no"; 
        $Where .= $GroupBy;
        $WhereForTotal .= $GroupBy;   
      }
      
      if($center_code_enc == "" && $exam_code_enc == "" && $exam_period_enc == ""){
        $Where .= $GroupBy;
        $WhereForTotal .= $GroupBy;   
      }
      
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
        
        $center_name = '';
        $btn_str = ' <div class="text-centerx no_wrap" style="width: 60px; margin: 0 auto;"> ';

        if($center_code_enc == "" && $exam_code_enc == "" && $exam_period_enc == "")
        {
          $center_name .= '<a href="'.site_url('iibfbcbf/admin/admitcard/centerwise_admitcard_pdf/'.url_encode($Res['center_code']).'/'.url_encode($Res['exm_cd']).'/'.url_encode($Res['exam_period'])).'" title="Centerwise Admitcard">'.$Res['centre_name'].'</a>';

          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/admitcard/download_admit_card_pdf/'.url_encode($Res['center_code']).'/'.url_encode($Res['exm_cd']).'/'.url_encode($Res['exam_period'])).'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';

        }else{
          $center_name .= $Res['centre_name'];

          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/admitcard/download_admit_card_pdf/'.url_encode($Res['center_code']).'/'.url_encode($Res['exm_cd']).'/'.url_encode($Res['exam_period']).'/'.url_encode($Res['admitcard_id'])).'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';

        }
        $row[] = $center_name;
        $row[] = $Res['exm_cd'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['no_of_candidates'];     
 
        
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
    
    public function centerwise_admitcard_pdf($center_code_enc,$exam_code_enc,$exam_period_enc){
      $data['act_id'] = "Download Admitcard";
      $data['sub_act_id'] = "Centerwise Admitcard";
      $data['page_title'] = 'Centerwise Download Admitcard';

      $data['center_code_enc'] = $center_code_enc;
      $data['exam_code_enc'] = $exam_code_enc;
      $data['exam_period_enc'] = $exam_period_enc;
      
      $data['agency_centre_data'] = array();

      $this->db->join('iibfbcbf_agency_master am', 'am.agency_code = bm.inscd', 'LEFT');
      $this->db->join('iibfbcbf_exam_centre_master cm', 'cm.centre_code = bm.center_code', 'LEFT');
      $this->db->group_by('bm.center_code');
      $this->db->order_by('cm.centre_name'); 

      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_admit_card_info bm', array(), 'bm.center_code, cm.centre_name');

      $inst_code = $this->login_agency_or_centre_id;  

      $this->load->view('iibfbcbf/admin/admitcard_admin', $data);
    }

    public function download_admit_card_pdf($center_code_enc='',$exam_code_enc='',$exam_period_enc='',$enc_admitcard_id= '0')
    {
      $admitcard_id = $inst_code = 0;
      if($enc_admitcard_id != '0') { $admitcard_id = url_decode($enc_admitcard_id); }

      $center_code = url_decode($center_code_enc); 
      $exam_code = url_decode($exam_code_enc); 
      $exam_period = url_decode($exam_period_enc); 
      
      $data = array();  
      $admit_card_data = array(); 

      if($this->login_user_type == 'agency')
      { 
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$this->login_agency_or_centre_id), 'agency_code');
        $inst_code = $agency_data[0]["agency_code"]; 
        $this->db->where(array('ac.inscd'=>$inst_code));/* ,'admitcard_image'=>'' */
      }
      else if($this->login_user_type == 'centre')
      {
        $center_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id'=>$this->login_agency_or_centre_id), 'centre_username,agency_id');  
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$center_data[0]["agency_id"]), 'agency_code'); 
        $inst_code = $agency_data[0]["agency_code"]; 
      } 

      if($center_code_enc != "" && $exam_code_enc != "" && $exam_period_enc != ""){
        $this->db->where(array('ac.center_code' => $center_code,'ac.exm_cd' => $exam_code,'ea.exam_period' => $exam_period));
      }
      if($admitcard_id != 0){
        $this->db->where(array('ac.admitcard_id' => $admitcard_id));
      }
      $this->db->join('iibfbcbf_exam_activation_master ea', 'ea.exam_code = ac.exm_cd', 'INNER');
      $this->db->join('iibfbcbf_batch_candidates bc', 'bc.regnumber = ac.mem_mem_no', 'LEFT');
      $this->db->join('iibfbcbf_exam_subject_master sm', 'sm.subject_code = LEFT(ac.sub_cd,3) AND sm.exam_code = ac.exm_cd');
      $data['admit_card_data'] = $admit_card_data = $this->master_model->getRecords('iibfbcbf_admit_card_info ac', array(), 'ac.admitcard_id, ac.inscd, ac.center_code, ac.mem_mem_no, ac.mam_nam_1, ac.mem_adr_1, ac.mem_adr_2, ac.mem_adr_3, ac.mem_adr_4, ac.mem_adr_5, ac.mem_adr_6, ac.mem_pin_cd, ac.exm_cd, ac.mode, ac.pwd, ac.m_1, ac.vendor_code, ac.venueid, ac.venueadd1, ac.venueadd2, ac.venueadd3, ac.venueadd4, ac.venueadd5, ac.venpin, ac.insname, ac.seat_identification, ac.time, ac.date as exam_date, bc.dob, bc.candidate_id, ea.exam_period, sm.subject_description as sub_dsc', array("admitcard_id"=>"DESC"),'0','100'); 
      //echo $this->db->last_query();die;
      //_pa($admit_card_data,1);die;

      $payment_data = array();
      if(count($admit_card_data) > 0)
      {
          $file_dir_name = $center_code.'_'.$exam_code.'_'.$inst_code;
          $cnt = 0;
          foreach($admit_card_data as $k=>$member_result)
          {                 
            $directory_name = './uploads/iibfbcbf/iibf_bcbf_admitcard/'.date('Ymd').'/'.$file_dir_name;  
            create_directories($directory_name);
              
              if($admitcard_id == "0")
              {
                /*START : BULK ADMIT CARD*/
                $this->db->where(array('ac.admitcard_id' => $member_result['admitcard_id']));
                $this->db->join('iibfbcbf_exam_activation_master ea', 'ea.exam_code = ac.exm_cd', 'INNER');
                $this->db->join('iibfbcbf_batch_candidates bc', 'bc.regnumber = ac.mem_mem_no', 'LEFT');
                $this->db->join('iibfbcbf_exam_subject_master sm', 'sm.subject_code = LEFT(ac.sub_cd,3) AND sm.exam_code = ac.exm_cd');
                $data['admit_card_data'] = $admit_card_data = $this->master_model->getRecords('iibfbcbf_admit_card_info ac', array(), 'ac.admitcard_id, ac.inscd, ac.center_code, ac.mem_mem_no, ac.mam_nam_1, ac.mem_adr_1, ac.mem_adr_2, ac.mem_adr_3, ac.mem_adr_4, ac.mem_adr_5, ac.mem_adr_6, ac.mem_pin_cd, ac.exm_cd, ac.mode, ac.pwd, ac.m_1, ac.vendor_code, ac.venueid, ac.venueadd1, ac.venueadd2, ac.venueadd3, ac.venueadd4, ac.venueadd5, ac.venpin, ac.insname, ac.seat_identification, ac.time, ac.date as exam_date, bc.dob, bc.candidate_id, ea.exam_period, sm.subject_description as sub_dsc', array("admitcard_id"=>"DESC"));
                  if($data['admit_card_data'][0]['candidate_id'] && $data['admit_card_data'][0]['candidate_id'] > 0)
                  {
                    $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam', array('candidate_id' => $data['admit_card_data'][0]['candidate_id'],"exam_code"=>$data['admit_card_data'][0]['exm_cd'],"exam_period"=>$data['admit_card_data'][0]['exam_period'],"pay_status"=>'1'), 'member_exam_id,payment_mode');
                    if($member_exam_data){
                        $payment_mode = $member_exam_data[0]['payment_mode'];
                        $member_exam_id = $member_exam_data[0]['member_exam_id'];
                        if($member_exam_id && $payment_mode && $payment_mode == 'Bulk'){
                          $this->db->where('find_in_set("'.$member_exam_id.'", exam_ids) <> 0');
                          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array("exam_code"=>$data['admit_card_data'][0]['exm_cd'],"exam_period"=>$data['admit_card_data'][0]['exam_period'],"status"=>'1'), 'transaction_no,UTR_no,created_on,payment_mode');
                        }else if($member_exam_id && $payment_mode && $payment_mode != 'Bulk'){
                          $this->db->where('exam_ids',$member_exam_id);
                          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array("exam_code"=>$data['admit_card_data'][0]['exm_cd'],"exam_period"=>$data['admit_card_data'][0]['exam_period'],"status"=>'1'), 'transaction_no,UTR_no,created_on,payment_mode');
                        }
                        if($payment_data){
                          $data['admit_card_data'][0]['transaction_no'] = $payment_data[0]['transaction_no']; 
                          $data['admit_card_data'][0]['UTR_no'] = $payment_data[0]['UTR_no']; 
                          $data['admit_card_data'][0]['created_on'] = $payment_data[0]['created_on']; 
                          $data['admit_card_data'][0]['payment_mode'] = $payment_data[0]['payment_mode'];
                        }
                        else{
                          $data['admit_card_data'][0]['transaction_no'] = ''; 
                          $data['admit_card_data'][0]['UTR_no'] = ''; 
                          $data['admit_card_data'][0]['created_on'] = ''; 
                          $data['admit_card_data'][0]['payment_mode'] = '';
                        } 
                    } 
                  }
                  
                  $data['exam_result'] = $this->master_model->getRecords('iibfbcbf_exam_master', array('exam_code'=>$data['admit_card_data'][0]['exm_cd']), 'description, exam_type, exam_code','','0','1');

                   if($cnt > 7) { redirect(site_url('iibfbcbf/admin/Admitcard/download_admit_card_pdf/'.$center_code_enc."/".$exam_code_enc."/".$exam_period_enc)); } 

                    $pdfFilePath = $data['admit_card_data'][0]['exm_cd']."_".$data['admit_card_data'][0]['exam_period']."_".$data['admit_card_data'][0]['mem_mem_no'].".pdf";
                    $file_arr[] = $pdfFilePath;
                    
                    //$cnt++;
                    if (!file_exists($directory_name.'/'.$pdfFilePath)) 
                    {
                      $attchpath_admitcard_bulk = $this->load->view('iibfbcbf/agency/admitcardpdf_attach', $data, true);
                      $this->load->library('m_pdf');
                      $pdf = $this->m_pdf->load();            
                      $pdf->WriteHTML($attchpath_admitcard_bulk);
                      $pdf->Output($directory_name.'/'.$pdfFilePath,"F");
                      $cnt++;
                    }
                    /*END : BULK ADMIT CARD*/
              }
              else
              {  
                  /*START : INDIVIDUAL ADMIT CARD*/ 
                  if($member_result['candidate_id'] && $member_result['candidate_id'] > 0){
                    $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam', array('candidate_id' => $member_result['candidate_id'],"exam_code"=>$member_result['exm_cd'],"exam_period"=>$member_result['exam_period'],"pay_status"=>'1'), 'member_exam_id,payment_mode');
                    if($member_exam_data){
                        $payment_mode = $member_exam_data[0]['payment_mode'];
                        $member_exam_id = $member_exam_data[0]['member_exam_id'];
                        if($member_exam_id && $payment_mode && $payment_mode == 'Bulk'){
                          $this->db->where('find_in_set("'.$member_exam_id.'", exam_ids) <> 0');
                          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array("exam_code"=>$member_result['exm_cd'],"exam_period"=>$member_result['exam_period'],"status"=>'1'), 'transaction_no,UTR_no,created_on,payment_mode');
                        }else if($member_exam_id && $payment_mode && $payment_mode != 'Bulk'){
                          $this->db->where('exam_ids',$member_exam_id);
                          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array("exam_code"=>$member_result['exm_cd'],"exam_period"=>$member_result['exam_period'],"status"=>'1'), 'transaction_no,UTR_no,created_on,payment_mode');
                        }
                        if($payment_data){
                          $data['admit_card_data'][0]['transaction_no'] = $payment_data[0]['transaction_no']; 
                          $data['admit_card_data'][0]['UTR_no'] = $payment_data[0]['UTR_no']; 
                          $data['admit_card_data'][0]['created_on'] = $payment_data[0]['created_on']; 
                          $data['admit_card_data'][0]['payment_mode'] = $payment_data[0]['payment_mode'];
                        }
                        else{
                          $data['admit_card_data'][0]['transaction_no'] = ''; 
                          $data['admit_card_data'][0]['UTR_no'] = ''; 
                          $data['admit_card_data'][0]['created_on'] = ''; 
                          $data['admit_card_data'][0]['payment_mode'] = '';
                        } 
                    }  
                  } 
                  $data['exam_result'] = $this->master_model->getRecords('iibfbcbf_exam_master', array('exam_code'=>$member_result['exm_cd']), 'description, exam_type, exam_code','','0','1');
                   
                  if($admitcard_id != "0")
                  {
                    $pdfFilePath_Individual = $data['admit_card_data'][0]['exm_cd']."_".$data['admit_card_data'][0]['exam_period']."_".$data['admit_card_data'][0]['mem_mem_no'].".pdf";
                    $attchpath_admitcard_individual = $this->load->view('iibfbcbf/agency/admitcardpdf_attach', $data, true);
                    //echo $attchpath_admitcard;die;
                    $this->load->library('m_pdf');
                    $pdf = $this->m_pdf->load(); 
                    $pdf->WriteHTML($attchpath_admitcard_individual);
                    $path = $pdf->Output($pdfFilePath_Individual, "D");  
                    die;
                  }
                  /*END : INDIVIDUAL ADMIT CARD*/
              }
           
          //echo "<br>".$this->db->last_query();die;
            
        }
        //_pa($data['admit_card_data'],1);die;
        
          $zip_name = 'admitcard_files_'.date("YmdHis").rand().".zip";

          //file directory creation 
          $zip_folder_path = "uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name."/zip";
          $directory_name = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name."/zip";
          create_directories($directory_name);
           
          if (file_exists($zip_folder_path."/".$zip_name)) {
            @unlink($zip_folder_path."/".$zip_name); 
          } 

          $zip = new ZipArchive;

          if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) { 
            if (count($file_arr) > 0) {
              foreach ($file_arr as $file) {
                if($file != ""){
                  $path = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name."/".$file;
                  if (file_exists($path)) {
                    $filename_parts = explode('/', $path);  // Split the filename up by the '/' character
                    $zip->addFile($path, end($filename_parts));  
                  }
                } 
              }
            }
            $zip->close();  
          }  

          //echo "==".count($file_arr);die;
          
          //START : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
          /*$all_directories = $this->get_directory_list("uploads/iibfbcbf/iibf_bcbf_admitcard/"); 
          if(count($all_directories) > 0)
          {
            foreach($all_directories as $dir)
            {
              $explode_arr = explode("_", $dir, 2);
              //echo $explode_arr[0]."==".$dir;die;
              $chk_dir = str_replace("/","",$explode_arr[0]);
              //echo $chk_dir;die;
              if($chk_dir != date('Ymd'))
              {
                //echo "<br> Delete : ".$dir;
                $this->rmdir_recursive("uploads/iibfbcbf/iibf_bcbf_admitcard/".$chk_dir);
              }
            }
          }*/       
          //END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
          if (count($file_arr) > 0){
            redirect(base_url('uploads/iibfbcbf/iibf_bcbf_admitcard/'.date('Ymd').'/'.$file_dir_name.'/zip/'.$zip_name));
          } 
          


      } 
    }

    public function download_centerwise_pdf($center_code_enc,$exam_code_enc,$exam_period_enc,$mem_mem_no_enc = '0')
    {
      
      $inst_code = 0;
      //$inst_code = $this->session->userdata['dra_institute']['institute_code']; 

      $center_code = url_decode($center_code_enc); 
      $exam_code = url_decode($exam_code_enc); 
      $exam_period = url_decode($exam_period_enc); 

      if($this->login_user_type == 'agency')
      { 
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$this->login_agency_or_centre_id), 'agency_code');
        $inst_code = $agency_data[0]["agency_code"]; 
        $this->db->where(array('iibfbcbf_admit_card_info.inscd'=>$inst_code));/* ,'admitcard_image'=>'' */
      }
      else if($this->login_user_type == 'centre')
      {
        $center_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id'=>$this->login_agency_or_centre_id), 'centre_username,agency_id');
        //$center_code = $center_data[0]["centre_username"];  

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$center_data[0]["agency_id"]), 'agency_code');  
        //$this->db->where(array());/* ,'admitcard_image'=>'' */
        $inst_code = $agency_data[0]["agency_code"]; 
      }
      
      if($mem_mem_no_enc != "0"){
        $mem_mem_no = url_decode($mem_mem_no_enc); 
        $this->db->where(array('mem_mem_no'=>$mem_mem_no));
      } 

      $this->db->order_by("iibfbcbf_admit_card_info.admitcard_id", "desc"); 
      $this->db->join('iibfbcbf_exam_activation_master', 'iibfbcbf_exam_activation_master.exam_code = iibfbcbf_admit_card_info.exm_cd', 'LEFT');


      $member_record = $this->master_model->getRecords('iibfbcbf_admit_card_info', array('center_code' => $center_code,'exm_cd'=>$exam_code,'iibfbcbf_exam_activation_master.exam_period'=>$exam_period), 'mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5,mem_adr_6, mem_pin_cd, mode, pwd, center_code, m_1, vendor_code, iibfbcbf_exam_activation_master.exam_period');  

 
      //echo $last_qry = $this->db->last_query(); die;

      //echo "<br><<pre>".print_r($member_record);die;
       
      if(sizeof($member_record) == 0)
      { 
        //$this->Iibf_bcbf_model->insert_common_log('IIBF BCBF : Agency Generate admit card error', 'iibfbcbf_admit_card_info', $this->db->last_query(), $this->login_agency_or_centre_id,'agency_admit_card_action','The agency Generate admit card error ', json_encode(array(''))); 
        return '';
        exit;
      }
      else
      {
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
         
        $file_dir_name = $center_code.'_'.$exam_code.'_'.$inst_code;
        $directory_name = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd');
        //mkdir($directory_name); 
        if (!file_exists($directory_name))
        {
          mkdir($directory_name);
        }

        $directory_name = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name;
        //mkdir($directory_name); 
        if (!file_exists($directory_name))
        {
          mkdir($directory_name);
        }

        $cnt=0; 
        
        //foreach($member_record->result() as $member_result)
        foreach($member_record as $member_result)
        {         
          $member_id=$member_result['mem_mem_no'];

          $pdfFilePath = $exam_code."_".$exam_period."_".$center_code."_".$member_id.".pdf";

          if(isset($member_result['vendor_code']) || $member_result['vendor_code']!='' )
          {
            $vcenter = $member_result['vendor_code'];
          }
          elseif(!isset($member_result['vendor_code']) || $member_result['vendor_code']=='' )
          {
            $vcenter = '0';
          }
          
          $medium_code = $member_result['m_1'];

          $this->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,seat_identification');
          $this->db->from('iibfbcbf_admit_card_info');
          $this->db->where(array('iibfbcbf_admit_card_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'center_code'=>$center_code));
          $this->db->group_by('venueid');
          $this->db->order_by("date", "asc");
          $venue_record = $this->db->get();
          $venue_result = $venue_record->result();
          
          $this->db->select('description,exam_type,exam_code');
          $exam = $this->db->get_where('iibfbcbf_exam_master', array('exam_code' => $exam_code));
          $exam_result = $exam->row();
          
          $this->db->select('iibfbcbf_exam_subject_master.exam_date as ex_date,iibfbcbf_exam_subject_master.subject_description,iibfbcbf_admit_card_info.date,time,venueid,seat_identification');
          $this->db->from('iibfbcbf_admit_card_info');
          $this->db->join('iibfbcbf_exam_subject_master', 'iibfbcbf_exam_subject_master.subject_code = LEFT(iibfbcbf_admit_card_info.sub_cd,3)');
          $this->db->where(array('iibfbcbf_exam_subject_master.exam_code' => $exam_code,'iibfbcbf_admit_card_info.mem_mem_no'=>$member_id,'subject_delete'=>0,'iibfbcbf_exam_subject_master.exam_period'=>$exam_period));
          //$this->db->where('pwd!=','');
          //$this->db->where('seat_identification!=','');
          //$this->db->where('remark',1);
          $this->db->order_by("iibfbcbf_admit_card_info.date", "asc");
          $subject = $this->db->get();
          $subject_result = $subject->result(); 
          $lstQry2 = $this->db->last_query();
           
          //$this->Iibf_bcbf_model->insert_common_log('IIBF BCBF : Agency Generate admit card in 2', 'iibfbcbf_admitcard_info', $this->db->last_query(), $this->login_agency_or_centre_id,'agency_admit_card_action','The agency Generate admit card in 2 ', json_encode($subject_result));
          
          $pdate = $subject->result();
          foreach($pdate as $pdate)
          {
            $exdate = date("d-M-y", strtotime($subject_result[0]->date)); ;
            $examdate = explode("-",$exdate);
            $examdatearr[] = $examdate[1];
          }
          
          $exdate = date("d-M-y", strtotime($subject_result[0]->date)); 
          $examdate = explode("-",$exdate);
          $printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
          
          if($medium_code == 'ENGLISH' || $medium_code == 'E')
          {
            $medium_code_lng = 'E';
          }
          elseif($medium_code == 'HINDI' || $medium_code == 'H')
          {
            $medium_code_lng = 'H';
            }elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
            $medium_code_lng = 'A';
            }elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
            $medium_code_lng = 'G';
            }elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
            $medium_code_lng = 'K';
            }elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
            $medium_code_lng = 'L';
            }elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
            $medium_code_lng = 'M';
            }elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
            $medium_code_lng = 'N';
            }elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
            $medium_code_lng = 'O';
            }elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
            $medium_code_lng = 'S';
            }elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
            $medium_code_lng = 'T';
          }
          
          $this->db->select('medium_description');
          $medium = $this->db->get_where('iibfbcbf_exam_medium_master', array('medium_code' => $medium_code_lng));
          $medium_result = $medium->row();

          //Payment Transaction Details
          $payment_data = array();
          $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber' => $member_result['mem_mem_no']), 'candidate_id,dob', array('candidate_id'=>'DESC'));
          if($candidate_data){
            $candidate_id = $candidate_data[0]['candidate_id'];
            if($candidate_id && $candidate_id > 0){
              $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam', array('candidate_id' => $candidate_id,"exam_code"=>$exam_code,"exam_period"=>$exam_period,"pay_status"=>'1'), 'member_exam_id,payment_mode');
              if($member_exam_data){
                  $payment_mode = $member_exam_data[0]['payment_mode'];
                  $member_exam_id = $member_exam_data[0]['member_exam_id'];
                  if($member_exam_id && $payment_mode && $payment_mode == 'Bulk'){
                    $this->db->where('find_in_set("'.$member_exam_id.'", exam_ids) <> 0');
                    $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array("exam_code"=>$exam_code,"exam_period"=>$exam_period,"status"=>'1'), 'transaction_no,UTR_no,created_on');
                  }else if($member_exam_id && $payment_mode && $payment_mode != 'Bulk'){
                    $this->db->where('exam_ids',$member_exam_id);
                    $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array("exam_code"=>$exam_code,"exam_period"=>$exam_period,"status"=>'1'), 'transaction_no,UTR_no,created_on');
                  }
              }
            }
          } 

          $this->db->select('transaction_no,date');
          $this->db->order_by('id', 'desc');
          $payment = $this->db->get_where('payment_transaction', array('member_regnumber' => $result->mem_mem_no,'exam_code'=> '101','status'=> '1'));
          $payment_result = $payment->row();
          
          $data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period, 'candidate_data'=>$candidate_data,'payment_data'=>$payment_data,'exam_result'=>$exam_result);
          
          //$this->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 3", "description"=>serialize($data)));
          //$this->Iibf_bcbf_model->insert_common_log('IIBF BCBF : Agency Generate admit card in 3', 'iibfbcbf_admitcard_info', $this->db->last_query(), $this->login_agency_or_centre_id,'agency_admit_card_action','The agency Generate admit card in 3 ', json_encode($data));
          
          /*echo '<pre>';
            print_r($data);
          exit;*/ 

          //$this->Iibf_bcbf_model->insert_common_log('IIBF BCBF : Agency Generate admit card End', 'iibfbcbf_admitcard_info', $this->db->last_query(), $this->login_agency_or_centre_id,'agency_admit_card_action','The agency Generate admit card End ', json_encode($data));
          
          
          
          if($cnt > 7) { redirect(site_url('iibfbcbf/admin/admitcard/download_centerwise_pdf/'.$center_code_enc."/".$exam_code_enc."/".$exam_period_enc)); }
          
          
          $pdfFilePath = 'uploads/iibfbcbf/iibf_bcbf_admitcard/'.date('Ymd').'/'.$file_dir_name.'/'; 
          //$pdfFilename = $exam_code."_".$exam_period."_".$member_id.".pdf";
          $pdfFilename = $exam_code."_".$exam_period."_".$member_id.".pdf";

          $file_arr[] = $pdfFilename;

          if (!file_exists($pdfFilePath.$pdfFilename)) 
          {
            $html = $this->load->view('iibfbcbf/agency/admitcardpdf_attach', $data, true);
            $this->load->library('m_pdf');
            $pdf = $this->m_pdf->load();            
            $pdf->WriteHTML($html);

            if($mem_mem_no_enc != "0")
            {
              //$pdf->Output($pdfFilePath, "D"); 
            }else{
                $pdf->Output($pdfFilePath.$pdfFilename,"F");
            } 
            //$pdf->Output($pdfFilePath, "D");
            $cnt++;
          }

          if($mem_mem_no_enc != "0")
          {
            $attchpath_admitcard = $this->load->view('iibfbcbf/agency/admitcardpdf_attach', $data, true);
            //echo $attchpath_admitcard;die;
            $this->load->library('m_pdf');
            $pdf = $this->m_pdf->load();
            $pdfFilePath = $exam_code."_".$exam_period."_".$center_code."_".$member_id.".pdf";
            $pdf->WriteHTML($attchpath_admitcard);
            $path = $pdf->Output($pdfFilePath, "D");  
            die;
          } 
           
        }
         
        $zip_name = 'admitcard_files_'.date("YmdHis").rand().".zip";

          //file directory creation 
          $zip_folder_path = "uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name."/zip";
          $directory_name = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name."/zip";
          //mkdir($directory_name); 
          if (!file_exists($directory_name))
          {
            mkdir($directory_name);
          }

          if (file_exists($zip_folder_path."/".$zip_name)) {
            @unlink($zip_folder_path."/".$zip_name); 
          }
          

          $zip = new ZipArchive;

          if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) { 
            if (count($file_arr) > 0) {
              foreach ($file_arr as $file) {
                if($file != ""){
                  $path = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd')."/".$file_dir_name."/".$file;
                  if (file_exists($path)) {
                    $filename_parts = explode('/', $path);  // Split the filename up by the '/' character
                    $zip->addFile($path, end($filename_parts));  
                  }
                } 
              }
            }
            $zip->close(); 
              
          } 
          
          //START : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
              $all_directories = $this->get_directory_list("./uploads/iibfbcbf/iibf_bcbf_admitcard/");
              //print_r($all_directories);die;
            //echo count($all_directories);
            if(count($all_directories) > 0)
            {
              foreach($all_directories as $dir)
              {
                $explode_arr = explode("_", $dir, 2);
                //echo $explode_arr[0]."==".$dir;die;
                $chk_dir = str_replace("/","",$explode_arr[0]);
                //echo $chk_dir;die;
                if($chk_dir != date('Ymd'))
                {
                  //echo "<br> Delete : ".$dir;
                  $this->rmdir_recursive("uploads/iibfbcbf/iibf_bcbf_admitcard/".$chk_dir);
                }
              }
            }       
            //END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
            // echo "==".count($file_arr);die;
            if (count($file_arr) > 0){
              redirect(base_url('uploads/iibfbcbf/iibf_bcbf_admitcard/'.date('Ymd').'/'.$file_dir_name.'/zip/'.$zip_name));
            } 

      }
      
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
  
  
    function delete_directory($dirname) {
           if (is_dir($dirname))
             $dir_handle = opendir($dirname);
       if (!$dir_handle)
            return false;
       while($file = readdir($dir_handle)) {
             if ($file != "." && $file != "..") {
                  if (!is_dir($dirname."/".$file))
                       unlink($dirname."/".$file);
                  else
                       delete_directory($dirname.'/'.$file);
             }
       }
       closedir($dir_handle);
       rmdir($dirname);
       return true;
    }
 
   
 } 
?>  