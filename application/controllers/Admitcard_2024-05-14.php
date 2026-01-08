<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
defined('BASEPATH') OR exit('No direct script access allowed');
class Admitcard extends CI_Controller 
{
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		 parent::__construct(); 
		
		 //load mPDF library
		 //$this->load->library('m_pdf');
		 $this->load->model('Master_model');
	} 
	
	// Common login function for BCBF admitcard link
	public function bcbf()
  { 
		try
    { 
			$data=array();
			$data['error']='';
			$feedback_exam_name = 'bcbf';
			
      if(isset($_POST['submit']))
      {
				$config = array(
        array(
        'field' => 'Username',
        'label' => 'Username',
        'rules' => 'trim|required'
        ),
        /*array(
          'field' => 'Password',
          'label' => 'Password',
          'rules' => 'trim|required',
        ),*/
        array(
        'field' => 'code',
        'label' => 'Code',
        'rules' => 'trim|required|callback_check_captcha_userlogin',
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
        //'usrpassword'=>$encpass,
        'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE)
        {
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0)
          { 
            
						$exmarr = array(
            'exm_cd'=> $this->input->post('examcode'),
            'mem_mem_no'=> $this->input->post('Username')
            
            );
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
						if(count($chkexam) > 0){
							$mysqltime=date("H:i:s");
							$seprate_user_data=array('regid'=>$user_info[0]['regid'],
              'spregnumber'=>$user_info[0]['regnumber'],
              'spfirstname'=>$user_info[0]['firstname'],
              'spmiddlename'=>$user_info[0]['middlename'],
              'splastname'=>$user_info[0]['lastname'],
              'feedback_exam_name' => $feedback_exam_name
              );
							$this->session->set_userdata($seprate_user_data);
							redirect(base_url().'admitcard/feedback');
              }else{
							$data['error']='<span style="">Invalid credential.</span>';
            }
          }
          else
          {
            $user_info = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber'=>$this->input->post('Username')));
            if(count($user_info) > 0)
            { 
              $exmarr = array(
              /* 'exm_cd'=> $this->input->post('examcode'), */
              'mem_mem_no'=> $this->input->post('Username')
              
              );
              $chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
              
              if(count($chkexam) > 0)
              {
                $mysqltime = date("H:i:s");
                $seprate_user_data=array('regid'=>$user_info[0]['candidate_id'],
                'spregnumber'=>$user_info[0]['regnumber'],
                'spfirstname'=>$user_info[0]['salutation']." ".$user_info[0]['first_name'],
                'spmiddlename'=>$user_info[0]['middle_name'],
                'splastname'=>$user_info[0]['last_name'],
                'feedback_exam_name' => $feedback_exam_name
                );
                $this->session->set_userdata($seprate_user_data);
                redirect(base_url().'admitcard/feedback');
              }
              else
              {
                $data['error']='<span style="">Invalid credential.</span>';
              }
            }
            else
            {
              $data['error']='<span style="">Invalid credential..</span>';
            }
          }
        }
        else
        {
          $data['validation_errors'] = validation_errors();
        }
      }
			/*$this->load->helper('captcha');
        $vals = array(
        'img_path' => './uploads/applications/',
        'img_url' => '/uploads/applications/',
        );
        $cap = create_captcha($vals);
        $data['image'] = $cap['image'];
        $data['code']=$cap['word'];
        $this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
      */
			$this->load->model('Captcha_model');                 
      $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('bcbfjuly',$data);
			
      }catch(Exception $e){
			echo "Message : ".$e->getMessage();
    }	
  }
  
	
	public function bcbf506(){ 
		try{ 
			$data=array();
			$data['error']='';
			$feedback_exam_name = 'bcbf';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
					
						$exmarr = array(
										'exm_cd'=> 996,
										'mem_mem_no'=> $this->input->post('Username')
										
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
						if(count($chkexam) > 0){
							$mysqltime=date("H:i:s");
							$seprate_user_data=array('regid'=>$user_info[0]['regid'],
														'spregnumber'=>$user_info[0]['regnumber'],
														'spfirstname'=>$user_info[0]['firstname'],
														'spmiddlename'=>$user_info[0]['middlename'],
														'splastname'=>$user_info[0]['lastname'],
														'feedback_exam_name' => $feedback_exam_name
													);
							$this->session->set_userdata($seprate_user_data);
							redirect(base_url().'admitcard/feedback');
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential..</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('bcbfjuly',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function kotak_bcbf(){ 
		try{ 
			$data=array();
			$data['error']='';
			$feedback_exam_name = 'bcbf';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
					
						$exmarr = array(
										'exm_cd'=> $this->input->post('examcode'),
										'mem_mem_no'=> $this->input->post('Username'),
										'date' => '2019-02-25'
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
						if(count($chkexam) > 0){
							$mysqltime=date("H:i:s");
							$seprate_user_data=array('regid'=>$user_info[0]['regid'],
														'spregnumber'=>$user_info[0]['regnumber'],
														'spfirstname'=>$user_info[0]['firstname'],
														'spmiddlename'=>$user_info[0]['middlename'],
														'splastname'=>$user_info[0]['lastname'],
														'feedback_exam_name' => $feedback_exam_name
													);
							$this->session->set_userdata($seprate_user_data);
							redirect(base_url().'admitcard/feedback');
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential..</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('bcbfjuly',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	// dashboard function for user portal
	public function getadmitdashboard() {
		try
    {
      if($this->session->userdata('spregnumber') != '')
      {
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
      }

			if($this->session->userdata('regnumber') != '')
      {
				$member_id = $this->session->userdata('regnumber');
				$name      = $this->session->userdata('firstname')." ".$this->session->userdata('middlename')." ".$this->session->userdata('lastname');	
      }

			if($this->session->userdata('nmregnumber')!='')
      {
				$member_id = $this->session->userdata('nmregnumber');
				$name      = $this->session->userdata('nmfirstname')." ".$this->session->userdata('nmmiddlename')." ".$this->session->userdata('nmlastname');
      }

			if($this->session->userdata('dbregnumber')!='')
      {
				$member_id = $this->session->userdata('dbregnumber');
				$name      = $this->session->userdata('dbfirstname')." ".$this->session->userdata('dbmiddlename')." ".$this->session->userdata('dblastname');
      }
			
			if(!isset($member_id)){
				redirect(base_url());
			}
			
			// $query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' and date>'".date('Y-m-d')."' ");
			//For bcbf 101 below code modified
			
			/*$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' and ( date>'".date('Y-m-d')."' OR STR_TO_DATE(date ,'%d-%b-%Y')>'".date('d-M-Y')."' ) ");*/
			

			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' "); // Remove today date condition by Anil S,Priyanka D on 16 Oct 2023 

			$exm_arr = $query->result();

			// if ($this->get_client_ip()=='115.124.115.77') {
			// 	$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' and ( date>'".date('Y-m-d')."' OR STR_TO_DATE(date ,'%d-%b-%Y')>'".date('d-M-Y')."' ) ");
			// $exm_arr = $query->result();
			// 	echo $this->db->last_query();
			// 	die;
			// }
      
			$todayDate = date('Y-m-d');
			
			$query_1 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' AND exam_date >='".$todayDate."' order by admitcard_id desc");
			$exm_arr_1 = $query_1->result();

			//echo $this->db->last_query();
      
			$result_new = array();
			$result = array();
			$tbl_new = '';
			$tbl = '';
			//echo sizeof($exm_arr);exit;
			if(sizeof($exm_arr) > 0)
      {
				if(count($exm_arr) > 1)
        {
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
          ");
					$result = $record->result();
					$tbl = 'old';
					//print_r($this->db->last_query());exit;
        }
        elseif(count($exm_arr) == 1)
        {
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					$tbl = 'old';
        }
      }
			
      //Added by Priyanka W for RPE old Admit card display functionality
            $examCdArr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','1017','1019','1020','2027'); //,'11'
      $examCdStr = implode(',', $examCdArr);
      
      if(sizeof($exm_arr_1) > 0)
      { 
				//echo $exm_arr_1[0]->exm_cd;die;
				$tDate=date('Y-m-d');
				if($exm_arr_1[0]->exm_cd == $this->config->item('examCodeJaiib') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeDBF') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSOB') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSBIRPE') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSBIRPEAMLKYC') ){
					$record=$this->db->query("SELECT admit_card_details.exm_cd,admit_card_details.exm_prd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '$tDate' AND  exm_prd NOT IN(915,910,912,916,121,221,122) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					"); // chaitali - reMoved not in of exam code on 2021-11-11 // priyanka d - 24-01-23 removed exam_period
            //echo'<pre>';print_r($this->db->last_query());exit;
					//echo $this->db->last_query();die;
        }
				/*elseif (in_array($exm_arr_1[0]->exm_cd, $examCdArr)) {
					$record=$this->db->query("SELECT admit_card_details.exm_prd, admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' 
					AND admit_card_details.remark = 1 
					AND exm_prd NOT IN(915,910,912,916,121,221,122,919) 
					AND exm_cd IN (".$examCdStr.")
					GROUP BY admit_card_details.exm_prd
					ORDER BY admit_card_details.admitcard_id DESC LIMIT 4
					");
					//echo $this->db->last_query();die;
				}*/
				else{
					$record=$this->db->query("SELECT admit_card_details.exm_cd,admit_card_details.exm_prd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND  exm_prd NOT IN(915,910,912,916,121,221,122,919) 
					AND admit_card_details.exam_date >='".$todayDate."'
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					");
				}
				 $result_new = $record->result();
				 $tbl_new = 'new';
				 if($member_id==510130439) {
					//echo '<pre>';
				 //print_r($result_new);
				 }
				 //echo $this->db->last_query();//die;
				 /*echo '<pre>';
				 print_r($result_new);*/
			}

			//elseif(count($exm_arr) == 1){ 
			elseif(count($exm_arr) > 0){	

				if($exm_arr_1[0]->exm_cd == $this->config->item('examCodeJaiib') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeDBF') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSOB')){
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '2021-04-30'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,121,221,122,222)  
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					LIMIT 1;
					");
					
        }
				else{
					//echo 'else---';
					$record=$this->db->query("SELECT admit_card_details.exm_cd,admit_card_details.exm_prd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '".date("Y-m-d")."'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,121,221,122,919,222) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					LIMIT 1;
					");
				}
			
				
				$result_new = $record->result();
				$tbl_new = 'new';
			}
			else{
				$tbl = 'none';
				$result = array();
			}
			//echo'<pre>';print_r($result);exit;

			//print_r($result_new);

			// code to display old rpe admit cards--vishal 
			//$query_2 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' AND exam_date ='2023-08-12' AND remark='1' AND exm_prd='828' order by admitcard_id desc");

			$query_2 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' AND exam_date  IN ('2023-09-09','2023-08-26','2023-05-27') AND remark='1' AND exm_prd IN ('829','830','831','823' ) order by admitcard_id desc");

			$exm_arr_2 = $query_2->result();
			// echo $this->db->last_query();
			// die;

			/*if(sizeof($exm_arr_2) > 0){ 
				$tDate=date('Y-m-d');
				
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND  exm_prd IN ('829','830','831','823' ) AND  exm_cd IN ('1002','1013','1014')  
				AND admit_card_details.exam_date IN ('2023-09-09','2023-08-26','2023-05-27')
				GROUP BY admit_card_details.exm_cd
				ORDER BY admit_card_details.admitcard_id DESC 
				");
				// echo $this->db->last_query();
			// die;
				
				 $result_new2 = $record->result();
				 $result_new = array_merge($result_new,$result_new2);
				 $tbl_new = 'new';
				
				 
			}*/
			// code to old admit card rpe
			
			// echo '<pre>';print_r($result_new);
			$data = array("exam_name"=>$result,"exam_name_new"=>$result_new,'name'=>$name,'frm'=>'dwn','mid'=>$member_id,'tbl'=>$tbl,'tbl_new'=>$tbl_new);
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}

	// dashboard function for user portal
	public function getadmitdashboard_anil(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
				$name      = $this->session->userdata('firstname')." ".$this->session->userdata('middlename')." ".$this->session->userdata('lastname');	
			}
			if($this->session->userdata('nmregnumber')!=''){
				$member_id = $this->session->userdata('nmregnumber');
				$name      = $this->session->userdata('nmfirstname')." ".$this->session->userdata('nmmiddlename')." ".$this->session->userdata('nmlastname');
			}
			if($this->session->userdata('dbregnumber')!=''){
				$member_id = $this->session->userdata('dbregnumber');
				$name      = $this->session->userdata('dbfirstname')." ".$this->session->userdata('dbmiddlename')." ".$this->session->userdata('dblastname');
			}
			
			if(!isset($member_id)){
				redirect(base_url());
			}
			
			// $query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' and date>'".date('Y-m-d')."' ");
			//For bcbf 101 below code modified
			
			/*$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' and ( date>'".date('Y-m-d')."' OR STR_TO_DATE(date ,'%d-%b-%Y')>'".date('d-M-Y')."' ) ");*/

			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' "); // Remove today date condition by Anil S,Priyanka D on 16 Oct 2023 

			$exm_arr = $query->result();

			echo $this->db->last_query();

			// if ($this->get_client_ip()=='115.124.115.77') {
			// 	$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' and ( date>'".date('Y-m-d')."' OR STR_TO_DATE(date ,'%d-%b-%Y')>'".date('d-M-Y')."' ) ");
			// $exm_arr = $query->result();
			// 	echo $this->db->last_query();
			// 	die;
			// }

			$todayDate = date('Y-m-d');
			
			$query_1 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' AND exam_date >='".$todayDate."' order by admitcard_id desc");
			$exm_arr_1 = $query_1->result();

			//echo $this->db->last_query();

			$result_new = array();
			$result = array();
			$tbl_new = '';
			$tbl = '';
			//echo sizeof($exm_arr);exit;
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					$tbl = 'old';
					//print_r($this->db->last_query());exit;
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					$tbl = 'old';
				}
			}

			//Added by Priyanka W for RPE old Admit card display functionality
            $examCdArr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','1017','1019','1020','2027'); //,'11'
            $examCdStr = implode(',', $examCdArr);
			
			if(sizeof($exm_arr_1) > 0){ 
				$tDate=date('Y-m-d');
				if($exm_arr_1[0]->exm_cd == $this->config->item('examCodeJaiib') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeDBF') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSOB') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSBIRPE') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSBIRPEAMLKYC') ){
					$record=$this->db->query("SELECT admit_card_details.exm_cd,admit_card_details.exm_prd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '$tDate' AND  exm_prd NOT IN(915,910,912,916,121,221,122) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					"); // chaitali - reMoved not in of exam code on 2021-11-11 // priyanka d - 24-01-23 removed exam_period
					//echo'<pre>';print_r($this->db->last_query());exit;
					//echo $this->db->last_query();die;
					
				}
				/*elseif (in_array($exm_arr_1[0]->exm_cd, $examCdArr)) {
					$record=$this->db->query("SELECT admit_card_details.exm_prd, admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' 
					AND admit_card_details.remark = 1 
					AND exm_prd NOT IN(915,910,912,916,121,221,122,919) 
					AND exm_cd IN (".$examCdStr.")
					GROUP BY admit_card_details.exm_prd
					ORDER BY admit_card_details.admitcard_id DESC LIMIT 4
					");
					//echo $this->db->last_query();die;
				}*/
				else{
					$record=$this->db->query("SELECT admit_card_details.exm_cd,admit_card_details.exm_prd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND  exm_prd NOT IN(915,910,912,916,121,221,122,919) 
					AND admit_card_details.exam_date >='".$todayDate."'
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					");
        }
        $result_new = $record->result();
        $tbl_new = 'new';
        //echo $this->db->last_query();//die;
        /*echo '<pre>';
        print_r($result_new);*/
      }

			//elseif(count($exm_arr) == 1){ 
			elseif(count($exm_arr) > 0){	

				if($exm_arr_1[0]->exm_cd == $this->config->item('examCodeJaiib') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeDBF') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSOB')){
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '2021-04-30'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,121,221,122,222)  
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					LIMIT 1;
					");
        }
				else{
					//echo 'else---';
					$record=$this->db->query("SELECT admit_card_details.exm_cd,admit_card_details.exm_prd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '".date("Y-m-d")."'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,121,221,122,919,222) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					LIMIT 1;
					");
        }
        
				
				$result_new = $record->result();
				$tbl_new = 'new';
      }
			else
      {
        $tbl = 'none';
				$result = array();
      }
			//echo'<pre>';print_r($result);exit;

			//print_r($result_new);

			// code to display old rpe admit cards--vishal 
			//$query_2 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' AND exam_date ='2023-08-12' AND remark='1' AND exm_prd='828' order by admitcard_id desc");

			$query_2 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' AND exam_date  IN ('2023-09-09','2023-08-26','2023-05-27') AND remark='1' AND exm_prd IN ('829','830','831','823' ) order by admitcard_id desc");

			$exm_arr_2 = $query_2->result();
			// echo $this->db->last_query();
			// die;

			/*if(sizeof($exm_arr_2) > 0){ 
				$tDate=date('Y-m-d');
				
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND  exm_prd IN ('829','830','831','823' ) AND  exm_cd IN ('1002','1013','1014')  
				AND admit_card_details.exam_date IN ('2023-09-09','2023-08-26','2023-05-27')
				GROUP BY admit_card_details.exm_cd
				ORDER BY admit_card_details.admitcard_id DESC 
				");
				// echo $this->db->last_query();
			// die;
      
				 $result_new2 = $record->result();
				 $result_new = array_merge($result_new,$result_new2);
				 $tbl_new = 'new';
				
				 
			}*/
			// code to old admit card rpe
			
			// echo '<pre>';print_r($result_new);
      $data = array("exam_name"=>$result,"exam_name_new"=>$result_new,'name'=>$name,'frm'=>'dwn','mid'=>$member_id,'tbl'=>$tbl,'tbl_new'=>$tbl_new);
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
    }
    catch(Exception $e)
    {
			echo "Message : ".$e->getMessage();
    }	
  }
  
	
	public function getadmitdashboardtest(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
				$name      = $this->session->userdata('firstname')." ".$this->session->userdata('middlename')." ".$this->session->userdata('lastname');	
			}
			if($this->session->userdata('nmregnumber')!=''){
				$member_id = $this->session->userdata('nmregnumber');
				$name      = $this->session->userdata('nmfirstname')." ".$this->session->userdata('nmmiddlename')." ".$this->session->userdata('nmlastname');
			}
			if($this->session->userdata('dbregnumber')!=''){
				$member_id = $this->session->userdata('dbregnumber');
				$name      = $this->session->userdata('dbfirstname')." ".$this->session->userdata('dbmiddlename')." ".$this->session->userdata('dblastname');
			}
			
			if(!isset($member_id)){
				redirect(base_url());
			}
			
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			$result_new = array();
			$result = array();
			$tbl_new = '';
			$tbl = '';
			
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					$tbl = 'old';
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					$tbl = 'old';
				}
			}
			
			$query_1 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' order by admitcard_id desc ");
			$exm_arr_1 = $query_1->result();
			//echo '>>'. $exm_arr_1[0]->exm_cd;
			//echo $this->db->last_query();
			if(sizeof($exm_arr_1) > 0){ 
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND exam_date != '2019-03-10' AND  exm_prd NOT IN(915,910,912,122) 
				GROUP BY admit_card_details.exm_cd
				ORDER BY admit_card_details.admitcard_id DESC 
				 ");
				 $result_new = $record->result();
				 $tbl_new = 'new';
				 //echo $this->db->last_query();
				 /*echo '<pre>';
				 print_r($result_new);*/
			}elseif(count($exm_arr) == 1){ 
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '".date("Y-m-d")."'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,122) 
				GROUP BY admit_card_details.exm_cd
				ORDER BY admit_card_details.admitcard_id DESC 
				LIMIT 1;
				");
				$result_new = $record->result();
				$tbl_new = 'new';
			}else{
				$tbl = 'none';
				$result = array();
			}
			//echo $tbl_new;
		
			$data = array("exam_name"=>$result,"exam_name_new"=>$result_new,'name'=>$name,'frm'=>'dwn','mid'=>$member_id,'tbl'=>$tbl,'tbl_new'=>$tbl_new);
			// echo '<pre>';
			// print_r($data);  exit;
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	// function run for old table for exam code 101
	public function getadmitcardsp($enc_exam_code='')
  {
		try
    {
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			//$exam_code = base64_decode($this->uri->segment(3));
			$exam_code = base64_decode($enc_exam_code);
			
      $disp_unique_no = '';
      if(in_array($exam_code, array(1037,1038)))
      {
        $img_path = base_url()."uploads/iibfbcbf/photo/";
			  $sig_path =  base_url()."uploads/iibfbcbf/sign/";

        $this->db->select('admitcard_info.*,iibfbcbf_batch_candidates.candidate_photo AS scannedphoto, iibfbcbf_batch_candidates.candidate_sign AS scannedsignaturephoto, iibfbcbf_batch_candidates.candidate_id');
        $this->db->from('admitcard_info');
        $this->db->join('iibfbcbf_batch_candidates', 'admitcard_info.mem_mem_no = iibfbcbf_batch_candidates.regnumber');
        $this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
        $record = $this->db->get();
        $result = $record->row();
        
        $this->db->order_by('me.member_exam_id', 'DESC');
        $payment_data = $this->master_model->getRecords('iibfbcbf_member_exam me',array('me.candidate_id'=>$result->candidate_id, 'me.exam_code'=>$exam_code, 'me.pay_status'=>'1'), 'me.ref_utr_no, me.created_on'); 
        if(count($payment_data) > 0)
        {
          $disp_unique_no = $payment_data[0]['created_on'].' '.$payment_data[0]['ref_utr_no'];
        }
      }
      else
      {
        $img_path = base_url()."uploads/photograph/";
			  $sig_path =  base_url()."uploads/scansignature/";

        $this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
        $this->db->from('admitcard_info');
        $this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
        $this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
        $record = $this->db->get();
        $result = $record->row();
      }
      
      if($exam_code != 101 && $exam_code != 1037 && $exam_code != 1038)
      {
				if($result->vendor_code == 3){
					$vcenter = '3';
				}elseif($result->vendor_code == 1){
					$vcenter = '1';
				}
			}
      elseif($exam_code == 101)
      {
				$this->db->select('center_code'); 
				$this->db->from('sify_center');
				$scenter = $this->db->get();
				$sifyresult = $scenter->result();
				foreach($sifyresult as $sifyresult){
					$sifycenter[] = $sifyresult->center_code;
				}
				
				$this->db->select('center_code'); 
				$this->db->from('nseit_center');
				$ncenter = $this->db->get();
				$nseitresult = $ncenter->result();
				foreach($nseitresult as $nseitresult){
					$nseitcenter[] = $nseitresult->center_code;
				}
				
				if(in_array($result->center_code, $nseitcenter)){
					$vcenter = '3';
				}
				if(in_array($result->center_code, $sifycenter)){
					$vcenter = '1';
				}
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			//echo $this->db->last_query();die;
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
      $examdate = explode("-",$exdate);
      
      if($exam_code == 1037 || $exam_code == 1038)
      {
        $printdate = $examdate[1].' '.$examdate[2];
      }
      else
      {
			  $printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
      }
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$memberDetails = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute'));

			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row(); 
			
			// Added by Pooja Mane for Transaction no to be displayed on admit card
			$this->db->select('transaction_no,date');
			$this->db->order_by('id', 'desc');
			$payment = $this->db->get_where('payment_transaction', array('member_regnumber' => $result->mem_mem_no,'exam_code'=> '101','status'=> '1'));
			$payment_result = $payment->row();
			// echo $this->db->last_query();//die;

			// print_r($payment_result);
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate,'memberDetails'=>$memberDetails,'transaction_no' => $payment_result->transaction_no,'date' => $payment_result->date, 'disp_unique_no'=>$disp_unique_no);
			
			$this->load->view('admitcardsp', $data);
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	// function run for old table for exam code 101
	public function getadmitcardpdfsp($enc_exam_code='')
  {
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			//$exam_code = base64_decode($this->uri->segment(3));
			$exam_code = base64_decode($enc_exam_code);

      $disp_unique_no = '';
      if(in_array($exam_code, array(1037,1038)))
      {
        $img_path = base_url()."uploads/iibfbcbf/photo/";
			  $sig_path =  base_url()."uploads/iibfbcbf/sign/";

        $this->db->select('admitcard_info.*,iibfbcbf_batch_candidates.candidate_photo AS scannedphoto, iibfbcbf_batch_candidates.candidate_sign AS scannedsignaturephoto, iibfbcbf_batch_candidates.candidate_id');
        $this->db->from('admitcard_info');
        $this->db->join('iibfbcbf_batch_candidates', 'admitcard_info.mem_mem_no = iibfbcbf_batch_candidates.regnumber');
        $this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
        $record = $this->db->get();
        $result = $record->row();

        $this->db->order_by('me.member_exam_id', 'DESC');
        $payment_data = $this->master_model->getRecords('iibfbcbf_member_exam me',array('me.candidate_id'=>$result->candidate_id, 'me.exam_code'=>$exam_code, 'me.pay_status'=>'1'), 'me.ref_utr_no, me.created_on'); 
        if(count($payment_data) > 0)
        {
          $disp_unique_no = $payment_data[0]['created_on'].' '.$payment_data[0]['ref_utr_no'];
        }
      }
      else
      {
        $img_path = base_url()."uploads/photograph/";
			  $sig_path =  base_url()."uploads/scansignature/";

        $this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
        $this->db->from('admitcard_info');
        $this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
        $this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
        $record = $this->db->get();
        $result = $record->row();

        $this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
        $this->db->from('admitcard_info');
        $this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
        $this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
        $record = $this->db->get();
        $result = $record->row();
      }
      
      if($exam_code != 101 && $exam_code != 1037 && $exam_code != 1038){
				if($result->vendor_code == 3){
					$vcenter = '3';
				}elseif($result->vendor_code == 1){
					$vcenter = '1';
				}
			}elseif($exam_code == 101){
				$this->db->select('center_code'); 
				$this->db->from('sify_center');
				$scenter = $this->db->get();
				$sifyresult = $scenter->result();
				foreach($sifyresult as $sifyresult){
					$sifycenter[] = $sifyresult->center_code;
				}
				
				$this->db->select('center_code'); 
				$this->db->from('nseit_center');
				$ncenter = $this->db->get();
				$nseitresult = $ncenter->result();
				foreach($nseitresult as $nseitresult){
					$nseitcenter[] = $nseitresult->center_code;
				}
				
				if(in_array($result->center_code, $nseitcenter)){
					$vcenter = '3';
				}
				if(in_array($result->center_code, $sifycenter)){
					$vcenter = '1';
				}
			}
			
			$medium_code = $result->m_1; 
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			
      if($exam_code == 1037 || $exam_code == 1038)
      {
        $printdate = $examdate[1].' '.$examdate[2];
      }
      else
      {
			  $printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
      }
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$memberDetails = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute'));

			// Added by Pooja Mane for Transaction no to be displayed on admit card
			$this->db->select('transaction_no,date');
			$this->db->order_by('id', 'desc');
			$payment = $this->db->get_where('payment_transaction', array('member_regnumber' => $result->mem_mem_no,'exam_code'=> '101','status'=> '1'));
			$payment_result = $payment->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'memberDetails'=>$memberDetails,'transaction_no' => $payment_result->transaction_no,'date' => $payment_result->date, 'disp_unique_no'=>$disp_unique_no);
			
			$html=$this->load->view('admitcardpdf', $data, true);
      $this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
		
	public function check_captcha_userlogin($code){
		try{
			if(!isset($this->session->useradmitcardlogincaptcha) && empty($this->session->useradmitcardlogincaptcha)){
				redirect(base_url().'index/');
			}
			if($code == '' || $this->session->useradmitcardlogincaptcha != $code ){
				$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
				$this->session->set_userdata("userlogincaptcha", rand(1,100000));
				return false;
			}
			if($this->session->useradmitcardlogincaptcha == $code){
				$this->session->set_userdata('useradmitcardlogincaptcha','');
				$this->session->unset_userdata("useradmitcardlogincaptcha");
				return true;
			}
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();	
		}
	}
	public function generatecaptchaajax(){
		try{
			/*$this->load->helper('captcha');
			$this->session->unset_userdata("useradmitcardlogincaptcha");
			$this->session->set_userdata("useradmitcardlogincaptcha", rand(1, 100000));
			$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',);
			$cap = create_captcha($vals);
			$data = $cap['image'];
			$_SESSION["useradmitcardlogincaptcha"] = $cap['word'];
			echo $data;*/
			$this->load->model('Captcha_model');                 
		    echo $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();	
		}
	}
	public function Logout(){
		try{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
				$this->session->unset_userdata($key);    
			}
			redirect('http://iibf.org.in/');
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
		
	// function run for old table  from website link without exam codde 101
	public function getadmitcardjd()
	{
		try
		{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			 
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_info.*,member_registration.*');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->order_by("date", "asc");
			$record = $this->db->get();
			$result = $record->row(); 
			if(!empty($result)) {
				
				$result->mem_adr_1=$result->address1;
				$result->mem_adr_2=$result->address2;
				$result->mem_adr_3=$result->address3;
				$result->mem_adr_4=$result->address4;
				$result->mem_adr_5=$result->address5;
				$result->mem_pin_cd=$result->pincode;
			}
			/* if($member_id == '510462384')
			 { 
			 	echo $member_id."<pre> Exam =>".$exam_code;
			 	
			 }*/
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			
			$dipcert_arr = array(8,11,18,19,24,25,26,78,79,149,151,153,156,158,162,163,165,166);
			$caiib_arr = array($this->config->item('examCodeCaiib'),61,$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
			
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') )
			{
				// $drr = array('2022-04-09','2022-04-23','2022-04-24');
				$drr = array('2023-06-10','2023-06-11','2023-06-18','2023-06-24');
			}else if(in_array($exam_code,$dipcert_arr)){
				$drr = array('2023-07-09','2023-07-19','2023-07-23');
			}else if(in_array($exam_code,$caiib_arr)){
				$drr = array('2023-07-08');
			}else{
			 	$drr = array('2022-01-30','2022-02-06','2022-02-12');
			 
			}
			
			
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->where_in('date',$drr);
			$this->db->group_by('venueid');
			//$this->db->group_by('date');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
		
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') )
			{ //echo $member_id;exit;
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2023-06-10','2023-06-11','2023-06-18','2023-06-24') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}else if(in_array($exam_code,$dipcert_arr)){
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2023-07-09','2023-07-19','2023-07-23') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
				
			}else if(in_array($exam_code,$caiib_arr)){
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2023-07-08','2023-07-09') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
				
			}else{
			    $subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2022-01-30','2022-02-06','2022-02-12') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}
			
			$subject_result = $subject->result();
			//echo $this->db->last_query();
			//exit;
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			//$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			$printdate = date('M Y',strtotime($exdate));
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();

			foreach($subject_result as $subject){ //priyanka d -apr-23 
				$subject->exam_date=$subject->date;
			}

			$memberDetails = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute')); //priyanka d -feb-23
			//echo $img_path; die;
			$instituteDetails = $this->master_model->getRecords('institution_master',array('institude_id'=>$memberDetails[0]['associatedinstitute']),array('name')); //priyanka d -feb-23
			if(!empty($instituteDetails)) {
				$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
			}
		
			$this->db->select('optFlg,selected_vendor');
			$this->db->from('member_exam');
			$this->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'pay_status'=>1));		
			$this->db->order_by("id", "desc");
			$optFlgRecordRow = $this->db->get();
			$optFlgRecord=$selected_vendor=''; 
			if(!empty($optFlgRecordRow)) {
				$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
				$selected_vendor	=	$optFlgRecordRow->row()->selected_vendor;
			}
				

			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate,"member_result"=>$result,"venue_result"=>$results,'member_id'=>$member_id,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord,'selected_vendor'=>$selected_vendor);
			//echo 'hhhhhh';
			//print_r($data);die;
			$this->load->view('admitcardjd', $data);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function getadmitcardjdtest(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$drr = array('06-Dec-20','12-Dec-20','13-Dec-20','27-Dec-20','26-Dec-20','20-Dec-20','17-Jan-21','24-Jan-21','31-Jan-21','23-Jan-21','28-Feb-21','07-Mar-21','27-Feb-21');
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->where_in('date',$drr);
			$this->db->group_by('venueid');
			//$this->db->group_by('date');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			//510368944
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('06-Dec-20','12-Dec-20','13-Dec-20','27-Dec-20','26-Dec-20','20-Dec-20','17-Jan-21','24-Jan-21','31-Jan-21','23-Jan-21','28-Feb-21','07-Mar-21','27-Feb-21') ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			//echo $this->db->last_query();
			exit;
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardjd', $data);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
		
	// function run for old table  from website link without exam codde 101
	public function getadmitcardpdfjd(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info.*,member_registration.*');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			if(!empty($result)) {
				
				$result->mem_adr_1=$result->address1;
				$result->mem_adr_2=$result->address2;
				$result->mem_adr_3=$result->address3;
				$result->mem_adr_4=$result->address4;
				$result->mem_adr_5=$result->address5;
				$result->mem_pin_cd=$result->pincode;
			}
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			/*$drr = array('10-Jul-21','11-Jul-21','18-Jul-21','06-Dec-20','12-Dec-20','13-Dec-20','27-Dec-20','26-Dec-20','20-Dec-20','10-Jan-21','17-Jan-21','24-Jan-21','31-Jan-21','23-Jan-21','28-Feb-21','07-Mar-21','27-Feb-21');*/
			$dipcert_arr = array(8,11,18,19,24,25,26,78,79,149,151,153,156,158,162,163,165,166,220);
			$caiib_arr = array($this->config->item('examCodeCaiib'),61,$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))
			{
				$drr = array('2023-06-10','2023-06-11','2023-06-18','2023-06-24');
			}else if(in_array($exam_code,$dipcert_arr)){
				$drr = array('2023-07-09','2023-07-19','2023-07-23');
			}else if(in_array($exam_code,$caiib_arr)){
				$drr = array('2023-07-08');
			}else{
			 $drr = array('2022-01-30','2022-02-06','2022-02-12');
			 
			}
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->where_in('date',$drr);
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
		
            
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') )
			{
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0'  AND date IN('2023-06-10','2023-06-11','2023-06-18','2023-06-24') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}else if(in_array($exam_code,$dipcert_arr)){
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0'  AND date IN('2023-07-09','2023-07-19','2023-07-23') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}else if(in_array($exam_code,$caiib_arr)){
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2023-07-08','2023-07-09') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}else{
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0'  AND date IN('2022-01-30','2022-02-06','2022-02-12') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			//$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			$printdate = date('M Y',strtotime($exdate));
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			foreach($subject_result as $subject){ //priyanka d -apr-23 
				$subject->exam_date=$subject->date;
			}
			$memberDetails = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute')); //priyanka d -feb-23
			//echo $img_path; die;
			$instituteDetails = $this->master_model->getRecords('institution_master',array('institude_id'=>$memberDetails[0]['associatedinstitute']),array('name')); //priyanka d -feb-23
			if(!empty($instituteDetails)) {
				$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
			}
		
			$this->db->select('optFlg,selected_vendor');
			$this->db->from('member_exam');
			$this->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'pay_status'=>1));		
			$this->db->order_by("id", "desc");
			$optFlgRecordRow = $this->db->get();
			$optFlgRecord=$selected_vendor='';
			if(!empty($optFlgRecordRow)) {
				$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
				$selected_vendor	=	$optFlgRecordRow->row()->selected_vendor;
			}
				

			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id,'idate'=>$exdate,"member_result"=>$result,"venue_result"=>$results,'member_id'=>$member_id,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord,'selected_vendor'=>$selected_vendor);
			
			$html=$this->load->view('admitcardpdfjd', $data, true);
		
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
		    $pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "I");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	
	// function run for new table  from user portal
	public function getadmitcardsp_new(){
	//	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			 
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3));
			$mem_exam_id = $this->uri->segment(4);
			
			$this->db->select('admit_card_details.*, DATE_FORMAT(admit_card_details.created_on, "%d%m%Y%H%i%s") as created_on, member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admit_card_details');
			$this->db->join('member_registration', 'admit_card_details.mem_mem_no = member_registration.regnumber');
			//$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> date("Y-m-d"),'mem_exam_id'=>$mem_exam_id));
			
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> '2021-05-01','mem_exam_id'=>$mem_exam_id));
			
			$record = $this->db->get();
			$result = $record->row(); 
			
			//echo $this->db->last_query(); die;
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}else
			if($result->vendor_code == 4){
			    $vcenter = '4';
			}else
			if($result->vendor_code != 3){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
		
			$caiib = array($this->config->item('examCodeCaiib'),"62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
			$dipcert = array(8,11,18,19,24,25,26,78,79,149,151,153,156,158,162,163,165,166);
			if(in_array($exam_code,$caiib) || in_array($exam_code,$dipcert))
			{
				$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
				$this->db->from('admitcard_info');
				$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
				$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
				$this->db->group_by('venueid');
				$this->db->order_by("date", "asc");
				$record = $this->db->get();
				$results = $record->result();
				
				if(empty($results)) { // priyanka d- 23-march-23 >> added this query because for caaiib admitcard not showing venue details
					$this->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
					$this->db->from('admit_card_details');
					$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1));
					$this->db->group_by('venueid');
					$this->db->order_by("exam_date", "asc");
					$venue_record = $this->db->get();
					$results = $venue_record->result();
				}
				//echo '-----'.$this->db->last_query(); die;
				
			}
			
			else{
				$this->db->select('*');
				$this->db->from('admit_card_details');
				$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> '2021-05-01','mem_exam_id'=>$mem_exam_id));
				$this->db->group_by('venueid');
				$this->db->order_by("exam_date", "asc");
				$nrecord = $this->db->get();
				$results = $nrecord->result();
				//echo '+++++'.$this->db->last_query(); die;
			}

			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB')){
				$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND exm_prd NOT IN(121) AND admit_card_details.mem_exam_id = ".$mem_exam_id."  ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC "); //priyanka d - removed   group by admit_card_details.exam_date >> as same date row was showing only 1 >> 14-feb-23
				//echo '+++++'.$this->db->last_query(); die;
				$subject_result = $subject->result(); 
				//echo '+++++'.$this->db->last_query(); //die;
			}
			else if(in_array($exam_code,$dipcert)){
				$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND admit_card_details.mem_exam_id = ".$mem_exam_id."   group by admit_card_details.exam_date  ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");

				$subject_result = $subject->result();
				//echo '***'.$this->db->last_query();//exit;
			}
			else{
			
				$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND exm_prd NOT IN(121)  AND admit_card_details.mem_exam_id = ".$mem_exam_id."   group by admit_card_details.exam_date ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");

				$subject_result = $subject->result();
				//echo '----'.$this->db->last_query();//exit;
			
			}
			
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = date('d-M-y',strtotime($pdate->exam_date));
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->exam_date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." ".$examdate[0];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();

			$memberDetails = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute')); //priyanka d -feb-23
			//echo $img_path; die;
			$instituteDetails = $this->master_model->getRecords('institution_master',array('institude_id'=>$memberDetails[0]['associatedinstitute']),array('name')); //priyanka d -feb-23
			if(!empty($instituteDetails)) {
				$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
			}
		
			

			$this->db->select('optFlg,selected_vendor');
			$this->db->from('member_exam');
			$this->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'pay_status'=>1));		
			$this->db->order_by("id", "desc");
			$optFlgRecordRow = $this->db->get();
			$optFlgRecord=$selected_vendor='';
			if(!empty($optFlgRecordRow)) {
				$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib

				$selected_vendor = $optFlgRecordRow->row()->selected_vendor;

			}

			// Added by Priyanka W for Transaction no dissplay on admit card
			$this->db->select('transaction_no');
			$payment = $this->db->get_where('payment_transaction', array('member_regnumber' => $result->mem_mem_no, 'ref_id' => $result->mem_exam_id));
			$payment_result = $payment->row();
				
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate,'exam_period'=>$result->exm_prd,'mem_exam_id'=>$mem_exam_id,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord,"member_result"=>$result,"member_id"=>$member_id,"venue_result"=>$results,'selected_vendor'=>$selected_vendor, 'transaction_no' => $payment_result->transaction_no); //priyanka d -feb-23 added instituteDetails,memberDetails,optFlgRecord
			
			if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004  || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1031 || $exam_code == 1032)
			{
				
				$this->load->view('rel_jdadmitcardsp', $data);
			}
			else
			{
				if($member_id=='100097057') {
				//	echo'<pre>';print_r($subject_result);
				}
				$this->load->view('jdadmitcardsp', $data);
			}
			
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	// function run for new table  from user portal
	public function getadmitcardpdfsp_new(){ 
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$mem_exam_id = $this->uri->segment(4);
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admit_card_details.*, DATE_FORMAT(created_on, "%d%m%Y%H%i%s") as created_on, member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admit_card_details');
			$this->db->join('member_registration', 'admit_card_details.mem_mem_no = member_registration.regnumber');
			//$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> date("Y-m-d"),'mem_exam_id'=>$mem_exam_id));
			
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> '2021-05-01','mem_exam_id'=>$mem_exam_id));
			
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}else
			if($result->vendor_code == 4){
			    $vcenter = '4';
			}else
			if($result->vendor_code != 3){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			
			$this->db->select('*');
			$this->db->from('admit_card_details');
			/*$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> '2021-05-01','mem_exam_id'=>$mem_exam_id));*/
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'mem_exam_id'=>$mem_exam_id));
			$this->db->group_by('venueid');
			$this->db->order_by("exam_date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB')){
				$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND admit_card_details.mem_exam_id = ".$mem_exam_id."   ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");//priyanka d - removed   group by admit_card_details.exam_date >> as same date row was showing only 1 >> 14-feb-23
			$subject_result = $subject->result();
			}else{
				$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND admit_card_details.mem_exam_id = ".$mem_exam_id."   group by admit_card_details.exam_date  ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			}
			
			
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				date('d-M-y',strtotime($pdate->exam_date));
				$exdate = date('d-M-y',strtotime($pdate->exam_date));
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->exam_date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." ".$examdate[0];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();

			$memberDetails = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute')); //priyanka d -feb-23
			//echo $img_path; die;
			$instituteDetails = $this->master_model->getRecords('institution_master',array('institude_id'=>$memberDetails[0]['associatedinstitute']),array('name')); //priyanka d -feb-23
			if(!empty($instituteDetails)) {
				$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
			}
			

			$this->db->select('optFlg,selected_vendor');
			$this->db->from('member_exam');
			$this->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'pay_status'=>1));		
			$this->db->order_by("id", "desc");
			$optFlgRecordRow = $this->db->get();
			$optFlgRecord=$selected_vendor='';
			if(!empty($optFlgRecordRow)) {

				$optFlgRecord = $optFlgRecordRow->row()->optFlg;//priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
				$selected_vendor=$optFlgRecordRow->row()->selected_vendor;
			}
				
			$this->db->select('transaction_no');
			$payment = $this->db->get_where('payment_transaction', array('member_regnumber' => $result->mem_mem_no, 'ref_id' => $result->mem_exam_id));
			$payment_result = $payment->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'exam_period'=>$result->exm_prd,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord,"member_result"=>$result,"member_id"=>$member_id,"venue_result"=>$results,'selected_vendor'=>$selected_vendor,'transaction_no'=>$payment_result->transaction_no); //priyanka d - added instituteDetails,memberDetails ,optFlgRecord= 23-feb-23
			
			if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004  || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014  || $exam_code == 2027 || $exam_code == 1017 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1031 || $exam_code == 1032)
			{
				
				
				$html=$this->load->view('rel_jdadmitcardpdf', $data, true);
			}
			else
			{
				$html=$this->load->view('jdadmitcardpdf', $data, true);
			}
			
			if($this->get_ip_address() == '115.124.115.71')
			{
				//echo $html; exit;   
			}
			//echo $html; exit;   
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = $exam_code."_".$result->exm_prd."_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");   
			
		}catch(Exception $e){
			echo $e->getMessage(); 
		}
	}
	
	function get_ip_address()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
    $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
    $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
    $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
    else
    $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }
	
	public function caiib(){
		try{
			
			
		
			$data=array();
			$data['error']='';
			
			$feedback_type = 'new';
			$feedback_exam_name = 'caiib219';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
									/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				
				/*
				$caiib_arr = array($this->config->item('examCodeCaiib'),61,$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));

				$member_arr = array('510385794','510378481','510261819','7617460','510412951','510373797','510419192','510211483','510322865','510394797','510307504','510151050','510296561','510400127','510328813','510256240','510333537','510342771','510399497','510389638','510361474','510401894','510101714','510421696','510395741','510420473','510359276','510366774','510374350','510029479','510366937','510369556','510344605','510250764','510389757','510415838','510402003','510378111','510286534','510294228','510019864','510182114','510434096','510386396','500211137','510284772','510344671','500186013','510422642','510037815','510214850','500110484','510410844','510412403','510315033','510315200','510448028','510343719','510318034','500040206','510381559','510249753','510119444','510369060','500185010','510016717','500109453','510169420','510446167','510419958','510431509','510137606','300037214','510146560','510107616','510453133','510348575','520620553','510185585','510413504','510144143','510063672','500182699');
				
				if(in_array($this->input->post('Username'),$member_arr)){
					$data['error']='<span style="">Invalid credential..</span>';
				}else{*/
					if ($this->form_validation->run() == TRUE){
					    $todayDate  =   date('Y-m-d');
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
					//	$this->db->where("$todayDate < admitcard_info.date");
					//	$this->db->where("admitcard_info.date >=","$todayDate"); //commented by priyanka dhikale on 19-dec-22 as harshala asked candidate not able to download admit card
					$this->db->order_by('admitcard_id','DESC');
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
					//	echo $this->db->last_query(); exit;
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							$foundExCodes=array();
							foreach($chkexam as $excds) {
								$foundExCodes[]=$excds['exm_cd'];
							}
							$commonExCd = array_intersect($foundExCodes, $ex_arr);
							//if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
							if(count($commonExCd)>0) {
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							/*}
							else{
								$data['error']='<span style="">Invalid credential.</span>';
							}*/
						}else{
							//print_r($ex_arr);
							$data['error']='<span style="">Invalid credential...</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
						$data['validation_errors'] = validation_errors();
					}
				}
				
				
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);*/
			$this->load->model('Captcha_model');                 
			$captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
		
			$this->load->view('loginjd_caiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function caiib_covid(){
		try{
			
			
			
			$data=array();
			$data['error']='';
			
			$feedback_type = 'new';
			$feedback_exam_name = 'caiib219';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
									/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				
				$member_arr = array('510286534','510294228','510019864','510182114','510434096','510386396','500211137','510284772','510344671','500186013','510422642','510037815','510214850','500110484','510410844','510412403','510315033','510315200','510448028','510343719','510318034','500040206','510381559','510249753','510119444','510369060','500185010','510016717','500109453','510169420','510446167','510419958','510431509','510137606','300037214','510146560','510107616','510453133','510348575','520620553','510185585','510413504','510144143','510063672','500182699');
				
				if(!in_array($this->input->post('Username'),$member_arr)){
					$data['error']='<span style="">Invalid credential..</span>';
				}else{
					if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
						$data['validation_errors'] = validation_errors();
					}
				}
				
				
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_caiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function caiib_ReSchedul(){
		try{
			$data=array();
			$data['error']='';
			
			$feedback_type = 'new';
			$feedback_exam_name = 'caiib219';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
									/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				
				$member_arr = array('510385794','510378481','510261819','7617460','510412951','510373797','510419192','510211483','510322865','510394797','510307504','510151050','510296561','510400127','510328813','510256240','510333537','510342771','510399497','510389638','510361474','510401894','510101714','510421696','510395741','510420473','510359276','510366774','510374350','510029479','510366937','510369556','510344605','510250764','510389757','510415838','510402003','510378111');
				
				if(in_array($this->input->post('Username'),$member_arr)){
					if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
						$data['validation_errors'] = validation_errors();
					}
				}else{
					$data['error']='<span style="">Invalid credential..</span>';
				}
				
				
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_caiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function caiib220dashboard(){
	//	echo'here';
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/caiib/')); 
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			//echo'<pre>';print_r($exm_arr);
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date >= '".date('Y-m-d')."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					if($exm_arr[0]->exm_cd==$this->config->item('examCodeCaiib')) {
						$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
						FROM admit_exam_master
						JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
						WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date >= '".date('Y-m-d')."'  
						ORDER BY admitcard_info.admitcard_id DESC 
						LIMIT 1;
						");
					}
					else {
						$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
						FROM admit_exam_master
						JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
						WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date >= '".date('Y-m-d')."'  
						ORDER BY admitcard_info.admitcard_id DESC 
						LIMIT 1;
						");
					}
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			$caiib_arr = array($this->config->item('examCodeCaiib'),61,$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));

			foreach($result as $k=>$r) {
				if(!in_array($r->exm_cd,$caiib_arr))
					unset($result[$k]);
			}
			//echo'<pre>';print_r($result);exit;
			// echo $this->db->last_query(); exit;
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function caiib_rescheduled(){
		try{
			$data=array();
			$data['error']='';
			
			$feedback_type = 'new';
			$feedback_exam_name = 'caiibres';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_caiib_rescheduled',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_caiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function caiibdashboardres(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/caiib_rescheduled/'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_caiib_rescheduled where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_caiib_rescheduled.exm_cd, admitcard_caiib_rescheduled.m_1, admitcard_caiib_rescheduled.sub_cd, admit_exam_master.description, admitcard_caiib_rescheduled.mode, admitcard_caiib_rescheduled.date as exam_date, admitcard_caiib_rescheduled.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_caiib_rescheduled ON admitcard_caiib_rescheduled.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_caiib_rescheduled.exm_cd
					ORDER BY admitcard_caiib_rescheduled.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_caiib_rescheduled.exm_cd, admitcard_caiib_rescheduled.m_1, admitcard_caiib_rescheduled.sub_cd, admit_exam_master.description, admitcard_caiib_rescheduled.mode, admitcard_caiib_rescheduled.date as exam_date, admitcard_caiib_rescheduled.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_caiib_rescheduled ON admitcard_caiib_rescheduled.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."'
					ORDER BY admitcard_caiib_rescheduled.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardcdres',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function getadmitcardcdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_caiib_rescheduled.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->join('member_registration', 'admitcard_caiib_rescheduled.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_caiib_rescheduled ON admit_subject_master.subject_code = LEFT(admitcard_caiib_rescheduled.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardcdres', $data);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getadmitcardpdfcdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_caiib_rescheduled.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->join('member_registration', 'admitcard_caiib_rescheduled.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_caiib_rescheduled ON admit_subject_master.subject_code = LEFT(admitcard_caiib_rescheduled.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdfjd', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
	
	
	public function jaiib(){ 
		// if( $this->get_client_ip() != '115.124.115.69' ){
		// 		die;
		// }
		try{
		
	
		
		$data=array();
		$data['error']='';
		
		if(isset($_POST['submit'])){
			$config = array(
							array(
									'field' => 'Username',
									'label' => 'Username',
									'rules' => 'trim|required'
								),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
							array(
									'field' => 'code',
									'label' => 'Code',
									'rules' => 'trim|required|callback_check_captcha_userlogin',
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
				'isactive'=>'1',
				//'usrpassword'=>$encpass
			);
			if ($this->form_validation->run() == TRUE){
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info) > 0){ 
					//if(!in_array($this->input->post('Username'),$member_array)){
						$mysqltime=date("H:i:s");
						$seprate_user_data=array('regid'=>$user_info[0]['regid'],
													'spregnumber'=>$user_info[0]['regnumber'],
													'spfirstname'=>$user_info[0]['firstname'],
													'spmiddlename'=>$user_info[0]['middlename'],
													'splastname'=>$user_info[0]['lastname'],
													'feedback_exam_name' => 'jaiib'
												);
						$this->session->set_userdata($seprate_user_data);
						redirect(base_url().'admitcard/feedback');
					/*}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}*/
				}else{
					$data['error']='<span style="">Invalid credential.</span>';
				}
			}else{
				$data['validation_errors'] = validation_errors();
			}
		}
		/*$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => '/uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
		*/
		$this->load->model('Captcha_model');                 
		$captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
		$data['image'] = $captcha_img;
		//admitcardlogin
		$this->load->view('loginjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function jaiib_covid(){ 
	try{
		
		$member_array = array('510203784','510267903','510220527','510452647','510092880','510463268','500182084','510448149','510456120','510326268','510435647','510443536','510473314','510261919','500080159','510205982','510450872','510198752','510290749','510431654','510432063','510432443','510119198','510421236','510458164','510426331','510447229','510012094','510252169','500176513','510224439','510443269','510415828','510445337','510385075','510331868','510356643','510370523','510175914','510465797','510239407','510448614','510458876','510476597','510109750','510388870','510462857','510311361','500115232','510439084','510481163','510402162','510032512','510115026','510214176','510445202','510036196','510060242','510465260','510170496','510478222','510079340','510133232','510250212','510148809','510292336','510271677','510144894','510316323','510259920','510463480','510098537','510195301','510266425','510426082','500180380','510388825','510320287','510426721','510426162','510346464','510358831','510236045','510442794','510423523','510394302','510426533','500133622','510302407','510418171','510393229','510477348','510315943','510401617','510286160','510467162','500025012','510473577','510233821','510454185','500147163','510461475','510391841','510433257','510451557','510401592','510460547','510421046','510036495','510329745','510404935','500051303','500167023','510409778','510477511','510456370');
		
		$data=array();
		$data['error']='';
		
		if(isset($_POST['submit'])){
			$config = array(
							array(
									'field' => 'Username',
									'label' => 'Username',
									'rules' => 'trim|required'
								),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
							array(
									'field' => 'code',
									'label' => 'Code',
									'rules' => 'trim|required|callback_check_captcha_userlogin',
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
				'isactive'=>'1',
				//'usrpassword'=>$encpass
			);
			if ($this->form_validation->run() == TRUE){
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info) > 0){ 
					if(in_array($this->input->post('Username'),$member_array)){
						$mysqltime=date("H:i:s");
						$seprate_user_data=array('regid'=>$user_info[0]['regid'],
													'spregnumber'=>$user_info[0]['regnumber'],
													'spfirstname'=>$user_info[0]['firstname'],
													'spmiddlename'=>$user_info[0]['middlename'],
													'splastname'=>$user_info[0]['lastname'],
													'feedback_exam_name' => 'jaiib'
												);
						$this->session->set_userdata($seprate_user_data);
						redirect(base_url().'admitcard/feedback');
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
					$data['error']='<span style="">Invalid credential.</span>';
				}
			}else{
				$data['validation_errors'] = validation_errors();
			}
		}
		/*$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => '/uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
		//admitcardlogin
		*/
		$this->load->model('Captcha_model');                 
		$captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
		$data['image'] = $captcha_img;
		$this->load->view('loginjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function jaiib_reschedule(){ 
	try{
		$data=array();
		$data['error']='';
		
		if(isset($_POST['submit'])){
			$config = array(
							array(
									'field' => 'Username',
									'label' => 'Username',
									'rules' => 'trim|required'
								),
							array(
									'field' => 'code',
									'label' => 'Code',
									'rules' => 'trim|required|callback_check_captcha_userlogin',
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
				'isactive'=>'1'
				//'usrpassword'=>$encpass
			);
			
			if ($this->form_validation->run() == TRUE){
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info) > 0){ 
					$mysqltime=date("H:i:s");
					$seprate_user_data=array('regid'=>$user_info[0]['regid'],
												'spregnumber'=>$user_info[0]['regnumber'],
												'spfirstname'=>$user_info[0]['firstname'],
												'spmiddlename'=>$user_info[0]['middlename'],
												'splastname'=>$user_info[0]['lastname'],
												'feedback_exam_name' => 'jaiibres'
											);
					$this->session->set_userdata($seprate_user_data);
					redirect(base_url().'admitcard/feedback');	
				}else{
					$data['error']='<span style="">Invalid credential.</span>';
				}
			}else{
				$data['validation_errors'] = validation_errors();
			}
			
		}
		/*$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => '/uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
		//admitcardlogin
		*/
		$this->load->model('Captcha_model');                 
		$captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
		$data['image'] = $captcha_img;
		$this->load->view('loginjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function jaiibdashboard(){
		try{
			$result = array();
			$tbl = '';
			
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/jaiib/'));
			}
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			
			if(sizeof($exm_arr) > 0 && count($exm_arr) > 1){
				$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
				FROM admit_exam_master
				JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
				WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date >= '".date('Y-m-d')."'
				GROUP BY admitcard_info.exm_cd
				ORDER BY admitcard_info.admitcard_id DESC 
				");
				$result = $record->result();
			}elseif(count($exm_arr) == 1){
				$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
				FROM admit_exam_master
				JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
				WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date >= '".date('Y-m-d')."'
				ORDER BY admitcard_info.admitcard_id DESC 
				LIMIT 1;
				");
				$result = $record->result();
			}else{
				$result = array();
			}
			// if($member_id=='700027730'){
			// 	print_r($result); exit;
			// }
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
		}
	}
	
	public function jaiibdashboardres(){
		try{ 
			$result = array();
			$tbl = '';
			
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/jaiib/'));
			}
			$query = $this->db->query("select exm_cd from admitcard_info_190 where mem_mem_no = '".$member_id."' ");
			
			$exm_arr = $query->result();
			
			if(sizeof($exm_arr) > 0 && count($exm_arr) > 1){
				$record=$this->db->query("SELECT admitcard_info_190.exm_cd, admitcard_info_190.m_1, admitcard_info_190.sub_cd, admit_exam_master.description, admitcard_info_190.mode, admitcard_info_190.date as exam_date, admitcard_info_190.admitcard_id
FROM admit_exam_master
JOIN admitcard_info_190 ON admitcard_info_190.exm_cd = admit_exam_master.exam_code
WHERE admitcard_info_190.mem_mem_no = '".$member_id."' 
GROUP BY admitcard_info_190.exm_cd
ORDER BY admitcard_info_190.admitcard_id DESC 
");
				$result = $record->result();
			}elseif(count($exm_arr) == 1){
				$record=$this->db->query("SELECT admitcard_info_190.exm_cd, admitcard_info_190.m_1, admitcard_info_190.sub_cd, admit_exam_master.description, admitcard_info_190.mode, admitcard_info_190.date as exam_date, admitcard_info_190.admitcard_id
FROM admit_exam_master
JOIN admitcard_info_190 ON admitcard_info_190.exm_cd = admit_exam_master.exam_code
WHERE admitcard_info_190.mem_mem_no = '".$member_id."'
ORDER BY admitcard_info_190.admitcard_id DESC 
LIMIT 1;
");
				$result = $record->result();
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjdres',$data);
			
		}catch(Exception $e){
		}
	}
	
	// function run for old table  from website link without exam codde 101
	public function getadmitcardjdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_info_190.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info_190');
			$this->db->join('member_registration', 'admitcard_info_190.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info_190');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info_190 ON admit_subject_master.subject_code = LEFT(admitcard_info_190.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info_190.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardjdres', $data);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	// function run for old table  from website link without exam codde 101
	public function getadmitcardpdfjdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info_190.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info_190');
			$this->db->join('member_registration', 'admitcard_info_190.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info_190');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info_190 ON admit_subject_master.subject_code = LEFT(admitcard_info_190.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info_190.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdfjd', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function dipcert808(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert808';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcertdashboard808(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcert901(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert901';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcertdashboard901(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date = '27-Apr-19' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  AND date = '27-Apr-19' 
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
		
	public function dipcert(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$this->db->order_by('admitcard_id','desc');
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						//echo $this->db->last_query();
						//exit;
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcert23jan(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd,date');
						//echo $this->db->last_query();
						//echo '>>'.$this->input->post('examcode');
						//echo '<pre>';print_r($chkexam);
						if(count($chkexam) > 0 ){
							$ex_arr = explode(",",$this->input->post('examcode'));
							//print_r($ex_arr);
							
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			*/
			$this->load->model('Captcha_model');                 
		    $captcha_img = $this->Captcha_model->generate_captcha_img('useradmitcardlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcertdashboard(){
		
		// exam code 166,78,153,11,151,18,162,79,19,158,149,8,165,163,26,25,24,156
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			
		
			if(sizeof($exm_arr) > 0){  
				if(count($exm_arr) > 1){ 
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date IN ('2023-07-09','2023-07-16','2023-07-23')
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					//echo $this->db->last_query();die;
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  AND date IN ('2023-07-09','2023-07-16','2023-07-23')
					ORDER BY admitcard_info.admitcard_id DESC
					LIMIT 1;
					");
					$result = $record->result();
					
				}
				
			}else{
				//echo'out';die;
				$result = array();
			}

				// if($member_id == '510344203')
				// { 
				// 	echo count($exm_arr)."-------".$member_id."<pre> Exam =>";
				// 	print_r($exm_arr);
				// 	print_r($result);
				// 	echo $this->db->last_query();exit;
				// }
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			//print_r($data);die;
			$this->load->view('admitdashboardjd',$data);
			//Admit letters for Diploma / Certificate / Blended examinations July-2022 (physical classroom environment)
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcertdashboardtest(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){ 
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date IN ('05-Jan-20','12-Jan-20','19-Jan-20','17-Jan-21','24-Jan-21','31-Jan-21')
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  AND date IN ('05-Jan-20','12-Jan-20','19-Jan-20','17-Jan-21','24-Jan-21','31-Jan-21')
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					
				}
			}else{
				$result = array();
			}
			
			echo $this->db->last_query();
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function feedback(){
		$this->load->view('feedback');
	}
	
	public function getadmitdashboardippb(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
				$name      = $this->session->userdata('firstname')." ".$this->session->userdata('middlename')." ".$this->session->userdata('lastname');	
			}
			if($this->session->userdata('nmregnumber')!=''){
				$member_id = $this->session->userdata('nmregnumber');
				$name      = $this->session->userdata('nmfirstname')." ".$this->session->userdata('nmmiddlename')." ".$this->session->userdata('nmlastname');
			}
			if($this->session->userdata('dbregnumber')!=''){
				$member_id = $this->session->userdata('dbregnumber');
				$name      = $this->session->userdata('dbfirstname')." ".$this->session->userdata('dbmiddlename')." ".$this->session->userdata('dblastname');
			}
			
			if(!isset($member_id)){
				redirect(base_url());
			}
			
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			$result_new = array();
			$result = array();
			$tbl_new = '';
			$tbl = '';
			
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					$tbl = 'old';
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					$tbl = 'old';
				}
			}
			//echo $this->db->last_query();die;
			$query_1 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' order by admitcard_id desc ");
			$exm_arr_1 = $query_1->result();
			//echo $this->db->last_query();
			//echo count($exm_arr);die;
			if(sizeof($exm_arr_1) > 0){ 
			
				if($exm_arr_1[0]->exm_cd == $this->config->item('examCodeJaiib') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeDBF') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSOB')){
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '2021-04-30' AND exam_date != '2019-03-10' AND  exm_prd NOT IN(915,910,912,916,121,221) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					"); // chaitali - reoved not in of exam code on 2021-11-11
				}else{
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '2022-06-12' AND exam_date != '2019-03-10' AND  exm_prd NOT IN(915,910,912,916,121,221) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					");
				}
				 $result_new = $record->result();
				 $tbl_new = 'new';
				 //echo $this->db->last_query();die;
				 /*echo '<pre>';
				 print_r($result_new);*/
			}elseif(count($exm_arr) == 1){ 
			//echo "swati";die;
				if($exm_arr_1[0]->exm_cd == $this->config->item('examCodeJaiib') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeDBF') || $exm_arr_1[0]->exm_cd == $this->config->item('examCodeSOB')){
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '2021-04-30'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,121,221)  
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					LIMIT 1;
					");
				}else{
					$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
					FROM admit_exam_master
					JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
					WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1  AND admit_card_details.exam_date >= '2022-06-12'  AND exam_date != '2019-03-10'  AND exm_prd NOT IN(915,910,912,121,221) 
					GROUP BY admit_card_details.exm_cd
					ORDER BY admit_card_details.admitcard_id DESC 
					LIMIT 1;
					");
				}
			
				
				$result_new = $record->result();
				$tbl_new = 'new';
			}else{
				$tbl = 'none';
				$result = array();
			}
			//echo $tbl_new;
			//echo '<pre>';
			// print_r($result_new);
			// if($member_id == '510219589'){
			// 	echo '<pre>'; print_r($result);
			// 	echo $this->db->last_query();die;
			// }
			$data = array("exam_name"=>$result,"exam_name_new"=>$result_new,'name'=>$name,'frm'=>'dwn','mid'=>$member_id,'tbl'=>$tbl,'tbl_new'=>$tbl_new);
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
}
