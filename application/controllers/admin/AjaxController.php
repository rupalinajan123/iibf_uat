<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class AjaxController extends CI_Controller {

	public $UserID;

			

	public function __construct(){

		parent::__construct();

		if($this->session->UserID==""){

			redirect('Login');

		}		

		$this->load->model('UserModel');

		$this->UserID=$this->session->UserID;

		$this->load->helper('TAPortal');

	}

	

	

	public function editDepartment(){

		$DepartmentID=$this->input->post('DepartmentID');

		$field = $this->input->post('field');

		$value = $this->input->post('value');

		if(empty($DepartmentID)){

			$perm=hasPermission($this->UserID,'Departments','Add Department');

			$st='Add';

		}else{

			$perm=hasPermission($this->UserID,'Departments','Update Department');

			$st='Update';

		}		

		if(!$perm){

			$this->session->set_flashdata('error_message', "You don't have permissions to $st Department");

			redirect('admin/MainController/Page/Departments');die;

		}

				

		$data=array($field=>$value);

		//print_r($data);

		

		if(!empty($DepartmentID)){

			$is=$this->UserModel->updateDepartment($DepartmentID,$data);

			$m='updated';

		}else{	

			$is=$this->UserModel->addDepartment($data);

			$m='inserted';

		}

		

		if($is==TRUE){

			echo "1";

		}else{

			echo "0";

		}			

	}
}