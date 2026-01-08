<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CheckExamActivatoin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
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

	##------------------ Exam list for logged in user(PRAFULL)---------------##
	public function examlist()
	{
		 $date = date('Y-m-d', strtotime("- 4 day"));
		 $this->db->where('exam_to_date <',$date);
		 $this->db->where('exam_period !=',0);
		 $exam_list=$this->master_model->getRecords('exam_activation_master');
		 echo $this->db->last_query();exit;
		 if(count($exam_list) > 0)
		 {
		 	foreach($exam_list as $row)
			{
				$update_data = array('exam_period' => 0);
				$update_query=$this->master_model->updateRecord('exam_activation_master',$update_data,array('id'=>$row['id']));
			}
		 }
	}
}