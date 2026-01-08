<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF MASTER DATA USING API
  ** Created BY: Sagar Matale On 29-02-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Exam_api_iibfbcbf extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');

      //1=>Basic, 2=>Advance
      $this->exam_type_arr = array('1037'=>'2', '1038'=>'1', '1041'=>'2', '1042'=>'1', '1057'=>'2');
    }
    
    public function index($exam_code='', $exam_period='')
    {   
      $response_arr = array();
      if($exam_code == '' || $exam_period == '')
      {
        echo 'The exam code or exam period is blank';
      }
      else
      { 
        $is_valid_exam_code_flag = 1;
        $exam_code_arr = explode(",",$exam_code);
        if(count($exam_code_arr) > 0)
        {
          foreach($exam_code_arr as $exam_code_res)
          {
            if(!in_array($exam_code_res, array(1037,1038,1041,1042,1057)))
            {
              $is_valid_exam_code_flag = 0;
            }
          }
        }

        if($is_valid_exam_code_flag == '0') { echo 'Invalid exam code entered. Valid exam codes are 1037,1038,1041,1042,1057'; exit; } ?>
        <html>
          <head>
            <style>
              body { width: 100%; max-width: 1200px; font-family: calibri; margin: 10px auto; font-size:14px; }
              .custom_tbl { border: 1px solid #000; border-collapse: collapse; margin: 20px auto; width: 100%; }
              .custom_tbl tr > th { border: 1px solid #000; border-collapse: collapse; background: #ccc; padding: 10px; text-align: center; font-size:16px; vertical-align:top; }
              .custom_tbl tr > td { border: 1px solid #000; border-collapse: collapse; padding: 5px 10px; font-size:14px; }
              .text-center { text-align: center; } 
              .hide { display:none; }
              .row_error, .row_error td, .row_error th { color: #c60909; font-weight: bold;  }
              .final_result td { text-align: center; font-size: 16px !important; padding: 10px !important; background: #238823; color: #fff; font-weight: bold; }
              .row_error.final_result td { background: red; }
              .btn { background: #238823; padding: 3px 10px 4px; display: block; color: #fff; text-decoration: none; white-space: nowrap; border-radius: 3px; opacity: 0.9; font-size: 12px; min-width: 60px; font-weight: bold; text-transform: capitalize; }
              .btn:hover { opacity: 1; }
              .btn.btn_error { background: red; }
            </style>
          </head>
          
          <body>
            <div style="font-weight: 600; font-size: 18px; text-align: center; background: #2fa9e3; color: #0c1715; padding: 8px 0px;">IIBFBCBF Masters Data Import Using API : Exam Code <u>&nbsp;<?php echo $exam_code; ?>&nbsp;</u> & Exam Period <u>&nbsp;<?php echo $exam_period; ?>&nbsp;</u></div>
        
            <?php 
            if(count($exam_code_arr) > 0)
            {
              foreach($exam_code_arr as $exam_code_val)
              { 
                $response_arr = array();  ?>
                <table class="custom_tbl">
                  <thead>
                    <tr><th colspan="8"><?php echo 'Exam Code : '.$exam_code_val.' & Exam Period : '.$exam_period; ?></th></tr>
                    <tr>
                      <th>Sr. No.</th>
                      <th>API & Table Name</th>
                      <th>Status</th>
                      <th>Total Record</th>
                      <th>Success Record</th>
                      <th>Fail Record</th>
                      <th>Message</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $response_arr[] = $bcbf_exam_master_res = $this->bcbf_exam_master($exam_code_val, $exam_period);
                      $response_arr[] = $bcbf_subject_master_res = $this->bcbf_subject_master($exam_code_val, $exam_period);
                      $response_arr[] = $bcbf_misc_master_res = $this->bcbf_misc_master($exam_code_val, $exam_period);
                      $response_arr[] = $bcbf_fee_master_res = $this->bcbf_fee_master($exam_code_val, $exam_period);            
                      $response_arr[] = $bcbf_centre_master_res = $this->bcbf_centre_master($exam_code_val, $exam_period);            
                      $response_arr[] = $bcbf_medium_master_res = $this->bcbf_medium_master($exam_code_val, $exam_period);
                      
                      if($bcbf_exam_master_res['flag'] == 'success' && $bcbf_subject_master_res['flag'] == 'success' && $bcbf_misc_master_res['flag'] == 'success' && $bcbf_fee_master_res['flag'] == 'success' && $bcbf_centre_master_res['flag'] == 'success' && $bcbf_medium_master_res['flag'] == 'success')
                      {
                        $response_arr[] = $this->bcbf_exam_activation_master($exam_code_val, $exam_period);
                      }
                      else
                      {
                        $response_arr[] = array('API Title'=>'Exam Activation Master (iibfbcbf_exam_activation_master)', 'api_name'=>'exam_activation_master', 'flag'=>'error', 'total_record'=>0, 'success_record'=>0, 'response_msg'=>'Exam activation API not call due to error in above masters');
                      }
                      
                      $sr_no = 1;
                      $error_cnt = 0;
                      foreach($response_arr as $response_res)
                      { ?>
                        <tr class="<?php if($response_res['flag'] == 'error') { echo "row_error"; } ?>">
                          <td class="text-center"><?php echo $sr_no; ?></td>
                          <td><?php echo $response_res['API Title']; ?></td>
                          <td class="text-center">
                            <div class='btn <?php if($response_res['flag'] == 'error') { echo 'btn_error'; } ?>'>
                              <?php echo $response_res['flag']; if($response_res['flag'] == 'error') { $error_cnt++; } ?>
                            </div>
                          </td>
                          <td class="text-center"><?php echo $response_res['total_record']; ?></td>
                          <td class="text-center"><?php echo $response_res['success_record']; ?></td>
                          <td class="text-center"><?php echo ($response_res['total_record'] - $response_res['success_record']); ?></td>
                          <td><?php echo $response_res['response_msg']; ?></td>
                          <td class="text-center"><a class='btn <?php if($response_res['flag'] == 'error') { echo 'btn_error'; } ?>' href="<?php echo site_url('iibfbcbf/exam_api_iibfbcbf/show_api_response/'.$exam_code_val.'/'.$exam_period.'/'.$response_res['api_name']); ?>" target="_blank">API Response</a></td>                    
                        </tr>
                      <?php $sr_no++;
                      } ?>
                      
                      <tr class="final_result <?php if($error_cnt > 0) { echo "row_error"; } ?>">
                        <td colspan="8">
                          <?php if($error_cnt == 0) 
                          { 
                            echo 'Status : Exam successfully activated';
                          } 
                          else { echo 'Status : Exam is not activated due to error in '.$error_cnt.' masters'; } ?> 
                        </td>
                      </tr>
                  </tbody>
                </table>
              <?php }
            } ?>
          </body>
        </html>        
      <?php }
    }  
    
    //START : API CODE TO INSERT EXAM MASTER DATA INTO DATABASE TABLE
    public function bcbf_exam_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'exam_master';

      if($exam_code == '' || $exam_period == '')
      {
        $response_msg = 'The exam code or exam period is blank';
      }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          if(is_array($decoded_res) && count($decoded_res) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exam_code'];
            
              if($response_exam_code ==  $exam_code)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_code', $response_exam_code, $exam_period);                  
                }

                $add_data = array();
                $add_data['exam_code'] = trim($response_exam_code);
                $add_data['description'] = trim($res['exam_desc']);

                $exam_type = 0;
                if(array_key_exists($response_exam_code,$this->exam_type_arr))
                {
                  $exam_type = $this->exam_type_arr[$response_exam_code];
                }
                $add_data['exam_type'] = trim($exam_type);
                
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }
              $i++;
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

      $result_arr['API Title'] = 'Exam Master ('.$tbl_name.')';
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT EXAM MASTER DATA INTO DATABASE TABLE

    //START : API CODE TO INSERT SUBJECT MASTER DATA INTO DATABASE TABLE
    public function bcbf_subject_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_subject_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'subject_master';

      if($exam_code == '' || $exam_period == '') { $response_msg = 'The exam code or exam period is blank'; }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          if(is_array($decoded_res) && count($decoded_res) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exm_CD'];
              $response_exam_period = $res['exm_PRD'];
            
              if($response_exam_code == $exam_code && $response_exam_period == $exam_period)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_subject_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_code', $response_exam_code, $exam_period); 
                }

                $add_data = array();
                $add_data['exam_code'] = trim($response_exam_code);
                $add_data['exam_period'] = trim($response_exam_period);
                $add_data['part_no'] = trim($res['prt_NO']);
                $add_data['syllabus_code'] = trim($res['syl_CD']);
                $add_data['subject_code'] = trim($res['sub_CD']);
                $add_data['subject_description'] = trim($res['sub_DSC']);
                $add_data['group_code'] = trim($res['grp_CD']);
                $add_data['exam_date'] = trim($res['exm_DT']);
                $add_data['exam_time'] = trim($res['exm_TIME'])."00";
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_subject_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }
              $i++;
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

      $result_arr['API Title'] = 'Subject Master ('.$tbl_name.')';
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT SUBJECT MASTER DATA INTO DATABASE TABLE
    
    //START : API CODE TO INSERT MISC MASTER DATA INTO DATABASE TABLE
    public function bcbf_misc_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_misc_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'misc_master';

      if($exam_code == '' || $exam_period == '') { $response_msg = 'The exam code or exam period is blank'; }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          if(is_array($decoded_res) && isset($decoded_res[0]) && count($decoded_res[0]) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exam_cd'];
              $response_exam_period = $res['exam_period'];
            
              if($response_exam_code == $exam_code && $response_exam_period == $exam_period)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_misc_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_code', $response_exam_code, $exam_period);
                }

                $add_data = array();
                $add_data['exam_code'] = trim($response_exam_code);
                $add_data['exam_period'] = trim($response_exam_period);
                $add_data['exam_month'] = trim($res['exam_mth']);
                $add_data['trg_value'] = trim($res['trg_val_upto']);
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_misc_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }
              $i++;
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

      $result_arr['API Title'] = 'Misc Master ('.$tbl_name.')';      
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT MISC MASTER DATA INTO DATABASE TABLE
    
    //START : API CODE TO INSERT FEE MASTER DATA INTO DATABASE TABLE
    public function bcbf_fee_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_fee_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'fee_master';

      if($exam_code == '' || $exam_period == '') { $response_msg = 'The exam code or exam period is blank'; }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          //_pa($decoded_res,1);
          if(is_array($decoded_res) && count($decoded_res) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exam_cd'];
              $response_exam_period = $res['exam_period'];

              if($response_exam_code == $exam_code && $response_exam_period == $exam_period)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_fee_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_code', $response_exam_code, $exam_period); 
                }

                $add_data = array();
                $add_data['exam_code'] = trim($response_exam_code);
                $add_data['exam_period'] = trim($response_exam_period);
                $add_data['part_no'] = trim($res['part_no']);
                $add_data['syllabus_code'] = trim($res['syl_cd']);
                $add_data['member_category'] = trim($res['mem_category']);
                $add_data['group_code'] = trim($res['grp_cd']);
                $add_data['fee_amount'] = trim($res['fee_amt']);
                $add_data['sgst_amt'] = trim($res['sgst_amt']);
                $add_data['cgst_amt'] = trim($res['cgst_amt']);
                $add_data['igst_amt'] = trim($res['igst_amt']);
                $add_data['cs_tot'] = trim($res['cs_tot']);
                $add_data['igst_tot'] = trim($res['igst_tot']);
                $add_data['fr_date'] = trim($res['frm_date']);
                $add_data['to_date'] = trim($res['to_date']);
                $add_data['exempt'] = trim($res['exempt']);
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_fee_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }  
              $i++;            
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
      $result_arr['API Title'] = 'Fee Master ('.$tbl_name.')';      
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT FEE MASTER DATA INTO DATABASE TABLE

    //START : API CODE TO INSERT CENTRE MASTER DATA INTO DATABASE TABLE
    public function bcbf_centre_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_centre_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'centre_master';

      if($exam_code == '' || $exam_period == '') { $response_msg = 'The exam code or exam period is blank'; }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          if(is_array($decoded_res) && count($decoded_res) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exm_CD'];
              $response_exam_period = $res['exm_PRD'];

              if($response_exam_code == $exam_code && $response_exam_period == $exam_period)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_centre_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_name', $response_exam_code, $exam_period);
                }

                $add_data = array();
                $add_data['exam_name'] = trim($response_exam_code);
                $add_data['exam_period'] = trim($response_exam_period);
                $add_data['centre_code'] = trim($res['ctr_CD']);
                $add_data['centre_name'] = trim($res['ctr_NAM']);
                $add_data['state_code'] = trim($res['ste_CD']);
                $add_data['state_description'] = trim($res['ste_DSC']);
                $add_data['exammode'] = trim($res['mode_OF_EXAM']);
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_centre_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }  
              $i++;            
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
      $result_arr['API Title'] = 'Centre Master ('.$tbl_name.')';      
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT CENTRE MASTER DATA INTO DATABASE TABLE
    
    //START : API CODE TO INSERT MEDIUM MASTER DATA INTO DATABASE TABLE
    public function bcbf_medium_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_medium_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'medium_master';

      if($exam_code == '' || $exam_period == '') { $response_msg = 'The exam code or exam period is blank'; }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          //_pa($decoded_res,1);
          if(is_array($decoded_res) && count($decoded_res) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exm_CD'];
              $response_exam_period = $res['exm_PRD'];

              if($response_exam_code == $exam_code && $response_exam_period == $exam_period)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_medium_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_code', $response_exam_code, $exam_period);
                }

                $add_data = array();
                $add_data['exam_code'] = trim($response_exam_code);
                $add_data['exam_period'] = trim($response_exam_period);
                $add_data['medium_code'] = trim($res['med_CD']);
                $add_data['medium_description'] = trim($res['med_DESC']);
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_medium_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }  
              $i++;            
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
      $result_arr['API Title'] = 'Medium Master ('.$tbl_name.')';      
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT MEDIUM MASTER DATA INTO DATABASE TABLE

    //START : API CODE TO INSERT EXAM ACTIVATION MASTER DATA INTO DATABASE TABLE
    public function bcbf_exam_activation_master($exam_code='', $exam_period='')
    {
      $flag = 'error';
      $response_msg = '';		
      $tbl_name = 'iibfbcbf_exam_activation_master';	
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'exam_activation_master';

      if($exam_code == '' || $exam_period == '') { $response_msg = 'The exam code or exam period is blank'; }
      else
      {
        $api_response = $this->common_api_call($exam_code,$exam_period,$api_name);
        if($api_response['api_res_flag'] == 'success')
        {
          $decoded_res = json_decode($api_response['api_res_response'],true);
          //_pa($decoded_res,1);
          if(is_array($decoded_res) && count($decoded_res) > 0)
          {
            $total_record = count($decoded_res);
            $i = 0;
            foreach($decoded_res as $res)
            {
              $response_exam_code = $res['exam_cd'];
              $response_exam_period = $res['exam_period'];

              if($response_exam_code == $exam_code && $response_exam_period == $exam_period)
              {
                //START : DELETE OLD RECORD FROM table iibfbcbf_exam_activation_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
                if($i == 0) 
                { 
                  $this->inc_delete_existing_master_records($tbl_name, 'exam_code', $response_exam_code, $exam_period);
                }

                $add_data = array();
                $add_data['exam_code'] = trim($response_exam_code);
                $add_data['exam_period'] = trim($response_exam_period);
                $add_data['exam_from_date'] = trim($res['activation_from_date']);
                $add_data['exam_from_time'] = '10:00:00';
                $add_data['exam_to_date'] = trim($res['activation_to_date']);
                $add_data['exam_to_time'] = '23:59:00';
                $insert_id = $this->master_model->insertRecord($tbl_name,$add_data, true);
                if($insert_id > 0)
                {
                  $success_cnt++;                  	
                }
                else
                {
                  $error_cnt++;
                }
                //END : DELETE OLD RECORD FROM table iibfbcbf_exam_activation_master FOR PROVIDED EXAM CODE AND INSERT NEW RECORD
              }  
              $i++;            
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
      $result_arr['API Title'] = 'Exam Activation Master ('.$tbl_name.')';      
      $result_arr['api_name'] = $api_name;
      $result_arr['flag'] = $flag;
      $result_arr['total_record'] = $total_record;
      $result_arr['success_record'] = $success_cnt;
			$result_arr['response_msg'] = $response_msg;
			
			return $result_arr;
    }//END : API CODE TO INSERT EXAM ACTIVATION MASTER DATA INTO DATABASE TABLE

    function inc_delete_existing_master_records($tbl_name='', $field_name='', $response_exam_code='', $exam_period='')
    {
      if($tbl_name != '' && $field_name != '' && $response_exam_code != '' && $exam_period != '')
      {
        if(in_array($response_exam_code, array(1037,1038,1039,1040)))//DELETE EXISTING RECORDS FOR EXAM CODE 1037,1038,1039,1040 
        { 
          $this->master_model->deleteRecord($tbl_name, $field_name, $response_exam_code);
        }
        else if(in_array($response_exam_code, array(1041,1042,1057)))
        {
          $exam_activation_data = $this->master_model->getRecords('iibfbcbf_exam_activation_master',array('exam_code'=>$response_exam_code),'exam_code, exam_period, exam_from_date, exam_to_date');

          if(count($exam_activation_data) > 0)
          {
            if($tbl_name == 'iibfbcbf_exam_master')//DELETE EXISTING RECORDS FROM 'EXAM MASTER' AS WE REQUIRE ONLY ONE RECORD IN EXAM MASTER FOR ONE EXAM CODE
            {
              $this->master_model->deleteRecord($tbl_name, $field_name, $response_exam_code);
            }
            else
            {
              foreach($exam_activation_data as $exam_activation_res)
              {
                $delete_flag = 0;
                //DELETE EXISTING RECORDS IF EXAM ACTIVATION'S EXAM CODE AND EXAM PERIOD IS SAME AS REQUESTED EXAM CODE AND EXAM PERIOD
                if($exam_activation_res['exam_code'] == $response_exam_code && $exam_activation_res['exam_period'] == $exam_period)
                {
                  $delete_flag = 1;
                }
                /* else if($tbl_name != 'iibfbcbf_exam_activation_master')
                {
                  if($exam_activation_res['exam_to_date'] < date('Y-m-d'))
                  {
                    $delete_flag = 1;
                  }
                } */
                else if($exam_activation_res['exam_to_date'] < date('Y-m-d', strtotime("-15days")))//DELETE EXISTING RECORD IF EXAM ACTIVATION'S 'EXAM TO DATE' IS LESS THAN CURRENT DATE - 15 DAYS
                {
                  $delete_flag = 1;
                }
  
                if($delete_flag == 1)
                {
                  $this->db->where($field_name, $response_exam_code);
                  $this->db->where('exam_period', $exam_activation_res['exam_period']);
                  $this->db->delete($tbl_name);
                  $this->db->affected_rows();
                }
              }
            }
          }
        }
      }      
    }

    public function common_api_call($exam_code=0, $exam_period=0,$type='')
    {
      $api_res_flag = 'error';
      $api_res_msg = '';

      $api_url="";
      if($type == 'exam_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getExamDetails/".$exam_code."/".$exam_period."/1"; //OLD API 					
        $api_url="http://10.10.233.76:8090/masterData/getExamDetails/".$exam_code."/".$exam_period."/1";	//NEW API ADDED BY SAGAR ON 2024-03-19
      }
      else if($type == 'subject_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getSubjectMasterDetails/".$exam_code."/".$exam_period."/1"; //OLD API 			
        $api_url="http://10.10.233.76:8090/masterData/getSubjectMasterDetails/".$exam_code."/".$exam_period."/1"; //NEW API ADDED BY SAGAR ON 2024-03-19
      }
      else if($type == 'misc_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getMiscParamMasterDetails/".$exam_code."/".$exam_period."/1"; //OLD API 					
        $api_url="http://10.10.233.76:8090/masterData/getMiscParamMasterDetails/".$exam_code."/".$exam_period."/1"; //NEW API ADDED BY SAGAR ON 2024-03-19
      }
      else if($type == 'fee_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getFeeMasterDetails/".$exam_code."/".$exam_period."/1"; //OLD API 				
        $api_url="http://10.10.233.76:8090/masterData/getFeeMasterDetails/".$exam_code."/".$exam_period."/1"; //NEW API ADDED BY SAGAR ON 2024-03-19
      }
      else if($type == 'centre_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getCenterMasterDetails/".$exam_code."/".$exam_period."/1"; //OLD API 
        $api_url="http://10.10.233.76:8090/masterData/getCenterMasterDetails/".$exam_code."/".$exam_period."/1"; //NEW API ADDED BY SAGAR ON 2024-03-19
      }
      else if($type == 'medium_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getMediumMasterDetails/".$exam_code."/".$exam_period."/1"; //OLD API 
        $api_url="http://10.10.233.76:8090/masterData/getMediumMasterDetails/".$exam_code."/".$exam_period."/1"; //NEW API ADDED BY SAGAR ON 2024-03-19
      }
      else if($type == 'exam_activation_master')
      {
        //$api_url="http://10.10.233.66:8091/masterData/getExamActivateMasterDetails/".$exam_code."/".$exam_period."/1"; //OLD API 
        $api_url="http://10.10.233.76:8090/masterData/getExamActivateMasterDetails/".$exam_code."/".$exam_period."/1"; //NEW API ADDED BY SAGAR ON 2024-03-19
      }
      
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
      /* echo '<pre>';print_r($api_result_arr); exit;			
      echo '<pre>';print_r(json_decode($api_res_msg,1)); exit; */			
			return $api_result_arr;
    }

    function show_api_response($exam_code,$exam_period,$api_name)
    { ?>
      <html>
        <head>
          <style>
            body { width: 95%; font-family: calibri; margin: 10px auto; font-size:14px; }
            .custom_tbl { border: 1px solid #000; border-collapse: collapse; margin: 20px auto; width: 100%; }
            .custom_tbl tr > th { border: 1px solid #000; border-collapse: collapse; background: #ccc; padding: 10px; text-align: center; font-size:16px; vertical-align:top; }
            .custom_tbl tr > td { border: 1px solid #000; border-collapse: collapse; padding: 5px 10px; font-size:14px; }
            .text-center { text-align: center; } 
            .hide { display:none; }
            .row_error, .row_error td, .row_error th { color: #c60909; font-weight: bold;  }
            .final_result td { text-align: center; font-size: 16px !important; padding: 10px !important; background: #238823; color: #fff; font-weight: bold; }
            .row_error.final_result td { background: red; }
            .btn { background: #238823; padding: 3px 10px 4px; display: block; color: #fff; text-decoration: none; white-space: nowrap; border-radius: 3px; opacity: 0.9; font-size: 12px; min-width: 60px; font-weight: bold; text-transform: capitalize; }
            .btn:hover { opacity: 1; }
            .btn.btn_error { background: red; }
          </style>
        </head>
          
        <body>
          <div style="font-weight: 600; font-size: 18px; text-align: center; background: #2fa9e3; color: #0c1715; padding: 8px 0px;">IIBFBCBF Masters Data Using API : Exam Code <u>&nbsp;<?php echo $exam_code; ?>&nbsp;</u> & Exam Period <u>&nbsp;<?php echo $exam_period; ?>&nbsp;</u></div>

          <?php 
          $exam_code_arr = explode(",",$exam_code);
          foreach($exam_code_arr as $res)
          {
            $api_response = $this->common_api_call($res,$exam_period,$api_name);
            if($api_response['api_res_flag'] == 'success')
            {
                        
              $decoded_res = json_decode($api_response['api_res_response'],true);
              //_pa($decoded_res,1);
              if(is_array($decoded_res) && count($decoded_res) > 0)
              {
                //echo '<br><br><b>Response : </b>'.$api_response['api_res_response'];
                //_pa($decoded_res);  ?>

                <table class="custom_tbl" style="margin-bottom:-21px;">
                  <thead>
                    <tr>
                      <th><?php echo 'Exam Code : '.$res.' & Exam Period : '.$exam_period; ?></th>
                      <th><?php echo '<b>Api for : </b>'.$api_name; ?></th>
                      <th><?php echo '<b>Api URL : </b>'.$api_response['api_url']; ?></th>
                    </tr>
                  </thead>
                </table>

                <?php 
                $response_field_arr = array();
                if($api_name == 'exam_master')
                { 
                  $response_field_arr = array('exam_code', 'exam_desc', 'qly_exam1', 'qly_part1', 'qly_exam2', 'qly_part2', 'qly_exam3', 'qly_part3', 'elearning_applicable'); 
                }
                else if($api_name == 'subject_master')
                {
                  $response_field_arr = array('exm_CD', 'exm_PRD', 'syl_CD', 'sub_CD', 'sub_DSC', 'prt_NO', 'grp_CD', 'exm_DT', 'exm_TIME'); 
                }
                else if($api_name == 'misc_master')
                {
                  $response_field_arr = array('exam_cd', 'exam_period', 'exam_mth', 'trg_val_upto'); 
                }
                else if($api_name == 'fee_master')
                {
                  $response_field_arr = array('exam_cd', 'exam_period', 'part_no', 'syl_cd', 'mem_category', 'grp_cd', 'fee_amt', 'sgst_amt', 'cgst_amt', 'igst_amt', 'cs_tot', 'igst_tot', 'elearning_fee_amt', 'elearning_sgst_amt', 'elearning_cgst_amt', 'elearning_igst_amt', 'elearning_cs_tot', 'elearning_igst_tot', 'frm_date', 'to_date', 'exempt'); 
                }
                else if($api_name == 'centre_master')
                {
                  $response_field_arr = array('exm_CD', 'exm_PRD', 'ctr_CD', 'ctr_NAM', 'ste_CD', 'ste_DSC', 'mode_OF_EXAM'); 
                }
                else if($api_name == 'medium_master')
                {
                  $response_field_arr = array('med_CD', 'med_DESC', 'exm_CD', 'exm_PRD'); 
                }
                else if($api_name == 'exam_activation_master')
                {
                  $response_field_arr = array('exam_cd', 'exam_period', 'activation_from_date', 'activation_to_date'); 
                }

                if(count($response_field_arr) > 0)
                { ?>
                  <div style="overflow-y:auto;">
                    <table class="custom_tbl">
                      <thead>
                        <tr>
                          <th>Sr. No.</th>
                          <?php foreach($response_field_arr as $header)
                          { ?>
                            <th><?php echo $header; ?></th>
                          <?php  }  ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          if(count($decoded_res) > 0)
                          {
                            $sr_no = 1;
                            foreach($decoded_res as $res)
                            { ?>
                              <tr>
                                <td style="text-align:center;"><?php echo $sr_no; ?></td>
                                <?php $sr_no;
                                foreach($response_field_arr as $header)
                                { ?>
                                  <td><?php echo $res[$header]; ?></td>
                                <?php } ?>
                              </tr>
                            <?php $sr_no++;
                            }                           
                          }
                          else
                          { ?>
                            <tr><td colspan="<?php echo count($response_field_arr)+1; ?>" style="text-align:center;">No response from API</td></tr>
                          <?php } ?>
                      </tbody>
                    </table>
                  </div>
                <?php }
              }
              else
              {
                echo "<br><br><b>Response : </b>No response from the API";
              }
            }
            else
            {
              echo "<br><br><b>Response : </b>".$api_response['api_res_response'];          
            }

            echo '<br><br><br>';
          } ?>
        </body>
      </html>
    <?php }

    function test_eligible_api()
    {
      $r = $this->Iibf_bcbf_model->iibf_bcbf_eligible_master_api(1037,2,54585475);
      _pa($r);
    }
  }