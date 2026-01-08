<?php
  /********************************************************************
  * Description: This controller is used to upload the missing images of DRA candidates in Admin panel. There is no navigation provided in Admin panel to access this link. Admin can access this link by putting the url in browser after login. If any image is missing/invalid for DRA member, then only candidate will appear in the list with EDIT option. In Edit option, Admin can only update the missing images data. 
  * Created : Sagar Matale on 31-10-2023
  * Update : Sagar Matale
	********************************************************************/

	defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");

	class Candidate_list_missing_images_for_admin extends CI_Controller 
  {
		public function __construct() 
    {
			parent::__construct();
			if (!$this->session->userdata('dra_admin')) 
      {
        redirect('iibfdra/admin/Login');
      }

			$this->load->library('upload');	
			$this->load->library('session');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->model('UserModel');
			$this->load->model('master_model');	
			$this->load->helper('pagination_helper');
			$this->load->library('pagination');	
			$this->load->model('log_model');
			$this->load->helper('general_helper');
			$this->load->helper('dra_seatallocation_helper');			
		}


    //get all candidates against batch
		public function index()
		{			
      $regnumber_arr = array(801360241,801024161,801076732,801118446,801123583,801150905,801152349,801175386,801178363,801189723,801198088,801241937,801262214,801262217,801264078);
			
      $this->db->where_in('dm.regnumber',$regnumber_arr);
      $this->db->join('agency_batch ab','ab.id = dm.batch_id','LEFT');
      $data['candidate_data']	= $candidate_data = $this->master_model->getRecords("dra_members dm",array(),"dm.regid, dm.registration_no, dm.batch_id, ab.batch_code, ab.batch_name, ab.batch_from_date, ab.batch_to_date, dm.regnumber, dm.namesub, dm.firstname, dm.middlename, dm.lastname, dm.contactdetails, dm.address1, dm.address2, dm.address3, dm.address4, dm.district, dm.city, dm.state, dm.pincode, dm.dateofbirth, dm.gender, dm.qualification, dm.inst_code, dm.email_id, dm.qualification_type, dm.mobile_no, dm.aadhar_no, dm.scannedphoto, dm.scannedsignaturephoto, dm.idproofphoto, dm.quali_certificate, dm.createdon");
            	    
			$this->load->view('iibfdra/candidate_list_missing_images_for_admin_custom',$data);
		}
		
    public function viewApplicant()
    {
			$data = array();
			$data['examRes'] = array();
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);
			//check if id is integer in url if not regdirect to home
			if(!intval($id)) 
      {
				echo 'No such applicant exists';
				exit;
			}

			$id = intval($id);
			$this->db->select('agency_batch.*,dra_members.*,agency_center.center_id,agency_center.location_name,city_master.city_name,state_master.state_name');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
			$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
			$this->db->join('state_master','state_master.state_code=dra_members.state','LEFT');
			$examRes = $this->master_model->getRecords('dra_members',array('regid'=>$id,'isdeleted' => 0));
			//print_r( $this->db->last_query() ); die();
			//print_r($examRes); die;
			if(count($examRes))
			{
				//print_r($examRes[0]); die;
				$data['examRes'] = $examRes[0];
      } 
      else
      { //check entered id details are present in db if not redirect to home
				echo 'No such applicant exists';
				exit;
			}
			
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			
			//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
			$this->db->not_like('name','Election Voters card');
			$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
			
			
			$data['states'] = $states;
			$data['idtype_master'] = $idtype_master;			
			
									
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/dracandidate_view_missing_images_for_admin_custom',$data);
		}
		
		// edit candidate details
		public function editCandidate()
		{
			$data = array();
			$data['examRes'] = array();
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);
			// $decdexamcode =$_SESSION['excode'];
			
			//check if id is integer in url if not regdirect to home
			if(!intval($id)) 
      {
				echo 'No such applicant exists';
				exit;
			}

			$id = intval($id);
			// $examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_period');
			$this->db->select('agency_batch.*,dra_members.*,agency_center.center_id,agency_center.location_name,city_master.*,dra_member_exam.exam_medium,dra_member_exam.exam_center_code');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
			$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
			$this->db->join('dra_member_exam','dra_member_exam.regid=dra_members.regid','LEFT');
			$examRes = $this->master_model->getRecords('dra_members',array('dra_members.regid'=>$id,'dra_members.isdeleted' => 0));
			//print_r( $this->db->last_query() ); die();
			//print_r($examRes); die;
			if(count($examRes))
			{
				//print_r($examRes[0]); die;
				$data['examRes'] = $examRes[0];				
      } 
      else 
      { //check entered id details are present in db if not redirect to home
				echo 'No such applicant exists';
				exit;
			}

			if(isset($_POST['btnSubmit']))
			{				
				$this->form_validation->set_rules('edit_candidate_flag_custom','','trim|required');				
				
				if($this->form_validation->run()==TRUE)
				{
					$photofnm = $signfnm = $idfnm = $qualifnm = '';
					$photo_flg = $signature_flg = $id_flg = $tcertificate_flg = $qualicertificate_flg = 'N';
					
					$date = date('Y-m-d h:i:s');
					
					$image_size_error = 0;
					$image_size_error_message = array();
					
					//if( !empty($input) ) {
					//if($this->input->post('hiddenphoto') != '')
          $image_error_msg = '';
          if($_FILES['drascannedphoto']['name'] != "")
					{
						$size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
						if($size)
						{
							/*$input = $this->input->post('hiddenphoto');
							
							$tmp_nm = strtotime($date).rand(0,100);
							$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
							$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
							file_put_contents($outputphoto, file_get_contents($input));
							$photofnm = "p_".$tmp_nm.".jpg";
							$photo_flg = 'Y'; */

              if($examRes[0]['regnumber'] != '') { $tmp_nm = $examRes[0]['regnumber']; }
              else { $tmp_nm = strtotime($date).rand(0,100); }
              
              $flag1 = 0;
              $path_img1 = $_FILES['drascannedphoto']['name']; 
                        
              $ext_img1 = strtolower(pathinfo($path_img1, PATHINFO_EXTENSION));
              $valid_ext_arr1 = array('jpg','jpeg');
              $allowed_types1 = 'jpg|jpeg';
              
              if(!in_array(strtolower($ext_img1),$valid_ext_arr1)) { $flag1=1; }
              
              if($flag1 == 0)
              {
                $upload_path1 = 'uploads/iibfdra';            
                                
                $file=$_FILES;	
                $_FILES['file_upload']['name'] = $file['drascannedphoto']['name'];
                $final_img1 = "p_".$tmp_nm.".".$ext_img1;
                
                $config1['file_name']     = $final_img1;
                $config1['upload_path']   = $upload_path1;
                $config1['allowed_types'] = $allowed_types1;
                $config1['overwrite'] = true;
                
                $this->upload->initialize($config1);					
                
                $_FILES['file_upload']['type']=$file['drascannedphoto']['type'];
                $_FILES['file_upload']['tmp_name']=$file['drascannedphoto']['tmp_name'];
                $_FILES['file_upload']['error']=$file['drascannedphoto']['error'];
                $_FILES['file_upload']['size']=$file['drascannedphoto']['size'];            
                
                if($this->upload->do_upload('file_upload'))
                {
                  $data=$this->upload->data();

                  $photofnm = $final_img1;
                  $photo_flg = 'Y';
                }
                else
                {
                  //$this->session->set_flashdata('error','Selected file is corrupted. Please upload valid file.');
                  $image_error_msg .="Selected file is corrupted. Please upload valid file for Photograph.<br>";
                }
              }
              else
              {
                //$this->session->set_flashdata('error',"Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image.");
                $image_error_msg .="Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image for Photograph.<br>";
              }
						}
						else
						{
							$photofnm = $this->input->post('hiddenphoto');
						}
					}
          
          //if( !empty($inputsignature) ) {
					//if($this->input->post('hiddenscansignature') != '')
          if($_FILES['drascannedsignature']['name'] != "")
					{
						$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
						if($size)
						{
							/* $inputsignature = $_POST["hiddenscansignature"];
							
							$tmp_signnm = strtotime($date).rand(0,100);
							$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
							$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
							file_put_contents($outputsign, file_get_contents($inputsignature));
							$signfnm = "s_".$tmp_signnm.".jpg";
							$signature_flg = 'Y'; */

              if($examRes[0]['regnumber'] != '') { $tmp_signnm = $examRes[0]['regnumber']; }
              else { $tmp_signnm = strtotime($date).rand(0,100); }
              
              $flag2 = 0;
              $path_img2 = $_FILES['drascannedsignature']['name']; 
                        
              $ext_img2 = strtolower(pathinfo($path_img2, PATHINFO_EXTENSION));
              $valid_ext_arr2 = array('jpg','jpeg');
              $allowed_types2 = 'jpg|jpeg';
              
              if(!in_array(strtolower($ext_img2),$valid_ext_arr2)) { $flag2=1; }
              
              if($flag2 == 0)
              {
                $upload_path2 = 'uploads/iibfdra';            
                                
                $file=$_FILES;	
                $_FILES['file_upload']['name'] = $file['drascannedsignature']['name'];
                $final_img2 = "s_".$tmp_signnm.".".$ext_img2;
                
                $config2['file_name']     = $final_img2;
                $config2['upload_path']   = $upload_path2;
                $config2['allowed_types'] = $allowed_types2;
                $config2['overwrite'] = true;
                
                $this->upload->initialize($config2);					
                
                $_FILES['file_upload']['type']=$file['drascannedsignature']['type'];
                $_FILES['file_upload']['tmp_name']=$file['drascannedsignature']['tmp_name'];
                $_FILES['file_upload']['error']=$file['drascannedsignature']['error'];
                $_FILES['file_upload']['size']=$file['drascannedsignature']['size'];            
                
                if($this->upload->do_upload('file_upload'))
                {
                  $data=$this->upload->data();

                  $signfnm = $final_img2;
							    $signature_flg = 'Y';
                }
                else
                {
                  //$this->session->set_flashdata('error','Selected file is corrupted. Please upload valid file.');
                  $image_error_msg .="Selected file is corrupted. Please upload valid file for Signature.<br>";
                }
              }
              else
              {
                //$this->session->set_flashdata('error',"Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image.");
                $image_error_msg .="Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image for Signature.<br>";
              }
						}
						else
						{
							$signfnm = $this->input->post('hiddenscansignature');
						}
					}
					
					//if( !empty($inputidproofphoto) ) {
					//if($this->input->post('hiddenidproofphoto') != '')
          if($_FILES['draidproofphoto']['name'] != "")
					{
						$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
						if($size)
						{
							/*$inputidproofphoto = $_POST["hiddenidproofphoto"];
							
							$tmp_inputidproof = strtotime($date).rand(0,100);
							$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
							$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
							file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
							$idfnm = "pr_".$tmp_inputidproof.".jpg";
							$id_flg = 'Y';*/

              if($examRes[0]['regnumber'] != '') { $tmp_inputidproof = $examRes[0]['regnumber']; }
              else { $tmp_inputidproof = strtotime($date).rand(0,100); }
              
              $flag3 = 0;
              $path_img3 = $_FILES['draidproofphoto']['name']; 
                        
              $ext_img3 = strtolower(pathinfo($path_img3, PATHINFO_EXTENSION));
              $valid_ext_arr3 = array('jpg','jpeg');
              $allowed_types3 = 'jpg|jpeg';
              
              if(!in_array(strtolower($ext_img3),$valid_ext_arr3)) { $flag3=1; }
              
              if($flag3 == 0)
              {
                $upload_path3 = 'uploads/iibfdra';            
                                
                $file=$_FILES;	
                $_FILES['file_upload']['name'] = $file['draidproofphoto']['name'];
                $final_img3 = "pr_".$tmp_inputidproof.".".$ext_img3;
                
                $config3['file_name']     = $final_img3;
                $config3['upload_path']   = $upload_path3;
                $config3['allowed_types'] = $allowed_types3;
                $config3['overwrite'] = true;
                
                $this->upload->initialize($config3);					
                
                $_FILES['file_upload']['type']=$file['draidproofphoto']['type'];
                $_FILES['file_upload']['tmp_name']=$file['draidproofphoto']['tmp_name'];
                $_FILES['file_upload']['error']=$file['draidproofphoto']['error'];
                $_FILES['file_upload']['size']=$file['draidproofphoto']['size'];            
                
                if($this->upload->do_upload('file_upload'))
                {
                  $data=$this->upload->data();
                  
                  $idfnm = $final_img3;
							    $id_flg = 'Y';
                }
                else
                {
                  //$this->session->set_flashdata('error','Selected file is corrupted. Please upload valid file.');
                  $image_error_msg .="Selected file is corrupted. Please upload valid file for Proof of Identity.<br>";
                }
              }
              else
              {
                //$this->session->set_flashdata('error',"Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image.");
                $image_error_msg .="Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image for Proof of Identity.<br>";
              }
						}
						else
						{
							$idfnm = $this->input->post('hiddenidproofphoto');
						}
					}
          				
					//if( !empty($input_qualicertificate) ) {
					//if($this->input->post('hiddenqualicertificate') != '')
          if($_FILES['qualicertificate']['name'] != "")
					{
						$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
						if($size)
						{
							/*$input_qualicertificate = $_POST["hiddenqualicertificate"];
							
							$tmp_qualicertificate = strtotime($date).rand(0,100);
							$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
							$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
							file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
							$qualifnm = "degre_".$tmp_qualicertificate.".jpg";
							$qualicertificate_flg = 'Y';*/

              if($examRes[0]['regnumber'] != '') { $tmp_qualicertificate = $examRes[0]['regnumber']; }
              else { $tmp_qualicertificate = strtotime($date).rand(0,100); }
              
              $flag4 = 0;
              $path_img4 = $_FILES['qualicertificate']['name']; 
                        
              $ext_img4 = strtolower(pathinfo($path_img4, PATHINFO_EXTENSION));
              $valid_ext_arr4 = array('jpg','jpeg');
              $allowed_types4 = 'jpg|jpeg';
              
              if(!in_array(strtolower($ext_img4),$valid_ext_arr4)) { $flag4=1; }
              
              if($flag4 == 0)
              {
                $upload_path4 = 'uploads/iibfdra';            
                                
                $file=$_FILES;	
                $_FILES['file_upload']['name'] = $file['qualicertificate']['name'];
                $final_img4 = "degre_".$tmp_qualicertificate.".".$ext_img4;
                
                $config4['file_name']     = $final_img4;
                $config4['upload_path']   = $upload_path4;
                $config4['allowed_types'] = $allowed_types4;
                $config4['overwrite'] = true;
                
                $this->upload->initialize($config4);					
                
                $_FILES['file_upload']['type']=$file['qualicertificate']['type'];
                $_FILES['file_upload']['tmp_name']=$file['qualicertificate']['tmp_name'];
                $_FILES['file_upload']['error']=$file['qualicertificate']['error'];
                $_FILES['file_upload']['size']=$file['qualicertificate']['size'];            
                
                if($this->upload->do_upload('file_upload'))
                {
                  $data=$this->upload->data();
                  
                  $qualifnm = $final_img4;
							    $qualicertificate_flg = 'Y';
                }
                else
                {
                  //$this->session->set_flashdata('error','Selected file is corrupted. Please upload valid file.');
                  $image_error_msg .="Selected file is corrupted. Please upload valid file for Qualification Certificate.<br>";
                }
              }
              else
              {
                //$this->session->set_flashdata('error',"Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image.");
                $image_error_msg .="Please upload valid ".str_replace('|',' | ',$allowed_types1)." extension image for Qualification Certificate.<br>";
              }
						}
						else
						{
							$qualifnm =$this->input->post('hiddenqualicertificate'); 	
						}
					}
					// eof file upload code

          //echo $image_error_msg; exit;
          $regid = $examRes[0]['regid'];
          if($image_error_msg != '')
          {
            $this->session->set_flashdata('error',$image_error_msg);
            redirect(base_url().'iibfdra/candidate_list_missing_images_for_admin/editCandidate/'.$regid);
          }
          else
          {					
            $update_data = array();
            if($photofnm != '')
            {
              $update_data['scannedphoto'] = $photofnm;
              $update_data['photo_flg'] = $photo_flg;
            }

            if($signfnm != '')
            {
              $update_data['scannedsignaturephoto'] = $signfnm;
              $update_data['signature_flg'] = $signature_flg;
            }

            if($idfnm != '')
            {
              $update_data['idproofphoto'] = $idfnm;
              $update_data['id_flg'] = $id_flg;				
            }

            if($qualifnm != '')
            {
              $update_data['quali_certificate'] = $qualifnm;
              $update_data['qualicertificate_flg'] = $qualicertificate_flg;
            }
            //print_r($update_data);
            
            if(count($update_data) > 0)
            {
              $UserData = $this->session->userdata('dra_admin');
              
              $update_data['editedby'] = date("Y-m-d H:i:s");
              $update_data['editedon'] = date("Y-m-d H:i:s");
              $update_data['edited_by_id'] = $UserData['id'];
              if($this->master_model->updateRecord('dra_members',$update_data,  array('regid'=>$regid)))
              {
                $desc['updated_data'] = $update_data;
                $desc['old_data'] = $examRes[0];
                log_dra_user($log_title = "custom : Edit Applicant Successful by admin", $log_message = serialize($desc));
                $this->session->set_flashdata('success','Record updated successfully');
                redirect(base_url().'iibfdra/candidate_list_missing_images_for_admin/editCandidate/'.$regid);
              }
              else
              {
                $desc['updated_data'] = $update_data;
                $desc['old_data'] = $examRes[0];
                log_dra_user($log_title = "custom : Edit Applicant Unsuccessful by admin", $log_message = serialize($desc));
                  //echo validation_errors();die;
                $this->session->set_flashdata('error','Error occured while updating record');
                redirect(base_url().'iibfdra/candidate_list_missing_images_for_admin/editCandidate/'.$regid);
              }
            }
          }
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}
			}
			
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			$this->db->where('city_master.city_delete', '0');
			$cities = $this->master_model->getRecords('city_master'); 
			
			//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
			$this->db->not_like('name','Election Voters card');
			$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));			
			
			$data['states'] = $states;
			$data['cities'] = $cities;
			$data['idtype_master'] = $idtype_master;			
			
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/dracandidate_update_missing_images_for_admin_custom',$data);
		}	


    //BELOW FUNCTIONS ARE THE CUSTOM FUNCTION FOR TESTING PURPOSE
    function candidate_corrupted_images($start='1')
    {
      $this->db->join('agency_batch ab','ab.id = dm.batch_id','LEFT');
      //$this->db->where("(dm.scannedphoto = '' OR dm.scannedsignaturephoto = '' OR dm.idproofphoto = '' OR dm.quali_certificate = '')");
      $this->db->order_by('dm.regid','ASC');
      $this->db->limit(10000,$start);
      $candidate_data = $this->master_model->getRecords("dra_members dm",array('DATE(dm.createdon) >='=>'2023-01-01', 'ab.batch_code NOT LIKE'=>'BC%'),"dm.regid, dm.registration_no, dm.batch_id, ab.batch_code, ab.batch_name, ab.batch_from_date, ab.batch_to_date, dm.regnumber, dm.namesub, dm.firstname, dm.middlename, dm.lastname, dm.contactdetails, dm.address1, dm.address2, dm.address3, dm.address4, dm.district, dm.city, dm.state, dm.pincode, dm.dateofbirth, dm.gender, dm.qualification, dm.inst_code, dm.email_id, dm.qualification_type, dm.mobile_no, dm.aadhar_no, dm.scannedphoto, dm.scannedsignaturephoto, dm.idproofphoto, dm.quali_certificate, dm.createdon");
      echo $this->db->last_query().'<br>'; //exit;
      
      
      $success_cnt = $error_cnt = $img_cnt = 0;
      if(count($candidate_data) > 0)
      {
        foreach($candidate_data as $res)
        {
          $img_arr = array($res['scannedphoto'], $res['scannedsignaturephoto'], $res['idproofphoto'], $res['quali_certificate']);
          foreach($img_arr as $res2)
          {
            $img_cnt++;
            $imagePath = './uploads/iibfdra/'.$res2;

            if (file_exists($imagePath)) 
            {
              //echo "file exist : ".$imagePath."<br>";
              $imageInfo = getimagesize($imagePath);
                
              if ($imageInfo !== false) 
              {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                $mime = $imageInfo['mime'];

                //echo "Image Width: $width<br>";
                //echo "Image Height: $height<br>";
                //echo "MIME Type: $mime<br>";

                if($mime == 'image/jpeg' || $mime == 'image/jpg' || $mime == 'image/png' || $mime == 'image/gif') { $success_cnt++; }
                else
                {
                  echo "<br>Invalid image file1 : ".$res['regid'].' - <a href="'.base_url($imagePath).'" target="_blank">'.$imagePath.'</a> - '.$mime;
                  $error_cnt++;
                }
              } 
              else 
              {
                echo "<br>Invalid image file2 : ".$res['regid'].' - <a href="'.base_url($imagePath).'" target="_blank">'.$imagePath.'</a>';
                $error_cnt++;
              }
            } 
            else 
            {
              echo "<br>Image file does not exist : ".$res['regid'].' - <a href="'.base_url($imagePath).'" target="_blank">'.$imagePath.'</a>';
              $error_cnt++;
            }
          }
        }
      }

      echo '<br><br>Record Count : '.count($candidate_data).' ('.$candidate_data[0]['regid'].' To '.$candidate_data[(count($candidate_data) - 1)]['regid'].')';
      echo '<br>img_cnt : '.$img_cnt;
      echo '<br>success_cnt : '.$success_cnt;
      echo '<br>error_cnt : '.$error_cnt;
    }
    
    function test_chk_img()
    {
      $imagePath = './uploads/iibfdra/p_16941173074.jpg';

      if (file_exists($imagePath)) 
      {
        echo "file exist : ".$imagePath."<br>";
          $imageInfo = getimagesize($imagePath);
          
          if ($imageInfo !== false) {
              $width = $imageInfo[0];
              $height = $imageInfo[1];
              $mime = $imageInfo['mime'];

              echo "Image Width: $width<br>";
              echo "Image Height: $height<br>";
              echo "MIME Type: $mime<br>";
          } else {
              echo "Invalid image file.";
          }
      } else 
      {
         echo "<br>file exist : ".$imagePath; 
          echo "<br>Image file does not exist.";
      }
    }

    function get_regnumber_from_custom_log()
    {
      $res_data = $this->master_model->getRecords("dra_userlogs dm",array('title'=>'custom : Edit Applicant Successful'));
      if(count($res_data) > 0)
      {
        foreach($res_data as $res)
        {
          
          $unserialize_data = unserialize($res['description']);

          echo '<br> >> : '.$res['date'].' >> '.$unserialize_data['old_data']['regnumber'];

          //echo '<pre>'; print_r($unserialize_data); echo '</pre>';
        }
      }      
    }
	} ?>