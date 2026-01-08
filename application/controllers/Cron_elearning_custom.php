<?php
  /********************************************************************
  * Description: Daily Cron - Generate E-learning separate module data into csv format on ESDS FTP
  * Created BY: Pratibha Purkar
  * Created On: 22-06-2021
  * Update By: Sagar Matale
  * Updated on: 01-07-2021
	********************************************************************/
  
  defined('BASEPATH') OR exit('No direct script access allowed');
  class Cron_elearning_custom extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      //$this->load->model('UserModel');
      //$this->load->helper('pagination_helper'); 
      //$this->load->library('pagination');
      $this->load->model('Master_model'); 
      $this->load->model('log_model');
      $this->load->model('Emailsending');
      error_reporting(E_ALL);
      ini_set('display_errors', 1); 
      ini_set("memory_limit", "-1");
    }    
    
    /* Send data to kesdee vendor */
    public function elearning_kesdee()
    {
      $vendor_name = 'KESDEE';
      $cron_date = '2022-04-26'; //date('Y-m-d'); //
      $this->generate_data_for_vendors_common($vendor_name, $cron_date);
    }
    
    /* Send data to SIFY vendor */
    public function elearning_sify()
    {
      $vendor_name = 'SIFY';
      $cron_date = '2022-04-26'; // date('Y-m-d'); //
      $this->generate_data_for_vendors_common($vendor_name, $cron_date);
    }
    
    //START : COMMON FUNCTION FOR ALL VENDORS TO GENERATE DATA INTO CSV AND STORED IT ON ESDS FTP
    public function generate_data_for_vendors_common($vendor_name='', $cron_date='')
    {
      if($vendor_name != '' && $cron_date != '')
      {
        $current_date = date("Ymd", strtotime($cron_date));
        $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($cron_date)));
        $success = $error = array();
        $start_time = date("Y-m-d H:i:s");
        
        $cron_file_dir = $this->create_directory("uploads/rahultest/elearning_".$vendor_name."/".$current_date); //create required directory
        
        //$this->log_model->cronlog("Cron for E-learning separate module data generation on ESDS FTP for vendor ".$vendor_name." Start", json_encode(array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => ""))); //store log in table cronlogs
        
        if(file_exists($cron_file_dir))
        {
          $file_csv_name = "iibfportal_elearning_".$vendor_name."_".$current_date.".csv";
          $file_csv_action = fopen($cron_file_dir.'/'.$file_csv_name, 'w');
          
          $file_txt_name = "logs_elearning_".$vendor_name."_".$current_date.".txt";
          $file_txt_action = fopen($cron_file_dir.'/'.$file_txt_name, 'a');
          fwrite($file_txt_action, "\n************ Cron for E-learning separate module data generation on ESDS FTP for vendor ".$vendor_name." Start - ".$start_time." *********** \n");
          
          $subject_code = $this->get_subject_code_by_vendor($vendor_name);
          $this->db->where_in('ms.subject_code', $subject_code);
          
          $select = 'ms.el_sub_id, ms.regid, ms.regnumber, ms.pt_id, ms.transaction_no, ms.receipt_no, ms.exam_code, ms.subject_code, ms.subject_description, ms.fee_amount, ms.sgst_amt, ms.cgst_amt, ms.igst_amt, ms.cs_tot, ms.igst_tot, ms.status, ms.created_on, ms.updated_on, pt.member_regnumber, pt.gateway, pt.amount, pt.date, pt.transaction_no AS p_transaction_no, pt.receipt_no AS p_receipt_no, pt.pay_type, pt.ref_id, pt.description, pt.transaction_details, pt.status AS p_status, er.firstname, er.middlename, er.lastname, er.email, er.mobile, er.state';  
					$this->db->group_by('ms.el_sub_id');
          $this->db->join('payment_transaction pt','pt.id = ms.pt_id','LEFT');
          $this->db->join('spm_elearning_registration er','er.regid = ms.regid AND er.isactive = 1','LEFT');           
          $exam_data = $this->Master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1', 'pt.pay_type'=>'20', 'pt.status'=>'1' , 'DATE(ms.created_on)'=>$yesterday),$select);
          
          fwrite($file_txt_action, "\n".$this->db->last_query()."\n");
          
          $record_cnt = 0;
          if(count($exam_data))
          {
            // Column headers			
            $csv_headers = "First Name, Middle name, Last Name, Mem. Number, Email ID, Mobile, State, Country, Exam Code, Sub Code, Sub Desc, Receipt Number \n";
            fwrite($file_csv_action, $csv_headers);
            
            $data = '';
            foreach($exam_data as $exam)
            {
              $firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
              $middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
              $lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
              $mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
              
              //First Name|Middle name|Last Name|Mem. Number|Email ID|Mobile|State|Country|Exam Code|Sub Code|Sub Desc|Transaction Number|Receipt Number
              $data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$exam['email'].','.$mobile.','.$exam['state'].','.'INDIA'.','.$exam['exam_code'].','.$exam['subject_code'].','.$exam['subject_description'].','.$exam['receipt_no'].','.''."\n";
              $record_cnt++;
            }
            
            if(fwrite($file_csv_action, $data)) { $success['cand_exam'] = "Elearning data sending File Generated Successfully For ".$vendor_name; }
            else { $error['cand_exam'] = "Error While Generating Elearning CSV Details File For ".$vendor_name; }
            fwrite($file_txt_action, "\nTotal Exam Applications - ".$record_cnt." For ".$vendor_name." \n");
          }
          else
          {
            fwrite($file_txt_action, "\nNo data found for the date: ".$yesterday." For ".$vendor_name." \n");
            $success[] = "No data found for the date For ".$vendor_name;
          }
          fclose($file_csv_action);
          
          // File Rename Functinality
          $oldPath = $cron_file_dir."/iibfportal_elearning_".$vendor_name."_".$current_date.".csv";
          $newPath = $cron_file_dir."/iibfportal_elearning_".$vendor_name."_".$current_date.date('His')."_".$record_cnt.".csv";
          rename($oldPath,$newPath);
          
          $OldName = "iibfportal_elearning_".$vendor_name."_".$current_date.".csv";
          $NewName = "iibfportal_elearning_".$vendor_name."_".$current_date.date('His')."_".$record_cnt.".csv";
          
          $add_data['CurrentDate'] = date('Y-m-d', strtotime($current_date));
          $add_data['vendor_name'] = $vendor_name;
          $add_data['old_file_name'] = $OldName;
          $add_data['new_file_name'] = $NewName;
          $add_data['record_count'] = $record_cnt;
          $add_data['createdon'] = date('Y-m-d H:i:s');
          $this->master_model->insertRecord('spm_elearning_cron_csv', $add_data, true);
          
          //$this->log_model->cronlog("Cron for E-learning separate module data generation on ESDS FTP for vendor ".$vendor_name." End", json_encode(array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => date("Y-m-d H:i:s")))); //store log in table cronlogs
          
          fwrite($file_txt_action, "\n"."************ Cron for E-learning separate module data generation on ESDS FTP for vendor ".$vendor_name." End - ".date("Y-m-d H:i:s")." *************"."\n");
          fclose($file_txt_action);
        }        
      }
    }//END : COMMON FUNCTION FOR ALL VENDORS TO GENERATE DATA INTO CSV AND STORED IT ON ESDS FTP
    
    //START : GET SUBJECT CODE USING VENDOR NAME : ADDED BY SAGAR ON 30-06-2021
    public function get_subject_code_by_vendor($vendor_name='')
    {
      $subject_codes = array();
      
      $this->db->join('spm_elearning_subject_master sm', 'sm.exam_code = em.exam_code', 'INNER', false);
      $subject_code_data = $this->Master_model->getRecords('spm_elearning_exam_master em',array('em.is_active'=>'1','em.vendor_name'=>$vendor_name),'em.exam_id, em.exam_code, em.exam_name, em.vendor_name, sm.id AS SubjectId, sm.subject_code');
      
      if(count($subject_code_data) > 0)
      {
        foreach($subject_code_data as $res)
        {
          $subject_codes[] = $res['subject_code'];
        }
      }
      
      return $subject_codes;
    }//END : GET SUBJECT CODE USING VENDOR NAME
    
    //START : CREATE DIRECTORY UPTO N LEVEL : ADDED BY SAGAR ON 30-06-2021
    function create_directory($directory_name='')
    {
      $chk_dir_name = '';
      if($directory_name != '')
      {
        $directory_name_arr = explode("/",$directory_name);
        
        if(count($directory_name_arr) > 0)
        {
          $chk_dir_name = '.';
          foreach($directory_name_arr as $directory_name_res)
          {
            $chk_dir_name .= "/".$directory_name_res;
            
            if(!is_dir($chk_dir_name))
            { 
              $dir = mkdir($chk_dir_name,0700);					
              $myfile = fopen($chk_dir_name."/index.php", "w") or die("Unable to open file!");
              $txt = ""; fwrite($myfile, $txt); fclose($myfile);
            }
          }
        }
      }      
      return $chk_dir_name;
    }//END : CREATE DIRECTORY UPTO N LEVEL
  }
