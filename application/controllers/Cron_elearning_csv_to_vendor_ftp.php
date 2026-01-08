<?php
  /********************************************************************
  * Description	: Daily Cron - Copy E-learning separate module data csv from ESDS FTP to VENDOR FTP
  * Created BY	: Sagar Matale
  * Created On	: 01-07-2021
  * Update By		: Sagar Matale  
  * Updated on	: 07-07-2021  
	********************************************************************/
  
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_elearning_csv_to_vendor_ftp extends CI_Controller  
{
	public function __construct()
  {
		parent::__construct();
		
    $this->load->model('Master_model'); 
    $this->load->model('log_model');
    $this->load->model('Emailsending');
		
		error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("memory_limit", "-1");		
	}
  
  
	public function send_elearning_data_to_kesdee()
	{
		$vendor_name = 'KESDEE';
    $cron_date = date('Y-m-d'); //'2021-10-12'; // 
		$this->send_elearning_data_to_vendor_common($vendor_name, $cron_date);
	}
	
  public function send_elearning_data_to_sify()
	{exit;
		$vendor_name = 'SIFY';
    $cron_date = date('Y-m-d'); //'2021-10-12'; //   
		$this->send_elearning_data_to_vendor_common($vendor_name, $cron_date);
	}

  public function send_elearning_data_to_teamlease()
  {
    $vendor_name = 'teamlease';
    $cron_date = date('Y-m-d'); //'2021-10-12'; //   
    $this->send_elearning_data_to_vendor_common($vendor_name, $cron_date);
  }
  

 
  public function send_elearning_data_to_vendor_common($vendor_name='', $cron_date='')
	{
    if($vendor_name != '' && $cron_date != '')
    {
      $current_date = date("Ymd", strtotime($cron_date));
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($cron_date)));
      $start_time = date("Y-m-d H:i:s");
      $success = $error = array();
      
      $record_count = $new_file_name = '';
      $send_mail_flag = 0;
      
      $this->log_model->cronlog("Cron for E-learning separate module data csv file transfer from ESDS FTP to vendor ".$vendor_name." ftp : Start", json_encode(array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => ""))); //store log in table cronlogs
      
      $csv_file_data = $this->master_model->getRecords('spm_elearning_cron_csv',array('CurrentDate'=>date('Y-m-d',strtotime($current_date)), 'vendor_name'=>$vendor_name),'new_file_name,record_count', array('id'=>'DESC'), '0', '1');
      
      if(count($csv_file_data) > 0)
      {		
        $new_file_name = $csv_file_data[0]['new_file_name'];
        $record_count = $csv_file_data[0]['record_count'];
        
        $file_list = array($new_file_name);
        
        // FTP ACCOUNT TEST SERVER FROM VINOD
        $ftp_host = '115.124.109.58';
        $ftp_user_name = 'phptest';	
        $ftp_user_pass = 'v7nHQm8dZcj4';      
        $conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");// connect using basic FTP
        
        $login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);// login to FTP
        
        if($login_result)// CHECK IF FTP CONNECT TO REMOTE SERVER
        {
          $local_server_dir_path = '/home/supp0rttest/public_html/uploads/elearning_'.$vendor_name.'/'.$current_date.'/';
          //$local_server_dir_path = '/home/iibfgrow/public_html/uploads/elearning_'.$vendor_name.'/'.$current_date.'/';
          $remote_server_dir_path = "/".$vendor_name."/elearning/";
        
          foreach($file_list as $key => $file_name)// send each file to FTP
          {
            // SET MODE ACCORDING TO FILE TYPE
            $mode = FTP_ASCII;	// FOR TEXT FILE
            $info = pathinfo($file_name);
            if($info["extension"] == "zip") { $mode = FTP_BINARY; } // FOR ZIP FILE
          
            $local_file_path = $local_server_dir_path.$file_name; 
            $remote_file_path = $remote_server_dir_path.$file_name; 
          
            // CHECK IF LOCAL FILE IS PRESENT 
            if(!file_exists($local_file_path))
            {
              $error[] = "File not exist : $file_name.";
            }
            else
            {
              // GET FILE SIZE 
              $file_size = filesize($local_file_path);
              
              if (@ftp_put($conn, $remote_file_path, $local_file_path, $mode)) //SEND LOCAL FILE TO FTP
              {
                $success[] = "Successfully file transfer : $file_name (size : $file_size).";
                $send_mail_flag = 1;
              }
              else
              {
                $error[] = "There was a problem while file transfer : $file_name (size : $file_size).";
              }
            }
          }
        }			
        else
        {
          $error[] = "There was a problem while login to remote server ftp.";	
        }
        ftp_close($conn);// CLOSE THE CONNECTION 
      }
      else
      {
        $error[] = "There was no record in database for csv file.";	
      }
      
      $end_time = date("Y-m-d H:i:s");
          
      $this->log_model->cronlog("Cron for E-learning separate module data csv file transfer from ESDS FTP to vendor ".$vendor_name." ftp : End", json_encode(array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time))); //store log in table cronlogs
          
      //echo "<pre>"; print_r($success); echo "<br>"; print_r($error); echo "</pre>";
      
      if($send_mail_flag == 1)
      {
        $msg = '';
        if($record_count > 0)
        {
          $msg .= 'Dear IIBF LMS Support Team,<br><br>';
          $msg .= 'The file named '.$new_file_name.' with a total of '.$record_count.' records has been copied to the SFTP location on '.date("Y/m/d H:i:s").' for further processing.  Please inform the IIBF team once the file has been processed.<br><br>';
          $msg .= 'If the file gets corrupted or there is mismatch in the number of records, please inform iibfdevp@esds.co.in.<br><br>';
          $msg .= 'Regards,<br>';
          $msg .= 'The ESDS Team.';
        }
        else
        {
          $msg .= 'Dear IIBF LMS Support Team,<br><br>';
          $msg .= 'No data found for the date: '.$yesterday.'<br><br>';
          $msg .= 'Regards,<br>';
          $msg .= 'The ESDS Team.';
        }
        
        $result = $this->sendmail($current_date, $msg, $vendor_name);// SEND EMAIL NOTIFICATION
      }
    }
	}
	
	public function sendmail($current_date = "", $msg = "", $vendor_name = "")
	{
		if($current_date == "") { $current_date = date("Ymd"); }
		
		$from_name = 'IIBF';
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'E-learning Separate module CSV File Transfer To '.$vendor_name.' Vendor';
    
    if($vendor_name == 'KESDEE')
    {
      $recipient_list = array('manojm@kesdee.com', 'iibfdevp@esds.co.in');
      //$recipient_list = array('sagar.matale@esds.co.in');
    }
    else if($vendor_name == 'SIFY') 
    {exit;
      $recipient_list = array('livewire.helpdesk@sifycorp.com', 'iibfdevp@esds.co.in');
      //$recipient_list = array('sagar.matale@esds.co.in');
    }
    else
    {
      $recipient_list = array('iibfdevp@esds.co.in');
    }
		
		$attachment_filename = 'logs_elearning_'.$vendor_name.'_'.$current_date.'.txt';
		$attachment_path = './uploads/elearning_'.$vendor_name.'/'.$current_date.'/' . $attachment_filename;
		
    $config = array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
		
		$this->email->initialize($config);
		$this->email->from($from_email, $from_name);
		$this->email->to($recipient_list);
		$this->email->subject($subject);
		$this->email->message($msg);
		/* if($attachment_path != '') 
		{
			$this->email->attach($attachment_path);
		} */
    
		if($this->email->send())
		{
			//echo 'Email Sent.';
			//$this->email->print_debugger();
			//echo $this->email->print_debugger();
			return 'Email successfully send';
		}
		else
		{
			//echo 'Email Not Sent.';			
			return 'Email not send <br>'.$this->email->print_debugger();
		}
	}
	
}