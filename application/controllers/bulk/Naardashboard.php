<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Naardashboard extends CI_Controller

{

    public function __construct()

    {

        parent::__construct();

        $this->load->model('UserModel');

        $this->load->model('Master_model');

        $this->load->helper('pagination_helper');

        $this->load->library('pagination');

        $this->load->helper('upload_helper');

        $this->load->library('email');

        $this->load->model('Emailsending');

		//$this->load->model('chk_session');

		//$this->chk_session->chk_bank_login_session();

    }

	   

	/* Bulk Dashboard */

    public function index()

    {

		$check_exam_activation=bulk_check_exam_activate(1015);

		

	    $exam_period='';

		$inst_id=10004;

	    $examinfo = $this->session->userdata('exmCrdPrd');

		$exam_code= 1015;

		//$exam_period=$examinfo['exam_prd'];

		$this->db->where('exam_code',1015);

		$act_info=$this->master_model->getRecords('bulk_exam_activation_master','','exam_period');

		$exam_period=$act_info[0]['exam_period'];

		

		

		$exam_data=array('exam_code'=>$exam_code,'exam_prd'=>$exam_period,'Extype'=>2);	

		$this->session->set_userdata('exmCrdPrd',$exam_data);

		

		$exm_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code),'description');

		$member_list=array();

	    $categories=array(0, 2,3);

		

		$this->db->where_in('pay_status', $categories);
		$this->db->join('admit_card_details','member_exam.id=admit_card_details.mem_exam_id');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

		$this->db->join('center_master','center_master.center_code = member_exam.exam_center_code');

		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

		$this->db->where("center_master.exam_name",$exam_code);

		$this->db->where("center_master.exam_period",$exam_period);

		$member_list=$this->master_model->getRecords('member_exam',array('institute_id'=>$inst_id,'member_exam.exam_code'=>$exam_code,'member_exam.exam_period'=>$exam_period,'bulk_isdelete' =>0),'member_exam.id,member_exam.regnumber,member_exam.exam_center_code, center_master.center_name, member_exam.base_fee,member_exam.pay_status,member_exam.exam_period'); 
		// echo $this->db->last_query();die();
		//'member_exam.exam_period'=>$exam_period,

		 

		

		$is_exam_activated = 1;

		if($check_exam_activation['flag']==0) //['flag']

		{

			$is_exam_activated = 0;

		}

		if($exam_code=='')

		{

			redirect(base_url().'bulk/BulkApply/examlist/');	

		}

		

		

		//$result = $this->master_model->getRecords('member_exam');

		$data  = array('middle_content'=>'bulk/exam_naarapplicantlst','exam_name'=>$exm_name[0]['description'],'member_list'=>$member_list,'exam_period'=>$exam_period,'exam_code'=>$exam_code,'is_exam_activated'=>$is_exam_activated);

		//$data  = array('middle_content' => 'bulk/naar_dashboard');

		$this->load->view('bulk/bulk_naarcommon_view', $data);

    }

	

	/* Bank Profile */

	public function view_profile()

    {

		if(empty($this->session->userdata['institute_id']))

		{

			redirect(base_url().'bulk/Banklogin');

		}

			

			$instid = $_SESSION['institute_id'];

			$instRes = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code '=>$instid));

			

			

		$data  = array('middle_content' => 'bulk/view_profile','instRes'=>$instRes);

       	    $this->load->view('bulk/bulk_common_view', $data);

		//$this->load->view('bulk/view_profile');

    }

	/* Bank Logout */

	public function  logout()

    {

		$data  = array('middle_content' => 'bulk/view_profile');

       	    $this->load->view('bulk/bulk_common_view', $data);

		//$this->load->view('bulk/view_profile');

    }

	

	/* Apply Exam Function */

  	public function exam_applicantlst()

    {

		$data  = array('middle_content' => 'bulk/exam_applicantlst');

       	    $this->load->view('bulk/bulk_common_view', $data);

		//$this->load->view('bulk/exam_applicantlst');

    }

	

	/* Transcation Function */

	public function transcation()

    {

		$data  = array('middle_content' => 'bulk/transaction/transactions');

       	$this->load->view('bulk/bulk_common_view', $data);

		//$this->load->view('bulk/transaction/transactions');

    }

	

	/* Add member  Function */

public function add_member()

{

	if(isset($_POST['getdata']))

	{

		$this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');

		if ($this->form_validation->run() == TRUE) 

		{

			$mem_info=array();

			if(isset($_POST['regnumber']))

			{

				$mem_no=$_POST['regnumber'];

			}else

			{

				$this->session->set_flashdata('error','Enter the Membership number not present!!');

				redirect(base_url('bulk/Naardashboard/add_member/'));

			}

		//query to get member details

			$this->db->where("isactive",'1'); 

			$mem_info=  $this->master_model->getRecords('member_registration',array('regnumber'=>$mem_no));

			

			if(!empty($mem_info))

			{

				$data['mem_info']=$mem_info;

			}else

			{                          

				$mem_info=array();

				$data['mem_info']=$mem_info;

				$this->session->set_flashdata('error','Membership number not present!!');

				redirect(base_url('bulk/Naardashboard/add_member/'));

			}

			$data  = array('middle_content' => 'bulk/bulk_add_member');

       	    $this->load->view('bulk/bulk_common_view', $data);

			//$this->load->view('bulk/bulk_add_member',$data);

		}else

		{

			$this->session->set_flashdata('error','Enter the Membership number !');

			redirect(base_url('bulk/Naardashboard/add_member/'));

		}

			

	}else

	{

			$mem_info=array();

			$data['mem_info']=$mem_info;

			$data  = array('middle_content' => 'bulk/bulk_add_member');

       	    $this->load->view('bulk/bulk_common_view', $data);

			//$this->load->view('bulk/bulk_add_member',$data);	

	}

	}

	

}