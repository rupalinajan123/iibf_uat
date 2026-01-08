<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

 	function getseat($exam_code = NULL, $sel_center = NULL, $sel_venue = NULL, $sel_date = NULL, $sel_time = NULL , $ex_prd = NULL , $sel_subject = NULL,$capacity = NULL, $admit_card_id =NULL)
	{
		$flag=$seat_count=0;
		$CI = & get_instance();
		$seat_number=$last_id=$new_seat_cnt='';
		//$CI->load->model('my_model');
		if($exam_code !=NULL && $sel_center !=NULL && $sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $ex_prd !=NULL && $sel_subject != NULL && $capacity != NULL && $admit_card_id!='NULL')
		{
			$CI->db->trans_start();	
			/*$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
		SELECT 1 + IFNULL(max(seat_no), 0) as new_seat_cnt , '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
		FROM seat_allocation
		WHERE exam_code = '.$exam_code.' AND exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'" HAVING new_seat_cnt <= '.$capacity.'' ;*/
		##### check if seat number alredy exist in seat allocation table for admit card id######
		$seat_count=$CI->master_model->getRecords('seat_allocation',array('admit_card_id'=>$admit_card_id));
		if(count($seat_count) <=0)
		{
			//$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
			//SELECT 1 + IFNULL(max(seat_no), 0) as new_seat_cnt, '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
			//FROM seat_allocation
		//	WHERE  exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'"  AND record_source = 0 HAVING new_seat_cnt <= '.$capacity.'' ;
			
			if($exam_code == $CI->config->item('examCodeJaiib') || $exam_code == $CI->config->item('examCodeDBF') || $exam_code == $CI->config->item('examCodeSOB')){
			$get_seat=$CI->db->query('SELECT 1 + COUNT(`id`) as new_seat_cnt, '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
			FROM seat_allocation
			WHERE  exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'"HAVING new_seat_cnt <= '.$capacity.'');
			}else{
				$get_seat=$CI->db->query('SELECT 1 + IFNULL(max(seat_no), 0) as new_seat_cnt, '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
			FROM seat_allocation
			WHERE  exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'"HAVING new_seat_cnt <= '.$capacity.'');
			}
			
			if ($get_seat->num_rows() > 0)
			{
			    $row = $get_seat->row();
				$new_seat_cnt=$row->new_seat_cnt;
			} 

		if($new_seat_cnt!='' && $new_seat_cnt!=0)
		{	
			$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
			VALUES('.$new_seat_cnt.', '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'")' ;
				
				//	echo $CI->db->last_query();exit;
					$CI->db->query($sql);
					if($last_id=$CI->db->insert_id())
					{
						$seat_count=$CI->master_model->getRecords('seat_allocation',array('id'=>$last_id),'seat_no');
						if(count($seat_count) <=0)
						{
							$seat_number='';
							$log_title ="Seat Allocation log 4";
							$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
							$rId = $admit_card_id ;
							$regNo = $admit_card_id;
							$log_data['title'] = $log_title;
							$log_data['description'] = $log_message.'|'.$CI->db->last_query();
							$log_data['regid'] = $rId;
							$log_data['regnumber'] = $regNo;
							$CI->db->insert('userlogs', $log_data);
						}
						else
						{
							$seat_number=$seat_count[0]['seat_no'];
						}
					}
					else
					{
						$log_title ="Seat Allocation log 3";
						$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
						$rId = $admit_card_id ;
						$regNo = $admit_card_id;
						$log_data['title'] = $log_title;
						$log_data['description'] = $log_message.'|'.$CI->db->last_query();;
						$log_data['regid'] = $rId;
						$log_data['regnumber'] = $regNo;
						$CI->db->insert('userlogs', $log_data);
					}
					
					
					if($seat_number=='')
					{
							$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
			VALUES('.$new_seat_cnt.', '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'")' ;
			
							$CI->db->query($sql);	
							if($last_id=$CI->db->insert_id())
							{
								$seat_count=$CI->master_model->getRecords('seat_allocation',array('id'=>$last_id),'seat_no');
								if(count($seat_count) >0)
								{		
									$seat_number=$seat_count[0]['seat_no'];
								}
							}
							$log_title ="Seat Allocation log 5";
							$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
							$rId = $admit_card_id ;
							$regNo = $admit_card_id;
							$log_data['title'] = $log_title;
							$log_data['description'] = $log_message.'|'.$CI->db->last_query();;
							$log_data['regid'] = $rId;
							$log_data['regnumber'] = $regNo;
							$CI->db->insert('userlogs', $log_data);
							//	echo $CI->db->last_query();exit;
					}
				
		}
		else
		{
			$log_title ="Seat Allocation log 6";
			$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
			$rId = $admit_card_id ;
			$regNo = $admit_card_id;
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message.'|'.$CI->db->last_query();
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
			return $seat_number;
		}
		}
		else
		{
			$log_title ="Seat Allocation log 2";
			$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
			$rId = $admit_card_id ;
			$regNo = $admit_card_id;
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message.'|'.$CI->db->last_query();
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
		}
		
		
		$CI->db->trans_complete();
		return $seat_number;
		}
		else
		{
			$log_title ="Seat Allocation log 1";
			$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
			$rId = $admit_card_id ;
			$regNo = $admit_card_id;
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
								
			return $seat_number;
		}
	}
	
	function check_capacity($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL,$sel_center = NULL)
	{
		$seat_flag=1;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
		{
			//$CI->db->join('admit_card_details');
			//$sql='select (SELECT session_capacity FROM `venue_master` WHERE `exam_date` = "'.$sel_date.'" AND `venue_code` = "'.$sel_venue.'" AND `session_time` = "'.$sel_time.'") as session_capacity, (SELECT COUNT(*) FROM admit_card_details WHERE `exam_date` = "'.$sel_date.'" AND `venueid` = "'.$sel_venue.'" AND `time` = "'.$sel_time.'") as admit_card_Count where session_capacity';
		   //$CI->db->query($sql);
		    $CI->db->trans_start();
		 //    $CI->db->where('exm_cd !=','1027');
			// $CI->db->where('exm_cd !=','1029');
			$seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');
			//echo $CI->db->last_query();
			
			if(count($seat_count) > 0)
			{
				$CI->db->join('member_exam','member_exam.id = admit_card_details.mem_exam_id');
				//$CI->db->where("(`remark` = 1 OR `remark` = 2)");
				$CI->db->where('remark','2');
				// $CI->db->where('exm_cd !=','1027');
				// $CI->db->where('exm_cd !=','1029');
				$bulk_admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center,'record_source'=>'bulk','bulk_isdelete'=>'0'));		
				//$CI->db->where('pwd !=','');
				//$CI->db->where('seat_identification !=','');
				//echo 'admit_card_details---'.$CI->db->last_query();
				///$admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center));		
				$admit_card_Count=$CI->master_model->getRecords('seat_allocation',array('date'=>$sel_date,'venue_code'=>$sel_venue,'session'=>$sel_time,'center_code'=>$sel_center));		
				##Total count of regular and bulk capacity
				$total_count=(intval(count($bulk_admit_card_Count)) + intval(count($admit_card_Count)));
				//echo $total_count;
				//echo '$admit_card_Count---'.$CI->db->last_query();
				//echo '---<pre>'.$seat_count[0]['session_capacity'].'----'.($total_count);
				if($seat_count[0]['session_capacity'] <=($total_count))
				{
					$seat_flag=0;
				}
				else if(($total_count > $seat_count[0]['session_capacity']))
				{
					$seat_flag=0;
				}
				/*if(!(count($admit_card_Count) < $seat_count[0]['session_capacity']))
				{
					$seat_flag=0;
				}*/
			}
			$CI->db->trans_complete();
			//echo $CI->db->last_query().'<br>';
			//return $seat_number;
		}
		
		return $seat_flag;
		
	}
	
	###CSC Check Capacity ###
	function csc_check_capacity($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL,$sel_center = NULL,$selection_csc_nseit = NULL) // priyanka d >> added ,$selection_csc_nseit = NULL >>21-july-23
	{
		$seat_flag=1;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
		{
			//$CI->db->join('admit_card_details');
			//$sql='select (SELECT session_capacity FROM `venue_master` WHERE `exam_date` = "'.$sel_date.'" AND `venue_code` = "'.$sel_venue.'" AND `session_time` = "'.$sel_time.'") as session_capacity, (SELECT COUNT(*) FROM admit_card_details WHERE `exam_date` = "'.$sel_date.'" AND `venueid` = "'.$sel_venue.'" AND `time` = "'.$sel_time.'") as admit_card_Count where session_capacity';
		   //$CI->db->query($sql);
		   $CI->db->trans_start();
		   $query='(exam_date = "0000-00-00" OR exam_date = "")';
			$CI->db->where($query);
			$CI->db->where('session_time=','');
			if($CI->session->userdata['csc_venue_flag'] == 'F'){
				$CI->db->where('venue_flag','F');
			}elseif($CI->session->userdata['csc_venue_flag'] == 'P'){
				$CI->db->where('venue_flag','P');
			}
			if($selection_csc_nseit!=NULL) { // priyanka d >> added 21-july-23
				$CI->db->where('vendor',$selection_csc_nseit);
			}
			$seat_count=$CI->master_model->getRecords('venue_master',array('venue_code'=>$sel_venue,'center_code'=>$sel_center),'session_capacity');
			if(count($seat_count) > 0)
			{
				$CI->db->join('member_exam','member_exam.id = admit_card_details.mem_exam_id');
				//$CI->db->where("(`remark` = 1 OR `remark` = 2)");
				$CI->db->where('remark','2');
				$bulk_admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center,'record_source'=>'bulk','bulk_isdelete'=>'0'));		
				//$CI->db->where('pwd !=','');
				//$CI->db->where('seat_identification !=','');
				///$admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center));		
				$admit_card_Count=$CI->master_model->getRecords('seat_allocation',array('date'=>$sel_date,'venue_code'=>$sel_venue,'session'=>$sel_time,'center_code'=>$sel_center));		
				##Total count of regular and bulk capacity
				$total_count=(intval(count($bulk_admit_card_Count)) + intval(count($admit_card_Count)));
				if($seat_count[0]['session_capacity'] <=($total_count))
				{
					$seat_flag=0;
				}
				else if(($total_count > $seat_count[0]['session_capacity']))
				{
					$seat_flag=0;
				}
				/*if(!(count($admit_card_Count) < $seat_count[0]['session_capacity']))
				{
					$seat_flag=0;
				}*/
			}
			$CI->db->trans_complete();
			//echo $CI->db->last_query().'<br>';
			//return $seat_number;
		}
		
		return $seat_flag;
		
	}
	
	function getVenueDetails($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL, $sel_center = NULL )
	{
		$msg='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
		{
			//$CI->db->join('admit_card_details');
			//$sql='select (SELECT session_capacity FROM `venue_master` WHERE `exam_date` = "'.$sel_date.'" AND `venue_code` = "'.$sel_venue.'" AND `session_time` = "'.$sel_time.'") as session_capacity, (SELECT COUNT(*) FROM admit_card_details WHERE `exam_date` = "'.$sel_date.'" AND `venueid` = "'.$sel_venue.'" AND `time` = "'.$sel_time.'") as admit_card_Count where session_capacity';
		   //$CI->db->query($sql);
			$venue_add=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center));
			if(count($venue_add) > 0)
			{
				$full_venue_add=$venue_add[0]['venue_name'].'*'.$venue_add[0]['venue_addr1'].'*'.$venue_add[0]['venue_addr2'].'*'.$venue_add[0]['venue_addr3'].'*'.$venue_add[0]['venue_addr4'].'*'.$venue_add[0]['venue_addr5'].'*'.$venue_add[0]['venue_pincode'];
						$venue_add_finalstring= preg_replace('#[\*]+#', ',', $full_venue_add);
						$msg='Capacity for venue '.$venue_add_finalstring.' on '.date('d-M-Y',strtotime($venue_add[0]['exam_date'])).' for time '.$venue_add[0]['session_time'].' has been full';
						
						//on .'date('d-M-Y',strtotime($daterow['exam_date']))'. for time .'$daterow['session_time']'. has been full; 
			}
			//return $seat_number;
		}
		
		return $msg;
		
	}
	
	#########get venue wise capacity##########
	function get_capacity($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL,$sel_center = NULL,$exam_code=null)
	{
		$seat_capacity='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
		{
		    $CI->db->trans_start();
			$seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');

			if(count($seat_count) > 0)
			{
				/*$CI->db->where('pwd !=','');
				$CI->db->where('seat_identification !=','');
				$admit_card_Count=$CI->master_model->getRecordCount('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center));		*/
				// $CI->db->where('exm_cd !=','1027');
				// $CI->db->where('exm_cd !=','1029');
				$admit_card_Count=$CI->master_model->getRecordCount('seat_allocation',array('date'=>$sel_date,'venue_code'=>$sel_venue,'session'=>$sel_time,'center_code'=>$sel_center));	
				
				$CI->db->join('member_exam','member_exam.id = admit_card_details.mem_exam_id');
				//$CI->db->where("(`remark` = 1 OR `remark` = 2)");
				$CI->db->where('remark','2');
				// $CI->db->where('exm_cd !=','1027');
				// $CI->db->where('exm_cd !=','1029');
				$bulk_admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center,'record_source'=>'bulk','bulk_isdelete'=>'0'));	
				//echo $CI->db->last_query().'<br>';
				//echo '---'.intval(count($bulk_admit_card_Count)).'+++'.intval($admit_card_Count).'!!!'.$seat_count[0]['session_capacity'];
				$total_count=(intval(count($bulk_admit_card_Count)) + intval($admit_card_Count));
				$remain_seat=$seat_count[0]['session_capacity']-$total_count;
				//$seat_capacity=$remain_seat.' out of '.$seat_count[0]['session_capacity'];
				$seat_capacity=$remain_seat;
			}
			$CI->db->trans_complete();
			//echo $CI->db->last_query().'<br>';
			//return $seat_number;
		}

		$skipped_capacity_check_examcodes = $CI->config->item('skippedAdmitCardForExams') ;
		 //priyanka d >>27-dec-24 >> by default selecting venue for jaiib/caiiib as we don't have to create admitcard from filled form now >> exam_cd
		if($exam_code!=null && in_array($exam_code,$skipped_capacity_check_examcodes))
			return 2;
		else {
			return $seat_capacity;
		}
		
		
	}
	
	#########get venue wise capacity##########
	function csc_get_capacity($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL,$sel_center = NULL,$selection_csc_nseit=NULL) // priyanka d >> added ,$selection_csc_nseit=NULL 21 july-23
	{
		$seat_capacity='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
		{
		    $CI->db->trans_start();
			 $query='(exam_date = "0000-00-00" OR exam_date = "")';
			$CI->db->where($query);
			$CI->db->where('session_time=','');
			if($CI->session->userdata['csc_venue_flag'] == 'F'){
				$CI->db->where('venue_flag','F');
			}elseif($CI->session->userdata['csc_venue_flag'] == 'P'){
				$CI->db->where('venue_flag','P');
			}
			if($selection_csc_nseit!=NULL) {
				$CI->db->where('vendor',$selection_csc_nseit);
			}
			$seat_count=$CI->master_model->getRecords('venue_master',array('venue_code'=>$sel_venue,'center_code'=>$sel_center),'session_capacity');
			if(count($seat_count) > 0)
			{
				$admit_card_Count=$CI->master_model->getRecordCount('seat_allocation',array('date'=>$sel_date,'venue_code'=>$sel_venue,'session'=>$sel_time,'center_code'=>$sel_center));	
				
				$CI->db->join('member_exam','member_exam.id = admit_card_details.mem_exam_id');
				//$CI->db->where("(`remark` = 1 OR `remark` = 2)");
				$CI->db->where('remark','2');
				$bulk_admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center,'record_source'=>'bulk','bulk_isdelete'=>'0'));	
				
				$total_count=(intval(count($bulk_admit_card_Count)) + intval($admit_card_Count));
				$remain_seat=$seat_count[0]['session_capacity']-$total_count;
				//$seat_capacity=$remain_seat.' out of '.$seat_count[0]['session_capacity'];
				$seat_capacity=$remain_seat;
			}
			$CI->db->trans_complete();
			//echo $CI->db->last_query().'<br>';
			//return $seat_number;
		}
		return $seat_capacity;
		
	}
	
	
	
	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */