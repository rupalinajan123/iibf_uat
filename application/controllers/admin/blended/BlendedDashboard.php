<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BlendedDashboard extends CI_Controller
{
    public function __construct(){
        parent::__construct();
		if($this->session->id==""){
			redirect('blended_login/admin/login'); 
		}
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
		
    }
    public function index()
	{
		if (isset($_POST['btnSearch'])) 
		{	
			$program_code = $_POST["program_code"];
			$batch_code = $_POST["batch_code"];
			$training_type = $_POST['training_type'];  
			$zone_code = $_POST['zone_code'];
			$center_code = $_POST['center_code'];
			
			if($program_code != ""){$this->db->where('program_code', $program_code);}
			if($zone_code != ""){$this->db->where('zone_code', $zone_code);}
			if($center_code != ""){$this->db->where('center_code', $center_code);}
			if($batch_code != ""){$this->db->where('batch_code', $batch_code);}
			if($training_type != ""){$this->db->where('training_type', $training_type);}
			
			
			$this->db->where('blended_registration.pay_status',1);
			$this->db->where('payment_transaction.status',1);
			$this->db->where('exam_invoice.app_type','T');
			$this->db->where('payment_transaction.pay_type',10);
			$this->db->where('exam_invoice.invoice_image !=', '');
			$this->db->where('payment_transaction.transaction_no !=', '');
			$this->db->join('payment_transaction','blended_registration.blended_id = payment_transaction.ref_id', 'left');
			$this->db->join('exam_invoice', 'payment_transaction.id = exam_invoice.pay_txn_id', 'left');
			$mem_info = $this->master_model->getRecords('blended_registration','','createdon,blended_registration.member_no,program_code,batch_code,zone_code,training_type,blended_registration.center_code,start_date,end_date,invoice_image,attempt');
			//echo $this->db->last_query();
			$data['mem_info'] = $mem_info;
			$this->load->view('admin/blended_dashboard/member_list',$data);
		}
		else
		{
			$this->db->where('blended_registration.pay_status',1);
			$this->db->where('payment_transaction.status',1);
			$this->db->where('exam_invoice.app_type','T');
			$this->db->where('payment_transaction.pay_type',10);
			$this->db->where('exam_invoice.invoice_image !=', '');
			$this->db->where('payment_transaction.transaction_no !=', '');
			$this->db->join('payment_transaction','blended_registration.blended_id = payment_transaction.ref_id', 'left');
			$this->db->join('exam_invoice', 'payment_transaction.id = exam_invoice.pay_txn_id', 'left');
			$mem_info = $this->master_model->getRecords('blended_registration','','createdon,blended_registration.member_no,program_code,batch_code,zone_code,training_type,blended_registration.center_code,start_date,end_date,invoice_image,attempt');
		  	$data['mem_info'] = $mem_info;
		  	$this->load->view('admin/blended_dashboard/member_list',$data);
		}  
    }
	public function counts()
	{
		
		$countQry     = $this->db->query("SELECT batch_code, zone_code,training_type, program_code,COUNT(*) AS Counts,center_name,start_date,end_date FROM blended_registration WHERE pay_status = 1 GROUP BY batch_code ORDER BY blended_registration.batch_code DESC");
		$countArr     = $countQry->result_array();
		
		
		
							//check traning is activated or not 
		$traninginfo=array();
		$q2    = $this->db->query("SELECT * FROM `blended_program_activation_master`");
		$traninginfo    = $q2->result_array();
	
		$data['counts'] = $countArr;
		$data['traninginfo'] = $traninginfo;
		$this->load->view('admin/blended_dashboard/counts',$data);
		
	}
	
		public function download_CSV($batch_code,$training_type,$center_name)
	{
		 $csv = " Blended Course member registration details for ".$center_name."  ".$batch_code." ".$training_type." \n\n";
		 $csv.= "Sr no.,Membership no.,Name sub,First Name,Last Name,Bank Name,Email,Mobile,Fee,Attempt \n";//Column headers
	

	$subquery = $this->db->query(" SELECT member_no,namesub,firstname,lastname,name,email,mobile,fee,attempt  FROM `blended_registration` LEFT JOIN institution_master ON blended_registration.associatedinstitute=institution_master.institude_id WHERE   `training_type` LIKE '".$training_type."' AND `batch_code` LIKE '".$batch_code."' AND `pay_status` = 1
");

			$result = $subquery->result_array();
			
	
			if(!empty($result))
			{
				$i=1;
		foreach($result as $record)
		{
			
					
			// print_r($record);exit;
			 $csv.= $i.','.$record['member_no'].','.$record['namesub'].',"'.$record['firstname'].'",'.$record['lastname'].','.$record['name'].','.$record['email'].','.$record['mobile'].','.$record['fee'].','.$record['attempt']."\n";
			 $i++;
		}
	}
        $filename = "Blended_course_member_registration_details_for_".$center_name."_".$batch_code."_".$training_type.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}


	  public function cc_member_list()
    {
	
	
	#--------------------contcat classes --------------------------#
		//SELECT *  FROM `contact_classes_registration` WHERE `program_code` LIKE '20' AND `program_prd` = 417 AND `pay_status` = 1
		$course_info=array();
	//	$this->db->where('course_code', $courcecode);
		$this->db->group_by('course_code,exam_prd'); 
	
 		$course_info = $this->master_model->getRecords('contact_classes_cource_activation_master','','course_code,exam_prd');
	foreach($course_info  as $val)
	{
		$program_code[]=$val['course_code'];
		$program_prd[]=$val['exam_prd'];
		
	}

	
		$this->db->where_in('program_code',$program_code);
		$this->db->where_in('program_prd',$program_prd);
		$this->db->where('pay_status',1);
          $cc_mem_info = $this->master_model->getRecords('contact_classes_registration');
	
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_mem_info'] = $cc_mem_info;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('admin/blended_dashboard/cc_member_list',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }
		  public function cc_subject_list()
    {
	
	
	#--------------------contcat classes --------------------------#
		
		$cc_subject=$this->db->query(	"SELECT contact_classes_Subject_registration.`sub_code`,contact_classes_Subject_registration.`sub_name`,contact_classes_Subject_registration.`center_code`,count(contact_classes_Subject_registration.`id`)as total_reg,capacity FROM `contact_classes_Subject_registration` INNER JOIN contact_classes_subject_master ON contact_classes_subject_master.exam_prd=contact_classes_Subject_registration.`program_prd` AND contact_classes_subject_master.sub_code=contact_classes_Subject_registration.`sub_code` AND contact_classes_subject_master.center_code=contact_classes_Subject_registration.`center_code` WHERE contact_classes_Subject_registration.`program_prd`IN(220) AND contact_classes_Subject_registration.`program_code` IN(21,60) group by `program_code`,`program_prd`,`sub_code`,`center_code`");
						$cc_subject_list=  $cc_subject->result_array();
		  
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_subject_list'] = $cc_subject_list;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('admin/blended_dashboard/cc_subject_count',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }	
#-----------------------Deactive batch--------------------------------#
public function Deactive_batch()
{
	//echo '000';
	if(isset($_GET['batch_code']))
	{
		$batch_code=$_GET['batch_code'];
	
	
	//Offline mail table update 
	  $this->master_model->updateRecord('offline_email_master', array('isdelete' =>1) , array('batch_code' =>$batch_code, 'isdelete' =>0));
	  
	 //blended_dates
	   $this->master_model->updateRecord('blended_dates', array('isdelete' =>1) , array('batch_code' =>$batch_code, 'isdelete' =>0));
	   
	  //blended_fee_master
	  $this->master_model->updateRecord('blended_fee_master', array('fee_delete' =>1) , array('batch_code' =>$batch_code, 'fee_delete' =>0));

	//blended_program_activation_master
	 $this->master_model->updateRecord('blended_program_activation_master', array('program_activation_delete' =>1) , array('batch_code' =>$batch_code, 'program_activation_delete' =>0));
	 
	 //blended_venue_master
	 $this->master_model->updateRecord('blended_venue_master', array('isdeleted' =>1) , array('batch_code' =>$batch_code, 'isdeleted' =>0));

	}
}
}
