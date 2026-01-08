<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Exam extends CI_Controller {
	public function __construct()

	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('chk_session');
		$this->load->model('Emailsending');
		$this->chk_session->chk_member_session();
		if($this->router->fetch_method()!='comApplication' && $this->router->fetch_method()!='preview' && $this->router->fetch_method()!='Msuccess')
		{
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			if($this->session->userdata('examcode'))
			{
				$this->session->unset_userdata('examcode');
			}
		}
		
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
	
	##------------------ Member Applied Exam History (PRAFULL)---------------##
	public function history()
	{
		/*$this->db->select('member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_fee,member_exam.created_on,exam_master.description,center_master.center_name,misc_master.exam_month,medium_master.medium_description');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$this->db->join('medium_master','medium_master.exam_code=member_exam.exam_code AND medium_master.exam_period=member_exam.exam_period  AND medium_master.medium_code=member_exam.exam_medium');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->where('misc_master.misc_delete','0');
		$this->db->group_by('medium_master.exam_code');
		$exam_history=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'),'',array('	member_exam.id'=>'DESC'));*/
		
		$this->db->select('member_exam.exam_code,member_exam.exam_period,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_fee,member_exam.created_on,exam_master.description,member_exam.exam_mode,member_exam.exam_center_code,member_exam.exam_medium');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		//$this->db->group_by('member_exam.exam_code');
		//$this->db->group_by('member_exam.exam_period');
		$exam_history=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'),'',array('	member_exam.id'=>'DESC'));

		//echo $this->db->last_query(); die;
		
		
		$data=array('middle_content'=>'examhistory_list','exam_history'=>$exam_history);
		$this->load->view('common_view',$data);
	}
	
	public function history_new()
	{
		$this->db->select('member_exam.exam_code,member_exam.exam_period,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_fee,member_exam.created_on,exam_master.description,member_exam.exam_mode,member_exam.exam_center_code,member_exam.exam_medium');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$exam_history=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'),'',array('member_exam.id'=>'DESC'));
		
		
		$data=array('middle_content'=>'examhistory_list_new','exam_history'=>$exam_history);
		$this->load->view('common_view',$data); 
	}
}

