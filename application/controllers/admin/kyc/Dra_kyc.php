<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dra_kyc extends CI_Controller {
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
		$this->load->model('KYC_Log_model'); 
		$this->load->model('Emailsending');
		$this->load->model('Chk_KYC_session');
		$this->Chk_KYC_session->chk_recommender_session();
	}
public function index()
{
	
}
	
//show the Dashboard
public function dashboard()
{
	$this->load->view('admin/kyc/dashboard');
	
}


//To check the checkbox value 
public function edit_checkmember($reg_no)
{ 
		
		$new_arrayid=array();
		$status='0';
		$state='1';
		$today = date("Y-m-d H:i:s");
		$date = date("Y-m-d");
		if(isset($_POST['btnSubmit']))
		{
				$select = 'regnumber,registrationtype,email';
				$data = $this->master_model->getRecords('member_registration',array('regnumber'=>$reg_no),$select);
		
				$name = $_POST['cbox'];
				$regnumber=$data[0]['regnumber'];
				// optional
				// echo "You chose the following color(s): <br>";
				$check_arr = array();
				foreach ($name as $cbox)
				{
					//echo $cbox."<br />";
					$check_arr[] = $cbox;
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
					$msg.='Employer,';
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
				
				$email=$data[0]['email'];
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
									'kyc_status'			=> $status,
									'kyc_state'				=> $state,
									'recommended_by'         =>$this->session->userdata('kyc_id'),
									'recommended_date'		=>$today,
									'record_source'			=>'Edit'
									);
		       	$data = $this->master_model->insertRecord('member_kyc',$insert_data,'');
			
				$member = $this->master_model->getRecords("admin_kyc_users",array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit'));	
				$arrayid=explode(',', $member[0]['allotted_member_id']);
				$index= array_search($regnumber,$arrayid,true);
				unset($arrayid[$index]);
				if(count($arrayid) > 0)
				{
					foreach($arrayid as $row)
					{
						$new_arrayid []=	$row;
					}
				}
				$regstr=implode(',',$new_arrayid);
				$update_data=array('allotted_member_id'=>$regstr);
				$this->db->where('date',date('Y-m-d'));
				$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit'));
				
				
     if($dob_checkbox=='1' && $emp_checkbox=='1' && $photo_checkbox=='1' &&  $sign_checkbox=='1' && $idprf_checkbox=='1' )
	{
				redirect(base_url().'admin/kyc/Kyc/edited_member/'.$regnumber);
	}else
	{
	
					$message='<html>
							<head>
							<title>INCOMPLETE  DATA</title>
							</head>
							<body>
							<h3>Incomplete profile data</h3>
							<p>This mail is from IIBF (Indian Institute of Banking and Finance) your profile is incomplete Kindly update your user profile. 
							</p><br>
							<p>GO in edit profile and update your'.$msg.' </p>
							<p>Regards,</p>
							<p>IIBF</p>
							<p>kyciibf@gmail.com</p>
							</body>
							</html>';
				$info_arr=array(
				'to'=> "kyciibf@gmail.com",
				'from'=> "kyciibf@gmail.com",
				'subject'=>"Incomplete profile data",
				'message'=>$message
				);
				
				if($this->Emailsending->mailsend($info_arr))
				{
					$this->session->set_flashdata('success','Email Send Successfully !!');
					//log activity 
					$regnumber= $reg_no;
					$user_id=$this->session->userdata('kyc_id');
					$tilte='Mail send ';
					$description ='Recommended from Edited list, mail send to '.$regnumber.' by '. $this->session->userdata('role').'';
					$this->KYC_Log_model->create_log($tilte,$user_id,$regnumber,$description);
					redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
					redirect(base_url().'admin/kyc/Kyc/edited_member/'.$regnumber);
					
				}
			}
		}
		
	}



//To show the Edited member Recommender screen  	
public function edited_member($regnumber)
{
	     $success=$error='';
	//	echo $regnumber;exit;
		if($regnumber)
		{	
			$next_id= $sucess=$memregnumber='';
			$data['result'] = $new_arrayid=array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer=array();
			$field_count=0;
		$data=$update_data=array();
			$name =array();
			$state='1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			if(isset($_POST['btnSubmit']))
			{ 
					$select = 'regnumber,registrationtype,email';
					$data = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber),$select);
                 
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
						if(in_array('emp_checkbox',$check_arr))
						{
							$emp_checkbox = '1';
						}
						else
						{
							$emp_checkbox = '0';
							$field_count++;
							$update_data[].='Employer,';
							$msg.='Employer';
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
										'kyc_status'			=> '0',
										'kyc_state'				=> $state,
										'recommended_by'         =>$this->session->userdata('kyc_id'),
										'recommended_date'		=>$today,
										'record_source'			=>'Edit'
										);
					$data = $this->master_model->insertRecord('member_kyc',$insert_data);
				    //log activity
					// get recommended fields data from member registration -
					$select='namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto';
					$old_data=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber),$select);	
					$log_desc['old_data'] = $old_data;
					$log_desc['inserted_data'] = $insert_data;
					$description = serialize($log_desc);
					$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $regnumber, $description);
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
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   }if(in_array('DOB',$update_data) )
				   {
					   
					   $updatedata['dateofbirth']='0000-00-00';
										
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
					   
				   }if (in_array('Employer',$update_data))
				   {
					    $updatedata['associatedinstitute']='';
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
					
				   }if ( in_array('Photo',$update_data))
				   {
					   $updatedata['scannedphoto']='';
					  	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   } 
				   if ( in_array('Sign',$update_data))
				   {
					  $updatedata['scannedsignaturephoto']='';  
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   } if ( in_array('Id-proof',$update_data))
				   {
					     $updatedata['idproofphoto']='';  

						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   }
					$member = $this->master_model->getRecords("admin_kyc_users",array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit'));	
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
					$this->db->where('date',date('Y-m-d'));
					$this->db->where('list_type','Edit');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id')));
					if($dob_checkbox=='1' && $emp_checkbox=='1' && $photo_checkbox=='1' &&  $sign_checkbox=='1' && $idprf_checkbox=='1' )
					{
								 $success='Recommended Successfully !!';
							  
							   //redirect(base_url().'admin/kyc/Kyc/edited_member/'.$regnumber);
					}else
					{
		
					$message='<html>
								<head>
								<title>INCOMPLETE  DATA</title>
								</head>
								<body>
								<h3>Incomplete profile data</h3>
								<p>This mail is from IIBF (Indian Institute of Banking and Finance) your KYC is process completed
								</p><br>
							
								<p>Regards,</p>
								<p>IIBF</p>
								<p>kyciibf@gmail.com</p>
								</body>
								</html>';
					$info_arr=array(
					'to'=> "kyciibf@gmail.com",
					'from'=> "kyciibf@gmail.com",
					'subject'=>"Incomplete profile data",
					'message'=>$message
					);
					
					if($this->Emailsending->mailsend($info_arr))
					{
							$success='Email Send Successfully !!';
						 //log activity 
						$regnumber= $regnumber;
						$user_id=$this->session->userdata('kyc_id');
						$tilte='Mail send ';
						$description ='Recommended mail send to '.$info_arr['to'].' by '. $this->session->userdata('role').'';
						$this->KYC_Log_model->create_log($tilte,$user_id,$regnumber,$description);
						//redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
					
					}
				}
			}
			
			
			//$data['next_id']= $next_id;
			//$data['next_id'] = '77066';
			
			if($regnumber)
			{
				$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
				$members = $this->master_model->getRecords("member_registration", array('regnumber'=>$regnumber,'isactive'=>'1'), $select, "",'0','1');
				if(count($members) > 0)
				{
					$memregnumber=$members[0]['regnumber'];
				}
				
				/*if(count($members))
				{
					$data['result'] = $members;
					$data['reg_no'] = $members[0]['regnumber'];
				}*/
				
			}
			
			$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'));
			//$data['recomended_mem_data']=$recommnended_members_data;
			
			$data=array('result'=>$members,'reg_no'=>$memregnumber,'recomended_mem_data'=>$recommnended_members_data,'next_id'=>$next_id,'error'=>$error,'success'=>$success);
			$this->load->view('admin/kyc/edit_recommended_screen',$data);
		}
		else
		{

			$this->session->set_flashdata('error','Invalid record!!');
			redirect(base_url().'admin/kyc/Kyc/edited_list');
		}
	
}
		
