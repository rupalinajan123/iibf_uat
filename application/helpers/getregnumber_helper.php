<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 	

 	###-----generate scribe uid code added on 22 SEPT 2022---###
	function generate_scribe_uid($scribe_id = NULL)
	{
		$scribe_uid = '';
		$CI = & get_instance();
		if($scribe_id  !=NULL)
		{
			$insert_info = array('scribe_id'=>$scribe_id);
			$last_id = $CI->master_model->insertRecord('config_scribe_uid',$insert_info,true);
      
      $scribe_uid = 'SB'.sprintf("%04d", $last_id);
      $CI->master_model->updateRecord('config_scribe_uid',array('scribe_uid'=>$scribe_uid),array('id'=>$last_id));
	  
		}
		return $scribe_uid;
	}

	###-----generate elearning member regnumber code added on 23 Jun 2021---###
 	function generate_eLearning_memreg($reg_id = NULL)
	{
		$regnumber = '';
		$CI = & get_instance();
		if($reg_id  !=NULL)
		{
			$insert_info = array('regid'=>$reg_id);
			$last_id = $CI->master_model->insertRecord('spm_elearning_config_memreg',$insert_info,true);
      
      $regnumber = 'EL'.sprintf("%09d", $last_id);
      $CI->master_model->updateRecord('spm_elearning_config_memreg',array('regnumber'=>$regnumber),array('elearning_regnumber'=>$last_id));
		}
		return $regnumber;
	}
	

	###-----generate Ordinary member regnumber---###
 	function generate_O_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('regid'=>$reg_id);
			$last_id = $CI->master_model->insertRecord('config_O_memreg',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of generate Ordinary member regnumber---###
	###-----generate Ordinary member regnumber added by chaitali 2021-09-02 ---###
 	function generate_O_memreg_new($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		$check_id = $CI->master_model->getRecords('config_O_memreg',array('regid'=>$reg_id));
		//echo $this->db->last_query(); 
		if(!empty($check_id))
		{
			if(!empty($reg_id))
			{
				$insert_info = array('regid'=>$reg_id);
				$last_id = $CI->master_model->insertRecord('config_O_memreg',$insert_info,true);
			}
			
		}
		else
		{
			$last_id = $check_id['O_regnumber'];

		}
		return $last_id;
	}
	###-----End of generate Ordinary member regnumber---###
	
		function XLRI_sbi_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_XLRI_sbi_order_id',$insert_info,true);
		}
		return $last_id;
	}
		####----generate AMP member regnumber--###
	function generate_amp_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('amp_id '=>$reg_id);
			$last_id = $CI->master_model->insertRecord('config_AMP_memreg',$insert_info,true);
		}
		return $last_id;
	}
	
	
	###-----generate Non- member regnumber---###
	function generate_NM_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('regid'=>$reg_id);
			$last_id = $CI->master_model->insertRecord('config_NM_memreg',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of generate Non- member regnumber---###
	
	###-----generate Non- member regnumber---###
	function generate_DBF_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('regid'=>$reg_id);
			$last_id = $CI->master_model->insertRecord('config_DBF_memreg',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of generate Non- member regnumber---###
	
	###-----generate reg_sbi_order_id---###
	function reg_sbi_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_reg_sbi_order_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of reg_sbi_order_id---###
	
	###-----generate sbi_exam_order_id---###
	function sbi_exam_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_sbi_exam_order_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of sbi_exam_order_id---###
	
	###-----generate wallet_exam_order_id---###
	function wallet_exam_order_id($pt_id = NULL, $val= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('wallet_exam_order_id '=>$val,'pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_wallet_exam_order_id',$insert_info,true);
		}
		//return $last_id;
	}
	###-----generate wallet_exam_order_id---###
	
	###-----generate idcard_sbi_order_id---###
	function idcard_sbi_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_idcard_sbi_order_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of idcard_sbi_order_id---###
	
	###-----generate bd_exam_order_id---###
	function bd_exam_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_bd_exam_order_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of bd_exam_order_id---###
	
	###-----generate dra_sbi_order_id---###
	function dra_sbi_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_dra_sbi_order_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of dra_sbi_order_id---###
	
	###-----generate sbi_refund_request_id---###
	function sbi_refund_request_id($transaction_no = NULL, $receipt_no = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($transaction_no  != NULL && $receipt_no  != NULL)
		{
			$insert_info = array('transaction_no'=>$transaction_no, 'receipt_no'=>$receipt_no);
			$last_id = $CI->master_model->insertRecord('config_sbi_refund_request_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of sbi_refund_request_id---###
	
	###-----generate bd_refund_request_id---###
	function bd_refund_request_id($transaction_no = NULL, $receipt_no = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($transaction_no  != NULL && $receipt_no  != NULL)
		{
			$insert_info = array('transaction_no'=>$transaction_no, 'receipt_no'=>$receipt_no);
			$last_id = $CI->master_model->insertRecord('config_bd_refund_request_id',$insert_info,true);
		}
		return $last_id;
	}
	
	
		###-----generate amp_sbi_order_id---###
	function amp_sbi_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_amp_sbi_order_id',$insert_info,true);
		}
		return $last_id;
	}
	###-----End of bd_refund_request_id---###

/* Location: ./application/helpers/getregnumber_helper.php */