<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk_venue extends CI_Controller {

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

		$ex_prd='';

		if(isset($this->session->userdata['exmCrdPrd']['exam_prd']))

		{

			$ex_prd=$this->session->userdata['exmCrdPrd']['exam_prd'];

		}

		$centerCode= $_POST['centerCode'];

		$examCode= $_POST['examCode'];

		$venue_option='<option value="">Select Venue</option>';

		$date_option='<option value="">Select Date</option>';

		$time_option='<option value="">Select Time</option>';

		$subject_date=array();
		$skippedAdmitCardForExams =0;


		if(in_array($examCode,$this->config->item('skippedAdmitCardForExams'))) {
			$skippedAdmitCardForExams = 1;
		}
		if($centerCode!="" && $examCode!='')

		{

			if($ex_prd!='')

			{

				$this->db->where('exam_period',$ex_prd);

			}

			$getSubject_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$examCode),'exam_date');
			// echo $this->db->last_query();die;

			if(count($getSubject_date)>0)

			{

				foreach($getSubject_date as $row)

				{

					$subject_date[]=$row['exam_date'];

				}

			}

			$this->db->where_in('exam_date', $subject_date);

			$this->db->distinct('venue_code');
			if( $this->session->userdata('examcode') == 996 || $this->session->userdata('examcode') == 994  || $this->session->userdata('examcode') == 1055 || $this->session->userdata('examcode') == 1056  || $this->session->userdata('examcode') == 1015 || $this->session->userdata('examcode') == 1026 || $this->session->userdata('examcode') == 1027 || $this->session->userdata('examcode') == 1029 || $this->session->userdata('examcode') == 1028|| $this->session->userdata('examcode') == 1005 || $this->session->userdata('examcode') == 1009|| $this->session->userdata('examcode') == 1030|| $this->session->userdata('examcode') == 1033 || $this->session->userdata('examcode') == 1034 || $this->session->userdata('examcode') == 1006 || $this->session->userdata('examcode') == 1007 || $this->session->userdata('examcode') == 1002 || $this->session->userdata('examcode') == 1008 || $this->session->userdata('examcode') == 1013){

				/*START: Skip Institute Code Condition for exam code present in skippedAdmitCardForExams array Added on 09 Dec 2025 by Anil S*/
				if(!in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams')))
				{
					$this->db->where('institute_code',$this->session->userdata('institute_id'));
				}
				/*END: Skip Institute Code Condition for exam code present in skippedAdmitCardForExams array Added on 09 Dec 2025 by Anil S*/
				
			}
			$getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
			//echo $this->db->last_query();
			// die;

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

						$venue_add_finalstring = str_replace(',','',$venue_add_finalstring);
						$venue_option.='<option value='.$row['venue_code'].' title="'.$venue_add_finalstring.''.$pwd_enable.'">'.substr($venue_add_finalstring,0,39).''.$pwd_enable.'.</option>';

				}

			}

		}

		$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option,'skippedAdmitCardForExams'=>$skippedAdmitCardForExams);		

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

		$skippedAdmitCardForExams = 0;

		$date_option='<option value="">Select Date</option>';

		$time_option='<option value="">Select Time</option>';

		if(in_array($examcode,$this->config->item('skippedAdmitCardForExams'))) {
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
			//echo $this->db->last_query();
			

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

			//print_r($get_expired_date);die;
			
			if($eprid!='')

			{

				$this->db->where('exam_period',$eprid);

			}
			$this->db->order_by('subject_master.exam_date','ASC');
			$getdate_details=$this->master_model->getRecords('subject_master',array('exam_code'=>$examcode,'subject_code'=>$subject_code),'exam_date');

			

			if(count($getdate_details) > 0)

			{

				foreach($getdate_details as $daterow)

				{	

						$date_option.='<option value='.$daterow['exam_date'].'>'.date('d-M-Y',strtotime($daterow['exam_date'])).'</option>';

				}

			}

		}

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

		$examCode= $_POST['examcode'];
		
		$skippedAdmitCardForExams =0;


		if(in_array($examCode,$this->config->item('skippedAdmitCardForExams'))) {
			$skippedAdmitCardForExams = 1;
		}

		if($centerCode!="" && $venue_code!="" && $date_code!="")

		{

			$this->db->distinct('session_time');
			if($this->session->userdata('examcode') == 996 || $this->session->userdata('examcode') == 1055 || $this->session->userdata('examcode') == 1027 || $this->session->userdata('examcode') == 1029  || $this->session->userdata('examcode') == 1028  || $this->session->userdata('examcode') == 1008){
				$this->db->where('institute_code',$this->session->userdata('institute_id'));
			}
			$gettime_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode,'venue_code'=>$venue_code,'exam_date'=>$date_code),'session_time');
			//echo $this->db->last_query();
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

		$examcode = $_POST['examcode'];

		$skippedAdmitCardForExams =0;

		$capacity = get_capacity_bulk($venue_code,$date_code,$time,$centerCode);
		
		if(in_array($examcode,$this->config->item('skippedAdmitCardForExams'))) {
			$skippedAdmitCardForExams = 1;
			
		}
		
		$data_arr=array('capacity'=>$capacity,'skippedAdmitCardForExams'=>$skippedAdmitCardForExams);		

		echo json_encode($data_arr);

	}

	

	

}



