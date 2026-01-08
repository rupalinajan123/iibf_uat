<?php
/*
 * Controller Name    :    careers data csv imp
 * Created By        :    Bhushan Amrutkar
 * Created Date        :    12-10-2020
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_csv_careers extends CI_Controller
{
    public $UserID;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        // $this->UserID=$this->session->id;
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->model('log_model');
        $this->load->model('Emailsending');
        $this->load->library('email');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    function resize_image_max($image, $max_width, $max_height)
    {
        ini_set("memory_limit", "256M");
        ini_set("gd.jpeg_ignore_warning", 1);
        
        $org_img = $image;
        $image   = @ImageCreateFromJpeg($image);
        if (!$image) {
            $image = imagecreatefromstring(file_get_contents($org_img));
        }
        
        $w = imagesx($image); //current width
        $h = imagesy($image); //current height
        if ((!$w) || (!$h)) {
            $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.';
            return false;
        }
        
        if (($w <= $max_width) && ($h <= $max_height)) {
            return $image;
        } //no resizing needed
        
        //try max width first...
        $ratio = $max_width / $w;
        $new_w = $max_width;
        $new_h = $h * $ratio;
        
        //if that didn't work
        if ($new_h > $max_height) {
            $ratio = $max_height / $h;
            $new_h = $max_height;
            $new_w = $w * $ratio;
        }
        
        $new_image = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
        return $new_image;
    }
    
    public function careers_images($position_id, $from_date, $to_date)
    {
        // https://iibf.esdsconnect.com/admin/Cron_csv_careers/careers_images/2/2020-10-24/2020-11-02
		 // https://iibf.esdsconnect.com/admin/Cron_csv_careers/careers_images/3/2020-10-24/2020-11-02
		  // https://iibf.esdsconnect.com/admin/Cron_csv_careers/careers_images/6/2020-10-24/2020-11-02
        ini_set("memory_limit", "-1");
        
        $dir_flg         = 0;
        $parent_dir_flg  = 0;
        $file_w_flg      = 0;
        $photo_zip_flg   = 0;
        $sign_zip_flg    = 0;
        $idproof_zip_flg = 0;
        $success         = array();
        $error           = array();
        
        $start_time   = date("Y-m-d H:i:s");
        $current_date = date("Ymd");
        
        //$current_date = '20190603';
        
        $cron_file_dir = "./uploads/careers_csv/";
        $result        = array(
            "success" => "",
            "error" => "",
            "Start Time" => $start_time,
            "End Time" => ""
        );
        $desc          = json_encode($result);
        
        $this->log_model->cronlog("Careers Images Cron Execution Start", $desc);
        
        if ($position_id == 1) {
            $csv_name = "Junior_Executive";
        } else if ($position_id == 2) {
            $csv_name = "Assistant_Director_IT";
        } else if ($position_id == 3) {
            $csv_name = "Assistant_Director_Accounts";
        } else if ($position_id == 4) {
            $csv_name = "Director_Training";
        } else if ($position_id == 5) {
            $csv_name = "CEO";
        } else if ($position_id == 6) {
            $csv_name = "Deputy_Director";
        } else if ($position_id == 7) {
            $csv_name = "Faculty_Member";
        }
        
        if (!file_exists($cron_file_dir . $current_date)) {
            $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
        }
        if (file_exists($cron_file_dir . $current_date)) {
            $cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
            
            $file = $csv_name . "_careers_images_" . $current_date . ".txt";
            $fp   = fopen($cron_file_path . '/' . $file, 'w');
            
            $file1 = "logs_careers_images_" . $current_date . ".txt";
            $fp1   = fopen($cron_file_path . '/' . $file1, 'a');
            
            fwrite($fp1, "\n" . "************ Careers Images Details Cron End " . $start_time . " *************" . "\n");
            
            $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($current_date)));
            
            $select = 'unique_no,sel_namesub,firstname,middlename,lastname,mobile,scannedphoto,scannedsignaturephoto';
            $this->db->where('DATE(createdon) >=', $from_date);
            $this->db->where('DATE(createdon) <=', $to_date);
            $this->db->where('position_id', $position_id);
            $new_mem_reg = $this->Master_model->getRecords('careers_registration', array(
                'active_status' => '1'
            ), $select);
            
            if (count($new_mem_reg)) {
                $dirname   = $csv_name . "_careers_images_" . $current_date;
                $directory = $cron_file_path . '/' . $dirname;
                if (file_exists($directory)) {
                    array_map('unlink', glob($directory . "/*.*"));
                    rmdir($directory);
                    $dir_flg = mkdir($directory, 0700);
                } else {
                    $dir_flg = mkdir($directory, 0700);
                }
                // Create a zip of images folder
                $zip = new ZipArchive;
                $zip->open($directory . '.zip', ZipArchive::CREATE);
                
                $i           = 1;
                $mem_cnt     = 0;
                $photo_cnt   = 0;
                $sign_cnt    = 0;
                $idproof_cnt = 0;
                
                foreach ($new_mem_reg as $reg_data) {
                    $data  = '';
                    $photo = '';
                    
                    if (is_file("./uploads/photograph/" . $reg_data['scannedphoto'])) {
                        $photo = $reg_data['scannedphoto'];
                    } else {
                        fwrite($fp1, "Error - Photograph does not exist  - " . $reg_data['scannedphoto'] . " (" . $reg_data['unique_no'] . ")\n");
                    }
                    
                    $signature = '';
                    if (is_file("./uploads/scansignature/" . $reg_data['scannedsignaturephoto'])) {
                        $signature = $reg_data['scannedsignaturephoto'];
                    } else {
                        fwrite($fp1, "**ERROR** - Signature does not exist  - " . $reg_data['scannedsignaturephoto'] . " (" . $reg_data['unique_no'] . ")\n");
                    }
                    
                    
                    // Unique No | Name Prefix | Firstname | Middlename | Lastname
                    
                    $data .= '' . $reg_data['mobile'] . '|' . $reg_data['sel_namesub'] . '|' . $reg_data['firstname'] . '|' . $reg_data['middlename'] . '|' . $reg_data['lastname'] . '|' . $reg_data['scannedphoto'] . '|' . $reg_data['scannedsignaturephoto'] . "|\n";
                    
                    if ($dir_flg) {
                        // For photo images
                        if ($photo) {
                            $image      = "./uploads/photograph/" . $reg_data['scannedphoto'];
                            $max_width  = "200";
                            $max_height = "200";
                            
                            $imgdata = $this->resize_image_max($image, $max_width, $max_height);
                            imagejpeg($imgdata, $directory . "/" . $reg_data['scannedphoto']);
                            
                            $photo_to_add  = $directory . "/" . $reg_data['scannedphoto'];
                            $new_photo     = substr($photo_to_add, strrpos($photo_to_add, '/') + 1);
                            $photo_zip_flg = $zip->addFile($photo_to_add, $new_photo);
                            if (!$photo_zip_flg) {
                                fwrite($fp1, "**ERROR** - Photograph not added to zip  - " . $reg_data['scannedphoto'] . " (" . $reg_data['unique_no'] . ")\n");
                            } else
                                $photo_cnt++;
                            
                        }
                        // For signature images
                        if ($signature) {
                            $image      = "./uploads/scansignature/" . $reg_data['scannedsignaturephoto'];
                            $max_width  = "140";
                            $max_height = "100";
                            
                            $imgdata = $this->resize_image_max($image, $max_width, $max_height);
                            imagejpeg($imgdata, $directory . "/" . $reg_data['scannedsignaturephoto']);
                            
                            $sign_to_add  = $directory . "/" . $reg_data['scannedsignaturephoto'];
                            $new_sign     = substr($sign_to_add, strrpos($sign_to_add, '/') + 1);
                            $sign_zip_flg = $zip->addFile($sign_to_add, $new_sign);
                            if (!$sign_zip_flg) {
                                fwrite($fp1, "**ERROR** - Signature not added to zip  - " . $reg_data['scannedsignaturephoto'] . " (" . $reg_data['unique_no'] . ")\n");
                            } else
                                $sign_cnt++;
                        }
                        
                        if ($photo_zip_flg || $sign_zip_flg) {
                            $success['zip'] = "Careers Member Images Zip Generated Successfully";
                        } else {
                            $error['zip'] = "Error While Generating Careers Member Images Zip";
                        }
                    }
                    $i++;
                    $mem_cnt++;
                    $file_w_flg = fwrite($fp, $data);
                    if ($file_w_flg) {
                        $success['file'] = "Careers Member Details File Generated Successfully. ";
                    } else {
                        $error['file'] = "Error While Generating Careers Member Details File.";
                    }
                }
                fwrite($fp1, "\n" . "Total Careers Members Added = " . $mem_cnt . "\n");
                fwrite($fp1, "\n" . "Total Careers Photographs Added = " . $photo_cnt . "\n");
                fwrite($fp1, "\n" . "Total Careers Signatures Added = " . $sign_cnt . "\n");
                //$file_w_flg = fwrite($fp, $data);
                $zip->close();
            } else {
                $success[] = "No data found for the date";
            }
            fclose($fp);
            // Cron End Logs
            $end_time = date("Y-m-d H:i:s");
            $result   = array(
                "success" => $success,
                "error" => $error,
                "Start Time" => $start_time,
                "End Time" => $end_time
            );
            $desc     = json_encode($result);
            $this->log_model->cronlog("Careers Member Images Details Cron End", $desc);
            
            fwrite($fp1, "\n" . "************ Careers Member Images Details Cron End " . $end_time . " *************" . "\n");
            fclose($fp1);
        }
    }
    
    // Careers
    public function careers_csv($position_id, $from_date, $to_date)
    {
        
        //https://iibf.esdsconnect.com/admin/cron_csv_custom/careers_csv/1/2020-01-18/2020-02-12
		//https://iibf.esdsconnect.com/admin/cron_csv_careers/careers_csv/2/2020-10-24/2020-11-01
        
        ini_set("memory_limit", "-1");
        $dir_flg        = 0;
        $parent_dir_flg = 0;
        $exam_file_flg  = 0;
        $success        = array();
        $error          = array();
        $start_time     = date("Y-m-d H:i:s");
        $current_date   = date("Ymd");
        $cron_file_dir  = "./uploads/careers_csv/";
        $result         = array(
            "success" => "",
            "error" => "",
            "Start Time" => $start_time,
            "End Time" => ""
        );
        $desc           = json_encode($result);
        
        if ($position_id == 1) {
            $csv_name = "Junior_Executive";
        } else if ($position_id == 2) {
            $csv_name = "Assistant_Director_IT";
        } else if ($position_id == 3) {
            $csv_name = "Assistant_Director_Accounts";
        } else if ($position_id == 4) {
            $csv_name = "Director_Training";
        } else if ($position_id == 6) {
            $csv_name = " Deputy_Director";
        } else if ($position_id == 7) {
            $csv_name = " Faculty_Member";
        }
        
        $this->log_model->cronlog("Careers CSV Cron Execution Start", $desc);
        if (!file_exists($cron_file_dir . $current_date)) {
            $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
        }
        if (file_exists($cron_file_dir . $current_date)) {
            $cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
            $file           = "Careers_" . $csv_name . "_" . $current_date . ".csv";
            $fp             = fopen($cron_file_path . '/' . $file, 'w');
            $file1          = "Careers_logs_" . $current_date . ".txt";
            $fp1            = fopen($cron_file_path . '/' . $file1, 'a');
            fwrite($fp1, "\n**************** Careers CSV Cron Execution Started - " . $start_time . " **************** \n");
            
            $yesterday = date('Y-m-d', strtotime("- 1 day"));
						
						$select = 'c.careers_id, p.position, c.unique_no, c.sel_namesub, c.firstname, c.middlename, c.lastname, c.father_husband_name, c.dateofbirth, c.gender, c.email, c.marital_status, c.mobile, c.alternate_mobile, c.pan_no, c.aadhar_card_no, c.addressline1, c.addressline2, c.addressline3, c.addressline4, c.district, c.city, c.state, c.pincode, c.contact_number, c.addressline1_pr, c.addressline2_pr, c.addressline3_pr, c.addressline4_pr, c.district_pr, c.city_pr, c.state_pr, c.pincode_pr, c.contact_number_pr, c.exam_center, c.ess_course_name, c.ess_subject, c.ess_college_name, c.ess_university, c.ess_from_date, c.ess_to_date, c.ess_degree_completion_date, c.ess_aggregate_marks_obtained, c.ess_aggregate_max_marks, c.ess_percentage, c.ess_class, m.course_name, q.college_name, q.university, q.from_date, q.to_date, q.degree_completion_date, q.aggregate_marks_obtained, q.aggregate_max_marks, q.percentage, q.class, c.year_of_passing, c.membership_number, c.languages_known, c.languages_option, c.languages_known1, c.languages_option1, c.languages_known2, c.languages_option2, c.extracurricular, c.hobbies, c.achievements, c.declaration1, c.declaration_note, c.refname_one, c.refaddressline_one, c.reforganisation_one, c.refdesignation_one, c.refemail_one, c.refmobile_one, c.refname_two, c.refaddressline_two, c.reforganisation_two, c.refdesignation_two, c.refemail_two, c.refmobile_two, c.comment, c.declaration2,  c.scannedphoto, c.scannedsignaturephoto, c.place, DATE(c.submit_date) as submit_date, DATE(c.createdon) as apply_date';
						
            //$this->db->join('careers_employment_hist e', 'e.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_edu_qualification q', 'q.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_course_mst m', 'm.course_code = q.course_code', 'LEFT');
            $this->db->join('careers_position_master p', 'p.id = c.position_id', 'LEFT');
            $this->db->where('DATE(c.createdon) >=', $from_date);
            $this->db->where('DATE(c.createdon) <=', $to_date);
            $this->db->where('c.position_id', $position_id);
            $can_exam_data = $this->Master_model->getRecords('careers_registration c', array('active_status' => '1'), $select);
            echo "SQL>" . $this->db->last_query(); //exit;
            if (count($can_exam_data)) 
						{
                $i             = 1;
                $exam_cnt      = 0;
                // Column headers for CSV            
                $data1 = "Job_Position, Name, Father_Husband_Name, Date_of_Birth, Gender, Email, Marital_Status, Mobile, Alternate_Mobile, PAN_No, Aadhar_card_no, Address_line1, Address_line2, Address_line3, Address_line4, District, City, State, Pincode_Zipcode, Contact_no, Permanent_Address_line1, Permanent_Address_line2, Permanent_Address_line3, Permanent_Address_line4, Permanent_District, Permanent_City, Permanent_State, Permanent_Pincode_Zipcode, Contact_Number, Exam_Center, Essential_Course_Name, Essential_subject, Essential_college_name, Essential_University, Essential_from_date, Essential_to_date, Essential_degree_completion_date, Essential_aggregate_marks_obtained, Essential_aggregate_max_marks, Essential_percentage, Essential_class, Desirable_course_name, Desirable_college_name, Desirable_university, Desirable_from_date, Desirable_to_date, Desirable_degree_completion_date, Desirable_aggregate_marks_obtained, Desirable_aggregate_max_marks, Desirable_percentage, Desirable_class, Year_of_passing, Membership_no, organization1 ,designation1, responsibilities1, job_from_date1, job_to_date1, organization2, designation2, responsibilities2, job_from_date2,job_to_date2, organization3, designation3, responsibilities3, job_from_date3, job_to_date3, organization4, designation4, responsibilities4, job_from_date4, job_to_date4, Languages_known, Languages_option, Languages_known1, Languages_option1, Languages_known2, Languages_option2, Extracurricular, Hobbies, Achievements, Have your ever been arrested or kept under detention or bound down/fined/convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/charge is under investigation inquiry or trial or otherwise.YES or NO.If YES full particulars of the case should be given. Canvassing in any form will be a disqualification, Declaration_note, Refname_one, Refaddressline_one, Reforganisation_one, Refdesignation_one, Refemail_one, Refmobile_one, Refname_two, Refaddressline_two, Reforganisation_two, Refdesignation_two, Refemail_two, Refmobile_two, Comment, Any other information that the candidate would like to add,Declaration : I declare that all statements made in this application are true complete and correct to the best of my knowledge and belief. I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking and Finance my candidature/ appointment for the said post is liable to be cancelled at any stage and even after appointment my services are liable to be terminated without any notice, Photo, Signature, Place, Submit_date, Apply_date \n";
                $exam_file_flg = fwrite($fp, $data1);
                
                foreach ($can_exam_data as $exam) 
								{
                    $job_arr_data = array();
                    $select       = 'organization,designation,responsibilities,job_from_date,job_to_date';
                    $this->db->where('careers_id', $exam['careers_id']);
                    $job_arr_data = $this->Master_model->getRecords('careers_employment_hist', '', $select);
                    
                    $data = '';
                    
										$unique_no = $sel_namesub = $firstname = $middlename = $lastname = $father_husband_name = $dateofbirth = $gender = $email = $marital_status = $mobile = $alternate_mobile = $pan_no = $aadhar_card_no = $addressline1 = $addressline2 = $addressline3 = $addressline4 = $district = $city = $state = $pincode = $contact_number = $addressline1_pr = $addressline2_pr = $addressline3_pr = $addressline4_pr = $district_pr = $city_pr = $state_pr = $pincode_pr = $contact_number_pr = $exam_center = $ess_course_name = $ess_subject = $ess_college_name = $ess_university = $ess_from_date = $ess_to_date = $ess_degree_completion_date = $ess_aggregate_marks_obtained = $ess_aggregate_max_marks = $ess_percentage = $ess_grade_marks = $ess_class = $course_name = $college_name = $university = $from_date = $to_date = $degree_completion_date = $aggregate_marks_obtained = $aggregate_max_marks = $percentage = $grade_marks = $class = $year_of_passing = $membership_number = $languages_known = $languages_option = $languages_known1 = $languages_option1 = $languages_known2 = $languages_option2 = $extracurricular = $hobbies = $achievements = $refname_one = $refaddressline_one = $reforganisation_one = $refdesignation_one = $refemail_one = $refmobile_one = $refname_two = $refaddressline_two = $reforganisation_two = $refdesignation_two = $refemail_two = $refmobile_two = $declaration1 = $declaration_note = $declaration2 = $scannedphoto = $scannedsignaturephoto = $comment = $place = $submit_date = $createdon = $name = $address = $address_pr = $gender = $organization1 = $designation1 = $responsibilities1 = $job_from_date1 = $job_to_date1 = $organization2 = $designation2 = $responsibilities2 = $job_from_date2 = $job_to_date2 = $organization3 = $designation3 = $responsibilities3 = $job_from_date3 = $job_to_date3 = $organization4 = $designation4 = $responsibilities4 = $job_from_date4 = $job_to_date4 = "";
                    
                    $position              = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['position']);
                    $unique_no             = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['unique_no']);
                    $sel_namesub           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['sel_namesub']);
                    $firstname             = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['firstname']);
                    $middlename            = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['middlename']);
                    $lastname              = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['lastname']);
                    $father_husband_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['father_husband_name']);
                    $dateofbirth           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['dateofbirth']);
                    $gender                = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['gender']);
                    $email                 = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['email']);
                    $marital_status        = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['marital_status']);
                    $mobile                = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['mobile']);
                    $alternate_mobile      = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['alternate_mobile']);
                    $pan_no                = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['pan_no']);
                    $aadhar_card_no        = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['aadhar_card_no']);
                    $addressline1          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline1']);
                    $addressline2          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline2']);
                    $addressline3          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline3']);
                    $addressline4          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline4']);
                    $district              = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['district']);
                    $city                  = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['city']);
                    $state                 = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['state']);
                    $pincode               = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['pincode']);
                    $contact_number        = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['contact_number']);
                    $addressline1_pr       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline1_pr']);
                    $addressline2_pr       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline2_pr']);
                    $addressline3_pr       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline3_pr']);
                    $addressline4_pr       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['addressline4_pr']);
                    $district_pr           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['district_pr']);
                    $city_pr               = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['city_pr']);
                    $state_pr              = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['state_pr']);
                    $pincode_pr            = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['pincode_pr']);
                    $contact_number_pr     = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['contact_number_pr']);
                    $exam_center           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['exam_center']);
                    $ess_course_name       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_course_name']);
                    $ess_subject           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_subject']);
                    $ess_college_name      = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['ess_college_name']));
                    $ess_university        = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['ess_university']));
                    $ess_from_date         = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_from_date']);
                    $ess_to_date           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_to_date']);
                    $ess_degree_completion_date = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_degree_completion_date']);
                    $ess_aggregate_marks_obtained = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_aggregate_marks_obtained']);
                    $ess_aggregate_max_marks = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_aggregate_max_marks']);
                    $ess_percentage        = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_percentage']);
                    $ess_class             = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['ess_class']);
                    $course_name           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['course_name']);
                    $college_name          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['college_name']));
                    $university            = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['university']);
                    $from_date             = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['from_date']);
                    $to_date               = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['to_date']);
                    $degree_completion_date = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['degree_completion_date']);
                    $aggregate_marks_obtained = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['aggregate_marks_obtained']);
                    $aggregate_max_marks   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['aggregate_max_marks']);
                    $percentage            = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['percentage']);
                    $class                 = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['class']);
                    $year_of_passing       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['year_of_passing']);
                    $membership_number     = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['membership_number']);
                    $languages_known       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['languages_known']);
                    $languages_option      = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['languages_option']));
                    $languages_known1      = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['languages_known1']);
                    $languages_option1     = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['languages_option1']));
                    $languages_known2      = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['languages_known2']);
                    $languages_option2     = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['languages_option2']));
                    $extracurricular       = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['extracurricular']));
                    $hobbies               = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['hobbies']));
                    $achievements          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['achievements']));
                    $refname_one           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refname_one']);
                    $refaddressline_one    = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', trim(str_replace(',', ' | ', $exam['refaddressline_one'])));
                    $reforganisation_one   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['reforganisation_one']);
                    $refdesignation_one    = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refdesignation_one']);
                    $refemail_one          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refemail_one']);
                    $refmobile_one         = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refmobile_one']);
                    $refname_two           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refname_two']);
                    $refaddressline_two    = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', trim(str_replace(',', ' | ', $exam['refaddressline_two'])));
                    $reforganisation_two   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['reforganisation_two']);
                    $refdesignation_two    = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refdesignation_two']);
                    $refemail_two          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refemail_two']);
                    $refmobile_two         = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['refmobile_two']);
                    $declaration1          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['declaration1']);
                    $declaration_note      = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['declaration_note']);
                    $declaration2          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['declaration2']);
                    $scannedphoto          = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['scannedphoto']);
                    $scannedsignaturephoto = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['scannedsignaturephoto']);
                    $comment               = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', str_replace(',', ' | ', $exam['comment']));
                    $place                 = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['place']);
                    $submit_date           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['submit_date']);
                    $apply_date            = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['apply_date']);
                    
                    /* Name */
                    $name = $sel_namesub . ' ' . $firstname . ' ' . $middlename . ' ' . $lastname;
                    $name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $name);
                    
                    /* Address */
                    $address = $addressline1 . ' ' . $addressline2 . ' ' . $addressline3 . ' ' . $addressline4 . ' ' . $district . ' ' . $city . ' ' . $state . ' ' . $pincode;
                    $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
                    
                    /* Address Prnt */
                    $address_pr = $addressline1_pr . ' ' . $addressline2_pr . ' ' . $addressline3_pr . ' ' . $addressline4_pr . ' ' . $district_pr . ' ' . $city_pr . ' ' . $state_pr . ' ' . $pincode_pr;
                    $address_pr = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address_pr);
                    
                    /* Gender */
                    $gender = $gender;
                    if ($gender == 'male' || $gender == 'Male') {
                        $gender = 'Male';
                    } elseif ($gender == 'female' || $gender == 'Female') {
                        $gender = 'Female';
                    }
                    
                    foreach ($job_arr_data as $jk => $job_data) 
										{
                        if ($jk == 0) 
												{
                            $organization1     = $job_data['organization'];
                            $designation1      = $job_data['designation'];
                            $responsibilities1 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date1    = $job_data['job_from_date'];
                            $job_to_date1      = $job_data['job_to_date'];
                        } 
												elseif ($jk == 1) 
												{
                            $organization2     = $job_data['organization'];
                            $designation2      = $job_data['designation'];
                            $responsibilities2 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date2    = $job_data['job_from_date'];
                            $job_to_date2      = $job_data['job_to_date'];
                        } 
												elseif ($jk == 2) 
												{
                            $organization3     = $job_data['organization'];
                            $designation3      = $job_data['designation'];
                            $responsibilities3 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date3    = $job_data['job_from_date'];
                            $job_to_date3      = $job_data['job_to_date'];
                        } 
												elseif ($jk == 3) 
												{
                            $organization4     = $job_data['organization'];
                            $designation4      = $job_data['designation'];
                            $responsibilities4 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date4    = $job_data['job_from_date'];
                            $job_to_date4      = $job_data['job_to_date'];
                        }
                    }
                    
                    /* Job_Position | Name | Father_Husband_Name | Date_of_Birth | Gender | Email | Marital_Status | Mobile | Alternate_Mobile | PAN_No | Aadhar_card_no | Address | Contact_no | Permanent_Address | Contact_Number | Exam_Center | Essential_Course_Name | Essential_subject | Essential_college_name | Essential_University | Essential_from_date | Essential_to_date | Essential_grade_marks | Essential_class | Desirable_course_name | Desirable_college_name | Desirable_university | Desirable_from_date | Desirable_to_date | Desirable_grade_marks | Desirable_class | Year_of_passing | Membership_no | Organization | Designation | Responsibilities | Job_from_date | Job_to_date | Languages_known | Languages_option | Languages_known1 | Languages_option1 | Languages_known2 | Languages_option2 | Extracurricular | Hobbies | Achievements | Refname_one | Refaddressline_one | Refemail_one | Refmobile_one | Refname_two | Refaddressline_two,Refemail_two | Refmobile_two | Declaration1 | Declaration_note | Declaration2 | Photo | Signature | Comment | Place | Submit_date | Apply_date */
                    
                    $data .= '' . $position . ',' . $name . ',' . $father_husband_name . ',' . $dateofbirth . ',' . $gender . ',' . $email . ',' . $marital_status . ',' . $mobile . ',' . $alternate_mobile . ',' . $pan_no . ',' . $aadhar_card_no . ',' . $addressline1 . ',' .$addressline2 . ',' .$addressline3 . ',' .$addressline4 . ',' .$district . ',' .$city . ',' .$state . ',' .$pincode . ',' . $contact_number . ',' . $addressline1_pr . ',' .$addressline2_pr . ',' .$addressline3_pr . ',' .$addressline4_pr . ',' .$district_pr . ',' .$city_pr . ',' .$state_pr . ',' .$pincode_pr . ',' . $contact_number_pr . ',' . $exam_center . ',' . $ess_course_name . ',' . $ess_subject . ',' . $ess_college_name . ',' . $ess_university . ',' . $ess_from_date . ',' . $ess_to_date .','.$ess_degree_completion_date.','.$ess_aggregate_marks_obtained.','.$ess_aggregate_max_marks.','.$ess_percentage. ',' . $ess_class . ',' . $course_name . ',' . $college_name . ',' . $university . ',' . $from_date . ',' . $to_date .','.$degree_completion_date.','.$aggregate_marks_obtained.','.$aggregate_max_marks.','.$percentage. ',' . $class . ',' . $year_of_passing . ',' . $membership_number . ',' . $organization1 . ',' . $designation1 . ',' . $responsibilities1 . ',' . $job_from_date1 . ',' . $job_to_date1 . ',' . $organization2 . ',' . $designation2 . ',' . $responsibilities2 . ',' . $job_from_date2 . ',' . $job_to_date2 . ',' . $organization3 . ',' . $designation3 . ',' . $responsibilities3 . ',' . $job_from_date3 . ',' . $job_to_date3 . ',' . $organization4 . ',' . $designation4 . ',' . $responsibilities4 . ',' . $job_from_date4 . ',' . $job_to_date4 . ',' . $languages_known . ',' . $languages_option . ',' . $languages_known1 . ',' . $languages_option1 . ',' . $languages_known2 . ',' . $languages_option2 . ',' . $extracurricular . ',' . $hobbies . ',' . $achievements . ',' . $declaration1 . ',' . $declaration_note . ',' . $refname_one . ',' . $refaddressline_one .','.$reforganisation_one.','.$refdesignation_one. ',' . $refemail_one . ',' . $refmobile_one . ',' . $refname_two . ',' . $refaddressline_two .','.$reforganisation_two.','.$refdesignation_two. ',' . $refemail_two . ',' . $refmobile_two . ',' . $comment . ',' . $declaration2 . ',' . $scannedphoto . ',' . $scannedsignaturephoto . ',' . $place . ',' . $submit_date . ',' . $apply_date . "\n";
                    
                    $exam_file_flg = fwrite($fp, $data);
                    if ($exam_file_flg)
                        $success['cand_exam'] = "Careers CSV File Generated Successfully.";
                    else
                        $error['cand_exam'] = "Error While Generating Careers CSV File.";
                    $i++;
                    $exam_cnt++;
                }
								
                //exit;
                fwrite($fp1, "\n Total Applications - " . $exam_cnt . "\n");
                
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                $insert_info = array(
                    'CurrentDate' => $current_date, 
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => $exam_cnt,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
            } 
						else 
						{
                $yesterday = date('Y-m-d', strtotime("- 1 day"));
                fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => 0,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
                $success[] = "No data found for the date";
            }
            fclose($fp);
            $end_time = date("Y-m-d H:i:s");
            $result   = array(
                "success" => $success,
                "error" => $error,
                "Start Time" => $start_time,
                "End Time" => $end_time
                
            );
            $desc     = json_encode($result);
            $this->log_model->cronlog("Careers CSV Cron Execution End", $desc);
            fwrite($fp1, "\n" . "**************** Careers CSV Cron Execution End " . $end_time . " *****************" . "\n");
            fclose($fp1);
        }
    }
    
    // Careers
    public function careers_deputy_director($position_id, $from_date, $to_date)
    {
        
        //https://iibf.esdsconnect.com/admin/cron_csv_careers/careers_deputy_director/6/2020-02-22/2020-03-12
        
        ini_set("memory_limit", "-1");
        $dir_flg        = 0;
        $parent_dir_flg = 0;
        $exam_file_flg  = 0;
        $success        = array();
        $error          = array();
        $start_time     = date("Y-m-d H:i:s");
        $current_date   = date("Ymd");
        $cron_file_dir  = "./uploads/careers_csv/";
        $result         = array(
            "success" => "",
            "error" => "",
            "Start Time" => $start_time,
            "End Time" => ""
        );
        $desc           = json_encode($result);
        
        if ($position_id == 6) {
            $csv_name = "Deputy_Director";
        }
        
        
        $this->log_model->cronlog("Careers CSV Cron Execution Start", $desc);
        if (!file_exists($cron_file_dir . $current_date)) {
            $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
        }
        if (file_exists($cron_file_dir . $current_date)) {
            $cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
            $file           = "Careers_" . $csv_name . "_" . $current_date . ".csv";
            $fp             = fopen($cron_file_path . '/' . $file, 'w');
            $file1          = "Careers_logs_" . $current_date . ".txt";
            $fp1            = fopen($cron_file_path . '/' . $file1, 'a');
            fwrite($fp1, "\n**************** Careers CSV Cron Execution Started - " . $start_time . " **************** \n");
            
            $yesterday = date('Y-m-d', strtotime("- 1 day"));
            
            $select = 'c.careers_id,p.position, c.unique_no, c.sel_namesub, c.firstname, c.middlename, c.lastname, c.father_husband_name, c.dateofbirth, c.gender, c.email, c.marital_status, c.mobile, c.alternate_mobile, c.pan_no, c.aadhar_card_no, c.addressline1, c.addressline2, c.addressline3, c.addressline4, c.district, c.city, c.state, c.pincode, c.contact_number, c.addressline1_pr, c.addressline2_pr, c.addressline3_pr, c.addressline4_pr, c.district_pr, c.city_pr, c.state_pr, c.pincode_pr, c.contact_number_pr, c.exam_center, c.ess_course_name, c.ess_subject, c.ess_college_name, c.ess_university, c.ess_from_date, c.ess_to_date, c.ess_grade_marks, c.ess_class, m.course_name, q.college_name, q.university, q.from_date, q.to_date, q.grade_marks, q.class, c.year_of_passing, c.membership_number, c.languages_known, c.languages_option, c.languages_known1, c.languages_option1, c.languages_known2, c.languages_option2, c.extracurricular, c.hobbies, c.achievements, c.refname_one, c.refaddressline_one, c.refemail_one, c.refmobile_one, c.refname_two, c.refaddressline_two, c.refemail_two, c.refmobile_two, c.declaration1, c.declaration_note, c.declaration2, c.scannedphoto, c.scannedsignaturephoto, c.comment, c.place, DATE(c.submit_date) as submit_date, DATE(c.createdon) as apply_date, c.exp_in_bank,c.exp_in_functional_area,c.deputy_subject,q.specialisation';
            //$this->db->join('careers_employment_hist e', 'e.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_edu_qualification q', 'q.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_course_mst m', 'm.course_code = q.course_code', 'LEFT');
            $this->db->join('careers_position_master p', 'p.id = c.position_id', 'LEFT');
            $this->db->where('DATE(c.createdon) >=', $from_date);
            $this->db->where('DATE(c.createdon) <=', $to_date);
            $this->db->where('c.position_id', $position_id);
            $can_exam_data = $this->Master_model->getRecords('careers_registration c', array(
                'active_status' => '1'
            ), $select);
            echo "SQL>" . $this->db->last_query();
            if (count($can_exam_data)) {
                $i             = 1;
                $exam_cnt      = 0;
                // Column headers for CSV            
                $data1         = "Job_Position,Name,Father_Husband_Name,Date_of_Birth,Gender,Email,Marital_Status,Mobile,Alternate_Mobile,PAN_No,Aadhar_card_no,Address,Contact_no,Permanent_Address,Contact_Number,Exam_Center,Essential_Course_Name,Deputy Subject,Essential_subject,Essential_college_name,Essential_University,Essential_from_date,Essential_to_date,Essential_grade_marks,Essential_class,Desirable_course_name,Desirable_Specialisation,Desirable_college_name,Desirable_university,Desirable_from_date,Desirable_to_date,Desirable_grade_marks,Desirable_class,Year_of_passing,Membership_no,organization1,designation1,responsibilities1,job_from_date1,job_to_date1,organization2,designation2,responsibilities2,job_from_date2,job_to_date2,organization3,designation3,responsibilities3,job_from_date3,job_to_date3,organization4,designation4,responsibilities4,job_from_date4,job_to_date4,Experience as Faculty in Banks/Financial Institutions,Experience in one or more covering the functional areas,Languages_known,Languages_option,Languages_known1,Languages_option1,Languages_known2,Languages_option2,Extracurricular,Hobbies,Achievements,Refname_one,Refaddressline_one,Refemail_one,Refmobile_one,Refname_two,Refaddressline_two,Refemail_two,Refmobile_two,Any other information that the candidate would like to add,Declaration : I declare that all statements made in this application are true complete and correct to the best of my knowledge and belief. I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking and Finance my candidature/ appointment for the said post is liable to be cancelled at any stage and even after appointment my services are liable to be terminated without any notice ,Photo,Signature,Place,Submit_date,Apply_date\n";
                $exam_file_flg = fwrite($fp, $data1);
                
                foreach ($can_exam_data as $exam) {
                    $job_arr_data = array();
                    $select       = 'organization,designation,responsibilities,job_from_date,job_to_date';
                    $this->db->where('careers_id', $exam['careers_id']);
                    $job_arr_data = $this->Master_model->getRecords('careers_employment_hist', '', $select);
                    
                    $data = '';
                    
                    $unique_no = $sel_namesub = $firstname = $middlename = $lastname = $father_husband_name = $dateofbirth = $gender = $email = $marital_status = $mobile = $alternate_mobile = $pan_no = $aadhar_card_no = $addressline1 = $addressline2 = $addressline3 = $addressline4 = $district = $city = $state = $pincode = $contact_number = $addressline1_pr = $addressline2_pr = $addressline3_pr = $addressline4_pr = $district_pr = $city_pr = $state_pr = $pincode_pr = $contact_number_pr = $exam_center = $ess_course_name = $ess_subject = $ess_college_name = $ess_university = $ess_from_date = $ess_to_date = $ess_grade_marks = $ess_class = $course_name = $college_name = $university = $from_date = $to_date = $grade_marks = $class = $year_of_passing = $membership_number = $languages_known = $languages_option = $languages_known1 = $languages_option1 = $languages_known2 = $languages_option2 = $extracurricular = $hobbies = $achievements = $refname_one = $refaddressline_one = $refemail_one = $refmobile_one = $refname_two = $refaddressline_two = $refemail_two = $refmobile_two = $declaration1 = $declaration_note = $declaration2 = $scannedphoto = $scannedsignaturephoto = $comment = $place = $submit_date = $createdon = $name = $address = $address_pr = $gender = $organization1 = $designation1 = $responsibilities1 = $job_from_date1 = $job_to_date1 = $organization2 = $designation2 = $responsibilities2 = $job_from_date2 = $job_to_date2 = $organization3 = $designation3 = $responsibilities3 = $job_from_date3 = $job_to_date3 = $organization4 = $designation4 = $responsibilities4 = $job_from_date4 = $job_to_date4 = $exp_in_bank = $exp_in_functional_area = $deputy_subject = $specialisation = '';
                    
                    $position               = $exam['position'];
                    $unique_no              = $exam['unique_no'];
                    $sel_namesub            = $exam['sel_namesub'];
                    $firstname              = $exam['firstname'];
                    $middlename             = $exam['middlename'];
                    $lastname               = $exam['lastname'];
                    $father_husband_name    = $exam['father_husband_name'];
                    $dateofbirth            = $exam['dateofbirth'];
                    $gender                 = $exam['gender'];
                    $email                  = $exam['email'];
                    $marital_status         = $exam['marital_status'];
                    $mobile                 = $exam['mobile'];
                    $alternate_mobile       = $exam['alternate_mobile'];
                    $pan_no                 = $exam['pan_no'];
                    $aadhar_card_no         = $exam['aadhar_card_no'];
                    $addressline1           = $exam['addressline1'];
                    $addressline2           = $exam['addressline2'];
                    $addressline3           = $exam['addressline3'];
                    $addressline4           = $exam['addressline4'];
                    $district               = $exam['district'];
                    $city                   = $exam['city'];
                    $state                  = $exam['state'];
                    $pincode                = $exam['pincode'];
                    $contact_number         = $exam['contact_number'];
                    $addressline1_pr        = $exam['addressline1_pr'];
                    $addressline2_pr        = $exam['addressline2_pr'];
                    $addressline3_pr        = $exam['addressline3_pr'];
                    $addressline4_pr        = $exam['addressline4_pr'];
                    $district_pr            = $exam['district_pr'];
                    $city_pr                = $exam['city_pr'];
                    $state_pr               = $exam['state_pr'];
                    $pincode_pr             = $exam['pincode_pr'];
                    $contact_number_pr      = $exam['contact_number_pr'];
                    $exam_center            = $exam['exam_center'];
                    $ess_course_name        = $exam['ess_course_name'];
                    $ess_subject            = $exam['ess_subject'];
                    $ess_college_name       = str_replace(',', ' | ', $exam['ess_college_name']);
                    $ess_university         = str_replace(',', ' | ', $exam['ess_university']);
                    $ess_from_date          = $exam['ess_from_date'];
                    $ess_to_date            = $exam['ess_to_date'];
                    $ess_grade_marks        = $exam['ess_grade_marks'];
                    $ess_class              = $exam['ess_class'];
                    $course_name            = $exam['course_name'];
                    $college_name           = str_replace(',', ' | ', $exam['college_name']);
                    $university             = $exam['university'];
                    $from_date              = $exam['from_date'];
                    $to_date                = $exam['to_date'];
                    $grade_marks            = $exam['grade_marks'];
                    $class                  = $exam['class'];
                    $year_of_passing        = $exam['year_of_passing'];
                    $membership_number      = $exam['membership_number'];
                    $languages_known        = $exam['languages_known'];
                    $languages_option       = str_replace(',', ' | ', $exam['languages_option']);
                    $languages_known1       = $exam['languages_known1'];
                    $languages_option1      = str_replace(',', ' | ', $exam['languages_option1']);
                    $languages_known2       = $exam['languages_known2'];
                    $languages_option2      = str_replace(',', ' | ', $exam['languages_option2']);
                    $extracurricular        = str_replace(',', ' | ', $exam['extracurricular']);
                    $hobbies                = str_replace(',', ' | ', $exam['hobbies']);
                    $achievements           = str_replace(',', ' | ', $exam['achievements']);
                    $refname_one            = $exam['refname_one'];
                    $refaddressline_one     = trim(str_replace(',', ' | ', $exam['refaddressline_one']));
                    $refemail_one           = $exam['refemail_one'];
                    $refmobile_one          = $exam['refmobile_one'];
                    $refname_two            = $exam['refname_two'];
                    $refaddressline_two     = trim(str_replace(',', ' | ', $exam['refaddressline_two']));
                    $refemail_two           = $exam['refemail_two'];
                    $refmobile_two          = $exam['refmobile_two'];
                    $declaration1           = $exam['declaration1'];
                    $declaration_note       = trim(str_replace(',', ' | ', $exam['declaration_note']));
                    $declaration2           = $exam['declaration2'];
                    $scannedphoto           = $exam['scannedphoto'];
                    $scannedsignaturephoto  = $exam['scannedsignaturephoto'];
                    $comment                = str_replace(',', ' | ', $exam['comment']);
                    $place                  = $exam['place'];
                    $submit_date            = $exam['submit_date'];
                    $apply_date             = $exam['apply_date'];
                    $exp_in_bank            = trim(str_replace(',', ' | ', $exam['exp_in_bank']));
                    $exp_in_functional_area = trim(str_replace(',', ' | ', $exam['exp_in_functional_area']));
                    $deputy_subject         =trim(str_replace(',', ' | ', $exam['deputy_subject']));
					$specialisation         =trim(str_replace(',', ' | ', $exam['specialisation']));
                    /* Name */
                    $name = $sel_namesub . ' ' . $firstname . ' ' . $middlename . ' ' . $lastname;
                    $name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $name);
                    
                    /* Address */
                    $address = $addressline1 . ' ' . $addressline2 . ' ' . $addressline3 . ' ' . $addressline4 . ' ' . $district . ' ' . $city . ' ' . $state . ' ' . $pincode;
                    $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
                    
                    /* Address Prnt */
                    $address_pr = $addressline1_pr . ' ' . $addressline2_pr . ' ' . $addressline3_pr . ' ' . $addressline4_pr . ' ' . $district_pr . ' ' . $city_pr . ' ' . $state_pr . ' ' . $pincode_pr;
                    $address_pr = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address_pr);
                    
                    /* Gender */
                    $gender = $gender;
                    if ($gender == 'male' || $gender == 'Male') {
                        $gender = 'Male';
                    } elseif ($gender == 'female' || $gender == 'Female') {
                        $gender = 'Female';
                    }
                    
                    foreach ($job_arr_data as $jk => $job_data) {
                        if ($jk == 0) {
                            $organization1     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation1      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities1 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date1    = $job_data['job_from_date'];
                            $job_to_date1      = $job_data['job_to_date'];
							//$specialisation1   = trim(str_replace(',', ' | ', $job_data['specialisation']));
                        } elseif ($jk == 1) {
                            $organization2     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation2      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities2 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date2    = $job_data['job_from_date'];
                            $job_to_date2      = $job_data['job_to_date'];
                        } elseif ($jk == 2) {
                            $organization3     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation3      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities3 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date3    = $job_data['job_from_date'];
                            $job_to_date3      = $job_data['job_to_date'];
                        } elseif ($jk == 3) {
                            $organization4     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation4      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities4 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date4    = $job_data['job_from_date'];
                            $job_to_date4      = $job_data['job_to_date'];
                        }
                    }
                    
                    /* Job_Position | Name | Father_Husband_Name | Date_of_Birth | Gender | Email | Marital_Status | Mobile | Alternate_Mobile | PAN_No | Aadhar_card_no | Address | Contact_no | Permanent_Address | Contact_Number | Exam_Center | Essential_Course_Name | Essential_subject | Essential_college_name | Essential_University | Essential_from_date | Essential_to_date | Essential_grade_marks | Essential_class | Desirable_course_name | Desirable_college_name | Desirable_university | Desirable_from_date | Desirable_to_date | Desirable_grade_marks | Desirable_class | Year_of_passing | Membership_no | Organization | Designation | Responsibilities | Job_from_date | Job_to_date | Languages_known | Languages_option | Languages_known1 | Languages_option1 | Languages_known2 | Languages_option2 | Extracurricular | Hobbies | Achievements | Refname_one | Refaddressline_one | Refemail_one | Refmobile_one | Refname_two | Refaddressline_two,Refemail_two | Refmobile_two | Declaration1 | Declaration_note | Declaration2 | Photo | Signature | Comment | Place | Submit_date | Apply_date */
                    
                    
                    
                    
                    $data .= '' . $position . ',' . $name . ',' . $father_husband_name . ',' . $dateofbirth . ',' . $gender . ',' . $email . ',' . $marital_status . ',' . $mobile . ',' . $alternate_mobile . ',' . $pan_no . ',' . $aadhar_card_no . ',' . $address . ',' . $contact_number . ',' . $address_pr . ',' . $contact_number_pr . ',' . $exam_center . ',' . $ess_course_name . ',' . $deputy_subject . ',' . $ess_subject . ',' . $ess_college_name . ',' . $ess_university . ',' . $ess_from_date . ',' . $ess_to_date . ',' . $ess_grade_marks . ',' . $ess_class . ',' . $course_name . ',' . $specialisation . ',' . $college_name . ',' . $university . ',' . $from_date . ',' . $to_date . ',' . $grade_marks . ',' . $class . ',' . $year_of_passing . ',' . $membership_number . ',' . $organization1 . ',' . $designation1 . ',' . $responsibilities1 . ',' . $job_from_date1 . ',' . $job_to_date1 . ',' . $organization2 . ',' . $designation2 . ',' . $responsibilities2 . ',' . $job_from_date2 . ',' . $job_to_date2 . ',' . $organization3 . ',' . $designation3 . ',' . $responsibilities3 . ',' . $job_from_date3 . ',' . $job_to_date3 . ',' . $organization4 . ',' . $designation4 . ',' . $responsibilities4 . ',' . $job_from_date4 . ',' . $job_to_date4 . ',' . $exp_in_bank . ',' . $exp_in_functional_area . ',' . $languages_known . ',' . $languages_option . ',' . $languages_known1 . ',' . $languages_option1 . ',' . $languages_known2 . ',' . $languages_option2 . ',' . $extracurricular . ',' . $hobbies . ',' . $achievements . ',' . $refname_one . ',' . $refaddressline_one . ',' . $refemail_one . ',' . $refmobile_one . ',' . $refname_two . ',' . $refaddressline_two . ',' . $refemail_two . ',' . $refmobile_two . ',' . $comment . ',' . $declaration2 . ',' . $scannedphoto . ',' . $scannedsignaturephoto . ',' . $place . ',' . $submit_date . ',' . $apply_date . "\n";
                    
                    $exam_file_flg = fwrite($fp, $data);
                    if ($exam_file_flg)
                        $success['cand_exam'] = "Careers CSV File Generated Successfully.";
                    else
                        $error['cand_exam'] = "Error While Generating Careers CSV File.";
                    $i++;
                    $exam_cnt++;
                }
                //exit;
                fwrite($fp1, "\n Total Applications - " . $exam_cnt . "\n");
                
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => $exam_cnt,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
            } else {
                $yesterday = date('Y-m-d', strtotime("- 1 day"));
                fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => 0,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
                $success[] = "No data found for the date";
            }
            fclose($fp);
            $end_time = date("Y-m-d H:i:s");
            $result   = array(
                "success" => $success,
                "error" => $error,
                "Start Time" => $start_time,
                "End Time" => $end_time
                
            );
            $desc     = json_encode($result);
            $this->log_model->cronlog("Careers CSV Cron Execution End", $desc);
            fwrite($fp1, "\n" . "**************** Careers CSV Cron Execution End " . $end_time . " *****************" . "\n");
            fclose($fp1);
        }
    }
    
    // Careers
    public function careers_faculty_member($position_id, $from_date, $to_date)
    {
        
        //https://iibf.esdsconnect.com/admin/cron_csv_custom/careers_faculty_member/7/2020-02-22/2020-03-12
        
        ini_set("memory_limit", "-1");
        $dir_flg        = 0;
        $parent_dir_flg = 0;
        $exam_file_flg  = 0;
        $success        = array();
        $error          = array();
        $start_time     = date("Y-m-d H:i:s");
        $current_date   = date("Ymd");
        $cron_file_dir  = "./uploads/careers_csv/";
        $result         = array(
            "success" => "",
            "error" => "",
            "Start Time" => $start_time,
            "End Time" => ""
        );
        $desc           = json_encode($result);
        
        if ($position_id == 7) {
            $csv_name = "Faculty_Member";
        }
        
        
        $this->log_model->cronlog("Careers CSV Cron Execution Start", $desc);
        if (!file_exists($cron_file_dir . $current_date)) {
            $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
        }
        if (file_exists($cron_file_dir . $current_date)) {
            $cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
            $file           = "Careers_" . $csv_name . "_" . $current_date . ".csv";
            $fp             = fopen($cron_file_path . '/' . $file, 'w');
            $file1          = "Careers_logs_" . $current_date . ".txt";
            $fp1            = fopen($cron_file_path . '/' . $file1, 'a');
            fwrite($fp1, "\n**************** Careers CSV Cron Execution Started - " . $start_time . " **************** \n");
            
            $yesterday = date('Y-m-d', strtotime("- 1 day"));
            
            $select = 'c.careers_id,p.position, c.unique_no, c.sel_namesub, c.firstname, c.middlename, c.lastname, c.father_husband_name, c.dateofbirth, c.gender, c.email, c.marital_status, c.mobile, c.alternate_mobile, c.pan_no, c.aadhar_card_no, c.addressline1, c.addressline2, c.addressline3, c.addressline4, c.district, c.city, c.state, c.pincode, c.contact_number, c.addressline1_pr, c.addressline2_pr, c.addressline3_pr, c.addressline4_pr, c.district_pr, c.city_pr, c.state_pr, c.pincode_pr, c.contact_number_pr, c.exam_center, c.ess_course_name, c.ess_subject, c.ess_college_name, c.ess_university, c.ess_from_date, c.ess_to_date, c.ess_grade_marks, c.ess_class, m.course_name, q.college_name, q.university, q.from_date, q.to_date, q.grade_marks, q.class, c.year_of_passing, c.membership_number, c.languages_known, c.languages_option, c.languages_known1, c.languages_option1, c.languages_known2, c.languages_option2, c.extracurricular, c.hobbies, c.achievements, c.refname_one, c.refaddressline_one, c.refemail_one, c.refmobile_one, c.refname_two, c.refaddressline_two, c.refemail_two, c.refmobile_two, c.declaration1, c.declaration_note, c.declaration2, c.scannedphoto, c.scannedsignaturephoto, c.comment, c.place, DATE(c.submit_date) as submit_date, DATE(c.createdon) as apply_date, c.exp_in_bank,c.publication_of_books';
            //$this->db->join('careers_employment_hist e', 'e.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_edu_qualification q', 'q.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_course_mst m', 'm.course_code = q.course_code', 'LEFT');
            $this->db->join('careers_position_master p', 'p.id = c.position_id', 'LEFT');
            $this->db->where('DATE(c.createdon) >=', $from_date);
            $this->db->where('DATE(c.createdon) <=', $to_date);
            $this->db->where('c.position_id', $position_id);
            $can_exam_data = $this->Master_model->getRecords('careers_registration c', array(
                'active_status' => '1'
            ), $select);
            echo "SQL>" . $this->db->last_query();
            if (count($can_exam_data)) {
                $i             = 1;
                $exam_cnt      = 0;
                // Column headers for CSV            
                $data1         = "Job_Position,Name,Father_Husband_Name,Date_of_Birth,Gender,Email,Marital_Status,Mobile,Alternate_Mobile,PAN_No,Aadhar_card_no,Address,Contact_no,Permanent_Address,Contact_Number,Exam_Center,Essential_Course_Name,Essential_subject,Essential_college_name,Essential_University,Essential_from_date,Essential_to_date,Essential_grade_marks,Essential_class,Desirable_course_name,Desirable_college_name,Desirable_university,Desirable_from_date,Desirable_to_date,Desirable_grade_marks,Desirable_class,Year_of_passing,Membership_no,organization1,designation1,responsibilities1,job_from_date1,job_to_date1,organization2,designation2,responsibilities2,job_from_date2,job_to_date2,organization3,designation3,responsibilities3,job_from_date3,job_to_date3,organization4,designation4,responsibilities4,job_from_date4,job_to_date4,Experience as Faculty in Banks/Financial Institutions,Published Articles/Books,Languages_known,Languages_option,Languages_known1,Languages_option1,Languages_known2,Languages_option2,Extracurricular,Hobbies,Achievements,Refname_one,Refaddressline_one,Refemail_one,Refmobile_one,Refname_two,Refaddressline_two,Refemail_two,Refmobile_two,Any other information that the candidate would like to add,Declaration : I declare that all statements made in this application are true complete and correct to the best of my knowledge and belief. I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking and Finance my candidature/ appointment for the said post is liable to be cancelled at any stage and even after appointment my services are liable to be terminated without any notice ,Photo,Signature,Place,Submit_date,Apply_date\n";
                $exam_file_flg = fwrite($fp, $data1);
                
                foreach ($can_exam_data as $exam) {
                    $job_arr_data = array();
                    $select       = 'organization,designation,responsibilities,job_from_date,job_to_date';
                    $this->db->where('careers_id', $exam['careers_id']);
                    $job_arr_data = $this->Master_model->getRecords('careers_employment_hist', '', $select);
                    
                    $data = '';
                    
                    $unique_no = $sel_namesub = $firstname = $middlename = $lastname = $father_husband_name = $dateofbirth = $gender = $email = $marital_status = $mobile = $alternate_mobile = $pan_no = $aadhar_card_no = $addressline1 = $addressline2 = $addressline3 = $addressline4 = $district = $city = $state = $pincode = $contact_number = $addressline1_pr = $addressline2_pr = $addressline3_pr = $addressline4_pr = $district_pr = $city_pr = $state_pr = $pincode_pr = $contact_number_pr = $exam_center = $ess_course_name = $ess_subject = $ess_college_name = $ess_university = $ess_from_date = $ess_to_date = $ess_grade_marks = $ess_class = $course_name = $college_name = $university = $from_date = $to_date = $grade_marks = $class = $year_of_passing = $membership_number = $languages_known = $languages_option = $languages_known1 = $languages_option1 = $languages_known2 = $languages_option2 = $extracurricular = $hobbies = $achievements = $refname_one = $refaddressline_one = $refemail_one = $refmobile_one = $refname_two = $refaddressline_two = $refemail_two = $refmobile_two = $declaration1 = $declaration_note = $declaration2 = $scannedphoto = $scannedsignaturephoto = $comment = $place = $submit_date = $createdon = $name = $address = $address_pr = $gender = $organization1 = $designation1 = $responsibilities1 = $job_from_date1 = $job_to_date1 = $organization2 = $designation2 = $responsibilities2 = $job_from_date2 = $job_to_date2 = $organization3 = $designation3 = $responsibilities3 = $job_from_date3 = $job_to_date3 = $organization4 = $designation4 = $responsibilities4 = $job_from_date4 = $job_to_date4 = $exp_in_bank = $publication_of_books = '';
                    
                    $position              = $exam['position'];
                    $unique_no             = $exam['unique_no'];
                    $sel_namesub           = $exam['sel_namesub'];
                    $firstname             = $exam['firstname'];
                    $middlename            = $exam['middlename'];
                    $lastname              = $exam['lastname'];
                    $father_husband_name   = $exam['father_husband_name'];
                    $dateofbirth           = $exam['dateofbirth'];
                    $gender                = $exam['gender'];
                    $email                 = $exam['email'];
                    $marital_status        = $exam['marital_status'];
                    $mobile                = $exam['mobile'];
                    $alternate_mobile      = $exam['alternate_mobile'];
                    $pan_no                = $exam['pan_no'];
                    $aadhar_card_no        = $exam['aadhar_card_no'];
                    $addressline1          = $exam['addressline1'];
                    $addressline2          = $exam['addressline2'];
                    $addressline3          = $exam['addressline3'];
                    $addressline4          = $exam['addressline4'];
                    $district              = $exam['district'];
                    $city                  = $exam['city'];
                    $state                 = $exam['state'];
                    $pincode               = $exam['pincode'];
                    $contact_number        = $exam['contact_number'];
                    $addressline1_pr       = $exam['addressline1_pr'];
                    $addressline2_pr       = $exam['addressline2_pr'];
                    $addressline3_pr       = $exam['addressline3_pr'];
                    $addressline4_pr       = $exam['addressline4_pr'];
                    $district_pr           = $exam['district_pr'];
                    $city_pr               = $exam['city_pr'];
                    $state_pr              = $exam['state_pr'];
                    $pincode_pr            = $exam['pincode_pr'];
                    $contact_number_pr     = $exam['contact_number_pr'];
                    $exam_center           = $exam['exam_center'];
                    $ess_course_name       = $exam['ess_course_name'];
                    $ess_subject           = $exam['ess_subject'];
                    $ess_college_name      = trim(str_replace(',', ' | ', $exam['ess_college_name']));
                    $ess_university        = trim(str_replace(',', ' | ', $exam['ess_university']));
                    $ess_from_date         = $exam['ess_from_date'];
                    $ess_to_date           = $exam['ess_to_date'];
                    $ess_grade_marks       = $exam['ess_grade_marks'];
                    $ess_class             = $exam['ess_class'];
                    $course_name           = $exam['course_name'];
                    $college_name          = trim(str_replace(',', ' | ', $exam['college_name']));
                    $university            = $exam['university'];
                    $from_date             = $exam['from_date'];
                    $to_date               = $exam['to_date'];
                    $grade_marks           = $exam['grade_marks'];
                    $class                 = $exam['class'];
                    $year_of_passing       = $exam['year_of_passing'];
                    $membership_number     = $exam['membership_number'];
                    $languages_known       = $exam['languages_known'];
                    $languages_option      = trim(str_replace(',', ' | ', $exam['languages_option']));
                    $languages_known1      = $exam['languages_known1'];
                    $languages_option1     = trim(str_replace(',', ' | ', $exam['languages_option1']));
                    $languages_known2      = $exam['languages_known2'];
                    $languages_option2     = trim(str_replace(',', ' | ', $exam['languages_option2']));
                    $extracurricular       = trim(str_replace(',', ' | ', $exam['extracurricular']));
                    $hobbies               = trim(str_replace(',', ' | ', $exam['hobbies']));
                    $achievements          = trim(str_replace(',', ' | ', $exam['achievements']));
                    $refname_one           = $exam['refname_one'];
                    $refaddressline_one    = trim(str_replace(',', ' | ', $exam['refaddressline_one']));
                    $refemail_one          = $exam['refemail_one'];
                    $refmobile_one         = $exam['refmobile_one'];
                    $refname_two           = $exam['refname_two'];
                    $refaddressline_two    = trim(str_replace(',', ' | ', $exam['refaddressline_two']));
                    $refemail_two          = $exam['refemail_two'];
                    $refmobile_two         = $exam['refmobile_two'];
                    $declaration1          = $exam['declaration1'];
                    $declaration_note      = trim(str_replace(',', ' | ', $exam['declaration_note']));
                    $declaration2          = $exam['declaration2'];
                    $scannedphoto          = $exam['scannedphoto'];
                    $scannedsignaturephoto = $exam['scannedsignaturephoto'];
                    $comment               = trim(str_replace(',', ' | ', $exam['comment']));
                    $place                 = $exam['place'];
                    $submit_date           = $exam['submit_date'];
                    $apply_date            = $exam['apply_date'];
                    $exp_in_bank           = trim(str_replace(',', ' | ', $exam['exp_in_bank']));
                    $publication_of_books  = trim(str_replace(',', ' | ', $exam['publication_of_books']));
                    
                    /* Name */
                    $name = $sel_namesub . ' ' . $firstname . ' ' . $middlename . ' ' . $lastname;
                    $name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $name);
                    
                    /* Address */
                    $address = $addressline1 . ' ' . $addressline2 . ' ' . $addressline3 . ' ' . $addressline4 . ' ' . $district . ' ' . $city . ' ' . $state . ' ' . $pincode;
                    $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
                    
                    /* Address Prnt */
                    $address_pr = $addressline1_pr . ' ' . $addressline2_pr . ' ' . $addressline3_pr . ' ' . $addressline4_pr . ' ' . $district_pr . ' ' . $city_pr . ' ' . $state_pr . ' ' . $pincode_pr;
                    $address_pr = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address_pr);
                    
                    /* Gender */
                    $gender = $gender;
                    if ($gender == 'male' || $gender == 'Male') {
                        $gender = 'Male';
                    } elseif ($gender == 'female' || $gender == 'Female') {
                        $gender = 'Female';
                    }
                    
                    foreach ($job_arr_data as $jk => $job_data) {
                        if ($jk == 0) {
                            $organization1     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation1      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities1 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date1    = $job_data['job_from_date'];
                            $job_to_date1      = $job_data['job_to_date'];
                        } elseif ($jk == 1) {
                            $organization2     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation2      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities2 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date2    = $job_data['job_from_date'];
                            $job_to_date2      = $job_data['job_to_date'];
                        } elseif ($jk == 2) {
                            $organization3     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation3      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities3 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date3    = $job_data['job_from_date'];
                            $job_to_date3      = $job_data['job_to_date'];
                        } elseif ($jk == 3) {
                            $organization4     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation4      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities4 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date4    = $job_data['job_from_date'];
                            $job_to_date4      = $job_data['job_to_date'];
                        }
                    }
                    
                    /* Job_Position | Name | Father_Husband_Name | Date_of_Birth | Gender | Email | Marital_Status | Mobile | Alternate_Mobile | PAN_No | Aadhar_card_no | Address | Contact_no | Permanent_Address | Contact_Number | Exam_Center | Essential_Course_Name | Essential_subject | Essential_college_name | Essential_University | Essential_from_date | Essential_to_date | Essential_grade_marks | Essential_class | Desirable_course_name | Desirable_college_name | Desirable_university | Desirable_from_date | Desirable_to_date | Desirable_grade_marks | Desirable_class | Year_of_passing | Membership_no | Organization | Designation | Responsibilities | Job_from_date | Job_to_date | Languages_known | Languages_option | Languages_known1 | Languages_option1 | Languages_known2 | Languages_option2 | Extracurricular | Hobbies | Achievements | Refname_one | Refaddressline_one | Refemail_one | Refmobile_one | Refname_two | Refaddressline_two,Refemail_two | Refmobile_two | Declaration1 | Declaration_note | Declaration2 | Photo | Signature | Comment | Place | Submit_date | Apply_date */
                    
                    
                    
                    
                    $data .= '' . $position . ',' . $name . ',' . $father_husband_name . ',' . $dateofbirth . ',' . $gender . ',' . $email . ',' . $marital_status . ',' . $mobile . ',' . $alternate_mobile . ',' . $pan_no . ',' . $aadhar_card_no . ',' . $address . ',' . $contact_number . ',' . $address_pr . ',' . $contact_number_pr . ',' . $exam_center . ',' . $ess_course_name . ',' . $ess_subject . ',' . $ess_college_name . ',' . $ess_university . ',' . $ess_from_date . ',' . $ess_to_date . ',' . $ess_grade_marks . ',' . $ess_class . ',' . $course_name . ',' . $college_name . ',' . $university . ',' . $from_date . ',' . $to_date . ',' . $grade_marks . ',' . $class . ',' . $year_of_passing . ',' . $membership_number . ',' . $organization1 . ',' . $designation1 . ',' . $responsibilities1 . ',' . $job_from_date1 . ',' . $job_to_date1 . ',' . $organization2 . ',' . $designation2 . ',' . $responsibilities2 . ',' . $job_from_date2 . ',' . $job_to_date2 . ',' . $organization3 . ',' . $designation3 . ',' . $responsibilities3 . ',' . $job_from_date3 . ',' . $job_to_date3 . ',' . $organization4 . ',' . $designation4 . ',' . $responsibilities4 . ',' . $job_from_date4 . ',' . $job_to_date4 . ',' . $exp_in_bank . ',' . $publication_of_books . ',' . $languages_known . ',' . $languages_option . ',' . $languages_known1 . ',' . $languages_option1 . ',' . $languages_known2 . ',' . $languages_option2 . ',' . $extracurricular . ',' . $hobbies . ',' . $achievements . ',' . $refname_one . ',' . $refaddressline_one . ',' . $refemail_one . ',' . $refmobile_one . ',' . $refname_two . ',' . $refaddressline_two . ',' . $refemail_two . ',' . $refmobile_two . ',' . $comment . ',' . $declaration2 . ',' . $scannedphoto . ',' . $scannedsignaturephoto . ',' . $place . ',' . $submit_date . ',' . $apply_date . "\n";
                    
                    $exam_file_flg = fwrite($fp, $data);
                    if ($exam_file_flg)
                        $success['cand_exam'] = "Careers CSV File Generated Successfully.";
                    else
                        $error['cand_exam'] = "Error While Generating Careers CSV File.";
                    $i++;
                    $exam_cnt++;
                }
                //exit;
                fwrite($fp1, "\n Total Applications - " . $exam_cnt . "\n");
                
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => $exam_cnt,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
            } else {
                $yesterday = date('Y-m-d', strtotime("- 1 day"));
                fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => 0,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
                $success[] = "No data found for the date";
            }
            fclose($fp);
            $end_time = date("Y-m-d H:i:s");
            $result   = array(
                "success" => $success,
                "error" => $error,
                "Start Time" => $start_time,
                "End Time" => $end_time
                
            );
            $desc     = json_encode($result);
            $this->log_model->cronlog("Careers CSV Cron Execution End", $desc);
            fwrite($fp1, "\n" . "**************** Careers CSV Cron Execution End " . $end_time . " *****************" . "\n");
            fclose($fp1);
        }
    }
    
    // Careers
    public function careers_csv_ceo($position_id, $from_date, $to_date)
    {
        
        //https://iibf.esdsconnect.com/admin/cron_csv_custom/careers_csv_ceo/5/2020-02-15/2020-03-10
        
        ini_set("memory_limit", "-1");
        $dir_flg        = 0;
        $parent_dir_flg = 0;
        $exam_file_flg  = 0;
        $success        = array();
        $error          = array();
        $start_time     = date("Y-m-d H:i:s");
        $current_date   = date("Ymd");
        $cron_file_dir  = "./uploads/careers_csv/";
        $result         = array(
            "success" => "",
            "error" => "",
            "Start Time" => $start_time,
            "End Time" => ""
        );
        $desc           = json_encode($result);
        
        
        if ($position_id == 5) {
            $csv_name = "CEO";
        }
        
        
        $this->log_model->cronlog("Careers CSV Cron Execution Start", $desc);
        if (!file_exists($cron_file_dir . $current_date)) {
            $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
        }
        if (file_exists($cron_file_dir . $current_date)) {
            $cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
            $file           = "Careers_" . $csv_name . "_" . $current_date . ".csv";
            $fp             = fopen($cron_file_path . '/' . $file, 'w');
            $file1          = "Careers_logs_" . $current_date . ".txt";
            $fp1            = fopen($cron_file_path . '/' . $file1, 'a');
            fwrite($fp1, "\n**************** Careers CSV Cron Execution Started - " . $start_time . " **************** \n");
            
            $yesterday = date('Y-m-d', strtotime("- 1 day"));
            
            $select = 'c.careers_id,p.position, c.unique_no, c.sel_namesub, c.firstname, c.middlename, c.lastname, c.father_husband_name, c.dateofbirth, c.gender, c.email, c.marital_status, c.mobile, c.alternate_mobile, c.pan_no, c.aadhar_card_no, c.addressline1, c.addressline2, c.addressline3, c.addressline4, c.district, c.city, c.state, c.pincode, c.contact_number, c.addressline1_pr, c.addressline2_pr, c.addressline3_pr, c.addressline4_pr, c.district_pr, c.city_pr, c.state_pr, c.pincode_pr, c.contact_number_pr, c.exam_center, c.ess_course_name, c.ess_subject, c.ess_college_name, c.ess_university, c.ess_from_date, c.ess_to_date, c.ess_grade_marks, c.ess_class, m.course_name, q.college_name, q.university, q.from_date, q.to_date, q.grade_marks, q.class, c.year_of_passing, c.membership_number, c.languages_known, c.languages_option, c.languages_known1, c.languages_option1, c.languages_known2, c.languages_option2, c.extracurricular, c.hobbies, c.achievements, c.refname_one, c.refaddressline_one, c.refemail_one, c.refmobile_one, c.refname_two, c.refaddressline_two, c.refemail_two, c.refmobile_two, c.declaration1, c.declaration_note, c.declaration2, c.scannedphoto, c.scannedsignaturephoto, c.comment, c.place, DATE(c.submit_date) as submit_date, DATE(c.createdon) as apply_date,c.ph_d,c.phd_course,c.phd_university,c.publication_of_books,c.publication_of_articles,c.area_of_specialization,c.earliest_date_of_joining,c.suitable_of_the_post_of_CEO';
            $this->db->join('careers_edu_qualification q', 'q.careers_id=c.careers_id', 'LEFT');
            $this->db->join('careers_course_mst m', 'm.course_code = q.course_code', 'LEFT');
            $this->db->join('careers_position_master p', 'p.id = c.position_id', 'LEFT');
            $this->db->where('DATE(c.createdon) >=', $from_date);
            $this->db->where('DATE(c.createdon) <=', $to_date);
            $this->db->where('c.position_id', $position_id);
            $can_exam_data = $this->Master_model->getRecords('careers_registration c', array(
                'active_status' => '1'
            ), $select);
            //echo "SQL>".$this->db->last_query();
            
            if (count($can_exam_data)) {
                $i        = 1;
                $exam_cnt = 0;
                // Column headers for CSV            
                $data1    = "Job_Position,Name,Father_Husband_Name,Date_of_Birth,Gender,Email,Marital_Status,Mobile,Alternate_Mobile,PAN_No,Aadhar_card_no,Languages_known,Languages_option,Languages_known1,Languages_option1,Languages_known2,Languages_option2,Address,Contact_no,Permanent_Address,Contact_Number,Essential_Course_Name,Essential_College_Name,Essential_University,Essential_from_date,Essential_to_date,Essential_grade_marks,Essential_Class,CAIIB,Year_of_Passing,Membership_no,organization1,designation1,responsibilities1,job_from_date1,job_to_date1,organization2,designation2,responsibilities2,job_from_date2,job_to_date2,organization3,designation3,responsibilities3,job_from_date3,job_to_date3,organization4,designation4,responsibilities4,job_from_date4,job_to_date4,Experience as Principal/Director of a banking staff training college/centre/management institution,Experience as Faculty|Professor|Lecturer,PhD(in Banking or Finance),Name of Research Topic,University,Desirable_course_name,Desirable_college_name,Desirable_university,Desirable_from_date,Desirable_to_date,Desirable_grade_marks,Desirable_class,Publication of Books,Publication of articles (give latestnot more than ten),Area of Specializationorganization1,Refname_one,Refaddressline_one,Refemail_one,Refmobile_one,Refname_two,Refaddressline_two,Refemail_two,Refmobile_two,Declaration : I declare that all statements made in this application are true complete and correct to the best of my knowledge and belief. I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking and Finance my candidature/ appointment for the said post is liable to be cancelled at any stage and even after appointment my services are liable to be terminated without any notice,1.Earliest date of Joining if Selected,2.Why do you consider yourself suitable of the post of CEO of this Institute,3.Any other information that the candidate would like to add ,Place,Submit_date,Apply_date\n";
                
                $exam_file_flg = fwrite($fp, $data1);
                
                foreach ($can_exam_data as $exam) {
                    $job_arr_data = array();
                    $select       = 'organization,designation,responsibilities,job_from_date,job_to_date,experience_as_principal,
    experience_as_faculty';
                    $this->db->where('careers_id', $exam['careers_id']);
                    $job_arr_data = $this->Master_model->getRecords('careers_employment_hist', '', $select);
                    //echo "SQL>".$this->db->last_query();
                    $data         = '';
                    
                    $position = $name = $father_husband_name = $dateofbirth = $gender = $email = $marital_status = $mobile = $alternate_mobile = $pan_no = $aadhar_card_no = $languages_known = $languages_option = $languages_known1 = $languages_option1 = $languages_known2 = $languages_option2 = $address = $contact_number = $address_pr = $contact_number_pr = $ess_course_name = $ess_college_name = $ess_university = $ess_from_date = $ess_to_date = $ess_grade_marks = $ess_class = $ess_subject = $year_of_passing = $membership_number = $organization1 = $designation1 = $responsibilities1 = $job_from_date1 = $job_to_date1 = $organization2 = $designation2 = $responsibilities2 = $job_from_date2 = $job_to_date2 = $organization3 = $designation3 = $responsibilities3 = $job_from_date3 = $job_to_date3 = $organization4 = $designation4 = $responsibilities4 = $job_from_date4 = $job_to_date4 = $experience_as_principal1 = $experience_as_faculty1 = $ph_d = $phd_course = $phd_university = $course_name = $college_name = $university = $from_date = $to_date = $grade_marks = $class = $publication_of_books = $publication_of_articles = $area_of_specialization = $refname_one = $refaddressline_one = $refemail_one = $refmobile_one = $refname_two = $refaddressline_two = $refemail_two = $refmobile_two = $declaration2 = $earliest_date_of_joining = $suitable_of_the_post_of_CEO = $comment = $place = $submit_date = $apply_date = "";
                    
                    $position                    = $exam['position'];
                    $sel_namesub                 = $exam['sel_namesub'];
                    $firstname                   = $exam['firstname'];
                    $middlename                  = $exam['middlename'];
                    $lastname                    = $exam['lastname'];
                    $father_husband_name         = $exam['father_husband_name'];
                    $dateofbirth                 = $exam['dateofbirth'];
                    $gender                      = $exam['gender'];
                    $email                       = $exam['email'];
                    $marital_status              = $exam['marital_status'];
                    $mobile                      = $exam['mobile'];
                    $alternate_mobile            = $exam['alternate_mobile'];
                    $pan_no                      = $exam['pan_no'];
                    $aadhar_card_no              = $exam['aadhar_card_no'];
                    $languages_known             = str_replace(',', ' | ', $exam['languages_known']);
                    $languages_option            = str_replace(',', ' | ', $exam['languages_option']);
                    $languages_known1            = str_replace(',', ' | ', $exam['languages_known1']);
                    $languages_option1           = str_replace(',', ' | ', $exam['languages_option1']);
                    $languages_known2            = str_replace(',', ' | ', $exam['languages_known2']);
                    $languages_option2           = str_replace(',', ' | ', $exam['languages_option2']);
                    $addressline1                = $exam['addressline1'];
                    $addressline2                = $exam['addressline2'];
                    $addressline3                = $exam['addressline3'];
                    $addressline4                = $exam['addressline4'];
                    $district                    = $exam['district'];
                    $city                        = $exam['city'];
                    $state                       = $exam['state'];
                    $pincode                     = $exam['pincode'];
                    $contact_number              = $exam['contact_number'];
                    $addressline1_pr             = $exam['addressline1_pr'];
                    $addressline2_pr             = $exam['addressline2_pr'];
                    $addressline3_pr             = $exam['addressline3_pr'];
                    $addressline4_pr             = $exam['addressline4_pr'];
                    $district_pr                 = $exam['district_pr'];
                    $city_pr                     = $exam['city_pr'];
                    $state_pr                    = $exam['state_pr'];
                    $pincode_pr                  = $exam['pincode_pr'];
                    $contact_number_pr           = $exam['contact_number_pr'];
                    $ess_course_name             = $exam['ess_course_name'];
                    $ess_college_name            = str_replace(',', ' | ', $exam['ess_college_name']);
                    $ess_university              = str_replace(',', ' | ', $exam['ess_university']);
                    $ess_from_date               = $exam['ess_from_date'];
                    $ess_to_date                 = $exam['ess_to_date'];
                    $ess_grade_marks             = $exam['ess_grade_marks'];
                    $ess_class                   = $exam['ess_class'];
                    $ess_subject                 = $exam['ess_subject'];
                    $year_of_passing             = $exam['year_of_passing'];
                    $membership_number           = $exam['membership_number'];
                    $ph_d                        = $exam['ph_d'];
                    $phd_course                  = $exam['phd_course'];
                    $phd_university              = $exam['phd_university'];
                    $course_name                 = $exam['course_name'];
                    $college_name                = trim(str_replace(',', ' | ', $exam['college_name']));
                    $university                  = $exam['university'];
                    $from_date                   = $exam['from_date'];
                    $to_date                     = $exam['to_date'];
                    $grade_marks                 = $exam['grade_marks'];
                    $class                       = $exam['class'];
                    $publication_of_books        = trim(str_replace(',', ' | ', $exam['publication_of_books']));
                    $publication_of_articles     = trim(str_replace(',', ' | ', $exam['publication_of_articles']));
                    $area_of_specialization      = trim(str_replace(',', ' | ', $exam['area_of_specialization']));
                    $refname_one                 = $exam['refname_one'];
                    $refaddressline_one          = trim(str_replace(',', ' | ', $exam['refaddressline_one']));
                    $refemail_one                = $exam['refemail_one'];
                    $refmobile_one               = $exam['refmobile_one'];
                    $refname_two                 = $exam['refname_two'];
                    $refaddressline_two          = trim(str_replace(',', ' | ', $exam['refaddressline_two']));
                    $refemail_two                = $exam['refemail_two'];
                    $refmobile_two               = $exam['refmobile_two'];
                    $declaration2                = $exam['declaration2'];
                    $earliest_date_of_joining    = trim(str_replace(',', ' | ', $exam['earliest_date_of_joining']));
                    $suitable_of_the_post_of_CEO = trim(str_replace(',', ' | ', $exam['suitable_of_the_post_of_CEO']));
                    $comment                     = trim(str_replace(',', ' | ', $exam['comment']));
                    $place                       = $exam['place'];
                    $submit_date                 = $exam['submit_date'];
                    $apply_date                  = $exam['apply_date'];
                    
                    /* Name */
                    $name    = $sel_namesub . ' ' . $firstname . ' ' . $middlename . ' ' . $lastname;
                    $name    = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $name);
                    /* Address */
                    $address = $addressline1 . ' ' . $addressline2 . ' ' . $addressline3 . ' ' . $addressline4 . ' ' . $district . ' ' . $city . ' ' . $state . ' ' . $pincode;
                    $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
                    
                    /* Address Prnt */
                    $address_pr = $addressline1_pr . ' ' . $addressline2_pr . ' ' . $addressline3_pr . ' ' . $addressline4_pr . ' ' . $district_pr . ' ' . $city_pr . ' ' . $state_pr . ' ' . $pincode_pr;
                    $address_pr = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address_pr);
                    
                    /* Gender */
                    $gender = $gender;
                    if ($gender == 'male' || $gender == 'Male') {
                        $gender = 'Male';
                    } elseif ($gender == 'female' || $gender == 'Female') {
                        $gender = 'Female';
                    }
                    
                    foreach ($job_arr_data as $jk => $job_data) {
                        if ($jk == 0) {
                            $organization1            = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation1             = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities1        = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date1           = $job_data['job_from_date'];
                            $job_to_date1             = $job_data['job_to_date'];
                            $experience_as_principal1 = trim(str_replace(',', ' | ', $job_data['experience_as_principal']));
                            $experience_as_faculty1   = trim(str_replace(',', ' | ', $job_data['experience_as_faculty']));
                        } elseif ($jk == 1) {
                            $organization2     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation2      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities2 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date2    = $job_data['job_from_date'];
                            $job_to_date2      = $job_data['job_to_date'];
                        } elseif ($jk == 2) {
                            $organization3     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation3      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities3 = trim(str_replace(',', ' | ', $job_data['responsibilities']));
                            $job_from_date3    = $job_data['job_from_date'];
                            $job_to_date3      = $job_data['job_to_date'];
                        } elseif ($jk == 3) {
                            $organization4     = trim(str_replace(',', ' | ', $job_data['organization']));
                            $designation4      = trim(str_replace(',', ' | ', $job_data['designation']));
                            $responsibilities4 = str_replace(',', ' | ', $job_data['responsibilities']);
                            $job_from_date4    = $job_data['job_from_date'];
                            $job_to_date4      = $job_data['job_to_date'];
                        }
                    }
                    
                    $data .= '' . $position . ',' . $name . ',' . $father_husband_name . ',' . $dateofbirth . ',' . $gender . ',' . $email . ',' . $marital_status . ',' . $mobile . ',' . $alternate_mobile . ',' . $pan_no . ',' . $aadhar_card_no . ',' . $languages_known . ',' . $languages_option . ',' . $languages_known1 . ',' . $languages_option1 . ',' . $languages_known2 . ',' . $languages_option2 . ',' . $address . ',' . $contact_number . ',' . $address_pr . ',' . $contact_number_pr . ',' . $ess_course_name . ',' . $ess_college_name . ',' . $ess_university . ',' . $ess_from_date . ',' . $ess_to_date . ',' . $ess_grade_marks . ',' . $ess_class . ',' . $ess_subject . ',' . $year_of_passing . ',' . $membership_number . ',' . $organization1 . ',' . $designation1 . ',' . $responsibilities1 . ',' . $job_from_date1 . ',' . $job_to_date1 . ',' . $organization2 . ',' . $designation2 . ',' . $responsibilities2 . ',' . $job_from_date2 . ',' . $job_to_date2 . ',' . $organization3 . ',' . $designation3 . ',' . $responsibilities3 . ',' . $job_from_date3 . ',' . $job_to_date3 . ',' . $organization4 . ',' . $designation4 . ',' . $responsibilities4 . ',' . $job_from_date4 . ',' . $job_to_date4 . ',' . $experience_as_principal1 . ',' . $experience_as_faculty1 . ',' . $ph_d . ',' . $phd_course . ',' . $phd_university . ',' . $course_name . ',' . $college_name . ',' . $university . ',' . $from_date . ',' . $to_date . ',' . $grade_marks . ',' . $class . ',' . $publication_of_books . ',' . $publication_of_articles . ',' . $area_of_specialization . ',' . $refname_one . ',' . $refaddressline_one . ',' . $refemail_one . ',' . $refmobile_one . ',' . $refname_two . ',' . $refaddressline_two . ',' . $refemail_two . ',' . $refmobile_two . ',' . $declaration2 . ',' . $earliest_date_of_joining . ',' . $suitable_of_the_post_of_CEO . ',' . $comment . ',' . $place . ',' . $submit_date . ',' . $apply_date . ',' . "\n";
                    
                    $exam_file_flg = fwrite($fp, $data);
                    if ($exam_file_flg)
                        $success['cand_exam'] = "Careers CSV File Generated Successfully.";
                    else
                        $error['cand_exam'] = "Error While Generating Careers CSV File.";
                    $i++;
                    $exam_cnt++;
                }
                //exit;
                fwrite($fp1, "\n Total Applications - " . $exam_cnt . "\n");
                
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => $exam_cnt,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
            } else {
                $yesterday = date('Y-m-d', strtotime("- 1 day"));
                fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
                // File Rename Functinality
                $oldPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . $current_date . ".csv";
                $newPath = $cron_file_dir . $current_date . "/Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                rename($oldPath, $newPath);
                $OldName     = "Careers_" . $csv_name . "_" . $current_date . ".csv";
                $NewName     = "Careers_" . $csv_name . "_" . date('dmYhi') . "_0.csv";
                $insert_info = array(
                    'CurrentDate' => $current_date,
                    'old_file_name' => $OldName,
                    'new_file_name' => $NewName,
                    'record_count' => 0,
                    'createdon' => date('Y-m-d H:i:s')
                );
                $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
                $success[] = "No data found for the date";
            }
            fclose($fp);
            $end_time = date("Y-m-d H:i:s");
            $result   = array(
                "success" => $success,
                "error" => $error,
                "Start Time" => $start_time,
                "End Time" => $end_time
                
            );
            $desc     = json_encode($result);
            $this->log_model->cronlog("Careers CSV Cron Execution End", $desc);
            fwrite($fp1, "\n" . "**************** Careers CSV Cron Execution End " . $end_time . " *****************" . "\n");
            fclose($fp1);
        }
    }
    
    
    
    
    
    
    /********** CODE ADDED BY SAGAR ON 28-06-2020 FOR MEMBER IMAGES ****************/
    public function get_member_images($image_path = '', $reg_no = '', $regnumber = '', $scannedphoto = '', $idproofphoto = '', $scannedsignaturephoto = '')
    {
        $db_img_path      = $image_path; //Get old image path from database
        $scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';
        
        if ($scannedphoto != "" && file_exists(FCPATH . "uploads/photograph/" . $scannedphoto)) //Check photo in regular folder
            {
            $scannedphoto_res = base_url() . "uploads/photograph/" . $scannedphoto;
        } else if ($db_img_path != "") //Check photo in old image path
            {
            if ($reg_no != "" && file_exists(FCPATH . "uploads" . $db_img_path . "photo/p_" . $reg_no . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads" . $db_img_path . "photo/p_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads" . $db_img_path . "photo/p_" . $regnumber . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads" . $db_img_path . "photo/p_" . $regnumber . ".jpg";
            }
        } else //Check photo in kyc folder          
            {
            if ($reg_no != "" && file_exists(FCPATH . "uploads/photograph/k_p_" . $reg_no . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads/photograph/k_p_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads/photograph/k_p_" . $regnumber . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads/photograph/k_p_" . $regnumber . ".jpg";
            }
        }
        
        if ($idproofphoto != "" && file_exists(FCPATH . "uploads/idproof/" . $idproofphoto)) //Check id proof in regular folder
            {
            $idproofphoto_res = base_url() . "uploads/idproof/" . $idproofphoto;
        } else if ($db_img_path != "") //Check id proof in old image path
            {
            if ($reg_no != "" && file_exists(FCPATH . "uploads" . $db_img_path . "idproof/pr_" . $reg_no . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads" . $db_img_path . "idproof/pr_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads" . $db_img_path . "idproof/pr_" . $regnumber . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads" . $db_img_path . "idproof/pr_" . $regnumber . ".jpg";
            }
        } else //Check photo in kyc folder
            {
            if ($reg_no != "" && file_exists(FCPATH . "uploads/idproof/k_pr_" . $reg_no . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads/idproof/k_pr_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads/idproof/k_pr_" . $regnumber . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads/idproof/k_pr_" . $regnumber . ".jpg";
            }
        }
        
        if ($scannedsignaturephoto != "" && file_exists(FCPATH . "uploads/scansignature/" . $scannedsignaturephoto)) //Check signature in regular folder
            {
            $scannedsignaturephoto_res = base_url() . "uploads/scansignature/" . $scannedsignaturephoto;
        } else if ($db_img_path != "") //Check signature in old image path
            {
            if ($reg_no != "" && file_exists(FCPATH . "uploads" . $db_img_path . "signature/s_" . $reg_no . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads" . $db_img_path . "signature/s_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads" . $db_img_path . "signature/s_" . $regnumber . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads" . $db_img_path . "signature/s_" . $regnumber . ".jpg";
            }
        } else //Check signature in kyc folder
            {
            if ($reg_no != "" && file_exists(FCPATH . "uploads/scansignature/k_s_" . $reg_no . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads/scansignature/k_s_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads/scansignature/k_s_" . $regnumber . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads/scansignature/k_s_" . $regnumber . ".jpg";
            }
        }
        
        $data['scannedphoto']          = $scannedphoto_res;
        $data['idproofphoto']          = $idproofphoto_res;
        $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
        return $data;
    }
    
    
}