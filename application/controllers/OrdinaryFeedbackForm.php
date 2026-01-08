<?php defined('BASEPATH') OR exit('No direct script access allowed');
//15_MARCh_2018
class OrdinaryFeedbackForm extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->library('email');
	}
	public function index()
	{
		
		if(isset($_POST['btn_submit']))
		{
			$this->form_validation->set_rules('q1_answer','q1_answer','trim|required|xss_clean');
			$this->form_validation->set_rules('q2_answer','q2_answer','trim|required|xss_clean');
			$this->form_validation->set_rules('q3_answer','q3_answer','trim|required|xss_clean');
			$this->form_validation->set_rules('q4_answer','q4_answer','trim|required|xss_clean');
			$this->form_validation->set_rules('q6_answer_i','q6_answer_i','trim|xss_clean');
			$this->form_validation->set_rules('q6_answer_ii','q6_answer_ii','trim|xss_clean');
			$this->form_validation->set_rules('q7_answer','q7_answer','trim|xss_clean');
			
			if($this->form_validation->run()==TRUE)
			{
				$obj = new OS_BR();
				$browser_details=implode('|',$obj->showInfo('all'));
			
				$data_arr = array(
						'q1_answer'=>$this->input->post('q1_answer'),
						'q2_answer'=>$this->input->post('q2_answer'),
						'q3_answer'=>$this->input->post('q3_answer'),
						'q4_answer'=>$this->input->post('q4_answer'),
						'q5_answer'=>$this->input->post('q5_answer'),
						'q6_answer_i'=>$this->input->post('q6_answer_i'),
						'q6_answer_ii'=>$this->input->post('q6_answer_ii'),
						'q7_answer'=>$this->input->post('q7_answer'),
						'ip_address'=> $this->input->ip_address(),
						'browser'=>$browser_details,
						'created_on'=>date('Y-m-d H:i:s'),
				);
				if($last_id = $this->master_model->insertRecord('ordinary_feedback',$data_arr,true))
				{
					$this->session->set_flashdata('success','Feedback Submitted Successfully!');
					redirect(base_url()."OrdinaryFeedbackForm/success");
				}else{
					$this->session->set_flashdata('error','Error while form submission.please try again!');
					redirect(base_url()."OrdinaryFeedbackForm");
				}
			}
		}
		$data=array('middle_content'=>'ordinary_feedback');
	    $this->load->view('common_view_fullwidth',$data);
	}
	public function success()
	{
		$data=array('middle_content'=>'feedback_success');
	    $this->load->view('common_view_fullwidth',$data);
	}
}
?>