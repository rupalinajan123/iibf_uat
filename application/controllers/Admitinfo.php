<?php	
  /********************************************************************
  * Description	: Admit letter data automation for jaiib and caiib
  * Created BY	: Pratibha Purkar On 2-09-2021
  ********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Admitinfo extends CI_Controller 
	{	
		public function __construct()
		{
			parent::__construct();
		}
		public function isGarpEligible()
		{
			//$service_url = 'http://10.10.233.66:8084/garpapi/getExamCodeByMemNo/510208326';
			$service_url = 'http://10.10.233.66:8083/bcbfapi/getExamCodeByMemNo/845037926/20404/2009-11-10';
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$curl_response = curl_exec($curl);
			print_r($curl_response);
			curl_close($curl);
			exit;
		}
		
		
		public function RefundEnquiryStatus()
		{
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			echo '<br>service_url : '.$service_url = "https://www.sbiepay.sbi/payagg/RefundMISReport/refundEnquiryAPI";
			
			echo '<br>merchIdVal : '.$merchIdVal = "1000169";
			echo '<br>AggregatorId : '.$AggregatorId = "SBIEPAY";
			echo '<br>atrn : '.$atrn  = "812262447"; //"8461191092835";
			
			//$acrn   = "1966024713361"; //acrn
			echo '<br>arrn : '.$arrn = "202130302252109";//"2310576333361"; //arrn 
			
			$queryRequest  = $aes->encrypt($arrn."|".$atrn."|".$merchIdVal);
			$queryRequest33 = http_build_query(array('queryRequest' => $queryRequest,"aggregatorId"=>"SBIEPAY","merchantId"=>$merchIdVal));
			$ch = curl_init($service_url);      
			//curl_setopt($ch, CURLOPT_SSLVERSION, true);
			//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			//curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $queryRequest33);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$response = curl_exec ($ch);
			
			if (curl_errno($ch)) 
			{
				echo '<br>error_msg > '.$error_msg = curl_error($ch);
			}
			curl_close ($ch);
			echo '<br>API response : '.$response;
			
			$encData = $aes->decrypt($response);
			echo '<br>Decrypted response : '.($encData);
			
			/* $encData = $aes->decrypt('FMJXoPXkkqDqABtZweuX8ZAqGgaWLNosUHkVQu0XfcaS1yRz22Njds1EVZ8DGTI12ryVAnJNSF+U qZVmVd8ZbQ==');
			echo '<br>Decrypted response : '.($encData); */
		}
		
		
		public function sr()
		{
			$merchIdVal = "1000169"; //$this->config->item('sbi_merchIdVal');
			$AggregatorId = "SBIEPAY"; //$this->config->item('sbi_AggregatorId');
			$atrn  = "8461191092835";
			$acrn   = "1966024713361";
			
			//$queryRequest = $AggregatorId."|".$merchIdVal."|".$acrn."|".$atrn;
			$queryRequest = $acrn."|".$atrn."|".$merchIdVal;
			
			$service_url = "https://www.sbiepay.sbi/payagg/RefundMISReport/refundEnquiryAPI";
			
			echo "<BR>param = ".$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
							
			$ch = curl_init();       
			curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
			$result = curl_exec($ch);
			print_r($result);
			$response_array = explode("|", $result);
			print_r($response_array);
		}
		public function sbirefund()
		{
			error_reporting(-1);
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$service_url = "https://www.sbiepay.sbi/payagg/RefundMISReport/refundEnquiryAPI";
			
			$merchIdVal = "1000169";
			$AggregatorId = "SBIEPAY";
			$atrn  = "8461191092835";
			$acrn   = "1966024713361";
			$arrn = "2310576333361";
			$queryRequest  = $acrn."|".$atrn."|".$merchIdVal;
			$post_param = "queryRequest=".$queryRequest."&merchantId=".$merchIdVal."&aggregatorId=".$AggregatorId;
			$ch = curl_init();       
			curl_setopt($ch, CURLOPT_URL,urlencode($service_url));                                                 
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$info = curl_getinfo($ch);
			echo $result = curl_exec($ch);
			curl_close($ch);
			print_r($result); 
			
		}
		public function index() 
		{
			if (isset($_POST["btn_Submit"])) 
			{
				## Backup previous data sql from admitcard_info_temp table
				
				## Truncate previous data from admitcard_info_temp
				$this->db->truncate('admitcard_info_temp');
				//$this->db->query('TRUNCATE table admitcard_info_temp');
				## Proceed new data insertion in admitcard_info_temp table
				$fileName = $_FILES["file"]["tmp_name"];
				if ($_FILES["file"]["size"] > 0) {
					if (($handle = fopen($fileName, "r")) !== FALSE) {
					fgetcsv($handle);   
				    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
						   
					$num = count($data);
					for ($c=0; $c < $num; $c++) {
					  $col[$c] = $data[$c];
					}
			
					// if city name not present in csv file
					$center_code = $this->security->xss_clean($this->db->escape($col[8]));  
					$mem_type = $this->security->xss_clean($this->db->escape($col[2]));  
					$mem_mem_no = $this->security->xss_clean($this->db->escape($col[4]));  
					$g_1 = $this->security->xss_clean($this->db->escape($col[3])); 
					$mam_nam_1 = $this->security->xss_clean($this->db->escape($col[5]));  

					$exm_cd = $this->security->xss_clean($this->db->escape($col[0])); 
					$sub_cd = $this->security->xss_clean($this->db->escape($col[10]));  
					$m_1 = $this->security->xss_clean($this->db->escape($col[21]));  
					$venueid = $this->security->xss_clean($this->db->escape($col[12]));  
					$venueadd1 = $this->security->xss_clean($this->db->escape($col[13]." , ".$col[14]));  
					$venpin = $this->security->xss_clean($this->db->escape($col[15]));  
					$pwd = $this->security->xss_clean($this->db->escape($col[17]));  
					$date = $this->security->xss_clean(date('d-M-y',strtotime($col[18])));  
					$time = $this->security->xss_clean($this->db->escape($col[19]));  
					$mode = 'Online'; 
					$seat_identification = $this->security->xss_clean($this->db->escape($col[16]));  
					$vendor_code = $this->security->xss_clean($this->db->escape($col[23]));
					 
					$query = "INSERT INTO  admitcard_info_temp(center_code, mem_type, mem_mem_no, g_1, mam_nam_1,exm_cd, sub_cd, m_1, venueid, venueadd1, venpin, pwd, date, time, mode, seat_identification,vendor_code) VALUES (".$center_code.",".$mem_type.",".$mem_mem_no.",".$g_1.",".$mam_nam_1.",".$exm_cd.",".$sub_cd.",".$m_1.",".$venueid.",".$venueadd1.",".$venpin.",".$pwd.",'".$date."',".$time.",'".$mode."',".$seat_identification.",".$vendor_code.")";
					 
					/*echo "<br/><br/>";
					echo $query; 
					exit;*/
					
					$s     = $this->db->query($query);
					}//while
					fclose($handle);
					}//If handle		 
				}//if
				if(isset($_POST['data_type']) && $_POST['data_type'] == 'replace')
					$this->replace_data();
				else if(isset($_POST['data_type']) && $_POST['data_type'] == 'add') 
					$this->add_data();					
			}//if	
			$this->load->view('admitinfo',$data);
		}
		public function add_data()
		{
			## Check if record already present
			
			## Take data from admitcard_info_temp table and insert into admitcard_info_temp2 table
			$query = 'INSERT INTO admitcard_info_temp2 (center_code, mem_type, mem_mem_no, g_1, mam_nam_1,exm_cd, sub_cd, m_1, venueid, venueadd1, venpin, pwd, date, time, mode, seat_identification,vendor_code) SELECT center_code, mem_type, mem_mem_no, g_1, mam_nam_1,exm_cd, sub_cd, m_1, venueid, venueadd1, venpin, pwd, date, time, mode, seat_identification,vendor_code FROM admitcard_info_temp WHERE 1';
			$insert = $this->db->query($query);
			echo "<p align='center'><br/>Total records added to admitcard_info_temp2 table are => ".$this->db->affected_rows().'</p>';
		}
		public function replace_data()
		{
			$result = $this->db->query('SELECT * FROM admitcard_info_temp');
			$result = $result->result_array();
			$deleted_rec_count = 0;
			foreach($result as $res)
			{
				$query = 'DELETE FROM admitcard_info_temp2 WHERE center_code ='.$res['center_code'].' AND exm_cd = '.$res['exm_cd'].' AND mem_mem_no='.$res['mem_mem_no'].' AND exm_cd = '.$res['exm_cd'].' AND sub_cd = '.$res['sub_cd'];	
				if($this->db->query($query))
				{
					$deleted_rec_count++;
					$this->db->query('UPDATE admitcard_info_temp SET flag = "1" WHERE admitcard_id ='.$res['admitcard_id']);
					//echo '<br/>'.$this->db->last_query();
				}	
			}
			//print_r($result);
			
			echo "<p align='center'><br/>Total records deleted from admitcard_info_temp2 table are => ".$deleted_rec_count.'</p>';
			
			$this->db->query('INSERT INTO admitcard_info_temp2 (center_code, mem_type, mem_mem_no, g_1, mam_nam_1,exm_cd, sub_cd, m_1, venueid, venueadd1, venpin, pwd, date, time, mode, seat_identification,vendor_code) SELECT center_code, mem_type, mem_mem_no, g_1, mam_nam_1,exm_cd, sub_cd, m_1, venueid, venueadd1, venpin, pwd, date, time, mode, seat_identification,vendor_code FROM admitcard_info_temp WHERE flag = 1');
			//echo $this->db->last_query();
			echo "<p align='center'><br/>Total records inserted in admitcard_info_temp2 table are => ".$this->db->affected_rows().'</p>';
		}
		
	} 			