<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/
class CSCVenueDashboard extends CI_Controller

{

    public function __construct()

    {

        parent::__construct();

		//exit;

       /* if ($this->session->id == "") {

            redirect('admin/Login');

        }*/

        $this->load->model('UserModel');

        $this->load->model('Master_model');

        $this->UserID = $this->session->id;

        

        $this->load->helper('pagination_helper');

        $this->load->library('pagination');

        $this->load->helper('upload_helper');

        $this->load->helper('general_helper');

        $this->load->library('email');

		$this->load->library('upload'); // priyanka d - 17-ajn-23

		$this->load->model('log_model');  // priyanka d - 17-ajn-23

        $this->load->model('Emailsending');

        $this->load->model('KYC_Log_model');

        $this->load->library('session'); 

    }

    

   /*  public function index()

    {

        $this->load->view('admin/venue_master/exam_venue_dashboard', $data);

    } */

	//priyanka d- 18-jan-23
    public function getVenueListing() {

		// priyanka d - adding this function because , previous code not working properly it fetch all records one time and page continues loding. also for now adding double query for fetch total records and by limit to minimiza page loading time and execute query quickly - 17-jan-23
		
				$exm_cd=$_GET['exm_cd'];//implode(',',$_GET['exm_cd']);

				$exm_prd=$_GET['exm_prd'];//implode(',',$_GET['exm_prd']);

	

		$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") AND `exam_period` IN (".$exm_prd.")");

		//$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") ");



			$subdate = $subquery->result_array();

			//print_r($subdate);

		if(!empty($subdate))

