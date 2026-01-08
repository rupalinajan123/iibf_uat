<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dra_venue extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');		
		$this->load->model('log_model');
		$this->load->helper('dra_seatallocation_helper');
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
		$ex_prd='';
		
		$centerCode= $_POST['centerCode'];
		$examCode= $_POST['examCode'];
		$venue_option='<option value="">Select Venue</option>';
		$date_option='<option value="">Select Date</option>';
		$time_option='<option value="">Select Time</option>';
		$subject_date=array();
		if($centerCode!="" && $examCode!='')
		{
			
			$getSubject_date=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$examCode),'exam_date');
			if(count($getSubject_date)>0)
			{
				foreach($getSubject_date as $row)
				{
					$subject_date[]=$row['exam_date'];
				}
			}
			$this->db->where_in('exam_date', $subject_date);
			$this->db->distinct('venue_code');
			$getvenue_details=$this->master_model->getRecords('dra_venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
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
		$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option);		
		echo json_encode($data_arr);
	}
	
	########get Date ######## 
	public function getDate()
	{ 
		$examcode= $_POST['examcode'];
		$subject_code= $_POST['subject_code'];
		$venue_code= $_POST['venue_code'];
		
		$date_option='<option value="">Select Date</option>';
		$time_option='<option value="">Select Time</option>';
		if($examcode!="" && $subject_code!="" && $venue_code!="")
		{
			
			//Code for examination date validity by prafull
			$today_date=date('Y-m-d');
			$this->db->where("'$today_date' BETWEEN from_date AND to_date");
			$this->db->where('exam_code',$examcode);
			$get_valid_date=$this->master_model->getRecords('dra_valid_examination_date');
			
			$valid_date_arr = array('9999-01-01');
			if(count($get_valid_date) > 0)
			{
				foreach($get_valid_date as $validDate)
				{
					$valid_date_arr [] = $validDate['examination_date'];
				}
			}
			
			$this->db->where_in('exam_date',$valid_date_arr);
			$this->db->order_by('exam_date','ASC');
			$getdate_details=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$examcode,'subject_code'=>$subject_code),'exam_date');
			
			if(count($getdate_details) > 0)
			{
				foreach($getdate_details as $daterow)
				{	
						$date_option.='<option value='.$daterow['exam_date'].'>'.date('d-M-Y',strtotime($daterow['exam_date'])).'</option>';
				}
			}
		}
		$data_arr=array('date_option'=>$date_option,'time_option'=>$time_option,'qry'=>$this->db->last_query());		
		echo json_encode($data_arr);
	}
	
	########get Venue Time########
	public function getTime()
	{
		$centerCode= $_POST['centerCode'];
		$venue_code= $_POST['venue_code'];
		$date_code= $_POST['date_code'];
		$time_option='<option value="">Select Time</option>';
		if($centerCode!="" && $venue_code!="" && $date_code!="")
		{
			$this->db->distinct('session_time');
			$gettime_details=$this->master_model->getRecords('dra_venue_master',array('center_code'=>$centerCode,'venue_code'=>$venue_code,'exam_date'=>$date_code),'session_time');
			if(count($gettime_details) > 0)
			{
				foreach($gettime_details as $timerow)
				{	
						$session_time=$timerow['session_time'];
						$disable=$capacity_msg='';
						$capacity=dra_check_capacity($venue_code,$date_code,$session_time,$centerCode);
						if($capacity==0)
						{
							$disable='disabled="disabled"';
							$capacity_msg='(Capacity Full)';
						}
						$time_option.='<option value="'.$session_time.'" '.$disable.'>'.$session_time.''.$capacity_msg.'</option>';
				}
			}
		}
		$data_arr=array('time_option'=>$time_option);		
		echo json_encode($data_arr);
	}
	
	########get Venue Time########
	public function getCapacity()
	{
		$venue_code=$_POST['venue_code'];
		$date_code=$_POST['date_code'];
		$time=$_POST['time'];
		$centerCode= $_POST['centerCode'];
		$data_arr=array('capacity'=>dra_get_capacity($venue_code,$date_code,$time,$centerCode));		
		echo json_encode($data_arr);
	}
	
	
}
