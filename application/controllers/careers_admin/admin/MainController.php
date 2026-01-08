<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MainController extends CI_Controller {
	public $UserID;
	public $UserData;
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('career_admin')) {
			redirect('careers_admin/admin/Login');
		}	
		$this->UserData = $this->session->userdata('career_admin');
		$this->load->model('UserModel');
		$this->UserID = $this->UserData['id'];
	}
	public function index()
	{
		//echo "home"; die;
		//$data = $this->getUserInfo();
		
		// get total members registered -
		//$data['total_reg_dra_exam'] = $this->total_reg_dra_exam();
		//$data['total_reg_dra_telecaller_exam'] = $this->total_reg_dra_telecaller_exam();
		//$data['total_reg_reattempt'] = $this->total_reg_reattempt();
		
		// get total members registered today -
		//$current_date = date("Y-m-d");
		//$data['total_reg_today_dra_exam'] = $this->total_reg_dra_exam($current_date);
		//$data['total_reg_today_dra_telecaller_exam'] = $this->total_reg_dra_telecaller_exam($current_date);
		//$data['total_reg_today_reattempt'] = $this->total_reg_reattempt($current_date);
				
		$this->load->view('careers_admin/admin/home/home');	
	}
	public function getUserInfo(){
		$data['DraUser'] = $this->UserModel->getDraUserInfo( $this->UserID );
		return $data;
	}
}