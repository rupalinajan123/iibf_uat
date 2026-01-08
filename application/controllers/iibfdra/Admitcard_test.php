<?php 
	defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
	/**
		* Controller class Admitcard_test.
		*
		* Admitcard_test class handles all the actions related with DRA/DRA-TC admitcard download functionality. 
		* It communicates with Model classes and pull the Data required to display
		* Loads particular template and view to show the data (List data, show input forms)
		* This controller handles the server actions
		* @copyright    Copyright (c) 2018 ESDS Software Solution Private.
		* @author       Tejasvi Bhavsar
		* @package      Controller
		* @subpackage   Admitcard_test
		* @version      1.0 
		* @updated      2018-08-20
	*/
	class Admitcard_test extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			if(!$this->session->userdata('dra_institute')) {
				redirect('iibfdra/InstituteLogin');
			}
			$this->load->library('upload');
			
			error_reporting(E_ALL);
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
			
			$this->db->select('invoice_id, exam_code, exam_period, center_code, pay_txn_id, receipt_no, date_of_invoice, transaction_no, qty, institute_code, modified_on');
			$this->db->where('(exam_code = 45 OR exam_code=57)');
			$this->db->where('exam_period','777');
			$this->db->where('institute_code',$inst_code);
			$data['download_admit_card_data'] = $this->master_model->getRecords('exam_invoice');
			//echo $this->db->last_query(); exit;
			
			$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Admitcard_test</li>
			</ol>';
			$data['middle_content']	= 'admitcard_pdf_test';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			//echo '<pre>',print_r($data),'</pre>';
			$this->load->view('iibfdra/common_view',$data);
			
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