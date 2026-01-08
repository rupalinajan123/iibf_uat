<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public $UserID;
	public $UserData;
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('vendor_admin')) {
			redirect('vendors/Login');
		}	
		$this->delete_old_files_from_server();
		$this->UserData = $this->session->userdata('vendor_admin');
		$this->load->model('UserModel');
		$this->UserID = $this->UserData['id'];
	}

	public function index()
	{		 
		$data['title'] = '';  
		$this->load->view('vendors/admin/dashboard', $data);	
	} 

	public function get_vendor_registration(){
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
            $searchQuery .= " AND DATE(a.registration_date) >= '".$from_date."'";
        }
        else if($to_date != ''){
           $searchQuery .= " AND DATE(a.registration_date) <= '".$to_date."'";
        }
        else*/ 
        if($from_date != '' && $to_date != ''){
            $searchQuery .= " AND DATE(a.registration_date) >= '".$from_date."' AND DATE(a.registration_date) <= '".$to_date."'";
        }

        if($search_input_text != ""){
        	$searchValue = $search_input_text;
        }

        if($searchValue != '')
      	{
        	$searchValue = str_replace("'",'"',$searchValue);
          	$searchQuery .= " AND (a.full_name like '%".$searchValue."%' or a.pan_no like '%".$searchValue."%') ";
        }

        $select = $this->db->query("SELECT a.* 
            FROM vendor_registration a
            WHERE 1");

        ## Total number of records with filtering
        $records = $select->result_array();
        $total_records = count($records);


        ## Total number of records without filtering
        $select2 = "SELECT a.*
            FROM vendor_registration a 
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
                        <th>Vendor Name</th>  
                        <th>PAN No.</th>  
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
	                            <td>'.$agency['full_name'].'</td>  
	                            <td>'.$agency['pan_no'].'</td>  
	                            <td>'.$agency['registration_date'].'</td>  
	                        </tr>';

	                $filename = 'VENDOR_REG_'.date('Y-m-d').'.xls';
	              
	            }
	            else{
	                $data[] = array(
	                    "sr"=>$sr, 
	                    "full_name"=>$agency['full_name'],
	                    "pan_no"=>$agency['pan_no'],
	                    "registration_date"=>date("d-m-Y",strtotime($agency['registration_date'])),
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
			$columnHeader = "No." . "\t" . "Name" . "\t" . "Address" . "\t" . "State" . "\t" . "City" . "\t" . "Pincode" . "\t" . "Type of Person" . "\t" . "CIN" . "\t" . "Contact Person Name" . "\t" . "Designation" . "\t" . "Email ID" . "\t" . "Mobile No." . "\t" . "Website" . "\t" . "Telephone No." . "\t" . "Nature of Goods/ Services provided to IIBF" . "\t" . "Pan No." . "\t" . "GST No" . "\t" . "MSMED Registration No." . "\t" . "EPFO Registration No." . "\t" . "Vendor Name as per Bank" . "\t" . "Name of the Bank" . "\t" . "Branch Address" . "\t" . "Type of Account" . "\t" . "Bank Account No." . "\t" . "IFSC Code" . "\t" . "MICR Code of Branch" . "\t" . "Name of Authorized Person" . "\t" . "Designation" . "\t" . "Registration Date" . "\t";  
			  
			$setData = '';  
			  
			if ($agency_list) {  
			    $rowData = '';  
			    $i=1;
			    foreach ($agency_list as $key=>$value) {  
			        $value = '"' . $i . '"' . "\t".'"' . $value["full_name"] . '"' . "\t".'"' . $value["address"]. '"' . "\t" . $value["state"]."\t" . $value["city"]."\t" . $value["pin_code"]."\t" . $value["type_of_person"]."\t" . $value["company_cin"]."\t" . $value["contact_person_name"]."\t" . $value["designation"]."\t" . $value["email_id"]."\t" . $value["mobile_no"]."\t" . $value["website"]."\t" . $value["telephone_no"]."\t" . $value["nature_of_goods_services"]."\t" . $value["pan_no"]."\t" . $value["gst_no"]."\t" . $value["msmed_reg_no"]."\t" . $value["epfo_reg_no"]."\t" . $value["vendor_name_in_bank"]."\t" . $value["bank_name"]."\t" . $value["bank_branch_address"]."\t" . $value["account_type"]."\t" . $value["bank_account_no"]."\t" . $value["ifsc_code"]."\t" . $value["micr_code"]."\t" . $value["authorized_person_name"]."\t" . $value["authorized_person_designation"]."\t" . $value["registration_date"]."\t";  
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
				redirect(base_url('vendors/Dashboard'));
			}

            //header("Content-Disposition: attachment; filename=\"$filename\"");
            //header("Content-Type: application/vnd.ms-excel");
            /*$csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);*/
        }else if(isset($_POST['export_table_csv'])){
            //$csv.= '</table>';
            $filename = 'VENDOR_REG_'.date('Y-m-d').'.csv';
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

			$header = array("No.","Name","Address","State","City","Pincode","Type of Person","CIN","Contact Person Name","Designation","Email ID","Mobile No.","Website","Telephone No.","Nature of Goods/ Services provided to IIBF","Pan No.","GST No","MSMED Registration No.","EPFO Registration No.","Vendor Name as per Bank","Name of the Bank","Branch Address","Type of Account","Bank Account No.","IFSC Code","MICR Code of Branch","Name of Authorized Person","Designation","Registration Date");
			fputcsv($file, $header);
			//$agency_list = unset($agency_list["full_name"]);

			$agency_list = array_map(function($agency_list){
			    //unset($agency_list["id"]);
			    unset($agency_list["upload_pan_no"]);
			    unset($agency_list["upload_gst_no"]);
			    unset($agency_list["upload_msmed_reg_no"]);
			    unset($agency_list["upload_canceled_cheque"]); 
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
			  
			 /*$vendor_registration_merge_data = $this->db->query("Select upload_pan_no,upload_gst_no,upload_msmed_reg_no,upload_canceled_cheque From vendor_registration")->result_array();*/

			if (count($agency_list) > 0) {
				foreach ($agency_list as $final_key => $final_res) {
					if($final_res["upload_pan_no"]){
						$final_zip_arr[] = $final_res["upload_pan_no"];
						$final_zip_arr[] = $final_res["upload_gst_no"];
						$final_zip_arr[] = $final_res["upload_msmed_reg_no"];
						$final_zip_arr[] = $final_res["upload_canceled_cheque"];
					}
				}
			}
			//print_r($final_zip_arr);die;


			$zip_folder_path = 'uploads/vendor_registration/zip';
			$zip_name = 'vendors_files_'.date("YmdHis").rand().".zip";

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
							$path = "./uploads/vendor_registration/".$file;
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
					redirect(base_url('uploads/vendor_registration/zip/'.$zip_name));
				}else{
					redirect(base_url('vendors/Dashboard'));
				}
				
			}

			//redirect('vendors/dashboard');

			/*echo $file='./uploads/vendor_registration/zip/'.$zip_name;die;
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

    public function create_vendor_zip(){
		$final_zip_arr = array();  
			  
			 $vendor_registration_merge_data = $this->db->query("Select upload_pan_no,upload_gst_no,upload_msmed_reg_no,upload_canceled_cheque From vendor_registration")->result_array();

			if (count($vendor_registration_merge_data) > 0) {
				foreach ($vendor_registration_merge_data as $final_key => $final_res) {
					if($final_res["upload_pan_no"]){
						$final_zip_arr[] = $final_res["upload_pan_no"];
						$final_zip_arr[] = $final_res["upload_gst_no"];
						$final_zip_arr[] = $final_res["upload_msmed_reg_no"];
						$final_zip_arr[] = $final_res["upload_canceled_cheque"];
					}
				}
			}
			//print_r($final_zip_arr);die;

			$zip_folder_path = 'uploads/vendor_registration/zip';
			$zip_name = 'vendors_zip.zip';

			$zip = new ZipArchive;

			$log_file = $zip_name . ".txt";
			$fp = fopen('./' . $zip_folder_path . '/' . $log_file, 'a');
			fwrite($fp, "\n***** Started - " . date("Y-m-d H:i:s") . " ***** \n"); 
			if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) {
				$i = 1;
				if (count($final_zip_arr) > 0) {
					foreach ($final_zip_arr as $file) {
						if($file != ""){
							$path = "./uploads/vendor_registration/".$file;
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
	    $map = directory_map('./uploads/vendor_registration/zip/');
	    if (count($map) > 0) {
	      foreach ($map as $res) {
	        if ($res != 'index.php') {
	          if (strpos($res, date('YmdH')) !== false) {
	          } else {
	            if (strpos($res, date('YmdH', strtotime('-1 hour'))) !== false) {
	            } else {
	              @unlink('./uploads/vendor_registration/zip/' . $res);
	            }
	          }
	        }
	      }
	    }
	    //END : DELETE ALL PREVIOUS FILE BEFORE 2 HOURS
  	}  	
}