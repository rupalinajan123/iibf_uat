<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DonationModel extends CI_Model {	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function isUserExist($data)	
	{
		$this->db->select('*');
		$this->db->from('tbl_donation');
		$this->db->where($data);
		$q = $this->db->get();
		//echo $this->db->last_query();//exit;
		$res['result']=$q->result_array();
		$res['rows']=$q->num_rows();
		return $res;
	}
	
}