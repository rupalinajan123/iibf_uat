<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Approvercopy extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
	/*	if($this->session->userdata('kyc_id') == ""){
			redirect('admin/kyc/Login');
		}		*/
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID = $this->session->id;
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('Chk_KYC_session');
		$this->Chk_KYC_session->chk_approver_session();
		$this->load->model('KYC_Log_model'); 
	}
	

public function index()
{
	
}

//Home page 
public function dashboard()
{
	$this->load->view('admin/kyc/Approver/dashboard');
}

public function allocation_type()
{

	// check allocation type
	$new_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));
	//echo $this->db->last_query();exit;
	if(count($new_allocated_member_list) >0 )
	{
		if($new_allocated_member_list[0]['allotted_member_id']=='')
		{
	        redirect(base_url().'admin/kyc/Approvercopy/next_allocation_type');
 		}
	}

	$kyc_start_date=$this->config->item('kyc_start_date');
	$allocated_member_list=$members=array();
	$allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id !='=>'' ));
		//allocated_count
		if(count($allocated_member_list) )	
		{
				
				if(count($allocated_member_list) > 0)
				{
					$data['count']=$allocated_member_list[0]['allocated_count'];
					$arraid=explode(',', $allocated_member_list[0]['allotted_member_id']);
				}
			foreach($arraid as $row)
			{  
							$this->db->join('member_registration','member_registration.regnumber=member_kyc.regnumber','LEFT')	;
								$this->db->where('member_registration.isactive','1');
								$this->db->where('member_registration.isdeleted','0');
								$this->db->where('member_registration.kyc_status','0');
								$members = $this->master_model->getRecords("member_kyc",array('member_kyc.regnumber'=>$row,'member_kyc.field_count'=>'0'),'',array('kyc_id'=>'DESC'),'0','1');
								$members_arr[]  = $members;
			}
			$emptylistmsg=' ';
			$data['emptylistmsg']	=$emptylistmsg;
			$data['result']= call_user_func_array('array_merge', $members_arr);
			$this->load->view('admin/kyc/Approver/approver_edited_list',$data);
		}else
		{
				$this->load->view('admin/kyc/Approver/allocation_type');
		}
}



public function	next_allocation_type()
{
	$this->load->view('admin/kyc/Approver/next_allocation_type');
}


