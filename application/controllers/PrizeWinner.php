<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class PrizeWinner extends CI_Controller

{
	public function __construct()
	{ //exit;
		parent::__construct();
		$this->load->library('upload');
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');

		// $this->load->model('chk_session');
		// $this->chk_session->checklogin();
		// $this->load->model('chk_session');
		// $this->chk_session->chk_member_session();
		// accedd denied due to GST
		// $this->master_model->warning();

	}

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
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		redirect(base_url() . 'PrizeWinner/member');
	}

	/* Showing Blended Form */
	public

	function member()
	{
		$var_errors = '';
		if ($this->session->userdata('enduserinfo')) {
			$this->session->unset_userdata('enduserinfo');
		}

		$row = array();
		$selectedMemberId = '';
		if (isset($_POST['btnGetDetails'])) {
			$blendedQry = $this->db->query("SELECT * FROM prizewinners_registration WHERE regnumber = '" . $_POST['regnumber'] . "'LIMIT 1");
			$row = $blendedQry->row_array();

			// echo $this->db->last_query();
			// exit;

			if (!empty($row)) {
				$this->session->set_flashdata('error', 'You have already applyed  ..!');
				redirect(base_url() . 'PrizeWinner/member');
			}

			$selectedMemberId = $_POST['regnumber'];
			$this->session->set_userdata('regnumber', $_POST['regnumber']);
			if ($selectedMemberId != '') /* Check User Eligiblity */ {
				$row = $this->master_model->getRecords('exam_prizewinner_eligible_master', array(
					'regnumber' => $_POST['regnumber']
				));
				if (empty($row)) {
					$this->session->set_flashdata('error', 'Invalid  Membership No');
					redirect(base_url() . 'PrizeWinner/member'); /* Sent to Preview Page */
				}
			} else {
				$this->session->set_flashdata('error', 'The Membership No field is required');
			}
		} else {
			$password = '';
			$data['validation_errors'] = '';
			/* Check Server-Side Validations */
			if (isset($_POST['btnSubmit'])) {
				$pan_card_file_nm = $cancel_cheque_file_nm = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

				// $this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');

				$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
				$this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|xss_clean');
				if (isset($_POST['lastname'])) {
					$this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
				}

				$this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1[Addressline1]');
				if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
					$this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|callback_address1[Addressline2]|xss_clean');
				}

				if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
					$this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|callback_address1[Addressline3]|xss_clean');
				}

				if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
					$this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|callback_address1[Addressline4]|xss_clean');
				}

				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
				$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
				$this->form_validation->set_rules('bankname', 'Bank Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('branchname', 'Branch Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('ifs_code', 'IFS CODE', 'trim|max_length[11]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('account_type', 'Account Type', 'trim|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('account_no', 'Account Number', 'trim|required|alpha_numeric_spaces|xss_clean');

				$this->form_validation->set_rules('pan_card_file','Pan Card File ','file_required');
				$this->form_validation->set_rules('cancel_cheque_file','Cancel Cheque File ','file_required');

				// $this->form_validation->set_rules('scannedsignaturephoto1','Cancel Cheque Image ','file_required');

				//$this->form_validation->set_rules('scannedsignaturephoto1', 'Cancel Cheque Image', 'file_required|file_allowed_type[jpg,jpeg]|file_size_min[80]');
				if ($this->form_validation->run() == TRUE) {
					/* Add Form Fields Value in the Session */
					if ($_POST["firstname"] != '') {
						

					//$input_pan_card_file = $_POST["hidden_pan_card_file"];

					if (!empty($_FILES['pan_card_file']['name'])) {

					    $field_name = 'pan_card_file';
					    $unique_id  = strtotime($date) . rand(100, 999)."_".time();
					    $new_filename = 'pan_card_file_nm_' . $unique_id;

					    $file_ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));

					    // Conditional image validation
					    if (in_array($file_ext, ['jpg', 'jpeg'])) {
					        if (!@getimagesize($_FILES[$field_name]['tmp_name'])) {
					            $this->session->set_flashdata('error', 'Invalid image file.');
					            return;
					        }
					    }

					    $config = array(
					        'upload_path'      => './uploads/prize_winner_pan_card/',
					        'allowed_types'    => 'jpg|jpeg',
					        //'max_size'         => 1024, // 1 MB
					        'file_name'        => $new_filename,
					        /*'remove_spaces'    => TRUE,
					        'detect_mime'      => FALSE,
					        'file_ext_tolower' => TRUE*/
					    );

					    $this->upload->initialize($config);

					    if ($this->upload->do_upload($field_name)) {

					        $upload_data = $this->upload->data();
					        $pan_card_file_nm = $upload_data['file_name'];
					        $outputphoto1 = base_url('uploads/prize_winner_pan_card/' . $upload_data['file_name']);

					    } else {

					        $this->session->set_flashdata(
					            'error',
					            strip_tags($this->upload->display_errors())
					        );
					    }
					}
					
					if (!empty($_FILES['cancel_cheque_file']['name'])) {

					    $field_name = 'cancel_cheque_file';
					    $unique_id  = strtotime($date) . rand(100, 999)."_".time();
					    $new_filename = 'cancel_cheque_file_nm_' . $unique_id;

					    $file_ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));

					    // Conditional image validation
					    if (in_array($file_ext, ['jpg', 'jpeg'])) {
					        if (!@getimagesize($_FILES[$field_name]['tmp_name'])) {
					            $this->session->set_flashdata('error', 'Invalid image file.');
					            return;
					        }
					    }

					    $config = array(
					        'upload_path'      => './uploads/prize_winner_cancel_cheque/',
					        'allowed_types'    => 'jpg|jpeg',
					        //'max_size'         => 1024, // 1 MB
					        'file_name'        => $new_filename,
					        /*'remove_spaces'    => TRUE,
					        'detect_mime'      => FALSE,
					        'file_ext_tolower' => TRUE*/
					    );

					    $this->upload->initialize($config);

					    if ($this->upload->do_upload($field_name)) {

					        $upload_data = $this->upload->data();
					        $cancel_cheque_file_nm = $upload_data['file_name'];
					        $outputphoto1 = base_url('uploads/prize_winner_cancel_cheque/' . $upload_data['file_name']);

					    } else {

					        $this->session->set_flashdata(
					            'error',
					            strip_tags($this->upload->display_errors())
					        );
					    }
					} 

			         

        				if($pan_card_file_nm != '' && $cancel_cheque_file_nm != '')
        				{
        					$user_data = array(
								'regnumber' => $_POST["regnumber"],
								'firstname' => $_POST["firstname"],
								'sel_namesub' => $_POST["sel_namesub"],
								'bank_addressline1' => $_POST["addressline1"],
								'bank_addressline2' => $_POST["addressline2"],
								'bank_addressline3' => $_POST["addressline3"],
								'bank_addressline4' => $_POST["addressline4"],
								'email' => $_POST["email"],
								'lastname' => $_POST["lastname"],
								'mobile' => $_POST["mobile"],
								'bankname' => $_POST["bankname"],
								'branchname' => $_POST["branchname"],
								'branchadd1' => $_POST["addressline1"],
								'branchadd2' => $_POST["addressline2"],
								'branchadd3' => $_POST["addressline3"],
								'branchadd4' => $_POST["addressline4"],
								'ifs_code' => $_POST["ifs_code"],
								'account_type' => $_POST["account_type"],
								'acc_no' => $_POST["account_no"],
								'pan_card_file' => $pan_card_file_nm,
								'cancel_cheque_file' => $cancel_cheque_file_nm

								// 'venue_code' => $_POST['venue_code'],

							);
							/* Stored User Details In The Session */

							 
							$log_message = '';					
					        $log_message = json_encode($user_data); 
					        logactivity("Prize Winner data registration", $log_message); 

							$this->session->set_userdata('enduserinfo', $user_data);
							$this->form_validation->set_message('error', "");
							redirect(base_url() . 'PrizeWinner/preview'); /* Sent to Preview Page */
        				}
						else {
							$var_errors = str_replace("<p>", "<span>", $var_errors);
							$var_errors = str_replace("</p>", "</span><br />", $var_errors);
						}
						//$input = $_POST["hiddenscansignature1"];
						/* if (isset($_FILES['scannedsignaturephoto1']['name']))
						{
							$img = "scannedsignaturephoto1";

							
							$new_filename = 'cancel_cheque_' .$this->session->userdata['regnumber'];
							
							$config = array(
								'upload_path' => './uploads/blank_cheque/',
								'allowed_types' => 'jpeg|jpg',
								'file_name' => $new_filename,
								'max_size' => 0,
								'overwrite'=>TRUE
							);

							

							$this->upload->initialize($config);

							

							if ($this->upload->do_upload('scannedsignaturephoto1'))
							{
								$dt = $this->upload->data();
								$file = $dt['file_name'];
								$file_name = $dt['file_name'];
								$outputphoto1 = base_url() . "./uploads/blank_cheque/" . $file_name;
								$this->session->set_userdata('imageinfo', $dt);
							}
							else
							{
								$this->session->set_flashdata('error', 'Cancel Cheque Image  :' . $this->upload->display_errors());
								echo $this->upload->display_errors();

								

							}
						}
						 */
						
					} else {
						$var_errors = str_replace("<p>", "<span>", $var_errors);
						$var_errors = str_replace("</p>", "</span><br />", $var_errors);
					}
				}
			}
		}

		$this->load->helper('captcha');
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => base_url() . 'uploads/applications/'
		);
		$cap = create_captcha($vals);
		$_SESSION["regcaptcha"] = $cap['word'];
		$data = array(
			'middle_content' => 'prize_winner_module/prize_page',
			'image' => $cap['image'],
			'var_errors' => $var_errors,
			'row' => $row
		);
		$this->load->view('prize_winner_module/prize_common_view', $data);
	}

	/* Form Preview */
	public function preview()
	{
		$row = '';
		if (!$this->session->userdata('enduserinfo')) {
			redirect(base_url());
		}

		$selectedMemberId = $this->session->userdata['regnumber'];
		$validateQry = $this->db->query("SELECT * FROM exam_prizewinner_eligible_master WHERE regnumber='" . $selectedMemberId . "'LIMIT 1");
		$validateMemberNo = $validateQry->row_array();
		if (!empty($validateMemberNo)) {
			$data = array(
				'middle_content' => 'prize_winner_module/price_preview',
				'row' => $row
			);
			$this->load->view('prize_winner_module/prize_common_view', $data);
		} else {
			$this->session->set_flashdata('flsh_msg', 'You are not eligible to apply  ..!');
			redirect(base_url() . 'PrizeWinner');
		}
	}

	/* Member Details Stored In The Database */
	public function addmember()
	{

		// print_r($_SESSION);exit;

		if (!$this->session->userdata['enduserinfo']) {
			redirect(base_url());
		}

		$selectedMemberId =  $this->session->userdata['regnumber'];
		if ($selectedMemberId != '') {
			$blendedQry = $this->db->query("SELECT * FROM prizewinners_registration WHERE regnumber = '" . $selectedMemberId . "'  LIMIT 1 ");
			$row = $blendedQry->row_array();
			if (!empty($row)) {
				$this->session->set_flashdata('flsh_msg', 'You have already applyed  ..!');
				redirect(base_url() . 'PrizeWinner');
			}
		}

		$insert_info = array(
			'regnumber' => $selectedMemberId,
			'namesub' => $this->session->userdata['enduserinfo']['sel_namesub'],
			'firstname' => $this->session->userdata['enduserinfo']['firstname'],
			'lastname' => $this->session->userdata['enduserinfo']['lastname'],
			'bankaddress1' => $this->session->userdata['enduserinfo']['branchadd1'],
			'bankaddress2' => $this->session->userdata['enduserinfo']['branchadd2'],
			'bankaddress3' => $this->session->userdata['enduserinfo']['branchadd3'],
			'bankaddress4' => $this->session->userdata['enduserinfo']['branchadd4'],
			'email' => $this->session->userdata['enduserinfo']['email'],
			'moblie' => $this->session->userdata['enduserinfo']['mobile'],
			'ifs_code' => $this->session->userdata['enduserinfo']['ifs_code'],
			'account_type' => $this->session->userdata['enduserinfo']['account_type'],
			'account_no' => '#' . $this->session->userdata['enduserinfo']['acc_no'],
			'branchname' => $this->session->userdata['enduserinfo']['branchname'],
			'bankname' => $this->session->userdata['enduserinfo']['bankname'],
			'pan_card_file' => $this->session->userdata['enduserinfo']['pan_card_file'],
			'cancel_cheque_file' => $this->session->userdata['enduserinfo']['cancel_cheque_file'],
			//'image_name' => $this->session->userdata['imageinfo']['file_name'],
			//'image_path' => $this->session->userdata['imageinfo']['full_path'],
			'created_on' => date('Y-m-d H:i:s')
		);

		// echo "<pre>"; print_r($insert_info); echo "</pre>";exit;

		/* Stored user details and selected field details in the database table */
		if ($last_id = $this->master_model->insertRecord('prizewinners_registration', $insert_info, true)) {
			redirect(base_url() . 'PrizeWinner/acknowledge/');
		} else {
			$this->session->set_flashdata('flsh_msg', 'Error while during registration.please try again!');
			redirect(base_url() . 'PrizeWinner');
		}
	}

	/* Showing acknowledge after registration */
	public

	function acknowledge()
	{
		if (!$this->session->userdata('enduserinfo')) {
			redirect(base_url());
		}

		$data = array(
			'middle_content' => 'prize_winner_module/prize_acknowledge',
		);
		$this->load->view('prize_winner_module/prize_common_view', $data);
	}

	/* User Address Validations */
	public

	function address1($addressline1)
	{
		if (!preg_match('/^[a-z0-9 .,-\/]+$/i', $addressline1)) {
			$this->form_validation->set_message('address1', "Please enter valid addressline1");
			return false;
		} else {
			return true;
		}
	}

	/* Captcha Validations */
	public

	function check_captcha_userreg($code)
	{
		if (isset($_SESSION["regcaptcha"])) {
			if ($code == '' || $_SESSION["regcaptcha"] != $code) {
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
				return false;
			}

			if ($_SESSION["regcaptcha"] == $code) {
				return true;
			}
		} else {
			return false;
		}
	}

	/* Captcha Validations */
	public

	function ajax_check_captcha_code()
	{
		$code = $_POST['code'];
		if ($code == '' || $_SESSION["regcaptcha"] != $code) {
			echo 'failure';
		} else
		if ($_SESSION["regcaptcha"] == $code) {
			echo 'success';
		}
	}

	/* Generate Validations */
	public

	function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("regcaptcha");
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => base_url() . 'uploads/applications/'
		);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["regcaptcha"] = $cap['word'];
		echo $data;
	}
}
