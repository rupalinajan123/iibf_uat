<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Result
  ** Created BY: Sagar Matale On 11-12-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Result_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file');
      $this->load->helper('directory');
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
 
    }
    
    public function index($exam_code_enc='',$center_code_enc='')
    {   
      $data['act_id'] = "Download Result";
      $data['sub_act_id'] = "IIBF BCBF Result";
      $data['page_title'] = 'IIBF - BCBF Download Result';

      $data['exam_code_enc'] = $exam_code_enc;
      $data['center_code_enc'] = $center_code_enc;

      $data['agency_centre_data'] = array();
      if($this->login_user_type == 'agency') 
      { 
        $agency_id = $this->login_agency_or_centre_id;  
        $this->db->join('iibfbcbf_memdetails bc', 'bc.regnumber = bm.regnumber', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_code = bc.inst_code', 'LEFT');
        $this->db->group_by('bm.center_code');
        $this->db->order_by('bm.center_name');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_marks bm', array('am.agency_id'=>$agency_id), 'bm.center_code, bm.center_name');
      }
      $inst_code = $this->session->userdata['dra_institute']['institute_code'];  

      $this->load->view('iibfbcbf/agency/result_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_result_data_ajax()
    {
      $table = 'iibfbcbf_memdetails bc';
      $GroupBy = "Group By bm.center_code";

      $exam_code_enc = trim($this->security->xss_clean($this->input->post('exam_code_enc')));
      $center_code_enc = trim($this->security->xss_clean($this->input->post('center_code_enc')));
      
      if($exam_code_enc == "" && $center_code_enc == "")
      {
        $column_order = array('bc.id', 'bm.center_name', 'em.description', 'count(bm.regnumber) AS pay_count', 'bm.exam_period', 'bm.center_code', 'bm.exam_code', 'bm.regnumber'); //SET COLUMNS FOR SORT
        $column_search = array('bm.center_name', 'em.description', 'bm.exam_period'); //SET COLUMN FOR SEARCH
      }else{
        $column_order = array('bc.id', 'bm.center_name', 'em.description', 'bm.regnumber AS pay_count', 'bm.exam_period', 'bm.center_code', 'bm.exam_code', 'bm.regnumber'); //SET COLUMNS FOR SORT
        $column_search = array('bm.center_name', 'em.description', 'bm.regnumber', 'bm.exam_period'); //SET COLUMN FOR SEARCH
      }
      
      
      
      $order = array('bc.id' => 'DESC'); // DEFAULT ORDER 

      /*if($this->login_user_type == 'centre')
      {
        $WhereForTotal = "WHERE bc.centre_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.centre_id = '".$this->login_agency_or_centre_id."' ";
      }*/
      if($this->login_user_type == 'agency')
      {
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$this->login_agency_or_centre_id), 'agency_code');

        $WhereForTotal = "WHERE bc.inst_code = '".$agency_data[0]["agency_code"]."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.inst_code = '".$agency_data[0]["agency_code"]."' "; // AND bm.exam_code = '1038'
      }
      else if($this->login_user_type == 'centre')
      {
          
        $center_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id'=>$this->login_agency_or_centre_id), 'centre_username,agency_id');

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$center_data[0]["agency_id"]), 'agency_code');

        $WhereForTotal = "WHERE bc.inst_code = '".$agency_data[0]["agency_code"]."' AND bm.center_code = '".$center_data[0]["centre_username"]."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.inst_code = '".$agency_data[0]["agency_code"]."' AND bm.center_code = '".$center_data[0]["centre_username"]."' "; // AND bm.exam_code = '1038' 
      } 
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND bm.center_code = '".$s_centre."'"; } 
      $s_term = trim($this->security->xss_clean($this->input->post('s_term')));
      if($s_term != "") { $Where .= " AND (bm.center_name LIKE '%".$s_term."%' OR bm.regnumber LIKE '%".$s_term."%' OR em.description LIKE '%".$s_term."%' OR bm.exam_code LIKE '%".$s_term."%' OR bm.exam_period LIKE '%".$s_term."%')"; } 
      /* 
      $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") { $Where .= " AND bc.status = '".$s_payment_status."'"; } */
      
       
      

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN iibfbcbf_marks bm ON bm.regnumber = bc.regnumber"; 
      //$join_qry .= " LEFT JOIN iibfbcbf_agency_master am ON am.agency_code = bc.inst_code"; 
      $join_qry .= " LEFT JOIN iibfbcbf_exam_master em ON em.exam_code = bm.exam_code"; 
      
       

      if($center_code_enc != "") { 
        $center_code_enc = url_decode($center_code_enc);  
        $Where .= " AND bm.center_code = '".$center_code_enc."'"; 
        $WhereForTotal .= " AND bm.center_code = '".$center_code_enc."'"; 
      } 
      if($exam_code_enc != "") { 
        $exam_code_enc = url_decode($exam_code_enc);  
        $Where .= " AND bm.exam_code = '".$exam_code_enc."'"; 
        $WhereForTotal .= " AND bm.exam_code = '".$exam_code_enc."'"; 
      } 

      if($exam_code_enc == "" && $center_code_enc == ""){
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

        if($center_code_enc == "" && $exam_code_enc == "")
        {
          $center_name .= '<a href="'.site_url('iibfbcbf/agency/result_agency/centerwise_result_pdf/'.url_encode($Res['exam_code']).'/'.url_encode($Res['center_code'])).'" title="Centerwise Admitcard">'.$Res['center_name'].'</a>';

          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/result_agency/download_pdf/'.url_encode($Res['exam_code']).'/'.url_encode($Res['center_code'])).'" class="btn btn-success btn-xs" title="Download Result"><i class="fa fa-download"></i></a> ';

        }else{
          $center_name .= $Res['center_name'];

          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/result_agency/download_pdf/'.url_encode($Res['exam_code']).'/'.url_encode($Res['center_code']).'/'.url_encode($Res['regnumber'])).'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';

        }

        $row[] = $center_name;
        $row[] = $Res['description'];
        $row[] = $Res['pay_count'];
        $row[] = $Res['exam_period'];             
        
        
        
 
        
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
    

    public function centerwise_result_pdf($exam_code_enc,$center_code_enc){
      $data['act_id'] = "Download Result";
      $data['sub_act_id'] = "IIBF BCBF Result";
      $data['page_title'] = 'IIBF - BCBF Download Result';

      $data['exam_code_enc'] = $exam_code_enc;
      $data['center_code_enc'] = $center_code_enc; 
      
      $data['agency_centre_data'] = array();
      /*if($this->login_user_type == 'agency') 
      { 
        $agency_id = $this->login_agency_or_centre_id;  
        $this->db->join('iibfbcbf_memdetails bc', 'bc.regnumber = bm.regnumber', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_code = bc.inst_code', 'LEFT');
        $this->db->group_by('bm.center_code');
        $this->db->order_by('bm.center_name');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_marks bm', array('am.agency_id'=>$agency_id), 'bm.center_code, bm.center_name');
      }*/
      $inst_code = $this->login_agency_or_centre_id;  

      $this->load->view('iibfbcbf/agency/result_agency', $data);
    }

    public function download_pdf($exam_code,$center_code,$regnumber_enc = '0')
    { 
      ini_set("memory_limit", "-1"); 
      
      $data['result'] = '';
      $inst_code = $this->login_agency_or_centre_id;
      $exam_code = url_decode($exam_code);
      $center_code = url_decode($center_code);
      //$exam_period = $this->session->userdata('iibfbcbf_exam_period'); 
      //echo $exam_code."==".$center_code."==".url_decode($regnumber_enc);
      $select = array('iibfbcbf_memdetails.*','iibfbcbf_marks.*','iibfbcbf_result_subject.subject_code','iibfbcbf_result_subject.subject_name as subject_description','iibfbcbf_result_exam.exam_name_short','iibfbcbf_result_exam.exam_conduct','iibfbcbf_result_exam.result_date','iibfbcbf_result_subject.exam_period as exm_subject_prd');
      
      $this->db->join('iibfbcbf_marks','iibfbcbf_marks.regnumber = iibfbcbf_memdetails.regnumber');
      $this->db->join('iibfbcbf_result_exam','iibfbcbf_result_exam.exam_code = iibfbcbf_marks.exam_code AND iibfbcbf_result_exam.exam_period = iibfbcbf_marks.exam_period');
      $this->db->join('iibfbcbf_result_subject','iibfbcbf_result_subject.exam_code = iibfbcbf_marks.exam_code AND iibfbcbf_result_subject.exam_period = iibfbcbf_marks.exam_period');
      $this->db->join('iibfbcbf_agency_master','iibfbcbf_agency_master.agency_code = iibfbcbf_memdetails.inst_code');

      if($regnumber_enc != "0"){
        $regnumber_enc = url_decode($regnumber_enc); 
        $this->db->where(array('iibfbcbf_memdetails.regnumber'=>$regnumber_enc));
      } 

      //$result = $this->master_model->getRecords('iibfbcbf_memdetails',array('iibfbcbf_marks.center_code'=>$center_code,'iibfbcbf_marks.exam_code'=>$exam_code,'iibfbcbf_agency_master.agency_id'=>$inst_code),$select);
      $result = $this->master_model->getRecords('iibfbcbf_memdetails',array('iibfbcbf_marks.center_code'=>$center_code,'iibfbcbf_marks.exam_code'=>$exam_code),$select);
      
      //echo $this->db->last_query();
      //echo '<pre>',print_r($result),'</pre>';
      
      if($result)
      {       
          $this->load->library('m_pdf');
          $pdf = $this->m_pdf->load();
          //file directory creation
          //$file_dir_name = date('Ymd');  
          $file_dir_name = $center_code.'_'.$inst_code;
          $directory_name = "./uploads/iibfbcbf/iibf_bcbf_result/".date('Ymd');
          //mkdir($directory_name); 
          if (!file_exists($directory_name))
          {
            mkdir($directory_name);
          }

          $directory_name = "./uploads/iibfbcbf/iibf_bcbf_result/".date('Ymd').'/'.$file_dir_name;
          //mkdir($directory_name); 
          if (!file_exists($directory_name))
          {
            mkdir($directory_name);
          }
   
          $cnt = 0;
          foreach ($result as $val) 
          {
            if($cnt > 7) { redirect(site_url('iibfbcbf/agency/result_agency/download_pdf/'.url_encode($exam_code)."/".base64_encode($center_code))); }
            
            //echo '<pre>',print_r($val),'</pre>'; 
            $data['result_info'] = $val;
            $exam_period= $val['exm_subject_prd'];
            $member_id= $val['regnumber'];
            $pdfFilePath = 'uploads/iibfbcbf/iibf_bcbf_result/'.date('Ymd').'/'.$file_dir_name.'/'; 
            $pdfFilename = $exam_code."_".$member_id.".pdf";
            $file_arr[] = $pdfFilename;
            
            if (!file_exists($pdfFilePath.$pdfFilename)) 
            {
              $html = $this->load->view('iibfbcbf/agency/result_download', $data, true);
              $this->load->library('m_pdf');
              $pdf = $this->m_pdf->load();            
              $pdf->WriteHTML($html);
              if($regnumber_enc != "0")
              {
                //$pdf->Output($pdfFilePath, "D");
              }else{
                  $pdf->Output($pdfFilePath.$pdfFilename,"F");
              }
              
              //$pdf->Output($pdfFilePath, "D");
              $cnt++;
            } 

            if($regnumber_enc != "0")
            {
              $attchpath_resultcard = $this->load->view('iibfbcbf/agency/result_download', $data, true);
              //echo $attchpath_resultcard;die;
              $this->load->library('m_pdf');
              $pdf = $this->m_pdf->load();
              $pdfFilename = $exam_code."_".$member_id.".pdf";
              $pdf->WriteHTML($attchpath_resultcard);
              $path = $pdf->Output($pdfFilename, "D");  
              die;
            } 

          } 

          $zip_name = 'result_files_'.date("YmdHis").rand().".zip";

          //file directory creation 
          $zip_folder_path = "uploads/iibfbcbf/iibf_bcbf_result/".date('Ymd')."/".$file_dir_name."/zip";
          $directory_name = "./uploads/iibfbcbf/iibf_bcbf_result/".date('Ymd')."/".$file_dir_name."/zip";
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
                  $path = "./uploads/iibfbcbf/iibf_bcbf_result/".date('Ymd')."/".$file_dir_name."/".$file;
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
              $all_directories = $this->get_directory_list("./uploads/iibfbcbf/iibf_bcbf_result/");
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
                  $this->rmdir_recursive("uploads/iibfbcbf/iibf_bcbf_result/".$chk_dir);
                }
              }
            }       
            //END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
            // echo "==".count($file_arr);die;
            if (count($file_arr) > 0){
              redirect(base_url('uploads/iibfbcbf/iibf_bcbf_result/'.date('Ymd').'/'.$file_dir_name.'/zip/'.$zip_name));
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
  } ?>  