//to show the recommended member list 
public function recommended_list()
{
		$kycstatus=array();
		$data['result']=array();
		$date=date('Y-m-d H:i:s');	
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';		
		$searchBy=$this->input->post('regnumber');
		$searchBy_regtype=$this->input->post('registrationtype');
		if($searchBy!='')
		{
			$this->db->where('member_kyc.regnumber',$searchBy);
		}elseif($searchBy_regtype!='')
		{
			$this->db->where('member_kyc.mem_type',$searchBy_regtype);
		}
		
		$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
		$this->db->where('member_registration.isactive','1');
		$r_list = $this->master_model->getRecords("member_kyc",array('recommended_by'=>$this->session->userdata('kyc_id')),'member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
		mem_dob,mem_sign,mem_proof,mem_photo,mem_associate_inst,member_registration.dateofbirth,member_registration.associatedinstitute',array('kyc_id'=>'DESC'));

//$r_list = $this->master_model->getRecords("member_kyc",array('regnumber'=>$searchBy));

		
		if(count($r_list))
		{

				$data['result'] =  $r_list;	
				$data['status'] =  $kycstatus;	
		}	
		
		$this->load->view('admin/kyc/Approver/recommended_list',$data);
	}
	

	
//to get next 100 allocation ...for  same day
public function approver_next_allocated_list()
{	

	if(isset($_POST['selectby']))
	{
		$allocatedmemberarr=$data_array=$recommendedmemberarr=$member_kyc_lastest_record=$edit_recommended_list=array();
		$type=$_POST['selectby'];	
		$data['count']=0;
		$tilte=$allocated_count=$emptylistmsg='';
		$description=$allotted_member_id='';
		$allocates_arr=$members_arr=$result=$array=$allocated_member_list=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';
		$today=date('Y-m-d H:i:s');
		$per_page =100;
		$last =99;
		$start = 0;
		$list_type='New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		 $check=$kyc_data=array();
		 $date = date('Y-m-d H:i:s');
		 $check = $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));

		if(count($check) > 0)
		{
			if($check[0]['allotted_member_id']=='')
			{
					$kyc_data =$this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'list_type'=>'New' ,'user_type'=>'approver'),'allotted_member_id');
					
					if(count( $kyc_data ) > 0)
					{
						foreach($kyc_data  as $row)
						{
							if($row['allotted_member_id']!='')
							{
								$allocatedmemberarr[]=explode(',',$row['allotted_member_id']);
							}
						}
					}	
					// get all recommended members to todays date
					//$member_kyc = $this->master_model->getRecords("member_kyc",array('DATE(recommended_date)'=>date('Y-m-d'),'kyc_state'=>'1','field_count'=>'0','user_type'=>'approver'));
					//$member_kyc = $this->master_model->getRecords("member_kyc",array('DATE(recommended_date)'=>date('Y-m-d'),'kyc_state'=>'1','user_type'=>'approver'));
					//echo $this->db->last_query();exit;
					//print_r($member_kyc);
					
						$sql='kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
					//$this->db->group_by('member_kyc.regnumber');
					$this->db->where($sql);
					$member_kyc_lastest_record = $this->master_model->getRecords("member_kyc",array('kyc_status'=>'0'),'regnumber,field_count,kyc_state,kyc_id',array('kyc_id'=>'DESC'));
					//echo $this->db->last_query();exit;
					if(count($member_kyc_lastest_record) > 0)	
					{
						foreach($member_kyc_lastest_record  as $row)
						{
							if($row['field_count']==0)
							{
								$edit_recommended_list[]=$row['regnumber'];
							}
						}
					}
					//$sqltoday=date('Y-m-d');
					if(count($edit_recommended_list) > 0)
					{
						$this->db->where_not_in('regnumber',$edit_recommended_list);
					}
					$this->db->group_by('member_kyc.regnumber');
					$member_kyc = $this->master_model->getRecords("member_kyc",array('kyc_state'=>1),'MAX(kyc_id),regnumber');
					
					if(!empty($member_kyc))
					{
						foreach($member_kyc  as $row)
						{
							$recommendedmemberarr[]= $row['regnumber'];
						}	
					}
					$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber');
					$this->db->where('member_registration.kyc_status','0');
					$this->db->where('member_registration.isactive','1');
					$this->db->where('member_registration.registrationtype',$type);	
					$this->db->group_by('member_kyc.regnumber');
					if(count($allocatedmemberarr) > 0)
					{	// get the column data in a single array
						$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
						if(count($data_array) > 0)
						{
							$this->db->where_not_in('member_registration.regnumber',array_map('stripslashes', $data_array));
						}	
					}
					// merge allocated member array with recommended members array
					$data_array=array_merge($data_array,$recommendedmemberarr);	
					if(count($data_array) > 0)
					{
						$this->db->where_not_in('member_registration.regnumber',array_map('stripslashes', $data_array));
					}
						//get next 100 record from  member_registration 
						$members	 = $this->master_model->getRecords("member_kyc",array('field_count'=>'0','kyc_state'=>1),'MAX(kyc_id),member_kyc.regnumber,kyc_id,member_registration.dateofbirth,member_registration.associatedinstitute,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,mem_dob,mem_sign,mem_proof,mem_photo,mem_associate_inst',array('kyc_id'=>'DESC'),'', $per_page);
				
				
			/*$this->db->join('member_kyc', 'member_kyc.regnumber= member_registration.regnumber', 'LEFT');
					$this->db->where('member_registration.kyc_status','0');
					$this->db->where('member_registration.isactive','1');
					$this->db->where('member_registration.registrationtype',$type);	
					$this->db->group_by('member_kyc.regnumber');
					if(count($allocatedmemberarr) > 0)
					{
						// get the column data in a single array
						$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
						if(count($data_array) > 0)
						{
							$this->db->where_not_in('member_registration.regnumber',array_map('stripslashes', $data_array));
						}	
							
					}
						// merge allocated member array with recommended members array
						$data_array=array_merge($data_array,$recommendedmemberarr);	
						if(count($data_array) > 0)
						{
							$this->db->where_not_in('member_registration.regnumber',array_map('stripslashes', $data_array));
						}
						//get next 100 record from  member_registration 
						$members	 = $this->master_model->getRecords("member_registration",array('member_kyc.field_count'=>'0'),'MAX(kyc_id),member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
				mem_dob,mem_sign,mem_proof,mem_photo,mem_associate_inst',array('kyc_id'=>'DESC'),'', $per_page);*/
			//print_r($members);exit;
			//array1
			//echo $this->db->last_query();exit;
				$array_string1=$check[0]['original_allotted_member_id'];	
				$allocates_arr1= explode(',', $array_string1);
				foreach($members as $row)
				{  
					$allocates_arr[].=$row['regnumber'];
				//$reg[] = $row['regnumber'];
				//$regstr .= $row['regnumber'].',';
				}
				$count=count($allocates_arr);
				$allocated_count=$count+ $check[0]['allocated_count'];
				if(count($allocates_arr) >0)
				{
					$allotted_member_id=implode(',',$allocates_arr);
				}
				$new_array=array_merge($allocates_arr1,$allocates_arr);	
				$original_allotted_member_id=implode(',',$new_array);
				//get the  allocated list count
				if($allotted_member_id=='')
				{
					$list_count=$check[0]['allocated_list_count'];
				}else
				{
					$list_count=$check[0]['allocated_list_count']+1;
				}
				$update_data = array
				(	
						'user_type'						=> $this->session->userdata('role'),
						'user_id'							=> $this->session->userdata('kyc_id'),
						'allotted_member_id'		=> $allotted_member_id,
						'original_allotted_member_id'=>$original_allotted_member_id,
						'allocated_count'    		  =>$allocated_count,
						'allocated_list_count'     =>$list_count,
						'date'	               			  => $today,
						'list_type'            		  => $list_type
				);
				$this->db->where('user_id',$this->session->userdata('kyc_id'));
				$this->db->where('list_type','New');
				$this->master_model->updateRecord('admin_kyc_users',$update_data,array('DATE(date)'=>date('Y-m-d')));
				//log activity 
				$tilte='Approver got next  New member list allocation ';
				$user_id=$this->session->userdata('kyc_id');
				$this->KYC_Log_model->create_log($tilte,$user_id,'',serialize ($update_data));
	}
				$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id !='=>'' ));

		//allocated_count
		if(count($allocated_member_list) > 0)
		{
			$data['count']=$allocated_member_list[0]['allocated_count'];
			$arraid=explode(',', $allocated_member_list[0]['allotted_member_id']);
			
			//$data['result'] = $members;
			//$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
			if(count($arraid) > 0)
			{
				if($searchBy!='' || $searchBy_regtype!='' )
				{
						if($searchBy!='' && $searchBy_regtype!='' )
						{
							$this->db->where('regnumber',$searchBy);
							$this->db->where('registrationtype',$searchBy_regtype);
							$members = $this->master_model->getRecords("member_registration");
						}
						///search by registration number
						else if($searchBy!='')
						{
							$arrstr=explode(',',$allocated_member_list[0]['allotted_member_id']);
							$this->db->where_in('regnumber',array_map('stripslashes',$arrstr));
							$this->db->where('regnumber',$searchBy);
							$members = $this->master_model->getRecords("member_registration");
							//$row=$searchBy;
						}
						///search by registration type
						else if($searchBy_regtype!='')
						{
							$arrstr=explode(',',$allocated_member_list[0]['allotted_member_id']);
							$this->db->where_in('regnumber',array_map('stripslashes',$arrstr));
							$this->db->where('registrationtype',$searchBy_regtype);
							$members = $this->master_model->getRecords("member_registration");
						}
						if(count($members ) > 0)
						{
							foreach($members as $row)
							{
								$members_arr[][]= $row;
							}
						}
				}
				else
				{
							$this->db->where('member_registration.registrationtype',$type);						
					//default allocation list for 100 member
					foreach($arraid as $row)
					{  
							
							$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
							$this->db->where('member_registration.isactive','1');
							$members	 = $this->master_model->getRecords("member_kyc",array('member_kyc.regnumber'=>$row,'field_count'=>'0','kyc_state'=>1),'member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
				mem_dob,mem_sign,mem_proof,mem_photo,mem_associate_inst',array('kyc_id'=>'DESC'),'', 1);
						//	echo $this->db->last_query();exit;
							$members_arr[] = $members;
					}
				}
			}
			

		/*	if(count($members_arr) > 0)
			{
			
				foreach($members_arr as $k=>$v)
				{
						$result[]=$v;
				}
			}*/
				$data['result']= call_user_func_array('array_merge', $members_arr);
			//$data['result']=$result;

			//$data['reg_no'] = $members[0]['regnumber'];
			//$id=$data['reg_no'];
		}
		$total_row = 100;
		$url = base_url()."admin/kyc/Approvercopy/approver_edited_list/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		
		if(($start+$per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		
		if($total_row)
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries'; 
		else
			$data['info'] = 'Showing 0 to '.$end_of_total.' of '.$total_row.' entries'; 
		
		$data['index'] = $start + 1;
		
		 $emptylistmsg=' No records available...!!<br />
	   <a href='.base_url().'admin/kyc/Approvercopy/next_allocation_type/>Back</a>';
		$data['emptylistmsg']	=$emptylistmsg;  
		
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));	   
		$this->load->view('admin/kyc/Approver/approver_edited_list',$data);

			}else
			{
				redirect(base_url().'admin/kyc/Approvercopy/approver_edited_list');
			}
			
	}
}

//to show the kyc member list & 100 allocation  

