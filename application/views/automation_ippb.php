<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Automation extends CI_Controller {

	public function __construct(){
		parent::__construct();
			
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('csc_exam_api_helper');
	}

	public function index()
	{

		$data['typeArr'] = $typeArr = array(array('id' => 1, 'name' => 'DRA Exam'),
									  array('id' => 2, 'name' => 'GARP Exam'));

		$this->load->view('automation',$data);		
	}

	public function start_process()
	{
		$type = $_POST['type'];
		//echo $type; die;
		if($type == 8){
			$msg = $this->insertCSC_exam();
		}
		else{
	        if(!empty($_FILES['txtfiles']['name']) && count(array_filter($_FILES['txtfiles']['name'])) > 0)
	        { 
			    $filesCount = count($_FILES['txtfiles']['name']); 

			    if($type == 1){ //DRA Exam
	        		$allfiles = glob('uploads/dra_exam_live/*'); //get all file names
	        		$uploadPath = 'uploads/dra_exam_live/'; 
	        	}
	        	else if($type == 2){ //DRA Result
	        		$allfiles = glob('uploads/dra_result_live/*'); //get all file names
	        		$uploadPath = 'uploads/dra_result_live/'; 
	        	}
	        	else if($type == 3){ //RPE Exam
	        		$allfiles = glob('uploads/rpe_exam_live/*'); //get all file names
	        		$uploadPath = 'uploads/rpe_exam_live/'; 
	        	}
	        	else if($type == 4){ //RPE Result
	        		$allfiles = glob('uploads/rpe_result_live/*'); //get all file names
	        		$uploadPath = 'uploads/rpe_result_live/'; 
	        	}
	        	else if($type == 5){ //Diploma Certification Exam
	        		$allfiles = glob('uploads/dipcert_exam_live/*'); //get all file names
	        		$uploadPath = 'uploads/dipcert_exam_live/'; 
	        	}
	        	else if($type == 6){ //Diploma Certification Result
	        		$allfiles = glob('uploads/dipcert_result_live/*'); //get all file names
	        		$uploadPath = 'uploads/dipcert_result_live/'; 
	        	}
	        	else if($type == 7){ //GARP Exam
	        		$allfiles = glob('uploads/garp_exam_live/*'); //get all file names
	        		$uploadPath = 'uploads/garp_exam_live/'; 
	        	}

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
	                $config['allowed_types'] = 'txt|csv'; 

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
			                    
		    }else{ 
		        $statusMsg = 'Please select only text and csv files to upload.'; 
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
	        		//echo '3'; die;
	        		$msg = $this->insertRPE_exam();
	        	}
	        	else if($type == 4){ //RPE Result
	        		$msg = $this->insertRPE_result();
	        	}
	        	else if($type == 5){ //Diploma Certification Exam
	        		//echo '3'; die;
	        		$msg = $this->insertDipcert_exam();
	        	}
	        	else if($type == 6){ //Diploma Certificatio Result
	        		$msg = $this->insertDipcert_result();
	        	}
	        	else if($type == 7){ //GARP Exam
	        		$msg = $this->insertGARP_exam();
	        	}
			}
		}
        echo $msg;
	}

	public function insertCSC_exam(){

	}

	public function insertDRA_exam(){

		$center_insert = "INSERT INTO dra_center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

		$elg_insert = "INSERT INTO dra_eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, institute_code, training_from, training_to) VALUES";

		$fee_insert = "INSERT INTO dra_fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

		$medium_insert = "INSERT INTO dra_medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

		$misc_insert = "INSERT INTO dra_misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

		$qry = $this->db->query('SELECT * FROM dra_exam_master WHERE (exam_code = 45 OR exam_code = 57)');
		$file_names = $qry->result_array();

		$files = glob('uploads/dra_exam_live/*'); //get all file names

		foreach($files as $file){

			if(is_file($file)){
			
				//$center_sql = '';
				if(strpos($file,"CENTER_MASTER")!=false){
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
				//die;
				
				if(strpos($file,"ELG")!=false){
					if (($eligible_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($eligible_handle,filesize($file));
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	//$elg_sql ='';
			        	if(!empty($arr)){
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

		$this->db->query('TRUNCATE TABLE  dra_center_master;');
		$this->db->query('TRUNCATE TABLE  dra_eligible_master;');
		$this->db->query('TRUNCATE TABLE  dra_fee_master;');
		$this->db->query('TRUNCATE TABLE  dra_medium_master;');
		$this->db->query('TRUNCATE TABLE  dra_misc_master;');

		$center_insert.=rtrim($center_sql,",").';';
		$elg_insert.=rtrim($elg_sql,",".PHP_EOL).';';
		$fee_insert.=rtrim($fee_sql,",".PHP_EOL).';';
		$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
		$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
		//echo $sql2;

		//echo $center_insert; die;
		
		$insId1 = $this->db->query($center_insert);
		$insId2 = $this->db->query($elg_insert);
		$insId3 = $this->db->query($fee_insert);
		$insId4 = $this->db->query($medium_insert);
		$insId5 = $this->db->query($misc_insert);

		//$this->updates();

		if($insId1 > 0 && $insId2 > 0 && $insId3 > 0 && $insId4 > 0 && $insId5 > 0){
			$msg = 'DRA Exam Live Successfully...';
			return $msg;
		}
		//echo $insert1.'<br/>'.$insert2;	
	}

	public function insertDRA_result(){
		$marks_insert = "INSERT INTO dra_marks(regnumber, exam_code, exam_period, center_code, center_name, part_no, subject_id, marks, status) VALUES";

		$member_insert = "INSERT INTO dra_memdetails(regnumber, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, inst_code, institute_name, institute_secname) VALUES";

		$allfiles = glob('uploads/dra_result_live/*'); //get all file names
		
		foreach($allfiles as $file){
			if(is_file($file)){
				if(strpos($file,"MARK_OBT")!=false){
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
							    	unset($final_valueArr[9]);
							    	
							    	$final_value = implode($final_valueArr, ',');
							    
							    	$marks_sql.= "('".$final_value."),";

							    }
						    }
						}
					}
				}

				if(strpos($file,"Member_List")!=false){
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
		    		$exp1 = explode('_', $exp[2]);
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

								$updated_id3 = $this->master_model->updateRecord(' dra_result_subject',$uploadData1,array('exam_code' => 45)); 

								$updated_id4 = $this->master_model->updateRecord(' dra_result_subject',$uploadData1,array('exam_code' => 57)); 
						    }
					    }
						
					}
				}
			}
		}

		$this->db->query('TRUNCATE TABLE  dra_marks;');
		$this->db->query('TRUNCATE TABLE  dra_memdetails;');

		$marks_insert.=rtrim($marks_sql,",").';';
		$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
		//echo $sql2;
		
		$insId1 = $this->db->query($marks_insert);
		$insId2 = $this->db->query($member_insert);

		if($insId1 > 0 && $insId2 > 0 && $updated_id1 > 0 && $updated_id1 > 0){
			$msg = 'DRA Result Live Successfully...';
			return $msg;
		}
	}
	
	public function insertRPE_exam(){
		//echo '---'; die; 

		$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

		$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES";


		$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

		$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

		$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

		$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, examination_date, from_date, to_date) VALUES";

		$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

		

		$files = glob('uploads/rpe_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){
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

						    			$this->db->query('DELETE FROM center_master WHERE exam_name ='.$final_valueArr1[0]);

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
					//echo $file;
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

						    			$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$final_valueArr1[0]);

								    	
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

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$final_valueArr1[0]);

						    			foreach ($no_elearn_exam_code as $k => $v) {
						    				if($final_valueArr1[0] == $v){
						    					
										    	$final_value1 = str_replace('|', "','", $value);
										    	//echo $final_value;
										    	$fee_sql1.= "('".$final_value1."'),";
						    				}
						    			}

						    			foreach ($elearn_exam_code as $k => $v) {
						    				if($final_valueArr1[0] == $v){
						    					
										    	$final_value2 = str_replace('|', "','", $value);
										    	//echo $final_value;
										    	$fee_sql2.= "('".$final_value2."'),";
						    				}
						    			}

						    		}
								}
							 }
						}
					}
				}
				
				if(strpos($file,"MEDIUM")!=false){
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

						    			$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$final_valueArr1[0]);

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

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$final_valueArr1[0]);

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

						    			$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$final_valueArr1[0]);

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

						    			//need to change date format to 2022-10-02
						    			$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$final_valueArr1[0]);

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
					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){

						    			$final_valueArr1 = explode('|', $value);
						    			unset($final_valueArr1[1]);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$final_valueArr1[0]);
						    			//print_r($final_valueArr1);
						    			$final_value = implode('|', $final_valueArr1);
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

								    	$final_value.="','".$from_date."','".$to_date;
								    	//echo '</br>'.$final_value;

								    	$valid_exam_sql.= "('".$final_value."'),";
								    }
							    }
						    }
						}
			        	
					}
				}

				$row = 1;
				if(strpos($file,"Venue")!==false){
					//echo 'yes';
					if (($venue_handle = fopen($file, "r")) !== FALSE) {
						//print_r(fgetcsv($venue_handle));
				        while (($row = fgetcsv($venue_handle, 1000, ",")) !== FALSE) 
						{  
							//print_r($data);
							//need to change date format to 2022-10-02
							$row[1] = date("Y-m-d", strtotime($row[1])); 

						  	$this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$row[1].'","'.$row[2].'","'.$row[3].'","'.$row[4].'","'.$row[5].'","'.$row[6].'","'.$row[7].'","'.$row[8].'","'.$row[9].'","'.$row[10].'","'.$row[11].'","'.$row[12].'","'.$row[13].'","'.$row[0].'")');
						}
					}
				}

			}
		}
		//die();
		//echo '</br>'.$fee_sql; die;
		
		$center_insert.=rtrim($center_sql,",").';';
		$elg_insert.=rtrim($elg_sql,",").';';
		$fee_insert1.=rtrim($fee_sql1,",".PHP_EOL).';';
		$fee_insert2.=rtrim($fee_sql2,",".PHP_EOL).';';
		$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
		$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
		$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
		$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
		$valid_exam_insert.=rtrim($valid_exam_sql,",".PHP_EOL).';';
		//echo $valid_exam_insert; die();

		//echo $center_insert; die;
		
		$insId1 = $this->db->query($center_insert);
		$insId2 = $this->db->query($elg_insert);
		$insId3 = $this->db->query($fee_insert1);
		$insId4 = $this->db->query($fee_insert2);
		$insId5 = $this->db->query($medium_insert);
		$insId6 = $this->db->query($misc_insert);
		$insId7 = $this->db->query($subject_insert);
		$insId8 = $this->db->query($exam_activate_insert);
		$insId9 = $this->db->query($valid_exam_insert);

		//$this->updates();

		//if($insId1 > 0 && $insId2 > 0 && $insId3 > 0 && $insId4 > 0 && $insId5 > 0 && $insId6 > 0 && $insId7 > 0 && $insId8 > 0 && $insId9 > 0){
			$msg = 'RPE Exam Live Successfully...';
			return $msg;
		//}	
	}

	public function insertRPE_result(){
		//echo 'insertRPE'; die;
		$marks_insert = "INSERT INTO dipcert_mark(regnumber, exam_id, exam_period, part_no, subject_id, marks, status, result_date) VALUES";

		$member_insert = "INSERT INTO dipcert_member(member_number, member_type, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode, exam_code, part_no, syllabus_code, dummy, dummy_one, no_of_attempt, dob, period) VALUES";

		$result_exam_insert = "INSERT INTO result_exam(exam_code, exam_name_full, exam_name_short, part_no, exam_frequency, exam_conduct, result_date, exam_period) VALUES";

		$result_subject_insert = "INSERT INTO result_subject(exam_code, exam_period, subject_code, subject_name, subject_name_short, part_no, syllabus_code) VALUES";

		$display_result_setting_insert = "INSERT INTO display_result_setting(exam_code, period, type) VALUES";

		$exam_period = '';
		$files = glob('uploads/rpe_result_live/*'); //get all file names
		foreach($files as $file){
	
		    if(is_file($file)){
		    	if(strpos($file, 'MARK_OBT') !== false){
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
		    		$exp = explode('/', $file);
		    		$exp1 = explode('_', $exp[2]);
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
					    			$final_value1 = $final_value1.$exp1[3];
					    			$exam_period = $exp1[3];
					    			$member_sql.= "('".$final_value1."'),";

								}
						    }
						}
					}
		    	}
		    	
		    	if(strpos($file, 'EXAM_LIST') !== FALSE){
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
						    		$final_valueArr1[1] = $final_valueArr1[1].' (RPE)';
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
		//die;
		$marks_insert.=rtrim($marks_sql,",").';';
		$member_insert.=rtrim($member_sql,",".PHP_EOL).';';
		$result_exam_insert.=rtrim($exam_sql,",").';';
		$result_subject_insert.=rtrim($subject_sql,",").';';
		$display_result_setting_insert.=rtrim($result_setting_sql,",").';';
		//echo '</br>marks_insert:'.$marks_insert;
		//echo '</br>member_insert:'.$member_insert;
		//echo '</br>result_exam_insert:'.$result_exam_insert;
		//echo '</br>result_subject_insert:'.$result_subject_insert;
		//echo '</br>display_result_setting_insert:'.$display_result_setting_insert;
		//die();

		//echo '</br>insert3:'.$insert3; die;
		
		$insId1 = $this->db->query($marks_insert);
		$insId2 = $this->db->query($member_insert);
		$insId3 = $this->db->query($result_exam_insert);
		$insId4 = $this->db->query($result_subject_insert);
		$insId5 = $this->db->query($display_result_setting_insert);
		
		//if($insId1 > 0 && $insId2 > 0 && $insId3 > 0 && $insId4 > 0 && $insId5 > 0){
			$url = base_url().'marksheet/dipcertresult/'.base64_encode($exam_period);
			$msg = 'RPE Result Live Successfully...';
			return $msg.'---'.$url;
		//}
	}

	public function insertDipcert_exam(){
		//echo '---'; die; 

		$center_insert = "INSERT INTO center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES";

		$elg_insert = "INSERT INTO eligible_master(exam_code, eligible_period, part_no, member_no, member_type, exam_status, app_category, fees, subject, med_cd, remark, subject_code) VALUES";


		$medium_insert = "INSERT INTO medium_master(exam_code, exam_period, medium_code, medium_description) VALUES";

		$misc_insert = "INSERT INTO misc_master(exam_code, exam_period, exam_month, trg_value) VALUES";

		$subject_insert = "INSERT INTO subject_master(exam_code, exam_period, part_no, syllabus_code, subject_code, subject_description, group_code, exam_date, exam_time) VALUES";

		/*$valid_exam_insert = "INSERT INTO valid_examination_date(exam_code, exam_period, examination_date, from_date, to_date) VALUES";*/

		$fee_insert = "INSERT INTO fee_master(exam_code, exam_period, part_no, syllabus_code, member_category, group_code, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, fr_date, to_date, exempt) VALUES";

		$exam_activate_insert = "INSERT INTO exam_activation_master(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES";

		
		$files = glob('uploads/dipcert_exam_live/*'); //get all file names
		//print_r($files);die();
		$no_elearn_exam_code = array();
		$elearn_exam_code = array();

		$fromdateArr = array();
		$todateArr = array();
		foreach($files as $file){
		    if(is_file($file)){

				if(strpos($file,"CENTER_MASTER")!=false){
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

						    			$this->db->query('DELETE FROM center_master WHERE exam_name ='.$final_valueArr1[0]);

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
					//echo $file;
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

						    			$this->db->query('DELETE FROM eligible_master WHERE exam_code ='.$final_valueArr1[0]);

						    			//echo $this->db->last_query();

								    	
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
				
				/*if(strpos($file,"EXAM_MASTER")!=false){
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
				}*/

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
						    		
						    		if(strpos($value, 'EXM_CD|EXM_PRD|PRT_NO|SYL_CD|MEM_CATEGORY|GRP_CD|FEE_AMT|SGST_AMT|CGST_AMT|IGST_AMT|CS_TOT|IGST_TOT|FRM_DATE|TO_DATE|EXEMPT') === false){
						    			$final_valueArr1 = explode('|', $value);

						    			$this->db->query('DELETE FROM fee_master WHERE exam_code ='.$final_valueArr1[0]);

						    			$final_value1 = str_replace('|', "','", $value);
										    	//echo $final_value;
										$fee_sql.= "('".$final_value1."'),";
						    		
						    		}
								}
							 }
						}
					}
				}
				
				if(strpos($file,"MEDIUM")!=false){
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

						    			
						    			$this->db->query('DELETE FROM medium_master WHERE exam_code ='.$final_valueArr1[0]);

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

						    			$this->db->query('DELETE FROM misc_master WHERE exam_code ='.$final_valueArr1[0]);

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

						    			$this->db->query('DELETE FROM subject_master WHERE exam_code ='.$final_valueArr1[0]);

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

						    			//need to change date format to 2022-10-02
						    			$this->db->query('DELETE FROM exam_activation_master WHERE exam_code ='.$final_valueArr1[0]);

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

				/*if(strpos($file,"EXAM_DATE")!=false){
					if (($exam_date_handle = fopen($file, "r")) !== FALSE) {
						$readFile =  fread($exam_date_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);

			        	if(!empty($arr)){
						    foreach ($arr as $key => $value) {
						    	if(!empty($value)){
						    		if(strpos($value, 'EXM_CD|EXM_PRD|EXM_DT') === false){

						    			$final_valueArr1 = explode('|', $value);

						    			if($final_valueArr1[0] == '1017'){
						    				$final_valueArr1[0] = '2027';
						    			}

						    			$this->db->query('DELETE FROM valid_examination_date WHERE exam_code ='.$final_valueArr1[0]);

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
				}*/

				$row = 1;
				if(strpos($file,"Venue")!==false){
					//echo 'yes';
					if (($venue_handle = fopen($file, "r")) !== FALSE) {
						//print_r(fgetcsv($venue_handle));
				        while (($row = fgetcsv($venue_handle, 1000, ",")) !== FALSE) 
						{  
							//print_r($data);
							//need to change date format to 2022-10-02
							$row[1] = date("Y-m-d", strtotime($row[1])); 

						  	$this->db->query('INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ("'.$row[1].'","'.$row[2].'","'.$row[3].'","'.$row[4].'","'.$row[5].'","'.$row[6].'","'.$row[7].'","'.$row[8].'","'.$row[9].'","'.$row[10].'","'.$row[11].'","'.$row[12].'","'.$row[13].'","'.$row[0].'")');
						}
					}
				}

			}
		}
		//die();
		//echo '</br>'.$fee_sql; die;
		
		//$center_insert.=rtrim($center_sql,",").';';
		//$elg_insert.=rtrim($elg_sql,",").';';
		//$fee_insert.=rtrim($fee_sql,",".PHP_EOL).';';
		//$medium_insert.=rtrim($medium_sql,",".PHP_EOL).';';
		//$misc_insert.=rtrim($misc_sql,",".PHP_EOL).';';
		//$subject_insert.=rtrim($subject_sql,",".PHP_EOL).';';
		//$exam_activate_insert.=rtrim($exam_activate_sql,",".PHP_EOL).';';
		//echo $valid_exam_insert; die();

		//echo $center_insert; die;
		
		//$insId1 = $this->db->query($center_insert);
		//$insId2 = $this->db->query($elg_insert);
		//$insId3 = $this->db->query($fee_insert);
		//$insId4 = $this->db->query($medium_insert);
		//$insId5 = $this->db->query($misc_insert);
		//$insId6 = $this->db->query($subject_insert);
		//$insId7 = $this->db->query($exam_activate_insert);
		
		//$this->updates();

		//if($insId1 > 0 && $insId2 > 0 && $insId3 > 0 && $insId4 > 0 && $insId5 > 0 && $insId6 > 0 && $insId7 > 0){
			$msg = 'Diploma Certification Exam Live Successfully...';
			return $msg;
		//}	
	}

	public function insertGARP_exam(){

		//$elg_insert = "INSERT INTO garp_exam_eligible(member_no, exam_code, exam_period, qualification, status, attempt, fee_flag, mem_name) VALUES";

		$fee_insert = "INSERT INTO garp_exam_fee_master(exam_code, mem_type, fee_amount, sgst_amt, cgst_amt, igst_amt, cs_tot, igst_tot, exempt) VALUES";

		$files = glob('uploads/garp_exam_live/*'); //get all file names
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
		    		//echo '---'; //die;
		    		if (($fee_handle = fopen($file, "r")) !== FALSE) {
		    			$readFile =  fread($fee_handle,filesize($file));
						//echo $readFile; 
						$arr = explode("\n",
			                str_replace(["\r\n","\n\r","\r"],"\n",$readFile)
			        	);
			        	if(!empty($arr)){
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
		    	
		    	if(strpos($file,"GARP_Eligible.csv")!==false){
		    		$this->db->query('TRUNCATE TABLE  garp_exam_eligible;');
					//echo '---'.$file;
					if (($elg_handle = fopen($file, "r")) !== FALSE) {
						//print_r(fgetcsv($elg_handle));
						while (($row = fgetcsv($elg_handle, 1000, ",")) !== FALSE) 
						{ 
							//print_r($row);
							$this->db->query('INSERT INTO garp_exam_eligible(member_no, exam_code, exam_period, qualification, status) VALUES ("'.$row[0].'","'.$exam_code.'","'.$exam_period.'","'.$row[3].'","P")');
							//echo $this->db->last_query();
						}
					}
				}
			}
		}

		$this->db->query('TRUNCATE TABLE garp_exam_fee_master;');
		
		//die;
		//$elg_insert.=rtrim($elg_sql,",".PHP_EOL).';';
		$fee_insert.=rtrim($fee_sql,",".PHP_EOL).';';

		//echo $fee_insert; 
		//die();

		//$insId1 = $this->db->query($elg_insert);
		$insId1 = $this->db->query($fee_insert);

		if($insId1 > 0){
			$msg = 'GARP Exam Live Successfully...';
			return $msg;
		}	
	}
}