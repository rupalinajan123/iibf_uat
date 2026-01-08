<?php 
  /********************************************************************************************************************
  ** Description: Controller for OLD BCBF Exam
  ** Created BY: Anil Sonawane On 16-08-2024 for OLD BCBF Exam Validation
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Bcbfexam extends CI_Controller 
  {
    //START : OLD BCBF Exam Validation
    public function check_bank_bc_id_no()
    {
      $this->load->model('master_model');

      $flag = 'error';
      $response_msg = '';   
      $tbl_name = 'fedai_institution_master'; 
      $total_record = '';
      $success_cnt = $error_cnt = 0;
      $api_name = 'fedai_master';

      $name_of_bank_bc = isset($_POST['name_of_bank_bc']) ? $_POST['name_of_bank_bc'] : '';
      $ippb_emp_id = isset($_POST['ippb_emp_id']) ? $_POST['ippb_emp_id'] : '';
      $regnumber = isset($_POST['regnumber']) ? $_POST['regnumber'] : '';
      $is_ippb = isset($_POST['is_ippb']) ? $_POST['is_ippb'] : '';
      if($name_of_bank_bc != "" && $ippb_emp_id != "")
      {
        if(isset($_POST['mem_type']) && $_POST['mem_type'] == "NM"){

          if($is_ippb == "1"){
            $chk_member_registration = $this->master_model->getRecords('member_registration', array('name_of_bank_bc' => $name_of_bank_bc, 'bank_bc_id_no' => $ippb_emp_id, 'isactive' => '1'), 'regnumber');
            if(isset($chk_member_registration) && count($chk_member_registration) > 0){
                $regnumber = $chk_member_registration[0]['regnumber'];
            }
          } 
          
          if($regnumber != ""){
            $this->db->where("regnumber != ",$regnumber);
          }
          $member_registration = $this->master_model->getRecords('member_registration', array('name_of_bank_bc' => $name_of_bank_bc, 'ippb_emp_id' => $ippb_emp_id, 'isactive' => '1'), 'regnumber');
        }else{
          $this->db->where("regnumber != ",$regnumber);
          $member_registration = $this->master_model->getRecords('member_registration', array('name_of_bank_bc' => $name_of_bank_bc, 'ippb_emp_id' => $ippb_emp_id, 'isactive' => '1'), 'regnumber');
        }
        
         //echo $this->db->last_query();
         //echo count($member_registration)."===";
         if(isset($member_registration) && count($member_registration) > 0)
         {
             echo "Bank BC ID No Already Exists for selected Name of Bank.";     
         }else{
            echo "";
         } 
      }  
      //echo "<pre>"; print_r($result_arr);
    } //END : API CODE TO INSERT FEDAI MASTER DATA INTO DATABASE TABLE
 
  }