<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Automation extends CI_Controller {

	public function __construct(){
		parent::__construct();
			
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('csc_exam_api_helper');

		set_time_limit(0);
		ini_set('memory_limit', '20000M');

		require_once APPPATH . "/third_party/PHPExcel.php";
	}

	public function index()
	{

		$data['typeArr'] = $typeArr = array(array('type' => 'Exam', 'id' => 1, 'name' => 'DRA'),
											array('type' => 'Result','id' => 2, 'name' => 'DRA'),
											array('type' => 'Exam', 'id' => 3, 'name' => 'RPE'),
											array('type' => 'Result', 'id' => 4, 'name' => 'RPE'),
										    array('type' => 'Exam','id' => 5, 'name' => 'Diploma Certification'),
										    array('type' => 'Result', 'id' => 6, 'name' => 'Diploma Certification'),
										    array('type' => 'Exam','id' => 7, 'name' => 'GARP'),
											array('type' => 'Exam','id' => 8, 'name' => 'JAIIB'),
											array('type' => 'Result','id' => 9, 'name' => 'JAIIB'),
											array('type' => 'Exam','id' => 10, 'name' => 'CAIIB'),
											array('type' => 'Result','id' => 11, 'name' => 'CAIIB'),
											array('type' => 'Exam','id' => 12, 'name' => 'Bulk'),
											array('type' => 'Exam','id' => 13, 'name' => 'BCBF'),
											array('type' => 'Result','id' => 14, 'name' => 'BCBF'),
											array('type' => 'Result','id' => 15, 'name' => 'Blended'),
											array('type' => 'Training','id' => 16, 'name' => 'Blended'),
											array('type' => 'Training','id' => 17, 'name' => 'Contact Classes'),
											array('type' => 'Exam','id' => 18, 'name' => 'CISI'),
											array('type' => 'Exam','id' => 19, 'name' => 'DISA'),
											array('type' => 'Exam','id' => 20, 'name' => 'SPEL'),
											array('type' => 'Exam','id' => 21, 'name' => 'SOB'));

		$this->load->view('automation',$data);		
	}

	public function start_process()
	{
		$type = $_POST['type'];
		//echo $type; die;
		
	
        if(!empty($_FILES['txtfiles']['name']) && count(array_filter($_FILES['txtfiles']['name'])) > 0)
        { 
        	//echo '---'; die;
		    $filesCount = count($_FILES['txtfiles']['name']); 

		    if($type == 1){ //DRA Exam
        		$uploadPath = 'uploads/Automation/dra_exam_live/'; 
        	}
        	else if($type == 2){ //DRA Result
        		$uploadPath = 'uploads/Automation/dra_result_live/'; 
        	}
        	else if($type == 3){ //RPE Exam
        		$uploadPath = 'uploads/Automation/rpe_exam_live/'; 
        	}
        	else if($type == 4){ //RPE Result
        		$uploadPath = 'uploads/Automation/rpe_result_live/'; 
        	}
        	else if($type == 5){ //Diploma Certification Exam
        		$uploadPath = 'uploads/Automation/dipcert_exam_live/'; 
        	}
        	else if($type == 6){ //Diploma Certification Result
        		$uploadPath = 'uploads/Automation/dipcert_result_live/'; 
        	}
        	else if($type == 7){ //GARP Exam
        		$uploadPath = 'uploads/Automation/garp_exam_live/'; 
        	}
        	else if($type == 8){ //JAIIB Exam
        		$uploadPath = 'uploads/Automation/jaiib_exam_live/'; 
        	}
        	else if($type == 9){ //JAIIB Result
        		$uploadPath = 'uploads/Automation/jaiib_result_live/'; 
        	}
        	else if($type == 10){ //CAIIB Exam
        		$uploadPath = 'uploads/Automation/caiib_exam_live/'; 
        	}
        	else if($type == 11){ //CAIIB Result
        		$uploadPath = 'uploads/Automation/caiib_result_live/'; 
        	}
        	else if($type == 12){ //Bulk Exam
        		$uploadPath = 'uploads/Automation/bulk_exam_live/'; 
        	}
        	else if($type == 13){ //BCBF Exam
        		$uploadPath = 'uploads/Automation/bcbf_exam_live/'; 
        	}
        	else if($type == 14){ //BCBF Result
        		$uploadPath = 'uploads/Automation/bcbf_result_live/'; 
        	}
        	else if($type == 15){ //Blended Result
        		$uploadPath = 'uploads/Automation/blended_result_live/'; 
        	}
        	else if($type == 16){ //Blended Training
        		$uploadPath = 'uploads/Automation/blended_training_live/'; 
        	}
			else if($type == 17){ //Contact Classes Training
        		$uploadPath = 'uploads/Automation/contact_classes_training_live/'; 
        	}	  
        	else if($type == 18){ //CISI Exam
        		$uploadPath = 'uploads/Automation/CISI_exam_live/'; 
        	}
        	else if($type == 19){ //DISA Exam
        		$uploadPath = 'uploads/Automation/DISA_exam_live/'; 
        	}
			else if($type == 20){ //SPEL Exam
        		$uploadPath = 'uploads/Automation/SPEL_exam_live/'; 
        	}	 
        	else if($type == 21){ //SOB Exam
        		$uploadPath = 'uploads/Automation/SOB_exam_live/'; 
        	} 

        	if (!is_dir( $uploadPath)) {
				mkdir( $uploadPath );       
			} 
		
			$allfiles = glob($uploadPath.'/*'); //get all file names

			foreach($allfiles as $file){
			    if(is_file($file))
			    unlink($file); //delete file
			}
			//echo $filesCount; die();

	        for($i = 0; $i < $filesCount; $i++){ 
	            $_FILES['file']['name']     = $_FILES['txtfiles']['name'][$i]; 
	            $_FILES['file']['type']     = $_FILES['txtfiles']['type'][$i]; 
	            $_FILES['file']['tmp_name'] = $_FILES['txtfiles']['tmp_name'][$i]; 
	            $_FILES['file']['error']     = $_FILES['txtfiles']['error'][$i]; 
	            $_FILES['file']['size']     = $_FILES['txtfiles']['size'][$i]; 
	            //echo '</br>'.$_FILES['rpe_exam_files']['name'][$i];      	  
	            // File upload configuration 
	            
	            $config['upload_path'] = $uploadPath; 
                $config['allowed_types'] = 'txt|xls|xlsx'; 

	            $this->load->library('upload', $config); 
                $this->upload->initialize($config); 
                 
                // Upload file to server 
                if($this->upload->do_upload('file')){ 
                    // Uploaded file data 
                    $fileData = $this->upload->data(); 
                    $uploadData[$i]['file_name'] = $fileData['file_name']; 
                    $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 
                    $uploaded_id = 1;
                    //echo '</br>'.$fileData['file_name'];
                 
                }else{ 
                	//echo $this->upload->display_errors(); 
                    $errorUploadType .= $_FILES['file']['name'].' | ';  
                    $uploaded_id = 0;
                } 
	        } 
		    $uploaded_id = 1;              
	    }else{ 
	        $statusMsg = 'Please select only Text and Excel files to upload.'; 
	    }
		//echo $uploaded_id; die;
	    if($uploaded_id > 0){ 
    		if($type == 1){ //DRA Exam
        		$msg = $this->insertDRA_exam();
        	}
        	else if($type == 2){ //DRA Result
        		$msg = $this->insertDRA_result();
        	}
        	else if($type == 3){ //RPE Exam
        		$msg = $this->insertRPE_exam();
        	}
        	else if($type == 4){ //RPE Result
        		$msg = $this->insertRPE_result();
        	}
        	else if($type == 5){ //Diploma Certification Exam
        		$msg = $this->insertDipcert_exam();
        	}
        	else if($type == 6){ //Diploma Certificatio Result
        		$msg = $this->insertDipcert_result();
        	}
        	else if($type == 7){ //GARP Exam
        		$msg = $this->insertGARP_exam();
        	}
        	else if($type == 8){ //JAIIB Exam
        		$msg = $this->insertJAIIB_exam(0,100);
        	}
        	else if($type == 9){ //JAIIB Result
        		echo 'jaiib'; die;
        		$msg = $this->insertJAIIB_result();
        	}
        	else if($type == 10){ //CAIIB Exam
        		$msg = $this->insertCAIIB_exam();
        	}
        	else if($type == 11){ //CAIIB Result
        		$msg = $this->insertCAIIB_result();
        	}
        	else if($type == 12){ //Bulk Result
        		$msg = $this->insertBulk_exam();
        	}
        	else if($type == 13){ //BCBF Result
        		$msg = $this->insertBCBF_exam();
        	}
        	else if($type == 14){ //BCBF Result
        		$msg = $this->insertBCBF_result();
        	}
        	else if($type == 15){ //Blended result
        		$msg = $this->insertBlended_result();
        	}
        	else if($type == 16){ //Blended Training
        		$msg = $this->insertBlended_training();
        	}
        	else if($type == 17){ //Contact Classes Training
        		$msg = $this->insertContactclasses_training();
        	}
        	else if($type == 18){ //CISI Exam
        		$msg = $this->insertCISI_exam();
        	}
        	else if($type == 19){ //DISA Exam
        		$msg = $this->insertDISA_exam();
        	}
        	else if($type == 20){ //SPEL Exam
        		$msg = $this->insertSPEL_exam();
        	}
        	else if($type == 21){ //SOB Exam
        		$msg = $this->insertSOB_exam();
        	}
		}

        echo $msg;
	}

	public function insertDRA_exam(){

		$files = glob('uploads/Automation/dra_exam_live/*'); //get all file names

		foreach($files as $file){

			if(is_file($file)){
			
				//$center_sql = '';
				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO dra_center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){

			        		$this->db->query('TRUNCATE TABLE  dra_center_master;');

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){
						    			
								    	$final_value = str_replace('|', "','", $value);
								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}

					
				}
				
				
				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO dra_eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, institute_code, training_from, training_to) VALUES";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	if(!empty($arr)){

			        		$this->db->query('TRUNCATE TABLE  dra_eligible_master;');

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if($key > 0){
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
							    		//echo print_r($final_valueArr1);
							    		$final_valueArr1[12] = date("Y-m-d", strtotime($final_valueArr1[12])); 
							    		$final_valueArr1[13] = date("Y-m-d", strtotime($final_valueArr1[13])); 

							    		unset($final_valueArr1[14]);
								    	unset($final_valueArr1[15]);

								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	$final_value1 = rtrim($final_value1,"'");
							    		$elg_sql.= "('".$final_value1."'),".PHP_EOL;
							    	}
						    	}
			
						    }
						}
					}
					
				}

				if(strpos($file,"FEE")!=false){

					$fee_insert = "INSERT INTO dra_fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

					if (($fee_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){

			        		$this->db->query('TRUNCATE TABLE dra_fee_master;');

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if($key > 0){
						    			if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){

						    				$final_value = str_replace("B1_1","B1",$value);
									    	$final_value = str_replace('|', "','", $final_value);
									    	//echo $final_value;
									    	$fee_sql.= "('".$final_value."'),";
									    }
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO dra_medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){

			        		$this->db->query('TRUNCATE TABLE  dra_medium_master;');

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if($key > 0){
								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO dra_misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){

			        		$this->db->query('TRUNCATE TABLE  dra_misc_master;');

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if($key > 0){
								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"SUBJECT")!=false){
					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

								    	$examArr = explode('|', $value);

								    	$exam_period = $examArr[1];
								    	$exam_date = $examArr[7];

								    	$uploadData = array('exam_period' => $examArr[1],
															'exam_date'  => $examArr[7]
															);

										$updated_id = $this->master_model->updateRecord('dra_subject_master',$uploadData, array('exam_code' => $examArr[0])); 

										/*$updated_id = $this->master_model->updateRecord('dra_subject_master',$uploadData, array('exam_code' => 57)); */
										//echo '</br>'.$this->db->last_query();
									}
							    }
						    }
						}
					}
				}

				if(strpos($file,"EXAM_ACTIVATE")!=false){
					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        
			        	foreach ($arr as $key => $value) {
					    	if(!empty($value)){
					    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){
							    	$examArr = explode('|', $value);

							    	$uploadData = array( 'exam_period' => $examArr[1],
														 'exam_from_date'  => $examArr[2],
														 'exam_to_date'  => $examArr[3]
														);

									$updated_id = $this->master_model->updateRecord('dra_exam_activation_master',$uploadData, array('exam_code' => $examArr[0])); 
								}
						    }
					    }
					}
				}

			}
			
		}

		if($center_sql != ''){
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			$elg_insert.=rtrim($elg_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($fee_sql != ''){
			$fee_insert.=rtrim($fee_sql,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert);
		}

		if($medium_sql != ''){
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId4 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($misc_insert);
		}

		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0){
			$msg = 'DRA Exam Live Successfully...';
			return $msg;
		}
		//echo $insert1.'<br/>'.$insert2;	
	}

	public function insertDRA_result(){
		
		$allfiles = glob('uploads/Automation/dra_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){

					$marks_insert = "INSERT INTO dra_marks(regnumber, exam_code, exam_period, center_code, center_name, part_no, subject_id, marks, status) VALUES";

					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

			        		$this->db->query('TRUNCATE TABLE dra_marks;');

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
							    	$final_value = str_replace('|', "','", $value);
							    	//echo $final_value;
							    	$final_valueArr = explode(',', $final_value);
							    	unset($final_valueArr[9]);
							    	
							    	$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."),";

							    }
						    }
						}
					}
					
				}

				if(strpos($file,"Member_List")!=false){

					$member_insert = "INSERT INTO dra_memdetails(regnumber, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, inst_code, institute_name, institute_secname) VALUES";

					if (($handle1 = fopen($file, "r")) !== FALSE) {
						$readFile1 =  fread($handle1,filesize($file));
						$arr1 = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile1)
			        	);
			        	$sql = '';
			        	if(!empty($arr1)){

			        		$this->db->query('TRUNCATE TABLE  dra_memdetails;');

						    foreach ($arr1 as $key => $value) {
						    	if(!empty($value)){
						    		//echo $value;
						    		$final_value1 = str_replace(',', ' ', $value);
						    		$final_value1 = str_replace("'", ' ', $final_value1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_valueArr1 = explode(',', $final_value1);
						    		//echo '</br>'.count($final_valueArr1);
							    	unset($final_valueArr1[22]);
							    	//echo '</br>'.count($final_valueArr1);

							    	$final_value1 = implode($final_valueArr1, ',');
							    	$final_value1 = rtrim($final_value1,"'");
						    		$member_sql.= "('".$final_value1."'),".PHP_EOL;
						    	}
			
						    }
						}
						
					}

				}

				if(strpos($file,"EXAM_LIST")!=false){
					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);
					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        
			        	foreach ($arr as $key => $value) {
					    	if(!empty($value)){
						    	$examArr = explode('|', $value);

						    	$uploadData = array(  'exam_conduct' => $examArr[6],
									'result_date'  => $examArr[7],
									'exam_period'  => $exp1[3]
								);

								$updated_id1 = $this->master_model->updateRecord('dra_result_exam',$uploadData,array('exam_code' => 45)); 

								$updated_id2 = $this->master_model->updateRecord('dra_result_exam',$uploadData,array('exam_code' => 57)); 

								$uploadData1 = array( 
									'exam_period'  => $exp1[3]
								);

								$updated_id3 = $this->master_model->updateRecord('dra_result_subject',$uploadData1,array('exam_code' => 45)); 

								$updated_id4 = $this->master_model->updateRecord('dra_result_subject',$uploadData1,array('exam_code' => 57)); 
						    }
					    }
						
					}
				}
			}
		}

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",").';';
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($insId1 > 0 || $insId2 > 0 || $updated_id1 > 0 || $updated_id1 > 0){
			$msg = 'DRA Result Live Successfully...';
			return $msg;
		}
	}
	
	public function insertRPE_exam(){
		

		$files = glob('uploads/Automation/rpe_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$centerArr = array();
		$elgArr = array();
		$feeArr1 = array();
		$feeArr2 = array();
		$mediumArr = array();
		$miscArr = array();
		$subjectArr = array();
		$activationArr = array();
		$validexamArr = array();

		$fromdateArr = array();
		$todateArr = array();

		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			array_push($centerArr,$final_valueArr1[0]);
						    			
						    			$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);

								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		//if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    		if($key > 0){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[12]);

							    		if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			array_push($elgArr, $final_valueArr1[0]);

								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }

						}
					}
				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			

								    	if($final_valueArr1[8] == 'Y'){
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
								    	else{
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; die;
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false && strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|ELEARNING_FEE_AMT|ELEARNING_SGST_AMT|ELEARNING_CGST_AMT|ELEARNING_IGST_AMT|ELEARNING_CS_TOT|ELEARNING_IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			//echo 'if'; die;
						    			$final_valueArr1 = explode('|', $value);
						    			//print_r($final_valueArr1); die;

						    			//echo 'no_elearn_exam_code';
						    			//print_r($no_elearn_exam_code);
						    			//echo 'elearn_exam_code';
						    			//print_r($elearn_exam_code);
						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>no_elearn_exam_code+++'.$final_valueArr1[0];
							    					if($final_valueArr1[0] == '1017'){
									    				$final_valueArr1[0] = '2027';
									    			}

									    			array_push($feeArr1, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value1 = str_replace('|', "','", $final_value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>elearn_exam_code---'.$final_valueArr1[0];
							    					if($final_valueArr1[0] == '1017'){
									    				$final_valueArr1[0] = '2027';
									    			}

									    			array_push($feeArr2, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value2 = str_replace('|', "','", $final_value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}	
						    		}
								}
							}
						}
					}
					//echo '---'.$fee_sql1.'---'.$fee_sql2;
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			array_push($mediumArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			array_push($miscArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			array_push($subjectArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}
				
				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    				$org_val = '1017';
						    			}
						    			else{
						    				$org_val = '';
						    			}

						    			array_push($activationArr, $final_valueArr1[0]);

						    			$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$final_value.="','".$org_val;

								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"EXAM_DATE_MASTER")!=false){

					$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";

					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
						//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);
	
						    			$final_value = implode($final_valueArr1, '|');		
						    			$final_value = str_replace('|', "','", $final_value);

								    	//echo $final_value;
								    	foreach ($fromdateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$from_date = $v;
								    		}
								    	}
								    	
								    	foreach ($todateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$to_date = $v;
								    		}
								    	}

								    	if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			array_push($validexamArr, $final_valueArr1[0]);

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        }
			    }
				

				if(strpos($file,"Venue")!==false){
					$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$venueData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//print_r($venueData); die;
					foreach($venueData as $key => $venue) {
				    	if($key > 1){

				    		$filtered_venue = array_filter($venue); 
				    		//print_r($filtered_venue);
				    		if(count($filtered_venue)>0){

				    			//echo 'filtered_venue--'.$filtered_venue['B'].'</br>'; //die;

				    			//$date = new DateTime($filtered_venue['B']);
								//$exam_date = $date->format('Y-m-d');

								$date = explode('/', $filtered_venue['B']);
								$exam_date = $date[2].'-'.$date[1].'-'.$date[0];

								//echo 'exam_date--'.$exam_date.'</br>'; //die;

								$createdon = date('Y-m-d');

				    			$this->db->select('venue_master_id');
								$where = array('exam_date' => $exam_date, 'center_code' => $filtered_venue['C'], 'session_time' => $filtered_venue['D'], 'venue_code' => $filtered_venue['F'], 'vendor_code' => $filtered_venue['A'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);

				    			if(count($check_venue_exists) > 0){

				    				$this->db->query('DELETE FROM venue_master WHERE venue_master_id ='.$check_venue_exists[0]['venue_master_id']);
				    				//echo 'update'; die;
				    				// $uploadData = array('exam_date' => $exam_date, 'venue_name' => $filtered_venue['G'], 'venue_addr1' => $filtered_venue['H'], 'venue_addr2' => $filtered_venue['I'], 'venue_addr3' => $filtered_venue['J'], 'venue_addr4' => $filtered_venue['K'], 'venue_addr5' => $filtered_venue['L'], 'venue_pincode' => $filtered_venue['M'], 'pwd_enabled' => $filtered_venue['N']);
				    				// $insId10 = $this->master_model->updateRecord('venue_master',$uploadData, $where);
				    			}
				    			$insId10 = $this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$exam_date.'","'.$filtered_venue['C'].'","'.$filtered_venue['D'].'","'.$filtered_venue['E'].'","'.$filtered_venue['F'].'","'.$filtered_venue['G'].'","'.$filtered_venue['H'].'","'.$filtered_venue['I'].'","'.$filtered_venue['J'].'","'.$filtered_venue['K'].'","'.$filtered_venue['L'].'","'.$filtered_venue['M'].'","'.$filtered_venue['N'].'","'.$filtered_venue['A'].'")');
				    		}
				    	}
				    }
				}
			}
		}

		if($center_sql != ''){
			if(count($centerArr) > 0){
				$center_arr = array_filter($centerArr); 
				for ($i=0; $i < count($center_arr); $i++) { 
				    //$this->db->select('id');
	     			//$check_center_exists = $this->master_model->getRecords('center_master', array('exam_name' => $center_arr[$i]));

	    			//if(count($check_center_exists) > 0){
	    			$this->db->query('DELETE FROM center_master WHERE exam_name ='.$center_arr[$i]);
	    			//}
				}
			}

			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_eligible_exists = $this->master_model->getRecords('eligible_master', array('exam_code' => $elg_arr[$i]));

	    			//if(count($check_eligible_exists) > 0){
	    				$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
	    			//}
				}
			}

			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($fee_sql1 != ''){
			if(count($feeArr1) > 0){
				$fee_arr = array_filter($feeArr1); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_fee_exists = $this->master_model->getRecords('fee_master', array('exam_code' => $fee_arr[$i]));

	    			//if(count($check_fee_exists) > 0){
	    				$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    			//}
				}
			}

			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
			//echo 'fee_insert1---'.$this->db->last_query();
		}
		
		if($fee_sql2 != ''){
			if(count($feeArr2) > 0){
				$fee_arr = array_filter($feeArr2); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_fee_exists = $this->master_model->getRecords('fee_master', array('exam_code' => $fee_arr[$i]));

	    			//if(count($check_fee_exists) > 0){
	    				$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    			//}
				}
			}

			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
			//echo 'fee_insert2---'.$this->db->last_query();
		}

		if($medium_sql != ''){
			if(count($mediumArr) > 0){
				$medium_arr = array_filter($mediumArr); 
				for ($i=0; $i < count($medium_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_medium_exists = $this->master_model->getRecords('medium_master', array('exam_code' => $medium_arr[$i]));

	    			//if(count($check_medium_exists) > 0){
	    				$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$medium_arr[$i]);
	    			//}
				}
			}
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			if(count($miscArr) > 0){
				$misc_arr = array_filter($miscArr); 
				for ($i=0; $i < count($misc_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_misc_exists = $this->master_model->getRecords('misc_master', array('exam_code' => $misc_arr[$i]));

	    			//if(count($check_misc_exists) > 0){
	    				$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$misc_arr[$i]);
	    			//}
				}
			}
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			if(count($subjectArr) > 0){
				$subject_arr = array_filter($subjectArr); 
				for ($i=0; $i < count($subject_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_subject_exists = $this->master_model->getRecords('subject_master', array('exam_code' => $subject_arr[$i]));

	    			//if(count($check_subject_exists) > 0){
	    				$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$subject_arr[$i]);
	    			//}
	    		}
	    	}
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			if(count($activationArr) > 0){
				$activation_arr = array_filter($activationArr); 
				for ($i=0; $i < count($activation_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_activation_exists = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $activation_arr[$i]));

	    			//if(count($check_activation_exists) > 0){
	    				//need to change date format to 2022-10-02
	    				$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$activation_arr[$i]);
	    			//}
	    		}
	    	}
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId8 = $this->db->query($exam_activate_insert);
		}

		if($valid_exam_sql != ''){
			if(count($validexamArr) > 0){
				$validexam_arr = array_filter($validexamArr); 
				for ($i=0; $i < count($validexam_arr); $i++) { 
					//$this->db->select('id');
	    			//$check_valid_date_exists = $this->master_model->getRecords('valid_examination_date', array('exam_code' => $validexam_arr[$i]));

	    			//if(count($check_valid_date_exists) > 0){
	    				$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$validexam_arr[$i]);
	    			//}
	    		}
	    	}
			$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
			$insId9 = $this->db->query($valid_exam_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId8 > 0 || $insId9 > 0 || $insId10 > 0){
			$msg = 'RPE Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertRPE_result(){
		//echo 'insertRPE'; die;
		$exam_period = '';
		$files = glob('uploads/Automation/rpe_result_live/*'); //get all file names
		foreach($files as $file){
	
		    if(is_file($file)){
		    	if(strpos($file, 'MARK_OBT') !== false){
		    		
		    		$marks_insert = "INSERT INTO dipcert_mark(regnumber, exam_id, exam_period, part_no, subject_id, marks, status, result_date) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('id');
	    			$check_marks_exists = $this->master_model->getRecords('dipcert_mark', array('exam_id' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_marks_exists) > 0){

	    				$this->db->query('DELETE FROM dipcert_mark WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

			        		//$this->db->query('DELETE FROM dipcert_mark WHERE exam_period ='.$arr[0]['']);

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$explode = explode('|', $value);

						    		$exam_period[$explode[1]] = $explode[2];
						    		$period = $explode[2];
						    		$final_value = str_replace('|', "','", $value);
							    	$marks_sql.= "('".$final_value."'),";
							   	}
						    }
						}
					}
					
		    	}

		    	if(strpos($file, 'MEMBER_LIST') !== false){
		    		
		    		$member_insert = "INSERT INTO dipcert_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('member_number');
	    			$check_member_exists = $this->master_model->getRecords('dipcert_member', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

	    			if(count($check_member_exists) > 0){
	    				$this->db->query('DELETE FROM dipcert_member WHERE exam_code ='.$exp1[2].' AND period ='.$exp1[3]);
	    			}

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);

						    		$final_value1 = str_replace(',', ' ', $value);
					    			$final_value1 = str_replace("'", ' ', $final_value1);
					    			$final_value1 = str_replace('|', "','", $final_value1);
					    			$final_value1 = $final_value1.$exp1[3];
					    			$exam_period = $exp1[3];
					    			$member_sql.= "('".$final_value1."'),";

								}
						    }
						}
					}
					
		    	}
		    	
		    	if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('exam_id');
	    			$check_exam_exists = $this->master_model->getRecords('result_exam', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_exam_exists) > 0){
	    				$this->db->query('DELETE FROM result_exam WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

		    		//print_r($exp1);die;
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);

						    		unset($final_valueArr1[5]);
						    		$final_valueArr1[1] = $final_valueArr1[1].'('.$final_valueArr1[0].') (RPE)';
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_value1 = $final_value1."','".$exp1[3];
						    		$exam_period = $exp1[3];
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}
					$result_exam_insert.=rtrim($exam_sql,",").';';
					$insId3 = $this->db->query($result_exam_insert);
		    	}
		   
		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {

		    			$exp = explode('/', $file);
			    		$exp1 = explode('_', $exp[3]);

			    		$exam_period = $exp1[3];

			    		$this->db->select('subject_id');
						$check_subject_exists = $this->master_model->getRecords('result_subject', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

						//echo $this->db->last_query(); die;

		    			if(count($check_subject_exists) > 0){
		    				$this->db->query('DELETE FROM result_subject WHERE exam_code ='.$exp1[2].' AND exam_period = '.$exp1[3]);
		    			}

		    			$this->db->select('id');
		    			$check_data_exists = $this->master_model->getRecords('display_result_setting', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

		    			if(count($check_data_exists) > 0){
		    				$this->db->query('DELETE FROM display_result_setting WHERE exam_code ='.$exp1[2].' AND period = '.$exp1[3]);
		    			}
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);

						    		$final_val = $explode[0].','.$explode[1].','."'RESULT'";
						    		$result_setting_sql.= "(".$final_val."),";

						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}
			}
		
		}	

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",").';';
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($subject_sql != ''){
			$result_subject_insert.=rtrim($subject_sql,",").';';
			$insId4 = $this->db->query($result_subject_insert);
		}

		if($result_setting_sql != ''){
			$display_result_setting_insert.=rtrim($result_setting_sql,",").';';
			$insId5 = $this->db->query($display_result_setting_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0){
			$url = base_url().'marksheet/dipcertresult/'.base64_encode($exam_period);
			$msg = 'RPE Result Live Successfully...';
			return $msg.'---'.$url.'---Give above link to Sunay Marathe to make Exam live on Website';
		}
	}

	public function insertDipcert_exam(){
		
		$files = glob('uploads/Automation/dipcert_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$centerArr = array();
		$elgArr = array();
		$feeArr1 = array();
		$feeArr2 = array();
		$mediumArr = array();
		$miscArr = array();
		$subjectArr = array();
		$activationArr = array();
		$validexamArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";


					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($centerArr,$final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}	
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code, optForCandidate) VALUES";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	$prev_file = '';
			        	//count($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		//if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    		if($key > 0){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);

										
										if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|OPT_FLG') === false){
							    			unset($final_valueArr1[13]);
							    		}

						    			array_push($elgArr, $final_valueArr1[0]);

								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
						    }
						}
					}	
				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			
								    	if($final_valueArr1[8] == 'Y'){
								    		//echo 'if';
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
								    	else{
								    		//echo 'else';
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
					//die;
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; die;
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
			        		//print_r($arr); die;
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			//echo 'if'; die;
						    			$final_valueArr1 = explode('|', $value);
						    			//print_r($final_valueArr1); die;

						    			/*echo 'no_elearn_exam_code';
						    			print_r($no_elearn_exam_code);
						    			echo 'elearn_exam_code';
						    			print_r($elearn_exam_code);*/
						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					
									    			array_push($feeArr1, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value1 = str_replace('|', "','", $final_value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					
							    					array_push($feeArr1, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value2 = str_replace('|', "','", $final_value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}	
						    		}
								}
							}
						}
					}
					//echo '---'.$fee_sql1.'+++'.$fee_sql2;
					//die;
				}

				
				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($mediumArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($miscArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($subjectArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($activationArr, $final_valueArr1[0]);

						    			$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$final_value.="','".$org_val;

								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"Venue")!==false){
					$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$venueData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//print_r($venueData); die;
					foreach($venueData as $key => $venue) {
				    	if($key > 1){
				    		$filtered_venue = array_filter($venue); 
				    		if(count($filtered_venue)>0){
				    			
				    			$date = new DateTime($filtered_venue['C']);
								$exam_date = $date->format('Y-m-d');
				    			$createdon = date('Y-m-d');

				    			$this->db->select('venue_master_id');
								$where = array('exam_date' => $exam_date, 'center_code' => $filtered_venue['D'], 'session_time' => $filtered_venue['E'], 'venue_code' => $filtered_venue['G'], 'vendor_code' => $filtered_venue['B'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);

				    			//echo 'exam_date---'.$exam_date; die;

				    			if(count($check_venue_exists) > 0){
				    				
				    				$this->db->query('DELETE FROM venue_master WHERE venue_master_id ='.$check_venue_exists[0]['venue_master_id']);

				    			}

				    			$insId10 = $this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$exam_date.'","'.$filtered_venue['D'].'","'.$filtered_venue['E'].'","'.$filtered_venue['F'].'","'.$filtered_venue['G'].'","'.$filtered_venue['H'].'","'.$filtered_venue['I'].'","'.$filtered_venue['J'].'","'.$filtered_venue['K'].'","'.$filtered_venue['L'].'","'.$filtered_venue['M'].'","'.$filtered_venue['N'].'","'.$filtered_venue['O'].'","'.$filtered_venue['B'].'")');

				    			//echo 'insert---'.$this->db->last_query().'</br>';
				    		}
				    	}
				    }
				    //die;
				}
			}
		}

		if($center_sql != ''){
			if(count($centerArr) > 0){
				$center_arr = array_filter($centerArr); 
				for ($i=0; $i < count($center_arr); $i++) { 
					$this->db->query('DELETE FROM center_master WHERE exam_name ='.$center_arr[$i]);
				}
			}
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
	    		}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($fee_sql1 != ''){
			if(count($feeArr1) > 0){
				$fee_arr = array_filter($feeArr1); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
		}
		
		if($fee_sql2 != ''){
			if(count($feeArr2) > 0){
				$fee_arr = array_filter($feeArr2); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}

			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
		}

		if($medium_sql != ''){
			if(count($mediumArr) > 0){
				$medium_arr = array_filter($mediumArr); 
				for ($i=0; $i < count($medium_arr); $i++) { 
					$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$medium_arr[$i]);
	    		}
			}
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId4 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			if(count($miscArr) > 0){
				$misc_arr = array_filter($miscArr); 
				for ($i=0; $i < count($misc_arr); $i++) { 
					$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$misc_arr[$i]);
	    		}
			}
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			if(count($subjectArr) > 0){
				$subject_arr = array_filter($subjectArr); 
				for ($i=0; $i < count($subject_arr); $i++) { 
					$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$subject_arr[$i]);
	    		}
	    	}
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			if(count($activationArr) > 0){
				$activation_arr = array_filter($activationArr); 
				for ($i=0; $i < count($activation_arr); $i++) { 
					$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$activation_arr[$i]);
	    		}
	    	}
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($exam_activate_insert);
		}
		
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId10 > 0){
			$msg = 'Diploma Certification Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertDipcert_result(){
		$exam_period = '';
		$files = glob('uploads/Automation/dipcert_result_live/*'); //get all file names
		foreach($files as $file){
	
		    if(is_file($file)){
		    	if(strpos($file, 'MARK_OBT') !== false){

		    		$marks_insert = "INSERT INTO dipcert_mark(regnumber, exam_id, exam_period, part_no, subject_id, marks, status, result_date) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('id');
	    			$check_marks_exists = $this->master_model->getRecords('dipcert_mark', array('exam_id' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_marks_exists) > 0){

	    				$this->db->query('DELETE FROM dipcert_mark WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$explode = explode('|', $value);

						    		$exam_period[$explode[1]] = $explode[2];
						    		$period = $explode[2];
						    		$final_value = str_replace('|', "','", $value);
							    	$marks_sql.= "('".$final_value."'),";
							   	}
						    }
						}
					}
					
		    	}

		    	if(strpos($file, 'MEMBER_LIST') !== false){

		    		$member_insert = "INSERT INTO dipcert_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('member_number');
	    			$check_member_exists = $this->master_model->getRecords('dipcert_member', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

	    			if(count($check_member_exists) > 0){
	    				$this->db->query('DELETE FROM dipcert_member WHERE exam_code ='.$exp1[2].' AND period ='.$exp1[3]);
	    			}

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);

						    		$final_value1 = str_replace(',', ' ', $value);
					    			$final_value1 = str_replace("'", ' ', $final_value1);
					    			$final_value1 = str_replace('|', "','", $final_value1);
					    			$final_value1 = $final_value1.$exp1[3];
					    			$exam_period = $exp1[3];
					    			$member_sql.= "('".$final_value1."'),";

								}
						    }
						}
					}
					
		    	}
		    	
		    	if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('exam_id');
	    			$check_exam_exists = $this->master_model->getRecords('result_exam', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_exam_exists) > 0){
	    				$this->db->query('DELETE FROM result_exam WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

		    		//print_r($exp1);
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);

						    		unset($final_valueArr1[5]);
						    		$final_valueArr1[1] = $final_valueArr1[1].'('.$final_valueArr1[1].') (Dipcert)';
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_value1 = $final_value1."','".$exp1[3];
						    		$exam_period = $exp1[3];
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}
					
		    	}
		   
		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";



		    		if (($handle = fopen($file, "r")) !== FALSE) {

		    			$exam_period = $exp1[3];

			    		$this->db->select('subject_id');
						$check_subject_exists = $this->master_model->getRecords('result_subject', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

						//echo $this->db->last_query(); die;

		    			if(count($check_subject_exists) > 0){
		    				$this->db->query('DELETE FROM result_subject WHERE exam_code ='.$exp1[2].' AND exam_period = '.$exp1[3]);
		    			}

		    			$this->db->select('id');
		    			$check_data_exists = $this->master_model->getRecords('display_result_setting', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

		    			if(count($check_data_exists) > 0){
		    				$this->db->query('DELETE FROM display_result_setting WHERE exam_code ='.$exp1[2].' AND period = '.$exp1[3]);
		    			}
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);

						    		$final_val = $explode[0].','.$explode[1].','."'RESULT'";
						    		$result_setting_sql.= "(".$final_val."),";

						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}

		    	if(strpos($file, 'consolidatedMarksheetData') !== false){
		    		
		    		$consolidated_marks_insert = "INSERT INTO CONS_MRK_DIPCERT(exam_code, mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, subject_code, subject_name, exam_period, exam_hold_on, exam_result_date, marks, result_flag) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM CD|MEMBER NO|MEMBER NAME|ADDRESS 1|ADDRESS 2|ADDRESS 3|ADDRESS 4|ADDRESS 5|ADDRESS 6|PIN CD|STATE DESC|SUB CD|SUB DESC|EXM PRD|EXAM DATE|RESULT DATE|MARKS|PAS FAL') === false){

							    		$final_value1 = str_replace(',', ' ', $value);
						    			$final_value1 = str_replace("'", ' ', $final_value1);
						    			$final_value1 = str_replace('|', "','", $final_value1);
						    			$consolidated_marks_sql.= "('".$final_value1."'),";
								    }
								}
						    }
						}
					}

		    	}
			}	
		}	

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",").';';
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($exam_sql != ''){
			$result_exam_insert.=rtrim($exam_sql,",").';';
			$insId3 = $this->db->query($result_exam_insert);
		}

		if($subject_sql != ''){
			$result_subject_insert.=rtrim($subject_sql,",").';';
			$insId4 = $this->db->query($result_subject_insert);
		}

		if($result_setting_sql != ''){
			$display_result_setting_insert.=rtrim($result_setting_sql,",").';';
			$insId5 = $this->db->query($display_result_setting_insert);
		}

		if($consolidated_marks_sql != ''){
			$consolidated_marks_insert.=rtrim($consolidated_marks_sql,",").';';
			//echo 'consolidated_marks_insert'.$consolidated_marks_insert;
			//die;
			$insId6 = $this->db->query($consolidated_marks_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0){
			$url = base_url().'marksheet/dipcertresult/'.base64_encode($exam_period);
			$msg = 'Diploma Certification Result Live Successfully...';
			return $msg.'---'.$url.'---Give above link to Sunay Marathe to make Exam live on Website';
		}
	}

	public function insertGARP_exam(){

		//$elg_insert = "INSERT INTO garp_exam_eligible(member_no, exam_code, exam_period, qualification, status, attempt, fee_flag, mem_name) VALUES";

		$files = glob('uploads/Automation/garp_exam_live/*'); //get all file names
		//print_r($files);
		foreach($files as $file){
		    if(is_file($file)){
		    	//echo 'file:'.$file;
		    	if(strpos($file,"EXAM_ACTIVATE")!=false){
		    		if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
		    			$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	if(!empty($arr)){
			        		foreach ($arr as $key => $value) {
			        			if(!empty($value)){
			        				if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){
			        					$examArr = explode('|', $value);

								    	$uploadData = array(
								    		'exam_from_date' => $examArr[2],
											'exam_to_date'  => $examArr[3]
										);

										$where = array('exam_code' => $examArr[0],
													   'exam_period' => $examArr[1]);

										$exam_code = $examArr[0];
										$exam_period = $examArr[1];

										$updated_id = $this->master_model->updateRecord('exam_activation_master',$uploadData,$where); 
										//echo $this->db->last_query();
			        				}
			        			}
			        		}
			        	}
		    		}
		    	}
		    	//die;
		    	if(strpos($file,"FEE_MASTER")!=false){

		    		$fee_insert = "INSERT INTO garp_exam_fee_master(exam_code, mem_type, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, exempt) VALUES";

		    		//echo '---'; //die;
		    		if (($fee_handle = fopen($file, "r")) !== FALSE) {
		    			$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	if(!empty($arr)){
			        		$this->db->query('TRUNCATE TABLE garp_exam_fee_master;');
			        		foreach ($arr as $key => $value) {
			        			if(!empty($value)){
			        				if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){

			        					$examArr = explode('|', $value);
			        					unset($examArr[1]);
			        					unset($examArr[2]);
			        					unset($examArr[3]);
			        					unset($examArr[5]);
			        					unset($examArr[12]);
			        					unset($examArr[13]);

			        					$final_value = implode('|', $examArr);
			        					$final_value = str_replace('|', "','", $final_value);
								    	$fee_sql.= "('".$final_value."'),";
								    	//echo $fee_sql;
			        				}
			        			}
			        		}
			        	}
		    		}
		    		
		    	}
		    	//die;
		    	if(strpos($file,"MISC")!=false){
		    		if (($misc_handle = fopen($file, "r")) !== FALSE) {
		    			$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	if(!empty($arr)){
			        		foreach ($arr as $key => $value) {
			        			if(!empty($value)){
			        				if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){

			        					$examArr = explode('|', $value);
			        					$uploadData = array(
			        						'exam_month' => $examArr[2],
										);

										$where = array('exam_code' => $examArr[0],
													   'exam_period' => $examArr[1]);

										$updated_id = $this->master_model->updateRecord('misc_master',$uploadData,$where); 
										//echo $this->db->last_query();
			        				}
			        			}
			        		}
			        	}
		    		}
		    	}
		    	//die;
		    	//echo $file;
		    	$row = 1;
		    	if(strpos($file,"GARP_Eligible")!==false){
		    		$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$eligibleData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					print_r($eligibleData); die;

					if(count($eligibleData) > 0){

						$this->db->query('TRUNCATE TABLE  garp_exam_eligible;');

						foreach($eligibleData as $key => $eligible) {
					    	if($key > 1){
					    		$filtered_eligible = array_filter($eligible); 
					    		if(count($filtered_eligible)>0){
					
									$insId2 = $this->db->query('INSERT INTO garp_exam_eligible(member_no, exam_code, exam_period, qualification, status) VALUES ("'.$filtered_eligible['A'].'","'.$exam_code.'","'.$exam_period.'","'.$filtered_eligible['C'].'","P")');
									//echo $this->db->last_query();
								}
							}
						}
					}
				}
			}
		}

		//$elg_insert.=rtrim($elg_sql,",".PHP_EOL).';';
		
		//$insId1 = $this->db->query($elg_insert);
		
		if($fee_sql != ''){
			$fee_insert.=rtrim($fee_sql,",".PHP_EOL).';';
		    $insId1 = $this->db->query($fee_insert);
		}

		if($insId1 > 0 || $insId2 > 0){
			$msg = 'GARP Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertJAIIB_exam(){
		
		$files = glob('uploads/Automation/jaiib_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$centerArr = array();
		$elgArr = array();
		$feeArr1 = array();
		$feeArr2 = array();
		$mediumArr = array();
		$miscArr = array();
		$subjectArr = array();
		$activationArr = array();
		$validexamArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($centerArr,$final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code, optForCandidate) VALUES";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	//$key = $start;
			        	if(!empty($arr)){
			        		//while ($key <= $limit) {
			        			//$value = $arr[$key];
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|OPT_FLG|') === false){

						    			array_push($elgArr, $final_valueArr1[0]);

								    	//$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $value);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		//$final_value1 = str_replace('|', "','", $final_value1);

							    		$final_valueArr1 = explode('|', $final_value1);
		
							    		unset($final_valueArr1[13]);

							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	//$elg_sql.= "('".$final_value1."'),";

								    	/*$data = array('exam_code' => $final_valueArr1[0], 
								    		  'eligible_period' => $final_valueArr1[1], 
								    		  'part_no' => $final_valueArr1[2], 
								    		  'member_no' => $final_valueArr1[3], 
								    		  'member_type' => $final_valueArr1[4], 
								    		  'exam_status' => $final_valueArr1[5], 
								    		  'app_category' => $final_valueArr1[6], 
								    		  'fees' => $final_valueArr1[7], 
								    		  'subject' => $final_valueArr1[8], 
								    		  'med_cd' => $final_valueArr1[9], 
								    		  'remark' => $final_valueArr1[10], 
								    		  'subject_code' => $final_valueArr1[11], 
								    		  'optForCandidate' => $final_valueArr1[12]);
								    	
								    	$insId2 = $this->master_model->insertRecord('eligible_master',$data);
		*/
								    	/*$this->db->query('INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code, optForCandidate) VALUES ("'.$final_valueArr1[0].'","'.$final_valueArr1[1].'","'.$final_valueArr1[2].'","'.$final_valueArr1[3].'","'.$final_valueArr1[4].'","'.$final_valueArr1[5].'","'.$final_valueArr1[6].'","'.$final_valueArr1[7].'","'.$final_valueArr1[8].'","'.$final_valueArr1[9].'","'.$final_valueArr1[10].'","'.$final_valueArr1[11].'","'.$final_valueArr1[12].'")');*/
							    	}
						    	}
								//$key++;
								//$this->insertJAIIB_exam($key, $limit+100);
						    }
						}
					}


				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			
								    	if($final_valueArr1[8] == 'N'){
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}

								    	if($final_valueArr1[8] == 'Y'){
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false && strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|ELEARNING_FEE_AMT|ELEARNING_SGST_AMT|ELEARNING_CGST_AMT|ELEARNING_IGST_AMT|ELEARNING_CS_TOT|ELEARNING_IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			$final_valueArr1 = explode('|', $value);


						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){

							    					array_push($feeArr1, $final_valueArr1[0]);
							    					//echo '<pre>no_elearn_exam_code+++'.$final_valueArr1[0];
											    	$final_value1 = str_replace('|', "','", $value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){

							    					array_push($feeArr2, $final_valueArr1[0]);
							    					//echo '<pre>elearn_exam_code---'.$final_valueArr1[0];
											    	$final_value2 = str_replace('|', "','", $value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}

						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($mediumArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			array_push($miscArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($subjectArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}
				
				
				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($activationArr, $final_valueArr1[0]);

						    			$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$final_value.="','".$org_val;

								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"EXAM_DATE_MASTER")!=false){

					$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";

					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
						//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			//print_r($final_valueArr1);
						    			array_push($validexamArr, $final_valueArr1[0]);
						    			
								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	foreach ($fromdateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$from_date = $v;
								    		}
								    	}
								    	
								    	foreach ($todateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$to_date = $v;
								    		}
								    	}

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        }
			       
				}
				
				if(strpos($file,"Venue")!==false){
					$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$venueData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//print_r($venueData); die;
					foreach($venueData as $key => $venue) {
				    	if($key > 1){
				    		$filtered_venue = array_filter($venue); 
				    		if(count($filtered_venue)>0){

				    			$this->db->select('venue_master_id');
								$where = array('center_code' => $filtered_venue['C'], 'session_time' => $filtered_venue['D'], 'venue_code' => $filtered_venue['F'], 'vendor_code' => $filtered_venue['A'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);

				    			//$ex_date = explode('/',$filtered_venue['B']);
				    			$date = new DateTime($filtered_venue['B']);
								$exam_date = $date->format('Y-m-d');
				    			$createdon = date('Y-m-d');

				    		
				    			//$exam_date = $ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0];

				    			if(count($check_venue_exists) > 0){

				    				$this->db->query('DELETE FROM venue_master WHERE venue_master_id ='.$check_venue_exists[0]['venue_master_id']);
				    				//echo 'update'; die;
				    				/*$uploadData = array('exam_date' => $exam_date, 'venue_name' => $filtered_venue['G'], 'venue_addr1' => $filtered_venue['H'], 'venue_addr2' => $filtered_venue['I'], 'venue_addr3' => $filtered_venue['J'], 'venue_addr4' => $filtered_venue['K'], 'venue_addr5' => $filtered_venue['L'], 'venue_pincode' => $filtered_venue['M'], 'pwd_enabled' => $filtered_venue['N']);
				    				$insId10 = $this->master_model->updateRecord('venue_master',$uploadData, $where);*/
				    			}
				    			//else{
				    				//echo 'insert'; die;
				    				$insId10 = $this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$exam_date.'","'.$filtered_venue['C'].'","'.$filtered_venue['D'].'","'.$filtered_venue['E'].'","'.$filtered_venue['F'].'","'.$filtered_venue['G'].'","'.$filtered_venue['H'].'","'.$filtered_venue['I'].'","'.$filtered_venue['J'].'","'.$filtered_venue['K'].'","'.$filtered_venue['L'].'","'.$filtered_venue['M'].'","'.$filtered_venue['N'].'","'.$filtered_venue['A'].'")');
				    			//}
				    		}
				    	}
				    }
				}
			}
		}

		if($center_sql != ''){
			if(count($centerArr) > 0){
				$center_arr = array_filter($centerArr); 
				for ($i=0; $i < count($center_arr); $i++) { 
				    $this->db->query('DELETE FROM center_master WHERE exam_name ='.$center_arr[$i]);
				}
			}
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
				}
			}
			$elg_insert.=rtrim($elg_sql,",".PHP_EOL).';';
			echo 'elg_insert---'.$elg_insert; die;
			//$this->insertJAIIB_data(0,5,$elg_insert);
			//$insId2 = $this->db->query($elg_insert);

		}

		if($fee_sql1 != ''){
			if(count($feeArr1) > 0){
				$fee_arr = array_filter($feeArr1); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
		}
		
		if($fee_sql2 != ''){
			if(count($feeArr2) > 0){
				$fee_arr = array_filter($feeArr2); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
		}

		if($medium_sql != ''){
			if(count($mediumArr) > 0){
				$medium_arr = array_filter($mediumArr); 
				for ($i=0; $i < count($medium_arr); $i++) { 
					$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$medium_arr[$i]);
	    		}
			}
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			if(count($miscArr) > 0){
				$misc_arr = array_filter($miscArr); 
				for ($i=0; $i < count($misc_arr); $i++) { 
					$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$misc_arr[$i]);
	    		}
			}
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			if(count($subjectArr) > 0){
				$subject_arr = array_filter($subjectArr); 
				for ($i=0; $i < count($subject_arr); $i++) { 
					$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$subject_arr[$i]);
	    		}
	    	}
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			if(count($activationArr) > 0){
				$activation_arr = array_filter($activationArr); 
				for ($i=0; $i < count($activation_arr); $i++) { 
					$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$activation_arr[$i]);
	    		}
	    	}
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId8 = $this->db->query($exam_activate_insert);
		}

		if($valid_exam_sql != ''){
			if(count($validexamArr) > 0){
				$validexam_arr = array_filter($validexamArr); 
				for ($i=0; $i < count($validexam_arr); $i++) { 
					$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$validexam_arr[$i]);
	    		}
	    	}
			$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
			$insId9 = $this->db->query($valid_exam_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId8 > 0 || $insId9 > 0 || $insId10 > 0){
			$msg = 'JAIIB Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertJAIIB_data($start, $limit, $data){
		echo 'start--'.$start.'limit--'.$limit.'data--'.$data; die;
    	$key = $start;
    	while ($key <= $limit) {
    		if(!empty($data)){
		    			
	    		//echo '+++'.$value.'</br>';
	    		$final_valueArr1 = explode('|', $value);

	    		unset($final_valueArr1[12]);

    			$this->db->select('id');
    			$check_eligible_exists = $this->master_model->getRecords('eligible_master', array('exam_code' => $final_valueArr1[0]));

    			if(count($check_eligible_exists) > 0){
    				$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$final_valueArr1[0]);
    			}
		    	
		    	$final_value1 = implode($final_valueArr1, '|');

	    		$final_value1 = str_replace(',', ' ', $final_value1);
	    		$final_value1 = str_replace("'", ' ', $final_value1);
	    		$final_value1 = str_replace('|', "','", $final_value1);
	    		//echo '</br>'.$final_value1;
		    	//echo '</br>'.count($final_valueArr1);

		    	//$final_value1 = rtrim($final_value1,"'");
		    	$elg_sql.= "('".$final_value1."'),";
		    	echo $elg_sql;
			    	
	    	}
			$key++;
	    }				
	}

	public function insertJAIIB_result(){
		echo 'jaiib'; die;
		$allfiles = glob('uploads/Automation/jaiib_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){

					$marks_insert = "INSERT INTO jaiib_marks(regnumber, exam_id, exam_period, part_no, subject_id, marks, status) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('id');
	    			$check_marks_exists = $this->master_model->getRecords('jaiib_marks', array('exam_id' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_marks_exists) > 0){

	    				$this->db->query('DELETE FROM jaiib_marks WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

					if (($handle = fopen($file, "r")) !== FALSE) {
						echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
							    	$final_value = str_replace('|', "','", $value);
							    	//echo $final_value;
							    	$final_valueArr = explode(',', $final_value);
							    	unset($final_valueArr[7]);

							    	$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."),";

							    }
						    }
						}
					}
					//echo 'xx'.$marks_sql; //die;	
				}

				if(strpos($file,"MEMBER_LIST")!=false){

					$member_insert = "INSERT INTO jaiib_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, exam_period) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('member_number');
	    			$check_member_exists = $this->master_model->getRecords('jaiib_member', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

	    			if(count($check_member_exists) > 0){
	    				$this->db->query('DELETE FROM jaiib_member WHERE exam_code ='.$exp1[2].' AND period ='.$exp1[3]);
	    			}

					if (($handle1 = fopen($file, "r")) !== FALSE) {
						$readFile1 =  fread($handle1,filesize($file));
						$arr1 = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile1)
			        	);
			        	$sql = '';
			        	if(!empty($arr1)){

						    foreach ($arr1 as $key => $value) {
						    	if(!empty($value)){
						    		//echo $value;
						    		$final_value1 = str_replace(',', ' ', $value);
						    		$final_value1 = str_replace("'", ' ', $final_value1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_valueArr1 = explode(',', $final_value1);
						    		//echo '</br>'.count($final_valueArr1);
							    	unset($final_valueArr1[20]);
							    	//echo '</br>'.count($final_valueArr1);

							    	$final_value1 = implode($final_valueArr1, ',');
							    	$final_value1 = rtrim($final_value1,"'");
						    		$member_sql.= "('".$final_value1."'),".PHP_EOL;
						    	}
			
						    }
						}
						
					}
					//echo 'yy'.$member_sql; die;	
				}

				if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('exam_id');
	    			$check_exam_exists = $this->master_model->getRecords('result_exam', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_exam_exists) > 0){
	    				$this->db->query('DELETE FROM result_exam WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}
		    		//print_r($exp1);
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);
						    		unset($final_valueArr1[5]);

						    		$final_valueArr1[1] = $final_valueArr1[1].' (JAIIB)';
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_value1 = $final_value1."','".$exp1[3];
						    		$exam_period = $exp1[3];
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}
					//echo 'zz'.$exam_sql; die;	
		    	}

		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {

		    			$exp = explode('/', $file);
			    		$exp1 = explode('_', $exp[3]);

			    		$exam_period = $exp1[3];

			    		$this->db->select('subject_id');
						$check_subject_exists = $this->master_model->getRecords('result_subject', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

						//echo $this->db->last_query(); die;

		    			if(count($check_subject_exists) > 0){
		    				$this->db->query('DELETE FROM result_subject WHERE exam_code ='.$exp1[2].' AND exam_period = '.$exp1[3]);
		    			}

		    			$this->db->select('id');
		    			$check_data_exists = $this->master_model->getRecords('display_result_setting', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

		    			if(count($check_data_exists) > 0){
		    				$this->db->query('DELETE FROM display_result_setting WHERE exam_code ='.$exp1[2].' AND period = '.$exp1[3]);
		    			}
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);

						    		$final_val = $explode[0].','.$explode[1].','."'RESULT'";
						    		$result_setting_sql.= "(".$final_val."),";

						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}

		    	if(strpos($file, 'consolidatedMarksheetData') !== false){
		    		
		    		$consolidated_marks_insert = "INSERT INTO caiib_cons_mrk(exam_code, mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, subject_code, subject_name, exam_period, exam_hold_on, exam_result_date, marks, result_flag) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM CD|MEMBER NO|MEMBER NAME|ADDRESS 1|ADDRESS 2|ADDRESS 3|ADDRESS 4|ADDRESS 5|ADDRESS 6|PIN CD|STATE DESC|SUB CD|SUB DESC|EXM PRD|EXAM DATE|RESULT DATE|MARKS|PAS FAL') === false){

							    		$final_value1 = str_replace(',', ' ', $value);
						    			$final_value1 = str_replace("'", ' ', $final_value1);
						    			$final_value1 = str_replace('|', "','", $final_value1);
						    			$consolidated_marks_sql.= "('".$final_value1."'),";
								    }
								}
						    }
						}
					}

		    	}
			}
		}

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",").';';
			//echo 'marks_insert--'.$marks_insert;
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($exam_sql != ''){
			$result_exam_insert.=rtrim($exam_sql,",").';';
			//echo 'result_exam_insert--'.$result_exam_insert;
			$insId3 = $this->db->query($result_exam_insert);
		}

		if($subject_sql != ''){
			$result_subject_insert.=rtrim($subject_sql,",").';';
			//echo 'result_subject_insert--'.$result_subject_insert;
			$insId4 = $this->db->query($result_subject_insert);
		}

		if($result_setting_sql != ''){
			$display_result_setting_insert.=rtrim($result_setting_sql,",").';';
			//echo 'display_result_setting_insert--'.$display_result_setting_insert;
			$insId5 = $this->db->query($display_result_setting_insert);
		}

		if($consolidated_marks_sql != ''){
			$consolidated_marks_insert.=rtrim($consolidated_marks_sql,",").';';
			//echo 'consolidated_marks_insert'.$consolidated_marks_insert;
			//die;
			$insId6 = $this->db->query($consolidated_marks_insert);
		}
		
		if($insId1 > 0 || $insId2 > 0 || $updated_id1 > 0 || $updated_id1 > 0 || $insId6 > 0){
			$url = base_url().'marksheet/jaiibresult/'.base64_encode($exam_period);
			$msg = 'JAIIB Result Live Successfully...';
			return $msg.'---'.$url.'---Give above link to Sunay Marathe to make Exam live on Website';
		}
	}

	public function insertCAIIB_exam(){
		
		$files = glob('uploads/Automation/caiib_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$centerArr = array();
		$elgArr = array();
		$feeArr1 = array();
		$feeArr2 = array();
		$mediumArr = array();
		$miscArr = array();
		$subjectArr = array();
		$activationArr = array();
		$validexamArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($centerArr,$final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code, optForCandidate) VALUES";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		//if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    		if($key > 0){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[13]);

						    			array_push($elgArr, $final_valueArr1[0]);
								    	
								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }
						}
					}
				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			
								    	if($final_valueArr1[8] == 'N'){
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}

								    	if($final_valueArr1[8] == 'Y'){
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false && strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|ELEARNING_FEE_AMT|ELEARNING_SGST_AMT|ELEARNING_CGST_AMT|ELEARNING_IGST_AMT|ELEARNING_CS_TOT|ELEARNING_IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			$final_valueArr1 = explode('|', $value);

						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){

							    					array_push($feeArr1, $final_valueArr1[0]);
							    					//echo '<pre>no_elearn_exam_code+++'.$final_valueArr1[0];
											    	$final_value1 = str_replace('|', "','", $value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){

							    					array_push($feeArr2, $final_valueArr1[0]);
							    					//echo '<pre>elearn_exam_code---'.$final_valueArr1[0];
											    	$final_value2 = str_replace('|', "','", $value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}

						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

										array_push($mediumArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			array_push($miscArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($subjectArr, $final_valueArr1[0]);

								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}
				
				
				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

										array_push($activationArr, $final_valueArr1[0]);

						    			$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$final_value.="','".$org_val;

								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"EXAM_DATE_MASTER")!=false){

					$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";

					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
						//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			array_push($validexamArr, $final_valueArr1[0])
						    			
								    	$final_value = str_replace('|', "','", $value);
								    	//echo $final_value;
								    	foreach ($fromdateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$from_date = $v;
								    		}
								    	}
								    	
								    	foreach ($todateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$to_date = $v;
								    		}
								    	}

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        }
			       
				}
				
				if(strpos($file,"Venue")!==false){
					$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$venueData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//print_r($venueData); die;
					foreach($venueData as $key => $venue) {
				    	if($key > 1){
				    		$filtered_venue = array_filter($venue); 
				    		if(count($filtered_venue)>0){

				    			$this->db->select('venue_master_id');
								$where = array('center_code' => $filtered_venue['C'], 'session_time' => $filtered_venue['D'], 'venue_code' => $filtered_venue['F'], 'vendor_code' => $filtered_venue['A'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);

				    			//$ex_date = explode('/',$filtered_venue['B']);
				    			$date = new DateTime($filtered_venue['B']);
								$exam_date = $date->format('Y-m-d');
				    			$createdon = date('Y-m-d');


				    			$this->db->select('venue_master_id');
								$where = array('exam_date' => $exam_date, 'center_code' => $filtered_venue['C'], 'session_time' => $filtered_venue['D'], 'venue_code' => $filtered_venue['F'], 'vendor_code' => $filtered_venue['A'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);
				    			//$exam_date = $ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0];

				    			if(count($check_venue_exists) > 0){

				    				$this->db->query('DELETE FROM venue_master WHERE venue_master_id ='.$check_venue_exists[0]['venue_master_id']);
				    				//echo 'update'; die;
				    				/*$uploadData = array('exam_date' => $exam_date, 'venue_name' => $filtered_venue['G'], 'venue_addr1' => $filtered_venue['H'], 'venue_addr2' => $filtered_venue['I'], 'venue_addr3' => $filtered_venue['J'], 'venue_addr4' => $filtered_venue['K'], 'venue_addr5' => $filtered_venue['L'], 'venue_pincode' => $filtered_venue['M'], 'pwd_enabled' => $filtered_venue['N']);
				    				$insId10 = $this->master_model->updateRecord('venue_master',$uploadData, $where);*/
				    			}
				    			//else{
				    				//echo 'insert'; die;
				    				$insId10 = $this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$exam_date.'","'.$filtered_venue['C'].'","'.$filtered_venue['D'].'","'.$filtered_venue['E'].'","'.$filtered_venue['F'].'","'.$filtered_venue['G'].'","'.$filtered_venue['H'].'","'.$filtered_venue['I'].'","'.$filtered_venue['J'].'","'.$filtered_venue['K'].'","'.$filtered_venue['L'].'","'.$filtered_venue['M'].'","'.$filtered_venue['N'].'","'.$filtered_venue['A'].'")');
				    			//}
				    		}
				    	}
				    }
				}
			}
		}

		if($center_sql != ''){
			if(count($centerArr) > 0){
				$center_arr = array_filter($centerArr); 
				for ($i=0; $i < count($center_arr); $i++) { 
				   $this->db->query('DELETE FROM center_master WHERE exam_name ='.$center_arr[$i]);
				}
			}
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
				}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			echo 'elg_insert---'.$elg_insert;
			//$insId2 = $this->db->query($elg_insert);

		}

		if($fee_sql1 != ''){
			if(count($feeArr1) > 0){
				$fee_arr = array_filter($feeArr1); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
				}
			}
			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
		}
		
		if($fee_sql2 != ''){
			if(count($feeArr2) > 0){
				$fee_arr = array_filter($feeArr2); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
				}
			}
			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
		}

		if($medium_sql != ''){
			if(count($mediumArr) > 0){
				$medium_arr = array_filter($mediumArr); 
				for ($i=0; $i < count($medium_arr); $i++) { 
					$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$medium_arr[$i]);
				}
			}
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			if(count($miscArr) > 0){
				$misc_arr = array_filter($miscArr); 
				for ($i=0; $i < count($misc_arr); $i++) { 
					$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$misc_arr[$i]);
	    		}
			}
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			if(count($subjectArr) > 0){
				$subject_arr = array_filter($subjectArr); 
				for ($i=0; $i < count($subject_arr); $i++) { 
					$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$subject_arr[$i]);
	    		}
	    	}
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			if(count($activationArr) > 0){
				$activation_arr = array_filter($activationArr); 
				for ($i=0; $i < count($activation_arr); $i++) { 
					$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$activation_arr[$i]);
	    		}
	    	}
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId8 = $this->db->query($exam_activate_insert);
		}

		if($valid_exam_sql != ''){
			if(count($validexamArr) > 0){
				$validexam_arr = array_filter($validexamArr); 
				for ($i=0; $i < count($validexam_arr); $i++) { 
					$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$validexam_arr[$i]);
	    		}
	    	}
			$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
			$insId9 = $this->db->query($valid_exam_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId8 > 0 || $insId9 > 0 || $updated_id > 0){
			$msg = 'JAIIB Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertCAIIB_result(){
		
		$allfiles = glob('uploads/Automation/caiib_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){

					$marks_insert = "INSERT INTO caiib_marks(regnumber, exam_id, exam_period, part_no, subject_id, marks, status) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('id');
	    			$check_marks_exists = $this->master_model->getRecords('caiib_marks', array('exam_id' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_marks_exists) > 0){

	    				$this->db->query('DELETE FROM caiib_marks WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
							    	$final_value = str_replace('|', "','", $value);
							    	//echo $final_value;
							    	$final_valueArr = explode(',', $final_value);
							    	unset($final_valueArr[7]);
							    	
							    	$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."),";

							    }
						    }
						}
					}
					
				}

				if(strpos($file,"Member_List")!=false){

					$member_insert = "INSERT INTO caiib_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, exam_period) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('member_number');
	    			$check_member_exists = $this->master_model->getRecords('caiib_member', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_member_exists) > 0){
	    				$this->db->query('DELETE FROM caiib_member WHERE exam_code ='.$exp1[2].' AND exam_period ='.$exp1[3]);
	    			}

					if (($handle1 = fopen($file, "r")) !== FALSE) {
						$readFile1 =  fread($handle1,filesize($file));
						$arr1 = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile1)
			        	);
			        	$sql = '';
			        	if(!empty($arr1)){

						    foreach ($arr1 as $key => $value) {
						    	if(!empty($value)){
						    		//echo $value;
						    		$final_value1 = str_replace(',', ' ', $value);
						    		$final_value1 = str_replace("'", ' ', $final_value1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_valueArr1 = explode(',', $final_value1);
						    		//echo '</br>'.count($final_valueArr1);
							    	unset($final_valueArr1[20]);
							    	//echo '</br>'.count($final_valueArr1);

							    	$final_value1 = implode($final_valueArr1, ',');
							    	$final_value1 = rtrim($final_value1,"'");
						    		$member_sql.= "('".$final_value1."'),".PHP_EOL;
						    	}
			
						    }
						}
						
					}
					
				}

				if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('exam_id');
	    			$check_exam_exists = $this->master_model->getRecords('result_exam', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_exam_exists) > 0){
	    				$this->db->query('DELETE FROM result_exam WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}
		    		//print_r($exp1);
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);
						    		unset($final_valueArr1[5]);
						    		$final_valueArr1[1] = $final_valueArr1[1].' (CAIIB)';
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_value1 = $final_value1."','".$exp1[3];
						    		$exam_period = $exp1[3];
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}
					
		    	}

		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {

		    			$exp = explode('/', $file);
			    		$exp1 = explode('_', $exp[3]);

			    		$exam_period = $exp1[3];

			    		$this->db->select('subject_id');
						$check_subject_exists = $this->master_model->getRecords('result_subject', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

						//echo $this->db->last_query(); die;

		    			if(count($check_subject_exists) > 0){
		    				$this->db->query('DELETE FROM result_subject WHERE exam_code ='.$exp1[2].' AND exam_period = '.$exp1[3]);
		    			}

		    			$this->db->select('id');
		    			$check_data_exists = $this->master_model->getRecords('display_result_setting', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

		    			if(count($check_data_exists) > 0){
		    				$this->db->query('DELETE FROM display_result_setting WHERE exam_code ='.$exp1[2].' AND period = '.$exp1[3]);
		    			}
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);
						    		$final_val = $explode[0].','.$explode[1].','."'RESULT'";
						    		$result_setting_sql.= "(".$final_val."),";

						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}

		    	if(strpos($file, 'consolidatedMarksheetData') !== false){
		    		
		    		$consolidated_marks_insert = "INSERT INTO caiib_cons_mrk(exam_code, mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, subject_code, subject_name, exam_period, exam_hold_on, exam_result_date, marks, result_flag) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM CD|MEMBER NO|MEMBER NAME|ADDRESS 1|ADDRESS 2|ADDRESS 3|ADDRESS 4|ADDRESS 5|ADDRESS 6|PIN CD|STATE DESC|SUB CD|SUB DESC|EXM PRD|EXAM DATE|RESULT DATE|MARKS|PAS FAL') === false){

							    		$final_value1 = str_replace(',', ' ', $value);
						    			$final_value1 = str_replace("'", ' ', $final_value1);
						    			$final_value1 = str_replace('|', "','", $final_value1);
						    			$consolidated_marks_sql.= "('".$final_value1."'),";
								    }
								}
						    }
						}
					}

		    	}
			}
		}

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",").';';
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($exam_sql != ''){
			$result_exam_insert.=rtrim($exam_sql,",").';';
			$insId3 = $this->db->query($result_exam_insert);
		}

		if($subject_sql != ''){
			$result_subject_insert.=rtrim($subject_sql,",").';';
			$insId4 = $this->db->query($result_subject_insert);
		}

		if($result_setting_sql != ''){
			$display_result_setting_insert.=rtrim($result_setting_sql,",").';';
			$insId5 = $this->db->query($display_result_setting_insert);
		}

		if($consolidated_marks_sql != ''){
			$consolidated_marks_insert.=rtrim($consolidated_marks_sql,",").';';
			//echo 'consolidated_marks_insert'.$consolidated_marks_insert;
			//die;
			$insId6 = $this->db->query($consolidated_marks_insert);
		}


		if($insId1 > 0 || $insId2 > 0 || $updated_id1 > 0 || $updated_id1 > 0 || $insId6 > 0){
			$url = base_url().'marksheet/caiibresult/'.base64_encode($exam_period);
			$msg = 'CAIIB Result Live Successfully...';
			return $msg.'---'.$url.'---Give above link to Sunay Marathe to make Exam live on Website';
		}
	}

	public function insertBulk_exam(){
		

		$files = glob('uploads/Automation/bulk_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$centerArr = array();
		$elgArr = array();
		$feeArr1 = array();
		$feeArr2 = array();
		$mediumArr = array();
		$miscArr = array();
		$subjectArr = array();
		$activationArr = array();
		$validexamArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, '
						    			EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($centerArr,$final_valueArr1[0]);

						    			$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);

								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		//if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    		if($key > 0){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[12]);

						    			array_push($elgArr, $final_valueArr1[0]);
								    	
								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }

						}
					}

					
				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			
								    	if($final_valueArr1[8] == 'N'){
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}

								    	if($final_valueArr1[8] == 'Y'){
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; die;
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false && strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|ELEARNING_FEE_AMT|ELEARNING_SGST_AMT|ELEARNING_CGST_AMT|ELEARNING_IGST_AMT|ELEARNING_CS_TOT|ELEARNING_IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			//echo 'if'; die;
						    			$final_valueArr1 = explode('|', $value);
						    			//print_r($final_valueArr1); die;

						    			//echo 'no_elearn_exam_code';
						    			//print_r($no_elearn_exam_code);
						    			//echo 'elearn_exam_code';
						    			//print_r($elearn_exam_code);
						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>

									    			array_push($feeArr1, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value1 = str_replace('|', "','", $final_value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>elearn_exam_code---'.$final_valueArr1[0];
							    				
									    			array_push($feeArr2, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value2 = str_replace('|', "','", $final_value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}	
						    		}
								}
							}
						}
					}
					//echo '---'.$fee_sql1.'---'.$fee_sql2;
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($mediumArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			array_push($miscArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($subjectArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}
				
				
				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO bulk_exam_activation(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($activationArr, $final_valueArr1[0]);

						    			$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$final_value.="','".$org_val;

								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"EXAM_DATE_MASTER")!=false){

					$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";

					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
						//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			$final_value = implode($final_valueArr1, '|');		
						    			$final_value = str_replace('|', "','", $final_value);

								    	//echo $final_value;
								    	foreach ($fromdateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$from_date = $v;
								    		}
								    	}
								    	
								    	foreach ($todateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$to_date = $v;
								    		}
								    	}

						    			array_push($validexamArr, $final_valueArr1[0]);

						    			//print_r($final_valueArr1);
						    			

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        }
			       
				}
				
				if(strpos($file,"Venue")!==false){
					$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$venueData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//print_r($venueData); die;
					foreach($venueData as $key => $venue) {
				    	if($key > 1){
				    		$filtered_venue = array_filter($venue); 
				    		if(count($filtered_venue)>0){

				    			$this->db->select('venue_master_id');
								$where = array('center_code' => $filtered_venue['C'], 'session_time' => $filtered_venue['D'], 'venue_code' => $filtered_venue['F'], 'vendor_code' => $filtered_venue['A'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);

				    			//$ex_date = explode('/',$filtered_venue['B']);
				    			$date = new DateTime($filtered_venue['B']);
								$exam_date = $date->format('Y-m-d');
				    			$createdon = date('Y-m-d');

				    			//$exam_date = $ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0];

				    			if(count($check_venue_exists) > 0){
				    				$this->db->query('DELETE FROM venue_master WHERE venue_master_id ='.$check_venue_exists[0]['venue_master_id']);
				    				//echo 'update'; die;
				    				/*$uploadData = array('exam_date' => $exam_date, 'venue_name' => $filtered_venue['G'], 'venue_addr1' => $filtered_venue['H'], 'venue_addr2' => $filtered_venue['I'], 'venue_addr3' => $filtered_venue['J'], 'venue_addr4' => $filtered_venue['K'], 'venue_addr5' => $filtered_venue['L'], 'venue_pincode' => $filtered_venue['M'], 'pwd_enabled' => $filtered_venue['N']);
				    				$insId10 = $this->master_model->updateRecord('venue_master',$uploadData, $where);*/
				    			}
				    			//else{
				    				//echo 'insert'; die;
				    				$insId10 = $this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$exam_date.'","'.$filtered_venue['C'].'","'.$filtered_venue['D'].'","'.$filtered_venue['E'].'","'.$filtered_venue['F'].'","'.$filtered_venue['G'].'","'.$filtered_venue['H'].'","'.$filtered_venue['I'].'","'.$filtered_venue['J'].'","'.$filtered_venue['K'].'","'.$filtered_venue['L'].'","'.$filtered_venue['M'].'","'.$filtered_venue['N'].'","'.$filtered_venue['A'].'")');
				    			//}
				    		}
				    	}
				    }
				}
			}
		}

		if($center_sql != ''){
			if(count($centerArr) > 0){
				$center_arr = array_filter($centerArr); 
				for ($i=0; $i < count($center_arr); $i++) { 
				    $this->db->query('DELETE FROM center_master WHERE exam_name ='.$center_arr[$i]);
				}
			}
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
				}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($fee_sql1 != ''){
			if(count($feeArr1) > 0){
				$fee_arr = array_filter($feeArr1); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
			//echo 'fee_insert1---'.$this->db->last_query();
		}
		
		if($fee_sql2 != ''){
			if(count($feeArr2) > 0){
				$fee_arr = array_filter($feeArr2); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
			//echo 'fee_insert2---'.$this->db->last_query();
		}

		if($medium_sql != ''){
			if(count($mediumArr) > 0){
				$medium_arr = array_filter($mediumArr); 
				for ($i=0; $i < count($medium_arr); $i++) { 
					$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$medium_arr[$i]);
	    		}
			}
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			if(count($miscArr) > 0){
				$misc_arr = array_filter($miscArr); 
				for ($i=0; $i < count($misc_arr); $i++) { 
					$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$misc_arr[$i]);
	    		}
			}
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			if(count($subjectArr) > 0){
				$subject_arr = array_filter($subjectArr); 
				for ($i=0; $i < count($subject_arr); $i++) { 
					$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$subject_arr[$i]);
	    		}
	    	}
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			if(count($activationArr) > 0){
				$activation_arr = array_filter($activationArr); 
				for ($i=0; $i < count($activation_arr); $i++) { 
					$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$activation_arr[$i]);
	    		}
	    	}
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId8 = $this->db->query($exam_activate_insert);
		}

		if($valid_exam_sql != ''){
			if(count($validexamArr) > 0){
				$validexam_arr = array_filter($validexamArr); 
				for ($i=0; $i < count($validexam_arr); $i++) { 
					$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$validexam_arr[$i]);
	    		}
	    	}
			$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
			$insId9 = $this->db->query($valid_exam_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId8 > 0 || $insId9 > 0 || $updated_id > 0){
			$msg = 'Bulk Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertBCBF_exam(){
		

		$files = glob('uploads/Automation/bcbf_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			$this->db->select('id');
						    			$check_center_exists = $this->master_model->getRecords('center_master', array('exam_name' => $final_valueArr1[0]));

						    			if(count($check_center_exists) > 0){
						    				$this->db->query('DELETE FROM center_master WHERE exam_name ='.$final_valueArr1[0]);
						    			}

						    			$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);

								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		//if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    		if($key > 0){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[12]);

						    			$this->db->select('id');
						    			$check_eligible_exists = $this->master_model->getRecords('eligible_master', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_eligible_exists) > 0){
						    				$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$final_valueArr1[0]);
						    			}
								    	
								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }

						}
					}

					
				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			
								    	if($final_valueArr1[8] == 'N'){
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}

								    	if($final_valueArr1[8] == 'Y'){
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; die;
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false && strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|ELEARNING_FEE_AMT|ELEARNING_SGST_AMT|ELEARNING_CGST_AMT|ELEARNING_IGST_AMT|ELEARNING_CS_TOT|ELEARNING_IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			//echo 'if'; die;
						    			$final_valueArr1 = explode('|', $value);
						    			//print_r($final_valueArr1); die;

						    			//echo 'no_elearn_exam_code';
						    			//print_r($no_elearn_exam_code);
						    			//echo 'elearn_exam_code';
						    			//print_r($elearn_exam_code);
						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>no_elearn_exam_code+++'.$final_valueArr1[0];
							    				
									    			$this->db->select('id');
									    			$check_fee_exists = $this->master_model->getRecords('fee_master', array('exam_code' => $final_valueArr1[0]));

									    			if(count($check_fee_exists) > 0){
									    				$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$final_valueArr1[0]);
									    			}

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value1 = str_replace('|', "','", $final_value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>elearn_exam_code---'.$final_valueArr1[0];
							    			
									    			$this->db->select('id');
									    			$check_fee_exists = $this->master_model->getRecords('fee_master', array('exam_code' => $final_valueArr1[0]));

									    			if(count($check_fee_exists) > 0){
									    				$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$final_valueArr1[0]);
									    			}

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value2 = str_replace('|', "','", $final_value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}	
						    		}
								}
							}
						}
					}
					//echo '---'.$fee_sql1.'---'.$fee_sql2;
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			$this->db->select('id');
						    			$check_medium_exists = $this->master_model->getRecords('medium_master', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_medium_exists) > 0){
						    				$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$final_valueArr1[0]);
						    			}

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			$this->db->select('id');
						    			$check_misc_exists = $this->master_model->getRecords('misc_master', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_misc_exists) > 0){
						    				$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$final_valueArr1[0]);
						    			}

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			$this->db->select('id');
						    			$check_subject_exists = $this->master_model->getRecords('subject_master', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_subject_exists) > 0){
						    				$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$final_valueArr1[0]);
						    			}

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}
					
				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO bulk_exam_activation(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			$this->db->select('id');
						    			$check_activation_exists = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_activation_exists) > 0){
						    				//need to change date format to 2022-10-02
						    				$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$final_valueArr1[0]);
						    			}

						    			$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$final_value.="','".$org_val;

								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
					
				}

				if(strpos($file,"EXAM_DATE_MASTER")!=false){

					$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";

					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
						//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);
	
						    			$final_value = implode($final_valueArr1, '|');		
						    			$final_value = str_replace('|', "','", $final_value);

								    	//echo $final_value;
								    	foreach ($fromdateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$from_date = $v;
								    		}
								    	}
								    	
								    	foreach ($todateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$to_date = $v;
								    		}
								    	}

						    			$this->db->select('id');
						    			$check_activation_exists = $this->master_model->getRecords('valid_examination_date', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_activation_exists) > 0){
						    				$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$final_valueArr1[0]);
						    			}

						    			//print_r($final_valueArr1);
						    			

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        }
			       
				}
				

			}
		}

		if($center_sql != ''){
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($fee_sql1 != ''){
			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
			//echo 'fee_insert1---'.$this->db->last_query();
		}
		
		if($fee_sql2 != ''){
			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
			//echo 'fee_insert2---'.$this->db->last_query();
		}

		if($medium_sql != ''){
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId8 = $this->db->query($exam_activate_insert);
		}

		if($valid_exam_sql != ''){
			$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
			$insId9 = $this->db->query($valid_exam_insert);
		}
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId8 > 0 || $insId9 > 0){
			$msg = 'BCBF Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertBCBF_result(){
		
		$allfiles = glob('uploads/Automation/bcbf_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){

					$marks_insert = "INSERT INTO bcbf_marks(regnumber, exam_id, exam_period, part_no, subject_id, marks, status) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('id');
	    			$check_marks_exists = $this->master_model->getRecords('bcbf_marks', array('exam_id' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_marks_exists) > 0){

	    				$this->db->query('DELETE FROM bcbf_marks WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
							    	$final_value = str_replace('|', "','", $value);
							    	//echo $final_value;
							    	$final_valueArr = explode(',', $final_value);
							    	unset($final_valueArr[7]);
							    	
							    	$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."),";

							    }
						    }
						}
					}
					
				}

				if(strpos($file,"Member_List")!=false){

					$member_insert = "INSERT INTO bcbf_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, exam_period) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('member_number');
	    			$check_member_exists = $this->master_model->getRecords('bcbf_member', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_member_exists) > 0){
	    				$this->db->query('DELETE FROM bcbf_member WHERE exam_code ='.$exp1[2].' AND exam_period ='.$exp1[3]);
	    			}

					if (($handle1 = fopen($file, "r")) !== FALSE) {
						$readFile1 =  fread($handle1,filesize($file));
						$arr1 = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile1)
			        	);
			        	$sql = '';
			        	if(!empty($arr1)){

						    foreach ($arr1 as $key => $value) {
						    	if(!empty($value)){
						    		//echo $value;
						    		$final_value1 = str_replace(',', ' ', $value);
						    		$final_value1 = str_replace("'", ' ', $final_value1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_valueArr1 = explode(',', $final_value1);
						    		//echo '</br>'.count($final_valueArr1);
							    	unset($final_valueArr1[20]);
							    	//echo '</br>'.count($final_valueArr1);

							    	$final_value1 = implode($final_valueArr1, ',');
							    	$final_value1 = rtrim($final_value1,"'");
						    		$member_sql.= "('".$final_value1."'),".PHP_EOL;
						    	}
			
						    }
						}
						
					}
					
				}

				if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('exam_id');
	    			$check_exam_exists = $this->master_model->getRecords('result_exam', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_exam_exists) > 0){
	    				$this->db->query('DELETE FROM result_exam WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}
		    		//print_r($exp1);
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);
						    		unset($final_valueArr1[5]);
						    		$final_valueArr1[1] = $final_valueArr1[1].' (BCBF)';
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_value1 = $final_value1."','".$exp1[3];
						    		$exam_period = $exp1[3];
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}
					
		    	}

		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {

		    			$exp = explode('/', $file);
			    		$exp1 = explode('_', $exp[3]);

			    		$exam_period = $exp1[3];

			    		$this->db->select('subject_id');
						$check_subject_exists = $this->master_model->getRecords('result_subject', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

						//echo $this->db->last_query(); die;

		    			if(count($check_subject_exists) > 0){
		    				$this->db->query('DELETE FROM result_subject WHERE exam_code ='.$exp1[2].' AND exam_period = '.$exp1[3]);
		    			}

		    			$this->db->select('id');
		    			$check_data_exists = $this->master_model->getRecords('display_result_setting', array('exam_code' => $exp1[2], 'period' => $exp1[3]));

		    			if(count($check_data_exists) > 0){
		    				$this->db->query('DELETE FROM display_result_setting WHERE exam_code ='.$exp1[2].' AND period = '.$exp1[3]);
		    			}
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);
						    		$final_val = $explode[0].','.$explode[1].','."'RESULT'";
						    		$result_setting_sql.= "(".$final_val."),";

						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}

		    	if(strpos($file, 'consolidatedMarksheetData') !== false){
		    		
		    		$consolidated_marks_insert = "INSERT INTO caiib_cons_mrk(exam_code, mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, subject_code, subject_name, exam_period, exam_hold_on, exam_result_date, marks, result_flag) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM CD|MEMBER NO|MEMBER NAME|ADDRESS 1|ADDRESS 2|ADDRESS 3|ADDRESS 4|ADDRESS 5|ADDRESS 6|PIN CD|STATE DESC|SUB CD|SUB DESC|EXM PRD|EXAM DATE|RESULT DATE|MARKS|PAS FAL') === false){

							    		$final_value1 = str_replace(',', ' ', $value);
						    			$final_value1 = str_replace("'", ' ', $final_value1);
						    			$final_value1 = str_replace('|', "','", $final_value1);
						    			$consolidated_marks_sql.= "('".$final_value1."'),";
								    }
								}
						    }
						}
					}

		    	}
			}
		}

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",").';';
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($exam_sql != ''){
			$result_exam_insert.=rtrim($exam_sql,",").';';
			$insId3 = $this->db->query($result_exam_insert);
		}

		if($subject_sql != ''){
			$result_subject_insert.=rtrim($subject_sql,",").';';
			$insId4 = $this->db->query($result_subject_insert);
		}

		if($result_setting_sql != ''){
			$display_result_setting_insert.=rtrim($result_setting_sql,",").';';
			$insId5 = $this->db->query($display_result_setting_insert);
		}

		if($consolidated_marks_sql != ''){
			$consolidated_marks_insert.=rtrim($consolidated_marks_sql,",").';';
			$insId6 = $this->db->query($consolidated_marks_insert);
		}


		if($insId1 > 0 || $insId2 > 0 || $updated_id1 > 0 || $updated_id1 > 0 || $insId6 > 0){
			$url = base_url().'marksheet/caiibresult/'.base64_encode($exam_period);
			$msg = 'BCBF Result Live Successfully...';
			return $msg.'---'.$url.'---Give above link to Sunay Marathe to make Exam live on Website';
		}
	}

	public function insertBlended_result(){
		
		$allfiles = glob('uploads/Automation/blended_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){

					$marks_insert = "INSERT INTO blended_marks(regnumber, exam_id, exam_period, part_no, subject_id, marks, status, result_date) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('id');
	    			$check_marks_exists = $this->master_model->getRecords('blended_marks', array('exam_id' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_marks_exists) > 0){

	    				$this->db->query('DELETE FROM blended_marks WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
							    	$final_value = str_replace('|', "','", $value);
							    	//echo $final_value;
							    	//$final_valueArr = explode(',', $final_value);
							    	//unset($final_valueArr[7]);
							    	
							    	//$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."'),";

							    }
						    }
						}
					}
				}

				if(strpos($file,"MEMBER_LIST")!=false){

					$member_insert = "INSERT INTO blended_members(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, exam_period) VALUES";

					$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('member_number');
	    			$check_member_exists = $this->master_model->getRecords('blended_members', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_member_exists) > 0){
	    				$this->db->query('DELETE FROM blended_members WHERE exam_code ='.$exp1[2].' AND exam_period ='.$exp1[3]);
	    			}

					if (($handle1 = fopen($file, "r")) !== FALSE) {
						$readFile1 =  fread($handle1,filesize($file));
						$arr1 = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile1)
			        	);
			        	$sql = '';
			        	if(!empty($arr1)){

						    foreach ($arr1 as $key => $value) {
						    	if(!empty($value)){
						    		//echo $value;
						    		$final_value1 = str_replace(',', ' ', $value);
						    		$final_value1 = str_replace("'", ' ', $final_value1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_valueArr1 = explode(',', $final_value1);
						    		//echo '</br>'.count($final_valueArr1);
							    	unset($final_valueArr1[19]);
							    	//echo '</br>'.count($final_valueArr1);

							    	$final_value1 = implode($final_valueArr1, ',');
							    	$final_value1 = $final_value1.",'".$exp1[3];
					    			$exam_period = $exp1[3];
					    			$final_value1 = rtrim($final_value1,"'");
						    		$member_sql.= "('".$final_value1."'),".PHP_EOL;
						    	}
			
						    }
						}
						
					}
				}

				if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO blended_result_exam(exam_code, exam_period, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, exam_date, result_date) VALUES";
		    		
		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[3]);

		    		$exam_period = $exp1[3];

		    		$this->db->select('exam_id');
	    			$check_exam_exists = $this->master_model->getRecords('result_exam', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

	    			if(count($check_exam_exists) > 0){
	    				$this->db->query('DELETE FROM result_exam WHERE exam_id ='.$exp1[2].' AND exam_period = '.$exp1[3]);
	    			}

		    		//print_r($exp1);
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);
						    		unset($final_valueArr1[2]);
						    		unset($final_valueArr1[7]);
						    		$final_valueArr1[1] = $final_valueArr1[1];
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}

		    	}

		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO blended_result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {

		    			$exp = explode('/', $file);
			    		$exp1 = explode('_', $exp[3]);

			    		$exam_period = $exp1[3];

			    		$this->db->select('subject_id');
						$check_subject_exists = $this->master_model->getRecords('blended_result_subject', array('exam_code' => $exp1[2], 'exam_period' => $exp1[3]));

						//echo $this->db->last_query(); die;

		    			if(count($check_subject_exists) > 0){
		    				$this->db->query('DELETE FROM blended_result_subject WHERE exam_code ='.$exp1[2].' AND exam_period = '.$exp1[3]);
		    			}

						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);
						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}

		    	if(strpos($file, 'consolidatedMarksheetData') !== false){
		    		
		    		$consolidated_marks_insert = "INSERT INTO blended_cons_mrk(exam_code, mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, subject_code, subject_name, exam_period, exam_hold_on, exam_result_date, marks, result_flag) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		$final_value1 = str_replace(',', ' ', $value);
					    			$final_value1 = str_replace("'", ' ', $final_value1);
					    			$final_value1 = str_replace('|', "','", $final_value1);
					    			$consolidated_marks_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}

		    	}

			}
		}

		if($marks_sql != ''){
			$marks_insert.=rtrim($marks_sql,",".PHP_EOL).';';
			$insId1 = $this->db->query($marks_insert);
		}

		if($member_sql != ''){
			$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
			$insId2 = $this->db->query($member_insert);
		}

		if($exam_sql != ''){
			$result_exam_insert.=rtrim($exam_sql,",").';';
			$insId3 = $this->db->query($result_exam_insert);
		}

		if($subject_sql != ''){
			$result_subject_insert.=rtrim($subject_sql,",").';';
			$insId4 = $this->db->query($result_subject_insert);
		}	

		if($consolidated_marks_sql != ''){
			$consolidated_marks_insert.=rtrim($consolidated_marks_sql,",").';';
			//echo 'consolidated_marks_insert'.$consolidated_marks_insert;die;
			$insId5 = $this->db->query($consolidated_marks_insert);
		}

		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0){
			$msg = 'Blended Result Live Successfully...';
			$url = base_url().'Blended_result';
			return $msg.'---'.$url.'---Give above link to Sunay Marathe to make Exam live on Website';
		}
	}

	public function insertBlended_training(){
		
		$allfiles = glob('uploads/Automation/blended_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){

					$marks_insert = "INSERT INTO caiib_marks(regnumber, exam_id, exam_period, part_no, subject_id, marks, status) VALUES";

					if (($handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){

						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
							    	$final_value = str_replace('|', "','", $value);
							    	//echo $final_value;
							    	$final_valueArr = explode(',', $final_value);
							    	unset($final_valueArr[7]);
							    	
							    	$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."),";

							    }
						    }
						}
					}
					$marks_insert.=rtrim($marks_sql,",").';';
					$insId1 = $this->db->query($marks_insert);
				}

				if(strpos($file,"Member_List")!=false){

					$member_insert = "INSERT INTO caiib_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, exam_period) VALUES";

					if (($handle1 = fopen($file, "r")) !== FALSE) {
						$readFile1 =  fread($handle1,filesize($file));
						$arr1 = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile1)
			        	);
			        	$sql = '';
			        	if(!empty($arr1)){

						    foreach ($arr1 as $key => $value) {
						    	if(!empty($value)){
						    		//echo $value;
						    		$final_value1 = str_replace(',', ' ', $value);
						    		$final_value1 = str_replace("'", ' ', $final_value1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_valueArr1 = explode(',', $final_value1);
						    		//echo '</br>'.count($final_valueArr1);
							    	unset($final_valueArr1[20]);
							    	//echo '</br>'.count($final_valueArr1);

							    	$final_value1 = implode($final_valueArr1, ',');
							    	$final_value1 = rtrim($final_value1,"'");
						    		$member_sql.= "('".$final_value1."'),".PHP_EOL;
						    	}
			
						    }
						}
						
					}
					$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
					$insId2 = $this->db->query($member_insert);
				}

				if(strpos($file, 'EXAM_LIST') !== FALSE){

		    		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[2]);
		    		//print_r($exp1);
		    		if (($handle = fopen($file, "r")) !== FALSE) {
						
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		$final_valueArr1 = explode('|', $value);
						    		unset($final_valueArr1[5]);
						    		$final_valueArr1[1] = $final_valueArr1[1].' (CAIIB)';
						    		$final_value1 = implode('|', $final_valueArr1);
						    		$final_value1 = str_replace('|', "','", $final_value1);
						    		$final_value1 = $final_value1."','".$exp1[3];
						    		$exam_period = $exp1[3];
							    	$exam_sql.= "('".$final_value1."'),";

							    	//echo $exam_sql;
								}
						    }
						}
					}
					$result_exam_insert.=rtrim($exam_sql,",").';';
					$insId3 = $this->db->query($result_exam_insert);
		    	}

		    	if(strpos($file, 'SUBJECT_LIST') !== false){

		    		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		    		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";

		    		if (($handle = fopen($file, "r")) !== FALSE) {
				
						$readFile =  fread($handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$sql = '';
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){

						    		$explode = explode('|', $value);
						    		$final_val = $explode[0].','.$explode[1].','."'RESULT'";
						    		$result_setting_sql.= "(".$final_val."),";

						    		unset($explode[7]);
						    		$final_value1 = implode('|',$explode);
						    		$final_value1 = str_replace('|', "','", $final_value1);
					    			
							    	$subject_sql.= "('".$final_value1."'),";
								}
						    }
						}
					}
		    	}

			}
		}

		if($insId1 > 0 || $insId2 > 0 || $updated_id1 > 0 || $updated_id1 > 0){
			$msg = 'CAIIB Result Live Successfully...';
			return $msg;
		}
	}

	public function insertContactclasses_training(){

		require_once APPPATH . "/third_party/PHPExcel.php";
		
		$allfiles = glob('uploads/Automation/contact_classes_training_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				$inputFileType = PHPExcel_IOFactory::identify($file);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($file);
				$allDataInSheet = $objPHPExcel->getActiveSheet();
				$sheetnames  = $objReader->listWorksheetNames($file);
				$CurrentWorkSheetIndex = 0;

				for ($i=0; $i<count($sheetnames); $i++) {
					//$index = $objPHPExcel->getIndex($worksheet);
					//echo 'Worksheet name - ', $sheetnames[$index], PHP_EOL;

					switch ($sheetnames[$i]) {
						case "CENTER MASTER":
								//$center_insert = "INSERT INTO contact_classes_center_master( course_code, exam_prd, center_code, center_name, state_code, state_name, createdon) VALUES ";
								
							  	$centerData = $objPHPExcel->getSheet($i)->toArray(null,true,true,true);
							    $row = 1;
							    foreach($centerData as $key => $center) {
							    	if($key > 1){
							    		$filtered_centers = array_filter($center); 
							    		//print_r($filtered_centers); 
							    	
										// $this->db->select('id');
										// $where = array('course_code' => $filtered_centers['A']);
						    			//$check_center_exists = $this->master_model->getRecords('contact_classes_center_master', $where);

						    			//if(count($check_center_exists) > 0){
						    				//$this->db->query('DELETE FROM contact_classes_center_master WHERE id ='.$check_center_exists[0]['id']);
						    			//}

						    			if(count($filtered_centers)>0){
						    				
							    			$createdon = date('Y-m-d');

							    			$insId1 = $this->db->query('INSERT INTO contact_classes_center_master( course_code, exam_prd, center_code, center_name, state_code, state_name, createdon) VALUES ("'.$filtered_centers['A'].'","'.$filtered_centers['B'].'","'.$filtered_centers['C'].'","'.$filtered_centers['D'].'","'.$filtered_centers['E'].'","'.$filtered_centers['F'].'","'.$createdon.'")');
							    		}
						   			}
								}
						    	break;
						case "EXAM ACTIVATION":
								$activationData = $objPHPExcel->getSheet($i)->toArray(null,true,true,true);
							    $row = 1;
							    $prev_code = '';
							    foreach($activationData as $key => $activation) {
							    	if($key > 1){
							    		$filtered_activation = array_filter($activation); 
							    	
										// $this->db->select('id');
										// $where = array('course_code' => $filtered_activation['A']);
						    			//$check_activation_exists = $this->master_model->getRecords('contact_classes_cource_activation_master', $where);

						    			//if(count($check_activation_exists) > 0){
						    				//$this->db->query('DELETE FROM contact_classes_cource_activation_master WHERE id ='.$check_activation_exists[0]['id']);
						    			//}
						    			
						    			if(count($filtered_activation)>0){
							    			$createdon = date('Y-m-d');

							    			if($filtered_activation['A'] != ''){
							    				if($filtered_activation['A'] <> $prev_code){
							    					$prev_code = $filtered_activation['A'];
							    				}
							    			}

							    			$date1 = new DateTime($filtered_activation['C']);
											$from_date = $date1->format('Y/m/d');

											$date2 = new DateTime($filtered_activation['C']);
											$to_date = $date2->format('Y/m/d');

							    			$insId2 = $this->db->query('INSERT INTO contact_classes_cource_activation_master(course_code, exam_prd, from_date, to_date, createdon) VALUES ("'.$prev_code.'","'.$filtered_activation['B'].'","'.$from_date.'","'.$to_date.'","'.$createdon.'")');
							    		}
						   			}
								}
						    	break;
						/*case "EXAM MASTER":
								$examData = $objPHPExcel->getSheet($i)->toArray(null,true,true,true);
							    $row = 1;
							    foreach($examData as $key => $exam) {
							    	if($key > 1){
							    		$filtered_exams = array_filter($exam); 
							    	
										$this->db->select('id');
										$where = array('course_code' => $filtered_exams['A']);
						    			$check_exam_exists = $this->master_model->getRecords('contact_classes_cource_master', $where);

						    			if(count($check_exam_exists) > 0){
						    				$this->db->query('DELETE FROM contact_classes_cource_master WHERE id ='.$check_exam_exists[0]['id']);
						    			}

						    			if(count($filtered_centers)>0){
							    			$createdon = date('Y-m-d');

							    			$this->db->query('INSERT INTO contact_classes_cource_master( course_code, course_name, isactive, createdon, original_val) VALUES ("'.$filtered_exams['A'].'","'.$filtered_exams['B'].'","'.$filtered_exams['C'].'","'.$filtered_exams['D'].'","'.$filtered_exams['E'].'","'.$filtered_exams['E'].'","'.$filtered_exams['F'].'","'.$createdon.'")');
							    		}
						   			}
								}
						    	break;*/
						case "FEES MASTER":
								$feeData = $objPHPExcel->getSheet($i)->toArray(null,true,true,true);
							    $row = 1;
							    foreach($feeData as $key => $feeData) {
							    	if($key > 1){
							    		$filtered_fees = array_filter($feeData); 
							    	
										// $this->db->select('id');
										// $where = array('course_code' => $filtered_fees['A']);
						    			//$check_fee_exists = $this->master_model->getRecords('contact_classes_fee_master', $where);

						    			//if(count($check_feeData_exists) > 0){
						    			//$this->db->query('DELETE FROM contact_classes_fee_master WHERE id ='.$check_feeData_exists[0]['id']);
						    			//}

							    		if(count($filtered_fees) > 0){
							    			$createdon = date('Y-m-d');
							    			
							    			
							    			//echo 'prev_code--->'.$prev_code;

							    			$date1 = new DateTime($filtered_fees['J']);
											$from_date = $date1->format('Y/m/d');

											$date2 = new DateTime($filtered_fees['K']);
											$to_date = $date2->format('Y/m/d');

							    			$insId3 = $this->db->query('INSERT INTO contact_classes_fee_master( course_code, exam_prd, sub_code, fee, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, from_date, to_date, createdon) VALUES ("'.$filtered_fees['A'].'","'.$filtered_fees['B'].'","'.$filtered_fees['C'].'","'.$filtered_fees['D'].'","'.$filtered_fees['E'].'","'.$filtered_fees['F'].'","'.$filtered_fees['G'].'","'.$filtered_fees['H'].'","'.$filtered_fees['I'].'","'.$from_date.'","'.$to_date.'","'.$createdon.'")');

							    		}
						   			}
								}
						    	break;
						case "SUBJECT MASTER":
								$subjectData = $objPHPExcel->getSheet($i)->toArray(null,true,true,true);
							    $row = 1;
							    foreach($subjectData as $key => $subject) {
							    	if($key > 1){
							    		$filtered_subject = array_filter($subject); 
							    	
										// $this->db->select('id');
										// $where = array('course_code' => $filtered_subjects['A']);
						    			//$check_subject_exists = $this->master_model->getRecords('contact_classes_subject_master', $where);

						    			//if(count($check_subject_exists) > 0){
						    				//$this->db->query('DELETE FROM contact_classes_subject_master WHERE id ='.$check_subject_exists[0]['id']);
						    			//}

							    		if(count($filtered_subject) > 0){
							    			//print_r($filtered_subject);
							    			$createdon = date('Y-m-d');

											$date1 = new DateTime($filtered_subject['F']);
											$cource_date1 = $date1->format('Y/m/d');

											$date2 = new DateTime($filtered_subject['G']);
											$cource_date2 = $date2->format('Y/m/d');

							    			$insId4 = $this->db->query('INSERT INTO contact_classes_subject_master( course_code, exam_prd, center_code, sub_code, sub_name, cource_date1, cource_date2, createdon) VALUES ("'.$filtered_subject['A'].'","'.$filtered_subject['B'].'","'.$filtered_subject['C'].'","'.$filtered_subject['D'].'","'.$filtered_subject['E'].'","'.$cource_date1.'","'.$cource_date2.'","'.$createdon.'")');
							    		}
						   			}
								}
						    	break;
						/*case "VENUE MASTER":
								$examData = $objPHPExcel->getSheet($i)->toArray(null,true,true,true);
							    $row = 1;
							    foreach($examData as $key => $exam) {
							    	if($key > 1){
							    		$filtered_exams = array_filter($exam); 
							    	
										// $this->db->select('venue_master_id');
										// $where = array('course_code' => $filtered_exams['A']);
						    			//$check_exam_exists = $this->master_model->getRecords('contact_classes_center_master', $where);

						    			//if(count($check_exam_exists) > 0){
						    				//$this->db->query('DELETE FROM contact_classes_center_master WHERE venue_master_id ='.$check_exam_exists[0]['id']);
						    			//}

						    			$createdon = date('Y-m-d');

						    			$insId5 = $this->db->query('INSERT INTO contact_classes_venue_master( course_code, center_code, venue_code, venue_name, createdon) VALUES ("'.$filtered_exams['A'].'","'.$filtered_exams['B'].'","'.$filtered_exams['C'].'","'.$filtered_exams['D'].'","'.$createdon.'")');
						   			}
								}
						    	break;*/
						default:
						    "";
					}
				}
				
			}
		}

		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0){
			$url = base_url().'ContactClasses/';
			$msg = 'Contact Classes Training Live Successfully...';
			return $msg.'---'.$url.'---';
		}
	}

	public function insertCISI_exam(){
		

		$files = glob('uploads/Automation/CISI_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();
		$elgArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CISI_EligibleData")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	$elg_sql ='';
			        	//print_r($arr);die;
			        	//$key = $start;
			        	$key = 0;
			        	if(!empty($arr)){
			        		//print_r($arr);die;
						    //while ($key <= $limit) {
			        		while ($key <= count($arr)) {
						    	//echo '---'.$key.'</br>';
						    	//echo $value;
						    	$value = $arr[$key];
						    	//echo 'value--'.$value.'</br>';
						    	if(!empty($value)){
						    		if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    			
							    		//echo '+++'.$value.'</br>';
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[12]);

						    			array_push($elgArr, $final_valueArr1[0]);
								    	
								    	$final_value1 = implode($final_valueArr1, '|');

							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		//echo '</br>'.$final_value1;
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	echo $elg_sql;
							    	}
						    	}
								//$key++;
						    }
						    
						}
					}
				}
			}
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
	    		}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId1 = $this->db->query($elg_insert);
		}

		//$this->insertCISI_exam($key, $limit+5);
		echo 'insId1'.$insId1;

		/*if($insId1 > 0){
			$msg = 'CISI Exam Live Successfully...';
			return $msg;
		}	*/
	}

	public function insertDISA_exam(){
		

		$files = glob('uploads/Automation/DISA_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();
		$elgArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"ELG_LIST_EXM")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[12]);

						    			array_push($elgArr, $final_valueArr1[0]);
								    	
								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }

						}
					}
				}
			}
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
	    		}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($insId1 > 0){
			$msg = 'DISA Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertSPEL_exam(){
		
		$files = glob('uploads/Automation/SPEL_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();
		$elgArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"ELG_LIST_EXM")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|') === false){
						    			
							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[12]);

						    			array_push($elgArr, $final_valueArr1[0]);
								    	
								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }

						}
					}
				}
			}
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
	    		}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($insId1 > 0){
			$msg = 'SPEL Exam Live Successfully...';
			return $msg;
		}	
	}

	public function insertSOB_exam(){
		

		$files = glob('uploads/Automation/SOB_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$centerArr = array();
		$elgArr = array();
		$feeArr1 = array();
		$feeArr2 = array();
		$mediumArr = array();
		$miscArr = array();
		$subjectArr = array();
		$activationArr = array();
		$validexamArr = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){

					$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

					if (($center_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($center_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|CTR_CD|CTR_NAM|STE_CD|STE_DSC|MODE_OF_EXAM') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($centerArr,$final_valueArr1[0]);

						    			$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);

								    	//echo '</br>'.$final_value;
								    	$center_sql.= "('".$final_value."'),";
								    	//echo '</br>'.$center_sql;
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"ELG")!=false){

					$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code, optForCandidate) VALUES ";

					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXAM CD|ELG PRD|PRT NO|MEMBER NO|MEMBER TYP|P/F/V/B|APP CAT|FEES|ELECT SUB|MED_CD|REMARK|SUB CD|OPT_FLG|') === false){

							    		//echo $value;
							    		$final_valueArr1 = explode('|', $value);
		
							    		unset($final_valueArr1[13]);

						    			array_push($elgArr, $final_valueArr1[0]);
								    	
								    	$final_value1 = implode($final_valueArr1, '|');
							    		
							    		$final_value1 = str_replace(',', ' ', $final_value1);
							    		$final_value1 = str_replace("'", ' ', $final_value1);
							    		$final_value1 = str_replace('|', "','", $final_value1);
							    		
								    	//echo '</br>'.count($final_valueArr1);

								    	//$final_value1 = rtrim($final_value1,"'");
								    	$elg_sql.= "('".$final_value1."'),";
								    	//echo $elg_sql;
							    	}
						    	}
			
						    }

						}
					}
				}

				if(strpos($file,"EXAM_MASTER")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if($key>0){

						    			$final_valueArr1 = explode('|', $value);
						    			
								    	if($final_valueArr1[8] == 'N'){
								    		$fee_insert1 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

								    		array_push($no_elearn_exam_code,$final_valueArr1[0]);
								    	}

								    	if($final_valueArr1[8] == 'Y'){
								    		$fee_insert2 = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, elearning_fee_amt, elearning_sgst_amt, elearning_cgst_amt, elearning_igst_amt, elearning_cs_amt_total, elearning_igst_amt_total, fr_date, to_date, exempt) VALUES";
								    		array_push($elearn_exam_code,$final_valueArr1[0]);
								    	}
						    		}
								}
							 }
						}
					}
				}

				if(strpos($file,"FEE")!=false){
					if (($fee_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; die;
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false && strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|ELEARNING_FEE_AMT|ELEARNING_SGST_AMT|ELEARNING_CGST_AMT|ELEARNING_IGST_AMT|ELEARNING_CS_TOT|ELEARNING_IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			//echo 'if'; die;
						    			$final_valueArr1 = explode('|', $value);
						    			//print_r($final_valueArr1); die;

						    			//echo 'no_elearn_exam_code';
						    			//print_r($no_elearn_exam_code);
						    			//echo 'elearn_exam_code';
						    			//print_r($elearn_exam_code);
						    			if(count($no_elearn_exam_code) > 0){
						    				foreach ($no_elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>no_elearn_exam_code+++'.$final_valueArr1[0];
							    					
									    			array_push($feeArr1, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value1 = str_replace('|', "','", $final_value);
											    	//echo $final_value1;
											    	$fee_sql1.= "('".$final_value1."'),";
							    				}
							    			}
							    		}

							    		if(count($elearn_exam_code) > 0){
							    			foreach ($elearn_exam_code as $k => $v) {
							    				if($final_valueArr1[0] == $v){
							    					//echo '<pre>elearn_exam_code---'.$final_valueArr1[0];
							    					
									    			array_push($feeArr2, $final_valueArr1[0]);

							    					$final_value = implode($final_valueArr1, '|');
							    		
							    					$final_value2 = str_replace('|', "','", $final_value);
											    	//echo $final_value2;
											    	$fee_sql2.= "('".$final_value2."'),";
							    				}
							    			}
							    		}	
						    		}
								}
							}
						}
					}
					//echo '---'.$fee_sql1.'---'.$fee_sql2;
				}

				if(strpos($file,"MEDIUM")!=false){

					$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

					if (($medium_handle = fopen($file, "r")) !== FALSE) {
					//echo 'if' ;die;
						$readFile =  fread($medium_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|MED_CD|MED_DESC') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($mediumArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$medium_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"MISC")!=false){

					$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

					if (($misc_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($misc_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_MTH|TRG_VAL_UPTO') === false){
						    			//echo $value;

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($miscArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$misc_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}

				if(strpos($file,"SUBJECT_MASTER")!=false){

					$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

					if (($subject_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($subject_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|SUB_CD|SUB_DSC|GRP_CD|EXM_DT|EXM_TIME') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($subjectArr, $final_valueArr1[0]);

								    	$final_value = implode($final_valueArr1, '|');
							    		
							    		$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    	$subject_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}
				
				if(strpos($file,"EXAM_ACTIVATE")!=false){

					$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date) VALUES";

					if (($exam_activate_handle = fopen($file, "r")) !== FALSE) {
						//echo 'if' ;die;
						$readFile =  fread($exam_activate_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|FROM_DATE|TO_DATE') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			array_push($activationArr, $final_valueArr1[0])

						    			//$fromdateArr[$final_valueArr1[0]] = $final_valueArr1[2];
						    			//$todateArr[$final_valueArr1[0]] = $final_valueArr1[3];

						    			$final_value = implode('|', $final_valueArr1);
								    	$final_value = str_replace('|', "','", $final_value);
								    	//echo $final_value;
								    
								    	$exam_activate_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
					}
				}

				/*if(strpos($file,"EXAM_DATE_MASTER")!=false){

					$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";

					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
						//print_r($arr);
			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){
						    			//echo $value;
						    			$final_valueArr1 = explode('|', $value);

						    			
						    				
						    			$final_value = implode($final_valueArr1, '|');		
						    			$final_value = str_replace('|', "','", $final_value);

								    	//echo $final_value;
								    	foreach ($fromdateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$from_date = $v;
								    		}
								    	}
								    	
								    	foreach ($todateArr as $k => $v) {
								    		if($k = $final_valueArr1[0]){
								    			$to_date = $v;
								    		}
								    	}

								    	if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			$this->db->select('id');
						    			$check_activation_exists = $this->master_model->getRecords('valid_examination_date', array('exam_code' => $final_valueArr1[0]));

						    			if(count($check_activation_exists) > 0){
						    				$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$final_valueArr1[0]);
						    			}

						    			//print_r($final_valueArr1);
						    			

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        }
			    }*/
			
				if(strpos($file,"Venue")!==false){
					$inputFileType = PHPExcel_IOFactory::identify($file);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file);
					$venueData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//print_r($venueData); die;
					foreach($venueData as $key => $venue) {
				    	if($key > 1){
				    		$filtered_venue = array_filter($venue); 
				    		if(count($filtered_venue)>0){

				    			$this->db->select('venue_master_id');
								$where = array('center_code' => $filtered_venue['C'], 'session_time' => $filtered_venue['D'], 'venue_code' => $filtered_venue['F'], 'vendor_code' => $filtered_venue['A'], 'institute_code' => 0);
				    			$check_venue_exists = $this->master_model->getRecords('venue_master', $where);

				    			//$ex_date = explode('/',$filtered_venue['B']);
				    			$createdon = date('Y-m-d');

				    			//$exam_date = $ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0];
				    			$date = new DateTime($filtered_venue['C']);
								$exam_date = $date->format('Y-m-d');
								
				    			//$exam_date = date("Y-m-d", strtotime($filtered_activation['C']));

				    			if(count($check_venue_exists) > 0){
				    				//echo 'update'; die;
				    				$this->db->query('DELETE FROM venue_master WHERE venue_master_id ='.$check_venue_exists[0]['venue_master_id']);
				    				/*$uploadData = array('exam_date' => $exam_date, 'venue_name' => $filtered_venue['G'], 'venue_addr1' => $filtered_venue['H'], 'venue_addr2' => $filtered_venue['I'], 'venue_addr3' => $filtered_venue['J'], 'venue_addr4' => $filtered_venue['K'], 'venue_addr5' => $filtered_venue['L'], 'venue_pincode' => $filtered_venue['M'], 'pwd_enabled' => $filtered_venue['N']);
				    				$insId9 = $this->master_model->updateRecord('venue_master',$uploadData, $where);*/
				    			}
				    			//else{
				    				//echo 'insert'; die;
				    				$insId9 = $this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$exam_date.'","'.$filtered_venue['D'].'","'.$filtered_venue['E'].'","'.$filtered_venue['F'].'","'.$filtered_venue['G'].'","'.$filtered_venue['H'].'","'.$filtered_venue['I'].'","'.$filtered_venue['J'].'","'.$filtered_venue['K'].'","'.$filtered_venue['L'].'","'.$filtered_venue['M'].'","'.$filtered_venue['N'].'","'.$filtered_venue['O'].'","'.$filtered_venue['B'].'")');
				    			//}
				    		}
				    	}
				    }
				}
			}
		}

		if($center_sql != ''){
			if(count($centerArr) > 0){
				$center_arr = array_filter($centerArr); 
				for ($i=0; $i < count($center_arr); $i++) { 
				    $this->db->query('DELETE FROM center_master WHERE exam_name ='.$center_arr[$i]);
				}
			}
			$center_insert.=rtrim($center_sql,",").';';
			$insId1 = $this->db->query($center_insert);
		}

		if($elg_sql != ''){
			if(count($elgArr) > 0){
				$elg_arr = array_filter($elgArr); 
				for ($i=0; $i < count($elg_arr); $i++) { 
					$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$elg_arr[$i]);
				}
			}
			$elg_insert.=rtrim($elg_sql,",").';';
			$insId2 = $this->db->query($elg_insert);
		}

		if($fee_sql1 != ''){
			if(count($feeArr1) > 0){
				$fee_arr = array_filter($feeArr1); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
			$insId3 = $this->db->query($fee_insert1);
			//echo 'fee_insert1---'.$this->db->last_query();
		}
		
		if($fee_sql2 != ''){
			if(count($feeArr2) > 0){
				$fee_arr = array_filter($feeArr2); 
				for ($i=0; $i < count($fee_arr); $i++) { 
					$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$fee_arr[$i]);
	    		}
			}
			$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
			$insId4 = $this->db->query($fee_insert2);
			//echo 'fee_insert2---'.$this->db->last_query();
		}

		if($medium_sql != ''){
			if(count($mediumArr) > 0){
				$medium_arr = array_filter($mediumArr); 
				for ($i=0; $i < count($medium_arr); $i++) { 
					$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$medium_arr[$i]);
	    		}
			}
			$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
			$insId5 = $this->db->query($medium_insert);
		}

		if($misc_sql != ''){
			if(count($miscArr) > 0){
				$misc_arr = array_filter($miscArr); 
				for ($i=0; $i < count($misc_arr); $i++) { 
					$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$misc_arr[$i]);
	    		}
			}
			$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
			$insId6 = $this->db->query($misc_insert);
		}

		if($subject_sql != ''){
			if(count($subjectArr) > 0){
				$subject_arr = array_filter($subjectArr); 
				for ($i=0; $i < count($subject_arr); $i++) { 
					$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$subject_arr[$i]);
	    		}
	    	}
			$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
			$insId7 = $this->db->query($subject_insert);
		}

		if($exam_activate_sql != ''){
			if(count($activationArr) > 0){
				$activation_arr = array_filter($activationArr); 
				for ($i=0; $i < count($activation_arr); $i++) { 
					$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$activation_arr[$i]);
	    		}
	    	}
			$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
			$insId8 = $this->db->query($exam_activate_insert);
		}

		/*if($valid_exam_sql != ''){
			$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
			$insId9 = $this->db->query($valid_exam_insert);
		}*/
	
		if($insId1 > 0 || $insId2 > 0 || $insId3 > 0 || $insId4 > 0 || $insId5 > 0 || $insId6 > 0 || $insId7 > 0 || $insId8 > 0 || $insId9 > 0){
			$msg = 'SOB Exam Live Successfully...';
			return $msg;
		}	
	}
}