public function approver_edited_list()
{  
		
		$tilte=$type='';
		$description=$emptylistmsg='';
		$allocates_arr=$members_arr=$result=$array=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';
		$today=date('Y-m-d H:i:s');
		$per_page =100;
		$last =99;
		$start = 0;
		$list_type='New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		if($this->input->post('regnumber')!='')
		{	
			$searchBy=$this->input->post('regnumber');
		}
		if($this->input->post('registrationtype')!='')
		{	
			$searchBy_regtype=$this->input->post('registrationtype');
		}

		$registrationtype = '';
		$data['reg_no'] = ' ';
		
		if($page != 0)
		{
			$start = $page-1;
		}
		$allocates=array();
//get  all  user loging today 
	if(isset($_POST['selectby']))
	{
		
		$type=$_POST['selectby'];
		$kyc_data = $this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'user_type'=>'approver'),'original_allotted_member_id');

		$allocatedmemberarr= array();
		if(count($kyc_data) > 0)
		{
			foreach($kyc_data as $row)
			{
				$allocatedmemberarr[]=explode(',',$row['original_allotted_member_id']);
			}
		}
		
		if(count($allocatedmemberarr) > 0)
		{
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				if(count($data_array) > 0)
				{
					$this->db->where_not_in('member_kyc.regnumber',array_map('stripslashes', $data_array));
				}
					
		}
				$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
				$this->db->where('member_registration.kyc_status','0');
				$this->db->where('member_kyc.kyc_status','0');
				$this->db->where('member_registration.isactive','1');
				$this->db->where('member_registration.registrationtype',$type);	
				$this->db->group_by('member_kyc.regnumber');
				$r_list = $this->master_model->getRecords("member_kyc",array('field_count'=>0,'kyc_state'=>1),'MAX(kyc_id),member_kyc.regnumber,kyc_id,namesub,dateofbirth,associatedinstitute,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
				mem_dob,mem_sign,mem_proof,mem_photo,isactive,mem_associate_inst,field_count',array('kyc_id'=>'DESC'),$start ,$per_page);
				//echo $this->db->last_query();exit;
				//echo '<pre>';
				//print_r($r_list);exit;
				//echo $this->db->last_query();exit;
			
				$today = date("Y-m-d H:i:s");	
				$row_count = $this->master_model->getRecordCount("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));
		
					//echo $this->db->last_query();exit;
			if($row_count==0)
			{
				$regstr='';
				foreach($r_list  as $row)
				{  
					$allocates_arr[]=$row['regnumber'];
				}
				if(count($allocates_arr) >0)
				{
					$regstr=implode(',',$allocates_arr);
				}
					//print_r($regstr);exit;
				if($regstr!='')
				{
					
						$insert_data = array
							   (	
									'user_type'			=> $this->session->userdata('role'),
									'user_id'				=> $this->session->userdata('kyc_id'),
									'allotted_member_id'	=> $regstr,
									'original_allotted_member_id'	=> $regstr,
									'allocated_count'     =>count($allocates_arr),
									'allocated_list_count'     =>'1',
									'date'	                => $today,
									'list_type'             => 'New'
							  );
							$this->master_model->insertRecord('admin_kyc_users',$insert_data);
							//log activity 
							$tilte='Approver  KYC  member list allocation';
							$description ='Approver has allocated '. count($allocates_arr).' member';
							$user_id=$this->session->userdata('kyc_id');
							$this->KYC_Log_model->create_log($tilte,$user_id,'','',$description);
				}
		}
}
			$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id !='=>'' ));	
	
		   if(count($allocated_member_list) > 0)
		   {
			  $data['count']=$allocated_member_list[0]['allocated_count'];
			 }
			 else
			 {
			  $data['count']=0;
			  }
		    
		if(count($allocated_member_list) > 0)
		{
			$arraid=explode(',', $allocated_member_list[0]['allotted_member_id']);

			//$data['result'] = $members;
			//$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
			if(count($arraid) > 0)
			{
				if($searchBy!='' || $searchBy_regtype!='' )
				{
						if($searchBy!='' && $searchBy_regtype!='' )
						{
							$this->db->where('regnumber',$searchBy);
							$this->db->where('registrationtype',$searchBy_regtype);
							$members = $this->master_model->getRecords("member_registration");
						}
						///search by registration number
						else if($searchBy!='')
						{
							$arrstr=explode(',',$allocated_member_list[0]['allotted_member_id']);
							$this->db->where_in('regnumber',array_map('stripslashes',$arrstr));
							$this->db->where('regnumber',$searchBy);
							$members = $this->master_model->getRecords("member_registration");
							//$row=$searchBy;
						}
						///search by registration type
						else if($searchBy_regtype!='')
						{
							$arrstr=explode(',',$allocated_member_list[0]['allotted_member_id']);
							$this->db->where_in('regnumber',array_map('stripslashes',$arrstr));
							$this->db->where('registrationtype',$searchBy_regtype);
							$members = $this->master_model->getRecords("member_registration");
						}
						if(count($members ) > 0)
						{
							foreach($members as $row)
							{
								$members_arr[][]= $row;
							}
						}
				}
				else
				{

					//default allocation list for 100 member
					foreach($arraid as $row)
					{  		
							/*$this->db->join('member_kyc','member_kyc.regnumber=member_registration.regnumber','LEFT')	;
							$this->db->group_by('member_kyc.regnumber');
							$this->db->order_by('kyc_id','DESC');
							$this->db->where('member_kyc.field_count','0');
							$members = $this->master_model->getRecords("member_registration",array('member_registration.regnumber'=>$row,'isactive'=>'1','isdeleted'=>0));*/
							//$members = $this->master_model->getRecords("member_registration",array('member_registration.regnumber'=>$row));
							$this->db->join('member_registration','member_registration.regnumber=member_kyc.regnumber','LEFT')	;
							$this->db->where('member_registration.isactive','1');
							$this->db->where('member_registration.isdeleted','0');
							$this->db->where('member_kyc.kyc_status','0');
							$this->db->where('member_registration.kyc_status','0');
							$this->db->where('member_registration.registrationtype',$type);	
							$members = $this->master_model->getRecords("member_kyc",array('member_kyc.regnumber'=>$row,'member_kyc.field_count'=>'0','kyc_state'=>1),'',array('kyc_id'=>'DESC'),'0','1');
							$members_arr[]  = $members;
				
					}
				}
			}
		
		$data['result']= call_user_func_array('array_merge', $members_arr);
		//print_r($data['result']);
			//exit;	
			
			/*if(count($members_arr) > 0)
			{
			
				foreach($members_arr as $k=>$v)
				{
						$result[]=$v;
				}
			}*/
			
				
			//$data['result']=$result;
			//$data['reg_no'] = $members[0]['regnumber'];
			//$id=$data['reg_no'];
		}
		$total_row = 100;
		$url = base_url()."admin/kyc/Approvercopy/approver_edited_list/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		
		if(($start+$per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		
		if($total_row)
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries'; 
		else
			$data['info'] = 'Showing 0 to '.$end_of_total.' of '.$total_row.' entries'; 
		
		$data['index'] = $start + 1;
		//$allocatedmember=array();
		//$allocatedmember= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id '=>'' ));	
		//$data['result']= call_user_func_array('array_merge', $members_arr);
		$emptylistmsg=' No records available...!!<br />
		<a href='.base_url().'admin/kyc/Approvercopy/allocation_type/>Back</a>';
		$data['emptylistmsg']	=$emptylistmsg;  
		//		redirect(base_url().'admin/kyc/Approver/approver_edited_list');	
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));
		$this->load->view('admin/kyc/Approver/approver_edited_list',$data);
		
}


