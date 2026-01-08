<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Get_password extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			error_reporting(E_ALL);
		} 
		
		
		public function index($number='')
		{ 		
			if($number != "")
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$this->db->limit(1);
				$res = $this->master_model->getRecords('member_registration',array('regnumber'=>$number),'usrpassword');
				//$res = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id'=>$number),'password');
				//echo $this->db->last_query(); exit;
				//print_r($res);
				
				if(count($res) > 0)
				{
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					echo $decpass = $aes->decrypt(trim($res[0]['usrpassword']));

				}
				else
				{
					echo 'Invalid member number';
				}
			}
			else
			{
				echo 'Please add member number in url';
			}
		}
	}				