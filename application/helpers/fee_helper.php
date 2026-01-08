<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

 // added by chaitali on 2021-07-16
 	function getExamFeedetails_custom($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag = NULL)

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
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));

			//echo $CI->db->last_query();exit;

			if(count($getstate) <= 0)

			{

				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));

			}

			

			if(count($getstate) > 0)

			{

				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
//echo $CI->db->last_query();exit;
				if($grp_code!='')

				{

					if(substr($grp_code, 0, 1)=='S')

					{

						$grp_code='S1';

					}

				}

				else

				{

			

					$grp_code='B1_1';

				}

				 $today_date=date('Y-m-d');

				 //$today_date='2017-08-15';

				// $CI->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');

				

				//$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));

				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");

				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));

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
 
 	//get fee as per the cenrer selection (Prafull)	
	function getExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];

		/*echo '</br>centerCode:'.$centerCode;
		echo '</br>eprid:'.$eprid;
		echo '</br>excd:'.$excd;
		echo '</br>grp_code:'.$grp_code;
		echo '</br>memcategory:'.$memcategory;
		echo '</br>elearning_flag:'.$elearning_flag; die();*/
	
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
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d');
				 //XXX : START : Step 1 : Allowing member to register for JAIIB / CAIIB after registration closed
				 if(in_array($CI->session->userdata('regnumber'), array(500083125,510081606))) //500083125,510081606
				 { $today_date = '2022-12-09'; }
				 //XXX : END : Step 1 : Allowing member to register for JAIIB / CAIIB after registration closed
				 
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						if($elearning_flag == 'Y')
						{
							if(isset($CI->session->userdata['examinfo']['el_subject']) && count($CI->session->userdata['examinfo']['el_subject']) > 0 &&(base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) ==$CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65 || base64_decode($excd) == 11 ))
							{
							 	$fee=$getfees[0]['cs_tot'];

								 $el_amt = $getfees[0]['elearning_cs_amt_total'] * $CI->session->userdata('subject_cnt');
								$fee = $getfees[0]['cs_tot'] + $el_amt; //commented by priyanka d on 13-nov-24

							}else{
								$fee=$getfees[0]['elearning_cs_amt_total'];  
							}
						}else{
							$fee=$getfees[0]['cs_tot'];
						}
					}
					else
					{ 
						if($elearning_flag == 'Y')
						{
							if(isset($CI->session->userdata['examinfo']['el_subject']) && count($CI->session->userdata['examinfo']['el_subject']) > 0 &&(base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) ==$CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65  || base64_decode($excd) == 11) )
							
							{
								$fee=$getfees[0]['igst_tot'];
								$el_amt = $getfees[0]['elearning_cs_amt_total'] * $CI->session->userdata('subject_cnt');
								$fee = $getfees[0]['igst_tot'] + $el_amt; //commented by priyanka d on 13-nov-24
							}else{
								$fee=$getfees[0]['elearning_igst_amt_total'];
							}
						}else{
							$fee=$getfees[0]['igst_tot'];
						}
					}
				}
			}
		}
		return $fee;
	}
	
	// Get fee only for JAIIB multiple subject elearning selection
	function get_el_ExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
		//echo $centerCode.'='.$eprid.'='.$excd.'='.$grp_code.'='.$memcategory;
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			//echo 'here1';
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				//echo 'here2';
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			//echo $CI->db->last_query();exit;
			if(count($getstate) > 0)
			{
				//echo 'here3';
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d'); 
				 
				//XXX : START : Step 2 : Allowing member to register for JAIIB / CAIIB after registration closed
				 if(in_array($CI->session->userdata('regnumber'), array(500083125,510081606))) //,500083125,510081606
				 { $today_date = '2022-12-09'; }
				 //XXX : END : Step 2 : Allowing member to register for JAIIB / CAIIB after registration closed
				
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						if($elearning_flag == 'Y'){
							$fee=$getfees[0]['elearning_cs_amt_total'];
						}else{
							$fee=$getfees[0]['elearning_cs_amt_total'];
						}
					}
					else
					{
						if($elearning_flag == 'Y'){
							$fee=$getfees[0]['elearning_igst_amt_total'];
						}else{
							$fee=$getfees[0]['elearning_igst_amt_total'];
						}
					}
				}
			}
		}
		return $fee;
	}
	
	// Get fee only for JAIIB multiple subject elearning selection
	function get_el_ExamFeeFree($centerCode = NULL, $eprid =NULL ,$excd =NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $memcategory !=NULL )
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
				
				$today_date=date('Y-m-d');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						if($elearning_flag == 'Y'){
							$fee=$getfees[0]['elearning_cs_amt_total'];
						}else{
							$fee=$getfees[0]['elearning_cs_amt_total'];
						}
					}
					else
					{
						if($elearning_flag == 'Y'){
							$fee=$getfees[0]['elearning_igst_amt_total'];
						}else{
							$fee=$getfees[0]['elearning_igst_amt_total'];
						}
					}
				}
			}
		}
		return $fee;
	}
	
	//get fee as per the cenrer selection (Prafull)	
	function getExamFeeTest($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
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
			
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			//echo $CI->db->last_query();exit;
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $CI->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
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
	
	
	function getExamFeedetails($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag = NULL)
	{
		$getfees=array();
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		$insert_input_data['center_code'] = $centerCode;
        $insert_input_data['exam_period'] = $eprid;
        $insert_input_data['exam_code'] = $excd;
        $insert_input_data['group_code'] = $grp_code;
        $insert_input_data['member_category'] = $memcategory;
        $insert_input_data['elearning_flag'] = $elearning_flag; 

        $apply_exam_get_fee_logs_data['log_title'] = "Get Fee Details Using Input Data";
        $apply_exam_get_fee_logs_data['input_fee_details'] = serialize($insert_input_data); 
	
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
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				 $today_date=date('Y-m-d');
				 				
				//XXX : START : Step 3 : Allowing member to register for JAIIB / CAIIB after registration closed
				 if(in_array($CI->session->userdata('regnumber'), array(500083125,510081606)))//,500083125,510081606 
				 { $today_date = '2022-12-09'; }
				 //XXX : END : Step 3 : Allowing member to register for JAIIB / CAIIB after registration closed				
				//$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
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

		$apply_exam_get_fee_logs_data['output_fee_details'] = serialize($getfees);
		$apply_exam_get_fee_logs_data['log_date'] = date('Y-m-d H:i:s');
		$CI->master_model->insertRecord('apply_exam_get_fee_logs', $apply_exam_get_fee_logs_data);
		
		return $getfees;
	}
	

	function getExamFeedetails_anil($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag = NULL)
	{
		$getfees=array();
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
		          
        $insert_input_data['center_code'] = $centerCode;
        $insert_input_data['exam_period'] = $eprid;
        $insert_input_data['exam_code'] = $excd;
        $insert_input_data['group_code'] = $grp_code;
        $insert_input_data['member_category'] = $memcategory;
        $insert_input_data['elearning_flag'] = $elearning_flag; 

        $apply_exam_get_fee_logs_data['log_title'] = "Get Fee Details Using Input Data";
        $apply_exam_get_fee_logs_data['input_fee_details'] = serialize($insert_input_data);  
		  

		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			echo $CI->db->last_query();
			echo '<br>';
			echo "base64_decode==".count($getstate);
			
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}

			echo '<br>';
			echo $CI->db->last_query();
			echo '<br>';
			echo "==".count($getstate);
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));

				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}


				 //$today_date=date('Y-m-d');
				 $today_date = '2024-05-13';//date('Y-m-d');
				 				
				//XXX : START : Step 3 : Allowing member to register for JAIIB / CAIIB after registration closed
				 if(in_array($CI->session->userdata('regnumber'), array(500083125,510081606)))//,500083125,510081606 
				 { $today_date = '2022-12-09'; }
				 //XXX : END : Step 3 : Allowing member to register for JAIIB / CAIIB after registration closed				
				//$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				//echo $CI->db->last_query();exit;

				echo '<br>';
				echo $CI->db->last_query();
				echo '<br>';
				echo "base64_decode==".count($getfees);

				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				
				echo '<br>';
				echo $CI->db->last_query();
				echo "==".count($getfees);
				echo '<br>';
				//exit;



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

		 
		$apply_exam_get_fee_logs_data['output_fee_details'] = serialize($getfees);
		$apply_exam_get_fee_logs_data['log_date'] = date('Y-m-d H:i:s');
		$CI->master_model->insertRecord('apply_exam_get_fee_logs', $apply_exam_get_fee_logs_data);

		return $getfees;
	}
	
	function getExamFeedetailsEL($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$memcategory=NULL,$elearning_flag = NULL)
	{
		$getfees=array();
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $memcategory !=NULL )
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
				
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $CI->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				
				//$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid));
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
	
	
	function displayExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		
		
		
		$fee=0;
		$CI = & get_instance();
		//echo $CI->session->userdata('subject_cnt');
		if($CI->session->userdata('subject_cnt') > 0 && (base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) == $CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65 || base64_decode($excd) == 11)){
			$elearning_flag = 'Y';
		}
		/*if(isset($CI->session->userdata('subject_cnt'))){
			$elearning_flag = 'Y';
		}*/
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];

		// echo "centerCode :".$centerCode. " eprid : ". $eprid . " excd :". $excd . " grp_code :". $grp_code . " memcategory:". $memcategory;
		// exit;
		
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
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d');
				 
				 //XXX : START : Step 4 : Allowing member to register for JAIIB / CAIIB after registration closed
				 if(in_array($CI->session->userdata('regnumber'), array(500083125,5100816065)))//,500083125,510081606 
				 { $today_date = '2022-12-09'; }
				 //XXX : END : Step 4 : Allowing member to register for JAIIB / CAIIB after registration closed
				 
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				if(in_array($CI->session->userdata('regnumber'), array(500099034))) {
				 //echo $CI->db->last_query();exit;
				}
				
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getfees[0]['exempt']=='E')
					{
						$fee=$getfees[0]['fee_amount'];
					}
					else
					{  
						$rpeEXCodes = array(1002,1003,1004,1005,1009,1013,1014,1006,1007,1008,1011,1012,2027,1019,1020,1017);
						$dipcertEXCodes = array(11);
						if($elearning_flag == 'Y'){
							if($CI->session->userdata('subject_cnt') > 0 && (base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) == $CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65 || (in_array(base64_decode($excd),$dipcertEXCodes))) ){
								$el_amt = $getfees[0]['elearning_cs_amt_total'] * $CI->session->userdata('subject_cnt');
								$fee = $getfees[0]['igst_tot'] + $el_amt;
							} else{
								//$fee=$getfees[0]['elearning_fee_amt'].' + GST as applicable';
								if(in_array(base64_decode($excd),$rpeEXCodes) && $CI->session->userdata('subject_cnt') <= 0)
									$fee=''.$getfees[0]['fee_amount'].' + GST as applicable';
								
								else if((base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) == $CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65 || (in_array(base64_decode($excd),$dipcertEXCodes))) && $CI->session->userdata('subject_cnt') <= 0)
									$fee=''.$getfees[0]['fee_amount'].' + GST as applicable';
							else
									$fee=''.$getfees[0]['elearning_fee_amt'].' + GST as applicable';
								}
						}else{ 
							$fee=$getfees[0]['fee_amount'].' + GST as applicable';
						}
						
					}
				}
			}
		}
		
		return $fee;
	}
	
	
	function displayExamFeeEL($centerCode = NULL, $eprid =NULL ,$excd =NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//echo $CI->session->userdata('subject_cnt');
		if($CI->session->userdata('subject_cnt') > 0 && (base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) == $CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65)){
			$elearning_flag = 'Y'; 
		}
		else{
			$elearning_flag = 'N';
			$fee = 0;
		}

		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $memcategory !=NULL )
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
			
				$today_date=date('Y-m-d');
				 
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'exempt'=>$getstatedetails[0]['exempt']));
				//echo "1".$CI->db->last_query();//exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid));
					//echo "2".$CI->db->last_query();//exit;
				}
				
				if(count($getfees) > 0)
				{
					if($getfees[0]['exempt']=='E'){
						$fee=0;
					}
					else{
						if($elearning_flag == 'Y')
						{
							if($CI->session->userdata('subject_cnt') > 0 && (base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) == $CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65) )
							{
								$el_amt = $getfees[0]['elearning_cs_amt_total'] * $CI->session->userdata('subject_cnt');
								$fee = $el_amt;
							} 
						}else{
							$fee=0;
						}
						
					}
				}
			}
		}
		return $fee;
	}
	
	
	######special exam###
	function spldisplayExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
		
			$getstate=$CI->master_model->getRecords('spl_center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('spl_center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $CI->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
					//echo $CI->db->last_query();exit;
				}
				if(count($getfees) > 0)
				{
					if($getfees[0]['exempt']=='E')
					{
						$fee=$getfees[0]['fee_amount'];
					}
					else
					{
						$fee=$getfees[0]['fee_amount'].' + GST as applicable';
					}
						
					/*if($getstate[0]['state_code']=='MAH')
					{
						$fee=$getfees[0]['cs_tot'];
					}
					else
					{
						$fee=$getfees[0]['igst_tot'];
					}*/
				}
			}
		}
		//print_r(base64_decode($excd));
		//echo $CI->db->last_query();exit;
		return $fee;
	}
	
	//get fee as per the cenrer selection (Prafull)	
	function spl_getExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('spl_center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('spl_center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $CI->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
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
	
	
	function spl_getExamFeedetails($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
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
			$getstate=$CI->master_model->getRecords('spl_center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('spl_center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $CI->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				
				//$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('spl_fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
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