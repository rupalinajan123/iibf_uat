<?php

/********************************************************************************************************************
 ** Description: Common Helper for SUPERVISION Module
 ** Created BY: Priyanka Dhikale 20-may-24
 ********************************************************************************************************************/
defined('BASEPATH') || exit('No Direct Allowed Here');


function download_form_pdf_func($pdf_file) {
  
  $path = 'uploads/supervision/'; // change the path to fit your     websites document structure
  $fullPath = $path.basename($pdf_file);

  if (is_readable ($fullPath)) {
  $fsize = filesize($fullPath);
  $path_parts = pathinfo($fullPath);
  $ext = strtolower($path_parts["extension"]);
  switch ($ext) {
      case "pdf":
      header("Content-type: application/pdf"); // add here more headers for diff.     extensions
      header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");     // use 'attachment' to force a download
      break;
      default;
      header("Content-type: application/octet-stream");
      header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
  }
  header("Content-length: $fsize");
  header("Cache-control: private"); //use this to open files directly
  readfile($fullPath);
  
  exit;
  //$this->session->set_flashdata('success','Session form details successfully');   
  //redirect(site_url('supervision/candidate/dashboard_candidate/session_forms'));  
  } else {
          die("Invalid request");
  } 
}
function check_email($email)
/******** START : CHECK CORRECT EMAIL FORMAT ********/
{
  $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
  if (preg_match($regex, $email))
  {
    $vallid = 1;
  }
  else
  {
    $vallid = 0;
  }
  return $vallid;
}
/******** END : CHECK CORRECT EMAIL FORMAT ********/

function url_encode($id)
/******** START : CUSTOM URL ENCODE ID ********/
{
  /* $encode_id = trim(base64_encode($id*5),"=");
    $append_string = rand(0,9).("#*".$encode_id."*#").rand(0,9);
    $encode_id = base64_encode($append_string);
    return $final_encode_id = trim($encode_id,"=");  */

  return base64_encode($id);
}
/******** END : CUSTOM URL ENCODE ID ********/

function url_decode($id)
/******** START : CUSTOM URL DECODE ID ********/
{
 

  return base64_decode($id);
}
/******** END : CUSTOM URL DECODE ID ********/

function slug_url($text = 0)
/******** START : CREATE URL SLUG ********/
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text))
  {
    return 'na';
  }

  return character_limiter(strip_tags($text), 100);
}
/******** END : CREATE URL SLUG ********/