		{

			foreach($subdate  as $val)

			{

				$exam_date[]=$val['exam_date'];

			}

		//$datearray=implode("','",$exam_date);
		$datearray='0000-00-00';

		$countquery = $this->db->query(	"

		SELECT venue_master.vendor_code
		  FROM `admit_card_details` RIGHT JOIN venue_master
		
		 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.")  AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where  venue_master.exam_date  IN ('".$datearray."')
		
		GROUP BY 
		
		venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");	
				//echo $this->db->last_query();exit;		
		$totalRecordCount = $countquery->result_array();

		$query = $this->db->query(	"

			SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

			as occupied

			FROM `admit_card_details` RIGHT JOIN venue_master

			ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.")  AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."')

			GROUP BY 

			venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time limit ".$_GET['start']." , ".$_GET['length']."");	
				
				$reg_count = $query->result_array();
				$data=array();
				foreach($reg_count as $key=> $value) {
					
					$center_name = $this->Master_model->getRecords("center_master", array(
						'center_code' => $val['center_code'],'exam_period'=>$exm_prd
						), 'center_name', array(
						'center_name' => 'ASC'
						));
						$value['center_name']=$center_name[0]['center_name'];
						$min_cap=$value['session_capacity']-5;
						if($min_cap<=$value['registered_count'])
							$value['occupiedshow']='<span style="color:#F00;">'.round($value['occupied'],2) .'%'.'</span>';
						else
						$value['occupiedshow']=round($val['occupied'],2) .'%';

						$value['action']='<input type="hidden" class="form-control" id="venue_code1" name="venue_code1" value="'.$value['venue_code'].'" autocomplete="false">
						<input type="hidden" class="form-control" id="center_code" name="center_code" value="'.$value['center_code'].'" autocomplete="false">
						<a class="btn btn-danger btn-sm rounded-0 remove removeVenueCode" onclick="removeVenueCode(this)" center_code="'.$value['center_code'].'" venue_code="'.$value['venue_code'].'" type="submit"> Delete </a>';
						$data[]=array('vendor_code'=>$value['vendor_code'],'center_code'=>$value['center_code'],'center_name'=>$value['center_name'],'venue_code'=>$value['venue_code'],'venue_name'=>$value['venue_name'],'session_capacity'=>$value['session_capacity'],'registered_count'=>$value['registered_count'],'balance_capacity'=>$value['balance_capacity'],'occupiedshow'=>$value['occupiedshow'],'action'=>$value['action']
					);
				}
			
				$result=array(
					"draw"=>$_GET['draw'],
					  "recordsTotal"=>count($totalRecordCount),
					  "recordsFiltered"=>count($totalRecordCount),
					  "aaData"=>$data,
				 );
		   echo json_encode($result);
		   exit();

		}	
	}

	//priyanka d- 18-jan-23
	 public function index()

    {

		//print_r($_POST);

		$exm_cd=$exm_prd=$exam_date='';

		if(isset($_POST['btnSubmit']))

		{

			if(isset($_POST['exm_prd']) &&isset($_POST['exm_cd']) )

			{
				$exm_cd=implode(',',$_POST['exm_cd']);

				$exm_prd=implode(',',$_POST['exm_prd']);
				$subquery = $this->db->query(" SELECT description  FROM `exam_master` WHERE `exam_code` IN (".$exm_cd.") ");



					$result = $subquery->result_array();
					$examName='';
					foreach($result as $r) {
						$examName	=	$r['description'].', ';
					}


				
				$data['examName']=rtrim($examName,', ');
			}
			$data['exm_cd']=$exm_cd;

			$data['exm_prd']=$exm_prd;
		}
		else {
			$data['Total_registration']=array();

			        $data['exm_cd']='';

					$data['exm_prd']='';

		}

				
			 //$this->load->view('admin/exam_venue_count', $data);
			 $this->load->view('admin/venue_master/csc_venue_dashboard', $data);

		

	}

	/*Priyanka D - 20-jan-23*/
    public function delete_venue()
      {
		$venue=$_POST['venue_code'];
		$center_code=$_POST['center_code'];
		$exam_date = '0000-00-00';
		
		if($venue != '' || $center_code !='')
		{
			//echo "string";die;
			$get_venue = $this->master_model->getRecords('venue_master',array('venue_code'=>$venue,'center_code'=>$center_code,'exam_date'=>$exam_date,'session_time = '=>''));
			echo $this->db->last_query();//die;
			//print_r($get_venue);die;
			if(!empty($get_venue))
			{
				//$update_flag = array('deactive_flag'=>'1');
				//$result=$this->master_model->updateRecord('venue_master',$update_flag,array('venue_code'=>$venue,'center_code'=>$center_code));

				$result = 1;//$this->db->query(" delete FROM `venue_master` WHERE `venue_code` ='".$venue."' AND `center_code` ='".$center_code."' and exam_date='0000-00-00' and session_time=''");

				//return true;
				//echo $this->db->last_query();
				//print_r($result);die;
				 if($result>0)
	            { 
					$final_str	=	'Hello,<br/>';
					$final_str .= 'Venue Code are successfully deactivated. Details are >> '.json_encode($get_venue);
					$final_str .= 'Regards,';
					$final_str .= '<br/>';
					$final_str .= 'ESDS TEAM';
					$info_arr = array('to' => 'priyanka.dhikale@esds.co.in',//'iibfdevp@esds.co.in,priyanka.dhikale@esds.co.in,iibfexam@cscacademy.org',
						'from'                 => 'noreply@iibf.org.in',
						'subject'              => 'Venue Code Deactivated (Deleted)',
						'message'              => $final_str,
					);
					
				 	$this->Emailsending->mailsend_attch($info_arr);
	              	return true;
	              /*$this->session->set_flashdata('success_message',' Deleted Successfully.');*/
	              echo "Deleted Successfully!";
	            }
	            else
	            {
	              /*$this->session->set_flashdata('error_message','Oops,Something Went Wrong While Deleting Record.');*/
	              echo "Oops,Something Went Wrong While Deleting Record.";
	            }

			}
			else{
				return false;
			}
		}
    }

	//priyanka d- 18-jan-23
	public function exam_registered_data() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$data=array();
		$this->load->view('admin/venue_master/exam_registered_data', $data);
	}
	//priyanka d- 18-jan-23
	public function showExamRegisteredData() {

		if(isset($_GET['member_ids']) && !empty($_GET['member_ids'])) {
			$member_nos=explode(',',$_GET['member_ids']);
					foreach($member_nos as $m)
						$member_no[]=trim($m);
						//echo'<pre>';print_r($member_no);exit;
		}
		if(isset($_GET['exam_date']) && !empty($_GET['exam_date'])) {
			$exam_date=date('Y-m-d',strtotime($_GET['exam_date']));
		}
		if(isset($_GET['register_date']) && !empty($_GET['register_date'])){
			$register_date=date('Y-m-d',strtotime($_GET['register_date']));
		}
		//$member_no = array(802164364);

		$select = 'DISTINCT(b.transaction_no),a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';

		
		$this->db->where("((a.exam_code = '991' AND bankcode = 'csc') OR (a.exam_code = '997'))");
		
		
		if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
			$this->db->where_in('a.regnumber',$member_no);
		if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
			$this->db->where('d.exam_date',$exam_date);
		if(isset($_GET['register_date']) && !empty($_GET['register_date']))
					$this->db->where('date(a.created_on)',$register_date);

		$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
		$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
		$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
		$this->db->where('remark',1);
		$this->db->where('pay_type',2);
		$this->db->where('status',1);
		$this->db->where('isactive','1');
		$this->db->where('isdeleted',0);
		$this->db->where('pay_status',1);
		if(isset($_GET['search']['value']) && !empty($_GET['search']['value']))
			$this->db->where("a.regnumber like '%".$_GET['search']['value']."%' ");

		$totalRecordCount = $this->Master_model->getRecordCount("member_exam a");
		
		//echo $this->db->last_query();exit;
		//echo'<pre>';print_r($totalRecordCount);
		//	echo $totalRecordCount;
		$this->db->where("((a.exam_code = '991' AND bankcode = 'csc') OR (a.exam_code = '997'))");
		
		
		if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
			$this->db->where_in('a.regnumber',$member_no);
		if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
			$this->db->where('d.exam_date',$exam_date);
			else
			$this->db->where('d.exam_date >=',date('Y-m-d'));
		if(isset($_GET['register_date']) && !empty($_GET['register_date']))
			$this->db->where('date(a.created_on)',$register_date);

		$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
		$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
		$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
		$this->db->where('remark',1);
		$this->db->where('pay_type',2);
		$this->db->where('status',1);
		$this->db->where('isactive','1');
		$this->db->where('isdeleted',0);
		$this->db->where('pay_status',1);
		if(isset($_GET['search']['value']) && !empty($_GET['search']['value']))
			$this->db->where(" a.regnumber like '%".$_GET['search']['value']."%' ");

		$orderByCols=array('a.regnumber','c.firstname','registration_date','c.email','c.mobile');
		$getorderfield=$_GET['order'][0]['column'];
		$ascdesc=$_GET['order'][0]['dir'];
		$orderby=array($orderByCols[$getorderfield]=>$ascdesc);
		$can_exam_data = $this->Master_model->getRecords('member_exam a', '', $select,$orderby,$_GET['start'],$_GET['length'] );	
				
	//	echo $this->db->last_query(); //exit;
		//echo "<pre>"; print_r($can_exam_data); echo "</pre>"; exit;
		$exam_cnt = 0;
		$rowdata=array();
		if (count($can_exam_data)) 
				{
					$i = 1;					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
						
							
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
						{
							$ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
							if(count($ex_code)) 
							{
								if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
								{
									$exam_code = $ex_code[0]['original_val'];
								}
								else 
								{
									$exam_code = $exam['exam_code'];
								}
							}
							else 
							{
								$exam_code = $exam['exam_code'];
							}
						}
						else 
						{
							$exam_code = $exam['exam_code'];
						}
						
						$dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						
						$address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) 
						{
							foreach ($designation as $designation_row) 
							{
								if ($exam['designation'] == $designation_row['dcode']) 
												{
								$designation_name = $designation_row['dname'];
												}
							}
						}
						$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
									
						$medium = $this->master_model->getRecords('medium_master');
						if(count($medium)) 
						{
						foreach ($medium as $medium_row) 
										{
							if ($exam['exam_medium'] == $medium_row['medium_code']) 
											{
							$medium_name = $medium_row['medium_description'];
											}
										}
						}
						$medium_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						$institution_master = $this->master_model->getRecords('institution_master');
						if(count($institution_master)) 
						{
						foreach ($institution_master as $institution_row) 
										{
							if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
											{
							$institution_name = $institution_row['name'];
											}
										}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
									
						$exam_arr = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');            
						foreach ($exam_arr as $k => $val) 
									{
										if ($exam_code == $k)  
										{
											$exam_name = $val;
										}
									}
									
						$select    = 'regnumber';
						$this->db->where_in('exam_code', 991);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);            
						$attempt_count = $attempt_count - 1;
								
						$post_field_arr=array();
						$post_field_arr['name'] = $firstname.' '.$middlename.' '.$lastname;
						$post_field_arr['member_number'] = $exam['regnumber'];
						$post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
						$post_field_arr['email_id'] = $exam['email'];
						$post_field_arr['mobile'] = $mobile;
						$post_field_arr['address'] = $address.' '.$exam['state'].' '.$pincode;
						$post_field_arr['country'] = 'INDIA';
						$post_field_arr['exam_code'] = $exam_code;
						$post_field_arr['course'] = $exam_name;
						$post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
						$post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));

						$rowdata[] = $post_field_arr; 
					}
				}
				
				//echo'<pre>';print_r($totalRecordCount);
				$result=array(
					"draw"=>$_GET['draw'],
					  "recordsTotal"=>($totalRecordCount),
					  "recordsFiltered"=>($totalRecordCount),
					  "aaData"=>$rowdata,
				 );
		   echo json_encode($result);
		   exit();
	}

	//priyanka d- 17-jan
	public function get_member_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
    {	
		$yesterday = '2022-09-12';
			$recover_images = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
			$scannedphoto_res = $recover_images['scannedphoto'];
			$idproofphoto_res = $recover_images['idproofphoto'];
			$scannedsignaturephoto_res = $recover_images['scannedsignaturephoto'];
			
			if($scannedphoto_res == "" || $idproofphoto_res == "" || $scannedsignaturephoto_res == "")
			{			
				$this->db->where("REPLACE(title,' ','') LIKE '%CSCnonregINSERTArray%'");
				$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,' DATE(date) >= '=>$yesterday));
				
				if(COUNT($user_log) > 0)
				{
					$description = unserialize($user_log[0]['description']);
					$scannedphoto =  $description['scannedphoto'];
					$scannedsignaturephoto =  $description['scannedsignaturephoto'];
					$idproofphoto =  $description['idproofphoto'];
					
					$recover_images2 = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
					$scannedphoto_res = $recover_images2['scannedphoto'];
					$idproofphoto_res = $recover_images2['idproofphoto'];
					$scannedsignaturephoto_res = $recover_images2['scannedsignaturephoto'];
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
			$data['idproofphoto'] = $idproofphoto_res;
			$data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
			return $data;
		}
		public function recover_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
		{	
			//// FOR PHOTO
			if($scannedphoto != '' && $scannedphoto != 'p_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/photograph/".$scannedphoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/photograph/".$scannedphoto,"./uploads/photograph/p_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder Photo rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			//// FOR SIGNATURE
			if($scannedsignaturephoto != '' && $scannedsignaturephoto != 's_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/scansignature/".$scannedsignaturephoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/scansignature/".$scannedsignaturephoto,"./uploads/scansignature/s_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder Signature rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			//// FOR IDPROOF
			if($idproofphoto != '' && $idproofphoto != 'pr_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/idproof/".$idproofphoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/idproof/".$idproofphoto,"./uploads/idproof/pr_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder id proof rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			$extn = '.jpg';
			$member_no = $regnumber;
			
			//// Code for Photo
			$photo_name = $scannedphoto;
			$photo = strpos($photo_name,'photo');
			if($photo == 8)
			{
				$photo_replace = str_replace($photo_name,'p_',$photo_name);
				$updated_photo = $photo_replace.$member_no.$extn;
				
				$update_data = array('scannedphoto' => $updated_photo);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "Photo",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$scannedphoto = $updated_photo;
			} 
			
			//// Code for Signature
			$sign_name = $scannedsignaturephoto;
			$sign = strpos($sign_name,'sign');
			if($sign == 8)
			{
				$sign_replace = str_replace($sign_name,'s_',$sign_name);
				$updated_sign = $sign_replace.$member_no.$extn;
				
				$update_data = array('scannedsignaturephoto' => $updated_sign);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "Signature",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$scannedsignaturephoto = $updated_sign;
			}
			
			//// Code for IDPROOF
			$idproof_name = $idproofphoto;
			$idproof = strpos($idproof_name,'idproof');
			if($idproof == 8)
			{
				$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
				$updated_idproof = $idproof_replace.$member_no.$extn;
				
				$update_data = array('idproofphoto' => $updated_idproof);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "ID Proof",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$idproofphoto = $updated_idproof;
			}
			
			$db_img_path = $image_path; //Get old image path from database
			$scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';
			
			$final_photo_img = '';
			if($scannedphoto != "")
			{
				$photo_img_arr = explode('.', $scannedphoto);
				if(count($photo_img_arr) > 0)
				{
					$chk_photo_img = $photo_img_arr[0];
					
					if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpg'))
					{
						$final_photo_img = $chk_photo_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpeg'))
					{
						$final_photo_img = $chk_photo_img.'.jpeg';
					}
				}
			}
			
			if($final_photo_img == "")
			{
				if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpg'))
				{
					$final_photo_img = "p_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpeg'))
				{
					$final_photo_img = "p_".$member_no.'.jpeg';
				}
			}
				
			
			if($final_photo_img != "") //Check photo in regular folder
			{ 
				$scannedphoto_res = base_url()."uploads/photograph/".$final_photo_img; 
			}
			else if($db_img_path != "") //Check photo in old image path
			{ 
				if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$reg_no.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$regnumber.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$regnumber.".jpg"; 
				}
			}
			else  //Check photo in kyc folder          
			{
				if($reg_no != "" && file_exists(FCPATH."uploads/photograph/k_p_".$reg_no.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads/photograph/k_p_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads/photograph/k_p_".$regnumber.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads/photograph/k_p_".$regnumber.".jpg"; 
				}
			}
			
			
			$final_idproofphoto_img = '';
			if($idproofphoto != "")
			{
				$idproofphoto_img_arr = explode('.', $idproofphoto);
				if(count($idproofphoto_img_arr) > 0)
				{
					$chk_idproofphoto_img = $idproofphoto_img_arr[0];
					
					if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpg'))
					{
						$final_idproofphoto_img = $chk_idproofphoto_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpeg'))
					{
						$final_idproofphoto_img = $chk_idproofphoto_img.'.jpeg';
					}
				}
			}
			
			if($final_idproofphoto_img == "")
			{
				if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpg'))
				{
					$final_idproofphoto_img = "pr_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpeg'))
				{
					$final_idproofphoto_img = "pr_".$member_no.'.jpeg';
				}
			}
			
			
			if ($final_idproofphoto_img != "") //Check id proof in regular folder
			{ 
				$idproofphoto_res = base_url()."uploads/idproof/".$final_idproofphoto_img; 
			}
			else if($db_img_path != "") //Check id proof in old image path
			{ 
				if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"; 
				}
			}
			else //Check photo in kyc folder
			{
				if($reg_no != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$reg_no.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads/idproof/k_pr_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$regnumber.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads/idproof/k_pr_".$regnumber.".jpg"; 
				}
			}
			
			
			$final_scanphoto_img = '';
			if($scannedsignaturephoto != "")
			{
				$scanphoto_img_arr = explode('.', $scannedsignaturephoto);
				if(count($scanphoto_img_arr) > 0)
				{
					$chk_scanphoto_img = $scanphoto_img_arr[0];
					
					if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpg'))
					{
						$final_scanphoto_img = $chk_scanphoto_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpeg'))
					{
						$final_scanphoto_img = $chk_scanphoto_img.'.jpeg';
					}
				}
			}
			
			if($final_scanphoto_img == "")
			{
				if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpg'))
				{
					$final_scanphoto_img = "s_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpeg'))
				{
					$final_scanphoto_img = "s_".$member_no.'.jpeg';
				}
			}				
				
			if ($final_scanphoto_img != "") //Check signature in regular folder
			{ 
				$scannedsignaturephoto_res = base_url()."uploads/scansignature/".$final_scanphoto_img; 
			}
			else if($db_img_path != "") //Check signature in old image path
			{ 
				if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$reg_no.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$regnumber.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$regnumber.".jpg"; 
				}
			}
			else //Check signature in kyc folder
			{
				if($reg_no != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$reg_no.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$regnumber.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$regnumber.".jpg"; 
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
			$data['idproofphoto'] = $idproofphoto_res;
			$data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
			return $data;
		}
	//priyanka d - 18-jan
	public function download_CSV_of_examdata()

	{
		if(empty($_GET['member_ids']) && empty($_GET['exam_date']) && empty($_GET['register_date']))
			return 1;
		if(isset($_GET['member_ids']) && !empty($_GET['member_ids'])) {
			$member_nos=explode(',',$_GET['member_ids']);
					foreach($member_nos as $m)
						$member_no[]=trim($m);
		}
		if(isset($_GET['exam_date']) && !empty($_GET['exam_date'])) {
			$exam_date=date('Y-m-d',strtotime($_GET['exam_date']));
		}
		if(isset($_GET['register_date']) && !empty($_GET['register_date'])){
			$register_date=date('Y-m-d',strtotime($_GET['register_date']));
		}
		//$member_no = array(802164364);

		$select = 'DISTINCT(b.transaction_no),a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';

	
		$this->db->where("((a.exam_code = '991' AND bankcode = 'csc') OR (a.exam_code = '997'))");
		
		if(isset($_GET['register_date']) && !empty($_GET['register_date']))
					$this->db->where('date(a.created_on)',$register_date);
		if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
			$this->db->where_in('a.regnumber',$member_no);
		if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
			$this->db->where('d.exam_date',$exam_date);
			else
			$this->db->where('d.exam_date >=',date('Y-m-d'));
		$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
		$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
		$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
		$this->db->where('remark',1);
		$this->db->where('pay_type',2);
		$this->db->where('status',1);
		$this->db->where('isactive','1');
		$this->db->where('isdeleted',0);
		$this->db->where('pay_status',1);
		
		
		$can_exam_data = $this->Master_model->getRecords('member_exam a', '', $select);	
				
	//	echo $this->db->last_query(); //exit;
		//echo "<pre>"; print_r($can_exam_data); echo "</pre>"; exit;
		$exam_cnt = 0;
		$rowdata=array();
		if (count($can_exam_data)) 
				{
					$i = 1;					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
						
						//ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
						$member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);
						$scannedphoto = $member_images['scannedphoto'];
						$scannedsignaturephoto = $member_images['scannedsignaturephoto'];
						$idproofphoto = $member_images['idproofphoto'];
									
									
						 
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
						{
							$ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
							if(count($ex_code)) 
							{
								if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
												{
								$exam_code = $ex_code[0]['original_val'];
												} 
								else 
								{
								$exam_code = $exam['exam_code'];
												}
											}
							else 
							{
								$exam_code = $exam['exam_code'];
											}
										} 
							else 
										{
							$exam_code = $exam['exam_code'];
										}
										
										$dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
							$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
							
							$address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
							$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
							$gender = $exam['gender'];
							if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
							$designation = $this->master_model->getRecords('designation_master');
							if (count($designation)) 
										{
							foreach ($designation as $designation_row) 
											{
								if ($exam['designation'] == $designation_row['dcode']) 
												{
								$designation_name = $designation_row['dname'];
												}
											}
										}
							$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
										
										$medium = $this->master_model->getRecords('medium_master');
							if(count($medium)) 
										{
							foreach ($medium as $medium_row) 
											{
								if ($exam['exam_medium'] == $medium_row['medium_code']) 
												{
								$medium_name = $medium_row['medium_description'];
												}
											}
										}
							$medium_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
							
							$institution_master = $this->master_model->getRecords('institution_master');
							if(count($institution_master)) 
										{
							foreach ($institution_master as $institution_row) 
											{
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
												{
								$institution_name = $institution_row['name'];
												}
											}
										}
							$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
							$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
							$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
							$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
							$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
							$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
										
										$exam_arr = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');            
							foreach ($exam_arr as $k => $val) 
										{
							if ($exam_code == $k)  
											{
								$exam_name = $val;
											}
										}
										
							$select    = 'regnumber';
							$this->db->where_in('exam_code', 991);
							$this->db->where_in('regnumber', $exam['regnumber']);
							$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
							$attempt_count = count($attempt_count);            
							$attempt_count = $attempt_count - 1;
							
										$post_field_arr['first_name'] = $firstname;
							$post_field_arr['middle_name'] = $middlename;
							$post_field_arr['last_name'] = $lastname;
							$post_field_arr['member_number'] = $exam['regnumber'];
							$post_field_arr['password'] = $exam['pwd'];
							$post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
							$post_field_arr['gender'] = $gender;
							$post_field_arr['email_id'] = $exam['email'];
							$post_field_arr['mobile'] = $mobile;
							$post_field_arr['address'] = $address;
							$post_field_arr['state'] = $exam['state'];
							$post_field_arr['pin_code'] = $pincode;
							$post_field_arr['country'] = 'INDIA';
							$post_field_arr['profession'] = '';
							$post_field_arr['organization'] = $institution_name;
							$post_field_arr['designation'] = $designation_name;
							$post_field_arr['exam_code'] = $exam_code;
							$post_field_arr['course'] = $exam_name;
							$post_field_arr['elective_sub_code'] = $subject_code;
							$post_field_arr['elective_sub_desc'] = $subject_description;
							$post_field_arr['attempt'] = $attempt_count;
							$post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
							$post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));
							$post_field_arr['batch_start_time'] = $exam['time'];
							$post_field_arr['exam_medium'] = $medium_name;
							$post_field_arr['exam_center_code'] = $exam['exam_center_code'];
							$post_field_arr['venue_code'] = $exam['venueid'];
							$post_field_arr['server_url'] = $server_url;
							$post_field_arr['p_image'] = $scannedphoto;
							$post_field_arr['s_image'] = $scannedsignaturephoto;
							$post_field_arr['pr_image'] = $idproofphoto;
										
							
						
						$i++;
						$exam_cnt++;
						
						
						
						if(strpos($exam['scannedphoto'], "k_") !== false){
							$alternate_scannedphoto	=	base_url()."uploads/photograph/".str_replace("k_","",$exam['scannedphoto']);
						}
						else
						$alternate_scannedphoto	=	base_url()."uploads/photograph/".'k_'.$exam['scannedphoto'];
						
						if(strpos($exam['idproofphoto'], "k_") !== false){
							$alternate_idproofphoto	=	base_url()."uploads/idproof/".str_replace("k_","",$exam['idproofphoto']);
						}
						else
						$alternate_idproofphoto	=	base_url()."uploads/idproof/".'k_'.$exam['idproofphoto'];

						if(strpos($exam['scannedsignaturephoto'], "k_") !== false){
							$alternate_scannedsignaturephoto	=	base_url()."uploads/scansignature/".str_replace("k_","",$exam['scannedsignaturephoto']);
						}
						else
						$alternate_scannedsignaturephoto	=	base_url()."uploads/scansignature/".'k_'.$exam['scannedsignaturephoto'];

						$append_img_log =  $exam['regnumber'].' | '. $scannedphoto. ' | '.$scannedsignaturephoto.' | '.$idproofphoto .' || '.$alternate_scannedphoto.' | '.$alternate_scannedsignaturephoto.' | '.$alternate_idproofphoto;
						
						
						$post_field_arr['k_p_image']	=	$alternate_scannedphoto;
						$post_field_arr['k_s_image']	=	$alternate_scannedsignaturephoto;
						$post_field_arr['k_pr_image']	=	$alternate_idproofphoto;
						$api_data_arr[] = $post_field_arr; 
					}
					$csv='first_name,	middle_name	,last_name	,member_number,password,dob,gender,email_id,mobile,address,state,pin_code,country,profession,organization,designation,exam_code,course,elective_sub_code,elective_sub_desc,attempt,registration_date,exam_date,batch_start_time,exam_medium,exam_center_code,venue_code,server_url,p_image,s_image,pr_image,k_p_image,k_s_image,k_pr_image';
					//echo'<pre>';print_r($api_data_arr);exit;
					foreach($api_data_arr as $currDatas) {
						$csv.="\n";
						foreach($currDatas as $currData)
							$csv.=$currData.',';
							
					}
					
				}
		 
			//echo $csv;exit;
        $filename = "csc_exam_register_data_".date('Y-m-d').".csv";

		header('Content-type: application/csv');

		header('Content-Disposition: attachment; filename='.$filename);

		$csv_handler = fopen('php://output', 'w');

 		fwrite ($csv_handler,$csv);

 		fclose ($csv_handler);

	}

    

	public function upload_csv()
	{
		return 1; // disabled this function as npot much needed = priyanka d -18-jan-23

		$duplicate_venue=$oldcapacity_greater=array();
		$count_upadted=0;
		$count_upadted_not=0;
		$csv_file_read='';
		$data['error'] = $data['success'] = '';
		if($_FILES['userfile']['name'] != "")
		{
			$file_ext = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));
			 $new_filename = 'file_name_test_'.rand().date('YmdHis');
			
                        $userfile = $this->upload_file("userfile", array('csv'),$new_filename, "./uploads/venue_master/", "csv");
                        if($userfile['response'] == 'error')
                        {
                            $data['error'] = $userfile['message'];
                            //$error_flag = 1;
						}
                        else if($userfile['response'] == 'success')
                        {
                            $data['success'] = 'File Uploaded successfully.';
							  $csv_file_read = $userfile['message']; 
                          
						}
		}
		 $csv_file = "./uploads/venue_master/".$csv_file_read; 
		if(file_exists($csv_file))
		{
			//echo $string = read_file('./uploads/venue_master/'.$csv_file_read); die;
			$handle = fopen($csv_file, 'r');
			//print_r($handle); die;
			if (($handle = fopen($csv_file, "r")) !== FALSE) {
		   fgetcsv($handle);   
		   while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
			   
			 $num = count($data); 
			for ($c=0; $c < $num; $c++) {
			  $col[$c] = $data[$c];
			}
			 //print_r($data); echo '<pre>';
			 //print_r($col); die;
			 $originalDate = mysql_real_escape_string($col[1]);
			 $newDate = date("Y-m-d", strtotime($originalDate));
			 $vendor_code = mysql_real_escape_string($col[0]);
			 $exam_date = $newDate; 
			 $center_code = mysql_real_escape_string($col[2]); 
			 $session_time = mysql_real_escape_string($col[3]); 
			 $session_capacity = mysql_real_escape_string($col[4]); 
			 $venue_code = mysql_real_escape_string($col[5]); 
			 $venue_name = mysql_real_escape_string(trim($col[6])); 
			 $venue_addr1 = mysql_real_escape_string(trim($col[7]));  
			 $venue_addr2 = mysql_real_escape_string(trim($col[8]));  
			 $venue_addr3 = mysql_real_escape_string(trim($col[9])); 
			 $venue_addr4 = mysql_real_escape_string(trim($col[10])); 
			 $venue_addr5 = mysql_real_escape_string(trim($col[11])); 
			 $venue_pincode = mysql_real_escape_string(trim($col[12])); 
			 $pwd_enabled = mysql_real_escape_string(trim($col[13])); 
			
			$this->session->set_userdata('enduserinfo', $session_capacity);
			
			//checks on venue duplication
			$old_venue_details = $this->master_model->getRecords('venue_master_chaitali',
				array('center_code'=>$center_code,
					'venue_code'=>$venue_code,
					'exam_date'=>$exam_date,
					'session_time'=>$session_time
				));
				
				if(!empty($session_time))
				{
					
					if(strpos($session_time, '.') == true)
					{
						$time = str_ireplace(".",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//print_r($query); exit;
						//print_r($query['venue_master_id']); exit;
						$last_id = $query['venue_master_id'];
						//log query
						$log_message = serialize($query);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
						//print_r($logs); exit;
						$this->master_model->updateRecord('venue_master_replace_time', $logs, array(
						'venue_master_id'=>$last_id
							));						
						//$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
									 
					
					}
					else if(strpos($session_time, '::') == true)
					{
						$time = str_ireplace("::",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$last_id = $query['venue_master_id'];
						//log query
						$log_message = serialize($query);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
						//print_r($logs); exit;
						$this->master_model->updateRecord('venue_master_replace_time', $logs, array(
						'venue_master_id'=>$last_id
							));						
						
						
					}else if(strpos($session_time, '.:') == true)
					{
						$time = str_ireplace(".:",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$last_id = $query['venue_master_id'];
						//log query
						$log_message = serialize($query);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
						//print_r($logs); exit;
						$this->master_model->updateRecord('venue_master_replace_time', $logs, array(
						'venue_master_id'=>$last_id
							));						
						
					}
					else{
						
						$time = $session_time;
						
					}
					
				}
				//echo $time; exit;
				//Time check in csv
				
			if(isset($_POST['btnAdd']))
			{	
				//print_r(count($old_venue_details)); die;
					if(count($old_venue_details)<1)	
					{
						
					$query = "INSERT INTO venue_master_chaitali(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
				
					 
					$s     = mysql_query($query);
					//print_r($s); exit;
					}
					else if(count($old_venue_details)>1)
					{
						
						$duplicate_venue[]=$old_venue_details;
					}
			}
			else if(isset($_POST['btnUpdate']))
			{
				if(count($old_venue_details)==1)
				{
					if($old_venue_details[0]['session_capacity'] <=$session_capacity)
					{
						$update_data = array('session_capacity'=>$session_capacity);
						$this->master_model->updateRecord('venue_master_chaitali',$update_data, array('center_code'=>$center_code,
							'venue_code'=>$venue_code,
							'exam_date'=>$exam_date,
							'session_time'=>$time
						));
						
						$count_upadted++;
					}elseif($old_venue_details[0]['session_capacity'] >=$session_capacity)
					{
						$oldcapacity_greater[]=$old_venue_details;
					}
					$count_upadted_not++;
				}elseif(count($old_venue_details)>1)
				{
					//$count_upadted_not++;
					$duplicate_venue[]=$old_venue_details;
				
				}
			}
		}
		fclose($handle);
		foreach($duplicate_venue as $rec2)
		{
			foreach($rec2 as $val2)
			{
				$query = "INSERT INTO venue_master_duplicate_record(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
				
				//echo $val2['venue_code'].' '.$val2['exam_date'].' '.$val2['center_code'].' '.$val2['session_time'].' '.$val2['session_capacity'];
				//echo '<br>';
			}
		}
	}
	//echo "File data successfully imported to database!!";
	}
		
		$this->load->view('admin/venue_master/upload_csv', $data);
               

	}

	public function download_CSV($exm_prd,$exm_cd)

	{

		 $csv = " Examination Counts (Center Wise ) - ".date('Y-m-d')." \n\n";

		$csv.= "Vendor code,Exam date,Center code,Session time,Session capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Center name,Registered count,Balance capacity,Occupied \n";

		 //$csv.= "Vendor code,Center code,Center name,Venue code,Venue name,Exam date,Session time,Session capacity,Registered count,Balance capacity,Occupied \n";	 $exm_cd=$exm_prd='';

		 $exm_prd=$this->uri->segment(5);

		 $exm_cd=$this->uri->segment(6);



		$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") AND `exam_period` IN (".$exm_prd.")");



			$subdate = $subquery->result_array();

			if(!empty($subdate))

			{

			foreach($subdate  as $val)

			{

				$exam_date[]=$val['exam_date'];

			}

		$datearray=implode("','",$exam_date);

			

if($_SESSION['username']=='NseitAdmin')

{		

	$query = $this->db->query(	"

SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

as occupied

  FROM `admit_card_details` RIGHT JOIN venue_master

 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."') and venue_master.vendor_code  = '3'

GROUP BY 

venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");

}

elseif($_SESSION['username']=='SifyAdmin')

{

			

	$query = $this->db->query(	"

SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

as occupied

  FROM `admit_card_details` RIGHT JOIN venue_master

 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."') and venue_master.vendor_code  = '1'

GROUP BY 

venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");}

else

{

	

	

	$query = $this->db->query(	"SELECT venue_master.pwd_enabled,venue_master.venue_pincode,venue_master.venue_addr5,venue_master.venue_addr4,venue_master.venue_addr3,venue_master.venue_addr2,venue_master.venue_addr1,venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity as occupied
	    FROM `admit_card_details` RIGHT JOIN venue_master ON admit_card_details.center_code =venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."')GROUP BY venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");



//echo $this->db->last_query();

//exit;

	}		

			/*	$query = $this->db->query(" 

				 SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

 as occupied FROM venue_master LEFT JOIN 

 admit_card_details ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND admit_card_details.remark = 1 AND admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.")  Where venue_master.exam_date  IN ('".$datearray."') GROUP BY 

 venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ORDER BY venue_name ASC");*/

				$result = $query->result_array();

				

			

		foreach($result as $record)

		{

			

						 $center_name = $this->Master_model->getRecords("center_master", array(

												'center_code' => $record['center_code'],'exam_period'=>$exm_prd

												), 'center_name', array(

												'center_name' => 'ASC'

												));

									if(!empty($center_name))

									{

										$center_name= $center_name[0]['center_name'];

									}

			// print_r($record);exit; 

			

		

			 $csv.= $record['vendor_code'].','.$record['exam_date'].','.$record['center_code'].',"'.$record['session_time'].'",'.$record['session_capacity'].','.$record['venue_code'].','.$record['venue_name'].','.str_replace(","," ",$record['venue_addr1']).','.str_replace(","," ",$record['venue_addr2']).','.str_replace(","," ",$record['venue_addr3']).','.str_replace(","," ",$record['venue_addr4']).','.str_replace(","," ",$record['venue_addr5']).','.$record['venue_pincode'].','.$record['pwd_enabled'].','.$center_name.','. 

			 $record['registered_count'].','.$record['balance_capacity'].','.round($record['occupied'],2) .'%'."\n";

	

			// $csv.= $record['vendor_code'].','.$record['center_code'].','.$center_name.','.$record['venue_code'].',"'.$record['venue_name'].'",'.$record['exam_date'].','.$record['session_time'].','.$record['session_capacity'].','.$record['registered_count'].','.$record['balance_capacity'].','.round($record['occupied'],2) .'%'."\n";

		}

			}

        $filename = "Examination_Counts_(center_wise)_".date('Y-m-d').".csv";

		header('Content-type: application/csv');

		header('Content-Disposition: attachment; filename='.$filename);

		$csv_handler = fopen('php://output', 'w');

 		fwrite ($csv_handler,$csv);

 		fclose ($csv_handler);

	}


  public function get_multiple_exam()

	{





        if (isset($_POST["period"]) && !empty($_POST["period"]))

		{

            $period = $this->input->post('period');

			

			$array_period = explode(',', $period);

			//check specail exam also

		$exam_activation=  $exam_activation_nr=$exam_activation_sp=$new_array=array();







			

			//print_r($array_period );



			 $C_date =date('Y-m-d');

		 $date =  date('Y-m-d', strtotime($C_date .' -15 day'));

		// $exam_date=date('Y-m-d')-5;

		 

		 $this->db->where_in('exam_period',$array_period);

		   $exam_activation_nr = $this->Master_model->getRecords("exam_activation_master", array('exam_to_date >=' => $date),'exam_code');

		   

$this->db->where_in('period',$array_period);

		   $exam_period = $this->Master_model->getRecords("special_exam_dates");

							   

					if(!empty($exam_period))

					{

						

						foreach($exam_period as $val )

						{

							$array_date[]=$val['examination_date'];

						}

						$select="DISTINCT(`exam_code`)";

						 $this->db->where_in('examination_date',$array_date);

		   $exam_activation_sp = $this->Master_model->getRecords("member_exam",'',$select);	

		   

		 

}

$exam_activation1=array_merge($exam_activation_nr,$exam_activation_sp);



//print_r($exam_activation1);

foreach($exam_activation1 as $val)

{

	$new_array[]=$val['exam_code'];

}

 $exam_activation = array_unique($new_array);

						

                if(!empty($exam_activation))

				{

									 $i=1;

								   foreach($exam_activation as $val)

								   {

									  

									   			$exam_name = $this->Master_model->getRecords("exam_master", array(

												'exam_code' => $val

												), 'description,exam_category', array(

												'description' => 'ASC'

												));

						

										if(!empty($exam_name))

										{

										

										  echo '<option value="' . $val. '">' . $exam_name[0]['description'] . '</option>';

								

             

             

										}

										$i++;

									}

									

							   }else {

							   

					

                    echo '-No result found-';

                }



        }

    }

 
public function upload_file($input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0)
        {
            $flag = 0;
            if($is_multiple == 0) { $path_img = $_FILES[$input_name]['name']; }
            else { $path_img = $_FILES[$input_name]['name'][$cnt]; }
           
            $ext_img = pathinfo($path_img, PATHINFO_EXTENSION);
            $valid_ext_arr = $valid_arr;
           
            if(!in_array(strtolower($ext_img),$valid_ext_arr))
            {
                $flag=1;
            }
           
            if($flag == 0)
            {
                $chk_upload_dir = './uploads';
                if(!is_dir($chk_upload_dir))
                {
                    $dir = mkdir($chk_upload_dir,0755);
                   
                    $myfile0 = fopen($chk_upload_dir."/index.php", "w") or die("Unable to open file!");
                    $txt0 = "";
                    fwrite($myfile0, $txt0);               
                    fclose($myfile0);
                }
               
                if(is_dir($upload_path)){ }
                else
                {
                    $dir=mkdir($upload_path,0755);
                   
                    $myfile = fopen($upload_path."/index.php", "w") or die("Unable to open file!");
                    $txt = "";
                    fwrite($myfile, $txt);               
                    fclose($myfile);
                }   
               
                $file=$_FILES;   
                if($is_multiple == 0) { $_FILES['file_upload']['name'] = $file[$input_name]['name']; }
                else { $_FILES['file_upload']['name'] = $file[$input_name]['name'][$cnt]; }
               
                $filename = $new_file_name;
                $path = $_FILES['file_upload']['name'];
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));                           
                $final_img = $filename.".".$ext;                   
               
                $config['file_name']     = $final_img;
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = $allowed_types;
               
                $this->upload->initialize($config);                   
               
                if($is_multiple == 0)
                {
                    $_FILES['file_upload']['type']=$file[$input_name]['type'];
                    $_FILES['file_upload']['tmp_name']=$file[$input_name]['tmp_name'];
                    $_FILES['file_upload']['error']=$file[$input_name]['error'];
                    $_FILES['file_upload']['size']=$file[$input_name]['size'];
                }
                else
                {
                    $_FILES['file_upload']['type']=$file[$input_name]['type'][$cnt];
                    $_FILES['file_upload']['tmp_name']=$file[$input_name]['tmp_name'][$cnt];
                    $_FILES['file_upload']['error']=$file[$input_name]['error'][$cnt];
                    $_FILES['file_upload']['size']=$file[$input_name]['size'][$cnt];
                }
               
                if($this->upload->do_upload('file_upload'))
                {
                    $data=$this->upload->data();
                    return array('response'=>'success','message' => $final_img);
                }
                else
                {
                    return array('response'=>'error','message' => $this->upload->display_errors());
                }
            }
            else
            {
                return array('response'=>'error','message' => "Please upload valid ".str_replace('|',' | ',$allowed_types)." extension image.");
            }
        }
	
public function session_time_check($session_time)
{
	 $session_time = $this->session->userdata['session_time'];
	if(!empty($session_time))
				{
					
					if(strpos($session_time, '.') == true)
					{
						$time = str_ireplace(".",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$log_message = serialize($s);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
								
						$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
							return true;		 
					
					}
					else if(strpos($session_time, '::') == true)
					{
						$time = str_ireplace("::",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$log_message = serialize($s);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
								
						$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
						return true;
						
					}else if(strpos($session_time, '.:') == true)
					{
						$time = str_ireplace(".:",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$log_message = serialize($s);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
								
						$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
						return true;
					}
					else{
						
						$time = $session_time;
						return true;
					}
					
				}
				//echo $time; exit;
}

public function error_report()
{
	
	 $csv = "Exam date,Center code,Session time,Session capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Vendor code\n";//Column headers
		
		
		$query = $this->db->query("SELECT * FROM `venue_master_replace_time");
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.=$record['exam_date'].','.$record['center_code'].','.$record['session_time'].',"'.$record['session_capacity'].'","'.$record['venue_code'].'","'.$record['venue_name'].'",'.$record['venue_addr1'].','.$record['venue_addr2'].',"'.$record['venue_addr3'].'","'.$record['venue_addr4'].'","'.$record['venue_addr5'].'","'.$record['venue_pincode'].'","'.$record['pwd_enabled'].'",'.$record['vendor_code']."\n";
		}
		$sr ++;
		$filename = "error_report.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
}
public function duplicate_record()
{
	 $csv = "Exam date,Center code,Session time,Session capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Vendor code\n";//Column headers
		
		
		$query = $this->db->query("SELECT * FROM `venue_master_duplicate_record");
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.=$record['exam_date'].','.$record['center_code'].','.$record['session_time'].',"'.$record['session_capacity'].'","'.$record['venue_code'].'","'.$record['venue_name'].'",'.$record['venue_addr1'].','.$record['venue_addr2'].',"'.$record['venue_addr3'].'","'.$record['venue_addr4'].'","'.$record['venue_addr5'].'","'.$record['venue_pincode'].'","'.$record['pwd_enabled'].'",'.$record['vendor_code']."\n";
		}
		$sr ++;
		$filename = "duplicate_record.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
}

public function delete_data()
{
	$time_table = $this->db->query("DELETE FROM `venue_master_replace_time`");
	$deletetime = $time_table->result_array();
	
	$dup_table = $this->db->query("DELETE FROM `venue_master_duplicate_record`");
	$deletedup = $dup_table->result_array();
	//$time_table = "DELETE FROM `venue_master_replace_time`";
}	
}

