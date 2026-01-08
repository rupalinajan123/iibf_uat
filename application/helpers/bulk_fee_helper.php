<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 	//get fee as per the cenrer selection (Prafull)	
	function bulk_getExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL,$discount_flag=NULL)
	{
		
		/*echo $centerCode .'<br>';
		echo $eprid .'<br>';
		echo $excd .'<br>';
		echo $grp_code.'<br>';
		echo $memcategory.'<br>';
		exit;*/
		
		$fee=0;
		$CI = & get_instance();
		
		//echo $CI->session->userdata('subject_cnt');exit;
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
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
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$getSubjects=$CI->master_model->getRecords('subject_master',array('exam_code'=>($excd),'exam_period'=>$eprid));
			
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>'NE'));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						if($elearning_flag == 'Y'){
							$fee=(($getfees[0]['elearning_cs_amt_total']*($CI->session->userdata('subject_cnt')))+$getfees[0]['cs_tot']); // changed getSubjects to sub_arr it was calculating wrong fee >> DBFMOUPAYMENTCHANGE
						}else{
							$fee=$getfees[0]['cs_tot'];
						}
					}
					else
					{
						if($elearning_flag == 'Y'){
							$fee=(($getfees[0]['elearning_cs_amt_total']*($CI->session->userdata('subject_cnt')))+$getfees[0]['igst_tot']);// changed getSubjects to sub_arr it was calculating wrong fee >> DBFMOUPAYMENTCHANGE
						}else{
							$fee=$getfees[0]['igst_tot'];
						}
					}
				}
			}
		}
		//echo $fee;exit;
		return $fee;
	}
	
	//get base fee and app_category as per the centre selection (Tejasvi)	
	function bulk_getFee_Appcat($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL,$discount_flag=NULL)
	{
		$fee=0;
		$original_base_fee = 0;
		$bulk_discount_flg = 0;
		$discount_percent = 0;
		$calculate_discount = 0;
		$taken_discount = 0;
		$discount_percent_val = 0;
							
		$CI = & get_instance();
	
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
				//$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			
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
				//echo $grp_code;
				//echo '****';
				 $today_date=date('Y-m-d');
				 //$today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>'NE'));//,'exempt'=>$getstatedetails[0]['exempt'])
				$getSubjects=$CI->master_model->getRecords('subject_master',array('exam_code'=>($excd),'exam_period'=>$eprid));
			
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				
				if(count($getfees) > 0)
				{
					$ci =& get_instance();
					$ci->load->helper('bulk_calculate_tds_discount_helper');
					
					$ci->db->where('institute_code',$ci->session->userdata['institute_id']);
					$ci->db->where('exam_code',$excd);
					$ci->db->where('exam_period',$eprid);
					$discount_percent = $ci->master_model->getRecords('bulk_exam_activation_master','','discount,discount_amount');
					
					$fix_calculate_elearning_fee = 250;
					if($elearning_flag == 'Y'){
						if($discount_flag == 'Y'){
							
							// calculation only for discount amount
							if(($discount_percent[0]['discount'] == 0 || $discount_percent[0]['discount'] == 0.00) && $discount_percent[0]['discount_amount'] > 0){
								$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));// changed getSubjects to sub_arr it was calculating wrong fee >> DBFMOUPAYMENTCHANGE
								$fee_after_adding_elearning_fee = $fee_after_discount + $calculate_elearning_fee;
								$fee = $fee_after_adding_elearning_fee;
								$original_base_fee = $getfees[0]['fee_amount'];
								
								$discount_percent_val = 0.00;
								$bulk_discount_flg = $discount_flag;
								$calculate_discount = $discount_percent[0]['discount_amount'];
								$taken_discount = $discount_percent[0]['discount_amount'];
								$given_discount_amt = $discount_percent[0]['discount_amount'];
								
							}
							
							// calculation only for discount percentage
							if(($discount_percent[0]['discount_amount'] == 0 || $discount_percent[0]['discount_amount'] == 0.00) && $discount_percent[0]['discount'] > 0){
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));// changed getSubjects to sub_arr it was calculating wrong fee >> DBFMOUPAYMENTCHANGE
								$fee_after_adding_elearning_fee = $fee_after_discount + $calculate_elearning_fee;
								$fee = $fee_after_adding_elearning_fee;
								
								$original_base_fee = $getfees[0]['fee_amount'];
								$discount_percent_val = $discount_percent[0]['discount'];
								$bulk_discount_flg = $discount_flag;
								$calculate_discount = $getfees[0]['fee_amount'] - $fee_after_discount;
								$taken_discount = $calculate_discount;
								$given_discount_amt = 0;
							}
							
							
							// calculation only for discount amount + discount percentage
							if($discount_percent[0]['discount'] > 0 && $discount_percent[0]['discount_amount'] > 0.00){
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));;//$getfees[0]['elearning_fee_amt'] - $getfees[0]['fee_amount']; // changed getSubjects to sub_arr it was calculating wrong fee >> DBFMOUPAYMENTCHANGE
								$discount_amt = $getfees[0]['fee_amount'] - $fee_after_discount;
								if($discount_amt > $discount_percent[0]['discount_amount']){
									$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								}
								$fee_after_adding_elearning_fee = $fee_after_discount + $calculate_elearning_fee;
								$fee = $fee_after_adding_elearning_fee;
								$original_base_fee = $getfees[0]['fee_amount'];
								
								$discount_percent_val = $discount_percent[0]['discount'];
								$bulk_discount_flg = $discount_flag;
								$calculate_discount = $getfees[0]['fee_amount'] - $fee_after_discount;
								if($calculate_discount > $discount_percent[0]['discount_amount']){
									$taken_discount = $discount_percent[0]['discount_amount'];
								}else{
									$taken_discount = $calculate_discount;
								}
								$given_discount_amt = $discount_percent[0]['discount_amount'];
							}
						}else{ 
							$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));
							$fee=$getfees[0]['fee_amount'];
							$fee_after_adding_elearning_fee = $fee + $calculate_elearning_fee;
							$fee = $fee_after_adding_elearning_fee; // priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july >> added eleaning Fee

							$original_base_fee = $getfees[0]['fee_amount'];
							$bulk_discount_flg = 'N';
							$discount_percent_val = 0;
							$calculate_discount = 0;
							$taken_discount = 0;
							$given_discount_amt = 0;
							
						}
					}
					else{
						if($discount_flag == 'Y'){
							
							//echo '>>'.$discount_flag;
							// calculation only for discount amount
							if(($discount_percent[0]['discount'] == 0 || $discount_percent[0]['discount'] == 0.00) && $discount_percent[0]['discount_amount'] > 0){
								$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								$fee = $fee_after_discount;
								//echo '##00'.$discount_flag;exit;
								$original_base_fee = $getfees[0]['fee_amount'];
								$bulk_discount_flg = $discount_flag;
								$discount_percent_val = 0.00;
								$calculate_discount = $discount_percent[0]['discount_amount'];
								$taken_discount = $discount_percent[0]['discount_amount'];
								$given_discount_amt = $discount_percent[0]['discount_amount'];
							}
							
							
							// calculation only for discount percentage
							if(($discount_percent[0]['discount_amount'] == 0 || $discount_percent[0]['discount_amount'] == 0.00) && $discount_percent[0]['discount'] > 0){
								
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
							
								$fee = $fee_after_discount;
								
								$original_base_fee = $getfees[0]['fee_amount'];
								$bulk_discount_flg = $discount_flag;
								$discount_percent_val = $discount_percent[0]['discount'];
								$calculate_discount = $getfees[0]['fee_amount'] - $fee_after_discount;
								$taken_discount = $calculate_discount;
								$given_discount_amt = 0;
							}
							
							// calculation only for discount amount + discount percentage
							if($discount_percent[0]['discount'] > 0 && $discount_percent[0]['discount_amount'] > 0.00){
								
								
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								
								$discount_amt = $getfees[0]['fee_amount'] - $fee_after_discount;
								if($discount_amt > $discount_percent[0]['discount_amount']){
									$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								}
								$fee = $fee_after_discount;
								$original_base_fee = $getfees[0]['fee_amount'];
								$bulk_discount_flg = $discount_flag;
								$discount_percent_val = $discount_percent[0]['discount'];
								$calculate_discount = $getfees[0]['fee_amount'] - $fee_after_discount;
								
								if($discount_amt > $discount_percent[0]['discount_amount']){
									$taken_discount = $discount_percent[0]['discount_amount'];
								}else{
									$taken_discount = $calculate_discount;
								}
								$given_discount_amt = $discount_percent[0]['discount_amount'];
							}
						}else{
							$fee=$getfees[0]['fee_amount'];
							$original_base_fee = $getfees[0]['fee_amount'];
							$bulk_discount_flg = 'N';
							$discount_percent_val = 0;
							$calculate_discount = 0;
							$taken_discount = 0;
							$given_discount_amt = 0;
							
						}
					}
					
				}
			}
			
			
			
			$data = array('base_fee'=>$fee,'grp_code'=>$grp_code,'original_base_fee'=>$original_base_fee,'bulk_discount_flg'=>$bulk_discount_flg,'discount_percent'=>$discount_percent_val,'calculate_discount'=>$calculate_discount,'taken_discount'=>$taken_discount,'discount_amount'=>$given_discount_amt);
		}
		
		return $data;
	}
	
	
	function bulk_getExamFeedetails($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL)
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
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
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
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>'NE'));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
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
	
	
	function bulk_displayExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL,$discount_flag=NULL,$free_paid_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();

		if($CI->session->userdata('subject_cnt') > 0 && (base64_decode($excd) == $CI->config->item('examCodeJaiib') || base64_decode($excd) == $CI->config->item('examCodeDBF') || base64_decode($excd) == $CI->config->item('examCodeSOB') || base64_decode($excd) == $CI->config->item('examCodeCaiib') || base64_decode($excd) == 65 || base64_decode($excd) == 11)){
			$elearning_flag = 'Y';
		}
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
		
			$getSubjects=$CI->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($excd),'exam_period'=>$eprid));
			
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
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
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>'NE'));
				
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				//echo $CI->db->last_query(); 
				if(count($getfees) > 0)
				{
					$ci =& get_instance();
					$ci->load->helper('bulk_calculate_tds_discount_helper');
					
					$ci->db->where('institute_code',$ci->session->userdata['institute_id']);
					$ci->db->where('exam_code',base64_decode($excd));
					$ci->db->where('exam_period',$eprid);
					$discount_percent = $ci->master_model->getRecords('bulk_exam_activation_master','','discount,discount_amount');
					
					//echo $CI->db->last_query();
					//echo $CI->session->userdata('subject_cnt').'=';
					$fix_calculate_elearning_fee = 250;
					if($elearning_flag == 'Y')
					{
						if($discount_flag == 'Y'){
							
							// calculation only for discount amount
							if(($discount_percent[0]['discount'] == 0 || $discount_percent[0]['discount'] == 0.00) && $discount_percent[0]['discount_amount'] > 0){
								$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));
								
								$fee_after_adding_elearning_fee = $fee_after_discount + $calculate_elearning_fee;
							
								$fee = $fee_after_adding_elearning_fee.' + GST as applicable';
							}
							
							// calculation only for discount percentage
							if(($discount_percent[0]['discount_amount'] == 0 || $discount_percent[0]['discount_amount'] == 0.00) && $discount_percent[0]['discount'] > 0){
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));
								
								$fee_after_adding_elearning_fee = $fee_after_discount + $calculate_elearning_fee;
							
								$fee = $fee_after_adding_elearning_fee.' + GST as applicable';
							}
							
							// calculation only for discount amount + discount percentage
							if($discount_percent[0]['discount'] > 0 && $discount_percent[0]['discount_amount'] > 0.00){
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								//echo $fee_after_discount;
								$discount_amt = $getfees[0]['fee_amount'] - $fee_after_discount;
								
								$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));
								//echo $calculate_elearning_fee.'==';
								if($discount_amt > $discount_percent[0]['discount_amount']){
									$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								}
								
								$fee_after_adding_elearning_fee = $fee_after_discount + $calculate_elearning_fee;
							
								$fee = $fee_after_adding_elearning_fee.' + GST as applicable';
								
							}
							
						}else{
							$calculate_elearning_fee = ($getfees[0]['elearning_fee_amt']*$CI->session->userdata('subject_cnt'));
							$fee=($getfees[0]['fee_amount']+$calculate_elearning_fee).' + GST as applicable'; // priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july >> added elearning fee
						}
					}else{
						if($discount_flag == 'Y'){
							
							// calculation only for discount amount
							if(($discount_percent[0]['discount'] == 0 || $discount_percent[0]['discount'] == 0.00) && $discount_percent[0]['discount_amount'] > 0){
								$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								$fee = $fee_after_discount.' + GST as applicable';
							}
							
							// calculation only for discount percentage
							if(($discount_percent[0]['discount_amount'] == 0 || $discount_percent[0]['discount_amount'] == 0.00) && $discount_percent[0]['discount'] > 0){
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								$fee = $fee_after_discount.' + GST as applicable';
							}
							
							
							// calculation only for discount amount + discount percentage
							if($discount_percent[0]['discount'] > 0 && $discount_percent[0]['discount_amount'] > 0.00){
								$fee_after_discount = calculate_discount($getfees[0]['fee_amount'], $discount_percent[0]['discount']);
								$discount_amt = $getfees[0]['fee_amount'] - $fee_after_discount;
								if($discount_amt > $discount_percent[0]['discount_amount']){
									$fee_after_discount = $getfees[0]['fee_amount'] - $discount_percent[0]['discount_amount'];
								}
								$fee = $fee_after_discount.' + GST as applicable';
							}
							
						}else{
							$fee=$getfees[0]['fee_amount'].' + GST as applicable';
						}
					}
					
				}
			}
		}
		//print_r(base64_decode($excd));
		//echo $CI->db->last_query();exit;
		if($free_paid_flag == 'F' && $elearning_flag == 'N' && (base64_decode($excd) == 1017 || base64_decode($excd) == 1018))
		{
			$fee = 0;
		}
		
		if($free_paid_flag == 'F' && $elearning_flag == 'Y' && (base64_decode($excd) == 1017 || base64_decode($excd) == 1018)){
			$dra_el_fee = 250;
			$fee = $dra_el_fee.' + GST as applicable';
		}
		//echo $free_paid_flag.'###'.base64_decode($excd);
		if($free_paid_flag == 'N' && base64_decode($excd) == 1015){
			$fee = 0;
		}
		 
		
		return $fee;
	}
	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */