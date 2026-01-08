 <?php
/*
 * Controller Name    :    Cron File Transfer
 * Created By         :    Bhushan
 * Created Date       :    21 March 2018
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_csv_ftp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('log_model');
        $this->load->library('email');
        //error_reporting(E_ALL);
        //ini_set('display_errors', 1);
    }
    public function index($current_date = "")
    {
        $start_time = date("Y-m-d H:i:s");
        $success    = array();
        $error      = array();
        
        // cron initialise log -
        $result = array(
            "success" => $success,
            "error" => $error,
            "Start Time" => $start_time,
            "End Time" => ""
        );
        $desc   = json_encode($result);
        $this->log_model->cronlog("File Transfer Cron - Start", $desc);
        
        if ($current_date == "") {
            $current_date = date("Ymd");
        }
        
        // Get File Names
        $CurrentDate = date('Y-m-d', strtotime($current_date));
        $this->db->where('CurrentDate', $CurrentDate);
        $array         = $this->master_model->getRecords('cron_csv', '', 'new_file_name,record_count');
        $new_file_name = $array[0]['new_file_name'];
        $record_count  = $array[0]['record_count'];
        
        // files list -
        $file_list = array(
            $new_file_name
        );
        
        // file wise remote server path -
        $path_list = array(
            "/IIBF/"
        );
        //$path_list = array("/public_html/uploads/Save_CSV/");
        
        //FTP Account (Remote Server) - Development Server
        /*$ftp_host         = '115.124.120.228';
        $ftp_user_name     = 'iibfgrow';    
        $ftp_user_pass     = '7Uv7guf$r';*/
        
        // FTP Account Test Server from Vinod
        $ftp_host      = '115.124.109.58';
        $ftp_user_name = 'phptest';
        $ftp_user_pass = 'v7nHQm8dZcj4';
        
        /*$ftp_host         = '115.124.120.143';
        $ftp_user_name     = 'tgdemo';    
        $ftp_user_pass     = '5rjrzRwPaX';*/
        
        /*$ftp_host         = '59.185.104.27';
        $ftp_user_name     = 'fromweb';    
        $ftp_user_pass     = 'fromweb@123';*/
        
        //remote server directory path -
        //$remote_server_dir_path = 'public_html/ftp_uploads/cronfiles/';
        //$remote_server_dir_path = '/public_html/tgpublic/iibf_test/ftp_upload/cronfiles/';
        
        //local server directory path -
        //$local_server_dir_path = '/home/tgdemo/public_html/tgpublic/iibf/uploads/cronfiles/'.$current_date.'/';
        
        //$local_server_dir_path = '/home/iibfgrow/public_html/uploads/cronCSV/'.$current_date.'/';
        
        $local_server_dir_path = '/home/supp0rttest/public_html/uploads/cronCSV/' . $current_date . '/';
        
        // connect using basic FTP -
        $conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
        
        // login to FTP -
        $login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
        
        // check if FTP Connect to remote server -
        if ($login_result) {
            // send each file to FTP -
            foreach ($file_list as $key => $file_name) {
                // remote server file path -
                $remote_server_dir_path = $path_list[$key];
                
                // set mode according to file type -
                $mode = FTP_ASCII; // for text file
                
                $info = pathinfo($file_name);
                if ($info["extension"] == "zip") {
                    $mode = FTP_BINARY; // for zip file    
                }
                
                // local file path -
                $local_file_path = $local_server_dir_path . $file_name;
                
                // remote file path -
                $remote_file_path = $remote_server_dir_path . $file_name;
                
                // check if local file is present -
                if (!file_exists($local_file_path)) {
                    $error[] = "File not exist : $file_name.";
                } else {
                    // get file size -
                    $file_size = filesize($local_file_path);
                    
                    // send local file to FTP -
                    if (ftp_put($conn, $remote_file_path, $local_file_path, $mode)) {
                        //echo "Successfully file transfer : File Name - $file_name. <br>";
                        $success[] = "Successfully file transfer : $file_name (size : $file_size).";
                    } else {
                        //echo "There was a problem while file transfer : File name - $file_name. <br>";
                        $error[] = "There was a problem while file transfer : $file_name (size : $file_size).";
                    }
                }
            }
            
            // close the connection -
            ftp_close($conn);
        } else {
            $error[] = "There was a problem while login to remote server ftp.";
        }
        
        $end_time = date("Y-m-d H:i:s");
        
        // cron end log -
        $result = array(
            "success" => $success,
            "error" => $error,
            "Start Time" => $start_time,
            "End Time" => $end_time
        );
        $desc   = json_encode($result);
        $this->log_model->cronlog("CSV File Transfer Cron - Ends", $desc);
        
        echo "<pre>";
        print_r($success);
        echo "<br>";
        print_r($error);
        
        echo "<br>Start : " . $start_time;
        echo "<br>";
        echo "End : " . $end_time;
        
        if ($record_count != 0) {
            // mail details -
            $msg = '';
            $msg .= 'Dear IIBF LMS Support Team,<br><br>';
            $msg .= 'The file named ' . $new_file_name . ' with a total of ' . $record_count . ' records has been copied to the SFTP location on ' . date("Y/m/d H:i:s") . ' for further processing.  Please inform the IIBF team once the file has been processed.<br><br>';
            $msg .= 'If the file gets corrupted or there is mismatch in the number of records, please inform iibfdevp@esds.co.in.<br><br>';
            $msg .= 'Regards,<br>';
            $msg .= 'The ESDS Team.';
        } else {
            // mail details -
            $yesterday = date('Y-m-d', strtotime("- 1 day"));
            $msg       = '';
            $msg .= 'Dear IIBF LMS Support Team,<br><br>';
            $msg .= 'No data found for the  date: ' . $yesterday . '<br><br>';
            $msg .= 'Regards,<br>';
            $msg .= 'The ESDS Team.';
            
        }
        // send email notification -
        $result = $this->sendmail($current_date, $msg);
    }
    
    public function sendmail($current_date = "", $msg = "")
    {
        if ($current_date == "") {
            $current_date = date("Ymd");
        }
        
        $from_name  = 'IIBF';
        //$from_email = 'noreply@iibf.org.in';
        $from_email = 'logs@iibf.esdsconnect.com';
        $subject    = 'Member Exam Applications CSV File Transfer To TATA Interactive Services Server';
        
        // New Email List : Updated on 18 June 2018 - Bhushan
        $recipient_list = array(
            'Kanhiya.agrawal@mpsinteractive.com',
            'Sandeep.agarwal@mpsinteractive.com',
            'barun.yadav@mpsinteractive.com',
            'iibflmssupport@mpsinteractive.com',
            'kalpanashetty@iibf.org.in',
            'dattatreya@iibf.org.in',
            'sgbhatia@iibf.org.in',
            'elearning@iibf.org.in',
            'smuralidaran@iibf.org.in',
            'iibfdevp@esds.co.in'
        );
        
        
        //$recipient_list = array('bhushan.amrutkar@esds.co.in');
        
        $attachment_filename = 'logs_' . $current_date . '.txt';
        $attachment_path     = './uploads/cronCSV/' . $current_date . '/' . $attachment_filename;
        
        $message = '' . $msg;
        
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        );
        
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($recipient_list);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($attachment_path != '') {
            $this->email->attach($attachment_path);
        }
        if ($this->email->send()) {
            echo 'Email Sent.';
            //$this->email->print_debugger();
            //echo $this->email->print_debugger();
            return true;
        } else {
            echo 'Email Not Sent.';
            
            return false;
        }
    }
    
}

// Email Receipient List -
//$recipient_list = array('kalpanashetty@iibf.org.in','shruti.samdani@esds.co.in', 'bhushan.amrutkar@esds.co.in', 'bhagwan.sahane@esds.co.in', 'prafull.tupe@esds.co.in',  'pawansing.pardeshi@esds.co.in', 'pooja.godse@esds.co.in', 'tejasvi.bhavsar@esds.co.in', 'ronit.powar@esds.co.in', 'anilr@esds.co.in', 'dattatreya@iibf.org.in',  'sgbhatia@iibf.org.in','IibflmsSupport@tatainteractive.com', 'sandeepa@tatainteractive.com', 'elearning@iibf.org.in', 'smuralidaran@iibf.org.in');

// Old List/*$recipient_list=array('kalpanashetty@iibf.org.in','dattatreya@iibf.org.in','sgbhatia@iibf.org.in','IibflmsSupport@tatainteractive.com','sandeepa@tatainteractive.com','elearning@iibf.org.in','smuralidaran@iibf.org.in','iibfdevp@esds.co.in');*/ 