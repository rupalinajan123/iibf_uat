<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BulkApplyExcel extends CI_Controller {
	public function __construct()
	{
		parent::__construct(); 

		$this->load->library('excel');
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		 $this->load->helper('master_helper');
		 $this->load->model('Master_model');
		 $this->load->library('email');
		 $this->load->model('Emailsending');
		 //$this->load->model('Emailsending_123');
		 //$this->load->helper('bulk_admitcard_helper');
		 $this->load->helper('custom_contact_classes_invoice_helper');
		 $this->load->helper('custom_admitcard_helper');
		 //$this->load->helper('bulk_check_helper');
		 //$this->load->helper('bulk_seatallocation_helper');
		 $this->load->helper('bulk_invoice_helper');
		 $this->load->helper('bulk_admitcard_helper');
		 $this->load->model('chk_session');
		 $this->load->model('log_model');
		 $this->chk_session->chk_bank_login_session();
	
		if($this->router->fetch_method()!='read_xlsx')
		{
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			/* if($this->session->userdata('examcode'))
			{
				$this->session->unset_userdata('examcode');
			}*/
		}
		//exit;
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data=array('middle_content'=>'bulk/bulk_add_member_excel');
		$this->load->view('bulk/bulk_common_view',$data);
	}
	
	public function read_xlsx(){
		$success = '';
		$sucess_flag = 0;
		$this->load->library('Excel');
 		$data = $allDataInSheet=array();
		if(isset($_POST['submit'])){ 
			$this->form_validation->set_rules('csv_file','File for uploading','file_required|file_allowed_type[xlsx]|file_size_max[2000]');
			
			
			if($this->form_validation->run()==TRUE){ 
				$filename=$_FILES["csv_file"]["tmp_name"];
				 try{
					$xlsx = PHPExcel_IOFactory::load($filename);
					$allDataInSheet = $xlsx->getActiveSheet()->toArray(null);
				}
				 catch(Exception $e){
					 $this->resp->success = FALSE;
					 $this->resp->msg = 'Error Uploading file';
					 echo json_encode($this->resp);
					 exit;
				}
				
				
				if(count($allDataInSheet[0]) == 5){ 
					$i = 0; // regnumner
					$j = 1; // exam name
					$m = 2; // center name
					$p = 3; // medium
					$q = 4; // scribe
					//$r = 5; // exam_mode
					$c = 0;
					$total_record = sizeof($allDataInSheet) - 1;
					$cc = 0;
					$dup_arr = array();
					for($z = 1; $z < sizeof($allDataInSheet); $z++){
						if($allDataInSheet[$z][$i] == ''){
							
						}else{
							$cc++;
							$dup_arr[] = $allDataInSheet[$z][$i];
						}
					}
					
					$a = array_unique($dup_arr);
					$cnt1 = count($dup_arr);
					$cnt2 = count($a);
					
					if($cnt1 != $cnt2){
						$data['errmsg'] =  "Duplicate record in file";
					}elseif($cnt1 == $cnt2){
						
					
					
					if(sizeof($allDataInSheet) > 1){
						
					//for($s = 1; $s < sizeof($allDataInSheet); $s++){
					for($s = 1; $s < $cc+1; $s++){
					
						if($allDataInSheet[$s][$i] == '' || $allDataInSheet[$s][$j] == 'Select Exam' || $allDataInSheet[$s][$m] == 'Select Center'|| $allDataInSheet[$s][$p] == 'Select Medium'|| $allDataInSheet[$s][$q] == 'Select Scribe'){
							$rn = $s+1;
							$data['errmsg'] =  "Row number ".$rn." have  Wrong data in file";
						}else{
						//print_r($allDataInSheet);exit;
							if($this->session->userdata('examcode') == '')
							{
								redirect(base_url().'bulk/Bankdashboard');
							}
							$session_exm_cd = $this->session->userdata('examcode');
							
							
							$excd = explode("=",$allDataInSheet[$s][$j]);
							$exam_code = $excd[1];
							if($exam_code == '')
							{
								$this->session->set_flashdata('error','Wrong exam code...');
							    redirect(base_url().'bulk/BulkApply/exam_applicantlst');
							}
							if($session_exm_cd != $exam_code)
							{
								$this->session->set_flashdata('error',"Exam code not match!!");
								redirect(base_url('bulk/BulkApply/examlist/'));
							}
							
							$this->db->select('exam_master.exam_code,exam_period');
							$this->db->from('exam_master');
							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$this->db->where('bulk_exam_activation_master.exam_code',$exam_code);
							$this->db->where("exam_to_date >",date('Y-m-d'));
							$record = $this->db->get();
							$ex_info = $record->row();
							
							$cncd = explode("=",$allDataInSheet[$s][$m]);
							$center_code = $cncd[1];
							if($center_code == '')
							{
								$this->session->set_flashdata('error','Wrong center name...');
							    redirect(base_url().'bulk/BulkApply/exam_applicantlst');
							}
							$mdcd = explode("=",$allDataInSheet[$s][$p]);
							$medium = $mdcd[1];
							if($medium == '')
							{
								$this->session->set_flashdata('error','Wrong medium...');
							    redirect(base_url().'bulk/BulkApply/exam_applicantlst');
							}
							
							// Required Inputs
							$regnumber = $allDataInSheet[$s][$i];
							//$exam_code = $excd[1];
							$exam_period = $ex_info->exam_period;
							
							$exam_mode = 'Online';
							$institute_code = $this->session->userdata('institute_id');
							//$medium = $mdcd[1];
							
							/*echo 'regnumber',$regnumber,'</br>';
							echo 'exam_code',$exam_code,'</br>';
							echo 'exam_period',$exam_period,'</br>';
							echo 'center_code',$center_code,'</br>';
							echo 'exam_mode',$exam_mode,'</br>';
							echo 'institute_code',$institute_code,'</br>';
							echo 'medium',$medium,'</br>';
							echo 'scribe_flag',$scribe_flag,'</br>';
							//exit;*/
							
							$chk_one = bulk_check_is_member($regnumber);
							if($chk_one['flag'] == 0){
								$data['errmsg'] =  $chk_one['msg'];
								break;
							}else{
							
								//$regnumber = $allDataInSheet[$s][$i] ;
								$chk_two = bulk_is_profile_complete($regnumber);
								if($chk_two['flag'] == 0){
									$data['errmsg'] =  $chk_two['msg'];
									break;
								}else{
									
									//check whether fee avialaible or not for member
									$this->db->where('regnumber',$regnumber);
									$this->db->where('isactive','1');
									$member_info = $this->master_model->getRecords('member_registration');
									
									//$this->db->join('member_registration','member_registration.registrationtype = fee_master.member_category');
									//$this->db->where('regnumber',$regnumber);
									$this->db->where('fee_master.exam_code',$exam_code);
									$this->db->where('fee_master.exam_period',$exam_period);
									$this->db->where('fee_master.member_category',$member_info[0]['registrationtype']);
									$fee_chk = $this->master_model->getRecords('fee_master','','fee_amount'); 
									
									if($fee_chk[0]['fee_amount'] == '')
									{
										$data['errmsg'] = $regnumber." have invalid member type";
										break;
									}
									
									/*$this->db->where('member_no',$regnumber);
									$this->db->where('exam_code',$exam_code);
									$this->db->where('eligible_period',$exam_period);
									$eli_chk = $this->master_model->getRecords('eligible_master','','exam_status,remark'); 
									$ec = 0;
									foreach($eli_chk as $eli_chk_rec){
										if($eli_chk_rec['exam_status']=='F'){
											$ec++;
										}
									}
									
									if(count($eli_chk) != $ec){*/
									$chk_checkqualify = bulk_checkqualify_exam($regnumber,$exam_code,$exam_period);
									if($chk_checkqualify['flag'] == 0){
										$data['errmsg'] = $regnumber." ".'Valid application exist, Attempt remaining';
										//$data['errmsg'] = $regnumber." ".$chk_checkqualify['msg'];
										break;
									}
									
									$chk_three = bulk_check_exam_activate($exam_code);
									if($chk_three['flag'] == 0){ 
										$data['errmsg'] =  $chk_three['msg'];
										break;
									}else{ 
									
										if($exam_code == 33 || $exam_code == 47 || $exam_code == 51 || $exam_code == 52 )
										{
											$chk_four = bulk_checkusers($regnumber,$exam_code,$exam_period);
											if($chk_four['flag'] == 0){
												$data['errmsg'] =  $chk_four['msg'];
												break;
											}
										}
									else{
											
											if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72){
												$chk_five = bulk_checkqualify($regnumber,$exam_code,$exam_period,$member_type = 'O');
												$chk_five_flag = $chk_five['flag'];
											}else{
												$chk_five_flag = 0;
											}
											
											if($chk_five_flag == 1){
												$data['errmsg'] =  $chk_five['msg'];
												break;
											}else{
												$chk_six = bulk_check_exam_application($regnumber,$exam_code,$exam_period);
												if($chk_six['flag'] == 0){ 
													$data['errmsg'] =  $chk_six['msg'];
													break;
												}else{
													$chk_seven = bulk_examdate($regnumber,$exam_code);
													if($chk_seven['flag'] == 1){
														$data['errmsg'] =  $chk_seven['msg'];
														break;
													}else{
													
														$subject_code_arr = get_member_subjects($regnumber,$exam_code,$institute_code);
														
														foreach($subject_code_arr as $subject_code)
														{
														
														    //echo $regnumber;
															$subject_code_c = $subject_code['subject_code'];
															$chk_eight = bulk_excel_chk_capacity($regnumber,$exam_code,$exam_period,$center_code,$subject_code_c);
															///echo 'chk_eight:',$chk_eight['flag'],'</br>';
															if($chk_eight['flag'] == 0)
															{
																$chk = 0;
																break;
															}
															else
															{
																$chk = 1;
															}
															if($chk == 0)
															{
															 break;
															}
														}// end of foreach
														
														if($chk == 0){
															$data['errmsg'] = 'capacity not available';
															break;
														}else{
															$c++;
															$success = 'success';
															/*echo "####". $total_record;
															echo "<br/>";
															echo ">>>".$c;
															echo "<br/>";*/
														}
													}
												}
											}
										}
									}
								}
							}
						}
						
					}// end of for
				//	exit;
				/*echo "<br/>";
				echo "**************";
				echo "<br/>";
				echo ">>".$c;
				echo "<br/>";
				echo "##".$cc;
				echo "<br/>";
				
				if($c != $cc){
					echo "here";
					//exit;
				}*/
			
				if($c != $cc){
				
					$hold_update = array("is_delete"=>'1');
					$wh = array("inst_id"=>$this->session->userdata('institute_id'),'exam_code'=>$exam_code,'exam_period'=>$exam_period);
					$this->master_model->updateRecord('bulk_excel_temp',$hold_update,$wh);
					//exit;
				}
				
					//insert record in member_exam and admit_card_details
					//if($c == $total_record && $success == 'success'){
					//if($data['errmsg']=='')
					if($data['errmsg']=='')
					{
						if($c == $cc && $success == 'success'){
					$hold_update = array("is_delete"=>'1');
						$wh = array("inst_id"=>$this->session->userdata('institute_id'),'exam_code'=>$exam_code,'exam_period'=>$exam_period);
						$this->master_model->updateRecord('bulk_excel_temp',$hold_update,$wh);
					//for($s = 1; $s < sizeof($allDataInSheet); $s++){
					for($s = 1; $s < $cc+1; $s++){
					//echo 'in';exit;
					
					$excd = explode("=",$allDataInSheet[$s][$j]);
							
							$this->db->select('exam_master.exam_code,exam_period');
							$this->db->from('exam_master');
							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$this->db->where('bulk_exam_activation_master.exam_code',$exam_code);
							$this->db->where("exam_to_date >",date('Y-m-d'));
							$record = $this->db->get();
							$ex_info = $record->row();
							
							
							
							$cncd = explode("=",$allDataInSheet[$s][$m]);
							$mdcd = explode("=",$allDataInSheet[$s][$p]);
							
							// Required Inputs
							$regnumber = $allDataInSheet[$s][$i];
							$exam_code = $excd[1];
							$exam_period = $ex_info->exam_period;
							$center_code = $cncd[1];
							$exam_mode = 'Online';
							$institute_code = $this->session->userdata('institute_id');
							$medium = $mdcd[1];
							$scribe_flag = $allDataInSheet[$s][$q];
							
							/*echo 'regnumber',$regnumber,'</br>';
							echo 'exam_code',$exam_code,'</br>';
							echo 'exam_period',$exam_period,'</br>';
							echo 'center_code',$center_code,'</br>';
							echo 'exam_mode',$exam_mode,'</br>';
							echo 'institute_code',$institute_code,'</br>';
							echo 'medium',$medium,'</br>';
							echo 'scribe_flag',$scribe_flag,'</br>';
							exit;*/
					/*		
							$regnumber = $allDataInSheet[$s][$i];
						
							
							
							$this->db->select('exam_master.exam_code,exam_period');
							$this->db->from('exam_master');
							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$this->db->like("description",$excd[0]);
							//$this->db->like("description",$allDataInSheet[$s][$j]);
							$this->db->where("exam_to_date >",date('Y-m-d'));
							$record = $this->db->get();
							$ex_info = $record->row();
							
							//$exam_code = 11;	
							//$exam_period = 417;
							
							$this->db->select('center_code');
							$this->db->from('center_master');
							//$this->db->where("center_name ",$allDataInSheet[$s][$m]);
							$this->db->where("center_name ",$cncd[0]);
							$record_1 = $this->db->get();
							$center_info = $record_1->row();
							
							$center_code = $center_info->center_code;
							//$subject_code = $allDataInSheet[$s][$n];
							$institute_code = $this->session->userdata('institute_id');
							$medium = $allDataInSheet[$s][$p];
							
							//##--------------get medium code
							$this->db->select('medium_code'); 
							$this->db->from('medium_master');
							//$this->db->where('medium_description',$medium);
							$this->db->where('medium_description',$mdcd[0]);
							$record_2 = $this->db->get();
							$medium_info = $record_2->row();
							$medium = $medium_info->medium_code;*/
							
							//$exam_mode = $allDataInSheet[$s][$q];
							//$exam_mode = 'ON';
							
							
							//get member_info
							$this->db->where("isactive",'1'); 
							$mem_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber));
																
							//get group code
							$this->db->where("eligible_master.member_no",$regnumber);
							$this->db->where("eligible_master.app_category !=",'R');
							$this->db->where('eligible_master.exam_code',$exam_code); 
							$examinfo=$this->master_model->getRecords('eligible_master');
															
							if(isset($examinfo[0]['app_category'])){
								$grp_code=$examinfo[0]['app_category'];
							}else{
								$grp_code='B1_1';
							};
															    
							//If exam is special exam
							$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code));
																
							$special_exam_date = '';
							if($exam_category[0]['exam_category']==1)
							{
								
								$today_date=date('Y-m-d');
								$this->db->where("'$today_date' BETWEEN from_date AND to_date");
								$special_exam_dates=$this->master_model->getRecords('special_exam_dates');
								$special_exam_date = $special_exam_dates[0]['examination_date'];
							}
					
															
							$amount=bulk_getExamFee($center_code,$exam_period,$exam_code,$grp_code,$mem_info[0]['registrationtype']);
							
							
							
							//##------------get app_category and base_fee
							$fee_result=bulk_getFee_Appcat($center_code,$exam_period,$exam_code,$grp_code,$mem_info[0]['registrationtype']);
							
									
							
							//print_r($fee_result['base_fee']);
							$inser_array=array('regnumber'=>$regnumber,
							'member_type'=>$mem_info[0]['registrationtype'],
							//'app_category'=>$fee_result['grp_code'],
							'app_category'=>$grp_code,
							'base_fee'=>$fee_result['base_fee'],
							'exam_code'=>$exam_code,
							'exam_mode'=>'ON',
							'exam_medium'=>$medium,
							'exam_period'=>$exam_period,
							'exam_center_code'=>$center_code,
							'exam_fee'=>$amount,
							'examination_date'=>$special_exam_date,
							'scribe_flag'=>$scribe_flag,
							'created_on'=>date('y-m-d H:i:s'),
							'institute_id'=>$institute_code,
							'pay_status'=>'2',
							'bulk_isdelete'=>'0'
							);
							/*echo "<pre>";
							print_r($inser_array);
							exit;*/
						//$exam_last_id = 1;
					if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_array,true)){
					
						//##----------prepare user name
						$username=$mem_info[0]['firstname'].' '.$mem_info[0]['middlename'].' '.$mem_info[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						
						//##----------set invoice details
						$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'center_code'=>$center_code,'exam_period'=>$exam_period,'center_delete'=>'0'));
						
						 //##---------check Gender
						if($mem_info[0]['gender']=='male')
						{$gender='M';}
						else
						{$gender='F';}
						
						//##----------get state name
						$states=$this->master_model->getRecords('state_master',array('state_code'=>$mem_info[0]['state'],'state_delete'=>'0'));
						$state_name='';
						if(count($states) >0)
						{
							$state_name=$states[0]['state_name'];
						}		
						
						$password = random_password();

						$compulsory_subjects = get_member_subjects($regnumber,$exam_code,$institute_code);
						
						if(!empty($compulsory_subjects))
						{
								foreach($compulsory_subjects as $y)
								{
								
										$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$exam_period,'subject_code'=>$y['subject_code']),'subject_description');
									    
										//##-----------get venue,exam_date,exam_time dynamically
										$venue_info=bulk_excel_get_capacity($exam_code,$exam_period,$center_code,$y['subject_code']);
										
									
									
									if($venue_info['flag'] == 0)
									{
									  $data['errmsg'] = 'venue not available';
									  break;
									}
									else
									{
									
										$venueid=$venue_info['venue_arry']['venue_code'];
										$venue_name=$venue_info['venue_arry']['venue_name'];
										$venueadd1=$venue_info['venue_arry']['venue_addr1'];
										$venueadd2=$venue_info['venue_arry']['venue_addr2'];
										$venueadd3=$venue_info['venue_arry']['venue_addr3'];
										$venueadd4=$venue_info['venue_arry']['venue_addr4'];
										$venueadd5=$venue_info['venue_arry']['venue_addr5'];
										$venpin=$venue_info['venue_arry']['venue_pincode'];
										$exam_date=$venue_info['venue_arry']['exam_date'];
										$time=$venue_info['venue_arry']['session_time'];
										$vendor_code=$venue_info['venue_arry']['vendor_code'];
									
									}
									
									$admitcard_insert_array=array('mem_exam_id'=>$exam_last_id,
																'center_code'=>$getcenter[0]['center_code'],
																'center_name'=>$getcenter[0]['center_name'],
																'mem_type'=>$mem_info[0]['registrationtype'],
																'mem_mem_no'=>$regnumber,
																'g_1'=>$gender,
																'mam_nam_1'=>$userfinalstrname,
																'mem_adr_1'=>$mem_info[0]['address1'],
																'mem_adr_2'=>$mem_info[0]['address2'],
																'mem_adr_3'=>$mem_info[0]['address3'],
																'mem_adr_4'=>$mem_info[0]['address4'],
																'mem_adr_5'=>$mem_info[0]['district'],
																'mem_adr_6'=>$mem_info[0]['city'],
																'mem_pin_cd'=>$mem_info[0]['pincode'],
																'state'=>$state_name,
																'exm_cd'=>$exam_code,
																'exm_prd'=>$exam_period,
																'sub_cd '=>$y['subject_code'],
																'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
																'm_1'=>$medium,
																'venueid'=>$venueid,
																'venue_name'=>$venue_name,
																'venueadd1'=>$venueadd1,
																'venueadd2'=>$venueadd2,
																'venueadd3'=>$venueadd3,
																'venueadd4'=>$venueadd4,
																'venueadd5'=>$venueadd5,
																'venpin'=>$venpin,
																'exam_date'=>$exam_date,
																'time'=>$time,
																'vendor_code'=>$vendor_code,
																'pwd'=>$password,
																'mode'=>$exam_mode,
																'scribe_flag'=>$scribe_flag,
																'remark'=>2,
																'record_source'=>'Bulk',
																'record_mode'=>'1',
																'created_on'=>date('Y-m-d H:i:s'));
													//echo '<pre>',print_r($admitcard_insert_array),'</pre>';		
																
									$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
									
								}
						}
						 
						$data['succmsg'] =  "Application added sucessfully...";
						$sucess_flag = 1;
						//echo 'Insert admit card entry';
					}
																	
					}
					}
					else
					{
						$data['errmsg'] =  "Uploaded file is blank";	
					}
					}
				}
				}else{
					$data['errmsg'] =  "Coloumn data is not proper in file";
				}
			}else{
				$data['validation_errors'] = validation_errors();
			}
			
			}
		}
		if($sucess_flag == 1)
		{
			    $this->session->set_flashdata('success','Application for examination has been done successfully..');
				redirect(base_url().'bulk/BulkApply/exam_applicantlst');
		}
	    $data['middle_content'] = 'bulk/bulk_add_member_excel';
		//$data=array('middle_content'=>'bulk/bulk_add_member_excel');
		$this->load->view('bulk/bulk_common_view',$data);
	}
	
    public function excel()
    {
		//$exam_code = 11;
		$exam_code=base64_decode($this->uri->segment(4));
		
		$objWorksheet1 = $this->excel->createSheet();
		$objWorksheet1->setTitle('Another');

		$this->excel->getActiveSheet()->setCellValue('A1', 'Member_number');
		$this->excel->getActiveSheet()->setCellValue('B1', 'Exam Name');
		$this->excel->getActiveSheet()->setCellValue('C1', 'Center name');
		$this->excel->getActiveSheet()->setCellValue('D1', 'Exam medium');
		$this->excel->getActiveSheet()->setCellValue('E1', 'Scribe required');
		
	   for($col = ord('A'); $col <= ord('E'); $col++)
	   { 	
	   		//set column dimension 
			$this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
			//change the font size
			$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
			$this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);
		$objWorksheet1->getProtection()->setSheet(true);
		 
		######### member registration number ######	
		$total_cnt=35;
		for($i=2;$i<=35;$i++)
		{
			$cellA='A'.$i;
			$this->excel->getActiveSheet()->getStyle($cellA)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$objValidation = $this->excel->getActiveSheet()->getCell($cellA)->getDataValidation();
			$objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_CUSTOM);
			$objValidation->setErrorStyle('stop');
			$objValidation->setAllowBlank(true);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Duplicate member number not allowed!');
			$objValidation->setPromptTitle('Allowed input');
			$objValidation->setPrompt('Only unique numbers allowed.');
			$objValidation->setFormula1('=COUNTIF($A$2:$A$'.$total_cnt.','.$cellA.')=1');
			//$objValidation->setFormula2('=ISNUMBER(SUMPRODUCT(SEARCH(MID(A2,ROW(INDIRECT("1:"&LEN(A2))),1),"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")))');
			}
			
		 
		 ####### get Exam name ######
		 $exam_list=$this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code,'exam_delete'=>'0'),'description');
		 #### End of get Exam name #######
		$examname_exceldata=array();
		$exam_configs='';
		 if(count($exam_list) > 0)
		  {
			foreach ($exam_list as $row)
			 {
				 $exam_name=substr(strip_tags($row['description']),'0',74);
				 $examname_exceldata[] =$exam_name.'='.$exam_code;
			 }
			 $exam_configs=implode(',',$examname_exceldata);
		  }
		 
		for($i=2;$i<=35;$i++)
		{
			$cellB='B'.$i;
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setCellValue($cellB, "Select Exam");
			$objValidation = $this->excel->getActiveSheet()->getCell($cellB)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setErrorStyle('stop');
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"'.$exam_configs.'"');
			
			$this->excel->getActiveSheet()->getStyle($cellB)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
		
		/*code to write in another sheet start*/
		
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',$exam_code);
		$this->db->where('bulk_exam_activation_master.institute_code',$this->session->userdata('institute_id'));
		$this->db->where("center_delete",'0');
		$centers=$this->master_model->getRecords('center_master','','Center_name,center_code',array('center_name'=>'ASC'));
		#### End of get Center #######
		 $center_exceldata=array();
		 $center_exceldata_new=array();
		 $center_configs='';
          if(count($centers) > 0)
		  {
			  foreach ($centers as $row)
			  {
					$center_exceldata[] = $row['Center_name'].'='.$row['center_code'];
					$center_exceldata_new[] = $row['Center_name'].'='.$row['center_code'];
			  }
		  	 $center_configs=implode(',',$center_exceldata);
		  }
		
		 ###### center drop down in excel###
		 $center_cnt=0;
		for($i=1;$i<=count($center_exceldata_new);$i++)
		{
			$cellP='A'.$i;
			$this->excel->setActiveSheetIndex(1);
			$this->excel->getActiveSheet()->setCellValue($cellP, $center_exceldata_new[$center_cnt]);
			$objValidation = $this->excel->getActiveSheet()->getCell($cellP)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_CUSTOM );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setErrorStyle('stop');
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			//$objValidation->setShowDropDown(true);
			//$objValidation->setErrorTitle('Input error');
			//$objValidation->setError('Value is not in list.');
			//$objValidation->setPromptTitle('Pick from list');
			//$objValidation->setPrompt('Please pick a value from the drop-down list.');
			//$objValidation->setFormula1('"'.$center_configs1.'"');
			//$this->excel->getActiveSheet()->getStyle($cellP)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$center_cnt++;
		}
		
		$this->excel->setActiveSheetIndex(1);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		
		
		/*code to write in another sheet end*/
		
		
		 ### prepared  center data for excel ####
		 ####### get Center ######
		 /*$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',$exam_code);
		$this->db->where("center_delete",'0');
		$centers=$this->master_model->getRecords('center_master','','Center_name,center_code',array('center_name'=>'ASC'));*/
		
		
		  //echo $center_configs;
		  //exit;
		 // $center_configs = 'KOLKATA=568,MUMBAI=306';
		 $center_configs = 'AGARTALA=495,AGRA=501,AHMEDABAD=98,AURANGABAD=301,BANGALORE=191,BARODA=100,BHOPAL=257,BHUBANESHWAR=370,CALICUT=233,CHANDIGARH=603,CHENNAI=467,COIMBATORE=457,DEHRADUN=514,ERNAKULAM=236,GANGTOK=448,GAUHATI=48,HISSAR=137,HUBLI=207,HYDERABAD=13,INDORE=268';
		 ###### center drop down in excel###
		for($i=2;$i<=35;$i++)
		{
		$cellC='C'.$i;
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setCellValue($cellC, "Select Center");
		$objValidation = $this->excel->getActiveSheet()->getCell($cellC)->getDataValidation();
		$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation->setAllowBlank(false);
		$objValidation->setErrorStyle('stop');
		$objValidation->setShowInputMessage(true);
		$objValidation->setShowErrorMessage(true);
		$objValidation->setShowDropDown(true);
		$objValidation->setErrorTitle('Input error');
		$objValidation->setError('Value is not in list.');
		$objValidation->setPromptTitle('Pick from list');
		$objValidation->setPrompt('Please pick a value from the drop-down list.');
		//$objValidation->setFormula1('"'.$center_configs.'"');
		$objValidation->setFormula1('Another!A1:A'.count($center_exceldata_new));
		$this->excel->getActiveSheet()->getStyle($cellC)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
		
		
		###### End of center drop down in excel###
		
		### prepared  Medium data for excel ####
		####### get Medium ######
		/*$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_master.exam_code',$exam_code);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master','','medium_description,medium_code');*/
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_master.exam_code',$exam_code);
		$this->db->where('bulk_exam_activation_master.institute_code',$this->session->userdata('institute_id'));
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master','','medium_description,medium_code');
		
		 ####### End of get Medium ######
		 $medium_exceldata=array();
		 $medium_configs='';
          if(count($medium) > 0)
		  {
			  foreach ($medium as $row1)
			  {
					$medium_exceldata[] = $row1['medium_description'].'='.$row1['medium_code'];
			  }
		  	 $medium_configs=implode(',',$medium_exceldata);
			
			 ###### Medium drop down in excel###
		for($i=2;$i<=35;$i++)
		{
			$cellD='D'.$i;
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setCellValue($cellD, "Select Medium");
			$objValidation = $this->excel->getActiveSheet()->getCell($cellD)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setErrorStyle('stop');
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"'.$medium_configs.'"');
			$this->excel->getActiveSheet()->getStyle($cellD)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
			###### End of Medium drop down in excel###
	  }
	  ###End of Medium data for excel #### 
	  
		 ### prepared  Scribe required  data for excel ####
		 $scribe_configs="Yes,No";
		 ###### Medium drop down in excel###
		for($i=2;$i<=35;$i++)
		{
			$cellE='E'.$i;
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setCellValue($cellE, "Select Scribe");
			$objValidation = $this->excel->getActiveSheet()->getCell($cellE)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setErrorStyle('stop');
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"'.$scribe_configs.'"');
			$this->excel->getActiveSheet()->getStyle($cellE)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
		 ###End of Scribe required  data for excel ####
	    
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		
		
		$filename='PHPExcelDemo.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
    }
	
	public function excel_test()
    {
	
		$exam_code=34;
		$this->excel->getActiveSheet()->setCellValue('A1', 'Member_number');
		$this->excel->getActiveSheet()->setCellValue('B1', 'Exam Name');
		$this->excel->getActiveSheet()->setCellValue('C1', 'Center name');
		$this->excel->getActiveSheet()->setCellValue('D1', 'Exam medium');
		$this->excel->getActiveSheet()->setCellValue('E1', 'Scribe required');
		
	  
	   for($col = ord('A'); $col <= ord('E'); $col++)
	   { 	
	   		//set column dimension 
			$this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
			//change the font size
			$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
			$this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);
		 
	######### member registration number ######	
	$total_cnt = $this->input->post('no_of_inputs');
	$total_cnt = $total_cnt + 1;
	//$total_cnt=10;
	for($i=2;$i<=$total_cnt;$i++)
	{
		$cellA='A'.$i;
		$this->excel->getActiveSheet()->getStyle($cellA)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objValidation = $this->excel->getActiveSheet()->getCell($cellA)->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_CUSTOM);
        $objValidation->setErrorStyle('stop');
        $objValidation->setAllowBlank(true);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setErrorTitle('Input error');
        $objValidation->setError('Duplicate member number not allowed!');
        $objValidation->setPromptTitle('Allowed input');
        $objValidation->setPrompt('Only unique numbers allowed.');
        $objValidation->setFormula1('=COUNTIF($A$2:$A$'.$total_cnt.','.$cellA.')=1');
		//$objValidation->setFormula2('=ISNUMBER(SUMPRODUCT(SEARCH(MID(A2,ROW(INDIRECT("1:"&LEN(A2))),1),"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")))');
		}
		
		 
		 ####### get Exam name ######`
		 $exam_list=$this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code,'exam_delete'=>'0'),'description');
		 #### End of get Exam name #######
		$examname_exceldata=array();
		$exam_configs='';
		 if(count($exam_list) > 0)
		  {
			foreach ($exam_list as $row)
			 {
				 $exam_name=substr(strip_tags($row['description']),'0',74);
				 $examname_exceldata[] =$exam_name.'='.$exam_code;
			 }
			 $exam_configs=implode(',',$examname_exceldata);
		  }
		  
		for($i=2;$i<=$total_cnt;$i++)
		{
			$cellB='B'.$i;
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setCellValue($cellB, "Select Exam");
			$objValidation = $this->excel->getActiveSheet()->getCell($cellB)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"'.$exam_configs.'"');
			
			$this->excel->getActiveSheet()->getStyle($cellB)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
	    ####### get Medium ######
		
		
		 ### prepared  center data for excel ####
		 ####### get Center ######
		 $this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',$exam_code);
		$this->db->where("center_delete",'0');
		$centers=$this->master_model->getRecords('center_master','','Center_name,center_code',array('center_name'=>'ASC'));

		#### End of get Center #######
		 $center_exceldata=array();
		 $center_configs='';
          if(count($centers) > 0)
		  {
			  foreach ($centers as $row)
			  {
					$center_exceldata[] = $row['Center_name'].'='.$row['center_code'];
			  }
		  	 $center_configs=implode(',',$center_exceldata);
		  }
		  
		 ###### center drop down in excel###
		for($i=2;$i<=$total_cnt;$i++)
		{
		$cellC='C'.$i;
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setCellValue($cellC, "Select Center");
		$objValidation = $this->excel->getActiveSheet()->getCell($cellC)->getDataValidation();
		$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation->setAllowBlank(false);
		$objValidation->setShowInputMessage(true);
		$objValidation->setShowErrorMessage(true);
		$objValidation->setShowDropDown(true);
		$objValidation->setErrorTitle('Input error');
		$objValidation->setError('Value is not in list.');
		$objValidation->setPromptTitle('Pick from list');
		$objValidation->setPrompt('Please pick a value from the drop-down list.');
		$objValidation->setFormula1('"'.$center_configs.'"');
		$this->excel->getActiveSheet()->getStyle($cellC)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
		###### End of center drop down in excel###
		
		  
		### prepared  Medium data for excel ####
		####### get Medium ######
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_master.exam_code',$exam_code);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master','','medium_description,medium_code');
		 ####### End of get Medium ######
		 $medium_exceldata=array();
		 $medium_configs='';
          if(count($medium) > 0)
		  {
			  foreach ($medium as $row1)
			  {
					$medium_exceldata[] = $row1['medium_description'].'='.$row1['medium_code'];
			  }
		  	 $medium_configs=implode(',',$medium_exceldata);
			
			 ###### Medium drop down in excel###
		for($i=2;$i<=$total_cnt;$i++)
		{
			$cellD='D'.$i;
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setCellValue($cellD, "Select Medium");
			$objValidation = $this->excel->getActiveSheet()->getCell($cellD)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"'.$medium_configs.'"');
			$this->excel->getActiveSheet()->getStyle($cellD)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
			###### End of Medium drop down in excel###
	  }
	  ###End of Medium data for excel #### 
		  
		  
		 ### prepared  Scribe required  data for excel ####
		 $scribe_configs="Yes,No";
		 ###### Medium drop down in excel###
		for($i=2;$i<=$total_cnt;$i++)
		{
			$cellE='E'.$i;
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setCellValue($cellE, "Select Scribe");
			$objValidation = $this->excel->getActiveSheet()->getCell($cellE)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"'.$scribe_configs.'"');
			$this->excel->getActiveSheet()->getStyle($cellE)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		}
		 ###End of Scribe required  data for excel #### 
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$filename='PHPExcelDemo.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
    }


}