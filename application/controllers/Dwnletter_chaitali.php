<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dwnletter_chaitali extends CI_Controller {

	/**

	 * Index Page for this controller.

	 *

	 * Maps to the following URL

	 * 		http://example.com/index.php/welcome

	 *	- or -

	 * 		http://example.com/index.php/welcome/index

	 *	- or -

	 * Since this controller is set as the default controller in

	 * config/routes.php, it's displayed at http://example.com/

	 *

	custom_examinvoice_send_mail * So any other public methods not prefixed with an underscore will

	 * map to /index.php/welcome/<method_name>

	 * @see https://codeigniter.com/user_guide/general/urls.html

	 */

	 

	public function __construct()

	{

		 parent::__construct(); 

		 //load mPDF library

		 //$this->load->library('m_pdf');

//echo CI_VERSION;

//echo '<br/>';

		 $this->load->model('Master_model');

		 $this->load->library('email');

		 $this->load->model('Emailsending');

		 //$this->load->model('Emailsending_123');

		 //$this->load->helper('bulk_admitcard_helper');

		 $this->load->helper('custom_contact_classes_invoice_helper');

		 $this->load->helper('custom_admitcard_helper');

		 //$this->load->helper('bulk_check_helper');

		 //$this->load->helper('bulk_seatallocation_helper');

		 $this->load->helper('bulk_invoice_helper');

		 $this->load->helper('bulk_admitcard_helper');

		 $this->load->helper('custom_invoice_helper');

		 $this->load->helper('blended_invoice_custom_helper');

		 $this->load->helper('bulk_calculate_tds_discount_helper');

		 $this->load->helper('bulk_proforma_invoice_helper');

		$this->load->helper('renewal_invoice_helper');

		

		//exit;

	

	} 
	
	## Code added by pratibha on 4 MAr 2021
	public function subject_settle()
	{

		$arr = array(700021906);
		for($i=0;$i<count($arr);$i++)
		{
			$member_exam=$this->master_model->getRecords('member_exam',array('regnumber'=>$arr[$i],'exam_period'=>'121','pay_status'=>'1'),'id');
			//print_r($member_exam);
			
			$regnumber = array();
			//echo $this->db->last_query();
			for($j=0;$j<count($member_exam);$j++)
			{
				//echo "<br/> ID=>".$member_exam[$j]['id'];
				array_push($regnumber, $member_exam[$j]['id']);
			}
			//print_r($regnumber);
			//echo $reg = implode(",",$regnumber);
			//$this->master_model->where_in('mem_exam_id',$reg);
			//$admit_card = $this->master_model->getRecords('admit_card_details','','seat_identification');
			 
			$this->db->select('seat_identification');
			$this->db->where_in('mem_exam_id', $regnumber);// need to add dates in IN condition
			$admit_card_details = $this->master_model->getRecords('admit_card_details');
			//echo $this->db->last_query();
			for($a=0;$a<count($admit_card_details);$a++)
			{
				//echo "<br/> ID=>".$admit_card_details[$a]['seat_identification'];
				if(empty($admit_card_details[$a]['seat_identification']))
					  echo "<br/>".$arr[$i]."=>Required settlement";
				else echo "<br/>".$arr[$i]."=>no need";	
				
			}
		}
	}
	

	public function annual_operation(){

		echo $chk_time = date('Y-m-d H:i:s');

		$this->load->view('annual_register');

	}

///usr/local/bin/php /home/supp0rttest/public_html/index.php Dwnletter_chaitali custom_exam_invoice_chaitali

///usr/local/bin/php /home/supp0rttest/public_html/index.php Dwnletter_chaitali exam_invoice_settlement

	public function exam_invoice_settlement(){    		

	$arr = array('902422256','902428247');   // add receipt number  

		for($i=0;$i<sizeof($arr);$i++){ 

			$this->db->where('receipt_no',$arr[$i]);

			$invoice_info=$this->master_model->getRecords('exam_invoice','','invoice_id,member_no');

			echo $this->db->last_query();

			echo '<br/>';

			

			$this->db->where('receipt_no',$arr[$i]);

			$payment_info=$this->master_model->getRecords('payment_transaction','','ref_id,member_regnumber,transaction_no');

			echo $this->db->last_query();

			echo '<br/>';

			

			$this->db->where('id',$payment_info[0]['ref_id']);

			$exam_info=$this->master_model->getRecords('member_exam','','id,modified_on');

			echo $this->db->last_query();

		    echo '<br/>';

			echo '>>>'.$payment_info[0]['member_regnumber'];echo '<br/>';

			$this->db->where('regnumber',$payment_info[0]['member_regnumber']);

			$member_info=$this->master_model->getRecords('member_registration','','regid,regnumber');

		   echo $this->db->last_query();

		    echo '<br/>';

			 

			

			if($payment_info[0]['member_regnumber'] == $member_info[0]['regnumber']){

				$invoice_mem_no = $member_info[0]['regnumber'];

			}else{

				$invoice_mem_no =$invoice_info[0]['member_no']; 

			}

			$invoice_date_of_invoice = $exam_info[0]['modified_on'];

			$invoice_modified_on = $exam_info[0]['modified_on'];

			$this->db->where('invoice_id',$invoice_info[0]['invoice_id']);

			$config_info=$this->master_model->getRecords('config_exam_invoice','','exam_invoice_no');

			echo $config_info[0]['exam_invoice_no'];

			echo '<br/>';

			echo $this->db->last_query();

			echo '<br/>';

			if($config_info[0]['exam_invoice_no']!=''){

				$invoice_no = $config_info[0]['exam_invoice_no'];

				echo 'invoice number already present';

				

			}else{

				$insert_info = array('invoice_id'=>$invoice_info[0]['invoice_id']);

				$last_id = str_pad($this->master_model->insertRecord('config_exam_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;

			}

			echo $this->db->last_query();

			echo '<br/>';

			echo $invoice_number = 'EX/21-22/'.$last_id;

			echo '<br/>';

			echo $invoice_img_name = $member_info[0]['regnumber'].'_EX_21-22_'.$last_id.'.jpg';

			echo '<br/>';

			

			

			$update_arr = array(

									'member_no' => $invoice_mem_no,

									'transaction_no'=>$payment_info[0]['transaction_no'],

									'invoice_no' => $invoice_number,

									'invoice_image' => $invoice_img_name,

									'date_of_invoice' =>$invoice_date_of_invoice,

									'modified_on' => $invoice_modified_on

								);

			

			$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$invoice_info[0]['invoice_id'],'receipt_no'=>$arr[$i]));

			echo $this->db->last_query();

			echo '<br/>';

			echo 'Receipt no >> '.$arr[$i];

			echo '<br/>';

		}

	}

	public function contact_invoice_settlement(){    		

	$arr = array('902496454');   // add receipt number  

		for($i=0;$i<sizeof($arr);$i++){ 

			$this->db->where('receipt_no',$arr[$i]);

			$invoice_info=$this->master_model->getRecords('exam_invoice','','invoice_id,member_no');

			echo $this->db->last_query();

			echo '<br/>';

			

			$this->db->where('receipt_no',$arr[$i]);

			$payment_info=$this->master_model->getRecords('payment_transaction','','ref_id,member_regnumber,transaction_no,date');

			echo $this->db->last_query();

			echo '<br/>';

			
/*
			$this->db->where('id',$payment_info[0]['ref_id']);

			$exam_info=$this->master_model->getRecords('member_exam','','id,modified_on');

			echo $this->db->last_query();

			echo '<br/>';*/

			echo '>>>'.$payment_info[0]['member_regnumber'];echo '<br/>';

			$this->db->where('regnumber',$payment_info[0]['member_regnumber']);

			$member_info=$this->master_model->getRecords('member_registration','','regid,regnumber');

			echo $this->db->last_query();

			echo '<br/>';

			 

			

			if($payment_info[0]['member_regnumber'] == $member_info[0]['regnumber']){

				$invoice_mem_no = $member_info[0]['regnumber'];

			}else{

				$invoice_mem_no =$invoice_info[0]['member_no']; 

			}

			$invoice_date_of_invoice = $payment_info[0]['date'];

			$invoice_modified_on = $payment_info[0]['date'];

			$this->db->where('invoice_id',$invoice_info[0]['invoice_id']);

			$config_info=$this->master_model->getRecords('config_contact_classes_NZ_invoice','','exam_invoice_no');

			echo $config_info['exam_invoice_no'];

			echo '<br/>';

			echo $this->db->last_query();

			echo '<br/>';

			if($config_info[0]['exam_invoice_no']!=''){

				$invoice_no = $config_info[0]['exam_invoice_no'];

				echo 'invoice number already present';

				

			}else{

				$insert_info = array('invoice_id'=>$invoice_info[0]['invoice_id']);

				$last_id = str_pad($this->master_model->insertRecord('config_contact_classes_NZ_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;

			}

			echo $this->db->last_query();

			echo '<br/>';

			echo $invoice_number = 'TUNZ/21-22/'.$last_id;

			echo '<br/>';

			echo $invoice_img_name = 'TUNZ_21-22_'.$last_id.'.jpg';

			echo '<br/>';

			

			

			$update_arr = array(

									'member_no' => $invoice_mem_no,

									'transaction_no'=>$payment_info[0]['transaction_no'],

									'invoice_no' => $invoice_number,

									'invoice_image' => $invoice_img_name,

									'date_of_invoice' =>$invoice_date_of_invoice,

									'modified_on' => $invoice_modified_on

								);

			

			$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$invoice_info[0]['invoice_id'],'receipt_no'=>$arr[$i]));

			echo $this->db->last_query();

			echo '<br/>';

			echo 'Receipt no >> '.$arr[$i];

			echo '<br/>';

		}

	}

	public function member_invoice_settlement(){ 

	//echo 'hi';

	//exit;  

		

		$arr = array('812193866','812193867','812193878','812193907','812193912','812193920','812194434');   

		for($i=0;$i<sizeof($arr);$i++){ 

		

		//	echo 'in'; echo '<br/>';

			$this->db->where('receipt_no',$arr[$i]);

			$invoice_info=$this->master_model->getRecords('exam_invoice','','invoice_id,member_no');

			

			echo $this->db->last_query();

			echo '<br/>';

			

			$this->db->where('receipt_no',$arr[$i]);

			$payment_info=$this->master_model->getRecords('payment_transaction','','ref_id,member_regnumber,date,transaction_no');

			

			$transaction_no = $payment_info[0]['transaction_no'];

			

			echo $this->db->last_query();

			echo '<br/>';

			

			$this->db->where('regnumber',$payment_info[0]['member_regnumber']);

			$member_info=$this->master_model->getRecords('member_registration','','regid,regnumber');

			

			echo $this->db->last_query();

			echo '<br/>';

			 

			

			if($payment_info[0]['member_regnumber'] == $member_info[0]['regnumber']){

				$invoice_mem_no = $member_info[0]['regnumber'];

			}else{

				$invoice_mem_no = $invoice_info[0]['member_no']; 

			}

			

			echo $invoice_date_of_invoice = $payment_info[0]['date'];

			echo '<br/>';

			echo $invoice_modified_on = $payment_info[0]['date'];

			echo '<br/>';

			

			

			

			$this->db->where('invoice_id',$invoice_info[0]['invoice_id']);

			$config_info=$this->master_model->getRecords('config_reg_invoice','','reg_invoice_no');

			

			echo '>>>'. $config_info[0]['reg_invoice_no'];

			echo '<br/>';

			

			echo $this->db->last_query();

			echo '<br/>';

			echo '****';

			echo '<br/>';

			

			if($config_info[0]['reg_invoice_no']!=''){ 

				$invoice_no = $config_info[0]['reg_invoice_no'];

				echo 'invoice number already present';

				exit;

			}else{ echo 'config insert done'; echo '<br/>';

				$insert_info = array('invoice_id'=>$invoice_info[0]['invoice_id']);

				$last_id = str_pad($this->master_model->insertRecord('config_reg_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;

			}

			

			

			echo $this->db->last_query();

			echo '<br/>';

			

			echo $invoice_number = 'M/21-22/'.$last_id;

			echo '<br/>';

			echo $invoice_img_name = $invoice_mem_no.'_M_20-21_'.$last_id.'.jpg';

			echo '<br/>';
		
			$update_arr = array(

									'member_no' => $invoice_mem_no,

									'transaction_no' => $transaction_no,

									'invoice_no' => $invoice_number,

									'invoice_image' => $invoice_img_name,

									'date_of_invoice' =>$invoice_date_of_invoice,

									'modified_on' => $invoice_modified_on

								);

			

			$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$invoice_info[0]['invoice_id'],'receipt_no'=>$arr[$i]));

			

			echo $this->db->last_query();

			echo '<br/>';

			

			

			echo 'Receipt no >> '.$arr[$i];

			echo '<br/>';

			

		}

	}
	
	public function custom_renewal_invoice_settle()
	{
		echo $attachpath = genarate_renewal_invoice('2772328');
	}
	

	//CPD invoice generation
	public function custom_cpd_invoice(){  
		
		
		echo $path = $invoiceNumber = genarate_cpd_invoice('2758705');
		 	echo "<br/>"; 
		 
		
	}

	public function bulk_gst_cal(){

		

		//$base_total // cum of base_fee column of member exam table

		echo 'base_total (fee_amt in exam_invoice) >>'.$base_total = 3700; 

		echo '<br/>';

		echo 'discount_rate >>'.$discount_rate = 15;

		echo '<br/>';

		echo 'gst_rate >>'.$gst_rate = 18;

		echo '<br/>';

		echo 'after_discount >>'.$after_discount = calculate_discount($base_total, $discount_rate);

		echo '<br/>';

		echo 'discount amount (disc_amt in exam invoice) >>'.$discount_amount = $base_total - $after_discount;

		echo '<br/>';

		echo 'gst_amount_rate (igst_amt in exam invoice) >>'.$gst_amount_rate = calculate_gst_rate($after_discount, $gst_rate);

		echo '<br/>';

		echo 'amt_after_gst (igst_total in exam invoice) >>'.$amt_after_gst = calculate_gst($after_discount, $gst_amount_rate);

		echo '<br/>';

	}

	

	

	

	public function index(){
		echo phpinfo(); die;

		try{

			

			echo $system_date = date("Y-m-d H:i:s");

			exit;

			

			$data=array();

			$data['error']='';

			

		    if(isset($_POST['submit'])){

				$config = array(

								array(

										'field' => 'Username',

										'label' => 'Username',

										'rules' => 'trim|required'

									),

								/*array(

										'field' => 'Password',

										'label' => 'Password',

										'rules' => 'trim|required',

									),*/

								array(

										'field' => 'code',

										'label' => 'Code',

										'rules' => 'trim|required|callback_check_captcha_userlogin',

									),

							);

			

				$this->form_validation->set_rules($config);

				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

				$key = $this->config->item('pass_key');

				$aes = new CryptAES();

				$aes->set_key(base64_decode($key));

				$aes->require_pkcs5();

				$encpass = $aes->encrypt($this->input->post('Password'));

				//echo $decData = $aes->decrypt("0vXcgvTUG5yi2YG1AMSlnQ==");

				$dataarr=array(

					'regnumber'=> $this->input->post('Username'),

					//'usrpassword'=>$encpass,

				);

				if ($this->form_validation->run() == TRUE){

					$user_info=$this->master_model->getRecords('member_registration',$dataarr);

					if(count($user_info) > 0){ 

						$mysqltime=date("H:i:s");

						$seprate_user_data=array('regid'=>$user_info[0]['regid'],

													'spregnumber'=>$user_info[0]['regnumber'],

													'spfirstname'=>$user_info[0]['firstname'],

													'spmiddlename'=>$user_info[0]['middlename'],

													'splastname'=>$user_info[0]['lastname']

												);

						$this->session->set_userdata($seprate_user_data);

						redirect(base_url().'dwnletter/getadmitdashboard');	

					}else{

						$data['error']='<span style="">Membership No. is not valid.</span>';

					}

				}else{

					$data['validation_errors'] = validation_errors();

				}

			}

			$this->load->helper('captcha');

			$vals = array(

							'img_path' => './uploads/applications/',

							'img_url' => '/uploads/applications/',

						);

			$cap = create_captcha($vals);

			$data['image'] = $cap['image'];

			$data['code']=$cap['word'];

			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);

			

			$this->load->view('admitcardloginjaiib',$data);

			

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();

		}	

	} 

	 

	

	public function centerstat(){ 

		

		$exam_code = 101;

		$exam_period = '581';     

		

		$result=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');

		foreach($result as $record){

		//echo '<br>',$record['center_code'];

			$this->db->where('institute_id',0);

			$reg = $this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));//, "examination_date"=>2018-05-12

			//echo '<br>',$this->db->last_query();

			$insert_array = array( 

								'exam_code' =>$exam_code,

								'center_code'=>$record['center_code'],

								'center_name'=>$record['center_name'],

								'exam_period'=>$exam_period,

								'register_count'=>sizeof($reg)

							); 

							

			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);

							

		}

		//echo '<pre>',print_r($last_id);

		//exit;

		$this->load->dbutil();

		$this->load->helper('file');

		$this->load->helper('download');

		$delimiter = ",";

		$newline = "\r\n";

		$filename = "filename_you_wish.csv";

		$query = "SELECT * FROM center_stat";

		$result1 = $this->db->query($query);

		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);

		//$this->db->empty_table('center_stat'); 

		force_download($filename, $data);

		

	}

	public function dra_centerstat(){

		

		$exam_code = 57;

		$exam_period = '701';

		

		$result=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');

		

		foreach($result as $record){

			$reg = $this->master_model->getRecords('dra_member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));//, "examination_date"=>2018-05-12

			

			$insert_array = array(

								'exam_code' =>$exam_code,

								'center_code'=>$record['center_code'],

								'center_name'=>$record['center_name'],

								'exam_period'=>$exam_period,

								'register_count'=>sizeof($reg)

							); 

							

							

							

			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);

							

		}

		

		

		

		$this->load->dbutil();

		$this->load->helper('file');

		$this->load->helper('download');

		$delimiter = ",";

		$newline = "\r\n";

		$filename = "DRA_centerwise_counts_45_184.csv";

		$query = "SELECT * FROM center_stat";

		$result1 = $this->db->query($query);

		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);

		//$this->db->empty_table('center_stat'); 

		force_download($filename, $data);

		

	}

	

	public function venuestat(){

		

		$exam_code = 101;

		$exam_period = '545';

		

		$result=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');

		

		foreach($result as $record){

			$reg = $this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));

			

			$insert_array = array(

								'exam_code' =>$exam_code,

								'center_code'=>$record['center_code'],

								'center_name'=>$record['center_name'],

								'exam_period'=>$exam_period,

								'register_count'=>sizeof($reg)

							);

							

			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);

							

		}

		$this->load->dbutil();

		$this->load->helper('file');

		$this->load->helper('download');

		$delimiter = ",";

		$newline = "\r\n";

		$filename = "filename_you_wish.csv";

		$query = "SELECT * FROM center_stat ";

		$result1 = $this->db->query($query);

		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);

		//$this->db->empty_table('center_stat'); 

		force_download($filename, $data);

		

	}

	

	public function jaiib_datewise(){

		

		/*$this->load->dbutil();

        $this->load->helper('file');

        $this->load->helper('download');

        $delimiter = ",";

        $newline = "\r\n";

        $filename = "filename_you_wish.csv";

        $query = "SELECT * FROM dra_admin ";

        $result = $this->db->query($query);

        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $data);

		

		exit;*/

		

		$exam_code = 21;

		$exam_period = '117';

		

		//SELECT DATE(created_on),count(*)  FROM `member_exam` WHERE `exam_code` = 21 AND  `pay_status` = 1  GROUP BY DATE(created_on)

		$this->db->select('DATE(created_on),count(*)');

		$this->db->group_by('DATE(created_on)');

		$result=$this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'pay_status'=>1));

		echo $this->db->last_query();

		foreach($result as $record){

			$insert_array = array(

								'date' =>$record['DATE(created_on)'],

								'count'=>$record['count(*)'],

							);

			$last_id = $this->master_model->insertRecord('date_wise_count',$insert_array,true);

							

		}

		$this->load->dbutil();

		$this->load->helper('file');

		$this->load->helper('download');

		$delimiter = ",";

		$newline = "\r\n";

		$filename = "date_wise_count.csv";

		$query = "SELECT * FROM date_wise_count";

		$result1 = $this->db->query($query);

		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);

		$this->db->empty_table('date_wise_count'); 

		force_download($filename, $data);

	}

	

	// to check genaration of registration invoice

	public function chkinvoice(){

		//genarate_reg_invoice(179945);	

		/*$arr = array(812101678,812101505,812100615,812098958,812098949,812093903,812092790,812092173,812092172,812091368,812090856);

		for($i=0;$i<=10;$i++){

			echo $path = custom_genarate_reg_invoice($arr[$i]);

			echo "<br/>";

			

		}*/

		echo $path = custom_genarate_reg_invoice(812110468);

	}

	

	// to check genaration of custom invoice

	public function custom_chkinvoice(){  

	

		//echo "hi";  

		//exit;  

		/*$arr = array(901518630,901483588,901458815,901458030,901424733,901437533,901430968,901465553,901473884,901568576);   

		for($i=0;$i<=9;$i++){

			echo $path = custom_genarate_exam_invoice($arr[$i]);

			echo "<br/>"; 

		}*/

		echo $path = custom_genarate_exam_invoice(901568576); 

	}

	

	public function custom_dra_exam_invoice(){

		

		$arr = array(711700,711728,711704,711705,711715,711708,711706,711713,711717,711699,711714,711697,711702,711707,711716,711712);   

		for($i=0;$i<=15;$i++){ 

			echo $path = custom_genarate_draexam_invoice($arr[$i]);

			echo "<br/>"; 

		}

		

		//echo $path = custom_genarate_draexam_invoice('711706');  

	}

	

	public function custom_generate_disa_invoice(){

		$arr = array(901385815,901385923,901386505,901386734,901387514,901387553,901387855,901393303,901393749,901394433,901397342,901397572);   

		for($i=0;$i<=11;$i++){ 

			echo $path = custom_genarate_disa_invoice($arr[$i]);

			echo "<br/>"; 

		}

		//echo $path = custom_genarate_disa_invoice(901374487);

	}

	

	

	/* New 2019 */

	public function genarate_DISA_invoice_custom_new(){

		$invoice_no = '1826226';   

		echo $path = genarate_DISA_invoice_custom($invoice_no);

			

		//echo $path = custom_genarate_disa_invoice(901374487);

	}

	

	

	// Updated

	public function genarate_draexam_invoice_custom()

	{

		$exam_invoice_id = 2611788;

		$genarate_draexam_invoice = genarate_draexam_invoice_custom($exam_invoice_id);

	}

	

	// to check email sending

	public function chkmail(){

		$info_arr=array('to'=>'pwn.prdshi@rediffmail.com',

									'from'=>'IIBF',

									'subject'=>'test mail',

									'message'=>'this is testing mail'

								);

		//$this->Emailsending_123->mailsend($info_arr);

	}

	

	 

	

	// get old member photo and signature

	public function getphotosig(){

		

		echo $p = get_img_name(510192591,'p');

		echo "<br/>";

		echo $s = get_img_name(510192591,'s');

	}

	

	// to check genaration of duplicate certificate invoice

	public function dupinvoice(){

		

		custome_genarate_duplicatecert_invoice(14185);

	}

	

	

	

	// to check genaration of custom duplicate idcard invoice

	public function custom_dupidcardinvoice_mailsent(){

		

		//custome_genarate_duplicateicard_invoice(22029);	

		

		$MerchantOrderNo = 22029;

		$pay_txn_id = 1930043;

		

		$getinvoice_number=$this->master_model->getRecords('exam_invoice_test',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$pay_txn_id));

		

		if(count($getinvoice_number) > 0)

		{ 

				

			$attachpath=custome_genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);

			

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));

			

			$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=>5944633),'namesub,firstname,middlename,lastname,email,usrpassword,mobile');

			$username = $user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];

			$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

			$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);

			$newstring2 = str_replace("#MEM_NO#", "5944633", $newstring1 );

			$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);

			

			$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);

			

			

			

			if($attachpath!='')

			{	

				//if($this->Emailsending->mailsend($info_arr))

				if($this->Emailsending->mailsend_attch($info_arr,$attachpath))

				{

					echo "Invoice sent";

				}

				else

				{

					echo "Invoice not sent";

				}

			}

		}

		

		

		

	} 

	

	public function custom_dupidcardinvoice(){

		

		$array = array(51156,51136,50641,50495,50431,50395,50379,49985,49555,49207,48532,48487,48398,48317,48060,47641,47625,47153,46171,45766,45577,45332,45063,44626,44461,44422,43397,43181,42725,42331,42330,41445);

		

		for($i=0;$i<=31;$i++){

		 	$path = custome_genarate_duplicateicard_invoice($array[$i]);

		 	echo $path;

		 	echo "<br/>";

		}

	}

	

	// to check custom bankquest invoice

	public function custom_bankquest_invoice(){

		custom_genarate_bankquest_invoice(740);

	}

	

	// to genarate custom registration invoice

	public function custom_registration_invoice(){

		$receipt_no = 811950334;

		$attach = custom_genarate_reg_invoice($receipt_no);

		

		if($attach!=''){

			

			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>510336577),'usrpassword,email');

			$applicationNo = 510342058;

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));

			

			if(count($emailerstr) > 0)

			{

				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

				$key = $this->config->item('pass_key');

				$aes = new CryptAES();

				$aes->set_key(base64_decode($key));

				$aes->require_pkcs5();

				$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));

				$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);

				$final_str= str_replace("#password#", "".$decpass."",  $newstring);

				$info_arr=array('to'=>$user_info[0]['email'],

								//'to'=>'raajpardeshi@gmail.com',

								'from'=>$emailerstr[0]['from'],

								'subject'=>$emailerstr[0]['subject'],

								'message'=>$final_str

								); 

								

				if($this->Emailsending->mailsend_attch($info_arr,$attach)){

					echo "Email sent";

				}else{

					echo "Email not sent";

				}

			}

		}else{

			echo "attach path is blank";	

		}

	}

	

	public function genarate_csv(){

		$result=$this->master_model->getRecords('config_bankquest_invoice');

		//echo "<pre>";

		//print_r($result);

		/*$this->load->dbutil();

		$this->load->helper('file');

		$this->load->helper('download');

		$delimiter = ",";

		$newline = "\r\n";

		$filename = "uploads/date_wise_count.csv";

		$query = "SELECT * FROM date_wise_count";

		$result1 = $this->db->query($query);

		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);

		$this->db->empty_table('date_wise_count'); 

		force_download($filename, $data);*/

		

		$data_array = array (

            array ('1','2'),

            array ('2','2'),

            array ('3','6'),

            array ('4','2'),

            array ('6','5')

            );

		$csv = "col1,col2 \n";//Column headers

		foreach ($result as $record){

			$csv.= $record['sub_invoice_no'].','.$record['invoice_id']."\n"; //Append data to csv

			}

		

		$csv_handler = fopen ('uploads/csvfile.csv','w');

		fwrite ($csv_handler,$csv);

		fclose ($csv_handler);

		

		echo 'Data saved to csvfile.csv';

		

		

	}

	//tejasvi

	public function download_PDC_CSV()

	{

	    //exit; 

		 $csv = "exam_code,center_code,center_name,exam_period,register_count \n";//Column headers

		 

		$query = $this->db->query("SELECT center_master.exam_name,center_master.center_code,center_master.center_name,center_master.exam_period,COUNT(member_exam.id) AS registerd

		FROM center_master

		LEFT JOIN member_exam ON member_exam.exam_code = center_master.exam_name AND member_exam.exam_period = center_master.exam_period AND member_exam.exam_center_code= center_master.center_code

		WHERE center_master.exam_name IN (34,58,160) AND center_master.exam_period IN(714) AND member_exam.pay_status = 1 AND member_exam.examination_date = '2017-10-14'

		GROUP by center_master.exam_name,center_master.center_code,center_master.center_name,center_master.exam_period");

		

		

		$result = $query->result_array();

		foreach($result as $record)

		{

			

			// print_r($record);exit;

			 $csv.= $record['exam_name'].','.$record['center_code'].','.$record['center_name'].','.$record['exam_period'].','.$record['registerd']."\n";

		}

		

        $filename = "pdc_exam_stats.csv";

		header('Content-type: application/csv');

		header('Content-Disposition: attachment; filename='.$filename);

		$csv_handler = fopen('php://output', 'w');

 		fwrite ($csv_handler,$csv);

 		fclose ($csv_handler);

	}

	

	

	public function jaiib_previous(){

		try{

			

			$this->db->limit(0,20);

			$jaiib_member = $this->master_model->getRecords('jaiib_previous');

			

			$this->db->where('center_code',306);

			$venue = $this->master_model->getRecords('venue_master_j');

			

			$this->db->where('exam_code',21);

			$this->db->where('exam_period',217);

			$this->db->order_by("id", "asc");

			$subject = $this->master_model->getRecords('subject_master','','exam_date');

			foreach($subject as $subject_res){

				$exam_date_arr[] = $subject_res['exam_date'];

			}

			

			$time_array = array("2:00 PM","2.00 PM","11:15 AM","11.15 AM","8:30 AM","8.30 AM");

			

			foreach($jaiib_member as $jaiib_member_res){

				

				foreach($venue as $venue_res){

					

					

					$capacity=check_capacity($venue_res['venue_code'],$exam_date_arr[0],$time_array[1],306);

					if($capacity == 0){

						echo "Capacity is full";

					}elseif($capacity != 0){

						

						// insert in admit card dettail table

						$admitcard_insert_array=array(

											'mem_exam_id'=>1,

											'center_code'=>$jaiib_member_res['center_code'],

											'center_name'=>'test',

											'mem_type'=>$jaiib_member_res['member_type'],

											'mem_mem_no'=>$jaiib_member_res['member_number'],

											'g_1'=>$jaiib_member_res['gender'],

											'mam_nam_1'=>$jaiib_member_res['member_name'],

											'mem_adr_1'=>$jaiib_member_res['address_1'],

											'mem_adr_2'=>$jaiib_member_res['address_2'],

											'mem_adr_3'=>$jaiib_member_res['address_3'],

											'mem_adr_4'=>$jaiib_member_res['address_4'],

											'mem_adr_5'=>$jaiib_member_res['district'],

											'mem_adr_6'=>$jaiib_member_res['city'],

											'mem_pin_cd'=>$jaiib_member_res['pincode'],

											'state'=>$jaiib_member_res['state'],

											'exm_cd'=>$jaiib_member_res['exam_code'],

											'exm_prd'=>217,

											'sub_cd '=>$jaiib_member_res['subject_code'],

											'sub_dsc'=>'subject name',

											'm_1'=>$jaiib_member_res['medium'],

											'inscd'=>$jaiib_member_res['institute_code'],

											'insname'=>$jaiib_member_res['institute_name'],

											'venueid'=>$venue_res['venue_code'],

											'venue_name'=>$venue_res['venue_name'],

											'venueadd1'=>$venue_res['venue_addr1'],

											'venueadd2'=>$venue_res['venue_addr2'],

											'venueadd3'=>$venue_res['venue_addr3'],

											'venueadd4'=>$venue_res['venue_addr4'],

											'venueadd5'=>$venue_res['venue_addr5'],

											'venpin'=>$venue_res['venue_pincode'],

											'exam_date'=>$exam_date_arr[0],

											'time'=>$time_array[1],

											'mode'=>'Online',

											'scribe_flag'=>'',

											'vendor_code'=>$venue_res['vendor_code'],

											'remark'=>2,

											'created_on'=>date('Y-m-d H:i:s'));

						

						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);

						

						

						

						$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$venue_res['venue_code'],'exam_date'=>$exam_date_arr[0],'session_time'=>$time_array[1]));

						

						

						$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$venue_res['venue_code'],'exam_date'=>$exam_date_arr[0],'time'=>$time_array[1],'sub_cd'=>$jaiib_member_res['subject_code'],'mem_mem_no'=>$jaiib_member_res['member_number']));

						

						// insert in seat allocation table

						$seat_allocation = getseat(21, 306, $venue_res['venue_code'], $exam_date_arr[0], $time_array[1] , 217 , $jaiib_member_res['subject_code'],$get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);

					}

					

					$i++;

				}

			}

			

		}catch(Exception $e){echo $e->getMessage();}

	}

	

	// genarate admitcard for JAIIB prevoius valid member

	public function jaiib_previous_test(){

		

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		

		

			$start_time = date("Y-m-d H:i:s");

			

			

			

			//$member_array = array("100010289","100054194","200059157","300014603","3894274");

			//$member_array = array("300014603");

			

			// selected center

			$center_code = $this->uri->segment(3);

			//echo "###".$center_code;

			//exit;

			//$center_code = 581; 

			if($center_code == ''){

				die;

			}

			

			$this->db->distinct('mem_mem_no');

			//$this->db->select('mem_mem_no');

			$this->db->where('center_code', $center_code); 

			$this->db->where('remark !=', 1); 

			//$this->db->order_by("admitcard_id", "asc");    

			$this->db->limit(1, 0);  

			$member_array = $this->master_model->getRecords('admit_card_details',array('record_source'=>'Offline'),'mem_mem_no,exm_cd',array('admitcard_id'=>'asc')); // add remark condition here

			

			//echo "<pre>";

			//print_r($member_array);

			//exit; 

			//echo "<br/>";

			// subject dates

			$e_date_array = array("2017-11-12","2017-11-19","2017-11-26");

			

			// get all venues for selected center

			

			$ignore = array('854301A','211001A','211002A','133203A','221002B','221005B','211005A','680009','570022','IIBF306');

			//$this->db->where_not_in('crm.user_id', $ignore);

			

			$this->db->distinct();

			$this->db->select('venue_code');

			$this->db->where('center_code',$center_code);

			//$this->db->where('venue_code!=','IIBF306');

			$this->db->where_not_in('venue_code', $ignore);

			$this->db->where_in('exam_date', $e_date_array);// need to add dates in IN condition

			$venue = $this->master_model->getRecords('venue_master');

			

			/*echo "<pre>";

			print_r($venue);

			exit;*/

			

			// selected venue

			foreach($venue as $venue_res){

				$venue_arr[] = $venue_res['venue_code'];

			}

			//$venue_arr = array(400007);

			

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($member_array);*/

			//exit;*/

			

			foreach($member_array as $mem_no){

				

			// get all subjects for member

			/*$this->db->where('venueid', '');

			$this->db->where('exam_date', '	0000-00-00');

			$this->db->where('time', '');

			$this->db->where('remark', 2);

			$this->db->where('admitcard_image', '');*/

			//$this->db->where('admitcard_id > ', 309513); //greter than last primary key value

			

			/*echo ">>".$mem_no['mem_mem_no'];

			echo "<br/>";*/

			

			

			

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$this->db->where('exm_cd',$mem_no['exm_cd']);

			//$this->db->where('remark',0);

			$this->db->where('record_source','Offline');

			$jaiib_member = $this->master_model->getRecords('admit_card_details'); // add remark condition here

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($jaiib_member);

			echo "*************************";*/

			/*exit;*/

			

			$i = 0;

			$sub_arr = array();

			

			// check capacity for each subject

			foreach($jaiib_member as $jaiib_member_res)	// <= 3s

			{

				

				

				$sub_details = array();

				$exam_code = $jaiib_member_res['exm_cd'];

				$sub_code = $jaiib_member_res['sub_cd'];

				//echo "<br/>";

				

				$mem_mem_no = $jaiib_member_res['mem_mem_no'];

				$admitcard_id = $jaiib_member_res['admitcard_id'];

				//$admitcard_id = $jaiib_member_res['id'];

				

				$v_code = '';

				$e_date = '';

				$e_time = '';

				$flag = 0;

				$flag1 = 0;

				

				// get subject date

				$this->db->where('exam_code',$exam_code);

				$this->db->where('exam_period',217);

				$this->db->where('subject_code',$sub_code);

				$this->db->order_by("id", "asc");

				$subject = $this->master_model->getRecords('subject_master','','exam_date');

				$exam_date = $subject[0]['exam_date']; 

				

				/*echo $this->db->last_query();

				echo "<br/>";

				

				echo "<pre>";

				print_r($subject);*/

				//exit;

				

				// check for all venues

				$venue_size = count($venue_arr);

				for($j=0;$j<$venue_size;$j++){	// number of venues				

					

						// get all sessions for selected date

						$this->db->where('center_code',$center_code);

						$this->db->where('venue_code',$venue_arr[$j]);

						$this->db->where('exam_date',$exam_date);

						$time_sql = $this->master_model->getRecords('venue_master','','session_time');

						

							

						

								

						

						// make sessions array in descending order

						$time_sql_size = sizeof($time_sql);

						for($l=0;$l<$time_sql_size;$l++){

							

							if($time_sql[$l]['session_time'] == "2:00 PM" || $time_sql[$l]['session_time'] =="2.00 PM")

							{

								$temp_time_arr[0] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "11:15 AM" || $time_sql[$l]['session_time'] =="11.15 AM")

							{

								$temp_time_arr[1] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "8:30 AM" || $time_sql[$l]['session_time'] =="8.30 AM")

							{

								$temp_time_arr[2] = $time_sql[$l]['session_time'];	

							}

							

						}

						

						// check for all sessions

						$time_size = count($temp_time_arr);

						for($l=0;$l<$time_size;$l++){

						

							// check for capacity

							$capacity = check_capacity_j($venue_arr[$j],$exam_date,$temp_time_arr[$l],$center_code);

							if($capacity != 0){	// if capacity is not full

								

								$v_code = $venue_arr[$j];

								$e_date = $exam_date;

								$e_time = $temp_time_arr[$l];

								

								$sub_details = array("exam_code" => $exam_code, "center_code" => $center_code, "venue_code" => $v_code, "exam_date" => $e_date, "exam_time" => $e_time, "mem_mem_no" => $mem_mem_no, "admitcard_id" => $admitcard_id,"sub_code"=>$sub_code);							

								

								$sub_arr[$sub_code] = $sub_details;

								

								echo $sub_code."|".implode("|",$sub_details);

								echo "<br/>";

								

								$flag = 1;

								break;

								

							}

							else{	// capacity full

								echo $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|Capacity full";

								echo "<br/>";	

								$flag = 2;

								

								$log_title ="Offline seat allocation Capacity Full ";

								$log_message = $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|".$center_code."|Capacity full";

								$rId = $mem_mem_no;

								$regNo = $mem_mem_no;

								$log_data['title'] = $log_title;

								$log_data['description'] = $log_message;

								$log_data['regid'] = $rId;

								$log_data['regnumber'] = $regNo;

								$this->db->insert('userlogs', $log_data);

								

								

							}

							

						}// end of for loop for time

						if($flag == 1){

							$flag1 = 1;

							break;

						}

				} // end of for loop for venue

				

				$i++;

				

				echo "<br/>========================================<br/>";

				

			}// end of first for loop

			

			

			/*echo count($jaiib_member)."<br/>";

			echo count($sub_arr)."<br/>";

			exit;*/

			// generate seat no. and update

			if((count($jaiib_member) == count($sub_arr)) && count($sub_arr) > 0)

			{

				// generate password

				$password = random_password();

				

				foreach($sub_arr as $sub_details)

				{			

					$v_code = $sub_details['venue_code'];

					$e_date = $sub_details['exam_date'];

					$e_time = $sub_details['exam_time'];

					//$e_time = $sub_details['session_time'];

					

					$sub_code = $sub_details['sub_code'];

					$exam_code = $sub_details['exam_code'];

					

					$mem_mem_no = $sub_details['mem_mem_no'];

					$admitcard_id = $sub_details['admitcard_id'];

						

					// get venue details

					$get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));

					

					//$admit_card_details=$this->master_model->getRecords('admit_card_details',array('sub_cd'=>$sub_code,'mem_mem_no'=>$mem_mem_no,'exm_cd'=>$exam_code,'admitcard_id'=>$admitcard_id));

					

					// generate seat no. 

					$seat_allocation = getseat_j($exam_code, $center_code, $v_code, $e_date, $e_time, 217, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

					

					$update_data = array(

											'pwd' => $password,

											'seat_identification' => $seat_allocation,

											'remark' => 1,

											'modified_on' => date('Y-m-d H:i:s'),

											'exam_date' => $e_date,

											'time' => $e_time,

											'venpin' => $get_venue_details[0]['venue_pincode'],

											'venueadd1' => $get_venue_details[0]['venue_addr1'],

											'venueadd2' => $get_venue_details[0]['venue_addr2'],

											'venueadd3' => $get_venue_details[0]['venue_addr3'],

											'venueadd4' => $get_venue_details[0]['venue_addr4'],

											'venueadd5' => $get_venue_details[0]['venue_addr5'],

											'venue_name' => $get_venue_details[0]['venue_name'],

											'venueid' => $v_code,

											'created_on' => date('Y-m-d H:i:s'),

											'modified_on' => date('Y-m-d H:i:s'),

											'exm_prd'=>217,

											'scribe_flag'=>'N',

											'mode'=>'Online'

										);

					

					// check if seat get 

					if($seat_allocation != '')

					{

						//$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						echo "seat allocation done";

						echo "<br/><br/>";

						echo implode("|",$sub_details)."|".implode("|",$update_data);

						echo "<br/><br/>";

						

						$log_title ="Offline seat allocation done";

						$log_message = implode("|",$sub_details)."|".implode("|",$update_data);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

						

						

					}else{

						echo "seat allocation fail";

						echo "<br/><br/>";

						echo implode("|",$sub_details);

						echo "<br/><br/>";

						

						

						$log_title ="Offline seat allocation fail";

						$log_message = implode("|",$sub_details);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

					}

					

				}

			}

			

			$end_time = date("Y-m-d H:i:s");  

			

			echo "<br/>".$start_time."|".$end_time."<br/>";

			

			echo "<br/>========================================<br/>";

		

		}// end of ffirst for

		

	}

	

	// function to allocation caiib offline data

	public function caiib_previous_test(){

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		//ini_set('max_execution_time', 300); //300 seconds = 5 minutes

		ini_set("memory_limit", "-1");

		ini_set('max_execution_time', 0);

		$start_time = date("Y-m-d H:i:s");

		

			//$member_array = array("100010289","100054194","200059157","300014603","3894274");

			//$member_array = array("300014603");

			// selected center

			$center_code = $this->uri->segment(3);

			//echo "###".$center_code;

			//exit;

			//$center_code = 581; 

			if($center_code == ''){

				die;

			} 

			

			$this->db->distinct('mem_mem_no');

			//$this->db->select('mem_mem_no');

			$this->db->where('center_code', $center_code); 

			$this->db->where('remark !=', 1);

			$this->db->where('exm_cd', $this->config->item('examCodeCaiib')); 

			//$this->db->order_by("admitcard_id", "asc");    

			$this->db->limit(20, 0);  

			//$this->db->where('mem_mem_no', '510150764');

			$member_array = $this->master_model->getRecords('admit_card_details',array('record_source'=>'Offline'),'mem_mem_no,exm_cd',array('admitcard_id'=>'asc')); // add remark condition here

			

			//echo "<pre>";

			//print_r($member_array);

			//exit; 

			//echo "<br/>";

			// subject dates

			$e_date_array = array("2017-12-03","2017-12-10","2017-12-17");

			

			// get all venues for selected center

			

			$ignore = array('360003A','413531A','110095I');

			

			

			$this->db->distinct();

			$this->db->select('venue_code');

			$this->db->where('center_code',$center_code);

			//$this->db->where('venue_code!=','IIBF306');

			$this->db->where_not_in('venue_code', $ignore);

			$this->db->where_in('exam_date', $e_date_array);// need to add dates in IN condition

			$venue = $this->master_model->getRecords('venue_master');

			

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($venue);

			//exit;

			

			echo "<br/>";

			echo "***************************";*/

			

			// selected venue

			/*foreach($venue as $venue_res){

				$venue_arr[] = $venue_res['venue_code'];

			}*/

			$venue_arr = array('416414A');

			

			/*echo "<pre>";

			print_r($venue_arr);*/

			

			

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($member_array);*/

			//exit;*/

			

			

			

			

			

			foreach($member_array as $mem_no){

				

			

				

			$this->db->where('record_source !=','Offline');

			//$this->db->where('remark',1);

			$this->db->where('seat_identification !=','');

			$this->db->where('exm_cd',$this->config->item('examCodeCaiib'));

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$member_chk = $this->master_model->getRecords('admit_card_details','','mem_mem_no');

			

			if(isset($member_chk[0]['mem_mem_no'])){

				echo "already allocate in online";

				echo " >> ".$mem_no['mem_mem_no'];

				echo "<br/>";

				$log_title ="CAIIB Offline seat allocation fail_1";

				$log_message = $member_chk[0]['mem_mem_no'];

				$rId = $member_chk[0]['mem_mem_no'];

				$regNo = $member_chk[0]['mem_mem_no'];

				$log_data['title'] = $log_title;

				$log_data['description'] = $log_message;

				$log_data['regid'] = $rId;

				$log_data['regnumber'] = $regNo;

				$this->db->insert('userlogs', $log_data);

				//break;

			}else{

				

			

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$this->db->where('exm_cd',$mem_no['exm_cd']);

			$this->db->where('remark !=',1);

			$this->db->where('record_source','Offline');

			$jaiib_member = $this->master_model->getRecords('admit_card_details'); // add remark condition here

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($jaiib_member);

			echo "*************************";*/

			/*exit;*/

			

			$i = 0;

			$sub_arr = array();

			

			// check capacity for each subject

			foreach($jaiib_member as $jaiib_member_res)	// <= 3s

			{

				

				

				$sub_details = array();

				$exam_code = $jaiib_member_res['exm_cd'];

				$sub_code = $jaiib_member_res['sub_cd'];

				//echo "<br/>";

				

				$mem_mem_no = $jaiib_member_res['mem_mem_no'];

				$admitcard_id = $jaiib_member_res['admitcard_id'];

				//$admitcard_id = $jaiib_member_res['id'];

				

				$v_code = '';

				$e_date = '';

				$e_time = '';

				$flag = 0;

				$flag1 = 0;

				

				// get subject date

				$this->db->where('exam_code',$exam_code);

				$this->db->where('exam_period',217);

				$this->db->where('subject_code',$sub_code);

				$this->db->order_by("id", "asc");

				$subject = $this->master_model->getRecords('subject_master','','exam_date');

				$exam_date = $subject[0]['exam_date']; 

				

				/*echo $this->db->last_query();

				echo "<br/>";

				

				echo "<pre>";

				print_r($subject);*/

				//exit;

				

				// check for all venues

				$venue_size = count($venue_arr);

				for($j=0;$j<$venue_size;$j++){	// number of venues				

					

						// get all sessions for selected date

						$this->db->where('center_code',$center_code);

						$this->db->where('venue_code',$venue_arr[$j]);

						$this->db->where('exam_date',$exam_date);

						$time_sql = $this->master_model->getRecords('venue_master','','session_time');

						

						//echo $this->db->last_query();

						

						//echo "*************";

						

						/*echo "<pre>";

						print_r($time_sql);

						echo "<br/>";

						echo "################";

						echo "<br/>";

						echo ">>>". $venue_arr[$j];*/

						

								

						

						// make sessions array in descending order

						$time_sql_size = sizeof($time_sql);

						for($l=0;$l<$time_sql_size;$l++){

							

							if($time_sql[$l]['session_time'] == "8.30 AM" || $time_sql[$l]['session_time'] =="8:30 AM")

							{

								$temp_time_arr[0] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "11.15 AM" || $time_sql[$l]['session_time'] =="11:15 AM")

							{

								$temp_time_arr[1] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "2.00 PM" || $time_sql[$l]['session_time'] =="2:00 PM")

							{

								$temp_time_arr[2] = $time_sql[$l]['session_time'];	

							}

							

						}

						

						/*echo "********";

						echo "<pre>";

						print_r($temp_time_arr);

						exit;*/

						// check for all sessions

						 $time_size = count($temp_time_arr);

						 $temp_time_arr = array_values($temp_time_arr);

						 

						/* echo "<br/>";

						 echo "------------------";

						echo "<pre>";

						print_r($temp_time_arr);*/

						/*exit;*/

						for($l=0;$l<$time_size;$l++){

							

							if(isset($temp_time_arr[$l]) && $temp_time_arr[$l] != ''){

								

							// check for capacity

							$capacity = check_capacity_j($venue_arr[$j],$exam_date,$temp_time_arr[$l],$center_code);

							if($capacity != 0){	// if capacity is not full

								

								$v_code = $venue_arr[$j];

								$e_date = $exam_date;

								$e_time = $temp_time_arr[$l];

								

								$sub_details = array("exam_code" => $exam_code, "center_code" => $center_code, "venue_code" => $v_code, "exam_date" => $e_date, "exam_time" => $e_time, "mem_mem_no" => $mem_mem_no, "admitcard_id" => $admitcard_id,"sub_code"=>$sub_code);							

								

								$sub_arr[$sub_code] = $sub_details;

								

								echo $sub_code."|".implode("|",$sub_details);

								echo "<br/>";

								

								$flag = 1;

								break;

								

							}

							else{	// capacity full

								echo $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|Capacity full";

								echo "<br/>";	

								//$flag = 2;

								

								$log_title ="CAIIB Offline seat allocation Capacity Full ";

								$log_message = $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|".$center_code."|Capacity full";

								$rId = $mem_mem_no;

								$regNo = $mem_mem_no;

								$log_data['title'] = $log_title;

								$log_data['description'] = $log_message;

								$log_data['regid'] = $rId;

								$log_data['regnumber'] = $regNo;

								$this->db->insert('userlogs', $log_data);

								

								

							}

								/*if($flag == 2){

									break;

								}*/

							}

							

						}// end of for loop for time

						if($flag == 1){

							$flag1 = 1;

							break;

						}

				} // end of for loop for venue

				

				$i++;

				

				echo "<br/>========================================<br/>";

				

			}// end of first for date loop

			

			

			/*echo count($jaiib_member)."<br/>";

			echo count($sub_arr)."<br/>";

			exit;*/

			// generate seat no. and update

			if((count($jaiib_member) == count($sub_arr)) && count($sub_arr) > 0)

			{

				

				$this->db->where('record_source','Offline');

				$this->db->where('remark',1);

				$this->db->where('exm_cd',$this->config->item('examCodeCaiib'));

				$this->db->where('mem_mem_no',$sub_details['mem_mem_no']);

				$this->db->where('pwd !=','');

				$this->db->limit(1, 0);  

				$member_chk_pwd = $this->master_model->getRecords('admit_card_details','','pwd');

				

				// generate password

				if(count($member_chk_pwd) > 0){

					$password = $member_chk_pwd[0]['pwd'];

				}else{

					$password = random_password();

				}

				

				

				foreach($sub_arr as $sub_details)

				{			

					$v_code = $sub_details['venue_code'];

					$e_date = $sub_details['exam_date'];

					$e_time = $sub_details['exam_time'];

					//$e_time = $sub_details['session_time'];

					

					$sub_code = $sub_details['sub_code'];

					$exam_code = $sub_details['exam_code'];

					

					$mem_mem_no = $sub_details['mem_mem_no'];

					$admitcard_id = $sub_details['admitcard_id'];

						

					// get venue details

					$get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));

					

					//$admit_card_details=$this->master_model->getRecords('admit_card_details',array('sub_cd'=>$sub_code,'mem_mem_no'=>$mem_mem_no,'exm_cd'=>$exam_code,'admitcard_id'=>$admitcard_id));

					

					// generate seat no. 

					$seat_allocation = getseat_j($exam_code, $center_code, $v_code, $e_date, $e_time, 217, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

					

					$update_data = array(

											'pwd' => $password,

											'seat_identification' => $seat_allocation,

											'remark' => 1,

											'modified_on' => date('Y-m-d H:i:s'),

											'exam_date' => $e_date,

											'time' => $e_time,

											'venpin' => $get_venue_details[0]['venue_pincode'],

											'venueadd1' => $get_venue_details[0]['venue_addr1'],

											'venueadd2' => $get_venue_details[0]['venue_addr2'],

											'venueadd3' => $get_venue_details[0]['venue_addr3'],

											'venueadd4' => $get_venue_details[0]['venue_addr4'],

											'venueadd5' => $get_venue_details[0]['venue_addr5'],

											'venue_name' => $get_venue_details[0]['venue_name'],

											'venueid' => $v_code,

											'created_on' => date('Y-m-d H:i:s'),

											'modified_on' => date('Y-m-d H:i:s'),

											'exm_prd'=>217,

											'scribe_flag'=>'N',

											'mode'=>'Online'

										);

					

					// check if seat get 

					if($seat_allocation != '')

					{

						//$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						echo "seat allocation done";

						echo "<br/><br/>";

						echo implode("|",$sub_details)."|".implode("|",$update_data);

						echo "<br/><br/>";

						

						$log_title ="CAIIB Offline seat allocation done";

						$log_message = implode("|",$sub_details)."|".implode("|",$update_data);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

						

						

					}else{

						echo "seat allocation fail";

						echo "<br/><br/>";

						echo implode("|",$sub_details);

						echo "<br/><br/>";

						

						

						$log_title ="CAIIB Offline seat allocation fail_2";

						$log_message = implode("|",$sub_details);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

					}

					

				}

			}

			

			$end_time = date("Y-m-d H:i:s");  

			

			echo "<br/>".$start_time."|".$end_time."<br/>";

			

			echo "<br/>========================================<br/>";

			

			}

		

		}// end of ffirst for

		

	}

	

	public function custom_contact_class_invoice(){

		echo $path = custom_genarate_custom_contact_classes_invoice(2723867,3225);

	}

	

	public function offline_recover(){

		$this->db->where('center_code',324); 

		//$this->db->limit(1, 0); 

		$backup = $this->master_model->getRecords('admit_card_details_324');

		foreach($backup as $backupres){

			

			$update_data = array(

								'venueid' => $backupres['venueid'],

								'venue_name' => $backupres['venue_name'],

								'venueadd1' => $backupres['venueadd1'],

								'venueadd2' => $backupres['venueadd2'],

								'venueadd3' => $backupres['venueadd3'],

								'venueadd4' => $backupres['venueadd4'],

								'venueadd5' => $backupres['venueadd5'],

								'venpin' => $backupres['venpin'],

								'pwd' => $backupres['pwd'],

								'exam_date' => $backupres['exam_date'],

								'time' => $backupres['time'],

								'seat_identification'=>$backupres['seat_identification'],

								'remark'=>$backupres['remark'],

								'created_on'=>$backupres['created_on'],

								'modified_on' => $backupres['modified_on']

							);

			

			

			$this->db->where('remark',0);

			$this->db->where('record_source','Offline');

			$this->db->where('venueid','');

			$this->db->where('venue_name','');

			$this->db->where('venueadd1','');

			$this->db->where('venueadd2','');

			$this->db->where('venueadd3','');

			$this->db->where('venueadd4','');

			$this->db->where('venueadd5','');

			$this->db->where('pwd','');

			$this->db->where('time','');

			$this->db->where('seat_identification','');

			

			$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$backupres['admitcard_id']));	

			

			//$this->master_model->updateRecord('admit_card_details_parent',$update_data,array('admitcard_id'=>$backupres['admitcard_id'],'remark'=>0,'venueid'=>'','venue_name'=>'','venueadd1'=>'','venueadd2'=>'','venueadd3'=>'','venueadd4'=>'','venueadd5'=>'','pwd'=>'','time'=>'','seat_identification'=>''));	

			

			

			

			

		}

		

	}

	

	// send offline mail

	public function generate_offline_admitcard(){

		

		

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		$start_time = date("Y-m-d H:i:s");

		$center_code = $this->uri->segment(3);

		if($center_code == ''){

			die;

		}

		

		$this->db->distinct();

		$this->db->select('mem_mem_no,exm_cd,m_1,center_code,mam_nam_1');

		$this->db->where('record_source','Offline');

		$this->db->where('remark',1);

		$this->db->where('exm_cd',$this->config->item('examCodeCaiib'));

		$this->db->where('admitcard_image', '');

		$this->db->where('center_code', $center_code);

		//$this->db->limit(100,0);

		$record = $this->master_model->getRecords('admit_card_details');

		

		/*echo "<pre>";

		print_r($record);

		exit;*/

		 

		foreach($record as $res){

			$member_id = $res['mem_mem_no'];

			$exam_code = $res['exm_cd'];

			$exam_period = 217;

			$attachpath = custom_genarate_admitcard_offline($member_id,$exam_code,$exam_period);

			

			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$res['mem_mem_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode');

			

			//Query to get Medium	

			$this->db->where('exam_code',$exam_code);

			$this->db->where('exam_period',217);

			$this->db->where('medium_code',$res['m_1']);

			$this->db->where('medium_delete','0');

			$medium=$this->master_model->getRecords('medium_master','','medium_description');

			

			$this->db->where('exam_code',$exam_code);

			$exam_info = $this->master_model->getRecords('exam_master','','description');

			

			$this->db->where('center_code',$res['center_code']);

			$center = $this->master_model->getRecords('center_master','','center_name');

			

			

			

			if($attachpath!=''){ 

				

				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'offline'));

				$newstring1= str_replace("#USERNAME#", "".$res['mam_nam_1']."",$emailerstr[0]['emailer_text']);

				$newstring2 = str_replace("#REG_NUM#", "".$res['mem_mem_no']."",$newstring1);

				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

				$newstring4 = str_replace("#EXAM_DATE#", "Nov-2017",$newstring3);

				$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);

				$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);

				$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);

				$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);

				$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);

				$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);

				$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);

				$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);

				$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);

				$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);

				$newstring17 = str_replace("#CENTER#", "".$center[0]['center_name']."",$newstring16);

				$newstring18 = str_replace("#CENTER_CODE#", "".$res['center_code']."",$newstring17);

				$newstring19 = str_replace("#MODE#", "Online",$newstring18);

				$final_str = $newstring19;

				

				

				$info_arr=array(//'to'=>$result[0]['email'],

								'to'=>'ztest2500@gmail.com',

								'from'=>$emailerstr[0]['from'],

								'subject'=>$emailerstr[0]['subject'],

								'message'=>$final_str

								);

				

				$files=array($attachpath);

				

				if($this->Emailsending->mailsend_attch($info_arr,$files)){

					

					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline'));

				

					foreach($admit_card_details as $arec){

						if($arec['admitcard_image'] == '' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'yes','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['admitcard_image'] != ''){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'yes','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}else{

					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline'));

				

					foreach($admit_card_details as $arec){

						if($arec['admitcard_image'] == '' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'no','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['admitcard_image'] != ''){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'no','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}

				

				

			}elseif($attach_path==''){

				echo "admitcard not generate : ".$res['mem_mem_no'];

				echo "<br/>";

			}

			

			if($attachpath!=''){

				echo $result[0]['email'];

				echo "<br/>";

			}

		}

	}

	

	//send mail for close venue

	public function close_venue_mail(){

		

		

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		$start_time = date("Y-m-d H:i:s");

		$center_code = $this->uri->segment(3);

		if($center_code == ''){

			die;

		}

		

		$member_array = array(200027814);

		$center_array = array(610);

		$exm_array = array($this->config->item('examCodeCaiib'),62,63,64,65,66,67,68,69,70,71,72);

		

		$this->db->distinct();

		$this->db->select('mem_mem_no,exm_cd,m_1,center_code,mam_nam_1');

		$this->db->where('record_source !=','Offline');

		$this->db->where('remark',1);

		$this->db->where_in('exm_cd',$exm_array);

		$this->db->where_in('mem_mem_no',$member_array);

		$this->db->where('admitcard_image', '');

		$this->db->where('center_code', $center_code);

		$this->db->limit(1,0);

		$record = $this->master_model->getRecords('admit_card_details');

		

		

		foreach($record as $res){

			$member_id = $res['mem_mem_no'];

			$exam_code = $res['exm_cd'];

			$exam_period = 217;

			$attachpath = custom_genarate_admitcard_close_venue($member_id,$exam_code,$exam_period);

			

			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$res['mem_mem_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode');

			

			//Query to get Medium	

			$this->db->where('exam_code',$exam_code);

			$this->db->where('exam_period',217);

			$this->db->where('medium_code',$res['m_1']);

			$this->db->where('medium_delete','0');

			$medium=$this->master_model->getRecords('medium_master','','medium_description');

			

			$this->db->where('exam_code',$exam_code);

			$exam_info = $this->master_model->getRecords('exam_master','','description');

			

			$this->db->where('center_code',$res['center_code']);

			$center = $this->master_model->getRecords('center_master','','center_name');

			

			

			

			if($attachpath!=''){ 

				

				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'offline'));

				$newstring1= str_replace("#USERNAME#", "".$res['mam_nam_1']."",$emailerstr[0]['emailer_text']);

				$newstring2 = str_replace("#REG_NUM#", "".$res['mem_mem_no']."",$newstring1);

				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

				$newstring4 = str_replace("#EXAM_DATE#", "Nov-2017",$newstring3);

				$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);

				$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);

				$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);

				$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);

				$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);

				$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);

				$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);

				$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);

				$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);

				$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);

				$newstring17 = str_replace("#CENTER#", "".$center[0]['center_name']."",$newstring16);

				$newstring18 = str_replace("#CENTER_CODE#", "".$res['center_code']."",$newstring17);

				$newstring19 = str_replace("#MODE#", "Online",$newstring18);

				$final_str = $newstring19;

				

				

				$info_arr=array('to'=>$result[0]['email'],

								//'to'=>'ztest2500@gmail.com',

								'from'=>$emailerstr[0]['from'],

								'subject'=>$emailerstr[0]['subject'],

								'message'=>$final_str

								); 

				

				$files=array($attachpath);

				

				if($this->Emailsending->mailsend_attch($info_arr,$files)){

					

					$this->db->where('record_source !=','Offline');

					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));

				

					foreach($admit_card_details as $arec){

						if($arec['admitcard_image'] == '' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'yes','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['admitcard_image'] != ''){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'yes','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}else{

					$this->db->where('record_source !=','Offline');

					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));

				

					foreach($admit_card_details as $arec){

						if($arec['admitcard_image'] == '' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'no','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['admitcard_image'] != ''){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'no','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}

				

				

			}elseif($attach_path==''){

				echo "admitcard not generate : ".$res['mem_mem_no'];

				echo "<br/>";

			}

			

			if($attachpath!=''){

				echo $result[0]['email'];

				echo "<br/>";

			}

		}

	}

	

	public function download_admitcard_jaiibdbf(){

		try{

			$data=array();

			$data['error']='';

			

		    if(isset($_POST['submit'])){

				$config = array(

								array(

										'field' => 'Username',

										'label' => 'Username',

										'rules' => 'trim|required'

									),

								array(

										'field' => 'code',

										'label' => 'Code',

										'rules' => 'trim|required|callback_check_captcha_userlogin',

									),

							);

			

				$this->form_validation->set_rules($config);

				$dataarr=array(

					'regnumber'=> $this->input->post('Username'),

				);

				if ($this->form_validation->run() == TRUE){

					$user_info=$this->master_model->getRecords('member_registration',$dataarr);

					if(count($user_info) > 0){ 

						$mysqltime=date("H:i:s");

						$seprate_user_data=array('regid'=>$user_info[0]['regid'],

													'spregnumber'=>$user_info[0]['regnumber'],

													'spfirstname'=>$user_info[0]['firstname'],

													'spmiddlename'=>$user_info[0]['middlename'],

													'splastname'=>$user_info[0]['lastname']

												);

						$this->session->set_userdata($seprate_user_data);

						redirect(base_url().'dwnletter/getadmitdashboard');	

					}else{

						$data['error']='<span style="">Invalid credential.</span>';

					}

				}else{

					$data['validation_errors'] = validation_errors();

				}

			}

			$this->load->helper('captcha');

			$vals = array(

							'img_path' => './uploads/applications/',

							'img_url' => '/uploads/applications/',

						);

			$cap = create_captcha($vals);

			$data['image'] = $cap['image'];

			$data['code']=$cap['word'];

			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);

			//admitcardlogin

			$this->load->view('jdlogin',$data);

			

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();

		}

	}

	

	public function getadmitdashboard(){

		try{

			if($this->session->userdata('spregnumber') != ''){

				$member_id = $this->session->userdata('spregnumber');

				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');

			}

			

			

			if(!isset($member_id)){

				redirect(base_url('dwnletter/download_admitcard_jaiibdbf/'));

			}

			

			$query = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' ");

			$exm_arr = $query->result();

			

			

			if(count($exm_arr) > 1){

			

			$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id

FROM admit_exam_master

JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code

WHERE admit_card_details.mem_mem_no = '".$member_id."'

GROUP BY admit_card_details.exm_cd

ORDER BY admit_card_details.admitcard_id DESC 

 ");

 

			}elseif(count($exm_arr) == 1){

 

$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id

FROM admit_exam_master

JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code

WHERE admit_card_details.mem_mem_no = '".$member_id."'

ORDER BY admit_card_details.admitcard_id DESC 

LIMIT 1;

");

			}

			

			

			$result = $record->result();

			/*echo $this->db->last_query();

			echo "<pre>";

			print_r($result);

			exit;*/

			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);

			$this->load->view('jddashboard',$data);

			

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();

		}	

	}

	

	public function getadmitcardsp(){

		//To Do-- validate as per admin admit card setting(Need to Do)

		try{

			

			if($this->session->userdata('spregnumber') != ''){

				$member_id = $this->session->userdata('spregnumber');

			}

			if($this->session->userdata('regnumber') != ''){

				$member_id = $this->session->userdata('regnumber');

			}

			if($this->session->userdata('nmregnumber') != ''){

				$member_id = $this->session->userdata('nmregnumber');

			}

			if($this->session->userdata('dbregnumber') != ''){

				$member_id = $this->session->userdata('dbregnumber');

			}

			

			

			

			$this->db->select('center_code'); 

			$this->db->from('sify_center');

			$scenter = $this->db->get();

			$sifyresult = $scenter->result();

			foreach($sifyresult as $sifyresult){

				$sifycenter[] = $sifyresult->center_code;

			}

			

			$this->db->select('center_code'); 

			$this->db->from('nseit_center');

			$ncenter = $this->db->get();

			$nseitresult = $ncenter->result();

			foreach($nseitresult as $nseitresult){

				$nseitcenter[] = $nseitresult->center_code;

			}

			

			$img_path = base_url()."uploads/photograph/";

			$sig_path =  base_url()."uploads/scansignature/";

			$exam_code = base64_decode($this->uri->segment(3));

			

			

			

			$this->db->select('admit_card_details.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');

			$this->db->from('admit_card_details');

			$this->db->join('member_registration', 'admit_card_details.mem_mem_no = member_registration.regnumber');

			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));

			$record = $this->db->get();

			$result = $record->row();

			

			

			$this->db->select('*');

			$this->db->from('admit_card_details');

			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));

			$this->db->group_by('venueid');

			$this->db->order_by("exam_date", "asc");

			$nrecord = $this->db->get();

			$results = $nrecord->result();

			

			if(in_array($result->center_code, $nseitcenter)){

				$vcenter = 'NSEIT';

			}

			if(in_array($result->center_code, $sifycenter)){

				$vcenter = 'SIFY';

			}

			

			

			$medium_code = $result->m_1;

			

			$this->db->select('description');

			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));

			$exam_result = $exam->row();

			

			//$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = RIGHT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");

			$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");

			$subject_result = $subject->result();

			

			//echo $this->db->last_query();

			//exit;

			

			//echo "<pre>";

			//print_r($subject_result);

			//exit;

			

			$pdate = $subject->result();

			//echo "<pre>";

			//print_r($pdate);

			//exit;

			

			foreach($pdate as $pdate){

				$exdate = $pdate->exam_date;

				$examdate = explode("-",$exdate);

				$examdatearr[] = $examdate[1];

			}

			

			 

			

			

			$exdate = $subject_result[0]->exam_date;

			$examdate = explode("-",$exdate);

			//$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];

			$printdate = 'Nov 2017';

			//echo $printdate;

			//exit;

			

			

			if($medium_code == 'ENGLISH' || $medium_code == 'E'){

				$medium_code_lng = 'E';

			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){

				$medium_code_lng = 'H';

			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){

				$medium_code_lng = 'A';

			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){

				$medium_code_lng = 'G';

			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){

				$medium_code_lng = 'K';

			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){

				$medium_code_lng = 'L';

			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){

				$medium_code_lng = 'M';

			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){

				$medium_code_lng = 'N';

			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){

				$medium_code_lng = 'O';

			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){

				$medium_code_lng = 'S';

			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){

				$medium_code_lng = 'T';

			}

			

			$this->db->select('medium_description');

			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));

			$medium_result = $medium->row();

			

			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);

			//load the view and saved it into $html variable

			$this->load->view('jdadmitcardsp', $data);

			

		}catch(Exception $e){

			echo $e->getMessage();

		}

	}

	

	public function getadmitcardpdfsp(){

		//To Do-- validate as per admin admit card setting(Need to Do)

		try{

			

			if($this->session->userdata('spregnumber') != ''){

				$member_id = $this->session->userdata('spregnumber');

			}

			if($this->session->userdata('regnumber') != ''){

				$member_id = $this->session->userdata('regnumber');

			}

			if($this->session->userdata('nmregnumber') != ''){

				$member_id = $this->session->userdata('nmregnumber');

			}

			if($this->session->userdata('dbregnumber') != ''){

				$member_id = $this->session->userdata('dbregnumber');

			}

			

			$this->db->select('center_code'); 

			$this->db->from('sify_center');

			$scenter = $this->db->get();

			$sifyresult = $scenter->result();

			foreach($sifyresult as $sifyresult){

				$sifycenter[] = $sifyresult->center_code;

			}

			

			

			$this->db->select('center_code'); 

			$this->db->from('nseit_center');

			$ncenter = $this->db->get();

			$nseitresult = $ncenter->result();

			foreach($nseitresult as $nseitresult){

				$nseitcenter[] = $nseitresult->center_code;

			}

			

			$exam_code = base64_decode($this->uri->segment(3));

			$img_path = base_url()."uploads/photograph/";

			$sig_path =  base_url()."uploads/scansignature/";

			

			$this->db->select('admit_card_details.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');

			$this->db->from('admit_card_details');

			$this->db->join('member_registration', 'admit_card_details.mem_mem_no = member_registration.regnumber');

			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));

			$record = $this->db->get();

			$result = $record->row();

			

			$this->db->select('*');

			$this->db->from('admit_card_details');

			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));

			$this->db->group_by('venueid');

			$this->db->order_by("exam_date", "asc");

			$nrecord = $this->db->get();

			$results = $nrecord->result();

			

			if(in_array($result->center_code, $nseitcenter)){

				$vcenter = 'NSEIT';

			}

			if(in_array($result->center_code, $sifycenter)){

				$vcenter = 'SIFY';

			}

			

			//$exam_code = $result->exm_cd;

			

			$medium_code = $result->m_1;

			

			$this->db->select('description');

			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));

			$exam_result = $exam->row();

			

			

			//$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = RIGHT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");

			$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");

			

			$subject_result = $subject->result();

			

			

			$pdate = $subject->result();

			foreach($pdate as $pdate){

				$exdate = $pdate->exam_date;

				$examdate = explode("-",$exdate);

				$examdatearr[] = $examdate[1];

			}

			

			$exdate = $subject_result[0]->exam_date;

			$examdate = explode("-",$exdate);

			//$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];

			$printdate = 'Nov 2017';

			

			if($medium_code == 'ENGLISH' || $medium_code == 'E'){

				$medium_code_lng = 'E';

			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){

				$medium_code_lng = 'H';

			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){

				$medium_code_lng = 'A';

			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){

				$medium_code_lng = 'G';

			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){

				$medium_code_lng = 'K';

			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){

				$medium_code_lng = 'L';

			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){

				$medium_code_lng = 'M';

			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){

				$medium_code_lng = 'N';

			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){

				$medium_code_lng = 'O';

			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){

				$medium_code_lng = 'S';

			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){

				$medium_code_lng = 'T';

			}

			

			$this->db->select('medium_description');

			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));

			$medium_result = $medium->row();

			

			

			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate);

			

			//echo "<pre>";

			//print_r($data);

			

			//load the view and saved it into $html variable

			$html=$this->load->view('jdadmitcardpdf', $data, true);

			//this the the PDF filename that user will get to download

			//echo $html;

			//exit;

			$this->load->library('m_pdf');

			$pdf = $this->m_pdf->load();

			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";

			//generate the PDF from the given html

			$pdf->WriteHTML($html);

			//download it.

			$pdf->Output($pdfFilePath, "D");  

			

		}catch(Exception $e){

			echo $e->getMessage(); 

		}

	}

	

	public function check_captcha_userlogin($code){

		try{

			if(!isset($this->session->useradmitcardlogincaptcha) && empty($this->session->useradmitcardlogincaptcha)){

				redirect(base_url().'index/');

			}

			if($code == '' || $this->session->useradmitcardlogincaptcha != $code ){

				$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 

				$this->session->set_userdata("userlogincaptcha", rand(1,100000));

				return false;

			}

			if($this->session->useradmitcardlogincaptcha == $code){

				$this->session->set_userdata('useradmitcardlogincaptcha','');

				$this->session->unset_userdata("useradmitcardlogincaptcha");

				return true;

			}

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();	

		}

	}

	

	public function Logout(){

		try{

			$sessionData = $this->session->all_userdata();

			foreach($sessionData as $key =>$val){

				$this->session->unset_userdata($key);    

			}

			redirect('http://iibf.org.in/');

		}catch(Exception $e){

			echo "Message : ".$e->getMessage();

		}	

	}

	

	public function generate_dra_invoice(){

		

		$arr = array('8953','8954','8955','8976','9021','9107','9111','9126','9193','9277','9308','9334','9342','9346','9359','9362','9366','9034','9206','9323','9164','9195','9377');

		for($i=0;$i<=22;$i++){

			echo $path = custom_genarate_draexam_invoice($arr[$i]);

			echo "<br/>";

		}

		

		//echo $path = custom_genarate_draexam_invoice(9178);	

	} 

	

	public function offline_33(){

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		$start_time = date("Y-m-d H:i:s");

		

			//$member_array = array("100010289","100054194","200059157","300014603","3894274");

			//$member_array = array("300014603");

			// selected center

			$center_code = $this->uri->segment(3);

			//echo "###".$center_code;

			//exit;

			//$center_code = 581; 

			if($center_code == ''){

				die;

			}

			

			$this->db->distinct('mem_mem_no');

			//$this->db->select('mem_mem_no');

			$this->db->where('center_code', $center_code); 

			$this->db->where('remark !=', 1);

			$this->db->where('exm_cd', 20); 

			//$this->db->order_by("admitcard_id", "asc");    

			//$this->db->limit(1, 0);  

			$member_array = $this->master_model->getRecords('admit_card_details',array('record_source'=>'Offline'),'mem_mem_no,exm_cd',array('admitcard_id'=>'asc')); // add remark condition here

			

			//echo "<pre>";

			//print_r($member_array);

			//exit; 

			//echo "<br/>";

			// subject dates

			$e_date_array = array("2017-11-25");

			

			// get all venues for selected center

			

			//$ignore = array('360003A','413531A','110095I');

			

			

			$this->db->distinct();

			$this->db->select('venue_code');

			$this->db->where('center_code',$center_code);

			//$this->db->where('venue_code!=','IIBF306');

			//$this->db->where_not_in('venue_code', $ignore);

			$this->db->where_in('exam_date', $e_date_array);// need to add dates in IN condition

			$venue = $this->master_model->getRecords('venue_master');

			

			/*echo "<pre>";

			print_r($venue);

			exit;*/

			

			// selected venue

			foreach($venue as $venue_res){

				$venue_arr[] = $venue_res['venue_code'];

			}

			//$venue_arr = array(400007);

			

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($member_array);*/

			//exit;*/

			

			

			

			

			

			foreach($member_array as $mem_no){

				

			$this->db->where('record_source !=','Offline');

			//$this->db->where('remark',1);

			$this->db->where('seat_identification !=','');

			$this->db->where('exm_cd',20);

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$member_chk = $this->master_model->getRecords('admit_card_details','','mem_mem_no');

			

			if(isset($member_chk[0]['mem_mem_no'])){

				echo "already allocate in online";

				$log_title ="Offline 33 seat allocation fail_1";

				$log_message = $member_chk[0]['mem_mem_no'];

				$rId = $member_chk[0]['mem_mem_no'];

				$regNo = $member_chk[0]['mem_mem_no'];

				$log_data['title'] = $log_title;

				$log_data['description'] = $log_message;

				$log_data['regid'] = $rId;

				$log_data['regnumber'] = $regNo;

				$this->db->insert('userlogs', $log_data);

				//break;

			}else{

				

			

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$this->db->where('exm_cd',$mem_no['exm_cd']);

			$this->db->where('remark !=',1);

			$this->db->where('record_source','Offline');

			$jaiib_member = $this->master_model->getRecords('admit_card_details'); // add remark condition here

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($jaiib_member);

			echo "*************************";*/

			/*exit;*/

			

			$i = 0;

			$sub_arr = array();

			

			// check capacity for each subject

			foreach($jaiib_member as $jaiib_member_res)	// <= 3s

			{

				

				

				$sub_details = array();

				$exam_code = $jaiib_member_res['exm_cd'];

				$sub_code = $jaiib_member_res['sub_cd'];

				//echo "<br/>";

				

				$mem_mem_no = $jaiib_member_res['mem_mem_no'];

				$admitcard_id = $jaiib_member_res['admitcard_id'];

				//$admitcard_id = $jaiib_member_res['id'];

				

				$v_code = '';

				$e_date = '';

				$e_time = '';

				$flag = 0;

				$flag1 = 0;

				

				// get subject date

				/*$this->db->where('exam_code',$exam_code);

				$this->db->where('exam_period',517);

				$this->db->where('subject_code',$sub_code);

				$this->db->order_by("id", "asc");

				$subject = $this->master_model->getRecords('subject_master','','exam_date');*/

				$exam_date = "2017-11-25"; 

				

				/*echo $this->db->last_query();

				echo "<br/>";

				

				echo "<pre>";

				print_r($subject);*/

				//exit;

				

				// check for all venues

				$venue_size = count($venue_arr);

				for($j=0;$j<$venue_size;$j++){	// number of venues				

					

						// get all sessions for selected date

						$this->db->where('center_code',$center_code);

						$this->db->where('venue_code',$venue_arr[$j]);

						$this->db->where('exam_date',$exam_date);

						$time_sql = $this->master_model->getRecords('venue_master','','session_time');

						

						//echo "<pre>";

						//print_r($time_sql);

						//exit;	

						

								

						

						// make sessions array in descending order

						$time_sql_size = sizeof($time_sql);

						for($l=0;$l<$time_sql_size;$l++){

							

							if($time_sql[$l]['session_time'] == "12:30 PM" || $time_sql[$l]['session_time'] =="12.30 PM")

							{

								$temp_time_arr[0] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "10:00 AM" || $time_sql[$l]['session_time'] =="10.00 AM")

							{

								$temp_time_arr[1] = $time_sql[$l]['session_time'];

							}

							

						}

						

						

						// check for all sessions

						 $time_size = count($temp_time_arr);

						 if($time_size == 2){

						 $str = "$l<$time_size";

						 }else{

						 $str = "$l<=$time_size";

						 }

						

						 

						 

						 

						for($l=0;$str;$l++){

							

							if(isset($temp_time_arr[$l]) && $temp_time_arr[$l] != ''){

								

							// check for capacity

							$capacity = check_capacity_j($venue_arr[$j],$exam_date,$temp_time_arr[$l],$center_code);

							if($capacity != 0){	// if capacity is not full

								

								$v_code = $venue_arr[$j];

								$e_date = $exam_date;

								$e_time = $temp_time_arr[$l];

								

								$sub_details = array("exam_code" => $exam_code, "center_code" => $center_code, "venue_code" => $v_code, "exam_date" => $e_date, "exam_time" => $e_time, "mem_mem_no" => $mem_mem_no, "admitcard_id" => $admitcard_id,"sub_code"=>$sub_code);							

								

								$sub_arr[$sub_code] = $sub_details;

								

								echo $sub_code."|".implode("|",$sub_details);

								echo "<br/>";

								

								$flag = 1;

								break;

								

							}

							else{	// capacity full

								echo $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|Capacity full";

								echo "<br/>";	

								//$flag = 2;

								

								$log_title ="Offline 33 seat allocation Capacity Full ";

								$log_message = $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|".$center_code."|Capacity full";

								$rId = $mem_mem_no;

								$regNo = $mem_mem_no;

								$log_data['title'] = $log_title;

								$log_data['description'] = $log_message;

								$log_data['regid'] = $rId;

								$log_data['regnumber'] = $regNo;

								$this->db->insert('userlogs', $log_data);

								

								

							}

								/*if($flag == 2){

									break;

								}*/

							}

							

						}// end of for loop for time

						if($flag == 1){

							$flag1 = 1;

							break;

						}

				} // end of for loop for venue

				

				$i++;

				

				echo "<br/>========================================<br/>";

				

			}// end of first for date loop

			

			

			/*echo count($jaiib_member)."<br/>";

			echo count($sub_arr)."<br/>";

			exit;*/

			// generate seat no. and update

			if((count($jaiib_member) == count($sub_arr)) && count($sub_arr) > 0)

			{

				

				$this->db->where('record_source','Offline');

				$this->db->where('remark',1);

				$this->db->where('exm_cd',20);

				$this->db->where('mem_mem_no',$sub_details['mem_mem_no']);

				$this->db->where('pwd !=','');

				$this->db->limit(1, 0);  

				$member_chk_pwd = $this->master_model->getRecords('admit_card_details','','pwd');

				

				// generate password

				if(count($member_chk_pwd) > 0){

					$password = $member_chk_pwd[0]['pwd'];

				}else{

					$password = random_password();

				}

				

				

				foreach($sub_arr as $sub_details)

				{			

					$v_code = $sub_details['venue_code'];

					$e_date = $sub_details['exam_date'];

					$e_time = $sub_details['exam_time'];

					//$e_time = $sub_details['session_time'];

					

					$sub_code = $sub_details['sub_code'];

					$exam_code = $sub_details['exam_code'];

					

					$mem_mem_no = $sub_details['mem_mem_no'];

					$admitcard_id = $sub_details['admitcard_id'];

						

					// get venue details

					$get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));

					

					//$admit_card_details=$this->master_model->getRecords('admit_card_details',array('sub_cd'=>$sub_code,'mem_mem_no'=>$mem_mem_no,'exm_cd'=>$exam_code,'admitcard_id'=>$admitcard_id));

					

					// generate seat no. 

					$seat_allocation = getseat_j($exam_code, $center_code, $v_code, $e_date, $e_time, 517, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

					

					$update_data = array(

											'pwd' => $password,

											'seat_identification' => $seat_allocation,

											'remark' => 1,

											'modified_on' => date('Y-m-d H:i:s'),

											'exam_date' => $e_date,

											'time' => $e_time,

											'venpin' => $get_venue_details[0]['venue_pincode'],

											'venueadd1' => $get_venue_details[0]['venue_addr1'],

											'venueadd2' => $get_venue_details[0]['venue_addr2'],

											'venueadd3' => $get_venue_details[0]['venue_addr3'],

											'venueadd4' => $get_venue_details[0]['venue_addr4'],

											'venueadd5' => $get_venue_details[0]['venue_addr5'],

											'venue_name' => $get_venue_details[0]['venue_name'],

											'venueid' => $v_code,

											'created_on' => date('Y-m-d H:i:s'),

											'modified_on' => date('Y-m-d H:i:s'),

											'exm_prd'=>517,

											'scribe_flag'=>'N',

											'mode'=>'Online'

										);

					

					// check if seat get 

					if($seat_allocation != '')

					{

						//$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						echo "seat allocation done";

						echo "<br/><br/>";

						echo implode("|",$sub_details)."|".implode("|",$update_data);

						echo "<br/><br/>";

						

						$log_title ="Offline 33 seat allocation done";

						$log_message = implode("|",$sub_details)."|".implode("|",$update_data);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

						

						

					}else{

						echo "seat allocation fail";

						echo "<br/><br/>";

						echo implode("|",$sub_details);

						echo "<br/><br/>";

						

						

						$log_title ="Offline 33 seat allocation fail_2";

						$log_message = implode("|",$sub_details);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

					}

					

				}

			}

			

			$end_time = date("Y-m-d H:i:s");  

			

			echo "<br/>".$start_time."|".$end_time."<br/>";

			

			echo "<br/>========================================<br/>";

			

			}

		

		}// end of ffirst for

		

	}

	

	public function offline_33_mail(){

		

		

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		$start_time = date("Y-m-d H:i:s");

		$center_code = $this->uri->segment(3);

		if($center_code == ''){

			die;

		}

		

		$this->db->distinct();

		$this->db->select('mem_mem_no,exm_cd,m_1,center_code,mam_nam_1');

		$this->db->where('record_source','Offline');

		$this->db->where('remark',1);

		$this->db->where('admitcard_image', '');

		$this->db->where('exm_cd', 20);

		$this->db->where('center_code', $center_code);

		//$this->db->limit(100,0);

		$record = $this->master_model->getRecords('admit_card_details');

		

		/*echo "<pre>";

		print_r($record);

		exit;*/

		 

		foreach($record as $res){

			$member_id = $res['mem_mem_no'];

			$exam_code = $res['exm_cd'];

			$exam_period = 517;

			$attachpath = custom_genarate_admitcard_offline($member_id,$exam_code,$exam_period);

			

			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$res['mem_mem_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode');

			

			//Query to get Medium	

			

			

			$this->db->where('exam_code',$exam_code);

			$exam_info = $this->master_model->getRecords('exam_master','','description');

			

			$this->db->where('center_code',$res['center_code']);

			$center = $this->master_model->getRecords('center_master','','center_name');

			

			

			

			if($attachpath!=''){ 

				

				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'offline'));

				$newstring1= str_replace("#USERNAME#", "".$res['mam_nam_1']."",$emailerstr[0]['emailer_text']);

				$newstring2 = str_replace("#REG_NUM#", "".$res['mem_mem_no']."",$newstring1);

				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

				$newstring4 = str_replace("#EXAM_DATE#", "Nov-2017",$newstring3);

				$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);

				$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);

				$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);

				$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);

				$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);

				$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);

				$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);

				$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);

				$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);

				$newstring16 = str_replace("#MEDIUM#", "ENGLISH",$newstring14);

				$newstring17 = str_replace("#CENTER#", "".$center[0]['center_name']."",$newstring16);

				$newstring18 = str_replace("#CENTER_CODE#", "".$res['center_code']."",$newstring17);

				$newstring19 = str_replace("#MODE#", "Online",$newstring18);

				$final_str = $newstring19;

				

				

				$info_arr=array('to'=>$result[0]['email'],

								//'to'=>'ztest2500@gmail.com',

								'from'=>$emailerstr[0]['from'],

								'subject'=>$emailerstr[0]['subject'],

								'message'=>$final_str

								);

				

				$files=array($attachpath);

				

				if($this->Emailsending->mailsend_attch($info_arr,$files)){

					

					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline'));

				

					foreach($admit_card_details as $arec){

						if($arec['admitcard_image'] == '' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'yes','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['admitcard_image'] != ''){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'yes','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}else{

					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline'));

				

					foreach($admit_card_details as $arec){

						if($arec['admitcard_image'] == '' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'no','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['admitcard_image'] != ''){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],"subject_code"=>$arec['exm_prd'],'mail_sent'=>'no','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}

				

				

			}elseif($attach_path==''){

				echo "admitcard not generate : ".$res['mem_mem_no'];

				echo "<br/>";

			}

			

			if($attachpath!=''){

				echo $result[0]['email'];

				echo "<br/>";

			}

		}

	}

	

	public function caiib_close_venue(){

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		//ini_set('max_execution_time', 300); //300 seconds = 5 minutes

		ini_set("memory_limit", "-1");

		ini_set('max_execution_time', 0);

		$start_time = date("Y-m-d H:i:s");

		

			//$member_array = array("100010289","100054194","200059157","300014603","3894274");

			//$member_array = array("300014603");

			// selected center

			$center_code = $this->uri->segment(3);

			//echo "###".$center_code;

			//exit;

			//$center_code = 581; 

			if($center_code == ''){

				echo "center code not present".die;

			}

			

			$exm_array = array($this->config->item('examCodeCaiib'),62,63,64,65,66,67,68,69,70,71,72);

			

			$this->db->distinct('mem_mem_no');

			//$this->db->select('mem_mem_no');

			$this->db->where('center_code', $center_code); 

			$this->db->where('record_source', 'Offline');

			$this->db->where('remark !=', 1);

			$this->db->where('venueid', '');

			$this->db->where_in('exm_cd', $exm_array); 

			//$this->db->where('exm_cd', 60); 

			//$this->db->order_by("admitcard_id", "asc");    

			$this->db->limit(1, 0);  

			$member_array = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd',array('admitcard_id'=>'asc')); // add remark condition here

			

			/*echo "<pre>";

			print_r($member_array);

			exit;*/

			

			

			//echo "<br/>";

			// subject dates

			$e_date_array = array("2017-12-03","2017-12-10","2017-12-17");

			

			// get all venues for selected center

			

			$ignore = array('360003A','413531A','110095I');

			

			

			$this->db->distinct();

			$this->db->select('venue_code');

			$this->db->where('center_code',$center_code);

			//$this->db->where('venue_code!=','IIBF306');

			$this->db->where_not_in('venue_code', $ignore);

			$this->db->where_in('exam_date', $e_date_array);// need to add dates in IN condition

			$venue = $this->master_model->getRecords('venue_master');

			

			/*echo "<pre>";

			print_r($venue);*/

			//exit;

			

			// selected venue

			/*foreach($venue as $venue_res){

				$venue_arr[] = $venue_res['venue_code'];

			}*/

			$venue_arr = array('110085E');

			

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($member_array);*/

			//exit;*/

			

			

			

			

			

			foreach($member_array as $mem_no){

			

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$this->db->where('exm_cd',$mem_no['exm_cd']);

			$this->db->where('remark !=',1);

			$this->db->where('record_source','Offline');

			$this->db->where('venueid', '');

			$jaiib_member = $this->master_model->getRecords('admit_card_details'); // add remark condition here

			/*echo $this->db->last_query();

			echo "<br/>";

			echo "<pre>";

			print_r($jaiib_member);

			echo "*************************";*/

			/*exit;*/

			

			$i = 0;

			$sub_arr = array();

			

			// check capacity for each subject

			foreach($jaiib_member as $jaiib_member_res)	// <= 3s

			{

				

				

				$sub_details = array();

				$exam_code = $jaiib_member_res['exm_cd'];

				$sub_code = $jaiib_member_res['sub_cd'];

				//echo "<br/>";

				

				$mem_mem_no = $jaiib_member_res['mem_mem_no'];

				$admitcard_id = $jaiib_member_res['admitcard_id'];

				//$admitcard_id = $jaiib_member_res['id'];

				

				$v_code = '';

				$e_date = '';

				$e_time = '';

				$flag = 0;

				$flag1 = 0;

				

				// get subject date

				$this->db->where('exam_code',$exam_code);

				$this->db->where('exam_period',217);

				$this->db->where('subject_code',$sub_code);

				$this->db->order_by("id", "asc");

				$subject = $this->master_model->getRecords('subject_master','','exam_date');

				$exam_date = $subject[0]['exam_date']; 

				

				/*echo $this->db->last_query();

				echo "<br/>";

				

				echo "<pre>";

				print_r($subject);*/

				//exit;

				

				// check for all venues

				$venue_size = count($venue_arr);

				for($j=0;$j<$venue_size;$j++){	// number of venues				

					

						// get all sessions for selected date

						$this->db->where('center_code',$center_code);

						$this->db->where('venue_code',$venue_arr[$j]);

						$this->db->where('exam_date',$exam_date);

						$time_sql = $this->master_model->getRecords('venue_master','','session_time');

						

						//echo "*************";

						

						/*echo "<pre>";

						print_r($time_sql);

						exit;*/	

						

								

						

						// make sessions array in descending order

						$time_sql_size = sizeof($time_sql);

						for($l=0;$l<$time_sql_size;$l++){

							

							if($time_sql[$l]['session_time'] == "2.00 PM" || $time_sql[$l]['session_time'] =="2:00 PM")

							{

								$temp_time_arr[0] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "11.15 AM" || $time_sql[$l]['session_time'] =="11:15 AM")

							{

								$temp_time_arr[1] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "8.30 AM" || $time_sql[$l]['session_time'] =="8:30 AM")

							{

								$temp_time_arr[2] = $time_sql[$l]['session_time'];	

							}

							

						}

						

						/*echo "********";

						echo "<pre>";

						print_r($temp_time_arr);

						exit; */

						// check for all sessions

						 $time_size = count($temp_time_arr);

						 $temp_time_arr = array_values($temp_time_arr);

						/*echo "<pre>";

						print_r($temp_time_arr);

						exit;*/

						for($l=0;$l<$time_size;$l++){

							

							if(isset($temp_time_arr[$l]) && $temp_time_arr[$l] != ''){

								

							// check for capacity

							$capacity = check_capacity_j($venue_arr[$j],$exam_date,$temp_time_arr[$l],$center_code);

							if($capacity != 0){	// if capacity is not full

								

								$v_code = $venue_arr[$j];

								$e_date = $exam_date;

								$e_time = $temp_time_arr[$l];

								

								$sub_details = array("exam_code" => $exam_code, "center_code" => $center_code, "venue_code" => $v_code, "exam_date" => $e_date, "exam_time" => $e_time, "mem_mem_no" => $mem_mem_no, "admitcard_id" => $admitcard_id,"sub_code"=>$sub_code);							

								

								$sub_arr[$sub_code] = $sub_details;

								

								echo $sub_code."|".implode("|",$sub_details);

								echo "<br/>";

								

								$flag = 1;

								break;

								

							}

							else{	// capacity full

								echo $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|Capacity full";

								echo "<br/>";	

								//$flag = 2;

								

								$log_title ="CAIIB Offline seat allocation Capacity Full ";

								$log_message = $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|".$center_code."|Capacity full";

								$rId = $mem_mem_no;

								$regNo = $mem_mem_no;

								$log_data['title'] = $log_title;

								$log_data['description'] = $log_message;

								$log_data['regid'] = $rId;

								$log_data['regnumber'] = $regNo;

								$this->db->insert('userlogs', $log_data);

								

								

							}

								/*if($flag == 2){

									break;

								}*/

							}

							

						}// end of for loop for time

						if($flag == 1){

							$flag1 = 1;

							break;

						}

				} // end of for loop for venue

				

				$i++;

				

				echo "<br/>========================================<br/>";

				

			}// end of first for date loop

			

			

			/*echo count($jaiib_member)."<br/>";

			echo count($sub_arr)."<br/>";

			exit;*/

			// generate seat no. and update

			if((count($jaiib_member) == count($sub_arr)) && count($sub_arr) > 0)

			{

				

				$this->db->where('record_source','Offline');

				$this->db->where('remark',1);

				$this->db->where('exm_cd',$this->config->item('examCodeCaiib'));

				//$this->db->where_in('exm_cd',$exm_array);

				$this->db->where('mem_mem_no',$sub_details['mem_mem_no']);

				$this->db->where('pwd !=','');

				$this->db->limit(1, 0);  

				$member_chk_pwd = $this->master_model->getRecords('admit_card_details','','pwd');

				

				// generate password

				if(count($member_chk_pwd) > 0){

					$password = $member_chk_pwd[0]['pwd'];

				}else{

					$password = random_password();

				}

				

				

				foreach($sub_arr as $sub_details)

				{			

					$v_code = $sub_details['venue_code'];

					$e_date = $sub_details['exam_date'];

					$e_time = $sub_details['exam_time'];

					//$e_time = $sub_details['session_time'];

					

					$sub_code = $sub_details['sub_code'];

					$exam_code = $sub_details['exam_code'];

					

					$mem_mem_no = $sub_details['mem_mem_no'];

					$admitcard_id = $sub_details['admitcard_id'];

						

					// get venue details

					$get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));

					

					//$admit_card_details=$this->master_model->getRecords('admit_card_details',array('sub_cd'=>$sub_code,'mem_mem_no'=>$mem_mem_no,'exm_cd'=>$exam_code,'admitcard_id'=>$admitcard_id));

					

					// generate seat no. 

					$seat_allocation = getseat_j($exam_code, $center_code, $v_code, $e_date, $e_time, 217, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

					

					$update_data = array(

											'pwd' => $password,

											'seat_identification' => $seat_allocation,

											'remark' => 1,

											'modified_on' => date('Y-m-d H:i:s'),

											'exam_date' => $e_date,

											'time' => $e_time,

											'venpin' => $get_venue_details[0]['venue_pincode'],

											'venueadd1' => $get_venue_details[0]['venue_addr1'],

											'venueadd2' => $get_venue_details[0]['venue_addr2'],

											'venueadd3' => $get_venue_details[0]['venue_addr3'],

											'venueadd4' => $get_venue_details[0]['venue_addr4'],

											'venueadd5' => $get_venue_details[0]['venue_addr5'],

											'venue_name' => $get_venue_details[0]['venue_name'],

											'venueid' => $v_code,

											'created_on' => date('Y-m-d H:i:s'),

											'modified_on' => date('Y-m-d H:i:s'),

											'exm_prd'=>217,

											'scribe_flag'=>'N',

											'mode'=>'Online'

										);

					

					// check if seat get 

					if($seat_allocation != '')

					{

						//$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						echo "seat allocation done";

						echo "<br/><br/>";

						echo implode("|",$sub_details)."|".implode("|",$update_data);

						echo "<br/><br/>";

						

						$log_title ="CAIIB Offline seat allocation done";

						$log_message = implode("|",$sub_details)."|".implode("|",$update_data);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

						

						

					}else{

						echo "seat allocation fail";

						echo "<br/><br/>";

						echo implode("|",$sub_details);

						echo "<br/><br/>";

						

						

						$log_title ="CAIIB Offline seat allocation fail_2";

						$log_message = implode("|",$sub_details);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

					}

					

				}

			}

			

			$end_time = date("Y-m-d H:i:s");  

			

			echo "<br/>".$start_time."|".$end_time."<br/>";

			

			echo "<br/>========================================<br/>";

			

			

		

		}// end of ffirst for

		

	}

	

	public function caiib_previous_different_center(){

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		//ini_set('max_execution_time', 300); //300 seconds = 5 minutes

		ini_set("memory_limit", "-1");

		ini_set('max_execution_time', 0);

		$start_time = date("Y-m-d H:i:s");

		

			//$member_array = array("100010289","100054194","200059157","300014603","3894274");

			//$member_array = array("300014603");

			// selected center

			$center_code = $this->uri->segment(3);

			$member_code = $this->uri->segment(4);

			//echo "###".$center_code;

			//exit;

			//$center_code = 581; 

			if($center_code == ''){

				die;

			}

			

			$this->db->distinct('mem_mem_no');

			//$this->db->select('mem_mem_no');

			$this->db->where('center_code', $center_code); 

			$this->db->where('remark !=', 1);

			$this->db->where('exm_cd', $this->config->item('examCodeCaiib')); 

			//$this->db->order_by("admitcard_id", "asc");    

			$this->db->limit(1, 0);  

			$this->db->where('mem_mem_no', $member_code);

			//$this->db->where('mem_mem_no', '510095991');

			$member_array = $this->master_model->getRecords('admit_card_details',array('record_source'=>'Offline'),'mem_mem_no,exm_cd',array('admitcard_id'=>'asc')); // add remark condition here

			

			//echo "<pre>";

			//print_r($member_array);

			//exit; 

			//echo "<br/>";

			// subject dates

			$e_date_array = array("2017-12-03","2017-12-10","2017-12-17");

			

			// get all venues for selected center

			

			$ignore = array('360003A','413531A','110095I');

			

			

			$this->db->distinct();

			$this->db->select('venue_code');

			$this->db->where('center_code',$center_code);

			//$this->db->where('venue_code!=','IIBF306');

			$this->db->where_not_in('venue_code', $ignore);

			$this->db->where_in('exam_date', $e_date_array);// need to add dates in IN condition

			$venue = $this->master_model->getRecords('venue_master');

			

			foreach($venue as $venue_res){

				$venue_arr[] = $venue_res['venue_code'];

			}

			

			//$venue_arr = array(400058,400072);

			

			

			foreach($member_array as $mem_no){

			

			$this->db->where('mem_mem_no',$mem_no['mem_mem_no']);

			$this->db->where('exm_cd',$mem_no['exm_cd']);

			$this->db->where('remark !=',1);

			$this->db->where('record_source','Offline');

			$jaiib_member = $this->master_model->getRecords('admit_card_details'); // add remark condition here

			

			$i = 0;

			$sub_arr = array();

			

			// check capacity for each subject

			foreach($jaiib_member as $jaiib_member_res)	// <= 3s

			{

				

				

				$sub_details = array();

				$exam_code = $jaiib_member_res['exm_cd'];

				$sub_code = $jaiib_member_res['sub_cd'];

				//echo "<br/>";

				

				$mem_mem_no = $jaiib_member_res['mem_mem_no'];

				$admitcard_id = $jaiib_member_res['admitcard_id'];

				//$admitcard_id = $jaiib_member_res['id'];

				

				$v_code = '';

				$e_date = '';

				$e_time = '';

				$flag = 0;

				$flag1 = 0;

				

				// get subject date

				$this->db->where('exam_code',$exam_code);

				$this->db->where('exam_period',217);

				$this->db->where('subject_code',$sub_code);

				$this->db->order_by("id", "asc");

				$subject = $this->master_model->getRecords('subject_master','','exam_date');

				$exam_date = $subject[0]['exam_date']; 

				

				// check for all venues

				$venue_size = count($venue_arr);

				for($j=0;$j<$venue_size;$j++){	// number of venues				

					

						// get all sessions for selected date

						$this->db->where('center_code',$center_code);

						$this->db->where('venue_code',$venue_arr[$j]);

						$this->db->where('exam_date',$exam_date);

						$time_sql = $this->master_model->getRecords('venue_master','','session_time');

						

						

						// make sessions array in descending order

						$time_sql_size = sizeof($time_sql);

						for($l=0;$l<$time_sql_size;$l++){

							

							if($time_sql[$l]['session_time'] == "8.30 AM" || $time_sql[$l]['session_time'] =="8:30 AM")

							{

								$temp_time_arr[0] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "11.15 AM" || $time_sql[$l]['session_time'] =="11:15 AM")

							{

								$temp_time_arr[1] = $time_sql[$l]['session_time'];

							}

							elseif($time_sql[$l]['session_time'] == "2.00 PM" || $time_sql[$l]['session_time'] =="2:00 PM")

							{

								$temp_time_arr[2] = $time_sql[$l]['session_time'];	

							}

							

						}

						

						/*echo "********";

						echo "<pre>";

						print_r($temp_time_arr);

						exit;*/

						// check for all sessions

						 $time_size = count($temp_time_arr);

						 $temp_time_arr = array_values($temp_time_arr);

						 

						/* echo "<br/>";

						 echo "------------------";

						echo "<pre>";

						print_r($temp_time_arr);*/

						/*exit;*/

						for($l=0;$l<$time_size;$l++){

							

							if(isset($temp_time_arr[$l]) && $temp_time_arr[$l] != ''){

								

							// check for capacity

							$capacity = check_capacity_j($venue_arr[$j],$exam_date,$temp_time_arr[$l],$center_code);

							if($capacity != 0){	// if capacity is not full

								

								$v_code = $venue_arr[$j];

								$e_date = $exam_date;

								$e_time = $temp_time_arr[$l];

								

								$sub_details = array("exam_code" => $exam_code, "center_code" => $center_code, "venue_code" => $v_code, "exam_date" => $e_date, "exam_time" => $e_time, "mem_mem_no" => $mem_mem_no, "admitcard_id" => $admitcard_id,"sub_code"=>$sub_code);							

								

								$sub_arr[$sub_code] = $sub_details;

								

								echo $sub_code."|".implode("|",$sub_details);

								echo "<br/>";

								

								$flag = 1;

								break;

								

							}

							else{	// capacity full

								echo $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|Capacity full";

								echo "<br/>";	

								//$flag = 2;

								

								$log_title ="CAIIB Offline seat allocation Capacity Full ";

								$log_message = $sub_code."|".$mem_mem_no."|".$venue_arr[$j]."|".$exam_date."|".$temp_time_arr[$l]."|".$center_code."|Capacity full";

								$rId = $mem_mem_no;

								$regNo = $mem_mem_no;

								$log_data['title'] = $log_title;

								$log_data['description'] = $log_message;

								$log_data['regid'] = $rId;

								$log_data['regnumber'] = $regNo;

								$this->db->insert('userlogs', $log_data);

								

								

							}

								/*if($flag == 2){

									break;

								}*/

							}

							

						}// end of for loop for time

						if($flag == 1){

							$flag1 = 1;

							break;

						}

				} // end of for loop for venue

				

				$i++;

				

				echo "<br/>========================================<br/>";

				

			}// end of first for date loop

			

			//exit;

			/*echo count($jaiib_member)."<br/>";

			echo count($sub_arr)."<br/>";

			exit;*/

			// generate seat no. and update

			if((count($jaiib_member) == count($sub_arr)) && count($sub_arr) > 0)

			{

				

				$this->db->where('record_source','Offline');

				$this->db->where('remark',1);

				$this->db->where('exm_cd',$this->config->item('examCodeCaiib'));

				$this->db->where('mem_mem_no',$sub_details['mem_mem_no']);

				$this->db->where('pwd !=','');

				$this->db->limit(1, 0);  

				$member_chk_pwd = $this->master_model->getRecords('admit_card_details','','pwd');

				

				// generate password

				if(count($member_chk_pwd) > 0){

					$password = $member_chk_pwd[0]['pwd'];

				}else{

					$password = random_password();

				}

				

				

				foreach($sub_arr as $sub_details)

				{			

					$v_code = $sub_details['venue_code'];

					$e_date = $sub_details['exam_date'];

					$e_time = $sub_details['exam_time'];

					//$e_time = $sub_details['session_time'];

					

					$sub_code = $sub_details['sub_code'];

					$exam_code = $sub_details['exam_code'];

					

					$mem_mem_no = $sub_details['mem_mem_no'];

					$admitcard_id = $sub_details['admitcard_id'];

						

					// get venue details

					$get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));

					

					//$admit_card_details=$this->master_model->getRecords('admit_card_details',array('sub_cd'=>$sub_code,'mem_mem_no'=>$mem_mem_no,'exm_cd'=>$exam_code,'admitcard_id'=>$admitcard_id));

					

					// generate seat no. 

					$seat_allocation = getseat_j($exam_code, $center_code, $v_code, $e_date, $e_time, 217, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

					

					$update_data = array(

											'pwd' => $password,

											'seat_identification' => $seat_allocation,

											'remark' => 1,

											'modified_on' => date('Y-m-d H:i:s'),

											'exam_date' => $e_date,

											'time' => $e_time,

											'venpin' => $get_venue_details[0]['venue_pincode'],

											'venueadd1' => $get_venue_details[0]['venue_addr1'],

											'venueadd2' => $get_venue_details[0]['venue_addr2'],

											'venueadd3' => $get_venue_details[0]['venue_addr3'],

											'venueadd4' => $get_venue_details[0]['venue_addr4'],

											'venueadd5' => $get_venue_details[0]['venue_addr5'],

											'venue_name' => $get_venue_details[0]['venue_name'],

											'venueid' => $v_code,

											'created_on' => date('Y-m-d H:i:s'),

											'modified_on' => date('Y-m-d H:i:s'),

											'exm_prd'=>217,

											'scribe_flag'=>'N',

											'mode'=>'Online'

										);

					

					// check if seat get 

					if($seat_allocation != '')

					{

						//$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admitcard_id));	

						

						echo "seat allocation done";

						echo "<br/><br/>";

						echo implode("|",$sub_details)."|".implode("|",$update_data);

						echo "<br/><br/>";

						

						$log_title ="CAIIB Offline seat allocation done";

						$log_message = implode("|",$sub_details)."|".implode("|",$update_data);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

						

						

					}else{

						echo "seat allocation fail";

						echo "<br/><br/>";

						echo implode("|",$sub_details);

						echo "<br/><br/>";

						

						

						$log_title ="CAIIB Offline seat allocation fail_2";

						$log_message = implode("|",$sub_details);

						$rId = $mem_mem_no;

						$regNo = $mem_mem_no;

						$log_data['title'] = $log_title;

						$log_data['description'] = $log_message;

						$log_data['regid'] = $rId;

						$log_data['regnumber'] = $regNo;

						$this->db->insert('userlogs', $log_data);

					}

					

				}

			}

			

			$end_time = date("Y-m-d H:i:s");  

			

			echo "<br/>".$start_time."|".$end_time."<br/>";

			

			echo "<br/>========================================<br/>";

			

			

		

		}// end of ffirst for

		

	}

	

	// send caiin offline mail

	public function caiib_offline_mail(){

		

		

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		$start_time = date("Y-m-d H:i:s");

		$center_code = $this->uri->segment(3);

		if($center_code == ''){

			die;

		}

		

		$this->db->distinct();

		$this->db->select('mem_mem_no,exm_cd,m_1,center_code,mam_nam_1');

		$this->db->where('mailsend','no');

		$this->db->where('center_code', $center_code);

		//$this->db->limit(1,0);

		$record = $this->master_model->getRecords('admitcard_caiib_217_mailsend');

		

		 //echo "<pre>";

		 //print_r($record);

		// exit;

		 

		foreach($record as $res){

			$member_id = $res['mem_mem_no'];

			$exam_code = $res['exm_cd'];

			$exam_period = 217;

			$attachpath = caiib_custom_genarate_admitcard_offline($member_id,$exam_code,$exam_period);

			

			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$res['mem_mem_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode');

			

			//Query to get Medium	

			$this->db->where('exam_code',$exam_code);

			$this->db->where('exam_period',217);

			$this->db->where('medium_code',$res['m_1']);

			$this->db->where('medium_delete','0');

			$medium=$this->master_model->getRecords('medium_master','','medium_description');

			

			$this->db->where('exam_code',$exam_code);

			$exam_info = $this->master_model->getRecords('exam_master','','description');

			

			$this->db->where('center_code',$res['center_code']);

			$center = $this->master_model->getRecords('center_master','','center_name');

			

			

			

			if($attachpath!=''){ 

				

				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'offline'));

				$newstring1= str_replace("#USERNAME#", "".$res['mam_nam_1']."",$emailerstr[0]['emailer_text']);

				$newstring2 = str_replace("#REG_NUM#", "".$res['mem_mem_no']."",$newstring1);

				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

				$newstring4 = str_replace("#EXAM_DATE#", "Dec-2017",$newstring3);

				$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);

				$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);

				$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);

				$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);

				$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);

				$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);

				$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);

				$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);

				$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);

				$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);

				$newstring17 = str_replace("#CENTER#", "".$center[0]['center_name']."",$newstring16);

				$newstring18 = str_replace("#CENTER_CODE#", "".$res['center_code']."",$newstring17);

				$newstring19 = str_replace("#MODE#", "Online",$newstring18);

				$final_str = $newstring19;

				

				

				$info_arr=array('to'=>$result[0]['email'],

								//'to'=>'ztest2500@gmail.com',

								'from'=>$emailerstr[0]['from'],

								'subject'=>$emailerstr[0]['subject'],

								'message'=>$final_str

								);

				

				$files=array($attachpath);

				

				if($this->Emailsending->mailsend_attch($info_arr,$files)){

					

					$admit_card_details = $this->master_model->getRecords('admitcard_caiib_217_mailsend',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));

				

					foreach($admit_card_details as $arec){

						if($arec['mailsend'] == 'no' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],'mail_sent'=>'yes','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['mailsend'] != 'no'){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],'mail_sent'=>'yes','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}else{

					$admit_card_details = $this->master_model->getRecords('admitcard_caiib_217_mailsend',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));

				

					foreach($admit_card_details as $arec){

						if($arec['mailsend'] == 'no' ){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],'mail_sent'=>'no','image_update'=>'no');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}elseif($arec['mailsend'] != 'no'){

							$iarray = array("admitcard_id"=>$arec['admitcard_id'],"member_number"=>$arec['mem_mem_no'],"exam_code"=>$arec['exm_cd'],'mail_sent'=>'no','image_update'=>'yes');

							

							$inser_id=$this->master_model->insertRecord('offline_mail_log',$iarray);

						}

					}

				}

				

				

			}elseif($attach_path==''){

				echo "admitcard not generate : ".$res['mem_mem_no'];

				echo "<br/>";

			}

			

			if($attachpath!=''){

				echo $result[0]['email'];

				echo "<br/>";

			}

		}

	}

	

	// update caiib offline record admitcard image coloum

	public function update_caiib_offline_record(){

		

		

		error_reporting(E_ALL);

		ini_set('display_errors', '1');

		$start_time = date("Y-m-d H:i:s");

		$center_code = $this->uri->segment(3);

		if($center_code == ''){

			die;

		}

		

		$this->db->distinct();

		$this->db->select('mem_mem_no,exm_cd,m_1,center_code,mam_nam_1');

		$this->db->where('record_source','Offline');

		$this->db->where('remark',1);

		$this->db->where('exm_cd',$this->config->item('examCodeCaiib'));

		$this->db->where('admitcard_image', '');

		$this->db->where('center_code', $center_code);

		$this->db->limit(1,0);

		$record = $this->master_model->getRecords('admit_card_details');

		

		echo "<pre>";

		print_r($record);

		exit;

		 

		foreach($record as $res){

			$member_id = $res['mem_mem_no'];

			$exam_code = $res['exm_cd'];

			$exam_period = 217;

			

			$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";

			

			$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline','admitcard_image'=>''));

			

			$update_data = array('admitcard_image' => $pdfFilePath);

			

			foreach($admit_card_details as $admit_card_update){

				$this->db->where('remark', 1);

				$this->db->where('admitcard_image', '');

				$this->db->where('record_source','Offline');

				$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));

			}

			

		}

	}

	

	public function capacity_incrase(){ 

		

		//$this->db->limit(1);

		$venue_details = $this->master_model->getRecords('venue_master_pawan',array('is_done'=>'no')); 

		/*echo $this->db->last_query();

		echo '<br/>';

		echo '<pre>';

		print_r($venue_details);

		echo '<br/>';*/

		

		foreach($venue_details as $venue_details_rec){

			

			$old_venue_detail = $this->master_model->getRecords('venue_master',array('center_code'=>$venue_details_rec['center_code'],'venue_code'=>$venue_details_rec['venue_code'],'exam_date'=>$venue_details_rec['exam_date'],'session_time'=>$venue_details_rec['session_time']));

			

			//echo $this->db->last_query();

			//echo '<br/>';

		

			

			foreach($old_venue_detail as $old_venue_detail_rec){

				

				//echo '>>'.$venue_details_rec['session_capacity'];

				//echo '<br/>';

				

				$new_capacity = $venue_details_rec['session_capacity'];

				$update_data = array('session_capacity'=>$new_capacity);

				

				//echo $new_capacity;

				//echo '<br/>';

				//exit;

				

				$this->master_model->updateRecord('venue_master',$update_data,array('center_code'=>$venue_details_rec['center_code'],'venue_code'=>$venue_details_rec['venue_code'],'exam_date'=>$venue_details_rec['exam_date'],'session_time'=>$venue_details_rec['session_time']));	

				

				

			}

			

			$update_data_new = array('is_done'=>'yes');

				

			$this->master_model->updateRecord('venue_master_pawan',$update_data_new,array('center_code'=>$venue_details_rec['center_code'],'venue_code'=>$venue_details_rec['venue_code'],'exam_date'=>$venue_details_rec['exam_date']));	

			

		}

		

		

	}

	

	public function dynamic_invoice(){

		$this->load->helper('bulk_invoice_helper');

		bulk_exam_invoice(900000904);

	}

	

	public function read_csv(){

		$data = array();

		if(isset($_POST['submit'])){

			$this->form_validation->set_rules('csv_file','File for uploading','file_required|file_allowed_type[csv]|file_size_max[2000]');

			

			

			if($this->form_validation->run()==TRUE){

				

				$filename=$_FILES["csv_file"]["tmp_name"];

				$csv = array_map('str_getcsv', file($filename));

				

				if(count($csv[0]) == 3){

					$i = 0;

					$j = 1;

					$k = 2;

					for($s = 1; $s < sizeof($csv); $s++){

						

						if($csv[$s][$i] == '' || $csv[$s][$j] == '' || $csv[$s][$k] == ''){

							$data['errmsg'] =  "Wrong data in file";

						}else{

							$str =  $csv[$s][$i]."/".$csv[$s][$j]."/".$csv[$s][$k];

							$regnumber = $csv[$s][$i] ;

							$chk_one = bulk_check_is_member($regnumber);

							if($chk_one['flag'] == 1){

								$data['errmsg'] =  $chk_one['msg'];

								break;

							}else{

								$regnumber = $csv[$s][$i] ;

								$chk_two = bulk_is_profile_complete($regnumber);

								if($chk_two['flag'] == 1){

									$data['errmsg'] =  $chk_two['msg'];

									break;

								}else{

									$chk_three = bulk_check_exam_application($str);

									if($chk_three['flag'] == 1){

										$data['errmsg'] =  $chk_three['msg'];

										break;

									}else{

										echo "function three";

										echo "<br/>";

									}

								}

							}

						}

						

					}// end of for

				}else{

					$data['errmsg'] =  "Colom data is not proper in file";

				}

			}else{

				$data['validation_errors'] = validation_errors();

			}

			

			

		}

		$this->load->view('upload_csv',$data);

		

		/*$file_path = "http://iibf.teamgrowth.net/uploads/mycsv.csv";

		$csv = array_map('str_getcsv', file($file_path));

		//$csv = array_map('str_getcsv', file('data.csv'));	

		echo $csv[0][0]."//".$csv[0][1]."//".$csv[0][2];

		echo "<br>";

		echo $csv[1][0]."//".$csv[1][1]."//".$csv[1][2];

		echo "<br>";

		echo $csv[2][0]."//".$csv[2][1]."//".$csv[2][2];

		echo "<pre>";

		print_r($csv);

		exit;*/

	}

	

	public function read_xlsx(){

		$this->load->library('Excel');

 		$data = $allDataInSheet=array();

		if(isset($_POST['submit'])){ 

			$this->form_validation->set_rules('csv_file','File for uploading','file_required|file_allowed_type[xls]|file_size_max[2000]');

			

			

			if($this->form_validation->run()==TRUE){ 

				$filename=$_FILES["csv_file"]["tmp_name"];

				 try{

					$xlsx = PHPExcel_IOFactory::load($filename);

					$allDataInSheet = $xlsx->getActiveSheet()->toArray(null);

				 }

				 catch(Exception $e){

					 $this->resp->success = FALSE;

					 $this->resp->msg = 'Error Uploading file';

					 echo json_encode($this->resp);

					 exit;

				}

				

				

				

				if(count($allDataInSheet[0]) == 5){ 

					$i = 0; // regnumner

					$j = 1; // exam code

					$k = 2; // exam period

					$m = 3; // center code

					$n = 4; // subject code

					$c = 0;

					$total_record = sizeof($allDataInSheet) - 1;

					if(sizeof($allDataInSheet) > 1){

					for($s = 1; $s < sizeof($allDataInSheet); $s++){

						

						if($allDataInSheet[$s][$i] == '' || $allDataInSheet[$s][$j] == '' || $allDataInSheet[$s][$k] == ''|| $allDataInSheet[$s][$m] == ''|| $allDataInSheet[$s][$n] == ''){

							$data['errmsg'] =  "Wrong data in file";

						}else{

							$str =  $allDataInSheet[$s][$i]."/".$allDataInSheet[$s][$j]."/".$allDataInSheet[$s][$k];

							$regnumber = $allDataInSheet[$s][$i];

							$exam_code = $allDataInSheet[$s][$j];

							$exam_period = $allDataInSheet[$s][$k];

							$center_code = $allDataInSheet[$s][$m];

							$subject_code = $allDataInSheet[$s][$n];

							

							$chk_one = bulk_check_is_member($regnumber);

							if($chk_one['flag'] == 0){ 

								$data['errmsg'] =  $chk_one['msg'];

								break;

							}else{

								//$regnumber = $allDataInSheet[$s][$i] ;

								$chk_two = bulk_is_profile_complete($regnumber);

								if($chk_two['flag'] == 0){

									$data['errmsg'] =  $chk_two['msg'];

									break;

								}else{

									$chk_three = bulk_check_exam_activate($exam_code);

									if($chk_three['flag'] == 0){ 

										$data['errmsg'] =  $chk_three['msg'];

										break;

									}else{ 

										$chk_four = bulk_checkusers($regnumber,$exam_code,$exam_period);

										if($chk_four['flag'] == 0){

											$data['errmsg'] =  $chk_four['msg'];

											break;

										}else{

											if($exam_code == 21 || $exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72){

												$chk_five = bulk_checkqualify($regnumber,$exam_code,$exam_period,$member_type = 'O');

												$chk_five_flag = $chk_five['flag'];

											}else{

												$chk_five_flag = 0;

											}

											

											if($chk_five_flag == 1){

												$data['errmsg'] =  $chk_five['msg'];

												break;

											}else{

												$chk_six = bulk_check_exam_application($regnumber,$exam_code,$exam_period);

												

												if($chk_six['flag'] == 0){ 

													$data['errmsg'] =  $chk_six['msg'];

													break;

												}else{

													$chk_seven = bulk_examdate($regnumber,$exam_code);

													if($chk_seven['flag'] == 1){

														$data['errmsg'] =  $chk_seven['msg'];

														break;

													}else{

														$chk_eight = bulk_excel_chk_capacity($regnumber,$exam_code,$exam_period,$center_code,$subject_code);

														if($chk_eight['flag'] == 0){

															$data['errmsg'] =  $chk_eight['msg'];

															break;

														}else{

															$c++;

															/*echo "####". $total_record;

															echo "<br/>";

															echo ">>>".$c;

															echo "<br/>";*/

															if($c == $total_record){

																echo "here";

															}

															

														}

													}

												}

											}

										}

									}

								}

							}

						}

						

					}// end of for

					}

					else

					{

						$data['errmsg'] =  "Uploaded file is blank";	

					}

				}else{

					$data['errmsg'] =  "Colom data is not proper in file";

				}

			}else{

				$data['validation_errors'] = validation_errors();

			}

			

			

		}

		$this->load->view('upload_csv',$data);

	}

	

	public function read_xlsx_t(){

		$success = '';

		$this->load->library('Excel');

 		$data = $allDataInSheet=array();

		if(isset($_POST['submit'])){ 

			$this->form_validation->set_rules('csv_file','File for uploading','file_required|file_allowed_type[xls]|file_size_max[2000]');

			

			

			if($this->form_validation->run()==TRUE){ 

				$filename=$_FILES["csv_file"]["tmp_name"];

				 try{

					$xlsx = PHPExcel_IOFactory::load($filename);

					$allDataInSheet = $xlsx->getActiveSheet()->toArray(null);

				}

				 catch(Exception $e){

					 $this->resp->success = FALSE;

					 $this->resp->msg = 'Error Uploading file';

					 echo json_encode($this->resp);

					 exit;

				}

				

				

				if(count($allDataInSheet[0]) == 5){ 

					$i = 0; // regnumner

					$j = 1; // exam name

					$m = 2; // center name

					$p = 3; // medium

					$q = 4; // exam_mode

					$c = 0;

					$total_record = sizeof($allDataInSheet) - 1;

					

					$cc = 0;

					$dup_arr = array();					

					for($z = 1; $z < sizeof($allDataInSheet); $z++){

						if($allDataInSheet[$z][$i] == ''){

							

						}else{

							$cc++;

							$dup_arr[] = $allDataInSheet[$z][$i];

						}

					}

					$a = array_unique($dup_arr);

					$cnt1 = count($dup_arr);

					$cnt2 = count($a);

					

					if($cnt1 != $cnt2){

						$data['errmsg'] =  "Duplicate record in file";

					}elseif($cnt1 == $cnt2){

					

					if(sizeof($allDataInSheet) > 1){

					//for($s = 1; $s < sizeof($allDataInSheet); $s++){

					for($s = 1; $s < $cc+1; $s++){

						if($allDataInSheet[$s][$i] == '' || $allDataInSheet[$s][$j] == '' || $allDataInSheet[$s][$m] == ''|| $allDataInSheet[$s][$p] == ''|| $allDataInSheet[$s][$q] == ''){

							$data['errmsg'] =  "Wrong data in file123";

						}else{

							$regnumber = $allDataInSheet[$s][$i];

							

							$this->db->select('exam_master.exam_code,exam_period');

							$this->db->from('exam_master');

							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');

							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

							$this->db->like("description",$allDataInSheet[$s][$j]);

							$this->db->where("exam_to_date >",date('Y-m-d'));

							$record = $this->db->get();

							$ex_info = $record->row();

							

							//$exam_code = $ex_info->exam_code;

							$exam_code = 11;

							//$exam_period = $ex_info->exam_period;

							$exam_period = 417;

							

							$this->db->select('center_code');

							$this->db->from('center_master');

							$this->db->where("center_name ",$allDataInSheet[$s][$m]);

							$record_1 = $this->db->get();

							$center_info = $record_1->row();

							

							$center_code = $center_info->center_code;

							//$subject_code = $allDataInSheet[$s][$n];

							$institute_code = $this->session->userdata('institute_id');

							$medium = $allDataInSheet[$s][$p];

							$exam_mode = $allDataInSheet[$s][$q];

							

							$chk_one = bulk_check_is_member($regnumber);

							if($chk_one['flag'] == 0){ 

								$data['errmsg'] =  $chk_one['msg'];

								break;

							}else{

								$chk_two = bulk_is_profile_complete($regnumber);

								if($chk_two['flag'] == 0){

									$data['errmsg'] =  $chk_two['msg'];

									break;

								}else{

									$chk_three = bulk_check_exam_activate($exam_code);

									if($chk_three['flag'] == 0){ 

										$data['errmsg'] =  $chk_three['msg'];

										break;

									}else{ 

										$chk_four = bulk_checkusers($regnumber,$exam_code,$exam_period);

										if($chk_four['flag'] == 0){

											$data['errmsg'] =  $chk_four['msg'];

											break;

										}else{

											if($exam_code == 21 || $exam_code == 6$this->config->item('examCodeCaiib')0 || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72){

												$chk_five = bulk_checkqualify($regnumber,$exam_code,$exam_period,$member_type = 'O');

												$chk_five_flag = $chk_five['flag'];

											}else{

												$chk_five_flag = 0;

											}

											

											if($chk_five_flag == 1){

												$data['errmsg'] =  $chk_five['msg'];

												break;

											}else{

												$chk_six = bulk_check_exam_application($regnumber,$exam_code,$exam_period);

												if($chk_six['flag'] == 0){ 

													$data['errmsg'] =  $chk_six['msg'];

													break;

												}else{

													$chk_seven = bulk_examdate($regnumber,$exam_code);

													if($chk_seven['flag'] == 1){

														$data['errmsg'] =  $chk_seven['msg'];

														break;

													}else{

														$this->db->select('subject_code');

														$this->db->where('exam_code',$exam_code);

														$this->db->where('exam_period',$exam_period);

														$subject_code_arr = $this->master_model->getRecords('subject_master');

														$chk = 1;

														foreach($subject_code_arr as $subject_code)

														{

															$subject_code_c = $subject_code['subject_code'];

															$chk_eight = bulk_excel_chk_capacity($regnumber,$exam_code,$exam_period,$center_code,$subject_code_c);

															if($chk_eight['flag'] == 0)

															{

															 $chk = 0;

															}

														}

														if($chk == 0){

															$data['errmsg'] = 'capacity not available';

															break;

														}else{

															echo "here".$regnumber.'<br/>';

															$c++;

															$success = 'success';

															/*echo "####". $total_record;

															echo "<br/>";

															echo ">>>".$c;

															echo "<br/>";*/

														}

													}

												}

											}

										}

									}

								}

							}

						}

						

					}// end of for

					//insert record in member_exam and admit_card_details

					}

					else

					{

						$data['errmsg'] =  "Uploaded file is blank";	

					}

					}else{

						$data['errmsg'] =  "Coloumn data is not proper in file";

					}

				} // end of cnt

			}else{

				$data['validation_errors'] = validation_errors();

			}

			

			

		}

	    //$data['middle_content'] = 'bulk/bulk_add_member_excel';

		//$data=array('middle_content'=>'bulk/bulk_add_member_excel');

		$this->load->view('upload_csv',$data);

	}

	

	public function read_xlsx_tt(){

		$success = '';

		$this->load->library('Excel');

 		$data = $allDataInSheet=array();

		if(isset($_POST['submit'])){ 

			$this->form_validation->set_rules('csv_file','File for uploading','file_required|file_allowed_type[xls]|file_size_max[2000]');

			

			

			if($this->form_validation->run()==TRUE){ 

				$filename=$_FILES["csv_file"]["tmp_name"];

				 try{

					$xlsx = PHPExcel_IOFactory::load($filename);

					$allDataInSheet = $xlsx->getActiveSheet()->toArray(null);

				}

				 catch(Exception $e){

					 $this->resp->success = FALSE;

					 $this->resp->msg = 'Error Uploading file';

					 echo json_encode($this->resp);

					 exit;

				}

				

				

				if(count($allDataInSheet[0]) == 5){ 

					$i = 0; // regnumner

					$j = 1; // exam name

					$m = 2; // center name

					$p = 3; // medium

					$q = 4; // exam_mode

					$r = 5; // exam_mode

					$c = 0;

					$total_record = sizeof($allDataInSheet) - 1;

					

					$cc = 0;

					$dup_arr = array();					

					for($z = 1; $z < sizeof($allDataInSheet); $z++){

						if($allDataInSheet[$z][$i] == ''){

							

						}else{

							$cc++;

							$dup_arr[] = $allDataInSheet[$z][$i];

						}

					}

					

					

					if(sizeof($allDataInSheet) > 1){

					/*for($s = 1; $s < sizeof($allDataInSheet); $s++){*/

					for($s = 1; $s < $cc+1; $s++){

						

						if($allDataInSheet[$s][$i] == '' || $allDataInSheet[$s][$j] == '' || $allDataInSheet[$s][$m] == ''|| $allDataInSheet[$s][$p] == ''|| $allDataInSheet[$s][$q] == ''){

							$data['errmsg'] =  "Wrong data in file";

						}else{

						//print_r($allDataInSheet);exit;

							

							$regnumber = 510010181; //$allDataInSheet[$s][$i];

							

							$this->db->select('exam_master.exam_code,exam_period');

							$this->db->from('exam_master');

							//$this->db->join('exam_activation_master', 'exam_master.exam_code = exam_activation_master.exam_code');

							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');

							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

							$this->db->like("description",$allDataInSheet[$s][$j]);

							$this->db->where("exam_to_date >",date('Y-m-d'));

							$record = $this->db->get();

							$ex_info = $record->row();

							

							//$exam_code = $ex_info->exam_code;

							$exam_code = 11;

							//$exam_period = $ex_info->exam_period;

							$exam_period = 417;

							

							$this->db->select('center_code');

							$this->db->from('center_master');

							$this->db->where("center_name ",$allDataInSheet[$s][$m]);

							$record_1 = $this->db->get();

							$center_info = $record_1->row();

							

							$center_code = $center_info->center_code;

							//$subject_code = $allDataInSheet[$s][$n];

							$institute_code = $this->session->userdata('institute_id');

							$medium = $allDataInSheet[$s][$p];

							$exam_mode = $allDataInSheet[$s][$q];

							

							//echo '</br>',$regnumber,'</br>',$exam_code,'</br>',$exam_period,'</br>',$center_code,'</br>',$institute_code,'</br>',$medium,'</br>',$exam_mode;

								//print_r($allDataInSheet);exit;

							

							$chk_one = bulk_check_is_member($regnumber);

							if($chk_one['flag'] == 0){

								$data['errmsg'] =  $chk_one['msg'];

								break;

							}else{

								$chk_two = bulk_is_profile_complete($regnumber);

								if($chk_two['flag'] == 0){

									$data['errmsg'] =  $chk_two['msg'];

									break;

								}else{

									$chk_three = bulk_check_exam_activate($exam_code);

									if($chk_three['flag'] == 0){ 

										$data['errmsg'] =  $chk_three['msg'];

										break;

									}else{ 

										$chk_four = bulk_checkusers($regnumber,$exam_code,$exam_period);

										if($chk_four['flag'] == 0){

											$data['errmsg'] =  $chk_four['msg'];

											break;

										}else{

											if($exam_code == 21 || $exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72){

												$chk_five = bulk_checkqualify($regnumber,$exam_code,$exam_period,$member_type = 'O');

												$chk_five_flag = $chk_five['flag'];

											}else{

												$chk_five_flag = 0;

											}

											

											if($chk_five_flag == 1){

												$data['errmsg'] =  $chk_five['msg'];

												break;

											}else{

												$chk_six = bulk_check_exam_application($regnumber,$exam_code,$exam_period);

												

												if($chk_six['flag'] == 0){ 

													$data['errmsg'] =  $chk_six['msg'];

													break;

												}else{

													$chk_seven = bulk_examdate($regnumber,$exam_code);

														

													if($chk_seven['flag'] == 1){

														$data['errmsg'] =  $chk_seven['msg'];

														break;

													}else{

														

														$subject_code_arr = get_member_subjects($regnumber,$exam_code,$institute_code);

														$sc = 0;

														foreach($subject_code_arr as $subject_code)

														{

															

															$subject_code_c = $subject_code['subject_code'];

															$chk_eight = bulk_excel_chk_capacity_test($regnumber,$exam_code,$exam_period,$center_code,$subject_code_c);

															$chk = '';

															//echo ">>". $chk_eight['flag']."<br/>";

															

															if($chk_eight['flag'] == 0)

															{  

																$chk = 0;

																break;

															}else{

																$chk = 1;

																$sc++;

															}

															if($chk == 0){

																break;

															}

														}

														//echo "##".$chk."<br/>";

														//echo $sc;

														if($chk == 0){

															$data['errmsg'] = 'capacity not available123456';

															break;

														}else{

															echo "here123";

															$c++;

															$success = 'success';

															/*echo "####". $total_record;

															echo "<br/>";

															echo ">>>".$c;

															echo "<br/>";*/

														}

													}

												}

											}

										}

									}

								}

							}

						}

						

					}// end of for

					

					//insert record in member_exam and admit_card_details

					

					if($c == $total_record && $success == 'success'){

						echo "here";

						exit;	

					}

					

					}

					else

					{

						$data['errmsg'] =  "Uploaded file is blank";	

					}

				}else{

					$data['errmsg'] =  "Coloumn data is not proper in file";

				}

			}else{

				$data['validation_errors'] = validation_errors();

			}

			

			

		}

	    //$data['middle_content'] = 'bulk/bulk_add_member_excel';

		//$data=array('middle_content'=>'bulk/bulk_add_member_excel');

		$this->load->view('upload_csv',$data);

	}

	

	public function read_xlsx_ttt(){

		$success = '';

		$this->load->library('Excel');

 		$data = $allDataInSheet=array();

		if(isset($_POST['submit'])){ 

			$this->form_validation->set_rules('csv_file','File for uploading','file_required|file_allowed_type[xlsx]|file_size_max[2000]');

			

			

			if($this->form_validation->run()==TRUE){ 

				$filename=$_FILES["csv_file"]["tmp_name"];

				 try{

					$xlsx = PHPExcel_IOFactory::load($filename);

					$allDataInSheet = $xlsx->getActiveSheet()->toArray(null);

				}

				 catch(Exception $e){

					 $this->resp->success = FALSE;

					 $this->resp->msg = 'Error Uploading file';

					 echo json_encode($this->resp);

					 exit;

				}

				

				

				if(count($allDataInSheet[0]) == 5){ 

					$i = 0; // regnumner

					$j = 1; // exam name

					$m = 2; // center name

					$p = 3; // medium

					$q = 4; // scribe

					//$r = 5; // exam_mode

					$c = 0;

					$total_record = sizeof($allDataInSheet) - 1;

					$cc = 0;

					$dup_arr = array();

					for($z = 1; $z < sizeof($allDataInSheet); $z++){

						if($allDataInSheet[$z][$i] == ''){

							

						}else{

							$cc++;

							$dup_arr[] = $allDataInSheet[$z][$i];

						}

					}

					

					$a = array_unique($dup_arr);

					$cnt1 = count($dup_arr);

					$cnt2 = count($a);

					

					if($cnt1 != $cnt2){

						$data['errmsg'] =  "Duplicate record in file";

					}elseif($cnt1 == $cnt2){

						

					

					

					if(sizeof($allDataInSheet) > 1){

						

					//for($s = 1; $s < sizeof($allDataInSheet); $s++){

					for($s = 1; $s < $cc+1; $s++){

					

						if($allDataInSheet[$s][$i] == '' || $allDataInSheet[$s][$j] == 'Select Exam' || $allDataInSheet[$s][$m] == 'Select Center'|| $allDataInSheet[$s][$p] == 'Select Medium'|| $allDataInSheet[$s][$q] == 'Select Scribe'){

							$rn = $s+1;

							$data['errmsg'] =  "Row number ".$rn." have  Wrong data in file";

						}else{

						//print_r($allDataInSheet);exit;

							

							

							$excd = explode("=",$allDataInSheet[$s][$j]);

							

							$this->db->select('exam_master.exam_code,exam_period');

							$this->db->from('exam_master');

							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');

							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

							$this->db->like("description",$excd[0]);

							$this->db->where("exam_to_date >",date('Y-m-d'));

							$record = $this->db->get();

							$ex_info = $record->row();

							

							

							

							$cncd = explode("=",$allDataInSheet[$s][$m]);

							$mdcd = explode("=",$allDataInSheet[$s][$p]);

							

							// Required Inputs

							$regnumber = $allDataInSheet[$s][$i];

							$exam_code = $excd[1];

							$exam_period = $ex_info->exam_period;

							$center_code = $cncd[1];

							$exam_mode = 'Online';

							$institute_code = $this->session->userdata('institute_id');

							$medium = $mdcd[1];

							

							

							$chk_one = bulk_check_is_member($regnumber);

							if($chk_one['flag'] == 0){

								$data['errmsg'] =  $chk_one['msg'];

								break;

							}else{

							

								//$regnumber = $allDataInSheet[$s][$i] ;

								$chk_two = bulk_is_profile_complete($regnumber);

								if($chk_two['flag'] == 0){

									$data['errmsg'] =  $chk_two['msg'];

									break;

								}else{

									$chk_three = bulk_check_exam_activate($exam_code);

									if($chk_three['flag'] == 0){ 

										$data['errmsg'] =  $chk_three['msg'];

										break;

									}else{ 

										$chk_four = bulk_checkusers($regnumber,$exam_code,$exam_period);

										if($chk_four['flag'] == 0){

											$data['errmsg'] =  $chk_four['msg'];

											break;

										}else{

											if($exam_code == 21 || $exam_code == 6$this->config->item('examCodeCaiib')0 || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72){

												$chk_five = bulk_checkqualify($regnumber,$exam_code,$exam_period,$member_type = 'O');

												$chk_five_flag = $chk_five['flag'];

											}else{

												$chk_five_flag = 0;

											}

											

											if($chk_five_flag == 1){

												$data['errmsg'] =  $chk_five['msg'];

												break;

											}else{

												$chk_six = bulk_check_exam_application($regnumber,$exam_code,$exam_period);

												

												if($chk_six['flag'] == 0){ 

													$data['errmsg'] =  $chk_six['msg'];

													break;

												}else{

													$chk_seven = bulk_examdate($regnumber,$exam_code);

														

													if($chk_seven['flag'] == 1){

														$data['errmsg'] =  $chk_seven['msg'];

														break;

													}else{

													

													$subject_code_arr = get_member_subjects($regnumber,$exam_code,$institute_code);

														

														foreach($subject_code_arr as $subject_code)

														{

														

														   // echo $regnumber;

															$subject_code_c = $subject_code['subject_code'];

															$chk_eight = bulk_excel_chk_capacity_temp($regnumber,$exam_code,$exam_period,$center_code,$subject_code_c);

															//echo 'chk_eight:',$chk_eight['flag'],'</br>';

															

															if($chk_eight['flag'] == 0)

															{

																$chk = 0;

																break;

															}

															else

															{

																$chk = 1;

																

															}

															if($chk == 0)

															{

															 break;

															}

														}// end of foreach

														

														if($chk == 0){

															$data['errmsg'] = 'capacity not available123';

															break;

														}else{

															$c++;

															$success = 'success';

															/*echo "####". $total_record;

															echo "<br/>";

															echo ">>>".$c;

															echo "<br/>";*/

														}

													}

												}

											}

										}

									}

								}

							}

						}

						

					}// end of for

				//	exit;

				echo "<br/>";

				echo "**************";

				echo "<br/>";

				echo ">>".$c;

				echo "<br/>";

				echo "##".$cc;

				echo "<br/>";

				

				if($c != $cc){

					echo "here";

					$hold_update = array("is_delete"=>'1');

					$wh = array("inst_id"=>$this->session->userdata('institute_id'),'exam_code'=>$exam_code,'exam_period'=>$exam_period);

					$this->master_model->updateRecord('bulk_excel_temp',$hold_update,$wh);

					//exit;

				}

				if($c == $cc){

					echo "here123";

					//exit;

				}

				

					//insert record in member_exam and admit_card_details

					//if($c == $total_record && $success == 'success'){

					if($c == $cc && $success == 'success'){

						$hold_update = array("is_delete"=>'1');

						$wh = array("inst_id"=>$this->session->userdata('institute_id'),'exam_code'=>$exam_code,'exam_period'=>$exam_period);

						$this->master_model->updateRecord('bulk_excel_temp',$hold_update,$wh);

					echo "pawan"; exit;

					//for($s = 1; $s < sizeof($allDataInSheet); $s++){

					for($s = 1; $s < $cc+1; $s++){

					//echo 'in';exit;

					

					$excd = explode("=",$allDataInSheet[$s][$j]);

							

							$this->db->select('exam_master.exam_code,exam_period');

							$this->db->from('exam_master');

							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');

							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

							$this->db->like("description",$excd[0]);

							$this->db->where("exam_to_date >",date('Y-m-d'));

							$record = $this->db->get();

							$ex_info = $record->row();

							

							

							

							$cncd = explode("=",$allDataInSheet[$s][$m]);

							$mdcd = explode("=",$allDataInSheet[$s][$p]);

							

							// Required Inputs

							$regnumber = $allDataInSheet[$s][$i];

							$exam_code = $excd[1];

							$exam_period = $ex_info->exam_period;

							$center_code = $cncd[1];

							$exam_mode = 'Online';

							$institute_code = $this->session->userdata('institute_id');

							$medium = $mdcd[1];

							$scribe_flag = $allDataInSheet[$s][$q];

							

							/*echo 'regnumber',$regnumber,'</br>';

							echo 'exam_code',$exam_code,'</br>';

							echo 'exam_period',$exam_period,'</br>';

							echo 'center_code',$center_code,'</br>';

							echo 'exam_mode',$exam_mode,'</br>';

							echo 'institute_code',$institute_code,'</br>';

							echo 'medium',$medium,'</br>';

							echo 'scribe_flag',$scribe_flag,'</br>';

							exit;*/

					/*		

							$regnumber = $allDataInSheet[$s][$i];

						

							

							

							$this->db->select('exam_master.exam_code,exam_period');

							$this->db->from('exam_master');

							$this->db->join('bulk_exam_activation_master', 'exam_master.exam_code = bulk_exam_activation_master.exam_code');

							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

							$this->db->like("description",$excd[0]);

							//$this->db->like("description",$allDataInSheet[$s][$j]);

							$this->db->where("exam_to_date >",date('Y-m-d'));

							$record = $this->db->get();

							$ex_info = $record->row();

							

							//$exam_code = 11;	

							//$exam_period = 417;

							

							$this->db->select('center_code');

							$this->db->from('center_master');

							//$this->db->where("center_name ",$allDataInSheet[$s][$m]);

							$this->db->where("center_name ",$cncd[0]);

							$record_1 = $this->db->get();

							$center_info = $record_1->row();

							

							$center_code = $center_info->center_code;

							//$subject_code = $allDataInSheet[$s][$n];

							$institute_code = $this->session->userdata('institute_id');

							$medium = $allDataInSheet[$s][$p];

							

							//##--------------get medium code

							$this->db->select('medium_code');

							$this->db->from('medium_master');

							//$this->db->where('medium_description',$medium);

							$this->db->where('medium_description',$mdcd[0]);

							$record_2 = $this->db->get();

							$medium_info = $record_2->row();

							$medium = $medium_info->medium_code;*/

							

							//$exam_mode = $allDataInSheet[$s][$q];

							//$exam_mode = 'ON';

							

							

							//get member_info

							$this->db->where("isactive",'1'); 

							$mem_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber));

																

							//get group code

							$this->db->where("eligible_master.member_no",$regnumber);

							$this->db->where("eligible_master.app_category !=",'R');

							$this->db->where('eligible_master.exam_code',$exam_code); 

							$examinfo=$this->master_model->getRecords('eligible_master');

															

							if(isset($examinfo[0]['app_category'])){

								$grp_code=$examinfo[0]['app_category'];

							}else{

								$grp_code='B1_1';

							};

															    

							//If exam is special exam

							$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code));

																

							$special_exam_date = '';

							if($exam_category[0]['exam_category']==1)

							{

								

								$today_date=date('Y-m-d');

								$this->db->where("'$today_date' BETWEEN from_date AND to_date");

								$special_exam_dates=$this->master_model->getRecords('special_exam_dates');

								$special_exam_date = $special_exam_dates[0]['examination_date'];

							}

					

															

							$amount=bulk_getExamFee($center_code,$exam_period,$exam_code,$grp_code,$mem_info[0]['registrationtype']);

			

							//##------------get app_category and base_fee

							$fee_result=bulk_getFee_Appcat($center_code,$exam_period,$exam_code,$grp_code,$mem_info[0]['registrationtype']);

														

							

							//print_r($fee_result['base_fee']);

							$inser_array=array('regnumber'=>$regnumber,

							'member_type'=>$mem_info[0]['registrationtype'],

							//'app_category'=>$fee_result['grp_code'],

							'app_category'=>$grp_code,

							'base_fee'=>$fee_result['base_fee'],

							'exam_code'=>$exam_code,

							'exam_mode'=>$exam_mode,

							'exam_medium'=>$medium,

							'exam_period'=>$exam_period,

							'exam_center_code'=>$center_code,

							'exam_fee'=>$amount,

							'examination_date'=>$special_exam_date,

							'scribe_flag'=>$scribe_flag,

							'created_on'=>date('y-m-d H:i:s'),

							'institute_id'=>$institute_code,

							'pay_status'=>'2',

							'bulk_isdelete'=>'0'

							);

							/*echo "<pre>";

							print_r($inser_array);

							exit;*/

						//$exam_last_id = 1;

					if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_array,true)){

					

						//##----------prepare user name

						$username=$mem_info[0]['firstname'].' '.$mem_info[0]['middlename'].' '.$mem_info[0]['lastname'];

						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

						

						//##----------set invoice details

						$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'center_code'=>$center_code,'exam_period'=>$exam_period,'center_delete'=>'0'));

						

						 //##---------check Gender

						if($mem_info[0]['gender']=='male')

						{$gender='M';}

						else

						{$gender='F';}

						

						//##----------get state name

						$states=$this->master_model->getRecords('state_master',array('state_code'=>$mem_info[0]['state'],'state_delete'=>'0'));

						$state_name='';

						if(count($states) >0)

						{

							$state_name=$states[0]['state_name'];

						}		

						

						$password = random_password();

						$compulsory_subjects = get_member_subjects($regnumber,$exam_code,$institute_code);

						

						if(!empty($compulsory_subjects))

						{

								foreach($compulsory_subjects as $y)

								{

								

										$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$exam_period,'subject_code'=>$y['subject_code']),'subject_description');

									    

										//##-----------get venue,exam_date,exam_time dynamically

										$venue_info=bulk_excel_get_capacity($exam_code,$exam_period,$center_code,$y['subject_code']);

										

									

									

									if($venue_info['flag'] == 0)

									{

									  echo 'venue not available';

									  break;

									}

									else

									{

									

										$venueid=$venue_info['venue_arry']['venue_code'];

										$venue_name=$venue_info['venue_arry']['venue_name'];

										$venueadd1=$venue_info['venue_arry']['venue_addr1'];

										$venueadd2=$venue_info['venue_arry']['venue_addr2'];

										$venueadd3=$venue_info['venue_arry']['venue_addr3'];

										$venueadd4=$venue_info['venue_arry']['venue_addr4'];

										$venueadd5=$venue_info['venue_arry']['venue_addr5'];

										$venpin=$venue_info['venue_arry']['venue_pincode'];

										$exam_date=$venue_info['venue_arry']['exam_date'];

										$time=$venue_info['venue_arry']['session_time'];

										$vendor_code=$venue_info['venue_arry']['vendor_code'];

									

									}

									

									$admitcard_insert_array=array('mem_exam_id'=>$exam_last_id,

																'center_code'=>$getcenter[0]['center_code'],

																'center_name'=>$getcenter[0]['center_name'],

																'mem_type'=>$mem_info[0]['registrationtype'],

																'mem_mem_no'=>$regnumber,

																'g_1'=>$gender,

																'mam_nam_1'=>$userfinalstrname,

																'mem_adr_1'=>$mem_info[0]['address1'],

																'mem_adr_2'=>$mem_info[0]['address2'],

																'mem_adr_3'=>$mem_info[0]['address3'],

																'mem_adr_4'=>$mem_info[0]['address4'],

																'mem_adr_5'=>$mem_info[0]['district'],

																'mem_adr_6'=>$mem_info[0]['city'],

																'mem_pin_cd'=>$mem_info[0]['pincode'],

																'state'=>$state_name,

																'exm_cd'=>$exam_code,

																'exm_prd'=>$exam_period,

																'sub_cd '=>$y['subject_code'],

																'sub_dsc'=>$compulsory_subjects[0]['subject_description'],

																'm_1'=>$medium,

																'venueid'=>$venueid,

																'venue_name'=>$venue_name,

																'venueadd1'=>$venueadd1,

																'venueadd2'=>$venueadd2,

																'venueadd3'=>$venueadd3,

																'venueadd4'=>$venueadd4,

																'venueadd5'=>$venueadd5,

																'venpin'=>$venpin,

																'exam_date'=>$exam_date,

																'time'=>$time,

																'vendor_code'=>$vendor_code,

																'pwd'=>$password,

																'mode'=>$exam_mode,

																'scribe_flag'=>$scribe_flag,

																'remark'=>2,

																'record_source'=>'Bulk',

																'created_on'=>date('Y-m-d H:i:s'));

																

																

									$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);

									

								}

						}

						

						$data['succmsg'] =  "Application added sucessfully...";

						//echo 'Insert admit card entry';

					}

																	

					}

					}

					

					

					}

					else

					{

						$data['errmsg'] =  "Uploaded file is blank";	

					}

				}else{

					$data['errmsg'] =  "Coloumn data is not proper in file";

				}

			}else{

				$data['validation_errors'] = validation_errors();

			}

			

			}

		}

	    //$data['middle_content'] = 'bulk/bulk_add_member_excel';

		//$data=array('middle_content'=>'bulk/bulk_add_member_excel');

		$this->load->view('upload_csv',$data);

	}

	

	public function send_bulk_admitcard_invoice(){

		

		$id = 1;

		$utr_no = '20424473411DCICICI';

		$mem_exam_id_arr = array(2576029,2576034,2576059,2576069,2576128,2576139,2576143,2576166,2576311,2576486,2577278,2577484,2577591,2577692,2577693,2577939,2578112);

		

		

		//$mem_exam_id_arr = array(2576029);

		

		/*echo "<pre>";

		print_r($mem_exam_id_arr);

		exit;*/

		

		// Generate admitcard pdf call

			$this->db->where_in('mem_exam_id',$mem_exam_id_arr);

			$this->db->group_by('mem_mem_no'); 

			$member_array_admitcard = $this->master_model->getRecords('admit_card_details',array('remark'=>'1','record_source'=>'bulk','admitcard_image '=>''),'mem_mem_no,exm_cd,exm_prd');

			

			/*echo "pawan";

			exit; */

			

			foreach($member_array_admitcard as $member_array_record){

				$attchpath_admitcard = genarate_admitcard_bulk($member_array_record['mem_mem_no'],$member_array_record['exm_cd'],$member_array_record['exm_prd']);

				//echo ">>". $attchpath_admitcard;

				//echo "<br/>";

				

				// Send email template of admitcard pdf to user

				if($attchpath_admitcard != ''){

					

					

					$member_info_new = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_array_record['mem_mem_no']));

					

					//Query to get exam details	

					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');

					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$member_array_record['mem_mem_no'],'member_exam.exam_period'=>$member_array_record['exm_prd']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

					

					$username=$member_info_new[0]['firstname'].' '.$member_info_new[0]['middlename'].' '.$member_info_new[0]['lastname'];

					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

					

					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

					

					//Query to get Medium	

					$this->db->where('exam_code',$member_array_record['exm_cd']);

					$this->db->where('exam_period',$exam_info[0]['exam_period']);

					$this->db->where('medium_code',$exam_info[0]['exam_medium']);

					$this->db->where('medium_delete','0');

					$medium=$this->master_model->getRecords('medium_master','','medium_description');

					

					if($exam_info[0]['exam_mode']=='ON')

					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')

					{$mode='Offline';}

					

					

					

					// email for new member

					

					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

					$key = $this->config->item('pass_key');

					$aes = new CryptAES();

					$aes->set_key(base64_decode($key));

					$aes->require_pkcs5();

					$decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

					

					

					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'new_non_member_bulk'));

					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);

					$newstring2 = str_replace("#REG_NUM#", "".$member_array_record['mem_mem_no']."",$newstring1);

					//$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

					$newstring3 = str_replace("#EXAM_NAME#", "CERTIFIED CREDIT PROFESSIONAL",$newstring2);

					//$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);

					$newstring4 = str_replace("#EXAM_DATE#", "February-2018",$newstring3);

					$newstring6 = str_replace("#ADD1#", "".$member_info_new[0]['address1']."",$newstring4);

					$newstring7 = str_replace("#ADD2#", "".$member_info_new[0]['address2']."",$newstring6);

					$newstring8 = str_replace("#ADD3#", "".$member_info_new[0]['address3']."",$newstring7);

					$newstring9 = str_replace("#ADD4#", "".$member_info_new[0]['address4']."",$newstring8);

					$newstring10 = str_replace("#DISTRICT#", "".$member_info_new[0]['district']."",$newstring9);

					$newstring11 = str_replace("#CITY#", "".$member_info_new[0]['city']."",$newstring10);

					$newstring12 = str_replace("#STATE#", "".$member_info_new[0]['state_name']."",$newstring11);

					$newstring13 = str_replace("#PINCODE#", "".$member_info_new[0]['pincode']."",$newstring12);

					$newstring14 = str_replace("#EMAIL#", "".$member_info_new[0]['email']."",$newstring13);

					//$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);

					$newstring15 = str_replace("#MEDIUM#", "English",$newstring14);

					$newstring16 = str_replace("#CENTER#", "Mumbai",$newstring15);

					$newstring17 = str_replace("#CENTER_CODE#", "306",$newstring16);

					$newstring18 = str_replace("#MODE#", "Online",$newstring17);

					//$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);

					$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring18);

					//$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);

					$final_str_pdf = $newstring20;

					

					

					//$final_str_pdf = "This is mail for new member";

					

					$files_pdf=array($attchpath_admitcard);

					$info_arr_pdf=array('to'=>$member_info_new[0]['email'],

											//'to'=>'ztest2500@gmail.com',

											'from'=>'noreply@iibf.org.in',

											'subject'=>'Bulk Exam application',

											'message'=>$final_str_pdf

											);

					$this->Emailsending->mailsend_attch($info_arr_pdf,$files_pdf);

					

					

					echo "Mail send >> ".$member_array_record['mem_mem_no']."-".$member_info_new[0]['email']."<br/>";

					

				}

			}

			

			

	}

	

	public function custome_bulk_invoice(){

		echo ">>##". $attach = custome_generate_bulk_examinvoice(132);	 

	}

	

	public function send_bulk_invoice_mail(){

		$attchpath_examinvoice = 'https://iibf.esdsconnect.com/uploads/bulkexaminvoice/user/bulk_1343_EX_17-18_144407.jpg';

		

		if($attchpath_examinvoice != ''){

			$final_str_invoice = "Please check attach invoice";

			$files_invoice=array($attchpath_examinvoice);

			$info_arr_invoice=array(//'to'=>$bank_info[0]['email'],

									'to'=>'iibfexam@iibf.org.in',

									'from'=>'noreply@iibf.org.in',

									'subject'=>'Bulk Exam application',

									'message'=>$final_str_invoice

									);

			$this->Emailsending->mailsend_attch($info_arr_invoice,$files_invoice);

			echo "mail send";

		}

	}

	

	public function read_xlsx_seat_allocation(){

		$sub_arr = array();

		$flag = 0;

		$flag1 = 0;

		$flag2 = 0;

		$updated_date = date('Y-m-d H:i:s');

		$status = '';

		

		$utr_no = $this->input->post('utr_no'); // post parameter

		$id = $this->input->post('id'); // post parameter

		$mem_count = $this->input->post('mem_count'); // post parameter

		$payment_amt = $this->input->post('payment_amt'); // post parameter

		$payment_date = $this->input->post('payment_date'); // post parameter

		

		

		// Fetch all member_exam_id

		$memexamidlst = $this->master_model->getRecords('bulk_member_payment_transaction',array('ptid' => $id));

		foreach($memexamidlst as $memexamids){

			$mem_exam_id_arr[] = $memexamids['memexamid'];

		}

		//fetch all record for which we want to check capacity

		$this->db->where_in('mem_exam_id',$mem_exam_id_arr);

		$member_array = $this->master_model->getRecords('admit_card_details',array('remark'=>2,'record_source'=>'bulk','venueid'=>''));

		

		if($this->input->post('action') == "Approved"){ 

		

		

		foreach($member_array as $member_record){

			// fetch all exam date

			$exam_date = $this->master_model->getRecords('subject_master',array('exam_code'=>$member_record['exm_cd'],'subject_code'=>$member_record['sub_cd']),'exam_date');

			

			

			// fetch all venue of given center code

			$this->db->distinct();

			$this->db->select('venue_code');

			$this->db->where('center_code',$member_record['center_code']);

			$this->db->where('exam_date', $exam_date[0]['exam_date']);

			$venue = $this->master_model->getRecords('venue_master');

			

			// generate venue array of given center

			foreach($venue as $venue_res){

				$venue_arr[] = $venue_res['venue_code'];

			}

			

			$exam_code = $member_record['exm_cd'];

			$sub_code = $member_record['sub_cd'];

			$mem_mem_no = $member_record['mem_mem_no'];

			$admitcard_id = $member_record['admitcard_id'];

			

			$v_code = '';

			$e_date = '';

			$e_time = '';

			$venue_size = count($venue_arr);

			for($j=0;$j<$venue_size;$j++){

				// get all sessions for selected date

				$this->db->where('center_code',$member_record['center_code']);

				$this->db->where('venue_code',$venue_arr[$j]);

				$this->db->where('exam_date',$exam_date[0]['exam_date']);

				$time_sql = $this->master_model->getRecords('venue_master','','session_time');

				

				$time_sql_size = sizeof($time_sql);

				for($l=0;$l<$time_size;$l++){

					$capacity = check_capacity_bulk($venue_arr[$j],$exam_date[0]['exam_date'],$time_sql[$l],$member_record['center_code']);

					if($capacity != 0){

						$sub_details = array("exam_code" => $member_record['exm_cd'], "center_code" => $member_record['center_code'], "venue_code" => $venue_arr[$j], "exam_date" => $exam_date, "exam_time" => $time_sql[$l], "mem_mem_no" => $member_record['mem_mem_no'], "admitcard_id" => $member_record['admitcard_id'],"sub_code"=>$member_record['sub_cd'],'exam_period'=>$member_record['exm_prd'],'member_exam_id'=>$member_record['mem_exam_id']);

						$sub_arr[] = $sub_details;

						$flag = 1;

						break;

					}else{

						$sub_arr = array();

						$update_data_two = array(

									'regnumber'	=> $mem_mem_no,

									'venue_code'=> $venue_arr[$j],

								 	'exam_date'	=> $exam_date,

									'center_code'=> $member_record['center_code'],

									'exam_time'	=> $time_sql[$l],

									'sub_code'	=> $member_record['sub_cd']

								);

					log_bulk_admin($log_title = "Capacity not available - excel", $log_message = serialize($update_data_two));

					$data['success'] = 'Capacity not available!!';

					}

				} // end of for loop of time

				if($flag == 1){

					break;

				}

			} // end of for loop of venue

		} // end of for loop of member

		

		if(count($member_array) == count($sub_arr)  && count($sub_arr) > 0){

			foreach($sub_arr as $sub_details){

				$v_code = $sub_details['venue_code'];

				$e_date = $sub_details['exam_date'];

				$e_time = $sub_details['exam_time'];

				$sub_code = $sub_details['sub_code'];

				$exam_code = $sub_details['exam_code'];

				$mem_mem_no = $sub_details['mem_mem_no'];

				$admitcard_id = $sub_details['admitcard_id'];

				$exam_period = $sub_details['exam_period'];

				$center_code = $sub_details['center_code'];

				

				// get venue details

				$get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));

				

				// allocate seat call

				$seat_allocation = getseat_bulk($exam_code, $center_code, $v_code, $e_date, $e_time, $exam_period, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

				

				if($seat_allocation != ''){

					// update admit_card_detail table

					$update_seatno = array('seat_identification'=>$seat_allocation);

					$this->master_model->updateRecord('admit_card_details',$update_seatno,array('admitcard_id'=>$admitcard_id));	

					$update_data_three = array(

									'regnumber'	=> $mem_mem_no,

									'venue_code'=> $v_code,

								 	'exam_date'	=> $e_date,

									'center_code'=> $center_code,

									'exam_time'	=> $e_time,

									'sub_code'	=> $sub_code

								);

					log_bulk_admin($log_title = "Seat allocate successfully", $log_message = serialize($update_data_three));

				}else{

					$data['success'] = 'Error while allocating seat!!';

					$update_data_four = array(

									'regnumber'	=> $mem_mem_no,

									'venue_code'=> $v_code,

								 	'exam_date'	=> $e_date,

									'center_code'=> $center_code,

									'exam_time'	=> $e_time,

									'sub_code'	=> $sub_code

								);

					log_bulk_admin($log_title = "Seat not allocate ", $log_message = serialize($update_data_four));

				}

				

				// update member_exam table

				$exam_period = $record['exam_period'];

				$update_member_exam_date = array('pay_status' => $status,'modified_on'=>$updated_date);

				$this->master_model->updateRecord('member_exam',$update_member_exam_date,  array('id' => $sub_details['member_exam_id']));

				

				// update admit_card_detail table

				$update_seatno_remark = array('remark'=>1,'modified_on'=>$updated_date);

				$this->master_model->updateRecord('admit_card_details',$update_seatno_remark,array('admitcard_id'=>$sub_details['admitcard_id']));

				

			}// end of loop of sub_array

			

			// updated required table

			$data['success'] = 'success';

			$status = 1;

			$desc = 'Payment Success - Approved by Admin';

			$update_data = array(

							'status'		=> $status,

							'UTR_no'		=> $utr_no,

							'pay_count'		=> $mem_count,

							'amount'		=> $payment_amt,

							'date'			=> date("Y-m-d", strtotime($payment_date)),

							'description'	=> $desc,

							'updated_date'	=> $updated_date

						);

			$this->master_model->updateRecord('bulk_payment_transaction',$update_data,  array('id' => $id));

			

			// Generate admitcard pdf call

			$this->db->group_by('mem_mem_no,exm_cd'); 

			$this->db->where_in('mem_exam_id',$mem_exam_id_arr);

			$member_array_admitcard = $this->master_model->getRecords('admit_card_details',array('remark'=>'1','record_source'=>'bulk'),'mem_mem_no,exm_cd,exm_prd');

			foreach($member_array_admitcard as $member_array_record){

				$attchpath_admitcard = genarate_admitcard_bulk($member_array_record['mem_mem_no'],$member_array_record['exm_cd'],$member_array_record['exm_prd']);

				if($attchpath_admitcard != ''){

					$member_info_new = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_array_record['mem_mem_no']));

					

					//Query to get exam details	

					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');

					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$member_array_record['mem_mem_no']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

					

					$username=$member_info_new[0]['firstname'].' '.$member_info_new[0]['middlename'].' '.$member_info_new[0]['lastname'];

					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

					

					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

					

					//Query to get Medium	

					$this->db->where('exam_code',$member_array_record['exm_cd']);

					$this->db->where('exam_period',$exam_info[0]['exam_period']);

					$this->db->where('medium_code',$exam_info[0]['exam_medium']);

					$this->db->where('medium_delete','0');

					$medium=$this->master_model->getRecords('medium_master','','medium_description');

					

					if($exam_info[0]['exam_mode']=='ON')

					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')

					{$mode='Offline';}

					

					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');

						$aes = new CryptAES();

						$aes->set_key(base64_decode($key));

						$aes->require_pkcs5();

						$decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

						

						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'old_non_member_bulk'));

						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);

						$newstring2 = str_replace("#REG_NUM#", "".$member_array_record['mem_mem_no']."",$newstring1);

						$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

						$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);

						//$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);

						$newstring6 = str_replace("#ADD1#", "".$member_info_new[0]['address1']."",$newstring4);

						$newstring7 = str_replace("#ADD2#", "".$member_info_new[0]['address2']."",$newstring6);

						$newstring8 = str_replace("#ADD3#", "".$member_info_new[0]['address3']."",$newstring7);

						$newstring9 = str_replace("#ADD4#", "".$member_info_new[0]['address4']."",$newstring8);

						$newstring10 = str_replace("#DISTRICT#", "".$member_info_new[0]['district']."",$newstring9);

						$newstring11 = str_replace("#CITY#", "".$member_info_new[0]['city']."",$newstring10);

						$newstring12 = str_replace("#STATE#", "".$member_info_new[0]['state_name']."",$newstring11);

						$newstring13 = str_replace("#PINCODE#", "".$member_info_new[0]['pincode']."",$newstring12);

						$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);

						$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);

						$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);

						$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);

						$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);

						//$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);

						$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring18);

						//$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);

						$final_str_pdf = $newstring20;

						

						

						//$final_str_pdf = "This is mail for old member";

						

						$files_pdf=array($attchpath_admitcard);

						$info_arr_pdf=array(//'to'=>$member_info_new[0]['email'],

												'to'=>'ztest2500@gmail.com',

												'from'=>'noreply@iibf.org.in',

												'subject'=>'Bulk Exam application1',

												'message'=>$final_str_pdf

												);

						$this->Emailsending->mailsend_attch($info_arr_pdf,$files_pdf);

				}

			} // end of admitcard foreach

			

			// Generate exam invoice call

			$getinvoice_id = $this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$id),'invoice_id');

			$invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);

			if($invoiceNumber){

				$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

			}else{

				$invoiceNumber='';

			}

			

			

			$update_data_invoice = array('invoice_no' => $invoiceNumber,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'),'transaction_no'=>$utr_no);

			$this->db->where('pay_txn_id',$id);

			$this->master_model->updateRecord('exam_invoice',$update_data_invoice,array('receipt_no'=>$id));

			

			$attchpath_examinvoice = generate_bulk_examinvoice($id);

			

			$this->db->join('bulk_payment_transaction','bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');

			$this->db->where('bulk_payment_transaction.id',$id);

			$bank_info=$this->master_model->getRecords('bulk_accerdited_master','','email');

			

			// Send email template of exam invoice to bank

			if($attchpath_examinvoice != ''){

				$final_str_invoice = "Please check attach invoice";

				$files_invoice=array($attchpath_examinvoice);

				$info_arr_invoice=array(//'to'=>$bank_info[0]['email'],

										'to'=>'ztest2500@gmail.com',

										'from'=>'noreply@iibf.org.in',

										'subject'=>'Bulk Exam application2',

										'message'=>$final_str_invoice

										);

				$this->Emailsending->mailsend_attch($info_arr_invoice,$files_invoice);

			}

			

			

		} // end of if 

		

		if(count($member_array) != count($sub_arr)){

			// update member exam table.

			$reject_status = 0;

			$mem_exam_str = implode(",",$mem_exam_id_arr);

			$desc = 'Payment Failed - Rejected by Admin';

			$this->db->query("update member_exam set pay_status = 0, modified_on = '".$updated_date."' where id IN (".$mem_exam_str.")");

			

			// update bulk payment transaction table

			$update_payment_transaction_reject = array('status' => $reject_status,'updated_date'=>$updated_date,'description'=>$desc);

			$this->master_model->updateRecord('bulk_payment_transaction',$update_payment_transaction_reject,array("id"=>$id));

			

			// update exam invoice table

			$update_exam_invoice_reject = array('transaction_no' => '','modified_on'=>$updated_date);

			$this->master_model->updateRecord('exam_invoice',$update_exam_invoice_reject,array("pay_txn_id"=>$id));

			

			// update admit card details table

			$this->db->query("update admit_card_details set remark = 4, modified_on = '".$updated_date."' where mem_exam_id IN (".$mem_exam_str.")");

			

			//insert in log table

			$update_data_log = array(

								'status'		=> $reject_status,

								'description'	=> $desc,

								'updated_date'	=> $updated_date,

								'approve_id'	=> $id

							);	

							

			log_bulk_admin($log_title = "Capacity not avaiable", $log_message = serialize($update_data_log));

			

			$data['success'] = 'Capacity not avaiable';

		}

		

		

		}elseif($this->input->post('action') == "Rejected"){

			// update member exam table.

			$reject_status = 0;

			$mem_exam_str = implode(",",$mem_exam_id_arr);

			$desc = 'Payment Failed - Rejected by Admin';

			$this->db->query("update member_exam set pay_status = 0, modified_on = '".$updated_date."' where id IN (".$mem_exam_str.")");

			

			// update bulk payment transaction table

			$update_payment_transaction_reject = array('status' => $reject_status,'updated_date'=>$updated_date,'description'=>$desc);

			$this->master_model->updateRecord('bulk_payment_transaction',$update_payment_transaction_reject,array("id"=>$id));

			

			// update exam invoice table

			$update_exam_invoice_reject = array('transaction_no' => '','modified_on'=>$updated_date);

			$this->master_model->updateRecord('exam_invoice',$update_exam_invoice_reject,array("pay_txn_id"=>$id));

			

			// update admit card details table

			$this->db->query("update admit_card_details set remark = 4, modified_on = '".$updated_date."' where mem_exam_id IN (".$mem_exam_str.")");

			

			//insert in log table

			$update_data_log = array(

								'status'		=> $reject_status,

								'description'	=> $desc,

								'updated_date'	=> $updated_date,

								'approve_id'	=> $id

							);	

							

			log_bulk_admin($log_title = "Bulk Admin NEFT Rejected Successfully", $log_message = serialize($update_data_log));

			

			$data['success'] = 'Bulk Admin NEFT Rejected Successfully';

		}

		

		$json_res = json_encode($data);

		echo $json_res;

	}

	

	public function unlink_file(){

		//unlink('uploads/testunlink/21_217_511000009.pdf');

		try{

			//$mask = '21_217_*.*';

			//array_map('unlink', glob('uploads/testunlink/'.$mask));

			

			//date('Y-m-d h:i:s');

			//non_mem_photo_157379490890.jpg

			

			//$date = '2019-11-14';

			//1573669800

			//echo strtotime($date);

			

			$img = 'non_mem_photo_157370*';

			$path  = glob(('uploads/photograph/'.$img)); 

			echo '<pre>';

			print_r($path);

			exit;

			

			

			

		}catch(Exception $e){

			echo "Message: ".$e->getMessage();

		}

	}

	

	

	public function unlink_file_putty(){

		//unlink('uploads/testunlink/21_217_511000009.pdf');

		try{

			

			$img = 'non_mem_photo_157370*';

			$path  = glob(('uploads/photograph/'.$img)); 

			echo '<pre>';

			print_r($path);

			exit;

			

			

			

		}catch(Exception $e){

			echo "Message: ".$e->getMessage();

		}

	}

	

	public function generate_bulk_admitcard(){

		

		//$member_array = array();

		

		$member_array = array(500120317,500041369,500066039,500092086,500027556,510052154,500039997,500004718,510051729,500105429,801206512,400014311,500083692);

		

		$exam_code = 81;

		$exam_period = 802;

		

		for($i=0;$i<=12;$i++){

			$attchpath = custome_genarate_admitcard_bulk802($member_array[$i],$exam_code,$exam_period);

			echo $attchpath."<br/>";

		}

		

		

		

	}

	

	public function hydradelete(){

		$delete_array = $this->master_model->getRecords('hydrabad_delete');

		$i=1;

		foreach($delete_array as $delete_record){

			echo $delete_record['member_number']."--".$delete_record['exam_code']."--".$delete_record['subject_code'];

			echo "<br/>";

			echo "pawan";

			echo "<br/>";

			$this->db->where('mem_mem_no',$delete_record['member_number']);

			$this->db->where('exm_cd',$delete_record['exam_code']);

			$this->db->where('sub_cd',$delete_record['subject_code']);

			$this->db->where('remark','1');

			$this->db->where('exm_prd','417');

			$this->db->delete('admit_card_details');

			

			

			

			echo $this->db->last_query();

			echo "<br/>";

			$i++;

			

		}

	}

	

	public function addhydra(){

		$add_array = $this->master_model->getRecords('admit_card_details_hydrabad');

		foreach($add_array as $add_record){

			

			$center_name = $this->master_model->getValue('center_master',array('center_code' => $add_record['center_code'],'exam_name'=>$add_record['exm_cd'],'exam_period'=>417), 'center_name');

			

			$member_record = $this->master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$add_record['mem_mem_no']),'address1,address2,address3,address4,city,pincode');

			

			

			

			

			$insert_array = array(

									'center_code'=>$add_record['center_code'],

									'center_name'=>$center_name,

									'mem_type'=>$add_record['mem_type'],

									'mem_mem_no'=>$add_record['mem_mem_no'],

									'g_1'=>$add_record['g_1'],

									'mam_nam_1'=>$add_record['mam_nam_1'],

									'mem_adr_1'=>$member_record[0]['address1'],

									'mem_adr_2'=>$member_record[0]['address2'],

									'mem_adr_3'=>$member_record[0]['address3'],

									'mem_adr_4'=>$member_record[0]['address4'],

									'mem_adr_5'=>$member_record[0]['city'],

									'mem_pin_cd'=>$member_record[0]['pincode'],

									'exm_cd'=>$add_record['exm_cd'],

									'exm_prd'=>417,

									'sub_cd'=>$add_record['sub_cd'],

									'm_1'=>$add_record['m_1'],

									'venueid'=>$add_record['venueid'],

									'venueadd1'=>$add_record['venueadd1'],

									'venpin'=>$add_record['venpin'],

									'pwd'=>$add_record['pwd'],

									'exam_date'=>$add_record['exam_date'],

									'time'=>$add_record['time'],

									'mode'=>$add_record['mode'],

									'seat_identification'=>$add_record['seat_identification'],

									'remark'=> 1,

									'admitcard_image'=>$add_record['exm_cd']."_417_".$add_record['mem_mem_no']."pdf"

			

								);

								

				$this->db->insert('admit_card_details',$insert_array);

				

		}

	}

	

	public function bulk_examinvoice(){ 

		echo ">>". $attachpath = custome_generate_bulk_examinvoice(72); 

	}

	

	public function testtime(){

		

		$str_date = $this->uri->segment(3);

		echo $datetime = str_replace("%20"," ",$str_date);

		echo "<br/>";

		echo date("Y-m-d H:i:s");

		echo "<br/>";

		

		//$str_date='03-03-18 08:45:00pm';

		if(date("Y-m-d H:i:s") > $datetime){

			echo "yes";

		}else{

			echo "no";

		}

	}

	

	public function getdatetime(){

		echo date("Y-m-d H:i:s");	

	}

	

	public function url_track(){

		$this->load->view('testview');

	}

	

	public function chk_password(){

		

		$mis_match = array();

		$this->db->distinct();

		$this->db->select('pwd,mem_mem_no,exm_cd');

		//$this->db->select('pwd');

		//$this->db->like('created_on','2018-03-14');

		$this->db->where('created_on >= ','2018-03-14 00:00:00');

		$this->db->where('created_on <= ','2018-03-15 23:59:59');

		//$this->db->limit(500);

		$sql = $this->master_model->getRecords('admit_card_details_21_118');

		

		//echo $this->db->last_query();

		//exit;

		

		

		foreach($sql as $rec){ 

			//$this->db->distinct();

			$this->db->select('admit_card_details.*');

			$this->db->from('admit_card_details');

			$this->db->where(array('admit_card_details.mem_mem_no' => $rec['mem_mem_no'],'exm_cd'=>$rec['exm_cd'],'remark'=>1));

			$record = $this->db->get();

			$result = $record->row();

			

			

			//echo $this->db->last_query();

			//exit;

			

			/*echo $this->db->last_query();

			echo "<br/>";

			echo ">>".$rec['mem_mem_no'];

			echo "<br/>";

			echo ">>".$result->pwd;

			echo "<br/>";

			echo "<pre>";

			print_r($result);

			echo "<br/>";

			//echo "##".$result['pwd'];

			exit;*/

			

			if($result->exm_cd == $rec['exm_cd']){

				if($result->pwd != $rec['pwd']){

					$mis_match[] = $rec['mem_mem_no'];

				}

			}

			

			

		}

		

		echo "<pre>";

		print_r($mis_match);

		

	}

	

	public function chk_password_sendmail(){ 

		

		$member_array = array(510132647,510317019,510272550,510315471,510334127,500077034,510301768,510124465,510350875,510304210,510147882,500188460,510316603,500109469,510299906,510310068,700016469,510264542,510349100,510340169,510328779,510066753,510300077,500143056,510324104,510066050,510266090,510299594,510278726,510110485,510343535,510101867,500201834,510114665,510016721,510300719,510300311,500187133,510287030,510348378,510276759,510331070,500158146,510328360,510029867,510328312,510331394,510014193,510124053,500064850,510242550,510217163,510185774,510189095,510314993,510319769,510271003,510315568,510332926,500067899,510345822,510345689,510303784,510342997,510318020,510057967,510241950,510318856,500203104,510057901,510307973,510224394,100029333,510341505,510140757,510140396,500123657,510077922,510340833,510323891,510295990,500189163,510307886,510296248,510044958,510332712,510187883,510319614,510320827,510032793,510319924,510046730,510329035,510170069,510272221,510336664,510348792,510310716,510334404,510283965,510258190,510237284,510339370,510081294,500181455,510015202,510220161,510311250,510264546,510346325,510222226,510353922,510306514,510181848,510305335,510338834,510322393,510336837,500142913,510284594,510196064,510249649,510288195,510321371,510182276,510323590,510124629,510341958,510068153,510241236,510263368,510256397,510207348,510250039,510305333,510313505,510231834,510317944,500074597,510316774,510351142,510233251,510296747,510190992,510326568,510289813,510316973,510324145,510346778,510342319,510286321,510029195,510135369,510319400,510312529,510327476,510325477,510324309,510293909,510246158,510304181,510302058,510285814,510288709,510057490,510352822,510345112,510232277,500011066,510089816,510309701,500206548,510344807,510331020,510065783,510304322,510258951,510311261,510351135,510135868,510331039,510286373,510119796,510353933,510319134,510296945,510240743,510318087,510022841,510160498,510289060,510249541,510176688,510032675,510151485,510286328,500214997,510339863,510349689,510318161,510171573,510223481,510303193,510310461,510094893,510063229,510257133,510319237,510306047,510139168,510340062,500212820,510319932,510340952,510213081,510353491,510070126,510140843,510320206,510350630,510320958,510233532,510313402,510140510,510342443,510313398,510093093,510274264,510073141,510185041,510330933,510308208,510295358,510216683,500171043,510347386,510003685,510180551,510181608,510167990,510348325,700016810,510343641,510174964,500004924,510276861,510242093,510030235,510354133,510247827,500163841,510252417,510353035,510039411,510081350,500200216,510311686,510209440,510276296,500109688,500153949,510230096,510255823,510196168,500091493,510301700,510280970,510250074,510121526,510278228,510241064,510299141,510350322,510300340,510297310,500149307,510246899,510023757,510197351,500184548,510039792,510317618,510314438,510015983,510080143,500192754,510038788,510119435,510329376,510262290,510274516,510347341,510102597,510349956,510343954,700017849,510046975,500063435,510170850,510303903,510157384,510310732,510350802,510297439,510343468,510319886,510280713,500096680,510117267,510247693,510186696,510240496,510265730,510304491,500117099,510232967,500138941,510303305,500208731,510099923,510130885,510344291,510296804,510200899,510309642,510089512,510215984,510315529,510218696,510261060,510139863,510319903,510309208,510169376,510334954,510345124,500140004,510125745,510307277,510222462,510310702,510120651,510296912,510338400,510325224,510332071,510237468,510321395,510108355,510103586,510012957,510024498,510096651,510050978,510213095,500129217,510339377,510255779,510172899,510313545,510305319,510173768,510150308,510306074,500162383,510114133,510220346,510288836,510139604,510346806,510255879,510349281,510027590,500162440,510309426,500087028,510327166,510274220,510354351,510201912,510315390,510295003,510168218,510177912,510125293,510272788,510223115,510281131,510333563,510308566,510156578,500091182,510284448,500082181,510248406,510332013,510147012,510322209,510074548,510154087,510044186,500181407,510241787,510290613,510342918,7561604,510305973,510214474,510276894,510120376,510278023,510241029,510276104,510185304,510285293,510140993,510272099,510352212,510332391,510205178,510284746,510332347,500086976,510257181,510205176,510121578,510105010,500179826,510176160,510254634,510242993,510270858,510065936,510198414,400081108,510174485,510095520,510223310,510106722,510002826,510109883,510306471,510249476,510311804,500190516,500208726,510282959,500181545,510094662,510225349,500188749,510334189,510041910,500121638,510170032,510313869,510102902,510262120,510351674,510103306,510252121,510114690,500207098,510082775,510264475,510280289,500180035,510342965,500142034,500060542,510234698,510333843,510306968,510108458,510091676,510145105,500163291,510300709,500034099,510324923,500146537,510088534,510085498,510270418,510303917,510338973,510237776,510286378,500211859,510311770,500115481,510083749,510084296,500122054,500090588,510086527,510100663,510004754,510334491,510338651,510105603,510166190,500045109,500197952,500208273,510245442,510145026,510307759,510281818,510270623,510175874,510337784,510324922,510108148,510333316,510264029,510045403,510076318,500078680,510323152,700009123,500216333,510043878,510125840,510083105,510309806,510320975,510077089,510283523,510097251,510187837,500182971,510314959,510319092,510020538,510155078,510298845,400092381,510143240,500034456,500132346,510296774,510128124,510109013,500170163,510327269,510258571,510167634,510346002,510348913,510209515,510326013,510323631,510344007,510323571,510326895,510287954,510277566,510341273,510329262,510323543,510350164,510248081,500130495,510185918,510154998,510228608,510349381,510155026,510322024,510132864,510248435,510332131,510150390,510033488,510115099,510063510,510342010,510182363,510352909,510194152,510344384,510070884,500047282,500056302,510233513,510347957,510237819,510181912,510323588,510205473,510222264,510044499,500130276,510337701,510304840,510324299,510354424,510312857,510337990,510115593,510201805,510344057,510197490,700015121,510330672,500213906,510310561,510027951,500206014,510105493,510247846,510306360,500200161,510342970,500168131,510352289,510321311,510032682,510316204,500074578,510329840,510314935,510086534,510305135,510100357,510189681,510099917,510147290,510064333,510066712,510253522,510227634,510342114,510142851,510295752,510063539,510244101,510061001,510118240,510190300,510099103,510057307,510323121,510323762,510310484,510317186,500214076,500058731,510231447,510341814,510206922,510311414,510037572,510313898,510321099,510121715,510329720,500119158,510304722,510322068,500216159,510170097,510134847,510273267,510117910,510272887,500159786,510324054,510317176,510349196,510343620,510245961,510310318,510229725,510324514,510116198,510287764,510071260,510316086,510315925,500093275,510344140,510335372,510347837,510066115,510325333,510329565,510140582,510349223,510198460,510344402,510032840,500011624,510317990,510106085,510272875,510307275,510237835,510350125,510328804,510325203,510312557,510062239,510201733,510155054,510344428,500138563,500171031,500063547,510094373,510304449,510305158,510289354,510076746,510347544,510340049,510204945,510342450,510322200,510076731,500195370,510324402,510308206,500059591,510210512,510321439,510291126,510279388,510062985,510305733,510321532,510321637,510341199,510340121,510343840,510303476,510259015,510302261,510056593,500182034,510238143,510338931,510249587,510282812,510328939,510158475,510189463,510149534,510238317,510319298,510290795,510300437,510239027,510035150,510239950,510171136,510173091,510300457,500185043,510234896,510200440,510250473,510069111,510028332,510238707,510305362,510172607,510236762,510234235,510337303,510273187,510233496,510293515,510315596,510316586,510302679,510304531,500193990,510122261,500091330,510206315,510101458,510310487,510328250,510192274,6800441,510301028,510323542,510318743,510151980,500190771,510332176,510204142,500199747,510285183,510112370,500082987,510183057,500148244,700017927,510338940,510198409,510286789,510311691,510291240,510298863,510231567,510263273,510260011,510197042,510304281,510229949,510079379,510174099,510353853,510290110,510300094,510340115,510305691,510265733,510216517,510315541,510318390,510174184,500151175,510313360,510308565,510250232,510189777,510196290,510309211,510345943,510308428,510353044,510305073,510307656,510068552,510333397,510118567,510343813,510348172,510321007,510324993,510349642,510319266,510258419,510350672,500209209,510325945,510348659,510306953,510314727,510303103,510336858,510308984,510332705,510342320,510311747,510351807,510308226,510348896,510248579,510234161,510334215,510341905,510303879,510311741,510306218,510319520,500206856,510240462,510334260,510323591,510349126,510307156,510033096,510009077,510302313,510341566,510333699,700017748,500184992,500077624,510340063,510303938,510138889,510314770,510339076,510272761,510182583,510346990,510072306,510077351,510090158,510175952,510227744,500189810,700017697,510318142,510301943,510138870,510157516,510191769,700017824,510320065,510114608,510220683,510242217,510317372,510326638,510323240,510276492,510184803,510291634,510216911,500174428,510189290,510227425,510342133,510290178,510346932,510303038,510242903,510329953,500059679,510294071,510044460,510257859,510145900,510304662,510309789,510305394,510342295,510335165,510291060,510282990,510350369,510351398,510335069,510310460,510144437,510300655,510313145,510206965,510300502,510278584,510345208,510308652,510317235,510274076,510343323,510233597,510084885,510315865,510248698,510262049,510335481,510325465,700016990,510352707,510307991,510181624,510310230,500197800,510255818,510314972,510341985,510243009,510274903,510328629,510352466,510225399,510211637,510333854,510318132,510323176,510204618,510325815,510323282,510329090,510247610,510348810,510244099,510347860,510142521,510269774,510209339,510185405,510346371,510342227,500122783,510139547,510222698,700016105,510243525,510145712,510115316,510301268,510164582,510262677,510339002,510308181,510332617,510251085,510179119,510116698,510183150,510343690,510265249,510178756,510107577,510241802,500168446,510342298,510261374,510258130,510303649,510222163,510251637,510057686,510352629,510215770,510208422,510307393,510338558,510230207,510292833,510306065,510162088,510201752,510290717,510316137,510287161,510070709,700018155,510313038,510353725,510236042,700016928,510301036,510273079,510305150,700016914,7548219,510277703,510158462,510315702,510049697,510059531,510331641,510303358,510290899,510312445,510184938,510306308,500164176,510080919,510194698,510194059,510325082,510012464,510109949,510317168,510245429,510307507,510331634,500184616,510117065,510304058,510316591,510173016,7319771,510319475,510315817,510232765,510319813,500048336,510350227,510220151,510345247,510080640,510103952,510318604,510353421,500186767,510310372,510343152,510270078,510107546,510352666,510324150,500137371,510047407,500189883,510216017,510251381,510318111,500190346,510340807,510294831,510157511,510341123,510252710,510326287,510198777,510326896,510117345,510253253,7409716,510350082,510322076,510157439,510319195,510125979,510159844,510295797,510247409,510317802,510333073,510136941,510280277,510244709,500157795,510286650,510111846,510306765,510306157,510274268,510198895,510325403,510337404,510347143,510276110,510307709,510299426,510344724,510351559,510178819,510235476,510061865,510009894,510293889,510062099,510058775,510054362,510241121,510112921,510244551,510258546,510338981,510304372,510079660,510019680,510313039,510126173,510070672,510296888,510182075,510347095,510342512,510337242,510174362,510176444,510323413,510318750,500155357,510315839,510351918,510115853,510217563,510153103,510147388,510231972,510316366,510257752,510314441,510331243,510349586,510072411,510315778,510191467,7523238,510023478,510243021,500094768,510229610,510313070,510335641,510346931,510163453,510278323,510340795,510324747,510312382,510333564,510221131,510291480,510184167,510304624,510259622,510311424,510338962,510211958,500089261,510339842,510340134,510277964,510307355,510266711,510275662,510302394,510246064,500093774,510133201,510315075,510284776,510317856,510034607,510337446,510125552,510178352,510256697,510283352,510219579,510340723,510321772,510220194,510282894,500174087,510343742,510195274,510250158,510255526,510319643,510261328,510304537,510300569,510301543,510225121,510346144,510259361,500191384,500087341,510315938,510152792,510199040,500149219,500136115,510114208,510322939,510088854,510280469,510345892,700017310,510342147,510221849,510250714,510333066,510299390,510301133,510223448,510220271,510244047,510214248,510040072,510212982,510083711,510354737,700009473,510241469,510216399,510150452,510011589,510326588,510261271,510323169,700017157,510314665,510282844,510270332,510017077,510352798,510298798,510322962,510256230,3429668,510297181,510350627,510319317,510031363,700016917,700017608,510029522,510259070,500196483,510344539,510012502,510164109,510188786,510253058,510337082,510337917,510248109,510345588,510339187,510312347,510323823,510289839,500188065,700016179,510303283,510306404,510193441,510304087,510308264,510333860,510305889,500102368,700017932,510354564,510019890,510232374,510326624,510347191,510143184,510350180,510283640,510226619,510314803,500211081,510182388,510245860,510107112,510173581,510101256,510178409,510323308,510208366,510262368,510023953,510316570,510166500,510291782,510354091,510350688,510146955,510326087,510350226,510278351,510301647,510320192,510181504,510249355,510319944,510315270,510314874,510312737,510145549,510278265,510304518,510338791,510352085,510071433,510047155,510081585,700017261,510341058,510066899,510316158,510154778,500185982,510310097,510195706,510062590,510230054,510307861,510018527,510144364,510332545,510323711,510156194,510193475,510307758); 

		 

		//$this->db->like('created_on','2018-03-14'); 

		$this->db->distinct();

		$this->db->select('pwd,mem_mem_no,exm_cd,admitcard_image');

		$this->db->where('mail_send',0);

		$this->db->where_in('mem_mem_no',$member_array);

		$sql = $this->master_model->getRecords('admit_card_details_21_118');

		

		

		/*$this->db->select('admit_card_details_21_118.*');

		$this->db->from('admit_card_details_21_118');

		$this->db->where('mail_send',0);

		$this->db->where_in('mem_mem_no',$member_array);

		$record = $this->db->get();

		$result = $record->row();*/

		

		$final_str = 'Please check your revised admit card letter for JAIIB/DBF examination';

		

		foreach($sql as $rec){

			

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email'); 

			

			//echo "#". $rec['admitcard_image']."---//---".$email[0]['email'];

			//echo "<br/>";

			

			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];

			

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'ztest2500@gmail.com',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Exam Enrolment Acknowledgement',

							'message'=>$final_str

						);

						

			

			$files=array($attachpath);

			

			

			

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				$update_array = array('mail_send'=>'1');

				$this->master_model->updateRecord('admit_card_details_21_118',$update_array,array('admitcard_image'=>$rec['admitcard_image']));

				

				echo "Mail send to => ".$rec['mem_mem_no'];

				echo "<br/>"; 

			}

			

			

				

		}

	}

	

	public function chk_update_rec(){ 

		$update_data = array('pay_status' => '1');

		$this->master_model->updateRecord('member_exam',$update_data,array('id'=>'2090748'));	

	}

	

	public function april_invoice(){         

		$arr = array(900727199,900727341,900727563);

		for($i=0;$i<=2;$i++){

			echo $path = april_custom_genarate_exam_invoice($arr[$i]);

			echo "<br/>";

		}   

		 

		//echo $path = april_custom_genarate_exam_invoice('900725800');

	}

	

	public function custom_examinvoice_send_mail(){   

		$receipt_array = array(902389090);      

		$this->db->where_in('receipt_no',$receipt_array); 

		$sql = $this->master_model->getRecords('exam_invoice','','invoice_id,invoice_image,member_no,exam_code');

		

		$exam_name = $this->master_model->getRecords('exam_master',array('exam_code'=>$sql[0]['exam_code']),'description');

		

		$final_str = "Hello Sir/Madam"; 

		$final_str.= "<br/><br/>";

		$final_str.= 'Please check your invoice receipt for '.$exam_name[0]['description'].' exam registration.'; 

		//$final_str.= 'We acknowledge with thanks and receipt of the payment for Enrollment in Advance Management Program in Banking & Finance '; ;

		$final_str.= "<br/><br/>";

		$final_str.= "Regards,";

		$final_str.= "<br/>";

		$final_str.= "IIBF TEAM";

		

		foreach($sql as $rec){ 

			$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];

			//$attachpath = "uploads/ampinvoice/user/".$rec['invoice_image'];

			//$attachpath = "uploads/IIBF_ADMIT_CARD_510033421.pdf";

			echo $attachpath."<br/>";

			//$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 

			//echo ">>".$email[0]['email'];  

			//exit;

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'pawansing.pardeshi@esds.co.in',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Advanced Management Program in Banking & Finance',

							'message'=>$final_str

						);

						

			

			$files=array($attachpath);

			

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				

				echo "Mail send to => ".$rec['invoice_id'];

				echo "<br/>"; 

			}

			

		}

		

	}

	

	public function custom_gst_send_mail(){  

		$receipt_array = array(901337512,901337516,901338446,901338764,901339112,901339202,901339722,901339824,901339952,901340342,901340838,901341005,901341099,901341287,901341291,901341607,901341837);  

		$this->db->where_in('receipt_no',$receipt_array); 

		$sql = $this->master_model->getRecords('exam_invoice','','invoice_id,invoice_image,member_no');

		

		

		$final_str = "Dear Candidate";

		$final_str.= "<br/><br/>";

		$final_str.= 'This has reference to your exam application for BCBF examination scheduled to be held on 9-Feb-2019.';

		$final_str.= "<br/><br/>";

		$final_str.= 'While processing your exam Application form it was found that GST Amount was calculated less i.e. Rs.64/- instead of Rs.72/- due to technical issue.'; 

		$final_str.= "<br/><br/>";

		$final_str.= 'You where therefore requested to pay the difference of GST Amount of Rs.8/- (Rupees Eight Only) '; 

		$final_str.= "<br/><br/>";

		$final_str.= 'Even after repeated request via email and phone call you have not paid the difference of GST Amount, therefore, your BCBF exam application is cancelled / not processed and admit letter IS NOT issued.

'; 

		$final_str.= "<br/><br/>";

		$final_str.= 'Refund process for exam fees is initiated and the amount will be credited automatically to your Bank Account/Card (as per original method of payment) within 5 - 7 working days.'; 

		

		$final_str.= "<br/><br/>";

		$final_str.= 'Regards,'; 

		$final_str.= "<br/>";

		$final_str.= 'IIBF Team'; 

		

		foreach($sql as $rec){

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'pawansing.pardeshi@esds.co.in',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'BC/BF Examination dated 9-Feb-2019',

							'message'=>$final_str

						);

			if($this->Emailsending->mailsend($info_arr)){

				

				echo "Mail send to => ".$rec['member_no'];

				echo "<br/>"; 

			}else{

				echo "fail";	

			}

		}

	}

	

	public function caiib_eligible118(){ 

		$this->db->where('is_check','no');

		$this->db->limit(1000);

		$sql = $this->master_model->getRecords('eligible_master_60_21_apr_2018');

		foreach($sql as $rec){

			

			/*$this->db->where('exam_code','21');

			$this->db->where('eligible_period','118');

			$this->db->where('part_no',$rec['part_no']);

			$this->db->where('member_no',$rec['member_no']);

			$this->db->delete('eligible_master');*/

			

			

			$update_array = array('exam_code' => '21000');

			$this->master_model->updateRecord('eligible_master',$update_array,array('member_no'=>$rec['member_no'],'exam_code'=>21,'eligible_period'=>118,'part_no'=>$rec['part_no']));

			

			$update_data = array('is_check' => 'yes');

			$this->master_model->updateRecord('eligible_master_60_21_apr_2018',$update_data,array('member_no'=>$rec['member_no'],'exam_code'=>$rec['exam_code'],'eligible_period'=>$rec['eligible_period'],'part_no'=>$rec['part_no']));

			

			echo $rec['exam_code']."_".$rec['eligible_period']."_".$rec['part_no']."_".$rec['member_no']."<br/>";

			

			

			

		}

	}

	

    public function getadmitcardpdfjd_dummy(){

		//To Do-- validate as per admin admit card setting(Need to Do)

		try{

			

			

			

			$member_id = 510383894;

			$exam_code = 148;

			

			$this->db->select('center_code'); 

			$this->db->from('sify_center');

			$scenter = $this->db->get();

			$sifyresult = $scenter->result();

			foreach($sifyresult as $sifyresult){

				$sifycenter[] = $sifyresult->center_code;

			}

			

			

			$this->db->select('center_code'); 

			$this->db->from('nseit_center');

			$ncenter = $this->db->get();

			$nseitresult = $ncenter->result();

			foreach($nseitresult as $nseitresult){

				$nseitcenter[] = $nseitresult->center_code;

			}

			

			//$exam_code = base64_decode($this->uri->segment(3));

			$img_path = base_url()."uploads/photograph/";

			$sig_path =  base_url()."uploads/scansignature/";

			

			$this->db->select('admitcard_info.*');

			$this->db->from('admitcard_info');

			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));

			$record = $this->db->get();

			$result = $record->row();

			

			$this->db->select('*');

			$this->db->from('admitcard_info');

			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));

			$this->db->group_by('venueid');

			$this->db->order_by("date", "asc");

			$nrecord = $this->db->get();

			$results = $nrecord->result();

			

			/*echo $this->db->last_query();

			

			echo "<pre>";

			print_r($results);

			exit;*/

			

			if(in_array($result->center_code, $nseitcenter)){

				$vcenter = 'NSEIT';

			}

			if(in_array($result->center_code, $sifycenter)){

				$vcenter = 'SIFY';

			}

			

			//$exam_code = $result->exm_cd;

			

			$medium_code = $result->m_1;

			

			$this->db->select('description');

			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));

			$exam_result = $exam->row();

			

			

			//$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = RIGHT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");

			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");

			

			$subject_result = $subject->result();

			

			

			$pdate = $subject->result();

			foreach($pdate as $pdate){

				$exdate = $pdate->date;

				$examdate = explode("-",$exdate);

				$examdatearr[] = $examdate[1];

			}

			

			$exdate = $subject_result[0]->date;

			$examdate = explode("-",$exdate);

			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];

			//$printdate = 'May 2017';

			

			if($medium_code == 'ENGLISH' || $medium_code == 'E'){

				$medium_code_lng = 'E';

			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){

				$medium_code_lng = 'H';

			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){

				$medium_code_lng = 'A';

			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){

				$medium_code_lng = 'G';

			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){

				$medium_code_lng = 'K';

			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){

				$medium_code_lng = 'L';

			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){

				$medium_code_lng = 'M';

			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){

				$medium_code_lng = 'N';

			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){

				$medium_code_lng = 'O';

			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){

				$medium_code_lng = 'S';

			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){

				$medium_code_lng = 'T';

			}

			

			$this->db->select('medium_description');

			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));

			$medium_result = $medium->row();

			

			

			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id);

			

			//echo "<pre>";

			//print_r($data);

			

			//load the view and saved it into $html variable

			$html=$this->load->view('admitcardpdfjddummy', $data, true); 

			//this the the PDF filename that user will get to download

			//echo $html;

			//exit;

			$this->load->library('m_pdf');

			$pdf = $this->m_pdf->load();

			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";

			//generate the PDF from the given html

			$pdf->WriteHTML($html);

			//download it.

			$pdf->Output($pdfFilePath, "D");  

			

		}catch(Exception $e){

			echo $e->getMessage();

		}

	}

	

	public function custom_admitcard_pdf(){   

		echo $password = random_password();  

		exit;  

		$exam_code = $this->config->item('examCodeCaiib');     

		$exam_period = 219;   

		

		$this->db->limit('10');    

		$result=$this->master_model->getRecords('jaiib_pass_sub_copy',array('find_status'=>1));

		

		/*echo '<pre>';

		print_r($result);

		exit;*/

		

		           

		//echo $path = genarate_admitcard_custom('500213142',$exam_code,$exam_period);   

		//$member_array = array(500183353);        

		$arr_size = sizeof($result);  

		for($i=0;$i<=$arr_size;$i++){

			//echo $result[$i]['member_number'];

			//echo '<br/>'; 

			$path = genarate_admitcard_custom($result[$i]['member_number'],$exam_code,$exam_period,$result[$i]['id']);

			echo $path."<br/>"; 

		}

	}

	

	public function custom_admitcard_pdf_single(){     

		//echo 'hi';exit;

		$exam_code = 991;      

		$exam_period = 998;    

		$member_array = array('801567513','801566817');          

		$arr_size = sizeof($member_array);  

		for($i=0;$i<=$arr_size;$i++){

			echo $path = genarate_admitcard_custom_new($member_array[$i],$exam_code,$exam_period);  

			echo "<br/>"; 

		}

	}

	

	public function remote_custom_admitcard_pdf_single(){    

		//echo 'hi';exit;

		$exam_code = 1009;      

		$exam_period = 777;    

		$member_array = array(801553105);               

		$arr_size = sizeof($member_array);  

		for($i=0;$i<=$arr_size;$i++){

			echo $path = remote_genarate_admitcard_custom_new($member_array[$i],$exam_code,$exam_period);  

			echo "<br/>"; 

		}

	}

	

	public function naar_custom_admitcard_pdf_single(){    

		//echo 'hi';exit;

		$exam_code = 1015;       

		$exam_period = 7;    

		$member_array = array(801544776);                

		$arr_size = sizeof($member_array);  

		for($i=0;$i<=$arr_size;$i++){

			echo $path = naar_genarate_admitcard_bulk($member_array[$i],$exam_code,$exam_period);  

			echo "<br/>"; 

		}

	}

	

	

	public function remotedra__custom_admitcard_pdf_single(){    

		//echo 'hi';exit;

		$exam_code = 1015;      

		$exam_period = 7;    

		$member_array = array(801544733);               

		$arr_size = sizeof($member_array);  

		for($i=0;$i<=$arr_size;$i++){

			echo $path = remotedra_genarate_admitcard_custom_new($member_array[$i],$exam_code,$exam_period);  

			echo "<br/>"; 

		}

	}

	

	public function custom_bulkadmitcard_pdf(){ 

		$exam_code = 42;

		$exam_period = 119;

		$member_number = 510061968;

		echo $path = custome_genarate_admitcard_bulk($member_number,$exam_code,$exam_period);

		/*$member_array = array(801206513);

		$arr_size = sizeof($member_array);

		for($i=0;$i<=$arr_size;$i++){

			$path = custome_genarate_admitcard_bulk($member_array[$i],$exam_code,$exam_period);

			echo $path."<br/>";

		}*/

	}  

	

	public function custom_admitcardpdf_send_mail(){   //     

		$member_array = array(5321700,5321249);                

		//$member_array = array(510324238);    

		//$date_array = array('2019-03-23');  

		//$center_array = array(306);      

		$this->db->distinct('mem_mem_no');    

		$this->db->where('remark',1);

		$this->db->where('exm_cd',991);

		$this->db->where('exm_prd','998');

		//$this->db->where('free_paid_flg','F'); 

		//$this->db->where('record_source','Bulk');

		$this->db->where('admitcard_image !=','');

		//$this->db->where_in('center_code',$center_array);

		//$this->db->where_in('exam_date',$date_array);

		$this->db->where_in('mem_exam_id',$member_array);

		$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 

		    

		foreach($sql as $rec){ 

			

			$this->db->where('exam_code',$rec['exm_cd']);

			$exam_name = $this->master_model->getRecords('exam_master','','description');

			

			$final_str = 'Hello Sir/Madam <br/><br/>';

			$final_str.= 'Please check your  admit card letter for '.$exam_name[0]['description'].' examination';   

			$final_str.= '<br/><br/>';

			$final_str.= 'Regards,';

			$final_str.= '<br/>';

			$final_str.= 'IIBF TEAM'; 

			  

			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  

			//$attachpath = "uploads/IIBF_ADMIT_CARD_510360428.pdf";   

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   

			$info_arr=array('to'=>$email[0]['email'],

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Revised Admit Letter',

							'message'=>$final_str

						); 

			$files=array($attachpath);

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				echo "Mail send to ==> ".$rec['mem_mem_no'];

				echo "<br/>";  

			}

			

			

			

			

			/*$text='Due to issue in seat number allotment, revised admit letter is sent to your registered mail ID. You can also download it from your login/profile.'; 

			

			$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$email[0]['mobile']."&text=".urlencode($text)."&senderid=IIBFNM&route_id=2 &Unicode=1";

			$string = preg_replace('/\s+/', '', $url);

			$x = curl_init($string);

			curl_setopt($x, CURLOPT_HEADER, 0);	

			curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);

			curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			

			$reply = curl_exec($x);

			curl_close($x);*/

			

			

		}

	}

	

	public function custom_admitcardpdf_send_mail_old(){  //     

		$member_array = array(4904142);                

		//$member_array = array(510324238);    

		//$date_array = array('2019-03-23');  

		//$center_array = array(306);      

		$this->db->distinct('mem_mem_no');    

		$this->db->where('remark',1);

		$this->db->where('exm_prd','998'); 

		//$this->db->where('record_source','Bulk');

		$this->db->where('admitcard_image !=','');

		//$this->db->where_in('center_code',$center_array);

		//$this->db->where_in('exam_date',$date_array);

		$this->db->where_in('mem_exam_id',$member_array);

		$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 

		    

		foreach($sql as $rec){ 

			

			$this->db->where('exam_code',$rec['exm_cd']);

			$exam_name = $this->master_model->getRecords('exam_master','','description');

			

			$final_str = 'Hello Sir/Madam <br/><br/>';

			

			$final_str.= 'Please check your new attached admit card letter for '.$exam_name[0]['description'].' examination';   

			$final_str.= '<br/><br/>';

			$final_str.= 'Regards,';

			$final_str.= '<br/>';

			$final_str.= 'IIBF TEAM'; 

			  

			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  

			//$attachpath = "uploads/IIBF_ADMIT_CARD_510360428.pdf";   

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'chaitali.jadhav@esds.co.in',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Revised Admit Letter',

							'message'=>$final_str

						); 

			$files=array($attachpath);

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				echo "Mail send to ==> ".$rec['mem_mem_no'];

				echo "<br/>";  

			}

			

			

			

			

			

			

			

		}

	}

	

	public function matchdate(){

		$str_date='2018-08-06 21:45:00';

		//$live_date=date("Y-m-d H:i:s");

		$live_date='2018-08-06 21:45:01';

		

		echo "str_date>>>". $str_date;

		echo "<br/>";

		echo "live_date>>>". $live_date;

		echo "<br/>";

		

		if($live_date >= $str_date){

			echo "exam start";

		}else{

			echo "exam close";

		}

	}

	

	public function gst_recovery_email()

	{

		$attchpath_pdf = 'https://iibf.esdsconnect.com/uploads/Jammu_and_Kashmir_GST_Payment.pdf';

		$cand_arr = array('510306363','510124298','510343198','510055220','510147923','510292239','510148124','510131819','510220866','510344943','','510345081','510194910','510168831','510288985','801143032','510345888','510344325','510164759','510341877','510109038','510310366','510334748','510181243','510330518','510346041','510139726','510334888','510346074','510338429','510346116','510346126','510201343','510284413','510317568','510313228','510346170','510118002','200063623','500186362','510312313','510328996','510336614','500210628','500139866','510346239','510186789','510340100','510148143','500038368','510346306','510255286','500054287','510344745','510346356','510346355','510278988','510135158','510217336','510124926','500180446','510281690','510346461','510166551','510332994','510335207','200084731','510335126','510346655','500200980','510101602','510031347','500196295','510231585','510346725','510115356','510244052','510337099','510346333','500207963','510315377','510346856','510277957','510267063','801169071','510111696','510225896','510021137','510346985','510173161','510326031','510070101','510347075','500150204','510332490','510218692','510347144','510076828','510325495','500200186','700014094','510258257','510347303','510116787','510347376','500038892','500188851','510238255','510232963','500208561','200027547','510347502','510131638','510028446','510347523','510347460','510347562','510347501','510347466','510347469','510347474','510302436','510343817','500132373','510322948','510193330','510347651','510251399','510335853','510250437','510347720','510347081','510347753','510201405','510347755','510067024','500198997','510180472','510347857','510301080','510347843','510318079','500038896','510347955','510347940','510165135','510348013','510336775','510347908','510348030','510214772','510036956','510213165','510348177','510348208','510347961','510346777','510342146','500178837','510200536','510038862','500083243','510348299','510348337','510193620','500210557','510175659','510137077','510293436','510206333','510344409','500012087','510346295','510262667','500185028','510095259','510320376','510339187','510248328','500150763','510316077','510348673','510233281','510126878','510262431','510165017','510147247','510348139','510348746','510253219','510348781','510348770','510348795','510324409','510348432','510071485','510331584','801169653','510179251','500213575','510348710','510338981','510349011','510346573','510348697','510085974','510349100','510158073','510349210','510349212','510349239','510081236','510317782','510348154','510348807','510338700','510349295','510309318','510176297','510033238','510349352','510349365','510134040','510349376','510345848','510349459','510165378','510349412','510347597','510290641','510161540','500061365','510148190','500197952','510115785','510126745','510158553','500152157','510055332','500177312','510349774','510110742','510344652','510222973','510275542','510290580','510349984','510181809','510188182','500152726','510343345','500021894','510120110','510350187','500211445','500081958','510196103','510095224','510347262','510340767','510276385','510214936','510254505','510230456','510350700','510128654','510230107','510172714','510160160','510282207','500186668','510193581','510346700','510344484','510349195','510350907','510350896','500138134','510261307','500150220','5744660','510167739','510351073','510206600','510106656','510322639','510328710','510351216','510351156','510324187','510165997','510271410','510348532','510349400','510054621','510169198','510351361','510016200','510254443','510339198','510167518','510351565','510348706','510251908','510303108','510238932','510351611','510164724','510144088','510351796','510351869','500039779','510335658','510335400','510277176','510333760','500208833','510352059','510243438','500057467','510330368','510264575','510352098','510221577','510314924','510285620','510245657','510222587','200059961','510276173','510056134','500014311','510163897','510123544','510229523','510352371','510111731','510017951','510253907','510258560','500038386','510262712','510175667','510022907','500181161','500100530','510246289','510034934','500014813','510134300','510089286','510348600','500034041','510028176','510118520','510148002','510234874','510160213','510352658','510292141','510207009','510331508','510107848','510066917','510144164','510102721','510352345','510130733','500144067','500204204','510210813','510019448','510303228','510233947','510353013','510306311','510353033','510138878','510260772','510031007','500131099','510215937','510156543','6082934','510067275','510023565','510328163','500191929','500127155','510186043','510041275','510101194','510334075','510353505','510183280','500197053','510151676','510263993','510257672','510337973','510261406','510182391','510202205','510353708','510044142','510078357','510353751','510311065','500208716','510331293','500169116','510106700','510120557','510352715','510123983','510045134','510299884','510131542','510144374','500143786','510135904','6134208','510164950','510056605','510213030','510353951','510353979','510214495','500193654','510354036','200081452','510246840','510354083','510294894','510345390','510265545','510131162','510220700','510289830','510030184','510044334','510174241','510199797','510350260','510274195','510069966','510302665','500180538','510354397','510324743','510205729','510128415','500077133','500195355','510220474','510336120','510023389','500206409','510247425','510113789','510179711','510155453','510354627','510348801','500036855','510097534','510100526','500013583','510216147','500052626','510321123','510031808','510151622','510234480','6772014','510308328','510189272','510208526','500039850','510272847','510197303','510237205','510045942','510104681','510192420','510323755','510196328','510177120','510027929','510018614','500135781','500177132','510291789','510244930','7617460','510244936','510262598','510183321','510102644','500004092','500141128','500150396','510066707','510113398','500106526','500152116','400127234','510238826','510238826','500105605','510042922','510050680','510146981','510321042','510040931','510146610','510198152','510225439','510078889','510120314','500191570','510029874','500040171','510316108','510167371','510193945','510253601','500210571','200044092','510118456','510284351','510307821','500150642','510204617','510182714','500130130','510210697','500110311','510254067','510116174','510064602','510139841','500193401','500128234','510241395','510226567','510253780','510133156','500035288','510088566','400121339','500196270','500012338','500090748','500132377','510154168','510195391','500131057','510119101','510047447','500197206','510304735','500074330','510219364','510255000','510216516','510316441','510032701','510071597','510279575','510253748','510075525','500134754','500063241','500017673','500204339','510242452','500177367','510242699','510171360','510082178','510290658','500193576','510120434','510160067','510195548','510271641','510208135','510174509','510115887','510087906','510317157','510067024','510302344','510335053','510328600','500146420','801008333','510096885','801065315','5744660');

		if(count($cand_arr) > 0)

		{

			$cnt=1;

			$emailerstr=$this->master_model->getRecords('refund_email',array('emailer_name'=>'gst_recovery_email'));

			foreach($cand_arr as $reg_num)

			{

				$email_res = $this->master_model->getRecords('member_registration',array("regnumber"=>$reg_num),'email');

				if(count($email_res)>0)

				{

					//$email_res[0]['email'] anishrivastava@iibf.org.in

					$files_pdf=array($attchpath_pdf);

					

					$info_arr = array('to'=>$email_res[0]['email'],

					  'from'=>$emailerstr[0]['from'],

					  'subject'=>$emailerstr[0]['subject'],

					  'message'=>$emailerstr[0]['emailer_text']

					);

					

					if($this->Emailsending->mailsend_attch($info_arr,$files_pdf))

					{

						echo "<br>".$cnt.'='.$email_res[0]['email'].' '.'Application Number = '.$reg_num.'<br>';

						$cnt++;

					}

					

				}

			}

			echo "<br>mail sent";

		}

	}

	

	public function wrong_venue(){ 

		

		$member_array = array();

		$exam_array = array($this->config->item('examCodeCaiib'),'62','63','64','65','66','67','68','69','70','71','72');

		

		$this->db->where('status','0');

		$this->db->limit(20000, 0); 

		$member_detail = $this->master_model->getRecords('member_exam_replica');  

		

		/*echo $this->db->last_query(); 

		exit;

		echo "<pre>";

		print_r($member_detail );*/

		

		foreach($member_detail as $member_detail){

			$this->db->where('mem_mem_no',$member_detail['regnumber']);

			$this->db->where('remark','1');

			$this->db->where('exm_prd','218');

			$this->db->where_in('exm_cd',$exam_array);

			$admit_detail = $this->master_model->getRecords('admit_card_details');

			foreach($admit_detail as $admit_detail){

				

				$this->db->where('exam_date',$admit_detail['exam_date']);

				$this->db->where('center_code',$admit_detail['center_code']);

				$this->db->where('venue_code',$admit_detail['venueid']);

				$center_detail = $this->master_model->getRecords('venue_master','','venue_master_id');

				/*echo $this->db->last_query();

				echo "<br/>";

				echo $center_detail[0]['venue_master_id'];

				echo "<br/>";*/

				

				if($center_detail[0]['venue_master_id']==''){

					$member_array[] = $member_detail['regnumber'];

				}

			}

			$update_array = array('status'=>1);

			$this->master_model->updateRecord('member_exam_replica',$update_array,array('id'=>$member_detail['id']));	

		}

		$unique_arr = array_unique($member_array);

		echo "<pre>";

		print_r($unique_arr);

	}

	

	public function getpassword(){ 

		

		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

		$number = $this->uri->segment(3);

		$res = $this->master_model->getRecords('member_registration',array('regnumber'=>$number),'usrpassword');

		

		

		$key = $this->config->item('pass_key');

		$aes = new CryptAES();

		$aes->set_key(base64_decode($key));

		$aes->require_pkcs5();

		echo $decpass = $aes->decrypt(trim($res[0]['usrpassword']));

		

		//iibf.teamgrowth.net/dwnletter/getpassword/511000086

		

		

	}

	public function getpassword_arr(){ 

		

		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

		//$number = $this->uri->segment(3);

		$number = array(C09090,M20774,R17881,S47501,V07486,X00040);

		$this->db->where_in('regnumber',$number);

		$this->db->where('isactive','1');

		$this->db->order_by('regnumber','asc');

		$res = $this->master_model->getRecords('member_registration','','usrpassword,regnumber');

		//echo $this->db->last_query(); die;

		if(!empty($res))

		{

			foreach($res as $res)

			{

				$key = $this->config->item('pass_key');

				$aes = new CryptAES();

				$aes->set_key(base64_decode($key));

				$aes->require_pkcs5();

				 echo $decpass = $aes->decrypt(trim($res['usrpassword']));

				 $res['regnumber'].'==>'.$decpass ; 

				echo "<br/>"; 

			}

		}

	

		

	}

	public function generate_custom_blended_invoice(){

		$receipt_no = '901435554';

		$zone_code = 'CO';

		//$program_name = 'RISK IN FINANCIAL SERVICES';

		$program_name = 'CERTIFIED CREDIT OFFICER';

		$mem_gstin_no = '';

		

		echo $path = custom_genarate_blended_invoice($receipt_no,$zone_code,$program_name,$mem_gstin_no);

	}

	

	public function subject_missing_R(){            

		$member_array = array();

		$exarr = array(42,992); 

		//$exarr = array(21);

		$this->db->select('mem_mem_no');  

		$this->db->distinct('mem_mem_no');  

		$this->db->where_in('exm_cd',$exarr); 

		$this->db->where('exm_prd',120);

		$this->db->where('remark','1');

		//$this->db->where('mem_mem_no',510331312);

		$this->db->where('created_on >= ','2020-03-30 00:00:00');   

		$this->db->where('created_on <= ','2020-03-30 23:59:59');         

		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd');   

		

		foreach($admit_card as $member_no){ 

			$app_arr = array('R');

			$this->db->where('member_no',$member_no['mem_mem_no']);

			$this->db->where_in('exam_code',$exarr);

			$this->db->where('eligible_period',120);

			$this->db->where('app_category','R'); 

			$member_rec = $this->master_model->getRecords('eligible_master','','id');

			$member_rec_cnt = count($member_rec);

			

			if($member_rec_cnt != 0){

				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);

				$this->db->where_in('exm_cd',$exarr);

				$this->db->where('exm_prd',120);

				$this->db->where('remark','1');

				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');

				$admit_card_cnt = count($admit_card);

				

				if($member_no['exm_cd'] != 992){

					if($admit_card_cnt != 3){

						$member_array[] = $member_no['mem_mem_no'];

					}

				}elseif($member_no['exm_cd'] == 992){

					if($admit_card_cnt != 2){

						$member_array[] = $member_no['mem_mem_no'];

					}

				}

				

			}

		} 

		

		echo "<pre>";

		print_r($member_array);

	}

	

	public function subject_missing_F(){ 

		$member_array = array();

		$exarr = array(42,992); 

		//$exarr = array(21);  

		$this->db->select('mem_mem_no'); 

		$this->db->distinct('mem_mem_no');

		$this->db->where_in('exm_cd',$exarr);

		$this->db->where('exm_prd',120); 

		//$this->db->where('mem_mem_no',510402098); 

		$this->db->where('remark','1');

		$this->db->where('created_on >= ','2020-03-30 00:00:00');         

		$this->db->where('created_on <= ','2020-03-30 23:59:59');  

		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd');  

		

		foreach($admit_card as $member_no){

			$app_arr = array('F');

			$this->db->where('member_no',$member_no['mem_mem_no']);

			$this->db->where_in('exam_code',$exarr);

			$this->db->where('eligible_period',120);

			$this->db->where_in('app_category',$app_arr); 

			$member_rec = $this->master_model->getRecords('eligible_master','','id');

			$member_rec_cnt = count($member_rec);

			

			if($member_rec_cnt != 0){ 

				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);

				$this->db->where_in('exm_cd',$exarr);

				$this->db->where('exm_prd',120);

				$this->db->where('remark','1');

				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');

				$admit_card_cnt = count($admit_card);

				

				if($member_no['exm_cd'] != 992){

					if($member_rec_cnt != $admit_card_cnt){

						$member_array[] = $member_no['mem_mem_no'];

					}

				}elseif($member_no['exm_cd'] == 992){

					if($admit_card_cnt != 2){

						$member_array[] = $member_no['mem_mem_no'];

					}

				}

			

			}

		}

		

		echo "<pre>";

		print_r($member_array);

	}

	

	public function subject_missing_fresh(){

		$member_array = array();

		$exarr = array(42,992); 

		 //$exarr = array(21);  

		$this->db->select('mem_mem_no'); 

		$this->db->distinct('mem_mem_no');

		$this->db->where_in('exm_cd',$exarr);

		$this->db->where('exm_prd',120); 

		//$this->db->where('mem_mem_no','510453280'); 

		$this->db->where('remark','1');

		$this->db->where('created_on >= ','2020-03-30 00:00:00');        

		$this->db->where('created_on <= ','2020-03-30 23:59:59');    

		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd');

		

		foreach($admit_card as $member_no){

			$this->db->where('member_no',$member_no['mem_mem_no']);

			$this->db->where_in('exam_code',$exarr);

			$this->db->where('eligible_period',120);

			$member_rec = $this->master_model->getRecords('eligible_master','','id');

			$member_rec_cnt = count($member_rec);

			

			if($member_rec_cnt <= 0){ 

				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);

				$this->db->where_in('exm_cd',$exarr);

				$this->db->where('exm_prd',120);

				$this->db->where('remark','1');

				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');

				$admit_card_cnt = count($admit_card);

				

				

				

				if($member_no['exm_cd'] != 992){

					if($admit_card_cnt != 3){

						$member_array[] = $member_no['mem_mem_no'];

					}

				}elseif($member_no['exm_cd'] == 992){

					if($admit_card_cnt != 2){

						$member_array[] = $member_no['mem_mem_no'];

					}

				}

				

				

			}

		}

		echo "<pre>";

		print_r($member_array);  

	}

	

	public function member_exam_not_update(){

		

		$exam_arr = array();

		$exarr = array(21,42);

		$this->db->select('mem_exam_id');

		$this->db->distinct('mem_exam_id');

		//$this->db->where('exm_cd',21);

		$this->db->where_in('exm_cd',$exarr);

		$this->db->where('exm_prd',119);

		$this->db->where('remark','1');

		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_exam_id');

		

		foreach($admit_card as $admit_card_rec){

			

			$this->db->where('id',$admit_card_rec['mem_exam_id']);

			$exam = $this->master_model->getRecords('member_exam','','pay_status');

			

			if($exam[0]['pay_status'] != 1){

				$exam_arr[] = $admit_card_rec['mem_exam_id'];

			}

			

		}

		

		echo "<pre>";

		print_r($exam_arr);

	}

	

	public function remark_not_update(){

		

		$exam_arr = array();

		$exarr = array(21,42);

		$this->db->select('id');

		//$this->db->where('exam_code',21);

		$this->db->where_in('exam_code',$exarr);

		$this->db->where('exam_period',119);

		$this->db->where('pay_status','1');

		$exam = $this->master_model->getRecords('member_exam','','id');

		

		foreach($exam as $exam_rec){

			

			$this->db->where('mem_exam_id',$exam_rec['id']);

			//$this->db->where('exm_cd',21);

			$this->db->where_in('exm_cd',$exarr);

			$this->db->where('exm_prd',119);

			$admit_card = $this->master_model->getRecords('admit_card_details','','remark');

			

			if($admit_card[0]['remark'] == 2){

				$exam_arr[] = $exam_rec['id'];

			}

			

		}

		

		echo "<pre>";

		print_r($exam_arr);

		

	}

	

	public function disa_invoice_update(){

		$receipt_array = array(901385366,901385381,901385815,901385923,901386505,901386734,901387514,901387553,901387855,901393303,901393749,901394433,901397342,901397572);

		

		$update_data = array();

		foreach($receipt_array as $receipt){

			

			$payment = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt),'transaction_no,ref_id'); 

			

			$exam = $this->master_model->getRecords('member_exam',array('id'=>$payment[0]['ref_id']),'modified_on');

			

			$invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt),'invoice_id');

			

			$update_data = array(

								'transaction_no' =>$payment[0]['transaction_no'],

								'date_of_invoice' =>$exam[0]['modified_on'],

								'modified_on'=>$exam[0]['modified_on'],

							);

			

			

			$this->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice[0]['invoice_id']));	

			

			echo "Invoice ID.".$invoice[0]['invoice_id'];

			echo "<br/>";

			

			

		}

	}

	

	public function custom_generate_disa_invoice_no(){

		$receipt_array = array(901385381,901385815,901385923,901386505,901386734,901387514,901387553,901387855,901393303,901393749,901394433,901397342,901397572);  

		 

		$insert_array = array(); 

		$update_data = array(); 

		$invoice_no = '';

		$invoice_image = ''; 

		foreach($receipt_array as $receipt){

			$invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt),'invoice_id,member_no');

			$insert_array = array('invoice_id'=>$invoice[0]['invoice_id']);

			$last_id = $this->master_model->insertRecord('config_DISA_invoice',$insert_array,true);

			

			//$invoice_no = "EDC/18-19/0".$last_id;

			//$invoice_image = $invoice[0]['member_no']."_EDC_18-19_0".$last_id.".jpg";

			

			/*echo $invoice_no;

			echo "<br/>";

			echo $invoice_image;

			echo "<br/>";*/

			

			$update_data = array('invoice_no'=>$invoice_no,'invoice_image'=>$invoice_image);

			

			/*echo "<pre>";

			print_r($update_data);

			echo "<br/>";*/ 

			

			

			$this->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice[0]['invoice_id']));

			

			echo "Invoice ID.".$invoice[0]['invoice_id'];

			echo "<br/>";

			

		}

	}

	

	public function test(){

		echo date("H:i:s");

		

		$arr = array(801357120,510108711,801480917,801413578,801480641,510225552,510406158,510451290,510360802,510457984,510025172,510157702,510453788,7220070,801477839,510220111,510255357,801477830,510045557,510417405,801474613,801474564,500174961,510310823,510347122,510266876,510429625,510264237,510373512,801473221,510237775,510449455,510441974,801472953,500146071,510414237,510444270,801312813,510386861,510106710,510208425,500181983,801471985,801471981,510187115,510235302,801162935,801471532,510251012,510407634,510424252,510275653,500177383,510061753,510207080,510405356,510436579,510106548,510271705,510172108,510427437,510080551,510359169,510385599,801470348,510220194,510151661,510453582,510041758,801469977,510390447,510399529,510293056,510174220,500177617,510429007,510402258,510155054,510424067,500068021,510115410,500128537,510187967,510304316,510306661,510428909,510427881,510429682,510430051,510384087,510384188,510450732);

		

		$ex_arr = array(1002,1003,1004);

		$this->db->where_in('regnumber',$arr);

		$this->db->where_in('exam_code',$ex_arr);

		$this->db->where('pay_status',1);

		$getdate_details=$this->master_model->getRecords('member_exam','','id');

		

		echo '>> '.count($getdate_details);

		

		//echo "###". CI_VERSION;

		//exit;

		//echo $system_date = date("Y-m-d H:i:s");

		

	}

	

	

	public function fee_mismatch(){

		$exmcd = array(21,42);

		$this->db->where('remark',1);

		$this->db->where('exm_prd',119);

		$this->db->where('created_on >= ','2019-04-06 00:00:00'); 

		$this->db->where('created_on <= ','2019-04-07 23:59:59');

		$this->db->where_in('exm_cd',$exmcd);

		$member = $this->master_model->getRecords('admit_card_details','','mem_mem_no');

		$mismatch = array();

		foreach($member as $member){

			$this->db->where('eligible_period',119);

			$this->db->where('member_no',$member['mem_mem_no']);

			$this->db->where_in('exam_code',$exmcd);

			$eligible = $this->master_model->getRecords('eligible_master','','app_category,member_type');

			

			$this->db->where('member_category',$eligible['member_type']);

			$this->db->where('group_code',$eligible['app_category']);

			$this->db->where_in('exam_code',$exmcd);

			$fee = $this->master_model->getRecords('fee_master','','igst_tot');

			

			$this->db->where('regnumber',$member['mem_mem_no']);

			$this->db->where('exam_period',119);

			$this->db->where('pay_status',1);

			$this->db->where_in('exam_code',$exmcd);

			$exam = $this->master_model->getRecords('member_exam','','exam_fee');

			if($fee['igst_tot'] != $exam['exam_fee']){

				$mismatch[] = $member['mem_mem_no'];

			}

		}

		echo "<pre>";

		print_r($mismatch);

	}

	

	

	public function custom_exam_invoice_new_design(){  

		

/*		$this->db->where('gen_flag',0);

		$this->db->limit(50,0);

		$aug_invoice_gen = $this->master_model->getRecords('aug_invoice_gen');

		foreach($aug_invoice_gen as $res){

			echo $path = custom_genarate_exam_invoice_newdesign($res['invoice_id']);

		 	echo "<br/>";

		}*/

	  

		

		$invoice_id = 2578281;  

		echo $path = custom_genarate_exam_invoice_newdesign($invoice_id);

		

		 /*$arr = array(1812565,1812859,1815411,1815581,1817613,1818117,1818233,1818234,1820036);    

		 for($i=0;$i<=8;$i++){

		 	echo $path = custom_genarate_exam_invoice_newdesign($arr[$i]);

		 	echo "<br/>"; 

		 }*/

		

	}

	public function custom_exam_invoice_new_design_swati(){     
		 $arr = array(2924455); // add invoice id 
		 for($i=0;$i<sizeof($arr);$i++){
		 	echo $path = custom_genarate_exam_invoice_newdesign($arr[$i]);
		 	echo "<br/>"; 
		 } 
	}
	///usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_data_gen custom_exam_invoice_chaitali
	public function custom_exam_invoice_chaitali(){     

		 $arr = array('2746832'); // add invoice id 

		 for($i=0;$i<sizeof($arr);$i++){

		 	echo $path = custom_genarate_exam_invoice_newdesign($arr[$i]);

		 	echo "<br/>"; 

		 } 

	}

	public function custom_examinvoice_send_mail_temp(){ 

	$this->db->where('email_send', 0);

		$this->db->limit(20,0);

		$record = $this->master_model->getRecords('generate_new_invocie');

		

		if(count($record) > 0){

		foreach($record as $res){

		   

		$this->db->where_in('invoice_id',$res['invoice_id']); 

		$sql = $this->master_model->getRecords('exam_invoice','','invoice_id,invoice_image,member_no,exam_code');

		

		$exam_name = $this->master_model->getRecords('exam_master',array('exam_code'=>$sql[0]['exam_code']),'description');

		

		$final_str = "Hello Sir/Madam"; 

		$final_str.= "<br/><br/>";

		$final_str.= 'Please check your invoice receipt for '.$exam_name[0]['description'].' exam registration.'; 

		$final_str.= "<br/><br/>";

		$final_str.= "Regards,";

		$final_str.= "<br/>";

		$final_str.= "IIBF TEAM";

		

		foreach($sql as $rec){ 

			$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];

			//$attachpath = "uploads/IIBF_ADMIT_CARD_510033421.pdf";

			echo $attachpath."<br/>";

			//$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 

			//echo ">>".$email[0]['email'];  

			//exit;

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'swati.watpade@esds.co.in',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Exam Enrollment Acknowledgement',

							'message'=>$final_str

						);

						

			

			$files=array($attachpath);

			

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				

				echo "Mail send to => ".$rec['invoice_id'];

				echo "<br/>"; 

				$update_data = array('email_send'=> 1 );

			$this->master_model->updateRecord('generate_new_invocie',$update_data,array('id'=>$res['id']));	

			

			}

			

		}

	}

}else{

		 	echo "all Done.stop";

		 } 

		

}

     public function custom_exam_invoice_new_design_temp(){  

		$this->db->where('image_status', 0);

		$this->db->limit(20,0);

		$record = $this->master_model->getRecords('generate_new_invocie');

		

		if(count($record) > 0){

		foreach($record as $res){

			

			echo $path = custom_genarate_exam_invoice_newdesign_temp($res['invoice_id']);

		 	echo "<br/>"; 

		 	$update_data = array('image_status'=> 1 ,

		 		'path' => $path);

			$this->master_model->updateRecord('generate_new_invocie',$update_data,array('id'=>$res['id']));	

			 

		 	

		}

		 }else{

		 	echo "all Done.stop";

		 }    

		

	  }

