<?php

/********************************************************************************************************************
 ** Description: Common Helper for NCVET Module
 ** Created BY: Gaurav Shewale On 11-08-2025
 ********************************************************************************************************************/
defined('BASEPATH') || exit('No Direct Allowed Here');

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
  /* $decode_id = base64_decode($id);
    $explode = explode("#*", $decode_id);			
    if(count($explode) > 1)
    {
      $explode1 = explode("*#", $explode[1]);
      if(count($explode1) > 1)
      {      
        return round((base64_decode($explode1[0])/5));
      }
      else
      {
        return "error";
      }
    }
    else
    {
      return "error";
    } */

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
								.CaptchaBgText { position: relative; width: 130px; height: 35px; background-image: url(' . base_url("assets/ncvet/images/captcha_bg.png") . '); background-size: ' . $wd_ht_arr[$random_keys[0]] . '; border: 1px solid #A2A2A2; background-color: #ccc; background-position:' . $bg_position_arr[$random_bg_position_keys[0]] . '}
								
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

  // Convert time Difference in minutes 
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
function generate_ncvet_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('regid'=>$reg_id);
			$last_id = $CI->master_model->insertRecord('ncvet_config_memreg',$insert_info,true);
		}
		return $last_id;
	}
function generate_ncvet_reg_receipt_no($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  != NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('ncvet_config_order_id',$insert_info,true);
		}
		return $last_id;
	}

/*START - Generate NCVET Exam Invoice Number for NEFT Payment
function generate_ncvet_exam_invoice_number($invoice_id = NULL)
{
  $last_id = '';
  $CI = &get_instance();
  //$CI->load->model('my_model');
  if ($invoice_id  != NULL)
  {
    $insert_info = array('invoice_id' => $invoice_id);
    $last_id = str_pad($CI->master_model->insertRecord('ncvet_config_exam_invoice', $insert_info, true), 6, "0", STR_PAD_LEFT);;
  }
  return $last_id;
}
/*END - Generate NCVETExam Invoice Number for NEFT Payment*/



/*START - Generate NCVET Exam Invoice Number for NEFT Payment*/
function generate_ncvet_enroll_invoice_number($invoice_id = NULL)
{
  $last_id = '';
  $CI = &get_instance();
  //$CI->load->model('my_model');
  if ($invoice_id  != NULL)
  {
    $insert_info = array('invoice_id' => $invoice_id);
    $last_id = str_pad($CI->master_model->insertRecord('ncvet_config_enroll_invoice', $insert_info, true), 6, "0", STR_PAD_LEFT);;
  }
  return $last_id;
}
/*END - Generate NCVETExam Invoice Number for NEFT Payment*/

