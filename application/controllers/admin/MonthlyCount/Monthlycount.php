<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Monthlycount extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
    }
    public function index(){
		
		$user = $this->uri->segment(5);
		if($user == 'track'){
			$this->session->set_userdata('user_ses', 'pallavi');
			$this->load->view('admin/MonthlyCountDashboard/module_list');
		}elseif($user == 'show'){
			$this->session->set_userdata('user_ses', 'iibf');
			$this->load->view('admin/MonthlyCountDashboard/module_list');
		}else{
			echo 'Wrong URL';
		}
		//$this->load->view('admin/MonthlyCountDashboard/module_list');
    }
	
	public function oldmonth(){
		$flag = $this->uri->segment(5);
		$this->load->view('admin/MonthlyCountDashboard/module_list');
	}
	
	public function date_count()
	{
		$exam_counts = 0;
		$invoice_counts = 0;
		$app_counts = 0;
		$data = array();
		$result = array();
		
		$currentdate = $this->uri->segment(5);
		
		$ignore = array('12','13','14');
		$this->db->where_not_in('pay_type', $ignore);
		$module_info = $this->master_model->getRecords('pay_type_master');
		$i=0;
		foreach($module_info as $pay_type){
			$data[$i]['pay_type'] = $pay_type['pay_type'];
			$data[$i]['module_name'] = $pay_type['module_name'];
			
			// query for invoice count	
			$this->db->where("app_type",$pay_type['app_type']);
			$this->db->where("invoice_no !=","");
			$this->db->where("transaction_no !=","");
			$this->db->where("date(date_of_invoice) = '".$currentdate."'");
			$invoice_counts = $this->master_model->getRecordCount('exam_invoice');
			$data[$i]['invoice_cnt'] = $invoice_counts;
			
			//fetch pg count
			$this->db->where("pay_type ",$pay_type['pay_type']);
			$this->db->where("date(count_date) = '".$currentdate."'");
			$pg_monthly_count = $this->master_model->getRecords('pg_monthly_count','','invoice_hist,invoice_upload,app_hist,app_upload');
			if(count($pg_monthly_count)>0){
				$data[$i]['invoice_hist'] = $pg_monthly_count[0]['invoice_hist'];
				$data[$i]['invoice_upload'] = $pg_monthly_count[0]['invoice_upload'];
				$data[$i]['app_hist'] = $pg_monthly_count[0]['app_hist'];
				$data[$i]['app_upload'] = $pg_monthly_count[0]['app_upload'];
			}else{
				$data[$i]['invoice_hist'] = 0;
				$data[$i]['invoice_upload'] = 0;
				$data[$i]['app_hist'] = 0;
				$data[$i]['app_upload'] = 0;
			}
			
			/*query for application count*/
			if($pay_type['pay_type'] == 1){
				// Application count of member registratipon
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				$this->db->where("is_renewal ","0");
				$this->db->where("excode ","0");
				$this->db->where("date(createdon) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('member_registration');
				
			}elseif($pay_type['pay_type'] == 2){
				// Application count of exam registration
				$this->db->where("pay_status ","1");
				$this->db->where("institute_id ","0");
				$this->db->where("exam_period !=","999");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('member_exam');
				
				
				
			}elseif($pay_type['pay_type'] == 3){
				// Application count of duplicate id card
				$this->db->where("pay_status ","1");
				$this->db->where("date(added_date) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('duplicate_icard');
				
			}elseif($pay_type['pay_type'] == 4){
				// Application count of Dupliocate certificate
				$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('duplicate_certificate');
				
			}elseif($pay_type['pay_type'] == 5){
				// Application count of Membership Renewal
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				$this->db->where("is_renewal ","1");
				$this->db->where("date(createdon) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('member_registration');
				
			}elseif($pay_type['pay_type'] == 6){
				// Application count of bankquest module
				$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('bank_vision');
				
			}elseif($pay_type['pay_type'] == 7){
				// Application count of vision module
				$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('iibf_vision');
				
			}elseif($pay_type['pay_type'] == 8){
				// Application count of finquest module
				$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('fin_quest');
				
			}elseif($pay_type['pay_type'] == 9){
				// Application count of CPD registration module
				$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('cpd_registration');
				
			}elseif($pay_type['pay_type'] == 10){
				// Application count of Blended Course Registration module
				$this->db->where("pay_status ","1");
				$this->db->where("date(createdon) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('blended_registration');
				
			}elseif($pay_type['pay_type'] == 11){
				// Application count of Contact Classes module
				$this->db->where("pay_status ","1");
				$this->db->where("date(createdon) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('contact_classes_registration');
				
			}elseif($pay_type['pay_type'] == 12){
				// Application count of DRA institute registration module
				$this->db->where("pay_status ","1");
				$this->db->where("date(modified_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('dra_member_exam');
				
			}elseif($pay_type['pay_type'] == 16){
				// Application count of DRA Agency Registration module
				$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('agency_center');
				
			}elseif($pay_type['pay_type'] == 17){
				// Application count of DRA Agency Center Renew module
				/*$this->db->where("pay_status ","1");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('agency_center_renew');*/
				
				$this->db->join('payment_transaction','payment_transaction.ref_id = agency_center_renew.agency_renew_id','left');
				$this->db->where("pay_status ","1");
				$this->db->where("status ","1");
				$this->db->where("pay_type ","17");
				$this->db->where("date(date) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('agency_center_renew');
				
				
				
			}elseif($pay_type['pay_type'] == 18){
				// Application count of E-learning module
				$this->db->where("pay_status ","1");
				$this->db->where("exam_period ","999");
				$this->db->where("institute_id ","0");
				$this->db->where("date(created_on) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('member_exam');
			
			}elseif($pay_type['pay_type'] == 50){
				// Registration count of Edit Profile and Benchmark Disability
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				//$this->db->where("is_renewal ","0");
				$this->db->where("benchmark_edit_flg ","Y");
				$this->db->where("benchmark_disability ","Y");
				$this->db->where("date(benchmark_edit_date) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('member_registration');
				
			}elseif($pay_type['pay_type'] == 51){
				// Registration count of Edit Profile and Benchmark Disability
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				//$this->db->where("is_renewal ","0");
				$this->db->where("date(editedon) = '".$currentdate."'");
				$app_counts = $this->master_model->getRecordCount('member_registration');
			
			}
			
			$data[$i]['app_cnt'] = $app_counts;
			$i++;
			
		}
		$output['result']=$data;
		
		$this->load->view('admin/MonthlyCountDashboard/count_list',$output);
	}
	
	public function all_count()
	{
		$exam_counts = 0;
		$invoice_counts = 0;
		$app_counts = 0;
		$data = array();
		$result = array();
		
		
		$lastdate = $this->uri->segment(5);
		$exarr = explode("-",$lastdate);
		$first_date_month = $exarr[0]."-".$exarr[1]."-01 00:00:01";
		$last_date_month = $lastdate." 23:59:59";
		
		$pg_first_month = $exarr[0]."-".$exarr[1]."-01";
		$pg_last_month = $lastdate;
		
		$ignore = array('12','13','14');
		$this->db->where_not_in('pay_type', $ignore);
		$module_info = $this->master_model->getRecords('pay_type_master');
		$i=0;
		foreach($module_info as $pay_type){
			$data[$i]['pay_type'] = $pay_type['pay_type'];
			$data[$i]['module_name'] = $pay_type['module_name'];
			
			// query for invoice count	
			$this->db->where("app_type",$pay_type['app_type']);
			$this->db->where("invoice_no !=","");
			$this->db->where("transaction_no !=","");
			$this->db->where("date_of_invoice BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
			$invoice_counts = $this->master_model->getRecordCount('exam_invoice');
			$data[$i]['invoice_cnt'] = $invoice_counts;
			
			//fetch pg count
			$this->db->where("pay_type ",$pay_type['pay_type']);
			$this->db->where("count_date BETWEEN '".$pg_first_month."' AND '".$pg_last_month."' "); 
			$pg_monthly_count = $this->master_model->getRecords('pg_monthly_count','','invoice_hist,invoice_upload,app_hist,app_upload');
			//echo $this->db->last_query();
			//echo '<br/>'; 
			
			$invoice_hist = 0;
			$invoice_upload = 0;
			$app_hist = 0;
			$app_upload = 0;
			
			if(count($pg_monthly_count)>0){
				foreach($pg_monthly_count as $result){
					$invoice_hist 	+= $result['invoice_hist'];
					$invoice_upload += $result['invoice_upload'];
					$app_hist 		+= $result['app_hist'];
					$app_upload 	+= $result['app_upload'];
				}
			}
			
			$data[$i]['invoice_hist'] = $invoice_hist;
			$data[$i]['invoice_upload'] = $invoice_upload;
			$data[$i]['app_hist'] = $app_hist;
			$data[$i]['app_upload'] = $app_upload;
			
			/*$data[$i]['invoice_hist'] = 0;
			$data[$i]['invoice_upload'] = 0;
			$data[$i]['app_hist'] = 0;
			$data[$i]['app_upload'] = 0;*/
			
			/*query for application count*/
			if($pay_type['pay_type'] == 1){
				// Application count of member registratipon
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				$this->db->where("is_renewal ","0");
				$this->db->where("excode ","0");
				$this->db->where("createdon BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('member_registration');
				
			}elseif($pay_type['pay_type'] == 2){
				// Application count of exam registration
				$this->db->where("pay_status ","1");
				$this->db->where("institute_id ","0");
				$this->db->where("exam_period !=","999");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('member_exam');
				
				
				
			}elseif($pay_type['pay_type'] == 3){
				// Application count of duplicate id card
				$this->db->where("pay_status ","1");
				$this->db->where("added_date BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('duplicate_icard');
				
			}elseif($pay_type['pay_type'] == 4){
				// Application count of Dupliocate certificate
				$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('duplicate_certificate');
				
			}elseif($pay_type['pay_type'] == 5){
				// Application count of Membership Renewal
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				$this->db->where("is_renewal ","1");
				$this->db->where("createdon BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('member_registration');
				
			}elseif($pay_type['pay_type'] == 6){
				// Application count of bankquest module
				$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('bank_vision');
				
			}elseif($pay_type['pay_type'] == 7){
				// Application count of vision module
				$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('iibf_vision');
				
			}elseif($pay_type['pay_type'] == 8){
				// Application count of finquest module
				$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('fin_quest');
				
			}elseif($pay_type['pay_type'] == 9){
				// Application count of CPD registration module
				$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('cpd_registration');
				
			}elseif($pay_type['pay_type'] == 10){
				// Application count of Blended Course Registration module
				$this->db->where("pay_status ","1");
				$this->db->where("createdon BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('blended_registration');
				
			}elseif($pay_type['pay_type'] == 11){
				// Application count of Contact Classes module
				$this->db->where("pay_status ","1");
				$this->db->where("createdon BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('contact_classes_registration');
				
			}elseif($pay_type['pay_type'] == 12){
				// Application count of DRA institute registration module
				$this->db->where("pay_status ","1");
				$this->db->where("modified_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('dra_member_exam');
				
			}elseif($pay_type['pay_type'] == 16){
				// Application count of DRA Agency Registration module
				$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('agency_center');
				
			}elseif($pay_type['pay_type'] == 17){
				// Application count of DRA Agency Center Renew module
				/*$this->db->where("pay_status ","1");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('agency_center_renew');*/
				
				
				$this->db->join('payment_transaction','payment_transaction.ref_id = agency_center_renew.agency_renew_id','left');
				$this->db->where("pay_status ","1");
				$this->db->where("status ","1");
				$this->db->where("pay_type ","17");
				$this->db->where("date BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('agency_center_renew');
				
				
				
			}elseif($pay_type['pay_type'] == 18){
				// Application count of E-learning module
				$this->db->where("pay_status ","1");
				$this->db->where("exam_period ","999");
				$this->db->where("institute_id ","0");
				$this->db->where("created_on BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('member_exam');
			
			}elseif($pay_type['pay_type'] == 50){
				// Registration count of Benchmark Disability
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				$this->db->where("is_renewal ","0");
				$this->db->where("benchmark_edit_flg ","Y");
				$this->db->where("benchmark_disability ","Y");
				$this->db->where("benchmark_edit_date BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('member_registration');
				
			}elseif($pay_type['pay_type'] == 51){
				// Registration count of Edit Profile 
				$this->db->where("isactive ","1");
				$this->db->where("isdeleted ","0");
				$this->db->where("is_renewal ","0");
				$this->db->where("editedon BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('member_registration');
			
			
			}elseif($pay_type['pay_type'] == 21){
				// Registration count of Edit Profile 
				$this->db->where("req_status ","5");
				//$this->db->where("isdeleted ","0");
				$this->db->where("credit_note_number != ",'');
				$this->db->where("credit_note_date BETWEEN '".$first_date_month."' AND '".$last_date_month."' ");
				$app_counts = $this->master_model->getRecordCount('maker_checker');
			
			
			}
			
			$data[$i]['app_cnt'] = $app_counts;
			$i++;
			
		}
		$output['result']=$data;
		
		$this->load->view('admin/MonthlyCountDashboard/count_list',$output);
	}
	
	public function add_count()
	{	
		$created_on = $this->input->post('currentdate'); //echo $created_on;
		$module_name = $this->input->post('module_name');
		$pay_type =$this->input->post('pay_type');
		$exam_counts =$this->input->post('invoice_counts');
		$esds_app_cnt = $this->input->post('esds_app_cnt');
		$invoice_hist = $this->input->post('textfield1');
		$invoice_upload =$this->input->post('textfield2');
		$app_hist =$this->input->post('textfield3');
		$app_upload = $this->input->post('textfield4');
		
		if($module_name !='' && $pay_type !='' && $exam_counts!='' && $esds_app_cnt!='' && $invoice_hist!='' && $invoice_upload!='' && $app_hist!='' && $app_upload!='')
		{
			$count = $this->master_model->getRecords('pg_monthly_count',array('DATE(count_date)'=>$created_on ,'pay_type' => $pay_type));
			if(count($count) > 0){
				$update_data = array(
								'invoice_hist'	 =>$invoice_hist,
								'invoice_upload' =>$invoice_upload,
								'app_hist'	     =>$app_hist,
								'app_upload'	 =>$app_upload,
								'updated_on'	 =>date('Y-m-d H:i:s')
							);
				if($this->master_model->updateRecord('pg_monthly_count',$update_data,array('DATE(count_date)'=>$created_on,'pay_type' => $pay_type))){
					 $resp = array('status'  => 'success','userMsg' => 'Record updated successfully.');
        			 echo json_encode($resp);				
				}
				else{
					 $resp = array('status'  => 'error','userMsg' => 'Error occured while updating record.');
        			 echo json_encode($resp);
				}
			}else{
				$insert_data = array(
								'module_name'   =>$module_name,		
								'pay_type'		=>$pay_type,
								'esds_counts'	=>$exam_counts,
								'esds_app_cnt'	=>$esds_app_cnt,
								'invoice_hist'	=>$invoice_hist,
								'invoice_upload'=>$invoice_upload,
								'app_hist'	    =>$app_hist,
								'app_upload'    =>$app_upload,
								'count_date'	=>$created_on
							);
				if($this->master_model->insertRecord('pg_monthly_count',$insert_data)){
					 $resp = array('status'  => 'success','userMsg' => 'Record added successfully.');
        			 echo json_encode($resp);				
				}else{
					 $resp = array('status'  => 'error','userMsg' => 'Error occured while adding record.');
        			 echo json_encode($resp);
				}
			}
		}else{
			$resp = array('status'  => 'error','userMsg' => 'No data exists.');
        			echo json_encode($resp);
		}
	}
 }  