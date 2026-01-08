<?php if (!defined('BASEPATH'))
{
  exit('No direct script access alloed');
}

//CREATED BY SAGAR ON 2024-09-25
//DESCRIPTION : THIS MODEL IS USED TO VERIFY CSC RESPONSE AND CHECK TRANSACTION STATUS THROUGH QUERY API
//MODIFIED DATE : 2022-06-09
class Csc_pg_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('master_model');
  }

  public function get_client_ip_csc()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  //START : CSC QRY API (CHECK TRANSACTION STATUS) CREATED BY SAGAR ON 25-09-2024.
  function csc_qry_api_model($Receipt_no)
  {    
    require_once FCPATH . "/BridgePG/PHP_BridgePG/BridgePGUtil.php";// include the BridgePGUtil file here 
    $bconn = new BridgePGUtil();

		// Prepare JSON Post Data
		$data['merchant_id'] = $merchant_id = '69910';
		$data['merchant_txn'] = $merchant_txn = $Receipt_no;
		$data['csc_txn'] = $csc_txn = 'N';
    
    if (strpos(base_url(), '/staging') !== false) 
    {
      '<br>URL : '.$url = "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status";
    } 
    else 
    {
      '<br>URL : '.$url = "https://bridge.csccloud.in/v2/transaction/status";
    }
    
    ////echo '<pre>'; print_r($data); echo '</pre>';

		$message_text = '';
    foreach ($data as $p => $v)
    {
      $message_text .= $p . '=' . $v . '|';
    }
    //echo '<br>message_text : '.$message_text.'<br>';

		$message_cipher = $bconn->encrypt($message_text);
    ////echo'<br>message_cipher : '.$message_cipher;
		//  echo'<br>dec='.$bconn->decrypt($message_cipher);;
		
    $json_data_array = array(
		'merchant_id' => $merchant_id,
		'request_data' => $message_cipher
		);

		$post = json_encode($json_data_array);
		//https://bridge.csccloud.in/v2/transaction/status
		// cURL Request starts here
		$curl = curl_init();
		$headers = array('Content-Type: application/json');
		curl_setopt_array($curl, array
    (
			CURLOPT_RETURNTRANSFER => 1,
			// CURLOPT_URL => "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status",
			CURLOPT_URL => $url,
			CURLOPT_VERBOSE => true,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $headers,
			CURLINFO_HEADER_OUT => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
			//CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POST => 1,
			CURLOPT_TIMEOUT => 30,
			//CURLOPT_FOLLOWLOCATION => 1,      
			CURLOPT_POSTFIELDS => $post
		));
    $server_output = curl_exec($curl);

		$info = curl_getinfo($curl);
    if (curl_errno($curl))
    {
			$error_msg = curl_error($curl);
			////echo '<br><br>Error Message : '.$error_msg;
		}
    
    ////echo '<pre> original response : '; print_r($server_output); echo '</pre>';
    if ($server_output)
    {
			$xml_response = simplexml_load_string($server_output); 
			$p = $bconn->decrypt($xml_response->response_data);
			
			$p = explode('|',  $p);
			
			$fine_params = array();
      foreach ($p as $param)
      {
				$param = explode('=', $param);
        if (isset($param[0]))
        {
          if (isset($param[1]))
					$fine_params[$param[0]] = $param[1];
				}
			}
			$p = $fine_params;

			$xml_response = (array) $xml_response;
      //echo '<pre>response='; print_r($p);
      
      if(count($p) > 0) { return $p; }
      else
      { 
        $xml_response = new SimpleXMLElement($server_output);
        $jsonString_xml_response = json_encode($xml_response);
        $xml_response_array = json_decode($jsonString_xml_response, true);
        return $xml_response_array;
      }
    }
  }//END : CSC QRY API (CHECK TRANSACTION STATUS) CREATED BY SAGAR ON 25-09-2024.

  //START : CSC INITIATE REVERSE TRANSACTION API. THIS IS USE FOR SAME DAY TRANSACTION. CREATED BY SAGAR ON 25-09-2024.
  function csc_reverse_api_model($Receipt_no)
  {    
    $chk_payment = $this->csc_qry_api_model($Receipt_no);    
    if(is_array($chk_payment) && isset($chk_payment['txn_status']) && $chk_payment['txn_status'] == '100')//INITIATE REVERSE
    {
      require_once FCPATH . "/BridgePG/PHP_BridgePG/BridgePGUtil.php";// include the BridgePGUtil file here 
      $bconn = new BridgePGUtil();
  
      // Prepare JSON Post Data
      $data['merchant_id'] = $merchant_id = '69910';
      $data['merchant_txn'] = $merchant_txn = $Receipt_no;
      $data['merchant_txn_datetime'] = $merchant_txn_datetime = $chk_payment['creation_date'];
      
      if (strpos(base_url(), '/staging') !== false) 
      {
        '<br>URL : '.$url = "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/reverse";
      } 
      else 
      {
        '<br>URL : '.$url = "https://bridge.csccloud.in/v2/transaction/reverse";
      }
  
      ////echo '<pre>'; print_r($data); echo '</pre>';
  
      $message_text = '';
      foreach ($data as $p => $v)
      {
        $message_text .= $p . '=' . $v . '|';
      }
      ////echo '<br>message_text : '.$message_text.'<br>';
  
      $message_cipher = $bconn->encrypt($message_text);
      ////echo'<br>message_cipher : '.$message_cipher;
      //  echo'<br>dec='.$bconn->decrypt($message_cipher);;
      
      $json_data_array = array(
      'merchant_id' => $merchant_id,
      'request_data' => $message_cipher
      );
  
      $post = json_encode($json_data_array);
      //https://bridge.csccloud.in/v2/transaction/status
      // cURL Request starts here
      $curl = curl_init();
      $headers = array('Content-Type: application/json');
      curl_setopt_array($curl, array
      (
        CURLOPT_RETURNTRANSFER => 1,
        // CURLOPT_URL => "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status",
        CURLOPT_URL => $url,
        CURLOPT_VERBOSE => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => $headers,
        CURLINFO_HEADER_OUT => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
        //CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POST => 1,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_FOLLOWLOCATION => 1,      
        CURLOPT_POSTFIELDS => $post
      ));
      $server_output = curl_exec($curl);
  
      $info = curl_getinfo($curl);
      if (curl_errno($curl))
      {
        $error_msg = curl_error($curl);
        ////echo '<br><br>Error Message : '.$error_msg;
      }
      
      ////echo '<pre> original response : '; print_r($server_output); echo '</pre>';
      if ($server_output)
      {
        $xml_response = simplexml_load_string($server_output); 
        $p = $bconn->decrypt($xml_response->response_data);
        
        $p = explode('|',  $p);
        
        $fine_params = array();
        foreach ($p as $param)
        {
          $param = explode('=', $param);
          if (isset($param[0]))
          {
            if (isset($param[1]))
            $fine_params[$param[0]] = $param[1];
          }
        }
        $p = $fine_params;
  
        $xml_response = (array) $xml_response;
        //echo '<pre>response='; print_r($p);
        
        if(count($p) > 0) { return $p; }
        else
        { 
          $xml_response = new SimpleXMLElement($server_output);
          $jsonString_xml_response = json_encode($xml_response);
          $xml_response_array = json_decode($jsonString_xml_response, true);
          return $xml_response_array;
        }
      }
    }
    else
    {
      print_r($chk_payment);
    }

  }//END : CSC INITIATE REVERSE TRANSACTION API. THIS IS USE FOR SAME DAY TRANSACTION. CREATED BY SAGAR ON 25-09-2024.
  
  //START : CSC INITIATE REFUND TRANSACTION API. THIS IS USE FOR BACK DATED TRANSACTION. CREATED BY SAGAR ON 25-09-2024.
  function csc_refund_api_model($Receipt_no)
  {  
    $chk_payment = $this->csc_qry_api_model($Receipt_no);    
    if(is_array($chk_payment) && isset($chk_payment['txn_status']) && $chk_payment['txn_status'] == '100')//INITIATE REFUND
    {
      require_once FCPATH . "/BridgePG/PHP_BridgePG/BridgePGUtil.php";// include the BridgePGUtil file here 
      $bconn = new BridgePGUtil();
  
      // Prepare JSON Post Data
      $data['merchant_id'] = $merchant_id = '69910';
      $data['csc_txn'] = $csc_txn = $chk_payment['csc_txn'];
      $data['merchant_txn'] = $merchant_txn = $Receipt_no;
      $data['merchant_txn_param'] = $merchant_txn_param = 'N';
      $data['merchant_txn_status'] = $merchant_txn_status = 'Success';
      $data['merchant_reference'] = $merchant_reference = $chk_payment['csc_id'];////
      $data['refund_deduction'] = $refund_deduction = $chk_payment['txn_amount'];
      $data['refund_mode'] = $refund_mode = 'F';
      $data['refund_type'] = $refund_type = 'R';
      $data['refund_trigger'] = $refund_trigger = 'M';
      $data['refund_reason'] = $refund_reason = 'unable to deliver service';
      
      if (strpos(base_url(), '/staging') !== false) 
      {
        '<br>URL : '.$url = "https://bridgeuat.csccloud.in/cscbridge/v2/refund/log";
      } 
      else 
      {
        '<br>URL : '.$url = "https://bridge.csccloud.in/v2/refund/log";
      }
  
      ////echo '<pre>'; print_r($data); echo '</pre>';
  
      $message_text = '';
      foreach ($data as $p => $v)
      {
        $message_text .= $p . '=' . $v . '|';
      }
      ////echo '<br>message_text : '.$message_text.'<br>';
  
      $message_cipher = $bconn->encrypt($message_text);
      ////echo'<br>message_cipher : '.$message_cipher;
      //  echo'<br>dec='.$bconn->decrypt($message_cipher);;
      
      $json_data_array = array(
      'merchant_id' => $merchant_id,
      'request_data' => $message_cipher
      );
  
      $post = json_encode($json_data_array);
      //https://bridge.csccloud.in/v2/transaction/status
      // cURL Request starts here
      $curl = curl_init();
      $headers = array('Content-Type: application/json');
      curl_setopt_array($curl, array
      (
        CURLOPT_RETURNTRANSFER => 1,
        // CURLOPT_URL => "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status",
        CURLOPT_URL => $url,
        CURLOPT_VERBOSE => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => $headers,
        CURLINFO_HEADER_OUT => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
        //CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POST => 1,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_FOLLOWLOCATION => 1,      
        CURLOPT_POSTFIELDS => $post
      ));
      $server_output = curl_exec($curl);
      
      $info = curl_getinfo($curl);
      if (curl_errno($curl))
      {
        $error_msg = curl_error($curl);
        ////echo '<br><br>Error Message : '.$error_msg;
      }
      
      //echo '<pre> original response : '; print_r($server_output); echo '</pre>';
      if ($server_output)
      {
        $xml_response = simplexml_load_string($server_output); 
        $p = $bconn->decrypt($xml_response->response_data);
        
        $p = explode('|',  $p);
        
        $fine_params = array();
        foreach ($p as $param)
        {
          $param = explode('=', $param);
          if (isset($param[0]))
          {
            if (isset($param[1]))
            $fine_params[$param[0]] = $param[1];
          }
        }
        $p = $fine_params;
  
        $xml_response = (array) $xml_response;
        //echo '<pre>response='; print_r($p);
        
        if(count($p) > 0) 
        { 
          $api_result = $p;
          if(isset($api_result['refund_status']) && $api_result['refund_status'] == 'Success')
          {
            //INSERT LOG IN DATABASE TABLE
            $this->load->helper('url');
            $add_data = array();
            $add_data['refund_status'] = $api_result['refund_status'];
            $add_data['merchant_id'] = $api_result['merchant_id'];
            $add_data['merchant_txn'] = $api_result['merchant_txn'];
            $add_data['merchant_reference'] = $api_result['merchant_reference'];
            $add_data['refund_reference'] = $api_result['refund_reference'];
            $add_data['csc_txn'] = $api_result['csc_txn'];
            $add_data['refund_url'] = current_url();
            $add_data['ip_address'] = get_ip_address(); //general_helper.php
            $add_data['refund_initiated_on'] = date('Y-m-d H:i:s');
            $this->master_model->insertRecord('csc_refund_log',$add_data);
          }

          return $p; 
        }
        else
        { 
          $xml_response = new SimpleXMLElement($server_output);
          $jsonString_xml_response = json_encode($xml_response);
          $xml_response_array = json_decode($jsonString_xml_response, true);
          return $xml_response_array;
        }
      }
    }
    else
    {
      print_r($chk_payment);
    }
  }//END : CSC INITIATE REFUND TRANSACTION API. THIS IS USE FOR BACK DATED TRANSACTION. CREATED BY SAGAR ON 25-09-2024.
  

  //START : CSC REFUND STATUS API. CREATED BY SAGAR ON 25-09-2024.
  function csc_refund_status_api_model($Receipt_no='', $refund_reference='')
  {    
    $chk_payment = $this->csc_qry_api_model($Receipt_no);    
    if(is_array($chk_payment) && isset($chk_payment['txn_status']) && $chk_payment['txn_status'] == '100')//INITIATE REFUND
    {
      require_once FCPATH . "/BridgePG/PHP_BridgePG/BridgePGUtil.php";// include the BridgePGUtil file here 
      $bconn = new BridgePGUtil();
  
      // Prepare JSON Post Data
      $data['merchant_id'] = $merchant_id = '69910';
      $data['csc_txn'] = $csc_txn = $chk_payment['csc_txn'];
      $data['merchant_txn'] = $merchant_txn = $Receipt_no;
      $data['refund_reference'] = $refund_reference;
      
      if (strpos(base_url(), '/staging') !== false) 
      {
        '<br>URL : '.$url = "https://bridgeuat.csccloud.in/cscbridge/v2/refund/status";
      } 
      else 
      {
        '<br>URL : '.$url = "https://bridge.csccloud.in/v2/refund/status";
      }
  
      ////echo '<pre>'; print_r($data); echo '</pre>';
  
      $message_text = '';
      foreach ($data as $p => $v)
      {
        $message_text .= $p . '=' . $v . '|';
      }
      ////echo '<br>message_text : '.$message_text.'<br>';
  
      $message_cipher = $bconn->encrypt($message_text);
      ////echo'<br>message_cipher : '.$message_cipher;
      //  echo'<br>dec='.$bconn->decrypt($message_cipher);;
      
      $json_data_array = array(
      'merchant_id' => $merchant_id,
      'request_data' => $message_cipher
      );
  
      $post = json_encode($json_data_array);
      //https://bridge.csccloud.in/v2/transaction/status
      // cURL Request starts here
      $curl = curl_init();
      $headers = array('Content-Type: application/json');
      curl_setopt_array($curl, array
      (
        CURLOPT_RETURNTRANSFER => 1,
        // CURLOPT_URL => "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status",
        CURLOPT_URL => $url,
        CURLOPT_VERBOSE => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => $headers,
        CURLINFO_HEADER_OUT => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
        //CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POST => 1,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_FOLLOWLOCATION => 1,      
        CURLOPT_POSTFIELDS => $post
      ));
      $server_output = curl_exec($curl);
  
      $info = curl_getinfo($curl);
      if (curl_errno($curl))
      {
        $error_msg = curl_error($curl);
        ////echo '<br><br>Error Message : '.$error_msg;
      }
      
      //echo '<pre> original response : '; print_r($server_output); echo '</pre>';
      if ($server_output)
      {
        $xml_response = simplexml_load_string($server_output); 
        $p = $bconn->decrypt($xml_response->response_data);
        
        $p = explode('|',  $p);
        
        $fine_params = array();
        foreach ($p as $param)
        {
          $param = explode('=', $param);
          if (isset($param[0]))
          {
            if (isset($param[1]))
            $fine_params[$param[0]] = $param[1];
          }
        }
        $p = $fine_params;
  
        $xml_response = (array) $xml_response;
        //echo '<pre>response='; print_r($p);
        
        if(count($p) > 0) { return $p; }
        else
        { 
          $xml_response = new SimpleXMLElement($server_output);
          $jsonString_xml_response = json_encode($xml_response);
          $xml_response_array = json_decode($jsonString_xml_response, true);
          return $xml_response_array;
        }
      }
    }
    else
    {
      print_r($chk_payment);
    }
  }//END : CSC REFUND STATUS API. CREATED BY SAGAR ON 25-09-2024.





  function common_curl_for_api($url='', $data=array())
  {
    require_once FCPATH . "/BridgePG/PHP_BridgePG/BridgePGUtil.php";// include the BridgePGUtil file here 
    $bconn = new BridgePGUtil();

    echo '<br>'.$url;
    echo '<pre>'; print_r($data); echo '</pre>';

    $message_text = '';
    foreach ($data as $p => $v)
    {
      $message_text .= $p . '=' . $v . '|';
    }
    echo '<br>message_text : '.$message_text.'<br>';

    $message_cipher = $bconn->encrypt($message_text);
    echo'<br>message_cipher : '.$message_cipher;
    //  echo'<br>dec='.$bconn->decrypt($message_cipher);;
    
    $json_data_array = array(
    'merchant_id' => $merchant_id,
    'request_data' => $message_cipher
    );

    $post = json_encode($json_data_array);
    //https://bridge.csccloud.in/v2/transaction/status
    // cURL Request starts here
    $curl = curl_init();
    $headers = array('Content-Type: application/json');
    curl_setopt_array($curl, array
    (
      CURLOPT_RETURNTRANSFER => 1,
      // CURLOPT_URL => "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status",
      CURLOPT_URL => $url,
      CURLOPT_VERBOSE => true,
      CURLOPT_HEADER => false,
      CURLOPT_HTTPHEADER => $headers,
      CURLINFO_HEADER_OUT => false,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
      //CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POST => 1,
      CURLOPT_TIMEOUT => 30,
      //CURLOPT_FOLLOWLOCATION => 1,      
      CURLOPT_POSTFIELDS => $post
    ));
    $server_output = curl_exec($curl);

    $info = curl_getinfo($curl);
    if (curl_errno($curl))
    {
      $error_msg = curl_error($curl);
      echo '<br><br>Error Message : '.$error_msg;
    }
    
    //echo '<pre> original response : '; print_r($server_output); echo '</pre>';
    if ($server_output)
    {
      $xml_response = simplexml_load_string($server_output); 
      $p = $bconn->decrypt($xml_response->response_data);
      
      $p = explode('|',  $p);
      
      $fine_params = array();
      foreach ($p as $param)
      {
        $param = explode('=', $param);
        if (isset($param[0]))
        {
          if (isset($param[1]))
          $fine_params[$param[0]] = $param[1];
        }
      }
      $p = $fine_params;

      $xml_response = (array) $xml_response;
      //echo '<pre>response='; print_r($p);
      
      if(count($p) > 0) { return $p; }
      else
      { 
        $xml_response = new SimpleXMLElement($server_output);
        $jsonString_xml_response = json_encode($xml_response);
        $xml_response_array = json_decode($jsonString_xml_response, true);
        return $xml_response_array;
      }
    }
  }  
}
