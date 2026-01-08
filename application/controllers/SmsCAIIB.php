<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SmsCAIIB extends CI_Controller {
	public function __construct()

	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		//exit;
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{		
				//$cron_URL_Staging='/usr/local/bin/php /home/iibfgrow/public_html/index.php Sms index';
				$data = $this->master_model->getRecords('sms_members_caiib',array('status'=>'0'),'',array('status'=>'ASC'),0,40000);
				if($data)
				{
					foreach($data as $row)
					{
							$mem_no = $row['mem_no'];
							$mobile=$row['mobile_no'];
							
							$text='CAIIB Registration extended till 10th May with normal fee and upto 12th May with late fee. Register before last date to appear in June 18 exam.
IIBF Mumbai';

							$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$mobile."&text=".urlencode($text)."&senderid=IIBFNM&route_id=2&Unicode=0";
							
							//for Unicode=1 character 1 to 70 take 1 sms count
							//for Unicode=0 character 1 to 160 characters take 1 sms count
							//1-160 characters = 1 SMS Credit. 161-306 characters = 2 SMS Credits
							/*
							for Unicode SMS 	Unicode=1
							for Non-Unicode SMS  	Unicode=0
							*/
							
							$string = preg_replace('/\s+/','', $url);

							$x = curl_init($string);

							curl_setopt($x, CURLOPT_HEADER, 0);	

							curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);

							curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			

							$reply = curl_exec($x);

							curl_close($x);
	
							$sms_array=array('respond'=>htmlspecialchars_decode($reply),'mobile'=>$mobile,'status'=>'success');
			
							if($sms_array['status']=='success')
							{
								$update_data = $this->master_model->updateRecord('sms_members_caiib',array('status'=>'1'),array('mem_no'=>$mem_no));
							}
							
						
						 
					}
				}
				//$this->insertRecord('sms_log',$inser_array);

				
					//$res = $this->sms_balance_notify($reply);
					
						//$this->master_model->send_sms('',$sms_final_str);	
				
	}
}