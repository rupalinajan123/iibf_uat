<?php
/*
 	* Controller Name	:	Supervision Cron File Generation
 	* Created By		:	Priyanka
 	* Created Date		:	26-05-2024
*/

defined('BASEPATH') or exit('No direct script access allowed');
class Supervisioncron extends CI_Controller
{
  //exit;
  public function __construct()
  {
    parent::__construct();

    $this->load->model('UserModel');
    $this->load->model('Master_model');
    $this->load->model('supervision_model');
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->model('log_model');

    /* File Path */
    define('MEM_FILE_PATH', '/fromweb/images/newmem/');
    define('CSC_MEM_FILE_PATH', '/fromweb/images/newmem/');
    define('DRA_FILE_PATH', '/fromweb/images/dra/');
    define('MEM_FILE_EDIT_PATH', '/fromweb/images/edit/');
    define('MEM_FILE_RENEWAL_PATH', '/fromweb/images/renewal/');
    define('DIGITAL_EL_MEM_FILE_PATH', '/fromweb/images/newmem/');
    define('SCE_FILE_PATH', '/fromweb/images/scribe/');

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
  }

  public function ci_sessoin_delete()
  {
    $yesterday = date('Y-m-d', strtotime("- 2 day"));


  }
 


  /* Member Registration Cron */
  public function member()
  {
    ini_set("memory_limit", "-1");

    $dir_flg = 0;
    $parent_dir_flg = 0;
    $file_w_flg = 0;   
    $idproof_zip_flg = 0;
    $declaration_zip_flg = 0;
    $success = array();
    $error = array();

    $start_time = date("Y-m-d H:i:s");
    $current_date = date("Ymd");
    //$current_date = '20200105';
    $cron_file_dir = "./uploads/cronfiles_pg/";

    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    $this->log_model->cronlog("New Supervision Member Details Cron Execution Start", $desc);

    if (!file_exists($cron_file_dir . $current_date))
    {
      $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
    }

    if (file_exists($cron_file_dir . $current_date))
    {
      $cron_file_path = $cron_file_dir . $current_date;  // Path with CURRENT DATE DIRECTORY

      $file = "iibf_new_supervision_mem_details_" . $current_date . ".txt";
      $fp = fopen($cron_file_path . '/' . $file, 'w');

      $file1 = "logs_" . $current_date . ".txt";
      $fp1 = fopen($cron_file_path . '/' . $file1, 'a');
      fwrite($fp1, "\n********** New supervision Member Details Cron Start - " . $start_time . " ********** \n");

      $result_merge_arr = array();

      $yesterday = date('Y-m-d', strtotime("- 1 day"));
      $this->db->join('pdc_zone_master pdc', 'pdc.pdc_zone_code = a.pdc_zone');
      $this->db->join('supervision_role_fee role', 'role.role_id = a.role_id');
      $new_mem_reg = $this->Master_model->getRecords('supervision_candidates a', array( ' DATE(activation_date)' => $yesterday,'is_active' => '1', 'a.is_deleted' => 0));//
      //echo $this->db->last_query();exit;
           
       if(count($new_mem_reg)) { $result_merge_arr = array_merge($result_merge_arr,$new_mem_reg); }

     
      if (count($result_merge_arr))
      {
        $dirname = "supervision_regd_image_" . $current_date;
        $directory = $cron_file_path . '/' . $dirname;
        if (file_exists($directory))
        {
          array_map('unlink', glob($directory . "/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else
        {
          $dir_flg = mkdir($directory, 0700);
        }

        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory . '.zip', ZipArchive::CREATE);

        $i = 1;
        $mem_cnt = 0;
      
        $idproof_cnt = $bandcard_cnt=0;$data='';

        foreach ($result_merge_arr as $reg_data)
        {
          

          $data .= '' . $reg_data['candidate_code'] . '|' . $reg_data['candidate_name'] . '|' . $reg_data['email'] . '|' . $reg_data['mobile'] . '|' . $reg_data['bank'] . '|' . $reg_data['branch'] .'|' . $reg_data['designation'] . '|' . $reg_data['bank_id_card'] . '|' . '|' . $reg_data['pdc_zone'] . '|' . $reg_data['center_name'] . '|' . $reg_data['center_code'] . '|' .$reg_data['role_name']  . "\n";

          if ($dir_flg)
          {
            

            /* Benchmark Code End */

            // For bandcard images
            //if ($bandcard)
            {
              
              {
              $image = "./uploads/supervision/" . $reg_data['bank_id_card'];
              }
              $max_width = "200";
              $max_height = "200";

              $imgdata = $this->resize_image_max($image, $max_width, $max_height);
              imagejpeg($imgdata, $directory . "/" .$reg_data['bank_id_card']);
              $bandcard_to_add = $directory . "/" . $reg_data['bank_id_card'];
              $new_bandcard = substr($bandcard_to_add, strrpos($bandcard_to_add, '/') + 1);
              $bandcard_zip_flg = $zip->addFile($bandcard_to_add, $new_bandcard);
              if (!$bandcard_zip_flg)
              {
                fwrite($fp1, "**ERROR** - bank id card not added to zip  - " . $reg_data['bank_id_card'] . " (" . $reg_data['candidate_code'] . ")\n");
              }
              else
                $bandcard_cnt++;
            }

            if ($bandcard_zip_flg)
            {
              $success['zip'] = "Supervision New Member Images Zip Generated Successfully";
            }
            else
            {
              $error['zip'] = "Error While Generating Supervision New Member Images Zip";
            }
          }

          $i++;
          $mem_cnt++;

          //fwrite($fp1, "\n");

          $file_w_flg = fwrite($fp, $data);
          if ($file_w_flg)
          {
            $success['file'] = "Supervision New Member Details File Generated Successfully. ";
          }
          else
          {
            $error['file'] = "Error While Generating Supervision New Member Details File.";
          }
        }

        fwrite($fp1, "\n" . "Total Supervision New Members Added = " . $mem_cnt . "\n");
        fwrite($fp1, "\n" . "Total Supervision bank id card Added = " . $bandcard_cnt . "\n");
        


        $zip->close();
      }
      else
      {
        $success[] = "Supervision -No data found for the date";
      }
      fclose($fp);
      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Supervision New Member Details Cron End", $desc);

      fwrite($fp1, "\n" . "********** Supervision New Member Details Cron End " . $end_time . " ***********" . "\n");
      fclose($fp1);
    }
  }

  function resize_image_max($image, $max_width, $max_height)
  {
    ini_set("memory_limit", "256M");
    ini_set("gd.jpeg_ignore_warning", 1);

    $org_img = $image;
    $image = @ImageCreateFromJpeg($image);
    if (!$image)
    {
      $image = imagecreatefromstring(file_get_contents($org_img));
    }

    $w = imagesx($image); //current width
    $h = imagesy($image); //current height
    if ((!$w) || (!$h))
    {
      $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.';
      return false;
    }

    if (($w <= $max_width) && ($h <= $max_height))
    {
      return $image;
    } //no resizing needed

    //try max width first...
    $ratio = $max_width / $w;
    $new_w = $max_width;
    $new_h = $h * $ratio;

    //if that didn't work
    if ($new_h > $max_height)
    {
      $ratio = $max_height / $h;
      $new_h = $max_height;
      $new_w = $w * $ratio;
    }

    $new_image = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
    return $new_image;
  }
  // Supervision Registrations Cron */
  public function claimreport()
  {
    ini_set("memory_limit", "-1");

    $dir_flg = 0;
    $parent_dir_flg = 0;
    $supervision_flg = 0;
    $success = array();
    $error = array();

    $start_time = date("Y-m-d H:i:s");
    $current_date = date("Ymd");
    $cron_file_dir = "./uploads/cronfiles_pg/";

    // Cron start Logs
    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    $this->log_model->cronlog("Supervision Details Cron Execution Start", $desc);

    if (!file_exists($cron_file_dir . $current_date))
    {
      $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
    }

    if (file_exists($cron_file_dir . $current_date))
    {
      $cron_file_path = $cron_file_dir . $current_date;  // Path with CURRENT DATE DIRECTORY

      $file = "supervision_claims_" . $current_date . ".txt";
      $fp = fopen($cron_file_path . '/' . $file, 'w');

      $file1 = "logs_" . $current_date . ".txt";
      $fp1 = fopen($cron_file_path . '/' . $file1, 'a');
      fwrite($fp1, "\n********** Supervision Details Cron Start - " . $start_time . " ******************** \n");

      $yesterday = date('Y-m-d', strtotime("- 1 day"));
      //$yesterday = '2017-11-21';

      // get Supervision details for given date
      $this->db->join('supervision_session_forms sf', 'sf.id = c.session_form_id');
      $this->db->join('supervision_exam_activation se', 'se.exam_code = sf.exam_code');
      $this->db->join('supervision_candidates sc', 'sc.id = c.candidate_id');
      $br_supervision_data = $this->Master_model->getRecords('supervision_claims c', array( ' DATE(paid_date)' => $yesterday,'is_paid' => 1, 'sf.is_deleted' => 0, 'sc.is_active' => 1));//
     // echo $this->db->last_query();exit;
      if (count($br_supervision_data))
      {
        $dirname = "supervision_pan_cheque_docs_" . $current_date;
        $directory = $cron_file_path . '/' . $dirname;
        if (file_exists($directory))
        {
          array_map('unlink', glob($directory . "/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else
        {
          $dir_flg = mkdir($directory, 0700);
        }

        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory . '.zip', ZipArchive::CREATE);

        $data = '';
        $i = 1;
        $mem_cnt = 0;
        foreach ($br_supervision_data as $supervision_reg)
        {
         
              $data .= '' . $supervision_reg['candidate_code'] . '|' . $supervision_reg['exam_code'] . '|' .  $supervision_reg['exam_period'] . '|' . $supervision_reg['venue_code'] . '|' . $supervision_reg['venue_name'] . '|' . $supervision_reg['venueadd1'] . '|' . $supervision_reg['venueadd2'] . '|' . $supervision_reg['venueadd3'] . '|' . $supervision_reg['venueadd4'] . '|' . $supervision_reg['venueadd5'] . '|' . $supervision_reg['venpin'] . '|' . $supervision_reg['exam_date'] . '|' . $supervision_reg['exam_time'] . '|' . $supervision_reg['no_of_session'] . '|' . $supervision_reg['no_of_pc'] . '|' . $supervision_reg['suitable_venue_loc'] . '|' . $supervision_reg['suitable_venue_loc_reason'] . '|' . $supervision_reg['venue_open_bef_exam'] . '|' . $supervision_reg['venue_open_bef_exam_reason'] . '|' . $supervision_reg['venue_reserved'] . '|' . $supervision_reg['venue_reserved_reason'] . '|'. $supervision_reg['venue_power_problem'] . '|' . $supervision_reg['venue_power_problem_sol'] . '|' . $supervision_reg['no_of_supervisors'] . '|' .  $supervision_reg['registration_process'] . '|' . $supervision_reg['registration_process_reason'] . '|' . $supervision_reg['frisking'] . '|' . $supervision_reg['frisking_reason'] . '|' . $supervision_reg['frisking_lady'] . '|' . $supervision_reg['frisking_lady_reason'] . '|' . $supervision_reg['mobile_allowed'] .'|' . $supervision_reg['mobile_allowed_reason'] .'|' . $supervision_reg['admit_letter_checked'] .'|' . $supervision_reg['admit_letter_checked_reason'] .'|' . $supervision_reg['exam_without_admit_letter'] .'|' . $supervision_reg['exam_without_admit_letter_detils'] .'|' . $supervision_reg['seat_no_written'] .'|' . $supervision_reg['seat_no_written_reason'] .'|' . $supervision_reg['candidate_seated'] .'|' . $supervision_reg['candidate_seated_reason'] .'|' . $supervision_reg['scribe_arrange'] .'|' . $supervision_reg['scribe_arrange_reason'].'|' . $supervision_reg['announcement'].'|' . $supervision_reg['announcement_gap'].'|' . $supervision_reg['exam_started'].'|' . $supervision_reg['exam_started_reason'].'|' . $supervision_reg['candidate_appeared'].'|' . $supervision_reg['started_late'].'|' . $supervision_reg['started_late_reason'].'|' . $supervision_reg['unfair_candidates'].'|' . $supervision_reg['unfair_candidates_reason'].'|' . $supervision_reg['rough_sheet'].'|' . $supervision_reg['rough_sheet_reason'].'|' . $supervision_reg['action_for_unfair'].'|' . $supervision_reg['name_mob_exam_contro'].'|' . $supervision_reg['issue_reported'].'|' . $supervision_reg['observation'].'|' . $supervision_reg['filled_date'].'|' . $supervision_reg['pay_status'].'|' . $supervision_reg['uploaded_file'].'|' . $supervision_reg['session_wise_amount'].'|' . $supervision_reg['total_amount'].'|' . $supervision_reg['transaction_no'].'|' . $supervision_reg['beneficiary_name'].'|' . $supervision_reg['account_no'].'|' . $supervision_reg['bank_branch_name'].'|' . $supervision_reg['ifsc_code'].'|' . $supervision_reg['email'].'|' . $supervision_reg['mobile'].'|' . $supervision_reg['pan_card'].'|' . $supervision_reg['pan_card_doc'].'|' . $supervision_reg['uploaded_file'].'|' . $supervision_reg['canceled_cheque']. "\n";

              if ($dir_flg)
                {
                  
                  {
                    
                    $pan_card_doc = "./uploads/supervision/" . $supervision_reg['pan_card_doc'];
                   
                    
                    copy($pan_card_doc, $directory.'/'.$supervision_reg['pan_card_doc']);
                    
                    $file_to_add = $directory . "/" . $supervision_reg['pan_card_doc'];
                    $file_to_add1 = substr($file_to_add, strrpos($file_to_add, '/') + 1);
                    $pancard_zip_flg = $zip->addFile($file_to_add, $file_to_add1);

                   // $pancard_zip_flg = $zip->addFile(pathinfo ( $pan_card_doc, PATHINFO_BASENAME), $content);
                    if (!$pancard_zip_flg)
                    {
                      fwrite($fp1, "**ERROR** -pan  card not added to zip  - " . $supervision_reg['bank_id_card'] . " (" . $supervision_reg['candidate_code'] . ")\n");
                    }

                    if($supervision_reg['canceled_cheque']!='') {

                        $canceled_cheque = "./uploads/supervision/" . $supervision_reg['canceled_cheque'];

                        copy($canceled_cheque, $directory.'/'.$supervision_reg['canceled_cheque']);
                    
                        $file_to_add = $directory . "/" . $supervision_reg['canceled_cheque'];
                        $file_to_add1 = substr($file_to_add, strrpos($file_to_add, '/') + 1);
                        $cheque_zip_flg = $zip->addFile($file_to_add, $file_to_add1);
                        //$cheque_zip_flg = $zip->addFile(pathinfo ( $canceled_cheque, PATHINFO_BASENAME), $content);
                        if (!$cheque_zip_flg)
                        {
                          fwrite($fp1, "**ERROR** -pan  card not added to zip  - " . $supervision_reg['bank_id_card'] . " (" . $supervision_reg['candidate_code'] . ")\n");
                        }
                    }
                    
                   
                  }

                  if ($pancard_zip_flg)
                  {
                    $success['zip'] = "Supervision  Member pan card Zip Generated Successfully";
                  }
                  else
                  {
                    $error['zip'] = "Error While Generating Supervision New Member Images Zip";
                  }
                }

              $i++;
              $mem_cnt++;
        }
          
        $zip->close();

        $supervision_flg = fwrite($fp, $data);
        if ($supervision_flg)
          $success['supervision_reg'] = "Supervision Details File Generated Successfully. ";
        else
          $error['supervision_reg'] = "Error While Generating Supervision Details File.";

        fwrite($fp1, "\n" . "Total Supervision Applications = " . $mem_cnt . "\n");
      }
      else
      {
        $success[] = "No data found for the date";
      }
      fclose($fp);

      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Supervision Details Cron End", $desc);

      fwrite($fp1, "\n" . "********** Supervision Details Cron End " . $end_time . " ***********" . "\n");
      fclose($fp1);
    }
  }

}
