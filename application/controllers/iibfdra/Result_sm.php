<?php 
	defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
	/**
		* Controller class Result.
		*
		* Result class handles all the actions related with DRA/DRA-TC result generation and management. 
		* It communicates with Model classes and pull the Data required to display
		* Loads particular template and view to show the data (List data, show input forms)
		* This controller handles the server actions
		* @copyright    Copyright (c) 2018 ESDS Software Solution Private.
		* @author       Tejasvi Bhavsar
		* @package      Controller
		* @subpackage   Result
		* @version      1.0
		* @updated      2018-08-21
	*/
	class Result_sm extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			if(!$this->session->userdata('dra_institute')) {
				redirect('iibfdra/InstituteLogin');
			}
			//get current exam period 
			$exm_prd_data = $this->master_model->getRecords('dra_exam_activation_master','',array('exam_period'));
			//	$exm_prd_data = $this->master_model->getRecords('dra_result_subject','',array('exam_period'));
			if(!empty($exm_prd_data))
			{
				$this->session->set_userdata('dra_exam_period',$exm_prd_data[0]['exam_period']);
				}else{
				redirect('iibfdra/InstituteLogin');
			}
			//	echo 'prd',$this->session->userdata('dra_exam_period');
			
			$this->load->helper('directory');
			ini_set("memory_limit", "-1");
			ini_set('max_execution_time', '0');
			error_reporting(E_ALL);			
		}
		
		public function DRA_Result()
		{
			
			$data['result']	= '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			$exam_period = $this->session->userdata('dra_exam_period');
			
			$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
			$this->db->join('dra_center_master','dra_center_master.center_code = dra_marks.center_code AND dra_center_master.exam_name = dra_marks.exam_code');
			$this->db->group_by('dra_marks.center_code');
			$result = $this->master_model->getRecords('dra_memdetails',array('dra_memdetails.inst_code'=>$inst_code,'dra_marks.exam_code'=>45,'dra_marks.exam_period'=>$exam_period),array('no_of_candidates'=>"count('dra_marks.regnumber') as mem_count",'dra_marks.exam_period','dra_marks.center_code','dra_center_master.center_name','dra_marks.exam_code'));
			//echo $this->db->last_query();
			//echo '<pre>',print_r($result),'</pre>';
			if(!empty($result))
			{
				$data['result']	= $result;
			}
			
			$data['title']	= 'DRA Result (16th OCT 2020)';
			$data['application_name']	= 'DEBT RECOVERY AGENT';
			$data['middle_content']	= 'result';
			$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Admitcard</li>
			</ol>';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);  
			
		}
		public function DRATC_Result()
		{
			$data['result']	= '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			$exam_period = $this->session->userdata('dra_exam_period');
			
			$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
			$this->db->join('dra_center_master','dra_center_master.center_code = dra_marks.center_code AND dra_center_master.exam_name = dra_marks.exam_code');
			$this->db->group_by('dra_marks.center_code');
			$result = $this->master_model->getRecords('dra_memdetails',array('dra_memdetails.inst_code'=>$inst_code,'dra_marks.exam_code'=>57,'dra_marks.exam_period'=>$exam_period),array('no_of_candidates'=>"count('dra_marks.regnumber') as mem_count",'dra_marks.exam_period','dra_marks.center_code','dra_center_master.center_name','dra_marks.exam_code'));
			//	echo $this->db->last_query();
			//	echo '<pre>',print_r($result),'</pre>';
			if(!empty($result))
			{
				$data['result']	= $result;
			}
			
			$data['title']	= 'DRA-TC Result (16th OCT 2020)';
			$data['application_name']	= 'DEBT RECOVERY AGENT - TELE CALLERS';
			$data['middle_content']	= 'result';
			$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Admitcard</li>
			</ol>';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);  
			
		}
		public function download_pdf1()
		{ 
			////Auto referesh page
			echo "<script language=\"javascript\"> setTimeout('location.reload(true);', 5000);</script>";
			/*echo '<div class="loading" style="display:block;">
				<img src='.base_url().'assets/images/loading.gif width="120">
			</div>';*/
			
			$data['result'] = '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			$exam_code = base64_decode($this->uri->segment(4));
			$center_code = base64_decode($this->uri->segment(5));
			$exam_period = $this->session->userdata('dra_exam_period');
			
			/*$select = array('dra_memdetails.*','dra_marks.*','dra_subject_master.subject_code','dra_subject_master.subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_subject_master.exam_period as exm_subject_prd');*/
			
			$select = array('dra_memdetails.*','dra_marks.*','dra_result_subject.subject_code','dra_result_subject.subject_name as subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_result_subject.exam_period as exm_subject_prd');
			
			$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
			$this->db->join('dra_result_exam','dra_result_exam.exam_code = dra_marks.exam_code AND dra_result_exam.exam_period = dra_marks.exam_period');
			$this->db->join('dra_result_subject','dra_result_subject.exam_code = dra_marks.exam_code AND dra_result_subject.exam_period = dra_marks.exam_period');
			$result = $this->master_model->getRecords('dra_memdetails',array('dra_marks.center_code'=>$center_code,'dra_marks.exam_code'=>$exam_code,'dra_marks.exam_period'=>$exam_period,'dra_memdetails.inst_code'=>$inst_code),$select);
			
			//echo $this->db->last_query();
			//echo '<pre>',print_r($result),'</pre>';
			
			if($result)
			{
				
				$this->load->library('m_pdf');
				$pdf = $this->m_pdf->load();
				//file directory creation
				$file_dir_name = date('Ymd').'_'.$center_code.'_'.$inst_code;
	    	$directory_name = "./uploads/iibfdra_result/".$file_dir_name;
	    	//mkdir($directory_name); 
				if (!file_exists($directory_name))
	    	{
	    		mkdir($directory_name);
				}
	    	else
	    	{
	    		//echo "Directory ", $directory_name, " already exists";
					$this->form_validation->set_message('error','Directory already exists');
	    		redirect(base_url().'iibfdra/result/DRA_Result');
				}
				
				foreach ($result as $val) 
				{
					# code...
					//echo '<pre>',print_r($val),'</pre>';
					$data['result_info'] = $val;
					$exam_period= $val['exm_subject_prd'];
					$member_id= $val['regnumber'];
					//$this->load->view('iibfdra/result_download',$data);
					
					
					
					//$this->load->view('iibfdra/result_download',$data);
		    	$html=$this->load->view('iibfdra/result_download', $data, true);
					$this->load->library('m_pdf');
					$pdf = $this->m_pdf->load();
					$pdfFilename = $exam_code."_".$member_id.".pdf";
					$file_arr[] = $pdfFilename;
					$pdfFilePath = 'uploads/iibfdra_result/'.$file_dir_name.'/';
					$pdf->WriteHTML($html);
					$pdf->Output($pdfFilePath.$pdfFilename,"F");
					//$pdf->Output($pdfFilePath, "D");
					
				}
				
				//print_r($file_arr);
				//print_r($pdfFilePath);
				$zip = new ZipArchive(); // Load zip library 
				$zip_name = date('Ymd').'_'.$inst_code.".zip"; // Zip name
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
				if(file_exists($zip_name))
				{
					// push to download the zip
					header('Content-type: application/zip');
					header('Content-Disposition: attachment; filename="'.$zip_name.'"');
					readfile($zip_name);
					//remove zip file is exists in temp path
					unlink($zip_name);
					$rs = $this->delete_directory($pdfFilePath);   
				} 
				
			} 
			
		}
		
		public function download_pdf()
		{ 
			/*echo '<div class="loading" style="display:block;">
				<img src='.base_url().'assets/images/loading.gif width="120">
			</div>';*/
			
			$data['result'] = '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			$exam_code = base64_decode($this->uri->segment(4));
			$center_code = base64_decode($this->uri->segment(5));
			$exam_period = $this->session->userdata('dra_exam_period');
			
			/*$select = array('dra_memdetails.*','dra_marks.*','dra_subject_master.subject_code','dra_subject_master.subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_subject_master.exam_period as exm_subject_prd');*/
			
			$select = array('dra_memdetails.*','dra_marks.*','dra_result_subject.subject_code','dra_result_subject.subject_name as subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_result_subject.exam_period as exm_subject_prd');
			
			$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
			$this->db->join('dra_result_exam','dra_result_exam.exam_code = dra_marks.exam_code AND dra_result_exam.exam_period = dra_marks.exam_period');
			$this->db->join('dra_result_subject','dra_result_subject.exam_code = dra_marks.exam_code AND dra_result_subject.exam_period = dra_marks.exam_period');
			$result = $this->master_model->getRecords('dra_memdetails',array('dra_marks.center_code'=>$center_code,'dra_marks.exam_code'=>$exam_code,'dra_marks.exam_period'=>$exam_period,'dra_memdetails.inst_code'=>$inst_code),$select);
			
			//echo $this->db->last_query();
			//echo '<pre>',print_r($result),'</pre>';
			
			if($result)
			{				
				$this->load->library('m_pdf');
				$pdf = $this->m_pdf->load();
				//file directory creation
				$file_dir_name = date('Ymd').'_'.$center_code.'_'.$inst_code;
	    	$directory_name = "./uploads/iibfdra_result/".$file_dir_name;
	    	//mkdir($directory_name); 
				if (!file_exists($directory_name))
	    	{
	    		mkdir($directory_name);
				}
	    	/* else
	    	{
	    		//echo "Directory ", $directory_name, " already exists";
					$this->form_validation->set_message('error','Directory already exists');
	    		redirect(base_url().'iibfdra/result/DRA_Result');
				} */
				
				$cnt = 0;
				foreach ($result as $val) 
				{
					if($cnt > 7) { redirect(site_url('iibfdra/Result_sm/download_pdf/'.base64_encode($exam_code)."/".base64_encode($center_code))); }
					
					//echo '<pre>',print_r($val),'</pre>';
					$data['result_info'] = $val;
					$exam_period= $val['exm_subject_prd'];
					$member_id= $val['regnumber'];
					$pdfFilePath = 'uploads/iibfdra_result/'.$file_dir_name.'/'; 
					$pdfFilename = $exam_code."_".$member_id.".pdf";
					$file_arr[] = $pdfFilename;
					
					if (!file_exists($pdfFilePath.$pdfFilename))
					{
						//echo "in"; exit; 
						$html = $this->load->view('iibfdra/result_download', $data, true);
						$this->load->library('m_pdf');
						$pdf = $this->m_pdf->load();						
						$pdf->WriteHTML($html);
						$pdf->Output($pdfFilePath.$pdfFilename,"F");
						//$pdf->Output($pdfFilePath, "D");
						$cnt++;
					}					
				}
				
				//print_r($file_arr); exit;
				//print_r($pdfFilePath);
				$zip = new ZipArchive(); // Load zip library 
				$zip_name = date('Ymd').'_'.$inst_code.".zip"; // Zip name
				if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
				{ 
					// Opening zip file to load files
					$error .= "* Sorry ZIP creation failed at this time";
				}
				
				foreach ($file_arr as $filename)
				{
					$zip->addFile($pdfFilePath.$filename,$filename);
				}
				$zip->close();
				
				//START : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
				$all_directories = $this->get_directory_list("uploads/iibfdra_result/");
				if(count($all_directories) > 0)
				{
					foreach($all_directories as $dir)
					{
						$explode_arr = explode("_", $dir, 2);
						if($explode_arr[0] != date('Ymd'))
						{
							//echo "<br> Delete : ".$dir;
							$this->rmdir_recursive("uploads/iibfdra_result/".$dir);
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
		
		public function download_pdf_new()
	{ 
	
	  
		/*echo '<div class="loading" style="display:block;">
		<img src='.base_url().'assets/images/loading.gif width="120">
		</div>';*/
		
		$data['result'] = '';
		$inst_code = $this->session->userdata['dra_institute']['institute_code'];
		$exam_code = base64_decode($this->uri->segment(4));
		$center_code = base64_decode($this->uri->segment(5));
		$exam_period = $this->session->userdata('dra_exam_period');

		/*$select = array('dra_memdetails.*','dra_marks.*','dra_subject_master.subject_code','dra_subject_master.subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_subject_master.exam_period as exm_subject_prd');*/

		$select = array('dra_memdetails.*','dra_marks.*','dra_result_subject.subject_code','dra_result_subject.subject_name as subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_result_subject.exam_period as exm_subject_prd');

		$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
		$this->db->join('dra_result_exam','dra_result_exam.exam_code = dra_marks.exam_code AND dra_result_exam.exam_period = dra_marks.exam_period');
		$this->db->join('dra_result_subject','dra_result_subject.exam_code = dra_marks.exam_code AND dra_result_subject.exam_period = dra_marks.exam_period');
		$this->db->limit(10);
		$result = $this->master_model->getRecords('dra_memdetails',array('dra_marks.center_code'=>$center_code,'dra_marks.exam_code'=>$exam_code,'dra_marks.exam_period'=>$exam_period,'dra_memdetails.inst_code'=>$inst_code),$select);

		//echo $this->db->last_query();
		//echo '<pre>',print_r($result),'</pre>';

		if($result)
		{

			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			//file directory creation
			$file_dir_name = date('Ymd').'_'.$center_code.'_'.$inst_code;
	    	$directory_name = "./uploads/iibfdra_result/".$file_dir_name;
	    	//mkdir($directory_name); 
			if (!file_exists($directory_name))
	    	{
	    		mkdir($directory_name);
	    	}
	    	else
	    	{
	    		//echo "Directory ", $directory_name, " already exists";
				$this->form_validation->set_message('error','Directory already exists');
	    		//redirect(base_url().'iibfdra/result/DRA_Result');
	    	}
			
			$cnt = 0;
			foreach ($result as $val) 
			{
				if($cnt > 7) { redirect(site_url('iibfdra/Result_sm/download_pdf_new/'.base64_encode($exam_code)."/".base64_encode($center_code))); }
				
				$data['result_info'] = $val;
				$exam_period= $val['exm_subject_prd'];
				$member_id= $val['regnumber'];
				//$this->load->view('iibfdra/result_download',$data);

				$pdfFilePath = 'uploads/iibfdra_result/'.$file_dir_name.'/';
				$pdfFilename = $exam_code."_".$member_id.".pdf";
				$file_arr[] = $pdfFilename;
		    
				if (!file_exists($pdfFilePath.$pdfFilename))
				{
					//$this->load->view('iibfdra/result_download',$data);
					$html=$this->load->view('iibfdra/result_download', $data, true);
					$this->load->library('m_pdf');
					$pdf = $this->m_pdf->load();
					$pdf->WriteHTML($html);
					$pdf->Output($pdfFilePath.$pdfFilename,"F");
					//$pdf->Output($pdfFilePath, "D");
					
					$cnt++;
				}
			}

			   //print_r($file_arr);
			   //print_r($pdfFilePath);
		       $zip = new ZipArchive(); // Load zip library 
			   $zip_name = date('Ymd').'_'.$inst_code.".zip"; // Zip name
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
	
		
		function delete_directory($dirname) {
			if (is_dir($dirname))
			$dir_handle = opendir($dirname);
			if (!$dir_handle)
			return false;
			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
					else
					delete_directory($dirname.'/'.$file);
				}
			}
			closedir($dir_handle);
			rmdir($dirname);
			return true;
		}
		public function result_listing()
		{
			
			$data['result'] = '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			$exam_code = base64_decode($this->uri->segment(4));
			$center_code = base64_decode($this->uri->segment(5));
			$exam_period = $this->session->userdata('dra_exam_period');
			
			$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
			$this->db->join('dra_center_master','dra_center_master.center_code = dra_marks.center_code AND dra_center_master.exam_name = dra_marks.exam_code');
			//$this->db->group_by('dra_marks.center_code');
			$result = $this->master_model->getRecords('dra_memdetails',array('dra_memdetails.inst_code'=>$inst_code,'dra_marks.exam_code'=>$exam_code,'dra_marks.exam_period'=>$exam_period,'dra_marks.center_code'=>$center_code),array('dra_marks.exam_period','dra_marks.center_code','dra_center_master.center_name','dra_marks.exam_code','dra_memdetails.regnumber'));
			
			//echo $this->db->last_query();
			//echo '<pre>',print_r($result),'</pre>';
			if(!empty($result))
			{
				$data['result']	= $result;
				
			}
			
			if($exam_code==45)
			{
				$data['title']	= 'DRA';
				$data['application_name']	= 'DEBT RECOVERY AGENT';
				}else{
				$data['title']	= 'DRA-TC';
				$data['application_name']	= 'DEBT RECOVERY AGENT - TELE CALLERS';
			}
			
			$data['middle_content']	= 'centerlist_result';
			$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Admitcard</li>
			</ol>';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);  
		}
		//member wise result download
		public function download_result()
		{
			$data['result'] = '';
			$inst_code = $this->session->userdata['dra_institute']['institute_code'];
			$exam_code = base64_decode($this->uri->segment(4));
			$center_code = base64_decode($this->uri->segment(5));
			$regnumber = base64_decode($this->uri->segment(6));
			$exam_period = $this->session->userdata('dra_exam_period');
			
			/*$select = array('dra_memdetails.*','dra_marks.*','dra_subject_master.subject_code','dra_subject_master.subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_subject_master.exam_period as exm_subject_prd');*/
			
			$select = array('dra_memdetails.*','dra_marks.*','dra_result_subject.subject_code','dra_result_subject.subject_name as subject_description','dra_result_exam.exam_name_short','dra_result_exam.exam_conduct','dra_result_exam.result_date','dra_result_subject.exam_period as exm_subject_prd');
			
			$this->db->join('dra_marks','dra_marks.regnumber = dra_memdetails.regnumber');
			$this->db->join('dra_result_exam','dra_result_exam.exam_code = dra_marks.exam_code AND dra_result_exam.exam_period = dra_marks.exam_period');
			$this->db->join('dra_result_subject','dra_result_subject.exam_code = dra_marks.exam_code AND dra_result_subject.exam_period = dra_marks.exam_period');
			$result = $this->master_model->getRecords('dra_memdetails',array('dra_marks.center_code'=>$center_code,'dra_marks.exam_code'=>$exam_code,'dra_marks.exam_period'=>$exam_period,'dra_marks.regnumber'=>$regnumber,'dra_memdetails.inst_code'=>$inst_code),$select);
			
			//echo $this->db->last_query();
			//echo '<pre>',print_r($result),'</pre>';
			
			if($result)
			{	
				$data['result_info'] = $result[0];
				
			}
			
			//$this->load->view('iibfdra/result_download',$data);
    	$html=$this->load->view('iibfdra/result_download', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = $exam_code."_".$regnumber.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");
			
		}
		public function check_img()
		{
			$rs = get_img_name_dra('801006652','p');
			echo 'rs',print_r($rs);
			
		}
		
	}
	
?>