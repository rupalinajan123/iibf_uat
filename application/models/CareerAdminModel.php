<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CareerAdminModel extends CI_Model {	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function isUserExist($data)	
	{
		$this->db->select('*');
		$this->db->from('administrators u');
		//$this->db->join('role_master r', 'u.roleid = r.roleid','left');	
		$this->db->where($data);
		$q = $this->db->get();
		//echo $this->db->last_query();exit;
		$res['result']=$q->result_array();
		$res['rows']=$q->num_rows();
		return $res;
	}
	public function isDraUserExist($data) {
		$this->db->select('*');
		$this->db->from('dra_admin');
		$this->db->where($data);
		$q = $this->db->get();
		//echo $this->db->last_query();exit;
		$res['result'] = $q->result_array();
		$res['rows'] = $q->num_rows();
		return $res;
	}
	public function isDraInstExist($data) {
		$this->db->select('dra_accerdited_master.*,dra_inst_registration.id'); //added by aayusha 
		$this->db->join('dra_inst_registration ', 'dra_inst_registration.id = dra_accerdited_master.dra_inst_registration_id','left');	//added by aayusha 
		$this->db->where('dra_inst_registration.status',1);//added by aayusha 
		$this->db->from('dra_accerdited_master');
		$this->db->where($data);
		$q = $this->db->get();
		$res['result'] = $q->result_array();
		$res['rows'] = $q->num_rows();
		return $res;
	}
	public function isExamAdminExist($data) {
		$this->db->select('*');
		$this->db->from('exam_admin');
		$this->db->where($data);
		$q = $this->db->get();
		//echo $this->db->last_query();exit;
		$res['result'] = $q->result_array();
		$res['rows'] = $q->num_rows();
		return $res;
	}
	public function isBulkUserExist($data) {
		$this->db->select('*');
		$this->db->from('bulk_admin');
		$this->db->where($data);
		$q = $this->db->get();
		//echo $this->db->last_query();exit;
		$res['result'] = $q->result_array();
		$res['rows'] = $q->num_rows();
		return $res;
	}
	public function isBulkInstExist($data) {
		$this->db->select('*');
		$this->db->from('bulk_accerdited_master');
		$this->db->where($data);
		$q = $this->db->get();
		$res['result'] = $q->result_array();
		$res['rows'] = $q->num_rows();
		return $res;
	}
}