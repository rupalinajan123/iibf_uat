<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  header("Access-Control-Allow-Origin: *");
  
  class Payment_details extends CI_Controller
  {    
    public function __construct()
    { //exit;
      parent::__construct();
      $this->load->library('upload');
      $this->load->helper('upload_helper');
      $this->load->helper('master_helper');
      $this->load->helper('general_helper');
      $this->load->helper('blended_invoice_helper');
      $this->load->model('Master_model');
      $this->load->library('email');
      $this->load->helper('date');
      $this->load->library('email');
      $this->load->model('Emailsending');
      $this->load->model('log_model');
	  $this->load->model('billdesk_pg_model');
	  /* if(file_exists('./uploads/idproof/pr_801839579.jpg'))
	  {
		  @unlink('./uploads/idproof/pr_801839579.jpg');
	  } */
	}
    
    public function index()
    {
		
      $msg = $member_no = $member_response['member_data'] = $member_response['scannedphoto'] = $member_response['idproofphoto'] = $member_response['scannedsignaturephoto'] = $member_response['declarationphoto'] = '';
      $download_btn_flag = 0;
      
      if(isset($_POST) && count($_POST) > 0)
			{
				$_POST['receipt_no']	=	trim($_POST['receipt_no']);
				$_POST['member_no']	=	trim($_POST['member_no']);
				$this->form_validation->set_rules('member_no', 'Member No', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));		
				if($this->form_validation->run())
				{
					$this->db->where('payment_transaction.member_regnumber',$_POST['member_no']);

					if(isset($_POST['receipt_no']) && $_POST['receipt_no']!='')
					{
						$where = '(receipt_no="'.$_POST['receipt_no'].'" or transaction_no = "'.$_POST['receipt_no'].'")';
       					$this->db->where($where);
					}
					
					$this->db->where('payment_transaction.receipt_no != ""');
					$this->db->order_by('payment_transaction.id','desc');
					$payment_details = $this->master_model->getRecords('payment_transaction','','payment_transaction.*'); 
					$data['payment_details'] = $payment_details;

				}
				$data['member_no']=$_POST['member_no'];
				$data['receipt_no']=$_POST['receipt_no'];
			}
     
						
     
      $data['middle_content'] = 'payment_details/index';
      $this->load->view('payment_details/common_view', $data);
		}
    public function get_payment_details() {

		$responseArray	=	array();

		$responseArray['status']='Invalid Transaction ';
		$responseArray['date']='--';
		$responseArray['id']='--';
		$responseArray['amount']='--';

		$_POST['receipt_no']	=	trim($_POST['receipt_no']);
		$responsedata = $this->billdesk_pg_model->billdeskqueryapi($_POST['receipt_no']);

		
		if(isset($responsedata['refundInfo']) && !empty($responsedata['refundInfo']))
		{
			$refundStatusData	=	($responsedata['refundInfo']);
			$responseArray['status']='Refunded';
			$responseArray['date']=date('d-M-y g:i a',strtotime($refundStatusData[0]['refund_date']));
			$responseArray['id']=$refundStatusData[0]['refundid'];
			$responseArray['amount']=$refundStatusData[0]['refund_amount'];
		//	echo '<pre>';print_r($responsedata['refundInfo']);
		}
		else {
			
			if(isset($responsedata['auth_status']) ) {
				if($responsedata['auth_status']=='0300')
					$responseArray['status']='Transaction Success';
				else
					$responseArray['status']=$responsedata['transaction_error_desc'];
				$responseArray['date']=date('d-M-y g:i a',strtotime($responsedata['transaction_date']));
				$responseArray['id']=$responsedata['transactionid'];
				$responseArray['amount']=$responsedata['amount'];
			}
			 
				
			//echo json_encode($responsedata);
		}
		echo json_encode($responseArray);
	}
  }
  ?>