public function swati_data_insert_gst_invocie(){

		$arr=array(2121558,2121722,2121761,2121763,2121912,2122037,2122106,2122116,2122118,2122181,2122230,2122232,2122244,2122252,2122321,2122340,2122368,2122400,2122457,2122473,2122475,2122518,2122753,2122979,2123007,2123060,2123061,2123121,2123155,2123198,2123375,2123435,2123475,2123562,2123578,2123649,2123650,2123664,2123668,2123679,2123718,2123746,2123802,2123819,2123820,2123848,2123864,2123947,2124004,2124042,2124076,2124091,2124184,2124289,2124325,2124334,2124361,2124388,2124404,2124406,2124422,2124512,2124673,2124933,2124963,2124974,2125027,2125050,2125211,2125281,2125387,2125503,2125678,2125681,2125751,2125826,2125844,2125931,2125945,2125961,2125973,2125974,2126099,2126172,2126173,2126176,2126250,2126314,2126345,2126367,2126371,2126412,2126438,2126589,2126942,2126951,2127047,2127062,2127069,2127129,2127134,2127141,2127150,2127156,2127403,2127433,2127580,2127646,2127706,2127716,2127905,2127938,2128116,2128161,2128164,2128499,2128616,2128626,2128794,2128924,2128936,2129029,2129120,2129156,2129312,2129339,2129394,2129452,2129662,2129724,2129885,2129913,2129954,2129967,2129987,2130027,2130028,2130035,2130041,2130127,2130151,2130163,2130242,2130250,2130295,2130431,2130625,2130664,2130685,2130718,2131116,2131179,2131226,2131301,2131428,2131459,2131611,2131627,2131697,2132069,2132162,2132560,2132618,2132675,2132877,2132945,2132966,2133123,2133145,2133195,2133248,2133335,2133482,2133498,2133779,2133829,2133853,2133865,2134056,2134134,2134147,2134262,2134265,2134273,2134308,2134530,2134822,2134918,2135037,2135158,2135224,2135261,2135392,2135552,2135567,2135603,2135846,2135858,2135900,2135912,2135951,2135959,2135968,2135996,2136283,2136341,2136342,2136366,2136744,2136898,2136948,2136998,2137110,2137125,2137233,2137607,2137667,2137725,2137758,2137804,2137824,2137840,2137910,2137926,2137956,2137991,2137993,2138015,2138047,2138048,2138110,2138459,2139050,2139168,2139248,2139625,2139909,2140186,2140318,2140483,2140489,2140513,2140555,2140617,2140990,2141129,2141294,2141579,2141606,2141847,2141889,2141914,2141926,2142213,2142216,2142268,2142301,2142306,2142469,2143162,2143209,2143218,2143224,2143227,2143270,2143482,2143559,2143758,2143783,2143797,2143813,2143830,2143845,2143928,2144013,2144054,2144121,2144141,2144159,2144193,2144298,2144301,2144306,2144327,2144339,2145874,2147573,2148787,2149999,2150223,2151490,2152474,2154669,2157208,2157381,2159345,2160275,2160915,2161009,2161441,2163703,2164026,2164231,2166547,2166795,2167211,2169290,2170660,2171191);

		

		 for($i=0;$i<sizeof($arr);$i++){

		 	$insert_arr = array(

								'invoice_id' => $arr[$i],

							);

		 	$this->master_model->insertRecord('generate_new_invocie',$insert_arr,true);

		 }

		

	}

	public function custom_contactclass_invoice_new_design_jaiib(){  

		

		

		echo $path = genarate_contact_classes_invoice_custome_new(2736776,3264);

		 	echo "<br/>"; 

		 

		

	}
	
