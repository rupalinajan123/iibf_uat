<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

 	function validate_userdata($register_id = NULL)
	{
		$flag=0;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($register_id !=NULL)
		{
			$user_info=$CI->master_model->getRecords('member_registration',array('regnumber'=>$register_id,'isactive'=>'1'));
			/*if($user_info[0]['address1']=='')
			{
				$flag=1;
			}
			if($user_info[0]['district']=='')
			{
				$flag=1;
			}
			if($user_info[0]['city']=='')
			{
				$flag=1;
			}
			if($user_info[0]['state']=='')
			{
				$flag=1;
			}
			if($user_info[0]['pincode']=='')
			{
				$flag=1;
			}
			else if(!is_numeric ($user_info[0]['pincode']))
			{
				$flag=1;
			}
			
			if($user_info[0]['qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['specify_qualification']=='')
			{
				$flag=1;
			}
			if($user_info[0]['associatedinstitute']=='')
			{
				$flag=1;
			}*/
			/*if($user_info[0]['office']=='')
			{
				$flag=1;
			}
			if($user_info[0]['designation']=='')
			{
				$flag=1;
			}*/
			/*if($user_info[0]['dateofjoin']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofjoin']=='0000-00-00')
			{
				$flag=1;
			}*/
			if($user_info[0]['dateofbirth']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofbirth']=='0000-00-00')
			{
				$flag=1;
			}
			if($user_info[0]['email']=='')
			{
				$flag=1;
			}
			if($user_info[0]['mobile']=='')
			{
				$flag=1;
			}
			if($user_info[0]['state']!='ASS' && $user_info[0]['state']!=='JAM' && $user_info[0]['state']!='MEG')
			{
				/*if($user_info[0]['aadhar_card']=='')
				{
					$flag=1;
				}*/
			}
			if($user_info[0]['idproof']=='')
			{
				$flag=1;
			}

			if($user_info[0]['declaration']=='' && $user_info[0]['registrationtype'] == 'O')
			{
				$flag=1;
			}
			/*if($user_info[0]['idNo']=='')
			{
				$flag=1;
			}*/
			/*if($user_info[0]['optnletter']=='')
			{
				$flag=1;
			}*/
			if($user_info[0]['namesub']=='')
			{
				$flag=1;
			}
			if($user_info[0]['firstname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['displayname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['gender']=='')
			{
				$flag=1;
			}
			if(count($user_info)<=0)
			{
				$flag=1;
			}
		}
		else
		{
			$flag=1;
		}
		return $flag;
		//print_r($response_array);
		//var_dump($result);   
	}
	
	function validate_nonmemdata($register_id = NULL)
	{
		$flag=0;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($register_id !=NULL)
		{
			$user_info=$CI->master_model->getRecords('member_registration',array('regnumber'=>$register_id,'isactive'=>'1'));
			/*if($user_info[0]['address1']=='')
			{
				$flag=1;
			}
			if($user_info[0]['district']=='')
			{
				$flag=1;
			}
			if($user_info[0]['city']=='')
			{
				$flag=1;
			}
			if($user_info[0]['state']=='')
			{
				$flag=1;
			}
			if($user_info[0]['pincode']=='')
			{
				$flag=1;
			}
			else if(!is_numeric ($user_info[0]['pincode']))
			{
				$flag=1;
			}
			
			if($user_info[0]['qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['specify_qualification']=='')
			{
				$flag=1;
			}*/
			
			if($user_info[0]['dateofbirth']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofbirth']=='0000-00-00')
			{
				$flag=1;
			}
			if($user_info[0]['email']=='')
			{
				$flag=1;
			}
			if($user_info[0]['mobile']=='')
			{
				$flag=1;
			}
		/*	if($user_info[0]['aadhar_card']=='')
			{
				$flag=1;
			}*/
			if($user_info[0]['idproof']=='')
			{
				$flag=1;
			}
			/*if($user_info[0]['idNo']=='')
			{
				$flag=1;
			}*/
			
			if($user_info[0]['namesub']=='')
			{
				$flag=1;
			}
			if($user_info[0]['firstname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['gender']=='')
			{
				$flag=1;
			}
			
			if(count($user_info)<=0)
			{
				$flag=1;
			}
		}
		else
		{
			$flag=1;
		}
		return $flag;
		//print_r($response_array);
		//var_dump($result);   
	}
	
	
	function validate_edit_dbfmemdata($register_id = NULL)
	{
		$flag=0;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($register_id !=NULL)
		{
			$user_info=$CI->master_model->getRecords('member_registration',array('regnumber'=>$register_id,'isactive'=>'1'));
			if($user_info[0]['address1']=='')
			{
				$flag=1;
			}
			if($user_info[0]['district']=='')
			{
				$flag=1;
			}
			if($user_info[0]['city']=='')
			{
				$flag=1;
			}
			if($user_info[0]['state']=='')
			{
				$flag=1;
			}
			if($user_info[0]['pincode']=='')
			{
				$flag=1;
			}
			else if(!is_numeric ($user_info[0]['pincode']))
			{
				$flag=1;
			}
			
			if($user_info[0]['qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['specify_qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['dateofbirth']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofbirth']=='0000-00-00')
			{
				$flag=1;
			}
			if($user_info[0]['email']=='')
			{
				$flag=1;
			}
			if($user_info[0]['mobile']=='')
			{
				$flag=1;
			}
			if($user_info[0]['idproof']=='')
			{
				$flag=1;
			}
			if($user_info[0]['idNo']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['namesub']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['displayname']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['firstname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['gender']=='')
			{
				$flag=1;
			}
			/*if($user_info[0]['aadhar_card']=='')
			{
				$flag=1;
			}*/
			/*if($user_info[0]['aadhar_card']=='')
			{
				$flag=1;
			}*/
			if(count($user_info)<=0)
			{
				$flag=1;
			}
		}
		else
		{
			$flag=1;
		}
		return $flag;
		//print_r($response_array);
		//var_dump($result);   
	}
	
	
	function validate_edit_nonmemdata($register_id = NULL)
	{
		$flag=0;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($register_id !=NULL)
		{
			$user_info=$CI->master_model->getRecords('member_registration',array('regnumber'=>$register_id,'isactive'=>'1'));
			if($user_info[0]['address1']=='')
			{
				$flag=1;
			}
			if($user_info[0]['district']=='')
			{
				$flag=1;
			}
			if($user_info[0]['city']=='')
			{
				$flag=1;
			}
			if($user_info[0]['state']=='')
			{
				$flag=1;
			}
			if($user_info[0]['pincode']=='')
			{
				$flag=1;
			}
			else if(!is_numeric ($user_info[0]['pincode']))
			{
				$flag=1;
			}
			
			if($user_info[0]['qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['specify_qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['dateofbirth']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofbirth']=='0000-00-00')
			{
				$flag=1;
			}
			if($user_info[0]['email']=='')
			{
				$flag=1;
			}
			if($user_info[0]['mobile']=='')
			{
				$flag=1;
			}
			if($user_info[0]['idproof']=='')
			{
				$flag=1;
			}
			if($user_info[0]['idNo']=='')
			{
				$flag=1;
			}
			
			/*if($user_info[0]['namesub']=='')
			{
				$flag=1;
			}*/
			
			if($user_info[0]['firstname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['gender']=='')
			{
				$flag=1;
			}
			/*if($user_info[0]['aadhar_card']=='')
			{
				$flag=1;
			}*/
			
			if(count($user_info)<=0)
			{
				$flag=1;
			}
		}
		else
		{
			$flag=1;
		}
		return $flag;
		//print_r($response_array);
		//var_dump($result);   
	}
	
	
	function validate_ordinary_userdata($register_id = NULL)
	{
		$flag = 0;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($register_id !=NULL)
		{
			$user_info=$CI->master_model->getRecords('member_registration',array('regnumber'=>$register_id,'isactive'=>'1'));
			if($user_info[0]['address1']=='')
			{
				$flag=1;
			}
			if($user_info[0]['district']=='')
			{
				$flag=1;
			}
			if($user_info[0]['city']=='')
			{
				$flag=1;
			}
			if($user_info[0]['state']=='')
			{
				$flag=1;
			}
			if($user_info[0]['pincode']=='')
			{
				$flag=1;
			}
			else if(!is_numeric ($user_info[0]['pincode']))
			{
				$flag=1;
			}
			
			if($user_info[0]['qualification']=='')
			{
				$flag=1;
			}
			
			if($user_info[0]['specify_qualification']=='')
			{
				$flag=1;
			}
			if($user_info[0]['associatedinstitute']=='')
			{
				$flag=1;
			}
			if($user_info[0]['office']=='')
			{
				$flag=1;
			}
			if($user_info[0]['designation']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofjoin']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofjoin']=='0000-00-00')
			{
				$flag=1;
			}
			if($user_info[0]['dateofbirth']=='')
			{
				$flag=1;
			}
			if($user_info[0]['dateofbirth']=='0000-00-00')
			{
				$flag=1;
			}
			if($user_info[0]['email']=='')
			{
				$flag=1;
			}
			if($user_info[0]['mobile']=='')
			{
				$flag=1;
			}
			if($user_info[0]['idproof']=='')
			{
				$flag=1;
			}

			if($user_info[0]['declaration']=='' && $user_info[0]['registrationtype'] == 'O')
			{
				$flag=1;
			}
			/*if($user_info[0]['idNo']=='')
			{
				$flag=1;
			}*/
			if($user_info[0]['optnletter']=='')
			{
				$flag=1;
			}
			if($user_info[0]['namesub']=='')
			{
				$flag=1;
			}
			if($user_info[0]['firstname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['displayname']=='')
			{
				$flag=1;
			}
			if($user_info[0]['gender']=='')
			{
				$flag=1;
			}
			/*if($user_info[0]['aadhar_card']=='')
			{
				$flag=1;
			}*/
			if(count($user_info)<=0)
			{
				$flag=1;
			}
		}
		else
		{
			$flag=1;
		}
		return $flag;
		//print_r($response_array);
		//var_dump($result);   
	}
	
	
/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */