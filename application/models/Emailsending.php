<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Emailsending extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('email');
  }

  // SMTP email setting here
  public function setting_smtp()
  {
    $permission = TRUE;

    if ($permission == TRUE) {
      $config['protocol']      = 'SMTP';
      //$config['smtp_host']    = 'iibf.esdsconnect.com';
      // local ip 10.11.38.100 instead of 115.124.108.41 can also be used
      $config['smtp_host']    = '115.124.108.41';
      $config['smtp_port']    = '25';
      $config['smtp_timeout'] = '10';
      $config['smtp_user']    = 'logs@iibf.esdsconnect.com';
      $config['smtp_pass']    = 'logs@IiBf!@#';
      $config['charset']      = 'utf-8';
      $config['newline']      = "\r\n";
      $config['mailtype']   = 'html'; // or html
      $config['validation']   = TRUE; // bool whether to validate email or not  
      $this->email->initialize($config);
    }
  }
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
  public function sendmail($info_arr)
  {
    if ($this->get_client_ip_email() == '115.124.115.69' || $this->get_client_ip_email() == '115.124.115.755' || $this->get_client_ip_email() == '182.73.101.70') {
      return 1;
    }
    $this->setting_smtp();
    $this->email->clear(TRUE);
    $this->email->set_newline("\r\n");
    $this->email->from($info_arr['from']);

    //$this->email->to($info_arr['to']);		
    if (strpos($info_arr['to'], '@esds.co.in')) {
      $this->email->to($info_arr['to']);
    } elseif (in_array($info_arr['to'], array('ampregistrations@iibf.org.in'))) {
      $this->email->to($info_arr['to']);
    } else {
      $this->email->to('iibfdevp@esds.co.in');
    }

    //if(isset($info_arr['cc'])) { $this->email->cc($info_arr['cc']); }
    //if(isset($info_arr['bcc'])) { $this->email->bcc($info_arr['bcc']); }		
    if (strpos($info_arr['cc'], '@esds.co.in')) {
      $this->email->cc($info_arr['cc']);
    } elseif (in_array($info_arr['cc'], array('ampregistrations@iibf.org.in'))) {
      $this->email->cc($info_arr['cc']);
    } else {
      $this->email->cc('sagar.matale@esds.co.in', 'maheshwari.patil@esds.co.in');
    }

    $this->email->subject($info_arr['subject'] . ' Pre-Production'); //Added by Priyanka W for DRA testing
    $this->email->set_mailtype("html");
    $data['base_url'] = base_url();
    $this->email->message($info_arr['message']);
    if ($this->email->send()) {
      $this->email->print_debugger();
      //	echo $this->email->print_debugger();
      return true;
    }
  }

  public function mailsend($info_arr)
  {
    // echo "Hiii";exit;
    // if ($this->get_client_ip_email() == '115.124.115.70' || $this->get_client_ip_email() == '115.124.115.75')
    // { //|| $this->get_client_ip_email() =='182.73.101.70'
    //   return 1;
    // }

    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from(trim($info_arr['from']),"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');

    // $arr_email = ['ksaurav851@gmail.com','jatinarora2730@gmail.com','ampregistrations@iibf.org.in','je.exm4@iibf.org.in','Je.mss3@iibf.org.in','Je.mss4@iibf.org.in','pooja.mane@esds.co.in'];

    // $arr_email = ['ampregistrations@iibf.org.in', 'pooja.mane@esds.co.in','dd.it2@iibf.org.in','ad.it1@iibf.org.in','kalpanashetty@iibf.org.in','dd.it1@iibf.org.in','amit@iibf.org.in','ad.exm1@iibf.org.in','Avadhut.Pawar@esds.co.in','je.exm2@iibf.org.in', 'je.exm5@iibf.org.in', 'je.exm8@iibf.org.in', 'se.exm3@iibf.org.in', 'je.exm1@iibf.org.in','je.exm1@iibf.org.in','dd.exm2@iibf.org.in','ad.exm1@iibf.org.in','je.exm1@iibf.org.in','dd.it2@iibf.org.in','dd.it2@iibf.org.in','rupali.najan@esds.co.in'];
    $arr_email = ['pooja.mane@esds.co.in', 'Avadhut.Pawar@esds.co.in', 'rupali.najan@esds.co.in', 'pingaleshweta10@gmail.com', 'chetan.bhamare121@gmail.com', 'dattatreyahegde22@gmail.com','dd.it2@iibf.org.in','je.aca2@iibf.org.in']; //|| $info_arr['to']=='dd.it2@iibf.org.in''dd.it2@iibf.org.in',

    //$this->email->to($info_arr['to']);
    if (strpos($info_arr['to'], '@esds.co.in')) {
      $this->email->to($info_arr['to']);
    } elseif (in_array($info_arr['to'], $arr_email)) {
      $this->email->to($info_arr['to']);
    } else {
      $this->email->to('iibfdevp@esds.co.in');
      //$this->email->to($info_arr['to']);
    }

    //$this->email->cc('je.aca2@iibf.org.in');	// CC email added by Bhagwan Sahane, on 22-04-2017
    //$this->email->cc('sagar.matale@esds.co.in');
    //$this->email->cc('je.exm4@iibf.org.in,ad.exm2@iibf.org.in,je.exm1@iibf.org.in,iibfdevp@esds.co.in');
    if (isset($info_arr['cc']) && $info_arr['cc'] != '') {
      //$this->email->cc($info_arr['cc']);
      $this->email->cc('iibfdevp@esds.co.in');
    }
    //$this->email->cc('iibfdevp@esds.co.in,je.exm4@iibf.org.in,ad.exm2@iibf.org.in,je.exm1@iibf.org.in,dd.it2@iibf.org.in,dd.exm2@iibf.org.in');
    //$this->email->cc('iibfdevp@esds.co.in');
    /*if (strpos($info_arr['cc'], '@esds.co.in')) { $this->email->cc($info_arr['cc']); }
	    elseif (in_array($info_arr['cc'], array('ampregistrations@iibf.org.in'))) { $this->email->cc($info_arr['cc']); }*/
    // else { $this->email->cc('sagar.matale@esds.co.in,Gaurav.Shewale@esds.co.in'); }

    if (isset($info_arr['bcc'])) {
      //$this->email->bcc($info_arr['bcc']);
    }
    $this->email->subject($info_arr['subject'] . ' Pre-Production'); // Added by Priyanka W for DRA mail testing
    $this->email->message($info_arr['message']);
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //echo $this->email->print_debugger();
      return true;
    }
  }

  public function mailsend_attch_old($info_arr, $path = NULL)
  {
    if ($this->get_client_ip_email() == '115.124.115.69' || $this->get_client_ip_email() == '115.124.115.75') {
      //return 1;
    }
    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //				$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    $this->email->cc('logs@iibf.esdsconnect.com');  // CC email added by Bhagwan Sahane, on 03-06-2017
    $this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if ($path != NULL || $path != '') {
      $this->email->attach($path);
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }

  public function mailsend_attch_jaiib($info_arr, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69' || $this->get_client_ip_email() == '115.124.115.75' || $this->get_client_ip_email() == '182.73.101.70') {
      return 1;
    }
    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    //$this->email->to('spnair@iibf.org.in');
    //$this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    //$this->email->cc('logs@iibf.esdsconnect.com');	// CC email added by Bhagwan Sahane, on 03-06-2017
    $this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }

  public function mailsend_attch($info_arr, $path)
  {
    // return true;
    if ($this->get_client_ip_email() == '182.73.101.70') {
      //	return true;
    }
    if ($this->get_client_ip_email() == '115.124.115.70' || $this->get_client_ip_email() == '115.124.115.75') { //|| $this->get_client_ip_email() =='182.73.101.70'
      //return 1;
    }

    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    if (/* $info_arr['to'] == 'je.exm1@iibf.org.in' || $info_arr['to'] == 'testlogix@iibf.org.in' || */ $info_arr['to'] == 'pingaleshweta10@gmail.com' || $info_arr['to'] == 'chetan.bhamare121@gmail.com' /* || $info_arr['to'] == 'dd.it2@iibf.org.in' */ || $info_arr['to'] =='dattatreyahegde22@gmail.com' || $info_arr['to'] =='dd.it2@iibf.org.in' || $info_arr['to'] =='je.aca2@iibf.org.in') {
      $this->email->to($info_arr['to']);
    } else
      $this->email->to('iibfdevp@esds.co.in');
    //$this->email->to('anil.s@esds.co.in');

    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');

    //$this->email->cc('je.aca2@iibf.org.in');	// CC email added by Bhagwan Sahane, on 03-06-2017
    //if(isset($info_arr['cc'])) { $this->email->cc($info_arr['cc']); }
    $this->email->cc('iibfdevp@esds.co.in');//,ad.exm2@iibf.org.in,je.exm1@iibf.org.in,dd.it2@iibf.org.in,dd.exm2@iibf.org.in
    if (strpos($info_arr['cc'], '@esds.co.in')) {
      $this->email->cc($info_arr['cc']);
    } elseif (in_array($info_arr['cc'], array('ampregistrations@iibf.org.in'))) {
      $this->email->cc($info_arr['cc']);
    }

    //if(isset($info_arr['bcc'])) { $this->email->bcc($info_arr['bcc']); } 
    $this->email->subject($info_arr['subject'] . ' Pre-Production (UAT)'); //Added by Priyanka W for DRA testing
    //$this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }
  public function mailsend_attch_cpd($info_arr, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69') {
      return 1;
    }

    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    $this->email->cc('logs@iibf.esdsconnect.com');  // CC email added by Bhagwan Sahane, on 03-06-2017
    $this->email->bcc('iibfdevp@esds.co.in');
    $this->email->subject($info_arr['subject'] . ' - Pre production');
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }
  public function mailsend_attch_cpdsheet($info_arr, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69') {
      return 1;
    }

    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    $this->email->cc('logs@iibf.esdsconnect.com,iibfdevp@esds.co.in');  // CC email added by Chaitali Jadhav
    $this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }


  //chaitali 2021-05-15
  public function mailsend_attch_paymentsheet($info_arr, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69') {
      return 1;
    }

    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    //$this->email->cc('gnrao@iibf.org.in,jd.it2@iibf.org.in,iibfdevp@esds.co.in,logs@iibf.esdsconnect.com');	// CC email added by Chaitali Jadhav
    $this->email->cc('iibfdevp@esds.co.in,logs@iibf.esdsconnect.com');  // CC email added by Chaitali Jadhav
    $this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }


  public function sendmail_attach($info_arr, $other_info, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69' || $this->get_client_ip_email() == '182.73.101.70') {
      return 1;
    }

    $this->setting_smtp();
    $this->email->clear(TRUE);
    $this->email->set_newline("\r\n");
    $this->email->from($info_arr['from'], "iibf.com ");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    $this->email->subject($info_arr['subject']);
    $this->email->set_mailtype("html");
    $data['base_url'] = base_url();
    $this->email->message($this->load->view('email/' . $info_arr['view'], $other_info, true));
    $this->email->attach($path);
    if ($this->email->send()) {
      return true;
    }
  }

  public function mailsend_attch_cc($info_arr, $path)
  {
    /*if($this->get_client_ip_email() =='115.124.115.69' || $this->get_client_ip_email() =='182.73.101.70'){
			return 1;
		}*/
    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    //$this->email->to('spnair@iibf.org.in');
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    //$this->email->cc($info_arr['cc']);	// CC email added by Bhagwan Sahane, on 03-06-2017
    //$this->email->subject($info_arr['subject']);
    $this->email->subject($info_arr['subject'] . ' Pre-Production'); //Added by Priyanka W for DRA testing
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }
  //code added by Tejasvi //19-jan-2018
  public function mailsend_attch_DRA($info_arr, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69') {
      return 1;
    }
    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
    //$this->email->cc('logs@iibf.esdsconnect.com,soumya@iibf.org.in');	// CC email 
    //$this->email->cc('logs@iibf.esdsconnect.com,lathasekhar@iibf.org.in');	// CC email 
    $this->email->cc('logs@iibf.esdsconnect.com');  // CC email 
    $this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }

  //added by poooja godse for finquest 

  public function mailsend_attch_finquest($info_arr, $path)
  {
    if ($this->get_client_ip_email() == '115.124.115.69') {
      return 1;
    }
    $this->setting_smtp();
    //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
    //$this->email->initialize($config);
    //$this->email->from($info_arr['from'],"iibf.com"); 
    $this->email->from('POOJA.GODSE@ESDS.CO.IN', "IIBF");
    //$this->email->to($info_arr['to']);
    $this->email->to('iibfdevp@esds.co.in');
    //$this->email->reply_to('', 'IIBF');
    //$this->email->cc('iibfdevp@esds.co.in');	// CC email added by Bhagwan Sahane, on 03-06-2017
    $this->email->subject($info_arr['subject']);
    $this->email->message($info_arr['message']);
    if (is_array($path)) {
      foreach ($path as $row) {
        $this->email->attach($row);
      }
    } else {
      if ($path != NULL || $path != '') {
        $this->email->attach($path);
      }
    }
    if ($this->email->send()) {
      //$this->email->print_debugger();
      //	echo $this->email->print_debugger();
      $this->email->clear(TRUE);
      return true;
    }
  }
}
