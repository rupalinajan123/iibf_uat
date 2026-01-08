<?php 
	/********************************************************************
		* Description	: Model for generating captcha images
		* Created BY	: Sagar Matale On 24-08-2021
		* Update By		: Sagar Matale on 24-08-2021
	********************************************************************/
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Captcha_model extends CI_Model 
	{
		function __construct()
    {
			parent::__construct();
		}	
		
		function generate_captcha_img_bk($session_name='', $path='')
		{
			$this->load->helper('string');
			$this->load->helper('captcha');
			
			if($session_name == '') { $session_name = 'regcaptcha'; }
			if($path == '') { $path = 'uploads/applications/'; }
			
			//$cap_word = strtoupper(random_string('alpha', 5));
			//$this->session->unset_userdata($session_name);
			$cap_word = $this->generate_random_string(5);
			$this->session->set_userdata($session_name, rand(1, 100000));
			
			$vals['word'] = $cap_word;
			$vals['img_path'] = './'.$path;
			$vals['img_url'] = base_url().$path;
						
			$cap_img = create_captcha($vals);
			$_SESSION[$session_name] = $cap_img['word'];
			
			return $cap_img['image'];
		}
		
		function generate_captcha_img($session_name='', $path='')
		{
			$this->load->helper('string');
			
			if($session_name == '') { $session_name = 'regcaptcha'; }
			if($path == '') { $path = 'uploads/applications/'; }
			
			$cap_word = $this->generate_random_string(5);			
			$_SESSION[$session_name] = $cap_word;
			
			$wd_ht_arr = array('50%','60%','70%','80%','90%','100%','110%','120%','130%','140%','150%');
			$random_keys = array_rand($wd_ht_arr,2);
			return '	<style>
									.CaptchaBgText { position: relative; width: 150px; height: 30px; background-image: url('.site_url("assets/images/captcha_bg.png").'); background-size: '.$wd_ht_arr[$random_keys[0]].' '.$wd_ht_arr[$random_keys[1]].'; border: 1px solid #A2A2A2; background-color: #b7d2ed; }
									
									.CaptchaBgText::after { content: "'.$cap_word.'"; position: absolute; color: #10275b; top: 0; left: 0; width: 150px; height: 30px; text-align: center; font-size: 15px; font-weight: 600; letter-spacing: 12px; overflow: hidden; line-height: 28px; }
								</style>
								<div class="CaptchaBgText"></div>';
		}
		
		function generate_random_string($length=0)
		{
			$characters = 'ABCDEFGHJKLMNPQRTWXYZ';
			$randomString = '';
  
			for ($i = 0; $i < $length; $i++) 
			{
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}
  
			return $randomString;
		}
		
	}	