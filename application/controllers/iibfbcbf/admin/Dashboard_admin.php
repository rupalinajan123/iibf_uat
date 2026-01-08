<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	use Ilovepdf\Ilovepdf;
	class Dashboard_admin extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 

      $this->login_admin_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      
      if($this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
    }
    
    public function index()
		{   
			$data['act_id'] = "Dashboard";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - BCBF Admin Dashboard';

      //START : DASHBOARD COUNT DATA
      $all_exam_registration_data = $this->Iibf_bcbf_model->get_total_exam_registraion_data();
      
      //START : FOR ALL REGISTRATION COUNT
      $data['total_exam_registraion_cnt'] = $all_exam_registration_data['total_exam_registraion_cnt'];
      $data['total_basic_exam_reg_cnt'] = $all_exam_registration_data['total_basic_exam_reg_cnt'];
      $data['total_advanced_exam_reg_cnt'] = $all_exam_registration_data['total_advanced_exam_reg_cnt'];
      $data['total_basic_re_attempt_exam_reg_cnt'] = $all_exam_registration_data['total_basic_re_attempt_exam_reg_cnt'];
      $data['total_advanced_re_attempt_exam_reg_cnt'] = $all_exam_registration_data['total_advanced_re_attempt_exam_reg_cnt'];
      //END : FOR ALL REGISTRATION COUNT

      //START : FOR TODAYS REGISTRATION COUNT
      $data['today_exam_registraion_cnt'] = $all_exam_registration_data['today_exam_registraion_cnt'];
      $data['today_basic_exam_reg_cnt'] = $all_exam_registration_data['today_basic_exam_reg_cnt'];
      $data['today_advanced_exam_reg_cnt'] = $all_exam_registration_data['today_advanced_exam_reg_cnt'];
      $data['today_basic_re_attempt_exam_reg_cnt'] = $all_exam_registration_data['today_basic_re_attempt_exam_reg_cnt'];
      $data['today_advanced_re_attempt_exam_reg_cnt'] = $all_exam_registration_data['today_advanced_re_attempt_exam_reg_cnt'];
      //END : FOR TODAYS REGISTRATION COUNT
      
      $all_registration_for_training_data = $this->Iibf_bcbf_model->get_total_registraion_for_training_data();
      $data['total_registraion_for_training_cnt'] = $all_registration_for_training_data['total_registraion_for_training_cnt'];
      $data['total_basic_reg_for_training_cnt'] = $all_registration_for_training_data['total_basic_reg_for_training_cnt'];
      $data['total_advance_reg_for_training_cnt'] = $all_registration_for_training_data['total_advance_reg_for_training_cnt'];
      $data['total_re_attempt_basic_reg_for_training_cnt'] = $all_registration_for_training_data['total_re_attempt_basic_reg_for_training_cnt'];
      $data['total_re_attempt_advanced_reg_for_training_cnt'] = $all_registration_for_training_data['total_re_attempt_advanced_reg_for_training_cnt'];
      $data['total_eligible_for_exam'] = $all_registration_for_training_data['total_eligible_for_exam'];
      $data['total_re_enroll_for_training'] = $all_registration_for_training_data['total_re_enroll_for_training'];
      
      $this->load->view('iibfbcbf/admin/dashboard_admin', $data);
    }

    /******** START : CHANGE ADMIN PASSWORD ********/
    function change_password()
		{   
      $data['act_id'] = "Change Password";
			$data['sub_act_id'] = "Change Password";
      $log_slug = '';

      if($this->login_user_type == "admin") 
      { 
        $data['page_title'] = 'IIBF - BCBF Admin Change Password'; 

        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_id' => $this->login_admin_id), 'admin_id, is_active, is_deleted');

        $log_slug = 'admin_self_password_action';
      }       
      			
			if(isset($_POST) && count($_POST) > 0)
			{ 
				$this->form_validation->set_rules('current_pass_admin', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password',array('required' => 'Please enter %s'));
				$this->form_validation->set_rules('new_pass_admin', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				$this->form_validation->set_rules('confirm_pass_admin', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				
				if($this->form_validation->run())		
				{
          $posted_arr = json_encode($_POST);
          $admin_name = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type);

          if($this->login_user_type == "admin")
          {
            $up_data['admin_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('new_pass_admin'));
            $up_data['updated_on'] = date("Y-m-d H:i:s");
            $up_data['updated_by'] = $this->login_admin_id;
            $this->master_model->updateRecord('iibfbcbf_admin', $up_data, array('admin_id' => $this->login_admin_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Admin : Profile password updated', 'iibfbcbf_admin', $this->db->last_query(), $this->login_admin_id,'admin_self_password_action','The admin '.$admin_name['disp_name'].' has successfully updated the password', $posted_arr); 
          }  
					$this->session->set_flashdata('success','Password successfully updated');
					
          redirect(site_url('iibfbcbf/admin/dashboard_admin/change_password'));
				}
			}
			
      $data["enc_login_admin_id"] = url_encode($this->login_admin_id);
      //$data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('module_slug' => $log_slug, 'pk_id' => $this->login_admin_id), 'log_id, module_slug, description, created_on', array('created_on'=>'ASC'));
			$this->load->view('iibfbcbf/admin/change_password_admin', $data);
		}/******** END : CHANGE ADMIN PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		function validation_check_old_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['current_pass_admin'] != "")
			{
        if($type == '1') { $current_pass_admin = $this->security->xss_clean($this->input->post('current_pass_admin')); }
        else if($type == '0') { $current_pass_admin = $str; }        
								
				$enc_password = $this->Iibf_bcbf_model->password_encryption($current_pass_admin);

        if($this->login_user_type == "admin") 
        { 
          if(count($this->master_model->getRecords('iibfbcbf_admin', array('admin_password' => $enc_password, 'admin_id' => $this->login_admin_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted')) > 0)
          {
            $return_val_ajax = 'true';
          }
        }         
			}

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['current_pass_admin'] != "")
        {
          $this->form_validation->set_message('validation_check_old_password','Please enter correct old password');
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
		function validation_check_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'Please enter the Confirm Password to match the New Password';
			if(isset($_POST) && $_POST['confirm_pass_admin'] != "")
			{
        $new_pass_admin = $this->security->xss_clean($this->input->post('new_pass_admin'));
        if($type == '1') { $confirm_pass_admin = $this->security->xss_clean($this->input->post('confirm_pass_admin')); }
        else if($type == '0') { $confirm_pass_admin = $str; }   
        
        if($new_pass_admin == $confirm_pass_admin)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_pass_admin'] != "")
        {
          $this->form_validation->set_message('validation_check_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
    function fun_validate_password($str) // Custom callback function for check valid PASSWORD
    {
      if($str != '')
      {
        $password_length = strlen($str);
        $err_msg = '';
        if($password_length < 8) { $err_msg = 'Please enter minimum 8 characters in password'; }
        else if($password_length > 20) { $err_msg = 'Please enter maximum 20 characters in password'; }

        if($err_msg != "")
        {
          $this->form_validation->set_message('fun_validate_password', $err_msg);
          return false;
        }
        else
        {
          $result = $this->Iibf_bcbf_model->fun_validate_password($str); 
          if($result['flag'] == 'success') { return true; }
          else
          {
            $this->form_validation->set_message('fun_validate_password', $result['response']);
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/
		function validation_check_new_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'New password must be different from Current password';
			
      if(isset($_POST) && $_POST['new_pass_admin'] != "")
			{
        $current_pass_admin = $this->security->xss_clean($this->input->post('current_pass_admin'));
        if($type == '1') { $new_pass_admin = $this->security->xss_clean($this->input->post('new_pass_admin')); }
        else if($type == '0') { $new_pass_admin = $str; } 
        
        if (preg_match('/[A-Z]/', $new_pass_admin) && preg_match('/[a-z]/', $new_pass_admin) && preg_match('/[0-9]/', $new_pass_admin))
        {
          if($current_pass_admin != $new_pass_admin)
          {
            $return_val_ajax = 'true';
          }
        }
        else
        {
          $msg = 'Password must contain at least one upper-case character, one lower-case character, one digit and one special character';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['new_pass_admin'] != "")
        {
          $this->form_validation->set_message('validation_check_new_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/
		
		function test_pdf()
		{
			require_once(APPPATH.'/third_party/ilovepdf-php-master/init.php');
						
			$ilovepdf = new Ilovepdf('project_public_32c06e2d984932301b97099d60f93153_p5uUe32f9bd211a7370f904b07cc86a13102d','secret_key_9be40b7532a6c26f5602a1dfae47a7ad_vk41l3dcfff8304eff5aaa2df8abb5495c43c');
			
			/* $myTask = $ilovepdf->newTask('compress');
			$file1 = $myTask->addFile(APPPATH.'../uploads/xxx.pdf');
			$myTask->execute();
			$myTask->download(); */
			
			// Create a new task
			$myTask = $ilovepdf->newTask('repair');
			// Add files to task for upload
			$file1=$myTask->addFile(APPPATH.'../uploads/passenger evacuation.pdf');
			// Execute the task
			$myTask->execute();
			// Download the package files
			$myTask->download('uploads/');
		}
		
		function test_img()
		{
			/* $imageURL ='https://iibf.esdsconnect.com/assets/images/iibf_logo_short.png';
			$contents = base64_encode(file_get_contents($imageURL));
			echo $contents; */
			
			$contents = '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dXRlX3JlbmV3YWxfaW52b2ljZVwpKS9DcmVhdGlvbkRhdGUoRDoyMDIxMDUxMTE1MTYzNSswNSczMCcpL1Byb2R1Y2VyKGlUZXh0IDIuMS43IGJ5IDFUM1hUKT4+CmVuZG9iagp4cmVmCjAgMTIKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDA2NDgxIDAwMDAwIG4gCjAwMDAwMDY3ODQgMDAwMDAgbiAKMDAwMDAwMDAxNSAwMDAwMCBuIAowMDAwMDAyNzcwIDAwMDAwIG4gCjAwMDAwMDQ3NzIgMDAwMDAgbiAKMDAwMDAwNjg3MiAwMDAwMCBuIAowMDAwMDA2NzQ5IDAwMDAwIG4gCjAwMDAwMDY5MzUgMDAwMDAgbiAKMDAwMDAwNjk4OSAwMDAwMCBuIAowMDAwMDA3MDIxIDAwMDAwIG4gCjAwMDAwMDcxMjUgMDAwMDAgbiAKdHJhaWxlcgo8PC9JbmZvIDExIDAgUi9JRCBbPGQ3ZTQ1NzA4MGYyNTcxYjBiYjUzMTcwZTk2OGY5MThmPjwwNDg1NWQ5NDc4NjU2NTY1NjY0OWZkMThhNDAxM2U0NT5dL1Jvb3QgMTAgMCBSL1NpemUgMTI+PgpzdGFydHhyZWYKNzMwMQolJUVPRgo=';
			//echo $contents;
			//echo '<br><br>';
			//echo "<img src='data:image/jpeg;charset=utf8;base64,$contents' />";
			
			$img_data = $this->Common_model->getRecordsCi('check_img', array(), '', '', '', '', '');
			//print_r($img_data);
			//foreach($img_data as $res)
			{
				/* header("Content-type: image/png"); 
				//echo "<img src='data:image/png;charset=utf8;base64,".$res['img_name']."' />";
				//echo "<br><img src='".$res['img_name']."' />";
        //echo $res['img_name'];  */
				
				//echo '<br><img src="data:image/png;base64,'.base64_encode($res['img_name']).'"/>';
				/* echo '<br><a href="'.base64_decode($contents).'">Download</a>';
				echo '<br><a href="'.base64_decode($contents).'">Download</a>';
				echo '<br><a href="'.base64_decode($contents).'">Download</a>';
				echo '<br><a href='.base64_decode($contents).'">Download</a>'; */
			}
			
			//header("Content-type: image/png"); 
			//echo "<img src='data:image/png;charset=utf8;base64,".$res['img_name']."' />";
			//echo "<br><img src='".$res['img_name']."' />";
			//echo $res['img_name']; 
			
			//header("Content-type: image/png");
			//echo '<img src="data:image/png;base64,'.base64_decode($contents).'"/>';
			
			//header("Content-type: image/png");
			//echo '<img src="data:image/png;base64,'.$contents.'"/>';
			
			//header("Content-type: image/png");
			//echo '<img src="data:image/jpg;'.$contents.'"/>';
			
			//header("Content-type: image/png");
			//echo '<img src="'.base64_decode($contents).'" />';
			
			
			$file_cont=base64_decode($contents);
			
			
			header('Content-Type: application/pdf');
			header('Content-Length:'.strlen($file_cont));
			
			header('Content-disposition: inline; filename=cache.pdf');
			//file_put_contents('../public_html/uploads/put_file/testppp.pdf',$file);
			//header('Content-Type: application/pdf');
			header('Content-Transfer-Encoding: Binary');
			//	header('Content-disposition: inline; filename=testppp.pdf');
			//echo base64_decode($file);
			echo $file_cont; 
			
			/* $pdf_base64 = "base64pdf.txt";
			//Get File content from txt file
			$pdf_base64_handler = fopen($pdf_base64,'r');
			$pdf_content = fread ($pdf_base64_handler,filesize($pdf_base64));
			fclose ($pdf_base64_handler); */
			//Decode pdf content
			/* $pdf_decoded = base64_decode ($contents);
			//Write data back to pdf file
			$pdf = fopen ('test.pdf','w');
			fwrite ($pdf,$pdf_decoded);
			//close output file
			fclose ($pdf);
			echo 'Done'; */
		}

    function set_session()
    {
      $this->session->sess_expiration = '30';
      $_SESSION['test_session'] = date("Y-m-d H:i:s");

      print_r($_SESSION);
    }

    function get_session()
    {
      print_r($_SESSION);
    }

    //iibfbcbf_send_mail_common($subject='', $from_email='logs@iibf.esdsconnect.com', $from_name='IIBF', $to_email='', $to_name='', $cc_email='', $bcc_email='', $reply_to_email='noreply@iibf.org.in', $reply_to_name='IIBF', $mail_data='', $attachment='', $view_flag='0', $is_smtp='0')
    function test_mail()
    {
      $mail_arg = array();
      $mail_arg['subject'] = 'Test Mail Subject';      
      $mail_arg['to_email'] ='sagar.matale01@gmail.com,anil.s@esds.co.in';
      $mail_arg['to_name'] = 'Sagar';
      $mail_arg['cc_email'] = 'sagar.matale01@gmail.com,anil.s@esds.co.in';
      $mail_arg['bcc_email'] = 'sagar.matale@esds.co.in';
      $mail_arg['mail_content'] = 'Test Mail Data';
      //$mail_arg['view_flag'] = '1';
      //$mail_arg['is_smtp'] = '1';
      $res = $this->Iibf_bcbf_model->iibfbcbf_send_mail_common($mail_arg);
      _pa($res); 
    }


    function data_uri($file, $mime) 
    {  
      $contents = file_get_contents($file);
      $base64   = base64_encode($contents);
      return ('data:' . $mime . ';base64,' . $base64);
    }

    function match_photo()
    {
      $display_photo_path = 'https://iibf.esdsconnect.com/staging/uploads/sagar/';

      $image_arr = array();

      $image_arr[0]['image1'] = 'photo_802630953.jpeg';
      $image_arr[0]['image2'] = 'id_proof_802630953.jpeg';
      
      $image_arr[1]['image1'] = 'photo_802626825.jpeg';
      $image_arr[1]['image2'] = 'id_proof_802626825.jpg';

      $image_arr[2]['image1'] = 'photo_802614725.jpg';
      $image_arr[2]['image2'] = 'id_proof_802614725.jpg';

      /* $image_arr[3]['image1'] = 'photo_802422025.jpg';
      $image_arr[3]['image2'] = 'id_proof_802422025.jpg';

      $image_arr[4]['image1'] = 'photo_802420605.jpg';
      $image_arr[4]['image2'] = 'id_proof_802420605.jpg'; */
      
      echo '<style>
              table { width:800px; margin:20px auto; }  
              table, th, td { border:1px solid #000; border-collapse: collapse; } 
              th { padding:10px 10px; text-align:center; background:#eee; } 
              td { padding:5px 10px; text-align:center; } 
            </style>
            <table>
              <thead>
                <tr>
                  <th>Sr. No.</th>
                  <th>Image 1</th>
                  <th>Image 2</th>
                  <th>Response</th>
                </tr>
              </thead>
              <tbody>';
                $i = 1;
                foreach($image_arr as $res)
                {
                  $response = $this->face_Verification($res['image1'], $res['image2']);

                  if($response['flag'] == 'error')
                  {
                    $disp_msg = $response['response'];
                  }
                  else if($response['flag'] == 'success')
                  {
                    if(isset($response['response']['statusCode']) && $response['response']['statusCode'] == '200')
                    {
                      $disp_msg = 'statusCode : '.$response['response']['statusCode'];
                      $disp_msg .= '<br>statusMessage : '.$response['response']['statusMessage'];
                      $disp_msg .= '<br>resultIndex : '.$response['response']['data']['resultIndex'];
                      $disp_msg .= '<br>resultMessage : '.$response['response']['data']['resultMessage'];
                      $disp_msg .= '<br>similarPercent : '.$response['response']['data']['similarPercent'];
                    }
                  }

                  /* $response_detection = $this->face_detection($res['image1']); */                  
                  
                  echo '
                    <tr>
                      <td>'.$i.'</td>
                      <td><img src="'.$display_photo_path.$res['image1'].'" style="max-height:100px; max-width:200px;"></td>
                      <td><img src="'.$display_photo_path.$res['image2'].'" style="max-height:100px; max-width:200px;"></td>
                      <td style="text-align:left;">';
                        echo $disp_msg;
                        /* echo '<pre>'; print_r($response_detection); echo '</pre>'; */
                      echo '</td>
                    </tr>';

                  $i++;
                }
        echo '</tbody>
            </table>';
    }

    function face_Verification($image1, $image2)
    {  
      $match_photo_path = 'http://iibf.teamgrowth.net/uploads/sagar/';

      $curl = curl_init();
      $options = array(
                        CURLOPT_URL => "https://face-verification2.p.rapidapi.com/faceverification",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        
                        
                        //CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"linkFile1\"\r\n\r\nhttps://i.ds.at/PKrIXQ/rs:fill:750:0/plain/2022/11/08/Jordan-StraussInvisionAP.jpg\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"linkFile2\"\r\n\r\nhttps://pyxis.nymag.com/v1/imgs/e0a/79c/5671d6e6089515f706e9b2288d41d9e824-you-people.1x.rsquare.w1400.jpg\r\n-----011000010111000001101001--\r\n\r\n",
                        
                        CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"linkFile1\"\r\n\r\n".$match_photo_path.$image1."\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"linkFile2\"\r\n\r\n".$match_photo_path.$image2."\r\n-----011000010111000001101001--\r\n\r\n",
                        
                        CURLOPT_HTTPHEADER => 
                        [
                          "Content-Type: multipart/form-data; boundary=---011000010111000001101001",
                          "x-rapidapi-host: face-verification2.p.rapidapi.com",
                          "x-rapidapi-key: 4330cd4bc5msh8250c0a753ecce8p1c7f79jsnb757510a12f5"
                        ],
                      );
      
      curl_setopt_array($curl, $options);

      //_pa($options); exit;

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      $result['flag'] = 'error';
      $result['response'] = '';

      if ($err) 
      {
        $result['response'] = "cURL Error #:" . $err;
      } 
      else 
      {
        //echo $response;
        $result['flag'] = 'success';
        $result['response'] = $res = json_decode($response, true);
        //_pa($res);
      }

      return $result;
    }

    function face_detection($image)
    {
      $match_photo_path = 'http://iibf.teamgrowth.net/uploads/sagar/';

      $curl = curl_init();

      curl_setopt_array($curl, [
        CURLOPT_URL => "https://faceanalyzer-ai.p.rapidapi.com/faceanalysis",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"url\"\r\n\r\n".$image."\r\n-----011000010111000001101001--\r\n\r\n",
        CURLOPT_HTTPHEADER => [
          "Content-Type: multipart/form-data; boundary=---011000010111000001101001",
          "x-rapidapi-host: faceanalyzer-ai.p.rapidapi.com",
          "x-rapidapi-key: 4c9543be78mshb0fbd0dcbbd0383p1a8402jsn5d725d185620"
        ],
      ]);

      //Sagar Key : 4330cd4bc5msh8250c0a753ecce8p1c7f79jsnb757510a12f5
      //Anil Key : 4c9543be78mshb0fbd0dcbbd0383p1a8402jsn5d725d185620

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      $result['flag'] = 'error';
      $result['response'] = '';

      if ($err) 
      {
        $result['response'] = "cURL Error #:" . $err;
      } 
      else 
      {
        $result['flag'] = 'success >> '.$match_photo_path.$image;
        $result['response'] = json_decode($response, true);
      }

      return $result;
    }
  }	    