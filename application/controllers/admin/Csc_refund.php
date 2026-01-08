<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	
	class Csc_refund extends CI_Controller
	{    
		public function __construct()
		{ 
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('general_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model'); 
      
      date_default_timezone_set("Asia/Kolkata");
    }
    
    function index()
    {
      require_once FCPATH."/BridgePG/PHP_BridgePG/BridgePGUtil.php";
      $bconn = new BridgePGUtil ();
      
      $csc_txn = $merchant_txn = $txn_date = $refund_deduction = $refund_reason = '';
      if(isset($_POST) && count($_POST) > 0)
			{
				$this->form_validation->set_rules('csc_txn', 'CSC Transaction Number', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));		
				$this->form_validation->set_rules('merchant_txn', 'Merchant Transaction Number', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));			
				$this->form_validation->set_rules('txn_date', 'Transaction Date', 'trim|xss_clean');			
				$this->form_validation->set_rules('refund_deduction', 'Refund Deduction', 'trim|required|numeric|greater_than[0]|xss_clean',array('required' => 'Please enter the %s'));			
				$this->form_validation->set_rules('refund_reason', 'Refund Reason', 'trim|required|callback_check_special_character|xss_clean',array('required' => 'Please enter the %s'));			
				
				if($this->form_validation->run())
				{
					  //echo "<br>Posted Data<pre>"; print_r($_POST); echo "</pre>";//exit;
					  $csc_txn = $this->input->post('csc_txn');
					  $merchant_txn = $this->input->post('merchant_txn');
					  $txn_date = $this->input->post('txn_date');
					  $refund_deduction = $this->input->post('refund_deduction');
					  $refund_reason = $this->input->post('refund_reason');
				
					$add_data['txn_no'] = $csc_txn;
					$add_data['order_id'] = $merchant_txn;
					$add_data['txn_date'] = $txn_date;
					$add_data['txn_amt'] = $refund_deduction;
					$add_data['refund_reason'] = $refund_reason;
					$add_data['refund_date'] = date("Y-m-d H:i:s");
					$add_data['refund_status'] = '';
					$add_data['merchant_id'] = '';
					$add_data['merchant_txn'] = '';
					$add_data['merchant_reference'] = '';
					$add_data['refund_reference'] = '';
					$add_data['csc_txn'] = '';
					$add_data['created_on'] = date("Y-m-d H:i:s");
			//echo "<br>Insert Array<pre>"; print_r($add_data); echo "</pre>";
					$this->master_model->insertRecord("csc_refund_details",$add_data);
					$csc_refund_id = $this->db->insert_id();
					//echo "<br> Qry : ".$this->db->last_query();          
          //exit;
          $data = array();          
          // Prepare JSON Post Data
          $data['merchant_id'] = MERCHANT_ID;
          $data['csc_txn'] = $csc_txn; //'0164215622283356';//
          $data['merchant_txn'] = $merchant_txn; //'6991026032978';//
          $data['merchant_txn_param'] = 'N';
          $data['merchant_txn_status'] = 'S';
          $data['merchant_reference'] = rand(0, 999999);
          $data['refund_deduction'] = $refund_deduction; //'944.00';//
          $data['refund_mode'] = 'F';
          $data['refund_type'] = 'R';
          $data['refund_trigger'] = 'M';
          $data['refund_reason'] = $refund_reason; //'Found in Maker checker';//
           //echo "<br>JSON Post Datay<pre>"; print_r($data); echo "</pre>";//exit;
          $str = "merchant_id=$data[merchant_id]|csc_txn=$data[csc_txn]|merchant_txn=$data[merchant_txn]|merchant_txn_param=$data[merchant_txn_param]|merchant_txn_status=$data[merchant_txn_status]|merchant_reference=$data[merchant_reference]|refund_deduction=$data[refund_deduction]|refund_mode=$data[refund_mode]|refund_type=$data[refund_type]|refund_trigger=$data[refund_trigger]|refund_reason=$data[refund_reason]|";
          //echo "<br><br> Curl String : ".$str; //exit;
          $message_cipher = $bconn->encrypt($str);          
          $json_data_array = array(
          'merchant_id' => MERCHANT_ID,
          'request_data' => $message_cipher
          );
          
          $post = json_encode($json_data_array);
					
          // cURL Request starts here
          $ch = curl_init();
          $headers = array('Content-Type: application/json');
          curl_setopt_array($ch, array(
          CURLOPT_RETURNTRANSFER => 1,
          //CURLOPT_URL => "http://bridgeapi.csccloud.in/v2/refund/log",
          CURLOPT_URL => "https://bridge.csccloud.in/v2/refund/log",
          CURLOPT_VERBOSE => true,
          CURLOPT_HEADER => false,
          CURLOPT_HTTPHEADER => $headers,
          CURLINFO_HEADER_OUT => false,
          CURLOPT_CONNECTTIMEOUT  => 0,
          CURLOPT_TIMEOUT  => 0,
          CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => $post
          ));
          $server_output = curl_exec($ch);
          if(curl_errno($ch)){   
            echo 'Curl error: ' . curl_error($ch);
        }
          $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          print_r($p);
            echo "<br>Response Array<pre>=".$http_code.'='; print_r($server_output); echo "</pre>";
          if ($server_output != '') 
          { 
            $xml_response = simplexml_load_string($server_output);
            $p = $bconn->decrypt($xml_response->response_data);
            
            $p = explode('|', $p);
            
            $fine_params = array();
            foreach ($p as $param) 
            {            
              $param = explode('=', $param);
              if (isset($param[0])) 
              {
                if(count($param) >= 2) { $fine_params[$param[0]] = $param[1]; }
              }
            }
            $p = $fine_params;
            $xml_response = (array) $xml_response;
            
            print_r($p);
            echo "<br>Response Array<pre>"; print_r($p); echo "</pre>";
            if(count($p) > 0)
            {
							if($csc_txn == $p['csc_txn'])
							{
								$up_data['refund_status'] = $p['refund_status'];
								$up_data['merchant_id'] = $p['merchant_id'];
								$up_data['merchant_txn'] = $p['merchant_txn'];
								$up_data['merchant_reference'] = $p['merchant_reference'];
								$up_data['refund_reference'] = $p['refund_reference'];
								$up_data['csc_txn'] = $p['csc_txn'];
								$up_data['response_data'] = json_encode($p);
                				//echo "<br>Update Array<pre>"; print_r($up_data); echo "</pre>";
								$this->master_model->updateRecord("csc_refund_details",$up_data,array('id'=>$csc_refund_id));
								//echo "<br> Qry : ".$this->db->last_query(); //exit;
								//exit;
								$this->session->set_flashdata('success','Thank You');								
              				}
							else
							{
								$up_data['response_data'] = json_encode($p);
								$this->master_model->updateRecord("csc_refund_details",$up_data,array('id'=>$csc_refund_id));
								$this->session->set_flashdata('error','Error Occured');
              				}
						}
									else
									{
										$up_data['response_data'] = json_encode($p);
										$this->master_model->updateRecord("csc_refund_details",$up_data,array('id'=>$csc_refund_id));
									
										$this->session->set_flashdata('error','Error Occured');
						}
					  }
					else
					{
						$up_data['response_data'] = json_encode($p);
						$this->master_model->updateRecord("csc_refund_details",$up_data,array('id'=>$csc_refund_id));
						$this->session->set_flashdata('error','Error Occured');
          }
					
					redirect(site_url('admin/csc_refund'));
        }
      }
      
      $data['csc_txn'] = $csc_txn;
      $data['merchant_txn'] = $merchant_txn;
      $data['txn_date'] = $txn_date;
      $data['refund_deduction'] = $refund_deduction;
      $data['refund_reason'] = $refund_reason;
      $data['middle_content'] = 'csc_refund/index';
      $this->load->view('csc_refund/csc_refund_common_view', $data);
    }
    
    function check_special_character($str)
    {
      if($str != "")
      {
        if(preg_match("/^[0-9a-zA-Z ]{1,}$/", $str) === 0)
        {
          $this->form_validation->set_message('check_special_character', 'The Refund Reason field must contain only letters and digits');
          return false;
        } 
        else
        {
          return true;
        }
      }
      else
      {
        return true;
      }
    }
  }