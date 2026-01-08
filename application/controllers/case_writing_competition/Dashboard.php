<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public $UserID;
	public $UserData;
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('case_study_comp_admin')) {
			redirect('case_writing_competition/Login');
		}	
		$this->delete_old_files_from_server();
		$this->UserData = $this->session->userdata('case_study_comp_admin');
		$this->load->model('UserModel');
		$this->UserID = $this->UserData['id'];
	}

	public function index()
	{		 
		$data['title'] = '';  
		$this->load->view('case_writing_competition/admin/dashboard', $data);	
	} 

	public function get_case_study_comp_registration(){
        //print_r($_POST); die();
        ## Read value
        $draw = @$_POST['draw'];
        $row = @$_POST['start'];
        $rowperpage = @$_POST['length']; // Rows display per page
        $columnIndex = @$_POST['order'][0]['column']; // Column index
        $columnName = @$_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = @$_POST['order'][0]['dir']; // asc or desc
        $searchValue = @$_POST['search']['value']; // Search VALUES
        //echo $searchValue;
        ## Search 
        $searchQuery = " ";
        $reg_id='';
        $whr = '';
        //echo "<pre>";print_r($searchValue);die;
        ## Custom Field value
        $from_date   = @$_POST['from_date'];
        $to_date = @$_POST['to_date'];
        $search_input_text = @$_POST['search_input_text'];
        $reg_id = @$_POST['reg_id'];

        if($reg_id != ""){
        	//echo $reg_id;die;
        	$searchQuery .= " AND a.id = ".$reg_id;
        }

        /*if($from_date != ''){
            $searchQuery .= " AND DATE(a.created_on) >= '".$from_date."'";
        }
        else if($to_date != ''){
           $searchQuery .= " AND DATE(a.created_on) <= '".$to_date."'";
        }
        else*/ 
        if($from_date != '' && $to_date != ''){
            $searchQuery .= " AND DATE(a.created_on) >= '".$from_date."' AND DATE(a.created_on) <= '".$to_date."'";
        }

        if($search_input_text != ""){
        	$searchValue = $search_input_text;
        }

        if($searchValue != '')
      	{
        	$searchValue = str_replace("'",'"',$searchValue);
          	$searchQuery .= " AND (a.case_study_title like '%".$searchValue."%' or a.mobile_no like '%".$searchValue."%') ";
        }

        $select = $this->db->query("SELECT a.application_no,a.case_study_title,a.case_study_area,b.level AS case_study_level, c.desc AS case_study_level_desc,a.name_of_author,a.designation,a.employer,a.mobile_no,a.email_id,a.qualifications,a.other_info,a.upload_case_study_doc,a.place_name,a.upload_signature,a.is_deleted,a.submit_date,a.created_on 
            FROM case_study_comp_registration a LEFT JOIN case_study_comp_level b ON b.id = a.case_study_level_id LEFT JOIN case_study_comp_level_desc c ON c.id = a.case_study_level_desc_id
            WHERE 1");

        ## Total number of records with filtering
        $records = $select->result_array();
        $total_records = count($records);


        ## Total number of records without filtering
        $select2 = "SELECT a.application_no,a.case_study_title,a.case_study_area,b.level AS case_study_level, c.desc AS case_study_level_desc,a.name_of_author,a.designation,a.employer,a.mobile_no,a.email_id,a.qualifications,a.other_info,a.upload_case_study_doc,a.place_name,a.upload_signature,a.is_deleted,a.submit_date,a.created_on
            FROM case_study_comp_registration a LEFT JOIN case_study_comp_level b ON b.id = a.case_study_level_id LEFT JOIN case_study_comp_level_desc c ON c.id = a.case_study_level_desc_id
            WHERE 1".$searchQuery;

        $select3 = $this->db->query($select2);

        if(isset($_POST['export_table']) || isset($_POST['export_table_csv']) || isset($_POST['export_zip']) ){
            $agencyQuery = $select2;
        }
        else{
            //$Query = $select2." limit ".$row.",".$rowperpage;
            ## Fetch records
            $agencyQuery = $select2." ORDER BY ". $columnIndex."   ".$columnSortOrder."  LIMIT ".$row." ,".$rowperpage." ";
        }
       
        //echo $agencyQuery;die();

        $agency_query = $this->db->query($agencyQuery);
        $agency_list = $agency_query->result_array();

        $totalRecordwithFilter = count($records);
        //echo $this->db->last_query();die();
       
        $data = array();

        $csv='<style>
                table, th, td {
                  border: 1px solid black;
                  border-collapse: collapse;
                }
            </style>';

        if(isset($_POST['export_table']) || isset($_POST['export_table_csv']) || isset($_POST['export_zip']) ){
           
            $csv.= '<table class="table">  
                    <tr>  
                        <th>Sr.</th>  
                        <th>Application No.</th>  
                        <th>Case Study Title</th>  
                        <th>Mobile No.</th>  
                        <th>Registration Date</th>  
                    </tr>';
        }

        $sr = $_POST['start'];

        //print_r($agency_list); die;
        if($agency_list)
        { 
	        foreach ($agency_list as $key => $agency) {
	            //$sr = $key + 1;
	        	$sr++;
	            $status = '';
	            /*if($agency['batch_status']=="In Review"){ 

	                $status='<span class="statusi">In Review</span>';
	            }*/ 

	            if(isset($_POST['export_table']) || isset($_POST['export_table_csv']) || isset($_POST['export_zip'])){

	                $csv.= '<tr>  
	                            <td>'.$sr.'</td> 
	                            <td>'.$agency['application_no'].'</td>  
	                            <td>'.$agency['case_study_title'].'</td>  
	                            <td>'.$agency['mobile_no'].'</td>  
	                            <td>'.$agency['created_on'].'</td>  
	                        </tr>';

	                $filename = 'CASE_STUDY_COMP_REG_'.date('Y-m-d').'.xls';
	              
	            }
	            else{
	                $data[] = array(
	                    "sr"=>$sr, 
	                    "application_no"=>$agency['application_no'],
	                    "case_study_title"=>$agency['case_study_title'],
	                    "mobile_no"=>$agency['mobile_no'],
	                    "created_on"=>date("d-m-Y",strtotime($agency['created_on'])),
	                    "excel"=> '<a href="javascript:void(0);" onclick="download_excel('.$agency['id'].');"><span><i class="fa fa-file-excel-o"></i></span></a>',
	                    /*"csv"=> '<a href="javascript:void(0);" onclick="download_csv('.$agency['id'].');"><span><i class="fa fa-file-o"></i></span></a>',*/
	                    "zip"=> '<a href="javascript:void(0);" onclick="download_zip('.$agency['id'].');"><span><i class="fa fa-file-zip-o"></i></span></a>'
	                );
	            }
	        }
	    }


        if(isset($_POST['export_table'])){
            //$csv.= '</table>';
            /*header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");	 
			$show_coloumn = false;
			if(!empty($agency_list)) {
			  foreach($agency_list as $record) {
				if(!$show_coloumn) {
				  // display field/column names in first row
					$record = str_replace("_"," ",$record);
				  echo implode("\t", array_keys($record)) . "\n";
				  $show_coloumn = true;
				} 
				echo implode("\t", array_values($record)) . "\n";
			  }
			}
			exit;*/ 

			$columnHeader = '';   
			$columnHeader = "No." . "\t" . "Application Number" . "\t" . "Case Study Title" . "\t" . "Case Study Area" . "\t" . "Case Study Scheme" . "\t" . "Scheme Name" . "\t" . "Name of the Author" . "\t" . "Designation" . "\t" . "Employer" . "\t" . "Mobile No." . "\t" . "Email ID" . "\t" . "Qualifications" . "\t" . "Any other information" . "\t" . "Place" . "\t" . "Submit Date" . "\t" . "Registration Date" . "\t";  
			  
			$setData = '';  
			  
			if ($agency_list) {  
			    $rowData = '';  
			    $i=1;
			    foreach ($agency_list as $key=>$value) {  
			        $value = '"' . $i . '"' . "\t".'"' . $value["application_no"] . '"' . "\t".'"'. $value["case_study_title"] . '"' . "\t".'"' . $value["case_study_area"]. '"' . "\t" . $value["case_study_level"]."\t" . $value["case_study_level_desc"]."\t" . $value["name_of_author"]."\t" . $value["designation"]."\t" . $value["employer"]."\t" . $value["mobile_no"]."\t" . $value["email_id"]."\t" . $value["qualifications"]."\t" . $value["other_info"]."\t" . $value["place_name"]."\t" . $value["submit_date"]."\t" . $value["created_on"]."\t";  
			        $rowData .= $value. "\n";  
			    	$i++;
			    }  
			    $setData .= trim($rowData) . "\n";  
			}  
			  
			if (count($agency_list) > 0){  
			header("Content-type: application/octet-stream");  
			header("Content-Disposition: attachment; filename=\"$filename\"");  
			header("Pragma: no-cache");  
			header("Expires: 0");  
			  
			echo ucwords($columnHeader) . "\n" . $setData . "\n";  
			exit; 
			}
			else{
				redirect(base_url('case_writing_competition/Dashboard'));
			}

            //header("Content-Disposition: attachment; filename=\"$filename\"");
            //header("Content-Type: application/vnd.ms-excel");
            /*$csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);*/
        }else if(isset($_POST['export_table_csv'])){
            //$csv.= '</table>';
            $filename = 'CASE_STUDY_COMP_REG_'.date('Y-m-d').'.csv';
            //header('Content-Type: text/csv');
            //header("Content-Type: application/vnd.ms-excel");
			//header("Content-Disposition: attachment; filename=\"$filename\"");	

			 //$filename = 'users_'.date('Ymd').'.csv';
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: application/csv; "); 

			// get data
			//$usersData = $this->ExportModel->getUserDetails();

			// file creation
			$file = fopen('php://output', 'w');

			$header = array("No.","Application Number","Case Study Title","Case Study Area","Case Study Scheme","Scheme Name","Name of the Author","Designation","Employer","Mobile No.","Email ID","Qualifications","Any other information","Place","Submit Date","Registration Date");
			fputcsv($file, $header);
			//$agency_list = unset($agency_list["case_study_title"]);

			$agency_list = array_map(function($agency_list){
			    //unset($agency_list["id"]);
			    unset($agency_list["upload_case_study_doc"]);
			    unset($agency_list["upload_signature"]);  
			    unset($agency_list["is_active"]);
			    return $agency_list;
			}, $agency_list);

			if (count($agency_list) > 0){ 
				foreach ($agency_list as $key=>$line){
					//print_r($line["state"]);
					 fputcsv($file,$line);
				}
			}

			fclose($file);
			exit;

			/*$show_coloumn = false;
			if(!empty($agency_list)) {
			  foreach($agency_list as $record) {
				if(!$show_coloumn) {
				  // display field/column names in first row
				  echo implode("\t", array_keys($record)) . "\n";
				  $show_coloumn = true;
				}
				echo implode("\t", array_values($record)) . "\n";
			  }
			}
			exit;*/  
            //header("Content-Disposition: attachment; filename=\"$filename\"");
            //header("Content-Type: application/vnd.ms-excel");
            /*$csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);*/
        }else if(isset($_POST['export_zip'])){
        	$final_zip_arr = array();  
			  
			 /*$case_study_comp_registration_merge_data = $this->db->query("Select upload_case_study_doc,upload_signature,upload_msmed_reg_no,upload_canceled_cheque From case_study_comp_registration")->result_array();*/

			if (count($agency_list) > 0) {
				foreach ($agency_list as $final_key => $final_res) {
					if($final_res["upload_case_study_doc"]){
						$final_zip_arr[] = $final_res["upload_case_study_doc"];
						$final_zip_arr[] = $final_res["upload_signature"]; 
					}
				}
			}
			//print_r($final_zip_arr);die;


			$zip_folder_path = 'uploads/case_study_comp_registration/zip';
			$zip_name = 'case_writing_competition_files_'.date("YmdHis").rand().".zip";

			if (file_exists($zip_folder_path."/".$zip_name)) {
				@unlink($zip_folder_path."/".$zip_name); 
			}
			

			$zip = new ZipArchive;

			$log_file = $zip_name . ".txt";
			$fp = fopen('./' . $zip_folder_path . '/' . $log_file, 'a');
			fwrite($fp, "\n***** Started - " . date("Y-m-d H:i:s") . " ***** \n"); 
			if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) {
				$i = 1;
				if (count($final_zip_arr) > 0) {
					foreach ($final_zip_arr as $file) {
						if($file != ""){
							$path = "./uploads/case_study_comp_registration/".$file;
							if (file_exists($path)) {
								$filename_parts = explode('/', $path);  // Split the filename up by the '/' character
								$zip->addFile($path, end($filename_parts));

								fwrite($fp, "\n***** " . $i . ". File Added " . $path . ' >> ' . date("Y-m-d H:i:s") . " \n");
								$i++;
							}
						} 
					}
				}
				$zip->close(); 
				if (count($final_zip_arr) > 0){
					redirect(base_url('uploads/case_study_comp_registration/zip/'.$zip_name));
				}else{
					redirect(base_url('case_writing_competition/Dashboard'));
				}
				
			}

			//redirect('case_writing_competition/dashboard');

			/*echo $file='./uploads/case_study_comp_registration/zip/'.$zip_name;die;
			header("Content-type: application/zip;\n");
			header("Content-Transfer-Encoding: Binary");
			header("Content-length: ".filesize($file).";\n");
			header("Content-disposition: attachment; filename=\"".basename($file)."\"");
			readfile("$file");
			exit();*/ 
        }else if($reg_id != ""){
        	echo $reg_id;die;
        	header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");	 
			$show_coloumn = false;
			if(!empty($agency_list)) {
			  foreach($agency_list as $record) {
				if(!$show_coloumn) {
				  // display field/column names in first row
				  echo implode("\t", array_keys($record)) . "\n";
				  $show_coloumn = true;
				}
				echo implode("\t", array_values($record)) . "\n";
			  }
			}
			exit; 
        }else{
            $output = array(
              "draw" => intval($draw),
              "iTotalRecords" => $total_records,
              "iTotalDisplayRecords" => $totalRecordwithFilter,
              "aaData" => $data
            );

            echo json_encode($output);
        }
    }

    public function create_case_study_comp_registration_zip(){
		$final_zip_arr = array();  
			  
			 $case_study_comp_registration_merge_data = $this->db->query("Select upload_case_study_doc,upload_signature From case_study_comp_registration")->result_array();

			if (count($case_study_comp_registration_merge_data) > 0) {
				foreach ($case_study_comp_registration_merge_data as $final_key => $final_res) {
					if($final_res["upload_case_study_doc"]){
						$final_zip_arr[] = $final_res["upload_case_study_doc"];
						$final_zip_arr[] = $final_res["upload_signature"]; 
					}
				}
			}
			//print_r($final_zip_arr);die;

			$zip_folder_path = 'uploads/case_study_comp_registration/zip';
			$zip_name = 'case_writing_competition_zip.zip';

			$zip = new ZipArchive;

			$log_file = $zip_name . ".txt";
			$fp = fopen('./' . $zip_folder_path . '/' . $log_file, 'a');
			fwrite($fp, "\n***** Started - " . date("Y-m-d H:i:s") . " ***** \n"); 
			if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) {
				$i = 1;
				if (count($final_zip_arr) > 0) {
					foreach ($final_zip_arr as $file) {
						if($file != ""){
							$path = "./uploads/case_study_comp_registration/".$file;
							if (file_exists($path)) {
								$filename_parts = explode('/', $path);  // Split the filename up by the '/' character
								$zip->addFile($path, end($filename_parts));

								fwrite($fp, "\n***** " . $i . ". File Added " . $path . ' >> ' . date("Y-m-d H:i:s") . " \n");
								$i++;
							}
						} 
					}
				}
				$zip->close(); 
			}
			exit;
	}

    function delete_old_files_from_server()
  	{
	    //START : DELETE ALL PREVIOUS FILE BEFORE 2 HOURS
	    $this->load->helper('directory');
	    $map = directory_map('./uploads/case_study_comp_registration/zip/');
	    if (count($map) > 0) {
	      foreach ($map as $res) {
	        if ($res != 'index.php') {
	          if (strpos($res, date('YmdH')) !== false) {
	          } else {
	            if (strpos($res, date('YmdH', strtotime('-1 hour'))) !== false) {
	            } else {
	              @unlink('./uploads/case_study_comp_registration/zip/' . $res);
	            }
	          }
	        }
	      }
	    }
	    //END : DELETE ALL PREVIOUS FILE BEFORE 2 HOURS
  	}  	
}