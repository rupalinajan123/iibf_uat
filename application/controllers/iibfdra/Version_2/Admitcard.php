<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
/**
 * Controller class Admitcard.
 *
 * Admitcard class handles all the actions related with DRA/DRA-TC admitcard download functionality. 
 * It communicates with Model classes and pull the Data required to display
 * Loads particular template and view to show the data (List data, show input forms)
 * This controller handles the server actions
 * @copyright    Copyright (c) 2018 ESDS Software Solution Private.
 * @author       Tejasvi Bhavsar
 * @package      Controller
 * @subpackage   Admitcard
 * @version      1.0 
 * @updated      2018-08-20
 */
class Admitcard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('dra_institute')) {
			redirect('iibfdra/Version_2/InstituteLogin');
		}
		$this->load->library('upload');
		$this->load->helper('dra_admitcard_helper');
		$this->load->helper('directory');
	}
	public function index()
	{

		$data['pdf_listing'] = '';
		$inst_code = $this->session->userdata['dra_institute']['institute_code'];

		//----------#get directory path
	    $dir = 'uploads/iibfdra_admitcard/'.$inst_code;
		
		//-----------#Open a directory, and read its contents
		$i= 0;
		if (is_dir($dir))
		{
			if ($dlisting = opendir($dir)) 
			{

			    while (false !== ($file = readdir($dlisting))) {

			        if ($file != "." && $file != "..") {

			            //echo "$entry\n";
			            $data['pdf_listing'][$i]['pdf'] = 'uploads/iibfdra_admitcard/'.$inst_code.'/'.$file;
			            $file_parts = pathinfo($file);
			            $data['pdf_listing'][$i]['admitcard_name'] = $file_parts['filename'];
			        }
			         $i++;
			    }

			    closedir($dlisting);
			}
		}

			/* $this->db->select('invoice_id, exam_code, exam_period, center_code, pay_txn_id, receipt_no, date_of_invoice, transaction_no, qty, institute_code, modified_on');
			$this->db->where('(exam_code = 45 OR exam_code=57)');
			$this->db->where('exam_period','777');
			$this->db->where('institute_code',$inst_code);
			$data['download_admit_card_data'] = $this->master_model->getRecords('exam_invoice'); */
			
			$this->db->select('id, exam_code, exam_period, UTR_no, receipt_no, transaction_no, pay_count, inst_code, updated_date');
			$this->db->where('(exam_code = 45 OR exam_code=57 OR exam_code=1036)');
			$this->db->where('exam_period','1');
			$this->db->where('status','1');
			$this->db->where('updated_date !=','0000-00-00 00:00:00');
			$this->db->where('inst_code',$inst_code);
			$this->db->order_by('updated_date','DESC'); 
			$data['download_admit_card_data'] = $this->master_model->getRecords('dra_payment_transaction');
			//echo $this->db->last_query(); exit;
			
		    $code=array(45,57,1036);
			$this->db->select('admitcard_id, exm_cd, exam_period,center_code,count(mem_mem_no) as no_of_candidates,center_name,date');
			$this->db->where_in('exm_cd' ,$code);
			
			$this->db->where('inscd',$inst_code);
			$this->db->group_by('center_code,exm_cd');
			$this->db->order_by('admitcard_id','DESC'); 
			$data['download_admitcard_data'] = $this->master_model->getRecords('dra_admitcard_info');

			$this->db->select('date');
			$this->db->where_in('exm_cd' ,$code);
			$this->db->group_by('date'); 
			$date_admitcard_data = $this->master_model->getRecords('dra_admitcard_info');
			$date_str = '';
			foreach ($date_admitcard_data as $key => $value) {
				if ($key>0) {
					$date_str .= ' & '.date('d-M-Y',strtotime($value['date']));
				} else {
					$date_str .= date('d-M-Y',strtotime($value['date']));
				}
			}
			$data['date_str'] = $date_str; 
 			
	$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Admitcard</li>
		 </ol>';
	$data['middle_content']	= 'admitcard_pdf';
	$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
	$res = $this->master_model->getRecords("dra_exam_master a");
	$data['active_exams'] = $res;
	//echo '<pre>',print_r($data),'</pre>';
	$this->load->view('iibfdra/Version_2/common_view',$data);

	}
	
		public function centerwise_member_list($center_code='',$exam_code='',$exam_period='')
		{
			
			$data['pdf_listing'] = '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			/* $center_code=$this->uri->segment('4');
      $exam_code=$this->uri->segment('5');
      $exam_period=$this->uri->segment('6'); */
			
			$this->db->select('admitcard_id, exm_cd, exam_period,center_code,mem_mem_no,center_name,date');
			$this->db->where('exm_cd' ,$exam_code);
			$this->db->where('exam_period' ,$exam_period);
			$this->db->where('center_code' ,$center_code);
			$this->db->where('inscd',$inst_code);
			$this->db->order_by('admitcard_id','DESC'); 
			$data['download_admitcard_data'] = $this->master_model->getRecords('dra_admitcard_info');
			//echo $this->db->last_query(); exit;												
			$this->db->select('date');
			$this->db->where('exm_cd' ,$exam_code);
			$this->db->group_by('date'); 
			$date_admitcard_data = $this->master_model->getRecords('dra_admitcard_info');
			$date_str = '';
			foreach ($date_admitcard_data as $key => $value) {
				if ($key>0) {
					$date_str .= ' & '.date('d-M-Y',strtotime($value['date']));
				} else {
					$date_str .= date('d-M-Y',strtotime($value['date']));
				}
			}
			$data['date_str'] = $date_str; 	

			$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			<li class="active"><a href="'.base_url().'iibfdra/Version_2/Admitcard/">Admitcard</a></li>
			</ol>';
			$data['middle_content']	= 'admitcard_pdf_member_list';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			//echo '<pre>',print_r($data),'</pre>';
			$this->load->view('iibfdra/Version_2/common_view',$data);
			
		}
	
	public function download_pdfs()
	{
				
		$inst_code = $this->session->userdata['dra_institute']['institute_code'];
		//$dir = 'dir';
		$dir = 'uploads/iibfdra_admitcard/'.$inst_code;
		$zip_file = 'admitcards"'.$inst_code.'".zip';
		// Get real path for our folder
		$rootPath = realpath($dir);

		// Initialize archive object
		$zip = new ZipArchive();
		$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
		    new RecursiveDirectoryIterator($rootPath),
		    RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file)
		{
		    // Skip directories (they would be added automatically)
		    if (!$file->isDir())


		    {
		        // Get real and relative path for current file
		        $filePath = $file->getRealPath();
		        $relativePath = substr($filePath, strlen($rootPath) + 1);

		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }
		}

		// Zip archive will be created only after closing object
		$zip->close();


		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($zip_file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($zip_file));
		readfile($zip_file);


	} 
	
	public function download_member_pdf($member_no='',$exam_code='',$exam_period=''){
	    
	    /* $member_no=$this->uri->segment('4');
	    $exam_code=$this->uri->segment('5');
	    $exam_period=$this->uri->segment('6'); */
	    $attchpath_admitcard = genarate_admitcardinfo_dra($member_no,$exam_code,$exam_period);
	    
	    echo $attchpath_admitcard;die;
	    $this->load->library('m_pdf');
		$pdf = $this->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_no.".pdf";
		$pdf->WriteHTML($attchpath_admitcard);
		$path = $pdf->Output($pdfFilePath, "D"); 
	    //$path = $pdf->Output($attchpath_admitcard, "D"); 
	}
	public function download_centerwise_pdf($center_code='',$exam_code='',$exam_period=''){
	    
	    $inst_code = $this->session->userdata['dra_institute']['institute_code'];
		  /* $center_code=$this->uri->segment('4');
	    $exam_code=$this->uri->segment('5');
	    $exam_period=$this->uri->segment('6'); */
			//$this->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card start", "description"=>"member_id = ".$member_id.' >> exam_code = '.$exam_code.' >> exam_period = '.$exam_period));		
			
			$this->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5,mem_adr_6, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code');
			$this->db->from('dra_admitcard_info');
			$this->db->where(array('dra_admitcard_info.center_code' => $center_code,'exm_cd'=>$exam_code,'exam_period'=>$exam_period,'inscd'=>$inst_code));/* ,'admitcard_image'=>'' */
			$this->db->order_by("admitcard_id", "desc");
			$member_record = $this->db->get();
			//$member_result = $member_record->row();
			$last_qry = $this->db->last_query();
			
			if(sizeof($member_record) == 0)
			{
				$this->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card error", "description"=>$last_qry));
				return '';
				exit;
			}
			else
			{
				$this->load->library('m_pdf');
    		$pdf = $this->m_pdf->load();
    		$pdfFilePath = $exam_code."_".$exam_period."_".$center_code.".pdf";
    		$pdf->WriteHTML($attchpath_admitcard);
				//file directory creation
				$file_dir_name = date('Ymd').'_'.$center_code.'_'.$exam_code.'_'.$inst_code;
				$directory_name = "./uploads/iibfdra_admitcard_new/".$file_dir_name;
				//mkdir($directory_name); 
				if (!file_exists($directory_name))
				{
					mkdir($directory_name);
				}
				$cnt=0;
				
				//echo  "swa".$member_id=$member_result->vendor_code;
				//	print_r($member_record);
		    foreach($member_record->result() as $member_result)
				{					
					$member_id=$member_result->mem_mem_no;
					if(isset($member_result->vendor_code) || $member_result->vendor_code!='' )
					{
						$vcenter = $member_result->vendor_code;
					}
					elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' )
					{
						$vcenter = '0';
					}
					
					$medium_code = $member_result->m_1;
					
					$this->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name,seat_identification');
					$this->db->from('dra_admitcard_info');
					$this->db->where(array('dra_admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exam_period'=>$exam_period));
					$this->db->group_by('venueid');
					$this->db->order_by("date", "asc");
					$venue_record = $this->db->get();
					$venue_result = $venue_record->result();
					
					$this->db->select('description');
					$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
					$exam_result = $exam->row();
					
					$this->db->select('subject_description,dra_admitcard_info.date,time,venueid,seat_identification');
					$this->db->from('dra_admitcard_info');
					$this->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(dra_admitcard_info.sub_cd,3)');
					$this->db->where(array('admit_subject_master.exam_code' => $exam_code,'dra_admitcard_info.mem_mem_no'=>$member_id,'subject_delete'=>0,'dra_admitcard_info.exam_period'=>$exam_period));
					$this->db->where('pwd!=','');
					// $this->db->where('seat_identification!=','');
					//$this->db->where('remark',1);
					$this->db->order_by("dra_admitcard_info.date", "asc");
					$subject = $this->db->get();
					$subject_result = $subject->result();
					$lstQry2 = $this->db->last_query();

					$this->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 2", "description"=>$lstQry2));
					
					$pdate = $subject->result();
					foreach($pdate as $pdate)
					{
						$exdate = date("d-M-y", strtotime($subject_result[0]->date)); ;
						$examdate = explode("-",$exdate);
						$examdatearr[] = $examdate[1];
					}
					
					$exdate = date("d-M-y", strtotime($subject_result[0]->date)); 
					$examdate = explode("-",$exdate);
					$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];

					if($medium_code == 'ENGLISH' || $medium_code == 'E')
					{
						$medium_code_lng = 'E';
					}
					elseif($medium_code == 'HINDI' || $medium_code == 'H')
					{
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
					$medium = $this->db->get_where('dra_medium_master', array('medium_code' => $medium_code_lng));
					$medium_result = $medium->row();
					
					$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
					
					/*if($this->get_client_ip1() == '115.124.98.10') {	
						echo "<pre>"; print_r($data); 
					}*/
					
					$this->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 3", "description"=>serialize($data)));
					
					/*echo '<pre>';
						print_r($data);
					exit;*/
					
					
					
					$dra_admit_card_details = $this->db->get_where('dra_admitcard_info', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exam_period'=>$exam_period));
					
					$this->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card End", "description"=>$pdfFilePath));		
					
					
					
    	    if($cnt > 7) { redirect(site_url('iibfdra/Version_2/Admitcard/download_centerwise_pdf/'.$center_code."/".$exam_code."/".$exam_period)); }
					
					
					$pdfFilePath = 'uploads/iibfdra_admitcard_new/'.$file_dir_name.'/'; 
					$pdfFilename = $exam_code."_".$exam_period."_".$member_id.".pdf";
					$file_arr[] = $pdfFilename;
					
					if (!file_exists($pdfFilePath.$pdfFilename)) 
					{   
				    $html=$this->load->view('iibfdra/Version_2/dra_admitcardpdf_attach', $data, true);
						$this->load->library('m_pdf');
						$pdf = $this->m_pdf->load();						
						$pdf->WriteHTML($html);
						$pdf->Output($pdfFilePath.$pdfFilename,"F");
						//$pdf->Output($pdfFilePath, "D");
						$cnt++;
					}
					
				}
				/*if($this->get_client_ip1() == '115.124.98.10') {	
					exit; 
				}*/
				//print_r($file_arr);
				//print_r($pdfFilePath);
				$zip = new ZipArchive(); // Load zip library 
				$zip_name = date('Ymd').'_'.$center_code.'_'.$exam_code.'_'.$inst_code.".zip"; // Zip name
				if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
				{ 
					// Opening zip file to load files
					$error .= "* Sorry ZIP creation failed at this time";
				}
				
				foreach ($file_arr as $filename)
				{
					$zip->addFile($pdfFilePath.$filename,$filename);
					//$zip->addFile($zip_name.$filename);
				}
				
				// $zip->addFile($pdfFilePath.$pdfFilename); // Adding files into zip
				$zip->close();
				
				//START : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
				  $all_directories = $this->get_directory_list("uploads/iibfdra_admitcard_new/");
				 //echo count($all_directories);
				if(count($all_directories) > 0)
				{
					foreach($all_directories as $dir)
					{
						$explode_arr = explode("_", $dir, 2);
						if($explode_arr[0] != date('Ymd'))
						{
							//echo "<br> Delete : ".$dir;
							$this->rmdir_recursive("uploads/iibfdra_admitcard_new/".$dir);
						}
					}
				}				
				//END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
				
				if(file_exists($zip_name))
				{
					// push to download the zip
					header('Content-type: application/zip');
					header('Content-Disposition: attachment; filename="'.$zip_name.'"');
					readfile($zip_name); 
					//remove zip file is exists in temp path
					unlink($zip_name);
					//$rs = $this->delete_directory($pdfFilePath);   
				}	
			}	    
	    	
		}
			
		function get_client_ip1() {
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

		/* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */
		function get_directory_list($dir_name)
		{
			return $this->array_sort_ascending(directory_map('./'.$dir_name, 1)); // This is use to get all folders and files from current directory excluding subfolders
		}
		
		/* SORT ARRAY IN ASCENDING ORDER USING VALUES NOT KEY */
		function array_sort_ascending($array)
		{
			if($array != "") { sort($array); /* sort() - sort arrays in ascending order. rsort() - sort arrays in descending order. */ }
			return $array;
		}
		
		/* RECURSIVE FUNCTION TO DELETE ALL SUB FILES AND FOLDER FROM REQUIRED FOLDER */
		function rmdir_recursive($dir) 
		{
			foreach(scandir($dir) as $file) 
			{
				if ('.' === $file || '..' === $file) continue;
				if (is_dir("$dir/$file")) 
				{
					$this->rmdir_recursive("$dir/$file");
				}
				else unlink("$dir/$file");
			}
			rmdir($dir);
		}
	/*
	public function download_pdfs()
	{
		$zipname = 'admitcard.zip';
	    $zip = new ZipArchive;
	    $zip->open($zipname, ZipArchive::CREATE);

	    $inst_code = $this->session->userdata['dra_institute']['institute_code'];

		//----------#get directory path
	    $dir = 'uploads/iibfdra_admitcard/'.$inst_code;
		

	    if ($handle = opendir($dir)) {
	      while (false !== ($entry = readdir($handle))) {
	        if ($entry != "." && $entry != ".." && !strstr($entry,'.pdf')) {
	            $zip->addFile($entry);
	        }
	      }
	      closedir($handle);
	    }

	    $zip->close();

	  /*  header('Content-Type: application/zip');
	    header("Content-Disposition: attachment; filename='admitcard.zip'");
	    header('Content-Length: ' . filesize($zipname));
	    header("Location: admitcard.zip");//

	    header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($zipname));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);

	} 
    */
  
}

?>