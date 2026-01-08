<?php
/*
 	* Controller Name	:	Cron File Generation
 	* Created By		:	Bhushan
 	* Created Date		:	26-07-2019
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class Cronfeb23 extends CI_Controller {
			//exit;
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		/* File Path */
		define('MEM_FILE_PATH','/fromweb/images/newmem/');
		define('CSC_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/fromweb/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/fromweb/images/renewal/');
		define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('SCE_FILE_PATH','/fromweb/images/scribe/');
		
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
	// Cron_custom/admit_update_220
	
	public function admit_update_caiib_220()
	{
		// Get Admit Card Data from TEMP
		
		/*/usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron admit_update_caiib_220*/
		
		$select = '*';
		$this->db->limit(3000); 
		$temp_admit_card_data = $this->Master_model->getRecords('admit_card_details_jaiib',array('exm_cd'=>'71','exm_prd'=>'220','remark'=>'0'),$select);
		//echo $this->db->last_query();
		if(count($temp_admit_card_data))
		{
			foreach($temp_admit_card_data as $admit_row)
			{
				// Member Exam Applicatoin Update
				$update_member_app = array('exam_period'=>$admit_row['exm_prd'],
									'exam_center_code'=>$admit_row['center_code'],
									'created_on'=>'0000-00-00 00:00:00');
				$where_member_app = array('regnumber'=>$admit_row['mem_mem_no'],
									'exam_code'=>'71',
									'exam_period'=>'219',
									'pay_status'=>'1');
				$this->master_model->updateRecord('member_exam',$update_member_app,$where_member_app);
				
				
				$this->db->where('exam_name',$this->config->item('examCodeCaiibElective71'));
				$this->db->where('exam_period',220);
				$this->db->where('center_code',$admit_row['center_code']);
				$center = $this->Master_model->getRecords('center_master','','center_name');
			
				// Admit Card Mail Table Update
				$admit_update_data = array('center_code'=>$admit_row['center_code'],
								'center_name'=>$center[0]['center_name'],
								'exm_prd'=>$admit_row['exm_prd'],
								'venueid'=>$admit_row['venueid'],
								'venue_name'=>$admit_row['venue_name'],
								'venueadd1'=>$admit_row['venueadd1'],
								'venueadd2'=>$admit_row['venueadd2'],
								'venueadd3'=>$admit_row['venueadd3'],
								'venueadd4'=>$admit_row['venueadd4'],
								'venueadd5'=>$admit_row['venueadd5'],
								'venpin'=>$admit_row['venpin'],
								'exam_date'=>$admit_row['exam_date'],
								'time'=>$admit_row['time'],
								'created_on'=>'0000-00-00 00:00:00',
								);
				$where_admit_card_arr = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>'71',
								'exm_prd'=>'219',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');	
				$this->master_model->updateRecord('admit_card_details',$admit_update_data,$where_admit_card_arr);
				// Get admitcard_id from orginal admit card tabele
				$admitcard_id = '';
				$where_primary_key = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>'71',
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');
				$select_col = 'admitcard_id';
				$get_primary_key = $this->Master_model->getRecords('admit_card_details',$where_primary_key,$select_col);
				$admitcard_id = $get_primary_key[0]['admitcard_id'];
				
				// Seat allocation update
				$set_update_data = array('venue_code'=>$admit_row['venueid'],
										'session'=>$admit_row['time'],
										'center_code'=>$admit_row['center_code'],
										'date'=>$admit_row['exam_date'],
										'exam_period'=>$admit_row['exm_prd'],
										'createddate'=>'0000-00-00 00:00:00');
				$where_set_all = array('admit_card_id'=>$admitcard_id,
										'exam_code'=>'71',
										'exam_period'=>'219');
				$this->master_model->updateRecord('seat_allocation',$set_update_data,$where_set_all);
				
				// Admit Card Sample table update
				$admit_update_remark = array('remark'=>'4');
				$where_admit_remark = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>'71',
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'0');
				$this->master_model->updateRecord('admit_card_details_jaiib',$admit_update_remark,$where_admit_remark);
				
				echo '>>'. $this->db->last_query();
				echo '<br/>';
			}
		}
	}
	
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron admit_update_220
	public function admit_update_220()
	{
		// Get Admit Card Data from TEMP
		
		$select = '*';
		$this->db->limit(3000);
		$temp_admit_card_data = $this->Master_model->getRecords('admit_card_details_jaiib',array('exm_cd'=>'21','exm_prd'=>'220','remark'=>'0'),$select);
		//echo $this->db->last_query();
		if(count($temp_admit_card_data))
		{
			foreach($temp_admit_card_data as $admit_row)
			{
				// Member Exam Applicatoin Update
				$update_member_app = array('exam_period'=>$admit_row['exm_prd'],
									'exam_center_code'=>$admit_row['center_code'],
									'created_on'=>'0000-00-00 00:00:00');
				$where_member_app = array('regnumber'=>$admit_row['mem_mem_no'],
									'exam_code'=>$this->config->item('examCodeJaiib'),
									'exam_period'=>'120',
									'pay_status'=>'1');
				$this->master_model->updateRecord('member_exam',$update_member_app,$where_member_app);
				
			
				// Admit Card Mail Table Update
				$admit_update_data = array('center_code'=>$admit_row['center_code'],
								'center_name'=>$admit_row['center_name'],
								'exm_prd'=>$admit_row['exm_prd'],
								'venueid'=>$admit_row['venueid'],
								'venue_name'=>$admit_row['venue_name'],
								'venueadd1'=>$admit_row['venueadd1'],
								'venueadd2'=>$admit_row['venueadd2'],
								'venueadd3'=>$admit_row['venueadd3'],
								'venueadd4'=>$admit_row['venueadd4'],
								'venueadd5'=>$admit_row['venueadd5'],
								'venpin'=>$admit_row['venpin'],
								'exam_date'=>$admit_row['exam_date'],
								'time'=>$admit_row['time'],
								'created_on'=>'0000-00-00 00:00:00',
								);
				$where_admit_card_arr = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeJaiib'),
								'exm_prd'=>'120',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');	
				$this->master_model->updateRecord('admit_card_details',$admit_update_data,$where_admit_card_arr);
				// Get admitcard_id from orginal admit card tabele
				$admitcard_id = '';
				$where_primary_key = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeJaiib'),
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');
				$select_col = 'admitcard_id';
				$get_primary_key = $this->Master_model->getRecords('admit_card_details',$where_primary_key,$select_col);
				$admitcard_id = $get_primary_key[0]['admitcard_id'];
				
				// Seat allocation update
				$set_update_data = array('venue_code'=>$admit_row['venueid'],
										'session'=>$admit_row['time'],
										'center_code'=>$admit_row['center_code'],
										'date'=>$admit_row['exam_date'],
										'exam_period'=>$admit_row['exm_prd'],
										'createddate'=>'0000-00-00 00:00:00');
				$where_set_all = array('admit_card_id'=>$admitcard_id,
										'exam_code'=>$this->config->item('examCodeJaiib'),
										'exam_period'=>'120');
				$this->master_model->updateRecord('seat_allocation',$set_update_data,$where_set_all);
				
				// Admit Card Sample table update
				$admit_update_remark = array('remark'=>'4');
				$where_admit_remark = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeJaiib'),
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'0');
				$this->master_model->updateRecord('admit_card_details_jaiib',$admit_update_remark,$where_admit_remark);
			}
		}
	}
	
	public function admit_update_42_220()
	{
		// Get Admit Card Data from TEMP
		$select = '*';
		$this->db->limit(1500);
		$temp_admit_card_data = $this->Master_model->getRecords('admit_card_details_jaiib',array('exm_cd'=>$this->config->item('examCodeDBF'),'exm_prd'=>'220','remark'=>'0'),$select);
		//echo "<br> 1 >>".$this->db->last_query();
		if(count($temp_admit_card_data))
		{
			foreach($temp_admit_card_data as $admit_row)
			{
				// Member Exam Applicatoin Update
				$update_member_app = array('exam_period'=>$admit_row['exm_prd'],
									'exam_center_code'=>$admit_row['center_code'],
									'created_on'=>'0000-00-00 00:00:00');
				$where_member_app = array('regnumber'=>$admit_row['mem_mem_no'],
									'exam_code'=>$this->config->item('examCodeDBF'),
									'exam_period'=>'120',
									'pay_status'=>'1');
				$this->master_model->updateRecord('member_exam',$update_member_app,$where_member_app);
				//echo "<br> 2 >>".$this->db->last_query();
				// Admit Card Mail Table Update
				$admit_update_data = array('center_code'=>$admit_row['center_code'],
								'center_name'=>$admit_row['center_name'],
								'exm_prd'=>$admit_row['exm_prd'],
								'venueid'=>$admit_row['venueid'],
								'venue_name'=>$admit_row['venue_name'],
								'venueadd1'=>$admit_row['venueadd1'],
								'venueadd2'=>$admit_row['venueadd2'],
								'venueadd3'=>$admit_row['venueadd3'],
								'venueadd4'=>$admit_row['venueadd4'],
								'venueadd5'=>$admit_row['venueadd5'],
								'venpin'=>$admit_row['venpin'],
								'exam_date'=>$admit_row['exam_date'],
								'time'=>$admit_row['time'],
								'created_on'=>'0000-00-00 00:00:00',
								);
				$where_admit_card_arr = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeDBF'),
								'exm_prd'=>'120',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');	
				$this->master_model->updateRecord('admit_card_details',$admit_update_data,$where_admit_card_arr);
				//echo "<br> 3 >>".$this->db->last_query();
				// Get admitcard_id from orginal admit card tabele
				$admitcard_id = '';
				$where_primary_key = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeDBF'),
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');
				$select_col = 'admitcard_id';
				$get_primary_key = $this->Master_model->getRecords('admit_card_details',$where_primary_key,$select_col);
				$admitcard_id = $get_primary_key[0]['admitcard_id'];
				//echo "<br> 4 >>".$this->db->last_query();
				// Seat allocation update
				$set_update_data = array('venue_code'=>$admit_row['venueid'],
										'session'=>$admit_row['time'],
										'center_code'=>$admit_row['center_code'],
										'date'=>$admit_row['exam_date'],
										'exam_period'=>$admit_row['exm_prd'],
										'createddate'=>'0000-00-00 00:00:00');
				$where_set_all = array('admit_card_id'=>$admitcard_id,
										'exam_code'=>$this->config->item('examCodeDBF'),
										'exam_period'=>'120');
				$this->master_model->updateRecord('seat_allocation',$set_update_data,$where_set_all);
				//echo "<br> 5 >>".$this->db->last_query();
	
				// Admit Card Sample table update
				$admit_update_remark = array('remark'=>'4');
				$where_admit_remark = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeDBF'),
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'0');
				$this->master_model->updateRecord('admit_card_details_jaiib',$admit_update_remark,$where_admit_remark);
				//echo "<br> 6 >>".$this->db->last_query();
			}
		}
	}
	
	public function admit_update_992_220()
	{
		// Get Admit Card Data from TEMP
		$select = '*';
		$this->db->limit(1500);
		$temp_admit_card_data = $this->Master_model->getRecords('admit_card_details_jaiib',array('exm_cd'=>$this->config->item('examCodeSOB'),'exm_prd'=>'220','remark'=>'0'),$select);
		//echo "<br> 1 >>".$this->db->last_query();
		if(count($temp_admit_card_data))
		{
			foreach($temp_admit_card_data as $admit_row)
			{
				// Member Exam Applicatoin Update
				$update_member_app = array('exam_period'=>$admit_row['exm_prd'],
									'exam_center_code'=>$admit_row['center_code'],
									'created_on'=>'0000-00-00 00:00:00');
				$where_member_app = array('regnumber'=>$admit_row['mem_mem_no'],
									'exam_code'=>$this->config->item('examCodeSOB'),
									'exam_period'=>'120',
									'pay_status'=>'1');
				$this->master_model->updateRecord('member_exam',$update_member_app,$where_member_app);
				//echo "<br> 2 >>".$this->db->last_query();
				// Admit Card Mail Table Update
				$admit_update_data = array('center_code'=>$admit_row['center_code'],
								'center_name'=>$admit_row['center_name'],
								'exm_prd'=>$admit_row['exm_prd'],
								'venueid'=>$admit_row['venueid'],
								'venue_name'=>$admit_row['venue_name'],
								'venueadd1'=>$admit_row['venueadd1'],
								'venueadd2'=>$admit_row['venueadd2'],
								'venueadd3'=>$admit_row['venueadd3'],
								'venueadd4'=>$admit_row['venueadd4'],
								'venueadd5'=>$admit_row['venueadd5'],
								'venpin'=>$admit_row['venpin'],
								'exam_date'=>$admit_row['exam_date'],
								'time'=>$admit_row['time'],
								'created_on'=>'0000-00-00 00:00:00',
								);
				$where_admit_card_arr = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeSOB'),
								'exm_prd'=>'120',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');	
				$this->master_model->updateRecord('admit_card_details',$admit_update_data,$where_admit_card_arr);
				//echo "<br> 3 >>".$this->db->last_query();
				// Get admitcard_id from orginal admit card tabele
				$admitcard_id = '';
				$where_primary_key = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeSOB'),
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'1');
				$select_col = 'admitcard_id';
				$get_primary_key = $this->Master_model->getRecords('admit_card_details',$where_primary_key,$select_col);
				$admitcard_id = $get_primary_key[0]['admitcard_id'];
				//echo "<br> 4 >>".$this->db->last_query();
				// Seat allocation update
				$set_update_data = array('venue_code'=>$admit_row['venueid'],
										'session'=>$admit_row['time'],
										'center_code'=>$admit_row['center_code'],
										'date'=>$admit_row['exam_date'],
										'exam_period'=>$admit_row['exm_prd'],
										'createddate'=>'0000-00-00 00:00:00');
				$where_set_all = array('admit_card_id'=>$admitcard_id,
										'exam_code'=>$this->config->item('examCodeSOB'),
										'exam_period'=>'120');
				$this->master_model->updateRecord('seat_allocation',$set_update_data,$where_set_all);
				//echo "<br> 5 >>".$this->db->last_query();
	
				// Admit Card Sample table update
				$admit_update_remark = array('remark'=>'4');
				$where_admit_remark = array('mem_mem_no'=>$admit_row['mem_mem_no'],
								'exm_cd'=>$this->config->item('examCodeSOB'),
								'exm_prd'=>'220',
								'sub_cd'=>$admit_row['sub_cd'],
								'remark'=>'0');
				$this->master_model->updateRecord('admit_card_details_jaiib',$admit_update_remark,$where_admit_remark);
				//echo "<br> 6 >>".$this->db->last_query();
			}
		}
	}
	
	/* Membership benchmark Edit data Cron */
	public function benchmark_edit_data()
	{ 
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$benchmark_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Edited Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$benchmark_file = "edited_cand_details_benchmark_".$current_date.".txt";
			
			$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Benchmark Edited Candidate Details Cron Start - ".$start_time."**********\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d');
			
			$edited_benchmark_data = $this->Master_model->getRecords('member_registration',array('DATE(benchmark_edit_date)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'benchmark_edit_flg' => 'Y','benchmark_disability'=>'Y'));
			
			if(count($edited_benchmark_data))
			{
				$dirname = "edited_benchmark_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					// Delete all the files in directory
					array_map('unlink', glob($directory."/*.*"));
					// Remove directory
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);
				$vis_imp_cert_img_cnt = 0;
				$orth_han_cert_img_cnt = 0;
				$cer_palsy_cert_img_cnt = 0;
				$vis_imp_cert_img_flg_cnt = 0;
				$orth_han_cert_img_flg_cnt = 0;
				$cer_palsy_cert_img_flg_cnt = 0;
				
				foreach($edited_benchmark_data as $imgdata)
				{	
					$img_edited_by = '';
					
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;		
					}
					
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					
					
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'];  // added by chaitali on Clients request on 2022-06-20
					
					/* $data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||'; */
					
					/* mem_mem_no
					mem_mem_typ
					benchmark_upd_dt
					is_benchmark_disability
					is_visually_impaired
					is_orthopedically_handicapped
					is_cerebral_palsy
					vis_imp_cert_img_path
					orth_han_cert_img_path
					cer_palsy_cert_img_path
					visually_impaired_cert
					orthopedically_handicapped_cert
					cerebral_palsy_cert
					usr_id */

					
					if($imgdata['images_editedby'] == ''){
						$img_edited_by = "Candidate";
					}
					else{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					
					
					/* Benchmark Code Start */
					$benchmark_disability = $imgdata['benchmark_disability'];
					$visually_impaired = $imgdata['visually_impaired'];
					$vis_imp_cert_img = '';
					$orthopedically_handicapped = $imgdata['orthopedically_handicapped'];
					$orth_han_cert_img = '';
					$cerebral_palsy = $imgdata['cerebral_palsy'];
					$cer_palsy_cert_img = '';
					
					if($benchmark_disability == 'Y')
					{
						$vis_imp_cert_img = '';
						if($visually_impaired == 'Y')
						{
							if($imgdata['vis_imp_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))
							{
								$vis_imp_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['vis_imp_cert_img'];
							}
						}
						
						$orth_han_cert_img = '';
						if($orthopedically_handicapped == 'Y')
						{
							if($imgdata['orth_han_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))
							{
								$orth_han_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['orth_han_cert_img'];
							}
							
						}
						$cer_palsy_cert_img = '';
						if($cerebral_palsy == 'Y')
						{
							if($imgdata['cer_palsy_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))
							{
								$cer_palsy_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['cer_palsy_cert_img'];
							}
							
						}
					}
					/* Benchmark Code End */
					
					$benchmark_data = $data.'|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$orthopedically_handicapped.'|'.$cerebral_palsy.'|'.$vis_imp_cert_img.'|'.$orth_han_cert_img.'|'.$cer_palsy_cert_img.'|'.$img_edited_by."\n";
					  
					/* $benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n"; */
							
					$benchmark_file_flg = fwrite($benchmark_fp, $benchmark_data);
					
					if($benchmark_file_flg)
						$success['benchmark_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
					else
						$error['benchmark_file'] = "Error While Generating Edited Candidate Details Photo File.";
					
					
					// Zip Image Folder
					if($dir_flg)
					{
						$benchmark_zip_flg = 0;
						
						/* Benchmark Code Start */
						// For Visually impaired certificate images
						if($vis_imp_cert_img)
						{
							$vis_imp_cert_img_flg_cnt++;
					
							if(is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['vis_imp_cert_img'];
								$max_width = "1000";
								$max_height = "800";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['vis_imp_cert_img']);
								
								$vis_imp_cert_img_to_add = $directory."/".$imgdata['vis_imp_cert_img'];
								$new_vis_imp_cert_img = substr($vis_imp_cert_img_to_add,strrpos($vis_imp_cert_img_to_add,'/') + 1);
								$vis_imp_cert_img_zip_flg = $zip->addFile($vis_imp_cert_img_to_add,$new_vis_imp_cert_img);
								if(!$vis_imp_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Visually impaired certificate not added to zip  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");	
								}
								else
									$vis_imp_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");	
							}
						}
						
						// For Orthopedically handicapped certificate images
						if($orth_han_cert_img)
						{
							$orth_han_cert_img_flg_cnt++;
					
							if(is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['orth_han_cert_img'];
								$max_width = "1000";
								$max_height = "800";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['orth_han_cert_img']);
								
								$orth_han_cert_img_to_add = $directory."/".$imgdata['orth_han_cert_img'];
								$new_orth_han_cert_img = substr($orth_han_cert_img_to_add,strrpos($orth_han_cert_img_to_add,'/') + 1);
								$orth_han_cert_img_zip_flg = $zip->addFile($orth_han_cert_img_to_add,$new_orth_han_cert_img);
								if(!$orth_han_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate not added to zip  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");	
								}
								else
									$orth_han_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");	
							}
						}
						
						// For Cerebral palsy certificate images
						if($cer_palsy_cert_img)
						{
							$cer_palsy_cert_img_flg_cnt++;
					
							if(is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['cer_palsy_cert_img'];
								$max_width = "1000";
								$max_height = "800";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['cer_palsy_cert_img']);
								
								$cer_palsy_cert_img_to_add = $directory."/".$imgdata['cer_palsy_cert_img'];
								$new_cer_palsy_cert_img = substr($cer_palsy_cert_img_to_add,strrpos($cer_palsy_cert_img_to_add,'/') + 1);
								$cer_palsy_cert_img_zip_flg = $zip->addFile($cer_palsy_cert_img_to_add,$new_cer_palsy_cert_img);
								if(!$cer_palsy_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Cerebral palsy certificate not added to zip  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");	
								}
								else
									$cer_palsy_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");	
							}
						}
						
						
						if($benchmark_zip_flg)
						{
							$success['zip'] = "Edited Candidate Benchmark Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Benchmark Zip";
						}
					}
				}
				
				fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."/".$vis_imp_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."/".$orth_han_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."/".$cer_palsy_cert_img_flg_cnt." \n");
				
				$zip->close();
			}
			else
			{
				$success['img'] = "No Benchmark Edit data found for the date";
			}
			// Close Benchmark
			
			
			
			fclose($benchmark_fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Benchmark Edited Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Benchmark Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Temp Exam Application Cron*/
	public function exam_temp()
	{	exit;
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Exam Temp Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "temp_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "temp_logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Temp Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			$exam_codes_arr = array('1002','1003','1004');
			
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.id,a.created_on';
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			
			//echo $this->db->last_query();exit;
			
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
				
				
					$select = 'ref_id';
					$check_payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>2,'status'=>1,'ref_id'=>$exam['id']),$select);
					
					if(empty($check_payment_data))
					{
				
						$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					$trans_date = '';
					if($exam['created_on'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['created_on']));
					}
					
					$exam_period = '777';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					
					$scribe_flag = $exam['scribe_flag'];
					
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == 60 || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
							
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
							
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
							
						$elected_sub_code = '';
						if($exam_code == 60)
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						
						$transaction_no = '0000000000000';
						$exam_fee = '0';
						
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$scribe_flag."\n";
					}
					else
					{    
						$transaction_no = '0000000000000';
						$exam_fee = '0';
					
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$scribe_flag."\n";
					}
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					
					$i++;
					$exam_cnt++;
					
					}
				}
				
				fwrite($fp1, "Total Exam Temp Applications - ".$exam_cnt."\n");
				
				
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
			$this->log_model->cronlog("Candidate Exam Temp Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate Exam Temp Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Admit Card Cron */
	public function admit_card_temp()
	{
		exit;
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		//$cron_file_dir = "./uploads/cronFilesCustom/";
		
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Exam Admit Card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "temp_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "temp_logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam Temp Admit Card Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-04';
			
			// get member exam application details for given date
			$exam_codes_arr = array('1002','1003','1004');
			$select = 'a.id as mem_exam_id, a.examination_date';
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_status'=>1),$select);
			//'pay_type'=>2,'status'=>1,
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					$select = 'ref_id';
					$check_payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>2,'status'=>1,'ref_id'=>$mem_exam_id),$select);
					
					if(empty($check_payment_data))
					{
						// get admit card details for this member by mem_exam_id
						$this->db->where('remark', 1);
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
						if(count($admit_card_details_arr))
						{
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$data = '';
								
								$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
								
								$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
								
								$venue_name = '';
								if($admit_card_data['venue_name'] != '')
								{
									$venue_name = trim(str_replace(PHP_EOL, '', $admit_card_data['venue_name']));
									$venue_name = str_replace(array("\n", "\r"), '', $venue_name);
								}
								
								$venueadd1 = '';
								if($admit_card_data['venueadd1'] != '')
								{
									$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
									$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
								}
								
								$venueadd2 = '';
								if($admit_card_data['venueadd2'] != '')
								{
									$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
									$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
								}
								
								$venueadd3 = '';
								if($admit_card_data['venueadd3'] != '')
								{
									$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
									$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
								}
								
								// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
								$exam_period = '777';
								if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
								{
									$ex_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
									if(count($ex_period))
									{
										$exam_period = $ex_period[0]['period'];	
									}
								}
								else
								{
									$exam_period = $admit_card_data['exm_prd'];
								}
								
								
								
								$exam_code = '';
								if($admit_card_data['exm_cd'] == 340 || $admit_card_data['exm_cd'] == 3400 || $admit_card_data['exm_cd'] == 34000)
								{
									$exam_code = 34;
								}elseif($admit_card_data['exm_cd'] == 580 || $admit_card_data['exm_cd'] == 5800 || $admit_card_data['exm_cd'] == 58000){
									$exam_code = 58;
								}elseif($admit_card_data['exm_cd'] == 1600 || $admit_card_data['exm_cd'] == 16000){
									$exam_code = 160;
								}elseif($admit_card_data['exm_cd'] == 200){
									$exam_code = 20;
								}elseif($admit_card_data['exm_cd'] == 1770 || $admit_card_data['exm_cd'] == 17700){
									$exam_code =177;
								}elseif ($admit_card_data['exm_cd'] == 590){
									$exam_code = 59;
								}elseif ($admit_card_data['exm_cd'] == 810){
									$exam_code = 81;
								}elseif ($admit_card_data['exm_cd'] == 1750){
									$exam_code = 175;
								}else{
									$exam_code = $admit_card_data['exm_cd'];
								}
								
								$transaction_no = '0000000000000';
								
	//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME
								
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$transaction_no."\n";
								
								$admit_card_sub_count++;
								
								$file_w_flg = fwrite($fp, $data);
							}
							
							if($file_w_flg)
							{
								$success[] = "Exam Temp Admit Card Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Exam Temp Admit Card Details File.";
							}
						}
						
						$admit_card_count++;
					}
				}
				
				fwrite($fp1, "\n"."Total Exam Temp Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Exam Temp Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Exam Temp Admit Card Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Exam Temp Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	
	/* Member Registration Cron */
	public function member()
	{
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
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("New Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_new_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New Member Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2019-11-21';
			$excode = array('528','529','991');
			$this->db->where_not_in('a.excode', $excode);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
			
			if(count($new_mem_reg))
			{
				$dirname = "regd_image_".$current_date;
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
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$declaration_cnt = 0;

				$vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}

					
					$declarationimg = '';
					if(is_file("./uploads/declaration/".$reg_data['declaration']))
					{
						$declarationimg = MEM_FILE_PATH.$reg_data['declaration'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
					}
					
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/					
										
					}
					
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						//'pg_flag'=>'IIBF_EXAM_REG',
						/* For Bulk Members if Non-Member Application */
						if(empty($trans_details))
						{
							$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
							$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
							$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
							$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
							$this->db->where("( DATE(a.updated_date) = '".$yesterday."') AND a.status = 1 AND d.regnumber = '".$reg_data['regnumber']."'");
							$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
							//$trans_details = $this->db->get('bulk_payment_transaction a');
						}
						
						/*if(empty($trans_details))
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						
						if(empty($trans_details))
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EX_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}*/
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					elseif($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
					{
						$get_date = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$reg_data['regnumber']),'DATE_FORMAT(createdon,"%Y-%m-%d") createdon');
						
						$date = $get_date[0]['createdon'];
						$transaction_date = date('d-M-y',strtotime($date));	
						$transaction_no = '000000000000';
						$transaction_amt = '0';	
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					
					/* Benchmark Code Start */
					$benchmark_disability = $reg_data['benchmark_disability'];
					$visually_impaired = $reg_data['visually_impaired'];
					$vis_imp_cert_img = '';
					$orthopedically_handicapped = $reg_data['orthopedically_handicapped'];
					$orth_han_cert_img = '';
					$cerebral_palsy = $reg_data['cerebral_palsy'];
					$cer_palsy_cert_img = '';
					
					if($benchmark_disability == 'Y')
					{
						$vis_imp_cert_img = '';
						if($visually_impaired == 'Y')
						{
							if(is_file("./uploads/disability/".$reg_data['vis_imp_cert_img'])){
							$vis_imp_cert_img 	= MEM_FILE_PATH.$reg_data['vis_imp_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$reg_data['vis_imp_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
						$orth_han_cert_img = '';
						if($orthopedically_handicapped == 'Y')
						{
							if(is_file("./uploads/disability/".$reg_data['orth_han_cert_img'])){
							$orth_han_cert_img 	= MEM_FILE_PATH.$reg_data['orth_han_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$reg_data['orth_han_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
						$cer_palsy_cert_img = '';
						if($cerebral_palsy == 'Y')
						{
							if(is_file("./uploads/disability/".$reg_data['cer_palsy_cert_img'])){
							$cer_palsy_cert_img 	= MEM_FILE_PATH.$reg_data['cer_palsy_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$reg_data['cer_palsy_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
					}
					/* Benchmark Code End */
					
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declarationimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
					
					if($dir_flg)
					{
						/* Benchmark Code Start */
						// For Visually impaired certificate images
						if($vis_imp_cert_img)
						{
							$image = "./uploads/disability/".$reg_data['vis_imp_cert_img'];
							$max_width = "900";
							$max_height = "700";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['vis_imp_cert_img']);
							
							$vis_imp_cert_img_to_add = $directory."/".$reg_data['vis_imp_cert_img'];
							$new_vis_imp_cert_img = substr($vis_imp_cert_img_to_add,strrpos($vis_imp_cert_img_to_add,'/') + 1);
							$vis_imp_cert_img_zip_flg = $zip->addFile($vis_imp_cert_img_to_add,$new_vis_imp_cert_img);
							if(!$vis_imp_cert_img_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Visually impaired certificate not added to zip  - ".$reg_data['vis_imp_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$vis_imp_cert_img_cnt++;
						}
						
						// For Orthopedically handicapped certificate images
						if($orth_han_cert_img)
						{
							$image = "./uploads/disability/".$reg_data['orth_han_cert_img'];
							$max_width = "900";
							$max_height = "700";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['orth_han_cert_img']);
							
							$orth_han_cert_img_to_add = $directory."/".$reg_data['orth_han_cert_img'];
							$new_orth_han_cert_img = substr($orth_han_cert_img_to_add,strrpos($orth_han_cert_img_to_add,'/') + 1);
							$orth_han_cert_img_zip_flg = $zip->addFile($orth_han_cert_img_to_add,$new_orth_han_cert_img);
							if(!$orth_han_cert_img_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate not added to zip  - ".$reg_data['orth_han_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$orth_han_cert_img_cnt++;
						}
						
						// For Cerebral palsy certificate images
						if($cer_palsy_cert_img)
						{
							$image = "./uploads/disability/".$reg_data['cer_palsy_cert_img'];
							$max_width = "900";
							$max_height = "700";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['cer_palsy_cert_img']);
							
							$cer_palsy_cert_img_to_add = $directory."/".$reg_data['cer_palsy_cert_img'];
							$new_cer_palsy_cert_img = substr($cer_palsy_cert_img_to_add,strrpos($cer_palsy_cert_img_to_add,'/') + 1);
							$cer_palsy_cert_img_zip_flg = $zip->addFile($cer_palsy_cert_img_to_add,$new_cer_palsy_cert_img);
							if(!$cer_palsy_cert_img_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate not added to zip  - ".$reg_data['cer_palsy_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$cer_palsy_cert_img_cnt++;
						}
						/* Benchmark Code End */
						
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
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
							$image = "./uploads/declaration/".$reg_data['declaration'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['declaration']);
							
							$declaration_to_add = $directory."/".$reg_data['declaration'];
							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
							if(!$declaration_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$declaration_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "New Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Member Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$declaration_cnt."\n");
				fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."\n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."\n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."\n");
				
				
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
			$this->log_model->cronlog("New Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** New Member Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* PG CSC Member Registration Cron */
	public function member_csc()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("CSC Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_new_mem_csc_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** CSC Member Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2019-11-21';
			$exam_period = array('998','997');
			$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
			$this->db->where_in('a.exam_period', $exam_period);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
			//,'bankcode' => 'csc' - removed by chaitali 2022-07-15
			if(count($new_mem_reg))
			{
				$dirname = "regd_image_csc_".$current_date;
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
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					/*
					$photo 		= MEM_FILE_PATH.$reg_data['scannedphoto'];
					$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];*/
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= CSC_MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= CSC_MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = CSC_MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/				
										
					}
					
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					
					// CSC_EXM_NM
					// CSC_NM_REG
					
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');	
						//'pg_flag'=>'CSC_NM_REG',
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
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
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "New Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Member Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				
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
			$this->log_model->cronlog("CSC Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** CSC Member Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Member Registration Digital ELearning Cron */
	public function member_digital_eLearning()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Digital ELearning Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_digital_eLearning_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_digital_eLearning_mem_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n***** IIBF Digital ELearning Member Details Cron Start - ".$start_time." ***** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			//$yesterday = '2019-09-14';
			
			$excode = array('528','529');
			$this->db->where_in('a.excode', $excode);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
			
			if(count($new_mem_reg))
			{
				$dirname = "mem_digital_eLearning_image_".$current_date;
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
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= DIGITAL_EL_MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= DIGITAL_EL_MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = DIGITAL_EL_MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/		
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					
					/*if($reg_data['registrationtype'] == 'NM')
					{*/
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','pay_type'=>18,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					/*}*/
					
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
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
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "Digital ELearning Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Digital ELearning Member Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "Digital ELearning Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating Digital ELearning Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total Digital ELearning Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Digital ELearning Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Digital ELearning Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total Digital ELearning ID Proofs Added = ".$idproof_cnt."\n");
				
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
			$this->log_model->cronlog("Digital ELearning Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."******* IIBF Digital ELearning Member Details Cron End ".$end_time." ********"."\n");
			fclose($fp1);
		}
	}
	
	/* Only for CSC Vendor : 21-11-2019 */
	public function csc_member()
	{
	    ini_set("memory_limit", "-1");
	    
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		
		//$current_date = '20190603';
		
		$cron_file_dir = "./uploads/cronfiles/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		
		$this->log_model->cronlog("Vendor CSC Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_csc_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_CSC_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$start_time." *************"."\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			$new_mem_reg = $this->Master_model->getRecords('member_registration',array(' DATE(date)'=>$yesterday,'pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'),$select);
			if(count($new_mem_reg))
			{
				$dirname = "csc_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$photo = '';
					
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
								
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "CSC Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating CSC Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "CSC Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating CSC Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor CSC Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	
	public function csc_member_sagar()
	{
		ini_set("memory_limit", "-1");
	    
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		
		//$current_date = '20190603';
		
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		
		$this->log_model->cronlog("Vendor CSC Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_csc_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_CSC_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$start_time." *************"."\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			$mem = array();
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			$this->db->where_in('regnumber',$mem);
			$new_mem_reg = $this->Master_model->getRecords('member_registration',array('pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'),$select);
			//' DATE(date) >='=>'2021-05-01', ' DATE(date) <='=>'2021-05-14',
			if(count($new_mem_reg))
			{
				$dirname = "csc_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$photo = '';
					
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
								
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "CSC Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating CSC Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "CSC Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating CSC Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor CSC Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	
	/* E-learning Separate Module Member Registration Cron : Added by sagar on 26-08-2021 */
	public function member_elearning_spm()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = $parent_dir_flg = $member_file_flg = 0;
		$success = $error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
						
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("E-learning Separate Module Member Registration Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); }
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_new_member_elearning_spm_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** E-learning Separate Module Member Registration Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$this->db->where('DATE(er.createdon)',$yesterday);
			
			$this->db->group_by('er.regnumber');
			$select = 'er.regid, er.regnumber, er.namesub, er.firstname, er.middlename, er.lastname, er.state, er.email, er.registrationtype, er.mobile, er.isactive, er.createdon, pt.id AS PtId, pt.pay_type, pt.status AS PtStatus';
			$this->db->join('payment_transaction pt', 'pt.member_regnumber = er.regnumber AND pt.pay_type = 20 AND status = 1', 'INNER');
			$member_data = $this->Master_model->getRecords('spm_elearning_registration er',array('er.isactive'=>'1'),$select);
						
			if(count($member_data))
			{
				$i = 1;
				$rec_cnt = 0;
				foreach($member_data as $res)
				{
					$data = '';
											
					$data .= $res['regnumber'].'|'.$res['namesub'].'|'.$res['firstname'].'|'.$res['middlename'].'|'.$res['lastname'].'|'.$res['state'].'|'.$res['email'].'|'.$res['mobile'].'|'.$res['createdon']."\n";  
					
					$member_file_flg = fwrite($fp, $data);
					
					if($member_file_flg){ $success['member_details'] = "E-learning Separate Module Member Registration Details File Generated Successfully."; }
					else { $error['member_details'] = "Error While Generating E-learning Separate Module Member Registration Details File."; }
					
					$i++;
					$rec_cnt++;
				}
				
				fwrite($fp1, "Total E-learning Separate Module Member Registration - ".$rec_cnt."\n");
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
			$this->log_model->cronlog("E-learning Separate Module Member Registration Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** E-learning Separate Module Member Registration Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1); 
		}
	}	
		
	/* Membership Edit data Cron */
	public function edit_data()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$photo_file_flg = 0;
		$sign_file_flg = 0;
		$idproof_file_flg = 0;
		$decl_file_flg = 0;
		$edit_file_flg = 0;
		$success = array();
		$error = array();
		$ii = 0;
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Edited Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))  
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "edited_cand_details_".$current_date.".txt";	// Path with CURRENT DATE DIRECTORY
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			 $photo_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$sign_fp = fopen($cron_file_path.'/'.$file, 'w');
			$id_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$decl_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Edited Candidate Details Cron Start - ".$start_time."**********\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d'); 
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0 ,'DATE(images_editedon)'=>'0000-00-00'));
			//'DATE(images_editedon) != '=>$yesterday
			if(count($edited_mem_data))
			{
				$i = 1;
				$mem_cnt = 0;
				foreach($edited_mem_data as $editeddata)
				{
					$photo_data = '';
					$sign_data = '';
					$id_data = '';
					
					$data = '';
					$gender = '';
					if($editeddata['gender'] == 'male')	{ $gender = 'M';}
					else if($editeddata['gender'] == 'female')	{ $gender = 'F';}
					
					$qualification = '';
					switch($editeddata['qualification'])
					{				
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/						
					}
					
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					
					$mem_dob = '';
					if($editeddata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($editeddata['dateofbirth']));
					}
					$mem_doj = '';
					if($editeddata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($editeddata['dateofjoin']));
					}
					
					if(strlen($editeddata['stdcode']) > 10)
					{	$std_code = substr($editeddata['stdcode'],0,9);		}
					else
					{	$std_code = $editeddata['stdcode'];	}
					
					if(strlen($editeddata['address1']) > 30)
					{	$address1 = substr($editeddata['address1'],0,29);		}
					else
					{	$address1 = $editeddata['address1'];	}
					
					if(strlen($editeddata['address2']) > 30)
					{	$address2 = substr($editeddata['address2'],0,29);		}
					else
					{	$address2 = $editeddata['address2'];	}
					
					if(strlen($editeddata['address3']) > 30)
					{	$address3 = substr($editeddata['address3'],0,29);		}
					else
					{	$address3 = $editeddata['address3'];	}
					
					if(strlen($editeddata['address4']) > 30)
					{	$address4 = substr($editeddata['address4'],0,29);		}
					else
					{	$address4 = $editeddata['address4'];	}
					
					if(strlen($editeddata['district']) > 30)
					{	$district = substr($editeddata['district'],0,29);		}
					else
					{	$district = $editeddata['district'];	}
					
					if(strlen($editeddata['city']) > 30)
					{	$city = substr($editeddata['city'],0,29);		}
					else
					{	$city = $editeddata['city'];	}
					
					if($editeddata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $editeddata['editedby'];	
					}
					
					$branch = '';
					$branch_name = '';
					if($editeddata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $editeddata['branch'];
					}
					else if($editeddata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($editeddata['office']))
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
						else
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					
					$optnletter = "Y";
					if($editeddata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($editeddata['optnletter'] != "")
					{	$optnletter = $editeddata['optnletter'];	}
					
					if($ii == 0){$data = ''; }else{$data = "\n";}
					
					$data .= $editeddata['regnumber'].'|'.$editeddata['registrationtype'].'|'.$editeddata['namesub'].'|'.$editeddata['firstname'].'|'.$editeddata['middlename'].'|'.$editeddata['lastname'].'|'.$editeddata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$editeddata['pincode'].'|'.$editeddata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$editeddata['specify_qualification'].'|'.$editeddata['associatedinstitute'].'|'.$branch_name.'|'.$editeddata['designation'].'|'.$mem_doj.'|'.$editeddata['email'].'|'.$std_code.'|'.$editeddata['office_phone'].'|'.$editeddata['mobile'].'|'.$editeddata['idproof'].'|'.$editeddata['idNo'].'||||';
					
					$data .= $edited_by.'|'.$optnletter.'|N|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card'].'|'.$editeddata['bank_emp_id'].'||||';
					
					
					
					//print_r($data); die;
					$edit_data_flg = fwrite($fp, $data);
					if($edit_data_flg)
						$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
					else
						$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
					
					$i++;
					$mem_cnt++;
					$ii++;
				}
				
				fwrite($fp1, "\n"."Total Members Edited = ".$mem_cnt."\n");
			}
			else
			{
				$success[] = "No Profile data found for the date";
			}
			
			// Image data
			$edited_img_data = $this->Master_model->getRecords('member_registration',array('DATE(images_editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			if(count($edited_img_data))
			{
				$dirname = "edited_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
					// Delete all the files in directory
					array_map('unlink', glob($directory."/*.*"));
					// Remove directory
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);
				
				//$imgdata = $edited_img_data[0];
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				$declaration_flg_cnt = 0;
				foreach($edited_img_data as $imgdata)
				{	
					$photo = '';
					$signature = '';
					$idproofimg = '';
					$declaration = '';
					$img_edited_by = '';
					
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/			
					}
					
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					
					if($ii == 0){$data = ''; }else{$data = "\n";}
					$data .= $imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';					
					
					if($imgdata['images_editedby'] == '')
					{
						$img_edited_by = "Candidate";
					}
					else
					{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					
					$this->load->model('Image_search_model');
					
					$photo = $signature = $idproofimg = $declaration = '';
					$photo_send = $signature_send = $idproofimg_send = $declaration_send = '';
					$photoflag = $imgdata['photo_flg'];
					$signflag = $imgdata['signature_flg'];
					$idprofflag = $imgdata['id_flg'];
					$declarationflag = $imgdata['declaration_flg'];
					$member_img_response = $this->Image_search_model->get_member_data($imgdata['regnumber']);

				    $photoserver = $member_img_response['scannedphoto'];
				    $idproofimgserver = $member_img_response['idproofphoto'];
					$declarationserver = $member_img_response['declarationphoto'];
				    $signatureserver = $member_img_response['scannedsignaturephoto'];
					
					if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))
					{
						$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];
					}
					else{
						$photoarr = explode("/",$photoserver); 
						$photo 	= MEM_FILE_EDIT_PATH.$photoarr[2];
					}
					 
					if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];
					}
					else{
						$signarr = explode("/",$signatureserver);
						$signature 	= MEM_FILE_EDIT_PATH.$signarr[2];
					}
					
					if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))
					{
						$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];
					}
					else{
						$idarr = explode("/",$idproofimgserver);
						$idproofimg = MEM_FILE_EDIT_PATH.$idarr[2];
					}
					if($imgdata['declaration'] != "" && is_file("./uploads/declaration/".$imgdata['declaration']))
					{
						$declaration = MEM_FILE_EDIT_PATH.$imgdata['declaration'];
					}
					else{
						$declarationarr = explode("/",$declarationserver);
						$declaration = MEM_FILE_EDIT_PATH.$declarationarr[2];
					}
					$photo_img_data = '';
					$sign_img_data = '';
					$idproff_img_data = '';
					$decl_img_data = '';
					if($photoflag == 'Y'){$photo_img_data = $photo;}
					if($signflag == 'Y'){$sign_img_data = $signature;}
					if($idprofflag == 'Y'){$idproff_img_data = $idproofimg;}
					if($declarationflag == 'Y'){$decl_img_data = $declaration;}
					
					$edit_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photoflag.'|'.$signflag.'|'.$idprofflag.'|'.$declarationflag.'|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo_img_data.'|'.$sign_img_data.'|'.$idproff_img_data.'|'.$decl_img_data;
					$edit_file_flg = fwrite($fp, $edit_data);
					if($edit_file_flg){
					$success['file'] = "Edited Candidate Details Photo File Generated Successfully. ";}
					else{
					$error['file'] = "Error While Generating Edited Candidate Details Photo File.";}
					/* if($photo != '')
					{
						if($imgdata['photo_flg']=='Y')
						{
							//$photo_data = $data.$img_edited_by.'|'.$optnletter.'|Y|N|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo.'|||';
							//xxx $photo_file_flg = fwrite($photo_fp, $photo_data);
							
						}
						$edited_photo_flg = $imgdata['photo_flg'];
						if($photo_file_flg)
							$success['photo_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
						else
							$error['photo_file'] = "Error While Generating Edited Candidate Details Photo File.";
					} 
					else
					{
						$edited_photo_flg = "N";
					}
					
					if($signature != '')
					{
						if($imgdata['signature_flg']=='Y')
						{
							$sign_data = $data.$img_edited_by.'|'.$optnletter.'|N|Y|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'||'.$signature.'||';
							//xxx $sign_file_flg =  fwrite($sign_fp, $sign_data);
						}
						$edited_sign_flg = $imgdata['signature_flg'];
						if($sign_file_flg)
							$success['sign_file'] = "Edited Candidate Details Signature File Generated Successfully.";
						else
							$error['sign_file'] = "Error While Generating Edited Candidate Details Signature File.";
					}
					else
					{
						$edited_sign_flg = "N";	
					}
					
					if($idproofimg != '')
					{
						if($imgdata['id_flg']=='Y')
						{
							$id_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|Y|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|||'.$idproofimg.'|';
							//xxx $idproof_file_flg =  fwrite($id_fp, $id_data);
						}
						$edited_idproof_flg = $imgdata['id_flg'];
						if($idproof_file_flg)
							$success['idproof_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['idproof_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					else
					{
						$edited_idproof_flg = "N";
					} 
					
					if($declaration != '')
					{
						if($imgdata['declaration_flg']=='Y')
						{
							$decl_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|N|Y|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'||||'.$declaration;
							//xxx $decl_file_flg =  fwrite($decl_fp, $decl_data);
						}
						$edited_idproof_flg = $imgdata['declaration_flg'];
						if($decl_file_flg)
							$success['declaration_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['declaration_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					else
					{
						$edited_idproof_flg = "N";
					} */
					 
					//WRITE CODE GOES HERE
					//echo $data;
					//echo '<br>=================================================================<br><br>';
					
					// Zip Image Folder
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						$declaration_zip_flg = 0;
						if($imgdata['photo_flg'] == 'Y' && $photo != '')
						{
							$photo_flg_cnt++;
							
							// For photo images
							if(is_file("./uploads/photograph/".$imgdata['scannedphoto']))
							{
								$image = "./uploads/photograph/".$imgdata['scannedphoto'];
								//$image = $photoarr[2];
								$max_width = "200";
								$max_height = "200";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedphoto']);
								
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$imgdata['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							elseif($photoserver!= '')
							{
								
								$image = $photoserver;
								$max_width = "200";
								$max_height = "200";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$photoarr[2]);
								
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$photoarr[2];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photoarr[2]." (".$imgdata['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						if($imgdata['signature_flg'] == 'Y' && $signature != '')
						{
							$sign_flg_cnt++;
							//copy("./uploads/scansignature/".$imgdata['scannedsignaturephoto'],$directory."/".$imgdata['scannedsignaturephoto']);
							// For signature images
							if(is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$imgdata['scannedsignaturephoto'];
								//$image = $signature;
								$max_width = "140";
								$max_height = "100";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedsignaturephoto']);
								
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$imgdata['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							elseif($signatureserver != '')
							{
								
								$image = $signatureserver;
								$max_width = "140";
								$max_height = "100";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$signarr[2]);
								
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$signarr[2];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$signarr[2]." (".$imgdata['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['id_flg'] == 'Y' && $idproofimg != '')
						{
							$idproof_flg_cnt++;
							
							// For ID proof images
							if(is_file("./uploads/idproof/".$imgdata['idproofphoto']))
							{
								$image = "./uploads/idproof/".$imgdata['idproofphoto'];
								$max_width = "800";
								$max_height = "500";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['idproofphoto']);
								
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$imgdata['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							elseif($idproofimgserver != '')
							{ 
								$image = $idproofimgserver;
								$max_width = "800";
								$max_height = "500";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$idarr[2]);
								
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$idarr[2];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idarr[2]." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$idproofimg." (".$imgdata['regnumber'].")\n");	
							}
						}
						//echo $declaration; echo $imgdata['declaration']; die;
						if($imgdata['declaration_flg'] == 'Y' && $declaration != '')
						{
							$declaration_flg_cnt++;
							// For declaration images
							if(is_file("./uploads/declaration/".$imgdata['declaration']))
							{
								$image = "./uploads/declaration/".$imgdata['declaration'];
								
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['declaration']);
								$declaration_to_add = $directory."/".$imgdata['declaration'];
								$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
								$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
								if(!$declaration_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$declaration." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$declaration_cnt++;
							}
							elseif($declarationserver !='')
							{
								$image = $declarationserver;
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$declarationarr[2]);
								$declaration_to_add = $directory."/".$declarationarr[2];
								$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
								$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
								if(!$declaration_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$declarationarr[2]." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$declaration_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$declaration." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Images Zip";
						}
					}
					$ii++;
				}
				
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."/".$declaration_flg_cnt."\n");
				$zip->close();
			}
			else
			{
				$success['img'] = "No Image data found for the date";
			}
			
			fclose($fp);
			fclose($photo_fp);
			fclose($sign_fp);
			fclose($id_fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	

	/* Membership Edit data Cron */
	public function edit_data_old()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$photo_file_flg = 0;
		$sign_file_flg = 0;
		$idproof_file_flg = 0;
		$decl_file_flg = 0;
		$success = array();
		$error = array();
		 
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Edited Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date)) 
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			/* $file = "edited_cand_details_".$current_date.".txt";
			$photo_file = "edited_cand_details_photo_".$current_date.".txt";
			$sign_file = "edited_cand_details_sign_".$current_date.".txt";
			$id_file = "edited_cand_details_id_".$current_date.".txt"; */
			$file = "edited_cand_details_".$current_date.".txt";	// Path with CURRENT DATE DIRECTORY
			$benchmark_file = "edited_cand_details_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			 $photo_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$sign_fp = fopen($cron_file_path.'/'.$file, 'w');
			$id_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$decl_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Edited Candidate Details Cron Start - ".$start_time."**********\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d'); 
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			if(count($edited_mem_data))
			{
				$i = 1;
				$mem_cnt = 0;
				foreach($edited_mem_data as $editeddata)
				{
					$photo_data = '';
					$sign_data = '';
					$id_data = '';
					
					$data = '';
					$gender = '';
					if($editeddata['gender'] == 'male')	{ $gender = 'M';}
					else if($editeddata['gender'] == 'female')	{ $gender = 'F';}
					
					$qualification = '';
					switch($editeddata['qualification'])
					{				
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/						
					}
					
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					
					$mem_dob = '';
					if($editeddata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($editeddata['dateofbirth']));
					}
					$mem_doj = '';
					if($editeddata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($editeddata['dateofjoin']));
					}
					
					if(strlen($editeddata['stdcode']) > 10)
					{	$std_code = substr($editeddata['stdcode'],0,9);		}
					else
					{	$std_code = $editeddata['stdcode'];	}
					
					if(strlen($editeddata['address1']) > 30)
					{	$address1 = substr($editeddata['address1'],0,29);		}
					else
					{	$address1 = $editeddata['address1'];	}
					
					if(strlen($editeddata['address2']) > 30)
					{	$address2 = substr($editeddata['address2'],0,29);		}
					else
					{	$address2 = $editeddata['address2'];	}
					
					if(strlen($editeddata['address3']) > 30)
					{	$address3 = substr($editeddata['address3'],0,29);		}
					else
					{	$address3 = $editeddata['address3'];	}
					
					if(strlen($editeddata['address4']) > 30)
					{	$address4 = substr($editeddata['address4'],0,29);		}
					else
					{	$address4 = $editeddata['address4'];	}
					
					if(strlen($editeddata['district']) > 30)
					{	$district = substr($editeddata['district'],0,29);		}
					else
					{	$district = $editeddata['district'];	}
					
					if(strlen($editeddata['city']) > 30)
					{	$city = substr($editeddata['city'],0,29);		}
					else
					{	$city = $editeddata['city'];	}
					
					if($editeddata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $editeddata['editedby'];	
					}
					
					$branch = '';
					$branch_name = '';
					if($editeddata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $editeddata['branch'];
					}
					else if($editeddata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($editeddata['office']))
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
						else
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					
					$optnletter = "Y";
					if($editeddata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($editeddata['optnletter'] != "")
					{	$optnletter = $editeddata['optnletter'];	}
					
					$data = ''.$editeddata['regnumber'].'|'.$editeddata['registrationtype'].'|'.$editeddata['namesub'].'|'.$editeddata['firstname'].'|'.$editeddata['middlename'].'|'.$editeddata['lastname'].'|'.$editeddata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$editeddata['pincode'].'|'.$editeddata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$editeddata['specify_qualification'].'|'.$editeddata['associatedinstitute'].'|'.$branch_name.'|'.$editeddata['designation'].'|'.$mem_doj.'|'.$editeddata['email'].'|'.$std_code.'|'.$editeddata['office_phone'].'|'.$editeddata['mobile'].'|'.$editeddata['idproof'].'|'.$editeddata['idNo'].'||||';
					
					$data .= $edited_by.'|'.$optnletter.'|N|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card'].'|'.$editeddata['bank_emp_id'].'||||'."\n";
					
					
					
					//print_r($data); die;
					$edit_data_flg = fwrite($fp, $data);
					if($edit_data_flg)
						$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
					else
						$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
					
					$i++;
					$mem_cnt++;
				}
				
				fwrite($fp1, "\n"."Total Members Edited = ".$mem_cnt."\n");
			}
			else
			{
				$success[] = "No Profile data found for the date";
			}
			
			// Image data
			$edited_img_data = $this->Master_model->getRecords('member_registration',array('DATE(images_editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			if(count($edited_img_data))
			{
				$dirname = "edited_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
					// Delete all the files in directory
					array_map('unlink', glob($directory."/*.*"));
					// Remove directory
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);
				
				//$imgdata = $edited_img_data[0];
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				$declaration_flg_cnt = 0;
				foreach($edited_img_data as $imgdata)
				{	
					$photo = '';
					$signature = '';
					$idproofimg = '';
					$declaration = '';
					$img_edited_by = '';
					
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/			
					}
					
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';
					
					
					
					if($imgdata['images_editedby'] == '')
					{
						$img_edited_by = "Candidate";
					}
					else
					{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					
					$this->load->model('Image_search_model');
					
					$photo = $signature = $idproofimg = $declaration = '';
					$photo_send = $signature_send = $idproofimg_send = $declaration_send = '';
					$photoflag = $imgdata['photo_flg'];
					$signflag = $imgdata['signature_flg'];
					$idprofflag = $imgdata['id_flg'];
					$declarationflag = $imgdata['declaration_flg'];
					$member_img_response = $this->Image_search_model->get_member_data($imgdata['regnumber']);

				    $photo = $member_img_response['scannedphoto'];
				    $idproofimg = $member_img_response['idproofphoto'];
					$declaration = $member_img_response['declarationphoto'];
				    $signature = $member_img_response['scannedsignaturephoto'];
					
					if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))
					{
						$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];
					}
					 
					if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];
					}
					
					if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))
					{
						$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];
					}
					if($imgdata['declaration'] != "" && is_file("./uploads/declaration/".$imgdata['declaration']))
					{
						$declaration = MEM_FILE_EDIT_PATH.$imgdata['declaration'];
					}
					 if($photo != '')
					{
						if($imgdata['photo_flg']=='Y')
						{
							$photo_data = $data.$img_edited_by.'|'.$optnletter.'|Y|N|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo.'|||'."\n";
							$photo_file_flg = fwrite($photo_fp, $photo_data);
						}
						$edited_photo_flg = $imgdata['photo_flg'];
						if($photo_file_flg)
							$success['photo_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
						else
							$error['photo_file'] = "Error While Generating Edited Candidate Details Photo File.";
					} 
					else
					{
						$edited_photo_flg = "N";
					}
					
					if($signature != '')
					{
						if($imgdata['signature_flg']=='Y')
						{
							$sign_data = $data.$img_edited_by.'|'.$optnletter.'|N|Y|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'||'.$signature.'||'."\n";
							$sign_file_flg =  fwrite($sign_fp, $sign_data);
						}
						$edited_sign_flg = $imgdata['signature_flg'];
						if($sign_file_flg)
							$success['sign_file'] = "Edited Candidate Details Signature File Generated Successfully.";
						else
							$error['sign_file'] = "Error While Generating Edited Candidate Details Signature File.";
					}
					else
					{
						$edited_sign_flg = "N";	
					}
					
					if($idproofimg != '')
					{
						if($imgdata['id_flg']=='Y')
						{
							$id_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|Y|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|||'.$idproofimg.'|'."\n";
							$idproof_file_flg =  fwrite($id_fp, $id_data);
						}
						$edited_idproof_flg = $imgdata['id_flg'];
						if($idproof_file_flg)
							$success['idproof_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['idproof_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					else
					{
						$edited_idproof_flg = "N";
					} 
					if($declaration != '')
					{
						if($imgdata['declaration_flg']=='Y')
						{
							$decl_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|N|Y|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'||||'.$declaration."\n";
							$decl_file_flg =  fwrite($decl_fp, $decl_data);
						}
						$edited_idproof_flg = $imgdata['declaration_flg'];
						if($decl_file_flg)
							$success['declaration_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['declaration_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					else
					{
						$edited_idproof_flg = "N";
					} 
					// Zip Image Folder
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						$declaration_zip_flg = 0;
						if($imgdata['photo_flg'] == 'Y' && $photo != '')
						{
							$photo_flg_cnt++;
							
							// For photo images
							if(is_file("./uploads/photograph/".$imgdata['scannedphoto']))
							{
								$image = "./uploads/photograph/".$imgdata['scannedphoto'];
								$max_width = "200";
								$max_height = "200";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedphoto']);
								
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$imgdata['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						if($imgdata['signature_flg'] == 'Y' && $signature != '')
						{
							$sign_flg_cnt++;
							//copy("./uploads/scansignature/".$imgdata['scannedsignaturephoto'],$directory."/".$imgdata['scannedsignaturephoto']);
							// For signature images
							if(is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$imgdata['scannedsignaturephoto'];
								$max_width = "140";
								$max_height = "100";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedsignaturephoto']);
								
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$imgdata['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['id_flg'] == 'Y' && $idproofimg != '')
						{
							$idproof_flg_cnt++;
							
							// For ID proof images
							if(is_file("./uploads/idproof/".$imgdata['idproofphoto']))
							{
								$image = "./uploads/idproof/".$imgdata['idproofphoto'];
								$max_width = "800";
								$max_height = "500";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['idproofphoto']);
								
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$imgdata['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['id_flg'] == 'Y' && $declaration != '')
						{
							$declaration_flg_cnt++;
							// For declaration images
							if(is_file("./uploads/declaration/".$imgdata['declaration']))
							{
								$image = "./uploads/declaration/".$imgdata['declaration'];
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['declaration']);
								$declaration_to_add = $directory."/".$imgdata['declaration'];
								$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
								$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
								if(!$declaration_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$imgdata['declaration']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$declaration_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$imgdata['declaration']." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Images Zip";
						}
					}
				}
				
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."/".$declaration_flg_cnt."\n");
				$zip->close();
			}
			else
			{
				$success['img'] = "No Image data found for the date";
			}
			
			fclose($fp);
			fclose($photo_fp);
			fclose($sign_fp);
			fclose($id_fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	
	
	/* Duplicate i-card  Cron */
	public function dup_icard()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dup_icard_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Duplicate I-card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_dup_icard_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Duplicate I-card Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$select = 'c.regnumber,c.reason_type,c.icard_cnt,a.registrationtype,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.transaction_no';
			$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
			$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
			$dup_icard_data = $this->Master_model->getRecords('duplicate_icard c',array(' DATE(added_date)'=>$yesterday,'pay_type'=>3,'isactive'=>'1','status'=>'1','isdeleted'=>0),$select);
			if(count($dup_icard_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($dup_icard_data as $icard)
				{
					$reason_type = '';
					switch($icard['reason_type'])
					{
						case "mis"	: 	$reason_type = 1;
										break;
						case "dam"	: 	$reason_type = 2;
										break;
						case "cha"	: 	$reason_type = 3;
										break;
					}
					
					//MEM_MEM_NO,MEM_MEM_TYP,REQ_REASON,BDRNO,MEM_DUP_SNO,TRN_DATE,TRN_AMT,INSTRUMENT_NO
					
					$icard_date = '';
					if($icard['date'] != '0000-00-00')
					{
						$icard_date = date('d-M-Y',strtotime($icard['date']));
					}
													
					$data .= ''.$icard['regnumber'].'|'.$icard['registrationtype'].'|'.$reason_type.'|'.$icard['transaction_no'].'|'.$icard['icard_cnt'].'|'.$icard_date.'|'.$icard['amount'].'|'.$icard['transaction_no']."\n";
		
					$i++;
					$mem_cnt++;
				}
				
				fwrite($fp1, "\n"."Total Duplicate I-card Applications = ".$mem_cnt."\n");
				
				$dup_icard_flg = fwrite($fp, $data);
				if($dup_icard_flg)
						$success['dup_icard'] = "Duplicate I-card Details File Generated Successfully. ";
				else
					$error['dup_icard'] = "Error While Generating Duplicate I-card Details File.";
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
			$this->log_model->cronlog("Duplicate I-card Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Duplicate I-card Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Exam Application Cron*/
	public function exam()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs123_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday2 = date('Y-m-d', strtotime("- 2 day")); 
			
			$not_exam_codes = array('991','997','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027','1019','1020'); // Not becoz this exam code we send exam date from other crons CSC and remote
			
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.sub_el_count,a.optFlg';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on) <='=>$yesterday,' DATE(a.created_on) >='=>$yesterday2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'pay_type'=>2),$select);
			//echo $this->db->last_query();exit;
			if(count($can_exam_data)) 
			{
				
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					
					if($exam['exam_code'] == 1021 || $exam['exam_code'] == 1022 || $exam['exam_code'] == 1023 || $exam['exam_code'] == 1024 || $exam['exam_code'] == 1025)
					{
						$syllabus_code = 'R';
						$part_no = '1';
					}
					else
					{
						$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
						if(count($subject_data)>0)
						{
							$syllabus_code = $subject_data[0]['syllabus_code'];
							$part_no = $subject_data[0]['part_no'];
						}
					}
					
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					
					$exam_period = '';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					
					$scribe_flag = $exam['scribe_flag'];
					
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					else if($exam_code == '1016' || $exam_code == '1018')
					{
						$part_no = 1;
						$syllabus_code = 'R';
					}
					
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == 60 || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
							
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
							
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
							
						$elected_sub_code = '';
						if($exam_code == 60)
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						
						
						
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count'].'|'.$exam['optFlg']."\n"; 
						
						
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count'].'|'.$exam['optFlg']."\n";  
						
						
					}
					echo $data.'<br>';
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
				
				
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
			$this->log_model->cronlog("Candidate Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
			//echo base_url().$cron_file_path.'/'.$file1;
		}
	}
	
	/* CSC Exam Application Cron*/
	public function csc_exam()
	{
		
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "csc_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** CSC Exam Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,c.email,c.associatedinstitute,c.mobile';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',array('991','997'));
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.free_paid_flg'=>'P'),$select);
			
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					
					$exam_date = '';
					if($exam['exam_date'] != '0000-00-00') 
					{
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					}
					
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = '';
					
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE
					if($exam_code == '997')
					{
						$ippb_data = $this->Master_model->getRecords('member_registration_ippb a',array('mobile'=>$exam['mobile']),'emp_id');
						$emp_id = $ippb_data[0]['emp_id'];
						
						if(empty($exam['associatedinstitute']))
						{ 
							$inst_id = '2851';
						}
						else{
							$inst_id = $exam['associatedinstitute'];
						}
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$inst_id.'|'.$emp_id."\n";
					}
					else{
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'||'."\n";
					}
					 
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "CSC Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating CSC Exam Details File.";
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total CSC Exam Applications - ".$exam_cnt."\n");
				
				
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
			$this->log_model->cronlog("CSC Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** CSC Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Remote Exam Application Cron*/
	public function remote_exam()
	{
		
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		//$current_date = '2023-01-07';	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Remote Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "remote_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Remote Exam Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			$exam_codes_arr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027','1019','1020');
			
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,a.elearning_flag,a.free_paid_flg,a.sub_el_count';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					//$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					
					//LIKE CONDITION FOR EXAM CODE ADDED BY POOJA MANE 10-01-23 
					$this->db->like('exam_code', $exam['exam_code']);
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					
					$exam_date = '';
					if($exam['exam_date'] != '0000-00-00')
					{
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					}
					
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = $free_paid_flg = '';
					
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					
					if($exam['exam_fee'] == '295.00')
					{
						$free_paid_flg = 'F';
					}
					else
					{
						$free_paid_flg = $exam['free_paid_flg'];
					}
					if($exam_code  == '2027')
					{
						$exam_code = '1017';
					}
					else{
						$exam_code = $exam['exam_code'];
					}	//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG,free_paid_fl FREE_PAID_FLG(P/F)
					if($exam['elearning_flag'] == 'Y')
					{
						$el_count = '1';//$exam['sub_el_count']
					}
					else{
						$el_count = $exam['sub_el_count'];
					}
					
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$exam['elearning_flag'].'|'.$free_paid_flg.'|'.$el_count."\n";
					//'|'.$exam['sub_el_count'].
					 
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Remote Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Remote Exam Details File.";
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total Remote Exam Applications - ".$exam_cnt."\n");
				
				
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
			$this->log_model->cronlog("Remote Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Remote Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Free Remote Exam Application Cron*/
	public function remote_exam_free()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Free Remote Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "free_remote_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Free Remote Exam Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$exam_codes_arr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','991');
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,a.elearning_flag,a.free_paid_flg,a.created_on';
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.free_paid_flg'=>'F'),$select);
			
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0){
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					$trans_date = '';
					if($exam['created_on'] != '0000-00-00'){
						$trans_date = date('d-M-Y',strtotime($exam['created_on']));
					}
					
					$exam_date = '';
					if($exam['exam_date'] != '0000-00-00'){
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					}
					
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = '';
					
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					$transaction_no = '0000000000000';
					$exam_fee = '0';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG(Y/N),FREE_PAID_FLG(F/P)
					
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$exam['elearning_flag'].'|'.$exam['free_paid_flg']."\n"; 
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Free Remote Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Free Remote Exam Details File.";
					
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Free Remote Exam Applications - ".$exam_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Free Remote Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Free Remote Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Exam E-learning Separate Module Application Cron : Added by sagar on 24-08-2021 */
	public function exam_elearning_spm()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Exam E-learning Separate Module Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); }
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_cand_elearning_spm_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam E-learning Separate Module Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$this->db->where('DATE(ms.created_on)',$yesterday);
			
			$this->db->group_by('ms.el_sub_id');
			$select = 'ms.el_sub_id, ms.regid, ms.subject_description, ms.fee_amount, ms.sgst_amt,  ms.cgst_amt, ms.igst_amt, ms.cs_tot, ms.igst_tot, ms.updated_on, pt.transaction_no, ms.exam_code, ms.subject_code, pt.member_regnumber, pt.member_exam_id, pt.amount, DATE_FORMAT(pt.date,"%Y-%m-%d")date, pt.receipt_no, pt.ref_id, pt.status, er.regnumber, er.registrationtype, er.state';
			$this->db->join('payment_transaction pt','pt.receipt_no = ms.receipt_no','LEFT');
			$this->db->join('spm_elearning_registration er','er.regnumber = ms.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1', 'pt.pay_type'=>'20', 'pt.status'=>'1','er.isactive'=>'1'),$select);
			//echo $this->db->last_query(); exit; 
			
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					
					$trans_date = '';
					if($exam['date'] != '0000-00-00') { $trans_date = date('d-M-Y',strtotime($exam['date'])); }
					
					$data .= ''.$exam['el_sub_id'].'|'.$exam['regid'].'|'.$exam['regnumber'].'|'.$exam['transaction_no'].'|'.$exam['receipt_no'].'|'.$exam['exam_code'].'|'.$exam['subject_code'].'|'.$exam['subject_description'].'|'.$exam['fee_amount'].'|'.$exam['sgst_amt'].'|'.$exam['cgst_amt'].'|'.$exam['igst_amt'].'|'.$exam['cs_tot'].'|'.$exam['igst_tot'].'|'.$exam['status'].'|'.$trans_date.'|'.$exam['updated_on']."\n";  
					
					$exam_file_flg = fwrite($fp, $data);
					
					if($exam_file_flg){ $success['cand_exam'] = "Candidate Exam E-learning Separate Module Details File Generated Successfully."; }
					else { $error['cand_exam'] = "Error While Generating Candidate Exam E-learning Separate Module Details File."; }
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total Exam E-learning Separate Module Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam E-learning Separate Module Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate Exam E-learning Separate Module Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}	
		
		
	/* DRA Members Cron */
	public function dra_member()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$tc_zip_flg = 0;
		$dc_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");			
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("New DRA Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dra_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New DRA Candidate Details Cron Start - ".$start_time." ***************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
			$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
			$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
			$this->db->join('dra_members d','d.regid = c.regid','LEFT');
			$this->db->where("DATE(a.updated_date) = '".$yesterday."' AND 'isdeleted'= 0 AND a.status = 1 AND d.new_reg = 1 ");
			$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
			
		/*AND re_attempt = 0
		$this->db->where("( DATE(a.date) = '".$yesterday."'  OR DATE(a.updated_date) = '".$yesterday."') AND 'isdeleted'= 0 AND a.status = 1");*/
			
			if(count($new_dra_reg))
			{
				$data = '';
				$dirname = "dra_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
				 	array_map('@unlink', glob($directory."/*.*"));
				 	rmdir($directory);
				// $existing_files = glob($directory.'/*'); // get all file names
    //                 foreach($existing_files as $file)
    //                 { // iterate files
    //                     if(is_file($file))
    //                     {
    //                         @unlink($file); // delete file
    //                     }
    //                 }
    //                 rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$tcimg_cnt = 0;
				$dcimg_cnt = 0;
				foreach($new_dra_reg as $dra)
				{
					$gender = '';
					if($dra['gender'] == 'male')	{ $gender = 'M';}
					else if($dra['gender'] == 'female')	{ $gender = 'F';}
					
					$dc_file = '';
					$tc_file = '';
					$photo_file = '';
					$sign_file = '';
					$idproof_file = '';
					if($dir_flg)
					{
						if($dra['quali_certificate'])
						{
							$file_arr = explode('.',$dra['quali_certificate']);
							$dc_file = "dc_".$dra['regnumber'].".".end($file_arr);
						}
						
						if($dra['training_certificate']) 
						{
							$file_arr1 = explode('.',$dra['training_certificate']);
							$tc_file = "tc_".$dra['regnumber'].".".end($file_arr1);
						}
						
						// Photograph
						if($dra['scannedphoto'] != '' && is_file("./uploads/iibfdra/".$dra['scannedphoto']))
						{
							$photo_file = $dra['scannedphoto'];
							copy("./uploads/iibfdra/".$dra['scannedphoto'],$directory."/".$dra['scannedphoto']);
							$photo_to_add = $directory."/".$dra['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg"))
						{
							
							$photo_file = "p_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg",$directory."/".$photo_file);
							$photo_to_add = $directory."/".$photo_file;
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photo_file." (".$dra['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
						}
						
						// Signature
						if(is_file("./uploads/iibfdra/".$dra['scannedsignaturephoto']))
						{
							$sign_file = $dra['scannedsignaturephoto'];
							copy("./uploads/iibfdra/".$dra['scannedsignaturephoto'],$directory."/".$dra['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$dra['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg"))
						{
							$sign_file = "s_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg",$directory."/".$sign_file);
							$sign_to_add = $directory."/".$sign_file;
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$sign_file." (".$dra['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Signature does not exist  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
						}
						
						//ID Proof
						if(is_file("./uploads/iibfdra/".$dra['idproofphoto']))
						{
							$idproof_file = $dra['idproofphoto'];
							copy("./uploads/iibfdra/".$dra['idproofphoto'],$directory."/".$dra['idproofphoto']);
							$proof_to_add = $directory."/".$dra['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg"))
						{
							$idproof_file = "pr_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg",$directory."/".$idproof_file);
							$proof_to_add = $directory."/".$idproof_file;
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idproof_file." (".$dra['regnumber'].")\n");	
							}
							else
								$idproof_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
						}
						
						// TC
						if($tc_file != '' && is_file("./uploads/iibfdra/".$dra['training_certificate']))
						{
							//$tc_file = $dra['training_certificate'];
							copy("./uploads/iibfdra/".$dra['training_certificate'],$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
							}
							else 
								$tcimg_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg"))
						{
							$tc_file = "tc_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg",$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$tc_file." (".$dra['regnumber'].")\n");	
							}
							else
								$tcimg_cnt++;
						}
						else
						{
							//fwrite($fp1, "**ERROR** - Training Certificate does not exist  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
						}
						
						// DC
						if($dc_file && is_file("./uploads/iibfdra/".$dra['quali_certificate']))
						{
							copy("./uploads/iibfdra/".$dra['quali_certificate'],$directory."/".$dc_file);
							$dc_to_add = $directory."/".$dc_file;
							$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
							$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
							if(!$dc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");	
							}
							else 
								$dcimg_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg"))
						{
							$dc_file = "dc_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg",$directory."/".$dc_file);
							$dc_to_add = $directory."/".$dc_file;
							$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
							$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
							if(!$dc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dc_file." (".$dra['regnumber'].")\n");	
							}
							else
								$dcimg_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Degree Certificate does not exist  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");
						}
						
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $tc_zip_flg || $dc_zip_flg)
						{
							$success['zip'] = "New DRA Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New DRA Candidate Images Zip";
						}
					}
					
					/*$photo 		= DRA_FILE_PATH.$photo_file;
					$signature 	= DRA_FILE_PATH.$sign_file;
					$idproofimg = DRA_FILE_PATH.$idproof_file;
					$tcimg 		= DRA_FILE_PATH.$tc_file;
					$dcimg 		= DRA_FILE_PATH.$dc_file;*/
					
					$photo 	= "";
					if($photo_file!=""){
						$photo 	= DRA_FILE_PATH.$photo_file;
					}
					
					$signature 	= "";
					if($sign_file!=""){
						$signature 	= DRA_FILE_PATH.$sign_file;
					}
					
					$idproofimg 	= "";
					if($idproof_file!=""){
						$idproofimg = DRA_FILE_PATH.$idproof_file;
					}
					
					$tcimg 	= "";
					if($tc_file!=""){
						$tcimg 	= DRA_FILE_PATH.$tc_file;
					}
					
					$dcimg 	= "";
					if($dc_file!=""){
						$dcimg 	= DRA_FILE_PATH.$dc_file;
					}
					
					$qualification = '';
					$specialisation = '';
					switch($dra['qualification'])
					{
						/*case "tenth"	: 	$qualification = 1;
											$specialisation = 7;
											break;
						case "twelth"	: 	$qualification = 1;
											$specialisation = 8;
											break;
						case "graduate"	: 	$qualification = 3;
											$specialisation = 2;
											break;*/
											
						/* Changes updated as per 'Exam_Application_QualificationCode' by Dattatreya sir on 09-01-2020 */					
						
						case "tenth"	: 	$qualification = 'UG';
											$specialisation = 7;
											break;
						case "twelth"	: 	$qualification = 'UG';
											$specialisation = 8;
											break;
						case "graduate"	: 	$qualification = 'G';
											$specialisation = 2;
											break;					
					}
					
					//801135663|NM|MR|ADITYA|NAGNATH|SHIDDHE|MR ADITYA NAGNATH SHIDDHE|862|SHUKRAWAR PETH|SOLAPUR|SOLAPUR|||413001|MAH|24-Mar-91|M|1|8||||||shreekantyadwad@yahoo.co.in|||9096439943|2||6647590215714|29-Sep-16|12075|6647590215714|Y|||/webonline/fromweb/images/dra/p_801135663.jpg|/webonline/fromweb/images/dra/s_801135663.jpg|/webonline/fromweb/images/dra/pr_801135663.jpg|/webonline/fromweb/images/dra/tc_801135663.jpg|/webonline/fromweb/images/dra/dc_801135663.jpg|||||
					
					//MEM_MEM_NO,MEM_MEM_TYP,MEM_TLE,MEM_NAM_1,MEM_NAM_2,MEM_NAM_3,ID_CARD_NAME,MEM_ADR_1,MEM_ADR_2,MEM_ADR_3,MEM_ADR_4,MEM_ADR_5,MEM_ADR_6,MEM_PIN_CD,MEM_STE_CD,MEM_DOB,MEM_SEX_CD,MEM_QLF_GRD,MEM_QLF_CD,MEM_INS_CD,BRANCH,MEM_DSG_CD,MEM_BNK_JON_DT ,FI_YEAR_ID,      EMAIL,STD_R,PHONE_R,MOBILE,ID_TYPE,ID_NO,BDRNO,TRN_DATE,TRN_AMT,INSTRUMENT_NO,AR_FLG,FILE_ID,ZONE_CD,filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,filename_2 filler char(400),SIGNATURE LOBFILE (filename_2) TERMINATED BY EOF,filename_3 filler char(400),ID LOBFILE (filename_3) TERMINATED BY EOF,filename_4 filler char(400),TRG_CERT LOBFILE (filename_4) TERMINATED BY EOF,filename_5 filler char(400),QLF_CERT LOBFILE (filename_5) TERMINATED BY EOF,USR_ID,LOT_NO,LOT_TYP,UPD_DT
					
					
					$displayname = $dra['namesub'].' '.$dra['firstname'].' '.$dra['middlename'].' '.$dra['lastname'];
					
					$dob = '';
					if($dra['dateofbirth'] != '0000-00-00')
					{
						$dob = date('d-M-y',strtotime($dra['dateofbirth']));
					}
					
					if(strlen($dra['stdcode']) > 10)
					{	$std_code = substr($dra['stdcode'],0,9);	}
					else
					{	$std_code = $dra['stdcode'];	}
					
					if(strlen(str_replace("\&quot;", "", $dra['address1'])) > 30)
					{	$address1 = substr(str_replace("\&quot;", "", $dra['address1']),0,29);	}
					else
					{	$address1 = str_replace("\&quot;", "", $dra['address1']);	} 
				
					if(strlen(str_replace("\&quot;", "", $dra['address2'])) > 30)
					{	$address2 = substr(str_replace("\&quot;", "", $dra['address2']),0,29);	}
					else
					{	$address2 = str_replace("\&quot;", "", $dra['address2']);	}
					
					if(strlen($dra['city']) > 30)
					{	$city = substr($dra['city'],0,29);	}
					else
					{	$city = $dra['city'];	}
					
					if(strlen($dra['district']) > 30)
					{	$district = substr($dra['district'],0,29);	}
					else
					{	$district = $dra['district'];	}
					
					$trans_date = '';
					$transaction_no = '';
					if($dra['gateway']==1)
					{	
						$transaction_no = $dra['UTR_no'];
						$trans_date = date('d-M-y',strtotime($dra['updated_date']));
					}
					else if($dra['gateway']==2)
					{	
						$transaction_no = $dra['transaction_no'];
						$trans_date = date('d-M-y',strtotime($dra['date']));	
					}
					
					$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$address1.'|'.$address2.'|'.$city.'|'.$district.'|||'.$dra['pincode'].'|'.$dra['state'].'|'.$dob.'|'.$gender.'|'.$qualification.'|'.$specialisation.'||||||'.$dra['email'].'|'.$std_code.'|'.$dra['phone'].'|'.$dra['mobile'].'|'.$dra['idproof'].'||'.$transaction_no.'|'.$trans_date.'|'.$dra['amount'].'|'.$transaction_no.'|Y|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$tcimg.'|'.$dcimg.'|||||'.$dra['aadhar_no']."\n";
					
					$i++;
					$mem_cnt++;
				}
				
				fwrite($fp1, "\n"."Total New DRA  Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Training Certificates Added = ".$tcimg_cnt."\n");
				fwrite($fp1, "\n"."Total Degree Certificates Added = ".$dcimg_cnt."\n");
				
				$file_w_flg = fwrite($fp, $data);
				if($file_w_flg)
				{
					$success['file'] = "New DRA Candidate Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating New DRA Candidate Details File.";
				}
				
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
			$this->log_model->cronlog("New DRA Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** New DRA Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* DRA Edited Members data Cron : added by sagar on 13-08-2021 */
	public function dra_member_edited()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$tc_zip_flg = 0;
		$dc_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");			
		$cron_file_dir = "./uploads/cronfiles_pg/";
				
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Edited Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dra_edited_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Edited Candidate Details Cron Start - ".$start_time." ***************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2021-08-12';
			
			$this->db->select("d.*");
			$this->db->where("DATE(d.editedby) = '".$yesterday."' AND d.isdeleted = 0 AND d.regnumber != '' AND d.regnumber != '0'");//AND d.new_reg = 1
			$new_dra_reg = $this->Master_model->getRecords('dra_members d');
			//echo $this->db->last_query(); exit;
			
			if(count($new_dra_reg))
			{
				$data = '';
				$dirname = "dra_edited_regd_image_".$current_date; 
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
				 	array_map('@unlink', glob($directory."/*.*"));
				 	rmdir($directory);				
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$tcimg_cnt = 0;
				$dcimg_cnt = 0;
				foreach($new_dra_reg as $dra)
				{
					$gender = '';
					if($dra['gender'] == 'male')	{ $gender = 'M';}
					else if($dra['gender'] == 'female')	{ $gender = 'F';}
					
					$dc_file = '';
					$tc_file = '';
					$photo_file = '';
					$sign_file = '';
					$idproof_file = '';
					if($dir_flg)
					{
						if($dra['quali_certificate'])
						{
							$file_arr = explode('.',$dra['quali_certificate']);
							$dc_file = "dc_".$dra['regnumber'].".".end($file_arr);
						}
						
						if($dra['training_certificate']) 
						{
							$file_arr1 = explode('.',$dra['training_certificate']);
							$tc_file = "tc_".$dra['regnumber'].".".end($file_arr1);
						}
						
						// Photograph
						if($dra['photo_flg'] == 'Y')
						{
							if($dra['scannedphoto'] != '' && is_file("./uploads/iibfdra/".$dra['scannedphoto']))
							{
								$photo_file = $dra['scannedphoto'];
								copy("./uploads/iibfdra/".$dra['scannedphoto'],$directory."/".$dra['scannedphoto']);
								$photo_to_add = $directory."/".$dra['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg"))
							{
								
								$photo_file = "p_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg",$directory."/".$photo_file);
								$photo_to_add = $directory."/".$photo_file;
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photo_file." (".$dra['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
							}
						}
						
						// Signature
						if($dra['signature_flg'] == 'Y') 
						{
							if(is_file("./uploads/iibfdra/".$dra['scannedsignaturephoto']))
							{
								$sign_file = $dra['scannedsignaturephoto'];
								copy("./uploads/iibfdra/".$dra['scannedsignaturephoto'],$directory."/".$dra['scannedsignaturephoto']);
								$sign_to_add = $directory."/".$dra['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg"))
							{
								$sign_file = "s_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg",$directory."/".$sign_file);
								$sign_to_add = $directory."/".$sign_file;
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$sign_file." (".$dra['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
							}
						}
						
						//ID Proof
						if($dra['id_flg'] == 'Y') 
						{
							if(is_file("./uploads/iibfdra/".$dra['idproofphoto']))
							{
								$idproof_file = $dra['idproofphoto'];
								copy("./uploads/iibfdra/".$dra['idproofphoto'],$directory."/".$dra['idproofphoto']);
								$proof_to_add = $directory."/".$dra['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg"))
							{
								$idproof_file = "pr_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg",$directory."/".$idproof_file);
								$proof_to_add = $directory."/".$idproof_file;
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idproof_file." (".$dra['regnumber'].")\n");	
								}
								else
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
							}
						}
							
						// TC						 
						/* if($tc_file != '' && is_file("./uploads/iibfdra/".$dra['training_certificate']))
						{
							//$tc_file = $dra['training_certificate'];
							copy("./uploads/iibfdra/".$dra['training_certificate'],$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
							}
							else 
								$tcimg_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg"))
						{
							$tc_file = "tc_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg",$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$tc_file." (".$dra['regnumber'].")\n");	
							}
							else
								$tcimg_cnt++;
						}
						else
						{
							//fwrite($fp1, "**ERROR** - Training Certificate does not exist  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
						} */
						
						// DC
						if($dra['qualicertificate_flg'] == 'Y')
						{
							if($dc_file && is_file("./uploads/iibfdra/".$dra['quali_certificate']))
							{
								copy("./uploads/iibfdra/".$dra['quali_certificate'],$directory."/".$dc_file);
								$dc_to_add = $directory."/".$dc_file;
								$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
								$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
								if(!$dc_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");	
								}
								else 
									$dcimg_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg"))
							{
								$dc_file = "dc_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg",$directory."/".$dc_file);
								$dc_to_add = $directory."/".$dc_file;
								$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
								$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
								if(!$dc_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dc_file." (".$dra['regnumber'].")\n");	
								}
								else
									$dcimg_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Degree Certificate does not exist  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");
							}
						}							
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $tc_zip_flg || $dc_zip_flg)
						{
							$success['zip'] = "DRA Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating DRA Edited Candidate Images Zip";
						}
					}
					
					$photo 	= "";
					if($photo_file!=""){ $photo 	= DRA_FILE_PATH.$photo_file; }
					
					$signature 	= "";
					if($sign_file!=""){ $signature 	= DRA_FILE_PATH.$sign_file; }
					
					$idproofimg 	= "";
					if($idproof_file!=""){ $idproofimg = DRA_FILE_PATH.$idproof_file; }
					
					$tcimg 	= "";
					if($tc_file!=""){ $tcimg 	= DRA_FILE_PATH.$tc_file; }
					
					$dcimg 	= "";
					if($dc_file!=""){ $dcimg 	= DRA_FILE_PATH.$dc_file; }
					
					$qualification = '';
					$specialisation = '';
					switch($dra['qualification'])
					{
						/* Changes updated as per 'Exam_Application_QualificationCode' by Dattatreya sir on 09-01-2020 */	
						case "tenth"	: 	$qualification = 'UG';
											$specialisation = 7;
											break;
						case "twelth"	: 	$qualification = 'UG';
											$specialisation = 8;
											break;
						case "graduate"	: 	$qualification = 'G';
											$specialisation = 2;
											break;					
					}
					
					$displayname = $dra['namesub'].' '.$dra['firstname'].' '.$dra['middlename'].' '.$dra['lastname'];
					
					$mem_dob = '';
					if($dra['dateofbirth'] != '0000-00-00') { $mem_dob = date('d-M-y',strtotime($dra['dateofbirth'])); }
					
					$mem_doj = '';
					
					if(strlen($dra['stdcode']) > 10) {	$std_code = substr($dra['stdcode'],0,9);		}
					else { $std_code = $dra['stdcode']; }
						
					if(strlen($dra['address1']) > 30) { $address1 = substr($dra['address1'],0,29); }
					else { $address1 = $dra['address1'];	}
					
					if(strlen($dra['address2']) > 30) {	$address2 = substr($dra['address2'],0,29); }
					else {	$address2 = $dra['address2'];	}
					
					if(strlen($dra['address3']) > 30) {	$address3 = substr($dra['address3'],0,29);		}
					else { $address3 = $dra['address3'];	}
					
					if(strlen($dra['address4']) > 30) {	$address4 = substr($dra['address4'],0,29);		}
					else { $address4 = $dra['address4'];	}
					
					if(strlen($dra['district']) > 30) {	$district = substr($dra['district'],0,29);		}
					else { $district = $dra['district'];	}
					
					if(strlen($dra['city']) > 30) {	$city = substr($dra['city'],0,29);		}
					else {	$city = $dra['city'];	}
					
					/* if($dra['editedby'] == '') { $edited_by = "Candidate"; }
					else { $edited_by = $dra['editedby']; } */
					$edited_by = "Candidate";
					
					$branch = '';
					$branch_name = '';
					if($branch == '') { $branch = $city; }
					
					if(strlen($branch) > 20) {	$branch_name = substr($branch,0,19);	}
					else {	$branch_name = $branch;	}
					
					$optnletter = "N";	
					
					$designation = $idNo = $bank_emp_id = '';
					
					//THESE ARE THE HEADER PROVIDED BY PALLAVI
					/* MEM_MEM_NO, MEM_MEM_TYP, MEM_TLE, MEM_NAM_1, MEM_NAM_2, MEM_NAM_3, ID_CARD_NAME, MEM_ADR_1, MEM_ADR_2, MEM_ADR_3, MEM_ADR_4, MEM_ADR_5, MEM_ADR_6, MEM_PIN_CD, MEM_STE_CD, MEM_DOB, MEM_SEX_CD, MEM_QLF_GRD, MEM_QLF_CD, MEM_INS_CD, BRANCH, MEM_DSG_CD, MEM_BNK_JON_DT, EMAIL, STD_R, PHONE_R, MOBILE, ID_TYPE, ID_NO, BDRNO, TRN_DATE, TRN_AMT, USR_ID, AR_FLG, PHOTO_FLG, SIGNATURE_FLG, ID_FLG, UPDATED_ON, AADHAR_NO, QLF_CERT_FLG, BANK_EMP_ID, PHOTO_PATH, ID_PATH, SIGNATURE_PATH, QLF_CERTIFICATE_PATH */ 
					
					$editedon = date('d-M-y H:i:s',strtotime($dra['editedby'])); //SWATI UPDATE EDITED DATA TIME IN editedby COLUMN
					
					$img_dir_path = '/fromweb/dra/images/'; 
					
					$scannedphoto_path = $idproofphoto_path = $scannedsignaturephoto_path = $dc_file_path = '';
					if($dra['scannedphoto'] != "" && $dra['photo_flg'] == 'Y') { $scannedphoto_path = $img_dir_path.$dra['scannedphoto']; }
					if($dra['idproofphoto'] != "" && $dra['id_flg'] == 'Y') { $idproofphoto_path = $img_dir_path.$dra['idproofphoto']; }
					if($dra['scannedsignaturephoto'] != "" && $dra['signature_flg'] == 'Y') { $scannedsignaturephoto_path = $img_dir_path.$dra['scannedsignaturephoto']; }
					if($dc_file != "" && $dra['qualicertificate_flg'] == 'Y') { $dc_file_path = $img_dir_path.$dc_file; }					
					
					$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$dra['pincode'].'|'.$dra['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$specialisation.'|'.$dra['inst_code'].'|'.$branch_name.'|'.$designation.'|'.$mem_doj.'|'.$dra['email'].'|'.$std_code.'|'.$dra['phone'].'|'.$dra['mobile'].'|'.$dra['idproof'].'|'.$idNo.'||||'.$edited_by.'|'.$optnletter.'|'.$dra['photo_flg'].'|'.$dra['signature_flg'].'|'.$dra['id_flg'].'|'.$editedon.'|'.$dra['aadhar_no'].'|'.$dra['qualicertificate_flg'].'|'.$bank_emp_id.'|'.$scannedphoto_path.'|'.$idproofphoto_path.'|'.$scannedsignaturephoto_path.'|'.$dc_file_path."\n";
					
					$i++;
					$mem_cnt++;
				}
				
				fwrite($fp1, "\n"."Total Edited DRA  Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Training Certificates Added = ".$tcimg_cnt."\n");
				fwrite($fp1, "\n"."Total Degree Certificates Added = ".$dcimg_cnt."\n");
				
				$file_w_flg = fwrite($fp, $data); 
				if($file_w_flg)
				{
					$success['file'] = "DRA Edited Candidate Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating DRA Edited Candidate Details File.";
				}
				
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
			$this->log_model->cronlog("DRA Edited Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** DRA Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/*  DRA Exam Data */
	public function dra_exam()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "dra_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			//$this->db->where("( DATE(date) = '".$yesterday."'  OR DATE(updated_date) = '".$yesterday."') AND status = 1");
			$this->db->where("DATE(updated_date) = '".$yesterday."' AND status = 1");
			$dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
			
			if(count($dra_payment))
			{
				
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				foreach($dra_payment as $payment)
				{
					$this->db->join('dra_member_exam b','a.memexamid = b.id','LEFT');
					$mem_exam_data = $this->Master_model->getRecords('dra_member_payment_transaction a',array('ptid'=>$payment['id']));
					if(count($mem_exam_data))
					{
						foreach($mem_exam_data as $exam)
						{
							$reg_num = '';
							$reg_type = '';
							$member = $this->Master_model->getRecords('dra_members',array('regid'=>$exam['regid']),'regnumber,registrationtype');
							if(count($member))
							{
								$reg_num = $member[0]['regnumber'];
								$reg_type = $member[0]['registrationtype'];
							}
							
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('dra_subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							$exam_mode = 'O';
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
							
							//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,	TRNG_INS_CD,trng_from,TRNG_TO,EXAM_DATE
							
							//print_r($exam);
							
							/*$trans_date = '';
							if($payment['created_date'] != '0000-00-00 00:00:00')
							{
								$trans_date = date('Y-m-d H:i:s',strtotime($payment['created_date']));
							}*/
							
							$trans_date = '';
							$transaction_no = '';
							if($payment['gateway']==1)
							{	
								$transaction_no = $payment['UTR_no'];
								$trans_date = date('Y-m-d H:i:s',strtotime($payment['updated_date']));
							}
							else if($payment['gateway']==2)
							{	
								$transaction_no = $payment['transaction_no'];
								$trans_date = date('Y-m-d H:i:s',strtotime($payment['created_date']));	
							}
							
							$training_from = '';
							if($exam['training_from'] != '0000-00-00')
							{
								$training_from = date('d-M-y',strtotime($exam['training_from']));
							}
							
							$training_to = '';
							if($exam['training_to'] != '0000-00-00')
							{
								$training_to = date('d-M-y',strtotime($exam['training_to']));
							}
							$bulk_flg = 'Y';
							$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$reg_num.'|'.$reg_type.'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$payment['amount'].'|'.$transaction_no.'|'.$payment['inst_code'].'|'.$training_from.'|'.$training_to.'|'.$bulk_flg.'|'.$exam['fee_paid_flag'].'|'.$exam['exam_date']."\n";
							
							$exam_cnt++;
						}
					}
					$i++;
				}
				
				fwrite($fp1, "Total DRA Exam Applications - ".$exam_cnt."\n");
				
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
					$success['DRA_exam'] = "DRA Candidate Exam Details File Generated Successfully."; 
				else
					$error['DRA_exam'] = "Error While Generating DRA Candidate Exam Details File.";
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
			$this->log_model->cronlog("DRA Candidate Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** DRA Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Duplicate Certificate Cron */
	public function dup_cert()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dup_cert_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Duplicate Certificate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dup_cert_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Duplicate Certificate Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-21';
			
			// get duplicate certificate registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$dup_cert_data = $this->Master_model->getRecords('duplicate_certificate c',array(' DATE(created_on)' => $yesterday,'pay_type' => 4,'pay_status' => 1,'status' => '1'),$select);
			
			if(count($dup_cert_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($dup_cert_data as $dup_cert)
				{										
//EXM_CD|EXM_NAME|MEM_NO|MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_ADR_1|MEM_ADR_2|MEM_ADR_3|MEM_ADR_4|MEM_ADR_5|MEM_ADR_6|MEM_STE_CD|MEM_PIN_CD|MEM_EMAIL|MEM_MOBILE_NO|FEE_AMT|TRN_NO|TRN_DATE|INSTRUMENT_NO|INSTRUMENT_TYPE|
					
					$trn_date = '';
					if($dup_cert['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($dup_cert['date']));
					}
					//coded added by chaitali on 2021-06-10
					$exam_code = $dup_cert['exam_code'];
					if($exam_code == '555')
					{
						$exam_code = 1;
					}
					$INSTRUMENT_TYPE = 'ONLINE';	// mode of application
													
					$data .= ''.$exam_code.'|'.$dup_cert['exam_name'].'|'.$dup_cert['regnumber'].'|'.$dup_cert['registrationtype'].'|'.$dup_cert['namesub'].'|'.$dup_cert['firstname'].'|'.$dup_cert['middlename'].'|'.$dup_cert['lastname'].'|'.$dup_cert['address1'].'|'.$dup_cert['address2'].'|'.$dup_cert['address3'].'|'.$dup_cert['address4'].'|'.$dup_cert['district'].'|'.$dup_cert['city'].'|'.$dup_cert['state'].'|'.$dup_cert['pincode'].'|'.$dup_cert['email'].'|'.$dup_cert['mobile'].'|'.$dup_cert['amount'].'|'.$dup_cert['transaction_no'].'|'.$trn_date.'|'.$dup_cert['transaction_no'].'|'.$INSTRUMENT_TYPE."\n";
		
					$i++;
					$mem_cnt++;
				}
				
				$dup_cert_flg = fwrite($fp, $data);
				if($dup_cert_flg)
						$success['dup_cert'] = "Duplicate Certificate Details File Generated Successfully. ";
				else
					$error['dup_cert'] = "Error While Generating Duplicate Certificate Details File.";
					
				fwrite($fp1, "\n"."Total Duplicate Certificate Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("Duplicate Certificate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Duplicate Certificate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Membership Renewal Cron */
	public function renewal()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$declaration_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Renewal Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "renewal_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Renewal Member Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-09-07';
			
			//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 1));
			
			if(count($new_mem_reg))
			{
				$dirname = "renewal_image_".$current_date;
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
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_RENEWAL_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_RENEWAL_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_RENEWAL_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}

					$declarationimg = '';
					if(is_file("./uploads/declaration/".$reg_data['declaration']))
					{
						$declarationimg = MEM_FILE_RENEWAL_PATH.$reg_data['declaration'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
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
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/									
										
					}
					
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')	// DB Member
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else	// Ordinary Member
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfren','status'=>1,'pay_type'=>5,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else	// Non Member
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declarationimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
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

						// For Declaration images
						if($declarationimg)
						{
							$image = "./uploads/declaration/".$reg_data['declaration'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['declaration']);
							$declaration_to_add = $directory."/".$reg_data['declaration'];
							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
							if(!$declaration_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$declaration_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "Renewal Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Renewal Member Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "Renewal Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating Renewal Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total Renewal Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."\n");
				
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
			$this->log_model->cronlog("Renewal Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Renewal Member Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Free Admit Card Cron */
	public function admit_card_free()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Free Exam Admit Card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "free_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Free Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			// get member exam application details for given date
			$select = 'a.id as mem_exam_id, a.examination_date';
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'free_paid_flg'=>'F','pay_status'=>1),$select);
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					
					// get admit card details for this member by mem_exam_id
					$this->db->where('remark', 1);
					$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id,'free_paid_flg'=>'F'));
					if(count($admit_card_details_arr))
					{
						foreach($admit_card_details_arr as $admit_card_data)
						{
							$data = '';
							$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
							$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
							
							$venue_name = '';
							if($admit_card_data['venue_name'] != ''){
								$venue_name = trim(str_replace(PHP_EOL, '', $admit_card_data['venue_name']));
								$venue_name = str_replace(array("\n", "\r"), '', $venue_name);
							}
							
							$venueadd1 = '';
							if($admit_card_data['venueadd1'] != ''){
								$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
								$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
							}
							
							$venueadd2 = '';
							if($admit_card_data['venueadd2'] != ''){
								$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
								$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
							}
							
							$venueadd3 = '';
							if($admit_card_data['venueadd3'] != ''){
								$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
								$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
							}
							
							// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
							$exam_period = '';
							$exam_period = $admit_card_data['exm_prd'];
							$exam_code = '';
							$exam_code = $admit_card_data['exm_cd'];
							$transaction_no = '0000000000000';
							
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME|TRANSACTION_NO|FREE_PAID_FLG(F/P)
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$transaction_no.'|'.$admit_card_data['free_paid_flg']."\n";
							
							$admit_card_sub_count++;
							$file_w_flg = fwrite($fp, $data);
						}
						
						if($file_w_flg){
							$success[] = "Free Exam Admit Card Details File Generated Successfully. ";
						}
						else{
							$error[] = "Error While Generating Free Exam Admit Card Details File.";
						}
					}
					$admit_card_count++;
				}
				fwrite($fp1, "\n"."Total Free Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Free Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Free Exam Admit Card Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Free Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	
	/* Admit Card Cron */
	public function admit_card()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Exam Admit Card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-04';
			
			// get member exam application details for given date
			$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					
					// get admit card details for this member by mem_exam_id
					$this->db->where('remark', 1);
					$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
					if(count($admit_card_details_arr))
					{
						foreach($admit_card_details_arr as $admit_card_data)
						{
							$data = '';
							
							$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
							
							$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
							
							$venue_name = '';
							if($admit_card_data['venue_name'] != '')
							{
								$venue_name = trim(str_replace(PHP_EOL, '', $admit_card_data['venue_name']));
								$venue_name = str_replace(array("\n", "\r"), '', $venue_name);
							}
							
							$venueadd1 = '';
							if($admit_card_data['venueadd1'] != '')
							{
								$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
								$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
							}
							
							$venueadd2 = '';
							if($admit_card_data['venueadd2'] != '')
							{
								$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
								$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
							}
							
							$venueadd3 = '';
							if($admit_card_data['venueadd3'] != '')
							{
								$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
								$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
							}
							
							// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $admit_card_data['exm_prd'];
							}
							
							
							
							$exam_code = '';
							if($admit_card_data['exm_cd'] == 340 || $admit_card_data['exm_cd'] == 3400 || $admit_card_data['exm_cd'] == 34000)
							{
								$exam_code = 34;
							}elseif($admit_card_data['exm_cd'] == 580 || $admit_card_data['exm_cd'] == 5800 || $admit_card_data['exm_cd'] == 58000){
								$exam_code = 58;
							}elseif($admit_card_data['exm_cd'] == 1600 || $admit_card_data['exm_cd'] == 16000){
								$exam_code = 160;
							}elseif($admit_card_data['exm_cd'] == 200){
								$exam_code = 20;
							}elseif($admit_card_data['exm_cd'] == 1770 || $admit_card_data['exm_cd'] == 17700){
								$exam_code =177;
							}elseif ($admit_card_data['exm_cd'] == 590){
								$exam_code = 59;
							}elseif ($admit_card_data['exm_cd'] == 810){
								$exam_code = 81;
							}elseif ($admit_card_data['exm_cd'] == 1750){
								$exam_code = 175;
							}else if($admit_card_data['exm_cd'] == 2027)
							{
								$exam_code = 1017;
							}
							else{
								$exam_code = $admit_card_data['exm_cd'];
							}
							
							
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$exam['transaction_no']."\n";
							
							$admit_card_sub_count++;
							
							$file_w_flg = fwrite($fp, $data);
						}
						
						if($file_w_flg)
						{
							$success[] = "Exam Admit Card Details File Generated Successfully. ";
						}
						else
						{
							$error[] = "Error While Generating Exam Admit Card Details File.";
						}
					}
					
					$admit_card_count++;
				}
				
				fwrite($fp1, "\n"."Total Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Exam Admit Card Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	
	// FBankQuest Registrations Cron */
	public function bankquest()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("BankQuest Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "bankquest_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** BankQuest Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			// get BankQuest registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.bv_id','LEFT');
			$bankquest_data = $this->Master_model->getRecords('bank_vision c',array(' DATE(created_on)' => $yesterday,'pay_type' => 6,'pay_status' => 1,'status' => '1'),$select);
			
			if(count($bankquest_data))
			{
				$data = '';
				$i = 1;
				$bankquest_reg_cnt = 0;
				foreach($bankquest_data as $bq_reg)
				{										
//NAME_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|SEX(M/F)|EMAIL|CONTACT_NO|ADDRESS1|ADDRESS2|ADDRESS3|ADDRESS4|CITY|STATE|PINCODE|SUBSCRIPTION_NO|SUBSCRIPTION_AMT|SUBSCRIPTION_FROM_DATE|SUBSCRIPTION_TO_DATE|TRANSACTION_NO|TRANSACTION_DATE|PRD_TYPE(BQ)|
					
					$subscription_from_date = '';
					if($bq_reg['subscription_from_date'] != '0000-00-00')
					{
						$subscription_from_date = date('d-M-Y',strtotime($bq_reg['subscription_from_date']));
					}
					
					$subscription_to_date = '';
					if($bq_reg['subscription_to_date'] != '0000-00-00')
					{
						$subscription_to_date = date('d-M-Y',strtotime($bq_reg['subscription_to_date']));
					}
					
					$trn_date = '';
					if($bq_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($bq_reg['date']));
					}
					
					$PRD_TYPE = 'BQ';	// BQ for BankQuest
					
					$data .= ''.$bq_reg['namesub'].'|'.$bq_reg['fname'].'|'.$bq_reg['mname'].'|'.$bq_reg['lname'].'|'.$bq_reg['gender'].'|'.$bq_reg['email_id'].'|'.$bq_reg['contact_no'].'|'.$bq_reg['address_1'].'|'.$bq_reg['address_2'].'|'.$bq_reg['address_3'].'|'.$bq_reg['address_4'].'|'.$bq_reg['city'].'|'.$bq_reg['state'].'|'.$bq_reg['pincode'].'|'.$bq_reg['subscription_no'].'|'.$bq_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$bq_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE.'|'.$bq_reg['state']."\n";
		
					$i++;
					$bankquest_reg_cnt++;
				}
				
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['bq_reg'] = "BankQuest Details File Generated Successfully. ";
				else
					$error['bq_reg'] = "Error While Generating BankQuest Details File.";
					
				fwrite($fp1, "\n"."Total BankQuest Applications = ".$bankquest_reg_cnt."\n");
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
			$this->log_model->cronlog("BankQuest Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** BankQuest Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Vision Registrations Cron */
	public function vision()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vision Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_vision_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Vision Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-31';
			
			// get Vision registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.vision_id','LEFT');
			$vision_data = $this->Master_model->getRecords('iibf_vision c',array(' DATE(created_on)' => $yesterday,'pay_type' => 7,'pay_status' => 1,'status' => '1'),$select);
			
			if(count($vision_data))
			{
				$data = '';
				$i = 1;
				$vision_reg_cnt = 0;
				foreach($vision_data as $vi_reg)
				{										
//NAME_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|SEX(M/F)|EMAIL|CONTACT_NO|ADDRESS1|ADDRESS2|ADDRESS3|ADDRESS4|CITY|STATE|PINCODE|SUBSCRIPTION_NO|SUBSCRIPTION_AMT|SUBSCRIPTION_FROM_DATE|SUBSCRIPTION_TO_DATE|TRANSACTION_NO|TRANSACTION_DATE|PRD_TYPE(VI)|
					
					$subscription_from_date = '';
					if($vi_reg['subscription_from_date'] != '0000-00-00')
					{
						$subscription_from_date = date('d-M-Y',strtotime($vi_reg['subscription_from_date']));
					}
					
					$subscription_to_date = '';
					if($vi_reg['subscription_to_date'] != '0000-00-00')
					{
						$subscription_to_date = date('d-M-Y',strtotime($vi_reg['subscription_to_date']));
					}
					
					$trn_date = '';
					if($vi_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($vi_reg['date']));
					}
					
					$PRD_TYPE = 'VIS';	// VI for Vision
					
					$data .= ''.$vi_reg['namesub'].'|'.$vi_reg['fname'].'|'.$vi_reg['mname'].'|'.$vi_reg['lname'].'|'.$vi_reg['gender'].'|'.$vi_reg['email_id'].'|'.$vi_reg['contact_no'].'|'.$vi_reg['address_1'].'|'.$vi_reg['address_2'].'|'.$vi_reg['address_3'].'|'.$vi_reg['address_4'].'|'.$vi_reg['city'].'|'.$vi_reg['state'].'|'.$vi_reg['pincode'].'|'.$vi_reg['subscription_no'].'|'.$vi_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$vi_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE.'|'.$vi_reg['state']."\n";
		
					$i++;
					$vision_reg_cnt++;
				}
				
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['vi_reg'] = "Vision Details File Generated Successfully. ";
				else
					$error['vi_reg'] = "Error While Generating Vision Details File.";
					
				fwrite($fp1, "\n"."Total Vision Applications = ".$vision_reg_cnt."\n");
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
			$this->log_model->cronlog("Vision Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Vision Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	// FinQuest Registrations Cron */
	public function finquest()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("FinQuest Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "finquest_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** FinQuest Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-31';
			
			// get FinQuest registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$finquest_data = $this->Master_model->getRecords('fin_quest c',array(' DATE(created_on)' => $yesterday,'pay_type' => 8,'pay_status' => 1,'status' => '1'),$select);
			
			if(count($finquest_data))
			{
				$data = '';
				$i = 1;
				$finquest_reg_cnt = 0;
				foreach($finquest_data as $fq_reg)
				{										
//MEM_NO|MEM_TYP|NAME_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|SEX(M/F)|EMAIL|CONTACT_NO|ADDRESS1|ADDRESS2|ADDRESS3|ADDRESS4|CITY|STATE|PINCODE|SUBSCRIPTION_NO|SUBSCRIPTION_AMT|SUBSCRIPTION_FROM_DATE|SUBSCRIPTION_TO_DATE|TRANSACTION_NO|TRANSACTION_DATE|PRD_TYPE(FQ)|
					
					$subscription_from_date = '';
					if($fq_reg['subscription_from_date'] != '0000-00-00')
					{
						$subscription_from_date = date('d-M-Y',strtotime($fq_reg['subscription_from_date']));
					}
					
					$subscription_to_date = '';
					if($fq_reg['subscription_to_date'] != '0000-00-00')
					{
						$subscription_to_date = date('d-M-Y',strtotime($fq_reg['subscription_to_date']));
					}
					
					$trn_date = '';
					if($fq_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($fq_reg['date']));
					}
					
					$PRD_TYPE = 'FQ';	// FQ for FinQuest
					$gender = '';
					if($fq_reg['gender'] == 'male')	{ $gender = 'M';}
					else if($fq_reg['gender'] == 'female')	{ $gender = 'F';}
					
					$data .= ''.$fq_reg['mem_no'].'|'.$fq_reg['registrationtype'].'|'.$fq_reg['namesub'].'|'.$fq_reg['fname'].'|'.$fq_reg['mname'].'|'.$fq_reg['lname'].'|'.$gender.'|'.$fq_reg['email_id'].'|'.$fq_reg['contact_no'].'|'.$fq_reg['address_1'].'|'.$fq_reg['address_2'].'|'.$fq_reg['address_3'].'|'.$fq_reg['address_4'].'|'.$fq_reg['city'].'|'.$fq_reg['state'].'|'.$fq_reg['pincode'].'|'.$fq_reg['subscription_no'].'|'.$fq_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$fq_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE."\n";
		
					$i++;
					$finquest_reg_cnt++;
				}
				
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['fq_reg'] = "FinQuest Details File Generated Successfully. ";
				else
					$error['fq_reg'] = "Error While Generating FinQuest Details File.";
					
				fwrite($fp1, "\n"."Total FinQuest Applications = ".$finquest_reg_cnt."\n");
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
			$this->log_model->cronlog("FinQuest Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** FinQuest Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	// CPD Registrations Cron
	public function cpd()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("CPD Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "cpd_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** CPD Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-17';
			
			// get CPD registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$cpd_data = $this->Master_model->getRecords('cpd_registration c',array(' DATE(created_on)' => $yesterday,'pay_type' => 9,'pay_status' => 1,'status' => '1'),$select);
			
			if(count($cpd_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($cpd_data as $cpd_reg)
				{										
//MEM_NO|MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_EMAIL|MEM_MOBILE_NO|MEM_ADR_1|MEM_ADR_2|MEM_ADR_3|MEM_ADR_4|MEM_ADR_5|MEM_ADR_6|MEM_STE_CD|MEM_PIN_CD|INST_CD|BRANCH_OFFICE|DESIGNATION|QUALIFICATION|SPECIFY_QUALIFICATION|EXPERIENCE|FEE_AMT|TRN_NO|TRN_DATE|
					
					$trn_date = '';
					if($cpd_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($cpd_reg['date']));
					}
					
					$data .= ''.$cpd_reg['member_no'].'|'.$cpd_reg['registrationtype'].'|'.$cpd_reg['namesub'].'|'.$cpd_reg['firstname'].'|'.$cpd_reg['middlename'].'|'.$cpd_reg['lastname'].'|'.$cpd_reg['email'].'|'.$cpd_reg['mobile'].'|'.$cpd_reg['address1'].'|'.$cpd_reg['address2'].'|'.$cpd_reg['address3'].'|'.$cpd_reg['address4'].'|'.$cpd_reg['district'].'|'.$cpd_reg['city'].'|'.$cpd_reg['state'].'|'.$cpd_reg['pincode'].'|'.$cpd_reg['associatedinstitute'].'|'.$cpd_reg['office'].'|'.$cpd_reg['designation'].'|'.$cpd_reg['qualification'].'|'.$cpd_reg['specified_qualification'].'|'.$cpd_reg['experience'].'|'.$cpd_reg['amount'].'|'.$cpd_reg['transaction_no'].'|'.$trn_date."\n";
		
					$i++;
					$mem_cnt++;
				}
				
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['cpd_reg'] = "CPD Details File Generated Successfully. ";
				else
					$error['cpd_reg'] = "Error While Generating CPD Details File.";
					
				fwrite($fp1, "\n"."Total CPD Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("CPD Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** CPD Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	// Blended Registrations Cron */
	public function blended()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$blended_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Blended Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "blended_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Blended Details Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-11-21';
			
			// get Blended registration details for given date
			$br_select = 'c.*';
			$br_blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1),$br_select);
			if(count($br_blended_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($br_blended_data as $br_blended_reg)
				{
					$blended_id = $br_blended_reg['blended_id'];
					
					$transaction_no = '';
					$trn_date = '';
					$fees = 0;
					$receipt_no = '';
					$pay_txn_id = '';
					$pay_type = '';
					
					$record_type_flag = 1;	// 1 => training_type = VC, attempt = 1, fees = 0 and 2 => training_type = PC
					
					// check if training type is Virtual, attempt is 1 and Fees is 0
					if($br_blended_reg['training_type'] == 'VC' && $br_blended_reg['attempt'] == 1 && $br_blended_reg['fee'] == 0)
					{
						$record_type_flag = 1;
						
						$select = 'c.*';
						$blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1, 'blended_id' => $blended_id),$select);
					}
					else if($br_blended_reg['training_type'] == 'PC' && $br_blended_reg['attempt'] == 0 && $br_blended_reg['fee'] == 0)
					{
						$record_type_flag = 1;
						
						$select = 'c.*';
						$blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1, 'blended_id' => $blended_id),$select);
					}
					else
					{
						$record_type_flag = 2;
						
						// get Blended registration details for given date
						$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.receipt_no,b.id AS pay_txn_id,b.pay_type';
						$this->db->join('payment_transaction b','b.ref_id=c.blended_id','LEFT');
						$blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_type' => 10,'pay_status' => 1,'status' => '1', 'blended_id' => $blended_id),$select);
					}
					
					if(count($blended_data))
					{
						foreach($blended_data as $blended_reg)
						{										
		//member_no|program_code|zone_code|center_code|center_name|batch_code|venue_name|start_date|end_date|namesub|firstname|middlename|lastname|address1|address2|address3|address4|district|city|state|pincode|dateofbirth|email|mobile|res_stdcode+residential_phone|stdcode+office_phone|qualification|qualification_name|designation|designation_name|associatedinstitute|associatedinstitute_name|specify_qualification|emergency_contact_no|blood_group|fee|pay_status|txn_date|invoice_no|tax_type|app_type|exempt|txn_no|pay_type|emergency_name|attempt|training_type|mem_gstin_no|
							
							// check if training type is Virtual, attempt is 1 and Fees is 0
							if($record_type_flag == 1)
							{
								$transaction_no = '';
								$txn_date = '';
								if($blended_reg['createdon'] != '0000-00-00')
								{
									$trn_date = date('d-M-Y',strtotime($blended_reg['createdon']));
								}
								$fees = $blended_reg['fee'];
								$receipt_no = '';
								$pay_txn_id = '';
								$pay_type = 10;
							}
							else
							{
								$transaction_no = $blended_reg['transaction_no'];
								$txn_date = '';
								if($blended_reg['date'] != '0000-00-00')
								{
									$trn_date = date('d-M-Y',strtotime($blended_reg['date']));
								}
								$fees = $blended_reg['amount'];
								$receipt_no = $blended_reg['receipt_no'];
								$pay_txn_id = $blended_reg['pay_txn_id'];
								$pay_type = $blended_reg['pay_type'];
							}
							
							$start_date = '';
							if($blended_reg['start_date'] != '0000-00-00')
							{
								$start_date = date('d-M-Y',strtotime($blended_reg['start_date']));
							}
							
							$end_date = '';
							if($blended_reg['end_date'] != '0000-00-00')
							{
								$end_date = date('d-M-Y',strtotime($blended_reg['end_date']));
							}
							
							$dateofbirth = '';
							if($blended_reg['dateofbirth'] != '0000-00-00')
							{
								$dateofbirth = date('d-M-Y',strtotime($blended_reg['dateofbirth']));
							}
							
							$qualification_name = '';
							if($blended_reg['qualification'] == 'U')
							{
								$qualification_name = 'Under Graduate';
							}
							elseif($blended_reg['qualification'] == 'G')
							{
								$qualification_name = 'Graduate';		
							}
							elseif($blended_reg['qualification'] == 'P')
							{
								$qualification_name = 'Post Graduate';	
							}
							
							$designation_name = '';
							
							$designation_code = $blended_reg['designation'];
							
							$designation_details = $this->Master_model->getRecords('designation_master',array('dcode'=>$designation_code),'dname');
							
							if(count($designation_details))
							{
								/*foreach($designation_details as $designation_data)
								{
									$designation_name = $designation_data['dname'];
								}*/
								
								$designation_name = $designation_details[0]['dname'];
							}
							
							$associatedinstitute_name = '';
							
							$associatedinstitute_code = $blended_reg['associatedinstitute'];
							
							$associatedinstitute_details = $this->Master_model->getRecords('institution_master',array('institude_id'=>$associatedinstitute_code),'name');
							
							if(count($associatedinstitute_details))
							{
								/*foreach($associatedinstitute_details as $associatedinstitute_data)
								{
									$associatedinstitute_name = $associatedinstitute_data['name'];
								}*/
								
								$associatedinstitute_name = $associatedinstitute_details[0]['name'];
							}
							
							$specified_qualification_name = '';
							
							$specified_qualification_code = $blended_reg['specify_qualification'];
							
							$specified_qualification_details = $this->Master_model->getRecords('qualification',array('qid'=>$specified_qualification_code),'name');
							
							if(count($specified_qualification_details))
							{
								/*foreach($specified_qualification_details as $specified_qualification_data)
								{
									$specified_qualification_name = $specified_qualification_data['name'];
								}*/
								
								$specified_qualification_name = $specified_qualification_details[0]['name'];
							}
							
							$pay_status = 'SUCCESS';
							
							// Invoice details
							$invoice_no = '';
							$tax_type = '';
							$app_type = '';
							$exempt = '';
							
							if($receipt_no != '' && $pay_txn_id != '')
							{
								//$receipt_no = $blended_reg['receipt_no'];
								//$pay_txn_id = $blended_reg['pay_txn_id'];
								
								// get invoice details for this blended course payment transaction by id and receipt_no
								$this->db->where('transaction_no !=','');
								$this->db->where('app_type','T');
								$this->db->where('receipt_no',$receipt_no);
								$blended_course_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
								
								if(count($blended_course_invoice_data))
								{
									foreach($blended_course_invoice_data as $blended_course_invoice)
									{
										$invoice_no = $blended_course_invoice['invoice_no'];
										$tax_type = $blended_course_invoice['tax_type'];
										$app_type = $blended_course_invoice['app_type'];
										$exempt = $blended_course_invoice['exempt'];
									}
								}
							}
							if(strlen($blended_reg['city']) > 20)
							{	$city = substr($blended_reg['city'],0,19);	}
							else
							{	$city = $blended_reg['city'];	}
							
							if(strlen($blended_reg['district']) > 20)
							{	$district = substr($blended_reg['district'],0,19);	}
							else
							{	$district = $blended_reg['district'];	}
							
							/*$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$blended_reg['zone_code'].'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$blended_reg['district'].'|'.$blended_reg['city'].'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].' '.$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].' '.$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$blended_reg['fee'].'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$blended_reg['transaction_no'].'|'.$blended_reg['pay_type'].'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type']."|\n";*/
							
							$zone_code = '';
							$zone_code = $blended_reg['zone_code'];
							if($zone_code == 'CO' || $zone_code == 'CZ'){
								$zone_code = 'WZ';
							}
							
							$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$zone_code.'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$district.'|'.$city.'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$fees.'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$transaction_no.'|'.$pay_type.'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type'].'|'.$blended_reg['gstin_no']."\n";
				
							$i++;
							$mem_cnt++;
						}
					}
				}
				
				$blended_flg = fwrite($fp, $data);
				if($blended_flg)
						$success['blended_reg'] = "Blended Details File Generated Successfully. ";
				else
					$error['blended_reg'] = "Error While Generating Blended Details File.";
					
				fwrite($fp1, "\n"."Total Blended Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("Blended Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Blended Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	public function get_calendar_input()
	{
		$calendar = array();
		$from_year = date('Y', strtotime("- 60 year"));
		$to_year = date('Y');
		
		for($y=$from_year;$y<=$to_year;$y++)
		{
			$calendar['year'][] = $y;
		}
		
		for($i=1;$i<13;$i++)
		{
			$calendar['month'][] = date('F',strtotime('01-'.$i.'-'.$to_year)); 
		}
		
		for($j=1;$j<32;$j++)
		{
			$calendar['date'][] = $j; 
		}
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
	
	/* Fuction to fetch DRA Inst./Accredited Institute registrations */
	public function dra_inst()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dra_inst_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		//$current_date = "20171107";	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Institute Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dra_inst_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Institute Cron Start - ".$start_time." ******************** \n");
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-01-30';
			
			// get DRA Institute registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$dra_inst_data = $this->Master_model->getRecords('dra_inst_registration c',array(' DATE(created_on)' => $yesterday,'pay_type' => 12,'c.status' => 1,'b.status' => '1'),$select);
			
			
			if(count($dra_inst_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($dra_inst_data as $dra_inst_reg)
				{										
//INST_NAME|ESTB_YEAR|MAIN_ADR_1|MAIN_ADR_2|MAIN_ADR_3|MAIN_ADR_4|MAIN_ADR_5|MAIN_ADR_6|MAIN_STE_CD|MAIN_PIN_CD|INST_PHONE_NO|INST_FAX_NO|INST_WEBSITE|INST_HEAD_NAME|INST_HEAD_CONTACT_NO|INST_HEAD_EMAIL|LOC_NAME|LOC_ADR_1|LOC_ADR_2|LOC_ADR_3|LOC_ADR_4|LOC_ADR_5|LOC_ADR_6|LOC_STE_CD|LOC_PIN_CD|OFFICE_NO|CONTACT_PERSON_NAME|CONTACT_PERSON_MOBILE|CONTACT_PERSON_EMAIL|INST_TYPE|DUE_DLGNC|TRN_DATE|FEE_AMT|TRN_NO|
					
					$trn_date = '';
					if($dra_inst_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($dra_inst_reg['date']));
					}
					
					$data .= ''.$dra_inst_reg['inst_name'].'|'.$dra_inst_reg['estb_year'].'|'.$dra_inst_reg['main_address1'].'|'.$dra_inst_reg['main_address2'].'|'.$dra_inst_reg['main_address3'].'|'.$dra_inst_reg['main_address4'].'|'.$dra_inst_reg['main_district'].'|'.$dra_inst_reg['main_city'].'|'.$dra_inst_reg['main_state'].'|'.$dra_inst_reg['main_pincode'].'|'.$dra_inst_reg['inst_phone'].'|'.$dra_inst_reg['inst_fax_no'].'|'.$dra_inst_reg['inst_website'].'|'.$dra_inst_reg['inst_head_name'].'|'.$dra_inst_reg['inst_head_contact_no'].'|'.$dra_inst_reg['inst_head_email'].'|'.$dra_inst_reg['location_name'].'|'.$dra_inst_reg['address1'].'|'.$dra_inst_reg['address2'].'|'.$dra_inst_reg['address3'].'|'.$dra_inst_reg['address4'].'|'.$dra_inst_reg['district'].'|'.$dra_inst_reg['city'].'|'.$dra_inst_reg['state'].'|'.$dra_inst_reg['pincode'].'|'.$dra_inst_reg['contact_person_name'].'|'.$dra_inst_reg['contact_person_mobile'].'|'.$dra_inst_reg['email_id'].'|'.$dra_inst_reg['email_id'].'|'.$dra_inst_reg['inst_type'].'|'.$dra_inst_reg['due_diligence'].'|'.$trn_date.'|'.$dra_inst_reg['amount'].'|'.$dra_inst_reg['transaction_no']."|\n";
		
		
		
					$i++;
					$mem_cnt++;
				}
				
				$dra_inst_flg = fwrite($fp, $data);
				if($dra_inst_flg)
						$success['dra_inst_reg'] = "DRA Institute Details File Generated Successfully. ";
				else
					$error['dra_inst_reg'] = "Error While Generating DRA Institute Details File.";
					
				fwrite($fp1, "\n"."Total DRA Institute Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("DRA Institute Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** DRA Institute Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Bulk Exam Application Cron */
	public function bulk_exam_bk()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bulk_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			//$yesterday = '2018-02-12';
			
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));
					
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
					if(count($mem_exam_data))
					{
						foreach($mem_exam_data as $exam)
						{
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							$exam_mode = 'O';
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
								 	 
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $exam['exam_period'];
							}
							
							$exam_code = '';
							if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000){
								$exam_code = 34;
							}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
								$exam_code = 58;
							}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
								$exam_code = 160;
							}elseif($exam['exam_code'] == 200){
								$exam_code = 20;
							}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
								$exam_code =177;
							}elseif ($exam['exam_code'] == 590){
								$exam_code = 59;
							}elseif ($exam['exam_code'] == 810){
								$exam_code = 81;
							}elseif ($exam['exam_code'] == 1750){
								$exam_code = 175;
							}elseif ($exam['exam_code'] == 1010 || $exam['exam_code'] == 10100 || $exam['exam_code'] == 101000){
								$exam_code = 101;
							}
							else{
								$exam_code = $exam['exam_code'];
							}
							
							
							$trans_date = '';
							$transaction_no = '';
							if($payment['gateway']==1){	
								$transaction_no = $payment['UTR_no'];
								$trans_date = date('d-M-Y',strtotime($payment['updated_date']));
							}
							else if($payment['gateway']==2){	
								$transaction_no = $payment['transaction_no'];
								$trans_date = date('d-M-Y',strtotime($payment['created_date']));	
							}
							
							if($exam['institute_id'] != 0){
								$bulk_flg = 'Y';
							} else { 
							   	$bulk_flg = 'N'; 
							}
							
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							
							//$exam['elected_sub_code']!=0
							if($exam_code == 60 || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
							{
								if($exam['state']){	$state = $exam['state']; }
								
								if($exam['pincode']){ $pincode = $exam['pincode']; }
								
								if(strlen($exam['city']) > 30) { 
									$city = substr($exam['city'],0,29);
								}
								else { 
									$city = $exam['city'];	
								}
								
								if($exam['editedon'] < "2016-12-29 00:00:00") {
									$branch = $exam['branch'];
								} 
								else if($exam['editedon'] >= "2016-12-29") 
								{
									if(is_numeric($exam['office']))
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $city;
									} else
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $exam['office'];
									}
								}
								
								if($branch == ''){
									$branch = $city;
								}
								
								if(strlen($branch) > 20)
								{	$branch_name = substr($branch,0,19); }
								else
								{	$branch_name = $branch;	}
		
								if($exam['place_of_work'])
									$place_of_work = $exam['place_of_work'];
								else
									$place_of_work =  $branch_name;
									
								if($exam['pin_code_place_of_work'])
									$pin_code_place_of_work = $exam['pin_code_place_of_work'];
								else
									$pin_code_place_of_work =  $pincode;
									
								if($exam['state_place_of_work'])
									$state_place_of_work = $exam['state_place_of_work'];
								else
									$state_place_of_work =  $state;
									
								$elected_sub_code = '';
								if($exam_code == 60)
								{	$elected_sub_code = $exam['elected_sub_code'];	}
								
								if(strlen($place_of_work) > 30)
								{	$place_of_work = substr($place_of_work,0,29);		}
								else
								{	$place_of_work = $place_of_work;	}
//EXM_CD|EXM_PRD|MEM_NO|MEM_TYP|MED_CD|CTR_CD|ONLINE_OFFLINE_YN|SYLLABUS_CODE|CURRENCY_CODE|EXM_PART|SUB_CD|PLACE_OF_WORK|POW_PINCD|POW_STE_CD|BDRNO|TRN_DATE|FEE_AMT|INSTRUMENT_NO|SCRIBE_FLG|INS_CD|DISCOUNT_PER|TDS_AMT|BULK_FLG(Y/N)|
												
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$exam_inv_data[0]['disc_rate'].'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg."\n";
							}
							else
							{
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$exam_inv_data[0]['disc_rate'].'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg."\n";
							}
							$exam_cnt++;
						}
					}
					$i++;
				}
				
				fwrite($fp1, "Total Bulk Exam Applications - ".$exam_cnt."\n");
				
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
					$success['Bulk_exam'] = "Bulk Candidate Exam Details File Generated Successfully."; 
				else
					$error['Bulk_exam'] = "Error While Generating Bulk Candidate Exam Details File.";
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
			$this->log_model->cronlog("Bulk Candidate Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Bulk Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	
	/* Bulk Exam Application Cron */
	public function bulk_exam()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bulk_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			//$yesterday = '2020-09-15';
			
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
			//$this->db->where("status = 1");
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));
					
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$this->db->join('admit_card_details c','a.memexamid = c.mem_exam_id','LEFT');
					$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
					//,'b.exam_period'=>'777'
					if(count($mem_exam_data))
					{
						foreach($mem_exam_data as $exam)
						{
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							$exam_mode = 'O';
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
								 	 
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $exam['exam_period'];
							}
							
							$exam_code = '';
							if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000){
								$exam_code = 34;
							}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
								$exam_code = 58;
							}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
								$exam_code = 160;
							}elseif($exam['exam_code'] == 200){
								$exam_code = 20;
							}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
								$exam_code =177;
							}elseif ($exam['exam_code'] == 590){
								$exam_code = 59;
							}elseif ($exam['exam_code'] == 810){
								$exam_code = 81;
							}elseif ($exam['exam_code'] == 1750){
								$exam_code = 175;
							}elseif ($exam['exam_code'] == 1010 || $exam['exam_code'] == 10100 || $exam['exam_code'] == 101000){
								$exam_code = 101;
							}elseif ($exam['exam_code'] == 2010){
								$exam_code = 1010;
							}
							else{
								$exam_code = $exam['exam_code'];
							}
							
							
							$trans_date = '';
							$transaction_no = '';
							if($payment['gateway']==1){	
								$transaction_no = $payment['UTR_no'];
								$trans_date = date('d-M-Y',strtotime($payment['updated_date']));
							}
							else if($payment['gateway']==2){	
								$transaction_no = $payment['transaction_no'];
								$trans_date = date('d-M-Y',strtotime($payment['created_date']));	
							}
							
							if($exam['institute_id'] != 0){
								$bulk_flg = 'Y';
							} else { 
							   	$bulk_flg = 'N'; 
							}
							$exam_date = '';
							if($exam['exam_date'] != '0000-00-00' && $exam['exam_date'] != '')
							{
								$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
							}
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							
							$original_base_fee=$calculate_discount=$taken_discount=0;
							
							$bulk_discount_flg = $exam['bulk_discount_flg'];
							$original_base_fee = $exam['original_base_fee'];
							$discount_percent = $exam['discount_percent'];
							$calculate_discount = $exam['calculate_discount'];
							$taken_discount = $exam['taken_discount'];
							$elearning_flag = $exam['elearning_flag'];
							$base_fee = $exam['base_fee'];
							$disc_rate = 0.00;
							
							//$exam['elected_sub_code']!=0
							if($exam_code == 60 || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
							{
								if($exam['state']){	$state = $exam['state']; }
								
								if($exam['pincode']){ $pincode = $exam['pincode']; }
								
								if(strlen($exam['city']) > 30) { 
									$city = substr($exam['city'],0,29);
								}
								else { 
									$city = $exam['city'];	
								}
								
								if($exam['editedon'] < "2016-12-29 00:00:00") {
									$branch = $exam['branch'];
								} 
								else if($exam['editedon'] >= "2016-12-29") 
								{
									if(is_numeric($exam['office']))
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $city;
									} else
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $exam['office'];
									}
								}
								
								if($branch == ''){
									$branch = $city;
								}
								
								if(strlen($branch) > 20)
								{	$branch_name = substr($branch,0,19); }
								else
								{	$branch_name = $branch;	}
		
								if($exam['place_of_work'])
									$place_of_work = $exam['place_of_work'];
								else
									$place_of_work =  $branch_name;
									
								if($exam['pin_code_place_of_work'])
									$pin_code_place_of_work = $exam['pin_code_place_of_work'];
								else
									$pin_code_place_of_work =  $pincode;
									
								if($exam['state_place_of_work'])
									$state_place_of_work = $exam['state_place_of_work'];
								else
									$state_place_of_work =  $state;
									
								$elected_sub_code = '';
								if($exam_code == 60)
								{	$elected_sub_code = $exam['elected_sub_code'];	}
								
								if(strlen($place_of_work) > 30)
								{	$place_of_work = substr($place_of_work,0,29);		}
								else
								{	$place_of_work = $place_of_work;	}
//EXM_CD|EXM_PRD|MEM_NO|MEM_TYP|MED_CD|CTR_CD|ONLINE_OFFLINE_YN|SYLLABUS_CODE|CURRENCY_CODE|EXM_PART|SUB_CD|PLACE_OF_WORK|POW_PINCD|POW_STE_CD|BDRNO|TRN_DATE|FEE_AMT|INSTRUMENT_NO|SCRIBE_FLG|INS_CD|DISCOUNT_PER|TDS_AMT|BULK_FLG(Y/N)|BULK_DISCOUNT_FLG(Y/N)|ORIGINAL_BASE_FEE|DISCOUNT_PERCENT|CALCULATE_DISCOUNT|TAKEN_DISCOUNT| ELEARNING_FLAG(Y/N)
		
		
		// $exam_inv_data[0]['disc_rate'] 
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$disc_rate.'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg.'|'.$bulk_discount_flg.'|'.$original_base_fee.'|'.$discount_percent.'|'.$calculate_discount.'|'.$taken_discount.'|'.$elearning_flag.'|'.$base_fee.'|'.$exam_date."\n";
							}
							else
							{
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$disc_rate.'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg.'|'.$bulk_discount_flg.'|'.$original_base_fee.'|'.$discount_percent.'|'.$calculate_discount.'|'.$taken_discount.'|'.$elearning_flag.'|'.$base_fee.'|'.$exam_date."\n";
							}
							$exam_cnt++;
						}
					}
					$i++;
				}
				
				fwrite($fp1, "Total Bulk Exam Applications - ".$exam_cnt."\n");
				
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
					$success['Bulk_exam'] = "Bulk Candidate Exam Details File Generated Successfully."; 
				else
					$error['Bulk_exam'] = "Error While Generating Bulk Candidate Exam Details File.";
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
			$this->log_model->cronlog("Bulk Candidate Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Bulk Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* DRA Admit Card Cron - 25-09-2020 */
	public function dra_admit_card()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();	
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Exam Admit Card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "dra_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2020-09-23';
			$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
			$dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
			
			if(count($dra_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
							
				foreach($dra_payment as $payment)
				{
					$this->db->join('dra_member_exam b','a.memexamid = b.id','LEFT');
					$cand_exam_data = $this->Master_model->getRecords('dra_member_payment_transaction a',array('ptid'=>$payment['id']));
					if(count($cand_exam_data))
					{
						foreach($cand_exam_data as $exam)
						{
							$mem_exam_id = $exam['memexamid'];
							$this->db->where('remark', 1);
							$admit_card_details_arr=$this->Master_model->getRecords('dra_admit_card_details',array('mem_exam_id' => $mem_exam_id));
							
							if(count($admit_card_details_arr))
							{
								foreach($admit_card_details_arr as $admit_card_data)
								{
									$data = '';
									
									$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
									//$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
									$trn_date = date('d-M-y',strtotime($payment['updated_date']));
									
									$venueadd1 = '';
									if($admit_card_data['venueadd1'] != ''){
										$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
										$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
									}
									
									$venueadd2 = '';
									if($admit_card_data['venueadd2'] != ''){
										$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
										$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
									}
									
									$venueadd3 = '';
									if($admit_card_data['venueadd3'] != ''){
										$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
										$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
									}
									
									$exam_period = $admit_card_data['exm_prd'];
									$exam_code = $admit_card_data['exm_cd'];
									
									//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|
									
									$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$admit_card_data['free_paid_flg']."\n";
									
									$admit_card_sub_count++;
									
									$file_w_flg = fwrite($fp, $data);
								}
								
								if($file_w_flg)
								{
									$success[] = "Dra Exam Admit Card Details File Generated Successfully. ";
								}
								else
								{
									$error[] = "Error While Generating Dra Exam Admit Card Details File.";
								}
							}
							
							$admit_card_count++;
						}
					}
				}
				
				fwrite($fp1, "\n"."Total Dra Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Dra Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Dra Exam Admit Card Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Dra Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	
	}
	
	/* Bulk Exam Admit Card */
	public function bulk_admit_card()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Exam Admit Card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "bulk_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-02-12';
			//DATE(date) = '".$yesterday."' OR
			$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
							
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					/*$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));*/
					
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$cand_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
						if(count($cand_exam_data))
						{
							foreach($cand_exam_data as $exam)
							{
								$mem_exam_id = $exam['memexamid'];
								
								// get admit card details for this member by mem_exam_id
								$this->db->where('remark', 1);
								$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
								
								if(count($admit_card_details_arr))
								{
									foreach($admit_card_details_arr as $admit_card_data)
									{
										$data = '';
										
										$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
										
										//$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
										
										$trn_date = date('d-M-y',strtotime($payment['updated_date']));
										
										$venueadd1 = '';
										if($admit_card_data['venueadd1'] != '')
										{
											$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
											$venueadd1 = str_replace(array("\n", "\r", "|"), '', $venueadd1);
										}
										
										$venueadd2 = '';
										if($admit_card_data['venueadd2'] != '')
										{
											$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
											$venueadd2 = str_replace(array("\n", "\r", "|"), '', $venueadd2);
										}
										
										$venueadd3 = '';
										if($admit_card_data['venueadd3'] != '')
										{
											$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
											$venueadd3 = str_replace(array("\n", "\r", "|"), '', $venueadd3);
										}
										
										// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
										$exam_period = '';
										if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
										{
											$ex_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
											if(count($ex_period))
											{
												$exam_period = $ex_period[0]['period'];	
											}
										}
										else
										{
											$exam_period = $admit_card_data['exm_prd'];
										}
										
										/* Start Dyanamic Exam code get from database table : Bhushan 25 March 2019 */
										$exam_code = '';
										if($admit_card_data['exm_cd'] != '' && $admit_card_data['exm_cd'] != 0)
										{
											$ex_code = $this->master_model->getRecords('bulk_exam_activation_master',array('exam_code'=>$admit_card_data['exm_cd'])); // Added on 25 March 2019 - Bhushan (As per pooja changes for bulk)
											
											if(count($ex_code))
											{
												if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
												{	$exam_code = $ex_code[0]['original_val'];	}
												else
												{	$exam_code = $admit_card_data['exm_cd'];	}
											}
										}else{
											$exam_code = $admit_card_data['exm_cd'];
										}
										/* End Dyanamic Exam code get from database table : Bhushan 25 March 2019 */
	
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|
										
										$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."\n";
										
										$admit_card_sub_count++;
										
										$file_w_flg = fwrite($fp, $data);
									}
									
									if($file_w_flg)
									{
										$success[] = "Bulk Exam Admit Card Details File Generated Successfully. ";
									}
									else
									{
										$error[] = "Error While Generating Bulk Exam Admit Card Details File.";
									}
								}
								
								$admit_card_count++;
							}
						}
				}
				
				fwrite($fp1, "\n"."Total Bulk Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Bulk Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Bulk Exam Admit Card Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Bulk Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	
	}
	
	/* Blended Member Feedback */
	public function blended_feedback_member()
	{
		ini_set("memory_limit", "-1");
		//error_reporting(E_ALL);
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$blended_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Blended Feedback Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "blended_member_feedback_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Blended Member Feedback Details Cron Start - ".$start_time." ******************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-08-03';
			
			$br_select = 'c.*';
			$br_blended_data = $this->Master_model->getRecords('blended_feedback_infrastructure c',array(' DATE(creation_date)' => $yesterday,'c.feedback_type' => 'I'),$br_select);
			
			if(count($br_blended_data)){
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				$res = array();
				foreach($br_blended_data as $br_blended_reg){
					$batch_code = $br_blended_reg['batch_code'];
					$mem_details = array();
					$mem_details['member_no'] = $br_blended_reg['member_no'];
					$mem_details['mem_name'] = $br_blended_reg['mem_name'];
					$mem_details['batch_code'] = $batch_code;
					$mem_details['program_code'] = $br_blended_reg['program_code'];
					
					$this->db->order_by('topic_code','ASC');
					$blended_data = $this->Master_model->getRecords('infrastructure_feedback_question',array('isactive' => 1,'feedback_type' => 'I'));
					
					if(count($blended_data)){
						$q_details = array();
						foreach($blended_data as $key => $blended_reg){										
							$q_no = $key+1;
							$q_details[$key]['qes'] = $blended_reg['question'];
							if($blended_reg['option1'] != ''){
								$q_details[$key]['ans'] = $br_blended_reg['q'.$q_no.'_ans'];
							}
							else{
								$q_details[$key]['ans'] = $br_blended_reg['comment'];	
							}
							$i++;
							$mem_cnt++;
						}
						$mem_details['feedback_data'] = $q_details;	
					}
					$res[] = $mem_details;
				}
				
				$program_code = $batch_code = $mem_name = $member_no = $feedback_data = $member_name_no = '';
				foreach($res as $key1 => $details){	
					$program_code = $details['program_code'];
					$batch_code = $details['batch_code'];
					$mem_name = $details['mem_name'];
					$member_no = $details['member_no'];
					$feedback_data = $details['feedback_data'];
					$member_name_no = $mem_name."-".$member_no;
					$flag = 0;
					$b = 1;
					foreach($feedback_data as $key2 => $rows){
						$review_no = '';
						$review_arr = array('Excellent'	=> 5,'Very Good' => 4,'Good' => 3,'Fair' => 2,'Poor' => 1);
						foreach($review_arr as $k => $val){
							if($rows['ans'] == $k) { 
								$review_no = $val; 
							}
							else if($rows['qes'] == 'Any other comments :'){
								$review_no = $rows['ans']; 
								$flag = 1;
							}
						}
						
						$review_no = preg_replace("/\s+/", " ", $review_no);
						
						if($flag == 0){
							$data .= ''.$program_code.'|'.$batch_code.'|'.$member_name_no.'|'.'0'.$b.'.'.$rows['qes'].'||'.$review_no."\n";
						}
						else{
							$data .= ''.$program_code.'|'.$batch_code.'|'.$member_name_no.'|'.'0'.$b.'.'.$rows['qes'].'|'.$review_no."|5\n";
						}
						$b++;
					}
				}
				$blended_flg = fwrite($fp, $data);
				if($blended_flg)
						$success['blended_reg'] = "Blended MemberFeedback Details File Generated Successfully. ";
				else
					$error['blended_reg'] = "Error While Generating Blended Member Feedback Details File.";
					
				fwrite($fp1, "\n"."Total Blended Member Feedbacks = ".$mem_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Blended Member Feedback Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Blended Member Feedback Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
 	/* Blended Faculty Feedback */
	public function blended_feedback_faculty()
	{
		ini_set("memory_limit", "-1");
		//error_reporting(E_ALL);
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$blended_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Blended Feedback Faculty Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "blended_faculty_feedback_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Blended Faculty Feedback Details Cron Start - ".$start_time." ******************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-08-03';
			
			$br_select = 'c.*';
			$br_blended_data = $this->Master_model->getRecords('blended_feedback_Faculty c',array(' DATE(creation_date)' => $yesterday,'c.feedback_type' => 'F'),$br_select);
			
			if(count($br_blended_data)){
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				$res = array();
				
				foreach($br_blended_data as $br_blended_reg){
					$batch_code = $br_blended_reg['batch_code'];
					$mem_details = array();
					$mem_details['batch_code'] = $batch_code;
					$mem_details['program_code'] = $br_blended_reg['program_code'];
					
					$this->db->order_by('f_id','ASC');
					$blended_data = $this->Master_model->getRecords('faculty_feedback_question',array('isactive' => 1,'feedback_type' => 'F','batch_code' => $batch_code));
					
					if(count($blended_data)){
						$q_details = array();
						foreach($blended_data as $key => $blended_reg){										
							$q_no = $key+1;
							$q_details[$key]['qes'] = $blended_reg['question'];
							$q_details[$key]['session_code'] = $blended_reg['session_code'];
							$q_details[$key]['batch_date'] = $blended_reg['batch_date'];
							$q_details[$key]['facilitator_code'] = $blended_reg['facilitator_code'];
							$q_details[$key]['topic_code'] = $blended_reg['topic_code'];
							
							if($blended_reg['option1'] != ''){
								$q_details[$key]['ans'] = $br_blended_reg['q'.$q_no.'_ans'];
							}
							else{
								$q_details[$key]['ans'] = $br_blended_reg['comment'];	
							}
							$i++;
							$mem_cnt++;
						}
						$mem_details['feedback_data'] = $q_details;	
					}
					$res[] = $mem_details;
				}
				$program_code = $batch_code = $feedback_data = '';
				foreach($res as $key1 => $details){	
					$program_code = $details['program_code'];
					$batch_code = $details['batch_code'];
					$feedback_data = $details['feedback_data'];
					
					foreach($feedback_data as $key2 => $rows){
						$review_no = '';
						$review_arr = array('Excellent'	=> 'E','Very Good' => 'V','Good' => 'G','Fair' => 'F','Poor' => 'P');
						foreach($review_arr as $k => $val){
							if($rows['ans'] == $k) { 
								$review_no = $val; 
							}
							else if($rows['qes'] == 'Any other comments :'){
								$review_no = $rows['ans']; 
								
							}
						}
						
						$review_no = preg_replace("/\s+/", " ", $review_no);
						
						$data .= ''.$program_code.'|'.$batch_code.'|'.$rows['session_code'].'|'.$rows['batch_date'].'|'.$rows['facilitator_code'].'|'.$rows['topic_code'].'|'.$review_no."\n";
					}
				}
				$blended_flg = fwrite($fp, $data);
				if($blended_flg)
						$success['blended_reg'] = "Blended Faculty Feedback Details File Generated Successfully. ";
				else
					$error['blended_reg'] = "Error While Generating Blended Faculty Feedback Details File.";
					
				fwrite($fp1, "\n"."Total Blended Faculty Feedbacks = ".$mem_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Blended Faculty Feedback Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Blended Faculty Feedback Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Digital Elearning Reg. Cron : Bhushan 12/04/2019 */
	public function digital_elearning()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = $parent_dir_flg = $exam_file_flg = 0;
		$error = $success = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		
		$this->log_model->cronlog("Digital Elearning Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "digital_elearning_exam_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n***** Digital Elearning Exam Details Cron Start - ".$start_time." ***** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.scribe_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			
			if(count($can_exam_data)){
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam){
					
					$data = $exam_mode = $trans_date = $exam_period = $exam_code = '';
					
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					if($exam['date'] != '0000-00-00'){
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					$exam_period = $exam['exam_period'];	
					$exam_code = $exam['exam_code'];	
					
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."\n";
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Digital Elearning Exam Details Cron File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Digital Elearning Exam Details Cron File.";
					
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Digital Elearning Exam Applications - ".$exam_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			$end_time = date("Y-m-d H:i:s");
			$result = array("success"=>$success,"error"=>$error,"Start Time" =>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Digital Elearning Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."***** Digital Elearning Exam Details Cron End ".$end_time." *****"."\n");
			fclose($fp1);
		}
	}
	
	/* Only for CSC Vendor */
	public function csc_member_old()
	{
	    ini_set("memory_limit", "-1");
	    
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		
		//$current_date = '20190603';
		
		$cron_file_dir = "./uploads/cronfiles/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("CSC Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_csc_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_CSC_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			
			fwrite($fp1, "IIBF CSC New Member Details Cron Start - ".$start_time."\n");
			
			
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			
			$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(date)'=>$yesterday,'pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'));
			if(count($new_mem_reg)){
				
				$dirname = "csc_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					$trans_details = $this->Master_model->getRecords('payment_transaction a',array(' DATE(date)'=>$yesterday,'status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');	
					
					if(count($trans_details)){
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00'){
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					if(strlen($reg_data['stdcode']) > 10){	$std_code = substr($reg_data['stdcode'],0,9);	}
					else{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20){	$branch = substr($reg_data['office'],0,19);	}
					else{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30){	$address1 = substr($reg_data['address1'],0,29);	}
					else{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30){	$address2 = substr($reg_data['address2'],0,29);	}
					else{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30){	$address3 = substr($reg_data['address3'],0,29);	}
					else{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30){	$address4 = substr($reg_data['address4'],0,29);	}
					else{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30){	$district = substr($reg_data['district'],0,29);	}
					else{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30){	$city = substr($reg_data['city'],0,29);	}
					else{	$city = $reg_data['city'];	}
					
					//Member Number | Registration Type(O/NM) | Name Prefix | Firstname | Middlename | Lastname | Address1 | Address2 | Address3 | Address4 | District | City | Pincode | State | DOB | Gender| Designation | Email | Mobile  | Transaction Number | Transaction Date | Transaction Amount | photo | signature | idproofimg
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$mem_dob.'|'.$gender.'|'.$reg_data['designation'].'|'.$reg_data['email'].'|'.$reg_data['mobile'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$photo.'|'.$signature.'|'.$idproofimg."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
								
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "CSC Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating CSC Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "CSC Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating CSC Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("CSC Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."************ CSC Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	
	/* Exam Training Application Cron*/
	public function exam_training()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = $parent_dir_flg = $exam_file_flg = 0;
		$success = $error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Exam Training Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_training_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam Training Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,DATE_FORMAT(a.created_on,"%Y-%m-%d") created_on,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag';
			$this->db->where_in('a.exam_code', array('191','1910','19100')); 
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'a.pay_status'=>1),$select);
			
			if(count($can_exam_data)){
				$i = 1;
				$exam_cnt = 0;
				
				foreach($can_exam_data as $exam){
					
					$data = $syllabus_code = $part_no = $trans_date = '';
					
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					
					if(count($subject_data)>0){
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					if($exam['created_on'] != '0000-00-00'){
						$trans_date = date('d-M-Y',strtotime($exam['created_on']));
					}
					
					$exam_code = '';
					
					$exam_code = $exam['exam_code'];
					$exam_period = $exam['exam_period'];
					$scribe_flag = $exam['scribe_flag'];
					$transaction_no = '000000000000';
						
					if($exam_code == 1910 || $exam_code == 19100){
					 	$exam_code = 191;
					}
					else{
						$exam_code = 191;
					}
									//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$transaction_no.'|'.$scribe_flag."\n";
					
					$exam_file_flg = fwrite($fp, $data);
					
					if($exam_file_flg)
							$success['cand_exam'] = "Exam Training Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Exam Training Details File.";
					
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "\nTotal Exam Training Applications - ".$exam_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Exam Training Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Exam Training Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	
	
	
  public function NSEIT_member() 
  {
    ini_set("memory_limit", "-1");
    
    $dir_flg = 0;
    $parent_dir_flg = 0;
    $file_w_flg = 0;
    $photo_zip_flg = 0;
    $sign_zip_flg = 0;
    $idproof_zip_flg = 0;
    $success = array();
    $error = array();
    $start_time = date("Y-m-d H:i:s");
    $current_date = date("Ymd");
    
    $cron_file_dir = "./uploads/cronfiles/";
    
    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    
    $this->log_model->cronlog("Vendor NSEIT Member Details Cron Execution Start", $desc);
    
    if(!file_exists($cron_file_dir.$current_date))
    {
      $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
    }
    if(file_exists($cron_file_dir.$current_date))
    {
      $cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
      
      $file = "iibf_NSEIT_mem_details_".$current_date.".txt";
      $fp = fopen($cron_file_path.'/'.$file, 'w');
      
      $file1 = "logs_NSEIT_".$current_date.".txt";
      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$start_time." *************"."\n");
      
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
      
      //$yesterday = '2020-05-31';
      //$members = array('510101898','510415515');
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
      $exam_code_arry = array('1002','1010','1011','1012','1013','1014','1019','1020','2027');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      $this->db->where_in('a.exam_code',$exam_code_arry);
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1,
			'DATE(a.created_on)' => $yesterday
      ), $select);
      //echo "<br> Qry : ".$this->db->last_query();
      
      if(count($new_mem_reg))
      {
        $dirname = "NSEIT_regd_image_".$current_date;
        $directory = $cron_file_path.'/'.$dirname;
        if(file_exists($directory)){
          array_map('unlink', glob($directory."/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else{
          $dir_flg = mkdir($directory, 0700);
        }
        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory.'.zip', ZipArchive::CREATE);
        
        $i = 1;
        $mem_cnt = 0;
        $photo_cnt = 0;
        $sign_cnt = 0;
        $idproof_cnt = 0;
        
        foreach($new_mem_reg as $reg_data)
        {
          $data = '';					
          $image_path = $reg_data['image_path'];
          
          $photo = '';
          if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
          {
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg"))
          {
            $phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
            $scannedphoto = "p_".$reg_data['regnumber'].".jpg";
            
            $photo 	= MEM_FILE_PATH.$scannedphoto;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['reg_no'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['regnumber'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
          }
          else
          {
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$photo = '';
            
            if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
            }
            else{
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $signature = '';
          if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
          {
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg"))
          {
            $signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
            $scannedsignature = "s_".$reg_data['regnumber'].".jpg";
            $signature 	= MEM_FILE_PATH.$scannedsignature;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['reg_no'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['regnumber'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }
          /*$signature = '';
            if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $idproofimg = '';
          
          if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
          {
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg"))
          {	
            $idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
            $scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
            
            $idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['reg_no'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['regnumber'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$idproofimg = '';
            if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          // Member Number | Name Prefix | Firstname | Middlename | Lastname
          
          /***** ADDED BY SAGAR ON 15-07-2020 *******/
          $include_zip_flag = 0;
          if($reg_data['exam_code'] == '1002')
          {
            if($reg_data['exam_date'] != '2020-08-02' && $reg_data['exam_date'] != '2020-08-08' && $reg_data['exam_date'] != '2020-08-09') 
            { 
              $include_zip_flag = 1;
            }
          }
          else if($reg_data['exam_code'] != '1002')
          {
            $include_zip_flag = 1;
          } 
          
          if($include_zip_flag == 1)
          {
            $data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
          } 
          
          if($dir_flg)
          {
            // For photo images
            if($photo)
            {
              $max_width = "200";
              $max_height = "200";
              
              if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              elseif($image_path != '')
              {
                $image = $phtofile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedphoto);
                $photo_to_add = $directory."/".$scannedphoto;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['reg_no'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['regnumber'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              
              $new_photo = str_replace("k_","",substr($photo_to_add,strrpos($photo_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
                if(!$photo_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
                } 
                else 
                { 
                	$photo_cnt++; 
                }
              }  						
            }
            
            // For signature images
            if($signature){
              
              $max_width = "140";
              $max_height = "100";
              if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              elseif($image_path != '')
              {
                $image = $signaturefile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedsignature);
                $sign_to_add = $directory."/".$scannedsignature;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['reg_no'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['regnumber'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              
              $new_sign = str_replace("k_","",substr($sign_to_add,strrpos($sign_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
                if(!$sign_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                {
                	$sign_cnt++;
                }
              }              
            }
            
            // For ID proof images
            if($idproofimg)
            {
              $max_width = "800";
              $max_height = "500";
              if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              elseif($image_path != '')
              {
                $image = $idprooffile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedidproofimg);
                
                $proof_to_add = $directory."/".$scannedidproofimg;
                
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['reg_no'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['regnumber'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              
              $new_proof = str_replace("k_","",substr($proof_to_add,strrpos($proof_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
                if(!$idproof_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                { 
                  $idproof_cnt++;
                }
              }
            }
            
            if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
              $success['zip'] = "cscvendor Member Images Zip Generated Successfully";
              }else{
              $error['zip'] = "Error While Generating cscvendor Member Images Zip";
            }
          }
          $i++;
          if($include_zip_flag == 1) { $mem_cnt++; }
          $file_w_flg = fwrite($fp, $data);
          if($file_w_flg){
            $success['file'] = "NSEIT Member Details File Generated Successfully. ";
          }
          else{
            $error['file'] = "Error While Generating NSEIT Member Details File.";
          }
        }
        fwrite($fp1, "\n"."Total NSEIT Members Added = ".$mem_cnt."\n");
        fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
        fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
        fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
        //$file_w_flg = fwrite($fp, $data);
        $zip->close();
      }
      else{
        $success[] = "No data found for the date";
      }
      fclose($fp);
      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Vendor NSEIT Member Details Cron End", $desc);
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$end_time." *************"."\n");
      fclose($fp1);
    }
  }
  
  public function NSEIT_member_sagar()
  {
    ini_set("memory_limit", "-1");
    
    $dir_flg = 0;
    $parent_dir_flg = 0;
    $file_w_flg = 0;
    $photo_zip_flg = 0;
    $sign_zip_flg = 0;
    $idproof_zip_flg = 0;
    $success = array();
    $error = array();
    $start_time = date("Y-m-d H:i:s");
    $current_date = date("Ymd");
    
    $cron_file_dir = "./uploads/rahultest/";
    
    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    
    $this->log_model->cronlog("Vendor NSEIT Member Details Cron Execution Start", $desc);
    
    if(!file_exists($cron_file_dir.$current_date))
    {
      $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
    }
    if(file_exists($cron_file_dir.$current_date))
    {
      $cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
      
      $file = "iibf_NSEIT_mem_details_".$current_date.".txt";
      $fp = fopen($cron_file_path.'/'.$file, 'w');
      
      $file1 = "logs_NSEIT_".$current_date.".txt";
      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$start_time." *************"."\n");
      
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
      
      //$yesterday = '2020-05-31';
      $members = array(7232642,7060477,510296157,400026081,500201132,3650143,510034682,801141461,7527943,500214774,510061398,4549571,7030100,510141040,500034158,510020677,510302907,500092558,500093603,510355441,200087616,510058224,500165074,500110540,7526991,510169776,801673415,510148941,6702987,801673522,500039017,510150628,510109552,500049190,510052664,510053454,7283285,845115338,510357869,200037221,4974441,510205726,510249159,510353806,801557608,7447913,801674070,801674088,500182301,500180891,510416044,510279623,510224071,500004827,500087217,500185035,510184665,7553602,500200618);
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
      //$exam_code_arry = array('1002','1010','1011','1012','1013','1014');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      //$this->db->where_in('a.exam_code',$exam_code_arry);
	  $this->db->where_in('c.regnumber',$members);
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1
      ), $select);
      //echo "<br> Qry : ".$this->db->last_query();
      //,'DATE(a.created_on) >= ' => '2021-05-01'
      if(count($new_mem_reg))
      {
        $dirname = "NSEIT_regd_image_".$current_date;
        $directory = $cron_file_path.'/'.$dirname;
        if(file_exists($directory)){
          array_map('unlink', glob($directory."/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else{
          $dir_flg = mkdir($directory, 0700);
        }
        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory.'.zip', ZipArchive::CREATE);
        
        $i = 1;
        $mem_cnt = 0;
        $photo_cnt = 0;
        $sign_cnt = 0;
        $idproof_cnt = 0;
        
        foreach($new_mem_reg as $reg_data)
        {
          $data = '';					
          $image_path = $reg_data['image_path'];
          
          $photo = '';
          if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
          {
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg"))
          {
            $phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
            $scannedphoto = "p_".$reg_data['regnumber'].".jpg";
            
            $photo 	= MEM_FILE_PATH.$scannedphoto;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['reg_no'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['regnumber'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
          }
          else
          {
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$photo = '';
            
            if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
            }
            else{
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $signature = '';
          if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
          {
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg"))
          {
            $signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
            $scannedsignature = "s_".$reg_data['regnumber'].".jpg";
            $signature 	= MEM_FILE_PATH.$scannedsignature;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['reg_no'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['regnumber'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }
          /*$signature = '';
            if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $idproofimg = '';
          
          if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
          {
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg"))
          {	
            $idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
            $scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
            
            $idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['reg_no'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['regnumber'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$idproofimg = '';
            if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          // Member Number | Name Prefix | Firstname | Middlename | Lastname
          
          /***** ADDED BY SAGAR ON 15-07-2020 *******/
          $include_zip_flag = 0;
          if($reg_data['exam_code'] == '1002')
          {
            if($reg_data['exam_date'] != '2020-08-02' && $reg_data['exam_date'] != '2020-08-08' && $reg_data['exam_date'] != '2020-08-09') 
            { 
              $include_zip_flag = 1;
            }
          }
          else if($reg_data['exam_code'] != '1002')
          {
            $include_zip_flag = 1;
          } 
          
          if($include_zip_flag == 1)
          {
            $data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
          } 
          
          if($dir_flg)
          {
            // For photo images
            if($photo)
            {
              $max_width = "200";
              $max_height = "200";
              
              if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              elseif($image_path != '')
              {
                $image = $phtofile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedphoto);
                $photo_to_add = $directory."/".$scannedphoto;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['reg_no'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['regnumber'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              
              $new_photo = str_replace("k_","",substr($photo_to_add,strrpos($photo_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
                if(!$photo_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
                } 
                else 
                { 
                	$photo_cnt++; 
                }
              }  						
            }
            
            // For signature images
            if($signature){
              
              $max_width = "140";
              $max_height = "100";
              if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              elseif($image_path != '')
              {
                $image = $signaturefile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedsignature);
                $sign_to_add = $directory."/".$scannedsignature;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['reg_no'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['regnumber'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              
              $new_sign = str_replace("k_","",substr($sign_to_add,strrpos($sign_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
                if(!$sign_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                {
                	$sign_cnt++;
                }
              }              
            }
            
            // For ID proof images
            if($idproofimg)
            {
              $max_width = "800";
              $max_height = "500";
              if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              elseif($image_path != '')
              {
                $image = $idprooffile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedidproofimg);
                
                $proof_to_add = $directory."/".$scannedidproofimg;
                
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['reg_no'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['regnumber'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              
              $new_proof = str_replace("k_","",substr($proof_to_add,strrpos($proof_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
                if(!$idproof_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                { 
                  $idproof_cnt++;
                }
              }
            }
            
            if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
              $success['zip'] = "cscvendor Member Images Zip Generated Successfully";
              }else{
              $error['zip'] = "Error While Generating cscvendor Member Images Zip";
            }
          }
          $i++;
          if($include_zip_flag == 1) { $mem_cnt++; }
          $file_w_flg = fwrite($fp, $data);
          if($file_w_flg){
            $success['file'] = "NSEIT Member Details File Generated Successfully. ";
          }
          else{
            $error['file'] = "Error While Generating NSEIT Member Details File.";
          }
        }
        fwrite($fp1, "\n"."Total NSEIT Members Added = ".$mem_cnt."\n");
        fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
        fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
        fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
        //$file_w_flg = fwrite($fp, $data);
        $zip->close();
      }
      else{
        $success[] = "No data found for the date";
      }
      fclose($fp);
      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Vendor NSEIT Member Details Cron End", $desc);
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$end_time." *************"."\n");
      fclose($fp1);
    }
  }
  
	
  public function Csc_member_data_chaitali()
  {
    ini_set("memory_limit", "-1");
    
    $dir_flg = 0;
    $parent_dir_flg = 0;
    $file_w_flg = 0;
    $photo_zip_flg = 0;
    $sign_zip_flg = 0;
    $idproof_zip_flg = 0;
    $success = array();
    $error = array();
    $start_time = date("Y-m-d H:i:s");
    $current_date = date("Ymd");
    
    $cron_file_dir = "./uploads/rahultest/";
    
    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    
    $this->log_model->cronlog("Vendor NSEIT Member Details Cron Execution Start", $desc);
    
    if(!file_exists($cron_file_dir.$current_date))
    {
      $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
    }
    if(file_exists($cron_file_dir.$current_date))
    {
      $cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
      
      $file = "iibf_CSC_mem_details_".$current_date.".txt";
      $fp = fopen($cron_file_path.'/'.$file, 'w');
      
      $file1 = "logs_NSEIT_".$current_date.".txt";
      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$start_time." *************"."\n");
      
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
      
      $yesterday = '2020-05-02';
      $members = array(500087801,500093046);
      $this->db->where_in('c.regnumber',$members);
     // $this->db->where('DATE(a.created_on) >=', '2021-05-01');
     // $this->db->where('DATE(a.created_on) <=', '2021-05-13');
      
     // $exam_code_arry = array('1002','1010','1011','1012','1013','1014');
      $exam_code = array('1003','1004','1005','1006','1007','1008','1009');
	  $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      $this->db->where_in('a.exam_code',$exam_code); 
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1
			
      ), $select);
	  //'DATE(a.created_on) = ' => '2021-05-12'
      //echo "<br> Qry : ".$this->db->last_query(); die;
      
      if(count($new_mem_reg))
      {
        $dirname = "CSC_regd_image_".$current_date;
        $directory = $cron_file_path.'/'.$dirname;
        if(file_exists($directory)){
          array_map('unlink', glob($directory."/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else{
          $dir_flg = mkdir($directory, 0700);
        }
        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory.'.zip', ZipArchive::CREATE);
        
        $i = 1;
        $mem_cnt = 0;
        $photo_cnt = 0;
        $sign_cnt = 0;
        $idproof_cnt = 0;
        
        foreach($new_mem_reg as $reg_data)
        {
          $data = '';					
          $image_path = $reg_data['image_path'];
          
          $photo = '';
          if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
          {
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg"))
          {
            $phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
            $scannedphoto = "p_".$reg_data['regnumber'].".jpg";
            
            $photo 	= MEM_FILE_PATH.$scannedphoto;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['reg_no'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['regnumber'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
          }
          else
          {
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$photo = '';
            
            if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
            }
            else{
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $signature = '';
          if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
          {
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg"))
          {
            $signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
            $scannedsignature = "s_".$reg_data['regnumber'].".jpg";
            $signature 	= MEM_FILE_PATH.$scannedsignature;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['reg_no'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['regnumber'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }
          /*$signature = '';
            if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $idproofimg = '';
          
          if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
          {
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg"))
          {	
            $idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
            $scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
            
            $idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['reg_no'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['regnumber'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$idproofimg = '';
            if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          // Member Number | Name Prefix | Firstname | Middlename | Lastname
          
          /***** ADDED BY SAGAR ON 15-07-2020 *******/
          $include_zip_flag = 0;
          if($reg_data['exam_code'] == '1002')
          {
            if($reg_data['exam_date'] != '2020-08-02' && $reg_data['exam_date'] != '2020-08-08' && $reg_data['exam_date'] != '2020-08-09') 
            { 
              $include_zip_flag = 1;
            }
          }
          else if($reg_data['exam_code'] != '1002')
          {
            $include_zip_flag = 1;
          } 
          
          if($include_zip_flag == 1)
          {
            $data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
          } 
          
          if($dir_flg)
          {
            // For photo images
            if($photo)
            {
              $max_width = "200";
              $max_height = "200";
              
              if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              elseif($image_path != '')
              {
                $image = $phtofile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedphoto);
                $photo_to_add = $directory."/".$scannedphoto;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['reg_no'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['regnumber'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              
              $new_photo = str_replace("k_","",substr($photo_to_add,strrpos($photo_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
                if(!$photo_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
                } 
                else 
                { 
                	$photo_cnt++; 
                }
              }  						
            }
            
            // For signature images
            if($signature){
              
              $max_width = "140";
              $max_height = "100";
              if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              elseif($image_path != '')
              {
                $image = $signaturefile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedsignature);
                $sign_to_add = $directory."/".$scannedsignature;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['reg_no'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['regnumber'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              
              $new_sign = str_replace("k_","",substr($sign_to_add,strrpos($sign_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
                if(!$sign_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                {
                	$sign_cnt++;
                }
              }              
            }
            
            // For ID proof images
            if($idproofimg)
            {
              $max_width = "800";
              $max_height = "500";
              if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              elseif($image_path != '')
              {
                $image = $idprooffile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedidproofimg);
                
                $proof_to_add = $directory."/".$scannedidproofimg;
                
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['reg_no'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['regnumber'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              
              $new_proof = str_replace("k_","",substr($proof_to_add,strrpos($proof_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
                if(!$idproof_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                { 
                  $idproof_cnt++;
                }
              }
            }
            
            if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
              $success['zip'] = "cscvendor Member Images Zip Generated Successfully";
              }else{
              $error['zip'] = "Error While Generating cscvendor Member Images Zip";
            }
          }
          $i++;
          if($include_zip_flag == 1) { $mem_cnt++; }
          $file_w_flg = fwrite($fp, $data);
          if($file_w_flg){
            $success['file'] = "NSEIT Member Details File Generated Successfully. ";
          }
          else{
            $error['file'] = "Error While Generating NSEIT Member Details File.";
          }
        }
        fwrite($fp1, "\n"."Total NSEIT Members Added = ".$mem_cnt."\n");
        fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
        fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
        fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
        //$file_w_flg = fwrite($fp, $data);
        $zip->close();
      }
      else{
        $success[] = "No data found for the date";
      }
      fclose($fp);
      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Vendor NSEIT Member Details Cron End", $desc);
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$end_time." *************"."\n");
      fclose($fp1);
    }
  }
  
	
  public function cscvendor_member()
  {
    ini_set("memory_limit", "-1");
    $dir_flg = 0;
    $parent_dir_flg = 0;
    $file_w_flg = 0;
    $photo_zip_flg = 0;
    $sign_zip_flg = 0;
    $idproof_zip_flg = 0;
    $success = array();
    $error = array();
    $start_time = date("Y-m-d H:i:s");
    $current_date = date("Ymd");
    $cron_file_dir = "./uploads/cronfiles/";
    
    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    $this->log_model->cronlog("cscvendor Details Cron Execution Start", $desc);
    
    if(!file_exists($cron_file_dir.$current_date)){
      $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
    }
    
    if(file_exists($cron_file_dir.$current_date))
    {
      $cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
      
      $file = "iibf_cscvendor_mem_details_".$current_date.".txt";
      $fp = fopen($cron_file_path.'/'.$file, 'w');
      $file1 = "logs_cscvendor_".$current_date.".txt";
      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
      fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron Start ".$start_time." *****"."\n");
      
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
      
      //$yesterday = '2020-06-17';
      //$members = array('510101898','510415515');
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
      $exam_code_arry = array('1002','1003','1004','1005','1006','1007','1008','1009');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      $this->db->where_in('a.exam_code',$exam_code_arry);		
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1,
			'DATE(a.created_on)' => $yesterday
      ), $select);
      //echo "<br> Qry : ".$this->db->last_query();
      
      if(count($new_mem_reg))
      {
        $dirname = "cscvendor_regd_image_".$current_date;
        $directory = $cron_file_path.'/'.$dirname;
        if(file_exists($directory)){
          array_map('unlink', glob($directory."/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else{
          $dir_flg = mkdir($directory, 0700);
        }
        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory.'.zip', ZipArchive::CREATE);
        
        $i = 1;
        $mem_cnt = 0;
        $photo_cnt = 0;
        $sign_cnt = 0;
        $idproof_cnt = 0;
        
        foreach($new_mem_reg as $reg_data)
        {	
          $data = '';
          $image_path = $reg_data['image_path'];
          
          $photo = '';
          if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];	
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg")){
            $phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
            $scannedphoto = "p_".$reg_data['regnumber'].".jpg";
            
            $photo 	= MEM_FILE_PATH.$scannedphoto;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['reg_no'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['regnumber'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
          }
          else{
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$photo = '';
            
            if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
            }
            else{
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $signature = '';
          if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg")){
            $signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
            $scannedsignature = "s_".$reg_data['regnumber'].".jpg";
            $signature 	= MEM_FILE_PATH.$scannedsignature;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['reg_no'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['regnumber'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
          }
          else{
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$signature = '';
            if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $idproofimg = '';
          if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg")){	
            $idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
            $scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
            $idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['reg_no'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['regnumber'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
          }
          else{
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$idproofimg = '';
            if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          // Member Number | Name Prefix | Firstname | Middlename | Lastname
          
          /***** ADDED BY SAGAR ON 15-07-2020 *******/
          $include_zip_flag = 0;
          if($reg_data['exam_code'] == '1002' && ($reg_data['exam_date']  == '2020-08-02' || $reg_data['exam_date']  == '2020-08-08' || $reg_data['exam_date']  == '2020-08-09'))
          {
            $include_zip_flag = 1;
          }
          elseif($reg_data['exam_code'] != '1002')
          {
            $include_zip_flag = 1;
          }
          
          if($include_zip_flag == 1)
          {          
            $data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
          } 
          
          if($dir_flg)
          {
            // For photo images
            if($photo)
            {
              $max_width = "200";
              $max_height = "200";
              
              if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
                $image = "./uploads/photograph/".$reg_data['scannedphoto']; 
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              elseif($image_path != ''){ 
                $image = $phtofile; 
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedphoto);
                $photo_to_add = $directory."/".$scannedphoto;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['reg_no'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['regnumber'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['regnumber'].".jpg";
                }
              }
              else{
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              
              $new_photo = str_replace("k_","",substr($photo_to_add,strrpos($photo_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
                if(!$photo_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
                } else { $photo_cnt++; }
              }
            }
            
            // For signature images
            if($signature)
            {
              $max_width = "140";
              $max_height = "100";
              if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              elseif($image_path != ''){
                $image = $signaturefile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedsignature);
                
                $sign_to_add = $directory."/".$scannedsignature;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['reg_no'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['regnumber'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['regnumber'].".jpg";
                }
              }
              else{
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              
              $new_sign = str_replace("k_","",substr($sign_to_add,strrpos($sign_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
                if(!$sign_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
                }
                else { $sign_cnt++; }
              }
            }
            
            // For ID proof images
            if($idproofimg)
            {
              $max_width = "800";
              $max_height = "500";
              if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              elseif($image_path != ''){
                $image = $idprooffile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedidproofimg);
                
                $proof_to_add = $directory."/".$scannedidproofimg;	
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['reg_no'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['regnumber'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['regnumber'].".jpg";
                }
              }
              else{
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              
              $new_proof = str_replace("k_","",substr($proof_to_add,strrpos($proof_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
                if(!$idproof_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
                }
                else { $idproof_cnt++; }
              }
            }
            
            if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
              $success['zip'] = "cscvendor Member Images Zip Generated Successfully";
              }else{
              $error['zip'] = "Error While Generating cscvendor Member Images Zip";
            }
          }
          $i++;
          if($include_zip_flag == 1) { $mem_cnt++; }
          $file_w_flg = fwrite($fp, $data);
          if($file_w_flg){
            $success['file'] = "cscvendor Member Details File Generated Successfully. ";
          }
          else{
            $error['file'] = "Error While Generating cscvendor Member Details File.";
          }
        }
        fwrite($fp1, "\n"."Total cscvendor Members Added = ".$mem_cnt."\n");
        fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
        fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
        fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
        //$file_w_flg = fwrite($fp, $data);
        $zip->close();
      }
      else{
        $success[] = "No data found for the date";
      }
      fclose($fp);
      
      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Vendor cscvendor Member Details Cron End", $desc);
      fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron End ".$end_time." *****"."\n");
      fclose($fp1);
    }
  }
	
  /* Only for Free CSC Vendor : 30-09-2020 */
	public function free_csc_member()
	{
	    ini_set("memory_limit", "-1");
	    
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		
		//$current_date = '20190603';
		
		$cron_file_dir = "./uploads/CSC_FREE_APP/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		
		$this->log_model->cronlog("Vendor CSC Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_csc_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_CSC_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$start_time." *************"."\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			
			
			
			$exam_code = array('991');
			$select    = 'a.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto';
            $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
            $this->db->where_in('a.exam_code', $exam_code);
            $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
                'c.isactive' => '1',
                'c.isdeleted' => 0,
                'a.pay_status' => 1,
				'a.free_paid_flg' => 'F',
                'DATE(a.created_on)' => $yesterday
            ), $select);
			if(count($new_mem_reg))
			{
				$dirname = "csc_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$photo = '';
					
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
								
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "CSC Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating CSC Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "CSC Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating CSC Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor CSC Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	
	/* dra_member_vendor Cron */
	// admin/Crontest/dra_member_vendor
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron dra_member_vendor
	public function dra_member_vendor()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");			
		$cron_file_dir = "./uploads/dra_csv/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("New DRA Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dra_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New DRA Candidate Details Cron Start - ".$start_time." ***************** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$this->db->select("namesub, firstname, middlename, lastname, regnumber, scannedphoto, scannedsignaturephoto, idproofphoto, idproof, DATE(a.updated_date) updated_date, d.image_path, d.registration_no");
			$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
			$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
			$this->db->join('dra_members d','d.regid = c.regid','LEFT');
			$this->db->where("DATE(a.updated_date) = '".$yesterday."'");
			$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
			
			//echo $this->db->last_query();
			//exit;
			
			if(count($new_dra_reg))
			{
				$data = '';
				$dirname = "dra_regd_image_".$current_date;
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
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				
				foreach($new_dra_reg as $dra)
				{
					$photo_file = '';
					$sign_file = '';
					$idproof_file = '';
					if($dir_flg)
					{
						// Photograph
						if($dra['scannedphoto'] != '' && is_file("./uploads/iibfdra/".$dra['scannedphoto']))
						{
							$photo_file = $dra['scannedphoto'];
							copy("./uploads/iibfdra/".$dra['scannedphoto'],$directory."/".$dra['scannedphoto']);
							$photo_to_add = $directory."/".$dra['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg"))
						{
							
							$photo_file = "p_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg",$directory."/".$photo_file);
							$photo_to_add = $directory."/".$photo_file;
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photo_file." (".$dra['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
						}
						
						// Signature
						if(is_file("./uploads/iibfdra/".$dra['scannedsignaturephoto']))
						{
							$sign_file = $dra['scannedsignaturephoto'];
							copy("./uploads/iibfdra/".$dra['scannedsignaturephoto'],$directory."/".$dra['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$dra['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg"))
						{
							$sign_file = "s_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg",$directory."/".$sign_file);
							$sign_to_add = $directory."/".$sign_file;
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$sign_file." (".$dra['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Signature does not exist  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
						}
						
						//ID Proof
						if(is_file("./uploads/iibfdra/".$dra['idproofphoto']))
						{
							$idproof_file = $dra['idproofphoto'];
							copy("./uploads/iibfdra/".$dra['idproofphoto'],$directory."/".$dra['idproofphoto']);
							$proof_to_add = $directory."/".$dra['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg"))
						{
							$idproof_file = "pr_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg",$directory."/".$idproof_file);
							$proof_to_add = $directory."/".$idproof_file;
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idproof_file." (".$dra['regnumber'].")\n");	
							}
							else
								$idproof_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "New DRA Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New DRA Candidate Images Zip";
						}
					}
					
					/*$photo 		= DRA_FILE_PATH.$photo_file;
					$signature 	= DRA_FILE_PATH.$sign_file;
					$idproofimg = DRA_FILE_PATH.$idproof_file;
					$tcimg 		= DRA_FILE_PATH.$tc_file;
					$dcimg 		= DRA_FILE_PATH.$dc_file;*/
					
					$photo 	= "";
					if($photo_file!=""){
						$photo 	= DRA_FILE_PATH.$photo_file;
					}
					
					$signature 	= "";
					if($sign_file!=""){
						$signature 	= DRA_FILE_PATH.$sign_file;
					}
					
					$idproofimg 	= "";
					if($idproof_file!=""){
						$idproofimg = DRA_FILE_PATH.$idproof_file;
					}
					
					$data .= ''.$dra['regnumber'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname']."\n";
					
					$i++;
					$mem_cnt++;
				}
				
				fwrite($fp1, "\n"."Total New DRA Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				
				$file_w_flg = fwrite($fp, $data);
				if($file_w_flg)
				{
					$success['file'] = "New DRA Candidate Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating New DRA Candidate Details File.";
				}
				
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
			$this->log_model->cronlog("New DRA Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** New DRA Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}

	/*SCRIBE REGISTRATION DATA CRON : POOJA MANE : 16-11-2022*/
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
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
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

					$data .= ''.$reg_data['scribe_uid'].'|'.$reg_data['exam_code'].'|'.$reg_data['exam_name'].'|'.$reg_data['subject_code'].'|'.$reg_data['subject_name'].'|'.$reg_data['regnumber'].'|'.$reg_data['center_name'].'|'.$reg_data['center_code'].'|'.$reg_data['name_of_scribe'].'|'.$reg_data['mobile_scribe'].'|'.$qualification.'|'.$reg_data['emp_details_scribe'].'|'.$description.'|'.$reg_data['photoid_no'].'|'.$idproofimg.'|'.$declarationimg.'|'.$reg_data['exam_date'].'|'.$reg_data['modified_on'].'|'.$special_assistance.'|'.$extra_time."\n";
					
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
		
	}
	/*SCRIBE REGISTRATION DATA CRON : POOJA MANE : 16-11-2022*/
}
