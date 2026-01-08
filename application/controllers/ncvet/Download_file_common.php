<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Download_file_common extends CI_Controller
{  
  public function __construct()
  {
    parent::__construct();       
    $this->load->model('master_model');
    $this->load->model('ncvet/Ncvet_model');
    $this->load->helper('ncvet/ncvet_helper');
    $this->load->helper('file');

    $this->training_schedule_file_path = 'uploads/ncvet/training_schedule';
    // $this->inspection_report_by_admin_file_path = 'uploads/ncvet/inspection_report_by_admin';
    // $this->inspection_attachment_path = 'uploads/ncvet/inspection_attachment';
  }

  public function index($enc_id='',$input_name='')
  {
    $id = url_decode($enc_id);

    $form_data = array();
    if(in_array($input_name, array('training_schedule_file', 'inspection_report_by_admin')))
    {
      $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_id' => $id, 'is_deleted' => '0'), $input_name);
    }
    else if(in_array($input_name, array('invoice_image')))
    {
      $form_data = $this->master_model->getRecords('ncvet_exam_invoice', array('invoice_id' => $id), $input_name);
    }
    
    if(count($form_data) == 0) { echo 'File not exist'; exit; }
    
    if($input_name == 'training_schedule_file')
    {
      download_file($this->training_schedule_file_path, $form_data[0][$input_name]); //helpers\ncvet\ncvet_helper.php
    }
    else if($input_name == 'invoice_image')
    {
      download_file('uploads/ncvet/reginvoice/user', $form_data[0][$input_name]); //helpers\ncvet\ncvet_helper.php
    }
  }
}
