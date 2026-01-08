<?php
/*
 	* Controller Name	:	Cron Custom Scribe
 	* Created By		:	Pooja mane
 	* Created Date		:	21-11-2022
 	* First cron of scribe : (Cron_app_custom/scribe)
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_custom_scribe extends CI_Controller {
			//exit;
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->model('Emailsending');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		/* File Path */
		define('MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		/*define('CSC_MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		define('DRA_FILE_PATH','/fromweb/testscript/images/dra/');
		define('MEM_FILE_EDIT_PATH','/fromweb/testscript/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/fromweb/testscript/images/renewal/');*/
		define('SCE_FILE_PATH','/fromweb/testscript/images/scribe/');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	public function ci_sessoin_delete()
	{
		$yesterday = date('Y-m-d', strtotime("- 2 day"));
		
		//$this->db->where('DATE(FROM_UNIXTIME(timestamp))', $yesterday);
		//$this->Master_model->getRecords('ci_sessions');
		//$this->db->delete('ci_sessions');
		//echo ">>".$this->db->last_query();
		//$this->db->delete('ci_sessions');
		//SELECT date(FROM_UNIXTIME(timestamp)) FROM `ci_sessions` where date(FROM_UNIXTIME(timestamp)) BETWEEN '2020-01-01' and '2020-08-31'
		
		//$query = $this->db->query('OPTIMIZE TABLE ci_sessions');
	}
	function resize_image_max($image,$max_width,$max_height) 
	{
		ini_set("memory_limit","256M");
		ini_set("gd.jpeg_ignore_warning", 1);
		$org_img = $image;
		$image = @ImageCreateFromJpeg($image);
		if (!$image)
		{
			$image= imagecreatefromstring(file_get_contents($org_img));
		}
		$w = imagesx($image); //current width
		$h = imagesy($image); //current height
		if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
		if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
		//try max width first...
		$ratio = $max_width / $w;
		$new_w = $max_width;
		$new_h = $h * $ratio;
		//if that didn't work
		if ($new_h > $max_height) {
			$ratio = $max_height / $h;
			$new_h = $max_height;
			$new_w = $w * $ratio;
		}
		$new_image = imagecreatetruecolor ($new_w, $new_h);
		imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		return $new_image;
	}
	
	// EXECUTE CRON AT THE TIME OF CAIIB AND JAIIB REGISTRATIONS only //

	/*INTERVAL AUTOMATED EMAIL SENDING CRON POOJA MANE : 18-11-2022*/
	public function scribe_mail()
	{
		if(!isset($_GET['custom']))
		exit;				//stopped execution as exam is not live.
		$data = '';
		$from_date =date('Y-m-d', strtotime("- 1 week"));  
		$to_date =date('Y-m-d', strtotime("- 1 day")) ;

		/*get members with scribe flag Y*/
		$query = $this->db->query("SELECT DISTINCT(regnumber), em.description FROM member_exam me JOIN subject_master sm ON sm.exam_code = me.exam_code JOIN exam_master em ON em.exam_code = me.exam_code WHERE scribe_flag = 'Y' AND pay_status = '1' AND institute_id = '0' AND me.exam_code in($this->config->item('examCodeJaiib'),$this->config->item('examCodeCaiib'),$this->config->item('examCodeDBF')) AND sm.exam_date >= '".DATE('Y-m-d')."'");
		$result1 = $query->result_array(); 
		
		if(!empty(count($result1)))
		{

			foreach ($result1 as $res) 
			{
				$exam_name = $email = '';
				$regnumber = $res['regnumber'];
				$exam_name = $res['description'];
				//check alredy applied for scribe or not
				$qry = $this->db->query("SELECT * FROM scribe_registration as sr WHERE  sr.regnumber = ".$regnumber." AND sr.exam_date >= '".DATE('Y-m-d')."' ");
				$resul = $qry->result_array(); 
				
				if(empty($resul))
				{ 
					
					$query = $this->db->query("SELECT email, regnumber FROM member_registration WHERE  isactive = '1' AND regnumber = ".$regnumber );
					$result2 = $query->result_array(); 
					$email = $result2[0]['email'];

					$final_str = 'Dear Sir/Madam,<br/><br/>';
					$final_str.= 'This is regarding your Application for '.$exam_name.' exam.';
					$final_str.= '<br/><br/>';   
					$final_str.= '&nbsp;';
					$final_str.= 'As per the revised GUIDELINES FOR VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES USING SCRIBE, those candidates who are blind/low vision or affected by cerebral palsy with loco-motor impairment, whose writing speed is affected and Physically Handicapped (PH) candidates who are not in a position to operate Keyboard and Mouse can use their scribe at his/her own cost during the examination. Compensatory time and facility for scribe would not be provided to other Physically Handicapped candidates.';
					$final_str.= '<br/><br/>';
					$final_str.= 'For detailed guidelines, rules and process to be followed for applying for scribe permission click the below link:';
					$final_str.= '<br/><br/>';
					$final_str.= '<u><a target="_blank" href="https://iibf.esdsconnect.com/uploads/SCRIBE_Guideline.pdf">https://iibf.esdsconnect.com/uploads/SCRIBE_Guideline.pdf</a></u>';
					$final_str.= '<br/><br/>';
					$final_str.= 'While submitting the exam application for the upcoming JAIIB/CAIIB examination you opted for the requirement of Scribe and as per our records we have not received your request for Scribe permission. You are therefore requested to apply for Scribe Permission as soon as possible without which you may not be allowed to take the Scribe with you for your JAIIB/CAIIB examination.';
					$final_str.= '<br/><br/>';
					$final_str.= 'Click on the below link to apply for Scribe permission or visit our website <a target="_blank" href="https://www.iibf.org.in/"> www.iibf.org.in</a>';
					$final_str.= '<br/><br/>';
					$final_str.= '<a target="_blank" href="https://iibf.esdsconnect.com/Scribe_form">https://iibf.esdsconnect.com/Scribe_form</a>';
					$final_str.= '<br/><br/>';
					$final_str.= 'Note:<br/>';
					$final_str.= '<ol>
					<li>Processing of Scribe permission applications via email is discontinued.</li>
					<li>Candidate needs to apply for Scribe permission/compensatory time/additional facility via Online mode only using the link provided above or visit our website www.iibf.org.in</li>
					<li>Candidate needs to apply for Scribe permission for all the eligible subjects separately.</li>
					<li>For clarification on Scribe permission, you may write to iibfwzmem@iibf.org.in</li>
					</li></ol><br/><br/>';
				
					$final_str.= 'Thanks & Regards,';
					$final_str.= '<br/>';
					$final_str.= 'Indian Institute of Banking & Finance';
					//echo $final_str;die;
					
					$info_arr=array('to'=>$email,
									'from'=>'noreply@iibf.org.in',
									'subject'=>'Your Application for JAIIB/CAIIB Nov/Dec-2022 exam',
									'message'=>$final_str
								);
					$this->Emailsending->mailsend_attch($info_arr,'');
				}
				
			}
		
		}	
	}	
 	/*AUTOMATED EMAIL SENDING CRON TO CANDIDATE END POOJA MANE : 18-11-2022*/

 	/*DAILY MAIL SENDING CRON FOR MSS By Pooja Mane : 21-11-2022*/
	public function scribe_admin_mail()
	{
		
		$Yesterday =date('Y-m-d', strtotime("- 1 day"));
		//echo $Yesterday;

		$this->db->select('count(*) as rows');
		$this->db->like('modified_on',$Yesterday);
		$data['total_approve']= $total_approve= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '1'));
		$data['approve']= $approve = $total_approve[0]['rows']; //approved special
		

		/*TOTAL REJECTED SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$this->db->like('modified_on',$Yesterday);
		$data['total_reject']= $total_reject= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '3'));
		$data['reject']= $reject = $total_reject[0]['rows']; //rejected scribe
		

		//GET ACTIVE EXAMS FOR SCRIBE
		$qry = $this->db->query("SELECT `exam_name` , `created_on`, count(*) as `count` FROM scribe_registration WHERE created_on LIKE '%$Yesterday%' GROUP BY exam_name");
		$exam_data = $qry->result_array(); 

			$final_str = 'Dear Team,<br/><br/>';
			$final_str.= '*****Scribe Application count dated on '.$Yesterday.' *****';
			$final_str.= '<br/><br/>';   
			foreach ($exam_data as  $exam) 
			{
				$exam_name = $exam['exam_name'];
				$count = $exam['count'];
				$final_str.= $exam_name." application count = ".$count;
				$final_str.= '<br/><br/>';
			}  
			
			$final_str.= '---------------------------------------------------<br/><br/>';

			$final_str.= 'Total application approved count = '.$approve;
			$final_str.= '<br/><br/>'; 
			$final_str.= 'Total application rejeted count = '.$reject;
			$final_str.= '<br/><br/>';   
			
			$final_str.= 'Thanks & Regards,';
			$final_str.= '<br/>';   
			$final_str.= 'IIBF Team';

			//echo $final_str;die;
			
			$info_arr=array('to'=>'iibfwzmem@iibf.org.in',
							'cc'=>'iibfdevp@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Scribe Application count dated on '.$Yesterday,
							'message'=>$final_str
						);
			$this->Emailsending->mailsend_attch($info_arr,'');

	}
	/*MAIL SENDING CRON FOR MSS END : Pooja Mane : 21-11-2022*/

	/*DAILY SCRIBE REGISTRATION CRON : POOJA MANE : 16-11-2022*/
	public function scribe()
	{
		//echo "string";die;
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$declaration_zip_flg = 0;
		$vis_imp_cert_img_zip_flg = 0;
		$orth_han_cert_img_zip_flg = 0;
		$cer_palsy_cert_img_zip_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		//echo $start_time;die;
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result); 

		$this->log_model->cronlog("New Scribe Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_scribe_reg_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New Scribe Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$new_mem_reg = $this->Master_model->getRecords('scribe_registration a',array(' DATE(modified_on)'=>$yesterday,'scribe_approve'=>'1'));	
			//echo $this->db->last_query();die;
		
			if(count($new_mem_reg))
			{
				$dirname = "scribe_image_".$current_date;
				//echo $dirname.'<br>';//die;
				$directory = $cron_file_path.'/'.$dirname;
				//echo $directory.'<br>';//die;
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
				//echo 'newdiectory'.$directory.'<br><br>';//die;
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				/*$photo_cnt = 0;
				$sign_cnt = 0;*/
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$scribe_uid = $reg_data['scribe_uid'];
					$regnumber = $reg_data['regnumber'];
					$idproofimg = '';
					if(is_file("./uploads/scribe/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = SCE_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					
					$declarationimg = '';
					if(is_file("./uploads/scribe/declaration/".$reg_data['declaration_img']))
					{
						$declarationimg = SCE_FILE_PATH.$reg_data['declaration_img'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration_img']." (".$reg_data['regnumber'].")\n");	
					}
					
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
						
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
										
					}

					if(strlen($reg_data['exam_name']) > 30)
					{	$exam_name = substr($reg_data['exam_name'],0,29);	}
					else
					{	$exam_name = $reg_data['exam_name'];	}
					
				
					if(strlen($reg_data['subject_name']) > 30)
					{	$subject_name = substr($reg_data['subject_name'],0,29);	}
					else
					{	$subject_name = $reg_data['subject_name'];	}
					
					if(strlen($reg_data['center_name']) > 30)
					{	$center_name = substr($reg_data['center_name'],0,29);	}
					else
					{	$center_name = $reg_data['center_name'];	}
				
					
					if(strlen($reg_data['name_of_scribe']) > 30)
					{	$name_of_scribe = substr($reg_data['name_of_scribe'],0,29);	}
					else
					{	$name_of_scribe = $reg_data['name_of_scribe'];	}
				
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['mobile_scribe']) > 30)
					{	$mobile_scribe = substr($reg_data['mobile_scribe'],0,29);	}
					else
					{	$mobile_scribe = $reg_data['mobile_scribe'];	}
				
					
					if(strlen($reg_data['emp_details_scribe']) > 30)
					{	$emp_details_scribe = substr($reg_data['emp_details_scribe'],0,29);	}
					else
					{	$emp_details_scribe = $reg_data['emp_details_scribe'];	}
				
					
					if(strlen($reg_data['description']) > 30)
					{	$description = substr($reg_data['description'],0,29);	}
					else
					{	$description = $reg_data['description'];	}
					
					if(strlen($reg_data['photoid_no']) > 30)
					{	$photoid_no = substr($reg_data['photoid_no'],0,29);	}
					else
					{	$photoid_no = $reg_data['photoid_no'];	}
					
					if(strlen($reg_data['exam_date']) > 30)
					{	$exam_date = substr($reg_data['exam_date'],0,29);	}
					else
					{	$exam_date = $reg_data['exam_date'];	}
					
					if(strlen($reg_data['scribe_approve']) > 30)
					{	$scribe_approve = substr($reg_data['scribe_approve'],0,29);	}
					else
					{	$scribe_approve = $reg_data['scribe_approve'];	}

					if(strlen($reg_data['special_assistance']) > 30)
					{	$special_assistance = substr($reg_data['special_assistance'],0,29);	}
					else
					{	$special_assistance = $reg_data['special_assistance'];	}

					if(strlen($reg_data['extra_time']) > 30)
					{	$extra_time = substr($reg_data['extra_time'],0,29);	}
					else
					{	$extra_time = $reg_data['extra_time'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					/*$specified_qualification_name = '';
					$specified_qualification_code = $reg_data['specify_qualification'];
					$specified_qualification_details = $this->Master_model->getRecords('qualification',array('qid'=>$specified_qualification_code),'name');*/
					//print_r($reg_data);die;
					$data .= ''.$reg_data['scribe_uid'].'|'.$reg_data['exam_code'].'|'.$reg_data['exam_name'].'|'.$reg_data['subject_code'].'|'.$reg_data['subject_name'].'|'.$reg_data['regnumber'].'|'.$reg_data['center_name'].'|'.$reg_data['center_code'].'|'.$reg_data['name_of_scribe'].'|'.$reg_data['mobile_scribe'].'|'.$qualification.'|'.$reg_data['emp_details_scribe'].'|'.$description.'|'.$reg_data['photoid_no'].'|'.$idproofimg.'|'.$declarationimg.'|'.$reg_data['exam_date'].'|'.$reg_data['created_on'].'|'.$special_assistance.'|'.$extra_time."\n";
					
					if($dir_flg)
					{
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/scribe/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}


						// For ID declaration images
						if($declarationimg)
						{
							$image = "./uploads/scribe/declaration/".$reg_data['declaration_img'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['declaration_img']);
							
							$declaration_to_add = $directory."/".$reg_data['declaration_img'];
							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
							if(!$declaration_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$reg_data['declaration_img']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$declaration_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "New Scribe Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Scribe Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Scribe Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Scribe Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Scribes Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."\n");
				/*fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."\n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."\n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."\n");*/
				
				$zip->close();
			}
			else
			{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("New Scribe Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** New Scribe Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
		echo "executed Successfully for date ".$yesterday;
	}
	/*SCRIBE REGISTRATION CRON : POOJA MANE : 16-11-2022*/
}
