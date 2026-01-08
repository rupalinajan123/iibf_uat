
<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  class Amc extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct(); 
      $this->load->model('Master_model');

      $this->current_time = date("Y-m-d H:i:s"); // '2023-09-02 11:00:00'; //
      $this->start_time = '2025-09-09 10:30:00';
      $this->end_time = '2025-09-17 13:10:00';
      
      //if(date('Y-m-d') == '2024-09-21') { redirect('https://iibf.esdsconnect.com/amc/registration'); }
        redirect('https://iibf.esdsconnect.com/amc/registration');
    }
    
    public function index()
    {
      redirect(site_url('amc/registration'));
    }
    
    public function registration()
    {
      $this->check_valid_time();
      $this->load->view('welcome_msg'); 
    }

    public function annual_registration()
    {
      $this->check_valid_time();

      //$redirect_url = site_url('Amc/thank_you');
      $redirect_url = 'https://nsdl-livewebcast.com/21092024/iibf';

      //START : CHECK ALREADY LOGGED IN OR NOT
      $AGM_REGNUMBER = $_SESSION['AGM_REGNUMBER'];
      $AGM_EMAIL = $_SESSION['AGM_EMAIL'];
      if(isset($AGM_REGNUMBER) && $AGM_REGNUMBER != "" && isset($AGM_EMAIL) && $AGM_EMAIL != "")
      {
        $user_data_session = $this->master_model->getRecords('annual_regsiter_login', array('regnumber'=>$AGM_REGNUMBER));

        if(count($user_data_session) == 0)
        {
          $user_data_session = $this->master_model->getRecords('member_registration', array('regnumber'=>$AGM_REGNUMBER));
        }

        if(count($user_data_session) > 0)
        {
          //echo 'This is sample webcast page'; exit;
          redirect($redirect_url);
        }
      }
      //END : CHECK ALREADY LOGGED IN OR NOT

      $data=array();
      $data['error']='';
      if(isset($_POST['submit']))
      { 
        $config = array(
        array(
        'field' => 'Password',
        'label' => 'Password',
        'rules' => 'trim|required',
        ),
        array(
        'field' => 'Username',
        'label' => 'Registration/Membership No.',
        'rules' => 'trim|required'
        ),
        );
        $this->form_validation->set_rules($config);         
        
        if ($this->form_validation->run() == TRUE)
        { 
          $regnumber = $this->input->post('Username');
          $Password = $this->input->post('Password');
          
          $user_data = $this->master_model->getRecords('annual_regsiter_login', array('regnumber'=>$regnumber, 'usrpassword'=>$Password));

          if(count($user_data) == 0)
          {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('pass_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encpass = $aes->encrypt($Password);
            //$encpass = $aes->encrypt($this->decryptpwd($this->input->post('Password')));

            $user_data = $this->master_model->getRecords('member_registration', array('regnumber'=>$regnumber, 'usrpassword'=>$encpass, 'isactive'=>'1', 'isdeleted'=>'0'));              
          }

          if(count($user_data) > 0)
          {
            $chk_exist = $this->master_model->getRecords('annual_regsiter',array('regnumber'=> $regnumber));
            if(count($chk_exist) == 0)
            {
              $insert_email = $user_data[0]['email'];
              if($insert_email == '')
              {
                $get_data1 = $this->master_model->getRecords('annual_regsiter_login', array('regnumber'=>$regnumber));
                if(count($get_data1) > 0)
                {
                  if($get_data1[0]['email'] != '') { $insert_email = $get_data1[0]['email']; }
                }
              }

              if($insert_email == '')
              {
                $get_data2 = $this->master_model->getRecords('member_registration', array('regnumber'=>$regnumber));
                if(count($get_data2) > 0)
                {
                  if($get_data2[0]['email'] != '') { $insert_email = $get_data2[0]['email']; }
                  else
                  {
                    if($get_data2[0]['namesub'] != "") { $insert_email .= $get_data2[0]['namesub']; }
                    if($get_data2[0]['firstname'] != "") { $insert_email .= ' '.$get_data2[0]['firstname']; }
                  }
                }
              }

              $insert_info = array('regnumber'=>$regnumber,'email'=>$insert_email);
              $this->master_model->insertRecord('annual_regsiter',$insert_info);               
            }              
            
            $this->session->set_userdata(array('AGM_REGNUMBER'=>$regnumber, 'AGM_EMAIL'=>$insert_email));
            
            //echo 'This is sample webcast page'; exit;
            redirect($redirect_url);
          }
          else
          {
            $data['error']='<span style="">Invalid credential.</span>';
          }
        } 
        else
        {
          $data['validation_errors'] = validation_errors();
        }
      }
      $this->load->view('welcome_login',$data);      
    }

    public function check_valid_time()
    {
      '<br> current_time : '.$current_time = $this->current_time;
      '<br> start_time : '.$start_time = $this->start_time;
      '<br> end_time : '.$end_time = $this->end_time;

      $disp_msg = 'Invalid Link';
      $error_flag = 0;

      if(isset($current_time) && $current_time != '' && isset($start_time) && $start_time != '' && isset($end_time) && $end_time != '')
      {      
        if($current_time < $start_time)
        {
          $error_flag = 1;
          //$disp_msg = "Registration Link will be open on ".date("dS F Y", strtotime($start_time))." from ".date("h:i A", strtotime($start_time))." to ".date("h:i A", strtotime($end_time))."</h4>";
          $disp_msg = "Registration Link will be open on ".date("dS F Y", strtotime($start_time))." from ".date("h:i A", strtotime($start_time))."</h4>";
        }
        else if($current_time > $end_time)
        {
          $error_flag = 1;
          $disp_msg = "Registration has been closed on ".date("dS F Y, h:i A", strtotime($end_time))."</h4>"; 
        }
        else if($current_time >= $start_time && $current_time <= $end_time) { }
        else { $error_flag = 1; }
      }
      else { $error_flag = 1; }

      if($error_flag == 1)
      {
        echo "<div style='border: 3px solid #619fda;text-align: center;font-size: 20px;margin: 50px 150px; padding: 30px 20px 5px;background: #eee;'>
                <tr>
                  <td style='margin: 0px 50px'><img src='https://iibf.esdsconnect.com/assets/images/iibf_logo_short.png' style='height: 50px;border-bottom: 1px solid #619fda;'></td>
                  <td class='login-logo' align='center'><a style='vertical-align: top;display: inline-block;margin: 0 0 0 10px;'>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>An ISO 21001:2018 Certified Organisation</small></a></td>
                </tr>
                <h4 >".$disp_msg."</h4>
              </div>";
          exit;
      }
    }
    
    public function annual_registration_bk()
    { exit;
      $chk_time = date('Y-m-d H:i:s');
      
      //if($chk_time > '2020-08-28 16:31:22' && $chk_time < '2020-08-28 16:35:22'){
			$data=array();
			$data['error']='';
			if(isset($_POST['submit']))
      { 
        $config = array(
        array(
        'field' => 'Password',
        'label' => 'Password',
        'rules' => 'trim|required',
        ),
        array(
        'field' => 'Username',
        'label' => 'Registration/Membership No.',
        'rules' => 'trim|required'
        ),
        );
        $this->form_validation->set_rules($config);
        
        /*include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
        $encpass = $aes->encrypt($this->input->post('Password'));*/
        
        $dataarr=array('regnumber'=> $this->input->post('Username'));
        
        if ($this->form_validation->run() == TRUE)
        {          
          $this->db->where('regnumber',$this->input->post('Username'));
          $this->db->where('usrpassword',$this->input->post('Password'));
          $rec = $this->master_model->getRecords('annual_regsiter_login');
          
          if(count($rec) > 0)
          {
            $user_data=array('regnumber'=>$this->input->post('Username'), 'email'=>$rec[0]['email']);
            $this->session->set_userdata($user_data);
            redirect(base_url().'Amc/annual_operation');
          }
          else
          {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('pass_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encpass = $aes->encrypt($this->input->post('Password'));
            //$encpass = $aes->encrypt($this->decryptpwd($this->input->post('Password')));
            
            $this->db->where('regnumber', $this->input->post('Username'));
            $this->db->where('usrpassword', $encpass);
            $rec2 = $this->master_model->getRecords('member_registration');
            
            if(count($rec2) > 0)
            {
              $user_data=array('regnumber'=>$this->input->post('Username'), 'email'=>$rec2[0]['email']);
              $this->session->set_userdata($user_data);
              redirect(base_url().'Amc/annual_operation'); 
            }
            else
            {         
              $data['error']='<span style="">Invalid credential.</span>';
            }
          }
        } 
        else
        {
          $data['validation_errors'] = validation_errors();
        }
      }
			$this->load->view('welcome_login',$data);
      /*}else{
        exit;	
      }*/
    }
    
    public function annual_registration_live()
    { exit;
      $chk_time = date('Y-m-d H:i:s');
      
      //if($chk_time > '2020-08-28 16:31:22' && $chk_time < '2020-08-28 16:35:22'){
			$data=array();
			$data['error']='';
			if(isset($_POST['submit'])){ 
        $config = array(
        array(
        'field' => 'Password',
        'label' => 'Password',
        'rules' => 'trim|required',
        ),
        array(
        'field' => 'Username',
        'label' => 'Registration/Membership No.',
        'rules' => 'trim|required'
        ),
        );
        $this->form_validation->set_rules($config);
        
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $encpass = $aes->encrypt($this->input->post('Password'));
        
        $dataarr=array(
        'regnumber'=> $this->input->post('Username'),
        
        );
        if ($this->form_validation->run() == TRUE){
          
          $this->db->where('regnumber',$this->input->post('Username'));
          $this->db->where('usrpassword',$encpass);
          $this->db->where('isactive','1');
          $rec = $this->master_model->getRecords('member_registration');
          echo $this->db->last_query();
          if(count($rec) > 0){
            $user_data=array(
            'regnumber'=>$this->input->post('Username'),
            'email'=>$rec[0]['email']
            );
            $this->session->set_userdata($user_data);
            redirect(base_url().'Amc/annual_operation');
            }else{
            $data['error']='<span style="">Invalid credential.</span>';
          }
					} else{
          $data['validation_errors'] = validation_errors();
        }
      }
			$this->load->view('welcome_login',$data);
      /*}else{
        exit;	
      }*/
    }
    
    public function annual_operation()
    { exit;
      $this->load->view('annual_register');
    }
    
    public function annual_register_insert()
    { exit;
      $regnumber =  $this->session->userdata('regnumber');
      $email =  $this->session->userdata('email');
      
      $this->db->where('regnumber',$regnumber);
      $this->db->where('email',$email);
      $rec = $this->master_model->getRecords('annual_regsiter');
      if(count($rec) == 0){
        $insert_info = array('regnumber'=>$regnumber,'email'=>$email);
        $this->master_model->insertRecord('annual_regsiter',$insert_info); 
      }
      $val = array('success'=>1);
      echo json_encode($val);
    }
    
    public function annual_dashboard()
    {
      $start_date = '2024-09-16';
      $end_date = '2024-09-21';
      $this->db->where('date_time >= ',$start_date.' 00:00:00');
      $this->db->where('date_time <=',$end_date.' 23:59:59');
      $sql = $this->master_model->getRecords('annual_regsiter');
      $total_cnt = count($sql);
      $param = $this->uri->segment(3);
      if(isset($param) && $param == 'csv')
      {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "annual_regsiter_".date("YmdHis").".csv";
        $query = "SELECT * FROM annual_regsiter where date_time >= '".$start_date." 00:00:00' AND date_time <= '".$end_date." 23:59:59'";
		    //echo $this->db->last_query();
        //$query = "SELECT * FROM annual_regsiter ";
        $result1 = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
        force_download($filename, $data);
      }
       
      $data = array('total_cnt'=>$total_cnt);
      $this->load->view('annual_dashboard',$data);
    } 
    
    public function thank_you()
    {
      $AGM_REGNUMBER = $_SESSION['AGM_REGNUMBER'];
      $AGM_EMAIL = $_SESSION['AGM_EMAIL'];
      if(isset($AGM_REGNUMBER) && $AGM_REGNUMBER != "" && isset($AGM_EMAIL) && $AGM_EMAIL != "")
      {
        $user_data_session = $this->master_model->getRecords('annual_regsiter_login', array('regnumber'=>$AGM_REGNUMBER));

        if(count($user_data_session) == 0)
        {
          $user_data_session = $this->master_model->getRecords('member_registration', array('regnumber'=>$AGM_REGNUMBER));
        }

        if(count($user_data_session) > 0)
        {
          echo '<div style="text-align: center; line-height: 28px; background: #eee; width: 300px; padding: 30px 30px; display: block; margin: 20px auto; font-size: 18px; border: 1px solid #ccc;  border-radius: 15px; font-family: calibri;">" <span style="font-weight: 500; font-style: italic;text-decoration: underline;">96th Annual General Meeting of Indian Institute of Banking & Finance</span> "<br><br>Thank you for your registration.<div style="margin: 20px 0 0 0; font-size: 16px; line-height: 22px; font-weight: 600; ">Member Number : '.$AGM_REGNUMBER.'<br>Email : '.$AGM_EMAIL.'</div></div>';
        }
        else
        {
          $_SESSION['AGM_REGNUMBER'] = $_SESSION['AGM_EMAIL'] = '';
          redirect(site_url('amc/registration'));
        }
      } 
      else
      {
        $_SESSION['AGM_REGNUMBER'] = $_SESSION['AGM_EMAIL'] = '';
        redirect(site_url('amc/registration'));
      }     
    }
  }