public function custom_contactclass_invoice_new_design_caiib(){  

		

		

		echo $path = genarate_contact_classes_invoice_custome_new_caiib(2737459,3276);

		 	echo "<br/>"; 

		 

		

	}



	//dra invoice generate swati

	public function custom_draexaminvoice_newdesign(){ 

		

		/*$arr = array(1774284); 

		for($i=0;$i<=0;$i++){

			echo $path = custom_genarate_draexam_invoice_new_design($arr[$i]);

			echo "<br/>"; 

		}*/

		$invoice_id = ''; //2611791;

		echo $path = custom_genarate_draexam_invoice_new_design($invoice_id);

	}

	

	//dra invoice generate swati

	public function custom_draexaminvoice_newdesign_swati(){ 

		

		/*$arr = array(1774284); 

		for($i=0;$i<=0;$i++){

			echo $path = custom_genarate_draexam_invoice_new_design($arr[$i]);

			echo "<br/>"; 

		}*/

		$invoice_id = 1811409;

		echo $path = custom_genarate_draexam_invoice_new_design_swati($invoice_id);

	}

	public function custome_bulk_invoice_new_design(){

		echo $attach = custome_generate_bulk_examinvoice_new_design(281); 	

	}

    public function custom_dra_acc_invoice_newdesign(){ 

		//$invoice_id = 1664150;

		//echo $path = custom_genarate_reg_invoice_new($invoice_id);

		

			$arr = array(2405594);   

		for($i=0;$i<=0;$i++){

			echo $path = custom_genarate_dra_acc_invoice_newdesign($arr[$i]);

			echo "<br/>"; 

		}

		

	}	

	public function custom_dra_renew_invoice($invoice_id){ 

		//$invoice_id = 1600455;

		echo $path = custom_genarate_agnecy_renewal_invoice($invoice_id);

	}

	

	public function custom_mem_reg_invoice_new(){   

		// $invoice_id = 1835819;

		// echo $path = custom_genarate_reg_invoice_new($invoice_id);   

		//,,,

		

		$arr = array('2639014','2639162','2641275','2638681','2639013','2639047','2639138','2639192');      

		 for($i=0;$i<sizeof($arr);$i++){

		 	echo $path = custom_genarate_reg_invoice_new($arr[$i]);

		 	echo "<br/>"; 

		 }

	}

	// Dwnletter/fu_genarate_amp_invoice_custom

	public function fu_genarate_amp_invoice_custom(){ 

		$invoice_id = 2608942;

		echo $path = genarate_amp_invoice_custom1($invoice_id);

	}

	public function new_blended_invoice_custom(){ 

		

		$invoice_no = array('2523010');

		$zone_code = 'CO';

		$program_name = 'CERTIFIED BANKING COMPLIANCE PROFESSIONAL';

		//$mem_gstin_no = '36AABCT5589K1ZQ';

		for($i=0;$i<count($invoice_no);$i++){

		echo $path = genarate_blended_invoice_custom_new($invoice_no[$i],$zone_code,$program_name);

		}

		

	}

	

	public function custom_elearning_invoice(){

		$invoice_id = 2640320;

		echo $path =  custom_genarate_elearning_exam_invoice($invoice_id);

	}

	

	public function custom_dra_center_invoice(){

		

		$invoice_id = 2196652;

		echo $path = custom_genarate_dra_invoice($invoice_id);

	}

	public function un_wallet()

	{

		error_reporting(E_ALL);

		$query="`gateway` LIKE 'wallet' AND `data` LIKE '%Successful%' ";

		$this->db->where($query);

		$result=$this->master_model->getRecords('paymentlogs');

		$trans_arr=array();

		foreach($result as $row)

		{

			//echo  ($row['data']).'<br>';

			$string=str_replace("encData=","",$row['data']);

			 $array= unserialize($string);

			 $trans_arr[]=$array['csc_txn'];

		}

		 //echo '<pre>';

		 //print_r($trans_arr);

		 echo $str = implode(',',$trans_arr);

	}

	public function update_csc(){

		$arr = array(9152161221679747,9153114422893936,9153122923006616,9153181523737895,9153183623784424,9153184123796004,9153190823854282,9153224224200222,9154202726147355,9154205726193565,9154210226201142,9155090126431953,9155092026461114,9155114726862355,9155151127457012,9155171627795606,9155191228114752,9155193728177023,9155215128378715,9155233928431654,9156120628968851,9156135929240601,9156141929276512,9156173729641583,9156180629706307,9156192929833127,9156211629965276,9156214429985345,9156221220001164,9156220829999544,9156221420001887,9156224220011455,9157100420274626,9157111420472032,9157120820650987,9157131120850445,9157130920845781,9157143021052405,9157174821540315,9157182921644911,9157191521758027,9157191721762131,9157200421865321,9158075522109727,9158101822322757,9158113922568181,9158120222635971,9158124022762077,9158132022879157,9158133522920587,9158134122936801,9158134822956284,9158135022961022,9158161423314422,9158183623714104,9158211524021942,9158213224039313,9159075624165836,9159114924668676,9159134224966853,9159134624977012,9159140725026212,9159141825048884,9159143125076836,9159151025158675,9159151525171031,9159154625238433,9159154925245234,9159164025364483,9159174225524752,9159174825538514,9159175225549652,9159182525631157,9159184525677735,9160090126093557,9160091626107044,9160092526115225,9160113026282942,9160114126306531,9160120326352187,9160121626378641,9160124526434135,9160131226480312,9160133726517732,9160134126524775,9160171226797813,9160203027094076,9160204527109737,9160205927121731,9161101327471042,9161111127666895,9161130528012424,9161144428303901,9161162928598764,9161171228732983,9161172228764966,9161174428838602,9161175828883343,9161182228956737,9161212029358025,9161214329382282,9162104829774253,9162110129820106,9162122920160573,9162165920974637,9162171621027891,9162181321201642,9162182021224063,9162182121226724,9162192621425841,9162201521549583,9162205221619854,9162211521656801,9163162623309714,9163212824010897,9163212924011366,9163230324080645,9163231724086173,9164121924820095,9164125724940664,9164164825556611); 

		

		$size = sizeof($arr);

		for($i=0;$i<=$size;$i++){

			$trim_tran = substr($arr[$i], 0, -1);

			

			$update_data = array('transaction_no'=>$arr[$i]);

			$this->master_model->updateRecord('exam_invoice',$update_data,array('transaction_no'=>$trim_tran));	

			echo $this->db->last_query();

			echo '<br/>';

			

			

		}

	}

  //By swati for update transection no of DRA exam invoice 

	public function update_transection(){

		$transection = $this->db->query("SELECT exam_invoice.`invoice_id`, exam_invoice.`pay_txn_id`, exam_invoice.`receipt_no`, exam_invoice.`transaction_no`, exam_invoice.`invoice_no`,dra_payment_transaction.`UTR_no` FROM `exam_invoice`,dra_payment_transaction WHERE  exam_invoice.receipt_no = dra_payment_transaction.receipt_no AND exam_invoice.pay_txn_id = dra_payment_transaction.id AND exam_invoice.`invoice_no` != '' AND exam_invoice.transaction_no = '' AND exam_invoice.`app_type` LIKE 'I' AND DATE(exam_invoice.`date_of_invoice`) BETWEEN '2019-04-01' AND '2019-07-31' ORDER By exam_invoice.receipt_no ASC"); 

                $transection = $transection->result_array();

				

				// Get Group Code

				if(count($transection)>0)

				{

						foreach ($transection as $key) {

							$update_data = array('transaction_no'=>$key['UTR_no']);

							$a=$this->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$key['invoice_id']));	

							if($a){

								echo 'Invoice_id = '.$key['invoice_id'].'<br>';

							}

							else{

								echo "Fail";

							}

						}

				}else{

					echo "no data";

				}

	}

	

	public function dynamic_invoice_generation(){ 

		$complete_arr = array(); 

		$r_cat = array();

		$aug_insert = array();

		$receipt_no_arr = array(901850514);    

		$sizearr = sizeof($receipt_no_arr);

		for($i=0;$i<$sizearr;$i++){

			$payment = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no_arr[$i]),'member_regnumber,transaction_no,id,amount,ref_id,date,receipt_no');

			

			$member = $this->master_model->getRecords('member_exam',array('id'=>$payment[0]['ref_id']),'id,exam_code,exam_period,exam_center_code,created_on,modified_on');

			

			$registration = $this->master_model->getRecords('member_registration',array('regnumber'=>$payment[0]['member_regnumber']),'registrationtype');

			

			$this->db->where('exam_code',$member[0]['exam_code']);

			$this->db->where('eligible_period',$member[0]['exam_period']);

			$this->db->where('member_no',$payment[0]['member_regnumber']);

			$eligible = $this->master_model->getRecords('eligible_master','','app_category');

			/*echo '<pre>';

			print_r($eligible);

			echo '<br/>';*/

			

			if($eligible){

				if($eligible[0]['app_category'] == 'R'){

					$this->db->where('group_code','B1_1');

				}else{

					$this->db->where('group_code',$eligible[0]['app_category']);

				}

			}else{

				$this->db->where('group_code','B1_1');

			}

			$this->db->where('exam_code',$member[0]['exam_code']);

			$this->db->where('exam_period',$member[0]['exam_period']);

			$this->db->where('member_category',$registration[0]['registrationtype']);

			$ex = explode(" ",$payment[0]['date']);

			$pay_date=$ex[0];

			$this->db->where("'$pay_date' BETWEEN fr_date AND to_date");

			$fee = $this->master_model->getRecords('fee_master_219','','fee_amount,sgst_amt,cgst_amt,igst_amt,cs_tot,igst_tot');

			

			/*echo '>>>'. $this->db->last_query();

			echo '<pre>';

			print_r($fee);*/

			//exit;

			

			$this->db->where('exam_name',$member[0]['exam_code']);

			$this->db->where('exam_period',$member[0]['exam_period']);

			$this->db->where('center_code',$member[0]['exam_center_code']);

			$center = $this->master_model->getRecords('center_master','','center_name,state_code,state_description');

			

			//echo $this->db->last_query();

			//echo '<br/>';

			

			$state = $this->master_model->getRecords('state_master',array('state_code'=>$center[0]['state_code']),'state_no,exempt');

			

			/*echo $this->db->last_query();

			echo '<br/>';

			echo '>>'. $state[0]['state_no'];

			echo '<br/>';*/

			

			if($state[0]['state_no'] == 27){

				$cgst_rate = 9.00;

				$cgst_amt = $fee[0]['cgst_amt'];

				$sgst_rate = 9.00;

				$sgst_amt = $fee[0]['sgst_amt'];

				$cs_total = $fee[0]['cs_tot'];

				$igst_rate = 0.00;

				$igst_amt = 0.00;

				$igst_total = 0.00;

				$disc_rate = 0.00;

				$disc_amt = 0.00;

				$tds_amt = 0.00;

				$tax_type = 'Intra';

			}else{

				$cgst_rate = 0.00;

				$cgst_amt = 0.00;

				$sgst_rate = 0.00;

				$sgst_amt = 0.00;

				$cs_total = 0.00;

				$igst_rate = 18.00;

				$igst_amt = $fee[0]['igst_amt'];

				$igst_total = $fee[0]['igst_tot'];

				$disc_rate = 0.00;

				$disc_amt = 0.00;

				$tds_amt = 0.00;

				$tax_type = 'Inter';

			}

			

			$insert_arr = array(

								'exam_code' => $member[0]['exam_code'],

								'exam_period' => $member[0]['exam_period'],

								'center_code' => $member[0]['exam_center_code'],

								'center_name' => $center[0]['center_name'],

								'state_of_center' => $center[0]['state_code'],

								'member_no' => $payment[0]['member_regnumber'],

								'pay_txn_id' => $payment[0]['id'],

								'receipt_no' => $payment[0]['receipt_no'],

								'transaction_no' => $payment[0]['transaction_no'],

								'gstin_no' => '',

								'service_code' => 999294,

								'qty' => 1,

								'fresh_fee' => 0.00,

								'rep_fee' => 0.00,

								'fresh_count' => 0,

								'rep_count' => 0,

								'cess' => 0.00,

								'institute_code' => 0,

								'institute_name' => '',

								'state_code' => $state[0]['state_no'],

								'state_name' => $center[0]['state_description'],

								'invoice_no' => '',

								'invoice_image' => '',

								'fee_amt' => $fee[0]['fee_amount'],

								'cgst_rate' => $cgst_rate,

								'cgst_amt' => $cgst_amt,

								'sgst_rate' => $sgst_rate,

								'sgst_amt' => $sgst_amt,

								'cs_total' => $cs_total,

								'igst_rate' => $igst_rate,

								'igst_amt' => $igst_amt,

								'igst_total' => $igst_total,

								'disc_rate' => $disc_rate,

								'disc_amt' => $disc_amt,

								'tds_amt' => $tds_amt,

								'date_of_invoice' => $member[0]['modified_on'],

								'created_on' => $member[0]['created_on'],

								'modified_on' => $member[0]['modified_on'],

								'tax_type' => $tax_type,

								'app_type' => 'O',

								'exempt' => $state[0]['exempt']

								);

			/*echo '<pre>';

			print_r($insert_arr);

			echo '<br/>';

			exit; */

			

			$exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no_arr[$i]),'invoice_id'); 

			

			/*echo 'here'.$eligible[0]['app_category'];

			exit;*/

			

			if(count($eligible) > 0){

			

			if($eligible[0]['app_category']!=''){

				if($exam_invoice[0]['invoice_id'] == ''){

					$last_id = $this->master_model->insertRecord('exam_invoice',$insert_arr,true);

					if($last_id > 0){

						$config_inset_arr = array(

													'invoice_id' => $last_id,

													'created_date' => $member[0]['modified_on']

												);

						$config_last_id = $this->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true);

						//$invoice_no = 'EX/19-20/'.$config_last_id;

						//$invoice_image = $payment[0]['member_regnumber'].'_EX_21-22_'.$config_last_id.'.jpg';

						$update_arr = array(

											'invoice_no' => $invoice_no,

											'invoice_image' => $invoice_image

										);

						$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$last_id));

					}

				}else{

					echo $payment[0]['receipt_no'].'Dupicate entry';

					echo '<br/>'; 

				}

			}else{

					$r_cat[] = $payment[0]['receipt_no']; 

					$aug_insert = array(

										'receipt_no'=>$receipt_no_arr[$i],

										'exm_cd'=>$member[0]['exam_code']

										);

					$this->master_model->insertRecord('aug_invoice',$aug_insert,true);

					

			}

			}else{

				if($exam_invoice[0]['invoice_id'] == ''){

					$last_id = $this->master_model->insertRecord('exam_invoice',$insert_arr,true);

					if($last_id > 0){

						$config_inset_arr = array(

													'invoice_id' => $last_id,

													'created_date' => $member[0]['modified_on']

												);

						$config_last_id = $this->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true);

						//$invoice_no = 'EX/19-20/'.$config_last_id;

						//$invoice_image = $payment[0]['member_regnumber'].'_EX_21-22_'.$config_last_id.'.jpg';

						$update_arr = array(

											'invoice_no' => $invoice_no,

											'invoice_image' => $invoice_image

										);

						$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$last_id));

					}

				}else{

					echo $payment[0]['receipt_no'].'Dupicate entry';

					echo '<br/>'; 

				}

			}

			$complete_arr[] = $payment[0]['receipt_no'];

		} 

		

		echo '<pre>';

		print_r($r_cat);

		echo '<br/>';

		

		echo '<pre>';

		print_r($complete_arr);

		//8007701593

	}

	

	public function dynamic_invoice_generation_old(){ 

		$complete_arr = array(); 

		$r_cat = array();

		$aug_insert = array();

		$receipt_no_arr = array(901993804);     

		$sizearr = sizeof($receipt_no_arr);

		for($i=0;$i<$sizearr;$i++){

			$payment = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no_arr[$i]),'member_regnumber,transaction_no,id,amount,ref_id,date,receipt_no');

			

			$member = $this->master_model->getRecords('member_exam',array('id'=>$payment[0]['ref_id']),'id,exam_code,exam_period,exam_center_code,created_on,modified_on');

			

			$registration = $this->master_model->getRecords('member_registration',array('regnumber'=>$payment[0]['member_regnumber']),'registrationtype');

			

			$this->db->where('exam_code',$member[0]['exam_code']);

			$this->db->where('eligible_period',$member[0]['exam_period']);

			$this->db->where('member_no',$payment[0]['member_regnumber']);

			$eligible = $this->master_model->getRecords('eligible_master','','app_category');

			/*echo '<pre>';

			print_r($eligible);

			echo '<br/>';*/

			

			if($eligible){

				if($eligible[0]['app_category'] == 'R'){

					$this->db->where('group_code','B1_1');

				}else{

					$this->db->where('group_code',$eligible[0]['app_category']);

				}

			}else{

				$this->db->where('group_code','B1_1');

			}

			$this->db->where('exam_code',$member[0]['exam_code']);

			$this->db->where('exam_period',$member[0]['exam_period']);

			$this->db->where('member_category',$registration[0]['registrationtype']);

			$ex = explode(" ",$payment[0]['date']);

			$pay_date=$ex[0];

			$this->db->where("'$pay_date' BETWEEN fr_date AND to_date");

			$fee = $this->master_model->getRecords('fee_master','','fee_amount,sgst_amt,cgst_amt,igst_amt,cs_tot,igst_tot');

			

			/*echo '>>>'. $this->db->last_query();

			echo '<pre>';

			print_r($fee);*/

			//exit;

			

			$this->db->where('exam_name',$member[0]['exam_code']);

			$this->db->where('exam_period',$member[0]['exam_period']);

			$this->db->where('center_code',$member[0]['exam_center_code']);

			$center = $this->master_model->getRecords('center_master','','center_name,state_code,state_description');

			

			//echo $this->db->last_query();

			//echo '<br/>';

			

			$state = $this->master_model->getRecords('state_master',array('state_code'=>$center[0]['state_code']),'state_no,exempt');

			

			/*echo $this->db->last_query();

			echo '<br/>';

			echo '>>'. $state[0]['state_no'];

			echo '<br/>';*/

			

			if($state[0]['state_no'] == 27){

				$cgst_rate = 9.00;

				$cgst_amt = $fee[0]['cgst_amt'];

				$sgst_rate = 9.00;

				$sgst_amt = $fee[0]['sgst_amt'];

				$cs_total = $fee[0]['cs_tot'];

				$igst_rate = 0.00;

				$igst_amt = 0.00;

				$igst_total = 0.00;

				$disc_rate = 0.00;

				$disc_amt = 0.00;

				$tds_amt = 0.00;

				$tax_type = 'Intra';

			}else{

				$cgst_rate = 0.00;

				$cgst_amt = 0.00;

				$sgst_rate = 0.00;

				$sgst_amt = 0.00;

				$cs_total = 0.00;

				$igst_rate = 18.00;

				$igst_amt = $fee[0]['igst_amt'];

				$igst_total = $fee[0]['igst_tot'];

				$disc_rate = 0.00;

				$disc_amt = 0.00;

				$tds_amt = 0.00;

				$tax_type = 'Inter';

			}

			

			$insert_arr = array(

								'exam_code' => $member[0]['exam_code'],

								'exam_period' => $member[0]['exam_period'],

								'center_code' => $member[0]['exam_center_code'],

								'center_name' => $center[0]['center_name'],

								'state_of_center' => $center[0]['state_code'],

								'member_no' => $payment[0]['member_regnumber'],

								'pay_txn_id' => $payment[0]['id'],

								'receipt_no' => $payment[0]['receipt_no'],

								'transaction_no' => $payment[0]['transaction_no'],

								'gstin_no' => '',

								'service_code' => 999294,

								'qty' => 1,

								'fresh_fee' => 0.00,

								'rep_fee' => 0.00,

								'fresh_count' => 0,

								'rep_count' => 0,

								'cess' => 0.00,

								'institute_code' => 0,

								'institute_name' => '',

								'state_code' => $state[0]['state_no'],

								'state_name' => $center[0]['state_description'],

								'invoice_no' => '',

								'invoice_image' => '',

								'fee_amt' => $fee[0]['fee_amount'],

								'cgst_rate' => $cgst_rate,

								'cgst_amt' => $cgst_amt,

								'sgst_rate' => $sgst_rate,

								'sgst_amt' => $sgst_amt,

								'cs_total' => $cs_total,

								'igst_rate' => $igst_rate,

								'igst_amt' => $igst_amt,

								'igst_total' => $igst_total,

								'disc_rate' => $disc_rate,

								'disc_amt' => $disc_amt,

								'tds_amt' => $tds_amt,

								'date_of_invoice' => $member[0]['modified_on'],

								'created_on' => $member[0]['created_on'],

								'modified_on' => $member[0]['modified_on'],

								'tax_type' => $tax_type,

								'app_type' => 'O',

								'exempt' => $state[0]['exempt']

								);

			echo '<pre>';

			print_r($insert_arr);

			echo '<br/>';

			exit; 

			

			$exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no_arr[$i]),'invoice_id'); 

			

			/*echo 'here'.$eligible[0]['app_category'];

			exit;*/

			

			if(count($eligible) > 0){

			

			if($eligible[0]['app_category']!=''){

				if($exam_invoice[0]['invoice_id'] == ''){

					$last_id = $this->master_model->insertRecord('exam_invoice',$insert_arr,true);

				}else{

					echo $payment[0]['receipt_no'].'Dupicate entry';

					echo '<br/>';  

				}

			}else{

					$r_cat[] = $payment[0]['receipt_no']; 

					$aug_insert = array(

										'receipt_no'=>$receipt_no_arr[$i],

										'exm_cd'=>$member[0]['exam_code']

										);

					$this->master_model->insertRecord('aug_invoice',$aug_insert,true);

					

			}

			}else{

				if($exam_invoice[0]['invoice_id'] == ''){

					$last_id = $this->master_model->insertRecord('exam_invoice',$insert_arr,true);

				}else{

					echo $payment[0]['receipt_no'].'Dupicate entry';

					echo '<br/>'; 

				}

			}

			$complete_arr[] = $payment[0]['receipt_no'];

		} 

		

		echo '<pre>';

		print_r($r_cat);

		echo '<br/>';

		

		echo '<pre>';

		print_r($complete_arr);

		//8007701593

	}

	public function swati_data_insert_(){

		$arr=array(2030068);

		

		 for($i=0;$i<sizeof($arr);$i++){

		 	$insert_arr = array(

								'invoice_id' => $arr[$i],

							);

		 	$this->master_model->insertRecord('aug_invoice_gen',$insert_arr,true);

		 }

		

	}

		public function downlaod_zip(){

			$zip = new ZipArchive();

			$zip_name = time().".zip"; // Zip name

			$zip->open($zip_name,  ZipArchive::CREATE);

			$this->db->where('image_status', 1);

		    $this->db->limit(100,0);

		    $record = $this->master_model->getRecords('generate_new_invocie');

		

		if(count($record) > 0){

			

			foreach ($record as $record) {

				$this->db->where('invoice_id', $record['invoice_id']);

				$invoice = $this->master_model->getRecords('exam_invoice');

				$invc = $invoice[0];

				$this->db->where('invoice_id', $invc['invoice_id']);

				$invc_no = $this->master_model->getRecords('config_exam_invoice');

				//$file = $invc['member_no'].'_EX_21-22_'.$invc_no[0]['exam_invoice_no'].'.jpg';

			   $path = "uploads/examinvoice/user/".$file; 

			  if(file_exists($path)){

			  $zip->addFromString(basename($path),  file_get_contents($path));  

			  $update_data = array('image_status'=> 0 );

			  $this->master_model->updateRecord('generate_new_invocie',$update_data,array('id'=>$record['id']));	

			

			  }

			  else{

			   echo"file does not exist";

			  }

			}

		}

		else{

			echo "Done";

		}

			$zip->close();

			 header('Content-disposition: attachment; filename=files.zip');

        header('Content-type: application/zip');

        readfile($zip_name);

		}

	public function update_dra_members(){  

		

		

		 $arr = array(801346433,801346434,801346435);    

		 for($i=0;$i<sizeof($arr);$i++){

		 	 $update_data = array(

	          	'new_reg'=>0,

	            

	        );

          	$sql= $this->master_model->updateRecord('dra_members',$update_data,array('regnumber' => $arr[$i]));

		 	

		 	echo $arr[$i]; 

		 	echo '</br>'; 

		 } 

		

	}

	//auto mail sending of invoices and admitcrad

	public function settlement_mail_send(){  

		$record_details = $this->master_model->getRecords('exam_invoice_settlement',array('refund_case'=> '0','email_send'=>'0' ),'id,exam_code,exam_period,member_regnumber,receipt_no');

		//echo $this->db->last_query();

        if(count($record_details) > 0){

  

        	foreach($record_details as $record_details){ 

        // send mail for invoice		

		$receipt_array = array($record_details['receipt_no']);    

		$this->db->where_in('receipt_no',$receipt_array); 

		$sql = $this->master_model->getRecords('exam_invoice','','invoice_id,invoice_image,member_no,exam_code');

		

		$exam_name = $this->master_model->getRecords('exam_master',array('exam_code'=>$sql[0]['exam_code']),'description');

		

		$final_str = "Hello Sir/Madam"; 

		$final_str.= "<br/><br/>";

		$final_str.= 'Please check your invoice receipt for '.$exam_name[0]['description'].' exam registration.'; 

		$final_str.= "<br/><br/>";

		$final_str.= "Regards,";

		$final_str.= "<br/>";

		$final_str.= "IIBF TEAM";

		

		foreach($sql as $rec){ 

			$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];

			//$attachpath = "uploads/IIBF_ADMIT_CARD_510033421.pdf";

			echo $attachpath."<br/>";

			//$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 

			//echo ">>".$email[0]['email'];  

			//exit;

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'prafull.tupe@esds.co.in',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Exam Enrollment Acknowledgement',

							'message'=>$final_str

						);

						

			

			$files=array($attachpath);

			

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				

				echo "Invoice Mail send to => ".$rec['invoice_id'];

				echo "<br/>"; 

			    }

			

		      }

		//send mail of admitcrad

		if($record_details['exam_code'] != 101 || $record_details['exam_code'] != 45){

		$member_array = array($record_details['member_regnumber']);             

		

		$this->db->distinct('mem_mem_no');   

		$this->db->where('remark',1);

		$this->db->where('exm_prd',$record_details['exam_period']);

		$this->db->where('admitcard_image !=','');

		//$this->db->where_in('center_code',$center_array);

		//$this->db->where_in('exam_date',$date_array);

		$this->db->where_in('mem_mem_no',$member_array);

		$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 

		    

		foreach($sql as $rec){ 

			

			$this->db->where('exam_code',$rec['exm_cd']);

			$exam_name = $this->master_model->getRecords('exam_master','','description');

			

			$final_str = 'Hello Sir/Madam <br/><br/>';

			/*$final_str.= 'Please ignore previous mail of  revised admit card letter for '.$exam_name[0]['description'].' examination';

			$final_str.= '<br/><br/>';*/

			$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   

			$final_str.= '<br/><br/>';

			$final_str.= 'Regards,';

			$final_str.= '<br/>';

			$final_str.= 'IIBF TEAM'; 

			  

			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  

			//$attachpath = "uploads/IIBF_ADMIT_CARD_510360428.pdf";   

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   

			$info_arr=array('to'=>$email[0]['email'],

							//'to'=>'prafull.tupe@esds.co.in',

							'from'=>'noreply@iibf.org.in',

							'subject'=>'Revised Admit Letter',

							'message'=>$final_str

						); 

			$files=array($attachpath);

			if($this->Emailsending->mailsend_attch($info_arr,$files)){

				echo "Admitcard Mail send to ==> ".$rec['mem_mem_no'];

				echo "<br/>";  

			      }

	        	}

	        	}

	        	$update_data = array(

	          	'email_send'=>1,

	             );

	        	$sql= $this->master_model->updateRecord('exam_invoice_settlement',$update_data,array('id' => $record_details['id']));

        	}

        } else{

        	echo "No record found.";

        } 

	}

	public function find_images(){

		$payment = $this->master_model->getRecords('payment_transaction',array('exam_code !='=>0,'status'=>1,'date >' => '2019-05-30'),'receipt_no');   

		//echo $this->db->last_query(); die;

		

		if(count($payment) > 0){

		foreach($payment as $payment){ 

			                  $this->db->select('invoice_image,receipt_no,exam_invoice.app_type,pay_type_master.module_name');

			                  $this->db->join('pay_type_master','exam_invoice.app_type=pay_type_master.app_type');

			$record_details = $this->master_model->getRecords('exam_invoice',array('receipt_no'=> $payment['receipt_no'],'invoice_image !='=>''),'invoice_image,receipt_no,app_type');

            if(count($record_details) > 0){

            	$oldfilepath_user ='uploads/examinvoice/user/'.$record_details[0]['invoice_image'];

            	echo $oldfilepath_photo;exit;

				if(!file_exists($oldfilepath_user))

					{

						$insert_arr= array(

							'invoice_image' => $record_details[0]['invoice_image'],

							'receipt_no' => $record_details[0]['receipt_no'],

							'type'=> 'user'

						);

						$this->master_model->insertRecord('exam_invoice_images',$insert_arr,true);

						echo $record_details[0]['receipt_no'].'<br>';

					}

					$oldfilepath_supplier ='uploads/examinvoice/supplier/'.$record_details[0]['invoice_image'];

            	//echo $oldfilepath_photo;exit;

				if(!file_exists($oldfilepath_supplier))

					{

						$insert_arr= array(

							'invoice_image' => $record_details[0]['invoice_image'],

							'receipt_no' => $record_details[0]['receipt_no'],

							'type'=> 'supplier'

						);

						$this->master_model->insertRecord('exam_invoice_images',$insert_arr,true);

						echo $record_details[0]['receipt_no'].'<br>';

					}

            }

exit();

		}

		}else{

			echo "No Data";

		}

				

	}

	

	public function ttt(){

		

		echo 'Total=>1266';

		echo '<br/>';

		

		$record_details = $this->master_model->getRecords('admit_card_seatno_missing',array('image_generate'=> 1));

		echo 'settle=>'.count($record_details);

		exit;

		

			//$system_date='2018-08-06 21:45:01';

			//date_default_timezone_set('Asia/Kolkata');

			echo $date = date("Y-m-d");

			echo '<br/>';

			echo '>>>'.$system_date = date("Y-m-d H:i:s");	

			if($system_date > '2019-10-17 14:59:00' && $system_date < '2019-10-17 15:15:00'){ 

				echo 'in';

			}else{

				echo 'out';

			}      

	}

	

	function caiib_wrong_seat(){

		$arr_one = array();

		$arr_two = array();

		$string_one = '';

		$string_two = '';

		

		$data_arr = array('2019-12-08','2019-12-15','2019-12-22');

		

		//$this->db->where('venue_code','146001A');

		$this->db->where_in('exam_date',$data_arr);

		$this->db->group_by('exam_date,center_code,session_time,venue_code');

		$this->db->limit(500,2000); 

		$venue_sql = $this->master_model->getRecords('venue_master','','session_capacity,exam_date,center_code,session_time,venue_code,venue_master_id');

		

		//500

		//500,500

		//1000,500

		//1500,500

		//2000,200

		

		

		//echo '<pre>';print_r($venue_sql);

		$i=1;

		foreach($venue_sql as $res){

			$string_one=$string_two='';

			

			//echo 'exam_date='.$res['exam_date'].'**center_code='.$res['center_code'].'**session_time='.$res['session_time'].'**venue_code='.$res['venue_code'];

			//echo '<br/><br/>';

			//echo 'total_capacity=>' .$res['session_capacity'];

			//echo '<br/><br/>';

			

			

			$this->db->where('venue_code',$res['venue_code']);

			$this->db->where('session',$res['session_time']);

			$this->db->where('center_code',$res['center_code']);

			$this->db->where('date',$res['exam_date']);

			$rowseat_sql = $this->master_model->getRecords('seat_allocation');

			$rcnt = count($rowseat_sql);

			

			

			

			//echo 'Row_count=>' .count($rowseat_sql);

			//echo '<br/><br/>';

			

			$this->db->where('venue_code',$res['venue_code']);

			$this->db->where('session',$res['session_time']);

			$this->db->where('center_code',$res['center_code']);

			$this->db->where('date',$res['exam_date']);

			$this->db->order_by("seat_no", "DESC");

			$lastseat_sql = $this->master_model->getRecords('seat_allocation','','seat_no');

			

			//echo 'last_seat=>' .$lastseat_sql[0]['seat_no'];

			//echo '<br/><br/>';

			

			

			if($rcnt < $lastseat_sql[0]['seat_no']){

				//$string_one.='exam_date='.$res['exam_date'].'**center_code='.$res['center_code'].'**session_time='.$res['session_time'].'**venue_code='.$res['venue_code'].'**capacity='.$res['session_capacity'].'**rowcount='.$rcnt.'**lastseat='.$lastseat_sql[0]['seat_no'];

				

				$arr_one = array(

									'venue_master_id'=>$res['venue_master_id'],

									'exam_date'=>$res['exam_date'],

									'center_code'=>$res['center_code'],

									'session_time'=>$res['session_time'],

									'venue_code'=>$res['venue_code'],

									'session_capacity'=>$res['session_capacity'],

									'rowcount'=>$rcnt,

									'lastseat'=>$lastseat_sql[0]['seat_no'],

									'string_number'=>'one',

								);

								

				$last_id = $this->master_model->insertRecord('caiib_seat_issue',$arr_one);

				

				

			

			}

			

			if(($res['session_capacity'] <= $lastseat_sql[0]['seat_no']) && ($rcnt != $lastseat_sql[0]['seat_no'])){

				//$string_two.='exam_date='.$res['exam_date'].'**center_code='.$res['center_code'].'**session_time='.$res['session_time'].'**venue_code='.$res['venue_code'].'**capacity='.$res['session_capacity'].'**rowcount='.$rcnt.'**lastseat='.$lastseat_sql[0]['seat_no'];

				

				$arr_two = array(

									'venue_master_id'=>$res['venue_master_id'],

									'exam_date'=>$res['exam_date'],

									'center_code'=>$res['center_code'],

									'session_time'=>$res['session_time'],

									'venue_code'=>$res['venue_code'],

									'session_capacity'=>$res['session_capacity'],

									'rowcount'=>$rcnt,

									'lastseat'=>$lastseat_sql[0]['seat_no'],

									'string_number'=>'two',

								);

								

				$last_id = $this->master_model->insertRecord('caiib_seat_issue',$arr_two);

				

			}

			

			

			/*if($string_one!='')

			{

				echo '<br/>';		

				echo '******************************';

				echo 'String_one=>'.$string_one;

				echo '<br/>';		

			}

			if($string_two!='')

			{

				echo '<br/>';		

				echo '###################################';

				echo 'String_two=>'.$string_two;

				echo '<br/>';

			}*/

		

		} // end of foreach

	}

	

	function caiib_seat_update(){

		$update_data = array();

		

		$this->db->where('is_settle',0);

		//$this->db->where('venue_master_id','43212');

		$sql = $this->master_model->getRecords('caiib_seat_issue');

		//echo '<pre>';print_r($sql); exit;

		foreach($sql as $res){

			if($res['lastseat'] > $res['session_capacity']){

				// update rowcount to venue master session capacity

				$update_data = array(

										'session_capacity' => $res['rowcount'],

									);

				

				$this->master_model->updateRecord('venue_master',$update_data,array('venue_master_id'=>$res['venue_master_id']));	

				

				$this->master_model->updateRecord('caiib_seat_issue',array('is_settle'=>1),array('venue_master_id'=>$res['venue_master_id']));	

			}

		}

		

	}

	

	

	function caiib_seat_update_two(){

		$update_data = array();

		

		$this->db->where('is_settle',0);

		//$this->db->where('venue_master_id','43889');

		$sql = $this->master_model->getRecords('caiib_seat_issue');

		//echo '<pre>';print_r($sql); exit;

		foreach($sql as $res){

			

			$diff =  $res['lastseat'] -  $res['rowcount'];

			$new_capacity = $res['session_capacity'] - $diff;

			

			

			$update_data = array(

									'session_capacity' => $new_capacity,

								);

			

			$this->master_model->updateRecord('venue_master',$update_data,array('venue_master_id'=>$res['venue_master_id']));	

			

			$this->master_model->updateRecord('caiib_seat_issue',array('is_settle'=>1),array('venue_master_id'=>$res['venue_master_id']));	

			

		}

		

	}

	

	public function cisi_invoice(){

		//$invoice_id  = '2150500';

		//echo $path = custom_genarate_CISI_invoice($invoice_id);

		

		

		$arr = array(2178004); 

		for($i=0;$i<sizeof($arr);$i++){

			echo $path = custom_genarate_CISI_invoice($arr[$i]);

			echo "<br/>"; 

		}

	}

	

