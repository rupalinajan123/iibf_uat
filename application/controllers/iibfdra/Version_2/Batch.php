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
			redirect('iibfdra/Version_2/admin/Login');
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
		<li><a href="'.base_url().'iibfdra/Version_2/Batch/">
		<i class="fa fa-home"></i> Home </a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/batch/"> Traning Batch List</a></li>
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
	
			
		$this->load->view('iibfdra/Version_2/admin/batch/batch_list',$data);
	}
		
	public function get_table_data_ajax()
	{
		$table = 'agency_batch b';
		$column_order = array('b.id','b.total_registered_candidates','COUNT(bi.id) as reported','b.training_medium','ai.inspector_name','b.hours','b.holidays','ir.inst_name', 'IF(cm.city_name = "", ac.location_name, cm.city_name) AS CityName', 'b.batch_code', 'b.batch_name', 'b.batch_from_date', 'b.timing_from', 'b.created_on', 
		'b.batch_status AS BatchStatus'			
		,'b.batch_to_date', 'b.timing_to', 'b.batch_status', 'IF(b.batch_online_offline_flag = "1", " (Online)", "(Offline)") AS BatchFlag');

		$column_order_drop_down = array('b.id','b.training_medium','b.hours','ai.inspector_name','b.holidays','ir.inst_name', 'IF(cm.city_name = "", ac.location_name, cm.city_name) AS CityName', 'b.batch_code', 'b.batch_name', 'b.batch_from_date', 'b.timing_from', 'b.created_on', 
		'b.batch_status AS BatchStatus'			
		,'b.batch_to_date', 'b.timing_to', 'b.batch_status', 'IF(b.batch_online_offline_flag = "1", " (Online)", "(Offline)") AS BatchFlag');

		 //SET COLUMNS FOR SORT 
		
		$column_search = array('ir.inst_name', 'IF(cm.city_name = "", ac.location_name, cm.city_name)', 'b.batch_code', 'b.batch_name', 'b.batch_from_date', 'b.timing_from', 'b.created_on',
		'b.batch_status'			
		,'b.batch_to_date', 'b.timing_to', 'b.batch_status', 'IF(b.batch_online_offline_flag = "1", " (Online)", "(Offline)")'); //SET COLUMN FOR SEARCH
		$order_by = "ORDER BY b.created_on DESC, b.id DESC, b.updated_on DESC"; //;array('b.created_on' => 'DESC', 'b.updated_on' => 'DESC'); // DEFAULT ORDER
		
		$where_search = $s_agency_name = $s_center_location = $s_batch_code = $s_batch_name = $s_modoe_of_training = '';
		if(isset($_POST['s_agency_name']) && $_POST['s_agency_name'] != "")
		{
    		$s_agency_name = html_entity_decode(trim($_POST['s_agency_name']));
			$where_search .= " AND ir.inst_name LIKE '%".$s_agency_name."%'";
		}
		
		if(isset($_POST['s_center_location']) && $_POST['s_center_location'] != "")
		{
   			$s_center_location = html_entity_decode(trim($_POST['s_center_location']));
			$where_search .= " AND IF(cm.city_name = '', ac.location_name, cm.city_name) LIKE '%".$s_center_location."%'";
		}
		
		if(isset($_POST['s_batch_code']) && $_POST['s_batch_code'] != "")
		{
    		$s_batch_code = html_entity_decode(trim($_POST['s_batch_code']));
			$where_search .= " AND b.batch_code LIKE '%".$s_batch_code."%'";
		}
		
		/*if(isset($_POST['s_batch_name']) && $_POST['s_batch_name'] != "")
		{
    	$s_batch_name = html_entity_decode(trim($_POST['s_batch_name']));
			$where_search .= " AND b.batch_name LIKE '%".$s_batch_name."%'";
		}*/

		if(isset($_POST['s_batch_hours']) && $_POST['s_batch_hours'] != "")
		{
    	$s_batch_hours = $_POST['s_batch_hours'];
			$where_search .= " AND b.hours = ".$s_batch_hours;
		}

		if(isset($_POST['s_batch_from_date']) && $_POST['s_batch_from_date'] != "" && isset($_POST['s_batch_to_date']) && $_POST['s_batch_to_date'] != "")
		{
    	$s_batch_from_date  = $_POST['s_batch_from_date'];
    	$s_batch_to_date    = $_POST['s_batch_to_date'];
			
			$where_search .= " AND (
	        ('" . $s_batch_from_date . "' BETWEEN b.batch_from_date AND b.batch_to_date) OR
	        ('" . $s_batch_to_date . "' BETWEEN b.batch_from_date AND b.batch_to_date) OR
	        (b.batch_from_date BETWEEN '" . $s_batch_from_date . "' AND '" . $s_batch_to_date . "') OR
	        (b.batch_to_date BETWEEN '" . $s_batch_from_date . "' AND '" . $s_batch_to_date . "')
	    )";

		}

		if(isset($_POST['s_inspector_name']) && $_POST['s_inspector_name'] != "")
		{
    	$s_inspector_name = html_entity_decode(trim($_POST['s_inspector_name']));
			$where_search .= " AND ai.inspector_name LIKE '%".$s_inspector_name."%'";
		}
		
		if(isset($_POST['s_modoe_of_training']) && $_POST['s_modoe_of_training'] != "")
		{
    	$s_modoe_of_training = html_entity_decode(trim($_POST['s_modoe_of_training']));
			$where_search .= " AND b.batch_online_offline_flag = ".$s_modoe_of_training;
		}

		$DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
		$DRA_Version_2_instIdStr = implode(',',$DRA_Version_2_instId);

		$WhereForTotal 	= "WHERE 1=1 ".$where_search; //DEFAULT WHERE CONDITION FOR ALL RECORDS
		$Where = "WHERE 1=1 AND batch_status <> 'In Review' AND b.agency_id IN (".$DRA_Version_2_instIdStr.")".$where_search; //DEFAULT WHERE CONDITION 
		$WhereWithoutSearch = "WHERE 1=1 AND b.agency_id IN (".$DRA_Version_2_instIdStr.")";

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
		
		
		$join_qry =  " LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id";
		$join_qry .=  " LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id";
		$join_qry .= " LEFT JOIN dra_inst_registration ir ON ir.id = b.agency_id";
		$join_qry .= " LEFT JOIN agency_center ac ON ac.center_id = b.center_id";
		$join_qry .= " LEFT JOIN city_master cm ON cm.id = ac.location_name";

		$join_qry_drop_down =  " LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id";
		$join_qry_drop_down .= " LEFT JOIN dra_inst_registration ir ON ir.id = b.agency_id";
		$join_qry_drop_down .= " LEFT JOIN agency_center ac ON ac.center_id = b.center_id";
		$join_qry_drop_down .= " LEFT JOIN city_master cm ON cm.id = ac.location_name";
					
		$print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where GROUP BY b.id $Order $Limit "; //ACTUAL QUERY
		// echo "Main Query :<br>".$print_query;
		$Result = $this->db->query($print_query); 
		
		$Rows = $Result->result_array();
		$sql_query = $this->db->last_query(); 
		
		$q = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where GROUP BY b.id";
		// echo "<br>Total Query :<br>".$q;
		$query=$this->db->query($q);
		$TotalResult = $query->num_rows();

		$Filteredq = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where GROUP BY b.id";
		// echo "<br>Filter Query :<br>".$Filteredq;
		$Filteredquery=$this->db->query($Filteredq);
		$FilteredResult = $Filteredquery->num_rows();
		
		// $TotalResult = $this->numRow($column_order[0],$table." ".$join_qry,$Where);
		// $FilteredResult = $this->numRow($column_order[0],$table." ".$join_qry,$Where);
		// echo $TotalResult.' ---  '.$FilteredResult; exit;

		$data = array();
		$no = $_POST['start'];
		
		foreach ($Rows as $Res) 
		{
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $Res['inst_name'];
			$row[] = $Res['CityName'];
			$row[] = $Res['total_registered_candidates'];
			$row[] = $Res['batch_code'];
			$row[] = $Res['inspector_name'];
			$row[] = $Res['reported'];
			$row[] = $Res['training_medium'];
			$row[] = $Res['batch_name'].$Res['BatchFlag'];
			$row[] = $Res['hours'];
			
			if( $Res['batch_from_date'] != '' && $Res['batch_to_date'] != '') 
			{ 
				$from_to_date = "FROM <strong>".date_format(date_create($Res['batch_from_date']),"d-M-Y")."</strong> TO <strong>".date_format(date_create($Res['batch_to_date']),"d-M-Y")."</strong>";
			}else { $from_to_date = "<strong>-batch date not added-</strong>"; }
			$row[] = $from_to_date;
			
			$row[] = str_replace(',',', ',$Res['holidays']);
			// $row[] = $Res['holidays'];

			if( $Res['timing_from'] != ''  && $Res['timing_to'] != '') 
			{	
				$from_to_time = "FROM <strong>".$Res['timing_from']." </strong> TO <strong>".$Res['timing_to']."</strong> ";
			}
			else { $from_to_time = "<strong>-Batch Timing not added-</strong>"; }
			$row[] = $from_to_time;
			
			$row[] = date("Y-m-d H:i:s", strtotime($Res['created_on']));

			$div_class2 = '';
			
			//echo $Res['batch_status']; 
			
			if($Res['batch_status'] == 'Approved' ) { $Res['BatchStatus'] = 'Go Ahead'; $div_class2 = '#004d00'; }
			elseif($Res['batch_status'] == 'Final Review' ) { $div_class2 = '#33cc33'; }
			elseif($Res['batch_status'] == 'Cancelled' ) { $div_class2 = '#ff0000'; }
			elseif($Res['batch_status'] == 'Hold' ) { $div_class2 = '#9900cc'; }
			elseif($Res['batch_status'] == 'UnHold' ) { $div_class2 = '#ff3399'; }
			elseif($Res['batch_status'] == 'Batch Error' ) { $div_class2 = '#FF8C00'; }
			elseif($Res['batch_status'] == 'Rejected' ) { $div_class2 = '#800000'; }
			elseif($Res['batch_status'] == 'Re-Submitted' ) { $div_class2 = '#7b3ede'; }

			$row[] = '<span style="font-weight:800;color:'.$div_class2.'">'.$Res['BatchStatus'];

			$batch_communication = $this->master_model->getRecords("dra_batch_communication",array('batch_id'=>$Res['id'], 'notification_flag' => 1));

			$cls = '';
			if(count($batch_communication) >0 ){
				$cls = '<span class="bell_shake"><i class="fa fa-bell"></i><span class="notifn_cnt">'.count($batch_communication).'</span></span>';
			}

			$row[] = '<a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/Version_2/batch/batch_detail/'.base64_encode($Res['id']).'" target="_blank">View '.$cls.'</a><a target="blank_" class="btn btn-xs" href="'.base_url().'iibfdra/Version_2/batch/candidates_list/'.base64_encode($Res['id']).'">All Candidates</a>';

			$data[] = $row; 
		}
		//die;		
		//<a target="blank_" class="btn btn-xs" href="'.base_url().'iibfdra/Version_2/batch/candidates_list/'.$Res['id'].'">All Candidates</a>';
		      
      ## CODE TO DISPLAY SEARCH FILTER DROPDOWN
      $agency_name_arr = $center_location_arr = $batch_code_arr = $batch_name_arr = $inspector_name_arr = array();
      
      $search_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order_drop_down))." FROM $table $join_qry_drop_down $WhereWithoutSearch ";
			$SearchResult = $this->db->query($search_query);  
			$SearchRows = $SearchResult->result_array();
      
      // echo $this->db->last_query(); exit;	

      foreach ($SearchRows as $Res)
      {
        $agency_name_arr[] = $Res['inst_name'];
				$center_location_arr[] = $Res['CityName'];
				$batch_code_arr[] = $Res['batch_code'];
				// $batch_name_arr[] = $Res['batch_name'];
      	$inspector_name_arr[] = $Res['inspector_name'];
      }
  
      $agency_name_arr = array_unique($agency_name_arr); sort($agency_name_arr);
      $center_location_arr = array_unique($center_location_arr); sort($center_location_arr);
      $batch_code_arr = array_unique($batch_code_arr); sort($batch_code_arr);
      // $batch_name_arr = array_unique($batch_name_arr); sort($batch_name_arr);
      $inspector_name_arr = array_unique($inspector_name_arr); sort($inspector_name_arr);
      
      $agency_name_str = $center_location_str = $batch_code_str = $batch_name_str = $inspector_name_str = "<option value=''>All</option>";
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
  
  	/*if(count($batch_name_arr) > 0)
		{
			foreach($batch_name_arr as $res)
			{
				if(trim($res) != "")
				{
					if($s_batch_name != "" && $s_batch_name == $res) { $sel = 'selected'; } else { $sel = ''; }
					$batch_name_str .= "<option value='".$res."' ".$sel.">".$res."</option>";
				}
			}
		}*/
		
		if(count($inspector_name_arr) > 0)
		{
			foreach($inspector_name_arr as $res)
			{
				if(trim($res) != "")
				{
					if($s_inspector_name != "" && $s_inspector_name == $res) { $sel = 'selected'; } else { $sel = ''; }
					$inspector_name_str .= "<option value='".$res."' ".$sel.">".$res."</option>";
				}
			}
		}

	
		$output = array(
		"draw" => $_POST['draw'],
		"recordsTotal" => $TotalResult, //All result count
		"recordsFiltered" => $FilteredResult, //Disp result count
		/*"Query" => $print_query,*/
		"data" => $data,
		'sql_query'=>$sql_query,
		"agency_name_str" => $agency_name_str,
		"center_location_str" => $center_location_str,
		"batch_code_str" => $batch_code_str,
		// "batch_name_str" => $batch_name_str,
		"inspector_name_str" => $inspector_name_str
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function candidates_list($batch_id=0)
	{	
		$batchId = base64_decode($batch_id);
		$data['batch_id'] = $batch_id = $batchId;
		$this->load->model('UserModel');
	//echo "test123";die;
		$data["breadcrumb"] = '
		<ol class="breadcrumb">
			<li><a href="'.base_url().'iibfdra/Version_2/batch/"><i class="fa fa-home"></i> Home</a></li>
			<li class=""><a href="'.base_url().'iibfdra/Version_2/batch/"> Candidates List</a></li>
			<li class="active"><a href="'.base_url().'iibfdra/Version_2/batch/candidates_list/'.$batch_id.'">All candidates</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/batch/candidates_list';	
		$this->load->view('iibfdra/Version_2/admin/batch/candidates_list',$data);

	
	}
	
	public function get_candidates_data_ajax()
	{
		//	$login_agency=$this->session->userdata('dra_institute');
		//	$agency_id=$login_agency['dra_inst_registration_id'];
		//	$instcode = $login_agency['institute_code'];
		//$batch_id='13688';
		$table = 'dra_members m';
		$column_order = array('m.regid', 'cm.city_name', 'batch_code', 'ab.batch_name', 'm.regnumber', 'm.training_id','REPLACE(CONCAT(TRIM(m.firstname), " ", TRIM(m.middlename), " ", TRIM(m.lastname)), "  "," ") AS DispName', 'm.dateofbirth', 'm.email_id', 'ab.batch_from_date', 'ab.batch_to_date', 'm.hold_release','(
        SELECT COUNT(*) 
        FROM dra_member_exam dme 
        WHERE dme.regid = m.regid AND dme.pay_status = 1
    ) AS dra_mem_exam_count'); //SET COLUMNS FOR SORT 			
		
		$column_search = array('cm.city_name', 'batch_code', 'ab.batch_name', 'm.regnumber','m.training_id','REPLACE(CONCAT(TRIM(m.firstname), " ", TRIM(m.middlename), " ", TRIM(m.lastname)), "  "," ")', 'm.dateofbirth', 'm.email_id', 'ab.batch_from_date', 'ab.batch_to_date', 'm.hold_release'); //SET COLUMN FOR SEARCH			
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
		
		$DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
		$str = implode(',', $DRA_Version_2_instId);

		$WhereForTotal 	= "WHERE  m.isdeleted = 0".$where_search; //DEFAULT WHERE CONDITION FOR ALL RECORDS
		 
		// $Where = "WHERE m.isdeleted = 0 AND ab.agency_id IN (".$str.")".$where_search; //DEFAULT WHERE CONDITION 
		
		$Where = "WHERE m.isdeleted = 0 AND ((ab.agency_id = 58 AND m.regnumber = '801577949') OR (ab.agency_id != 58 AND ab.agency_id IN (".$str.")))".$where_search;

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
		// echo $this->db->last_query(); exit; 
		$TotalResult = $this->numRow($column_order[0],$table." ".$join_qry,$WhereForTotal);
		$FilteredResult = $this->numRow($column_order[0],$table." ".$join_qry,$Where);
		
		$data = array();
		$no = $_POST['start'];
		
		foreach ($Rows as $Res) 
		{
			$training_from = $Res['batch_from_date'];
			$training_to =$Res['batch_to_date'] ;

			// $check_exam_qry = $this->db->query("SELECT id from dra_member_exam WHERE regid = ".$Res['regid']." AND pay_status = 1");  
			// $check_exam_applied = $check_exam_qry->result_array();
			if($Res['dra_mem_exam_count']>0){
				$exam_applied = 'Yes';
			}
			else{
				$exam_applied = 'No';
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $Res['city_name'];
			$row[] = $Res['batch_code'];
			$row[] = $Res['batch_name'];
			$row[] = $Res['regnumber'];
			$row[] = $Res['training_id'];
			$row[] = $Res['DispName'];
			$row[] = date("d-M-Y", strtotime($Res['dateofbirth']));
			$row[] = $Res['email_id'];
			$row[] = $Res['hold_release'];
			$row[] = $exam_applied;
			
			$action_str = '';
		
			
			$action_str .= '<a  href="'.base_url().'iibfdra/Version_2/batch/editApplicant/'.$Res['regid'].'/'.$s_batch_id.'" target="blank_">Edit</a>';
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
	
		
	public function editApplicant($id=0, $url_batch_id=0)
	{
		//$id= $reg_id;
		$data = array();
		$data['examRes'] = array();
   		$data['url_batch_id'] = $url_batch_id;
		//$last = $this->uri->total_segments();
		//$id = $this->uri->segment($last);
		//	$decdexamcode =$_SESSION['excode'];

			
		//check if id is integer in url if not regdirect to home
		if(!intval($id)) 
		{
			$this->session->set_flashdata('error','No such applicant exists');
			redirect(base_url().'iibfdra/Version_2/batch/');
		}
		
		$id = intval($id);

		$this->db->where_not_in('form_type', ['invalid_all', 'invalid_qual', 'invalid_photo','EditOtherDetails Function']);
		$candidate_icon = $this->master_model->updateRecord("dra_candidate_logs",array('is_read' => '1'),array('candidate_id'=>$id));
		
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
			
			} else { //check entered id details are present in db if not redirect to home
			$this->session->set_flashdata('error','No such applicant exists');
			redirect(base_url().'iibfdra/Version_2/batch/');
		}

		$data['batch_id'] = $examRes[0]['batch_id'];
				
		//$data['chk_exam_mode'] = $chk_exam_mode = $examact[0]['dra_exam_mode']; //RPE or PHYSICAL
		if(isset($_POST['btnSubmit']))
		{				
			$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
			$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
			$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
			$this->form_validation->set_rules('dob_date','Date of Birth','trim|xss_clean|required');
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
				
        //echo '<pre>'; print_r($_POST); print_r($_FILES); print_r($examRes[0]); //exit;

				//if( !empty($input) ) {
				//if($this->input->post('hiddenphoto') != '')
        $photofnm = $examRes[0]['scannedphoto'];
        if($_FILES['drascannedphoto']['tmp_name'] != '')
				{
          $size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
					if($size)
					{
						//$input = $this->input->post('hiddenphoto');
            $input = $_FILES['drascannedphoto']['tmp_name'];

            if($regnumbers !='' || $regnumbers != 0) { $tmp_nm = $regnumbers; }
            else { $tmp_nm = strtotime($date).rand(0,100); }
						
						$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
						$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
						file_put_contents($outputphoto, file_get_contents($input));
						$photofnm = "p_".$tmp_nm.".jpg";
						
						$photo_flg = 'Y';
					}
					else
					{
						//$photofnm = $this->input->post('hiddenphoto'); 
					}
				}        
				
				// generate dynamic scan signature
				
				//if( !empty($inputsignature) ) {
				//if($this->input->post('hiddenscansignature') != '')
        $signfnm = $examRes[0]['scannedsignaturephoto'];
        if($_FILES['drascannedsignature']['tmp_name'] != '')
				{
					$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
					if($size)
					{
						//$inputsignature = $_POST["hiddenscansignature"];
            $inputsignature = $_FILES['drascannedsignature']['tmp_name'];
						
            if($regnumbers !='' || $regnumbers != 0) { $tmp_signnm = $regnumbers; }
            else { $tmp_signnm = strtotime($date).rand(0,100); }

						$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
						$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
						file_put_contents($outputsign, file_get_contents($inputsignature));
						$signfnm = "s_".$tmp_signnm.".jpg";
						
						$signature_flg = 'Y';
					}
					else
					{
						//$signfnm = $this->input->post('hiddenscansignature');						
					}
				}
				
				// generate dynamic id proof
				
				//if( !empty($inputidproofphoto) ) {
				//if($this->input->post('hiddenidproofphoto') != '')
        $idfnm = $examRes[0]['idproofphoto'];
        if($_FILES['draidproofphoto']['tmp_name'] != '')
				{
					$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
					if($size)
					{
						//$inputidproofphoto = $_POST["hiddenidproofphoto"];
            $inputidproofphoto = $_FILES['draidproofphoto']['tmp_name'];
						
            if($regnumbers !='' || $regnumbers != 0) { $tmp_inputidproof = $regnumbers; }
            else { $tmp_inputidproof = strtotime($date).rand(0,100); }

						$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
						$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
						file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
						$idfnm = "pr_".$tmp_inputidproof.".jpg";
						
						$id_flg = 'Y';
					}
					else
					{
						//$idfnm = $this->input->post('hiddenidproofphoto');						
					}
				}
				
				// generate dynamic qualification certificate
				
				//if( !empty($input_qualicertificate) ) {
				//if($this->input->post('hiddenqualicertificate') != '')
        $qualifnm = $examRes[0]['quali_certificate'];
        if($_FILES['qualicertificate']['tmp_name'] != '')
				{
					$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
					if($size)
					{
						//$input_qualicertificate = $_POST["hiddenqualicertificate"];
            $input_qualicertificate = $_FILES['qualicertificate']['tmp_name'];
						
            if($regnumbers !='' || $regnumbers != 0) { $tmp_qualicertificate = $regnumbers; }
            else { $tmp_qualicertificate = strtotime($date).rand(0,100); }

						$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
						$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
						file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
            $qualifnm = "degre_".$tmp_qualicertificate.".jpg";						
						
						$qualicertificate_flg = 'Y';
					}
					else
					{
						//$qualifnm =$this->input->post('hiddenqualicertificate'); 							
					}
				}
				// eof file upload code
				
				// check if invalid image error
				
				//eof code
				
				//START : CODE ADDED BY SAGAR ON 29-01-2022
				if($photo_flg == 'N')
				{
					if(date('Y-m-d', strtotime($examRes[0]['editedby'])) == date('Y-m-d'))
					{
						$photo_flg = $examRes[0]['photo_flg'];
					}
				}
				
				if($signature_flg == 'N')
				{
					if(date('Y-m-d', strtotime($examRes[0]['editedby'])) == date('Y-m-d'))
					{
						$signature_flg = $examRes[0]['signature_flg'];
					}
				}
				
				if($id_flg == 'N')
				{
					if(date('Y-m-d', strtotime($examRes[0]['editedby'])) == date('Y-m-d'))
					{
						$id_flg = $examRes[0]['id_flg'];
					}
				}
				//END : CODE ADDED BY SAGAR ON 29-01-2022
				
				
				//$dmemexam_id = $this->input->post('dmemexam_id');
				//'training_certificate' => $trgfnm, removed by Manoj
				$update_data = array(	
				'namesub' => $this->input->post('sel_namesub'),
				'firstname'		=>$this->input->post('firstname'),
				'middlename'		=>$this->input->post('middlename'),
				'lastname'		=>$this->input->post('lastname'),
				'dateofbirth' => $this->input->post('dob_date'),
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
				'mobile_no'	=>$this->input->post('mobile'),
				'aadhar_no'	=> $this->input->post('aadhar_no'),	// added by Bhagwan Sahane, on 06-05-2017
				'email_id' => $this->input->post('email'),
				'qualification'	=>$this->input->post('edu_quali'),
				'idproof'	=>$this->input->post('idproof'),
				'idproof_no'	=>$this->input->post('idproof_no'),
				'scannedphoto' 	=> $photofnm,
				'scannedsignaturephoto' => $signfnm,
				'idproofphoto' 	=> $idfnm,
				'quali_certificate' => $qualifnm,
				'photo_flg' 	=> $photo_flg,
				'signature_flg' => $signature_flg,
				'id_flg' 		=> $id_flg,
				'tcertificate_flg' => $tcertificate_flg,
				'qualicertificate_flg' => $qualicertificate_flg ,
				'editedby' => date('Y-m-d H:i:s'),
				'editedon' => date('Y-m-d H:i:s'),
				'edited_by_id' => $this->UserID
				);
				//print_r($update_data);die;
				$regid = $examRes[0]['regid'];
				$batchId = $examRes[0]['batch_id'];
				$registrationtype = $examRes[0]['registrationtype'];
			
				$training_from=$examRes[0]['batch_from_date'];
				$training_to=$examRes[0]['batch_to_date'];
				$updRes = $this->master_model->updateRecord('dra_members',$update_data,  array('regid'=>$regid));
				//echo $this->db->last_query(); die;			
					if($updRes)
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examRes[0];

            //log_dra_user($log_title, $log_message) //general_helper.php
					  //log_dra_user($log_title = "Edit Applicant Successful", $log_message = serialize($desc));
            log_dra_user('DRA Update Member Successful by Admin : '.$regid, serialize($desc));

            $add_cand_log['action'] = 'Update';   
            $add_cand_log['form_type'] = 'form2';               
            $add_cand_log['candidate_id'] = $regid;
            $add_cand_log['log_title'] = 'Candidate successfully updated by Admin ';
            $add_cand_log['log_decription'] = serialize($desc);
            $add_cand_log['status'] = 'success';
            $add_cand_log['created_by_type'] = 'admin';
            $add_cand_log['created_by'] = $this->UserID;
            $add_cand_log['created_on'] = date("Y-m-d H:i:s");
            $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);

						$this->session->set_flashdata('success','Record updated successfully');
						redirect(base_url().'iibfdra/Version_2/batch/editApplicant/'.$regid);
						//redirect(base_url().'iibfdra/Version_2/DraExam/edit/'.$dmemexam_id);
						//redirect(base_url().$_SESSION['reffer']);
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examRes[0];
						//log_dra_user($log_title = "Edit Applicant Unsuccessful", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating record');
						redirect(base_url().'iibfdra/Version_2/batch/editApplicant/'.$regid);
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

		// echo $examRes[0]["state"];

		$this->db->where('city_master.state_code', $examRes[0]["state"]);
		$this->db->where('city_master.city_delete', '0');
		$data['cities_edit'] = $this->master_model->getRecords('city_master');
	
		//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
		$this->db->not_like('name','Election Voters card');
		$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
				
		$data['states'] = $states;
		$data['cities'] = $cities;
		$data['idtype_master'] = $idtype_master;
			
		$this->load->model('UserModel');
	
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Version_2/batch/">
		<i class="fa fa-home"></i> Home</a></li>

		<li class="active"><a href="'.base_url().'iibfdra/Version_2/batch/editapplicant/'.$id.'">Edit Candidate</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/batch/candidate_edit';
		$data["DraCandidateLogs"] = $this->getDraCandidateLogs($id);
		$this->load->view('iibfdra/Version_2/admin/batch/candidate_edit',$data);		
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
		<li><a href="'.base_url().'iibfdra/Version_2/Batch/batch_list">
		<i class="fa fa-home"></i> Home</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/batch/"> Traning Batch List</a></li>
		</ol>';	
	
		$url = base_url()."iibfdra/Version_2/admin/agency/getList/";
	
		$this->db->where('dra_inst_registration.status',1);
		$res = $this->master_model->getRecords("dra_inst_registration");
		$data['agency_list'] = $res;
		$this->load->view('iibfdra/Version_2/agency/agency_list',$data);		
	}	

	function fun_check_extend_date($str, $batch_id='') // Custom callback function for check extend date
  {
    if($str != '')
    {
      $current_val = $str;
      
      $batch_data = $this->master_model->getRecords('agency_batch', array('id' => $batch_id, 'is_deleted' => '0'), 'batch_from_date, batch_to_date');
      
      if(count($batch_data) > 0) 
      { 
        $extend_date = $current_val;
        $batch_chk_date_start = $batch_data[0]['batch_from_date'];
        $batch_chk_date_end = $batch_data[0]['batch_to_date'];
      
        if($extend_date < $batch_chk_date_start || $extend_date > $batch_chk_date_end)
        {
          $this->form_validation->set_message('fun_check_extend_date', "Please Select the Date between ".$batch_chk_date_start." and ".$batch_chk_date_end.".");
          return false;
        }
        else { return true; }  
      }
      else { return true; }               
    }
    else { return true; }       
  }

  // Custom callback function for validating the date range
	public function validate_date_range() {
	    $from_date = $this->input->post('batch_from_date');
	    $to_date = $this->input->post('batch_to_date');
	    
	    if (strtotime($to_date) < strtotime($from_date)) {
	        $this->form_validation->set_message('validate_date_range', 'The To Date must be greater than or equal to the From Date.');
	        return false;
	    }
	    return true;
	}

  public function edit_training_period()
	{
		$batch_id = $_POST['batch_id'];

		$form_data = $this->master_model->getRecords('agency_batch',array('id'=>$batch_id));
		$agency_id = $form_data[0]['agency_id'];
		
		//START : UPDATE TRAINING PERIOD
		if(isset($_POST) && count($_POST) > 0 && $form_data[0]['batch_status'] == 'Approved')
    {  
        $this->form_validation->set_rules(
	        'batch_from_date', 
	        'From Date', 
	        'trim|required|xss_clean', 
	        array('required' => "Please select the from date.")
		    );

		    // Validate 'batch_to_date'
		    $this->form_validation->set_rules(
	        'batch_to_date', 
	        'To Date', 
	        'trim|required|xss_clean|callback_validate_date_range', 
	        array('required' => "Please select the to date.")
		    );

        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
        	
          $batch_from_date = $this->input->post('batch_from_date');
	    		$batch_to_date   = $this->input->post('batch_to_date');

	    		$old_from_date = $this->input->post('old_from_date');
	    		$old_to_date   = $this->input->post('old_to_date');

          $up_data = array();
          $up_data['batch_from_date']   = $batch_from_date;
          $up_data['batch_to_date']     = $batch_to_date;
          $up_data['updated_on']        = date("Y-m-d H:i:s"); 
          $up_data['updated_by']        = $this->UserID;
          
          $this->master_model->updateRecord('agency_batch',$up_data,array('id' => $batch_id));
          
          $log_data =	array(	
						'agency_id'		=>	$agency_id,
						'user_type'		=>	"R",					
						'updated_by' 	=>  $this->UserID,
						'user_id'		  =>	$agency_id,
						'created_on'	=>	date('Y-m-d H:i:s'),
						);	
						
        	$log_text = 'Add/Update candidate';
      		
        	$logmassege = "Batch Training Period successfully updated by the Admin from (".$old_from_date." to ".$old_to_date.") to (".$batch_from_date." to ".$batch_to_date.").";

					log_dra_admin( $log_title = $logmassege, $log_message = serialize($update_data) );
					
					log_dra_agency_batch_detail($log_title = $logmassege,$batch_id,serialize($log_data));
              
          $this->session->set_flashdata('success','Batch training period successfully updated');

          redirect(base_url('iibfdra/Version_2/batch/batch_detail/'.base64_encode($batch_id)));
        }
        else
				{
					$this->session->set_flashdata('error',validation_errors()); 
	        redirect(base_url('iibfdra/Version_2/batch/batch_detail/'.base64_encode($batch_id))); 
				} //END : UPDATE TRAINING PERIOD 
      }
	}

	public function extend_edit_date()
	{
		$batch_id = $_POST['batch_id'];

		$form_data = $this->master_model->getRecords('agency_batch',array('id'=>$batch_id));
		$agency_id = $form_data[0]['agency_id'];
		// echo "<pre>"; print_r($_POST); exit;
		//START : EXTEND DATE FOR ADD/UPDATE CANDIDATES
		if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_action']) && $_POST['form_action'] == 'extend_date_action' && date('Y-m-d') <= $form_data[0]['batch_to_date'] && $form_data[0]['batch_status'] == 'Approved')
    {  
        $this->form_validation->set_rules('batch_extend_type', 'type', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_extend_date', 'date', 'trim|required|callback_fun_check_extend_date['.$batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        
        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
          // $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin'); 

          $up_data = array();
          $up_data['batch_extend_date'] = $batch_extend_date = $this->input->post('batch_extend_date');
          $up_data['batch_extend_type'] = $batch_extend_type = $this->input->post('batch_extend_type');
          $up_data['updated_on']        = date("Y-m-d H:i:s"); 
          $up_data['updated_by']        = $this->UserID;
          $this->master_model->updateRecord('agency_batch',$up_data,array('id' => $batch_id));
          
          $log_data =	array(	
						'agency_id'		=>	$agency_id,
						'user_type'		=>	"R",					
						'updated_by' 	=>  $this->UserID,
						'user_id'		  =>	  $agency_id,
						'created_on'	=>	date('Y-m-d H:i:s'),
						);	
						
          $this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));

        	$log_text = 'Add/Update candidate';
        	if($batch_extend_type == 2) { $log_text = 'Only Update candidate'; }

					log_dra_admin( $log_title = "DRA Admin for Batch extend ". $log_text." date successfully extended to ".$batch_extend_date." by the admin.", $log_message = serialize($update_data) );
					
					log_dra_agency_batch_detail($log_title = "DRA Admin for Batch extend ". $log_text." date successfully extended to ".$batch_extend_date." by the admin.",$batch_id,serialize($log_data));
              
          $this->session->set_flashdata('success','Batch Add/Update candidate date successfully extended');

          redirect(base_url('iibfdra/Version_2/batch/batch_detail/'.base64_encode($batch_id)));
        }
        else
				{
					$this->session->set_flashdata('error',validation_errors()); 
	        redirect(base_url('iibfdra/Version_2/batch/batch_detail/'.base64_encode($batch_id))); 
				} //END : EXTEND DATE FOR ADD/UPDATE CANDIDATES 
      }
	}

	function clear_extended_date($enc_batch_id=0)
  {
    $batch_id  = base64_decode($enc_batch_id);
    
    $form_data = $this->master_model->getRecords('agency_batch', array('id' => $batch_id, 'is_deleted' => '0', 'batch_status !=' => 'In Review', 'batch_extend_type !=' => '0', 'batch_extend_type !=' => ''));
    
    $agency_id = $form_data[0]['agency_id'];
    
    if(count($form_data) == 0) 
    { 
      $this->session->set_flashdata('error','Error occurred.. Please try again.'); 
      redirect(base_url('iibfdra/Version_2/batch')); 
    }

    $posted_arr = json_encode($_POST);
    // $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin'); 

    $up_data = array();
    $up_data['batch_extend_date'] = NULL;
    $up_data['batch_extend_type'] = '0';
    // $up_data['ip_address'] = get_ip_address(); //general_helper.php  
    $up_data['updated_on'] = date("Y-m-d H:i:s"); 
    $up_data['updated_by'] = $this->UserID;
    $this->master_model->updateRecord('agency_batch',$up_data,array('id' => $batch_id));
    
    log_dra_admin( $log_title = "Batch Add/Update candidate date successfully cleared by the Admin.", $log_message = serialize($up_data) );
		
     $log_data =	array(	
			'agency_id'		=>	$agency_id,
			'user_type'		=>	"R",					
			'updated_by' 	=>  $this->UserID,
			'user_id'		  =>	  $agency_id,
			'created_on'	=>	date('Y-m-d H:i:s'),
			);	
						
		log_dra_agency_batch_detail($log_title = "Batch Add/Update candidate date successfully cleared by the Admin.",$batch_id,serialize($log_data));

    // $this->Iibf_bcbf_model->insert_common_log('Admin : Cleared extended date for add/update candidate', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','Batch Add/Update candidate date successfully cleared by the admin '.$centreName['disp_name'], $posted_arr);
        
    $this->session->set_flashdata('success','Batch Add/Update candidate date successfully cleared');

    redirect(site_url('iibfdra/Version_2/batch/batch_detail/'.$enc_batch_id));
  }


	// function to view batch Details-
	public function batch_detail($batch_id)
	{	
		$batchId = base64_decode($batch_id);
		$data['batch_id'] = $batch_id = $batchId;
		$this->load->model('UserModel');

		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Version_2/batch/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/Version_2/batch/"> Traning Batch List</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/batch/batch_detail/'.$batch_id.'">Traning Batch Detail</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/batch/batch_detail';		
		
		$batch_communication = $this->master_model->getRecords("dra_batch_communication",array('batch_id'=>$batch_id));
		$data['batch_communication'] = $batch_communication;	
		if(count($batch_communication) > 0){
			foreach ($batch_communication as $key => $value) {
				if($value['notification_flag'] == 1){
					$updArr = array('notification_flag' => 0);
					$whereArr = array('batch_id'=>$value['batch_id']);
					$updRes = $this->master_model->updateRecord("dra_batch_communication",$updArr,$whereArr);
				}
			}
		}
		
		$batch_attendance = $this->master_model->getRecords("dra_batch_attendance",array('batch_id'=>$batch_id));
		$data['batch_attendance'] = $batch_attendance;	

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
					//File Uploadation of inspector report
					//print_r($_FILES['inspector_report']['name']); die;
                    if($_FILES['inspector_report']['name'] != '')
                    {
					  
					  $inspector_report=$_FILES['inspector_report']['name'];
					  if($this->upload->do_upload('inspector_report'))
                        {
                            $dt1=$this->upload->data();
							$inspector_report = $dt1['file_name'];		

							//echo "string".$inspector_report;die;											
							$update_data = array(							
									'inspector_report'	=> $inspector_report,												
									'updated_on'		=> $updated_date,
									'updated_by' 		=> $user_type_flag 
									);		


										
							$data['error'] = '';
							$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));

							//echo $this->db->last_query();die;
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
	
		$this->db->select("dra_inst_registration.*,c2.location_name as offline_location_name,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname, f1.faculty_name as first_faculty_name,cm1.city_name as offline_city_name, f1.faculty_code as first_faculty_code, f1.academic_qualification as first_faculty_qualification, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code, f2.academic_qualification as sec_faculty_qualification, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code, f3.academic_qualification as add_first_faculty_qualification, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code, f4.academic_qualification as add_sec_faculty_qualification");	
		$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$this->db->join('agency_center c2','agency_batch.batch_center_id=c2.center_id','left');
		$this->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$this->db->join('city_master','agency_batch.city=city_master.id','LEFT');
		$this->db->join('city_master cm1','cm1.id=c2.location_name','left');		
		$this->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$this->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');		
		$this->db->join('faculty_master f1','agency_batch.first_faculty=f1.faculty_id','left');
		$this->db->join('faculty_master f2','agency_batch.sec_faculty=f2.faculty_id','left');
		$this->db->join('faculty_master f3','agency_batch.additional_first_faculty=f3.faculty_id','left');
		$this->db->join('faculty_master f4','agency_batch.additional_sec_faculty=f4.faculty_id','left');
		$this->db->where('agency_batch.id = "'.$batch_id.'"');
		// $this->db->where('agency_center.center_display_status','1'); // to hide centers and batches.		
		$res = $this->master_model->getRecords("agency_batch");	
		// echo $this->db->last_query(); die;
		// echo "<pre>";print_r($res); 
		// echo "</pre>".count($res); die;
		
		if(!count($res)){
			redirect(base_url().'iibfdra/Version_2/Batch');
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
		// echo $this->db->last_query();
		// echo "<pre>";print_r($res_inspector); echo "</pre>"; die;
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
		
		$tenth_members_count = $twelth_members_count = $graduate_members_count = 0;

		if($res[0]['hours'] == 100)
		{	
			$this->db->select('regid');
			$tenth_rst = $this->db->get_where('dra_members',array('batch_id'=>$batch_id,'qualification'=>'tenth'));
			$tenth_members_count = $tenth_rst->num_rows();
			$data['tenth_members_count'] = $tenth_members_count;

			$this->db->select('regid');
			$twelth_rst = $this->db->get_where('dra_members',array('batch_id'=>$batch_id,'qualification'=>'twelth'));
			$twelth_members_count = $twelth_rst->num_rows();
			$data['twelth_members_count'] = $twelth_members_count;
		}
			
		$this->db->select('regid');
		$this->db->where('batch_id', $batch_id);
		$this->db->group_start(); // Start a group for the OR condition
		$this->db->where('qualification', 'graduate');
		$this->db->or_where('qualification', 'post_graduate');
		$this->db->group_end(); // End the group for the OR condition
		$graduate_rst = $this->db->get('dra_members');
		$graduate_members_count = $graduate_rst->num_rows();
		$data['graduate_members_count'] = $graduate_members_count;

		// Code to fetch Candidate list 
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=dra_members.medium_of_exam','LEFT');
		$this->db->where('dra_members.batch_id = "'.$batch_id.'"');	
		$this->db->where('dra_members.isdeleted = 0');
		$res_student = $this->master_model->getRecords("dra_members");		
		$data['result_student'] = $res_student;
		
		// Code to fetch Admin logs  
		//$qry = $this->db->query("SELECT * FROM dra_agency_batch_adminlogs l JOIN dra_admin  a ON l.userid = a.id WHERE title NOT LIKE '%Batch Communication%' AND batch_id = ".$batch_id." ORDER BY l.date DESC");

		//$qry_05_08_2023 = $this->db->query("SELECT * FROM dra_agency_batch_adminlogs l JOIN dra_admin  a ON l.userid = a.id WHERE batch_id = ".$batch_id." ORDER BY l.date DESC");

		$qry = $this->db->query("SELECT * FROM dra_agency_batch_adminlogs l WHERE batch_id = ".$batch_id." ORDER BY l.date DESC");
		
		/*$this->db->join('dra_admin','dra_agency_batch_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_batch_adminlogs.batch_id',$batch_id);
		$this->db->order_by('dra_agency_batch_adminlogs.date','DESC');
		$res_logs = $this->master_model->getRecords("dra_agency_batch_adminlogs");*/
		$data['agency_batch_logs'] = $res_logs = $qry->result_array();	
		//print_r($data['agency_batch_logs']); die;
		
		// Code to Batch Communication logs  
		
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

		$disp_holidays = '';
		if(count($res) > 0 && $res[0]['holidays'] != "")
		{
			$disp_holidays = $this->holiday_arr_ascending_order(explode(',', $res[0]['holidays']));
		}
		$data['disp_holidays'] = $disp_holidays;
		
		$this->load->view('iibfdra/Version_2/admin/batch/batch_detail',$data);
		
	}

	function holiday_arr_ascending_order($holidaysArr=array())
	{
		$holidaysStr = '';
		if(count($holidaysArr) > 0)
		{			
			// Custom function to convert d-m-Y date to Y-m-d format
			function convertToYmd($date) { return date("Y-m-d", strtotime($date)); }

			// Convert the date array using the custom function
			$holidaysArrYmd = array_map('convertToYmd', $holidaysArr);
			//echo '<br>'; print_r($holidaysArrYmd);

			sort($holidaysArrYmd);
			//echo '<br>'; print_r($holidaysArrYmd);

			// Custom function to convert Y-m-d date to d-m-Y format
			function convertTodmY($date) { return date("d-m-Y", strtotime($date)); }

			$holidaysArrdmY = array_map('convertTodmY', $holidaysArrYmd);
			//echo '<br>'; print_r($holidaysArrdmY);

			$holidaysStr = implode(',', $holidaysArrdmY);
		}
		return $holidaysStr;
	}
	
	public function candidate_detail($candidate_id){
		$this->load->model('UserModel');
		
		$data["breadcrumb"] = '<ol class="breadcrumb">
		<li><a href="'.base_url().'iibfdra/Version_2/batch/">
		<i class="fa fa-home"></i> Home</a></li>
		<li class=""><a href="'.base_url().'iibfdra/Version_2/batch/"> Traning Batch List</a></li>
		<li class="active"><a href="'.base_url().'iibfdra/Version_2/batch/candidate_detail/'.$candidate_id.'">Candidate Detail</a></li>
		 </ol>';
		$data['middle_content']	= 'iibfdra/Version_2/admin/batch/candidate_detail';	
		//state
		$this->db->join('state_master','state_master.state_code=dra_members.state','LEFT');
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=dra_members.medium_of_exam','LEFT');
		$this->db->where('dra_members.isdeleted = 0');	
		$this->db->where('dra_members.regid = "'.$candidate_id.'"');
		$res = $this->master_model->getRecords("dra_members");		
		$data['result'] = $res[0];
		
		$this->load->view('iibfdra/Version_2/admin/batch/candidate_detail',$data);
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

		//print_r($_POST);
		
		if(isset($_POST['rejection'])){
			$rejection = $_POST['rejection'];
		}else{
			$rejection = '';
		}

		//echo $rejection;die;
		
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}
		
		if(isset($_POST['inspector_id']) ){
			if($_POST['inspector_id'] !=''){
			$inspector_id = $this->input->post('inspector_id');
			}else{
			 $inspector_id = 0;
			}
			
		}else{
			$inspector_id = 0;
		}	
		//echo $inspector_id; die;
		$update_data = array(
						'batch_status'	=> 'Approved',	
						'inspector_id'	=> $inspector_id,												
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);						

		if($rejection != '' && $inspector_id == 0 ){
			$msg = "DRA Admin Approved Agency Batch";
			

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
			
			$msg = "DRA Admin Approved Agency Batch and Assign Inspector ".$inspector_name." - by Admin" ;
			/*$log_data =	array(	
							'agency_id'		=>	$this->input->post('agency_id'),
							'user_type'		=>	$user_type,	
							'inspector_id'	=>  $inspector_id,	
							'batch_status'	=> 'Approved',	
							'rejection'		=>	$rejection,				
							'updated_by' 	=>  $user_type_flag,
							'user_id'		=>	$drauserdata['id'],
							'created_on'	=>	date('Y-m-d H:i:s'),
							);	*/
		}

		$log_data =	array(	
							'agency_id'		=>	$this->input->post('agency_id'),
							'user_type'		=>	$user_type,	
							'inspector_id'	=>  $inspector_id,					
							'updated_by' 	=>  $user_type_flag,
							'batch_status'	=> 'Approved',	
							'rejection'		=>	$rejection,
							'user_id'		=>	$drauserdata['id'],
							'created_on'	=>	date('Y-m-d H:i:s'),
							);	
			
		log_dra_admin($log_title = "DRA Admin Approved Agency Center", $log_message = serialize($update_data));
		$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
		
		//echo 'inspector_name--'.$inspector_name.'--inspector_email--'.$inspector_email;die;
		if($inspector_name != '' && $inspector_email != ''){			
		  batch_inspection_mail_V2($batch_id, $inspector_email, $inspector_id); //added by Priyanka W	
		}
				
		log_dra_agency_batch_detail($log_title = $msg,$batch_id,serialize($log_data));		
		batch_approve_mail_V2($batch_id,$user_type_flag,$user_type_flag);
		
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

		if($inspector_name != '' && $inspector_email != ''){			
		  batch_inspection_mail_V2($batch_id, $inspector_email, $inspector_id); //added by Priyanka W	
		}
		
		log_dra_agency_batch_detail($log_title = "DRA Admin Assign Inspector  ".$inspector_name."  for Agency Batch - by Admin",$batch_id,serialize($log_data));

		
	}

	public function agency_update_batch_communication($batch_id=''){	
		  
		//$this->load->helper('general_agency_helper');
		$drauserdata = $this->session->userdata('dra_admin');	
		$user_type_flag = $drauserdata['roleid'];	
		$created_date = date('Y-m-d H:i:s');

		$agency_id = $this->input->post('agency_id');
		$batch_id = $this->input->post('batch_id');	
		$batch_communication = $this->input->post('batch_communication');	
			
		//'batch_status'	=> 'A',			
		$insert_data = array(			
						'agency_id'		=>	$agency_id,				
						'batch_id'	=> $batch_id,	
						'batch_communication' => $batch_communication,					
						'created_by_id'	=> $user_type_flag,
						'notification_flag' => 2,
						'created_on' 	=> $created_date 
						);						

		$log_data =	array(	
						'agency_id'		=>	$agency_id,
						'batch_id'		=>	$batch_id,
						'rejection'		=>  $batch_communication,
						'updated_by' 	=>  $user_type_flag,
						'user_id'		=>	$drauserdata['id'],
						'created_on'	=>	date('Y-m-d H:i:s'),
						);	
						//print_r($update_data);
						//print_r($log_data);	die;

						
		log_dra_admin($log_title = "DRA Admin Batch Communication for Agency batch By Admin", $log_message = serialize($insert_data));
		$res = $this->master_model->insertRecord('dra_batch_communication',$insert_data);
		
		//batch_inspection_mail($batch_id, $inspector_email); //added by aayusha	

		log_dra_agency_batch_detail($log_title = "DRA Admin Batch Communication  ".$inspector_name."  for Agency Batch - by Admin",$batch_id,serialize($log_data));
		echo $res;
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
		}else if($batch_status == 'Rejected'){
			
		  $str_reson = ' Reject ';	
		}else if($batch_status == 'Batch Error'){
			
		  $str_reson = ' Batch Error ';	
		}

		
		if($user_type_flag == 1){		
			$user_type = 'A';				
		}else{
			$user_type = 'R';		
		}


		if($batch_status == 'UnHold'){
			
		  $update_data = array(
						'batch_status'	=> 'Approved',									
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);
		
		}else{

			if ($batch_status == 'Cancelled') {
					$update_data = array(
						'batch_status'	=> $this->input->post('action_status'),				
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag
						// 'first_faculty' => '',
						// 'sec_faculty' => '',
						// 'additional_first_faculty' => '',
						// 'additional_sec_faculty	' => ''
						);

			} else {
				$update_data = array(
						'batch_status'	=> $this->input->post('action_status'),				
						'updated_on'	=> $updated_date,
						'updated_by' 	=> $user_type_flag 
						);
			}
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
		  	batch_cancel_mail_V2($batch_id,$user_type_flag);	
		}else
		{
			batch_reject_mail_V2($batch_id,$user_type_flag);
		}
		
		log_dra_admin($log_title = "DRA Admin ".$str_reson." Agency Batch", $log_message = serialize($update_data));	
		log_dra_admin($log_title = "DRA Admin ".$str_reson." Agency Batch Add entry in agency_batch_rejection table", $log_message = serialize($insert_data));	
		log_dra_agency_batch_detail($log_title = "DRA Admin ".$str_reson." Agency Batch - by Admin",$batch_id,serialize($log_data));
	}
	
	
	public function resend_email() 
	{
		$result = batch_inspection_mail_test('3357', 'sagar.matale@esds.co.in');//
		echo $result; 
	}

	/*GET VALUES OF CITY */
    public function getCity() 
    {
		if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
		{
			$state_code = $this->security->xss_clean($this->input->post('state_code'));
			$result = $this->master_model->getRecords('city_master', array('state_code' => $state_code));
			if ($result) 
			{
				echo '<option value="">- Select - </option>';
				foreach ($result AS $data) 
				{
					if ($data) 
					{
						echo '<option value="' . $data['city_name'] . '">' . $data['city_name'] . '</option>';
					}
				}
			} 
			else 
			{
				echo '<option value="">City Not Available, Please select other state</option>';
			}
			
		}
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
				redirect('iibfdra/Version_2/InstituteHome/dashboard');	
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

  //START : ADDED BY SAGAR ON 2023-09-17
  //THIS FUNCTION IS USED TO GET CANDIDATES LOG
  function getDraCandidateLogs($candidate_id=0)
  {
    $candidate_log_data = $this->master_model->getRecords('dra_candidate_logs',array('candidate_id'=>$candidate_id, 'status'=>'success'),'log_id, action, form_type, candidate_id, reason, log_title, status, created_by_type, created_by, created_on', array('log_id'=>'ASC'));

    if(count($candidate_log_data) == 0)
    {
      $member_data = $this->master_model->getRecords('dra_members',array('regid'=>$candidate_id),'regid, created_by_id, edited_by_id, editedby, createdon, editedon, (SELECT inst_name FROM dra_inst_registration WHERE id = dra_members.created_by_id) AS AgencyName');
      //echo '<pre>'; print_r($member_data);

      $candidate_log_data = array();
      if(count($member_data) > 0)
      {
        if($member_data[0]['createdon'] != '0000-00-00 00:00:00')
        {
          $agency_name = 'agency';
          if($member_data[0]['AgencyName'] != '') { $agency_name = $member_data[0]['AgencyName']; }
          $candidate_log_data[0]['log_title'] = 'Candidate successfully added by '.$agency_name;
          $candidate_log_data[0]['created_on'] = $member_data[0]['createdon'];

          if($member_data[0]['editedon'] != '0000-00-00 00:00:00' || $member_data[0]['editedby'] != '0000-00-00 00:00:00')
          {
            $agency_name = 'agency';
            if($member_data[0]['AgencyName'] != '') { $agency_name = $member_data[0]['AgencyName']; }
            $candidate_log_data[1]['log_title'] = 'Candidate successfully updated by '.$agency_name;

            if($member_data[0]['editedon'] != '0000-00-00 00:00:00')
            {
              $candidate_log_data[1]['created_on'] = $member_data[0]['editedon'];
            }
            else if($member_data[0]['editedby'] != '0000-00-00 00:00:00')
            {
              $candidate_log_data[1]['created_on'] = $member_data[0]['editedby'];
            }
          }
        }
      }
    }
    
    return $candidate_log_data;
  }//END : ADDED BY SAGAR ON 2023-09-17
} ?>