function ncvet_genarate_reg_invoice($invoice_no,$enrollment=''){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('ncvet_exam_invoice',array('invoice_id'=>$invoice_no));
	
	$mem_info = $CI->master_model->getRecords('ncvet_candidates',array('regnumber'=>$invoice_info[0]['member_no']),'first_name,middle_name,last_name,address1_pr,address2_pr,address3_pr,city_pr,state_pr,pincode_pr');
	$member_name = $mem_info[0]['first_name']." ".$mem_info[0]['middle_name']." ".$mem_info[0]['last_name'];
	
	
	/*if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}*/
  $wordamt = amtinword($invoice_info[0]['fee_amt']);
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  160, 980, 160, $black); // line-5
	imageline ($im,   20,  200, 980, 200, $black); // line-6
	imageline ($im,   20,  480, 980, 480, $black); // line-7
	imageline ($im,   20,  520, 980, 520, $black); // line-8
	imageline ($im,   20,  580, 980, 580, $black); // line-9
	imageline ($im,   20,  850, 980, 850, $black); // line-10
	imageline ($im,   650,  200, 650, 480, $black); // line-11
	imageline ($im,   85,  520, 85, 850, $black); // line-12
	imageline ($im,   500,  520, 500, 850, $black); // line-13
	imageline ($im,   650,  520, 650, 850, $black); // line-14
	imageline ($im,   785,  520, 785, 850, $black); // line-15
	imageline ($im,   860,  520, 860, 850, $black); // line-16
	imageline ($im,   40,  880, 625, 880, $black); // line-17
	
	
	//imagestring(image,font,x,y,string,color);
	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "BILL OF SUPPLY", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "SAC ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	
	imagestring($im, 3, 40,  600, "1", $black);
  $titleToSet = "Fees Paid towards Candidate Enrollment for";
  if($enrollment=='re-enrollment')
    $titleToSet = "Fees Paid towards Candidate Re-Enrollment for";
	imagestring($im, 3, 105,  600, $titleToSet, $black);
	imagestring($im, 3, 105,  620, "Fundamentals of Retail Banking", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
	/*if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 690,  720, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 690,  740, "-", $black);
		
		imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  720, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  740, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, "-", $black);
		imagestring($im, 3, 690,  720, "-", $black);
		imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
		
		imagestring($im, 3, 900,  700, "-", $black);
		imagestring($im, 3, 900,  720, "-", $black);
		imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
	}*/
    
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	/*if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}*/
  imagestring($im, 3, 900,  830, $invoice_info[0]['fee_amt'], $black);
  
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".ucfirst($wordamt)." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ncvet/reginvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('ncvet_exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
	imagepng($im,"uploads/ncvet/reginvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ncvet/reginvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ncvet/reginvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  160, 980, 160, $black); // line-5
	imageline ($im,   20,  200, 980, 200, $black); // line-6
	imageline ($im,   20,  480, 980, 480, $black); // line-7
	imageline ($im,   20,  520, 980, 520, $black); // line-8
	imageline ($im,   20,  580, 980, 580, $black); // line-9
	imageline ($im,   20,  850, 980, 850, $black); // line-10
	imageline ($im,   650,  200, 650, 480, $black); // line-11
	imageline ($im,   85,  520, 85, 850, $black); // line-12
	imageline ($im,   500,  520, 500, 850, $black); // line-13
	imageline ($im,   650,  520, 650, 850, $black); // line-14
	imageline ($im,   785,  520, 785, 850, $black); // line-15
	imageline ($im,   860,  520, 860, 850, $black); // line-16
	imageline ($im,   40,  880, 625, 880, $black); // line-17
	
	
	//imagestring(image,font,x,y,string,color);
	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "BILL OF SUPPLY", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "SAC ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 105,  600, $titleToSet, $black);
	imagestring($im, 3, 105,  620, "Fundamentals of Retail Banking", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
  
	/*if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 690,  720, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 690,  740, "-", $black);
		
		imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  720, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  740, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, "-", $black);
		imagestring($im, 3, 690,  720, "-", $black);
		imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
		
		imagestring($im, 3, 900,  700, "-", $black);
		imagestring($im, 3, 900,  720, "-", $black);
		imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
	}*/
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	/*if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}*/
  imagestring($im, 3, 900,  830, $invoice_info[0]['fee_amt'], $black);
  
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".ucfirst($wordamt)." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ncvet/reginvoice/supplier/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/ncvet/reginvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ncvet/reginvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ncvet/reginvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/ncvet/reginvoice/user/".$imagename;
		
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

/*START - Generate NCVETINVOICE IMAGE*/
function ncvet_exam_invoice($invoice_no)
{
  $CI = &get_instance();

  // get invoice details
  $CI->db->join('state_master sm', 'sm.state_no = ei.state_code', 'LEFT');
  $invoice_info = $CI->master_model->getRecords('exam_invoice ei', array('ei.invoice_id' => $invoice_no), 'ei.*, sm.state_code AS invoiceStateCode');
  if (!$invoice_info)
  {
    //log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Invoice ID ot found');
    $CI->Ncvet_model->insert_common_log('Transaction : NCVETexam invoice generation', 'exam_invoice', $CI->db->last_query(), $invoice_no, 'transaction_action', 'The Exam Invoice ID not found', json_encode($invoice_info));
    return '';
  }

  $date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));

  // get DRA institute details
  $CI->db->join('state_master sm', 'sm.state_code = am.agency_state', 'LEFT');
  $CI->db->join('city_master ct', 'ct.id = am.agency_city', 'LEFT');
  $inst_details = $CI->master_model->getRecords('ncvet_agency_master am', array('am.agency_code' => $invoice_info[0]['institute_code']), 'am.agency_address1, am.agency_address2, am.agency_address3, am.agency_address4, am.agency_pincode, sm.state_name, sm.state_no, ct.city_name');

  if (!$inst_details)
  {
    //log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Institute code not found in dra_accerdited_master');
    $CI->Ncvet_model->insert_common_log('Transaction : NCVETexam invoice generation', 'ncvet_agency_master', $CI->db->last_query(), $invoice_info[0]['institute_code'], 'transaction_action', 'The Agency code not found', json_encode($inst_details));
    return '';
  }

  $payment_mode = 'Bulk';
  $payment_info = $CI->master_model->getRecords('ncvet_payment_transaction', array('id' => $invoice_info[0]['pay_txn_id']), 'payment_mode, exam_ids, centre_id');
  if ($payment_info > 0)
  {
    $payment_mode = $payment_info[0]['payment_mode'];
  }

  // convert invoice amount in words
  $amt_in_words = '';
  $invoice_chk_state = '';
  if ($payment_mode == 'Bulk')
  {
    $invoice_chk_state = $invoice_info[0]['invoiceStateCode'];    
  }
  else
  {
    $invoice_chk_state = $invoice_info[0]['state_of_center'];    
  }

  if ($invoice_chk_state == 'MAH')
  {
    $amt_in_words = trim(amount_in_word($invoice_info[0]['cs_total']));
  }
  elseif ($invoice_chk_state != 'MAH')
  {
    $amt_in_words = trim(amount_in_word($invoice_info[0]['igst_total']));
  }

  // create image
  //imagecreate(width, height);
  $im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
  $background_color = imagecolorallocate($im, 255, 255, 255); // white
  $black = imagecolorallocate($im, 0, 0, 0); // black

  $address1 = $address2 = $address3 = $address4 = $pincode = $state = $state_code = $city = '';
  if ($payment_mode == 'Bulk')
  {
    $CI->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
    $CI->db->join('city_master ct', 'ct.id = cm.centre_city', 'LEFT');
    $center_data = $CI->master_model->getRecords('ncvet_centre_master cm', array('cm.centre_id' => $payment_info[0]['centre_id']), 'cm.invoice_address, cm.centre_address1, cm.centre_address2, cm.centre_address3, cm.centre_address4, cm.centre_pincode, sm.state_name, sm.state_no, ct.city_name');
    if (count($center_data) > 0)
    {
      if ($center_data[0]['invoice_address'] == '1') //SHOW AGENCY ADDRESS ON INVOICE
      {
        $address1 = $inst_details[0]['agency_address1'];
        $address2 = $inst_details[0]['agency_address2'];
        $address3 = $inst_details[0]['agency_address3'];
        $address4 = $inst_details[0]['agency_address4'];
        $pincode = $inst_details[0]['agency_pincode'];
        $state = $inst_details[0]['state_name'];
        $state_code = $inst_details[0]['state_no'];
        $city = $inst_details[0]['city_name'];
      }
      else if ($center_data[0]['invoice_address'] == '2') //SHOW CENTRE ADDRESS ON INVOICE
      {
        $address1 = $center_data[0]['centre_address1'];
        $address2 = $center_data[0]['centre_address2'];
        $address3 = $center_data[0]['centre_address3'];
        $address4 = $center_data[0]['centre_address4'];
        $pincode = $center_data[0]['centre_pincode'];
        $state = $center_data[0]['state_name'];
        $state_code = $center_data[0]['state_no'];
        $city = $center_data[0]['city_name'];
      }
    }
  }
  else if ($payment_mode == 'Individual' || $payment_mode == 'CSC')
  {
    $CI->db->join('ncvet_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
    $CI->db->join('state_master sm', 'sm.state_code = bc.state', 'LEFT');
    $CI->db->join('city_master ct', 'ct.id = bc.city', 'LEFT');
    $candidate_data = $CI->master_model->getRecords('ncvet_member_exam me', array('me.member_exam_id' => $payment_info[0]['exam_ids']), 'bc.salutation, bc.first_name, bc.middle_name, bc.last_name, bc.address1, bc.address2, bc.address3, bc.address4, bc.pincode, sm.state_name, sm.state_no, ct.city_name');
    if (count($candidate_data) > 0)
    {
      $address1 = $candidate_data[0]['address1'];
      $address2 = $candidate_data[0]['address2'];
      $address3 = $candidate_data[0]['address3'];
      $address4 = $candidate_data[0]['address4'];
      $pincode = $candidate_data[0]['pincode'];
      $state = $candidate_data[0]['state_name'];
      $state_code = $candidate_data[0]['state_no'];
      $city = $candidate_data[0]['city_name'];
    }
  }

  $full_address = rtrim(trim($address1), ',');
  if ($address2 != "")
  {
    $full_address .= ', ' . rtrim(trim($address2), ',');
  }
  if ($address3 != "")
  {
    $full_address .= ', ' . rtrim(trim($address3), ',');
  }
  if ($address4 != "")
  {
    $full_address .= ', ' . rtrim(trim($address4), ',');
  }
  $final_add_arr = wrapString($full_address);

  //imageline ($im,   x1,  y1, x2, y2, color); 
  imageline($im,   20,  20, 980, 20, $black); // line-1
  imageline($im,   20,  980, 980, 980, $black); // line-2
  imageline($im,   20,  20, 20, 980, $black); // line-3
  imageline($im,   980, 20, 980, 980, $black); // line-4
  imageline($im,   20,  160, 980, 160, $black); // line-5
  imageline($im,   20,  200, 980, 200, $black); // line-6
  imageline($im,   20,  480, 980, 480, $black); // line-7
  imageline($im,   20,  520, 980, 520, $black); // line-8
  imageline($im,   20,  580, 980, 580, $black); // line-9
  imageline($im,   20,  850, 980, 850, $black); // line-10
  imageline($im,   650,  200, 650, 480, $black); // line-11
  imageline($im,   85,  520, 85, 850, $black); // line-12
  imageline($im,   500,  520, 500, 850, $black); // line-13
  imageline($im,   650,  520, 650, 850, $black); // line-14
  imageline($im,   785,  520, 785, 850, $black); // line-15
  imageline($im,   860,  520, 860, 850, $black); // line-16
  imageline($im,   40,  880, 625, 880, $black); // line-17  


  //imagestring(image,font,x,y,string,color);
  imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
  imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
  imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
  imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
  imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
  imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
  imagestring($im, 5, 400,  170, "BILL OF SUPPLY", $black);

  imagestring($im, 5, 40,  220, "Details of service recipient", $black);
  imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);

  if ($payment_mode == 'Bulk')
  {
    imagestring($im, 3, 40,  260, "Name of Institute: " . $invoice_info[0]['institute_name'], $black);
  }
  else if ($payment_mode == 'Individual' || $payment_mode == 'CSC')
  {
    imagestring($im, 3, 40,  260, "Name of Candidate: " . $candidate_data[0]['salutation'] . ' ' . $candidate_data[0]['first_name'] . ' ' . $candidate_data[0]['middle_name'] . ' ' . $candidate_data[0]['last_name'], $black);
  }

  $height_start = '280';
  if (isset($final_add_arr[0]) && trim($final_add_arr[0]) != "")
  {
    imagestring($im, 3, 40,  $height_start, "Address: " . trim($final_add_arr[0]), $black);
  }
  else
  {
    imagestring($im, 3, 40,  $height_start, "Address: ", $black);
  }

  if (isset($final_add_arr[1]) && trim($final_add_arr[1]) != "")
  {
    $height_start = $height_start + 20;
    imagestring($im, 3, 40,  $height_start, trim($final_add_arr[1]), $black);
  }

  if (isset($final_add_arr[2]) && trim($final_add_arr[2]) != "")
  {
    $height_start = $height_start + 20;
    imagestring($im, 3, 40,  $height_start, trim($final_add_arr[2]), $black);
  }

  if (isset($final_add_arr[3]) && trim($final_add_arr[3]) != "")
  {
    $height_start = $height_start + 20;
    imagestring($im, 3, 40,  $height_start, trim($final_add_arr[3]), $black);
  }

  imagestring($im, 3, 40, ($height_start + 20), "Pincode: " . $pincode, $black);
  imagestring($im, 3, 40, ($height_start + 40), "State: " . $state, $black);
  imagestring($im, 3, 40, ($height_start + 60), "State Code: " . $state_code, $black);
  imagestring($im, 3, 40, ($height_start + 80), "City: " . $city, $black);

  if ($payment_mode == 'Bulk')
  {
    imagestring($im, 3, 40, ($height_start + 100), "GST No: " . $invoice_info[0]['gstin_no'], $black);
    imagestring($im, 3, 40, ($height_start + 120), "Transaction Number : " . $invoice_info[0]['transaction_no'], $black);
  }
  else if ($payment_mode == 'Individual' || $payment_mode == 'CSC')
  {
    imagestring($im, 3, 40, ($height_start + 100), "Transaction Number : " . $invoice_info[0]['transaction_no'], $black);
  }

  imagestring($im, 3, 670,  260, "Invoice Number: " . $invoice_info[0]['invoice_no'], $black);
  imagestring($im, 3, 670,  280, "Date: " . $date_of_invoice, $black);
  imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);


  imagestring($im, 3, 40,  530, "Sr. No.", $black);
  imagestring($im, 3, 118,  530, "Description of Service", $black);
  imagestring($im, 3, 535,  530, "SAC ", $black);
  imagestring($im, 3, 535,  542, "code", $black);
  imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
  imagestring($im, 3, 808,  530, "Unit", $black);
  imagestring($im, 3, 900,  530, "Total(Rs.)", $black);



  imagestring($im, 3, 40,  600, "1", $black);
  imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
  imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);

  if ($invoice_info[0]['fresh_count'] != 0)
  {
    $base_total_R =  $invoice_info[0]['fresh_fee'] * $invoice_info[0]['fresh_count'];
    imagestring($im, 3, 690,  600, $invoice_info[0]['fresh_fee'], $black); // Rate
    imagestring($im, 3, 820,  600, $invoice_info[0]['fresh_count'], $black); // Quantity [unit]
    imagestring($im, 3, 900,  600, $base_total_R, $black); // Total
  }

  if ($invoice_info[0]['rep_count'] != 0)
  {
    $base_total_R =  $invoice_info[0]['rep_fee'] * $invoice_info[0]['rep_count'];
    imagestring($im, 3, 690,  620, $invoice_info[0]['rep_fee'], $black); // Rate
    imagestring($im, 3, 820,  620, $invoice_info[0]['rep_count'], $black); // Quantity [unit]
    imagestring($im, 3, 900,  620, $base_total_R, $black); // Total
  }

  if ($invoice_chk_state == 'MAH')
  {

    imagestring($im, 3, 118,  660, "CGST", $black);
    imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate'] . "%", $black);
    imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
    imagestring($im, 3, 118,  672, "SGST", $black);
    imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate'] . "%", $black);
    imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);

    //imagestring($im, 3, 100,  710, "Supply", $black);
    imagestring($im, 3, 118,  710, "IGST", $black);
    imagestring($im, 3, 690,  710, "-", $black);
    imagestring($im, 3, 900,  710, "-", $black);
  }

  if ($invoice_chk_state != 'MAH' && $invoice_chk_state != 'JAM')
  {

    imagestring($im, 3, 118,  660, "CGST", $black);
    imagestring($im, 3, 690,  660, "-", $black);
    imagestring($im, 3, 900,  660, "-", $black);
    imagestring($im, 3, 118,  672, "SGST", $black);
    imagestring($im, 3, 690,  672, "-", $black);
    imagestring($im, 3, 900,  672, "-", $black);


    //imagestring($im, 3, 100,  710, "Supply", $black);
    imagestring($im, 3, 118,  710, "IGST", $black);
    imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate'] . "%", $black);
    imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
  }

  if ($invoice_chk_state == 'JAM')
  {

    imagestring($im, 3, 118,  660, "CGST", $black);
    imagestring($im, 3, 690,  660, "-", $black);
    imagestring($im, 3, 900,  660, "-", $black);
    imagestring($im, 3, 118,  672, "SGST", $black);
    imagestring($im, 3, 690,  672, "-", $black);
    imagestring($im, 3, 900,  672, "-", $black);


    //imagestring($im, 3, 100,  710, "Supply", $black);
    imagestring($im, 3, 118,  710, "IGST", $black);
    imagestring($im, 3, 690,  710, "-", $black);
    imagestring($im, 3, 900,  710, "-", $black);
  }


  imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
  if ($invoice_chk_state == 'MAH')
  {
    imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
  }
  elseif ($invoice_chk_state != 'MAH')
  {
    imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
  }




  imagestring($im, 3, 40,  860, "Amount in words :" . $amt_in_words . " Only", $black);
  imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
  imagestring($im, 3, 260,  900, "Y/N", $black);
  imagestring($im, 3, 300,  900, "NO", $black);
  imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
  imagestring($im, 3, 280,  930, "% ---", $black);
  imagestring($im, 3, 350,  930, "Rs.---", $black);

  imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
  imagestring($im, 3, 720,  950, "Authorised Signatory", $black);

  $savepath = base_url() . "uploads/ncvet/ncvet_examinvoice/user/";
  $ino = str_replace("/", "_", $invoice_info[0]['invoice_no']);
  $imagename = $invoice_info[0]['institute_code'] . "_" . $ino . ".jpg";

  $update_data = array('invoice_image' => $imagename);
  $CI->master_model->updateRecord('exam_invoice', $update_data, array('invoice_id' => $invoice_no));

  imagepng($im, "uploads/ncvet/ncvet_examinvoice/user/" . $imagename);
  $png = @imagecreatefromjpeg('assets/images/sign.jpg');
  $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
  $jpeg = @imagecreatefromjpeg("uploads/ncvet/ncvet_examinvoice/user/" . $imagename);

  //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
  @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
  @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
  imagepng($im, 'uploads/ncvet/ncvet_examinvoice/user/' . $imagename);
  imagedestroy($im);


  /*********************** Image for supplier *************************************/
  //imagecreate(width, height);
  $im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
  $background_color = imagecolorallocate($im, 255, 255, 255); // white
  $black = imagecolorallocate($im, 0, 0, 0); // black


  //imageline ($im,   x1,  y1, x2, y2, color); 
  imageline($im,   20,  20, 980, 20, $black); // line-1
  imageline($im,   20,  980, 980, 980, $black); // line-2
  imageline($im,   20,  20, 20, 980, $black); // line-3
  imageline($im,   980, 20, 980, 980, $black); // line-4
  imageline($im,   20,  160, 980, 160, $black); // line-5
  imageline($im,   20,  200, 980, 200, $black); // line-6
  imageline($im,   20,  480, 980, 480, $black); // line-7
  imageline($im,   20,  520, 980, 520, $black); // line-8
  imageline($im,   20,  580, 980, 580, $black); // line-9
  imageline($im,   20,  850, 980, 850, $black); // line-10
  imageline($im,   650,  200, 650, 480, $black); // line-11
  imageline($im,   85,  520, 85, 850, $black); // line-12
  imageline($im,   500,  520, 500, 850, $black); // line-13
  imageline($im,   650,  520, 650, 850, $black); // line-14
  imageline($im,   785,  520, 785, 850, $black); // line-15
  imageline($im,   860,  520, 860, 850, $black); // line-16
  imageline($im,   40,  880, 625, 880, $black); // line-17



  //imagestring(image,font,x,y,string,color);
  imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
  imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
  imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
  imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
  imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
  imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
  imagestring($im, 5, 400,  170, "BILL OF SUPPLY", $black);

  imagestring($im, 5, 40,  220, "Details of service recipient", $black);
  imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);

  if ($payment_mode == 'Bulk')
  {
    imagestring($im, 3, 40,  260, "Name of Institute: " . $invoice_info[0]['institute_name'], $black);
  }
  else if ($payment_mode == 'Individual' || $payment_mode == 'CSC')
  {
    imagestring($im, 3, 40,  260, "Name of Candidate: " . $candidate_data[0]['salutation'] . ' ' . $candidate_data[0]['first_name'] . ' ' . $candidate_data[0]['middle_name'] . ' ' . $candidate_data[0]['last_name'], $black);
  }

  $height_start = '280';
  if (isset($final_add_arr[0]) && trim($final_add_arr[0]) != "")
  {
    imagestring($im, 3, 40,  $height_start, "Address: " . trim($final_add_arr[0]), $black);
  }
  else
  {
    imagestring($im, 3, 40,  $height_start, "Address: ", $black);
  }

  if (isset($final_add_arr[1]) && trim($final_add_arr[1]) != "")
  {
    $height_start = $height_start + 20;
    imagestring($im, 3, 40,  $height_start, trim($final_add_arr[1]), $black);
  }

  if (isset($final_add_arr[2]) && trim($final_add_arr[2]) != "")
  {
    $height_start = $height_start + 20;
    imagestring($im, 3, 40,  $height_start, trim($final_add_arr[2]), $black);
  }

  if (isset($final_add_arr[3]) && trim($final_add_arr[3]) != "")
  {
    $height_start = $height_start + 20;
    imagestring($im, 3, 40,  $height_start, trim($final_add_arr[3]), $black);
  }

  imagestring($im, 3, 40, ($height_start + 20), "Pincode: " . $pincode, $black);
  imagestring($im, 3, 40, ($height_start + 40), "State: " . $state, $black);
  imagestring($im, 3, 40, ($height_start + 60), "State Code: " . $state_code, $black);
  imagestring($im, 3, 40, ($height_start + 80), "City: " . $city, $black);

  if ($payment_mode == 'Bulk')
  {
    imagestring($im, 3, 40, ($height_start + 100), "GST No: " . $invoice_info[0]['gstin_no'], $black);
    imagestring($im, 3, 40, ($height_start + 120), "Transaction Number : " . $invoice_info[0]['transaction_no'], $black);
  }
  else if ($payment_mode == 'Individual' || $payment_mode == 'CSC')
  {
    imagestring($im, 3, 40, ($height_start + 100), "Transaction Number : " . $invoice_info[0]['transaction_no'], $black);
  }

  imagestring($im, 3, 670,  260, "Invoice Number: " . $invoice_info[0]['invoice_no'], $black);
  imagestring($im, 3, 670,  280, "Date: " . $date_of_invoice, $black);
  imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);

  imagestring($im, 3, 40,  530, "Sr. No.", $black);
  imagestring($im, 3, 118,  530, "Description of Service", $black);
  imagestring($im, 3, 535,  530, "SAC ", $black);
  imagestring($im, 3, 535,  542, "code", $black);
  imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
  imagestring($im, 3, 808,  530, "Unit", $black);
  imagestring($im, 3, 900,  530, "Total(Rs.)", $black);



  imagestring($im, 3, 40,  600, "1", $black);
  imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
  imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);


  if ($invoice_info[0]['fresh_count'] != 0)
  {
    $base_total_R =  $invoice_info[0]['fresh_fee'] * $invoice_info[0]['fresh_count'];
    imagestring($im, 3, 690,  600, $invoice_info[0]['fresh_fee'], $black); // Rate
    imagestring($im, 3, 820,  600, $invoice_info[0]['fresh_count'], $black); // Quantity [unit]
    imagestring($im, 3, 900,  600, $base_total_R, $black); // Total
  }

  if ($invoice_info[0]['rep_count'] != 0)
  {
    $base_total_R =  $invoice_info[0]['rep_fee'] * $invoice_info[0]['rep_count'];
    imagestring($im, 3, 690,  620, $invoice_info[0]['rep_fee'], $black); // Rate
    imagestring($im, 3, 820,  620, $invoice_info[0]['rep_count'], $black); // Quantity [unit]
    imagestring($im, 3, 900,  620, $base_total_R, $black); // Total
  }

  if ($invoice_chk_state == 'MAH')
  {

    imagestring($im, 3, 118,  660, "CGST", $black);
    imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate'] . "%", $black);
    imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
    imagestring($im, 3, 118,  672, "SGST", $black);
    imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate'] . "%", $black);
    imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);


    //imagestring($im, 3, 100,  710, "Supply", $black);
    imagestring($im, 3, 118,  710, "IGST", $black);
    imagestring($im, 3, 690,  710, "-", $black);
    imagestring($im, 3, 900,  710, "-", $black);
  }

  if ($invoice_chk_state != 'MAH' && $invoice_chk_state != 'JAM')
  {

    imagestring($im, 3, 118,  660, "CGST", $black);
    imagestring($im, 3, 690,  660, "-", $black);
    imagestring($im, 3, 900,  660, "-", $black);
    imagestring($im, 3, 118,  672, "SGST", $black);
    imagestring($im, 3, 690,  672, "-", $black);
    imagestring($im, 3, 900,  672, "-", $black);


    //imagestring($im, 3, 100,  710, "Supply", $black);
    imagestring($im, 3, 118,  710, "IGST", $black);
    imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate'] . "%", $black);
    imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
  }

  if ($invoice_chk_state == 'JAM')
  {

    imagestring($im, 3, 118,  660, "CGST", $black);
    imagestring($im, 3, 690,  660, "-", $black);
    imagestring($im, 3, 900,  660, "-", $black);
    imagestring($im, 3, 118,  672, "SGST", $black);
    imagestring($im, 3, 690,  672, "-", $black);
    imagestring($im, 3, 900,  672, "-", $black);


    //imagestring($im, 3, 100,  710, "Supply", $black);
    imagestring($im, 3, 118,  710, "IGST", $black);
    imagestring($im, 3, 690,  710, "-", $black);
    imagestring($im, 3, 900,  710, "-", $black);
  }


  imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
  if ($invoice_chk_state == 'MAH')
  {
    imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
  }
  elseif ($invoice_chk_state != 'MAH')
  {
    imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
  }




  imagestring($im, 3, 40,  860, "Amount in words :" . $amt_in_words . " Only", $black);
  imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
  imagestring($im, 3, 260,  900, "Y/N", $black);
  imagestring($im, 3, 300,  900, "NO", $black);
  imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
  imagestring($im, 3, 280,  930, "% ---", $black);
  imagestring($im, 3, 350,  930, "Rs.---", $black);

  imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
  imagestring($im, 3, 720,  950, "Authorised Signatory", $black);



  $savepath = base_url() . "uploads/ncvet/ncvet_examinvoice/supplier/";
  $ino = str_replace("/", "_", $invoice_info[0]['invoice_no']);
  $imagename = $invoice_info[0]['institute_code'] . "_" . $ino . ".jpg";

  imagepng($im, "uploads/ncvet/ncvet_examinvoice/supplier/" . $imagename);
  $png = @imagecreatefromjpeg('assets/images/sign.jpg');
  $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
  $jpeg = @imagecreatefromjpeg("uploads/ncvet/ncvet_examinvoice/supplier/" . $imagename);

  //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
  @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
  @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
  imagepng($im, 'uploads/ncvet/ncvet_examinvoice/supplier/' . $imagename);
  imagedestroy($im);

  return $attachpath = "uploads/ncvet/ncvet_examinvoice/user/" . $imagename;
}
/*END - Generate NCVETINVOICE IMAGE*/

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

