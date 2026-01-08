<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

 		//get fee as per the cenrer selection (Prafull)	
	function getExamFee_test($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
				
				if($grp_code=='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
					else
					{
						$grp_code='B1';
					}
				}
				else
				{
					$grp_code='B1';
				}
				
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						$fee=$getfees[0]['cs_tot'];
					}
					//else if($getstate[0]['state_code']=='JAM')
					//	{//
					//	$fee=$getfees[0]['fee_amount'];
					//}
					else
					{
						$fee=$getfees[0]['igst_tot'];
					}
				}
			}
		}
		return $fee;
	}
	
	
	function getExamFeedetails($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
	{
		$getfees=array();
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
				if($grp_code==''|| substr($grp_code, 0, 1)=='S')
				//if($grp_code=='')
				{
					$grp_code='B1';
				}
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				/*if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						$fee=$getfees[0]['cs_tot'];
					}
					else
					{
						$fee=$getfees[0]['igst_tot'];
					}
				}*/
			}
		}
		return $getfees;
	}
	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */