<?php 
  /********************************************************************************************************************
  ** Description: Controller for DELETE THE UNWANTED FILES FROM SERVER
  ** Created BY: Sagar Matale On 16-07-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Delete_unwanted_folders extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file');      
		}

    function index()
    {
      $this->delete_old_folders();
    }


    function delete_old_folders() //START: GET ALL FOLDER LIST FROM BELOW FOLDERS AND DELETE UNWANTED FOLDERS
    {
      //START: GET ALL FOLDER LIST FROM BELOW FOLDERS AND DELETE UNWANTED FOLDERS
      $folder_arr = array('system/cache', 'system/cache2');   
      //_pa($folder_arr);
      
      $not_delete_folder_global_arr = array();

      //START : DELETE ALL FOLDERS EXCEPT LAST 5 DAYS FOR 'system/cache'
      for($i = 0; $i<5; $i++)
      {
        $not_delete_folder_global_arr['system/cache'][] = 'sessions_'.date('Y-m-d', strtotime("-".$i."days"));
      }//END : DELETE ALL FOLDERS EXCEPT LAST 5 DAYS FOR 'system/cache'

      //FOR REFERENCE : START : DELETE ALL FOLDERS EXCEPT LAST 2 DAYS FOR 'system_cache2'
      /* for($i = 0; $i<2; $i++)
      {
        $not_delete_folder_global_arr['system/cache2'][] = 'test_'.date('Ymd', strtotime("-".$i."days"));
      } *///END : DELETE ALL FOLDERS EXCEPT LAST 2 DAYS FOR 'system_cache2'

      //_pa($not_delete_folder_global_arr);
      
      foreach($folder_arr as $dir_name)
      {
        $not_delete_folder_arr = $not_delete_folder_global_arr[$dir_name];
        ////echo '<br> dir_name : '.$dir_name;
        ////_pa($not_delete_folder_arr);
        
        //$baseDir = '/path/to/your/base/directory';
        $baseDir = rtrim($dir_name, '/') . '/';// Ensure the base directory ends with a slash      
        $directories = glob($baseDir . '*', GLOB_ONLYDIR);// Get all directories in the base directory
        
        ////_pa($directories);
        foreach ($directories as $dir) 
        {
          $dirName = basename($dir);// Get the base name of the directory
          if (!in_array($dirName, $not_delete_folder_arr)) // Check if the directory is not "temp"
          {
            // Recursively delete the directory
            delete_files($dir, TRUE); // Delete all files inside
            rmdir($dir); // Remove the directory itself
          }
        }
      }//END: GET ALL FOLDER LIST FROM FOLDERS AND DELETE ALL DATA EXCEPT LAST 5 DAYS
      
    }
  }