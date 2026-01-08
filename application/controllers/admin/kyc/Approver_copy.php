<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Approver_copy extends CI_Controller {
			
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->library('email');
		$this->load->model('KYC_Log_model'); 
		$this->load->model('Emailsending');
		$this->load->model('Chk_KYC_session');
	}
	
	public function index()
	{
		die();	// remove this when run
		
		ini_set('memory_limit', '128M');

		set_time_limit(0);
		error_reporting(E_ALL);
		
		$start = date("Y-m-d h:i:s");

		echo 'Batch : 3 <br>';
		
		$total_cnt = 0;
			
		$img_found = array();
		$img_not_found = array();
		
		$img_renamed = array();
		$img_not_renamed = array();
		
		// get image path for given member nos.
		//$member_reg = $this->db->query("SELECT reg_no, regnumber, image_path FROM member_registration WHERE isactive = '1' AND scannedphoto = '' AND regnumber IN ('500210153')");
		$member_reg = $this->db->query("SELECT reg_no, regnumber, image_path FROM member_registration WHERE isactive = '1' AND scannedsignaturephoto = '' AND regnumber IN ('500210153')");
		//$member_reg = $this->db->query("SELECT reg_no, regnumber, image_path FROM member_registration WHERE isactive = '1' AND idproofphoto = '' AND regnumber IN ('510331121')");
						
									
		echo $this->db->last_query(); exit;
		
		if($member_reg->num_rows() > 0)
		{
			foreach($member_reg->result_array() as $row)
			{
				//print_r($row);
				
				$reg_no = $row['reg_no'];
				$regnumber = $row['regnumber'];
				$image_path = $row['image_path'];
				
				// photo image
				/*$image_path = 'uploads'.$image_path."photo/p_".$reg_no.".jpg";	// original image
				//uploads/iibf_mem_reg/uploads/2015-10-06/22/photo/p_810094117.jpg
				if(file_exists($image_path))
				{
					$img_found[] = $regnumber;
					
					$img_not_renamed[] = $regnumber;
					
					$file_path = implode('/', explode('/', $image_path, -1));
					$photo_file = 'k_p_'.$reg_no.'.jpg';
					if( @ rename($image_path, $file_path.'/'.$photo_file) )
					{
						echo "<br>Image Rename Successfully. : " . $regnumber . "<br>";	
					}
					else
					{
						echo "<br>Image Not Rename Successfully. : " . $regnumber . "<br>";	
					}
				}
				else
				{
					$image_path = 'uploads'.$image_path."photo/k_p_".$reg_no.".jpg";	// renamed image
					if(file_exists($image_path))
					{
						$img_renamed[] = $regnumber;
					}
					else
					{
						$img_not_found[] = $regnumber;	
					}
				}*/
				
				// sign
				$image_path = 'uploads'.$image_path."signature/s_".$reg_no.".jpg";	// original image
				//uploads/iibf_mem_reg/uploads/2016-02-05/22/signature/s_810184905.jpg
				if(file_exists($image_path))
				{
					$img_found[] = $regnumber;
					
					$img_not_renamed[] = $regnumber;
					
					$file_path = implode('/', explode('/', $image_path, -1));
					$photo_file = 'k_s_'.$reg_no.'.jpg';
					if( @ rename($image_path, $file_path.'/'.$photo_file) )
					{
						echo "<br>Sign Image Rename Successfully. : " . $regnumber . "<br>";	
					}
					else
					{
						echo "<br>Sign Image Not Rename Successfully. : " . $regnumber . "<br>";	
					}
				}
				else
				{
					$image_path = 'uploads'.$image_path."signature/k_s_".$reg_no.".jpg";	// renamed image
					if(file_exists($image_path))
					{
						$img_renamed[] = $regnumber;
					}
					else
					{
						$img_not_found[] = $regnumber;	
					}
				}
				
				// idproof
				/*$image_path = 'uploads'.$image_path."idproof/pr_".$reg_no.".jpg";	// original image
			
				if(file_exists($image_path))
				{
					$img_found[] = $regnumber;
					
					$img_not_renamed[] = $regnumber;
					
					$file_path = implode('/', explode('/', $image_path, -1));
					$photo_file = 'k_pr_'.$reg_no.'.jpg';
					if( @ rename($image_path, $file_path.'/'.$photo_file) )
					{
						echo "<br>Image Rename Successfully. : " . $regnumber . "<br>";	
					}
					else
					{
						echo "<br>Image Not Rename Successfully. : " . $regnumber . "<br>";	
					}
				}
				else
				{
					$image_path = 'uploads'.$image_path."idproof/k_pr_".$reg_no.".jpg";	// renamed image
					if(file_exists($image_path))
					{
						$img_renamed[] = $regnumber;
					}
					else
					{
						$img_not_found[] = $regnumber;	
					}
				}*/
				
				$total_cnt++;
			}	
		}
		
		$end = date("Y-m-d h:i:s");
	
		echo "<br>";
		echo $start . " - " . $end;
		
		echo '<br><br>Images Found : <br>';
		print_r($img_found);
		echo '<br><br>Images Not Found : <br>';
		print_r($img_not_found);
		
		echo '<br><br>Images Renamed : <br>';
		print_r($img_renamed);
		echo '<br><br>Images Not Renamed : <br>';
		print_r($img_not_renamed);
	}
	
	// SMTP email setting here
	public function setting_smtp()
	{
		$permission = TRUE;
		
		if($permission == TRUE)
		{
			/*$config['protocol']    	= 'SMTP';
			$config['smtp_host']    = 'iibf.esdsconnect.com';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '10';
			$config['smtp_user']    = 'logs@iibf.esdsconnect.com';
			$config['smtp_pass']    = 'logs@IiBf!@#';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not*/
			
			
			//$config['protocol']   	= 'SMTP';
			$config['protocol']    		= 'smtp';
			//$config['smtp_host']    	= 'ssl://iibf.esdsconnect.com';
			$config['smtp_host']    	= 'dc-e4b90dce110d.esdsconnect.com';
			//$config['smtp_port']  	= '465';
			$config['smtp_port']    	= 465;
			//$config['smtp_timeout']	= '10';
			$config['smtp_timeout'] 	= 30;
			$config['smtp_user']    	= 'logs@iibf.esdsconnect.com';
			$config['smtp_pass']    	= 'logs@IiBf!@#';
			$config['charset']    		= 'utf-8';
			//$config['newline']    	= "\r\n";
			$config['mailtype'] 		= 'html';
			//$config['smtp_crypto'] 		= 'ssl';
			$config['validation'] 		= TRUE;
			
			$this->email->initialize($config);
		}
	}
	
	public function mailsend_attch($info_arr,$path=NULL)
	{
		//print_r($info_arr); die();
		
		$this->setting_smtp();
		//$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
		//$this->email->initialize($config);
		//$this->email->from($info_arr['from'],"iibf.com"); 
		$this->email->set_newline("\r\n");
		//$this->email->set_crlf("\r\n");
		$this->email->from('logs@iibf.esdsconnect.com',"IIBF"); 
		$this->email->to($info_arr['to']);
		$this->email->reply_to('noreply@iibf.org.in', 'IIBF');
		//$this->email->cc('logs@iibf.esdsconnect.com');	// CC email added by Bhagwan Sahane, on 03-06-2017
		$this->email->subject($info_arr['subject']);
		$this->email->message($info_arr['message']);
		if($path != NULL || $path!='')
		{
			$this->email->attach($path);
		}
		if($this->email->send())
		{
			print_r($this->email->print_debugger());
			$this->email->clear(TRUE);
			//return true;
		}
		
		print_r($this->email->print_debugger());
		
		echo 'DONE 4';
		
		$host= gethostname();
		$ip = gethostbyname($host);
		echo " - ".$ip;
		
		die();
	}
	
	public function test_mail()
	{
		$info_arr = array();
		
		//$info_arr['to'] = 'bvsahane89@gmail.com';
		$info_arr['to'] = 'sagar.deshmukh_2626@rediffmail.com';
		$info_arr['subject'] = 'Test Email from Production Server By Developer.';
		$info_arr['message'] = 'This is a Test Email from Production Server By Developer. Thanks !!!';
		
		$attachpath = '';
		
		$this->mailsend_attch($info_arr,$attachpath);
	}
	
}