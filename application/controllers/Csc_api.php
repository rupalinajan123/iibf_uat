<?php	
  /********************************************************************
		* Description: Controller for All CSC API
		* Created BY: Sagar Matale, 25-09-2024
		* Update By:  
	********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Csc_api extends CI_Controller 
	{	
		public function __construct()
		{
			parent::__construct();
			$this->load->model('csc_pg_model');

      echo '<div style="text-align: center; width: 400px; margin: 0 auto; background: red; color: #fff;font-weight: 600;padding: 8px 10px;">IP Address : '.$this->get_client_ip_csc();

      $esds_ip_arr = array('115.124.115.69','115.124.115.75','115.124.125.4', '115.124.115.77', '115.124.115.72');
      if (!in_array($this->get_client_ip_csc(), $esds_ip_arr))      
      {        
        echo '<div style="margin-top:10px;">You do not have permission to access this page</div>'; exit;
      }

      echo '</div>';
		}
		
		function index()
		{
			echo '<b>Welcome to CSC API</b>';
			echo '<br><br><b>1. Qry Api : </b>'.site_url('csc_api/csc_qry_api/000000');
			echo '<br><br><b>2. Refund Api : </b>'.site_url('csc_api/csc_refund_api/000000');
			echo '<br><br><b>3. Refund Status Api : </b>'.site_url('csc_api/csc_refund_status_api/000000');
		}
    
		//START : CSC QRY API (CHECK TRANSACTION STATUS)
		public function csc_qry_api($Receipt_no='')
		{
			if($Receipt_no != "")
			{
				$res = $this->csc_pg_model->csc_qry_api_model($Receipt_no);

        $merchant_id = $merchant_txn = $csc_id = $csc_txn = $product_id = $txn_amount = $txn_mode = $txn_status = $response_status = $creation_date = '';
        if(isset($res['response_status']) && $res['response_status'] == 'Success')
        {
          $merchant_id = $res['merchant_id'];
          $merchant_txn = $res['merchant_txn'];
          $csc_id = $res['csc_id'];
          $csc_txn = $res['csc_txn'];
          $product_id = $res['product_id'];
          $txn_amount = $res['txn_amount'];
          $txn_mode = $res['txn_mode'];
          $txn_status = $res['txn_status'];
          $response_status = $res['response_status'];
          $creation_date = $res['creation_date'];
        }
        else if(isset($res['response_status']) && $res['response_status'] == 'Fail')
        {
          $merchant_id = $merchant_txn = $csc_id = $csc_txn = $product_id = $txn_amount = $txn_mode = $res['response_data'];
          $txn_status = $res['response_code'];
          $response_status = $res['response_status'].'<br>'.$res['response_message'].'<br>'.$res['response_server'];
          $creation_date = $res['response_date'];
        }

			  echo '	<div align="center">
									<p><b>Order ID : '. $Receipt_no . '</b></p>
									
									<h3>CSC Wallet Transaction Query Result</h3>
									<table width="100%" border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse">
										<tr bgcolor="#CCCCCC">
											<th scope="col">Merchant ID</th>
											<th scope="col">Merchant Txn</th>
											<th scope="col">CSC ID</th>
											<th scope="col">CSC TXN</th>
											<th scope="col">Product ID</th>
											<th scope="col">Amount</th>
											<th scope="col">Mode</th>
											<th scope="col">Status</th>
											<th scope="col">Status Message</th>
											<th scope="col">Date</th>
										</tr>
										
										<tr>
											<td>'.$merchant_id.'</td>
											<td>'.$merchant_txn.'</td>
											<td>'.$csc_id.'</td>
											<td>'.$csc_txn.'</td>
											<td>'.$product_id.'</td>
											<td>'.$txn_amount.'</td>
											<td>'.$txn_mode.'</td>
											<td>'.$txn_status.'</td>
											<td>'.$response_status.'</td>
											<td>'.$creation_date.'</td>
										</tr>
									</table>
								</div>';
								
        
        if(count($res) > 0)
        {
          echo '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;"><tbody>';
          foreach($res as $key=>$val)
          {
            echo '<tr>
                    <td>'.$key.'</td>
                    <td>'.$val.'</td>
                  </tr>';
          }
          echo '</tbody></table>';
        }
			}
			else
			{
				echo 'Invalid Details';
			}
		}//END : CSC QRY API (CHECK TRANSACTION STATUS)		


		//START : CSC INITIATE REVERSE TRANSACTION API. THIS IS USE FOR SAME DAY TRANSACTION
		public function csc_reverse_api($Receipt_no='')
		{
			if($Receipt_no != "")
			{
				$res = $this->csc_pg_model->csc_reverse_api_model($Receipt_no);        

        $merchant_id = $merchant_txn = $reversal_reference = $txn_status = $reversal_response = '';
        if(isset($res['txn_status']) && $res['txn_status'] == '100')
        {
          $merchant_id = $res['merchant_id'];
          $merchant_txn = $res['merchant_txn'];
          $reversal_reference = $res['reversal_reference'];
          $txn_status = $res['txn_status'];
          $reversal_response = $res['reversal_response'];
        }
        else
        {
          $txn_status = $res['response_code'];
          $reversal_response = $res['response_status'].'<br>'.$res['response_message'].'<br>'.$res['response_server'].'<br>'.$res['response_data'].'<br>'.$res['response_data'].'<br>'.$res['response_date'];
        }
        
        echo '	<div align="center">
									<p><b>Order ID : '. $Receipt_no . '</b></p>
									
									<h3>CSC Wallet Transaction Reverse Result</h3>
									<table width="100%" border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse">
										<tr bgcolor="#CCCCCC">
											<th scope="col">Merchant ID</th>
											<th scope="col">Merchant Txn</th>
											<th scope="col">Reversal Reference</th>
											<th scope="col">Status</th>
											<th scope="col">Reversal Response</th>
										</tr>
										
										<tr>
											<td>'.$merchant_id.'</td>
											<td>'.$merchant_txn.'</td>
											<td>'.$reversal_reference.'</td>
											<td>'.$txn_status.'</td>
											<td>'.$reversal_response.'</td>
										</tr>
									</table>
								</div>';
								
        if(count($res) > 0)
        {
          echo '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;"><tbody>';
          foreach($res as $key=>$val)
          {
            echo '<tr>
                    <td>'.$key.'</td>
                    <td>'.$val.'</td>
                  </tr>';
          }
          echo '</tbody></table>';
        }
			}
			else
			{
				echo 'Invalid Details';
			}
		}//END : CSC INITIATE REVERSE TRANSACTION API. THIS IS USE FOR SAME DAY TRANSACTION
		
    //START : CSC INITIATE REFUND TRANSACTION API. THIS IS USE FOR BACK DATED TRANSACTION
		public function csc_refund_api($Receipt_no='')
		{
			if($Receipt_no != "")
			{
				$res = $this->csc_pg_model->csc_refund_api_model($Receipt_no);   
        
        $refund_status = $merchant_id = $merchant_txn = $merchant_reference = $refund_reference = $csc_txn = '';
        if(isset($res['refund_status']) && $res['refund_status'] == 'Success')
        {
          $refund_status = $res['refund_status'];
          $merchant_id = $res['merchant_id'];
          $merchant_txn = $res['merchant_txn'];
          $merchant_reference = $res['merchant_reference'];
          $refund_reference = $res['refund_reference'];
          $csc_txn = $res['csc_txn'];
        }
        else
        {
          $refund_status = $res['response_status'];
          $refund_reference = $res['response_code'].'<br>'.$res['response_data'].'<br>'.$res['response_message'].'<br>'.$res['response_server'].'<br>'.$res['response_date'];
        }
        
        echo '	<div align="center">
									<p><b>Order ID : '. $Receipt_no . '</b></p>
									
									<h3>CSC Wallet Transaction Refund Initiate Result</h3>
									<table width="100%" border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse">
										<tr bgcolor="#CCCCCC">
											<th scope="col">Refund Status</th>
											<th scope="col">Merchant Id</th>
											<th scope="col">Merchant Txn</th>
											<th scope="col">Merchant Reference</th>
											<th scope="col">Refund Reference</th>
											<th scope="col">CSC Txn</th>
										</tr>
										
                    <tr>
											<td>'.$refund_status.'</td>
											<td>'.$merchant_id.'</td>
											<td>'.$merchant_txn.'</td>
											<td>'.$merchant_reference.'</td>
											<td>'.$refund_reference.'</td>
											<td>'.$csc_txn.'</td>
										</tr>
									</table>
								</div>';
								
        if(count($res) > 0)
        {
          echo '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;"><tbody>';
          foreach($res as $key=>$val)
          {
            echo '<tr>
                    <td>'.$key.'</td>
                    <td>'.$val.'</td>
                  </tr>';
          }
          echo '</tbody></table>';
        }
			}
			else
			{
				echo 'Invalid Details';
			}
		}//END : CSC INITIATE REFUND TRANSACTION API. THIS IS USE FOR BACK DATED TRANSACTION
		

    //START : CSC REFUND STATUS TRANSACTION API.
		public function csc_refund_status_api($Receipt_no='', $refund_reference='')
		{
			if($Receipt_no != "")
			{
				$res = $this->csc_pg_model->csc_refund_status_api_model($Receipt_no, $refund_reference);   
        
        $refund_status = $refund_message = $csc_txn = $merchant_id = $merchant_txn = $merchant_txn_param = $refund_mode = $refund_bucket = $refund_date = '';
        if(isset($res['refund_status']) && $res['refund_status'] == 'S')
        {
          $refund_status = $res['refund_status'];
          $refund_message = $res['refund_message'];
          $csc_txn = $res['csc_txn'];
          $merchant_id = $res['merchant_id'];
          $merchant_txn = $res['merchant_txn'];
          $merchant_txn_param = $res['merchant_txn_param'];
          $refund_mode = $res['refund_mode'];
          $refund_bucket = $res['refund_bucket'];
          $refund_date = $res['refund_date'];
        }
        else
        {
          $refund_status = $res['response_status'];
          $refund_message = $res['response_code'].'<br>'.$res['response_data'].'<br>'.$res['response_message'].'<br>'.$res['response_server'].'<br>'.$res['response_date'];
        }
        
        echo '	<div align="center">
									<p><b>Order ID : '. $Receipt_no . '</b></p>
									
									<h3>CSC Wallet Transaction Refund Details</h3>
									<table width="100%" border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse">
										<tr bgcolor="#CCCCCC">
											<th scope="col">Refund Status</th>
											<th scope="col">Refund Message</th>
											<th scope="col">CSC Txn</th>
											<th scope="col">Merchant Id</th>
											<th scope="col">Merchant Txn</th>
											<th scope="col">Merchant Txn Parameter</th>
											<th scope="col">Refund Mode</th>
											<th scope="col">Refund Bucket</th>
											<th scope="col">Refund Date</th>
										</tr>

                    <tr>
											<td>'.$refund_status.'</td>
											<td>'.$refund_message.'</td>
											<td>'.$csc_txn.'</td>
											<td>'.$merchant_id.'</td>
											<td>'.$merchant_txn.'</td>
											<td>'.$merchant_txn_param.'</td>
											<td>'.$refund_mode.'</td>
											<td>'.$refund_bucket.'</td>
											<td>'.$refund_date.'</td>
										</tr>
									</table>
								</div>';
								
        if(count($res) > 0)
        {
          echo '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;"><tbody>';
          foreach($res as $key=>$val)
          {
            echo '<tr>
                    <td>'.$key.'</td>
                    <td>'.$val.'</td>
                  </tr>';
          }
          echo '</tbody></table>';
        }
			}
			else
			{
				echo 'Invalid Details';
			}
		}//END : CSC REFUND STATUS TRANSACTION API.
    
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
	} 				