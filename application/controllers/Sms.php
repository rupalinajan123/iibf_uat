<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {
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
				$data = $this->master_model->getRecords('sms_members_second_round',array('status'=>'0'),'',array('status'=>'ASC'),0,1);
				if($data)
				{
					foreach($data as $row)
					{
						$mem_no = $row['mem_no'];
						$mobile_num = $this->master_model->getRecords('member_registration',array('regnumber'=>$mem_no,'isactive'=>'1'),'mobile');
						
						if(count($mobile_num) > 0)
						{
							$mobile=$mobile_num[0]['mobile'];
							$text='Dear Member,
Registration time for JAIIB extended till 10 Apr with normal fee and 13 Apr with late fee. Pls register before last date to appear in May 18 exam';

							$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile='7666135365'&text='hii'&senderid=IIBFNM&route_id=2&Unicode=0";
							
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
								$update_data = $this->master_model->updateRecord('sms_members_second_round',array('status'=>'1'),array('mem_no'=>$mem_no));
							}
							
						}
						 
					}
				}
				



				
	}



	public function testsms()
	{
		
		$mobile='9096241879';
		$text= 'test';

		$url ="https://api.trustsignal.io/v1/sms?api_key=XXXXXX";
							
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

							print_r($reply);

							curl_close($x);
	}


	public function send_sms_new($value='')
	{
		$api_key = '6cc49b51-5a2e-4e4d-a34a-ef5c2c203da2';
		
        ## Trust Signal Templates
		/*$data = array("sender_id" => "IIBFSM", "to" => [9689900343,9372758912] ,"message"=>"Thanks for enrolling for JAIIB for the 123 exam. Your exam form and fee 100 is received vide transaction 123, IIBF Team","route"=>"transactional","template_id"=>"7wQz5SwGR");*/

		## Template details provided by IIBF
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703,7588096918] ,"message"=>"Thanks for enrolling for JAIIB for the 123 exam. Your exam form and fee 100 is received vide transaction 123, IIBF Team","route"=>"transactional","template_id"=>"1vX-e_N7R");*/
		
		## One
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for JAIIB for the 123 exam. Your exam form and fee 100 is received vide transaction 123, IIBF Team","route"=>"transactional","template_id"=>"7wQz5SwGR");*/
		
		/*## Two
		$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for 1. Your fee 100 is received vide transaction 123, IIBF Team","route"=>"transactional","template_id"=>"C-48OSQMg");
		*/
		## Three
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully registered. IIBF Team","route"=>"transactional","template_id"=>"c1_uKIwMg");*/
		
		## Four
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully subscribe for IIBF vision.","route"=>"transactional","template_id"=>"c8MLFIwGg");*/
		
		## Five
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thx for your ordinary life membership registration. Your Membership No is 56887 AND your Membership Password is {#var#}. IIBF Team","route"=>"transactional","template_id"=>"DPDoOIwMR");*/
		
		## Six
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for DBF . Your exam form and fee 100 is received vide transaction 123. Registration is valid till you appear for the final test or 90 days whichever is earlier, IIBF Team","route"=>"transactional","template_id"=>"dvPQcIQGR");*/
		
		## Seven
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully registered for Certificate in Risk in Financial Services Level 2. IIBF Team","route"=>"transactional","template_id"=>"gewX5IwGR");*/
		
		## Eight
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Your have successfully register for Contact classes. IIBF Team","route"=>"transactional","template_id"=>"IO0yFSwGR");*/
		
		## Nine
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703,9372758912] ,"message"=>"Thx for your non member life membership registration. Your Registration No is 5676575 AND your Membership Password is test@123#$. IIBF Team","route"=>"transactional","template_id"=>"IZAU5IQGR");*/
				
		## Ten 
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Your Transaction for 2323 for the 345 Exam is NOT successful. Kindly apply again.IIBF Team","route"=>"transactional","template_id"=>"Jw6bOIQGg");*/
		
		## Eleven
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Dear Candidate, You have requested your login details from forgot password link in our site. The details are given below and have been also sent on respected mail id 123. Your Membership No is 11111 and Your password is tets@#$%^&123 Your Sincerely, IIBF Team","route"=>"transactional","template_id"=>"jYZ1dSwGR");*/
		
		## Twelve Not sent
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully subscribed for IIBF Finquest.","route"=>"transactional","template_id"=>"kyM2FIwMg");*/
		
		## Thirteen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for DRA exam. Your exam form and fee 100 is received vide transaction 123, IIBF Team","route"=>"transactional","template_id"=>"LSy_cIwGg");*/
		
		## Fourteen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thx for your renwal membership. Your Membership No is 56765 AND your Membership Password is 5$55~`!@&*._ IIBF Team","route"=>"transactional","template_id"=>"MQvtFIwMg");*/
		
		## Fifteen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for BCBF for the {101 exam. Your exam form and fee 500 is received vide transaction 111, IIBF Team","route"=>"transactional","template_id"=>"mUr3FSwGR");*/

		## Sixteen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully registered for Duplicate certificate. Your Duplicate Certificate will be dispatched within One month. IIBF Team","route"=>"transactional","template_id"=>"MVPWKSwGg");*/
		
		## Seventeen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for AMP exam. Your exam form and fee 345 is received vide transaction 888. Your membership number is 56767, IIBF Team","route"=>"transactional","template_id"=>"N8YRKIwMg");*/
		
		## Eighteen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for asas for the asa exam. Your exam form and fee 100 is received vide transaction 11, IIBF Team","route"=>"transactional","template_id"=>"oOmlKIQGR");*/
		
		## Ninteen
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for aaaa for the aabb exam. Your exam form and fee 500 is received vide transaction 122, IIBF Team","route"=>"transactional","template_id"=>"P6tIFIwGR");*/
		
		## Twenty
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully registered for CPD. IIBF Team","route"=>"transactional","template_id"=>"q3QoKSQGR");*/
		
		## Twenty one
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully subscribe for IIBF bankquest.","route"=>"transactional","template_id"=>"QeuxKIwMR");*/
		
		## Twenty two
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Dear Candidate, Plz check your email for the wrongly applied examination and revert at the earliest. If any issues, send email to onlineservices@iibf.org.in. IIBF Team
","route"=>"transactional","template_id"=>"r29rOSwMR");*/

		## Twenty three
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thx for your ordinary life membership registration. Your Membership No is {#var#} AND your Membership Password is {#var#}. IIBF Team","route"=>"transactional","template_id"=>"rP53cIwMR");*/
		
		## Twenty four
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully registered for DISA certificate. Certificate will be issued to within two months from the date of registration. IIBF Team","route"=>"transactional","template_id"=>"S8OmhSQGg");*/
		
		## Twenty five
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"Thanks for enrolling for {#var#} for the {#var#} exam. Your exam form and fee {#var#} is received vide transaction {#var#}, IIBF Team","route"=>"transactional","template_id"=>"vdGlOSwMR");*/
		
		## Twenty six
		/*$data = array("sender_id" => "IIBFCO", "to" => [9689900343,9881191703] ,"message"=>"You have successfully register for {#var#}.IIBF Team","route"=>"transactional","template_id"=>"Xb5EFSwGg");*/
					
		$data_string = json_encode($data);                                                                                   
		$ch = curl_init('https://api.trustsignal.io/v1/sms?api_key='.$api_key);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		$msg_res=json_decode($response,true);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
		/*
		Success response
		{"message":"Request process successfully","results":[{"phone":9881191703,"transaction_id":"3046908759579100"}],"success":true}
		*/
    }
  
	public function send_sms_new_sm()
	{
		$mobile_no = 9881191703;
		$message = "Thanks for enrolling for JAIIB for the 123 exam. Your exam form and fee 100 is received vide transaction 123, IIBF Team";
		$template_id = "7wQz5SwGR";
		
		$sms_response = $this->master_model->send_sms_new_model($mobile_no, $message, $template_id);
		echo '<pre>'; print_r($sms_response); echo '</pre>';
	}
	
    ### Test function added by pratibha
	public function stest()
	{
		$service_url = "https://www.sbiepay.sbi/payagg/RefundMISReport/refundEnquiryAPI";
		$merchIdVal = "1000169";
		$AggregatorId = "SBIEPAY";
		$atrn  = "8461191092835";
		$acrn   = "1966024713361";
		$arrn = "2310576333361";
		$queryRequest  = $acrn."|".$atrn."|".$merchIdVal;
		$queryRequest33 = http_build_query(array('queryRequest' => $queryRequest,"aggregatorId"=>"SBIEPAY","merchantId"=>$merchIdVal));
		$ch = curl_init($service_url);      
		//curl_setopt($ch, CURLOPT_SSLVERSION, true);
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		//curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $queryRequest33);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		$response = curl_exec ($ch);
		if (curl_errno($ch)) {
		echo $error_msg = curl_error($ch);
		}
		curl_close ($ch);
		echo $response;
	}
	

}