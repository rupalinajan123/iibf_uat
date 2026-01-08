<?php

/********************************************************************************************************************
 ** Description: Common Helper for DRA Module
 ** Created BY: Gaurav Shewale On 15-11-2024
 ********************************************************************************************************************/
defined('BASEPATH') || exit('No Direct Allowed Here');

function generate_captcha($session_captcha_name = '', $length = '4')
/******** START : GENERATE CAPTCHA IMAGE ********/
{
  /* $upload_path = './uploads/captcha/'.CURRENTDATE.'/';    
    create_directories($upload_path);
    
    $vals = array( 'img_path' => $upload_path, 'img_url' => base_url().$upload_path, 'img_width' => '150', 'img_height' => '32', 'font_size' => 35, 'pool' => 'ABCDEFGHJKMNPQRSTWXYZ23456789', 'word_length'=>$length, 'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(0, 0, 0),
                'text' => array(0, 0, 0),
                'grid' => array(255, 200, 200)
        ));  
    $captcha = create_captcha($vals);
    $_SESSION[$session_captcha_name] = $captcha['word'];
    return $captcha['image']; */

  $cap_word = generate_random_string(6);
  $_SESSION[$session_captcha_name] = $cap_word;

  $wd_ht_arr = array('20%', '25%', '30%', '35%', '40%', '45%');
  $transform_arr = array('1deg', '2deg', '3deg', '4deg', '5deg', '-1deg', '-2deg', '-3deg', '-4deg', '-5deg');
  $bg_position_arr = array('bottom', 'centre', 'top');
  $random_keys = array_rand($wd_ht_arr, 2);
  $random_transform_keys = array_rand($transform_arr, 2);
  $random_bg_position_keys = array_rand($bg_position_arr, 2);

  return '	<style>
								.CaptchaBgText { position: relative; width: 130px; height: 35px; background-image: url(' . base_url("assets/iibfbcbf/images/captcha_bg.png") . '); background-size: ' . $wd_ht_arr[$random_keys[0]] . '; border: 1px solid #A2A2A2; background-color: #ccc; background-position:' . $bg_position_arr[$random_bg_position_keys[0]] . '}
								
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

function show_log_date($date)
/******** START : SHOW LOG DATE FORMAT ********/
{
  return date("d M Y, h:i A", strtotime($date));
}
/******** END : SHOW LOG DATE FORMAT ********/

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

function _pq($exit_flag = 0)
{
  $ci = &get_instance();
  echo $ci->db->last_query();
  if ($exit_flag == '1')
  {
    exit();
  }
}