//To show the approver recommend screen
public function approver_edited_member($regnumber=NULL)
{ 
//$member1=array();
 //	$member1 = $this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit'));	
 //print_r($member1);exit;
 
	//	echo $regnumber;exit;
	if($regnumber)
	{	
		$state=$next_id=$success=$error='';
		$data['result'] = $name=$update_data=$old_user_data=$member_kyc_lastest_record=$sql=array();
		$new_arrayid= array();
		$today=$date=date('Y-m-d H:i:s');
		$registrationtype = '';
		$data['reg_no'] =' ';
		$field_count=0;
		//recommendation submit
		if(isset($_POST['btnSubmitRecmd']))
		{
				$select = 'regnumber,registrationtype,email';
				$data = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1','kyc_status'=>'0'),$select);
					if(isset($_POST['cbox']))
					{
						$name = $this->input->post('cbox');
					}
					$regnumber=$data[0]['regnumber'];
					// optional
					// echo "You chose the following color(s): <br>";
					$check_arr = array();
					if(count($name ) > 0)
					{
						foreach ($name as $cbox)
						{
							//echo $cbox."<br />";
							$check_arr[] = $cbox;
						}
					}
					
				$msg='Edit your profile as :-';
					
					if(count($check_arr) > 0)
					{
						if(in_array('name_checkbox',$check_arr))
						{
							$name_checkbox = '1';
							
						}
						else
						{
					        $name_checkbox = '0';
							$field_count++;
							$update_data[]='Name';
							$msg.='Name,';
						}
						if(in_array('dob_checkbox',$check_arr))
						{
							$dob_checkbox = '1';
						}
						else
						{
							
							$dob_checkbox = '0';
							$field_count++;
							$update_data[].='DOB';
							$msg.='Date of Birth ,';
						}
						if($data[0]['registrationtype']=='DB' || $data[0]['registrationtype']=='NM')
						{
								if(in_array('emp_checkbox',$check_arr))
								{
									$emp_checkbox = '1';
								}else
								{
									$emp_checkbox = '1';
								}
						}elseif($data[0]['registrationtype']=='O' || $data[0]['registrationtype']=='F' || $data[0]['registrationtype']=='A')
						{
								if(in_array('emp_checkbox',$check_arr))
								{
									$emp_checkbox = '1';
								}
								else
								{
									$emp_checkbox = '0';
									$field_count++;
									$update_data[].='Employer';
									$msg.='Employer,';
								}
						}
						if(in_array('photo_checkbox',$check_arr))
						{
							$photo_checkbox = '1';
						}
						else
						{
							$photo_checkbox = '0';
							$field_count++;
							$update_data[].='Photo';
							$msg.='Photo,';
						}
						if(in_array('sign_checkbox',$check_arr))
						{
							$sign_checkbox = '1';
						}
						else
						{
							$sign_checkbox = '0';
							$field_count++;
							$update_data[].='Sign';
							$msg.='Sign,';
						}
						
						if(in_array('idprf_checkbox',$check_arr))
						{
							$idprf_checkbox = '1';
						}
						else
						{
						    $idprf_checkbox = '0';
							$field_count++;
							$update_data[].='Id-proof';
							$msg.='Id-proof';
						}
					}
					else
					{
						$name_checkbox = '0';$msg.='Name,';$field_count++;$update_data[].='Name';
						
						$dob_checkbox = '0';$msg.='Date of Birth ,';$field_count++;$update_data[].='DOB';
						
						$emp_checkbox = '0';$msg.='Employer,';$field_count++;$update_data[].='Employer';
						
						$photo_checkbox = '0';$msg.='Photo,';$field_count++;$update_data[].='Photo';
						
						$sign_checkbox = '0';$msg.='Sign,';$field_count++;$update_data[].='Sign';
						
						$idprf_checkbox = '0';$msg.='Id-proof';$field_count++;$update_data[].='Id-proof';
						
					}
				
					$email=$data[0]['email'];
					
		
					if($name_checkbox =='1' && $dob_checkbox=='1' && $emp_checkbox=='1' && $photo_checkbox=='1' &&  $sign_checkbox=='1' && $idprf_checkbox=='1' )
					{
							//$error='Please  unchecked atleast one checkbox!!';
							$this->session->set_flashdata('error','Please  uncheck atleast one checkbox!!');
						}
					else
					{
						$select='namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto';
					
						$old_user_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1'),$select);
						$userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));	
						
						$insert_data = array
								   (	
										'regnumber'			    => $data[0]['regnumber'],
										'mem_type'				=> $data[0]['registrationtype'],
										'mem_name'			    => $name_checkbox,
										//'email_address'			=> $data[0]['email'],
										'mem_dob'			    => $dob_checkbox,
										'mem_associate_inst'	=> $emp_checkbox ,
										'mem_photo'				=> $photo_checkbox,
										'mem_sign'				=> $sign_checkbox,
										'mem_proof'				=> $idprf_checkbox,
										'field_count'				=> $field_count,
										'old_data'					=>serialize($old_user_data),
										'kyc_status'			=> '0',
										'kyc_state'				=> '1',
										'recommended_by'         =>$this->session->userdata('kyc_id'),
										'user_type'					=>$this->session->userdata('role'),
										'recommended_date'		=>$today,
										'record_source'			=>'New'
										);
										
								//insert the record and get latest  kyc_id					
								$last_insterid = $this->master_model->insertRecord('member_kyc',$insert_data,true);
								if($data[0]['registrationtype']=='O' || $data[0]['registrationtype']=='F' || $data[0]['registrationtype']=='A')
								{
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$userpass= $aes->decrypt($userdata[0]['usrpassword']);
									
									$username=$userdata[0]['namesub'].' '.$userdata[0]['firstname'].' '.$userdata[0]['middlename'].' '.$userdata[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									
									$msg=implode(',',$update_data);
									
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommendation_email_O'));
									$newstring1 = str_replace("#REGNUMBER#", "".$regnumber."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#PASSWORD#", "".$userpass."",  $newstring2);
									$newstring4 = str_replace("#MSG#", "".$msg."",  $newstring3);
									$final_str = str_replace("#CLICKHERE#", '<a href="'.base_url().'" style="color:#F00">Click here to Login </a>',$newstring4);
									
									//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
					
									$info_arr=array(
								//	'to'=> "kyciibf@gmail.com",
									'to'=> $userdata[0]['email'],
									'from'=> $emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str
									);
								}
								elseif($data[0]['registrationtype']=='DB' || $data[0]['registrationtype']=='NM')
								{
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$userpass= $aes->decrypt($userdata[0]['usrpassword']);
									
									$username=$userdata[0]['namesub'].' '.$userdata[0]['firstname'].' '.$userdata[0]['middlename'].' '.$userdata[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									
									$msg=implode(',',$update_data);
									
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommendation_email_NM'));
									$newstring1 = str_replace("#REGNUMBER#", "".$regnumber."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#PASSWORD#", "".$userpass."",  $newstring2);
									$newstring4 = str_replace("#MSG#", "".$msg."",  $newstring3);
									$final_str = str_replace("#CLICKHERE#", '<a href="'.base_url().'nonmem/" style="color:#F00">Click here to Login </a>',$newstring4);
								
								//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
				
								$info_arr=array(
								//'to'=> "kyciibf@gmail.com",
							'to'=> $userdata[0]['email'],
								'from'=> $emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
								
								}
							//email send on recommend...
							 //email to user
							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);
							//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
							$userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber),'reg_no');	
			
							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							
						
							if($this->Emailsending->mailsend($info_arr))
							{
								
										 $this->session->set_flashdata('success','KYC recommend for '.$regnumber.'  (previous record) & Email sent successfully !!');
									//$success='KYC  recommend for the candidate & Email sent successfully !!';
									//log activity
									// get recommended fields data from member registration -
									$select='namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto';
									$old_data=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'),$select);	
									$log_desc['old_data'] = $old_data;
									$log_desc['inserted_data'] = $insert_data;
									$description = serialize($log_desc);
									$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'),$last_insterid,$regnumber, $description);
					
									//email log
								    $this->KYC_Log_model->email_log($last_insterid ,$this->session->userdata('kyc_id'),'0','' ,$regnumber,serialize($info_arr),$today,$this->session->userdata('role'));
									// make recommended fields empty  -
									if(in_array('Name',$update_data) )
								   { 
										/*$updatedata=array(
																	'namesub'=>'',
																	'firstname'=>'',
																	'middlename'=>'',
																	'lastname'=>''
																 );*/
																 
										$updatedata['namesub']=$updatedata['firstname']=$updatedata['middlename']=$updatedata['lastname']='';
										$this->db->where('isactive','1');
										$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
								   }if(in_array('DOB',$update_data) )
								   {
									   
									   $updatedata['dateofbirth']='0000-00-00';
														
										//$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
									   
								   }if (in_array('Employer',$update_data))
								   {
										$updatedata['associatedinstitute']='';
										//$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
										
									
								   }if ( in_array('Photo',$update_data))
								   {
									   $updatedata['scannedphoto']='';
									   if($old_data[0]['scannedphoto']=='')
									  {
										$oldfilepath= get_img_name($regnumber,'p');
									    $file_path   = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_'.$userdata[0]['reg_no'].'.jpg';
									   @ rename($oldfilepath,$file_path.'/'.$photo_file);
									  }
										//$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
								   } 
								   if ( in_array('Sign',$update_data))
								   {
									  $updatedata['scannedsignaturephoto']='';  
									   if($old_data[0]['scannedsignaturephoto']=='')
									  {
										$oldfilepath= get_img_name($regnumber,'s');
									    $file_path   = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_'.$userdata[0]['reg_no'].'.jpg';
									   @ rename($oldfilepath,$file_path.'/'.$photo_file);
									  }
										//$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
								   } if ( in_array('Id-proof',$update_data))
								   {
									 $updatedata['idproofphoto']='';  
									 if($old_data[0]['idproofphoto']=='')
									  {
											$oldfilepath= get_img_name($regnumber,'pr');
											$file_path   = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_'.$userdata[0]['reg_no'].'.jpg';
										   @ rename($oldfilepath,$file_path.'/'.$photo_file);
									  }
										//$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
								   }
									if(!empty($updatedata))
									{
										$this->db->where('isactive','1');
										$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
									}
									
									$member = $this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id')));	
									$arrayid=explode(',', $member[0]['allotted_member_id']);
									$index= array_search($regnumber,$arrayid,true);
									
									//get next record
									$currentid	=	 $index;
									
									$nextid=$currentid+1;
									if(array_key_exists($nextid, $arrayid))
									{
										$next_id = $arrayid[$nextid];
									}
									else
									{
										$next_id = $arrayid[0];
									}
									//end of next record
									//unset the  current id index
									unset($arrayid[$index]);
									if(count($arrayid) > 0)
									{
										foreach($arrayid as $row)
										{
											$new_arrayid []=	$row;
										}
									}
									if(count($new_arrayid) > 0)
									{
										$regstr=implode(',',$new_arrayid);
									}
									else
									{
										$regstr='';
										$next_id='';	
									}
		
									$update_data=array('allotted_member_id'=>$regstr);
									$this->db->where('DATE(date)',date('Y-m-d'));
									$this->db->where('list_type','New');
									$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id')));
									redirect(base_url().'admin/kyc/Approvercopy/approver_edited_member/'.$next_id);
						
							}
			}
		}
		//kyc submit	
		if(isset($_POST['btnSubmitkyc']))
		{		
			if(isset($_POST['cbox']))
					{
						$name = $this->input->post('cbox');
					}
					//$regnumber=$data[0]['regnumber'];
					// optional
					// echo "You chose the following color(s): <br>";
					$check_arr = array();
					if(count($name ) > 0)
				  {
						foreach ($name as $cbox)
						{
						//echo $cbox."<br />";
						$check_arr[] = $cbox;
						}
				  }
				  
				$regnumber=$this->input->post('regnumber');
				$this->db->where('regnumber',$regnumber);
				$this->db->where('isactive','1');
				$member_regtype = $this->master_model->getRecords('member_registration','','registrationtype');
				//Kyc complet for DB and NM  member only 5 fileds are consider 			
				if($member_regtype[0]['registrationtype']=='NM' || $member_regtype[0]['registrationtype']=='DB')
				{
								
						if(in_array('name_checkbox',$check_arr) && in_array('dob_checkbox',$check_arr)   && in_array('photo_checkbox',$check_arr)  && in_array('sign_checkbox',$check_arr)  && in_array('idprf_checkbox',$check_arr))
						{
                          					
								$new_arrayid=$members =	$old_user_data =array();
								$status='0';
								$state='1';
								$date = date('Y-m-d H:i:s');
								//$this->db->where('recommended_date',$date);
								$this->db->where('regnumber',$regnumber);
								$member_kyc_details = $this->master_model->getRecords('member_kyc');
								if(isset($_POST['cbox']))
								{
									$name = $this->input->post('cbox');
								}
			//					$regnumber=$data[0]['regnumber'];
								// optional
								// echo "You chose the following color(s): <br>";
									$check_arr = array();
									if(count($name ) > 0)
								   {
									
										foreach ($name as $cbox)
										{
										//echo $cbox."<br />";
										$check_arr[] = $cbox;
										}
								   }
									$msg='Edit your profile as :-';
									if(in_array('name_checkbox',$check_arr))
									{
										$name_checkbox = '1';
									}
									else
									{
										$name_checkbox = '0';
										$msg.='Name,';
									}
									if(in_array('dob_checkbox',$check_arr))
									{
										$dob_checkbox = '1';
									}
									else
									{
										$dob_checkbox = '0';
										$msg.='Date of Birth ,';
									}
									if(in_array('photo_checkbox',$check_arr))
									{
										$photo_checkbox = '1';
									}
									else
									{
										$photo_checkbox = '0';
										$msg.='Photo,';
									}
									if(in_array('sign_checkbox',$check_arr))
									{
										$sign_checkbox = '1';
									}
									else
									{
										$sign_checkbox = '0';
										$msg.='Sign,';
									}
									
									if(in_array('idprf_checkbox',$check_arr))
									{
										$idprf_checkbox = '1';
									}
									else
									{
										$idprf_checkbox = '0';
										$msg.='Id-proof';
									}
									
									//get the old_data
									$select='namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto';
									$old_user_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1'),$select);
									$userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));	
									//$email=$data[0]['email'];
									$update_data = array
									(	
									'mem_name'			    => $name_checkbox,
									//'email_address'			=> $data[0]['email'],
									'mem_dob'			    => $dob_checkbox,
									'mem_associate_inst'	=> '0',
									'mem_photo'				=> $photo_checkbox,
									'mem_sign'				=> $sign_checkbox,
									'mem_proof'				=> $idprf_checkbox,
									'old_data'					=>serialize($old_user_data),
									'kyc_status'			=> '1',
									'kyc_state'			=> 3,
									'approved_by'           =>$this->session->userdata('kyc_id'),
									'approved_date'		    =>$today,
									'record_source'			=>'Edit'
									);	
								
									//	$memberdata=$this->master_model->getRecords("member_kyc",array('regnumber'=>$regnumber),'approved_date',array('kyc_id'=>'DESC'),'0','1');
		///print_r(date('Y-m-d',strtotime($memberdata[0]['approved_date'])));exit;
									//if(date('Y-m-d',$memberdata[0]['approved_date']))
									//query to update the latest record in member kyc
									$sql='kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
									$this->db->where($sql);
									$member_kyc_lastest_record = $this->master_model->getRecords("member_kyc",array('regnumber'=>$regnumber,'kyc_status'=>'0'),'regnumber,kyc_state,kyc_id',array('kyc_id'=>'DESC'));
								//print_r($member_kyc_lastest_record );exit;
									
									$this->db->where('isactive','1');
									$this->master_model->updateRecord('member_registration',array('kyc_status'=>'1'),array('regnumber'=>$regnumber));
									//echo $this->db->last->query();exit;
									$this->db->where('kyc_id',$member_kyc_lastest_record[0]['kyc_id']);
									$this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber']));	
								
				
									//email send on KYC complete  for DB & NM
								 $last_insterid=$this->master_model->getRecords("member_kyc",array('regnumber'=>$regnumber,'kyc_status'=>'1','kyc_state'=>3,'approved_by' =>$this->session->userdata('kyc_id')),'kyc_id',array('kyc_id'=>'DESC'),'0','1');
	//echo $this->db->last->query();exit;
	// print_r($last_insterid);exit;
								$nomsg='';
								/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_KYC_complete_email'));
								$final_str = str_replace("#MSG#", "".$nomsg."",  $emailerstr[0]['emailer_text']);
							//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
			
								$info_arr=array(
								'to'=> "kyciibf@gmail.com",
								'from'=> $emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);*/
								
									$username=$userdata[0]['namesub'].' '.$userdata[0]['firstname'].' '.$userdata[0]['middlename'].' '.$userdata[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$msg=implode(',',$update_data);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'KYC_completion_email_to_NM'));
									//echo $emailerstr[0]['emailer_text'];exit;
									$newstring1 = str_replace("#REGNUMBER#", "".$regnumber."",  $emailerstr[0]['emailer_text']);
									$final_str = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
								
								
									//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
				
									$info_arr=array(
								//	'to'=> "kyciibf@gmail.com",
								'to'=> $userdata[0]['email'],
									'from'=> $emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str
									);
								
								
								if($this->Emailsending->mailsend($info_arr))
								{
										 
									$this->session->set_flashdata('success','KYC Complete for '.$regnumber.'  (previous record)   & Email sent successfully !!');
								//$success='KYC Completed for the candidate & Email sent successfully !!';
									//log activity 
									$regnumber= $regnumber;
									$user_id=$this->session->userdata('kyc_id');
									$tilte='Member KYC completed';
									$description =''.$regnumber.' has been approve by '. $this->session->userdata('role').'';
								
									$this->KYC_Log_model->create_log($tilte,$user_id, $last_insterid[0]['kyc_id'],$regnumber,$description);	
									//$this->session->set_flashdata('success','kyc completed Successfully  !!');
									//redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
										//email log
									$this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'],$this->session->userdata('kyc_id'),'1','',$regnumber,serialize($info_arr),$today,$this->session->userdata('role'));
								}	
									//rebulide the array 
									$member = $this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id')));	
								
									$arrayid=explode(',', $member[0]['allotted_member_id']);
									$index= array_search($regnumber,$arrayid,true);
									
									//get next record
									$currentid	=	 $index;
									$nextid=$currentid+1;
									
									if(array_key_exists($nextid, $arrayid))
									{
										$next_id = $arrayid[$nextid];
									}
									else
									{
										$next_id = $arrayid[0];
									}
								//end of next record
									unset($arrayid[$index]);
									if(count($arrayid) > 0)
									{
										foreach($arrayid as $row)
										{
											$new_arrayid []=	$row;
										}
									}
									if(count($new_arrayid) > 0)
									{
										$regstr=implode(',',$new_arrayid);
									}
									else
									{
										$regstr='';
										$next_id='';	
									}
									$update_data=array('allotted_member_id'=>$regstr);
									$this->db->where('DATE(date)',date('Y-m-d'));
									$this->db->where('list_type','New');
									$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id')));
									redirect(base_url().'admin/kyc/Approvercopy/approver_edited_member/'.$next_id);
					}
						else
						{
							//$error='Select all check box to complete the Kyc !!';
							$this->session->set_flashdata('error','Select all check box to complete the Kyc !!');
							//$error='Select all check box to complete the Kyc !!';
							//redirect(base_url().'admin/kyc/Approver/approver_edited_member/'.$regnumber);
						}
				}//Kyc complet forO,A,F  member only 5 fileds are consider 	
				elseif($member_regtype[0]['registrationtype']=='O' || $member_regtype[0]['registrationtype']=='A' || $member_regtype[0]['registrationtype']=='F')
				{       	
					if(in_array('name_checkbox',$check_arr) && in_array('dob_checkbox',$check_arr)  && in_array('emp_checkbox',$check_arr)  && in_array('photo_checkbox',$check_arr)  && in_array('sign_checkbox',$check_arr)  && in_array('idprf_checkbox',$check_arr))
						{
                          					
								$new_arrayid=$members =array();
								$status='0';
								$state='1';
								$date = date("Y-m-d H:i:s");
								//$this->db->where('recommended_date',$date);
								$this->db->where('regnumber',$regnumber);
					
								$member_kyc_details = $this->master_model->getRecords('member_kyc');
								if(isset($_POST['cbox']))
								{
									$name = $this->input->post('cbox');
								}
			//					$regnumber=$data[0]['regnumber'];
								// optional
								// echo "You chose the following color(s): <br>";
									$check_arr = array();
									if(count($name ) > 0)
								   {
									
										foreach ($name as $cbox)
										{
										//echo $cbox."<br />";
										$check_arr[] = $cbox;
										}
								   }
									$msg='Edit your profile as :-';
									if(in_array('name_checkbox',$check_arr))
									{
										$name_checkbox = '1';
									}
									else
									{
										$name_checkbox = '0';
										$msg.='Name,';
									}
									if(in_array('dob_checkbox',$check_arr))
									{
										$dob_checkbox = '1';
									}
									else
									{
										$dob_checkbox = '0';
										$msg.='Date of Birth ,';
									}
									if(in_array('emp_checkbox',$check_arr))
									{
										$emp_checkbox = '1';
									}
									else
									{
										$emp_checkbox = '0';
										$msg.='Associate institude ,';
									}
									if(in_array('photo_checkbox',$check_arr))
									{
										$photo_checkbox = '1';
									}
									else
									{
										$photo_checkbox = '0';
										$msg.='Photo,';
									}
									if(in_array('sign_checkbox',$check_arr))
									{
										$sign_checkbox = '1';
									}
									else
									{
										$sign_checkbox = '0';
										$msg.='Sign,';
									}
									
									if(in_array('idprf_checkbox',$check_arr))
									{
										$idprf_checkbox = '1';
									}
									else
									{
										$idprf_checkbox = '0';
										$msg.='Id-proof';
									}
									
									//$email=$data[0]['email'];
									$update_data = array
									(	
									'mem_name'			    => $name_checkbox,
									//'email_address'			=> $data[0]['email'],
									'mem_dob'			    => $dob_checkbox,
									'mem_associate_inst'	=> $emp_checkbox,
									'mem_photo'				=> $photo_checkbox,
									'mem_sign'				=> $sign_checkbox,
									'mem_proof'				=> $idprf_checkbox,
									'kyc_status'			=> '1',
									'kyc_state'			=> 3,
									'approved_by'           =>$this->session->userdata('kyc_id'),
									'approved_date'		    =>$today,
									'record_source'			=>'Edit'
									);	
			
									//query to update the latest record of the regnumber
									$sql='kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
									$this->db->where($sql);
									$member_kyc_lastest_record = $this->master_model->getRecords("member_kyc",array('regnumber'=>$regnumber,'kyc_status'=>'0'),'regnumber,kyc_state,kyc_id',array('kyc_id'=>'DESC'));
								//print_r($member_kyc_lastest_record );exit;
									
									$this->db->where('isactive','1');
									$this->master_model->updateRecord('member_registration',array('kyc_status'=>'1'),array('regnumber'=>$regnumber));
									
									$this->db->where('kyc_id',$member_kyc_lastest_record[0]['kyc_id']);
									$this->master_model->updateRecord('member_kyc',$update_data,array('regnumber'=>$member_kyc_lastest_record[0]['regnumber']));	
				    			   
								    $last_insterid=$this->master_model->getRecords("member_kyc",array('regnumber'=>$regnumber,'kyc_status'=>'1','kyc_state'=>3,'approved_by' =>$this->session->userdata('kyc_id')),'kyc_id',array('kyc_id'=>'DESC'),'0','1');
			
			//print_r($last_insterid[0]['kyc_id']);exit;
									$nomsg='';
									$userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));
									$username=$userdata[0]['namesub'].' '.$userdata[0]['firstname'].' '.$userdata[0]['middlename'].' '.$userdata[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$msg=implode(',',$update_data);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'KYC_completion_email_to_O'));
									//echo $emailerstr[0]['emailer_text'];exit;
									$newstring1 = str_replace("#REGNUMBER#", "".$regnumber."",  $emailerstr[0]['emailer_text']);
									$final_str = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
								
								
									//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
				
									$info_arr=array(
									//'to'=> "kyciibf@gmail.com",
									'to'=> $userdata[0]['email'],
									'from'=> $emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str
									);
									
								/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'approver_KYC_complete_email'));
								$final_str = str_replace("#MSG#", "".$nomsg."",  $emailerstr[0]['emailer_text']);
								//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
				
								$info_arr=array(
								'to'=> "kyciibf@gmail.com",
								'from'=> $emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);*/
									
						/*echo '<pre>';
						print_r($info_arr);
						exit;*/
								if($this->Emailsending->mailsend($info_arr))
								{
											$this->session->set_flashdata('success','KYC Complete for '.$regnumber.'  (previous record)  candidate & Email sent successfully !!');
										//$success='KYC Completed for the candidate & Email sent successfully !!';
								//log activity 
									$regnumber= $regnumber;
									$user_id=$this->session->userdata('kyc_id');
									$tilte='Member KYC completed';
									$description =''.$regnumber.' has been approve by '. $this->session->userdata('role').'';
									$this->KYC_Log_model->create_log($tilte,$user_id,$last_insterid[0]['kyc_id'],$regnumber,$description);	
									//$this->session->set_flashdata('success','kyc completed Successfully  !!');
									//redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
										//email log
									$this->KYC_Log_model->email_log($last_insterid[0]['kyc_id'],$this->session->userdata('kyc_id'),'1','',$regnumber,serialize($info_arr),$today,$this->session->userdata('role'));
								
								}
									//rebulide the array 
									$member = $this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id')));	
								
									$arrayid=explode(',', $member[0]['allotted_member_id']);
									$index= array_search($regnumber,$arrayid,true);
									
									//get next record
									$currentid	=	 $index;
									$nextid=$currentid+1;
									
									if(array_key_exists($nextid, $arrayid))
									{
										$next_id = $arrayid[$nextid];
									}
									else
									{
										$next_id = $arrayid[0];
									}
								//end of next record
									unset($arrayid[$index]);
									if(count($arrayid) > 0)
									{
										foreach($arrayid as $row)
										{
											$new_arrayid []=	$row;
										}
									}
									if(count($new_arrayid) > 0)
									{
										$regstr=implode(',',$new_arrayid);
									}
									else
									{
										$regstr='';
										$next_id='';	
									}
					$update_data=array('allotted_member_id'=>$regstr);
					$this->db->where('DATE(date)',date('Y-m-d'));
					$this->db->where('list_type','New');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id')));
					redirect(base_url().'admin/kyc/Approvercopy/approver_edited_member/'.$next_id);
				}
						else
						{
							$this->session->set_flashdata('error','Select all check box to complete the Kyc !!');
							//$error='Select all check-box to complete the Kyc !!';
							//redirect(base_url().'admin/kyc/Approver/approver_edited_member/'.$regnumber);
						}
                                                                                                                                     }
		}
		
		if($regnumber)
		{
			$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
			$members = $this->master_model->getRecords("member_registration a", array('regnumber'=>$regnumber,'isactive'=>'1'), $select, "",'0','1');
			//echo $this->db->last_query();exit;
			/*if(count($members))
			{
				$data['result'] = $members;
				
				$data['reg_no'] = $members[0]['regnumber'];
				$id=$data['reg_no'];
			}*/
		
		}
		//$this->db->where('field_count','0');
		$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'),'0','1');
		
		//echo $this->db->last_query();exit;
		//$data['recomended_mem_data']=$recommnended_members_data;
		$data=array('result'=>$members,'next_id'=>$next_id,'recomended_mem_data'=>$recommnended_members_data,'error'=>$error,'success'=>$success);
		$this->load->view('admin/kyc/Approver/approver_edited_screen',$data);
	}else
		{
					
				
				$this->session->set_flashdata('success',$this->session->flashdata('success'));
				//$this->session->set_flashdata('error','Invalid record!!');
				redirect(base_url().'admin/kyc/Approvercopy/approver_edited_list');
		}
 }

