<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  header("Access-Control-Allow-Origin: *");
  
  class Member_details_search_sm extends CI_Controller
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
		}
    
    public function index()
    {
      $msg = $member_no = $member_response['member_data'] = $member_response['scannedphoto'] = $member_response['idproofphoto'] = $member_response['scannedsignaturephoto'] = '';
      $download_btn_flag = 0;
      
      if(isset($_POST) && count($_POST) > 0)
			{
				$this->form_validation->set_rules('member_no', 'Member No', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));		
				if($this->form_validation->run())
				{
          $member_no = $this->input->post('member_no');
          $member_response = $this->get_member_data($member_no);
          
          // If member data not found for posted member number, then display member number validation message
          if(empty($member_response['member_data']) || $member_response['member_data'] == "") 
          { 
            $msg = '<label class="error">Please enter valid member No.</label>'; 
					}
				}
			}
      
      if($member_response['scannedphoto'] != "" || $member_response['idproofphoto'] != "" || $member_response['scannedsignaturephoto'] |= "")
      {
        $download_btn_flag = 1;
			}
      
      $data['member_no'] = $member_no;
      $data['member_data'] = $member_response['member_data'];
      $data['msg'] = $msg;
      $data['scannedphoto'] = $member_response['scannedphoto'];
      $data['idproofphoto'] = $member_response['idproofphoto'];
      $data['scannedsignaturephoto'] = $member_response['scannedsignaturephoto'];
      $data['download_btn_flag'] = $download_btn_flag;
      
      $data['middle_content'] = 'member_search/index';
      $this->load->view('member_search/member_common_view', $data);
		}
    
    public function get_member_data($member_no=0)
    {
      $select = "regid, reg_no, regnumber, namesub, firstname, middlename, lastname, email, mobile, scannedphoto, scannedsignaturephoto, idproofphoto, aadhar_card, id_proof_flag, kyc_status, kyc_edit, isactive, isdeleted, image_path,registrationtype";
      //$whr_con['regnumber'] = $member_no;
			$this->db->where('(regnumber = "'.$member_no.'" OR email = "'.$member_no.'")');
      $whr_con['isactive'] = '1';
      $whr_con['isdeleted'] = '0';
      $member_data = $this->master_model->getRecords('member_registration',$whr_con,$select,array(),'',1);
      $scannedphoto = $idproofphoto = $scannedsignaturephoto = '';  
      
      if(!empty($member_data))
      {
        $db_img_path = $member_data[0]['image_path']; //Get old image path from database
        
        if($member_data[0]['scannedphoto'] != "" && file_exists(FCPATH."uploads/photograph/".$member_data[0]['scannedphoto'])) //Check photo in regular folder
        { 
          $scannedphoto = "uploads/photograph/".$member_data[0]['scannedphoto']; 
				}
        else if($db_img_path != "") //Check photo in old image path
        {
          if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$member_data[0]['reg_no'].".jpg"))
          {
            $phtofile = $scannedphoto = "uploads".$db_img_path."photo/p_".$member_data[0]['reg_no'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$member_data[0]['regnumber'].".jpg"))
          {
            $phtofile = $scannedphoto = "uploads".$db_img_path."photo/p_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
        else if($member_data[0]['reg_no'] != '') //Check photo in kyc folder      
        {
          if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"; 
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
				
        if ($member_data[0]['idproofphoto'] != "" && file_exists(FCPATH."uploads/idproof/".$member_data[0]['idproofphoto'])) //Check id proof in regular folder
        { 
          $idproofphoto = "uploads/idproof/".$member_data[0]['idproofphoto']; 
				}
        else if($db_img_path != "") //Check id proof in old image path
        { 
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$member_data[0]['reg_no'].".jpg"))
          {
            $idproofphoto = "uploads".$db_img_path."idproof/pr_".$member_data[0]['reg_no'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$member_data[0]['regnumber'].".jpg"))
          {
            $idproofphoto = "uploads".$db_img_path."idproof/pr_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
        elseif($member_data[0]['reg_no'] != "") //Check Idproof in kyc folder
        {
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['reg_no'].".jpg"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['reg_no'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['regnumber'].".jpg"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['regnumber'].".jpg"; 
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
        
        if ($member_data[0]['scannedsignaturephoto'] != "" && file_exists(FCPATH."uploads/scansignature/".$member_data[0]['scannedsignaturephoto'])) //Check signature in regular folder
        { 
          $scannedsignaturephoto = "uploads/scansignature/".$member_data[0]['scannedsignaturephoto']; 
				}
        else if($db_img_path != "") //Check signature in old image path
        { 
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedsignaturephoto = "uploads".$db_img_path."signature/s_".$member_data[0]['reg_no'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedsignaturephoto = "uploads".$db_img_path."signature/s_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
        elseif($member_data[0]['reg_no'] != "") //Check signature in kyc folder
        {
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['reg_no'].".jpg"; 
					}
          else if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['regnumber'].".jpg"; 
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
						else if($member_data[0]['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"))
						{
							$image = "./uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/k_p_".$member_data[0]['regnumber'].".jpg");
							$photo_to_add = $directory."/k_p_".$member_data[0]['regnumber'].".jpg";
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
				
			}
      
      $data['member_data'] = $member_data;
      $data['scannedphoto'] = $scannedphoto;
      $data['idproofphoto'] = $idproofphoto;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto;
      return $data;
		}
    
    public function download($member_no=0)
    {
      $this->load->library('zip');      
      $member_response = $this->get_member_data($member_no); 
			//echo "<pre>"; print_r($member_response); echo "</pre>";
			
			$scannedphoto = $member_response['scannedphoto'];
			$idproofphoto = $member_response['idproofphoto'];
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
			
      // Download
      $filename = $member_no.".zip";
			unlink($file2);			
			unlink($file4);			
			unlink($file6);			
      $this->zip->download($filename);
		} 
		
		function resize_image_max($image,$max_width,$max_height) 
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
	}
