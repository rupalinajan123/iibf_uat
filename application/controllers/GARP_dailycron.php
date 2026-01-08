 <?php
/*
 	## Controller Name	:	GARP_dailycron
	## Created By 		:	Swati Watpade
 	
*/
## Daily Cron executes at 12.30pm 
## Cron used to send daily registrations count with details for GARP

defined('BASEPATH') OR exit('No direct script access allowed');
class GARP_dailycron extends CI_Controller 
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
	
	
	public function garp_csv()
	{
	    die;
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/GARP_DAILYCRON/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("GARP-FRR Examination CSV Details Cron Execution Start", $desc);
		$yesterday = date('Y-m-d', strtotime("- 1 day"));
		## Get total record count till date
		$records = $this->db->query("SELECT record_count FROM `cron_csv_custom` WHERE `old_file_name` LIKE '%GARP_%' AND `new_file_name` LIKE '%GARP_%' AND createdon LIKE '".$yesterday."%' ORDER BY `cron_csv_custom`.`createdon` DESC LIMIT 1");
		$res = $records->result_array();
		$last_cnt = $res[0]['record_count'];
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "GARP_".$current_date.".csv";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n***** GARP-FRR Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
		    
			
		    $exam_code = 1018; 
			
			$this->db->select('DISTINCT(member_exam.regnumber),namesub,member_exam.exam_code,c.firstname,c.middlename,c.lastname,c.email,c.qualification');
			$this->db->join('payment_transaction b','b.ref_id = member_exam.id','LEFT');
			$this->db->join('garp_exam_registration c','member_exam.id=c.mem_exam_id','LEFT'); 
			$this->db->where('member_exam.exam_code', $exam_code);
			$this->db->where('member_exam.created_on BETWEEN "'. date('Y-m-d', strtotime('2021-09-30')). '" and "'. date('Y-m-d', strtotime('2021-11-17')).'"');
           // $this->db->where('member_exam.created_on BETWEEN "'. date('Y-m-d', strtotime($yesterday)). '" and "'. date('Y-m-d', strtotime(date('Y-m-d'))).'"');
			$can_exam_data = $this->master_model->getRecords('member_exam',array('b.status'=>1,'member_exam.pay_status'=>1));
			echo $this->db->last_query(); //die;
			$date_format = date("d-m-Y", strtotime($yesterday));
			$data='';
			$cnt=$last_cnt;
			if(count($can_exam_data) > 0)
			{
				$i = 1;
				$exam_cnt = 0;
				// Column headers	
			   	$data1 = "Financial Risk and Regulation Cource IIBF Registration Worksheet \n Sr.No.,Program,First Name,Last Name,Email Address,IIBF Course Requisite(JAIIB or CAIIB),FRR Cource,Ebooks,Total,Opt-In to Share Exam Results,Member no \n";
			
				$exam_file_flg = fwrite($fp, $data1);
				foreach($can_exam_data as $exam)
				{	
				    
					$cnt++;				
					$data = $cnt.',"Financial Risk and Regulation",'.$exam['firstname'].','.$exam['lastname'].','.$exam['email'].','.$exam['qualification'].'," $300.00"," Included "," $300.00 ","Yes",'.$exam['regnumber'].','.''."\n";
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "GARP-FRR Examination CSV Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating GARP-FRR Examination CSV Details File.";
					$i++;
					$exam_cnt++;
					//$cnt++;
				}
				
				fwrite($fp1, "Total GARP-FRR Exam Applications - ".$exam_cnt."\n");
				// File Rename Functinality
				$oldPath = $cron_file_dir.$current_date."/GARP_".$current_date.".csv";
				$newPath = $cron_file_dir.$current_date."/GARP_".date('dmYhi')."_".$exam_cnt.".csv";
				rename($oldPath,$newPath);
				$OldName = "GARP_".$current_date.".csv";
				$NewName = "GARP_".date('dmYhi')."_".$exam_cnt.".csv";
				$insert_info = array(
				'CurrentDate' => $current_date,
				'old_file_name' => $OldName,
				'new_file_name' => $NewName,
				'record_count' => $cnt,
				'createdon' => date('Y-m-d H:i:s'));
			//	$this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
				$message = "Dear Team, <br/><br/>  GARP-FRR Exam Applications Count for ".$date_format." - ".$exam_cnt."<br>Aggregate Applications Count - ".$cnt."<br/><br/> Yours truly,<br/>IIBF Team<br/>";
				## Removed soumya@iibf.org.in email as per client request on 25th May
				$info_arr = array('to'=>'soumya@iibf.org.in,smuralidaran@iibf.org.in,iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com', 'subject'=>'IIBF GARP-FRR Daily Report of '. $yesterday,'message'=>$message);
			//	$info_arr = array('to'=>'iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com',  'subject'=>'IIBF GARP-FRR Daily Report of '. $yesterday,'message'=>$message);
				$files=array($newPath);
				//$this->Emailsending->mailsend_attch($info_arr,$files);
			//	$this->Emailsending->mailsend($info_arr);
			}
			else
			{
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				fwrite($fp1, "No data found for the date: ".$yesterday." \n");
				// File Rename Functinality
				$oldPath = $cron_file_dir.$current_date."/GARP_".$current_date.".csv";
				$newPath = $cron_file_dir.$current_date."/GARP_".date('dmYhi')."_0.csv";
				rename($oldPath,$newPath);
				$OldName = "GARP_".$current_date.".csv";
				$NewName = "GARP_".date('dmYhi')."_0.csv";
				$insert_info = array(
				'CurrentDate' => $current_date,
				'old_file_name' => $OldName,
				'new_file_name' => $NewName,
				'record_count' => $cnt,
				'createdon' => date('Y-m-d H:i:s'));
			//	$this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
				$success[] = "No data found for the date";
				$message = "Dear Team, <br/><br/>  There are no registrations for date  ".$date_format." .<br/><br/><br/> Yours truly,<br/>IIBF Team<br/>";
				$info_arr = array('to'=>'soumya@iibf.org.in,smuralidaran@iibf.org.in,iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF GARP-FRR Daily Report of '. $yesterday,'message'=>$message);
				//$info_arr = array('to'=>'iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com', 'subject'=>'IIBF GARP-FRR Daily Report of '. $yesterday,'message'=>$message);
				//$files=array($newPath);
				//$this->Emailsending->mailsend_attch($info_arr,$files);
			//	$this->Emailsending->mailsend($info_arr);
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("GARP-FRR Examination CSV Details Cron Execution End", $desc);
			fwrite($fp1, "\n"."***** GARP-FRR Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
			fclose($fp1);
		
	}
}

/** FUNCTION ADDED  TO GENERATE garp INVOICE using payment trans id  garp by swati***/
	public function GenerateGarp_invoice_custom()
	{
	    
        die;
		error_reporting(E_ALL);
        //payment trns id in array
		$arr = array(5154361,5154442,5154505,5154509,5154522,5154551,5154574,5154591,5154652);
	
//	$arr=array(); 
		for($i=0;$i<sizeof($arr);$i++)
		{
		        $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array( 'id' => $arr[$i]), 'ref_id,member_regnumber,status,id,transaction_no,date');
            $reg_id        = $get_user_regnum_info[0]['ref_id'];
            $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
            
            $transaction_no= $get_user_regnum_info[0]['transaction_no'];
            
            $date_of_invoice = $get_user_regnum_info[0]['date'];
            
            $reg_data = $this->Master_model->getRecords('garp_exam_registration', array('member_no' => $applicationNo, 'mem_exam_id' => $reg_id),'exam_code');
            $selected_exam_code =$reg_data[0]['exam_code'];
        
          /* Get User Attempt */
            $attemptQry=$this->db->query("SELECT attempt,fee_flag FROM garp_exam_eligible WHERE member_no='".$applicationNo."' AND exam_code = '" . $selected_exam_code . "' LIMIT 1"); 
            $attemptArr = $attemptQry->row_array();
            $attempt = $attemptArr['attempt'];
            $fee_flag=$attemptArr['fee_flag'];
            $attempt = $attempt+1; 
 
		     $blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
            $memberexam_data = array('pay_status'=>1,  'modified_on'=>date('Y-m-d H:i:s'));
             //$regno=$this->session->userdata['memberdata']['regno'];
            $this->master_model->updateRecord('garp_exam_registration',$blended_data,array('mem_exam_id'=>$reg_id,'member_no'=>$applicationNo));
           $this->master_model->updateRecord('member_exam',$memberexam_data,array('id'=>$reg_id));
           $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'garp_email'));
      if (!empty($applicationNo)) {
           $user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
           }
               if (count($emailerstr) > 0) 
            {
               // echo "swati";
            $Qry=$this->db->query("SELECT exam_code, qualification FROM garp_exam_registration WHERE mem_exam_id = '".$reg_id."' LIMIT 1");
            $detailsArr        = $Qry->row_array();
             $exam_code = $detailsArr['exam_code'];
            $exam_name ='GARP-FRR Exam'; //$detailsArr['qualification'];
             $newstring    = str_replace("#exam_name#","".$exam_name."",$emailerstr[0]['emailer_text']);
              /* Set Email sending options */
              $info_arr          = array(
                                  'to' => ''.$user_info[0]['email'].',swati.watpade@esds.co.in',
                                  'from' => $emailerstr[0]['from'],
                                  'subject' => $emailerstr[0]['subject'],
                                  'message' => $newstring
                            );
                           // echo $get_user_regnum_info[0]['id'];
              $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $get_user_regnum_info[0]['id']));
             // echo $this->db->last_query(); die;
              $zone_code = "";
              $zoneArr = array();
              //$regno = $this->session->userdata['memberdata']['regno'];
              
              /* Invoice Number Genarate Functinality */
              if (count($getinvoice_number) > 0){
                  //echo 'swati';
              $invoiceNumber =custom_generate_GARP_invoice_number($getinvoice_number[0]['invoice_id']);
              //  echo $invoiceNumber.'swa';die;
              if($invoiceNumber)
              {
                $invoiceNumber=$this->config->item('garp_invoice_no_prefix').$invoiceNumber;
              }
                  $update_data = array(
                                     'invoice_no' => $invoiceNumber,
                                    //'member_no' => $applicationNo,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => $date_of_invoice,
                                    'modified_on' => $date_of_invoice
                                );
                                //$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('pay_txn_id' => $get_user_regnum_info[0]['id']));
                /* Invoice Genarate Function */
                                $attachpath = genarate_garp_invoice_custom($getinvoice_number[0]['invoice_id']);
                                $this->Emailsending->mailsend_attch($info_arr,$attachpath);
			echo "<br>";
			echo "<br>".$path = $attachpath;
		}
	}
	}
}

}