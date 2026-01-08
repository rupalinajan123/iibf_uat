<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('master_helper');
		}

	 ##---------insert click count in DB (prafull)-----------##
	public function publicationCount()
	{
		$member_no=$_POST['member_no'];
		if($member_no!='')
		{
			$inser_array=array(	'member_no'=>$member_no,'created_on'=>date('y-m-d H:i:s'));
			$inser_id=$this->master_model->insertRecord('publication',$inser_array,true);
		}
	}
}