//to get next recode on click of next button
public function next_recode($regnumber)
{
		if($regnumber)
		{
			$ky_id=$this->session->userdata('kyc_id');
			$arrayid=array();
			$date=date("Y-m-d");	
			$select='*';
			$this->db->where('date',$date);

			$this->db->where('user_type',$this->session->userdata('role'));
			$this->db->where('user_id',$this->session->userdata('kyc_id'));
			$member = $this->master_model->getRecords("admin_kyc_users","", $select);	
			$arrayid=explode(',', $member[0]['allotted_member_id']);
			$currentid	=	 array_search($regnumber,$arrayid,true);
			$nextid=$currentid+1;
			if(array_key_exists($nextid, $arrayid))
			{
				$next_id = $arrayid[$nextid];
			}
			
			else
			{
				$next_id = $arrayid[0];
			}
	
			redirect(base_url().'admin/kyc/Approvercopy/member/'.$next_id);
		}
		else
		{
			redirect(base_url().'admin/kyc/Approvercopy/kyc_complete');
		}
}

/*	
public function new_member_list()
{
		$data['result'] = array();
		$per_page = 10;
		$last =100;
		$start = 0;
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$searchText = '';
		$searchBy = '';
		//$from_date = '';
		//$to_date = '';
		$registrationtype = '';
		$data['reg_no'] = ' ';
		
		if($page != 0)
		{
			$start = $page-1;
		}
	$select='*';
	$members = $this->master_model->getRecords("member_kyc a", "", $select, array('regid'=>'DESC'), $start, $per_page);
	$data['start'] = $start;
		
		//echo $this->db->last_query();
		if(count($members))
		{
			$data['result'] = $members;
			
			$data['reg_no'] = $members[0]['regnumber'];
			$id=$data['reg_no'];
	
		}
		$total_row = 100;
		$url = base_url()."admin/kyc/Kyc/allocated_list/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		
		if(($start+$per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		
		if($total_row)
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries'; 
		else
			$data['info'] = 'Showing 0 to '.$end_of_total.' of '.$total_row.' entries'; 
		
		$data['index'] = $start + 1;
		
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));
		
		
		
		
		
	$this->load->view('admin/kyc/alocated_member',$data);
		
	
	
}*/
	

	
//Recommended list to approver - by prafull
public function recommneder_list()
{

		$kycstatus=array();
		$data['result']=array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';		
		$searchBy=$this->input->post('regnumber');
		$searchBy_regtype=$this->input->post('registrationtype');
		if($searchBy!='')
		{
			$this->db->where('member_kyc.regnumber',$searchBy);
		}elseif($searchBy_regtype!='')
		{
			$this->db->where('member_kyc.mem_type',$searchBy_regtype);
		}
		
		$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
		$this->db->where('member_registration.isactive','1');
		$r_list = $this->master_model->getRecords("member_kyc",array('recommended_by'=>$this->session->userdata('kyc_id'),'recommended_date'=>$date),'member_kyc.regnumber,kyc_id,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
		mem_dob,mem_sign,mem_proof,mem_photo,mem_associate_inst,member_kyc.old_data,member_registration.dateofbirth,member_registration.associatedinstitute',array('kyc_id'=>'DESC'));
//$r_list = $this->master_model->getRecords("member_kyc",array('regnumber'=>$searchBy));

  
		
		if(count($r_list))
		{

				$data['result'] =  $r_list;	
				$data['status'] =  $kycstatus;	
		}	
			
	    $this->load->view('admin/kyc/Approver/recommended_list',$data);
		//redirect(base_url().'admin/kyc/Approver/member');
}
		
//KYC completed list to approver - by pawan
public function kyccomplete_newlist()
{

		$allocates_arr=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$today=date('Y-m-d H:i:s');
	
	//WHERE kyc_id IN (SELECT MAX(kyc_id) FROM member_kyc GROUP BY member_kyc.regnumber)
	
		$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
		$this->db->where('member_registration.kyc_status','1');
	   $this->db->where('member_kyc.kyc_state',3);
		 $this->db->where('member_registration.isactive','1');
		$this->db->group_by('member_kyc.regnumber');
		$members = $this->master_model->getRecords("member_kyc",array('field_count'=>'0'),'MAX(kyc_id),member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,registrationtype,email,recommended_by,recommended_date,approved_date',array('kyc_id'=>'DESC'));
		//echo $this->db->last_query();exit;

		//print_r($members);exit;
		if(count($members))
		{
			$recminfo = $this->master_model->getRecords("administrators",array('id'=>$members[0]['recommended_by']),'username');
			
			$data['result'] = $members;
			$data['recommended_name'] = $recminfo[0]['username'];
			$data['reg_no'] = $members[0]['regnumber'];
			$id=$data['reg_no'];
		}
		  //insert the allocated array list in table
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li><li>Search</li></ol>';
		
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array('isactive'=>'1'),'registrationtype',array('registrationtype'=>'ASC'));
	    $this->load->view('admin/kyc/Approver/kyccomplete_newlist',$data);
		//redirect(base_url().'admin/kyc/Approver/member');
}

	
//Details of Recommended member - by prafull
public function details($regnumber=NULL)
{
	$data['result'] = array();
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if($regnumber)
		{
			
			$members = $this->master_model->getRecords("member_registration", array('regnumber'=>$regnumber,'isactive'=>'1'));
			//echo $this->db->last_query();exit;
			if(count($members))
			{
				$data['result'] = $members;
				$data['reg_no'] = $members[0]['regnumber'];
				$id=$data['reg_no'];
			}
		}
		$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'));
		$data['recomended_mem_data']=$recommnended_members_data;
		$this->load->view('admin/kyc/Approver/approver_view_recommended_details',$data);
}
	
