<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SessionController extends CI_Controller {

    public function __construct() {
        parent:: __construct();

        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() 
    {
        
        $newdata = array( 
                       
                       'from_date' => $from_date,
                       'to_date' => $to_date
                    );  

        //$this->load->view('sessions/index');
        $this->session->set_userdata($newdata);
        /*echo $newdata;exit;*/
        $this->load->view('admin/admin_combo');
    }
}