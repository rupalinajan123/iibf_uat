<?php if( !defined('BASEPATH')) exit('No direct script access alloed');
	
	//THIS MODEL IS CREATED BY SAGAR ON 05-02-2021 TO FIND MISSING IMAGES FROM DIFFERENT LOCATIONS
	class Image_search_model extends CI_Model
	{
		public function get_member_data($member_no=0)
    	{
      $select = "regid, reg_no, regnumber, namesub, firstname, middlename, lastname, email, mobile, scannedphoto, scannedsignaturephoto, idproofphoto,declaration, aadhar_card, id_proof_flag, kyc_status, kyc_edit, isactive, isdeleted, image_path,registrationtype";
      //$whr_con['regnumber'] = $member_no;
			$this->db->where('(regnumber = "'.$member_no.'" OR email = "'.$member_no.'")');
      $whr_con['isactive'] = '1';
      $whr_con['isdeleted'] = '0';
      $member_data = $this->master_model->getRecords('member_registration',$whr_con,$select,array(),'',1);
      $scannedphoto = $idproofphoto  = $declaration= $idproofphoto = $scannedsignaturephoto = '';  
      
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
						else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpeg'))
						{
							$final_photo_img = $chk_photo_img.'.jpeg';
						}
					}
				}			
				
				if($final_photo_img == "")
				{
					if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpg'))
					{
						$final_photo_img = "p_".$member_no.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpeg'))
					{
						$final_photo_img = "p_".$member_no.'.jpeg';
					}
				}
				
        if($final_photo_img != "") //Check photo in regular folder
        { 
          $scannedphoto = "uploads/photograph/".$final_photo_img; 
				}
        
				if($db_img_path != "" && $scannedphoto == "") //Check photo in old image path
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
        
				if($member_data[0]['reg_no'] != '' && $scannedphoto == "") //Check photo in kyc folder      
        {
          if($member_data[0]['reg_no'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['reg_no'].".jpg"; 
					}
				}
				
				if($member_data[0]['regnumber'] != '' && $scannedphoto == "") //Check photo in kyc folder      
        {
          if($member_data[0]['regnumber'] != '' && file_exists(FCPATH."uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedphoto = "uploads/photograph/k_p_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
				
				if($scannedphoto == "")//Check photo in logs          
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
				
				//echo '<pre>'; print_r($member_data); echo '</pre>';
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
						else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpeg'))
						{
							$final_idproofphoto_img = $chk_idproofphoto_img.'.jpeg';
						}
					}
				} 
				
				if($final_idproofphoto_img == "")
				{
					if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpg'))
					{
						$final_idproofphoto_img = "pr_".$member_no.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpeg'))
					{
						$final_idproofphoto_img = "pr_".$member_no.'.jpeg';
					}
				}
				
				//echo '<br>final_idproofphoto_img : '.$final_idproofphoto_img;
				
				if($final_idproofphoto_img != "") //Check id proof in regular folder
        { 
					$idproofphoto = "uploads/idproof/".$final_idproofphoto_img; 
				}
        
				if($db_img_path != "" && $idproofphoto == "") //Check id proof in old image path
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
        
				if($member_data[0]['reg_no'] != "" && $idproofphoto == "") //Check Idproof in kyc folder
        {
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['reg_no'].".jpg"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['reg_no'].".jpg"; 
					}
				}
				
				if($member_data[0]['regnumber'] != "" && $idproofphoto == "") //Check Idproof in kyc folder
        {
          if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$member_data[0]['regnumber'].".jpg"))
          {
            $idproofphoto = "uploads/idproof/k_pr_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
				
				if($idproofphoto == "")//Check Idproof in logs          
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
						else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpeg'))
						{
							$final_scanphoto_img = $chk_scanphoto_img.'.jpeg';
						}
					}
				}
				
				if($final_scanphoto_img == "")
				{
					if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpg'))
					{
						$final_scanphoto_img = "s_".$member_no.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpeg'))
					{
						$final_scanphoto_img = "s_".$member_no.'.jpeg';
					}
				}
				
        if ($final_scanphoto_img != "") //Check signature in regular folder
        { 
          $scannedsignaturephoto = "uploads/scansignature/".$final_scanphoto_img; 
				}
        
				if($db_img_path != "" && $scannedsignaturephoto == "") //Check signature in old image path
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
        
				if($member_data[0]['reg_no'] != "" && $scannedsignaturephoto == "") //Check signature in kyc folder
        {
          if($member_data[0]['reg_no'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['reg_no'].".jpg"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['reg_no'].".jpg"; 
					}
				}
				
				if($member_data[0]['regnumber'] != "" && $scannedsignaturephoto == "") //Check signature in kyc folder
        {
          if($member_data[0]['regnumber'] != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$member_data[0]['regnumber'].".jpg"))
          {
            $scannedsignaturephoto = "uploads/scansignature/k_s_".$member_data[0]['regnumber'].".jpg"; 
					}
				}
				
				if($scannedsignaturephoto == "")//Check signature in logs          
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
					
					$photo_to_add = '';
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
						if(file_exists($directory."/".$scannedphoto))
						{
							imagejpeg($imgdata, $directory."/".$scannedphoto);
							$photo_to_add = $directory."/".$scannedphoto;
						}
						else if(file_exists("./".$scannedphoto))
						{
							imagejpeg($imgdata, "./".$scannedphoto);
							$photo_to_add = "./".$scannedphoto;
						}
						else
						{
							$photo_to_add = "";
						}
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

				/* This code added by pratibha borse on 5 April 22*/
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
	
    
	} ?>