//Details of KYC completed member - by pawan
public function completed_details($regnumber=NULL)
{
		$data['result'] = array();
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if($regnumber)
		{
			$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
			$members = $this->master_model->getRecords("member_registration a", array('regnumber'=>$regnumber,'isactive'=>'1'), $select, "");
			//echo $this->db->last_query();exit;
			if(count($members))
			{
				$data['result'] = $members;
				$data['reg_no'] = $members[0]['regnumber'];
				$id=$data['reg_no'];
			}
		}
		$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'));
		$data['recomended_mem_data']=$recommnended_members_data;
		$this->load->view('admin/kyc/Approver/approver_view_kyccompleted_details',$data);
}
	
	
public function edited_member($regnumber)
{
	//	echo $regnumber;exit;
		$data['result'] = array();
	
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if($regnumber)
		{
			$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
			$members = $this->master_model->getRecords("member_registration a", array('regnumber'=>$regnumber,'isactive'=>'1'), $select, "",'0','1');
			//echo $this->db->last_query();exit;
			if(count($members))
			{
				$data['result'] = $members;
				
				$data['reg_no'] = $members[0]['regnumber'];
				$id=$data['reg_no'];
			}
		}
		$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'));
		$data['recomended_mem_data']=$recommnended_members_data;
$this->load->view('admin/kyc/Approver/approver_edited_screen',$data);
		
}
	
	
// By VSU : Function to fetch list of members to initiate KYC
public function member($regnumber)
{
	//	echo $regnumber;exit;
		$data['result'] = array();
	
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if($regnumber)
		{
			$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
			$members = $this->master_model->getRecords("member_registration a", array('regnumber'=>$regnumber,'isactive'=>'1'), $select, "");
			//echo $this->db->last_query();exit;
			if(count($members))
			{
				$data['result'] = $members;
				
				$data['reg_no'] = $members[0]['regnumber'];
	       		$id=$data['reg_no'];
				$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'));
		       $data['recomended_mem_data']=$recommnended_members_data;
			}
		}
		$this->load->view('admin/kyc/Approver/approver_edited_screen',$data);
	}	
}