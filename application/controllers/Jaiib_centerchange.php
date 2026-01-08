<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	class Jaiib_centerchange extends CI_Controller
	{
    public function __construct()
    { //exit;
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			//$this->load->helper('exam_invoice_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
		}
		
		/* Showing Garp_exam Form */
		public function new_link()
    {
        die;
      $flag       = 1;
      $var_errors = '';
			$center_data = array();
      if ($this->session->userdata('enduserinfo')) {
        $this->session->unset_userdata('enduserinfo');
			}
      $row =$validateMemberNo= array();
      $selectedMemberId = '';
      if (isset($_POST['btnGetDetails'])) 
      {
				$selectedMemberId = ltrim(rtrim($_POST['regnumber']));
        if ($selectedMemberId != '')  /* Check User Eligiblity */
        {
          $row = $this->validateMember($selectedMemberId);
					if(count($row) > 0)
					{
						$center_data = $this->center_data($row['exm_cd']);
					}
				} 
        else 
        { 
          $row = array("msg" => "The Membership No field is required.");
				}
			} 
      else 
      {
        $password = $var_errors = '';
        $data['validation_errors'] = '';
        /* Check Server-Side Validations */
        if (isset($_POST['btnSubmit'])) 
        {
          $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';
					$this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');
					$this->form_validation->set_rules('new_center_code', 'New center name is required', 'trim|required|xss_clean');
					$this->form_validation->set_rules('declaration1', 'declaration is required', 'trim|required|xss_clean');
					$this->form_validation->set_rules('userfile', 'Attachment is required', 'trim|required|xss_clean');
          if ($this->form_validation->run() == TRUE) 
          {
            $resp = array( 'success' => 0, 'error' => 0,'msg' => '');
            $this->session->unset_userdata('enduserinfo');
            $date         = date('Y-m-d h:i:s');
            
						//Check whether user upload picture						
						$valid_img_flag = 0;
            if(!empty($_FILES['userfile']['name']))
						{
							$config['upload_path'] = 'uploads/jaiib_centerchange/';
							$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
							$config['file_name'] = $_POST['regnumber'].'_'.$_FILES['userfile']['name'];
							//Load upload library and initialize configuration
							$this->load->library('upload',$config);
							$this->upload->initialize($config);
							if($this->upload->do_upload('userfile'))
							{
								$uploadData = $this->upload->data();
								$userfile = $uploadData['file_name'];
							}
							else
							{
								$valid_img_flag = 1;
								$userfile = '';
								$var_errors = $this->upload->display_errors();
							}
						}
						else
						{
							$userfile = '';
							$valid_img_flag = 1;
							$var_errors = 'Transfer Order Required';
						}
            
						if ($_POST["regnumber"] != '' && $valid_img_flag == 0) 
            {
              $user_data = array(
							'old_center_code' => ($_POST["old_center_code"]),
							'new_center_code' => ($_POST["new_center_code"]),
							'old_center_name' => ($_POST["old_center_name"]),
							'regnumber' => ($_POST["regnumber"]),
							'attachment' => $userfile,
							'exam_code' => ($_POST['exam_code']),
							'exam_period' => ($_POST['exam_period']),
							'exam_name' => ($_POST['exam_name']),
							'date'         => date('Y-m-d h:i:s'),
              );
              /* Stored User Details In The Session */
              $this->form_validation->set_message('error', "");
							$this->master_model->insertRecord('jaiib_centerchange', $user_data, true);
							$this->session->set_flashdata('flsh_msg_success', 'Thank You! Your request for centre change is registered with IIBF.');
              redirect(base_url() . 'Jaiib_centerchange/new_link'); /* Sent to Preview Page */
						} 
            else 
            {
              $var_errors = str_replace("<p>", "<span>", $var_errors);
              $var_errors = str_replace("</p>", "</span><br>", $var_errors);
						}
					}
				}
			}
			$exam_code=array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'),$this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
			$this->db->where_in('exam_name', $exam_code);
      $this->db->where('exam_period', '121');
      $center = $this->master_model->getRecords('center_master');
      /* Get Programs */
      $current_date  = date("Y-m-d H:i:s");
      if ($flag == 0) {
        $data = array(
				'middle_content' => 'garp_exam_reg1'
        );
        $this->load->view('JAIIB_centerchange/common_view', $data);
				} else {
        $data = array(
				'middle_content' => 'garp_exam_reg1',
				'center' => $center,
				'var_errors' => $var_errors,
				'row' => $row,
				'center_data' => $center_data
				//'validateMemberNo' => $validateMemberNo
        );
        $this->load->view('JAIIB_centerchange/common_view', $data);
			}
		}
		
		
	/* public function center_data($exam_code){
		    
		      $this->db->where('exam_name', $exam_code);
              $this->db->where('exam_period', 121);
              $query = $this->db->get('center_master');
              $output = '<option value="">----Select Centre-</option>';
              foreach($query->result() as $row)
              {
               $output .= '<option value="'.$row->center_code.'">'.$row->center_name.'</option>';
              }
              return $output;
    } */
		
	public function center_data($exam_code)
	{		    
		$this->db->where('exam_name', $exam_code);
		$this->db->where('exam_period', 121);
		$query = $this->db->get('center_master');
		return $query->result_array();		
	}
		    
	
		
		
		
		/* Validate Member Function */
		function validateMember($selectedMemberId)
		{
			$exam_code=array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'),$this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
			$this->db->where('remark', 1);
			$this->db->where('exm_prd', 121);
			$this->db->where_in('exm_cd', $exam_code);
			$this->db->where('mem_mem_no', $selectedMemberId);
			$validateMemberNo = $this->master_model->getRecords('admit_card_details');
			//echo $this->db->last_query(); die;
			if (COUNT($validateMemberNo) > 0) 
			{
				$blendedQry1 = $this->db->query("SELECT * FROM jaiib_centerchange WHERE regnumber = '" . $selectedMemberId . "'"); 
				$rows        = $blendedQry1->row_array();
        if(COUNT($rows) > 0){
					$row = array("msg" => "Your request for centre change is already submitted!");
				}
        else{
					$blendedQry = $this->db->query("SELECT admit_card_details.*,exam_master.description FROM admit_card_details LEFT JOIN exam_master ON exam_master.exam_code=admit_card_details.exm_cd  WHERE mem_mem_no = '" . $selectedMemberId . "' AND remark=1 and exm_prd=121 and exm_cd IN (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').",".$this->config->item('examCodeCaiib').",".$this->config->item('examCodeCaiibElective63').",65,".$this->config->item('examCodeCaiibElective68').",".$this->config->item('examCodeCaiibElective69').",".$this->config->item('examCodeCaiibElective70').",".$this->config->item('examCodeCaiibElective71').") LIMIT 1 "); 
					$row        = $blendedQry->row_array();
				}
			}
			else 
			{
        $row = array("msg" => "Sorry! We do not have your application registered for JAIIB/DB&F/SOB/CAIIB/CAIIB Elective Exam -  Aug/Sep-2021.");
			}
			return $row;
			// return $validateMemberNo;
		}
	}		