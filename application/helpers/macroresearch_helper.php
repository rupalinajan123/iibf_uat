<?php

/********************************************************************************************************************
 ** Description: Common Helper for MACRORESEARCH Module
 ** Created BY: Priyanka Dhikale 20-may-24
 ********************************************************************************************************************/
defined('BASEPATH') || exit('No Direct Allowed Here');


function download_form_pdf_func($pdf_file) {
  
  $path = 'uploads/macroresearch/'; // change the path to fit your     websites document structure
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
  //redirect(site_url('macroresearch/candidate/dashboard_candidate/session_forms'));  
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
								.CaptchaBgText { position: relative; width: 130px; height: 35px; background-image: url(' . base_url("assets/macroresearch/images/captcha_bg.png") . '); background-size: ' . $wd_ht_arr[$random_keys[0]] . '; border: 1px solid #A2A2A2; background-color: #ccc; background-position:' . $bg_position_arr[$random_bg_position_keys[0]] . '}
								
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

function macroresearch_img_p($member_id = NULL, $type = '')
{
  $CI = &get_instance();
  $mem_info = $CI->master_model->getRecords('macroresearch_batch_candidates', array('regnumber' => $member_id), 'regnumber,candidate_photo,candidate_sign');
  if ($mem_info)
  {
    $scannedphoto = '';
    if ($mem_info[0]['candidate_photo'] != '' && $type == 'photo')
    {
      $new_image_path  = 'uploads/macroresearch/photo/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_photo']))
      {
        $scannedphoto = base_url() . $new_image_path . $mem_info[0]['candidate_photo'];
      }
    }
    else if ($mem_info[0]['candidate_sign'] != '' && $type == 'sign')
    {
      $new_image_path  = 'uploads/macroresearch/sign/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_sign']))
      {
        $scannedphoto = base_url() . $new_image_path . $mem_info[0]['candidate_sign'];
      }
    }
    else
    {
      $new_image_path  = 'uploads/macroresearch/photo/';
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
