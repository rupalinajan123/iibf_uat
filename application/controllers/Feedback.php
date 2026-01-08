<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {
exit;	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
	}

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
	 
	
	 
	 
	public function index()
	{
	
		 $data['middle_content'] =  'feedback/feedback_form';
		 $this->load->view('feedback/blended_common_view', $data);
		 
	}
	public function feedback()
	{
		
		 
		 if($this->input->post('submit_f')!='')
	{
	

			//print_r($_POST);exit;
			$data['msg']="";
			// save  the feed back 
			
				
					$insert_info        = array(  
					'member_no' => $_POST['no'],
					'name' => $_POST['name'],
					'mem_name' => $_POST['empname'],
					'designation' => $_POST['designation'],
					'branch' => $_POST['branch'],
					'q1_ans' =>$_POST['Q11'],
					'q2_ans' => $_POST['Q12'],
					'comment' =>$_POST['comment'],
					'creation_date' => date('Y-m-d H:i:s')
       				 );
		}else
				{
						$this->session->flashdata('Please the proper question');
						redirect('/Feedback/index/');
				}
	//echo "<pre>"; print_r($insert_info); echo "</pre>";exit;
		
			/* Stored user details and selected field details in the database table */
			if ($last_id = $this->master_model->insertRecord('Feedback_electronic_sheet', $insert_info, true)) 
			{
				
				$data['middle_content'] =  'feedback/feedback_acknowledge';
					//print_r($data);exit;
				$this->load->view('feedback/blended_common_view', $data);
				
			}
		
		else
		{
		
			redirect('/Feedback/index');
	
		 
	}
	}
}