function ncvet_img_p($member_id = NULL, $type = '')
{
  $CI = &get_instance();
  $mem_info = $CI->master_model->getRecords('ncvet_candidates', array('regnumber' => $member_id), 'regnumber,candidate_photo,candidate_sign');
  if ($mem_info)
  {
    $imagepath = '';
    if ($mem_info[0]['candidate_photo'] != '' && $type == 'photo')
    {
      $new_image_path  = 'uploads/ncvet/photo/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_photo']))
      {
        $imagepath = base_url() . $new_image_path . $mem_info[0]['candidate_photo'];
      }
    }
    else if ($mem_info[0]['candidate_sign'] != '' && $type == 'sign')
    {
      $new_image_path  = 'uploads/ncvet/sign/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_sign']))
      {
        $imagepath = base_url() . $new_image_path . $mem_info[0]['candidate_sign'];
      }
    }
    else
    {
      $new_image_path  = 'uploads/ncvet/photo/';
      if (file_exists($new_image_path . $mem_info[0]['candidate_photo']))
      {
        $imagepath = base_url() . $new_image_path . $mem_info[0]['candidate_photo'];
      }
    }
    return $imagepath;
  }
}

function display_exam_name($description = '', $exam_code = '', $exam_type = '')
{
  $description = trim($description);
  $exam_code = trim($exam_code);
  $exam_type = trim($exam_type);

  $final_exam_name = '';
  if ($description != '')
  {
    $tmp_exam_name = strtolower($description);//make it lowercase
    $tmp_exam_name = str_replace("(advanced)","",$tmp_exam_name);//replace (advanced) with empty value
    $tmp_exam_name = str_replace("(advance)","",$tmp_exam_name);//replace (advance) with empty value
    $tmp_exam_name = str_replace("(basic)","",$tmp_exam_name);//replace (basic) with empty value
    $tmp_exam_name = str_replace("  "," ",$tmp_exam_name);//replace double spaces with single space

    $final_exam_name .= ucwords($tmp_exam_name);
  }

  if ($exam_code != '' || $exam_type != '')
  {
    $final_exam_name .= " (";
  }
  if ($exam_code != '')
  {
    $final_exam_name .= $exam_code;
  }

  if ($exam_type != '')
  {
    if ($exam_code != "")
    {
      $final_exam_name .= ' - ';
    }

    if ($exam_type == '1')
    {
      $final_exam_name .= 'Basic';
    }
    else
    {
      $final_exam_name .= 'Advanced';
    }
  }

  if (in_array($exam_code, array(1037, 1038)))
  {
  }

  else if (in_array($exam_code, array(1039, 1040)))
  {
    $final_exam_name .= " - CSC Mode";
  }
  else if (in_array($exam_code, array(1041, 1042, 1057)))
  {
    $final_exam_name .= " - Hybrid Mode";
  }

  if ($exam_code != '' || $exam_type != '')
  {
    $final_exam_name .= ")";
  }

  return $final_exam_name;
}