//To show the edited list  
public function edited_list()
{ 
		$tilte='';
		$description=$emptylistmsg='';
		$allocates_arr=$members_arr=$result=$array=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';
		$today=date('Y-m-d');
		$per_page = 100;
		$last =99;
		$start = $data['count']='0';
		$list_type='Edit';
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
		$kyc_data = $this->master_model->getRecords("admin_kyc_users",array('date'=>$today,'list_type'=>'Edit' ),'original_allotted_member_id');

		$allocatedmemberarr= array();
		if(count($kyc_data) > 0)
		{
			foreach($kyc_data as $row)
			{
				$allocatedmemberarr[]=explode(',',$row['original_allotted_member_id']);
			}
		}
		
		$this->db->where('kyc_edit','1');
		$this->db->where('isactive','1');
		$this->db->where('kyc_status','0');
		
		if(count($allocatedmemberarr) > 0)
		{
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				if(count($data_array) > 0)
				{
					
					$this->db->where_not_in('regnumber',array_map('stripslashes', $data_array));
						
				}				
		}
			
		$members = $this->master_model->getRecords("member_registration","",'', array('regid'=>'DESC'), $start, $per_page);
		
		$today = date("Y-m-d");
		$row_count = $this->master_model->getRecordCount("admin_kyc_users",array('date' => $today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit' ));
		
		if($row_count == 0)
		{
			$regstr = '';
			foreach($members as $row)
			{  
				$regnumber = $row['regnumber'];
				$editedon = $row['editedon'];
				
				// get recent recommended record of this member
				$kyc_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), 'recommended_date', array('kyc_id' => 'DESC'), '', 1);
				
				//print_r($kyc_data);
				
				//echo $this->db->last_query();
				//die();
				
				// check if record exist
				if(!empty($kyc_data))
				{
					$recommended_date = $kyc_data[0]['recommended_date'];
										
					// check if member profile edited after recommendation
					if($editedon >= $recommended_date)
					{
						$allocates_arr[] = $regnumber;
					}
				}
				else
				{
						$allocates_arr[] = $regnumber;
				}
				
				// check if user get alloctaed 100 members
				if(count($allocates_arr)  == 100)
				{
					break;
				}
				
				//echo $regnumber;
				
				//$data['recommended_date']= $regnumber;
				
				//$reg[] = $row['regnumber'];
				//$regstr .= $row['regnumber'].',';
			}
			
			//print_r($allocates_arr); die();
			$allocated_count=count($allocates_arr);
			if(count($allocates_arr)  > 0)
			{
				$regstr = implode(',', $allocates_arr);
			}
		
			// insert the allocated array list in table
			$insert_data = array
				   (	
						'user_type'			=> $this->session->userdata('role'),
						'user_id'				=> $this->session->userdata('kyc_id'),
						'allotted_member_id'	=> $regstr,
						'original_allotted_member_id'	=> $regstr,
						'allocated_count'     =>$allocated_count,
						'allocated_list_count'     =>'1',
						'date'	                => $today,
						'list_type'             => $list_type
						
				  );
				$this->master_model->insertRecord('admin_kyc_users',$insert_data);
				//log activity 
				$tilte='Edited member list allocation';
				$description ='Recommender has allocated '. count($allocates_arr).' member';
				$user_id=$this->session->userdata('kyc_id');
				$this->KYC_Log_model->create_log($tilte,$user_id,'',$description);
		}

			$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit','allotted_member_id !='=>'' ));
		
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
					//default allocation list for 100 member
					foreach($arraid as $row)
					{  
							//$this->db->join('member_kyc','member_kyc.regnumber=member_registration.regnumber','LEFT');
							$members = $this->master_model->getRecords("member_registration",array('member_registration.regnumber'=>$row));
							$members_arr[] = $members;
					}
				}
			}
			if(count($members_arr) > 0)
			{
			
				foreach($members_arr as $k=>$v)
				{
						$result[]=$v;
				}
			}
			$data['result']=$result;
		
			//$data['reg_no'] = $members[0]['regnumber'];
			//$id=$data['reg_no'];
		}
		$total_row = 100;
		$url = base_url()."admin/kyc/Kyc/edited_list/";
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
		
		$emptylistmsg=' No records available-click on the below link to get next 100 Records.........!!; <br />
			   <a href='.base_url().'admin/kyc/Kyc/next_edited_list/>Next Records</a>';
		$data['emptylistmsg']	=$emptylistmsg;  
		
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));
		
		
	    $this->load->view('admin/kyc/edited_list',$data);
	}

