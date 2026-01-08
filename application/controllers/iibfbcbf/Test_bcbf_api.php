<?php 
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Test_bcbf_api extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
    }

    function test_eligible_api($exam_code, $exam_period, $member_no)
    {
      $api_res_flag = 'error';
      $api_res_msg = '';
      
      //echo '<br> API ENDPOINT : '.$api_url="http://10.10.233.66:8092/getBCBFEligibleData/".$exam_code."/".$exam_period."/".$member_no;     	
      echo '<br> API ENDPOINT : '.$api_url="http://10.10.233.76:8089/getBCBFEligibleData/1037/1/500005437";       	
      
      $string = preg_replace('/\s+/', '+', $api_url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);    
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
      
      $result = curl_exec($x);			
      if(curl_errno($x)) //CURL ERROR
      {
        $api_res_msg = curl_error($x);
      }
      else
      {
        $api_res_flag = 'success';
        $api_res_msg = json_decode($result,true);;
      }
      curl_close($x);

      $api_result_arr = array();
      $api_result_arr['api_res_flag'] = $api_res_flag;
			$api_result_arr['api_res_response'] = $api_res_msg;			
			_pa($api_result_arr);      
    }

    function test_eligible_api_bcbf_nar($exam_code, $exam_period, $member_no)
    {
      $api_res_flag = 'error';
      $api_res_msg = '';
      
      //echo '<br> API ENDPOINT UAT : '.$api_url="http://10.10.233.66:8097/getBCBFNAREligibleData/".$exam_code."/".$exam_period."/".$member_no;     	
      echo '<br> API ENDPOINT PRODUCTION : '.$api_url="http://10.10.233.76:8097/getBCBFNAREligibleData/".$exam_code."/".$exam_period."/".$member_no;
      
      $string = preg_replace('/\s+/', '+', $api_url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);    
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
      
      $result = curl_exec($x);			
      if(curl_errno($x)) //CURL ERROR
      {
        $api_res_msg = curl_error($x);
      }
      else
      {
        $api_res_flag = 'success';
        $api_res_msg = json_decode($result,true);;
      }
      curl_close($x);

      $api_result_arr = array();
      $api_result_arr['api_res_flag'] = $api_res_flag;
			$api_result_arr['api_res_response'] = $api_res_msg;			
			_pa($api_result_arr);      
    }

    public function test_exam_api($exam_period=0,$type='')
    {
      $exam_code_arr = array(1037,1038,1039,1040,1041,1042,1057);

      foreach($exam_code_arr as $res)
      {
        $exam_code = $res;
        $api_res_flag = 'error';
        $api_res_msg = '';

        $api_url="";
        if($type == 'exam_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getExamDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getExamDetails/".$exam_code."/".$exam_period."/1";						
        }
        else if($type == 'subject_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getSubjectMasterDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getSubjectMasterDetails/".$exam_code."/".$exam_period."/1";						
        }
        else if($type == 'misc_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getMiscParamMasterDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getMiscParamMasterDetails/".$exam_code."/".$exam_period."/1";						
        }
        else if($type == 'fee_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getFeeMasterDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getFeeMasterDetails/".$exam_code."/".$exam_period."/1";						
        }
        else if($type == 'centre_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getCenterMasterDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getCenterMasterDetails/".$exam_code."/".$exam_period."/1";						
        }
        else if($type == 'medium_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getMediumMasterDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getMediumMasterDetails/".$exam_code."/".$exam_period."/1";						
        }
        else if($type == 'exam_activation_master')
        {
          //$api_url="http://10.10.233.66:8091/masterData/getExamActivateMasterDetails/".$exam_code."/".$exam_period."/1";						
          $api_url="http://10.10.233.76:8090/masterData/getExamActivateMasterDetails/".$exam_code."/".$exam_period."/1";						
        }

        echo '<br> API ENDPOINT : '.$api_url;
        
        $string = preg_replace('/\s+/', '+', $api_url);
        $x = curl_init($string);
        curl_setopt($x, CURLOPT_HEADER, 0);    
        curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $result = curl_exec($x);			
        if(curl_errno($x)) //CURL ERROR
        {
          $api_res_msg = curl_error($x);
        }
        else
        {
          $api_res_flag = 'success';
          $api_res_msg = $result;
        }
        curl_close($x);

        $api_result_arr = array();
        $api_result_arr['api_res_flag'] = $api_res_flag;
        $api_result_arr['api_res_response'] = $api_res_msg;			
        $api_result_arr['api_url'] = $api_url;			
        _pa($api_result_arr);
      }
    }
  }