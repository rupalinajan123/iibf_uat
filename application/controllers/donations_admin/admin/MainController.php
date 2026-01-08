<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MainController extends CI_Controller {
	public $UserID;
	public $UserData;
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('career_admin')) {
			redirect('donations_admin/admin/Login');
		}	
		$this->UserData = $this->session->userdata('donation_admin');
		$this->load->model('UserModel');
		$this->UserID = $this->UserData['id'];
	}
	public function index()
	{
		$this->load->view('donations_admin/admin/home/home');	
	}
	public function getUserInfo(){
		$data['DraUser'] = $this->UserModel->getDraUserInfo( $this->UserID );
		return $data;
	}
}