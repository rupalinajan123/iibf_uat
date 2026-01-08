<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Requestadmitcard extends CI_Controller {
	private $USERDATA=array();		
	public function __construct(){
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->library('email');
		$this->load->model('Emailsending');
	}
	
	public function index(){
		$var_errors = '';
		$data = array('middle_content' => 'requestadmitcard','var_errors' => $var_errors);
        $this->load->view('renewal_common_view', $data);
	}
	
	public function create_zip(){
		ini_set("memory_limit", "-1"); 
		
		$this->db->where('status',0);
		$this->db->where('mail_send',0);
		$this->db->limit(1);
		$info=$this->master_model->getRecords('request_admitcard');
		
		if(count($info) > 0){
		
		
		$excode = $info[0]['exam_code'];
		$experiod = $info[0]['exam_period'];
		$request_id = $info[0]['id'];
		
		//$current_date = date('Y-m-d');
		$current_date = $info[0]['request_date'];;
		$cron_file_path = "./uploads/zipfile/"; 
		$directory = $cron_file_path.'/';
		$zipname = '_'.$excode.'_'.$experiod.'.zip';
		
		$todaydirectory = $cron_file_path.'/'.$current_date;
		if(file_exists($todaydirectory))
		{
			array_map('unlink', glob($todaydirectory."/*.*"));
			rmdir($todaydirectory);
			$dir_flg = mkdir($todaydirectory, 0700);
		}
		else
		{
			$dir_flg = mkdir($todaydirectory, 0700);
		}
		
		$zip = new ZipArchive;
		$zip->open($todaydirectory.$zipname, ZipArchive::CREATE);
		$this->db->where('admitcard_image !=','');
		$this->db->where('exm_cd',$excode);
		$this->db->where('exm_prd',$experiod);
		$this->db->where('remark',1);
		//$this->db->limit(5,0); 
		$user_info=$this->master_model->getRecords('admit_card_details','','admitcard_image');
		
		foreach($user_info as $result){
			copy("./uploads/admitcardpdf/".$result['admitcard_image'],$todaydirectory."/".$result['admitcard_image']);
			$photo_to_add = $todaydirectory."/".$result['admitcard_image'];
			$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
			$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo); 
		}
		$zip->close();
		
		$update_data = array('status'=>1);
		$this->master_model->updateRecord('request_admitcard',$update_data,array('id'=>$request_id));
		$attachpath = "./uploads/zipfile/".$current_date."_".$excode."_".$experiod.".zip"; 
		
		 
		}
	}
	
	public function send_mail_attach(){
		
		$this->db->where('status',1); 
		$this->db->where('mail_send',0);
		$this->db->limit(1);
		$info=$this->master_model->getRecords('request_admitcard');
		
		$current_date = $info[0]['request_date'];
		//$current_date = date('Y-m-d');
		
		$attachpath = "./uploads/zipfile/".$current_date."_".$info[0]['exam_code']."_".$info[0]['exam_period'].".zip"; 
		$attachpath1 = "./uploads/zipfile/".$info[0]['exam_code']."_".$info[0]['exam_period'].".csv"; 
		  
		//echo $attachpath;
		
		if(count($info) > 0){
		//$attachpath = "./uploads/zipfile/Course List_R&D.xlsx"; 
		
			if(file_exists($attachpath)){ 
				$final_str = 'Hello Sir,';
				$final_str.= '<br/><br/>';
				$final_str.= 'Please Check attach link of zip and CSV file of requested admitcard.';
				$final_str.= '<br/><br/>';
				$final_str.= 'Zip Link : '.base_url().$attachpath;
				$final_str.= '<br/><br/>';
				$final_str.= 'CSV Link : '.base_url().$attachpath1;
				$final_str.= '<br/><br/>';
				$final_str.= 'Exam Code: '.$info[0]['exam_code'];
				$final_str.= '<br/>';
				$final_str.= 'Exam Period: '.$info[0]['exam_period']; 
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'ESDS Team';
				
				
				$toarr = array('sajan@iibf.org.in','pawansing.pardeshi@esds.co.in','sgbhatia@iibf.org.in');
				//$toarr = array('pawansing.pardeshi@esds.co.in'); 
				$files=array($attachpath);
				$info_arr=array('to'=>$toarr,
									'from'=>'noreply@iibf.org.in',
									'subject'=>'Admit letter request file',
									'message'=>$final_str 
								);
				//echo $attachpath;  		
				//print_r($files);	
					
				if($this->Emailsending->mailsend($info_arr)){ 
					$update_data = array('mail_send'=>1);
					$this->master_model->updateRecord('request_admitcard',$update_data,array('id'=>$info[0]['id']));
				}else{
					echo 'mail not send'; 
				}
			}else{
				echo 'file not found';   
			}
		}
	}
	
	public function send_mail(){
		$exam_code = $this->input->post('exam_code');
		$exam_period = $this->input->post('exam_period');
		
		$insert_array = array(
								'exam_code'=>$exam_code,
								'exam_period'=>$exam_period,
								'request_date'=>date('Y-m-d'),
								'status' => 0
							);
							
		$last_id = $this->master_model->insertRecord('request_admitcard',$insert_array,true);
		
		$final_str = 'Hello Team,';
		$final_str.= '<br/><br/>';
		$final_str.= 'Please provide the admit card download link for below detail.';
		$final_str.= '<br/><br/>';
		$final_str.= 'Exam Code: '.$exam_code;
		$final_str.= '<br/>';
		$final_str.= 'Exam Period: '.$exam_period;
		$final_str.= '<br/>';
		$final_str.= 'Request Id: '.$last_id;
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards,';
		$final_str.= '<br/>';
		$final_str.= 'IIBF Team';
		
		$info_arr=array('to'=>'pawansing.pardeshi@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'Admit letter request ',
						'message'=>$final_str
						); 
						
		if($this->Emailsending->mailsend($info_arr)){
			$this->session->set_flashdata('success','Request successfully added');
			redirect(base_url().'Requestadmitcard'); 	
		}else{
			$this->session->set_flashdata('error','Mail not sent');
			redirect(base_url().'Requestadmitcard');
		}
	}
	
	public function create_xls(){
		
		
		$this->db->where('status',1); 
		$this->db->where('mail_send',0);
		$this->db->limit(1);
		$info=$this->master_model->getRecords('request_admitcard');
		
		$exm_cd = $info[0]['exam_code'];
		$exm_prd = $info[0]['exam_period'];
		
		$cron_file_path = "./uploads/zipfile/"; 
		$file           = $exm_cd."_".$exm_prd.".csv";
		$fp             = fopen($cron_file_path . '/' . $file, 'w');  
		
		
		$col = "exm_cd,exm_prd,mem_type,g_1,mem_mem_no,mam_nam_1,email,mobile,center_code,center_name,sub_cd,sub_dsc,venueid,venue_name,venue_address,venpin,seat_identification,pwd,exam_date,time,mode,m_1,scribe_flag,vendor_code \n";
		
		
		$select    = "exm_cd, exm_prd, mem_type, g_1, mem_mem_no, mam_nam_1, email, mobile, center_code, center_name, sub_cd, sub_dsc, venueid, venue_name, CONCAT(`venueadd1`,', ',`venueadd2`,', ',`venueadd3`,', ',`venueadd4`,', ',`venueadd5`) AS VENUE_ADDRESS, venpin, seat_identification, pwd, exam_date, time, mode, m_1, scribe_flag, vendor_code"; 
		$this->db->join('member_registration', 'member_registration.regnumber = admit_card_details.mem_mem_no');
		$this->db->where('admit_card_details.exm_cd', $exm_cd);
		$this->db->where('remark', 1);
		$this->db->where('member_registration.isactive', '1');
		$this->db->where('admit_card_details.exm_prd', $exm_prd); 
		//$this->db->limit(5,0);
		$sql = $this->master_model->getRecords('admit_card_details','',$select); 
		
		$exam_file_flg = fwrite($fp, $col);
		
		foreach($sql as $rec){ 
			
			
			$data = '';
			$exm_cd=$exm_prd=$mem_type=$g_1=$mem_mem_no=$mam_nam_1=$email=$mobile=$center_code=$center_name=$sub_cd=$sub_dsc=$venueid=$venue_name=$venue_address=$venpin=$seat_identification=$pwd=$exam_date=$time=$mode=$m_1=$scribe_flag=$vendor_code="";
			
			$exm_cd = $rec['exm_cd'];
			$exm_prd = $rec['exm_prd'];
			$mem_type = $rec['mem_type'];
			$g_1 = $rec['g_1'];
			$mem_mem_no = $rec['mem_mem_no'];
			$mam_nam_1 = $rec['mam_nam_1'];
			$email = $rec['email'];
			$mobile = $rec['mobile'];
			$center_code = $rec['center_code'];
			$center_name = $rec['center_name'];
			$sub_cd = $rec['sub_cd'];
			$sub_dsc = $rec['sub_dsc'];
			$venueid = $rec['venueid'];
			$venue_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $rec['venue_name']); 
			$venue_address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $rec['VENUE_ADDRESS']); 
			$venpin = $rec['venpin'];
			$seat_identification = $rec['seat_identification'];
			$pwd = $rec['pwd'];
			$exam_date = $rec['exam_date'];
			$time = $rec['time'];
			$mode = $rec['mode'];
			$m_1 = $rec['m_1'];
			$scribe_flag = $rec['scribe_flag'];
			$vendor_code = $rec['vendor_code'];
			
			$data .= '' . $exm_cd . ',' . $exm_prd . ',' . $mem_type . ',' . $g_1 . ',' . $mem_mem_no . ',' . $mam_nam_1 . ',' . $email . ',' . $mobile . ',' . $center_code . ',' . $center_name . ',' . $sub_cd . ',' . $sub_dsc . ',' . $venueid . ',' . $venue_name . ',' . $venue_address . ',' . $venpin . ',' . $seat_identification . ',' . $pwd . ',' . $exam_date . ',' . $time . ',' . $mode . ',' . $m_1 . ',' . $scribe_flag . ',' . $vendor_code ."\n";
			
			$exam_file_flg = fwrite($fp, $data);
			
		
		}
		
	}
	
}