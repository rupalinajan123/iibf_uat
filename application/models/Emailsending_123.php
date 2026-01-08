<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Emailsending_123 extends CI_Model
{ 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
	}
	
	// SMTP email setting here
	public function setting_smtp()
	{
		$permission=TRUE;
		
		if($permission==TRUE)
		{
			$config['protocol']    	= 'smtp';
			$config['smtp_host']    = 'serveriibf.esdsconnect.com';
			//$config['smtp_host']    = '115.124.123.26';
			$config['smtp_port']    = '25';
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = 'logs@iibf.esdsconnect.com';
			$config['smtp_pass']    = 'logs@IiBf!@#';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not  
			$this->email->initialize($config);	
		}
	}
	
	public function sendmail($info_arr)
	{	


		$this->setting_smtp();
		$this->email->clear(TRUE);
		$this->email->set_newline("\r\n");
		$this->email->from($info_arr['from'],"iibf.com");
		$this->email->to($info_arr['to']);
		$this->email->subject($info_arr['subject']);
		$this->email->set_mailtype("html");
		$data['base_url']=base_url();
		$this->email->message($info_arr['message']);
		if($this->email->send())
		{
			 $this->email->print_debugger();
			//	echo $this->email->print_debugger();
			return true;
		}
		
	}
	
	public function mailsend($info_arr)
	{
		$this->setting_smtp();
		//$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
		
		//$this->email->initialize($config);
		//$this->email->from(trim($info_arr['from']),"iibf.com"); 
		$this->email->from('logs@iibf.esdsconnect.com',"IIBF"); 
		$this->email->reply_to('noreply@iibf.org.in', 'IIBF');
		$this->email->to($info_arr['to']);
		$this->email->cc('logs@iibf.esdsconnect.com');	// CC email added by Bhagwan Sahane, on 22-04-2017
		if(isset($info_arr['bcc']))
		{
			$this->email->bcc($info_arr['bcc']);
		}
		$this->email->subject($info_arr['subject']);
		$this->email->message($info_arr['message']);
		if($this->email->send())
		{
			//$this->email->print_debugger();
			//echo $this->email->print_debugger();
			return true;
		}
	}
	
	public function mailsend_attch($info_arr,$path=NULL)
	{
		$this->setting_smtp();
		//$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
			//				$this->email->initialize($config);
							//$this->email->from($info_arr['from'],"iibf.com"); 
							$this->email->from('logs@iibf.esdsconnect.com',"IIBF"); 
							$this->email->to($info_arr['to']);
							$this->email->reply_to('noreply@iibf.org.in', 'IIBF');
							$this->email->cc('logs@iibf.esdsconnect.com');	// CC email added by Bhagwan Sahane, on 03-06-2017
							$this->email->subject($info_arr['subject']);
							$this->email->message($info_arr['message']);
							if($path!=NULL || $path!='')
							{
								$this->email->attach($path);
							}
							if($this->email->send())
							{
			 					//$this->email->print_debugger();
								//	echo $this->email->print_debugger();
								$this->email->clear(TRUE);
								return true;
							}
							
		}
	public function sendmail_attach($info_arr,$other_info,$path)
	{
		
		$this->setting_smtp();
		$this->email->clear(TRUE);
		$this->email->set_newline("\r\n");
		$this->email->from($info_arr['from'],"jodiwale.com ");
		$this->email->to($info_arr['to']);
		$this->email->subject($info_arr['subject']);
		$this->email->set_mailtype("html");
		$data['base_url']=base_url();
		$this->email->message($this->load->view('email/'.$info_arr['view'],$other_info,true)); 
		$this->email->attach($path);
		if($this->email->send())
		{return true;}           	
		
	}
}
?>