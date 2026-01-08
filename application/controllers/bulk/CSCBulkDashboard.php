<?php
/*
* Controller Name :  CSCBulkDashboard.php
* Created By      :  Pooja Mane
* Created Date    :  21-02-2024
* Use : This dashboard is created to send data to CSC for Bulk Exams
* Bulk Exam Codes : 1027,1028,1030,1033,1034
*/
defined('BASEPATH') OR exit('No direct script access allowed');
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/
class CSCBulkDashboard extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('UserModel');
      $this->load->model('Master_model');
      $this->UserID = $this->session->id;
      $this->load->helper('pagination_helper');
      $this->load->library('pagination');
      $this->load->helper('upload_helper');
      $this->load->helper('general_helper');
      $this->load->library('email');
      $this->load->library('upload'); 
      $this->load->model('log_model'); 
      $this->load->model('Emailsending');
      $this->load->model('KYC_Log_model');
      $this->load->library('session');
   }

   public function exam_registered_data() 
   {
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
      header("Cache-Control: no-store, no-cache, must-revalidate"); 
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      $data=array();
      $this->load->view('bulk/exam_registered_data', $data);
   }

   public function showExamRegisteredData() {

      // $_GET['exam_date'] = '2024-02-02';
       // echo'<pre>';print_r($_GET);exit;
      if(isset($_GET['member_ids']) && !empty($_GET['member_ids'])) {
         $member_nos=explode(',',$_GET['member_ids']);
               foreach($member_nos as $m)
                  $member_no[]=trim($m);
                  //echo'<pre>';print_r($_GET['exam_code'];);exit;
      }
      if(isset($_GET['exam_date']) && !empty($_GET['exam_date'])) {
         $exam_date=date('Y-m-d',strtotime($_GET['exam_date']));
      }
      if(isset($_GET['exam_code']) && !empty($_GET['exam_code'])){
         $exam_code=$_GET['exam_code'];
      }
      if(isset($_GET['exam_period']) && !empty($_GET['exam_period'])){
         $exam_period=$_GET['exam_period'];
      }
      // $member_no = array(802544895);

      $select = 'a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';

      
      
      // $this->db->where("((a.selected_vendor IS NULL) OR (a.selected_vendor='csc'))");
            
      
      if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
         $this->db->where_in('a.regnumber',$member_no);
      if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
         $this->db->where('d.exam_date',$exam_date);
      if(isset($_GET['exam_code']) && !empty($_GET['exam_code'])){
         $exam_code=$_GET['exam_code'];
      }
      if(isset($_GET['exam_period']) && !empty($_GET['exam_period'])){
         $exam_period=$_GET['exam_period'];
      }

      $this->db->where("a.exam_code",$exam_code);
      $this->db->where("a.exam_period",$exam_period);
      // $this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
      $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
      $this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
      $this->db->where('remark',1);
      // $this->db->where('pay_type',2);
      // $this->db->where('status',1);
      $this->db->where('isactive','1');
      $this->db->where('isdeleted',0);
      $this->db->where('pay_status',1);
      if(isset($_GET['search']['value']) && !empty($_GET['search']['value']))
         $this->db->where("a.regnumber like '%".$_GET['search']['value']."%' ");

      $totalRecordCount = $this->Master_model->getRecordCount("member_exam a");
      
      // echo 'sql1>>*'.$this->db->last_query();//exit;
      // echo'<pre>';
      // // print_r($totalRecordCount);
      // echo $totalRecordCount;//die;
      // $this->db->where("((a.exam_code = '1030') OR (a.exam_code = '1033'))");
      
      
      if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
         $this->db->where_in('a.regnumber',$member_no);
      if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
         $this->db->where('d.exam_date',$exam_date);
         else
         //$this->db->where('d.exam_date >=',date('Y-m-d'));//uncomment this
      if(isset($_GET['exam_code']) && !empty($_GET['exam_code'])){
         $exam_code=$_GET['exam_code'];
      }
      if(isset($_GET['exam_period']) && !empty($_GET['exam_period'])){
         $exam_period=$_GET['exam_period'];
      }

      $this->db->where("a.exam_code",$exam_code);
      $this->db->where("a.exam_period",$exam_period);
      
      // $this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
      $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
      $this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
      $this->db->where('remark',1);
      $this->db->where("a.exam_code",$exam_code);
      $this->db->where("a.exam_period",$exam_period);
      $this->db->where('isactive','1');
      $this->db->where('isdeleted',0);
      $this->db->where('pay_status',1);
      if(isset($_GET['search']['value']) && !empty($_GET['search']['value']))
         $this->db->where(" a.regnumber like '%".$_GET['search']['value']."%' ");

      $orderByCols=array('a.regnumber','c.firstname','c.email','c.mobile');//
      $getorderfield=$_GET['order'][0]['column'];
      $ascdesc=$_GET['order'][0]['dir'];
      $orderby=array($orderByCols[$getorderfield]=>$ascdesc);
      $can_exam_data = $this->Master_model->getRecords('member_exam a', '', $select,$orderby,$_GET['start'],$_GET['length'] );   
      // echo '<br><br>'.$this->db->last_query();exit;
            
         
      $exam_cnt = 0;
      $rowdata=array();
      if (count($can_exam_data)) 
            {
               $i = 1;              
               foreach ($can_exam_data as $exam) 
               {
                  $firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
                  
                     
                  if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
                  {
                     $ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
                     if(count($ex_code)) 
                     {
                        if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
                        {
                           $exam_code = $ex_code[0]['original_val'];
                        }
                        else 
                        {
                           $exam_code = $exam['exam_code'];
                        }
                     }
                     else 
                     {
                        $exam_code = $exam['exam_code'];
                     }
                  }
                  else 
                  {
                     $exam_code = $exam['exam_code'];
                  }
                  
                  $dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
                  $registration_date = date('d-m-Y', strtotime($exam['registration_date']));
                  
                  $address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
                  $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
                  $gender = $exam['gender'];
                  if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
                  $designation = $this->master_model->getRecords('designation_master');
                  if (count($designation)) 
                  {
                     foreach ($designation as $designation_row) 
                     {
                        if ($exam['designation'] == $designation_row['dcode']) 
                                    {
                        $designation_name = $designation_row['dname'];
                                    }
                     }
                  }
                  $designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
                           
                  $medium = $this->master_model->getRecords('medium_master');
                  if(count($medium)) 
                  {
                  foreach ($medium as $medium_row) 
                              {
                     if ($exam['exam_medium'] == $medium_row['medium_code']) 
                                 {
                     $medium_name = $medium_row['medium_description'];
                                 }
                              }
                  }
                  $medium_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
                  
                  $institution_master = $this->master_model->getRecords('institution_master');
                  if(count($institution_master)) 
                  {
                  foreach ($institution_master as $institution_row) 
                              {
                     if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
                                 {
                     $institution_name = $institution_row['name'];
                                 }
                              }
                  }
                  $institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
                  $firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
                  $middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
                  $lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
                  $mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
                  $pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
                           
                  $exam_arr = array('1027' => 'CERTIFICATE EXAM ON KYC-AML & COMPLIANCE FOR EMPLOYEES OF UCO BANK',
                                    '1030' => 'CERTIFICATE IN AML-KYC & COMPLIANCE',
                                    '1026' => 'Certificate in Cash & Currency Chest Management',
                                    '1033' => 'Certificate on AML/KYC & Compliance in Banks',
                                    '1034'=> 'Certificate on Compliance for employees of Indian Overseas Bank');
                  
                  foreach ($exam_arr as $k => $val) 
                           {
                              if ($exam_code == $k)  
                              {
                                 $exam_name = $val;
                              }
                           }
                           
                  $select    = 'regnumber';
                  $this->db->where_in('exam_code', array('991','997'));
                  $this->db->where_in('regnumber', $exam['regnumber']);
                  $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
                  $attempt_count = count($attempt_count);            
                  $attempt_count = $attempt_count - 1;
                        
                  $post_field_arr=array();
                  $post_field_arr['name'] = $firstname.' '.$middlename.' '.$lastname;
                  $post_field_arr['member_number'] = $exam['regnumber'];
                  $post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
                  $post_field_arr['email_id'] = $exam['email'];
                  $post_field_arr['mobile'] = $mobile;
                  $post_field_arr['address'] = $address.' '.$exam['state'].' '.$pincode;
                  $post_field_arr['country'] = 'INDIA';
                  $post_field_arr['exam_code'] = $exam_code;
                  $post_field_arr['course'] = $exam_name;
                  $post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
                  $post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));

                  $rowdata[] = $post_field_arr; 
               }
            }
            
            //echo'<pre>';print_r($totalRecordCount);
            $result=array(
               "draw"=>$_GET['draw'],
                 "recordsTotal"=>($totalRecordCount),
                 "recordsFiltered"=>($totalRecordCount),
                 "aaData"=>$rowdata,
             );
         echo json_encode($result);
         exit();
   }


   public function get_member_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
   {  
      $yesterday = '2022-09-12';
      $recover_images = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
      $scannedphoto_res = $recover_images['scannedphoto'];
      $idproofphoto_res = $recover_images['idproofphoto'];
      $scannedsignaturephoto_res = $recover_images['scannedsignaturephoto'];  
      if($scannedphoto_res == "" || $idproofphoto_res == "" || $scannedsignaturephoto_res == "")
      {        
         $this->db->where("REPLACE(title,' ','') LIKE '%CSCnonregINSERTArray%'");
         $user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,' DATE(date) >= '=>$yesterday));
         if(COUNT($user_log) > 0)
         {
            $description = unserialize($user_log[0]['description']);
            $scannedphoto =  $description['scannedphoto'];
            $scannedsignaturephoto =  $description['scannedsignaturephoto'];
            $idproofphoto =  $description['idproofphoto'];
            $recover_images2 = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
            $scannedphoto_res = $recover_images2['scannedphoto'];
            $idproofphoto_res = $recover_images2['idproofphoto'];
            $scannedsignaturephoto_res = $recover_images2['scannedsignaturephoto'];
         }
      }
      $data['scannedphoto'] = $scannedphoto_res;
      $data['idproofphoto'] = $idproofphoto_res;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
      return $data;
   }

   public function recover_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
   {  
      //// FOR PHOTO
      if($scannedphoto != '' && $scannedphoto != 'p_'.$regnumber.'.jpg')
      {                    
      $attachpath = "uploads/photograph/".$scannedphoto;
      if(file_exists($attachpath))
      {
      if(@ rename("./uploads/photograph/".$scannedphoto,"./uploads/photograph/p_".$regnumber.".jpg"))
      {
      $insert_data  = array(
      'member_no' => $regnumber,
      'update_value' => "uploads folder Photo rename",
      'update_date' => date('Y-m-d H:i:s')
      );
      $this->master_model->insertRecord('member_images_update', $insert_data);
      }              
      }           
      }
      //// FOR SIGNATURE
      if($scannedsignaturephoto != '' && $scannedsignaturephoto != 's_'.$regnumber.'.jpg')
      {                    
      $attachpath = "uploads/scansignature/".$scannedsignaturephoto;
      if(file_exists($attachpath))
      {
      if(@ rename("./uploads/scansignature/".$scannedsignaturephoto,"./uploads/scansignature/s_".$regnumber.".jpg"))
      {
      $insert_data  = array(
      'member_no' => $regnumber,
      'update_value' => "uploads folder Signature rename",
      'update_date' => date('Y-m-d H:i:s')
      );
      $this->master_model->insertRecord('member_images_update', $insert_data);
      }              
      }           
      }
      //// FOR IDPROOF
      if($idproofphoto != '' && $idproofphoto != 'pr_'.$regnumber.'.jpg')
      {                    
      $attachpath = "uploads/idproof/".$idproofphoto;
      if(file_exists($attachpath))
      {
      if(@ rename("./uploads/idproof/".$idproofphoto,"./uploads/idproof/pr_".$regnumber.".jpg"))
      {
      $insert_data  = array(
      'member_no' => $regnumber,
      'update_value' => "uploads folder id proof rename",
      'update_date' => date('Y-m-d H:i:s')
      );
      $this->master_model->insertRecord('member_images_update', $insert_data);
      }              
      }           
      }
      $extn = '.jpg';
      $member_no = $regnumber;
      //// Code for Photo
      $photo_name = $scannedphoto;
      $photo = strpos($photo_name,'photo');
      if($photo == 8)
      {
      $photo_replace = str_replace($photo_name,'p_',$photo_name);
      $updated_photo = $photo_replace.$member_no.$extn;
      $update_data = array('scannedphoto' => $updated_photo);
      $this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
      $insert_data  = array(
      'member_no' => $member_no,
      'update_value' => "Photo",
      'update_date' => date('Y-m-d H:i:s')
      );
      $this->master_model->insertRecord('member_images_update', $insert_data);
      $scannedphoto = $updated_photo;
      } 
      //// Code for Signature
      $sign_name = $scannedsignaturephoto;
      $sign = strpos($sign_name,'sign');
      if($sign == 8)
      {
      $sign_replace = str_replace($sign_name,'s_',$sign_name);
      $updated_sign = $sign_replace.$member_no.$extn;
      $update_data = array('scannedsignaturephoto' => $updated_sign);
      $this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
      $insert_data  = array(
      'member_no' => $member_no,
      'update_value' => "Signature",
      'update_date' => date('Y-m-d H:i:s')
      );
      $this->master_model->insertRecord('member_images_update', $insert_data);
      $scannedsignaturephoto = $updated_sign;
      }
      //// Code for IDPROOF
      $idproof_name = $idproofphoto;
      $idproof = strpos($idproof_name,'idproof');
      if($idproof == 8)
      {
      $idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
      $updated_idproof = $idproof_replace.$member_no.$extn;
      $update_data = array('idproofphoto' => $updated_idproof);
      $this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
      $insert_data  = array(
      'member_no' => $member_no,
      'update_value' => "ID Proof",
      'update_date' => date('Y-m-d H:i:s')
      );
      $this->master_model->insertRecord('member_images_update', $insert_data);
      $idproofphoto = $updated_idproof;
      }
      $db_img_path = $image_path; //Get old image path from database
      $scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';
      $final_photo_img = '';
      if($scannedphoto != "")
      {
      $photo_img_arr = explode('.', $scannedphoto);
      if(count($photo_img_arr) > 0)
      {
      $chk_photo_img = $photo_img_arr[0];
      if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpg'))
      {
      $final_photo_img = $chk_photo_img.'.jpg';
      }
      else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpeg'))
      {
      $final_photo_img = $chk_photo_img.'.jpeg';
      }
      }
      }
      if($final_photo_img == "")
      {
      if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpg'))
      {
      $final_photo_img = "p_".$member_no.'.jpg';
      }
      else if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpeg'))
      {
      $final_photo_img = "p_".$member_no.'.jpeg';
      }
      }
      if($final_photo_img != "") //Check photo in regular folder
      { 
      $scannedphoto_res = base_url()."uploads/photograph/".$final_photo_img; 
      }
      else if($db_img_path != "") //Check photo in old image path
      { 
      if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$reg_no.".jpg"))
      {
      $scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$reg_no.".jpg"; 
      }
      else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$regnumber.".jpg"))
      {
      $scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$regnumber.".jpg"; 
      }
      }
      else  //Check photo in kyc folder          
      {
      if($reg_no != "" && file_exists(FCPATH."uploads/photograph/k_p_".$reg_no.".jpg"))
      {
      $scannedphoto_res = base_url()."uploads/photograph/k_p_".$reg_no.".jpg"; 
      }
      else if($regnumber != "" && file_exists(FCPATH."uploads/photograph/k_p_".$regnumber.".jpg"))
      {
      $scannedphoto_res = base_url()."uploads/photograph/k_p_".$regnumber.".jpg"; 
      }
      }
      $final_idproofphoto_img = '';
      if($idproofphoto != "")
      {
      $idproofphoto_img_arr = explode('.', $idproofphoto);
      if(count($idproofphoto_img_arr) > 0)
      {
      $chk_idproofphoto_img = $idproofphoto_img_arr[0];
      if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpg'))
      {
      $final_idproofphoto_img = $chk_idproofphoto_img.'.jpg';
      }
      else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpeg'))
      {
      $final_idproofphoto_img = $chk_idproofphoto_img.'.jpeg';
      }
      }
      }
         if($final_idproofphoto_img == "")
         {
            if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpg'))
            {
               $final_idproofphoto_img = "pr_".$member_no.'.jpg';
            }
            else if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpeg'))
            {
               $final_idproofphoto_img = "pr_".$member_no.'.jpeg';
            }
         }
         if ($final_idproofphoto_img != "") //Check id proof in regular folder
         { 
            $idproofphoto_res = base_url()."uploads/idproof/".$final_idproofphoto_img; 
         }
         else if($db_img_path != "") //Check id proof in old image path
         { 
            if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"))
            {
               $idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"; 
            }
            else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"))
            {
               $idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"; 
            }
         }
         else //Check photo in kyc folder
         {
            if($reg_no != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$reg_no.".jpg"))
            {
               $idproofphoto_res = base_url()."uploads/idproof/k_pr_".$reg_no.".jpg"; 
            }
            else if($regnumber != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$regnumber.".jpg"))
            {
               $idproofphoto_res = base_url()."uploads/idproof/k_pr_".$regnumber.".jpg"; 
            }
         }
         $final_scanphoto_img = '';
         if($scannedsignaturephoto != "")
         {
            $scanphoto_img_arr = explode('.', $scannedsignaturephoto);
            if(count($scanphoto_img_arr) > 0)
            {
               $chk_scanphoto_img = $scanphoto_img_arr[0];
               if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpg'))
               {
                  $final_scanphoto_img = $chk_scanphoto_img.'.jpg';
               }
               else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpeg'))
               {
                  $final_scanphoto_img = $chk_scanphoto_img.'.jpeg';
               }
            }
         }
         if($final_scanphoto_img == "")
         {
            if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpg'))
            {
               $final_scanphoto_img = "s_".$member_no.'.jpg';
            }
            else if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpeg'))
            {
               $final_scanphoto_img = "s_".$member_no.'.jpeg';
            }
         }           
         if ($final_scanphoto_img != "") //Check signature in regular folder
         { 
            $scannedsignaturephoto_res = base_url()."uploads/scansignature/".$final_scanphoto_img; 
         }
      else if($db_img_path != "") //Check signature in old image path
      { 
         if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$reg_no.".jpg"))
         {
         $scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$reg_no.".jpg"; 
         }
         else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$regnumber.".jpg"))
         {
         $scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$regnumber.".jpg"; 
         }
      }
      else //Check signature in kyc folder
      {
         if($reg_no != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$reg_no.".jpg"))
         {
            $scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$reg_no.".jpg"; 
         }
         else if($regnumber != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$regnumber.".jpg"))
         {
            $scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$regnumber.".jpg"; 
         }
      }
      $data['scannedphoto'] = $scannedphoto_res;
      $data['idproofphoto'] = $idproofphoto_res;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
      return $data;
   }

   public function download_CSV_of_examdata()
   {
      if(empty($_GET['member_ids']) && empty($_GET['exam_date']) && empty($_GET['register_date']))
      return 1;
      if(isset($_GET['member_ids']) && !empty($_GET['member_ids'])) {
      $member_nos=explode(',',$_GET['member_ids']);
      foreach($member_nos as $m)
      $member_no[]=trim($m);
      }
      if(isset($_GET['exam_date']) && !empty($_GET['exam_date'])) {
      $exam_date=date('Y-m-d',strtotime($_GET['exam_date']));
      }
      if(isset($_GET['register_date']) && !empty($_GET['register_date'])){
      $register_date=date('Y-m-d',strtotime($_GET['register_date']));
      }
      //$member_no = array(802164364);
      $select = 'DISTINCT(b.transaction_no),a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
      $this->db->where("((a.exam_code = '991' AND bankcode = 'csc') OR (a.exam_code = '997'))");
      $this->db->where("((a.selected_vendor IS NULL) OR (a.selected_vendor='csc'))");
      if(isset($_GET['register_date']) && !empty($_GET['register_date']))
      $this->db->where('date(a.created_on)',$register_date);
      if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
      $this->db->where_in('a.regnumber',$member_no);
      if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
      $this->db->where('d.exam_date',$exam_date);
      else
      $this->db->where('d.exam_date >=',date('Y-m-d'));
      $this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
      $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
      $this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
      $this->db->where('remark',1);
      $this->db->where('pay_type',2);
      $this->db->where('status',1);
      $this->db->where('isactive','1');
      $this->db->where('isdeleted',0);
      $this->db->where('pay_status',1);
      $can_exam_data = $this->Master_model->getRecords('member_exam a', '', $select);  
         // echo $this->db->last_query(); //exit;
         //echo " <pre>"; print_r($can_exam_data); echo "</pre>  "; exit;
         $exam_cnt = 0;
         $rowdata=array();
         if (count($can_exam_data)) 
         {
         $i = 1;              
         foreach ($can_exam_data as $exam) 
         {
         $firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
         //ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
         $member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);
         $scannedphoto = $member_images['scannedphoto'];
         $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
         $idproofphoto = $member_images['idproofphoto'];
         if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
         {
         $ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
         if(count($ex_code)) 
         {
         if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
         {
         $exam_code = $ex_code[0]['original_val'];
         } 
         else 
         {
         $exam_code = $exam['exam_code'];
         }
         }
         else 
         {
         $exam_code = $exam['exam_code'];
         }
         } 
         else 
         {
         $exam_code = $exam['exam_code'];
         }
         $dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
         $registration_date = date('d-m-Y', strtotime($exam['registration_date']));
         $address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
         $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
         $gender = $exam['gender'];
         if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
         $designation = $this->master_model->getRecords('designation_master');
         if (count($designation)) 
         {
         foreach ($designation as $designation_row) 
         {
         if ($exam['designation'] == $designation_row['dcode']) 
         {
         $designation_name = $designation_row['dname'];
         }
         }
         }
         $designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
         $medium = $this->master_model->getRecords('medium_master');
         if(count($medium)) 
         {
         foreach ($medium as $medium_row) 
         {
         if ($exam['exam_medium'] == $medium_row['medium_code']) 
         {
         $medium_name = $medium_row['medium_description'];
         }
         }
         }
         $medium_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
         $institution_master = $this->master_model->getRecords('institution_master');
         if(count($institution_master)) 
         {
         foreach ($institution_master as $institution_row) 
         {
         if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
         {
         $institution_name = $institution_row['name'];
         }
         }
         }
         $institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
         $firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
         $middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
         $lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
         $mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
         $pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
         $exam_arr = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS','997'=>'IPPB');      
         foreach ($exam_arr as $k => $val) 
         {
         if ($exam_code == $k)  
         {
         $exam_name = $val;
         }
         }
         $select    = 'regnumber';
         $this->db->where_in('exam_code', array('991','997'));
         $this->db->where_in('regnumber', $exam['regnumber']);
         $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
         $attempt_count = count($attempt_count);            
         $attempt_count = $attempt_count - 1;
         $post_field_arr['first_name'] = $firstname;
         $post_field_arr['middle_name'] = $middlename;
         $post_field_arr['last_name'] = $lastname;
         $post_field_arr['member_number'] = $exam['regnumber'];
         $post_field_arr['password'] = $exam['pwd'];
         $post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
         $post_field_arr['gender'] = $gender;
         $post_field_arr['email_id'] = $exam['email'];
         $post_field_arr['mobile'] = $mobile;
         $post_field_arr['address'] = $address;
         $post_field_arr['state'] = $exam['state'];
         $post_field_arr['pin_code'] = $pincode;
         $post_field_arr['country'] = 'INDIA';
         $post_field_arr['profession'] = '';
         $post_field_arr['organization'] = $institution_name;
         $post_field_arr['designation'] = $designation_name;
         $post_field_arr['exam_code'] = $exam_code;
         $post_field_arr['course'] = $exam_name;
         $post_field_arr['elective_sub_code'] = $subject_code;
         $post_field_arr['elective_sub_desc'] = $subject_description;
         $post_field_arr['attempt'] = $attempt_count;
         $post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
         $post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));
         $post_field_arr['batch_start_time'] = $exam['time'];
         $post_field_arr['exam_medium'] = $medium_name;
         $post_field_arr['exam_center_code'] = $exam['exam_center_code'];
         $post_field_arr['venue_code'] = $exam['venueid'];
         $post_field_arr['server_url'] = $server_url;
         $post_field_arr['p_image'] = $scannedphoto;
         $post_field_arr['s_image'] = $scannedsignaturephoto;
         $post_field_arr['pr_image'] = $idproofphoto;
         $i++;
         $exam_cnt++;
         if(strpos($exam['scannedphoto'], "k_") !== false){
         $alternate_scannedphoto =  base_url()."uploads/photograph/".str_replace("k_","",$exam['scannedphoto']);
         }
         else
         $alternate_scannedphoto =  base_url()."uploads/photograph/".'k_'.$exam['scannedphoto'];
         if(strpos($exam['idproofphoto'], "k_") !== false){
         $alternate_idproofphoto =  base_url()."uploads/idproof/".str_replace("k_","",$exam['idproofphoto']);
         }
         else
         $alternate_idproofphoto =  base_url()."uploads/idproof/".'k_'.$exam['idproofphoto'];
         if(strpos($exam['scannedsignaturephoto'], "k_") !== false){
         $alternate_scannedsignaturephoto =  base_url()."uploads/scansignature/".str_replace("k_","",$exam['scannedsignaturephoto']);
         }
         else
         $alternate_scannedsignaturephoto =  base_url()."uploads/scansignature/".'k_'.$exam['scannedsignaturephoto'];
         $append_img_log =  $exam['regnumber'].' | '. $scannedphoto. ' | '.$scannedsignaturephoto.' | '.$idproofphoto .' || '.$alternate_scannedphoto.' | '.$alternate_scannedsignaturephoto.' | '.$alternate_idproofphoto;
         $post_field_arr['k_p_image']  =  $alternate_scannedphoto;
         $post_field_arr['k_s_image']  =  $alternate_scannedsignaturephoto;
         $post_field_arr['k_pr_image'] =  $alternate_idproofphoto;
         $api_data_arr[] = $post_field_arr; 
         }
         $csv='first_name, middle_name ,last_name  ,member_number,password,dob,gender,email_id,mobile,address,state,pin_code,country,profession,organization,designation,exam_code,course,elective_sub_code,elective_sub_desc,attempt,registration_date,exam_date,batch_start_time,exam_medium,exam_center_code,venue_code,server_url,p_image,s_image,pr_image,k_p_image,k_s_image,k_pr_image';
         //echo'<pre>';print_r($api_data_arr);exit;
         foreach($api_data_arr as $currDatas) 
         {
            $csv.="\n";
            foreach($currDatas as $currData)
            $csv.=$currData.',';
         }
      }
      //echo $csv;exit;
      $filename = "csc_exam_register_data_".date('Y-m-d').".csv";
      header('Content-type: application/csv');
      header('Content-Disposition: attachment; filename='.$filename);
      $csv_handler = fopen('php://output', 'w');
      fwrite ($csv_handler,$csv);
      fclose ($csv_handler);
   }
}