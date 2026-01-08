<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Billdesk_refund_status extends CI_Controller {
			
	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('Master_model');
		
	}
	public function index()
	{	
        $MerchantOrderNo='903870476';
    	//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
                'alg' => $this->config->item('BD_ALG'), //'HS256', 
                'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
                );
                $key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
                
                echo $service_url = $this->config->item('BD_REFUND_STATUS_URL');exit;
                $mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
                $orderId  = $MerchantOrderNo;//'900485161';  Test number
                $payload = [
                "mercid"     => $mercid,
                "orderid"    => $orderId
                ];
                $random_trace = substr(md5(mt_rand()), 0, 7);
                $bd_traceid = time() .$random_trace;
                $bd_timestamp =time();
                
                $jws = new \Gamegos\JWS\JWS();	
                $jwsString = $jws->encode($headers, $payload, $key);		
                $ch = curl_init();       
                curl_setopt($ch, CURLOPT_URL,$service_url);
                // Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/jose',
                'accept: application/jose',
                'bd-traceid: ' . $bd_traceid,
                'bd-timestamp: ' . $bd_timestamp)
                );
                curl_setopt($ch, CURLOPT_POST, true); 
                curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $info = curl_getinfo($ch);
                $result = curl_exec($ch);
                curl_close($ch);
                
                $txn_query_result = $jws->verify($result, $key);
                print_r($txn_query_result['payload']);
			
	}
}	
?>
