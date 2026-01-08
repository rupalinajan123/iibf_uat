 <?php
/*
 	## Controller Name	:	Chartered_cron_generate_csv
	## Created By 		:	Swati Watpade
 	## Updated By		:	Pratibha Purkar
 	## Update Date		:	18-05-2021
*/
## Daily Cron executes at 12.30pm 
## Cron used to send daily registrations count with details for charatered banker

defined('BASEPATH') OR exit('No direct script access allowed');
class Chartered_cron_generate_csv extends CI_Controller 
{
	public $UserID;
	public function __construct(){
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('pagination_helper'); 
		$this->load->library('pagination');
		$this->load->model('log_model');
		$this->load->library('email');
		$this->load->model('Emailsending');
	}
	public function chartered_csv()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/chartered/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Chartered Banker Examination CSV Details Cron Execution Start", $desc);
		$yesterday = date('Y-m-d', strtotime("- 1 day"));
		## Get total record count till date
		$records = $this->db->query("SELECT record_count FROM `cron_csv_custom` WHERE `old_file_name` LIKE '%CB_%' AND `new_file_name` LIKE '%CB_%' AND createdon LIKE '".$yesterday."%' ORDER BY `cron_csv_custom`.`createdon` DESC LIMIT 1");
		$res = $records->result_array();
		$last_cnt = $res[0]['record_count'];
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "CB_".$current_date.".csv";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n***** Chartered Banker Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
		    
			
		    $exam_code = 1016; 
			
