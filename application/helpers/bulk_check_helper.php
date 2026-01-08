<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 

function bulk_check_is_member($regnumber){
	$flag = 1; // return success
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	$member_chk = $CI->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1'));
	
	
	if(sizeof($member_chk) > 0){
		$flag = 1;	
	}else{
		$flag = 0;	
		$errmsg = $regnumber.' not register in system';
	}
	$arr = array('flag'=>$flag,'msg'=>$errmsg);
	return $arr;
	
}

function bulk_is_profile_complete($regnumber){
	$flag = 1; // return success
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	if(!is_file(get_img_name($regnumber,'s')) || !is_file(get_img_name($regnumber,'p')) || validate_userdata($regnumber))
	{
		$flag = 0;
		$errmsg = $regnumber.' have incomplete profile';
	}
	$arr = array('flag'=>$flag,'msg'=>$errmsg);
	return $arr;
}


function bulk_check_exam_activate($exam_code){
	$flag=1; // return success
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	if($exam_code !=NULL)
	{
		$today_date=date('Y-m-d');
		$CI->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
		$CI->db->where("bulk_exam_activation_master.institute_code",$CI->session->userdata('institute_id'));
		$exam_list=$CI->master_model->getRecords('bulk_exam_activation_master',array('exam_code'=>$exam_code));
		if(count($exam_list) == 0 || count($exam_list) == '')
		{
			$flag=0;
			$errmsg = $exam_code.' have incorrect exam period';
		}
	}
	$arr = array('flag'=>$flag,'msg'=>$errmsg);
	return $arr;
}


function bulk_checkusers($regnumber,$examcode,$exam_period){
	$flag=1; // return success
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	if($examcode!=NULL){
		 $exam_code = array(33,47,51,52);
		 if(in_array($examcode,$exam_code)){
			 $CI->db->where_in('eligible_master.exam_code', $exam_code);
			 $valid_member_list=$CI->master_model->getRecords('eligible_master',array('eligible_period'=>$exam_period,'member_type'=>'O'),'member_no');
			 if(count($valid_member_list) > 0){
				foreach($valid_member_list as $row){
					$memberlist_arr[]=$row['member_no'];
				}
				if(in_array($regnumber,$memberlist_arr)){
					$flag=1;
				}else{
					$flag=0;
					$errmsg = $regnumber.' not present in eligible master';
				}
			}else{
				$flag=0;
				$errmsg = $examcode.' exam data not present in eligible master';
			}
		}else{
			$flag=1;
		}
	}
	$arr = array('flag'=>$flag,'msg'=>$errmsg);
	return $arr;
}