//to show the recommended member list 
public function recommended_list()
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
		$r_list = $this->master_model->getRecords("member_kyc",'','member_kyc.regnumber,kyc_id,firstname,middlename,lastname,recommended_date,registrationtype,email,record_source,member_kyc.kyc_status,mem_name,
		mem_dob,mem_sign,mem_proof,mem_photo,mem_associate_inst');
//$r_list = $this->master_model->getRecords("member_kyc",array('regnumber'=>$searchBy));

		
		if(count($r_list))
		{

				$data['result'] =  $r_list;	
				$data['status'] =  $kycstatus;	
		}	
		
		$this->load->view('admin/kyc/recommended_list',$data);
	}
	



	
//To get the next recode on click of next button	
public function next_recode($regnumber)
{
	
		if($regnumber)
		{
			$ky_id=$this->session->userdata('kyc_id');
			$arrayid=array();
			$date=date("Y-m-d");	
			$member = $this->master_model->getRecords("admin_kyc_users", array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));	
			
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
			echo $next_id;exit;
			redirect(base_url().'admin/kyc/Kyc/member/'.$next_id);
		}
		else
		{
			redirect(base_url().'admin/kyc/Kyc/allocated_list');
		}
}



/*public function new_member_list()
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
	
		
//to get next 100 allocation ..for edited list  same day
public function next_edited_list()
{
		$data['count']=0;
		$tilte=$allocated_count=$emptylistmsg='';
		$allotted_member_id='';
		$description='';
		$allocates_arr=$members_arr=$result=$array=$allocated_member_list=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';
		$today=date('Y-m-d');
		$per_page = 100;
		$last =99;
		$start = 0;
		$list_type='New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		 $check=$kyc_data=array();
		 $date = date("Y-m-d");
		$check = $this->master_model->getRecords("admin_kyc_users ",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit','allotted_member_id'=>'' ));
		if(count($check))
		{
			if($check[0]['allotted_member_id']=='')
			{
					$kyc_data =$this->master_model->getRecords("admin_kyc_users",array('date'=>$today,'list_type'=>'Edit' ),'original_allotted_member_id');
					if(count( 	$kyc_data ) > 0)
					{
						foreach($kyc_data  as $row)
						{
							$allocatedmemberarr[]=explode(',',$row['original_allotted_member_id']);
						}
					}
					$this->db->where('kyc_edit','1');
						$this->db->where('isactive','1');
						$this->db->where('kyc_status','0');
					if(count($allocatedmemberarr) > 0)
					{
							// get the column data in a single array
							$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
							if(count($data_array) > 0)
							{
								$this->db->where_not_in('regnumber',array_map('stripslashes', $data_array));
							}		
									
					}
						
						$members = $this->master_model->getRecords("member_registration","",'', array('regid'=>'DESC'), $start, $per_page);	
						
						//array1
						$array_string1=$check[0]['original_allotted_member_id'];	
						$allocates_arr1= explode(',', $array_string1);
						foreach($members as $row)
						{  
							$allocates_arr[]=$row['regnumber'];
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
					
						$update_data = array
						(	
								'user_type'						=> $this->session->userdata('role'),
								'user_id'							=> $this->session->userdata('kyc_id'),
								'allotted_member_id'		=> $allotted_member_id,
								'original_allotted_member_id'	=>$original_allotted_member_id,
								'allocated_count'    		  =>$allocated_count,
								'allocated_list_count'     =>$check[0]['allocated_list_count']+1,
								'date'	               			  => $today,
								'list_type'            		  => 'Edit'
						);
						$this->master_model->updateRecord('admin_kyc_users',$update_data,array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit'));
						//log activity 
						$tilte='Recommender got next  Edited member list allocation ';
						$user_id=$this->session->userdata('kyc_id');
						$this->KYC_Log_model->create_log($tilte,$user_id,'',serialize ($update_data));
			}
				$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit','allotted_member_id !='=>'' ));

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
					//default allocation list for 100 member
					foreach($arraid as $row)
					{  
							$members = $this->master_model->getRecords("member_registration",array('regnumber'=>$row));
							$members_arr[] = $members;
					}
				}
			}
			
		if(count($members_arr) > 0)
		{
				
					foreach($members_arr as $k=>$v)
					{
							$result[]=$v;
					}
		}
				$data['result']=$result;
		
			//$data['reg_no'] = $members[0]['regnumber'];
			//$id=$data['reg_no'];
		}
		$total_row = 100;
		$url = base_url()."admin/kyc/Kyc/edited_list/";
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
		
		
		$emptylistmsg='No records available for allocation.........!!'; '<br />';
		$data['emptylistmsg']	=$emptylistmsg; 
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));
		
		
	    $this->load->view('admin/kyc/edited_list',$data);

				}else
				{
					echo 'Not empty';
				}
			
	}
	
	
	
//to get next 100 allocation ...for  same day
public function next_allocated_list()
{
		$data['count']=0;
		$tilte=$allocated_count=$emptylistmsg='';
		$description='';
		$allocates_arr=$members_arr=$result=$array=$allocated_member_list=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';
		$today=date('Y-m-d');
		$per_page = 100;
		$last =99;
		$start = 0;
		$list_type='New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		 $check=$kyc_data=array();
		 $date = date("Y-m-d");
		 $allocatedmemberarr= array();
		$check = $this->master_model->getRecords("admin_kyc_users ",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));
		if(count($check))
		{
			if($check[0]['allotted_member_id']=='')
			{
					$kyc_data =$this->master_model->getRecords("admin_kyc_users",array('date'=>$today,'list_type'=>'New' ),'original_allotted_member_id');
					if(count( 	$kyc_data ) > 0)
					{
						foreach($kyc_data  as $row)
						{
							$allocatedmemberarr[]=explode(',',$row['original_allotted_member_id']);
						}
					}
							$this->db->where('kyc_edit','0');
						$this->db->where('isactive','1');
						$this->db->where('kyc_status','0');	
					if(count($allocatedmemberarr) > 0)
					{
							// get the column data in a single array
							$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
							if(count($data_array) > 0)
							{
								$this->db->where_not_in('regnumber',array_map('stripslashes', $data_array));
							}	
							
					}
						
						$members = $this->master_model->getRecords("member_registration","",'', array('regid'=>'DESC'), $start, $per_page);	
					//array1
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
						$update_data = array
						(	
								'user_type'						=> $this->session->userdata('role'),
								'user_id'							=> $this->session->userdata('kyc_id'),
								'allotted_member_id'		=> $allotted_member_id,
								'original_allotted_member_id'	=>$original_allotted_member_id,
								'allocated_count'    		  =>$allocated_count,
								'allocated_list_count'     =>$check[0]['allocated_list_count']+1,
								'date'	               			  => $today,
								'list_type'            		  => $list_type
						);
						$this->master_model->updateRecord('admin_kyc_users',$update_data,array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));
						//log activity 
						$tilte='Recommender got next  New member list allocation ';
						$user_id=$this->session->userdata('kyc_id');
						$this->KYC_Log_model->create_log($tilte,$user_id,'',serialize ($update_data));
			}
				$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id !='=>'' ));

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
					//default allocation list for 100 member
					foreach($arraid as $row)
					{  
							$members = $this->master_model->getRecords("member_registration",array('regnumber'=>$row));
							$members_arr[] = $members;
					}
				}
			}
			

			if(count($members_arr) > 0)
			{
			
				foreach($members_arr as $k=>$v)
				{
						$result[]=$v;
				}
			}
			$data['result']=$result;
			

		
			//$data['reg_no'] = $members[0]['regnumber'];
			//$id=$data['reg_no'];
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
		
		$emptylistmsg='No records available for allocation.........!!';' <br />';
		$data['emptylistmsg']	=$emptylistmsg; 
		
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));	   
		 $this->load->view('admin/kyc/alocated_member',$data);

				}else
				{
					echo 'Not empty';
				}
			
	}
	
	
	
////to show the new member list & allocate 100 member  	
public function allocated_list()
{ 

		$data['count']=0;
		$tilte=$allocated_count='';
		$description=$emptylistmsg='';
		$allocates_arr=$members_arr=$result=$array=$allocated_member_list=$data_array=array();$data['result'] = array();
		$regstr=$searchText = $searchBy ='';
		$searchBy_regtype ='';
		$today=date('Y-m-d');
		$per_page = 100;
		$last =99;
		$start = 0;
		$list_type='New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		
		//$from_date = '';
		//$to_date = '';
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

	 	/*$kyc_data = $this->master_model->getRecords("member_kyc","",'regnumber');
		foreach($kyc_data as $item)
		{
			$array[] = $item['regnumber'];         
	 	 }
        $a = implode(',', $array);
        $select='*';
		$this->db->where('kyc_edit','0');
		$this->db->where('isactive','1');*/
		
		//$data=$this->db->where_not_in('regnumber', $a);
		//$arrstr=explode(',',$allocated_member_list[0]['allotted_member_id']);
		
		//get  all  user loging today 
		$kyc_data = $this->master_model->getRecords("admin_kyc_users",array('date'=>$today,'list_type'=>'New' ),'original_allotted_member_id');
		$allocatedmemberarr= array();
		if(count($kyc_data) > 0)
		{
			foreach($kyc_data as $row)
			{
				$allocatedmemberarr[]=explode(',',$row['original_allotted_member_id']);
			}
		}
				$this->db->where('kyc_edit','0');
		$this->db->where('isactive','1');
		$this->db->where('kyc_status','0');		
		
		if(count($allocatedmemberarr) > 0)
		{
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				if(count($data_array) > 0)
				{
					$this->db->where_not_in('regnumber',array_map('stripslashes', $data_array));
				}
					
		}
		
		$members = $this->master_model->getRecords("member_registration","",'', array('regid'=>'DESC'), $start, $per_page);
		
	    $data['start'] = $start;
		
		//echo $this->db->last_query();
		  //insert the allocated array list in table
		$today = date("Y-m-d");
		$row_count = $this->master_model->getRecordCount("admin_kyc_users",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New' ));
		
		if($row_count==0)
		{
		
			$regstr='';
			foreach($members as $row)
			{  
				$allocates_arr[]=$row['regnumber'];
				//$reg[] = $row['regnumber'];
				//$regstr .= $row['regnumber'].',';
			}
			$allocated_count=count($allocates_arr);
			if(count($allocates_arr) >0)
			{
				$regstr=implode(',',$allocates_arr);
			}
		
			$insert_data = array
				   (	
						'user_type'			=> $this->session->userdata('role'),
						'user_id'				=> $this->session->userdata('kyc_id'),
						'allotted_member_id'	=> $regstr,
						'original_allotted_member_id'	=> $regstr,
						'allocated_count'     =>$allocated_count,
						'allocated_list_count'     =>'1',
						'date'	                => $today,
						'list_type'             => $list_type
				  );
				$this->master_model->insertRecord('admin_kyc_users',$insert_data);
				//log activity 
				$tilte='Recommender New member list allocation';
				$description ='Recommender has allocated '. count($allocates_arr).' member';
				$user_id=$this->session->userdata('kyc_id');
				$this->KYC_Log_model->create_log($tilte,$user_id,'',$description);
		}

			$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('date'=>$today,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id !='=>'' ));

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
					//default allocation list for 100 member
					foreach($arraid as $row)
					{  
							$members = $this->master_model->getRecords("member_registration",array('regnumber'=>$row));
							$members_arr[] = $members;
					}
				}
			}
			

			if(count($members_arr) > 0)
			{
			
				foreach($members_arr as $k=>$v)
				{
						$result[]=$v;
				}
			}
			$data['result']=$result;
			

		
			//$data['reg_no'] = $members[0]['regnumber'];
			//$id=$data['reg_no'];
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
		
		
		$emptylistmsg=' No records available-click on the below link to get next 100 Records.........!!; <br />
			   <a href='.base_url().'admin/kyc/Kyc/next_allocated_list/>Next Records</a>';
		$data['emptylistmsg']	=$emptylistmsg;   
		
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration',array(),'registrationtype',array('registrationtype'=>'ASC'));	    $this->load->view('admin/kyc/alocated_member',$data);
	
}
	
	// Unset session values
