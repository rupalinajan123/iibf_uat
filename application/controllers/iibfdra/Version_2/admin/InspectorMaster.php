<?php
 /*Controller class Inspector Master.
  * @copyright    Copyright (c) 2018 ESDS Software Solution Private.
  * @author       Aayusha Kapadni 
  * @package      Controller
  * @updated      2019-06-24 by Manoj 
  */

defined('BASEPATH') OR exit('No direct script access allowed');
class InspectorMaster extends CI_Controller 
{
	public $UserID;
	public $UserData;				
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) 
		{
			redirect('iibfdra/Version_2/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
		$this->UserID   = $this->UserData['id'];	
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
	}
	public function index()
	{	
		$new_array=array();
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');

		$this->db->where('agency_inspector_master.is_delete','0');
		//$this->db->where('agency_inspector_master.is_active','1');
		$this->db->select('agency_inspector_master.*');
		$this->db->order_by("agency_inspector_master.id", "DESC");
		$data["inspector_list"] = $this->Master_model->getRecords("agency_inspector_master","","inspector_name,inspector_mobile,inspector_email,inspector_designation,plain_password");
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'iibfdra/Version_2/admin/'.$this->router->fetch_class().'">Manage Inspector</a></li>
							</ol>';
		$this->load->view('iibfdra/Version_2/admin/masters/inspector_list',$data);
	}
	// function to get all Inspectors for agency centers -
	public function getList()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 20;
		$start = 0;
		$session_arr = check_session();
		if($session_arr)
		{
			$field 		= $session_arr['field'];
			$value 		= $session_arr['value'];
			$sortkey 	= $session_arr['sortkey'];
			$sortval 	= $session_arr['sortval'];
			$per_page 	= $session_arr['per_page'];
			$start 		= $session_arr['start'];
		}
		$this->db->where('is_delete','0');
		$this->db->where('is_active','1');	
		$total_row = $this->UserModel->getRecordCount("agency_inspector_master");
	
		$url = base_url()."iibfdra/Version_2/admin/InspectorMaster/getList";
		// Pagination to listing page
		$config = pagination_init($url,$total_row,$per_page, 2);
		$this->pagination->initialize($config);
		
		$this->db->where('agency_inspector_master.is_delete','0');
		//$this->db->where('agency_inspector_master.is_active','1');
		$result = $this->UserModel->getRecords("agency_inspector_master",'','','DESC','','');

		$this->db->where('agency_inspector_master.is_delete','0');
		$this->db->where('agency_inspector_master.is_active','1');
		$this->db->select('agency_inspector_master.*,agency_center.location_name');
		$this->db->join('agency_inspector_center','agency_inspector_master.id=agency_inspector_center.inspector_id','left');
		$this->db->join('agency_center','agency_center.center_id=agency_inspector_center.center_id','left');
		$this->db->order_by("agency_inspector_master.created_on", "DESC");
		$result= $this->Master_model->getRecords("agency_inspector_master","","  inspector_name,inspector_mobile,inspector_email,inspector_designation, location_name");

		/*$this->db->select('agency_inspector_center.*,city_master.city_name');
        $this->db->join('city_master','agency_inspector_center.city=city_master.id','LEFT');        
        $city= $this->Master_model->getRecords("agency_inspector_center",array('inspector_id'=>$res['id']),'city');*/
        

		if($result)
		{
			$data['city'] =$city;
			$data['result'] = $result;
			$data['location_name']=$location_name;
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}	
		$this->load->view('iibfdra/Version_2/admin/masters/inspector_list',$data);
	}
	
		/* Start of generateCsv ,Author Harshala Donde */	
		public function generateCsv()
		{
		
			$this->load->dbutil();
			$this->load->helper('file');
			$this->load->helper('download');
	
			// Specify the delimiter and newline character
			$delimiter = ",";
			$newline = "\r\n"; // You can use "\n" or "\r\n" based on your preference
		
			$query = "SELECT inspector_name,inspector_email,username,plain_password FROM agency_inspector_master WHERE is_active = 1; ";
			$result1 = $this->db->query($query);
	
			// Generate the CSV data
			$csv_data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
	
	
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment;filename="credentials.csv"');
			header('Cache-Control: max-age=0');
	
			
			force_download('credentials.csv', $csv_data);
		}
	   /* End of generateCsv  */	


	// function to add Inspectors for agency centers -
	public function add()
	{
		$data = array();
		if(isset($_POST['btnSubmit']))
		{

			$inspector_mobile =	 $this->input->post('inspector_mobile');
			$inspector_email  =  $this->input->post('inspector_email');
			$this->form_validation->set_rules('inspector_name','Inspector Name','trim|required|xss_clean');
			$this->form_validation->set_rules('inspector_mobile','Inspector Mobile','trim|required|numeric|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('inspector_email','Inspector Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
				$this->form_validation->set_rules('inspector_designation','Inspector Designation','trim|required|xss_clean');
				
				########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
				$this->form_validation->set_rules('batch_online_offline_flag','Online / Offline Batch','trim|required'); 
				$batch_online_offline_flag = $this->input->post("batch_online_offline_flag");
				if($batch_online_offline_flag == 0)
				{
			$this->form_validation->set_rules('city[]', 'City', 'trim|required|xss_clean');
  			$this->form_validation->set_rules('state[]', 'State', 'trim|required|xss_clean');
				}
				########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
				
			if($this->form_validation->run()==TRUE)
			{		//echo "swads"; die;		
				$pass_string = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                $ori_pss = substr(str_shuffle($pass_string), 0, 7);	
					$insert_data = array(	'inspector_name'	=>ucwords($this->input->post('inspector_name')),
											'inspector_mobile'	=>$this->input->post('inspector_mobile'),
											'inspector_email'	=>$this->input->post('inspector_email'),
											'inspector_designation'	=>$this->input->post('inspector_designation'),
											'batch_online_offline_flag'	=>$batch_online_offline_flag,
											'username'			=> $this->input->post('inspector_email'),
											'password'			=> md5($ori_pss),
											'plain_password'	=> $ori_pss,
											'added_by'			=>$this->UserID,
											'created_on'    	=> date("Y-m-d H:i:s")
										);
					
					$this->Master_model->insertRecord('agency_inspector_master',$insert_data);
					$last_id = $this->db->insert_id();
					//echo $last_id; exit;
					if($last_id)
					{
						if($batch_online_offline_flag == 0) //IF OFFLINE, THEN ONLY INSERT CITY/STATE DATA ########## CONDITION ADDED BY SAGAR ON 18-08-2020 ###################
						{
						//$inspector_center = $this->input->post('inspector_center');
						$state = $this->input->post('state');
						$city  = $this->input->post('city');
						
						foreach ($city as $key => $center_value) 
						{

									 $this->db->where('id',$center_value);
							$state= $this->Master_model->getRecords("city_master");
							$state=$state[0];
							$add_data = array('inspector_id'	=> $last_id,
											  'state'			=> $state['state_code'],
											  'city'			=> $center_value,
											  'user_id'			=> $this->UserID,
											  'created_on'    	=> date("Y-m-d H:i:s")
									 		);
							$this->Master_model->insertRecord('agency_inspector_center',$add_data);

							//echo $this->db->last_query(); die;
						}
						}

						// $pass_string = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                        // $ori_pss = substr(str_shuffle($pass_string), 0, 7);

						// $ispector_login = array(	'inspector_id'	=> $last_id,
						// 					  		'username'			=> $this->input->post('inspector_email'),
						// 					  		'password'			=> md5($ori_pss),
						// 					 		'original_password'	=> $ori_pss,
						// 					 	 	'created_on'    	=> date("Y-m-d H:i:s")
						// 			 		);
						// $this->Master_model->insertRecord('dra_inspector_login',$ispector_login);

						log_dra_admin($log_title = "Add DRA Inspector login details Successful", $log_message = serialize($ispector_login));
						
						log_dra_admin($log_title = "Add DRA Inspector Successful", $log_message = serialize($insert_data));
						$this->session->set_flashdata('success','Record added successfully');
						redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster');
					}
					else
					{
						log_dra_admin($log_title = "Add DRA Inspector Unsuccessful", $log_message = serialize($insert_data));
						$this->session->set_flashdata('error','Error occured while adding record');
						redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster/add');
					}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
			
		$data['breadcrumb'] 	= '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/Version_2/admin/'.$this->router->fetch_class().'">Manage Inspector</a></li>
					<li class="active">Add</li>
				</ol>';

		/*Get State and city dropdown*/
		$this->db->where('state_master.state_delete', '0');
        $data['states']  = $this->master_model->getRecords('state_master');
        $this->db->where('city_master.city_delete', '0');
        $data['cities'] = $this->master_model->getRecords('city_master');
        //echo"<pre>"; print_r($states); exit;

		$data['inspectorRes'] 	= array('inspector_name'	=>'',
										'inspector_mobile'	=>'',
										'inspector_email'	=>'',
										'inspector_designation'	=>'',
									  );

		$data["inspector_list"] = $this->Master_model->getRecords("agency_inspector_master",array('is_delete'=>0),"inspector_name,inspector_mobile,inspector_email,inspector_designation");
		$data['inspector_center'] = array('center_id'     =>'',
										  'location_name' => '' );
		$data['inspector_center'] = $this->Master_model->getRecords('agency_center','','center_id,location_name');

        $this->db->join('agency_center','agency_center.center_id=agency_inspector_center.center_id','left');
        $data['location_name']= $this->Master_model->getRecords("agency_inspector_center");

		$this->load->view('iibfdra/Version_2/admin/masters/inspector_add',$data);
	}
	
	// function to update Inspectors details for agency centers -
	public function edit($id='')
	{		
		$data 	= array();
		$data['inspectorRes'] = array();
	    //$id = base64_decode($this->uri->segment(5));
	    $id = base64_decode($id);
		if(is_numeric($id))
		{
			$inspectorRes = $this->Master_model->getRecords('agency_inspector_master',array('id'=>$id));

			if(count($inspectorRes))
			{
				$data['inspectorRes'] = $inspectorRes[0];
			}
				else { redirect(site_url('iibfdra/Version_2/admin/InspectorMaster')); }
		}
		else { redirect(site_url('iibfdra/Version_2/admin/InspectorMaster')); }

		if(isset($_POST['btnSubmit']))
		{
			//print_r($_POST);die;
			$id = $this->input->post('inspector_id');
			$this->form_validation->set_rules('inspector_name','Inspector Name','trim|required|xss_clean');
			$this->form_validation->set_rules('inspector_mobile','Inspector Mobile','trim|required|numeric|xss_clean');
			$this->form_validation->set_rules('inspector_email','Inspector Email','trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('inspector_designation','Inspector Designation','trim|required|xss_clean'); 
				
				########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
				$this->form_validation->set_rules('batch_online_offline_flag','Online / Offline Batch','trim|required'); 
				$batch_online_offline_flag = $this->input->post("batch_online_offline_flag");
				if($batch_online_offline_flag == 0)
				{
			$this->form_validation->set_rules('city[]', 'City', 'trim|required|xss_clean');
  			$this->form_validation->set_rules('state[]', 'State', 'trim|required|xss_clean');
				}
				########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
			
			if($this->form_validation->run()==TRUE)
			{
				if($this->input->post('new_password')!='' && $this->input->post('confirm_password')!= ''){
					$new_password = $this->input->post('new_password');
					$confirm_password = $this->input->post('confirm_password');

					if($new_password != $confirm_password){
						array_push($error_msg, 'New Password and Confirm Password are no same.');
						$data['error_msg'] = $error_msg;	
					}
				}

				//echo 'validateed';
				$update_data = array(	
									'inspector_name'	=>ucwords($this->input->post('inspector_name')),
									'inspector_mobile'	=>$this->input->post('inspector_mobile'),
									'inspector_email'	=>$this->input->post('inspector_email'),
									'inspector_designation'	=>$this->input->post('inspector_designation'),
									'batch_online_offline_flag'	=>$batch_online_offline_flag,
									'added_by'			=>$this->UserID,
									'updated_on'    	=> date("Y-m-d H:i:s")
									);

				if(!empty($new_password) && !empty($new_password)){
					$update_data['password'] = md5($new_password);
					$update_data['plain_password'] = $new_password;
				}

				$this->Master_model->updateRecord('agency_inspector_master',$update_data,array('id'=>$id));
				//echo $this->db->last_query();die;
				//echo"<pre>"; print_r($_REQUEST);
				//echo"<pre>"; print_r($update_data); exit;
				if($id)
				{
					$this->Master_model->deleteRecord('agency_inspector_center','inspector_id',$id);
					
						if($batch_online_offline_flag == 0)//IF OFFLINE, THEN ONLY INSERT CITY/STATE DATA ########## CONDITION ADDED BY SAGAR ON 18-08-2020 ###################
						{
					$inspector_center = $_POST['city'];
					foreach ($inspector_center as $center_value) 
					{
						 $this->db->where('id',$center_value);
							$state= $this->Master_model->getRecords("city_master");
							$state=$state[0];
						$edit_data = array(	 'inspector_id'		=> $id,
											  'center_id'		=> $center_value,
											  'city'            => $center_value,
											  'state'           => $state['state_code'],
											  'user_id'			=> $this->UserID,
											  'updated_on'    	=> date("Y-m-d H:i:s")
									 		);
						//echo"<pre>"; print_r($edit_data);					
											
						$this->Master_model->insertRecord('agency_inspector_center',$edit_data);	
					}
						}
					
					//echo $this->db->last_query();
					//exit;
					
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $data['inspectorRes'];
					log_dra_admin($log_title = "Edit DRA Institute Successful", $log_message = serialize($desc));
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster');
				}
				else
				{
					//echo '1213';
					//exit;
					log_dra_admin($log_title = "Edit DRA Institute Unsuccessful", $log_message = serialize($desc));
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/Version_2/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/Version_2/admin/'.$this->router->fetch_class().'">Manage Inspector</a></li>
					<li class="active">Edit</li>
			</ol>';			

		/*Get State and city dropdown*/
		$data['inspector_list'] 	= array('id'			=>'',
										'inspector_name'	=>'',
										'inspector_mobile'	=>'',
										'inspector_email'	=>'',
										'inspector_designation'	=>'',
										);
			$data["inspector_list"] = $inspector_list = $this->Master_model->getRecords("agency_inspector_master",array('is_delete'=>0),"id,inspector_name,inspector_mobile,inspector_email,inspector_designation");

		
		$this->db->where('inspector_id',$id);
		$inspectorState = $this->Master_model->getRecords('agency_inspector_center');
 		$data['inspectorState'] = $inspectorState;

		$this->db->distinct('state');
		$this->db->where('inspector_id',$id);
		$inspectorStates = $this->Master_model->getRecords('agency_inspector_center','','state');
 		$data['inspectorStates'] = $inspectorStates;

		$this->db->where('state_master.state_delete', '0');
        $data['states']  = $this->master_model->getRecords('state_master');

        foreach ($data['inspectorStates'] as $state) {


        $this->db->where('city_master.state_code',$state['state']);
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');
        $c[]=$cities;
        }
       $data['cities'] = $c;
 		
       
//print_r($data['cities']); die;
		//$data['inspector_center'] = array('center_id'     =>'', 'location_name' => '');
		//$data['inspector_center'] = $this->Master_model->getRecords('agency_center','','center_id,location_name');
		//$data['center'] = array('center_id'     =>'','inspector_id'	=>'',);
		//$data['center'] = $this->Master_model->getRecords('agency_inspector_center','','center_id,inspector_id');

		$this->load->view('iibfdra/Version_2/admin/masters/inspector_edit',$data);
		//redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster');
	}
	// function to delete Inspector for agency centers -
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('is_delete'=>'1');
			$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_inspector_center.inspector_id','left');
			$result =  $this->Master_model->updateRecord("agency_inspector_master",$update_data,array('agency_inspector_master.id'=>$id));
			if(!empty($result))
			{
			 	$this->Master_model->updateRecord('agency_inspector_center', $update_data, array('agency_inspector_center.inspector_id'=>$id));
			
				log_dra_admin($log_title = "Delete DRA Inspector Successful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster');
			}
			else
			{
				log_dra_admin($log_title = "Delete DRA Inspector Unsuccessful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'iibfdra/Version_2/admin/InspectorMaster');
			}
		}
	}
	// function to check mobile number duplication  -
	public function check_mobileduplication($inspector_mobile)
	{
        if ($inspector_mobile != "")
        {
            $prev_count = $this->Master_model->getRecordCount('agency_inspector_master', array(
                'inspector_mobile' => $inspector_mobile,
                'is_active' => '1'));
        	if ($prev_count == 0)
            {
            	return true;
            }
          	else
            {
            	$str = 'The Entered Mobile Number Already Exist';
                $this->form_validation->set_message('check_mobileduplication',$str);
                return false;
            }
        }
      	else{ return false; }
	}
	// function to check email Id duplication  -
	public  function check_emailduplication($inspector_email)
	{
        if ($inspector_email != "")
        {
        $prev_count = $this->Master_model->getRecordCount('agency_inspector_master', array(
            'inspector_email' => $inspector_email,
            'is_active' => '1'
        ));
	        if ($prev_count == 0)
	        {
	        	return true;
	        }
	      	else
	        {
	            $str = 'The Entered Email ID Already Exist';
	            $this->form_validation->set_message('check_emailduplication', $str);
	            return false;
	        }
        }
        else
        {
        return false;
        }
    }	
    /*GET VALUES OF CITY */
    public function getCity() 
    {
        if (isset($_POST['state_code']) && !empty($_POST['state_code'])) 
        {
        	$inspector_id_prv='';
            $state_code = $this->security->xss_clean($this->input->post('state_code'));
            $state_code = explode(",", $state_code);


                       $this->db->where_in('state_code', $state_code);
            $result = $this->master_model->getRecords('city_master');

            $inspector_id_prv = $this->security->xss_clean($this->input->post('inspector_id_prv')); 
           $centerid = $this->master_model->getRecords("agency_inspector_center",array('inspector_id'=>$inspector_id_prv),'city');
             				
							$centerarr = array();
              				foreach($centerid as $citys)
              				{
               				 		$centerarr[] = $citys['city'];
             				}
							
//print_r($centerarr); die;
           
            if ($result) 
            {
                //echo '<option value="">- Select - </option>';
                foreach ($result AS $data) 
                {
                    if ($data) 
                    {
                    //	print_r($centerarr); die;
           

                    	if(in_array($data['id'],$centerarr)){

                    		echo '<option selected="selected"  value="' . $data['id'] . '">' . $data['city_name'] . '</option>';

                    	}else{
                    		echo '<option  value="' . $data['id'] . '">' . $data['city_name'] . '</option>';
                    	}
                        
                    }
                }
            } 
            else 
            {
                echo '<option value="0">City Not Available, Please select other state</option>';
            }
            
        }
    }
	
    public function emailduplication()
    {
 		$result = array();
    	if(!empty($_GET['inspector_email']))
    	{
		   $result = $this->master_model->getRecords('agency_inspector_master', array('inspector_email' =>$_GET['inspector_email'],'is_active'=>'1','is_delete'=>'0'));
		   if(isset($result[0]['inspector_email']) && $result[0]['inspector_email'] !='')
		   {
		    	echo 'false';
		   }else{	
		   	 	echo 'true';
		   }
    	}

    }
	
	public function mobileduplication()
    {
 		$result = array();
    	if(!empty($_GET['inspector_mobile']))
    	{
		   $result = $this->master_model->getRecords('agency_inspector_master', array('inspector_mobile' =>$_GET['inspector_mobile'],'is_active'=>'1','is_delete'=>'0'));
		   if(isset($result[0]['inspector_mobile']) && $result[0]['inspector_mobile'] !='')
		   {
		    	echo 'false';
		   }else{	
		   	 	echo 'true';
		   }
    	}
    }

    public function emailduplication_edit()
    {

 		$result = array();
 		$inspector_id = $_GET['inspector_id'];
    	if(!empty($_GET['inspector_email']))
    	{
    	   $id = array($inspector_id);
		   $this->db->where_not_in('agency_inspector_master.id', $id);
		   $result = $this->master_model->getRecords('agency_inspector_master', array('inspector_email' =>$_GET['inspector_email'],'is_active'=>'1','is_delete'=>'0'));
		   if(isset($result[0]['inspector_email']) && $result[0]['inspector_email'] !='')
		   {
		    	echo 'false';
		   }else{	
		   	 	echo 'true';
		   }
    	}
    	
    }
	
    public function mobileduplication_edit()
    {

 		$result = array();
 		$inspector_id = $_GET['inspector_id'];
    	if(!empty($_GET['inspector_mobile']))
    	{
    	   $id = array($inspector_id);
		   $this->db->where_not_in('agency_inspector_master.id', $id);
		   $result = $this->master_model->getRecords('agency_inspector_master', array('inspector_mobile' =>$_GET['inspector_mobile'],'is_active'=>'1','is_delete'=>'0'));
		   if(isset($result[0]['inspector_mobile']) && $result[0]['inspector_mobile'] !='')
		   {
		    	echo 'false';
		   }else{	
		   	 	echo 'true';
		   }
    	}
    }
	
	
	
	public function activate_deactivate()
    {
		$result = array();
    	if(!empty($_REQUEST['id']))
    	{
		  
			$is_active = 0;
			$this->load->helper('general_agency_helper');
			$updated_date = date('Y-m-d H:i:s');
			$drauserdata = $this->session->userdata('dra_admin');
			
			$activate_deactivate_id = $_REQUEST['id'];
			$type = trim($_REQUEST['data_type']);
			$action_type =  'active';									
			$this->db->where('agency_inspector_master.id = "'.$activate_deactivate_id.'"');	
			$res_inspector = $this->master_model->getRecords('agency_inspector_master'); 
						
			if(count($res_inspector) > 0){
				
				if($action_type == $type ){					
					$str = 'Deactivate';	
					$is_active = '0';
				}else{
					$str = 'Activate';
					$is_active = '1'; 
				}
				
				$update_data = array(
				'is_active'		=> $is_active,									
				'updated_on'	=> $is_active,
				'modified_by'   => $drauserdata['id']	 
				);
								
				$insert_data = array(	
				'id'			=> $activate_deactivate_id,
				'user_id'		=> $drauserdata['id'],	
				'is_active'		=> $is_active,
				'modified_on'	=> $updated_date,
				'modified_by'	=> $drauserdata['id']
				);
						
		//log_dra_admin($log_title = "DRA Admin Deactivate Agency", $log_message = serialize($update_data));	
		log_dra_admin($log_title = $str." DRA Inspector", $log_message = serialize($update_data));	
		$this->master_model->updateRecord('agency_inspector_master',$update_data,array('id' => $activate_deactivate_id));
		echo 'OK';
		//redirect(base_url() . 'iibfdra/Version_2/admin/InspectorMaster');		
		}else{
			echo 'ERR';
			//redirect(base_url() . 'iibfdra/Version_2/admin/InspectorMaster');
		}
		   
    	}

    }

}