public function checkfile()

{

	if(!file_exists("uploads/photograph/".$this->session->userdata['enduserinfo']['photoname']))

		{

			echo 'exists';

		}

		else

		{

			echo 'not exists';

		}

}	

public function send_mail(){

		/*$exam_code = $this->input->post('exam_code');

		$exam_period = $this->input->post('exam_period');

		

		$insert_array = array(

								'exam_code'=>$exam_code,

								'exam_period'=>$exam_period,

								'request_date'=>date('Y-m-d'),

								'status' => 0

							);

							

		$last_id = $this->master_model->insertRecord('request_admitcard',$insert_array,true);*/

		

		

		$final_str = 'Hello Team,';

		$final_str.= '<br/><br/>';

		$final_str.= 'Please provide the admit card download link for below detail.';

		$final_str.= '<br/><br/>';

		$final_str.= 'Exam Code: ';

		$final_str.= '<br/>';

		$final_str.= 'Exam Period: ';

		$final_str.= '<br/>';

		$final_str.= 'Request Id: ';

		$final_str.= '<br/><br/>';

		$final_str.= 'Regards,';

		$final_str.= '<br/>';

		$final_str.= 'IIBF Team';

		

		$info_arr=array('to'=>'pawansing.pardeshi@esds.co.in',

						'from'=>'noreply@iibf.org.in',

						'subject'=>'Admit letter request ',

						'message'=>$final_str

						); 

						

		if($this->Emailsending->mailsend($info_arr)){

			echo 'mail send';	

		}else{

			echo 'mail not send';	 

		}

	}

	

	// Dwnletter/custom_credit_note

	public function custom_credit_note(){

		$arr = array(4101349901725);   

		for($i=0;$i<count($arr);$i++){ 

			

			//echo $arr[$i];

			//echo '<br/>';

			

			// FOR New

			//echo $path = custom_generate_credit_note($arr[$i]);

			

			// For Image only one rec at a time

			echo $path = custom_generate_credit_note_img($arr[$i]);

			echo '<br/>';

		}

	}

	

	/** FUNCTION ADDED BY SAGAR ON 08-10-2020 TO GENERATE DRA MEMBER ADMIT CARD ***/

	public function GenerateDraMemberAdmitCard()

	{
		//exit;
		error_reporting(E_ALL);

		

		$mem_mem_no = array(801546302,801546300,801546301); //, '801546671', '801546672', '801546673', '801546674', '801546675', '801546662', '801546661', '801546682', '801546683', '801546684', '801546685', '801546686', '801546653', '801546654', '801546655', '801546656', '801546657', '801546658', '801546659', '801546660', '801546676');

		$exm_cd = '45'; 
		$exm_prd = '777';	

		foreach($mem_mem_no as $res)
		{

			//echo "<br>".$res;

			echo "<br>".$attchpath_admitcard = genarate_admitcard_dra_custom($res,$exm_cd,$exm_prd); 

		}

	}

}