<?php

/********************************************************************************************************************
 ** Description: Controller for COMMON IMAGE PROCESS
 ** Created BY: Sagar Matale On 15-01-2025
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Image_process_global extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('master_model');
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper');
    $this->load->helper('file');

    $this->file_path = 'uploads/tmp_processed_images';
  }
  
  public function index()
  {
    $data['file_path'] = $file_path = $this->file_path;
    $data['page_title'] = 'Image Process Global Link';
    $this->load->view('iibfbcbf/image_process_global', $data);
  }
  /******** END : ADD / UPDATE CANDIDATES DATA ********/

  /******** START : VALIDATION FUNCTION TO CHECK VALID FILE ********/
  function fun_validate_file_upload($str, $parameter) // Custom callback function for check valid file
  {
    $result = $this->Iibf_bcbf_model->fun_validate_file_upload($parameter);
    if ($result['flag'] == 'success')
    {
      return true;
    }
    else
    {
      $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
      return false;
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK VALID FILE ********/
}