public function kyc_list()
{
	$this->session->set_userdata('registrationtype','');
	redirect(base_url().'admin/kyc/Kyc/member');
}
	

	
//To show  get the vale of checkbox 
public function checkmember($reg_no)
{ 		

		$data=array();
		$name =array();
		$state='1';
		$today = date("Y-m-d H:i:s");
		$date = date("Y-m-d");
		if(isset($_POST['btnSubmit']))
		{
				$select = 'regnumber,registrationtype,email';
				$data = $this->master_model->getRecords('member_registration',array('regnumber'=>$reg_no),$select);
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
						$msg.='Employer,';
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
				}
				else
				{
					$name_checkbox = '0';$msg.='Name,';
					
					$dob_checkbox = '0';$msg.='Date of Birth ,';
					
					$emp_checkbox = '0';$msg.='Employer,';
					
					$photo_checkbox = '0';$msg.='Photo,';
					
					$sign_checkbox = '0';$msg.='Sign,';
					
					$idprf_checkbox = '0';$msg.='Id-proof';
					
				}
				$email=$data[0]['email'];
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
									'kyc_status'			=> '0',
									'kyc_state'				=> $state,
									'recommended_by'         =>$this->session->userdata('kyc_id'),
									'recommended_date'		=>$today,
									'record_source'			=>'New'
									);
		       	$data = $this->master_model->insertRecord('member_kyc',$insert_data);
			   //log activity 
			    $regnumber= $reg_no;
				$user_id=$this->session->userdata('kyc_id');
				$tilte='Member recommend';
				$description =''.$regnumber.' has been recommended by '. $this->session->userdata('role').'';
				$this->KYC_Log_model->create_log($tilte,$user_id,$regnumber,$description);
				
				$member = $this->master_model->getRecords("admin_kyc_users",array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));	
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
				$regstr=implode(',',$new_arrayid);
				$update_data=array('allotted_member_id'=>$regstr);
				$this->db->where('date',date('Y-m-d'));
				$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));
			
	if($dob_checkbox=='1' && $emp_checkbox=='1' && $photo_checkbox=='1' &&  $sign_checkbox=='1' && $idprf_checkbox=='1' )
	{
			$this->session->set_flashdata('success','Recommended Successfully !!');
			redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
	}else
	{
	
				$message='<html>
							<head>
							<title>INCOMPLETE  DATA</title>
							</head>
							<body>
							<h3>Incomplete profile data</h3>
							<p>This mail is from IIBF (Indian Institute of Banking and Finance) your KYC is process completed
							</p><br>
						
							<p>Regards,</p>
							<p>IIBF</p>
							<p>kyciibf@gmail.com</p>
							</body>
							</html>';
				$info_arr=array(
				'to'=> "kyciibf@gmail.com",
				'from'=> "kyciibf@gmail.com",
				'subject'=>"Incomplete profile data",
				'message'=>$message
				);
				
				if($this->Emailsending->mailsend($info_arr))
				{
					$this->session->set_flashdata('success','Email Send Successfully !!');
						 //log activity 
					$regnumber= $reg_no;
					$user_id=$this->session->userdata('kyc_id');
					$tilte='Mail send ';
					$description ='Recommended mail send to '.$regnumber.' by '. $this->session->userdata('role').'';
					$this->KYC_Log_model->create_log($tilte,$user_id,$regnumber,$description);
					redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
					
				}
			}
		}
}
	
