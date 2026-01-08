<?php
 /*Controller class Inspector Master.
  * @copyright    Copyright (c) 2018 ESDS Software Solution Private.
  * @author       Aayusha Kapadni 
  * @package      Controller
  * @updated      2019-06-24 by Manoj 
  */

defined('BASEPATH') OR exit('No direct script access allowed');
class InspectionSummary extends CI_Controller 
{
	public $UserID;
	public $UserData;				
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/Version_2/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
		$this->UserID   = $this->UserData['id'];
		$this->UserName = $this->UserData['name'];	
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
	}
	
	public function index()
	{	
		$DRA_Version_2_instId    = $this->config->item('DRA_Version_2_instId');
		$DRA_Version_2_instIdStr = implode(',',$DRA_Version_2_instId);

		$batchQry = $this->db->query("SELECT a.institute_name, a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.id, b.agency_id, b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name, f1.faculty_photo as first_faculty_photo, f1.faculty_code as first_faculty_code, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_photo as sec_faculty_photo, f2.faculty_code as sec_faculty_code, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code, f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code, f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.created_on
	        FROM agency_batch b 
	        LEFT JOIN agency_inspector_master ai ON b.inspector_id = ai.id
	        LEFT JOIN  dra_batch_inspection bi ON b.id = bi.batch_id
	        LEFT JOIN  dra_accerdited_master a ON b.agency_id = a.dra_inst_registration_id 
	        LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
	        LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
        	LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
        	LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        AND   b.is_deleted = 0
	        AND   b.agency_id IN (".$DRA_Version_2_instIdStr.")
	        -- below line comment by gaurav shewale as per client requirment check email subject (Inspection Report of Cancelled Batch BC564 not visible over DRA Module) 
	        -- AND  EXISTS (SELECT i.batch_id FROM dra_batch_inspection i WHERE b.id = i.batch_id)
	        GROUP BY b.id ORDER BY b.id DESC");

		$data['batch'] = $batch = $batchQry->result_array();
		// echo "<pre>"; print_r($data); exit;
		//$_SESSION['InspSummaryBatchIdSession'] = '';
		//$data['middle_content'] = 'inspecton_summary';
		$this->load->view('iibfdra/Version_2/admin/masters/inspection_summary',$data);
	}


    public function get_candidate_data()
	{
		$batch_id = $_POST['batch_id'];
		//$_SESSION['InspSummaryBatchIdSession'] = $batch_id;

		$query1 = "SELECT * FROM dra_batch_inspection WHERE batch_id = ".$batch_id; //ACTUAL QUERY

		$result1 = $this->db->query($query1);  
		$batch_insp = $result1->result_array();
		$width = '30%';
		$str = '';

		$str.='<tr>';
		$str.='<td style="min-width:80px;"><strong>Sr</strong></td>';
		$str.='<td style="min-width:150px;"><strong>Title</strong></td>';
		// print_r($batch_insp); exit;
		foreach($batch_insp as $key => $batch){
			$str.='<td style="min-width:150px;"><strong>Inspection No:'.$batch['inspection_no'].'</strong></td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="10%"><strong>Inspection Start Date/Time</strong></td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td><strong>'.$batch['inspection_start_time'].'</strong></td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="10%"><strong>Inspection End Date/Time</strong></td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td><strong>'.$batch['created_on'].'</strong></td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="5%"><strong>Inspection Name/ID</strong></td>';
		foreach($batch_insp as $key => $batch) {
			$insp=$this->master_model->getRecords('agency_inspector_master',array('id'=>$batch['inspector_id']));
			$str.='<td><strong>'.$insp[0]['inspector_name'].'/ '.$insp[0]['id'].'</strong></td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">1</td>';
		$str.='<td width="'.$width.'">Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['candidates_loggedin'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">2</td>';
		$str.='<td width="'.$width.'">Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['platform_name'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">3</td>';
		$str.='<td width="'.$width.'">Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['multiple_login_same_name'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">4</td>';
		$str.='<td width="'.$width.'">Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['instrument_name'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">5</td>';
		$str.='<td width="'.$width.'">Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['issues'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">6</td>';
		$str.='<td width="'.$width.'">Whether virtual recording is ‘On’ or “not On” or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['training_session'].'</td>';
		}
		$str.='</tr>';

		/*$str.='<tr>';
		$str.='<td width="3%">7</td>';
		$str.='<td width="'.$width.'">Number of candidates connected/login to the platform on start of inspection</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['candidates_connected'].'</td>';
		}
		$str.='</tr>';*/

		$str.='<tr>';
		$str.='<td width="3%">7</td>';
		$str.='<td width="'.$width.'">Training Details</td>';
		$str.='<td></td>';
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">(i) No. of candidates available during training sessions</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['session_candidates'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['training_session_plan'].'</td>';
		}
		$str.='</tr>';

		/*$str.='<tr>';
		$str.='<td width="3%">9</td>';
		$str.='<td width="'.$width.'">Whether Name of Batch Coordinator is displayed on the platform (Yes - enter the relevant information / No)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['actual_batch_coordinator'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">10</td>';
		$str.='<td width="'.$width.'">Coordinator is same as allotted or not (Yes/ No) if not mention the name of the co-ordinator</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['diff_batch_coordinator'].'</td>';
		}
		$str.='</tr>';*/

		$str.='<tr>';
		$str.='<td width="3%">8</td>';
		$str.='<td width="'.$width.'">Attendance</td>';
		$str.='<td></td>';
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['attendance_sheet_updated'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['attendance_mode'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['attendance_shown'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">9</td>';
		$str.='<td width="'.$width.'">Is there any group of candidates attending the sessions through a single device? (loptop/Mobile/PC/Big screen/monitor)
                    please mention the candidate count and device)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['candidate_count_device'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">10</td>';
		$str.='<td width="'.$width.'">Faculty Details</td>';
		$str.='<td></td>';
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['actual_faculty'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">b) Name / Code of Faculty taking session</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['faculty_taking_session'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">c) If the Faculty who is taking session is different from the declared one, please mention:
	         <br>i. Name and Qualification (highest) of the Faculty</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['name_qualification'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">ii. No. of days / sessions she/he has taken / will take</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['no_of_days'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">iii. Reason of such change in faculty</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['reason_of_change_in_faculty'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['experience_teaching_training_BFSI_sector'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">d) Language in which the Faculty is taking the session</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['faculty_language'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">e) The Faculty is taking sessions for how many hrs/min per day</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['faculty_session_time'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">f) Whether there are minimum 2 faculties are taking sessions to complete the 50 / 100 hours training.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['two_faculty_taking_session'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['faculty_language_understandable'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['whiteboard_ppt_pdf_used'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">11</td>';
		$str.='<td width="'.$width.'">Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming DRA training (Yes / No).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['session_on_etiquettes'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">12</td>';
		$str.='<td width="'.$width.'">Whether the faculty and trainees were conversant with the process of on-line training.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['faculty_trainees_conversant'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">13</td>';
		$str.='<td width="'.$width.'">Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['candidates_recognise'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">14</td>';
		$str.='<td width="'.$width.'">Whether candidates were given "Handbook on debt recovery" by the concerned agency.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['handbook_on_debt_recovery'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">15</td>';
		$str.='<td width="'.$width.'">Whether candidates are provided with other study materials in word/pdf format by the agency).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['other_study_materials'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">16</td>';
		$str.='<td width="'.$width.'">Whether the training was conducted without any interruption/ disturbances/ noises?</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['training_conduction'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">17</td>';
		$str.='<td width="'.$width.'">Batch Coordinator</td>';
		$str.='<td></td>';
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['batch_coordinator_available'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">b) Name / Code of the Coordinator is available in the Session</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['coordinator_available_name'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['current_coordinator_available_name'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">18</td>';
		$str.='<td width="'.$width.'">Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification</td>';
		// print_r($batch_insp); exit;
		foreach($batch_insp as $key => $batch)
		{
			if (trim($batch['any_irregularity']) == '---' || trim($batch['any_irregularity']) == '----' || trim($batch['any_irregularity']) == '-----') 
			{
				$str.='<td> </td>';	
			} 
			else
			{
				$str.='<td>'.$batch['any_irregularity'].'</td>';	
			}
		}	
		
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">19</td>';
		$str.='<td width="'.$width.'">Assessment / rating (viz. 1-Poor / 2-Average / 3-Good / 4-Excellent) consequent to overall impression during visit to the virtual training session</td>';
		$str.='<td></td>';
		/*foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['assessment'].'</td>';
		}*/
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">a) Quality of Teaching:
           <br>i. Level of interaction with candidates
           </td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['teaching_quality_interaction_with_candidates'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">
           ii. Understanding with curiosity while teaching (especially  during soft-skill session)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['teaching_quality_softskill_session'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">b) Candidates attentiveness and participation</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['candidates_attentiveness'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">c) Candidates Attitude and their Behaviour</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['DRA_attitude_behaviour'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">d) Quality of learning by DRAs:
                <br>i.  Interaction with Faculty</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['learning_quality_interaction_with_faculty'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">
                ii. Response to queries made by faculty / inspector </td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['learning_quality_response_to_queries'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">e) Effectiveness of training</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['teaching_effectiveness'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">f) Curriculum covered with reference to the Syllabus</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['curriculum_covered'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">g) Overall compliance on:
                i.  Training delivery</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['overall_compliance_training_delivery'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%"></td>';
		$str.='<td width="'.$width.'">
                ii. Training coordination</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['overall_compliance_training_coordination'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">20</td>';
		$str.='<td width="'.$width.'">Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line DRA Training</td>';
		foreach($batch_insp as $key => $batch){

			if (trim($batch['other_observations']) == '---' || trim($batch['other_observations']) == '----' || trim($batch['other_observations']) == '-----') 
			{
				$str.='<td></td>';	
			} 
			else
			{
				$str.='<td>'.$batch['other_observations'].'</td>';	
			}
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">21</td>';
		$str.='<td width="'.$width.'">Overall Observation of the Inspector on the training of the DRA Batch</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['overall_observation'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">22</td>';
		$str.='<td width="'.$width.'">Over all compliance</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['overall_compliance'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%">23</td>';
		$str.='<td width="'.$width.'">Attachment</td>';
		foreach($batch_insp as $key => $batch){
			if(!empty($batch['attachment'])){
				$str.='<td><a href="'.base_url('uploads/inspection_report/'.$batch['attachment']).'" target="_blank">View</a></td>';
			}
			else{
				$str.='<td></td>';
			}
			
			//$str.='<td></td>';
		}
		$str.='</tr>';

		$agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code
	        FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
	        WHERE agency_batch.id=".$batch_id);

		$agency_data = $agency->result_array();

		$query = "SELECT m.regid, m.training_id, concat(m.namesub, ' ', m.firstname, ' ', m.middlename, ' ', m.lastname) as name, m.dateofbirth, m.mobile_no, m.scannedphoto,m.quali_certificate,m.idproofphoto,
			SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
			SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt,
			GROUP_CONCAT(DISTINCT c.remark SEPARATOR '| ') remark, m.hold_release,
			TRIM(BOTH '| ' FROM GROUP_CONCAT(c.qualification_verify SEPARATOR '| ')) AS qualification_verify,
      		TRIM(BOTH '| ' FROM GROUP_CONCAT(c.qualification_remark SEPARATOR '| ')) AS qualification_remark,
      		TRIM(BOTH '| ' FROM GROUP_CONCAT(c.photo_verify SEPARATOR '| ')) AS photo_verify,
      		TRIM(BOTH '| ' FROM GROUP_CONCAT(c.photo_remark SEPARATOR '| ')) AS photo_remark
			FROM dra_members m LEFT JOIN dra_candidate_inspection c ON m.regid = c.candidate_id  
			WHERE m.batch_id = ".$batch_id."
			AND inst_code = ".$agency_data[0]['institute_code']."
			GROUP BY m.regid"; //ORDER BY m.regid DESC

		$result = $this->db->query($query);  
		$batch = $result->result_array();


		$inspe_query = "SELECT 
			DATE_FORMAT(c.created_on, '%d-%b-%Y %H:%i:%s') AS date_time,
			SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
			SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt
			FROM dra_candidate_inspection c  
			WHERE c.batch_id = ".$batch_id."
			GROUP BY c.batch_inspection_id"; //ORDER BY m.regid DESC
			
		$inspe_result = $this->db->query($inspe_query);  
		$inspe_batch  = $inspe_result->result_array();

		// echo "<pre>"; print_r($inspe_batch); exit;

		foreach ($batch as $key => $value) 
		{
			$rmrk = explode('| ', $value['remark']);
			$remark1 = '';
			foreach ($rmrk as $key1 => $val1) {
				if($val1 != ''){
					$a = $key1+1;
					$remark1.=$a.') '.$val1.'<br>';
				}
			}
			$batch[$key]['remark'] = $remark1;

			$qualification_rmrk = explode('| ', $value['qualification_remark']);
			$qualification_remark1 = '';
			 
			foreach ($qualification_rmrk as $key2 => $val2)
			{
				if ($val2 != '')
				{
				  $b = $key2 + 1;
				  $qualification_remark1 .= $b . ') ' . $val2 . '<br>';
				}
			}
			$batch[$key]['qualification_remark'] = $qualification_remark1;


			$photo_rmrk = explode('| ', $value['photo_remark']);
			$photo_remark1 = '';
			//print_r($rmrk);
			foreach ($photo_rmrk as $key7 => $val7)
			{
				if ($val7 != '')
				{
				  $y = $key7 + 1;
				  $photo_remark1 .= $y . ') ' . $val7 . '<br>';
				}
			}
			$batch[$key]['photo_remark'] = $photo_remark1;

			$qualification_verify = explode('| ', $value['qualification_verify']);
			$qualification_verify1 = '';
			//print_r($rmrk);
			foreach ($qualification_verify as $key3 => $val3)
			{
				if ($val3 != '')
				{
				  $c = $key3 + 1;
				  $qualification_verify1 .= $c . ') ' . $val3 . '<br>';
				}
			}
			$batch[$key]['qualification_verify'] = $qualification_verify1;

			$photo_verify = explode('| ', $value['photo_verify']);
			$photo_verify1 = '';
			//print_r($rmrk);
			foreach ($photo_verify as $key8 => $val8)
			{
				if ($val8 != '')
				{
				  $x = $key8 + 1;
				  $photo_verify1 .= $x . ') ' . $val8 . '<br>';
				}
			}
			$batch[$key]['photo_verify'] = $photo_verify1;
		}
		
		// $batch['inspection_cnt'] = $inspe_batch;

		$batch_login_details = $this->master_model->getRecords('agency_online_batch_user_details',array('batch_id'=>$batch_id));

		$str1 = '';
		$str1.='<table border="solid 1%">';
        $str1.='<thead>';
        $str1.='<tr>';
        $str1.='<th width="5%">Login Id</th>';
        $str1.='<th width="5%">Password</th>';
        $str1.='</tr>';
        $str1.='</thead>';
        $str1.='<tbody>';
        foreach ($batch_login_details as $key => $value) {
        	$str1.='<tr>';
        	$str1.='<td width="5%">'.$value['login_id'].'</td>';
        	$str1.='<td width="5%">'.base64_decode($value['password']).'</td>';
        	$str1.='</tr>';
        }
        $str1.='</tbody>';
        $str1.='</table>';
		
		echo $str.'~~~~'.json_encode($batch).'~~~~'.$str1.'~~~~'.json_encode($inspe_batch);
	}

	public function candidate_hold()
	{
		$candidates_checked_array = $_POST['candidates_checked_array'];
		$status = $_POST['status'];
		$batch_id = $_POST['batch_id'];
		if(count($candidates_checked_array)){
			for($i=0;$i<count($candidates_checked_array);$i++){
				$updArr = array('hold_release'=>$status);
				$where  = array('regid'=>$candidates_checked_array[$i]);
				$upd_id = $this->master_model->updateRecord('dra_members',$updArr,$where);

				if ($upd_id) 
				{
					$add_cand_log['action']          = 'Update';   
					$add_cand_log['form_type']       = 'form3';               
					$add_cand_log['candidate_id']    = $candidates_checked_array[$i];
					$add_cand_log['log_title']       = 'The candidate is put on '.$status.' by '.$this->UserName;
					$add_cand_log['log_decription']  = '';
					$add_cand_log['status']          = 'success';
					$add_cand_log['is_read']         = '0';
					$add_cand_log['created_by_type'] = 'inspector';
					$add_cand_log['created_by']      = $this->UserID;
					$add_cand_log['created_on']      = date("Y-m-d H:i:s");
					$this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);	
				}
			}
		}

		//$this->session->set_userdata('session_batch_id',$batch_id);
		$agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code
	        FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
	        WHERE agency_batch.id=".$batch_id);

		$agency_data = $agency->result_array();

		$query = "SELECT m.regid, m.training_id, concat(m.namesub, ' ', m.firstname, ' ', m.middlename, ' ', m.lastname) as name, m.dateofbirth, m.mobile_no, m.scannedphoto,m.quali_certificate,
			SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
			SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt,
			GROUP_CONCAT(DISTINCT c.remark SEPARATOR '| ') remark, m.hold_release,
			TRIM(BOTH '| ' FROM GROUP_CONCAT(c.qualification_verify SEPARATOR '| ')) AS qualification_verify,
      		TRIM(BOTH '| ' FROM GROUP_CONCAT(c.qualification_remark SEPARATOR '| ')) AS qualification_remark
			FROM dra_members m LEFT JOIN dra_candidate_inspection c ON m.regid = c.candidate_id  
			WHERE m.batch_id = ".$batch_id."
			AND inst_code = ".$agency_data[0]['institute_code']."
			GROUP BY m.regid"; //ORDER BY m.regid DESC

		$result = $this->db->query($query);  
		$batch = $result->result_array();
		
		foreach ($batch as $key => $value) {
			$rmrk = explode('| ', $value['remark']);
			$remark1 = '';
			foreach ($rmrk as $key1 => $val1) {
				if($val1 != ''){
					$a = $key1+1;
					$remark1.=$a.') '.$val1.'<br>';
				}
			}
			$batch[$key]['remark'] = $remark1;
		}
		
		echo $upd_id.'---'.json_encode($batch);
		exit(0);
	}

	public function export_to_pdf()
	{	
		$batch_id = $_POST['batch_id'];

		$this->db->select('a.institute_name, b.id, b.agency_id, b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_name as first_faculty_name, f2.faculty_name as sec_faculty_name, f3.faculty_name as add_first_faculty_name, f4.faculty_name as add_sec_faculty_name, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag');
		$where = array('b.batch_status' => 'Approved', 'b.is_deleted' => 0, 'b.created_on LIKE' => '%2023%');
		$this->db->order_by('b.id', 'DESC');
		$this->db->join('dra_accerdited_master a','b.agency_id = a.dra_inst_registration_id','left');
		$this->db->join('faculty_master f1','b.first_faculty=f1.faculty_id','left');
		$this->db->join('faculty_master f2','b.sec_faculty=f2.faculty_id','left');
		$this->db->join('faculty_master f3','b.additional_first_faculty=f3.faculty_id','left');
		$this->db->join('faculty_master f4','b.additional_sec_faculty=f4.faculty_id','left');

		$where1 = array('b.batch_status' => 'Approved', 'b.is_deleted' => 0,'b.id'=>$batch_id);
		$batch = $this->master_model->getRecords('agency_batch b', $where1);

		$data['batch_data'] = $batch[0];
		
		$query1 = $this->db->query("SELECT * FROM dra_batch_inspection WHERE batch_id = ".$batch_id); //ACTUAL QUERY

		$data['batch_insp'] = $batch_insp = $query1->result_array();

		$agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code, institute_name
	        FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
	        WHERE agency_batch.id=".$batch_id);

		$data['agency_data'] = $agency_data = $agency->result_array();

		$candidateQry = $this->db->query("SELECT m.regid, m.training_id, concat(m.namesub, ' ', m.firstname, ' ', m.middlename, ' ', m.lastname) as name, m.dateofbirth, m.mobile_no, m.scannedphoto,
			SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
			SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt,
			GROUP_CONCAT(DISTINCT c.remark SEPARATOR '| ') remark
			FROM dra_members m LEFT JOIN dra_candidate_inspection c ON m.regid = c.candidate_id  
			WHERE m.batch_id = ".$batch_id."
			AND inst_code = ".$agency_data[0]['institute_code']."
			GROUP BY m.regid
			ORDER BY m.regid DESC"); //ACTUAL QUERY

		$batch = $candidateQry->result_array();
		
		foreach ($batch as $key => $value) {
			$rmrk = explode('| ', $value['remark']);
			$remark = '';
			foreach ($rmrk as $key1 => $val1) {
				$j = $key1+1;
				if($val1 != ''){
					$remark.=$j.') '.$val1.'<br>';
				}
			}
			$batch[$key]['remark'] = $remark;
		}
		//print_r($batch);
		//echo $remark;die;
		$data['batch_candidates'] = $batch;

		$data['batch_login_details'] = $batch_login_details = $this->master_model->getRecords('agency_online_batch_user_details',array('batch_id'=>$batch_id));

		$html = $this->load->view('iibfdra/Version_2/admin/masters/inspection_summary_pdf',$data,true);
		//echo $html; die;
		$this->load->library('m_pdf');
		$pdf = $this->m_pdf->load();
		$pdfFilePath = 'inspection_summary_pdf_'.$batch1[0]['batch_code'].".pdf";
		//generate the PDF from the given html
		$pdf->WriteHTML($html);
		//download it.
		$pdf->Output($pdfFilePath, "D");  
	}

	public function details($regid)
	{
		$regid = base64_decode($regid);
		//echo $regid;die;

		$query1 = "SELECT c.*, b.inspection_no, i.inspector_name FROM dra_candidate_inspection c LEFT JOIN dra_batch_inspection b ON c.batch_inspection_id = b.id LEFT JOIN agency_inspector_master i ON c.inspector_id = i.id WHERE candidate_id = ".$regid; //ACTUAL QUERY

		$result = $this->db->query($query1);  
		$data['candidate_data'] = $candidate_data = $result->result_array();
		//print_r($candidate_data);die;

		$query2 = "SELECT d.* FROM dra_members d WHERE d.regid = ".$regid; //ACTUAL QUERY

		$result2 = $this->db->query($query2);  
		$status = $result2->result_array();
		$data['hold_release'] = $status[0]['hold_release']; 
		$data['regid'] = $status[0]['regid']; 
		$data['candidate_name'] = $status[0]['namesub'].' '.$status[0]['firstname'].' '.$status[0]['middlename'].' '.$status[0]['lastname']; 
		$data['training_id'] = $status[0]['training_id']; 

		$this->load->view('iibfdra/Version_2/admin/masters/candidate_details',$data);
	}

	public function change_status()
	{
		$status  = $_POST['status'];
		$regid   = $_POST['regid'];
		$reason  = $_POST['reason'];

		if($regid != '' && $status != '')
    	{	
    		$updated_date = date('Y-m-d H:i:s');
			$drauserdata  = $this->session->userdata('dra_admin');

			$this->db->where('dra_members.regid = "'.$regid.'"');	
			$res_candidate = $this->master_model->getRecords('dra_members'); 
			
			if(count($res_candidate) > 0) 
			{	
				$update_data = array(
					'hold_release' => $status,									
					'editedon'	   => $updated_date,
					'editedby'     => $drauserdata['id']	 
				);
				
				$update_status = $this->master_model->updateRecord('dra_members',$update_data,array('regid' => $regid));

				if ($update_status) 
				{
					log_dra_user('DRA Member hold by Agency : '.$regid, serialize($update_data));

					$add_cand_log['action']       = 'Add';                  
		            $add_cand_log['candidate_id'] = $regid;
		            $add_cand_log['log_title']    = 'Candidate '.$status.' by '.$drauserdata['name'];
		            $add_cand_log['reason']          = $reason;
		            $add_cand_log['log_decription']  = serialize($update_data);
		            $add_cand_log['status']          = 'success';
		            $add_cand_log['created_by_type'] = 'admin';
		            $add_cand_log['created_by']      = $this->session->userdata('dra_admin')['id'];
		            $add_cand_log['created_on']      = date("Y-m-d H:i:s");

		            $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);

		            $this->session->set_flashdata('success',"Candidate is on ".$status." successfully." );
					redirect(base_url().'iibfdra/Version_2/admin/inspectionSummary/details/'.base64_encode($regid));
		        }    
		        else 
				{
					$this->session->set_flashdata('error',"Error occured, When ".$status." the candidate." );
					redirect(base_url().'iibfdra/Version_2/admin/inspectionSummary/details/'.base64_encode($regid));
				}
			}
			else
		    {
				$this->session->set_flashdata('error',"Candidate details not found.");
				redirect(base_url().'iibfdra/Version_2/admin/InspectionSummary');	
		    }	
		}
		else
	    {
			$this->session->set_flashdata('error',"Candidate id and status should not be empty." );
			redirect(base_url().'iibfdra/Version_2/admin/inspectionSummary/details/'.base64_encode($regid));	
	    }	
	}

	public function faculty_view($faculty_id){

        $faculty_id = base64_decode($faculty_id);

        $qry = $this->db->query("SELECT faculty_id, institute_id, faculty_number, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_year2, gross_duration_month2, gross_duration_year3, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = ".$faculty_id);

        $data['faculty_data'] = $faculty_data = $qry->result_array();
        //print_r($faculty_data);

        $logqry = $this->db->query("SELECT faculty_id, action_taken, reason, created_on
            FROM faculty_status_logs
            WHERE faculty_id = ".$faculty_id."
            ORDER BY created_on DESC");//AND   action_taken != 'Add'

        $data['log_data'] = $records1 = $logqry->result_array();

        $data['request_from'] = 'Inspector';
        $data['action'] = 'view';
        $data['middle_content'] = 'faculty_add';
        $this->load->view('iibfdra/Version_2/admin/faculty/faculty_view',$data);
    }

}