function display_exam_name_admit_card($description = '', $exam_code = '', $exam_type = '')
{
  $description = trim($description);
  $exam_code = trim($exam_code);
  $exam_type = trim($exam_type);

  $final_exam_name = '';
  if ($description != '')
  {
    $tmp_exam_name = strtolower($description);//make it lowercase
    $tmp_exam_name = str_replace("(advanced)","",$tmp_exam_name);//replace (advanced) with empty value
    $tmp_exam_name = str_replace("(advance)","",$tmp_exam_name);//replace (advance) with empty value
    $tmp_exam_name = str_replace("(basic)","",$tmp_exam_name);//replace (basic) with empty value
    $tmp_exam_name = str_replace("  "," ",$tmp_exam_name);//replace double spaces with single space

    $final_exam_name .= ucwords($tmp_exam_name);
  }

  if ($exam_code != '' || $exam_type != '')
  {
    $final_exam_name .= " (";
  }

  if ($exam_type != '')
  {
    if ($exam_type == '1')
    {
      $final_exam_name .= 'Basic';
    }
    else
    {
      $final_exam_name .= 'Advanced';
    }
  }

  if (in_array($exam_code, array(1037, 1038)))
  {
  }

  else if (in_array($exam_code, array(1039, 1040)))
  {
    $final_exam_name .= " - CSC Mode";
  }
  else if (in_array($exam_code, array(1041, 1042, 1057)))
  {
    $final_exam_name .= " - Hybrid Mode";
  }

  if ($exam_code != '' || $exam_type != '')
  {
    $final_exam_name .= ")";
  }

  return $final_exam_name;
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

function checkEditableField($field='',$candidate_id=0)
{
  $isEditable = false;
  if ($field != '' && $candidate_id != 0) 
  {
    $CI = &get_instance();
    $candidate_data = $CI->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.updated_fields");

    $updated_fields = isset($candidate_data[0]['updated_fields']) && $candidate_data[0]['updated_fields'] != '' ? $candidate_data[0]['updated_fields'] : '';
    
    if (strpos($updated_fields, $field) !== false) {
      $isEditable = true;
    }  
  }
  return $isEditable;
}

  function ncvet_checkCandidateEligible($candidate_id,$examcode=0,$examperiod=0) 
  {
      $flag =1;
      $errorType=$$message = '';
      $CI = &get_instance();
      $candidate_data = $CI->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");   
      if($candidate_data[0]['kyc_status']!=2 || $candidate_data[0]['is_active']!=1 ) {
        $flag = 0;
        $errorType='kyc';
        $message = 'Please wait until KYC Process done for your documents';
      }
      if($examcode>0) {

        $check_eligibility_for_exam = $CI->master_model->getRecords('ncvet_eligible_master', array(
                    'ncvet_eligible_master.exam_code' => $examcode,
                    'member_no'                 => $candidate_data[0]['regnumber'],
        ));
        //echo $CI->db->last_query();exit;
        if(count($check_eligibility_for_exam)>0) {
          if($check_eligibility_for_exam[0]['exam_status']!='F') {
            $flag = 0;
            $errorType='exameligible';
            $message = 'You are not eligible for selected exam';
          }

          else 
          { //check if candidate already applied for another exam from ncvet list

            $get_exam_dates = $CI->master_model->getRecords('ncvet_subject_master', array(
                'exam_code' => $examcode,
                'exam_period' => $examperiod,
            ));
            
            $exam_date_arr = array();
            if (count($get_exam_dates) > 0) {
                foreach ($get_exam_dates as $k => $v) {
                    $exam_date_arr[] = $v['exam_date'];
                }
            }

            $CI->db->join('ncvet_exam_activation_master', 'ncvet_exam_activation_master.exam_code=ncvet_member_exam.exam_code AND ncvet_exam_activation_master.exam_period=ncvet_member_exam.exam_period');
            $CI->db->join('ncvet_exam_master', 'ncvet_exam_master.exam_code=ncvet_member_exam.exam_code');
            $CI->db->join('ncvet_admit_card_details', 'ncvet_admit_card_details.mem_exam_id = ncvet_member_exam.id', 'inner');
            
            $CI->db->where('bulk_isdelete', '0');
            

            $CI->db->where_in('ncvet_admit_card_details.exam_date', $exam_date_arr);

            //if instutute id is null/empty then check remark is 1 else no need to check remark
            $CI->db->where(" (((institute_id IS NULL OR institute_id = '' OR institute_id = '0') AND remark = '1') OR (institute_id IS NOT NULL AND institute_id != '' AND institute_id != '0')) ");

            $applied_exam_info = $CI->master_model->getRecords('ncvet_member_exam', array('regnumber' => $candidate_data[0]['regnumber']));
            if (isset($applied_exam_info) && count($applied_exam_info) > 0) {
              $flag = 0;
              $errorType='alreadyappliedondate';
              $message = 'You have already applied for exam on same date';
            }
          }
        }
        else
        {
          $flag = 0;
          $errorType='exameligible';
          $message = 'You are not eligible for selected exam';
        }

         
      }

      
      return array('flag'=>$flag,'errorType'=>$errorType,'message'=>$message,'candidate_data'=>$candidate_data);
  }
  function ncvet_checkqualify($candidate_id=0,$examcode=0,$examperiod=0) {
    $flag                    = 1;
    $message = '';
    $CI = & get_instance();
    $candidate_data = $CI->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 
    $check_qualify_exam = $CI->master_model->getRecords('ncvet_exam_master', array(
              'exam_code' => $examcode,
          ));
    $should_qualify_exams = array();
    if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0') {
        $should_qualify_exams[]=$check_qualify_exam[0]['qualifying_exam1'];
    }
    if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0') {
        $should_qualify_exams[]=$check_qualify_exam[0]['qualifying_exam2'];
    }
    if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0') {
        $should_qualify_exams[]=$check_qualify_exam[0]['qualifying_exam3'];
    }
    if ($check_qualify_exam[0]['qualifying_exam4'] != '' && $check_qualify_exam[0]['qualifying_exam4'] != '0') {
        $should_qualify_exams[]=$check_qualify_exam[0]['qualifying_exam4'];
    }
    if(count($should_qualify_exams)>0) {
      $CI->db->where_in('ncvet_eligible_master.exam_code', $should_qualify_exams);
      $check_eligible_data = $CI->master_model->getRecords('ncvet_eligible_master', array('member_no' => $candidate_data[0]['regnumber']));
      if(count($check_eligible_data)>0) {
        foreach($check_eligible_data as $check_eligible_data) {
          if($check_eligible_data['exam_status']!='P') {

            $exam_details = $CI->master_model->getRecords('ncvet_exam_master', array(
                'exam_code' => $check_eligible_data['exam_code'],
            ));
            $flag  = 0;
            $message = 'you have not cleared qualifying examination - <strong>' . $exam_details[0]['description'] . '</strong>.';
          }
        }
      }
      else {
          $flag  = 0;
          $message = 'you have not cleared qualifying examination ';

      }
    }
      

    return array('flag'=>$flag,'message'=>$message);

  }

  function ncvet_examapplied($candidate_id = null, $examcode = null)
  {
        // check where exam alredy apply or not
        $cnt        = 0;
        $message = '';
        $flag = 1;
        $today_date = date('Y-m-d');
        $CI = & get_instance();
        $candidate_data = $CI->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 
        
        $CI->db->join('ncvet_exam_activation_master', 'ncvet_exam_activation_master.exam_code=ncvet_member_exam.exam_code AND ncvet_exam_activation_master.exam_period=ncvet_member_exam.exam_period');
        $CI->db->join('ncvet_exam_master', 'ncvet_exam_master.exam_code=ncvet_member_exam.exam_code');
        
        $CI->db->where("'$today_date' BETWEEN ncvet_exam_activation_master.exam_from_date AND ncvet_exam_activation_master.exam_to_date");
        
        $CI->db->where('pay_status', '1');
        $applied_exam_info = $CI->master_model->getRecords('ncvet_member_exam', array(
            'ncvet_member_exam.exam_code' =>$examcode,
            'regnumber'             => $candidate_data[0]['regnumber'],
        ));
        //echo $CI->db->last_query();exit;
        
        if (count($applied_exam_info) <= 0) {
            $CI->db->join('ncvet_exam_activation_master', 'ncvet_exam_activation_master.exam_code=ncvet_member_exam.exam_code AND ncvet_exam_activation_master.exam_period=ncvet_member_exam.exam_period');
            $CI->db->join('ncvet_exam_master', 'ncvet_exam_master.exam_code=ncvet_member_exam.exam_code');
            $CI->db->where("'$today_date' BETWEEN ncvet_exam_activation_master.exam_from_date AND ncvet_exam_activation_master.exam_to_date");
            
            $CI->db->where('bulk_isdelete', '0');
            $CI->db->where('institute_id!=', '');
            $applied_exam_info = $CI->master_model->getRecords('ncvet_member_exam', array(
                'ncvet_member_exam.exam_code' => $examcode,
                'regnumber'             => $candidate_data[0]['regnumber'],
            ));
        }
        if(count($applied_exam_info)>0) {
          $flag = 0;
          $get_period_info = $CI->master_model->getRecords('ncvet_misc_master', array('ncvet_misc_master.exam_code' => $examcode, 'ncvet_misc_master.misc_delete' => '0'), 'exam_month');
           
            $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
            $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
            $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
        }
        return array('flag'=>$flag,'message'=>$message);
  }
  function check_ncvet_exam_activate($exam_code = NULL)
  {
    $flag=0;
    
    $CI = & get_instance();
    //$CI->load->model('my_model');
    if($exam_code !=NULL)
    {
      $today_date=date('Y-m-d');
      $CI->db->select('ncvet_exam_activation_master.*,ncvet_misc_master.exam_month');
      $CI->db->join('ncvet_center_master', 'ncvet_exam_activation_master.exam_code=ncvet_center_master.exam_name AND ncvet_exam_activation_master.exam_period=ncvet_center_master.exam_period');
      $CI->db->join('ncvet_misc_master', 'ncvet_exam_activation_master.exam_code=ncvet_misc_master.exam_code  AND ncvet_exam_activation_master.exam_period=ncvet_misc_master.exam_period');
      $CI->db->join('ncvet_medium_master', 'ncvet_exam_activation_master.exam_code=ncvet_medium_master.exam_code  AND ncvet_exam_activation_master.exam_period=ncvet_medium_master.exam_period');
      //$CI->db->join('ncvet_fee_master', 'ncvet_exam_activation_master.exam_code=ncvet_fee_master.exam_code  AND ncvet_exam_activation_master.exam_period=ncvet_fee_master.exam_period');
      $CI->db->join('ncvet_exam_master', 'ncvet_exam_activation_master.exam_code=ncvet_exam_master.exam_code');
      $CI->db->where("'$today_date' BETWEEN ncvet_exam_activation_master.exam_from_date AND ncvet_exam_activation_master.exam_to_date");
      $exam_list=$CI->master_model->getRecords('ncvet_exam_activation_master',array('ncvet_exam_activation_master.exam_code'=>$exam_code));
      
      if(count($exam_list) > 0)
      {
        $flag=1;
      }
    }
    return array('flag'=>$flag,'exam_list'=>$exam_list);
  }
  function ncvet_getexamfee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		
		
		
		$fee=0;
		$CI = & get_instance();
		
		
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('ncvet_center_master',array('exam_name'=>($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('ncvet_center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				 $today_date=date('Y-m-d');
				 
				
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('ncvet_fee_master',array('exam_code'=>($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
        //echo $CI->db->last_query();exit;
				
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('ncvet_fee_master',array('exam_code'=>($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getfees[0]['exempt']=='E')
					{
						$fee=$getfees[0]['fee_amount'];
					}
					else
					{  
						
						if($elearning_flag == 'Y'){
							$el_amt = $getfees[0]['elearning_cs_amt_total'] * 1;
							$fee = $getfees[0]['igst_tot'] + $el_amt;
						}else{ 
							$fee=$getfees[0]['igst_tot'];
						}
						
					}
				}
			}
		}
		
		return $fee;
	}

  function generate_ncvet_exam_order_id($pt_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($pt_id  !=NULL)
		{
			$insert_info = array('pt_id'=>$pt_id);
			$last_id = $CI->master_model->insertRecord('config_ncvet_exam_order_id',$insert_info,true);
		}
		return $last_id;
	}
 function ncvetexam_set_cookie($examid=NULL)
 {
	 if($examid!=NULL)
	 {
		$cookie = array(
        'name'   => 'examid',
        'value'  => $examid,
        'expire' => time()+300,
        );
		set_cookie($cookie,true);
	 }
	

 }
function genarate_ncvet_exam_invoice($invoice_no){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('ncvet_exam_invoice',array('invoice_id'=>$invoice_no));
	
	$mem_info = $CI->master_model->getRecords('ncvet_candidates',array('regnumber'=>$invoice_info[0]['member_no']),'first_name,middle_name,last_name,address1_pr,address2_pr,address3_pr,city_pr,state_pr,pincode_pr');
	$member_name = $mem_info[0]['first_name']." ".$mem_info[0]['middle_name']." ".$mem_info[0]['last_name'];
	
	
	/*if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}*/
  $wordamt = amtinword($invoice_info[0]['fee_amt']);
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  160, 980, 160, $black); // line-5
	imageline ($im,   20,  200, 980, 200, $black); // line-6
	imageline ($im,   20,  480, 980, 480, $black); // line-7
	imageline ($im,   20,  520, 980, 520, $black); // line-8
	imageline ($im,   20,  580, 980, 580, $black); // line-9
	imageline ($im,   20,  850, 980, 850, $black); // line-10
	imageline ($im,   650,  200, 650, 480, $black); // line-11
	imageline ($im,   85,  520, 85, 850, $black); // line-12
	imageline ($im,   500,  520, 500, 850, $black); // line-13
	imageline ($im,   650,  520, 650, 850, $black); // line-14
	imageline ($im,   785,  520, 785, 850, $black); // line-15
	imageline ($im,   860,  520, 860, 850, $black); // line-16
	imageline ($im,   40,  880, 625, 880, $black); // line-17
	
	
	//imagestring(image,font,x,y,string,color);
	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "BILL OF SUPPLY", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "SAC ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 105,  600, "Fees Paid towards Exam for", $black);
	imagestring($im, 3, 105,  620, "Fundamentals of Retail Banking", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	
  imagestring($im, 3, 900,  830, $invoice_info[0]['fee_amt'], $black);
  
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".ucfirst($wordamt)." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ncvet/examinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('ncvet_exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
	imagepng($im,"uploads/ncvet/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ncvet/examinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ncvet/examinvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  160, 980, 160, $black); // line-5
	imageline ($im,   20,  200, 980, 200, $black); // line-6
	imageline ($im,   20,  480, 980, 480, $black); // line-7
	imageline ($im,   20,  520, 980, 520, $black); // line-8
	imageline ($im,   20,  580, 980, 580, $black); // line-9
	imageline ($im,   20,  850, 980, 850, $black); // line-10
	imageline ($im,   650,  200, 650, 480, $black); // line-11
	imageline ($im,   85,  520, 85, 850, $black); // line-12
	imageline ($im,   500,  520, 500, 850, $black); // line-13
	imageline ($im,   650,  520, 650, 850, $black); // line-14
	imageline ($im,   785,  520, 785, 850, $black); // line-15
	imageline ($im,   860,  520, 860, 850, $black); // line-16
	imageline ($im,   40,  880, 625, 880, $black); // line-17
	
	
	//imagestring(image,font,x,y,string,color);
	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "BILL OF SUPPLY", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "SAC ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 105,  600, "Fees Paid towards Exam Application for", $black);
	imagestring($im, 3, 105,  620, "Fundamentals of Retail Banking", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	imagestring($im, 3, 900,  830, $invoice_info[0]['fee_amt'], $black);
  
	imagestring($im, 3, 40,  860, "Amount in words :".ucfirst($wordamt)." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ncvet/examinvoice/supplier/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/ncvet/examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ncvet/examinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ncvet/examinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/ncvet/examinvoice/user/".$imagename;
		
}
function genarate_ncvet_admitcard($member_id,$exam_code,$exam_period)
{
	
	try{
		
		$CI = & get_instance();	
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code,  mem_exam_id,insname, DATE_FORMAT(created_on, "%d%m%Y%H%i%s") as created_on');
		$CI->db->from('ncvet_admit_card_details');
		$CI->db->where(array('ncvet_admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->where('ncvet_admit_card_details.exam_date >=',date('Y-m-d'));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		$center = $member_result->center_name;

		if(sizeof($member_result) == 0){
			
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		$medium_code = $member_result->m_1;
		
		if($medium_code == 'ENGLISH' || $medium_code == 'E'){
			$medium_code_lng = 'E';
		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
			$medium_code_lng = 'H';
		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
			$medium_code_lng = 'A';
		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
			$medium_code_lng = 'G';
		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
			$medium_code_lng = 'K';
		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
			$medium_code_lng = 'L';
		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
			$medium_code_lng = 'M';
		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
			$medium_code_lng = 'N';

		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
			$medium_code_lng = 'O';
		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
			$medium_code_lng = 'S';
		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
			$medium_code_lng = 'T';
		}
		
		$CI->db->select('medium_description');
		$medium = $CI->db->get_where('ncvet_medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();

		$memberDetails = $CI->master_model->getRecords('ncvet_candidates',array('regnumber'=>$member_id)); 

				
		// Added by Priyanka W for Transaction no dissplay on admit card
		$CI->db->select('transaction_no');
		$payment = $CI->db->get_where('ncvet_payment_transaction', array('member_regnumber' => $member_result->mem_mem_no, 'ref_id' => $member_result->mem_exam_id));
		$payment_result = $payment->row();
		//$data['transaction_no'] = $payment_result->transaction_no;

    $CI->db->join('ncvet_subject_master', 'ncvet_subject_master.subject_code=ncvet_admit_card_details.sub_cd');
    $CI->db->where('ncvet_subject_master.exam_code',$exam_code);
    $CI->db->where('ncvet_subject_master.exam_period',$exam_period);
		$subject_result = $CI->master_model->getRecords('ncvet_admit_card_details', array('mem_mem_no' => $member_id, 'exm_cd' => $exam_code, 'exm_prd' => $exam_period,'remark'=>1));
    $exdate = date("d-M-y", strtotime($subject_result[0]['exam_date'])); 
		$examdate = explode("-",$exdate);
		$printdate = '';

		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period,'memberDetails'=>$memberDetails, 'transaction_no' => $payment_result->transaction_no,'center'=>$center); 
		
		$html=$CI->load->view('ncvet/candidate/ncvet_admitcard', $data, true);
		
		//echo $optFlgRecord;
		//
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/ncvet/admitcardpdf/'.$pdfFilePath, "F"); 
		
		
		
		$admit_card_details = $CI->db->get_where('ncvet_admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($admit_card_details as $admit_card_update){
		foreach($admit_card_details->result_array() as $admit_card_update){
			
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->update('ncvet_admit_card_details',$update_data);	
			
			//$last_update_query_error = $CI->db->_error_message();
			
			$last_update_query = $CI->db->last_query();
		}
	
		return 'uploads/ncvet/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}
function check_ncvet_training_activate($program_code = NULL)
  {
    $flag=0;
    
    $CI = & get_instance();
    //$CI->load->model('my_model');
    if($program_code !=NULL)
    {
      $today_date=date('Y-m-d');
      $CI->db->select('ncvet_training_activation.*');
     
      $CI->db->where("'$today_date' BETWEEN ncvet_training_activation.from_date AND ncvet_training_activation.to_date");
      $training_list=$CI->master_model->getRecords('ncvet_training_activation',array('ncvet_training_activation.program_code'=>$program_code));
      
      if(count($training_list) > 0)
      {
        $flag=1;
      }
    }
    return array('flag'=>$flag,'training_list'=>$training_list);
  }

function ncvet_checkCandidateEligibleTraining($candidate_id,$program_code=0) 
  {
      $flag =1;
      $errorType=$$message = '';
      $today_date=date('Y-m-d');
      $CI = &get_instance();
      $candidate_data = $CI->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");   
      if($candidate_data[0]['kyc_status']!=2 || $candidate_data[0]['is_active']!=1 ) {
        $flag = 0;
        $errorType='kyc';
        $message = 'Please wait until KYC Process done for your documents';
      }
      if($program_code>0) {

        $check_eligibility_for_training = $CI->master_model->getRecords('ncvet_training_eligible', array(
                    'ncvet_training_eligible.program_code' => $program_code,
                    'member_no'                 => $candidate_data[0]['regnumber'],
        ));
        //echo $CI->db->last_query();exit;
        if(count($check_eligibility_for_training)>0) {
         


            $CI->db->join('ncvet_training_activation', 'ncvet_training_activation.program_code=ncvet_training_registrations.program_code');
            
            $CI->db->where('ncvet_training_registrations.status', '1');
            
            $CI->db->where("'$today_date' BETWEEN ncvet_training_activation.from_date AND ncvet_training_activation.to_date");
            

            $applied_training_info = $CI->master_model->getRecords('ncvet_training_registrations', array(
              'ncvet_training_registrations.program_code' => $program_code,'regnumber' => $candidate_data[0]['regnumber']));
            if (isset($applied_training_info) && count($applied_training_info) > 0) {
              $flag = 0;
              $errorType='alreadyappliedfortraining';
              $message = 'You have already applied for Training';
            }
          
        }
        else
        {
          $flag = 0;
          $errorType='trainingligible';
          $message = 'You are not eligible for selected Training';
        }
 
         
      }

      
      return array('flag'=>$flag,'errorType'=>$errorType,'message'=>$message,'candidate_data'=>$candidate_data);
  }
  function ncvet_gettrainingfee($regnumber,$program_code) {
    $CI = &get_instance();
    $eligible_data = $CI->master_model->getRecords('ncvet_training_eligible', array(
                    'ncvet_training_eligible.program_code' => $program_code,
                    'member_no'                 => $regnumber,
        ));
      
      
    return $eligible_data[0]['fees'];

  }