// By VSU : Function to fetch list of members to initiate KYC
public function member($regnumber=NULL)
{
		//	echo $regnumber;exit;
		if($regnumber)
		{	
			$next_id= $sucess=$memregnumber='';
			$data['result'] = $new_arrayid=array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer=array();
			$field_count=0;
			$data=$update_data=array();
			$name =array();
			$state='1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			if(isset($_POST['btnSubmit']))
			{
					$select = 'regnumber,registrationtype,email';
					$data = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber),$select);
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
						if(in_array('emp_checkbox',$check_arr))
						{
							$emp_checkbox = '1';
						}
						else
						{
							$emp_checkbox = '0';
							$field_count++;
							$update_data[].='Employer,';
							$msg.='Employer';
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
										'kyc_status'			=> '0',
										'kyc_state'				=> $state,
										'recommended_by'         =>$this->session->userdata('kyc_id'),
										'recommended_date'		=>$today,
										'record_source'			=>'New'
										);
					$data = $this->master_model->insertRecord('member_kyc',$insert_data);
					  //log activity
					// get recommended fields data from member registration -
					$select='namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto';
					$old_data=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber),$select);	
					$log_desc['old_data'] = $old_data;
					$log_desc['inserted_data'] = $insert_data;
					$description = serialize($log_desc);
					$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $regnumber, $description);
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
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   }if(in_array('DOB',$update_data) )
				   {
					   
					   $updatedata['dateofbirth']='0000-00-00';
										
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
					   
				   }if (in_array('Employer',$update_data))
				   {
					    $updatedata['associatedinstitute']='';
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
					
				   }if ( in_array('Photo',$update_data))
				   {
					   $updatedata['scannedphoto']='';
					  	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   } 
				   if ( in_array('Sign',$update_data))
				   {
					  $updatedata['scannedsignaturephoto']='';  
						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   } if ( in_array('Id-proof',$update_data))
				   {
					     $updatedata['idproofphoto']='';  

						$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));
				   }
					$member = $this->master_model->getRecords("admin_kyc_users",array('date'=>$date,'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));	
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
					//echo '*****'.$next_id;exit;
					
					$update_data=array('allotted_member_id'=>$regstr);
					$this->db->where('date',date('Y-m-d'));
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array('user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'));
					
				
					if($dob_checkbox=='1' && $emp_checkbox=='1' && $photo_checkbox=='1' &&  $sign_checkbox=='1' && $idprf_checkbox=='1' )
					{
							//$this->session->set_flashdata('success','Recommended Successfully !!');
							$sucess='Recommended Successfully !!';
							//redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
					}
					else
					{
					$message='<html>
								<head>
								<title>INCOMPLETE  DATA</title>
								</head>
								<body>
								<h3>Incomplete profile data</h3>
								<p>This mail is from IIBF (Indian Institute of Banking and Finance) your KYC is process completed
								</p><br>
							
								<p>Regards,</p>
								<p>IIBF</p>
								<p>kyciibf@gmail.com</p>
								</body>
								</html>';
					$info_arr=array(
					'to'=> "kyciibf@gmail.com",
					'from'=> "kyciibf@gmail.com",
					'subject'=>"Incomplete profile data",
					'message'=>$message
					);
					
					if($this->Emailsending->mailsend($info_arr))
					{
						//$this->session->set_flashdata('success','Email Send Successfully !!');
						$sucess='Email Send Successfully !!';
							 //log activity 
						$regnumber= $regnumber;
						$user_id=$this->session->userdata('kyc_id');
						$tilte='Mail send ';
						$description ='Recommended from New list, mail send to '.$info_arr['to'].' by '. $this->session->userdata('role').'';
						$this->KYC_Log_model->create_log($tilte,$user_id,$regnumber,$description);
						//redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);
					}
				}
			}
			
			//$data['next_id']= $next_id;
			//$data['next_id'] = '77066';
			
			if($regnumber)
			{
				$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
				$members = $this->master_model->getRecords("member_registration a", array('regnumber'=>$regnumber,'isactive'=>'1'), $select, "",'0','1');
				if(count($members) > 0)
				{
					$memregnumber=$members[0]['regnumber'];
				}
				
				/*if(count($members))
				{
					$data['result'] = $members;
					$data['reg_no'] = $members[0]['regnumber'];
				}*/
				
			}
			
			$recommnended_members_data= $this->master_model->getRecords("member_kyc", array('regnumber'=>$regnumber),'',array('kyc_id'=>'DESC'));
			//$data['recomended_mem_data']=$recommnended_members_data;
			$data=array('result'=>$members,'reg_no'=>$memregnumber,'recomended_mem_data'=>$recommnended_members_data,'next_id'=>$next_id,'sucess'=>$sucess);
			/*echo '<pre>';
			print_r($data);
			exit;*/
			$this->load->view('admin/kyc/kyc_list',$data);
		}
		else
		{
			$this->session->set_flashdata('error','Invalid record!!');
			redirect(base_url().'admin/kyc/Kyc/allocated_list');
		}
}

//to Show the recommended details	
public function details($regnumber)
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
		$this->load->view('admin/kyc/recommended_view_details',$data);
	}
}