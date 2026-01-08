<?php 
  /********************************************************************************************************************
  ** Description: Controller for MACRORESEARCH application Master
  ** Created BY: Priyanka Dhikale 20-may-24
  ********************************************************************************************************************/
  ini_set('display_startup_errors', 1);ini_set('display_errors', 1);error_reporting(-1);

  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Applications extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('macroresearch_model');
      $this->load->helper('macroresearch_helper'); 
      
      $this->login_admin_id = $this->session->userdata('MACRORESEARCH_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('MACRORESEARCH_USER_TYPE');

      if($this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->macroresearch_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'admin' && $this->login_user_type != 'pdc')
      {
        $this->session->set_flashdata('error','You do not have permission to access Application module');
        redirect(site_url('macroresearch/admin/dashboard_admin'));
      }
		}
    
    public function index()
    {   
      redirect(site_url('/macroresearch/admin/applications/application_list/'.base64_encode('Individual'))); 
      
    }
    public function application_list($app_type='')
    {   
      $data['act_id'] = "Application Master";
      $data['page_title'] = 'IIBF - MACRO-RESEARCH Application Master';
      $data['app_type'] = base64_decode($app_type);
      $this->load->view('macroresearch/admin/application_admin', $data);
      
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Application DATA ********/
    public function get_application_data_ajax()
    {
      $table = 'macroresearch_applications am';
      
      $column_order = array('am.id','am.application_code','am.application_type', 'am.title_research_proposal','am.objectives','am.theme','am.created_on','am.institute_name','mr.candidate_name'); //SET COLUMNS FOR SORT
      
      $column_search = array('am.id','am.application_code','am.application_type', 'am.title_research_proposal','am.objectives','am.theme','am.created_on'); //SET COLUMN FOR SEARCH
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
      }
      else if($s_from_date != "") 
      { 
        $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."')"; 
      }
      else if($s_to_date != "") 
      { 
        $Where .= " AND (DATE(am.created_on) <= '".$s_to_date."')"; 
      } 
      if($_POST['app_type']!='' && $_POST['app_type']!='All')
      $Where .= " AND (am.application_type =  '".$_POST['app_type']."')"; 

      $s_application_status = trim($this->security->xss_clean($this->input->post('s_application_status')));
      if($s_application_status != "") { 
        $Where .= " AND am.is_active = '".$s_application_status."'"; 
      }
   
     
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN macroresearch_app_records mr ON am.id = mr.macro_id ";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where group by am.id $Order $Limit "; //ACTUAL QUERY
      
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->macroresearch_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->macroresearch_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
      
        $row[] = $no;
        if($_POST['app_type'] && $_POST['app_type']=='Institute') {
          $row[] = $Res['institute_name'];
        }
        $row[] = $Res['application_code'];
        $row[] = $Res['application_type'];
        $row[] = $Res['candidate_name'];
        $row[] = $Res['title_research_proposal'];
        $row[] = $Res['objectives'];
        $row[] = $Res['theme'];
        $row[] = date('d M Y',strtotime($Res['created_on']));
        
        
        $btn_str = ' <div class="text-center_name no_wrap"> ';

        

        $btn_str .= ' <a href="'.site_url('macroresearch/admin/applications/application_details/'.url_encode($Res['id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        
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
    /******** END : SERVER SIDE DATATABLE CALL FOR GET THE APPLICATION DATA ********/

    public function bank_id_card_upload()
    {
        if ($_FILES['bank_id_card']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('bank_id_card_upload', "No Id proof file selected");
            return false;
        }
    }

    /******** START : APPLICATION DETAILS PAGE ********/ 
    public function application_details($enc_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Application Master";      
      $data['page_title'] = 'IIBF - MACRO-RESEARCH Application Details';

      $data['enc_id'] = $enc_id;
      $id = url_decode($enc_id);
      
      $this->db->where('am.id',$id);
      $this->db->join('macroresearch_app_records mr', 'am.id = mr.macro_id', 'LEFT');
      $data['app_rows'] =$app_rows= $this->master_model->getRecords(' macroresearch_applications am', array('am.is_deleted' => '0'), "mr.*,am.*, IF(am.is_active=1, 'Active', 'Inactive') AS ApplicationStatus");

      if(count($app_rows) == 0) { redirect(site_url('macroresearch/admin/application')); }  
      

      $this->load->view('macroresearch/admin/application_details_admin', $data);
    }
    /******** END : APPLICATION DETAILS PAGE ********/


    function get_modal_change_password_data()
		{
			$data['enc_id'] = $enc_id = $this->input->post('enc_id');

      if($enc_id == "0")
			{
				echo "error";
			}
			else
			{
				$id = url_decode($enc_id);

        $result_data = $this->master_model->getRecords('macroresearch_applications am', array('am.id'=>$id, 'am.is_deleted'=>'0'));
        
        if(count($result_data) == 0)
				{
					echo "error";
				}
				else
				{
					$data['form_data'] = $result_data;
          $this->load->view('macroresearch/admin/modal_change_password_application', $data);
				}
			}
		}

    function download_files() {
      $Rows = $this->get_records_func();
      //echo $this->db->last_query();exit;
      $cron_file_dir = FCPATH.'uploads/macroresearch/';
      $current_date = date('Ymd');
      if(!file_exists($cron_file_dir.$current_date))
      {
        $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
      }
      $cron_file_path = $cron_file_dir.$current_date;
      
      $dirname = 'macroresearch_files'.date('Ymdhis');

      $directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}

        $proposaldirname = 'proposal_files';

        $proposaldir = $directory.'/'.$proposaldirname;
				if(file_exists($proposaldir))
				{
					array_map('unlink', glob($proposaldir."/*.*"));
					rmdir($proposaldir);
					$dir_flg = mkdir($proposaldir, 0700);
				}
				else
				{
					$dir_flg = mkdir($proposaldir, 0700);
				}

        $fldirname = 'forwarding_letters';

        $fldir = $directory.'/'.$fldirname;
				if(file_exists($fldir))
				{
					array_map('unlink', glob($fldir."/*.*"));
					rmdir($fldir);
					$dir_flg = mkdir($fldir, 0700);
				}
				else
				{
					$dir_flg = mkdir($fldir, 0700);
				}

        $cvdirname = 'cvs';

        $cvdir = $directory.'/'.$cvdirname;
				if(file_exists($cvdir))
				{
					array_map('unlink', glob($cvdir."/*.*"));
					rmdir($cvdir);
					$dir_flg = mkdir($cvdir, 0700);
				}
				else
				{
					$dir_flg = mkdir($cvdir, 0700);
				}

       

        $proposalzip = new ZipArchive;
				$Zip_flg = $proposalzip->open($proposaldir.'.zip', ZipArchive::CREATE);
       
        if($Zip_flg !== TRUE) {
          die("Failed to open or create the zip file: " . $directory . '.zip');
        }

        $flzip = new ZipArchive;
				$Zip_flg = $flzip->open($fldir.'.zip', ZipArchive::CREATE);
       
        if($Zip_flg !== TRUE) {
          die("Failed to open or create the zip file: " . $directory . '.zip');
        }

        $cvzip = new ZipArchive;
				$Zip_flg = $cvzip->open($cvdir.'.zip', ZipArchive::CREATE);
       
        if($Zip_flg !== TRUE) {
          die("Failed to open or create the zip file: " . $directory . '.zip');
        }
        
      foreach($Rows as $row) {
        $this->db->where('am.id',$row['id']);
        $this->db->join('macroresearch_app_records mr', 'am.id = mr.macro_id', 'LEFT');
        $app_rows= $this->master_model->getRecords(' macroresearch_applications am', array('am.is_deleted' => '0'), "mr.*,am.*, IF(am.is_active=1, 'Active', 'Inactive') AS ApplicationStatus");

        

        foreach($app_rows as $app_row) {
          if($app_row['proposal']!='') {
            $proposal_file = $cron_file_dir.''.$app_row['proposal'];
            copy($proposal_file, $proposaldir.'/'.$app_row['proposal']);

            $proposal_to_add =$cron_file_dir.'/'.$app_row['proposal'];
            $new_proposal = substr($proposal_to_add,strrpos($proposal_to_add,'/') + 1);

            $proposal_zip_flg = $proposalzip->addFile($proposal_to_add,$new_proposal);
          }

          if($app_row['forwarding_letter']!='') {
            $forwarding_letter_file = $cron_file_dir.''.$app_row['forwarding_letter'];
            
            copy($forwarding_letter_file, $fldir.'/'.$app_row['forwarding_letter']);

            $forwarding_letter_to_add =$cron_file_dir.'/'.$app_row['forwarding_letter'];
            $new_forwarding_letter = substr($forwarding_letter_to_add,strrpos($forwarding_letter_to_add,'/') + 1);

            $forwarding_letter_zip_flg = $flzip->addFile($forwarding_letter_to_add,$new_forwarding_letter);

          }

          if($app_row['resume']!='') {
            $resume_file = $cron_file_dir.''.$app_row['resume'];
            
            copy($resume_file, $cvdir.'/'.$app_row['resume']);

            $resume_to_add =$cron_file_dir.'/'.$app_row['resume'];
            $new_resume = substr($resume_to_add,strrpos($resume_to_add,'/') + 1);

            $resume_zip_flg = $cvzip->addFile($resume_to_add,$new_resume);

          }
        }
          
      }
      $proposalzip->close();
      $flzip->close();
      $cvzip->close();

      

      $zipFiles = [$proposaldir.'.zip', $fldir.'.zip', $cvdir.'.zip']; 

      // Name of the new ZIP file to be created
      $finalZipFile = $directory.'.zip';

      // Create a new zip archive
      $zip = new ZipArchive();

      // Open the new zip file for writing
      if ($zip->open($finalZipFile, ZipArchive::CREATE) === TRUE) {
          // Add each ZIP file into the new archive
          foreach ($zipFiles as $file) {
              if (file_exists($file)) {
                  // Add the file to the zip (with its name preserved)
                  $zip->addFile($file, basename($file));
              } else {
                  echo "Error: $file does not exist.<br>";
              }
          }
          // Close the archive
          $zip->close();
         // echo $cron_file_path;exit;
          $baseDir = rtrim($directory, '/') . '/';// Ensure the base directory ends with a slash      
        $directories = glob($baseDir . '*', GLOB_ONLYDIR);// Get all directories in the base directory
        
       // echo'<pre>';print_r($directories);exit;
        if(count($directories) > 0)
        {
          foreach ($directories as $dir) 
          {
            $dirName = basename($dir);// Get the base name of the directory
            
              // Recursively delete the directory
              $files = glob($dir . '/*');  // The '*' wildcard fetches all files in the folder
              //echo'<pre>';print_r($files);exit;
              // Loop through all the files
              foreach ($files as $file) {
                unlink($file);  // Delete the file
              }

              rmdir($dir); // Remove the directory itself
           
          }
          $files = glob($directory . '/*');  // The '*' wildcard fetches all files in the folder
              //echo'<pre>';print_r($files);exit;
              // Loop through all the files
              foreach ($files as $file) {
                unlink($file);  // Delete the file
              }
          rmdir($directory);
        }
        ob_clean();ob_get_clean();
          // Set headers to send the ZIP file for download
          header('Content-Type: application/zip');
          header('Content-Disposition: attachment; filename="' . basename($finalZipFile) . '"');
          header('Content-Length: ' . filesize($finalZipFile));

          // Send the file to the browser
          readfile($finalZipFile);

          // Optionally delete the file after download
          unlink($finalZipFile);
      } else {
          echo "Failed to create the ZIP file.";
      }

      
      

    }
    function get_records_func() {
      $table = 'macroresearch_applications am';
      
      $column_order = array('am.id','am.application_code','am.application_type', 'am.title_research_proposal','am.objectives','am.theme','am.created_on'); //SET COLUMNS FOR SORT
      
      $column_search = array('am.id','am.application_code','am.application_type', 'am.title_research_proposal','am.objectives','am.theme','am.created_on'); //SET COLUMN FOR SEARCH
      $order = array('am.id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE am.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE am.is_deleted = 0 	";
      
      //CUSTOM SEARCH
      $s_from_date = trim($this->security->xss_clean($_GET['s_from_date']));
      $s_to_date = trim($this->security->xss_clean($_GET['s_to_date']));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."' AND DATE(am.created_on) <= '".$s_to_date."')"; 
      }
      else if($s_from_date != "") 
      { 
        $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."')"; 
      }
      else if($s_to_date != "") 
      { 
        $Where .= " AND (DATE(am.created_on) <= '".$s_to_date."')"; 
      } 
      if($_GET['app_type']!='' && $_GET['app_type']!='All')
      $Where .= " AND (am.application_type =  '".$_GET['app_type']."')"; 

    
      $Order = ""; //DATATABLE SORT
     
      $Limit = "";
      
      $join_qry = "";//" LEFT JOIN pdc_zone_master sm ON am.pdc_zone = sm.pdc_zone_code  LEFT JOIN macroresearch_role_fee rl ON am.role_id = rl.id";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where group by am.id $Order $Limit "; //ACTUAL QUERY
      
      
        $Result = $this->db->query($print_query);  
        $Rows = $Result->result_array();
        return $Rows;
    }
   function export_to_csv() {

      require_once  FCPATH. 'application/helpers/PHPExcel/Classes/PHPExcel.php';
      require_once  FCPATH. 'application/helpers/PHPExcel/Classes/PHPExcel/IOFactory.php';
      

      $table = 'macroresearch_applications am';
      
      $column_order = array('am.id','am.application_code','am.application_type', 'am.title_research_proposal','am.objectives','am.theme','am.created_on','am.institute_name'); //SET COLUMNS FOR SORT
      
      $column_search = array('am.id','am.application_code','am.application_type', 'am.title_research_proposal','am.objectives','am.theme','am.created_on'); //SET COLUMN FOR SEARCH
      $order = array('am.id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE am.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE am.is_deleted = 0 	";
      
      //CUSTOM SEARCH
      $s_from_date = trim($this->security->xss_clean($_GET['s_from_date']));
      $s_to_date = trim($this->security->xss_clean($_GET['s_to_date']));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."' AND DATE(am.created_on) <= '".$s_to_date."')"; 
      }
      else if($s_from_date != "") 
      { 
        $Where .= " AND (DATE(am.created_on) >= '".$s_from_date."')"; 
      }
      else if($s_to_date != "") 
      { 
        $Where .= " AND (DATE(am.created_on) <= '".$s_to_date."')"; 
      } 
      if($_GET['app_type']!='' && $_GET['app_type']!='All')
      $Where .= " AND (am.application_type =  '".$_GET['app_type']."')"; 

    
      $Order = ""; //DATATABLE SORT
     
      $Limit = "";
      
      $join_qry = " LEFT JOIN macroresearch_app_records mr ON am.id = mr.macro_id ";
            
      $print_query = "SELECT *,mr.id as mrid,am.id as amid FROM $table $join_qry $Where group by mr.id $Order $Limit "; //ACTUAL QUERY
      
      
        $Result = $this->db->query($print_query);  
        $Rows = $Result->result_array();
        //echo $this->db->last_query();exit;
        $TotalResult = $this->macroresearch_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
        $FilteredResult = $this->macroresearch_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
        
        $data = array();
        $no = 1;    
        $filename = FCPATH.'uploads/macroresearch/macroresearch_data.xlsx';
      $newfilename = FCPATH.'uploads/macroresearch/macroresearch-records-'.date('ymdhis').'.xlsx';
      //echo '---------'.$newfilename;exit;
      $objPHPExcel =PHPExcel_IOFactory::load($filename);
      

      $objPHPExcel->setActiveSheetIndex(0);
      $cell =1;// $objPHPExcel->getActiveSheet()->getHighestRow()+1;

      $styleArray1 = array(
        
        'borders' => array(
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
          //	'color' => array('rgb' => 'AAAAAA')
          )
        ));
        $styleArray = array(
          'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'ffffff'),
            //'size'  => 15,
            //'name'  => 'Verdana'
          ),
          'borders' => array(
            'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN,
            //	'color' => array('rgb' => 'AAAAAA')
            )
          ));
        $i=1;
       $preparedArray = array();
        foreach ($Rows as $Res) 
        {
          $preparedArray[$Res['application_code']][] = $Res;

        }

        
        $mergedArray = [];

          // Loop through each key in the array
          foreach ($preparedArray as $key => $childArrays) {
              foreach ($childArrays as $index => $subArray) {
                
                  foreach ($subArray as $subKey => $value) {
                    $i=1;
                      // If the key already exists in the merged array, append the value with a comma
                      if (isset($mergedArray[$key][$subKey])) {
                        $i++;
                        if($subKey=='email') {
                          $var = '; ';
                          for($j=1;$j<$i;$j++)
                            $var .= '';
                        }
                        else {
                          $var = ' *';
                          for($j=1;$j<$i;$j++)
                            $var .= '*';
                        }
                          $var .= $value;
                          $mergedArray[$key][$subKey] .= $var;
                      } else  {
                          // Otherwise, just set the value
                          $mergedArray[$key][$subKey] = $value;
                      }
                      if($subKey=='title_research_proposal' || $subKey=='objectives' || $subKey=='theme' || $subKey=='application_type' || $subKey=='created_on' || $subKey=='application_code')
                      {
                        $mergedArray[$key][$subKey] = $value;
                      }
                      $mergedArray[$key][$subKey] = rtrim( $mergedArray[$key][$subKey],',');
                  }
              }
          }
          //echo '<pre>';print_r($mergedArray);exit;
          foreach($mergedArray as $Res) {
            $cell++;

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$cell,$i++)
                      ->setCellValue('B'.$cell, $Res['application_code'])
                      ->setCellValue('C'.$cell, $Res['application_type'])
                      ->setCellValue('D'.$cell, $Res['title_research_proposal'])
                      ->setCellValue('E'.$cell, $Res['objectives'])
                      ->setCellValue('F'.$cell, $Res['theme']) 
                      ->setCellValue('G'.$cell, $Res['salutation'])                    
                      ->setCellValue('H'.$cell, $Res['candidate_name'])                    
                      ->setCellValue('I'.$cell, $Res['dob'])                    
                      ->setCellValue('J'.$cell, $Res['email'])                    
                      ->setCellValue('K'.$cell, $Res['mobile'])                    
                      ->setCellValue('L'.$cell, $Res['nature_of_job'])                    
                      ->setCellValue('M'.$cell, $Res['employer'])                    
                      ->setCellValue('N'.$cell, $Res['designation'])                    
                      ->setCellValue('O'.$cell, $Res['address'])                    
                      ->setCellValue('P'.$cell, date('Y-m-d',strtotime($Res['created_on'])))
                      ->setCellValue('Q'.$cell, $Res['institute_name'])         ;

            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('N'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('O'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('P'.$cell)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('Q'.$cell)->applyFromArray($styleArray1);
            // 
          } 
          $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
          $objWriter->save($newfilename);
      
        		
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);
        
        ob_clean();ob_get_clean();
        $file = basename($newfilename);
        
      if(file_exists($newfilename)){
        
          header("Pragma: public");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Cache-Control: private",false);
          header("Content-Description: File Transfer");
          header("Content-Disposition: attachment; filename=\"$file\"");
          header("Content-Type:  application/vnd.ms-excel");
          header("Content-Transfer-Encoding: binary");
          header("Content-Length: ".filesize($newfilename));
          readfile($newfilename);
          unlink($newfilename);
        exit;
      }
    }
    function session_forms() {
      $data = array();
      $data['act_id'] = "Forms";
      $this->load->view('macroresearch/admin/session_forms_admin', $data);
    }

    function get_session_form_data_ajax() {
      $table = 'macroresearch_session_forms am';
      
      
      $column_order = array('am.id',  'am.exam_code', 'am.venue_name',  'am.exam_date', 'am.no_of_session', 'cm.center_name', 'am.total_amount','am.created_on', 'am.pay_status', 'cm.application_name', 'em.exam_name', 'pay_status', 'am.is_active', 'am.downloaded_file','am.uploaded_file','am.exam_period','am.application_id', 'am.venue_code'); //SET COLUMNS FOR SORT
      
      $column_search = array( 'am.exam_code', 'am.venue_code', 'am.venue_name',  'am.exam_date',  'cm.center_name','am.total_amount', 'am.pay_status', 'cm.application_name', 'em.exam_name','pay_status',  'cm.application_code'); //SET COLUMN FOR SEARCH
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

      if($this->session->userdata('MACRORESEARCH_ADMIN_TYPE')!=1) {
        $Where .= " AND cm.pdc_zone = '".$this->session->userdata('MACRORESEARCH_ADMIN_PDC')."'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN macroresearch_applications cm ON cm.id=am.application_id  LEFT JOIN macroresearch_exam_activation em ON em.exam_code=am.exam_code";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where group by am.id $Order $Limit "; //ACTUAL QUERY
         
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->macroresearch_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->macroresearch_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $PaidStatus='Under Process';
        if($Res['pay_status']==3) $PaidStatus='Rejected';
        if($Res['pay_status']==1) $PaidStatus='Processed';
        $row[] = $no;
        $row[] = $Res['exam_name'].' - '.$Res['exam_period'];
        $row[] = $Res['venue_name'];
        $row[] = $Res['exam_date'];
        $row[] = $Res['no_of_session'];
        $row[] = $Res['center_name'];
        $row[] = 'Rs. '.$Res['total_amount'];
        $row[] = date('Y-M-d',strtotime($Res['created_on']));        
        
        $row[] = '<span class="badge '.show_faculty_status($Res['pay_status']).'" style="min-width:90px;">'.$PaidStatus.'</span>';
        

        $claim_data = $form_data = $this->master_model->getRecords('macroresearch_claims am', array('am.session_form_id' => $Res['id'], 'am.is_deleted' => '0'), "am.id");   

        $btn_str = ' <div class="text-center_name no_wrap"> ';        
        
        $btn_str .= '<a href="'.site_url('macroresearch/admin/application/save_session_form/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';

        if($Res['uploaded_file']!='') {
        
          $btn_str .= '<a href="'.site_url('macroresearch/admin/application/download_form_pdf/?pdf_file='.($Res['uploaded_file'])).'" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a> ';

          if(count($claim_data)>0) {
            $btn_str .= '<a href="'.site_url('macroresearch/admin/application/save_claim_form/='.url_encode($Res['id'])).'/'.url_encode($claim_data[0]['id']).'" class="btn btn-success btn-xs" title="Honorarium form"><i  aria-hidden="true" class="fa fa-inr"></i></a> ';
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
    function save_session_form($enc_form_id=0) {
      $data = array();
      $data['act_id'] = "Forms";
      $data['enc_form_id'] = $enc_form_id;
       //START : FIND OUT THE CURRENT MODE AND GET THE APPLICATION DATA
       if($enc_form_id == '0') 
       { 
         $data['mode'] = $mode = "Add"; $form_id = $enc_form_id;
       }
       else
       {
         $form_id = url_decode($enc_form_id);
         
         $data['form_data'] = $form_data = $this->master_model->getRecords('macroresearch_session_forms am', array('am.id' => $form_id, 'am.is_deleted' => '0'), "am.*");        
         if(count($form_data) == 0) { 
          
          redirect(site_url('/macroresearch/admin/application/session_forms')); 
        } else if($form_data[0]['is_deleted']==1) redirect(site_url('/macroresearch/admin/application/session_forms')); 

        if(count($form_data)>0) {
          $application_appeared = explode(',',$form_data[0]['application_appeared']);
          if(isset($application_appeared[0]))
            $data['form_data'][0]['application_appeared1']=$application_appeared[0];
          if(isset($application_appeared[1]))
            $data['form_data'][0]['application_appeared2']=$application_appeared[1];
          if(isset($application_appeared[2]))
            $data['form_data'][0]['application_appeared3']=$application_appeared[2];
        }
         
         $data['mode'] = $mode = "Update";
       }//END : FIND OUT THE CURRENT MODE AND GET THE APPLICATION DATA
       $data['form_id'] =$form_id;
       $data['page_title'] = 'IIBF - MACRO-RESEARCH MACRO-RESEARCH Report';

       $this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
        
        $this->db->where('am.id',$data['form_data'][0]['application_id']);
        $data['application_data'] = $this->master_model->getRecords('macroresearch_applications am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS ApplicationStatus, sm.pdc_zone_name");

        $data['exams'] = $this->master_model->getRecords('macroresearch_exam_activation', array('is_deleted' => '0'));


       if(isset($_POST) && count($_POST) > 0)
      {
        if(count($form_data)>0 &&  $form_data[0]['pay_status']==1)  {
          $this->session->set_flashdata('error','PDC already reviewed this MACRO-RESEARCH Report');
          redirect(site_url('/macroresearch/admin/application/session_forms')); 
        }            
        
        if($this->input->post('comment_by_pdc') && trim($this->input->post('comment_by_pdc'))!='') {
          
        
          $add_data['updated_on'] = date("Y-m-d H:i:s");
          $add_data['updated_by'] = $this->login_admin_id;  
          $add_data['comment_by_pdc'] =   trim($this->input->post('comment_by_pdc'));        
          $this->master_model->updateRecord('macroresearch_session_forms', $add_data, array('id'=>$form_id));

          $emailerstr = $this->master_model->getRecords('emailer', array(
            'emailer_name' => 'macroresearch_session_form_comment',
          ));
            if (count($emailerstr) > 0) {

              $this->db->join('macroresearch_center_master cm', 'cm.center_code  = am.center_code ', 'LEFT');
              $venue_details = $this->master_model->getRecords('macroresearch_venue_master am', array('am.venue_delete' => '0','am.exam_code' => $this->input->post('exam_code'),'am.venue_code' => $this->input->post('venue_code'),'am.exam_date' => $this->input->post('exam_date')), "am.*,cm.center_name");

              $this->db->where('exam_code',$this->input->post('exam_code'));
              $selectedExam = $this->master_model->getRecords('macroresearch_exam_activation', array('is_deleted' => '0'));
              
              $exam_time_val='';
              foreach($_POST['exam_time'] as $k=>$exam_time) {
                $exam_time_val.= $exam_time.',';
                
              }

              $this->db->where("admin_id",$this->login_admin_id);
              $pdcloggedin = $this->master_model->getRecords('macroresearch_admin', array('is_deleted' => '0'));

              $venue = $venue_details[0]['venue_name'].' '.$venue_details[0]['venue_addr1'].' '.$venue_details[0]['venue_addr2'].' '.$venue_details[0]['venue_addr3'].' '.$venue_details[0]['venue_addr4'].' '.$venue_details[0]['venue_addr5'].' '.$venue_details[0]['venue_pincode'];
              $final_str = str_replace("#comment#", "" .trim($this->input->post('comment_by_pdc'))."", $emailerstr[0]['emailer_text']);
              $final_str = str_replace("#exam#", "" .$selectedExam[0]['exam_name']. "", $final_str);
              $final_str = str_replace("#exam_time#", "" .rtrim($exam_time_val, ','). "", $final_str);
              $final_str = str_replace("#venue#", "" .$venue. "", $final_str);
              $final_str = str_replace("#center#", "" .$venue_details[0]['center_name']. "", $final_str);
              $final_str = str_replace("#exam_date#", "" .date('Y-m-d',strtotime($this->input->post('exam_date'))). "", $final_str);
             
              $info_arr  = array(
                  'to'      => array($data['application_data'][0]['email'],$pdcloggedin[0]['admin_username']),
                 
                  'from'    => $emailerstr[0]['from'],
                  'subject' => $emailerstr[0]['subject'] ,
                  'message' => $final_str,
              );
              if ($this->Emailsending->mailsend($info_arr)) {
                
                $this->macroresearch_model->insert_common_log('MACRO-RESEARCH Report comment by pdc:  mail sent to observer', 'save_session_form', $final_str, $id,'save_session_form','', json_encode($info_arr));
                
                $this->session->set_flashdata('success','Comment Successfully sent to Observer');
              }
              else 
              $this->session->set_flashdata('error','Comment not sent to Observer. Please try again');
              redirect(site_url('macroresearch/admin/application/session_forms'));   
          }
        }
       
        return 1;
        //considering pdc will not update data in session form

          $this->form_validation->set_rules('exam_code', 'Exam', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|max_length[5]|xss_clean', array('required'=>"Please select the %s"));     
          $this->form_validation->set_rules('venue_code', 'Venue', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|xss_clean', array('required'=>"Please select the %s"));  
          $this->form_validation->set_rules('exam_date', 'Exam date', 'trim|required|max_length[20]|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('exam_time[]', 'Exam time', 'trim|required|max_length[100]|xss_clean', array('required'=>"Please select the %s"));
          $this->form_validation->set_rules('no_of_pc', 'No. of PC', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|max_length[4]|xss_clean');

          $this->form_validation->set_rules('suitable_venue_loc', 'Location of venue whether suitable and convenient', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['suitable_venue_loc']=='Yes') {
            $this->form_validation->set_rules('suitable_venue_loc_reason', 'Location of venue whether suitable and convenient - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('venue_open_bef_exam', 'Whether venue was opened before the examination time ', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['venue_open_bef_exam']=='Yes') {
            $this->form_validation->set_rules('venue_open_bef_exam_reason', 'Whether venue was opened before the examination time  - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('venue_reserved', 'Whether the venue was exclusively reserved for IIBF ', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['venue_reserved']=='Yes') {
            $this->form_validation->set_rules('venue_reserved_reason', 'Whether the venue was exclusively reserved for IIBF   - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('venue_power_problem', 'Was there a power problem in venue', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['venue_power_problem']=='Yes') {
            $this->form_validation->set_rules('venue_power_problem_sol', 'Was there a power problem in venue  - Solution', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('no_of_supervisors', 'Number of test supervisors in the venue', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('registration_process', 'Whether registration process was completed before the examination time', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['registration_process']=='Yes') {
            $this->form_validation->set_rules('registration_process_reason', 'Whether registration process was completed before the examination time - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('frisking', ' Whether frisking was done before the application were allowed to enter in computer lab', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['frisking']=='Yes') {
            $this->form_validation->set_rules('frisking_reason', ' Whether frisking was done before the application were allowed to enter in computer lab - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('frisking_lady', 'Whether lady frisking staff was available for frisking the lady applications', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['frisking_lady']=='Yes') {
            $this->form_validation->set_rules('frisking_lady_reason', 'Whether lady frisking staff was available for frisking the lady applications - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }          

          $this->form_validation->set_rules('mobile_allowed', 'Whether mobile phone,text materials etc. were allowed in venue', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['mobile_allowed']=='Yes') {
            $this->form_validation->set_rules('mobile_allowed_reason', 'Whether mobile phone,text materials etc. were allowed in venue - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('admit_letter_checked', 'Whether application admit letter was checked and verified before permitting to sit for examination be the supervisors', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['admit_letter_checked']=='Yes') {
            $this->form_validation->set_rules('admit_letter_checked_reason', 'Whether application admit letter was checked and verified before permitting to sit for examination be the supervisors - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('exam_without_admit_letter', 'Whether any application were permitted to appear for the examination without proper admit letter and ID card', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['exam_without_admit_letter']=='Yes') {
            $this->form_validation->set_rules('exam_without_admit_letter_detils', 'Whether any application were permitted to appear for the examination without proper admit letter and ID card - Details', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('seat_no_written', 'Whether seat numbers were written againts each PC', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['seat_no_written']=='Yes') {
            $this->form_validation->set_rules('seat_no_written_reason', 'Whether seat numbers were written againts each PC - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('application_seated', 'Whether applications were seated in the seat number mentioned in the admit letter', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['application_seated']=='Yes') {
            $this->form_validation->set_rules('application_seated_reason', 'Whether applications were seated in the seat number mentioned in the admit letter - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('scribe_arrange', 'Whether separate arrangments was made available for PWD(Person with Disabilities) applications using scribe', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['scribe_arrange']=='Yes') {
            $this->form_validation->set_rules('scribe_arrange_reason', 'Whether separate arrangments was made available for PWD(Person with Disabilities) applications using scribe - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }
          
          $this->form_validation->set_rules('announcement', 'Whether rules of examination are announced to the applications by the Invigilators', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          $this->form_validation->set_rules('announcement_gap', 'Whether rules of examination are announced to the applications by the Invigilators - Gap', 'trim|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          
          
          $this->form_validation->set_rules('exam_started', 'Whether examination started as scheduled', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['exam_started']=='Yes') {
            $this->form_validation->set_rules('exam_started_reason', 'Whether examination started as scheduled - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('application_appeared', 'Whether examination started as scheduled', 'trim|required|max_length[100]|xss_clean', array('required'=>"Please select the %s"));

          $this->form_validation->set_rules('started_late', 'Whether any application were allowed to start the examination after 15 minutes of scheduled examination', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['started_late']=='Yes') {
            $this->form_validation->set_rules('started_late_reason', 'Whether any application were allowed to start the examination after 15 minutes of scheduled examination - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('unfair_applications', 'Was any unfair means was adopted by the applications during the examination', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['unfair_applications']=='Yes') {
            $this->form_validation->set_rules('unfair_applications_reason', 'Was any unfair means was adopted by the applications during the examination - Details', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('rough_sheet', 'Rough sheet given to applications were collected back and destroyed.', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[3]|xss_clean', array('required'=>"Please select the %s"));

          if($_POST['rough_sheet']=='Yes') {
            $this->form_validation->set_rules('rough_sheet_reason', 'Rough sheet given to applications were collected back and destroyed. - Reason', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
          }

          $this->form_validation->set_rules('action_for_unfair', 'What is the action taken for unfair means adopted by the applications', 'trim|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('name_mob_exam_contro', 'Name & Mobile No. of Examination Controller - Sify/NSEIT', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('issue_reported', 'Any issue reported/faced by applications', 'trim|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('observation', 'Any other observation /Suggestion if any', 'trim|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));

          $this->form_validation->set_rules('filled_date', 'Date', 'trim|required|max_length[25]|xss_clean', array('required'=>"Please enter the %s"));

          if(isset($_POST['uploaded_file']) && $_POST['uploaded_file']!='') {
            $this->form_validation->set_rules('bank_id_card', 'Id card', 'file_required|file_allowed_type[pdf]|file_size_max[300]');
          }

          if($this->form_validation->run())
          {     
            $posted_arr = json_encode($_POST);

            $outputfile = $pdf_file='';
            $date = date('ymdhis');
            

            if($data['application_data'][0]['role_id']!='' && $data['application_data'][0]['role_id']!=0) {
              $role_fee = $this->master_model->getRecords('macroresearch_role_fee am', array('am.is_deleted' => '0','am.role_id' => $data['application_data'][0]['role_id']));
            }

            $venue_details = $this->master_model->getRecords('macroresearch_venue_master am', array('am.venue_delete' => '0','am.exam_code' => $this->input->post('exam_code'),'am.venue_code' => $this->input->post('venue_code'),'am.exam_date' => $this->input->post('exam_date')));

            $total_amount=0;
            $no_of_session=0;$exam_time_val='';
            foreach($_POST['exam_time'] as $k=>$exam_time) {
              $exam_time_val.= $exam_time.',';
              $no_of_session++;
              if($k==0)
                $add_amount = $role_fee[0]['B1_fee'];
              else
                $add_amount = $role_fee[0]['s1_fee'];
              $total_amount += $add_amount;
            }
            if($pdf_file!='') {
              $add_data['uploaded_file'] = $pdf_file;
            }

            
            $add_data['application_id'] = $data['form_data'][0]['application_id'];
            $add_data['exam_code'] = $this->input->post('exam_code');
            
            $add_data['venue_code'] = $this->input->post('venue_code');
            $add_data['venue_name'] = $venue_details[0]['venue_name'];
            $add_data['venueadd1'] = $venue_details[0]['venue_addr1'];
            $add_data['venueadd2'] = $venue_details[0]['venue_addr2'];
            $add_data['venueadd3'] = $venue_details[0]['venue_addr3'];
            $add_data['venueadd4'] = $venue_details[0]['venue_addr4'];
            $add_data['venueadd5'] = $venue_details[0]['venue_addr5'];
            $add_data['venpin'] = $venue_details[0]['venue_pincode'];
            $add_data['exam_date'] = date('Y-m-d',strtotime($this->input->post('exam_date')));
            $add_data['exam_time'] = rtrim($exam_time_val, ',');
            $add_data['no_of_session'] = $no_of_session;
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
            $add_data['application_seated'] = $this->input->post('application_seated');
            $add_data['application_seated_reason'] = ucfirst(trim($this->input->post('application_seated_reason')));
            $add_data['scribe_arrange'] = $this->input->post('scribe_arrange');
            $add_data['scribe_arrange_reason'] = ucfirst(trim($this->input->post('scribe_arrange_reason')));
            $add_data['announcement'] = $this->input->post('announcement');
            $add_data['announcement_gap'] = ucfirst(trim($this->input->post('announcement_gap')));
            $add_data['exam_started'] = $this->input->post('exam_started');
            $add_data['exam_started_reason'] = ucfirst(trim($this->input->post('exam_started_reason')));
            $add_data['application_appeared'] = $this->input->post('application_appeared');
            $add_data['started_late'] = $this->input->post('started_late');
            $add_data['started_late_reason'] = ucfirst(trim($this->input->post('started_late_reason')));
            $add_data['unfair_applications'] = $this->input->post('unfair_applications');
            $add_data['unfair_applications_reason'] = ucfirst(trim($this->input->post('unfair_applications_reason')));
            $add_data['rough_sheet'] = $this->input->post('rough_sheet');
            $add_data['rough_sheet_reason'] = ucfirst(trim($this->input->post('rough_sheet_reason')));
            $add_data['action_for_unfair'] = ucfirst(trim($this->input->post('action_for_unfair')));
            $add_data['name_mob_exam_contro'] = ucfirst(trim($this->input->post('name_mob_exam_contro')));
            $add_data['issue_reported'] = ucfirst(trim($this->input->post('issue_reported')));
            $add_data['observation'] = ucfirst(trim($this->input->post('observation')));
            $add_data['filled_date'] = date('Y-m-d',strtotime($this->input->post('filled_date')));           
            $add_data['total_amount'] = $total_amount;
            $add_data['pay_status'] = $this->input->post('pay_status');

            $pdfFilePath = "dwn_macroresearch_".$data['form_data'][0]['application_id'].$form_id.date('ymd',strtotime($this->input->post('exam_date'))).".pdf";

            $add_data['downloaded_file'] = $pdfFilePath;
            
            if($mode == "Add") 
            {
              $add_data['is_active'] = '1';   
              $add_data['created_by'] = $data['form_data'][0]['application_id'];  
              $form_id = $this->db->insert_id();
              $this->macroresearch_model->insert_common_log('Admin : MACRO-RESEARCH Report added', 'macroresearch_session_forms', $this->db->last_query(), 0,'save_session_form','The session form has successfully added by the application '.$this->login_admin_id , $posted_arr);

            }
            else if($mode == "Update")
            {
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_admin_id ;            
              $this->master_model->updateRecord('macroresearch_session_forms', $add_data, array('id'=>$form_id));
                            
              $this->macroresearch_model->insert_common_log('Admin : MACRO-RESEARCH Report Updated', 'macroresearch_session_forms', $this->db->last_query(), $form_id,'save_session_form','The session form has successfully updated by the application '.$this->login_admin_id , $posted_arr);
            }
            //generate PDF            

            $this->db->join('macroresearch_applications sm', 'sm.id = am.application_id', 'LEFT');
            $this->db->join('macroresearch_exam_activation se', 'se.exam_code = am.exam_code', 'LEFT');
            $this->db->where('sm.id',$data['form_data'][0]['application_id']);
            $this->db->where('am.id',$form_id);
            $data['form_details']= $this->master_model->getRecords('macroresearch_session_forms am', array('am.is_deleted' => '0'), "am.*, sm.application_name,, sm.email,, sm.mobile, sm.bank, sm.branch, sm.designation, sm.pdc_zone, sm.center_name,se.exam_name");

            $html=$this->load->view('macroresearch/session_form_download', $data, true);
            $this->load->library('m_pdf');
            $pdf =$this->m_pdf->load();
            
            $pdf->WriteHTML($html);
            $path = $pdf->Output('uploads/macroresearch/'.$pdfFilePath, "F"); 
          
           $this->session->set_flashdata('success','MACRO-RESEARCH Report details successfully');
            redirect(site_url('macroresearch/admin/application/session_forms'));   
           
          }
        }

       
      $this->load->view('macroresearch/admin/save_session_form_admin', $data);
    }
    
    function save_claim_form($enc_form_id=0,$enc_claim_id=0) {
      $data = array();
      $data['act_id'] = "Honorarium form";
      $data['enc_form_id'] = $enc_form_id;
      $data['enc_claim_id'] = $enc_claim_id;
     
       {
        $claim_id = url_decode($enc_claim_id);
         $form_id = url_decode($enc_form_id);
         
        $data['claim_data'] = $claim_data = $this->master_model->getRecords('macroresearch_claims am', array('am.id' => $claim_id, 'am.session_form_id' => $form_id, 'am.is_deleted' => '0'), "am.*");        
         if(count($claim_data) == 0) { 
         
          redirect(site_url('/macroresearch/application/dashboard_application/claims')); 
        } 
        else if($claim_data[0]['is_deleted']==1) 
          redirect(site_url('/macroresearch/application/dashboard_application/claims')); 
         
         $data['mode'] = $mode = "Update";
       }//END 

       $this->db->join('macroresearch_exam_activation sm', 'sm.exam_code = am.exam_code', 'LEFT');
         $data['form_data'] = $form_data = $this->master_model->getRecords('macroresearch_session_forms am', array('am.id' => $form_id, 'am.is_deleted' => '0'), "am.*,sm.exam_name");        
         if(count($form_data) == 0) { 
          $this->session->set_flashdata('error','MACRO-RESEARCH Report is not exist');
          redirect(site_url('/macroresearch/application/dashboard_application/session_forms')); 
        } 
        else if($form_data[0]['is_deleted']==1)  {
          $this->session->set_flashdata('error','MACRO-RESEARCH Report is not exist');
          redirect(site_url('/macroresearch/application/dashboard_application/session_forms')); 
        }          
        
        else if($form_data[0]['uploaded_file']=='' || $form_data[0]['downloaded_file']=='')  {
            $this->session->set_flashdata('error','signed PDF not uploaded for session form');
            redirect(site_url('/macroresearch/application/dashboard_application/session_forms')); 
        }
        else if($form_data[0]['is_active']!=1)  {
            $this->session->set_flashdata('error','MACRO-RESEARCH Report is under review');
            redirect(site_url('/macroresearch/application/dashboard_application/session_forms')); 
         } 
          
        $data['sessions'] = explode(',',$form_data[0]['exam_time']);
        $data['session_wise_amount'] = explode(',',$form_data[0]['session_wise_amount']);
        
       $data['form_id'] =$form_id;
       $data['claim_id'] =$claim_id;
       $data['page_title'] = 'IIBF - MACRO-RESEARCH Honorarium form';

       $this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
        
        $this->db->where('am.id',$form_data[0]['application_id']);
        $data['application_data'] = $this->master_model->getRecords('macroresearch_applications am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS ApplicationStatus, sm.pdc_zone_name");
        $data['exams'] = $this->master_model->getRecords('macroresearch_exam_activation', array('is_deleted' => '0'));

       if(isset($_POST) && count($_POST) > 0)
      {
          if(count($form_data)>0 &&  $form_data[0]['pay_status']==1)  {
            
            $this->session->set_flashdata('error','PDC already reviewed MACRO-RESEARCH Report');
            redirect(site_url('/macroresearch/admin/application/claims')); 
          }
          if($claim_data[0]['uploaded_file']=='' && $this->input->post('is_paid')==1) {
            $this->session->set_flashdata('error','Signed Document need to be uploaded before change status');
            redirect(site_url('/macroresearch/admin/application/claims')); 
          }
          
          $this->form_validation->set_rules('is_paid', 'Payment status', 'trim|required|max_length[2]|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|xss_clean', array('required'=>"Please select the %s"));  
          if($this->input->post('is_paid')==3) {
            $this->form_validation->set_rules('reject_reason', 'Reason of Rejection', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));  
          }

          if($this->form_validation->run())
          {     
            $posted_arr = json_encode($_POST);
            $add_data['is_paid'] =$this->input->post('is_paid');
            $add_data['paid_date'] = date("Y-m-d");
            if($this->input->post('reject_reason') ) {
              $add_data['reject_reason'] =trim($this->input->post('reject_reason'));
            }
            if($this->input->post('is_paid')==1){
              $add_data['reject_reason'] ='';
            }
            if($mode == "Update")
            {
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->session->userdata('MACRORESEARCH_LOGIN_ID');                
              $this->master_model->updateRecord('macroresearch_claims', $add_data, array('id'=>$claim_id));

              $for_data['updated_on'] = date("Y-m-d H:i:s");              
              $for_data['pay_status'] =$this->input->post('is_paid');
              $for_data['updated_by'] = $this->session->userdata('MACRORESEARCH_LOGIN_ID');            
              $this->master_model->updateRecord('macroresearch_session_forms', $for_data, array('id'=>$form_id));

              if($this->input->post('is_paid')==1)
              {
                $emailerstr = $this->master_model->getRecords('emailer', array(
                  'emailer_name' => 'macroresearch_claim_approved',
              ));
              }
              else if($this->input->post('is_paid')==3)
              {
                $emailerstr = $this->master_model->getRecords('emailer', array(
                  'emailer_name' => 'macroresearch_claim_rejected',
              ));
              }
                if (count($emailerstr) > 0) {
                    $final_str = str_replace("#application#", "" . $data['application_data'][0]['application_name'] . "", $emailerstr[0]['emailer_text']);
                    $final_str = str_replace("#reason#", "" . $this->input->post('reject_reason') . "", $final_str);
                    
                    $info_arr  = array(
                        'to'      => $data['application_data'][0]['email'],
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'] ,
                        'message' => $final_str,
                    );
                    if ($this->Emailsending->mailsend($info_arr)) {
        
                      $this->macroresearch_model->insert_common_log('Claim :  mail sent to application', 'macroresearch_claims', $final_str, $this->login_admin_id,'save_claim_form','The claim updated by the admin.', json_encode($info_arr));                     
                    
                    }
                }
              $this->macroresearch_model->insert_common_log('Admin : claim form Updated', 'save_claim_form', $this->db->last_query(), $claim_id,'save_claim_form','The claim form has successfully updated by the admin '.$this->session->userdata('MACRORESEARCH_LOGIN_ID'), $posted_arr);
            }

            $this->session->set_flashdata('success','Honorarium form details saved successfully');
            redirect(site_url('macroresearch/admin/application/claims'));   
           

          }
        }

       
      $this->load->view('macroresearch/admin/save_claim_form_admin', $data);
    }
    /******** START : CHANGE ADMIN PASSWORD ********/
    function send_mail_to_application($enc_id)
		{   
      $data['act_id'] = "Send E-mail to Observer";
			$data['sub_act_id'] = "Send E-mail to Observer";
      $log_slug = '';
      $data['enc_id'] = $enc_id;
      if($this->login_user_type == "admin") 
      { 
        $data['page_title'] = 'IIBF - Send E-mail to Observer'; 

       
      }       
      			
      $id = url_decode($enc_id);
			if(isset($_POST) && count($_POST) > 0)
			{ 
        
				if($_POST['mail_subject']!='' && $_POST['mail_content']!='') {
          $this->db->where("id",$id);
          $application_data = $this->master_model->getRecords('macroresearch_applications', array('is_deleted' => '0'));

          $this->db->where("admin_id",$this->login_admin_id);
          $pdcloggedin = $this->master_model->getRecords('macroresearch_admin', array('is_deleted' => '0'));

          $info_arr  = array(
            'to'      => array($application_data[0]['email'],$pdcloggedin[0]['admin_username']),
            'from'    => 'logs@iibf.esdsconnect.com',
            'subject' => $_POST['mail_subject'],
            'message' => trim($_POST['mail_content']),
          );
          if ($this->Emailsending->mailsend($info_arr)) {
            $this->session->set_flashdata('success','Mail Sent to Observer Successfully');
          }
          
        }
        else {
          $this->session->set_flashdata('error','Mail not sent to Observer');
					
          
        }
        redirect(site_url('macroresearch/admin/application/'));
				}
      $data["enc_login_admin_id"] = url_encode($this->login_admin_id);
      
			$this->load->view('macroresearch/admin/send_mail_to_application', $data);
		}/******** END : CHANGE ADMIN PASSWORD ********/
    function get_exam_venues_ajax()
    {
			if(isset($_POST) && count($_POST) > 0 && $this->input->post('exam_code')!='')
			{
        $result['flag'] = "success";
        //to show cities
        $onchange_fun = "get_exam_date_ajax(this.value);validate_file('venue_code')";
				$html = '	<select class="form-control chosen-select ignore_required venue_code" name="venue_code" id="venue_code" required onchange="'.$onchange_fun.'">';
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $this->db->group_by('venue_code');
        $venue_data = $this->master_model->getRecords('macroresearch_venue_master', array('exam_code' => $exam_code, 'venue_delete' => '0'), 'id, venue_code,venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5', array('venue_name'=>'ASC'));
      
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
			if(isset($_POST) && count($_POST) > 0 && $this->input->post('venue_code')!='') 
			{
        $result['flag'] = "success";
        //to show cities
        $onchange_fun = "get_exam_time_ajax(this.value);validate_file('exam_date')";
				$html = '	<select class="form-control chosen-select ignore_required exam_date" name="exam_date" id="exam_date" required onchange="'.$onchange_fun.'">';
				$venue_code = $this->security->xss_clean($this->input->post('venue_code'));
        $this->db->group_by('exam_date');
        $exam_dates_data = $this->master_model->getRecords('macroresearch_venue_master', array('venue_code' => $venue_code, 'venue_delete' => '0'), 'id, exam_date', array('exam_date'=>'ASC'));

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
        $exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $exam_date = $this->security->xss_clean($this->input->post('exam_date'));
        $this->db->group_by('session_time');
        $exam_times_data = $this->master_model->getRecords('macroresearch_venue_master', array('exam_code' => $exam_code,'venue_code' => $venue_code,'exam_date' => $exam_date, 'venue_delete' => '0'), 'id, session_time', array('id'=>'ASC'));

       $i=1;
				if(count($exam_times_data) > 0)
				{
					$html .= '	<option value="">Select </option>';
					foreach($exam_times_data as $exam_time)
					{
						$html .= '	<option class="'.$i++.'" value="'.$exam_time['session_time'].'">'.$exam_time['session_time'].'</option>';
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
    
    function download_form_pdf() {
      if(isset($_GET['session_flash']) && $_GET['session_flash']==1)
      $this->session->set_flashdata('success','MACRO-RESEARCH Report details successfully');
        
    

      download_form_pdf_func($_REQUEST['pdf_file']);
    }
    /******** START : CHANGE APPLICATION PASSWORD ********/
    public function change_password($enc_id=0)
    {   
      $data['enc_id'] = $enc_id;
            
      if($enc_id == '0') 
      { 
        $this->session->set_flashdata('error','Error occurred. Try again later.');
        redirect(site_url('macroresearch/admin/application')); 
      }
      else
      {
        $id = url_decode($enc_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('macroresearch_applications', array('id' => $id, 'is_deleted' => '0'), "*");
        if(count($form_data) == 0) 
        { 
          $this->session->set_flashdata('error','Error occurred. Try again later.');
          redirect(site_url('macroresearch/admin/application'));
        }
        else
        {
          if(isset($_POST) && count($_POST) > 0)
          { 
            $this->form_validation->set_rules('password', 'password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));             
           
            if($this->form_validation->run())
            { 
              
              $posted_arr = json_encode($_POST);
              $dispName = $this->macroresearch_model->getLoggedInUserDetails($this->login_admin_id, 'admin');
              
              $add_data['password'] = $this->macroresearch_model->password_encryption($this->input->post('password'));
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_admin_id;            
              $this->master_model->updateRecord('macroresearch_applications', $add_data, array('id'=>$id));
                
              $this->macroresearch_model->insert_common_log('Admin : Application Password Updated', 'macroresearch_applications', $this->db->last_query(), $id,'password_action','The application password has successfully updated by the admin '.$dispName['disp_name'].'.', $posted_arr);
                  
              $this->session->set_flashdata('success','Application password updated successfully');              
              redirect(site_url('macroresearch/admin/application'));
            }
            else
            {
              $this->session->set_flashdata('error','Validation error occurred. Please try again.');
              redirect(site_url('macroresearch/admin/application'));
            }
          }	
          else
          {
            $this->session->set_flashdata('error','Invalid request.');
            redirect(site_url('macroresearch/admin/application'));
          }
        }      
      }
    }/******** END : CHANGE APPLICATION PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/
    public function check_old_password()
    { 
			if(isset($_POST) && $_POST['password'] != "")
			{
        $password = $this->security->xss_clean($this->input->post('password')); 
        $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
        
        if($enc_id != "" && $enc_id != '0') { $id = url_decode($enc_id); }
        else { $id = $enc_id; }

        $result_data = $this->master_model->getRecords('macroresearch_applications am', array('am.is_deleted' => '0', 'am.password' => $this->macroresearch_model->password_encryption($password), 'am.id' => $id), 'am.id');
        
        if(count($result_data) > 0) { echo 'false'; }
        else { echo 'true'; }
			}
      else
      {
        echo 'true';
      }
    }/******** END : VALIDATION FUNCTION TO CHECK OLD PASSWORD FOR CHANGE PASSWORD ********/

    function get_city_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $onchange_fun = "validate_file('application_city')";
				$html = '	<select class="form-control chosen-select ignore_required" name="application_city" id="application_city" required onchange="'.$onchange_fun.'">';
				$pdc_zone_id = $this->security->xss_clean($this->input->post('pdc_zone_id'));
        
        $city_data = $this->master_model->getRecords('city_master', array('pdc_zone_code' => $pdc_zone_id, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
				if(count($city_data) > 0)
				{
					$html .= '	<option value="">Select City</option>';
					foreach($city_data as $city)
					{
						$html .= '	<option value="'.$city['id'].'">'.$city['city_name'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select City</option>';
        }
				$html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }

    /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    public function validation_check_valid_pincode($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['application_pincode'] != "")
			{
        if($type == '1') 
        { 
          $application_pincode = $this->security->xss_clean($this->input->post('application_pincode')); 
          $selected_pdc_zone_code = $this->security->xss_clean($this->input->post('selected_pdc_zone_code'));
        }
        else 
        { 
          $application_pincode = $str; 
          $selected_pdc_zone_code = $type;
        }

        $this->db->where(" '".$application_pincode."' BETWEEN start_pin AND end_pin ");
        $result_data = $this->master_model->getRecords('pdc_zone_master', array('pdc_zone_code' => $selected_pdc_zone_code), 'id, pdc_zone_code, start_pin, end_pin');
              
        if(count($result_data) > 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['application_pincode'] != "")
        {
          $pin_length = strlen($_POST['application_pincode']);

          $err_msg = 'Please enter valid pincode as per selected city';
          if($pin_length != 6) { $err_msg = 'Please enter only 6 numbers in pincode'; }

          $this->form_validation->set_message('validation_check_valid_pincode',$err_msg);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK APPLICATION MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['mobile'] != "")
			{
        if($type == '1') 
        { 
          $mobile = $this->security->xss_clean($this->input->post('mobile')); 
          $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
          
          if($enc_id != "" && $enc_id != '0') { $id = url_decode($enc_id); }
          else { $id = $enc_id; }
        }
        else 
        { 
          $mobile = $str; 
          $enc_id = $type;
          $id = url_decode($enc_id);
        }

        //check if application mobile exist or not
        $result_data = $this->master_model->getRecords('macroresearch_applications am', array('am.is_deleted' => '0', 'am.mobile' => $mobile, 'am.id !=' => $id), 'am.id, am.mobile, am.email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['mobile'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist','The mobile number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK APPLICATION MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK APPLICATION EMAIL ID EXIST OR NOT ********/
    public function validation_check_email_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['email'] != "")
			{
        if($type == '1') 
        { 
          $email = strtolower($this->security->xss_clean($this->input->post('email'))); 
          $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
          
          if($enc_id != "" && $enc_id != '0') { $id = url_decode($enc_id); }
          else { $id = $enc_id; }
        }
        else 
        { 
          $email = strtolower($str); 
          $enc_id = $type;
          $id = url_decode($enc_id);
        }

        //check if application mobile exist or not
        $result_data = $this->master_model->getRecords('macroresearch_applications am', array('am.is_deleted' => '0', 'am.email' => $email, 'am.id !=' => $id), 'am.id, am.mobile, am.email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['email'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist','The email id is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK APPLICATION MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->macroresearch_model->fun_restrict_input($str, $type); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_restrict_input', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

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
          $result = $this->macroresearch_model->fun_validate_password($str); 
          if($result['flag'] == 'success') { return true; }
          else
          {
            $this->form_validation->set_message('fun_validate_password', $result['response']);
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
		function validation_check_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'Please enter same password and confirm password';
			if(isset($_POST) && $_POST['confirm_password'] != "")
			{
        $password = $this->security->xss_clean($this->input->post('password'));
        if($type == '1') { $confirm_password = $this->security->xss_clean($this->input->post('confirm_password')); }
        else if($type == '0') { $confirm_password = $str; }   
        
        if($password == $confirm_password)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_password'] != "")
        {
          $this->form_validation->set_message('validation_check_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK APPLICATION GST NUMBER EXIST OR NOT ********/
    public function validation_check_gst_no_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['gst_no'] != "")
			{
        if($type == '1') 
        { 
          $gst_no = $this->security->xss_clean($this->input->post('gst_no')); 
          $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
          
          if($enc_id != "" && $enc_id != '0') { $id = url_decode($enc_id); }
          else { $centre_id = $enc_id; }
        }
        else 
        { 
          $gst_no = $str; 
          $enc_id = $type;
          $id = url_decode($enc_id);
        }

        $application_data = $this->master_model->getRecords('macroresearch_applications', array('is_deleted' => '0', 'gst_no' => $gst_no, 'id !=' => $id), 'id, gst_no, is_active');
      
        if(count($application_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['gst_no'] != "")
        {
          $this->form_validation->set_message('validation_check_gst_no_exist','The gst number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK APPLICATION GST NUMBER EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID GST NUMBER ********/
    function fun_validate_gst_no($str) // Custom callback function for check valid GST number
    {
      if($str != '')
      {
        $result = $this->macroresearch_model->fun_validate_gst_no($str); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_validate_gst_no', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID GST NUMBER ********/

    /******** START : APPLICATION STATUS CHANGE ********/ 
    public function change_application_status() 
    {      
      $flag = "error";
      $response = '';
      if(isset($_POST) && $_POST['enc_id'] != "")
      { 
        $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
        $status_value = $this->security->xss_clean($this->input->post('status_value')); 
        $role_id = $this->security->xss_clean($this->input->post('role_id')); 
        $center_code = $this->security->xss_clean($this->input->post('center_code')); 
        $exam_code = $this->security->xss_clean($this->input->post('exam_code')); 
        $id = url_decode($enc_id);        
        $this->db->where("id",$id);
        $application_data = $this->master_model->getRecords('macroresearch_applications', array('is_deleted' => '0'));
        
        $selected_center= $this->master_model->getRecords('macroresearch_center_master', array('center_delete' => '0','center_code '=>$this->input->post('center_code'),'exam_name'=>$this->input->post('exam_code') ), 'id, center_code ,  center_name', array('center_name'=>'ASC'));

        if(count($application_data) > 0)
        {
          $this->db->where("admin_id",$this->login_admin_id);
          $pdcloggedin = $this->master_model->getRecords('macroresearch_admin', array('is_deleted' => '0'));

          if($status_value!='' && $role_id!='' && $exam_code!='' && $center_code!='')
          {
            $add_logdata['updated_by'] = $pdcloggedin[0]['admin_id'];     
            $add_logdata['updated_on'] = date('Y-m-d H:i:s');
            $add_logdata['role_id'] = $role_id;
            $add_logdata['exam_code'] = $exam_code;
            $add_logdata['center_code'] = $center_code;
            $add_logdata['application_id'] = $id;
            $add_logdata['center_name'] = $selected_center[0]['center_name'];
            $this->master_model->insertRecord('macroresearch_application_exam',$add_logdata);
            
            $update_data["is_active"] = $status_value;
            $update_data["role_id"] = $role_id;
            $update_data["exam_code"] = $exam_code;
            $update_data["center_code"] = $center_code;
            $update_data['center_name'] = $selected_center[0]['center_name'];

            if($status_value == '0')
            {
              $response = 'The application details has been successfully saved!..';  
              $this->session->set_flashdata('application_status_success',$response);
              $this->macroresearch_model->insert_common_log('Application : Deactivated', 'macroresearch_applications', $this->db->last_query(), $id,'application_action','The application has been successfully deactivated by the admin', json_encode($update_data)); 
            }
            else if($status_value == '1')
            {
              $response = 'The application details has been successfully saved!..';  
              $this->session->set_flashdata('application_status_success',$response);
              $this->macroresearch_model->insert_common_log('Application : Activated', 'macroresearch_applications', $this->db->last_query(), $id,'application_action','The application has been successfully activated by the admin.', json_encode($update_data));

              if($application_data[0]['activation_mail_sent']==0) {
                $update_data["activation_date"] = date('Y-m-d');
                    $emailerstr = $this->master_model->getRecords('emailer', array(
                      'emailer_name' => 'macroresearch_application_activation',
                  ));
                  if (count($emailerstr) > 0) {                   
                    

                    $newstring = str_replace("#application#", "" . $application_data[0]['application_name'] . "", $emailerstr[0]['emailer_text']);
                    $newstring = str_replace("#username#", "" . $application_data[0]['application_code'] . "", $newstring);
                    $newstring = str_replace("#url#", "" . 'iibf.org.in' . "", $newstring);
                    $final_str = str_replace("#password#", "" . $this->macroresearch_model->password_decryption($application_data[0]['password'] ). "", $newstring);
                    $info_arr  = array(
                        'to'      => array($application_data[0]['email'],$pdcloggedin[0]['admin_username']),
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'] ,
                        'message' => $final_str,
                    );
                    if ($this->Emailsending->mailsend($info_arr)) {
                        
                      $update_data["activation_mail_sent"] = 1;

                      $this->macroresearch_model->insert_common_log('Application : Activated mail sent', 'macroresearch_applications', $final_str, $id,'application_action','The application has been successfully activated by the admin.', json_encode($info_arr));
                       
                    } 
                  }
              }
            }
            
            $this->db->where("id",$id);
            $this->db->update("macroresearch_applications",$update_data); 
            $flag = "success";
          } 
          else {
            $flag = "error";

            $this->session->set_flashdata('error','Please select all fields');
          }
          
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $response;
      $result['is_active'] = $status_value;
      echo json_encode($result);  
    } 
    /******** START : APPLICATION STATUS CHANGE ********/
   
 } 
?>  