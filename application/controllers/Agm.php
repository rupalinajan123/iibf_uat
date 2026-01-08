<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  class Agm extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct(); 
      $this->load->model('Master_model');
      exit;
    }    
    
    public function index()
    {
      redirect(site_url('agm/annual_registration')); 
    }    
    
    public function registration()
    { exit; 
      $this->load->view('welcome_msg'); 
    }
    
    public function annual_registration()
    { exit;
      //$chk_time = date('Y-m-d H:i:s');
      //print_r($chk_time);die;
      //if($chk_time >= '2022-09-17 10:00:00' && $chk_time < '2022-09-17 12:30:00')
      //{
        //echo "string";die;
        
        $redirect_url = 'https://nsdl-livewebcast.com/17092022/iibf/';

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
              
              $user_data = $this->master_model->getRecords('member_registration', array('regnumber'=>$regnumber, 'usrpassword'=>$Password));
            }

            if(count($user_data) > 0)
            {
              $chk_exist = $this->master_model->getRecords('annual_regsiter',array('regnumber'=> $regnumber));
              if(count($chk_exist) == 0)
              {
                $insert_info = array('regnumber'=>$regnumber,'email'=>$user_data[0]['email']);
                $this->master_model->insertRecord('annual_regsiter',$insert_info);               
              }              
              
              $this->session->set_userdata(array('AGM_REGNUMBER'=>$regnumber, 'AGM_EMAIL'=>$user_data[0]['email']));
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
      /*else
      {
        if($chk_time < '2022-09-17 10:00:00')
        {
          echo "<div style='border: 3px solid #619fda; text-align: center;font-size: 20px; margin: 50px 150px;'>
              <tr>
                  <td style='margin: 0px 50px'><img src='https://iibf.esdsconnect.com/assets/images/iibf_logo_short.png'></td>
                  <td class='login-logo' align='center'><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>An ISO 21001:2018 Certified Organisation</small></a></td>
                </tr>
              <h4 >Registration Link will be active on 17th September 2022 from 10.30 AM to 12.00AM</h4>
              </div>";
          echo " ";
        }
        exit;	
      }*/
    //}
    
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
            redirect(base_url().'Agm/annual_operation');
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
    { exit;
      $this->db->where('date_time >= ','2022-09-17 00:00:00');
      //$this->db->where('date_time <=','2020-08-28 16:25:59');
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
        $filename = "annual_regsiter.csv";
        $query = "SELECT * FROM annual_regsiter where date_time >= '2022-09-17 00:00:00'";
        //$query = "SELECT * FROM annual_regsiter ";
        $result1 = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
        force_download($filename, $data);
      }
      
      $data = array('total_cnt'=>$total_cnt);
      $this->load->view('annual_dashboard',$data);
    }    
  }
