<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Venue extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');		
		$this->load->model('log_model');
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
	
	########get venue#########
	public function getVenue()
	{
		$centerCode= $_POST['centerCode'];
		$examCode= $_POST['examCode'];
		$subject_array=$_POST['subject_array'];
		$venue_option='<option value="">Select Venue</option>';
		$date_option='<option value="">Select Date</option>';
		$time_option='<option value="">Select Time</option>';
		$subject_date=$venue_arr=array();
		$skippedAdmitCardForExamsCodes = $this->config->item('skippedAdmitCardForExams');  //priyanka D >> 16-jan >> to skip admit cards details
		$skippedAdmitCardForExams = 0;
		if(in_array($examCode,$skippedAdmitCardForExamsCodes)) {
			$skippedAdmitCardForExams = 1;
		}
		//print_r($subject_array);die;
		if($centerCode!="" && $examCode!='')
		{
			//exam period change for getting  venue as per their exam period
			if (($examCode=='101' || $examCode=='1046' || $examCode=='1047') && $subject_array=='') {
				$subject_array=array('0');
			}
			$this->db->where_in('subject_code',$subject_array,false);
			$this->db->where('exam_date !=','0000-00-00');
			$getSubject_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$examCode),array('exam_date'),array('exam_date'=>'ASC'));//'exam_period'
			//echo $this->db->last_query().'</br>'; 
			if(count($getSubject_date)>0)
			{
				$i=1;
				foreach($getSubject_date as $row)
				{
					//$subject_date[]=$row['exam_date'];
					//$exam_period =$getSubject_date[0]['exam_period']; 
					$this->db->where('exam_date',$row['exam_date']);
					$this->db->where('institute_code',0); // Added by Priyanka W to show only RPE venue without Bulk
					//$this->db->where('exam_period', $exam_period); 
					$this->db->group_by('venue_code');
					$getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
					//echo $this->db->last_query().'</br>';
					if(count($getvenue_details) > 0)
					{
						$venue_option='<option value="">Select Venue</option>';
						foreach($getvenue_details as $row)
						{	
								$pwd_enable='';
								if($row['pwd_enabled']=='Y')
								{
									//$pwd_enable=' (PWD enabled)';
									$pwd_enable='';
								}
								$venue_add='';
								$venue_add=$row['venue_name'].'*'.$row['venue_addr1'].'*'.$row['venue_addr2'].'*'.$row['venue_addr3'].'*'.$row['venue_addr4'].'*'.$row['venue_addr5'].'*'.$row['venue_pincode'];
								$venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
								$venue_add_finalstring = rtrim($venue_add_finalstring, ',');
								$venue_option.='<option value='.$row['venue_code'].' title="'.$venue_add_finalstring.''.$pwd_enable.'">'.substr($venue_add_finalstring,0,39).''.$pwd_enable.'.</option>';
							
						}
						
						$venue_arr['venue_option_'.$i]=$venue_option;
						
					}
					else
					{
						$venue_option='<option value="">Select Venue</option>';
						$venue_arr['venue_option_'.$i]=$venue_option;
					}
					$i++;
				}
			}
			/*//$exam_period =$getSubject_date[0]['exam_period']; 
			$this->db->where_in('exam_date', $subject_date);
			//$this->db->where('exam_period', $exam_period); 
			$this->db->distinct('venue_code');
			$getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
			//echo $this->db->last_query(),'</br>';
			if(count($getvenue_details) > 0)
			{
				foreach($getvenue_details as $row)
				{	
						$pwd_enable='';
						if($row['pwd_enabled']=='Y')
						{
							$pwd_enable=' (PWD enabled)';
						}
						$venue_add='';
						$venue_add=$row['venue_name'].'*'.$row['venue_addr1'].'*'.$row['venue_addr2'].'*'.$row['venue_addr3'].'*'.$row['venue_addr4'].'*'.$row['venue_addr5'].'*'.$row['venue_pincode'];
						$venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
						$venue_option.='<option value='.$row['venue_code'].' title="'.$venue_add_finalstring.''.$pwd_enable.'">'.substr($venue_add_finalstring,0,39).''.$pwd_enable.'.</option>';
				}
			}*/
		}
		$other_option_arr=array('date_option'=>$date_option,'time_option'=>$time_option,'skippedAdmitCardForExams'=>$skippedAdmitCardForExams);
		//$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option);		
		$data_arr=array_merge($venue_arr,$other_option_arr);
		echo json_encode($data_arr);
	}
	
	
	
	########get venue for elecetive#########
	public function getElectiveVenue()
	{
		$centerCode= $_POST['centerCode'];
		$examCode= $_POST['examCode'];
		$elective_subcode= $_POST['elective_subcode'];
		$skippedAdmitCardForExamsCodes = $this->config->item('skippedAdmitCardForExams');  //priyanka D >> 16-jan >> to skip admit cards details
		$skippedAdmitCardForExams = 0;
		if(in_array($examCode,$skippedAdmitCardForExamsCodes)) {
			$skippedAdmitCardForExams = 1;
		}
		$venue_option='<option value="">Select Venue</option>';
		$date_option='<option value="">Select Date</option>';
		$time_option='<option value="">Select Time</option>';
		$subject_date=$venue_arr=array();
		if($centerCode!="" && $examCode!='' && $elective_subcode!='')
		{
			$getSubject_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$examCode,'subject_code'=>$elective_subcode),'exam_date');
			if(count($getSubject_date)>0)
			{
				foreach($getSubject_date as $row)
				{
					$subject_date[]=$row['exam_date'];
				}
			}
			$this->db->where_in('exam_date', $subject_date);
			$this->db->distinct('venue_code');
			$getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
			if(count($getvenue_details) > 0)
			{
				foreach($getvenue_details as $row)
				{	
						$pwd_enable='';
						if($row['pwd_enabled']=='Y')
						{
							$pwd_enable=' (PWD enabled)';
						}
						$venue_add='';
						$venue_add=$row['venue_name'].'*'.$row['venue_addr1'].'*'.$row['venue_addr2'].'*'.$row['venue_addr3'].'*'.$row['venue_addr4'].'*'.$row['venue_addr5'].'*'.$row['venue_pincode'];
						$venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
						$venue_option.='<option value='.$row['venue_code'].' title="'.$venue_add_finalstring.''.$pwd_enable.'">'.substr($venue_add_finalstring,0,39).''.$pwd_enable.'.</option>';
				}
			}
		}
		//$other_option_arr=array('date_option'=>$date_option,'time_option'=>$time_option);
		$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option,'skippedAdmitCardForExams'=>$skippedAdmitCardForExams);		
		//$data_arr=array_merge($venue_arr,$other_option_arr);
		echo json_encode($data_arr);
	}
	
	########get venue for special exam#########
	/*public function getVenueSpecialExam()
	{
		$examCode= $_POST['examCode'];
		$centerCode= $_POST['centerCode'];
		
		$venue_option='<option value="">Select Venue</option>';
		$date_option='<option value="">Select Date</option>';
		$time_option='<option value="">Select Time</option>';
		if($centerCode!="" && $examCode!='')
		{
			
			if(count($getvenue_details) > 0)
			{
				foreach($getvenue_details as $row)
				{	
						$pwd_enable='';
						if($row['pwd_enabled']=='Y')
						{
							$pwd_enable=' (PWD enabled)';
						}
						$venue_add='';
						$venue_add=$row['venue_name'].'*'.$row['venue_addr1'].'*'.$row['venue_addr2'].'*'.$row['venue_addr3'].'*'.$row['venue_addr4'].'*'.$row['venue_addr5'].'*'.$row['venue_pincode'];
						$venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
						$venue_option.='<option value='.$row['venue_code'].' title="'.$venue_add_finalstring.''.$pwd_enable.'">'.substr($venue_add_finalstring,0,40).''.$pwd_enable.'.</option>';
				}
			}
		}
		$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option);		
		echo json_encode($data_arr);
	}*/
	
	########get Date ######## 
	public function getDate()
	{
		//$centerCode= $_POST['centerCode'];
		//$venue_code= $_POST['venue_code'];
		$eprid= $_POST['eprid'];
		$examcode= $_POST['examcode'];
		$subject_code= $_POST['subject_code'];
		$venue_code= $_POST['venue_code'];
		
		$date_option='<option value="">Select Date</option>';
		$time_option='<option value="">Select Time</option>';

		$skippedAdmitCardForExamsCodes = $this->config->item('skippedAdmitCardForExams');  //priyanka D >> 16-jan >> to skip admit cards details
		$skippedAdmitCardForExams = 0;
		if(in_array($examcode,$skippedAdmitCardForExamsCodes)) {
			$skippedAdmitCardForExams = 1;
		}

		if($eprid!="" && $examcode!="" && $subject_code!="" && $venue_code!="")
		{
			/*$this->db->distinct('exam_date');
			$getdate_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode,'venue_code'=>$venue_code),'exam_date');
			if(count($getdate_details) > 0)
			{
				foreach($getdate_details as $daterow)
				{	
						$date_option.='<option value='.$daterow['exam_date'].'>'.date('d-M-Y',strtotime($daterow['exam_date'])).'</option>';
				}
			}*/
			//$getdate_details=$this->master_model->getRecords('subject_master',array('exam_code'=>$examcode,'exam_period'=>$eprid,'subject_code'=>$subject_code),'exam_date');
			
			######### if exam is special exam#####
			if($_POST['examcode']==34 || $_POST['examcode']==58 || $_POST['examcode']==160 || $_POST['examcode']==1770)
			{
				$today_date=date('Y-m-d');
				$this->db->where("'$today_date' BETWEEN from_date AND to_date");
				$this->db->order_by('special_exam_dates.examination_date','ASC');
				$special_exam_dates=$this->master_model->getRecords('special_exam_dates','','examination_date');
				if(count($special_exam_dates) > 0)
				{
					foreach($special_exam_dates as $row)
					{
						$arr_special_exam_dates[]=$row['examination_date'];
					}
				}
				$this->db->where_in('exam_date',array_map('stripslashes',$arr_special_exam_dates));
				}
			######### End of if exam is special exam##
			
						
			//Code for examination date validity by prafull
			$today_date=date('Y-m-d');
			$this->db->where("'$today_date' BETWEEN from_date AND to_date");
			$this->db->where('exam_code',$examcode);
			$get_valid_date=$this->master_model->getRecords('valid_examination_date');
			// print_r($get_valid_date); exit;
			if(count($get_valid_date) > 0)
			{
					foreach($get_valid_date as $row)
					{
						$arr_special_exam_dates[]=$row['examination_date'];
					}
				$this->db->where_in('exam_date',array_map('stripslashes',$arr_special_exam_dates));
			}
			else
			{
				$today_date=date('Y-m-d');
				$this->db->where("'$today_date' NOT  BETWEEN from_date AND to_date");
				$this->db->where('exam_code',$examcode);
				$get_expired_date=$this->master_model->getRecords('valid_examination_date');
				
				if($get_expired_date)
				{
					foreach($get_expired_date as $expired_row)
					{
						if($expired_row['examination_date']!='0000-00-00')
						{
							$this->master_model->updateRecord('subject_master', array('exam_date' => '0000-00-00','exam_code'=>$expired_row['exam_code'].rand(111,999)), array('exam_date' =>$expired_row['examination_date'],'exam_code'=>$expired_row['exam_code']));
						}
					}
				}
			}
			//Code for examination date validity by prafull
			$this->db->order_by('subject_master.exam_date','ASC');
			if(isset($eprid) && $eprid == "853"){
				$this->db->where('exam_period',$eprid);
			}
			$getdate_details=$this->master_model->getRecords('subject_master',array('exam_code'=>$examcode,'subject_code'=>$subject_code),'exam_date');
			// echo $this->db->last_query(),'</br>'; exit;
			if(count($getdate_details) > 0)
			{
				foreach($getdate_details as $daterow)
				{	
						$date_option.='<option value='.$daterow['exam_date'].'>'.date('d-M-Y',strtotime($daterow['exam_date'])).'</option>';
				}
			}
		}
		// echo $date_option; exit;
		$data_arr=array('date_option'=>$date_option,'time_option'=>$time_option,'skippedAdmitCardForExams'=>$skippedAdmitCardForExams);		
		echo json_encode($data_arr);
	}
	
	########get Venue Time########
	public function getTime()
	{
		$centerCode= $_POST['centerCode'];
		$venue_code= $_POST['venue_code'];
		$date_code= $_POST['date_code'];
		$time_option='<option value="">Select Time</option>';

		$examcode = $_POST['examcode']; //priyanka D >> 16-jan >> to skip admit cards details
		$skippedAdmitCardForExamsCodes = $this->config->item('skippedAdmitCardForExams'); 
		$skippedAdmitCardForExams = 0;
		if(in_array($examcode,$skippedAdmitCardForExamsCodes)) {
			$skippedAdmitCardForExams = 1;
		}

		if($centerCode!="" && $venue_code!="" && $date_code!="")
		{
			$this->db->distinct('session_time');
			//$this->db->order_by("STR_TO_DATE(`session_time`,'%h.%i%p')",'');
			$this->db->order_by('TRIM(session_time)','');
			$gettime_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode,'venue_code'=>$venue_code,'exam_date'=>$date_code,'institute_code'=>0),'session_time');
			//echo $this->db->last_query(),'</br>';
			if(count($gettime_details) > 0)
			{
				foreach($gettime_details as $timerow)
				{	
						$session_time=$timerow['session_time'];
						$disable=$capacity_msg='';
						$capacity=check_capacity($venue_code,$date_code,$session_time,$centerCode);
						if($capacity==0)
						{
							$disable='disabled="disabled"';
							$capacity_msg='(Capacity Full)';
						}
						$time_option.='<option value="'.$session_time.'" '.$disable.'>'.$session_time.''.$capacity_msg.'</option>';
				}
			}
		}
		$data_arr=array('time_option'=>$time_option,'skippedAdmitCardForExams'=>$skippedAdmitCardForExams);		
		echo json_encode($data_arr);
	}
	
	########get Venue Time########
	public function getCapacity()
	{
		$venue_code=$_POST['venue_code'];
		$date_code=$_POST['date_code'];
		$time=$_POST['time'];
		$centerCode= $_POST['centerCode'];
		$exam_cd = null; //priyanka d >>27-dec-24 >> by default selecting venue for jaiib/caiiib as we don't have to create admitcard from filled form now >> exam_cd
		if(isset($_POST['exam_cd']) && !empty($_POST['exam_cd'])) {
			$exam_cd = $_POST['exam_cd'];
		}
		//echo 'venue_code--'.$venue_code.'date_code--'.$date_code.'time--'.$time.'centerCode--'.$centerCode;die;
		$data_arr=array('capacity'=>get_capacity($venue_code,$date_code,$time,$centerCode,$exam_cd));		
		echo json_encode($data_arr);
	}
	
	
}



