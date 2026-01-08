<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CSCVenue extends CI_Controller {

	public function __construct()

	{

		parent::__construct();

		$this->load->model('master_model');		

		$this->load->model('log_model');

		$this->load->helper('master_helper');

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

		if($centerCode!="" && $examCode!='')

		{

					$i=1;

					$query='(exam_date = "0000-00-00" OR exam_date = "")';

					$this->db->where($query);

					if($this->session->userdata['csc_venue_flag'] == 'F'){

						$this->db->where('venue_flag','F');

					}elseif($this->session->userdata['csc_venue_flag'] == 'P'){

						$this->db->where('venue_flag','P');

					}

					$this->db->where('session_time=','');
					if(isset($_POST['selection_csc_nseit']) && $_POST['selection_csc_nseit']!='') {
						$this->db->where('vendor',$_POST['selection_csc_nseit']);
					}

					$this->db->group_by('venue_code');

					$getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
					// echo $this->db->last_query(),'</br>';

					// echo $getvenue_details; exit;

					if(count($getvenue_details) > 0)

					{

						$venue_option='<option value="">Select Venue</option>';

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

						$venue_arr['venue_option_'.$i]=$venue_option;

					}

					else

					{

						$venue_option='<option value="">Select Venue</option>';

						$venue_arr['venue_option_'.$i]=$venue_option;

					}

					$i++;

				

		}

		$other_option_arr=array('date_option'=>$date_option,'time_option'=>$time_option);

		//$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option);		

		$data_arr=array_merge($venue_arr,$other_option_arr);

		echo json_encode($data_arr);

	}

	
	public function getCustomCSCVenue()
	{

		$centerCode= $_POST['centerCode'];

		$examCode= $_POST['examCode'];

		$subject_array=$_POST['subject_array'];

		$venue_option='<option value="">Select Venue</option>';

		$date_option='<option value="">Select Date</option>';

		$time_option='<option value="">Select Time</option>';

		$subject_date=$venue_arr=array();

		if($centerCode!="" && $examCode!='')

		{

					$i=1;

					$query='(exam_date = "0000-00-00" OR exam_date = "")';

					$this->db->where($query);

					if($this->session->userdata['csc_venue_flag'] == 'F'){

						$this->db->where('venue_flag','F');

					}elseif($this->session->userdata['csc_venue_flag'] == 'P'){

						$this->db->where('venue_flag','P');

					}

					$this->db->where('session_time=','');
					if(isset($_POST['selection_csc_nseit']) && $_POST['selection_csc_nseit']!='') {
						$this->db->where('vendor',$_POST['selection_csc_nseit']);
					}

					$this->db->group_by('venue_code');

					$getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
					// echo $this->db->last_query(),'</br>';

					// echo $getvenue_details; exit;

					if(count($getvenue_details) > 0)

					{

						$venue_option='<option value="">Select Venue</option>';

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

						$venue_arr['venue_option_'.$i]=$venue_option;

					}

					else

					{

						$venue_option='<option value="">Select Venue</option>';

						$venue_arr['venue_option_'.$i]=$venue_option;

					}

					$i++;

				

		}

		$other_option_arr=array('date_option'=>$date_option,'time_option'=>$time_option);

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

			if($this->session->userdata['csc_venue_flag'] == 'F'){

				$this->db->where('venue_flag','F');

			}elseif($this->session->userdata['csc_venue_flag'] == 'P'){

				$this->db->where('venue_flag','P');

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

		$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option);		

		//$data_arr=array_merge($venue_arr,$other_option_arr);

		echo json_encode($data_arr);

	}

	

	

	

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

		if($eprid!="" && $examcode!="" && $subject_code!="" && $venue_code!="")

		{

			$today_date=date('Y-m-d');

			

			$free_date_arr = array('2020-10-12','2020-10-13','2020-10-14','2020-10-15','2020-10-16','2020-10-19','2020-10-20','2020-10-21','2020-10-22','2020-10-23');

			if($this->session->userdata['csc_venue_flag'] == 'F'){

				$this->db->where_in('exam_date',$free_date_arr);

			}
 
			

			//$today_date='2019-06-03';

			$this->db->where("'$today_date' < exam_date");

			$this->db->distinct('exam_date');

			$this->db->order_by('exam_date','ASC');

			if($this->session->userdata['csc_venue_flag'] != 'F'){

			//$this->db->limit('100','4');
			$this->db->limit('100','2'); // As discussed with client changed cooling period upto 2 days from current date at 15 Nov 2025

			}
			if(isset($_POST['selection_csc_nseit']) && $_POST['selection_csc_nseit']=='nseit') {
				$getdate_details=$this->master_model->getRecords('nseit_ippb_exam_dates','','exam_date');

				if(count($getdate_details) > 0)

				{
					
					foreach($getdate_details as $daterow)

					{	
						$date_option.='<option value='.$daterow['exam_date'].'>'.date('d-m-Y',strtotime($daterow['exam_date'])).'</option>';
					}

				}

			}
			else {

				$getdate_details=$this->master_model->getRecords('csc_exam_dates','','exam_date');
				//echo $this->db->last_query();exit;
				//echo $this->db->last_query(),'</br>';

				if(count($getdate_details) > 0)

				{
					$disabledDates= getcentrenonavailability($venue_code); //priyanka d- added weekly off for csc,ippb - 15-may-23
				
					foreach($getdate_details as $daterow)

					{	
						$disableIt=0;
						if(in_array($daterow['exam_date'],$disabledDates['disabledDates'])) {
							$disableIt=1;
						}
						//echo $daterow['exam_date'].'=='.date('l', strtotime($daterow['exam_date'])).'='.$disabledDates['weeklyOff'];
						if($disabledDates['weeklyOff']!='' && date('l', strtotime($daterow['exam_date']))==$disabledDates['weeklyOff']) {
							$disableIt=1;
						}
						
						if($disableIt==1) {

							$date_option.='<option disabled value='.$daterow['exam_date'].'>'.date('d-m-Y',strtotime($daterow['exam_date'])).'  (Closed)</option>';
						}
						else {
							$date_option.='<option value='.$daterow['exam_date'].'>'.date('d-m-Y',strtotime($daterow['exam_date'])).'</option>';
					
						}
					}

				}
			}

		}
		//echo 'xxx'.$daterow['exam_date'];die;
		$data_arr=array('date_option'=>$date_option,'time_option'=>$time_option);		

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

			$this->db->order_by("STR_TO_DATE(`session_time`,'%h.%i%p')",'');

			if(isset($_POST['selection_csc_nseit']) && $_POST['selection_csc_nseit']=='nseit') {

				$gettime_details=$this->master_model->getRecords('nseit_ippb_exam_dates',array('exam_date'=>$date_code),'session_time');
				
			}
			else {
			$gettime_details=$this->master_model->getRecords('csc_exam_dates',array('exam_date'=>$date_code),'session_time');

			}

			if(count($gettime_details) > 0)

			{

				foreach($gettime_details as $timerow)

				{	

						$session_time=$timerow['session_time'];

						$disable=$capacity_msg='';

						$capacity=csc_check_capacity($venue_code,$date_code,$session_time,$centerCode);

						//echo $this->db->last_query(),'</br>';

						

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

		$selection_csc_nseit = '';

		if(isset($_POST['selection_csc_nseit']) && $_POST['selection_csc_nseit']!='') {
			$selection_csc_nseit=$_POST['selection_csc_nseit'];
		}

		$data_arr=array('capacity'=>csc_get_capacity($venue_code,$date_code,$time,$centerCode,$selection_csc_nseit));		

		echo json_encode($data_arr);

	}

	
	
	//priyanka d- 31-may-23 >> added this function to get centers by csc or nseit 
	public function getCenterBycscOrnseit() {
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',997);
		$this->db->where("center_delete",'0');
		$this->db->where("center_master.center_code !=",751);

		if(isset($_POST['selection_csc_nseit']) && $_POST['selection_csc_nseit']!='')
			$this->db->where("center_master.vendor",$_POST['selection_csc_nseit']);

		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));

		$html='<option value="">Select</option>';
		if(count($center) > 0) {
			foreach($center as $crow) {
				$html.='<option value="'.$crow['center_code'].'" class='.$crow['exammode'].'>'.$crow['center_name'].'</option>';
			}
		}
		echo $html;
	}

	public function saveFormAsDraft() {

					$draft_data['regnumber'] = $_POST['emp_id'];
					$draft_data['ex_cd'] = $_POST['examcode'];
					//echo'<pre>';print_r($_POST['form_data']);exit;
					foreach($_POST['form_data'] as $key=>$form_data) {
						if(strlen($form_data) > 35 && $key!='exname') {
							echo 'N';
							return false;
						}
					}
					if(isset($_POST['hiddenphoto']) && !empty($_POST['hiddenphoto'])) {

						$drafthiddenphoto=$this->stringToImg($_POST['hiddenphoto'],'./uploads/saveddraft/photograph/','non_mem_photo_',20480);

						if($drafthiddenphoto==false) {
							echo 'F';
							return false;
						}
						$_POST['form_data']['drafthiddenphoto'] = $drafthiddenphoto;

					}
					if(isset($_POST['hiddenscansignature']) && !empty($_POST['hiddenscansignature'])) {

						$drafthiddenscansignature=$this->stringToImg($_POST['hiddenscansignature'],'./uploads/saveddraft/scansignature/','non_mem_sign_',20480);

						if($drafthiddenscansignature==false) {
							echo 'F';
							return false;
						}
						$_POST['form_data']['drafthiddenscansignature'] = $drafthiddenscansignature;

					}
					if(isset($_POST['hiddenidproofphoto']) && !empty($_POST['hiddenidproofphoto'])) {

						$drafthiddenidproofphoto=$this->stringToImg($_POST['hiddenidproofphoto'],'./uploads/saveddraft/idproof/','non_mem_idproof_',305000);

						if($drafthiddenidproofphoto==false) {
							echo 'F';
							return false;
						}
						$_POST['form_data']['drafthiddenidproofphoto'] = $drafthiddenidproofphoto;

					}
					
					$draft_data['form_data'] = json_encode($_POST['form_data']);

					$this->db->where("ex_cd",$_POST['examcode']);
					$this->db->where("regnumber",$_POST['emp_id']);
					$draftData=$this->master_model->getRecords('apply_exam_draft_data');

					if(count($draftData)>0) {
						$this->db->where('id', $draftData[0]['id']);
						$this->db->update('apply_exam_draft_data', $draft_data);
						
					}
					else
						$this->db->insert('apply_exam_draft_data', $draft_data);
					echo 'Y';
	}
	public function stringToImg($data,$path,$filenm,$filesize) { //priyanka d >> save as draft files >> 19 june-23
		
	
			$string_pieces = explode( ";base64,", $data);
	
			/*@ Get type of image ex. png, jpg, etc. */
			// $image_type[1] will return type
			$image_type_pieces = explode( "image/", $string_pieces[0] );
	
			$image_type = $image_type_pieces[1];
			
			if (!in_array($image_type, [ 'jpg', 'jpeg' ])) {
				
				return false;
			}
			//$data = str_replace( ' ', '+', $data );
			$data = base64_decode($string_pieces[1] );
		
			if ($data === false) {
				
				return false;
			}
		
		$tmp_nm = $filenm.strtotime(date('Y-m-d h:i:s')).rand(0,100);
		$filename=$path."{$tmp_nm}.{$image_type}";
		file_put_contents($filename, $data);
	//	echo filesize($filename).'==========';return true;
		if(filesize($filename)>$filesize) {
			
			return false;
		}//unlink($filename);
		
		return $tmp_nm.'.'.$image_type;
	}
	public function getFormAsDraft() {
		
		$this->db->where("ex_cd",$_POST['examcode']);
		$this->db->where("regnumber",$_POST['emp_id']);
		$draftData=$this->master_model->getRecords('apply_exam_draft_data');

		
		if(count($draftData)>0) {
			$draftData	=	$draftData[0];
			$draftData['form_data']=json_decode($draftData['form_data']);
			echo json_encode(array('draftData'=>$draftData,'dataFound'=>'Y'));
		}
		else
		echo json_encode(array('dataFound'=>'N'));
	}
	
	

}







