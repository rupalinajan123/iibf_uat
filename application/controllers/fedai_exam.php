<?php 
  /********************************************************************************************************************
  ** Description: Controller for FEDAI MASTER DATA USING API
  ** Created BY: Gaurav Shewale On 27-05-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Fedai_exam extends CI_Controller 
  {
    //START : API CODE TO INSERT FEDAI MASTER DATA INTO DATABASE TABLE
    public function fedai_institute_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';   
      $tbl_name = 'fedai_institution_master'; 
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'fedai_master';

      if($exam_code == '' || $exam_period == '')
      {
        $response_msg = 'The exam code or exam period is blank';
      }
      else
      {
        $api_response = $this->iibf_fedai_institute_master_api(1009,$exam_period);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          
          if( is_array($decoded_res) && count($decoded_res) > 0 )
          {
            $total_record = count($decoded_res);
            $i = 0;

            $this->db->query('TRUNCATE TABLE  fedai_institution_master;');

            foreach($decoded_res as $res)
            {
              //START : DELETE OLD RECORD FROM table fedai_institution_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              $add_data = array();
              $add_data['institude_id'] = $res['ins_ins_cd'];
              $add_data['name']         = $res['ins_ins_nam'];

              $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
              if($insert_id > 0)
              { 
                $success_cnt++;                   
              }
              else
              {
                $error_cnt++;
              }
              //END : DELETE OLD RECORD FROM table fedai_institution_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
            }
            if($success_cnt == $total_record) { $flag = 'success'; }
            $response_msg = '';
          }
          else
          {
            $response_msg = "No response from the API";
          }
        }
        else
        {
          $response_msg = $api_response['api_res_response'];          
        }
      }
      
      $result_arr = array();

      $result_arr['API Title'] = 'FEDAI Master ('.$tbl_name.')';
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
      $result_arr['response_msg'] = $response_msg;
      
      echo "<pre>"; print_r($result_arr);
    } //END : API CODE TO INSERT FEDAI MASTER DATA INTO DATABASE TABLE

    public function iibf_fedai_institute_master_api($exam_code=0, $exam_period=0)
    {
      $api_res_flag = 'error';
      $api_res_msg = '';

      // $api_url= "http://10.10.233.66:8091/masterData/getFedaiMasterDetails/".$exam_code."/".$exam_period."/1";  //UAT API ADDED BY GAURAV ON 2024-05-27 

      $api_url= "http://10.10.233.76:8090/masterData/getFedaiMasterDetails/".$exam_code."/".$exam_period."/1";  //LIVE API ADDED BY GAURAV ON 2024-05-27                   

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
      $api_result_arr['api_res_flag']     = $api_res_flag;
      $api_result_arr['api_res_response'] = $api_res_msg;    
      return $api_result_arr;
    }
  }