<?php 
  /********************************************************************************************************************
  ** Description: Controller for STORING THE CLICK COUNT
  ** Created BY: Sagar Matale On 05-03-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Click_count extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();      
      $this->load->model('master_model');
		}

    public function index() 
    { 
      $add_data = array();
      $add_data['module_name'] = 'Email Redirection';
      $add_data['description'] = 'JAIIB Email Redirection';
      $add_data['ip_address'] = $this->get_client_ip_master();
      $add_data['created_on'] = date("Y-m-d H:i:s");
      $inser_id = $this->master_model->insertRecord('tbl_click_count', $add_data, true);
      redirect('https://bookscape.com/themes/iibf-learning-resources');
    }

    public function save_count() 
    { 
      $result['flag'] = "error";
      if(isset($_POST) && $_POST['module_name'] != "")
			{
        $add_data = array();
        $add_data['module_name'] = $_POST['module_name'];
        $add_data['description'] = $_POST['description'];
        $add_data['ip_address'] = $this->get_client_ip_master();
        $add_data['created_on'] = date("Y-m-d H:i:s");
        $inser_id = $this->master_model->insertRecord('tbl_click_count', $add_data, true);
        if(count($inser_id) > 0)
        {
          $result['flag'] = "success";
        }
			} 
			
      echo json_encode($result);
    }

    public function get_client_ip_master() {
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			   $ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
  }