			$this->db->select('DISTINCT(member_exam.regnumber),namesub,member_exam.exam_code,c.firstname,c.middlename,c.lastname,c.staffnumber,c.department,c.organisation,c.dateofbirth,c.email,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.registrationtype,c.qualification,member_exam.exam_period,c.createdon,c.fee');
			$this->db->join('payment_transaction b','b.ref_id = member_exam.id','LEFT');
			$this->db->join('caiib_jaiib_newexam_registration c','member_exam.id=c.mem_exam_id','LEFT'); 
			$this->db->where('member_exam.exam_code', $exam_code);
			//$this->db->where('member_exam.created_on BETWEEN "'. date('Y-m-d', strtotime('2022-12-20')). '" and "'. date('Y-m-d', strtotime('2022-12-21')).'"');
            $this->db->where('member_exam.created_on BETWEEN "'. date('Y-m-d', strtotime($yesterday)). '" and "'. date('Y-m-d', strtotime(date('Y-m-d'))).'"');
			$can_exam_data = $this->master_model->getRecords('member_exam',array('b.status'=>1,'member_exam.pay_status'=>1));
			//echo $this->db->last_query(); die;
			$date_format = date("d-m-Y", strtotime($yesterday));
			$data='';
			$cnt=$last_cnt;
			if(count($can_exam_data) > 0)
			{
				$i = 1;
				$exam_cnt = 0;
				// Column headers	
			   	$data1 = "Sr.No.,Membershi No,Title,First Name,Middle name,Last Name,Date of Birth,Staff Number,Organization Name,Department,Address1,Address2,Address3,Address4,District,City Name,State Name,Country,Pin code,Address Type,Telephone No,Contact Email,Qualification,Date \n";
			
				$exam_file_flg = fwrite($fp, $data1);
				foreach($can_exam_data as $exam)
				{	
				    
					$cnt++;				
					$data = $cnt.','.$exam['regnumber'].','.$exam['namesub'].','.$exam['firstname'].','.$exam['middlename'].','.$exam['lastname'].','.$exam['dateofbirth'].','.$exam['staffnumber'].','.$exam['organisation'].','.$exam['department'].','.$exam['address1'].','.$exam['address2'].','.$exam['address3'].','.$exam['address4'].','.$exam['district'].','.$exam['city'].','.$exam['state'].',"INDIA",'.$exam['pincode'].',"Official Address",'.$exam['mobile'].','.$exam['email'].','.$exam['qualification'].','.$exam['createdon'].','.''."\n";
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Chartered Banker Examination CSV Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Chartered Banker Examination CSV Details File.";
					$i++;
					$exam_cnt++;
					//$cnt++;
				}
				
				fwrite($fp1, "Total Chartered Banker Exam Applications - ".$exam_cnt."\n");
				// File Rename Functinality
				$oldPath = $cron_file_dir.$current_date."/CB_".$current_date.".csv";
				$newPath = $cron_file_dir.$current_date."/CB_".date('dmYhi')."_".$exam_cnt.".csv";
				rename($oldPath,$newPath);
				$OldName = "CB_".$current_date.".csv";
				$NewName = "CB_".date('dmYhi')."_".$exam_cnt.".csv";
				$insert_info = array(
				'CurrentDate' => $current_date,
				'old_file_name' => $OldName,
				'new_file_name' => $NewName,
				'record_count' => $cnt,
				'createdon' => date('Y-m-d H:i:s'));
				$this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
				$message = "Dear Team, <br/><br/>  PFA  Daily Report of  ".$date_format." .<br/> Total Chartered Banker Exam Applications - ".$exam_cnt."<br/><br/> Yours truly,<br/>IIBF Team<br/>";
				## Removed soumya@iibf.org.in email as per client request on 25th May
				$info_arr = array('to'=>'sgbhatia@iibf.org.in,smuralidaran@iibf.org.in,suhas@iibf.org.in,iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com', 'subject'=>'IIBF Chartered Banker Daily Report of '. $yesterday,'message'=>$message);
				//$info_arr = array('to'=>'iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com',  'subject'=>'IIBF Chartered Banker Daily Report of '. $yesterday,'message'=>$message);
				$files=array($newPath);
				$this->Emailsending->mailsend_attch($info_arr,$files);
			}
			else
			{
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				fwrite($fp1, "No data found for the date: ".$yesterday." \n");
				// File Rename Functinality
				$oldPath = $cron_file_dir.$current_date."/CB_".$current_date.".csv";
				$newPath = $cron_file_dir.$current_date."/CB_".date('dmYhi')."_0.csv";
				rename($oldPath,$newPath);
				$OldName = "CB_".$current_date.".csv";
				$NewName = "CB_".date('dmYhi')."_0.csv";
				$insert_info = array(
				'CurrentDate' => $current_date,
				'old_file_name' => $OldName,
				'new_file_name' => $NewName,
				'record_count' => $cnt,
				'createdon' => date('Y-m-d H:i:s'));
				$this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
				$success[] = "No data found for the date";
				$message = "Dear Team, <br/><br/>  There are no registrations for date  ".$date_format." .<br/><br/><br/> Yours truly,<br/>IIBF Team<br/>";
				$info_arr = array('to'=>'sgbhatia@iibf.org.in,smuralidaran@iibf.org.in,suhas@iibf.org.in,Sonal.Chavan@esds.co.in,iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF Chartered Banker DailReport of '. $yesterday,'message'=>$message);
				//$info_arr = array('to'=>'iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com', 'subject'=>'IIBF Chartered Banker Daily Report of '. $yesterday,'message'=>$message);
				//$files=array($newPath);
				//$this->Emailsending->mailsend_attch($info_arr,$files);
				$this->Emailsending->mailsend($info_arr);
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Chartered Banker Examination CSV Details Cron Execution End", $desc);
			fwrite($fp1, "\n"."***** Chartered Banker Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
			fclose($fp1);
		
	}
}

/** FUNCTION ADDED  TO GENERATE charterd INVOICE using payment trans id  created by swati***/
	public function GenerateCharteredPaymentInvoice_detail()
	{
	   
		$query = $this->db->query("SELECT e.pay_txn_id FROM payment_transaction left join exam_invoice as e on e.pay_txn_id=payment_transaction.id WHERE payment_transaction.exam_code = 1016 AND payment_transaction.status = 1 and e.invoice_no ='' and e.invoice_image='' ");
        //payment trns id in array
        $array = $query->result_array();
        
	foreach ($array as $arr ) 
		{
		    
		    
		        $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array( 'id' => $arr['pay_txn_id']), 'ref_id,member_regnumber,status,id,transaction_no,date');
            $reg_id        = $get_user_regnum_info[0]['ref_id'];
            $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
            
            $transaction_no= $get_user_regnum_info[0]['transaction_no'];
            
            $date_of_invoice = $get_user_regnum_info[0]['date'];
            
            $reg_data = $this->Master_model->getRecords('caiib_jaiib_newexam_registration', array('member_no' => $applicationNo, 'mem_exam_id' => $reg_id),'exam_code');
            $selected_exam_code =$reg_data[0]['exam_code'];
        
          /* Get User Attempt */
            $attemptQry=$this->db->query("SELECT attempt,fee_flag FROM caiib_jaiib_newexam_eligible WHERE member_no='".$applicationNo."' AND exam_code = '" . $selected_exam_code . "' LIMIT 1"); 
            $attemptArr = $attemptQry->row_array();
            $attempt = $attemptArr['attempt'];
            $fee_flag=$attemptArr['fee_flag'];
            $attempt = $attempt+1;
 
		     $blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
            $memberexam_data = array('pay_status'=>1,  'modified_on'=>date('Y-m-d H:i:s'));
             //$regno=$this->session->userdata['memberdata']['regno'];
            $this->master_model->updateRecord('caiib_jaiib_newexam_registration',$blended_data,array('mem_exam_id'=>$reg_id,'member_no'=>$applicationNo));
           $this->master_model->updateRecord('member_exam',$memberexam_data,array('id'=>$reg_id));
           $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_email'));
      if (!empty($applicationNo)) {
           $user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
           }
               if (count($emailerstr) > 0) 
            {
            $Qry=$this->db->query("SELECT exam_code, qualification FROM caiib_jaiib_newexam_registration WHERE mem_exam_id = '".$reg_id."' LIMIT 1");
            $detailsArr        = $Qry->row_array();
            $exam_code = $detailsArr['exam_code'];
            $exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];
             $newstring    = str_replace("#exam_name#","".$exam_name."",$emailerstr[0]['emailer_text']);
              /* Set Email sending options */
              $info_arr          = array(
                                  'to' => ''.$user_info[0]['email'].'',
                                  'from' => $emailerstr[0]['from'],
                                  'subject' => $emailerstr[0]['subject'],
                                  'message' => $newstring
                            );
              $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $get_user_regnum_info[0]['id']));
              $zone_code = "";
              $zoneArr = array();
              //$regno = $this->session->userdata['memberdata']['regno'];
              $zoneArr = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('mem_exam_id'=>$reg_id,'pay_status'=>1),'gstin_no');
              $gstin_no          = $zoneArr[0]['gstin_no'];
              /* Invoice Number Genarate Functinality */
              if (count($getinvoice_number) > 0){
              $invoiceNumber =custom_generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                
              if($invoiceNumber)
              {
                $invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
              }
                  $update_data = array(
                                     'invoice_no' => $invoiceNumber,
                                    //'member_no' => $applicationNo,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => $date_of_invoice,
                                    'modified_on' => $date_of_invoice
                                );
                                
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('pay_txn_id' => $get_user_regnum_info[0]['id']));
                /* Invoice Genarate Function */
                                $attachpath = genarate_chartered_invoice_custom($getinvoice_number[0]['invoice_id']);
                                $this->Emailsending->mailsend_attch($info_arr,$attachpath);
                                $insert_info=array('pay_txn_id' => $get_user_regnum_info[0]['id'],
                                    'path' => $attachpath
                                    );
                                $this->master_model->insertRecord('chartered_settlement', $insert_info, true);
		
		
		}
	}
	}
}

}