function bulk_checkqualify($regnumber,$exam_code,$exam_period,$member_type){
	$flag=0; // return success
	$exam_status=1;
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	$check_qualify=array();
	$message = '';
	
	$examcode = $exam_code;
	$pre_exam = $CI->master_model->getRecords('exam_master',array('exam_code'=>$examcode),'qualifying_exam1,qualifying_part1,exam_type');
	
	$qualify_id = $pre_exam[0]['qualifying_exam1'];
	$part_no = $pre_exam[0]['qualifying_part1'];
	$exam_type = $pre_exam[0]['exam_type'];
	
	$check_qualify_exam_name=$CI->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');
	
	if(count($check_qualify_exam_name) > 0){
		$message=$regnumber.' have not cleared qualifying examination1 - <strong>'.$check_qualify_exam_name[0]['description'].'</strong>.';
	}else{
		$message=$regnumber.' have not cleared qualifying examination2.';
	}
	$check_qualify_exam=$CI->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
	//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
	$CI->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
	$check_qualify_exam_eligibility=$CI->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$member_type,'member_no'=>$regnumber),'exam_status,remark');
	if(count($check_qualify_exam_eligibility) > 0){
		foreach($check_qualify_exam_eligibility as $check_exam_status){
			if($check_exam_status['exam_status']=='F' || $check_exam_status['exam_status']=='V' || $check_exam_status['exam_status']=='D'){
				$exam_status=0;
			}
		}
		if($exam_status==1){
			//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
			$CI->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
			$check_eligibility_for_applied_exam=$CI->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$member_type,'member_no'=>$regnumber));
		
			if(count($check_eligibility_for_applied_exam) > 0){
				foreach($check_eligibility_for_applied_exam as $check_exam_status){
					if($check_exam_status['exam_status']=='F'){
						$exam_status=0;
					}
				}
				if($exam_status==1){
					$flag=0;
					if($exam_type == '3'){
						$message=$regnumber.' have already cleared this subject under <strong>'.$check_qualify_exam_name[0]['description'].'</strong> Elective Examination. Hence you cannot apply for the same';
					}else{
						$message=$check_eligibility_for_applied_exam[0]['remark'];
					}
					$arr=array('flag'=>$flag,'msg'=>$message,'cnt'=>'one');
					return $arr;
				}elseif($exam_status==1){
					$flag=0;
					if($check_qualify_exam_eligibility[0]['remark']!=''){
						$message=$check_qualify_exam_eligibility[0]['remark'];
					}
					$arr=array('flag'=>$flag,'msg'=>$message,'cnt'=>'two');
					return $arr;
				}elseif($exam_status==0){
					$flag=1;// true
					$arr=array('flag'=>$flag,'msg'=>$message,'cnt'=>'three');
					return $arr;
				}
			}else{
				//CAIIB apply directly
				$flag=1;
				$arr=array('flag'=>$flag,'msg'=>$message,'cnt'=>'four');
				return $arr;
			}
		}else{
			$flag=0;
			$message=$check_qualify_exam_eligibility[0]['remark'];
			$arr=array('flag'=>$flag,'msg'=>$message,'cnt'=>'five');
			return $arr;
		}
	}else{
		//show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
		$flag=0;
		if($qualify_id){
			$get_exam=$CI->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');	
			if(count($get_exam) > 0){
				if($exam_type == '3'){
					$message=$regnumber.' have not cleared qualifying examination3 - <strong>'.$get_exam[0]['description'].'</strong>.';
				}else{
					$message=$regnumber.' have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';
				}
			}
		}
		$arr=array('flag'=>$flag,'msg'=>$message,'cnt'=>'six');
		return $arr;
	}
	
	
}

 
function bulk_check_exam_application($regnumber,$exam_code,$exam_period){
	$flag = 1; // return success
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	$exam_chk = $CI->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'exam_code'=>$exam_code,'exam_period'=>$exam_period));
	
	if(sizeof($exam_chk) > 0){
		$flag = 0;	
		$errmsg = $exam_chk[0]['regnumber'].' already apply for current exam';
	}
	
	$arr = array('flag'=>$flag,'msg'=>$errmsg);
	return $arr;
}

function bulk_examdate($regnumber,$exam_code){
	$flag=0; // return success
	$CI = & get_instance();
	$errmsg = '';
	$arr = array();
	$today_date=date('Y-m-d');
	$applied_exam_date=$CI->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
	if(count($applied_exam_date) > 0){
		$CI->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$getapplied_exam_code=$CI->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code');
		if(count($getapplied_exam_code) >0){
			foreach($getapplied_exam_code as $exist_ex_code){	
				$getapplied_exam_date=$CI->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
				if(count($getapplied_exam_date) > 0){
					foreach($getapplied_exam_date as $exist_ex_date){
						foreach($applied_exam_date as $sel_ex_date){
							if($sel_ex_date['exam_date']==$exist_ex_date['exam_date']){
								$flag=1;
								$errmsg = $regnumber.' try to apply two exam on same date';
							}
						}
					}
				}
			}
		}
	}
	$arr = array('flag'=>$flag,'msg'=>$errmsg);
	return $arr;
}