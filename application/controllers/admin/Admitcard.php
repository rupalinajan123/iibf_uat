<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard extends CI_Controller {

	private $USERDATA=array();		

	public function __construct(){

		parent::__construct();

		if($this->session->id==""){
			redirect('admin/Login');
		}
		
		if($this->session->userdata('roleid')!=1){
			redirect(base_url().'admin/MainController');
		}

	}

	

	public function index()

	{

		try{

			$this->db->select('description,exam_code');

			$exam = $this->db->get_where('exam_master');

			$examinfo = $exam->result();

			$data = array("examinfo" => $examinfo); 

			$this->load->view("admin/cardsetting",$data);	

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();

		}

	}

	public function add(){

		try{

			$ins_data = array(

							"from_date" => $this->input->post("from_date"),

							"to_date" => $this->input->post("to_date"),

							"exam_code" => $this->input->post("exam_code"),

						); 

						

			$this->db->select('exam_code');

			$chkexam = $this->db->get_where('admitcardsetting', array('exam_code' => $this->input->post("exam_code")));

			$chkexam_result = $chkexam->row();

			

			if($chkexam_result){

				$this->db->where('exam_code', $this->input->post("exam_code"));

				$this->db->update('admitcardsetting', $ins_data);
				//echo $this->db->last_query();die;

			}else{

				$this->db->insert('admitcardsetting', $ins_data);

			}			

			

			redirect(base_url().'admin/admitcard/');	

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();

		}

	}

	

}