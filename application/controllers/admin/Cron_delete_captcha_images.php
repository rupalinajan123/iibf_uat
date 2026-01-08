<?php 
	/********************************************************************
		* Description	: Cron Controller to delete captcha images from server
		* Created BY	: Sagar Matale On 24-08-2021
		* Update By		: Sagar Matale on 24-08-2021
		* Update By		: Sagar Matale on 25-08-2021
	********************************************************************/
	
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Cron_delete_captcha_images extends CI_Controller
  {
		public function __construct()
    {
      parent::__construct();   
			$this->load->helper('directory');
			$this->load->helper('file');
		}
    
    public function index() 
		{
			$dir_name_arr = array('uploads/applications/', 'uploads/applications2/');
			
			if(count($dir_name_arr) > 0)
			{
				foreach($dir_name_arr as $dir_name)
				{
					if(is_dir($dir_name))
					{
						delete_files($dir_name, true); // delete all files/folders
					}
				}
			}
		}
	}  		