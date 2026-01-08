<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class GstRecoverycustom extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('gstrecovery_custom_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
    }
	
    public function doc_no()
    {
        echo $gen_doc_no = generate_gst_recovery_invoice_number(); // put ref_id as parameter or put gst_recovery_details table pk
    }

    public function invoice()
    {
        echo $path = genarate_gst_recovery_invoice(4032);  // put ref_id as parameter or put gst_recovery_details table pk
				//uploads/gst_recovery_invoice/user/500039779_.jpg
    }
}