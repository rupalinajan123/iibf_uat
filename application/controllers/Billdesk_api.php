<?php	
  /********************************************************************
		* Description: Controller for All Billdesk API
		* Created BY: Sagar Matale, 18-06-2022
		* Update By:  Sagar Matale, 18-06-2022
	********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Billdesk_api extends CI_Controller 
	{	
		public function __construct()
		{
			parent::__construct();
			$this->load->model('billdesk_pg_model');

      echo '<div style="text-align: center; width: 400px; margin: 0 auto; background: red; color: #fff;font-weight: 600;padding: 8px 10px;">IP Address : '.$this->get_client_ip_email();

      $esds_ip_arr = array('115.124.115.69','115.124.115.75','115.124.125.4', '115.124.115.77', '115.124.115.72','182.73.101.70','223.233.82.176');
      if (!in_array($this->get_client_ip_email(), $esds_ip_arr))      
      {        
        echo '<div style="margin-top:10px;">You do not have permission to access this page</div>'; exit;
      }

      echo '</div>';
		}
		
		function index()
		{
			echo '<b>Welcome to Billdesk API</b>';
			echo '<br><br><b>1. Qry Api : </b>'.site_url('billdesk_api/billdesk_qry_api/000000');
			echo '<br><br><b>2. Refund Api : </b>'.site_url('billdesk_api/billdesk_refund_api/000000');
			echo '<br><br><b>3. Refund Status Api : </b>'.site_url('billdesk_api/billdesk_refund_status_api/000000');
		}
    
		//START : BILLDESK QRY API (CHECK TRANSACTION STATUS)
		public function billdesk_qry_api($MerchantOrderNo='')
		{
			if($MerchantOrderNo != "")
			{
				$res = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			//	echo'<pre>';print_r($res);
				echo '	<div align="center">
									<p><b>Order ID : '. $MerchantOrderNo . '</b></p>
									
									<h3>Billdesk Payment Transaction Query Result</h3>
									<table width="100%" border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse">
										<tr bgcolor="#CCCCCC">
											<th scope="col">Txn No.</th>
											<th scope="col">Status</th>
											<th scope="col">Order ID</th>
											<th scope="col">Amount</th>
											<th scope="col">Description</th>
											<th scope="col">Bank Code</th>
											<th scope="col">Date</th>
											<th scope="col">Pay Mode</th>
											<th scope="col">Bank Ref. ID</th>
										</tr>
										
										<tr>
											<td>'.$res['transactionid'].'</td>
											<td>'.($res['transaction_error_type'] != "" ? $res['transaction_error_type'] : $res['status']).'</td>
											<td>'.$res['orderid'].'</td>
											<td>'.$res['amount'].'</td>
											<td>'.($res['transaction_error_desc'] !="" ? $res['transaction_error_desc'] : $res['message']).'</td>
											<td>Billdesk</td>
											<td>'.$res['transaction_date'].'</td>
											<td>'.$res['payment_method_type'].'</td>
											<td>'.$res['bankid'].'</td>
										</tr>
									</table>
								</div>';
				
				echo '	<div align="center">				
                  <table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; width:100%; max-width:800px; margin:50px auto;">
										<thead><tr bgcolor="#CCCCCC"><th colspan="2">Result Array</th></tr></thead>
										<tbody>';
										
										if(count($res) > 0)
										{
											foreach($res as $key=>$val)
											{
												if($key == 'additional_info' || $key == 'refundInfo')
                        {
                          echo '<tr><td><b>'.$key.'</b></td><td>';

                          $sub_arr = array();
                          if($key == 'additional_info') { $sub_arr = $val; }
                          else if($key == 'refundInfo') { $sub_arr = $val[0]; }
                          
                          if(count($sub_arr) > 0)
                          {
                            echo '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse: collapse; margin: 10px; background: #eee;">
                              <tbody>';
                                foreach($sub_arr as $key2=>$val2)
                                {
                                  echo '<tr><td><b>'.$key2.'</b></td><td>'.json_encode($val2).'</td></tr>'; 
                                }
                              echo '</tbody>
                            </table>';
                          }
                          
                          echo  '</td></tr>';
                        }
                        else
                        {
												  echo '<tr><td><b>'.$key.'</b></td><td>'.json_encode($val).'</td></tr>';  
                        }
											}
										}
										
				echo '			</tbody>
									</table>
								</div>';
								
				//echo '<pre>'; print_r($res); echo '</pre>';
			}
			else
			{
				echo 'Invalid Details';
			}
		}
		//END : BILLDESK QRY API (CHECK TRANSACTION STATUS)
		
		//START : BILLDESK INITIATE REFUND REQUEST
		public function billdesk_refund_api($MerchantOrderNo='')
		{
			if($MerchantOrderNo != "")
			{
				$res = $this->billdesk_pg_model->billdeskRefundApi($MerchantOrderNo);
				 echo '<pre>'; print_r($res); echo '</pre>';
				 $status = $res['refund_status'];
				$status_req = $res['objectid'];
				if($status == '0699' && $status_req == 'refund')
				{
					$pt_status = "3";
					// update payment transaction status table for
					echo "<BR><BR> UPDATE PT : ".$update_pt_sql = $this->db->query("UPDATE `payment_transaction` SET `status` = '".$pt_status."' WHERE receipt_no = '".$MerchantOrderNo."' AND gateway = 'billdesk' AND status IN ('0','2','1')");
					//mysqli_query($update_pt_sql);
					//$result = $update_pt_sql->result_array();
				}  
				echo '	<div align="center">	
									<p><b>Order ID : '. $MerchantOrderNo . '</b></p>
									<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; width:100%; max-width:500px; margin:0 auto 50px auto;">
										<thead><tr bgcolor="#CCCCCC"><th colspan="2">Refund Details</th></tr></thead>
										<tbody>';
										
										if(count($res) > 0)
										{
											foreach($res as $key=>$val)
											{
												echo '<tr><td><b>'.$key.'</b></td><td>'.$val.'</td></tr>';
											}
										}
										
				echo '			</tbody>
									</table>
								</div>';
			}
			else
			{
				echo 'Invalid Details';
			}
		}
		//END : BILLDESK INITIATE REFUND REQUEST
		
		//START : BILLDESK CHECK STATUS OF REFUND REQUEST
		public function billdesk_refund_status_api($MerchantOrderNo='')
		{
			if($MerchantOrderNo != "")
			{
				$res = $this->billdesk_pg_model->billdeskRefundStatusApi($MerchantOrderNo);
				//echo '<pre>'; print_r($res); echo '</pre>';
				
				echo '	<div align="center">	
									<p><b>Order ID : '. $MerchantOrderNo . '</b></p>
									<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; width:100%; max-width:500px; margin:0 auto 50px auto;">
										<thead><tr bgcolor="#CCCCCC"><th colspan="2">Refund Status</th></tr></thead>
										<tbody>';
										
										if(count($res) > 0)
										{
											foreach($res as $key=>$val)
											{
												echo '<tr><td><b>'.$key.'</b></td><td>'.$val.'</td></tr>';
											}
										}
										
				echo '			</tbody>
									</table>
								</div>';
			}
			else
			{
				echo 'Invalid Details';
			}
		}
		//END : BILLDESK CHECK STATUS OF REFUND REQUEST


		//START : BILLDESK QRY API (CHECK TRANSACTION STATUS)
		public function billdesk_qry_api_old_txn($MerchantOrderNo='')
		{
			if($MerchantOrderNo != "")
			{
				$res = $this->billdesk_pg_model->billdeskqueryapi_old_transactions($MerchantOrderNo);
				echo "<pre>";
				print_r($res);

			}
	
		}


				//START : BILLDESK INITIATE REFUND REQUEST
		public function billdesk_refund_api_old_txn($billdesk_txn_no,$MerchantOrderNo)
		{
		

			if($MerchantOrderNo != "" && $MerchantOrderNo!='')
			{
				$res = $this->billdesk_pg_model->billdeskRefundApi_old_txn($billdesk_txn_no,$MerchantOrderNo);
				 echo '<pre>'; print_r($res); echo '</pre>';
				 $status = $res['refund_status'];
				$status_req = $res['objectid'];
				if($status == '0799' && $status_req == 'refund')
				{
					$pt_status = "3";
					// update payment transaction status table for
					echo "<BR><BR> UPDATE PT : ".$update_pt_sql = $this->db->query("UPDATE `payment_transaction` SET `status` = '".$pt_status."' WHERE receipt_no = '".$MerchantOrderNo."' AND gateway = 'billdesk' AND status IN ('0','2','1','3')");
					//mysqli_query($update_pt_sql);
					//$result = $update_pt_sql->result_array();
				}  
				echo '	<div align="center">	
									<p><b>Order ID : '. $MerchantOrderNo . '</b></p>
									<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; width:100%; max-width:500px; margin:0 auto 50px auto;">
										<thead><tr bgcolor="#CCCCCC"><th colspan="2">Refund Details</th></tr></thead>
										<tbody>';
										
										if(count($res) > 0)
										{
											foreach($res as $key=>$val)
											{
												echo '<tr><td><b>'.$key.'</b></td><td>'.$val.'</td></tr>';
											}
										}
										
				echo '			</tbody>
									</table>
								</div>';
			}
			else
			{
				echo 'Invalid Details';
			}
		}


			//START : BILLDESK CHECK STATUS OF REFUND REQUEST
		public function billdesk_refund_status_old_txn($billdesk_txn_no='')
		{
			if($billdesk_txn_no != "")
			{
				$res = $this->billdesk_pg_model->billdeskRefundStatusApi_new($billdesk_txn_no);
				//echo '<pre>'; print_r($res); echo '</pre>';
				
				echo '	<div align="center">	
									<p><b>Order ID : '. $billdesk_txn_no . '</b></p>
									<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; width:100%; max-width:500px; margin:0 auto 50px auto;">
										<thead><tr bgcolor="#CCCCCC"><th colspan="2">Refund Status</th></tr></thead>
										<tbody>';
										
										if(count($res) > 0)
										{
											foreach($res as $key=>$val)
											{
												echo '<tr><td><b>'.$key.'</b></td><td>'.$val.'</td></tr>';
											}
										}
										
				echo '			</tbody>
									</table>
								</div>';
			}
			else
			{
				echo 'Invalid Details';
			}
		}
		//END : BILLDESK CHECK STATUS OF REFUND REQUEST
		//END : BILLDESK INITIATE REFUND REQUEST
		//END : BILLDESK QRY API (CHECK TRANSACTION STATUS)

    public function get_client_ip_email()
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