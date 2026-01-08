<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rename_code extends CI_Controller {

			
	public function __construct(){
		parent::__construct();
	/*	if($this->session->userdata('kyc_id') == ""){
			redirect('admin/kyc/Login');
		}		*/
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');

		$this->load->model('KYC_Log_model'); 
	

	}
public function index()
{

$regnumber ='';
	
	if (isset($regnumber))
	 {
		// photo
			 /*  $updatedata['scannedphoto']='';
			
					 $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));	
					$oldfilepath= get_img_name($regnumber,'p');
					$file_path   = implode('/', explode('/', $oldfilepath, -1));
					$photo_file = 'k_p_'.$userdata[0]['reg_no'].'.jpg';
			    	if(@ rename($oldfilepath,$file_path.'/'.$photo_file)===false) 
					{ 
						echo 'not get rename';
						 echo "<br>";
						echo $regnumber;
				
					}else
					{
						echo 'get rename';
						echo "<br>";
						echo $regnumber;
						echo "<br>";
						echo $oldfilepath ;
					}*/
					
				//	signature
					
					
						/* $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));	
						$updatedata['scannedsignaturephoto']='';  
						//$userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber),'reg_no');	
						
						$oldfilepath= get_img_name($regnumber,'s');
						$file_path   = implode('/', explode('/', $oldfilepath, -1));
						$photo_file = 'k_s_'.$userdata[0]['reg_no'].'.jpg';
						if(@ rename($oldfilepath,$file_path.'/'.$photo_file)==false)
						{ 
							echo ' signature not get rename';
							echo "<br>";
							echo $regnumber; 
							
						}else
						{
							echo 'signature get rename';
							echo "<br>";
							echo $regnumber;
							echo "<br>";
							echo $oldfilepath ;
						}*/
					   
					   //ID - proof
					    
					/*	
						 $updatedata['idproofphoto']='';  
					 $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));	
						$oldfilepath= get_img_name($regnumber,'pr');
						
						$file_path   = implode('/', explode('/', $oldfilepath, -1));
						$photo_file = 'k_pr_'.$userdata[0]['reg_no'].'.jpg';
						if(@ rename($oldfilepath,$file_path.'/'.$photo_file)==false)
						{ 
							echo ' id-proof not get rename';
							echo "<br>";
							echo $regnumber;
							
						}else
						{
							echo 'id-proof get rename';
							echo "<br>";
							echo $regnumber;
							echo "<br>";
							echo $oldfilepath ;
						}
					*/
					   
					   //$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
		   	 
			  									//  $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
		   } 
}

}



