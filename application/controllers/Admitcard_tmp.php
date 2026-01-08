<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admitcard_tmp extends CI_Controller 
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
		 //$this->load->model('Master_model'); 
	} 
	
	// function run for old table  from website link without exam codde 101
	public function getadmitcardjd($enc_exam_code='') 
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
			
			if($member_id == '' ) { redirect(base_url());	}
			
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			//echo '<br>'.$exam_code = base64_decode($this->uri->segment(3)); 
			$exam_code = base64_decode($enc_exam_code); 
			
			$this->db->select('ai.admitcard_id, ai.vendor_code, ai.m_1, ai.admitcard_id, mr.scannedphoto, mr.scannedsignaturephoto');
			$this->db->from('admitcard_info ai');
			$this->db->join('member_registration mr', 'ai.mem_mem_no = mr.regnumber');
			$this->db->where('ai.mem_mem_no',$member_id);
			$this->db->where('ai.exm_cd',$exam_code);
			$this->db->order_by("ai.date", "asc");
			$record = $this->db->get();
			$result = $record->row(); 
			//echo '<br>3'.$this->db->last_query();
			
			// if($member_id == '510150628')
			// { 
			// 	echo $member_id."<pre> Exam =>".$exam_code;
			// 	print_r($result);
			// 	echo $this->db->last_query();exit;
			// }
			
			if($result->vendor_code == 3){ $vcenter = '3'; }
			elseif($result->vendor_code == 1){ $vcenter = '1'; }
			$medium_code = $result->m_1;
			
			/*$drr = array('06-Dec-20','12-Dec-20','13-Dec-20','27-Dec-20','26-Dec-20','20-Dec-20','10-Jan-21','17-Jan-21','24-Jan-21','31-Jan-21','23-Jan-21','28-Feb-21','07-Mar-21','27-Feb-21','10-Jul-21','18-Jul-21','11-Jul-21');*/
			//$drr = array('2022-01-08','2022-01-09','2022-01-22');
			$dipcert_arr = array(8,11,18,19,24,25,26,78,79,149,151,153,156,158,162,163,165,166);
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') )
			{
				// $drr = array('2022-04-09','2022-04-23','2022-04-24');
				$drr = array('2022-06-12','2022-06-19','2022-06-11');
			}
			else if(in_array($exam_code,$dipcert_arr))
			{
				$drr = array('2022-03-27','2022-03-20','2022-03-13');
			}
			else
			{
			 	$drr = array('2022-01-30','2022-02-06','2022-02-12');
			}
			
			//$this->db->select('ai.admitcard_id, ai.venueid, ai.venueadd1, ai.venueadd2, ai.venueadd3, ai.venueadd4, ai.venueadd5, ai.venpin');
			$this->db->select('ai.*');
			$this->db->from('admitcard_info ai');
			$this->db->where('ai.mem_mem_no', $member_id);
			$this->db->where('ai.exm_cd', $exam_code);
			$this->db->where_in('ai.date',$drr);
			$this->db->group_by('ai.venueid');
			//$this->db->group_by('date');
			$this->db->order_by("ai.date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			//echo '<br>4'.$this->db->last_query(); 
			//echo '<br>5 Count : '.count($results);
			//echo '<pre>'; print_r($results); echo '</pre>';
			
			
			/* Code is made by pratibha borse 26 feb 2022*/
			## if 3 exam have 2 venue then venue must be show by Ascnding date, test mem_mem_no is 510482951
			/* if(count($results)==2)
			{
				function date_sort($a, $b) 
				{
					return strtotime($a) - strtotime($b);
				}
				usort($results, "date_sort");
				//   echo "<pre>"; print_r($results); //exit; 
			} */
			/* code end */
			//echo '<br>6';
			
			//510368944
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') )
			{
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2022-06-12','2022-06-19','2022-06-11') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}else if(in_array($exam_code,$dipcert_arr)){
				$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2022-03-27','2022-03-20','2022-03-13') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}else{
			    $subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' AND date IN('2022-01-30','2022-02-06','2022-02-12') ORDER BY STR_TO_DATE(date, '%Y-%m-%d') ASC ");
			}
			$subject_result = $subject->result();
			//echo '<br>5'.$this->db->last_query(); exit; 
			
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
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			$this->load->view('admitcardjd', $data);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
}