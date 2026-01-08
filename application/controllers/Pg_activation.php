<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pg_activation extends CI_Controller 
{    
	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('Master_model'); 
    $this->load->model('log_model');
    $this->load->model('Emailsending');
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    ini_set("memory_limit", "-1");
	}
	
	function main($status=1)
	{

			
			if ($status==1 || $status==0) {

				$this->master_model->updateRecord('payment_gateway_activation',array('status'=>$status,'ip_address'=>$this->get_client_ip()),array('id'=>1));
				echo $this->db->last_query();
				echo "<br>";
				$pg_data = $this->Master_model->getRecords('payment_gateway_activation',array('id'=>1),'','',0,1);	
				echo 'current_db_status:'.$pg_data[0]['status'];
				echo "<br>";
				if (count($pg_data) && $pg_data[0]['status']==1) {
					echo "Payment gateway is active";
				}elseif (count($pg_data) && $pg_data[0]['status']==0) {
						echo "Payment gateway is inactive";
				}
			}else{
				echo "Invalid Status";
			}
			
			
		
	}

	 function get_client_ip() {
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