function get_tiny_url($url)
/******** START : CREATE TINY URL ********/
{
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
/******** END : CREATE TINY URL ********/

function custom_safe_string($str = "")
/******** START : CONVERT STRING INTO SAFE STRING ********/
{
  $str = str_replace('"', "&quot;", $str);
  $str = str_replace("'", "&apos;", $str);
  return $str;
}
/******** END : CONVERT STRING INTO SAFE STRING ********/

function custom_html_string($str = "")
/******** START : CONVERT SAFE STRING INTO HTML STRING ********/
{
  $str = str_replace('&quot;', "''", $str);
  $str = str_replace("&apos;", "'", $str);
  return $str;
}
/******** END : CONVERT SAFE STRING INTO HTML STRING ********/

function secToHR($secs)
/******** START : CONVERT SECONDS TO HOUR ********/
{
  $r = '';
  if ($secs >= 86400)
  {
    $days = floor($secs / 86400);
    $secs = $secs % 86400;
    $r = $days . 'd ';
  }
  $hours = floor($secs / 3600);
  $secs = $secs % 3600;
  $r .= str_pad($hours, 2, '0', STR_PAD_LEFT) . 'h ';
  $minutes = floor($secs / 60);
  $secs = $secs % 60;
  $r .= str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm ';
  $r .= str_pad($secs, 2, '0', STR_PAD_LEFT) . "s";
  return $r;
}
/******** END : CONVERT SECONDS TO HOUR ********/

function number_format_upto1($val)
/******** START : CONVERT NUMBER FORMAT ********/
{
  return number_format((float)$val, 1, '.', '');
}
/******** END : CONVERT NUMBER FORMAT ********/

function number_format_upto2($val)
/******** START : CONVERT NUMBER FORMAT ********/
{
  return number_format((float)$val, 2, '.', '');
}
/******** END : CONVERT NUMBER FORMAT ********/

function auto_version($file)
/******** START : AUTO VERSIONING ********/
{
  $curl = curl_init($file);

  //don't fetch the actual page, you only want headers
  curl_setopt($curl, CURLOPT_NOBODY, true);

  //stop it from outputting stuff to stdout
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  // attempt to retrieve the modification date
  curl_setopt($curl, CURLOPT_FILETIME, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($curl);

  //if ($result === false) { die (curl_error($curl)); }

  $timestamp = curl_getinfo($curl, CURLINFO_FILETIME);
  if ($timestamp != -1)
  {
    return $file . '?ver=' . $timestamp;
  }
  else return $file;

  return $file;
}
/******** END : AUTO VERSIONING ********/

function utf8_to_unicode($str)
/******** START : UTF TO UNICODE CONVERSION : FOR SENDING MARATHI LANGUAGE SMS ********/
{
  $unicode = array();
  $values = array();
  $lookingFor = 1;
  for ($i = 0; $i < strlen($str); $i++)
  {
    $thisValue = ord($str[$i]);
    if ($thisValue < 128)
    {
      $number = dechex($thisValue);
      $unicode[] = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
    }
    else
    {
      if (count($values) == 0)
        $lookingFor = ($thisValue < 224) ? 2 : 3;
      $values[] = $thisValue;
      if (count($values) == $lookingFor)
      {
        $number = ($lookingFor == 3) ?
          (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64) : (($values[0] % 32) * 64) + ($values[1] % 64
          );
        $number = dechex($number);
        $unicode[] =
          (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
        $values = array();
        $lookingFor = 1;
      } // if
    } // if
  }
  return implode("", $unicode);
}
/******** END : UTF TO UNICODE CONVERSION : FOR SENDING MARATHI LANGUAGE SMS ********/

function search_str_validate($search_str = '')
/******** START : CONVERT STRING INTO SERACH STRING FORMAT ********/
{
  $qry_str = trim($search_str);
  $qry_str = str_replace("%", "", $qry_str);
  $qry_str = str_replace("/", "", $qry_str);
  $qry_str = str_replace("/\/", "", $qry_str);
  $qry_str = str_replace("'", "", $qry_str);
  $qry_str = str_replace('"', "", $qry_str);
  return $qry_str;
}
/******** END : CONVERT STRING INTO SERACH STRING FORMAT ********/

/******** START : CREATE DIRECTORY ********/
//"./uploads/FOLDER1/FOLDER2/FOLDER3"
function create_directories($directory_path = '')
{
  $directory_path = str_replace("./", "", $directory_path);
  $directory_path_arr = explode("/", $directory_path);
  $chk_dir_path = './';
  if (count($directory_path_arr) > 0)
  {
    $i = 0;
    foreach ($directory_path_arr as $res)
    {
      if ($i > 0)
      {
        $chk_dir_path .= "/";
      }
      $chk_dir_path .= $res;

      if (!is_dir($chk_dir_path))
      {
        $dir = mkdir($chk_dir_path, 0755);
        $myfile = fopen($chk_dir_path . "/index.php", "w") or die("Unable to open file!");
        $txt = "";
        fwrite($myfile, $txt);
        fclose($myfile);
      }
      $i++;
    }
  }
  return $chk_dir_path;
}
/******** END : CREATE DIRECTORY ********/

function generate_captcha($session_captcha_name = '', $length = '4')
/******** START : GENERATE CAPTCHA IMAGE ********/
{
 

  $cap_word = generate_random_string(6);
  $_SESSION[$session_captcha_name] = $cap_word;

  $wd_ht_arr = array('20%', '25%', '30%', '35%', '40%', '45%');
  $transform_arr = array('1deg', '2deg', '3deg', '4deg', '5deg', '-1deg', '-2deg', '-3deg', '-4deg', '-5deg');
  $bg_position_arr = array('bottom', 'centre', 'top');
  $random_keys = array_rand($wd_ht_arr, 2);
  $random_transform_keys = array_rand($transform_arr, 2);
  $random_bg_position_keys = array_rand($bg_position_arr, 2);

  return '	<style>
								.CaptchaBgText { position: relative; width: 130px; height: 35px; background-image: url(' . base_url("assets/supervision/images/captcha_bg.png") . '); background-size: ' . $wd_ht_arr[$random_keys[0]] . '; border: 1px solid #A2A2A2; background-color: #ccc; background-position:' . $bg_position_arr[$random_bg_position_keys[0]] . '}
								
								.CaptchaBgText::after { content: "' . $cap_word . '"; position: absolute; color: #000; top: 0; left: 0; width: 100%; height: 35px; text-align: right; font-size: 15px; font-weight: bolder; letter-spacing: 10px; overflow: hidden; line-height: 30px; transform: rotate(' . $transform_arr[$random_transform_keys[0]] . '); /* All browsers support */ -moz-transform: rotate(' . $transform_arr[$random_transform_keys[0]] . '); -webkit-transform: rotate(' . $transform_arr[$random_transform_keys[0]] . '); -o-transform: rotate(' . $transform_arr[$random_transform_keys[0]] . '); -ms-transform: rotate(' . $transform_arr[$random_transform_keys[0]] . '); }
							</style>
							<div class="CaptchaBgText"></div>';
}
/******** END : GENERATE CAPTCHA IMAGE ********/

function generate_random_string($length = 0)
/******** START : GENERATE RANDOM STRING ********/
{
  $characters = 'ABCDEFGHJKLMNPQRTWXYZ123456789';
  $randomString = '';

  for ($i = 0; $i < $length; $i++)
  {
    $index = rand(0, strlen($characters) - 1);
    $randomString .= $characters[$index];
  }

  return $randomString;
}
/******** END : GENERATE RANDOM STRING ********/

function download_file($file_full_path = '', $file_name = '')
/******** START : DOWNLOAD FILE ********/
{
  $file_full_path = $file_full_path . '/' . $file_name;

  if ($file_full_path != '' && $file_name != '')
  {
    $mime = get_mime_by_extension($file_full_path);
    ob_end_clean();

    // Build the headers to push out the file properly.
    header('Pragma: public');     // required
    header('Expires: 0');         // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_full_path)) . ' GMT');
    header('Cache-Control: private', false);
    header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');  // Add the file name
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file_full_path)); // provide file size
    header('Connection: close');
    readfile($file_full_path); // push it out
    exit();
  }
}
/******** END : DOWNLOAD FILE ********/

function time_Ago($time, $actual_date)
/******** START : DISPLAY TIME AGO ********/
{
  // Calculate difference between current 
  // time and given timestamp in seconds 
  $diff     = time() - $time;

  // Time difference in seconds 
  $sec     = $diff;

  // Convert time difference in minutes 
  $min     = round($diff / 60);

  // Convert time difference in hours 
  $hrs     = round($diff / 3600);

  // Convert time difference in days 
  $days     = round($diff / 86400);

  // Convert time difference in weeks 
  $weeks     = round($diff / 604800);

  // Convert time difference in months 
  $mnths     = round($diff / 2600640);

  // Convert time difference in years 
  $yrs     = round($diff / 31207680);

  // Check for seconds 
  if ($sec <= 60)
  {
    return "$sec seconds ago";
  }

  // Check for minutes 
  else if ($min <= 60)
  {
    if ($min == 1)
    {
      return "one minute ago";
    }
    else
    {
      return "$min minutes ago";
    }
  }

  // Check for hours 
  else if ($hrs <= 24)
  {
    if ($hrs == 1)
    {
      return "one hour ago";
    }
    else
    {
      return "$hrs hours ago";
    }
  }
  else
  {
    return date("d M Y, h:ia", strtotime($actual_date));
  }

  
}
/******** END : DISPLAY TIME AGO ********/

function show_log_date($date)
/******** START : SHOW LOG DATE FORMAT ********/
{
  return date("d M Y, h:i A", strtotime($date));
}
/******** END : SHOW LOG DATE FORMAT ********/

function show_faculty_status($status = '')
/******** START : SHOW FACULTY STATUS ********/
{
  $badge_cls = '';
  if ($status == '0')
  {
    $badge_cls = 'badge-danger';
  }
  else if ($status == '1')
  {
    $badge_cls = 'badge-success';
  }
  else if ($status == '2')
  {
    $badge_cls = 'badge-warning';
  }
  else if ($status == '3')
  {
    $badge_cls = 'badge-primary';
  }
  return $badge_cls;
}
/******** END : SHOW FACULTY STATUS ********/

function show_batch_status($status = '')
/******** START : SHOW BATCH STATUS ********/
{
  $badge_cls = '';
  if ($status == '0')
  {
    $badge_cls = 'badge-default';
  }
  else if ($status == '1')
  {
    $badge_cls = 'badge-primary';
  }
  else if ($status == '2')
  {
    $badge_cls = 'badge-danger';
  }
  else if ($status == '3')
  {
    $badge_cls = 'badge-success';
  }
  else if ($status == '4')
  {
    $badge_cls = 'badge-warning';
  }
  else if ($status == '5')
  {
    $badge_cls = 'badge-danger';
  }
  else if ($status == '6')
  {
    $badge_cls = 'badge-primary';
  }
  else if ($status == '7')
  {
    $badge_cls = 'badge-danger';
  }
  else if ($status == '8')
  {
    $badge_cls = 'badge-warning';
  }
  return $badge_cls;
}
/******** END : SHOW BATCH STATUS ********/

function show_payment_status($status = '')
/******** START : SHOW PAYMENT STATUS ********/
{ //0=>Fail, 1=>Success, 2=>Pending, 3=>Applied, 4=>Cancelled
  $badge_cls = '';
  if ($status == '0')
  {
    $badge_cls = 'badge-danger';
  }
  else if ($status == '1')
  {
    $badge_cls = 'badge-success';
  }
  else if ($status == '2')
  {
    $badge_cls = 'badge-warning';
  }
  else if ($status == '3')
  {
    $badge_cls = 'badge-primary';
  }
  else if ($status == '4')
  {
    $badge_cls = 'badge-danger';
  }

  return $badge_cls;
}
/******** END : SHOW PAYMENT STATUS ********/

function _pa($arr, $exit_flag = 0)
{
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
  if ($exit_flag == '1')
  {
    exit();
  }
}

function _pq($exit_flag = 0)
{
  $ci = &get_instance();
  echo $ci->db->last_query();
  if ($exit_flag == '1')
  {
    exit();
  }
}

/******** START : THIS IS A CUSTOM FUNCTION. IT IS USE WHEN ADD CANDIDATE DATE IS OVER BUT STILL CLIENT ASK TO ENABLE THAT BUTTON ********/
function get_add_candidate_date($chk_batch_id_arr = array(), $batch_id = '0', $batch_start_date = '')
{
  if (count($chk_batch_id_arr) > 0)
  {
   

    if (array_key_exists($batch_id, $chk_batch_id_arr))
    {
      /*  echo '<br> key exist in array = '.$batch_id; */
      foreach ($chk_batch_id_arr as $res)
      {
        if ($res['batch_id'] == $batch_id)
        {
          /* echo '<br> batch id found = '.$batch_id; */
          if ($res['batch_extend_date'] != "" && $res['batch_extend_date'] != "0000-00-00" && $res['batch_extend_type'] == '1')
          {
            return $res['batch_extend_date'];
          }
          else
          {
            return $batch_start_date;
          }
        }
      }
    }
    else
    {
      return $batch_start_date;
    }
  }
  else
  {
    return $batch_start_date;
  }
}
/**** END : THIS IS A CUSTOM FUNCTION. IT IS USE WHEN ADD CANDIDATE DATE IS OVER BUT STILL CLIENT ASK TO ENABLE THAT BUTTON ********/

function convert_amount_into_words($amt = 0)
{
  $number = $amt;
  $no = round($number);
  $point = round($number - $no, 2) * 100;
  $hundred = null;
  $digits_1 = strlen($no);
  $i = 0;
  $str = array();
  $words = array(
    '0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety'
  );
  $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
  while ($i < $digits_1)
  {
    $divider = ($i == 2) ? 10 : 100;
    $number = floor($no % $divider);
    $no = floor($no / $divider);
    $i += ($divider == 10) ? 1 : 2;
    if ($number)
    {
      $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
      $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
      $str[] = ($number < 21) ? $words[$number] .
        " " . $digits[$counter] . $plural . " " . $hundred
        :
        $words[floor($number / 10) * 10]
        . " " . $words[$number % 10] . " "
        . $digits[$counter] . $plural . " " . $hundred;
    }
    else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " .
    $words[$point = $point % 10] : '';

  return $result;
}

/*START - Generate SUPERVISION Exam Invoice Number for NEFT Payment*/
function generate_supervision_exam_invoice_number($invoice_id = NULL)
{
  $last_id = '';
  $CI = &get_instance();
  //$CI->load->model('my_model');
  if ($invoice_id  != NULL)
  {
    $insert_info = array('invoice_id' => $invoice_id);
    $last_id = str_pad($CI->master_model->insertRecord('supervision_config_exam_invoice', $insert_info, true), 6, "0", STR_PAD_LEFT);;
  }
  return $last_id;
}
/*END - Generate SUPERVISION Exam Invoice Number for NEFT Payment*/

//START : THIS FUNCTION IS USED TO CONVERT LONG STRING INTO SMALL STRING WITH MULTIPLE LINE ARRAY
function wrapString($long_string)
{
  // Initialize an empty array to store the wrapped lines
  $wrapped_lines = array();

  // Wrap the long string to maximum 75 characters without breaking words
  $wrapped_string = wordwrap($long_string, 75, "\n", true);

  // Explode the wrapped string into an array of lines
  $wrapped_lines = explode("\n", $wrapped_string);

  // Return the array of wrapped lines
  return $wrapped_lines;
} //END : THIS FUNCTION IS USED TO CONVERT LONG STRING INTO SMALL STRING WITH MULTIPLE LINE ARRAY


function amount_in_word($number)
{
  $no = floor($number);
  $point = round($number - $no, 2) * 100;
  $hundred = null;
  $digits_1 = strlen($no);
  $i = 0;
  $str = array();
  $words = array(
    '0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety'
  );
  $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
  while ($i < $digits_1)
  {
    $divider = ($i == 2) ? 10 : 100;
    $number = floor($no % $divider);
    $no = floor($no / $divider);
    $i += ($divider == 10) ? 1 : 2;
    if ($number)
    {
      $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
      $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
      $str[] = ($number < 21) ? $words[$number] .
        " " . $digits[$counter] . $plural . " " . $hundred
        :
        $words[floor($number / 10) * 10]
        . " " . $words[$number % 10] . " "
        . $digits[$counter] . $plural . " " . $hundred;
    }
    else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    " And " . $words[$point / 10] . " " .
    $words[$point = $point % 10] : '';
  return str_replace("  ", " ", $result . "Rupees" . $points);
}

function supervision_img_p($member_id = NULL, $type = '')
{
  $CI = &get_instance();
  $mem_info = $CI->master_model->getRecords('supervision_batch_candidates', array('regnumber' => $member_id), 'regnumber,candidate_photo,candidate_sign');
  if ($mem_info)
  {
    $scannedphoto = '';
    if ($mem_info[0]['candidate_photo'] != '' && $type == 'photo')
    {
      $new_image_path  = 'uploads/supervision/photo/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_photo']))
      {
        $scannedphoto = base_url() . $new_image_path . $mem_info[0]['candidate_photo'];
      }
    }
    else if ($mem_info[0]['candidate_sign'] != '' && $type == 'sign')
    {
      $new_image_path  = 'uploads/supervision/sign/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_sign']))
      {
        $scannedphoto = base_url() . $new_image_path . $mem_info[0]['candidate_sign'];
      }
    }
    else
    {
      $new_image_path  = 'uploads/supervision/photo/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_photo']))
      {
        $scannedphoto = base_url() . $new_image_path . $mem_info[0]['candidate_photo'];
      }
    }
    return $scannedphoto;
  }
}


function breakLongWords($paragraph, $length = '20')
{
  // Split the paragraph into words
  $words = explode(' ', $paragraph);
  $final_para = '';

  // Iterate over each word
  foreach ($words as $word)
  {
    // Check if the length of the word exceeds 20 characters
    if (strlen($word) > $length)
    {
      // Break the word into 20-character segments
      $final_para .= ' ' . $word = wordwrap($word, $length, "<br>", true);
    }
    else
    {
      $final_para .= " " . $word;
    }
  }

  // Reconstruct the paragraph
  //$brokenParagraph = implode('', $words);

  return $final_para;
}
