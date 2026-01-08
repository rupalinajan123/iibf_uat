<?php

defined('BASEPATH')||exit('No Direct Allowed Here');

/**

 * Function to check for last seat and prevent user,.

 * @access public 

 * @param String

 * @return String

 */
 	//get latest or 2hr back record from  admit-card which are yet to be updated and prevent extra registration against the actual capacity (Prafull)	
	function getLastseat($excd =NULL,$centerCode = NULL,$venue_code=NULL,$date=NULL,$time=NULL)
	{
		//No need of exam code as of now, but during the function call pass the exam code for future purpose and accordingly we will make changes in below sql query.
		$CI = & get_instance();
		$total_admit_count=0;
		$query='SELECT admitcard_id FROM admit_card_details WHERE created_on <= NOW()  AND created_on > NOW() - INTERVAL 120 MINUTE AND seat_identification = "" AND remark = 2 AND center_code="'.$centerCode.'" AND venueid="'.$venue_code.'" AND exam_date="'.$date.'" AND time="'.$time.'"';
		$crnt_day_txn_qry = $CI->db->query($query);
		$total_admit_count=$crnt_day_txn_qry->num_rows();
		return $total_admit_count;
	}

	function preventUser($excd =NULL,$centerCode = NULL,$venue_code=NULL,$date=NULL,$time=NULL,$exprd=NULL)
	{
		$CI = & get_instance();
		$get_subject_details = $CI->master_model->getRecords('venue_master', array('venue_code' => $venue_code,'exam_date'=> $date,'session_time'=> $time,'center_code'=> $centerCode));
		
		$get_seat=$CI->db->query('SELECT 1 + (count(*)) as new_seat_cnt
			FROM admit_card_details
			WHERE  exm_prd = '.$exprd.' AND center_code = '.$centerCode.' AND venueid = "'.$venue_code.'" AND exam_date= "'.$date.'" AND time = "'.$time.'" AND created_on <= NOW()  AND created_on > NOW() - INTERVAL 120 MINUTE  HAVING new_seat_cnt <= '.$get_subject_details[0]['session_capacity'].'');
			
			return $get_seat->num_rows();
			
	}


	


/* End of file checkactiveexam_helper.php */

/* Location: ./application/helpers/checkactiveexam_helper.php */