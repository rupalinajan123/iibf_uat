<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pending_payment_chaitali extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
	}
	public function sbicallback_chaitali()
	{ 
		$str = '';
		//$filehandle = fopen("payment_pending/lock.txt", "c+");
		//if (flock($filehandle, LOCK_EX | LOCK_NB)) {
			
			
			$start_time = date("Y-m-d H:i:s");
			$todays_date = '2021-04-01';//date("d-m-Y");
			$dir = 'payment_pending/'.$todays_date;
			if(!is_dir($dir)){
				mkdir($dir, 0755);
			}
			
			 $yesterday = '2021-04-';//date('Y-m-d',strtotime("-1 days"));
			 $sql = "SELECT receipt_no FROM `payment_transaction` Where status = 2 AND exam_code != 991  AND date LIKE '%".$yesterday."%' Limit 20"; 
			/* $receipt_no = array('812204417','812205213');
			$this->db->where_in('receipt_no',$receipt_no);
			$this->db->where('exam_code !=','991');
			$sql = $this->Master_model->getRecords('payment_transaction',array('status' => '2'),'receipt_no'); */
			 //$sql = "SELECT receipt_no FROM `payment_transaction` Where status = 2 AND exam_code != 991"; 
			// echo $sql; exit;
			 $record = $this->db->query($sql);
			 if($record->num_rows()){
				foreach ($record->result_array() as $c_row){
					
					$responsedata = sbiqueryapi($c_row['receipt_no']);
					$receipt_no=$c_row['receipt_no'];
					$encData=implode('|',$responsedata);
					$resp_data = json_encode($responsedata);
						
					$resp_array = array('receipt_no'    => $c_row['receipt_no'],
										'txn_status'     => $responsedata[2],
										'txn_data'         => $encData.'&CALLBACK=C_S2S',
										'response_data' => $resp_data,
										'remark'         => '',
										'resp_date'     => date('Y-m-d H:i:s'), 
										);
					$this->master_model->insertRecord('pending_payment', $resp_array); 
					
					if($responsedata[2] != 'SUCCESS'){
						if($responsedata[2] == ''){
							$tra_status = 'FAIL';
							$status = 0;
						}else if($responsedata[2] == 'REFUND'){
							## added status 4 for transactions refunded by SBI
							$status = 4;
							$tra_status = $responsedata[2];
						}else{
							$status = 0;
							$tra_status = $responsedata[2];
						}
						$update_arr = array('status'=>$status,'transaction_details'=>$tra_status,'callback'=>'PSS2S');
						$where_arr = array('receipt_no'=>$c_row['receipt_no']);
						$this->master_model->updateRecord('payment_transaction',$update_arr,$where_arr);
						
						$str.= $this->db->last_query().'\n';
						 
					}
					
				}
			}
			  $fp = @fopen($dir."/logs_".date("dmY").".txt", "a") or die("Unable to open file!");
			echo $str.= date('Y-m-d H:i:s').' File execution start';
			fwrite($fp, $str);
			fclose($fp); 
						
			//flock($filehandle, LOCK_UN);  // don't forget to release the lock	
		//}
		/* else {
			// throw an exception here to stop the next cron job
			echo "Payment pending is already running";
		}
		 fclose($filehandle); */
		 
	}
	
	
	
}
