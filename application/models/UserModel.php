<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UserModel extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getUserInfo($UserID)	
	{
		$this->db->select('*');
		$this->db->from('administrators u');
		//$this->db->join('role_master r', 'u.roleid = r.roleid','left');	
		$this->db->where('u.id',$UserID);
		$q = $this->db->get();
		return $q->result();
	}
	
	public function getAccessPermissions($UserID,$Page,$Function){
		$this->db->select('u.id,f.FunctionID,f.Page,f.Function');
		$this->db->from('administrators u');
		$this->db->join('role_master r', 'u.roleid = r.roleid');	
		$this->db->join('tbl_rolepermissions rp', 'r.roleid = rp.roleid');	
		$this->db->join('tbl_functions f', 'f.FunctionID = rp.FunctionID');	
		$where=array(
			'u.id'=>$UserID,
			'f.Page'=>trim($Page),
			'f.Function'=>trim($Function),
			'rp.Active'=>1
		);		
		$this->db->where($where);
		$q = $this->db->get();
		return $q->num_rows();	
	}
		
	//General
	public function changeStatus($tbl,$field,$rowid){			
		switch($tbl){
			case 'Locations':
				$table='tbl_locations';
				$key='LocationID';
				break;	
			case 'Departments':
				$table='tbl_departments';
				$key='DepartmentID';
				break;		
			case 'Positions':
				$table='tbl_positions';
				$key='PositionID';
				break;
			case 'Roles':
				$table='role_master';
				$key='roleid';
				break;	
			case 'Users':
				$table='administrators';
				$key='id';
				break;										
		}					
		$q=$this->db->query("UPDATE $table SET $field = IF($field=1, 0, 1) 
							WHERE $key ='$rowid'");
			
		return $q;		
	}
	//End General
	
	
	public function changePassword($UserID,$Password,$NewPassword){
			$this->db->select('*');
			$this->db->from('administrators u');
			$this->db->where("id='$UserID' AND Password='$Password'");
			$q = $this->db->get();	
			if($q->num_rows()==1){
				$data=array(
					'Password'=>$NewPassword
				);
				return $this->db->update('administrators',$data,"id='$UserID'");	
			}else{
				return false;
			}
	}
	//end change password
	
	public function getRecordCount($table='',$field='',$value='',$select='')
	{
		$flag = 0;
		$result = array();
		/*if($select)
			$this->db->select($select);
		else*/
			$this->db->select('count(*) as tot');
		/*$this->db->join('city_master','state_master.state_code=city_master.city_state_code','left');
		$this->db->join('hotel_master','hotel_master.hotel_city=city_master.city_id','left');*/
		if(strpos($field,','))
		{
			$field = rtrim($field,',');
			$fieldarr = explode(',',$field);
			
			if (in_array('action', $fieldarr)) 
			{
				unset($fieldarr[array_search('action',$fieldarr)]);
			}
			if (in_array('srNo', $fieldarr)) 
			{
				unset($fieldarr[array_search('srNo',$fieldarr)]);
			}
			if(in_array('select', $fieldarr)) 
			{
				unset($fieldarr[array_search('select',$fieldarr)]);
			}
			$j = 0;
			foreach($fieldarr as $fld)
			{
				if($j==0)
				{
					$this->db->group_start();
					$flag = 1;
					$this->db->like($fld,$value);	
				}
				else
					$this->db->or_like($fld,$value);
				$j++;
			}
			if($flag ==1)
			{
				$this->db->group_end();
			}
		}
		else if($field)
		{
			$this->db->where($field,$value);
		}
		//$num = $this->db->count_all_results($table);
		$resArr = $this->db->get($table);
		if($resArr)
		{
			$result = $resArr->result_array();
		}
		/*if($select)
			$num = count($result);
		else*/
			$num = $result[0]["tot"];

		return $num;
	}
	public function getRecords($table='',$select='',$field='', $value='', $sortkey='', $sortval='', $per_page='', $start='')
	{
		$flag = 0;
		if($select)
			$this->db->select($select);
		else
			$this->db->select('*');
		$this->db->from($table);

		if($field != '')
		{
			if( strpos($field,','))
			{
				$field = rtrim($field,',');
				$fieldarr = explode(',',$field);
				if(in_array('action', $fieldarr)) 
				{
					unset($fieldarr[array_search('action',$fieldarr)]);
				}
				if(in_array('srNo', $fieldarr)) 
				{
					unset($fieldarr[array_search('srNo',$fieldarr)]);
				}
				if(in_array('select', $fieldarr)) 
				{
					unset($fieldarr[array_search('select',$fieldarr)]);
				}
				$j = 0;
				foreach($fieldarr as $fld)
				{
					if($j==0)
					{
						$this->db->group_start();
						$flag = 1;
						$this->db->like($fld,$value);	
					}
					else
						$this->db->or_like($fld,$value);
					$j++;
				}
				if($flag ==1)
				{
					$this->db->group_end();
				}
			}
			else
			{
				$this->db->where($field,$value);
			}
		}
		if(!empty($sortkey) && $sortkey!='action' && $sortkey!='srNo' && $sortkey!='select')
			$this->db->order_by($sortkey,$sortval);
		if($per_page)
			$this->db->limit($per_page,$start);
		$res = $this->db->get();
		return $res;
	}
	
	
	// NOT IN USE
	public function getRecordCount1($table='',$field='',$value='',$select='')
	{
		$flag = 0;
		if($select)
			$this->db->select($select);
		else
			$this->db->select('*');
		/*$this->db->join('city_master','state_master.state_code=city_master.city_state_code','left');
		$this->db->join('hotel_master','hotel_master.hotel_city=city_master.city_id','left');*/
		if(strpos($field,','))
		{
			$field = rtrim($field,',');
			$fieldarr = explode(',',$field);
			
			if (in_array('action', $fieldarr)) 
			{
				unset($fieldarr[array_search('action',$fieldarr)]);
			}
			if (in_array('srNo', $fieldarr)) 
			{
				unset($fieldarr[array_search('srNo',$fieldarr)]);
			}
			if(in_array('select', $fieldarr)) 
			{
				unset($fieldarr[array_search('select',$fieldarr)]);
			}
			$j = 0;
			foreach($fieldarr as $fld)
			{
				if($j==0)
				{
					$this->db->group_start();
					$flag = 1;
					$this->db->like($fld,$value);	
				}
				else
					$this->db->or_like($fld,$value);
				$j++;
			}
			if($flag ==1)
			{
				$this->db->group_end();
			}
		}
		else if($field)
		{
			$this->db->where($field,$value);
		}
		$num = $this->db->count_all_results($table);
		return $num;
	}
	
	/* Added for DRA User */
	public function getDraUserInfo($UserID)	{
		$this->db->select('*');
		$this->db->from('dra_admin');
		$this->db->where('id',$UserID);
		$q = $this->db->get();
		return $q->result();
	}
	
	//User
	public function getUsers($UserID=null){
		if($UserID!=null){					
			$this->db->select('*');
			$this->db->from('administrators a');
			$this->db->join('role_master r', 'a.roleid = r.roleid');
			$this->db->where('id',$UserID);
			$q = $this->db->get();				
		}else{
			$this->db->select('*');
			$this->db->from('administrators a');
			$this->db->join('role_master r', 'a.roleid = r.roleid','LEFT');
			//$this->db->where('active',1);
			$this->db->where('deleted',0);
			//$this->db->join('tbl_roles r', "u.RoleID = r.RoleID and u.Deleted='0'");		
			$q = $this->db->get();
			//echo $this->db->last_query();
		}		
		return $q->result();
	}
	
	public function getActiveRoles(){
			$this->db->select('*');
			$this->db->from('role_master');		
			$this->db->where("role_active",1);
			$q = $this->db->get();		
			return $q->result();
	}
	
	public function addUser($data){
		return $this->db->insert('administrators',$data);		
	}
	
	public function updateUser($UserID,$data){
		return $this->db->update('administrators',$data,"id='$UserID'");		
	}
	
	public function deleteUser($rowid){
		$q=$this->db->query("UPDATE administrators SET Deleted = '1' WHERE id ='$rowid'");				
		return $q;	
	}
	
	public function getRoles($roleid=null){
		if($roleid!=null){					
			$this->db->select('*');
			$this->db->from('role_master l');		
			$this->db->where('roleid',$roleid);
			$q = $this->db->get();				
		}else{
			$this->db->select('*');
			$this->db->from('role_master');			
			$q = $this->db->get();	
		}		
		return $q->result();
	}
	
	public function addRole($data){
		return $this->db->insert('role_master',$data);		
	}
	public function updateRole($roleid,$data){
		return $this->db->update('role_master',$data,"roleid='$roleid'");		
	}
	
	/* Added for Bulk User */
	public function getBulkUserInfo($UserID)	{
		$this->db->select('*');
		$this->db->from('bulk_admin');
		$this->db->where('id',$UserID);
		$q = $this->db->get();
		return $q->result();
	}
}
