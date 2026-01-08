<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class RecoveryEmails extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
    }
    public function index(){ 
	
$emailArray = array('sameerkhanmalik@gmail.com');

//$emailArray = array('bhushan.amrutkar@esds.co.in');


        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'remaining_fee'),'','','1');
		//echo "<pre>"; print_r($emailArray); echo "</pre>";
		foreach($emailArray as $email){
			if (count($emailerstr) > 0) { 
				$info_arr   = array(
					'to' => $email,
					'from' => $emailerstr[0]['from'],
					'subject' => $emailerstr[0]['subject'],
					'message' => $emailerstr[0]['emailer_text']);
			}
			$attachpath = '';
			echo "<br>".$email." | Email Send => ".$this->Emailsending->mailsend_attch($info_arr, $attachpath);
		}
	}
}
?>