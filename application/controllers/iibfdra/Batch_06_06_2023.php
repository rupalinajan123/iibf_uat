<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class Batch extends CI_Controller 
	{	
	public $UserID;			
		public function __construct()
		{
		parent::__construct();
			if(!$this->session->userdata('dra_admin')) 
			{
			redirect('iibfdra/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->library('upload');
        $this->load->helper('upload_helper');
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
		$this->load->helper('dra_agency_center_mail_helper');
		$this->load->library('email');
        $this->load->model('Emailsending');
		$this->load->helper('general_agency_helper');
	}
	
	public function index()
	{		
		$data['result'] 	= array();
		$data['action'] 	= array();
		$data['batch_list'] = array();		
		$data['links'] 		= '';
		$data['success']	= '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		//$data = $this->getUserInfo();
		$res_arr = array();
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Batch/">
		<i class="fa fa-home"></i> Home </a></li>
		<li class="active"><a href="'.base_url().'iibfdra/batch/"> Traning Batch List</a></li>
		</ol>';	
			
		/* $this->db->select('dra_inst_registration.inst_name,dra_inst_registration.id as institute_id,agency_center.location_name,agency_batch.*,city_master.city_name');
		$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');	
		$this->db->where('agency_center.center_display_status','1');
		$this->db->order_by('agency_batch.created_on','DESC');		
		$this->db->order_by('agency_batch.updated_on','DESC');		
		$result = $this->master_model->getRecords("agency_batch");		
		if(count($result))
		{			
			foreach($result as $row)
			{
				$confirm = "";
				$str_btn = '';
				if($row['created_on'] != ''){
				$row['created_on'] = date_format(date_create($row['created_on']),"d-M-Y");	
				}else{
				$row['created_on'] = '--';		
				}
				$res_arr[] = $row;
			}
			
			$data['batch_list'] = $res_arr;
			
		} */		
			
			
		$this->load->view('iibfdra/admin/batch/batch_list',$data);
	}
		
	public function get_table_data_ajax()
	{
		$table = 'agency_batch b';
		$column_order = array('b.id','ir.inst_name', 'IF(cm.city_name = "", ac.location_name, cm.city_name) AS CityName', 'b.batch_code', 'b.batch_name', 'b.batch_from_date', 'b.timing_from', 'b.created_on', 
		'b.batch_status AS BatchStatus'			
		,'b.batch_to_date', 'b.timing_to', 'b.batch_status', 'IF(b.batch_online_offline_flag = "1", " (Online)", "") AS BatchFlag'); //SET COLUMNS FOR SORT 
		
		$column_search = array('ir.inst_name', 'IF(cm.city_name = "", ac.location_name, cm.city_name)', 'b.batch_code', 'b.batch_name', 'b.batch_from_date', 'b.timing_from', 'b.created_on',
		'b.batch_status'			
		,'b.batch_to_date', 'b.timing_to', 'b.batch_status', 'IF(b.batch_online_offline_flag = "1", " (Online)", "")'); //SET COLUMN FOR SEARCH
		$order_by = "ORDER BY b.created_on DESC, b.updated_on DESC"; //;array('b.created_on' => 'DESC', 'b.updated_on' => 'DESC'); // DEFAULT ORDER
		
		$where_search = $s_agency_name = $s_center_location = $s_batch_code = $s_batch_name = '';
		if(isset($_POST['s_agency_name']) && $_POST['s_agency_name'] != "")
		{
    		$s_agency_name = trim($_POST['s_agency_name']);
			$where_search .= " AND ir.inst_name LIKE '%".$s_agency_name."%'";
		}
		
		if(isset($_POST['s_center_location']) && $_POST['s_center_location'] != "")
		{
   			$s_center_location = trim($_POST['s_center_location']);
			$where_search .= " AND IF(cm.city_name = '', ac.location_name, cm.city_name) LIKE '%".$s_center_location."%'";
		}
		
		if(isset($_POST['s_batch_code']) && $_POST['s_batch_code'] != "")
		{
    		$s_batch_code = trim($_POST['s_batch_code']);
			$where_search .= " AND b.batch_code LIKE '%".$s_batch_code."%'";
		}
		
		if(isset($_POST['s_batch_name']) && $_POST['s_batch_name'] != "")
		{
    	$s_batch_name = trim($_POST['s_batch_name']);
			$where_search .= " AND b.batch_name LIKE '%".$s_batch_name."%'";
		}
		
		$WhereForTotal 	= "WHERE 1=1 ".$where_search; //DEFAULT WHERE CONDITION FOR ALL RECORDS
		$Where = "WHERE 1=1 AND batch_status <> 'In Review'".$where_search; //DEFAULT WHERE CONDITION 
		$WhereWithoutSearch = "WHERE 1=1 ";

		if($_POST['search']['value']) // DATATABLE SEARCH
		{
			$Where .= " AND (";
			for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( $this->custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
			$Where = substr_replace( $Where, "", -3 );
			$Where .= ')';
		}			
		
		$Order = ""; //DATATABLE SORT
		if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
		else if(isset($order_by)) 
      	{ 
       	 	$Order = $order_by; 
     	}
		
		$Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
		
		$join_qry = " LEFT JOIN dra_inst_registration ir ON ir.id = b.agency_id";
		$join_qry .= " LEFT JOIN agency_center ac ON ac.center_id = b.center_id";
		$join_qry .= " LEFT JOIN city_master cm ON cm.id = ac.location_name";
					
		$print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
		$Result = $this->db->query($print_query);  
		$Rows = $Result->result_array();
		
		$TotalResult = $this->numRow($column_order[0],$table." ".$join_qry,$WhereForTotal);
		$FilteredResult = $this->numRow($column_order[0],$table." ".$join_qry,$Where);
		
		$data = array();
		$no = $_POST['start'];
		
		foreach ($Rows as $Res) 
		{
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $Res['inst_name'];
			$row[] = $Res['CityName'];
			$row[] = $Res['batch_code'];
			$row[] = $Res['batch_name'].$Res['BatchFlag'];
			
			if( $Res['batch_from_date'] != '' && $Res['batch_to_date'] != '') 
			{ 
				$from_to_date = "FROM <strong>".date_format(date_create($Res['batch_from_date']),"d-M-Y")."</strong> TO <strong>".date_format(date_create($Res['batch_to_date']),"d-M-Y")."</strong>";
			}else { $from_to_date = "<strong>-batch date not added-</strong>"; }
			$row[] = $from_to_date;
			
			if( $Res['timing_from'] != ''  && $Res['timing_to'] != '') 
			{	
				$from_to_time = "FROM <strong>".$Res['timing_from']." </strong> TO <strong>".$Res['timing_to']."</strong> ";
			}
			else { $from_to_time = "<strong>-Batch Timing not added-</strong>"; }
			$row[] = $from_to_time;
			
			$row[] = date("Y-m-d H:i:s", strtotime($Res['created_on']));
			
			if($Res['batch_status'] == 'Approved' ) { $Res['BatchStatus'] = 'Go Ahead'; $div_class2 = '#004d00'; }
			elseif($Res['batch_status'] == 'Final Review' ) { $div_class2 = '#33cc33'; }
			elseif($Res['batch_status'] == 'Cancelled' ) { $div_class2 = '#ff0000'; }
			elseif($Res['batch_status'] == 'Hold' ) { $div_class2 = '#9900cc'; }
			elseif($Res['batch_status'] == 'UnHold' ) { $div_class2 = '#ff3399'; }
			elseif($Res['batch_status'] == 'Batch Error' ) { $div_class2 = '#cc0000'; }
			elseif($Res['batch_status'] == 'Rejected' ) { $div_class2 = '#800000'; }
			elseif($Res['batch_status'] == 'Re-Submitted' ) { $div_class2 = '#7b3ede'; }

			$row[] = '<span style="font-weight:800;color:'.$div_class2.'">'.$Res['BatchStatus'];
			$row[] = '<a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/batch/batch_detail/'.$Res['id'].'">View</a><a target="blank_" class="btn btn-xs" href="'.base_url().'iibfdra/batch/candidates_list/'.$Res['id'].'">All Candidates</a>';

			$data[] = $row; 
		}		
		//<a target="blank_" class="btn btn-xs" href="'.base_url().'iibfdra/batch/candidates_list/'.$Res['id'].'">All Candidates</a>';
		      
      ## CODE TO DISPLAY SEARCH FILTER DROPDOWN
      $agency_name_arr = $center_location_arr = $batch_code_arr = $batch_name_arr = array();
      
      $search_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $WhereWithoutSearch ";
			$SearchResult = $this->db->query($search_query);  
			$SearchRows = $SearchResult->result_array();
      foreach ($SearchRows as $Res)
      {
        $agency_name_arr[] = $Res['inst_name'];
				$center_location_arr[] = $Res['CityName'];
				$batch_code_arr[] = $Res['batch_code'];
				$batch_name_arr[] = $Res['batch_name'];
      }
  
      $agency_name_arr = array_unique($agency_name_arr); sort($agency_name_arr);
      $center_location_arr = array_unique($center_location_arr); sort($center_location_arr);
      $batch_code_arr = array_unique($batch_code_arr); sort($batch_code_arr);
      $batch_name_arr = array_unique($batch_name_arr); sort($batch_name_arr);
      
      $agency_name_str = $center_location_str = $batch_code_str = $batch_name_str = "<option value=''>All</option>";
		if(count($agency_name_arr) > 0)
		{
			foreach($agency_name_arr as $res)
			{
				if(trim($res) != "")
				{
					if($s_agency_name != "" && $s_agency_name == $res) { $sel = 'selected'; } else { $sel = ''; }
					$agency_name_str .= "<option value='".$res."' ".$sel.">".$res."</option>";
				}
			}
		}
  
  		if(count($center_location_arr) > 0)
		{
			foreach($center_location_arr as $res)
			{
				if(trim($res) != "")
				{
					if($s_center_location != "" && $s_center_location == $res) { $sel = 'selected'; } else { $sel = ''; }
					$center_location_str .= "<option value='".$res."' ".$sel.">".$res."</option>";
				}
			}
		}
  
  		if(count($batch_code_arr) > 0)
		{
			foreach($batch_code_arr as $res)
			{
				if(trim($res) != "")
				{
					if($s_batch_code != "" && $s_batch_code == $res) { $sel = 'selected'; } else { $sel = ''; }
					$batch_code_str .= "<option value='".$res."' ".$sel.">".$res."</option>";
				}
			}
		}
  
  		if(count($batch_name_arr) > 0)
		{
			foreach($batch_name_arr as $res)
			{
				if(trim($res) != "")
				{
					if($s_batch_name != "" && $s_batch_name == $res) { $sel = 'selected'; } else { $sel = ''; }
					$batch_name_str .= "<option value='".$res."' ".$sel.">".$res."</option>";
				}
			}
		}
	
	
		$output = array(
		"draw" => $_POST['draw'],
		"recordsTotal" => $TotalResult, //All result count
		"recordsFiltered" => $FilteredResult, //Disp result count
		/*"Query" => $print_query,*/
		"data" => $data,
		"agency_name_str" => $agency_name_str,
		"center_location_str" => $center_location_str,
		"batch_code_str" => $batch_code_str,
		"batch_name_str" => $batch_name_str,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function candidates_list($batch_id=0)
	{
		$data['batch_id'] = $batch_id ;
		$this->load->model('UserModel');
	
		$data["breadcrumb"] = '
		<ol class="breadcrumb">
			<li><a href="'.base_url().'iibfdra/batch/"><i class="fa fa-home"></i> Home</a></li>
			<li class=""><a href="'.base_url().'iibfdra/batch/"> Candidates List</a></li>
			<li class="active"><a href="'.base_url().'iibfdra/batch/candidates_list/'.$batch_id.'">All candidates</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/batch/candidates_list';	
		$this->load->view('iibfdra/admin/batch/candidates_list',$data);

	
	}
	
	public function get_candidates_data_ajax()
	{
		//	$login_agency=$this->session->userdata('dra_institute');
		//	$agency_id=$login_agency['dra_inst_registration_id'];
		//	$instcode = $login_agency['institute_code'];
		//$batch_id='13688';
		$table = 'dra_members m';
		$column_order = array('m.regid', 'cm.city_name', 'batch_code', 'ab.batch_name', 'm.regnumber', 'REPLACE(CONCAT(TRIM(m.firstname), " ", TRIM(m.middlename), " ", TRIM(m.lastname)), "  "," ") AS DispName', 'm.dateofbirth', 'm.email_id', 'ab.batch_from_date', 'ab.batch_to_date'); //SET COLUMNS FOR SORT 			
		
		$column_search = array('cm.city_name', 'batch_code', 'ab.batch_name', 'm.regnumber', 'REPLACE(CONCAT(TRIM(m.firstname), " ", TRIM(m.middlename), " ", TRIM(m.lastname)), "  "," ")', 'm.dateofbirth', 'm.email_id', 'ab.batch_from_date', 'ab.batch_to_date'); //SET COLUMN FOR SEARCH			
		$order_by = "ORDER BY m.regid DESC"; //;array('b.created_on' => 'DESC', 'b.updated_on' => 'DESC'); // DEFAULT ORDER
		
		$where_search = $s_batch_id = $s_member_no = '';
		if(isset($_POST['s_batch_id']) && $_POST['s_batch_id'] != "")
		{
   			$s_batch_id = trim($_POST['s_batch_id']);
			$where_search .= " AND m.batch_id = '".$s_batch_id."'";
		}
		
		if(isset($_POST['s_member_no']) && $_POST['s_member_no'] != "")
		{
    		$s_member_no = trim($_POST['s_member_no']);
			$where_search .= " AND m.regnumber LIKE '%".$s_member_no."%'";
		}
		
		$WhereForTotal 	= "WHERE  m.isdeleted = 0 ".$where_search; //DEFAULT WHERE CONDITION FOR ALL RECORDS
		//ab.agency_id = '".$agency_id."' AND m.inst_code = '".$instcode."' AND
		$Where = "WHERE m.isdeleted = 0 ".$where_search; //DEFAULT WHERE CONDITION 
		//ab.agency_id = '".$agency_id."' AND m.inst_code = '".$instcode."' AND 
		if($_POST['search']['value']) // DATATABLE SEARCH
		{
			$Where .= " AND (";
			for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( $this->custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
			$Where = substr_replace( $Where, "", -3 );
			$Where .= ')';
		}			
		
		$Order = ""; //DATATABLE SORT
		if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
		else if(isset($order_by)) 
  		{ 
    		$Order = $order_by; 
		}
		
		$Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
		
		$join_qry = " LEFT JOIN agency_batch ab ON ab.id = m.batch_id";
		$join_qry .= " LEFT JOIN agency_center ac ON ac.center_id = ab.center_id";
		$join_qry .= " LEFT JOIN city_master cm ON cm.id = ac.location_name";
		
		$print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
		$Result = $this->db->query($print_query);  
		$Rows = $Result->result_array();
		
		$TotalResult = $this->numRow($column_order[0],$table." ".$join_qry,$WhereForTotal);
		$FilteredResult = $this->numRow($column_order[0],$table." ".$join_qry,$Where);
		
		$data = array();
		$no = $_POST['start'];
		
		foreach ($Rows as $Res) 
		{
			$training_from = $Res['batch_from_date'];
			$training_to =$Res['batch_to_date'] ;
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $Res['city_name'];
			$row[] = $Res['batch_code'];
			$row[] = $Res['batch_name'];
			$row[] = $Res['regnumber'];
			$row[] = $Res['DispName'];
			$row[] = date("d-M-Y", strtotime($Res['dateofbirth']));
			$row[] = $Res['email_id'];
			
			$action_str = '';
		
			
			$action_str .= '<a  href="'.base_url().'iibfdra/batch/editApplicant/'.$Res['regid'].'" target="blank_">Edit</a>';				
			$row[] = $action_str;
			$data[] = $row; 
		}      
		
		$output = array(
		"draw" => $_POST['draw'],
		"recordsTotal" => $TotalResult, //All result count
		"recordsFiltered" => $FilteredResult, //Disp result count
		"Query" => $print_query,
		"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
		
	public function editApplicant()
	{
		//$id= $reg_id;
		$data = array();
		$data['examRes'] = array();
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		//	$decdexamcode =$_SESSION['excode'];
			
		//check if id is integer in url if not regdirect to home
		if(!intval($id)) 
		{
			$this->session->set_flashdata('error','No such applicant exists');
			redirect(base_url().'iibfdra/batch/');
		}
		
		$id = intval($id);
		// $examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_period');
		$this->db->select('agency_batch.*,dra_members.*,agency_center.center_id,agency_center.location_name,city_master.*,dra_member_exam.exam_medium,dra_member_exam.exam_center_code');
		$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
		$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
		$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
		$this->db->join('dra_member_exam','dra_member_exam.regid=dra_members.regid','LEFT');
		$examRes = $this->master_model->getRecords('dra_members',array('dra_members.regid'=>$id,'dra_members.isdeleted' => 0));
		//print_r( $this->db->last_query() ); die();
		//print_r($examRes); die;
		$regnumbers=$examRes[0]['regnumber'];
		if(count($examRes))
		{
			//print_r($examRes[0]); die;
			$data['examRes'] = $examRes[0];
		} 
		else 
		{ //check entered id details are present in db if not redirect to home
			$this->session->set_flashdata('error','No such applicant exists');
			redirect(base_url().'iibfdra/batch/');
		}
				
		//$data['chk_exam_mode'] = $chk_exam_mode = $examact[0]['dra_exam_mode']; //RPE or PHYSICAL
		if(isset($_POST['btnSubmit']))
		{				
			$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
			$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
			$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
			$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[50]');
			$this->form_validation->set_rules('addressline2','Address Line2','trim|max_length[50]');
			$this->form_validation->set_rules('city','City','trim|required|max_length[30]');
			$this->form_validation->set_rules('district','District','trim|required|max_length[30]');
			$this->form_validation->set_rules('state','State','trim|required');
			$this->form_validation->set_rules('pincode','Pin Code','trim|required|max_length[6]');
			//$this->form_validation->set_rules('dob1','Date of Birth','trim');
			$this->form_validation->set_rules('gender','Gender','required');
			$this->form_validation->set_rules('mobile','Mobile No.','required|max_length[10]|min_length[10]');
			$this->form_validation->set_rules('email','Email','valid_email|required|trim');
			//$this->form_validation->set_rules('exam_center','Exam Center','required|trim');
			//$this->form_validation->set_rules('exam_medium','Exam medium','required|trim');
			$this->form_validation->set_rules('edu_quali','Qualification','required');
			$this->form_validation->set_rules('idproof','Id Proof','required');
			$this->form_validation->set_rules('declaration1','Declaration','required');
			//$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt');
			$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
			$this->form_validation->set_rules('phone','Phone No','max_length[8]');
			
			if($this->form_validation->run()==TRUE)
			{
				$outputphoto1 = $outputsign1 = $outputidproof1 = $outputtcertificate1 = $outputqualicertificate1 = '';
				$photofnm = $signfnm = $idfnm = $trgfnm = $qualifnm = '';
				$photo_flg = $signature_flg = $id_flg = $tcertificate_flg = $qualicertificate_flg = 'N';
				
				$date = date('Y-m-d h:i:s');
				
				$image_size_error = 0;
				$image_size_error_message = array();
				
				//if( !empty($input) ) {
				if($this->input->post('hiddenphoto') != '')
				{
					$size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
					if($size)
					{
						$input = $this->input->post('hiddenphoto');
						
						$tmp_nm = strtotime($date).rand(0,100);
						$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
						$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
						file_put_contents($outputphoto, file_get_contents($input));
						if($regnumbers !='' || $regnumbers != 0)
						{
							$photofnm = "p_".$regnumbers.".jpg";
						}
						else
						{
							$photofnm = "p_".$tmp_nm.".jpg";
						}
						
						$photo_flg = 'Y';
					}
					else
					{
						$photofnm = $this->input->post('hiddenphoto');
					}
				}
				
				// generate dynamic scan signature
				
				//if( !empty($inputsignature) ) {
				if($this->input->post('hiddenscansignature') != '')
				{
					$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
					if($size)
					{
						$inputsignature = $_POST["hiddenscansignature"];
						
						$tmp_signnm = strtotime($date).rand(0,100);
						$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
						$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
						file_put_contents($outputsign, file_get_contents($inputsignature));
						if($regnumbers !='' || $regnumbers != 0){
								$signfnm = "s_".$regnumbers.".jpg";
						}else{
								$signfnm = "s_".$tmp_signnm.".jpg";
						}
						
						$signature_flg = 'Y';
					}
					else
					{
						$signfnm = $this->input->post('hiddenscansignature');
					}
				}
				
				// generate dynamic id proof
				
				//if( !empty($inputidproofphoto) ) {
				if($this->input->post('hiddenidproofphoto') != '')
				{
					$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
					if($size)
					{
						$inputidproofphoto = $_POST["hiddenidproofphoto"];
						
						$tmp_inputidproof = strtotime($date).rand(0,100);
						$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
						$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
						file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
						if($regnumbers !='' || $regnumbers != 0){
								$idfnm = "pr_".$regnumbers.".jpg";
						}else{
								$idfnm = "pr_".$tmp_inputidproof.".jpg";
						}
						
						$id_flg = 'Y';
					}
					else
					{
						$idfnm = $this->input->post('hiddenidproofphoto');
					}
				}
				
				// generate dynamic qualification certificate
				
				//if( !empty($input_qualicertificate) ) {
				if($this->input->post('hiddenqualicertificate') != '')
				{
					$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
					if($size)
					{
						$input_qualicertificate = $_POST["hiddenqualicertificate"];
						
						$tmp_qualicertificate = strtotime($date).rand(0,100);
						$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
						$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
						file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
						if($regnumbers !='' || $regnumbers != 0){
								$qualifnm = "degre_".$regnumbers.".jpg";
						}else{
								$qualifnm = "degre_".$tmp_qualicertificate.".jpg";
						}
						
						
						$qualicertificate_flg = 'Y';
					}
					else
					{
						$qualifnm =$this->input->post('hiddenqualicertificate'); 	
					}
				}
				// eof file upload code
				
				// check if invalid image error
				
				//eof code
				
				
				//$dmemexam_id = $this->input->post('dmemexam_id');
				//'training_certificate' => $trgfnm, removed by Manoj
				$update_data = array(	
				'namesub' => $this->input->post('sel_namesub'),
				'firstname'		=>$this->input->post('firstname'),
				'middlename'		=>$this->input->post('middlename'),
				'lastname'		=>$this->input->post('lastname'),
				'address1'		=>$this->input->post('addressline1'),
				'address2'		=>$this->input->post('addressline2'),
				'address3'		=> $this->input->post('addressline3'),
				'address4'		=> $this->input->post('addressline4'),
				'city'				=>$this->input->post('city'),
				'state'				=>$this->input->post('state'),
				'district'			=>$this->input->post('district'),
				'pincode'				=>$this->input->post('pincode'),
				//'dateofbirth'				=>$this->input->post('dob1'),
				'gender'				=>$this->input->post('gender'),
				'stdcode'			=>$this->input->post('stdcode'),
				'phone'			=>$this->input->post('phone'),
				'mobile'	=>$this->input->post('mobile'),
				'aadhar_no'	=> $this->input->post('aadhar_no'),	// added by Bhagwan Sahane, on 06-05-2017
				'email' => $this->input->post('email'),
				'qualification'	=>$this->input->post('edu_quali'),
				'idproof'	=>$this->input->post('idproof'),
				'scannedphoto' 	=> $photofnm,
				'scannedsignaturephoto' => $signfnm,
				'idproofphoto' 	=> $idfnm,
				'quali_certificate' => $qualifnm,
				'photo_flg' 	=> $photo_flg,
				'signature_flg' => $signature_flg,
				'id_flg' 		=> $id_flg,
				'tcertificate_flg' => $tcertificate_flg,
				'qualicertificate_flg' => $qualicertificate_flg ,
				'editedby' => date('Y-m-d H:i:s')
				
				);
				//print_r($update_data);
				$regid = $examRes[0]['regid'];
				$batchId = $examRes[0]['batch_id'];
				$registrationtype = $examRes[0]['registrationtype'];
			
				$training_from=$examRes[0]['batch_from_date'];
				$training_to=$examRes[0]['batch_to_date'];
										
					if($this->master_model->updateRecord('dra_members',$update_data,  array('regid'=>$regid)))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examRes[0];
					//	log_dra_user($log_title = "Edit Applicant Successful", $log_message = serialize($desc));
						$this->session->set_flashdata('success','Record updated successfully');
						redirect(base_url().'iibfdra/batch/editApplicant/'.$regid);
						//redirect(base_url().'iibfdra/DraExam/edit/'.$dmemexam_id);
						//redirect(base_url().$_SESSION['reffer']);
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examRes[0];
						//log_dra_user($log_title = "Edit Applicant Unsuccessful", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating record');
						redirect(base_url().'iibfdra/batch/editApplicant/'.$regid);
					}
				
				
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		$this->db->select('id,state_code,state_name');
		$this->db->where('state_master.state_delete', '0');
		$states = $this->master_model->getRecords('state_master');
		$this->db->where('city_master.city_delete', '0');
		$cities = $this->master_model->getRecords('city_master');
		
		//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
		$this->db->not_like('name','Election Voters card');
		$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
				
		$data['states'] = $states;
		$data['cities'] = $cities;
		$data['idtype_master'] = $idtype_master;
			
		$this->load->model('UserModel');
	
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/batch/">
		<i class="fa fa-home"></i> Home</a></li>

		<li class="active"><a href="'.base_url().'iibfdra/batch/editapplicant/'.$id.'">Edit Candidate</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/batch/candidate_edit';
		$this->load->view('iibfdra/admin/batch/candidate_edit',$data);		
	}
		
	function getAllRec($select,$table,$where,$order_by=null) // GET ALL RECORDS WITH SELECT STRING
	{		 			 
		$q = "select $select from $table $where $order_by";
		$query=$this->db->query($q);
		return $query->result_array();
	}
    
    function numRow($select,$table,$where,$order_by=null) // GET NUMBER OF ROWS
	{
		$q = "select $select from $table $where $order_by";
		$query=$this->db->query($q);
		return $query->num_rows();
	}
	
	function custom_safe_string($str="")
	{
		$str = str_replace('"',"&quot;",$str);
		$str = str_replace("'","&apos;",$str);
		return $str;
	}
	
		
	public function batch_list()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Batch/batch_list">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/batch/"> Traning Batch List</a></li>
		</ol>';	
	
		$url = base_url()."iibfdra/admin/agency/getList/";
	
		$this->db->where('dra_inst_registration.status',1);
		$res = $this->master_model->getRecords("dra_inst_registration");
		$data['agency_list'] = $res;
		$this->load->view('iibfdra/agency/agency_list',$data);		
	}	

	// function to view batch Details-
	public function batch_detail($batch_id)
	{
		$data['batch_id'] = $batch_id = $batch_id;
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/batch/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/batch/"> Traning Batch List</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/batch/batch_detail/'.$batch_id.'">Traning Batch Detail</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/batch/batch_detail';		
		
		// Accept and Reject Ajency 
		if(isset($_REQUEST['action_status'])) {	
			//AP = Active period
			if($_REQUEST['action_status'] != '' && $_REQUEST['action_status'] == 'AP' ){
				$this->agency_batch_active_period($batch_id);
			}elseif($_REQUEST['action_status'] != 'Approved' &&  $_REQUEST['action_status'] != '' && $_REQUEST['action_status'] != 'AP' &&  $_REQUEST['action_status'] != 'REPORT' && $_REQUEST['action_status'] != 'UPDATE_INSPECTOR'){
				$this->agency_batch_reject($batch_id);				
			}elseif($_REQUEST['action_status'] == 'Approved' && $_REQUEST['action_status'] != 'AP'){
				$this->agency_batch_approve($batch_id);
			}elseif($_REQUEST['action_status'] == 'REPORT'){
				
				//$this->load->helper('general_agency_helper');
				$drauserdata = $this->session->userdata('dra_admin');	
				$user_type_flag = $drauserdata['roleid'];	
				$updated_date = date('Y-m-d H:i:s');
				if($user_type_flag == 1){		
					$user_type = 'A';				
				}else{
					$user_type = 'R';		
				}
								
				$date = date('Y-m-d H:i:s');
				$tmp_nm = strtotime($date).rand(0,100);
				$new_filename = 'dra_inspector_report_'.$tmp_nm;
                $config=array('upload_path'=>'./uploads/iibfdra/agency_center' ,
						'allowed_types'=>'pdf|PDF|doc|DOC|docx|DOCX|txt|TXT|jpg|png|jpeg|JPG|PNG|JPEG',
						'file_name'=>$new_filename);
						$this->upload->initialize($config);
					/*File Uploadation of inspector report*/
                    if($_FILES['inspector_report']['name'] != '')
                    {
					  
					  $inspector_report=$_FILES['inspector_report']['name'];
					  if($this->upload->do_upload('inspector_report'))
                        {
                            $dt1=$this->upload->data();
							$inspector_report = $dt1['file_name'];												
							$update_data = array(							
									'inspector_report'	=> $inspector_report,												
									'updated_on'		=> $updated_date,
									'updated_by' 		=> $user_type_flag 
									);											
										
							$data['error'] = '';
							$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
							log_dra_admin($log_title = "DRA Admin Upload Inspection Report", $log_message = serialize($update_data));
							
							log_dra_agency_batch_detail($log_title = "DRA Admin Upload Inspection Report",$batch_id,serialize($update_data));
							
                        }
                        else
                        {							
                            $error =  $this->upload->display_errors();
							$data['error'] = $error;
                        }
                    }
				
			}elseif($_REQUEST['action_status'] == 'UPDATE_INSPECTOR'){				
				  $this->agency_batch_update_inspector($batch_id);
			}
			
		}
	
		$this->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname, f1.faculty_name as first_faculty_name, f1.academic_qualification as first_faculty_qualification, f2.faculty_name as sec_faculty_name, f2.academic_qualification as sec_faculty_qualification, f3.faculty_name as add_first_faculty_name, f3.academic_qualification as add_first_faculty_qualification, f4.faculty_name as add_sec_faculty_name, f4.academic_qualification as add_sec_faculty_qualification");	
		$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$this->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$this->db->join('city_master','agency_batch.city=city_master.id','LEFT');		
		$this->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$this->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');		
		$this->db->join('faculty_master f1','agency_batch.first_faculty=f1.faculty_id','left');
		$this->db->join('faculty_master f2','agency_batch.sec_faculty=f2.faculty_id','left');
		$this->db->join('faculty_master f3','agency_batch.additional_first_faculty=f3.faculty_id','left');
		$this->db->join('faculty_master f4','agency_batch.additional_sec_faculty=f4.faculty_id','left');
		$this->db->where('agency_batch.id = "'.$batch_id.'"');
		$this->db->where('agency_center.center_display_status','1'); // to hide centers and batches.		
		$res = $this->master_model->getRecords("agency_batch");	
		//echo "<pre>";print_r($res); echo "</pre>"; die;
		
		if(!count($res)){
			redirect(base_url().'iibfdra/Batch');
			exit;
		}
		
		$data['result'] = $res[0];
		
		$center_id = $res[0]['center_id'];
		$location_name = $res[0]['location_name']; // change done on 11 feb 2019  as per discussion with sonal 	
		$city_id = $res[0]['city'];
		
		if($res[0]['batch_online_offline_flag'] == 0)
		{
		$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_inspector_center.inspector_id','LEFT');			
		$this->db->where('agency_inspector_center.city = "'.$location_name.'"');	
		$this->db->where('agency_inspector_center.is_delete != 1');	
		$this->db->where('agency_inspector_master.is_delete != 1');
		$this->db->where('agency_inspector_master.is_active',1);
		$res_inspector = $this->master_model->getRecords("agency_inspector_center");	
		}
		else
		{
			$this->db->select('id as inspector_id, inspector_name');
			$this->db->where('is_delete != 1');
			$this->db->where('is_active',1);
			$this->db->where('batch_online_offline_flag',1);
			$res_inspector = $this->master_model->getRecords("agency_inspector_master");
		}
		//echo $this->db->last_query();
		//echo "<pre>";print_r($res_inspector); echo "</pre>"; die;
		$data['result_inspector'] = $res_inspector;
		
		$this->db->select('dra_accerdited_master.institute_code, dra_accerdited_master.institute_name,agency_batch.batch_code,agency_batch.batch_name,max(agency_batch.id) max_id,agency_inspector_master.id, agency_inspector_master.inspector_name, agency_inspector_master.inspector_mobile, agency_inspector_master.inspector_email, agency_inspector_master.inspector_designation,agency_inspector_center.inspector_id, agency_inspector_center.center_id, agency_inspector_center.state, agency_inspector_center.city');
		$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_inspector_center.inspector_id','LEFT');		
		$this->db->join('agency_batch','agency_inspector_master.id=agency_batch.inspector_id ');
		$this->db->join('dra_accerdited_master','agency_batch.agency_id=dra_accerdited_master.dra_inst_registration_id','LEFT');	
		$this->db->where('agency_inspector_center.city = "'.$location_name.'"');	
		$this->db->where('agency_inspector_center.is_delete != 1');	
		$this->db->where('agency_inspector_master.is_delete != 1');
		$this->db->where('agency_inspector_master.is_active',1);
		$this->db->where('agency_batch.id !=',$batch_id);
		$this->db->order_by('agency_inspector_center.id', 'desc');
		$this->db->limit(5,0);
		$this->db->group_by('agency_inspector_master.id'); 
		$res_inspectors = $this->master_model->getRecords("agency_inspector_center");
		//echo $this->db->last_query(); die;
		$resultarr = array();
		if( $res_inspectors) 
		{
			foreach( $res_inspectors as $result ) 
			{
				$batch = $this->master_model->getRecords('agency_batch',array('id'=>$result['max_id']), 'agency_batch.batch_code,agency_batch.batch_name,agency_batch.batch_from_date, agency_batch.batch_to_date, agency_batch.timing_from, agency_batch.timing_to');	
				if(COUNT($batch) > 0)
				{
					$batch =$batch[0];
					$result['batch_code'] = $batch['batch_code'];
					$result['batch_name'] = $batch['batch_name'];
					$result['batch_from_date'] = $batch['batch_from_date'];
					$result['batch_to_date'] = $batch['batch_to_date'];
					$result['timing_from'] = $batch['timing_from'];
					$result['timing_to'] = $batch['timing_to'];
					$resultarr[] = $result; 
				}
				else
				{
					$result['batch_code'] = '';
					$result['batch_name'] = '';
					$result['batch_from_date'] = '';
					$result['batch_to_date'] = '';
					$result['timing_from'] = '';
					$result['timing_to'] = '';
					$resultarr[] = $result; 
				}
			}	
		}						
		$data['result_inspectors'] = $resultarr;
		
		// Code to fetch Candidate list 
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=dra_members.medium_of_exam','LEFT');
		$this->db->where('dra_members.batch_id = "'.$batch_id.'"');	
		$this->db->where('dra_members.isdeleted = 0');
		$res_student = $this->master_model->getRecords("dra_members");		
		$data['result_student'] = $res_student;
		
		// Code to fetch Admin logs  
		$this->db->join('dra_admin','dra_agency_batch_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_batch_adminlogs.batch_id',$batch_id);
		$this->db->order_by('dra_agency_batch_adminlogs.date','DESC');
		$res_logs = $this->master_model->getRecords("dra_agency_batch_adminlogs");
		$data['agency_batch_logs'] = $res_logs;	
		//print_r($data['agency_batch_logs']); die;
		
		
		// check is any candidate applied for exam and payed fee		
		$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid','INNER');
		$this->db->where('dra_members.batch_id = "'.$batch_id.'"');
		$where = '(dra_member_exam.pay_status="1" or dra_member_exam.pay_status = "3")';
		$this->db->where($where);
		$res_student_exam_apply_chk = $this->master_model->getRecordCount("dra_member_exam");	
		if($res_student_exam_apply_chk > 0 ){
			$data['is_applied'] = 1;
		}else{
			$data['is_applied'] = 0;
		}	
		 
		########## START : CODE ADDED BY SAGAR ON 21-08-2020 ###################
		$online_batch_user_details = array(); 
		if(isset($res[0]['batch_online_offline_flag']) && $res[0]['batch_online_offline_flag'] != "")
		{
			if($res[0]['batch_online_offline_flag'] == 1)
			{
				$this->db->where('agency_id', $res[0]['agency_id']);
				$this->db->where('batch_id', $batch_id);
				$online_batch_user_details = $this->master_model->getRecords('agency_online_batch_user_details');
			}
		}
		$data['online_batch_user_details'] = $online_batch_user_details;
		########## END : CODE ADDED BY SAGAR ON 21-08-2020 ###################
		
		$this->load->view('iibfdra/admin/batch/batch_detail',$data);
		
	}
	
	public function candidate_detail($candidate_id){
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/batch/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/batch/"> Traning Batch List</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/batch/candidate_detail/'.$candidate_id.'">Candidate Detail</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/admin/batch/candidate_detail';	
		//state
		$this->db->join('state_master','state_master.state_code=dra_members.state','LEFT');
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=dra_members.medium_of_exam','LEFT');
		$this->db->where('dra_members.isdeleted = 0');	
		$this->db->where('dra_members.regid = "'.$candidate_id.'"');
		$res = $this->master_model->getRecords("dra_members");		
		$data['result'] = $res[0];
		
		$this->load->view('iibfdra/admin/batch/candidate_detail',$data);
	}

	// Approve Agency Center 
	public function agency_batch_active_period($batch_id){	
		  
		//$this->load->helper('general_agency_helper');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$updated_date = date('Y-m-d H:i:s');
		
		$batch_active_period 	= date ("Y-m-d", strtotime ($this->input->post('batch_active_period')));
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}
		
		$update_data = array(
						'batch_active_period'	=> $batch_active_period,
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);						

		$log_data =	array(	
						'agency_id'		=>	$this->input->post('agency_id'),
						'user_type'		=>	$user_type,	
						'batch_active_period'	=>  $batch_active_period,					
						'updated_by' 	=>  $user_type_flag,
						'user_id'		=>	$drauserdata['id'],
						'created_on'	=>	date('Y-m-d H:i:s'),
						);		
						
		log_dra_admin($log_title = "DRA Admin Assign Batch Active period", $log_message = serialize($update_data));
		$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
		log_dra_agency_batch_detail($log_title = "DRA Admin Assign Batch Active period",$batch_id,serialize($log_data));
		
	}
	

	// Approve Agency Center 
	public function agency_batch_approve($batch_id){	
		  
		//$this->load->helper('general_agency_helper');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$updated_date = date('Y-m-d H:i:s');
		$inspector_id = 0;
		
		if(isset($_REQUEST['rejection'])){
			$rejection = $_REQUEST['rejection'];
		}else{
			$rejection = '';
		}
		
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}
		
		if(isset($_REQUEST['inspector_id']) ){
			if($_REQUEST['inspector_id'] !=''){
			$inspector_id = $this->input->post('inspector_id');
			}else{
			 $inspector_id = 0;
			}
			
		}else{
			$inspector_id = 0;
		}	
		
		$update_data = array(
						'batch_status'	=> 'Approved',	
						'inspector_id'	=> $inspector_id,												
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);						

		if($rejection != '' && $inspector_id == 0 ){
			$msg = "DRA Admin Approved Agency Batch";
			$log_data =	array(	
							'agency_id'		=>	$this->input->post('agency_id'),
							'user_type'		=>	$user_type,	
							'inspector_id'	=>  $inspector_id,					
							'updated_by' 	=>  $user_type_flag,
							'batch_status'	=> 'Approved',	
							'rejection'		=>	$this->input->post('rejection'),
							'user_id'		=>	$drauserdata['id'],
							'created_on'	=>	date('Y-m-d H:i:s'),
							);	
		}else{
			
		$this->db->where('agency_inspector_master.id',$inspector_id);
		$this->db->limit(1);
		$res = $this->master_model->getRecords("agency_inspector_master");	
		if(count($res)){
			$inspector_name = $res[0]['inspector_name'];
			$inspector_email = $res[0]['inspector_email'];	
		}else{
			$inspector_name = '';
			$inspector_email = '';
		}
			
			$msg = "DRA Admin Approved Agency Batch and Assign Inspector ".$inspector_name ;
			$log_data =	array(	
							'agency_id'		=>	$this->input->post('agency_id'),
							'user_type'		=>	$user_type,	
							'inspector_id'	=>  $inspector_id,	
							'batch_status'	=> 'Approved',					
							'updated_by' 	=>  $user_type_flag,
							'user_id'		=>	$drauserdata['id'],
							'created_on'	=>	date('Y-m-d H:i:s'),
							);	
		}
						
		log_dra_admin($log_title = "DRA Admin Approved Agency Center", $log_message = serialize($update_data));
		$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
		
		if($inspector_name != '' && $inspector_email != ''){			
		  batch_inspection_mail($batch_id, $inspector_email); //added by Manoj	
		}
				
		log_dra_agency_batch_detail($log_title = $msg,$batch_id,serialize($log_data));		
		batch_approve_mail($batch_id,$user_type_flag,$user_type_flag);
		
	}
	
	
	// Update INSPECTOR
	public function agency_batch_update_inspector($batch_id){	
	//print_r($_POST); die;
		  
		//$this->load->helper('general_agency_helper');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$updated_date = date('Y-m-d H:i:s');
		
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}
		
		if(isset($_REQUEST['inspector_id']) ){ 
			
			$inspector_id = $this->input->post('inspector_id');	
			$this->db->where('agency_inspector_master.id',$inspector_id);
			$this->db->limit(1);
			$res = $this->master_model->getRecords("agency_inspector_master");	
			$inspector_name = $res[0]['inspector_name'];		
			$inspector_email = $res[0]['inspector_email'];	
		}else{
			
			$inspector_id = 0;
			$inspector_name = '';
			$inspector_email ='';
		}
			
		//'batch_status'	=> 'A',			
		$update_data = array(										
						'inspector_id'	=> $inspector_id,												
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);						

		$log_data =	array(	
						'agency_id'		=>	$this->input->post('agency_id'),
						'user_type'		=>	$user_type,
						'inspector_id'	=>  $inspector_id,
						'inspector_name'=>  $inspector_name,					
						'updated_by' 	=>  $user_type_flag,
						'user_id'		=>	$drauserdata['id'],
						'created_on'	=>	date('Y-m-d H:i:s'),
						);	
						//print_r($update_data);
						//print_r($log_data);	die;
						
		log_dra_admin($log_title = "DRA Admin Assign Inspector for Agency batch", $log_message = serialize($update_data));
		$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
		
		//batch_inspection_mail($batch_id, $inspector_email); //added by aayusha
		
		log_dra_agency_batch_detail($log_title = "DRA Admin Assign Inspector  ".$inspector_name."  for Agency Batch",$batch_id,serialize($log_data));

		
	}
	
	
	// Reject Agency Center
	public function agency_batch_reject($batch_id){	
	
		//$this->load->helper('general_agency_helper');
		$updated_date = date('Y-m-d H:i:s');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$batch_status = $_REQUEST['action_status']; 
		
		if($batch_status == 'Cancelled'){
		  $str_reson = ' Cancel ';
		  
		}elseif($batch_status == 'Hold'){
			
		  $str_reson = ' Hold ';
		  
		}elseif($batch_status == 'UnHold'){
			
		  $str_reson = 'UnHold ';	
		}else{
			
		  $str_reson = ' Reject ';	
		}
		
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}
		if($batch_status == 'UH'){
			
		  $update_data = array(
						'batch_status'	=> 'Approved',													
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);
		
		}else{
		$update_data = array(
						'batch_status'	=> $this->input->post('action_status'),														
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);
		}
		$insert_data = array(	
						'agency_id'		=>$this->input->post('agency_id'),
						'batch_id'		=>$batch_id,
						'user_type'		=>$user_type,
						'rejection_type'=>$this->input->post('action_status'),		
						'rejection'		=>$this->input->post('rejection'),
						'user_id'		=>$drauserdata['id'],
						'created_on'	=>date('Y-m-d H:i:s'),
						);
						
		$log_data =	array(	
						'agency_id'		=>$this->input->post('agency_id'),
						'user_type'		=>$user_type,
						'rejection'		=>$this->input->post('rejection'),
						'updated_by' 	=> $user_type_flag ,
						'user_id'		=>$drauserdata['id'],
						'created_on'	=>date('Y-m-d H:i:s'),
						);	
		
		if($this->master_model->insertRecord('agency_batch_rejection',$insert_data)){	
			$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
		}
		if($batch_status == 'Cancelled' )
		{
		  	batch_cancel_mail($batch_id,$user_type_flag);	
		}else
		{
			batch_reject_mail($batch_id,$user_type_flag);
		}
		
		log_dra_admin($log_title = "DRA Admin ".$str_reson." Agency Batch", $log_message = serialize($update_data));	
		log_dra_admin($log_title = "DRA Admin ".$str_reson." Agency Batch Add entry in agency_batch_rejection table", $log_message = serialize($insert_data));	
		log_dra_agency_batch_detail($log_title = "DRA Admin ".$str_reson." Agency Batch",$batch_id,serialize($log_data));
	}
	
	
	public function resend_email() 
	{
		$result = batch_inspection_mail_test('3357', 'sagar.matale@esds.co.in');//
		echo $result; 
	}
	
	public function agency_direct_login($agency_id='')
	{
		if($agency_id != '')
		{
			$dataarr=array('dra_accerdited_master.dra_inst_registration_id'=> $agency_id);
				
			$this->db->select('dra_accerdited_master.*,dra_inst_registration.id'); //added by aayusha 
			$this->db->join('dra_inst_registration ', 'dra_inst_registration.id = dra_accerdited_master.dra_inst_registration_id','left');	//added by aayusha 
			$this->db->where('dra_inst_registration.status',1);//added by aayusha 
			$this->db->from('dra_accerdited_master');
			$this->db->where($dataarr);
			$q = $this->db->get();
			$res['result'] = $q->result_array();
			$res['rows'] = $q->num_rows();
			//echo $this->db->last_query(); exit;
			if($res['rows'] == 1 ) 
			{
				$newdata = $res['result'][0];
				$this->session->set_userdata('dra_institute', $newdata);		
				redirect('iibfdra/InstituteHome/dashboard');	
			} 
			else /*added by aayusha-start */
			{
				$this->db->select('dra_inst_registration.status');
				$this->db->join('dra_accerdited_master', 'dra_inst_registration.id = dra_accerdited_master.dra_inst_registration_id','left');
				$get_inst_detail=$this->master_model->getRecords('dra_inst_registration', array(
				'dra_accerdited_master.institute_code'=> $this->input->post('Username'),
				'dra_accerdited_master.password'=> md5($this->input->post('Password')),
				'dra_inst_registration.status' => '0'
					));
				if(count($get_inst_detail) >0)
				{
					if($get_inst_detail[0]['status']==0)
					{
						$this->session->set_flashdata('error_message','Agency has been deactivited!');
						$data['error']='<span style="color:red;">Agency has been deactivited!</span>';	
					}
				}/*added by aayusha-end */
				else
				{
					$this->session->set_flashdata('error_message','Invalid Credentials');
					$data['error']='<span style="color:red;">Invalid Credentials</span>';
				}
			}
		}
	}
} ?>