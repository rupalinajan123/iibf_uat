<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  header("Access-Control-Allow-Origin: *");
  
  class Member_details_search extends CI_Controller
  {    
    public function __construct()
    { //exit;
      parent::__construct();
      $this->load->library('upload');
      $this->load->helper('upload_helper');
      $this->load->helper('master_helper');
      $this->load->helper('general_helper');
      $this->load->helper('blended_invoice_helper');
      $this->load->model('Master_model');
      $this->load->library('email');
      $this->load->helper('date');
      $this->load->library('email');
      $this->load->model('Emailsending');
      $this->load->model('log_model');
	  
	  /* if(file_exists('./uploads/idproof/pr_801839579.jpg'))
	  {
		  @unlink('./uploads/idproof/pr_801839579.jpg');
	  } */
	}
    
    public function index()
    {
			/* error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1"); */
		//	unlink('/home/supp0rttest/public_html/uploads/photograph/p_802178883.jpg');
	//	echo '/home/supp0rttest/public_html/uploads/photograph/p_802178883.jpg';
			$this->load->model('Image_search_model');
      $msg = $member_no = $member_response['member_data'] = $member_response['scannedphoto'] = $member_response['idproofphoto'] = $member_response['scannedsignaturephoto'] = $member_response['declarationphoto'] = '';
      $download_btn_flag = 0;
      
      if(isset($_POST) && count($_POST) > 0)
			{
				$this->form_validation->set_rules('member_no', 'Member No', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));		
				if($this->form_validation->run())
				{
          $member_no = $this->input->post('member_no');
          //$member_response = $this->get_member_data($member_no);
          $member_response = $this->Image_search_model->get_member_data($member_no);
          
          // If member data not found for posted member number, then display member number validation message
          if(empty($member_response['member_data']) || $member_response['member_data'] == "") 
          { 
            $msg = '<label class="error">Please enter valid member No.</label>'; 
					}
				}
			}
      
      if($member_response['scannedphoto'] != "" || $member_response['idproofphoto'] != "" || $member_response['scannedsignaturephoto'] |= "" || $member_response['declarationphoto'] != "")
      {
        $download_btn_flag = 1;
			}
			
			/* $scannedphoto = $member_response['scannedphoto'];
			$expected_scannedphoto = 'uploads/photograph/p_'.$member_no.'.jpg';
			if($scannedphoto != $expected_scannedphoto)
			{						
				$chk_response = $this->update_image_name($scannedphoto,$expected_scannedphoto);
				if($chk_response != "") 
				{ 
					$member_response['scannedphoto'] = $chk_response;
					
					$explode_arr = explode('/',$chk_response);
					$update_data = array('scannedphoto' => end($explode_arr));
					//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				}
			} */	
			
			/* $scannedsignaturephoto = $member_response['scannedsignaturephoto'];
			$expected_scannedsignaturephoto = 'uploads/scansignature/s_'.$member_no.'.jpg';
			if($scannedsignaturephoto != $expected_scannedsignaturephoto)
			{
				$chk_response = $this->update_image_name($scannedsignaturephoto,$expected_scannedsignaturephoto);
				if($chk_response != "") 
				{ 
					$member_response['scannedsignaturephoto'] = $chk_response;
					
					$explode_arr = explode('/',$chk_response);
					$update_data = array('scannedsignaturephoto' => end($explode_arr));
					//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				}
			} */
			
			/* $idproofphoto = $member_response['idproofphoto'];
			$expected_idproofphoto = 'uploads/idproof/pr_'.$member_no.'.jpg';
			if($idproofphoto != $expected_idproofphoto)
			{
				$chk_response = $this->update_image_name($idproofphoto,$expected_idproofphoto);
				if($chk_response != "") 
				{ 
					$member_response['idproofphoto'] = $chk_response;
					$explode_arr = explode('/',$chk_response);
					$update_data = array('idproofphoto' => end($explode_arr));
					
					//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				}
			} */
						
      $data['member_no'] = $member_no;
      $data['member_data'] = $member_response['member_data'];
      $data['msg'] = $msg;
      $data['scannedphoto'] = $member_response['scannedphoto'];
      $data['idproofphoto'] = $member_response['idproofphoto'];
      $data['scannedsignaturephoto'] = $member_response['scannedsignaturephoto'];
      $data['declarationphoto'] = $member_response['declarationphoto'];
      $data['download_btn_flag'] = $download_btn_flag;
      
      $data['middle_content'] = 'member_search/index';
      $this->load->view('member_search/member_common_view', $data);
		}
    
    public function get_member_data_BK($member_no=0)//CREATED MODAL FOR IT TO USE COMMENLY
    {
      $select = "regid, reg_no, regnumber, namesub, firstname, middlename, lastname, email, mobile, scannedphoto, scannedsignaturephoto, idproofphoto, declaration, aadhar_card, id_proof_flag, kyc_status, kyc_edit, isactive, isdeleted, image_path,registrationtype";
      //$whr_con['regnumber'] = $member_no;
			$this->db->where('(regnumber = "'.$member_no.'" OR email = "'.$member_no.'")');
      $whr_con['isactive'] = '1';
      $whr_con['isdeleted'] = '0';
      $member_data = $this->master_model->getRecords('member_registration',$whr_con,$select,array(),'',1);
      $scannedphoto = $idproofphoto = $scannedsignaturephoto = '';  
      
      if(!empty($member_data))
      {

		
        $db_img_path = $member_data[0]['image_path']; //Get old image path from database
        
				$final_photo_img = '';
				if($member_data[0]['scannedphoto'] != "")
				{
					$photo_img_arr = explode('.', $member_data[0]['scannedphoto']);
					if(count($photo_img_arr) > 0)
					{
						$chk_photo_img = $photo_img_arr[0];
						
						if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpg'))
						{
							$final_photo_img = $chk_photo_img.'.jpg';
						}
						else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.JPG'))
						{
							$final_photo_img = $chk_photo_img.'.JPG';
						}
						else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpeg'))
						{
							$final_photo_img = $chk_photo_img.'.jpeg';
						}
						else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.JPEG'))
						{
							$final_photo_img = $chk_photo_img.'.JPEG';
						}
					}
				}				
				
        if($final_photo_img != "") //Check photo in regular folder
        { 
          $scannedphoto = "uploads/photograph/".$final_photo_img; 
				}
        else if($db_img_path != "") //Check photo in old image path
        {
          if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$member_data[0]['reg_no'].".jpg"))
          {
            $phtofile = $scannedphoto = "uploads".$db_img_path."photo/p_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$member_data[0]['reg_no'].".JPG"))
          {
            $phtofile = $scannedphoto = "uploads".$db_img_path."photo/p_".$member_data[0]['reg_no'].".JPG"; 
					}
          else if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$member_data[0]['regnumber'].".jpg"))
          {
            $phtofile = $scannedphoto = "uploads".$db_img_path."photo/p_".$member_data[0]['regnumber'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$member_data[0]['regnumber'].".JPG"))
          {
            $phtofile = $scannedphoto = "uploads".$db_img_path."photo/p_".$member_data[0]['regnumber'].".JPG"; 
					}
				}
        else if($member_data[0]['reg_no'] != '') //Check photo in kyc folder      
        {
          if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['reg_no'].".JPG"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['reg_no'].".JPG"; 
					}
          else if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['regnumber'].".JPG"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['regnumber'].".JPG"; 
					}
				}
				else  //Check photo in logs          
        {
					if($member_data[0]['registrationtype']=='NM')
					{
						$this->db->where('title','Non-Member user registration');
					}
					else if($member_data[0]['registrationtype']=='DB')
					{
						$this->db->where('title','DBF user registration');
					}
					else if($member_data[0]['registrationtype']=='O')
					{
						$this->db->where('title','Member user registration');
					}
					$this->db->like('description', $member_data[0]['email']);
					$member_log = $this->master_model->getRecords('userlogs');
        	
					$chk_photo_flag = 0;
					if(count($member_log) > 0)
					{
						$user_details = unserialize($member_log[0]['description']);
						if(file_exists(FCPATH."uploads/photograph/".$user_details['scannedphoto'].".jpg"))
						{
							$scannedphoto = "uploads/photograph/".$user_details['scannedphoto']; 		
							$chk_photo_flag = 1;
						}
						else if(file_exists(FCPATH."uploads/photograph/".$user_details['scannedphoto'].".JPG"))
						{
							$scannedphoto = "uploads/photograph/".$user_details['scannedphoto']; 		
							$chk_photo_flag = 1;
						}
					}
					
					if($chk_photo_flag == 0)
					{
						if($member_data[0]['registrationtype']=='NM')
						{
							$this->db->where('title','Non-Member Traning user registration');
						}
				  	$this->db->like('description', $member_data[0]['email']);
						$member_log = $this->master_model->getRecords('userlogs');
						if(count($member_log) > 0)
						{
							$user_details = unserialize($member_log[0]['description']);							
							if(file_exists(FCPATH."uploads/photograph/".$user_details['scannedphoto']))
							{
								$scannedphoto = "uploads/photograph/".$user_details['scannedphoto'];							
							}
						}
					}
				}
				
				
				$final_idproofphoto_img = '';
				if($member_data[0]['idproofphoto'] != "")
				{
					$idproofphoto_img_arr = explode('.', $member_data[0]['idproofphoto']);
					if(count($idproofphoto_img_arr) > 0)
					{
						$chk_idproofphoto_img = $idproofphoto_img_arr[0];
						
						if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpg'))
						{
							$final_idproofphoto_img = $chk_idproofphoto_img.'.jpg';
						}
						else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.JPG'))
						{
							$final_idproofphoto_img = $chk_idproofphoto_img.'.JPG';
						}
						else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpeg'))
						{
							$final_idproofphoto_img = $chk_idproofphoto_img.'.jpeg';
						}
						else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.JPEG'))
						{
							$final_idproofphoto_img = $chk_idproofphoto_img.'.JPEG';
						}
					}
				} 
				
				if($final_idproofphoto_img != "") //Check id proof in regular folder
        { 
					$idproofphoto = "uploads/idproof/".$final_idproofphoto_img; 
				}
        else if($db_img_path != "") //Check id proof in old image path
        { 
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$member_data[0]['reg_no'].".jpg"))
          {
            $idproofphoto = "uploads".$db_img_path."idproof/pr_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$member_data[0]['reg_no'].".JPG"))
          {
            $idproofphoto = "uploads".$db_img_path."idproof/pr_".$member_data[0]['reg_no'].".JPG"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$member_data[0]['regnumber'].".jpg"))
          {
            $idproofphoto = "uploads".$db_img_path."idproof/pr_".$member_data[0]['regnumber'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$member_data[0]['regnumber'].".JPG"))
          {
            $idproofphoto = "uploads".$db_img_path."idproof/pr_".$member_data[0]['regnumber'].".JPG"; 
					}
				}
        elseif($member_data[0]['reg_no'] != "") //Check Idproof in kyc folder
        {
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['reg_no'].".jpg"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['reg_no'].".JPG"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['reg_no'].".JPG"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['regnumber'].".jpg"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['regnumber'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['regnumber'].".JPG"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['regnumber'].".JPG"; 
					}
				}
				else  //Check Idproof in logs          
        {
					if($member_data[0]['registrationtype']=='NM')
					{
						$this->db->where('title','Non-Member user registration');
					}
					else if($member_data[0]['registrationtype']=='DB')
					{
						$this->db->where('title','DBF user registration');
					}
					else if($member_data[0]['registrationtype']=='O')
					{
						$this->db->where('title','Member user registration');
					}
					$this->db->like('description', $member_data[0]['email']);
					$member_log = $this->master_model->getRecords('userlogs');
					
					$chk_idproof_flag = 0;
        	if(count($member_log) > 0)
					{
						$user_details = unserialize($member_log[0]['description']);
						if(file_exists(FCPATH."uploads/idproof/".$user_details['idproofphoto']))
						{
							$idproofphoto = "uploads/idproof/".$user_details['idproofphoto'];
							$chk_idproof_flag = 1;
						}
					}
					
					if($chk_idproof_flag == 0)
					{
						if($member_data[0]['registrationtype']=='NM')
						{
							$this->db->where('title','Non-Member Traning user registration');
						}
				  	$this->db->like('description', $member_data[0]['email']);
						$member_log = $this->master_model->getRecords('userlogs');
						if(count($member_log) > 0)
						{
							$user_details = unserialize($member_log[0]['description']);
							if(file_exists(FCPATH."uploads/idproof/".$user_details['idproofphoto']))
							{
								$idproofphoto = "uploads/idproof/".$user_details['idproofphoto'];
							}
						}
					}
				}
        
				
				$final_scanphoto_img = '';
				if($member_data[0]['scannedsignaturephoto'] != "")
				{
					$scanphoto_img_arr = explode('.', $member_data[0]['scannedsignaturephoto']);
					if(count($scanphoto_img_arr) > 0)
					{
						$chk_scanphoto_img = $scanphoto_img_arr[0];
						
						if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpg'))
						{
							$final_scanphoto_img = $chk_scanphoto_img.'.jpg';
						}
						else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.JPG'))
						{
							$final_scanphoto_img = $chk_scanphoto_img.'.JPG';
						}
						else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpeg'))
						{
							$final_scanphoto_img = $chk_scanphoto_img.'.jpeg';
						}
						else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.JPEG'))
						{
							$final_scanphoto_img = $chk_scanphoto_img.'.JPEG';
						}
					}
				}
				
        if ($final_scanphoto_img != "") //Check signature in regular folder
        { 
          $scannedsignaturephoto = "uploads/scansignature/".$final_scanphoto_img; 
				}
        else if($db_img_path != "") //Check signature in old image path
        { 
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedsignaturephoto = "uploads".$db_img_path."signature/s_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$member_data[0]['reg_no'].".JPG"))
          {
            $scannedsignaturephoto = "uploads".$db_img_path."signature/s_".$member_data[0]['reg_no'].".JPG"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedsignaturephoto = "uploads".$db_img_path."signature/s_".$member_data[0]['regnumber'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$member_data[0]['regnumber'].".JPG"))
          {
            $scannedsignaturephoto = "uploads".$db_img_path."signature/s_".$member_data[0]['regnumber'].".JPG"; 
					}
				}
        elseif($member_data[0]['reg_no'] != "") //Check signature in kyc folder
        {
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['reg_no'].".JPG"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['reg_no'].".JPG"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['regnumber'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['regnumber'].".JPG"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['regnumber'].".JPG"; 
					}
				}
				else  //Check signature in logs          
        {
					if($member_data[0]['registrationtype']=='NM')
					{
						$this->db->where('title','Non-Member user registration');
					}
					else if($member_data[0]['registrationtype']=='DB')
					{
						$this->db->where('title','DBF user registration');
					}
					else if($member_data[0]['registrationtype']=='O')
					{
						$this->db->where('title','Member user registration');
					}
					$this->db->like('description', $member_data[0]['email']);
					$member_log = $this->master_model->getRecords('userlogs');
					
					$chk_signature_flag = 0;
        	if(count($member_log) > 0)
					{
						$user_details = unserialize($member_log[0]['description']);
						if(file_exists(FCPATH."uploads/scansignature/".$user_details['scannedsignaturephoto']))
						{
							$scannedsignaturephoto = "uploads/scansignature/".$user_details['scannedsignaturephoto']; 
							$chk_idproof_flag = 1;
						}
					}					
					
					if($chk_idproof_flag == 0)
					{
						if($member_data[0]['registrationtype']=='NM')
						{
							$this->db->where('title','Non-Member Traning user registration');
						}
				  	$this->db->like('description', $member_data[0]['email']);
						$member_log = $this->master_model->getRecords('userlogs');
						if(count($member_log) > 0)
						{
							$user_details = unserialize($member_log[0]['description']);
							if(file_exists(FCPATH."uploads/scansignature/".$user_details['scannedsignaturephoto']))
							{
								$scannedsignaturephoto = "uploads/scansignature/".$user_details['scannedsignaturephoto']; 
							}
						}
					}
				}
				
				##echo "<br> photo : ".$scannedphoto; 
				if($scannedphoto)
				{
					$max_width = "200";
					$max_height = "200";
					##echo "<br> scannedphoto : ".$member_data[0]['scannedphoto'];
					##echo "<br> image_path : ".$member_data[0]['image_path'];
					
					$directory = './uploads/photograph';
					if(is_file("./uploads/photograph/".$member_data[0]['scannedphoto']))
					{
						$image = "./uploads/photograph/".$member_data[0]['scannedphoto'];
						$imgdata = $this->resize_image_max($image,$max_width,$max_height);
						imagejpeg($imgdata, $directory."/".$member_data[0]['scannedphoto']);
						$photo_to_add = $directory."/".$member_data[0]['scannedphoto'];
					}
					elseif($member_data[0]['image_path'] != '')
					{
						$image = $phtofile;
						$imgdata = $this->resize_image_max($image,$max_width,$max_height);
						imagejpeg($imgdata, $directory."/".$scannedphoto);
						$photo_to_add = $directory."/".$scannedphoto;
					}
					else if($member_data[0]['reg_no'] != '' || $member_data[0]['regnumber'] != '')
					{
						if($member_data[0]['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"))
						{
							$image = "./uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/k_p_".$member_data[0]['reg_no'].".jpg");
							$photo_to_add = $directory."/k_p_".$member_data[0]['reg_no'].".jpg";
						}
						else if($member_data[0]['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$member_data[0]['reg_no'].".JPG"))
						{
							$image = "./uploads/photograph/k_p_".$member_data[0]['reg_no'].".JPG";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/k_p_".$member_data[0]['reg_no'].".JPG");
							$photo_to_add = $directory."/k_p_".$member_data[0]['reg_no'].".JPG";
						}
						else if($member_data[0]['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"))
						{
							$image = "./uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/k_p_".$member_data[0]['regnumber'].".jpg");
							$photo_to_add = $directory."/k_p_".$member_data[0]['regnumber'].".jpg";
						}
						else if($member_data[0]['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$member_data[0]['regnumber'].".JPG"))
						{
							$image = "./uploads/photograph/k_p_".$member_data[0]['regnumber'].".JPG";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/k_p_".$member_data[0]['regnumber'].".JPG");
							$photo_to_add = $directory."/k_p_".$member_data[0]['regnumber'].".JPG";
						}
					}
					else
					{
						$image = "./uploads/photograph/".$member_data[0]['scannedphoto'];
						$imgdata = $this->resize_image_max($image,$max_width,$max_height);
						imagejpeg($imgdata, $directory."/".$member_data[0]['scannedphoto']);
						$photo_to_add = $directory."/".$member_data[0]['scannedphoto'];
					}
					$new_photo = str_replace("k_","",substr($photo_to_add,strrpos($photo_to_add,'/') + 1));
					
					##echo "<br> photo_to_add : ".$photo_to_add; 
					##echo "<br> new_photo : ".$new_photo; //exit;
					
				}

				

				if ($member_data[0]['declaration'] != "" && file_exists(FCPATH."uploads/declaration/".$member_data[0]['declaration'])) //Check declaration in regular folder
				{ 
					$declaration = "uploads/declaration/".$member_data[0]['declaration']; 
				}
				else if($db_img_path != "") //Check declaration in old image path
				{ 
					if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."declaration/declaration_".$member_data[0]['reg_no'].".jpg"))
					{
						$declaration = "uploads".$db_img_path."declaration/declaration_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."declaration/declaration_".$member_data[0]['regnumber'].".jpg"))
					{
						$declaration = "uploads".$db_img_path."declaration/declaration_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
				elseif($member_data[0]['reg_no'] != "") //Check declaration in kyc folder
				{
					if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/declaration/k_declaration_".$member_data[0]['reg_no'].".jpg"))
					{
						$declaration = "uploads/declaration/k_declaration_".$member_data[0]['reg_no'].".jpg"; 
					}
					else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/declaration/k_declaration_".$member_data[0]['regnumber'].".jpg"))
					{
						$declaration = "uploads/declaration/k_declaration_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
				else  //Check Declaration in logs          
				{
					if($member_data[0]['registrationtype']=='NM')
					{
						$this->db->where('title','Non-Member user registration');
					}
					else if($member_data[0]['registrationtype']=='DB')
					{
						$this->db->where('title','DBF user registration');
					}
					else if($member_data[0]['registrationtype']=='O')
					{
						$this->db->where('title','Member user registration');
					}
					$this->db->like('description', $member_data[0]['email']);
					$member_log = $this->master_model->getRecords('userlogs');
					
					$chk_declaration_flag = 0;
					if(count($member_log) > 0)
					{
						$user_details = unserialize($member_log[0]['description']);
						if(file_exists(FCPATH."uploads/declaration/".$user_details['declaration']))
						{
							$declaration = "uploads/declaration/".$user_details['declaration'];
							$chk_declaration_flag = 1;
						}
					}
					
					if($chk_declaration_flag == 0)
					{
						if($member_data[0]['registrationtype']=='NM')
						{
							$this->db->where('title','Non-Member Traning user registration');
						}
						$this->db->like('description', $member_data[0]['email']);
						$member_log = $this->master_model->getRecords('userlogs');
						if(count($member_log) > 0)
						{
							$user_details = unserialize($member_log[0]['description']);
							if(file_exists(FCPATH."uploads/declaration/".$user_details['declaration']))
							{
								$declaration = "uploads/declaration/".$user_details['declaration'];
							}
						}
					}
				}
			}
      
      $data['member_data'] = $member_data;
      $data['scannedphoto'] = $scannedphoto;
      $data['idproofphoto'] = $idproofphoto;
      $data['declarationphoto'] = $declaration;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto;
      return $data;
		}
    
    public function download($member_no=0)
    {
			$this->load->model('Image_search_model');
      $this->load->library('zip');      
      //$member_response = $this->get_member_data($member_no); 
			$member_response = $this->Image_search_model->get_member_data($member_no);
			//echo "<pre>"; print_r($member_response); echo "</pre>";
			
			$scannedphoto = $member_response['scannedphoto'];
			$idproofphoto = $member_response['idproofphoto'];
			$declarationphoto = $member_response['declarationphoto'];
			$scannedsignaturephoto = $member_response['scannedsignaturephoto'];
			$reg_no = $member_response['member_data'][0]['reg_no'];
			$regnumber = $member_response['member_data'][0]['regnumber'];
      
			// Add file
      if($scannedphoto != "") 
			{ 
				$zip_scannedphoto = FCPATH.$scannedphoto;
				
				$exlode_scannedphoto_arr = explode("/",$scannedphoto);
				$current_scanphoto = $exlode_scannedphoto_arr[count($exlode_scannedphoto_arr)-1];
				
				if(strpos($current_scanphoto,"p_".$reg_no) !== false)
				{
					$directory_nm = "./uploads/rename_images/".$regnumber;
					if(!file_exists($directory_nm)) 
					{ 
						mkdir($directory_nm, 0700); 
					}
					
					$current_scanphoto_ext = strtolower(pathinfo($current_scanphoto, PATHINFO_EXTENSION));
					"<br>Old File : ".$file1 = './'.$scannedphoto;    
					"<br>New File : ".$file2 = $directory_nm.'/p_'.$regnumber.'.'.$current_scanphoto_ext; 
					
					if(copy($file1,$file2)) 
					{ 
						'<br>The file was copied successfully'; 
						
						$zip_scannedphoto = FCPATH.$file2;
					}
					else { '<br>An error occurred during copying the file'; }
				}
				
				$this->zip->read_file($zip_scannedphoto); 
			}
			
			if($idproofphoto != "") 
			{ 
				$zip_idproofphoto = FCPATH.$idproofphoto;
				
				$exlode_idproofphoto_arr = explode("/",$idproofphoto);
				$current_idproofphoto = $exlode_idproofphoto_arr[count($exlode_idproofphoto_arr)-1];
				
				if(strpos($current_idproofphoto,"pr_".$reg_no) !== false)
				{
					$directory_nm = "./uploads/rename_images/".$regnumber;
					if(!file_exists($directory_nm)) 
					{ 
						mkdir($directory_nm, 0700); 
					}
					
					$current_idproofphoto_ext = strtolower(pathinfo($current_idproofphoto, PATHINFO_EXTENSION));
					"<br>Old File : ".$file3 = './'.$idproofphoto;    
					"<br>New File : ".$file4 = $directory_nm.'/pr_'.$regnumber.'.'.$current_idproofphoto_ext; 
					
					if(copy($file3,$file4)) 
					{ 
						'<br>The file was copied successfully'; 
						
						$zip_idproofphoto = FCPATH.$file4;
					}
					else { '<br>An error occurred during copying the file'; }
				}
				
				$this->zip->read_file($zip_idproofphoto); 
			}
			
			if($scannedsignaturephoto != "") 
			{ 
				$zip_scannedsignaturephoto = FCPATH.$scannedsignaturephoto;
				
				$exlode_scannedsignaturephoto_arr = explode("/",$scannedsignaturephoto);
				$current_scannedsignaturephoto = $exlode_scannedsignaturephoto_arr[count($exlode_scannedsignaturephoto_arr)-1];
				
				if(strpos($current_scannedsignaturephoto,"s_".$reg_no) !== false)
				{
					$directory_nm = "./uploads/rename_images/".$regnumber;
					if(!file_exists($directory_nm)) 
					{ 
						mkdir($directory_nm, 0700); 
					}
					
					$current_scannedsignaturephoto_ext = strtolower(pathinfo($current_scannedsignaturephoto, PATHINFO_EXTENSION));
					"<br>Old File : ".$file5 = './'.$scannedsignaturephoto;    
					"<br>New File : ".$file6 = $directory_nm.'/s_'.$regnumber.'.'.$current_scannedsignaturephoto_ext; 
					
					if(copy($file5,$file6)) 
					{ 
						'<br>The file was copied successfully'; 
						
						$zip_scannedsignaturephoto = FCPATH.$file6;
					}
					else { '<br>An error occurred during copying the file'; }
				}
				
				$this->zip->read_file($zip_scannedsignaturephoto); 
			}

			if($declarationphoto != "") 
			{ 
				$zip_declarationphoto = FCPATH.$declarationphoto;
				
				$exlode_declarationphoto_arr = explode("/",$declarationphoto);
				$current_declarationphoto = $exlode_declarationphoto_arr[count($exlode_declarationphoto_arr)-1];
				
				if(strpos($current_declarationphoto,"declaration_".$reg_no) !== false)
				{
					$directory_nm = "./uploads/rename_images/".$regnumber;
					if(!file_exists($directory_nm)) 
					{ 
						mkdir($directory_nm, 0700); 
					}
					
					$current_declarationphoto_ext = strtolower(pathinfo($current_declarationphoto, PATHINFO_EXTENSION));
					"<br>Old File : ".$file7 = './'.$declarationphoto;    
					"<br>New File : ".$file8 = $directory_nm.'/declaration_'.$regnumber.'.'.$current_declarationphoto_ext; 
					
					if(copy($file7,$file8)) 
					{ 
						'<br>The file was copied successfully'; 
						
						$zip_declarationphoto = FCPATH.$file8;
					}
					else { '<br>An error occurred during copying the file'; }
				}
				
				$this->zip->read_file($zip_declarationphoto); 
			}
			
      // Download
      $filename = $member_no.".zip";
			unlink($file2);			
			unlink($file4);			
			unlink($file6);			
      $this->zip->download($filename);
		} 
		
		function resize_image_max_BK($image,$max_width,$max_height) 
		{
			ini_set("memory_limit","256M");
			ini_set("gd.jpeg_ignore_warning", 1);
			
			$org_img = $image;
			$image = @ImageCreateFromJpeg($image);
			if (!$image)
			{
				$image= imagecreatefromstring(file_get_contents($org_img));
			}
			
			$w = imagesx($image); //current width
			$h = imagesy($image); //current height
			if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
			
			if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
			
			//try max width first...
			$ratio = $max_width / $w;
			$new_w = $max_width;
			$new_h = $h * $ratio;
			
			//if that didn't work
			if ($new_h > $max_height) {
				$ratio = $max_height / $h;
				$new_h = $max_height;
				$new_w = $w * $ratio;
			}
			
			$new_image = imagecreatetruecolor ($new_w, $new_h);
			imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
			return $new_image;
		}
		
		public function update_image_name($current_img_name='', $new_img_name='')
		{
			$base_url = base_url();
			$current_img_name = str_replace($base_url,'./',$current_img_name);
			$new_img_name = str_replace($base_url,'./',$new_img_name); //exit;
			
			$final_img_name = '';
			
			if($current_img_name != "")
			{
				if(file_exists($current_img_name))
				{
					if($new_img_name != "" && $new_img_name != $current_img_name)
					{
						if(file_exists($new_img_name))
						{
							$final_img_name = $new_img_name;
						}
						else
						{
							@copy($current_img_name,$new_img_name);
							
							if(file_exists($new_img_name))
							{
								$final_img_name = $new_img_name;
							}
							else
							{
								$final_img_name = $current_img_name;
							}
						}
					}
					else
					{
						$final_img_name = $current_img_name;
					}
				}
			}
			
			return str_replace('./',$base_url,$final_img_name);
		}


		public function delete_rename_image_pb()
		{

			// Delete images
			// if(file_exists('./uploads/photograph/p_801912105.jpg'))
			// {
			// 	@unlink('./uploads/photograph/p_801912105.jpg');
			// } 
			// if(file_exists('./uploads/idproof/pr_801912105.jpg'))
			// {
			// 	@unlink('./uploads/idproof/pr_801912105.jpg');
			// } 
			// if(file_exists('./uploads/scansignature/s_801912105.jpg'))
			// {
			// 	@unlink('./uploads/scansignature/s_801912105.jpg');
			// }

			// Rename image
			echo '<pre>';

			
			if(file_exists('./uploads/photograph/p_801912105.JPG'))
			{
				$oldnamephoto = './uploads/photograph/p_801912105.JPG';
				$expected_namephoto= 'uploads/photograph/p_801912105.jpg';
				$chk_response = $this->update_image_name($oldnamephoto,$expected_namephoto);
				print_r($chk_response);

			} 
			if(file_exists('./uploads/idproof/pr_801912105.JPG'))
			{
				$oldnamephoto2 = './uploads/idproof/pr_801912105.JPG';
				$expected_namephoto2= 'uploads/idproof/pr_801912105.jpg';
				$chk_response2 = $this->update_image_name($oldnamephoto2,$expected_namephoto2);
				print_r($chk_response2);

			} 
			if(file_exists('./uploads/scansignature/s_801912105.JPG'))
			{
				$oldnamephoto3 = './uploads/scansignature/s_801912105.JPG';
				$expected_namephoto3= 'uploads/scansignature/s_801912105.jpg';
				$chk_response3 = $this->update_image_name($oldnamephoto3,$expected_namephoto3);
				print_r($chk_response3);

			}


		}
		
	}
	
